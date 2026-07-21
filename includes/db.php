<?php
$koneksi = mysqli_connect("wnj.id","wnjid1_it_wnj","@Bardan18","wnjid1_web_v3");

if ($koneksi->connect_error) {
    die("Koneksi WNJ Gagal: " . $koneksi->connect_error);
}