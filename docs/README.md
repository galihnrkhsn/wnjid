# Dokumentasi WNJ.ID

Kumpulan catatan teknis untuk perubahan-perubahan yang sudah didiskusikan dan diterapkan di aplikasi ini. Setiap file membahas satu topik: latar belakang masalah, keputusan yang diambil, dan bagian kode yang terlibat.

- [Verifikasi Email dengan Kode OTP](verifikasi-email-otp.md) — alur registrasi diubah dari verifikasi via link menjadi kode 6 digit.
- [Diagnosis Redirect cPanel vs .htaccess](htaccess-redirect-cpanel.md) — kenapa redirect baru dari cPanel tidak jalan, dan cara memperbaikinya.
- [Refactor agen/listnewpo.php](agen-listnewpo-refactor.md) — menyamakan kode `agen/listnewpo.php` dengan `distributor/listnewpo.php`.

## Konteks proyek

WNJ.ID (Wanoja) adalah aplikasi e-commerce fashion muslim, PHP native + mysqli (prepared statement wajib), tanpa framework. Ada beberapa portal terpisah per role mitra: `distributor/`, `agen/`, `reseller/`, `marketer/` — kode `distributor/` adalah acuan paling up-to-date, portal lain cenderung tertinggal/menyimpang (beda logic, beda persentase diskon, dst). Sedang berjalan juga migrasi bertahap ke satu direktori terpadu `mitra/` yang role-aware (belum dibahas di dokumentasi ini, lihat riwayat percakapan lain).
