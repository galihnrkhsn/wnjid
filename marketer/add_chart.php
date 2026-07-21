<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$idproduk=$_GET['id'];
$harga=$_GET['harga'];
$idmarketer=$_SESSION["mitraagen"]["idmitramarketer"];

date_default_timezone_set('Asia/Jakarta');
  $waktu=date('H:i:s');

$ambil1=$koneksi->query("SELECT stock FROM produk where idproduk='$idproduk' "); 
$stock=$ambil1->fetch_assoc();
$ambil=$koneksi->query("SELECT COUNT(*) as jmlh FROM keranjang where idproduk='$idproduk' and idmarketer='$idmarketer' "); 
$data=$ambil->fetch_assoc();

        if($data['jmlh']=='1' and $stock['stock']>0){
            $koneksi->query("UPDATE keranjang SET jmlh=jmlh+1,subtotal=subtotal+'$harga' where idproduk='$idproduk' and idmarketer='$idmarketer' ");
            $koneksi->query("UPDATE produk SET stock=stock-1 where idproduk='$idproduk'");
            
            $_SESSION['message'] = 'Qty Produk Telah di Tambahkan';
			header('location:modehemat.php');
        }
            else if($data['jmlh']=='0' and $stock['stock']>0){
            $koneksi->query("INSERT INTO keranjang (idkeranjang,idproduk,idmitra,idagen,idreseller,idmarketer,jmlh,harga,subtotal,tgl,waktu,status)
      VALUES (null,'$idproduk','','','','$idmarketer','1','$harga','$harga',NOW(),'$waktu','Active')");
			$koneksi->query("UPDATE produk SET stock=stock-1 where idproduk='$idproduk'");
			
			$_SESSION['message'] = 'Produk Telah di Masukan ke Keranjang';
			header('location:modehemat.php');
        }
        else if($stock['stock']==0){
            	$_SESSION['message'] = 'Produk Telah Habis';
			header('location:modehemat.php');
        }
        	
?>