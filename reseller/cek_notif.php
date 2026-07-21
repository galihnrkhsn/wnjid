
<?php 
include 'koneksi.php';
	$q = mysqli_query("SELECT count(idform) as jml FROM form WHERE status='tunggu' ", $koneksi);
	if(mysqli_num_rows($q) > 0) {
		$row = mysqli_fetch_assoc($q);
                echo $row['jml'];
	}
?>