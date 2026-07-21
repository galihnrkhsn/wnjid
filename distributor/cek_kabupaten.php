<?php
	session_start();
	include "koneksi.php";
	$idadmin = $_SESSION['idadmin'];
	$queryAdmin = $koneksi->query("SELECT kota FROM admin_mitra WHERE idadmin = '$idadmin'");
	$dataAdmin  = $queryAdmin->fetch_assoc();

	$idkota 	= $dataAdmin["kota"];
	if($idkota == ''){
		echo "<option value='' disabled='disabled' selected>~Pilih Kota/Kabupaten Tujuan~</option>";
        $provinsi = $_GET['prov_id'];
        $result_explode = explode('|', $provinsi);
        $provinsi_id=$result_explode[0];
	    $ambil=$koneksi->query("SELECT * FROM `tb_ro_cities` where province_id='$provinsi_id'");
	} else{
	 	$ambil=$koneksi->query("SELECT * FROM `tb_ro_cities` where city_id='$idkota'");
	}
	while($data=$ambil->fetch_assoc()){
    	echo "<option value='".$data['city_id']."|".$data['city_name']."'>".$data['city_name']."</option>";
	}
?>
