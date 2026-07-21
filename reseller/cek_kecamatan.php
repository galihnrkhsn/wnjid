<?php
	session_start();
	include "koneksi.php";
	$idmitrareseller 	= $_SESSION['idmitrareseller'];
	$queryReseller 		= $koneksi->query("SELECT kecamatan FROM mitrareseller WHERE idmitrareseller = '$idmitrareseller'");
	$dataReseller  		= $queryReseller->fetch_assoc();
	
	$idkecamatan = $dataReseller["kecamatan"];

	if($idkecamatan==''){
	echo "<option disabled='disabled' selected>~Pilih Kecamatan Tujuan~</option>";
	$kabupaten= $_GET['kabupaten_id'];
	$result_explode = explode('|', $kabupaten);
	$kabupaten_id=$result_explode[0];                
	$ambil=$koneksi->query("SELECT * FROM `tb_ro_subdistricts` where city_id='$kabupaten_id'");
	}
	else{
		$ambil=$koneksi->query("SELECT * FROM `tb_ro_subdistricts` where subdistrict_id='$idkecamatan'");
	}
		while($data=$ambil->fetch_assoc()){
		echo "<option value='".$data['subdistrict_id']."|".$data['subdistrict_name']."'>".$data['subdistrict_name']."</option>";
	}
?>