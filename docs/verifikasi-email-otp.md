# Verifikasi Email dengan Kode OTP

## Latar belakang

Alur registrasi sebelumnya mengirim **link** verifikasi ke email (`https://wnj.id/verify_email.php?token=...`). User harus buka email lalu klik link. Ini diganti dengan kode 6 digit yang diketik langsung di halaman web, karena lebih nyaman dipakai di HP — user tidak perlu pindah dari tab/app email, cukup lihat kodenya lalu ketik balik.

## Alur baru

1. User submit form di `register.php`.
2. Akun dibuat (`users`, belum `email_verified_at`), kode 6 digit di-generate dan dikirim ke email.
3. User diarahkan ke `verifikasi_otp.php` — form input kode + tombol "Kirim Ulang Kode" (dengan cooldown).
4. Kode benar → `email_verified_at` di-set, sesi verifikasi dibersihkan, user diarahkan login manual (tidak auto-login).

Kalau user kehilangan sesi (browser ditutup, dsb), jalur pemulihan tetap lewat `resend_verifikasi.php` (input email → kalau terdaftar & belum verifikasi, kirim kode baru → lanjut ke `verifikasi_otp.php`). Pesan di halaman ini sengaja **sama persis** baik email ketemu maupun tidak, supaya tidak bisa dipakai untuk enumerasi email terdaftar.

`verify_email.php` (alur link lama) **sengaja tidak dihapus** — dibiarkan tetap berfungsi sebagai fallback untuk user yang sudah kadung menerima email berisi link sebelum perubahan ini diterapkan.

## File yang terlibat

| File | Peran |
|---|---|
| `includes/otp_helper.php` | `kirimKodeVerifikasi()` — generate kode, hash, simpan ke DB, kirim email. Dipakai bareng oleh `register.php` dan `resend_verifikasi.php`/`verifikasi_otp.php`. |
| `register.php` | Setelah insert user sukses, panggil `kirimKodeVerifikasi()`, set `$_SESSION['otp_user_id']`, redirect ke `verifikasi_otp.php`. |
| `verifikasi_otp.php` | Halaman input kode + resend. Baru. |
| `resend_verifikasi.php` | Jalur pemulihan by-email, sekarang kirim kode (bukan link), tetap anti-enumeration. |
| `verify_email.php` | Alur link lama, dibiarkan apa adanya sebagai fallback. |
| `database/users_verifikasi_otp.sql` | Kolom baru di tabel `users` (lihat di bawah). |

## Perubahan skema

Kolom `verification_token` (varchar(64)) dipakai ulang untuk menyimpan **SHA-256 hash** dari kode 6 digit (bukan token link mentah lagi) — pas karena hash sha256 = 64 karakter hex. Tiga kolom baru ditambahkan:

```sql
ALTER TABLE users
    ADD COLUMN verification_expires_at DATETIME NULL AFTER verification_token,
    ADD COLUMN verification_attempts TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER verification_expires_at,
    ADD COLUMN verification_last_sent_at DATETIME NULL AFTER verification_attempts;
```

Sudah diterapkan ke database live.

## Aturan keamanan kode OTP

- **Numerik 6 digit**, di-generate dengan `random_int()`, disimpan sebagai hash — bukan plaintext.
- **Kadaluarsa 10 menit** (`verification_expires_at`).
- **Maksimal 5 kali salah** (`verification_attempts`) sebelum harus minta kode baru. Minta kode baru otomatis reset hitungan ini ke 0.
- **Cooldown kirim ulang 60 detik** (`verification_last_sent_at`), dicek di server (bukan cuma di JS) supaya tidak bisa dipakai spam kirim email.

## Bug yang ditemukan & diperbaiki saat pengujian

Perhitungan cooldown awalnya membandingkan `time()` (PHP) dengan `NOW()` (MySQL) lewat `strtotime()`. Saat diuji live, muncul selisih ~5 jam (PHP jalan di timezone `Europe/Berlin`, MySQL server jam-nya WIB) — sisa cooldown sempat tampil "18057 detik" alih-alih ~60 detik.

**Perbaikan**: semua perhitungan waktu (sisa cooldown, cek kadaluarsa) sekarang dihitung di sisi MySQL sendiri lewat `TIMESTAMPDIFF(SECOND, NOW(), ...)`, bukan membandingkan jam dua sistem berbeda. Query di `verifikasi_otp.php`:

```sql
SELECT ...,
    TIMESTAMPDIFF(SECOND, NOW(), verification_expires_at) AS detik_sampai_expired,
    TIMESTAMPDIFF(SECOND, verification_last_sent_at, NOW()) AS detik_sejak_kirim
FROM users WHERE id = ?
```

Ini membuat logic tetap benar berapa pun perbedaan timezone antara server PHP dan server MySQL.

## Pengujian yang sudah dilakukan

Diuji live end-to-end (server dev lokal + curl, data test dihapus setelahnya):
- Registrasi → redirect ke `verifikasi_otp.php`, baris `users` berisi hash kode + expiry + `last_sent_at` yang benar.
- Input kode salah → pesan error, `verification_attempts` bertambah.
- Input kode benar → `email_verified_at` ke-set, sesi `otp_user_id` dibersihkan.
- Resend langsung setelah registrasi → diblok cooldown dengan pesan sisa detik yang wajar (bukan lagi angka salah akibat bug timezone).
- Resend setelah cooldown lewat → berhasil kirim kode baru, cooldown baru mulai lagi.
