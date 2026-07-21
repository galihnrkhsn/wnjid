<?php 
include "koneksi.php";
$invoice=$_GET["inv"];
$hurufdepan=substr($invoice,0,1); 

// echo $hurufdepan;
if($hurufdepan=='A'){
	 $data=$koneksi->query("SELECT idproduk,jumlah FROM orderagen WHERE invoice='$invoice'");
	 while($tampilkan=$data->fetch_assoc()){

	 $idproduk=0;	
	 $jmlh=0;
	 $idproduk=$tampilkan['idproduk'];
	 $jmlh=$tampilkan['jumlah'];

	 $update = "UPDATE produk SET stock=stock+'$jmlh' where idproduk='$idproduk'";
	 $sql = mysqli_query( $koneksi, $update);
	  //echo "<script>alert('$idproduk , $jmlh');</script>";
	 }

	  $delete = "DELETE FROM orderagen where invoice='$invoice'";
	  $sql = mysqli_query( $koneksi, $delete);

	  $delete2 = "DELETE FROM orderpengiriman where invoice='$invoice'";
	  $sql2 = mysqli_query( $koneksi, $delete2);

	  echo "<script>alert('invoice telah berhasil dihapus');</script>";
	  echo "<script>location='ordermitra.php';</script>";

} else

if($hurufdepan=='R') {
	$data=$koneksi->query("SELECT idproduk,jumlah FROM orderreseller WHERE invoice='$invoice'");
	 while($tampilkan=$data->fetch_assoc()){

	 $idproduk=0;	
	 $jmlh=0;
	 $idproduk=$tampilkan['idproduk'];
	 $jmlh=$tampilkan['jumlah'];

	 $update = "UPDATE produk SET stock=stock+'$jmlh' where idproduk='$idproduk'";
	 $sql = mysqli_query( $koneksi, $update);
	  //echo "<script>alert('$idproduk , $jmlh');</script>";
	 }

	  $delete = "DELETE FROM orderreseller where invoice='$invoice'";
	  $sql = mysqli_query( $koneksi, $delete);

	  $delete2 = "DELETE FROM orderpengiriman where invoice='$invoice'";
	  $sql2 = mysqli_query( $koneksi, $delete2);

	  echo "<script>alert('invoice telah berhasil dihapus');</script>";
	  echo "<script>location='ordermitra.php';</script>";
} else

if ($hurufdepan=='M'){
	 $data=$koneksi->query("SELECT idproduk,jumlah FROM ordermarketer WHERE invoice='$invoice'");
	 while($tampilkan=$data->fetch_assoc()){

	 $idproduk=0;	
	 $jmlh=0;
	 $idproduk=$tampilkan['idproduk'];
	 $jmlh=$tampilkan['jumlah'];

	 $update = "UPDATE produk SET stock=stock+'$jmlh' where idproduk='$idproduk'";
	 $sql = mysqli_query( $koneksi, $update);
	  //echo "<script>alert('$idproduk , $jmlh');</script>";
	 }

	  $delete = "DELETE FROM ordermarketer where invoice='$invoice'";
	  $sql = mysqli_query( $koneksi, $delete);

	  $delete2 = "DELETE FROM orderpengiriman where invoice='$invoice'";
	  $sql2 = mysqli_query( $koneksi, $delete2);

	  echo "<script>alert('invoice telah berhasil dihapus');</script>";
	  echo "<script>location='ordermitra.php';</script>";
} else {

 $data=$koneksi->query("SELECT idproduk,jumlah FROM ordermitra WHERE invoice='$invoice'");
 while($tampilkan=$data->fetch_assoc()){

 $idproduk=0;	
 $jmlh=0;
 $idproduk=$tampilkan['idproduk'];
 $jmlh=$tampilkan['jumlah'];

 $update = "UPDATE produk SET stock=stock+'$jmlh' where idproduk='$idproduk'";
 $sql = mysqli_query( $koneksi, $update);
  //echo "<script>alert('$idproduk , $jmlh');</script>";
 }

  $delete = "DELETE FROM ordermitra where invoice='$invoice'";
  $sql = mysqli_query( $koneksi, $delete);

  $delete2 = "DELETE FROM orderpengiriman where invoice='$invoice'";
  $sql2 = mysqli_query( $koneksi, $delete2);

  echo "<script>alert('invoice telah berhasil dihapus');</script>";
  echo "<script>location='ordermitra.php';</script>";
}

?>