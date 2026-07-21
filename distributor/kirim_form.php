<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}


if(isset($_POST['contactFrmSubmit']) && !empty($_POST['bank']) && !empty($_POST['rekening']) && !empty($_POST['nama'])){
// data form yang dikirimkan
$bank = $_POST['bank'];
$rekening= $_POST['rekening'];
$nama= $_POST['nama'];
$idadmin= $_SESSION["admin_mitra"]["idadmin"];
/*
* Kirim email ke alamat dibawah ini
*/
	$koneksi->query("INSERT INTO rekeningku (idrek,idadmin,bank,namapemilik,rekening)
			VALUES (null,'$idadmin','$bank','$nama','$rekening')");


		
}	
?>		