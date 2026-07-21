<?php
require '../koneksi.php';
var_dump('as');
die();


$expiredTime = date('Y-m-d H:i:s', strtotime('-2 hours'));

$query = mysqli_query($conn, "
    SELECT * FROM keranjang 
    WHERE created_at <= '$expiredTime'
");

while ($row = mysqli_fetch_assoc($query)) {

    $variant_id = $row['variant_id'];
    $qty = $row['qty'];

    // Kembalikan stok
    mysqli_query($conn, "
        UPDATE variant 
        SET stok = stok + $qty 
        WHERE id = $variant_id
    ");

    // Hapus dari keranjang
    mysqli_query($conn, "
        DELETE FROM keranjang 
        WHERE id = ".$row['id']."
    ");
}