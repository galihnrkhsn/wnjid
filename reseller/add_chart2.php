<?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesReseller.php';
    
    date_default_timezone_set('Asia/Jakarta');
    $idvariant          = $_GET['id'];
    $harga              = $_GET['harga'];
    $idmitrareseller    = $_SESSION["idmitrareseller"];
    $waktu              = date('H:i:s');
    $ambil1             = $koneksi->query("SELECT stock FROM variants WHERE id = '$idvariant'"); 
    $stock              = $ambil1->fetch_assoc();

    if (!$stock) {
        echo "<script>alert('Variant tidak ditemukan');</script>";
        echo "<script>location='store4.php';</script>";
        exit();
    }

    $ambil  = $koneksi->query("SELECT COUNT(*) AS jmlh FROM keranjang WHERE idproduk = '$idvariant' AND idreseller = '$idmitrareseller'"); 
    $data   = $ambil->fetch_assoc();

    if($data['jmlh'] == '1' && $stock['stock'] > 0){
        $koneksi->query("UPDATE keranjang SET jmlh = jmlh + 1, subtotal = subtotal + '$harga' WHERE idproduk = '$idvariant' AND idreseller = '$idmitrareseller' ");
        $koneksi->query("UPDATE variants SET stock = stock - 1 WHERE id = '$idvariant'");
        
        $_SESSION['message'] = 'Qty Produk Telah di Tambahkan';
        echo "<script>location='store4.php';</script>";
    } else if($data['jmlh'] == '0' && $stock['stock'] > 0){
        $koneksi->query("INSERT INTO keranjang (idkeranjang, idproduk, idmitra, idagen, idreseller, 
                                                idmarketer, jmlh, harga, subtotal, tgl, waktu, status, variant)
                                VALUES (NULL, '$idvariant', '', '', '$idmitrareseller', '', '1', '$harga', 
                                        '$harga', NOW(), '$waktu', 'Active', '$idvariant')");
        $koneksi->query("UPDATE variants SET stock = stock - 1 WHERE id = '$idvariant'");
        $_SESSION['message'] = 'Produk Telah di Masukan ke Keranjang';
        echo "<script>location='store4.php';</script>";
    } else if($stock['stock'] == 0){
        $_SESSION['message'] = 'Produk Telah Habis';
        echo "<script>location='store4.php';</script>";
    }
        	
?>