<?php				      

include "koneksi.php";
$invoice = $_GET['invoice'];


$sql = "SELECT * FROM pomitra where invoice='$invoice' ";
$query = $koneksi->query($sql);
while($tampilMar = $query->fetch_assoc()){
$idpomitra =	$tampilMar['idpomitra'];
$jumlahnya = $tampilMar['jumlah'];
$produknya = $tampilMar['idpodetail'];
$idpo = $tampilMar['idpo'];

$koneksi->query("UPDATE pokategori SET stok=stok+'$jumlahnya' WHERE idpo='$idpo'" );

$koneksi->query("DELETE FROM pomitra WHERE idpomitra='$idpomitra'" );

// $cariinvoice = $koneksi->query("SELECT count(*) as jumlahinv FROM pomitra where invoice='$invoice'");
// $tampilkaninv=$cariinvoice->fetch_assoc();
// $jumlahinv = $tampilkaninv['jumlahinv'];

// 	if ($jumlahinv==0) {
// 	$delete = "DELETE FROM podropship where invoice='$invoice'";
// 	$sql = mysqli_query( $koneksi, $delete);
// 	}
}

echo "<script>alert('Pesanan Di Batalkan');</script>";
echo "<script>location='listnewpo.php';</script>";

 
?>