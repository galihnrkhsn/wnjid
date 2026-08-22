# Diagnosis: Redirect Baru dari cPanel Tidak Jalan

## Pertanyaan

Domain `wnj.id` sudah punya `.htaccess` custom di level `public_html` (satu tingkat di atas folder aplikasi `wnjid/`) yang meng-*expose* seluruh aplikasi tanpa prefix `/wnjid/` di URL. Ketika mencoba menambah redirect baru lewat fitur **Redirects** di cPanel (misal `wnj.id/katelog` → URL tertentu), redirect itu tidak berfungsi.

## Struktur .htaccess yang ada

```apache
Options -Indexes

<IfModule mod_rewrite.c>
RewriteEngine On
DirectoryIndex index.php index.html

# 1. Akses root domain -> index.php
RewriteCond %{REQUEST_URI} !^/wnjid/
RewriteRule ^$ /wnjid/index.php [L]

# 2. Sembunyikan .php (kalau file .php-nya ada)
RewriteCond %{REQUEST_URI} !^/wnjid/
RewriteCond %{DOCUMENT_ROOT}/wnjid/$1.php -f
RewriteRule ^(.*)$ /wnjid/$1.php [L]

# 3. Fallback: semua request lain -> tetap arahkan ke folder wnjid
RewriteCond %{REQUEST_URI} !^/wnjid/
RewriteRule ^(.*)$ /wnjid/$1 [L]
</IfModule>

<IfModule mod_rewrite.c>
RewriteEngine on
</IfModule>
RewriteCond %{HTTP_HOST} ^wnj\.id$ [OR]
RewriteCond %{HTTP_HOST} ^www\.wnj\.id$
RewriteRule ^fotoproduk$ "https://..." [R=301,L]
RewriteCond %{HTTP_HOST} ^wnj\.id$ [OR]
RewriteCond %{HTTP_HOST} ^www\.wnj\.id$
RewriteRule ^katalog$ "https://..." [R=301,L]
```

File ini **bukan** bagian dari repo `wnjid` (repo hanya punya `.htaccess` per-folder seperti `distributor/.htaccess`, tidak ada `.htaccess` di root) — dia hidup di `public_html`, satu level di atas folder aplikasi.

## Penyebab

Rule fallback (bagian "# 3") **tidak punya syarat file harus ada** (tidak pakai `-f`). Semua request yang belum diawali `/wnjid/` langsung di-rewrite ke `/wnjid/<path>` lalu berhenti (`[L]`).

Karena ini `.htaccess` di level direktori (bukan konfigurasi server), begitu URL berubah jadi `/wnjid/katelog`, Apache **mengulang proses rewrite dari rule paling atas lagi** (perilaku standar mod_rewrite di konteks per-direktori). Path sekarang sudah berawalan `wnjid/`, jadi rule redirect apa pun yang ditulis dengan pola polos seperti `^katelog$` (termasuk yang digenerate cPanel) **tidak akan pernah cocok lagi** — yang dicek sekarang adalah `wnjid/katelog`, bukan `katelog`.

Kalau cPanel menaruh rule redirect barunya **di bawah** blok fallback ini di file yang sama, rule itu praktis tidak akan pernah kesampaian. Ini juga berlaku walau cPanel pakai `Redirect`/`RedirectMatch` (mod_alias) alih-alih `RewriteRule` — mencampur mod_alias dengan catch-all mod_rewrite seperti ini memang dikenal rawan konflik urutan pemrosesan.

## Solusi

Jangan pakai fitur "Redirects" cPanel untuk domain ini. Tambahkan rule secara manual, mengikuti pola yang sudah ada untuk `katalog`/`fotoproduk`, dan **taruh di paling atas file** — sebelum blok `<IfModule mod_rewrite.c>` pertama (blok fallback `/wnjid/`):

```apache
Options -Indexes

<IfModule mod_rewrite.c>
RewriteEngine On

RewriteCond %{HTTP_HOST} ^wnj\.id$ [OR]
RewriteCond %{HTTP_HOST} ^www\.wnj\.id$
RewriteRule ^katelog$ "https://target-url-kamu" [R=301,L]

DirectoryIndex index.php index.html
... (rule-rule lama tetap di bawah ini, tidak diubah)
```

Kalau sudah kadung dibuat lewat UI cPanel: buka File Manager, cari blok `# BEGIN cPanel-generated ... redirect` di `.htaccess`, lalu **pindahkan blok itu ke paling atas file** (di atas rule fallback). Tidak perlu ubah isi rule-nya, cukup posisinya.
