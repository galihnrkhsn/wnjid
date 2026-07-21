<?php				      

include "koneksi.php";
$invoice = $_GET['invoice'];
//$jumlah_dipilih=count($invoice);


$sql = "SELECT * FROM ordermitra where invoice='$invoice' ";
$query = $koneksi->query($sql);
while($tampilMar = $query->fetch_assoc()){
$idorder =	$tampilMar['idorder'];
$jumlahnya = $tampilMar['jumlah'];
$produknya = $tampilMar['idproduk'];

$koneksi->query("UPDATE produk SET stock=stock+'$jumlahnya' WHERE idproduk='$produknya'" );

$koneksi->query("DELETE FROM ordermitra WHERE idorder='$idorder'" );

$cariinvoice = $koneksi->query("SELECT count(*) as jumlahinv FROM ordermitra where invoice='$invoice'");
$tampilkaninv=$cariinvoice->fetch_assoc();
$jumlahinv = $tampilkaninv['jumlahinv'];

	if ($jumlahinv==0) {
	$delete = "DELETE FROM orderpengiriman where invoice='$invoice'";
	$sql = mysqli_query( $koneksi, $delete);
	}
}

echo "<script>alert('Pesanan Di Batalkan');</script>";
echo "<script>location='transaksi.php';</script>";

 
?>