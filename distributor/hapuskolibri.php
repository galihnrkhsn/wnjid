<?php 

include "koneksi.php";
$idpomitra= $_GET['id'];

  $query = "SELECT * from pomitra Where idpomitra='$idpomitra'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);

  $idpoproduk=$data['idpoproduk'];
  $idpo=$data['idpo'];
  $invoice=$data['invoice'];



$sqlnya = $koneksi->query("DELETE from pomitra where idpomitra='$idpomitra';");

	if ($sqlnya) {
		echo "<script>alert('Data Berhasil dihapus');</script>";
		echo "<script>location='ubahpokolibricustom.php?id=$idpoproduk&invoice=$invoice';</script>";
	}else{
		echo "<script>alert('Data Gagal dihapus');</script>";
echo "<script>location='ubahpokolibricustom.php?id=$idpoproduk&invoice=$invoice';</script>";
	}


 ?>