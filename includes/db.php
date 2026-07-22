<?php
$dbConfigFile = __DIR__ . '/db.config.php';

if (!file_exists($dbConfigFile)) {
    die("Konfigurasi database tidak ditemukan. Salin includes/db.config.sample.php menjadi includes/db.config.php lalu isi kredensialnya.");
}

$dbConfig = require $dbConfigFile;

$koneksi = mysqli_connect($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);

if ($koneksi->connect_error) {
    die("Koneksi WNJ Gagal: " . $koneksi->connect_error);
}

mysqli_set_charset($koneksi, 'utf8mb4');