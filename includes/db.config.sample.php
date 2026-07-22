<?php
/**
 * Salin file ini menjadi "db.config.php" (di folder yang sama) lalu isi kredensial asli.
 * db.config.php TIDAK ikut ke-commit ke git (lihat .gitignore) — harus dibuat manual
 * di setiap environment (lokal maupun shared hosting) setelah git pull/clone.
 */
return [
    'host'     => 'localhost',
    'username' => '',
    'password' => '',
    'database' => '',
];
