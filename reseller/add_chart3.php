<?php 
    session_start();
    date_default_timezone_set('Asia/Jakarta');
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesReseller.php';

    $idproduk           = $_GET['id'];
    $harga              = $_GET['harga'];
    $idmitrareseller    = $_SESSION["idmitrareseller"];
    $waktu              = date('H:i:s');
    $ambil1             = $koneksi->query("SELECT stock FROM produk WHERE idproduk='$idproduk' "); 
    $stock              = $ambil1->fetch_assoc();

    if (!$stock) {
        echo "<script>alert('Variant tidak ditemukan');</script>";
        echo "<script>location='store2.php';</script>";
        exit();
    }

    $ambil  = $koneksi->query("SELECT COUNT(*) as jmlh FROM keranjang WHERE idproduk = '$idproduk' and idreseller='$idmitrareseller' "); 
    $data   = $ambil->fetch_assoc();

    if($data['jmlh']=='1' && $stock['stock']>0){
        $koneksi->query("UPDATE keranjang SET jmlh = jmlh + 1, subtotal = subtotal + '$harga' WHERE idproduk = '$idproduk' AND idreseller = '$idmitrareseller' ");
        $koneksi->query("UPDATE produk SET stock = stock - 1 WHERE idproduk = '$idproduk'");
        
        $_SESSION['message'] = 'Qty Produk Telah di Tambahkan';
        echo "<script>location='store2.php';</script>";

    } else if($data['jmlh']=='0' && $stock['stock']>0){
        $koneksi->query("INSERT INTO keranjang (idkeranjang,idproduk,idmitra,idagen,idreseller,idmarketer,jmlh,harga,subtotal,tgl,waktu,status, variant)
                                VALUES (null,'$idproduk','','','$idmitrareseller','','1','$harga','$harga',NOW(),'$waktu','Active', '$idproduk')");
        $koneksi->query("UPDATE produk SET stock=stock-1 WHERE idproduk='$idproduk'");
        $_SESSION['message'] = 'Produk Telah di Masukan ke Keranjang';
        echo "<script>location='store2.php';</script>";

    } else if($stock['stock']==0){
        $_SESSION['message'] = 'Produk Telah Habis';
        echo "<script>location='store2.php';</script>";
    }
        	
?>