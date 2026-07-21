<?php
 echo "<option disabled='disabled' selected>~Pilih Kecamatan Tujuan~</option>";
$kabupaten= $_GET['kabupaten_id'];
                 $result_explode = explode('|', $kabupaten);
$kabupaten_id=$result_explode[0];                

 include "koneksi.php";
	 $ambil=$koneksi->query("SELECT * FROM `tb_ro_subdistricts` where city_id='$kabupaten_id'");
	while($data=$ambil->fetch_assoc()){

    echo "<option value='".$data['subdistrict_id']."|".$data['subdistrict_name']."'>".$data['subdistrict_name']."</option>";
}
?>