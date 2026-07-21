<?php 
session_start();
include 'koneksi.php'; 
include 'assets/components/Sessions/sesDistri.php';

$idproduk = $_GET['id'];
$harga    = $_GET['harga'];
$idmitra  = $_SESSION["idadmin"];

date_default_timezone_set('Asia/Jakarta');
$waktu=date('H:i:s');

$ambil1 = $koneksi->query("SELECT stock FROM produk where idproduk='$idproduk' "); 
$stock  = $ambil1->fetch_assoc();

if (!$stock) {
  echo "<script>alert('Variant tidak ditemukan');</script>";
  echo "<script>location='store2.php';</script>";
  exit();
}

$ambil = $koneksi->query("SELECT COUNT(*) as jmlh FROM keranjang where idproduk='$idproduk' and idmitra='$idmitra' "); 
$data  = $ambil->fetch_assoc();

    if($data['jmlh']=='1' and $stock['stock']>0){
        $koneksi->query("UPDATE keranjang SET jmlh=jmlh+1,subtotal=subtotal+'$harga' where idproduk='$idproduk' and idmitra='$idmitra' ");
        $koneksi->query("UPDATE produk SET stock=stock-1 where idproduk='$idproduk'");
        
        $_SESSION['message'] = 'Qty Produk Telah di Tambahkan';
        echo "<script>location='view_cart2.php';</script>";

    }
        else if($data['jmlh']=='0' and $stock['stock']>0){
        $koneksi->query("INSERT INTO keranjang (idkeranjang,idproduk,idmitra,idagen,idreseller,idmarketer,jmlh,harga,subtotal,tgl,waktu,status, variant)
                                VALUES (null,'$idproduk','$idmitra','','','','1','$harga','$harga',NOW(),'$waktu','Active', '$idproduk')");
        $koneksi->query("UPDATE produk SET stock=stock-1 where idproduk='$idproduk'");
        $_SESSION['message'] = 'Produk Telah di Masukan ke Keranjang';
        echo "<script>location='view_cart2.php';</script>";
    } else if($stock['stock']==0){
            $_SESSION['message'] = 'Produk Telah Habis';
            echo "<script>location='view_cart2.php';</script>";
    }
        	
?>