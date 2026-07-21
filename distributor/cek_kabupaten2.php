<?php
    include "koneksi.php";
    echo "<option disabled='disabled' selected>~Pilih Kota/Kabupaten Tujuan~</option>";
    $provinsi = $_GET['prov_id'];
                $result_explode = explode('|', string: $provinsi);
    $provinsi_id=$result_explode[0];

    $ambil=$koneksi->query("SELECT * FROM `tb_ro_cities` where province_id='$provinsi_id'");
	while($data=$ambil->fetch_assoc()){

    echo "<option value='".$data['city_id']."|".$data['city_name']."'>".$data['city_name']."</option>";
}

?>
