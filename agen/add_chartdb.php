<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$idprodukdb=$_GET['id'];
$harga=$_GET['harga'];
$idagen=$_SESSION["mitraagen"]["idmitraagen"];

$ambil1=$koneksi->query("SELECT stock FROM produkdb where idprodukdb='$idprodukdb'"); 
$stock=$ambil1->fetch_assoc();
$ambil=$koneksi->query("SELECT COUNT(*) as jmlh FROM keranjangdb where idprodukdb='$idprodukdb' and idagen='$idagen'"); 
$data=$ambil->fetch_assoc();

        if($data['jmlh']=='1' and $stock['stock']>0){
            $koneksi->query("UPDATE keranjangdb SET jmlh=jmlh+1,subtotal=subtotal+'$harga' where idprodukdb='$idprodukdb' and idagen='$idagen' ");
            $koneksi->query("UPDATE produkdb SET stock=stock-1 where idprodukdb='$idprodukdb'");
            
            $_SESSION['message'] = 'Qty Produk Telah di Tambahkan';
			header('location:storedb.php');
        }
        else if($stock['stock']>0){
            $koneksi->query("INSERT INTO keranjangdb (idkeranjangdb,idprodukdb,idagen,jmlh,harga,subtotal,tgl)
			VALUES (null,'$idprodukdb','$idagen','1','$harga','$harga',NOW())");
			$koneksi->query("UPDATE produkdb SET stock=stock-1 where idprodukdb='$idprodukdb'");
			
			$_SESSION['message'] = 'produkdb Telah di Masukan ke Keranjang';
			header('location:storedb.php');
        }
        else if($stock['stock']==0){
            	$_SESSION['message'] = 'Produk Telah Habis';
			header('location:storedb.php');
        }
        	
?>