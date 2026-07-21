<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}


if(isset($_POST['contactFrmSubmit']) && !empty($_POST['bank']) && !empty($_POST['rekening']) && !empty($_POST['nama'])){
// data form yang dikirimkan
$bank = $_POST['bank'];
$rekening= $_POST['rekening'];
$nama= $_POST['nama'];
$idmitraagen= $_SESSION["mitraagen"]["idmitraagen"];
/*
* Kirim email ke alamat dibawah ini
*/
	$koneksi->query("INSERT INTO rekeningku (idrek,idmitraagen,bank,namapemilik,rekening)
			VALUES (null,'$idmitraagen','$bank','$nama','$rekening')");


		
}	
?>		