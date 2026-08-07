<?php
/**
 * Salin file ini menjadi "mail.config.php" (di folder yang sama) lalu isi kredensial
 * email asli. mail.config.php TIDAK ikut ke-commit ke git (lihat .gitignore) - harus
 * dibuat manual di setiap environment.
 *
 * Kredensial ini dari akun email yang dibuat lewat cPanel IDwebhost (mis. noreply@wnj.id),
 * bukan akun Gmail pribadi - supaya domain pengirim cocok sama domain website (penting
 * buat SPF/DKIM & supaya tidak masuk folder spam).
 */
return [
    // Host SMTP - biasanya "mail.namadomain.com" di cPanel IDwebhost, cek di
    // cPanel > Email Accounts > Connect Devices untuk host yang benar.
    'host'       => 'mail.wnj.id',
    'port'       => 587,
    // 'tls' (port 587) atau 'ssl' (port 465) - pakai yang sesuai port di atas.
    'encryption' => 'tls',
    'username'   => 'noreply@wnj.id',
    'password'   => '',
    // Alamat & nama pengirim yang muncul di inbox penerima - biasanya sama dengan username.
    'from_email' => 'noreply@wnj.id',
    'from_name'  => 'Wanoja',
];
