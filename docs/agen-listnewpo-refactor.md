# Refactor `agen/listnewpo.php`

## Permintaan

Samakan kode `agen/listnewpo.php` dengan `distributor/listnewpo.php` — `distributor/` adalah implementasi paling up-to-date, sedangkan `agen/listnewpo.php` versi lama sudah menyimpang (route form berbeda-beda nama, HTML berantakan dengan blok komentar mati, query PO reguler tidak mempertimbangkan downline). Perbedaan yang dipertahankan **hanya** yang memang terkait session/identitas role.

## Perbedaan yang dipertahankan (role-spesifik)

| Aspek | `distributor/listnewpo.php` | `agen/listnewpo.php` |
|---|---|---|
| File session | `assets/components/Sessions/sesDistri.php` (memanggil `session_start()` sendiri) | `assets/components/Sessions/sesAgen.php` (**tidak** memanggil `session_start()`, jadi dipanggil eksplisit di file) |
| Variabel identitas | `$_SESSION["idadmin"]` | `$_SESSION["idmitraagen"]` |
| Filter PO buka (`bukapo.jenis_mitra`) | `'Semua Mitra'` atau `'Distributor'` | `'Semua Mitra'` atau `'Agen'` |
| Param di link produk khusus (id 260/261) | `&idadmin=...` | `&idmitraagen=...` |
| Query "PO Regular" (hierarki downline) | Lihat di bawah | Lihat di bawah |

Nilai `'Agen'` untuk `jenis_mitra` bukan buatan baru — sudah dipakai konsisten di `adminwnj/` (`cek_db.php`, `cek_jenis_mitra.php`, `cek_per_jenismitra.php`, `cek_per_jenismitra_po.php`).

## Query hierarki PO Regular

Distributor melihat PO miliknya sendiri **plus semua downline** (agen, reseller, marketer di bawahnya), lewat kolom `idadmin` di tabel `mitraagen`/`mitrareseller`/`mitramarketer`:

```sql
WHERE
    p.idmitra = ?
    OR p.idmitraagen IN (SELECT idmitraagen FROM mitraagen WHERE idadmin = ?)
    OR p.idmitrareseller IN (SELECT idmitrareseller FROM mitrareseller WHERE idadmin = ?)
    OR p.idmitramarketer IN (SELECT idmitramarketer FROM mitramarketer WHERE idadmin = ?)
```

Agen tidak punya downline distributor/agen lain di bawahnya — hierarkinya cuma reseller & marketer yang opsional terhubung lewat kolom `idmitraagen` (FK nullable) di tabel `mitrareseller`/`mitramarketer`. Jadi versi agen:

```sql
WHERE
    p.idmitraagen = ?
    OR p.idmitrareseller IN (SELECT idmitrareseller FROM mitrareseller WHERE idmitraagen = ?)
    OR p.idmitramarketer IN (SELECT idmitramarketer FROM mitramarketer WHERE idmitraagen = ?)
```

## Yang ikut disamakan ke versi distributor (bukan sekadar dipertahankan)

- Struktur `$jenisPoRoutes` dan `$produkSpecial` (array routing per jenis PO) — memakai tabel routing distributor apa adanya, menggantikan puluhan blok `if ($tampilkan['jenis_po']=="...")` terpisah di versi lama agen yang beberapa nama filenya sempat menyimpang (mis. `formpomikicustomstock` vs `formpomikicustom`).
- Prepared statement penuh untuk kedua query (versi lama agen memakai `$koneksi->query()` langsung tanpa parameter binding untuk query pertama).
- JS countdown per-PO (styling & struktur sama persis dengan distributor).
- HTML dirapikan: dihapus blok komentar mati (jumbotron lama yang di-comment-out), CDN Font Awesome duplikat, dan include `settingdatatables.php` yang sudah tidak relevan karena tabelnya adalah tabel HTML biasa (bukan DataTables) di versi distributor.

## Verifikasi

- `php -l agen/listnewpo.php` — tidak ada syntax error.
- Query hierarki diuji live terhadap agen dengan data riil (`idmitraagen = 714`, 33 downline reseller): mengembalikan 57 baris PO reguler yang benar, termasuk PO yang berasal dari transaksi downline-nya — sebelumnya versi lama tidak pernah mengikutkan data downline sama sekali.
