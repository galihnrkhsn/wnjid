<?php 
include "koneksi.php";
$invoice=$_GET["inv"];
$idorder=$_GET["idorder"];
$hurufdepan=substr($invoice,0,1); 

// echo $hurufdepan;
if($hurufdepan=='A'){
	 $data=$koneksi->query("SELECT idproduk,jumlah FROM orderagen WHERE idorder='$idorder'");
	 while($tampilkan=$data->fetch_assoc()){

	 $idproduk=0;	
	 $jmlh=0;
	 $idproduk=$tampilkan['idproduk'];
	 $jmlh=$tampilkan['jumlah'];

	 $update = "UPDATE produk SET stock=stock+'$jmlh' where idproduk='$idproduk'";
	 $sql = mysqli_query( $koneksi, $update);
	  //echo "<script>alert('$idproduk , $jmlh');</script>";
	 }

	  $delete = "DELETE FROM orderagen where idorder='$idorder'";
	  $sql = mysqli_query( $koneksi, $delete);

	  	  	$cariinvoice = $koneksi->query("SELECT count(*) as jumlahinv FROM orderagen where invoice='$invoice'");
	$tampilkaninv=$cariinvoice->fetch_assoc();
  	$jumlahinv = $tampilkaninv['jumlahinv'];

  	if ($jumlahinv==0) {
  		$delete = "DELETE FROM orderpengiriman where invoice='$invoice'";
  		$sql = mysqli_query( $koneksi, $delete);
  	}
	  
	  echo "<script>alert('Produk telah berhasil dibatalkan');</script>";
	  echo "<script>location='ordermitra_keep.php';</script>";

} else

if($hurufdepan=='R') {
	$data=$koneksi->query("SELECT idproduk,jumlah FROM orderreseller WHERE idorder='$idorder'");
	 while($tampilkan=$data->fetch_assoc()){

	 $idproduk=0;	
	 $jmlh=0;
	 $idproduk=$tampilkan['idproduk'];
	 $jmlh=$tampilkan['jumlah'];

	 $update = "UPDATE produk SET stock=stock+'$jmlh' where idproduk='$idproduk'";
	 $sql = mysqli_query( $koneksi, $update);
	  //echo "<script>alert('$idproduk , $jmlh');</script>";
	 }

	  $delete = "DELETE FROM orderreseller where idorder='$idorder'";
	  $sql = mysqli_query( $koneksi, $delete);

	  	$cariinvoice = $koneksi->query("SELECT count(*) as jumlahinv FROM orderreseller where invoice='$invoice'");
	$tampilkaninv=$cariinvoice->fetch_assoc();
  	$jumlahinv = $tampilkaninv['jumlahinv'];

  	if ($jumlahinv==0) {
  		$delete = "DELETE FROM orderpengiriman where invoice='$invoice'";
  		$sql = mysqli_query( $koneksi, $delete);
  	}

	  echo "<script>alert('Produk telah berhasil dibatalkan');</script>";
	  echo "<script>location='ordermitra_keep.php';</script>";
} else

if ($hurufdepan=='M'){
	 $data=$koneksi->query("SELECT idproduk,jumlah FROM ordermarketer WHERE idorder='$idorder'");
	 while($tampilkan=$data->fetch_assoc()){

	 $idproduk=0;	
	 $jmlh=0;
	 $idproduk=$tampilkan['idproduk'];
	 $jmlh=$tampilkan['jumlah'];

	 $update = "UPDATE produk SET stock=stock+'$jmlh' where idproduk='$idproduk'";
	 $sql = mysqli_query( $koneksi, $update);
	  //echo "<script>alert('$idproduk , $jmlh');</script>";
	 }

	  $delete = "DELETE FROM ordermarketer where idorder='$idorder'";
	  $sql = mysqli_query( $koneksi, $delete);

	$cariinvoice = $koneksi->query("SELECT count(*) as jumlahinv FROM ordermarketer where invoice='$invoice'");
	$tampilkaninv=$cariinvoice->fetch_assoc();
  	$jumlahinv = $tampilkaninv['jumlahinv'];

  	if ($jumlahinv==0) {
  		$delete = "DELETE FROM orderpengiriman where invoice='$invoice'";
  		$sql = mysqli_query( $koneksi, $delete);
  	}

	  echo "<script>alert('Produk telah berhasil dibatalkan');</script>";
	  echo "<script>location='ordermitra_keep.php';</script>";
} else {

 $data=$koneksi->query("SELECT idproduk,jumlah FROM ordermitra WHERE idorder='$idorder'");
 while($tampilkan=$data->fetch_assoc()){

 $idproduk=0;	
 $jmlh=0;
 $idproduk=$tampilkan['idproduk'];
 $jmlh=$tampilkan['jumlah'];

 $update = "UPDATE produk SET stock=stock+'$jmlh' where idproduk='$idproduk'";
 $sql = mysqli_query( $koneksi, $update);
  //echo "<script>alert('$idproduk , $jmlh');</script>";
 }

  $delete = "DELETE FROM ordermitra where idorder='$idorder'";
  $sql = mysqli_query( $koneksi, $delete);

  	$cariinvoice = $koneksi->query("SELECT count(*) as jumlahinv FROM ordermitra where invoice='$invoice'");
	$tampilkaninv=$cariinvoice->fetch_assoc();
  	$jumlahinv = $tampilkaninv['jumlahinv'];

  	if ($jumlahinv==0) {
  		$delete = "DELETE FROM orderpengiriman where invoice='$invoice'";
  		$sql = mysqli_query( $koneksi, $delete);
  	}

  echo "<script>alert('Produk telah berhasil dibatalkan');</script>";
  echo "<script>location='ordermitra_keep.php';</script>";
}

?>