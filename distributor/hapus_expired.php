<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

$idkeranjang = $_GET['idkeranjang'];

         $tampil =$koneksi->query("SELECT * FROM keranjang where idkeranjang='$idkeranjang' ");
         $tampilMas=$tampil->fetch_assoc();

         $idproduk = $tampilMas['idproduk'];
         $jmlh = $tampilMas['jmlh'];
$koneksi->query("UPDATE produk SET stock= stock+'$jmlh' where idproduk='$idproduk'");
$koneksi->query("DELETE FROM keranjang WHERE idkeranjang='$idkeranjang'" );

 echo "<script>alert('Berhasil Dihapus');</script>";
                  echo "<script>location='view_cart.php';</script>";
?>