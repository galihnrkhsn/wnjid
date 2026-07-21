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

 <?php 
        $id=$_GET["idtransaksi"];
        $ambil=$koneksi->query("SELECT * FROM transaksi where idtransaksi='$id'");
        while($data=$ambil->fetch_assoc()){
        ?>

<form method="post">
      
      <input type="hidden" name="id" value="<?php echo $data['idtransaksi'];?>">
      
      <label>Invoice</label><br>
    <input type="text" name="invoice" value="<?php echo $data['invoice'];?>"><br>
    
    <label>Transaksi</label><br>
    <input type="text" name="transaksi" value="<?php echo $data['transaksi'];?>"><br>
    
    <label>Jumlah</label><br>
    <input type="text" name="jumlah" value="<?php echo $data['jumlah'];?>"><br><br>
   	<button class="btn btn-primary" name="save">Simpan</button>
</form>

<?php
if(isset($_POST["save"])){
	
	$id = $_POST["id"];
	$invoice = $_POST["invoice"];
	$transaksi = $_POST["transaksi"];
	$jumlah = $_POST["jumlah"];

		$koneksi->query("UPDATE transaksi set invoice='$invoice',transaksi='$transaksi',jumlah='$jumlah' where idtransaksi='$id' ");

			echo "<script>alert('data berhasil diubah');</script>";
		echo "<script>location='index.php?page=transaksi';</script>";

	}

}
?>