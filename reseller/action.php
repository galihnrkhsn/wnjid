
<?php
session_start();
$output =NULL;
$mysqli = NEW MySQLi("localhost", "wanoja_webmitra", "webmitra", "wanoja_webmitra");
 $idproduk=$_POST['idproduk'];
 
 $namaproduk = $_POST['namaproduk'];
$stok = $_POST['stok'];
$kodemitra = $_POST['kodemitra'];
$namamitra = $_POST['namamitra'];
$whatsapp = $_POST['whatsapp'];



foreach ($namaproduk as $key => $value) {
	$query = "SELECT idproduk FROM produk WHERE id= '". $mysqli->real_escape_string($idproduk[$key]) . "' LIMIT 1";

	$resultset = $mysqli->query($query);

	if($resultset->num_rows == 0){
		$query = "INSERT INTO produk(namaproduk,stok,kodemitra,namamitra,whatsapp,tgl)
		VALUES ('"
			. $mysqli->real_escape_string($value) .
		"','"
		. $mysqli->real_escape_string($stok[$key]) .
		"','"
			. $mysqli->real_escape_string($kodemitra[$key]) .
		"','"
			. $mysqli->real_escape_string($namamitra[$key]) .
		"','"
			. $mysqli->real_escape_string($whatsapp[$key]) .
		"',	
			NOW()
		)

		";

		$insert = $mysqli->query($query);

		if(!$insert){
			echo $mysqli->error;
		}else{
			$output .="<p>succes". $serial[$key]."</p>";
		}
	}else{
		$output .="failed.".$mysqli->error;
	}
	
}

header('location:index.php')
?>