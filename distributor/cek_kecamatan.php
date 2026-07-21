<?php
	session_start();
	include "koneksi.php";
	$idadmin = $_SESSION['idadmin'];
	$queryAdmin = $koneksi->query("SELECT kecamatan FROM admin_mitra WHERE idadmin = '$idadmin'");
	$dataAdmin  = $queryAdmin->fetch_assoc();

	$idkecamatan= $dataAdmin["kecamatan"];
	if($idkecamatan==''){
		echo "<option value='' disabled='disabled' selected>~Pilih Kecamatan Tujuan~</option>";
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