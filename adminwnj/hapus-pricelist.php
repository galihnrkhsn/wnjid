<?php 

$id = $_GET['id'];
$koneksi->query("DELETE FROM pricelist WHERE idharga='$id')or die(mysql_error())");

?>