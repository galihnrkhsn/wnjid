<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}
?>

<form method="post">
      <label>Keterangan</label><br>
    <textarea name="keterangan" cols="120" rows="20"></textarea><br> <font color="red" size="2">*catatan : tidak mengandung tanda kutip (') <br>
   	<button class="btn btn-primary" name="save">Update</button>
</form>
<?php
if(isset($_POST["save"])){
	
	$keterangan = $_POST["keterangan"];

		$sql=$koneksi->query("INSERT INTO stock_pusat (idstock,keterangan,tgl) VALUES ('null','$keterangan',NOW())");


	    if($sql){echo "<script>alert('data berhasil ditambah');</script>";
		echo "<script>location='index.php';</script>";
	    }
	    else{
    // Jika Gagal, Lakukan :
    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
	        }
        }

?>