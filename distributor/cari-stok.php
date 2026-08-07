<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="text/javascript" src="assets/DataTables/media/js/jquery.js"></script>
	<script type="text/javascript" src="assets/DataTables/media/js/jquery.dataTables.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/DataTables/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="assets/DataTables/media/css/dataTables.bootstrap.css">
<style>
* {
  box-sizing: border-box;
}

body {
  font-family: Arial, Helvetica, sans-serif;
}

/* Style the header */
.header {
  background-color: #f1f1f1;
  padding: 30px;
  text-align: center;
  font-size: 35px;
}

/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 50%;
  padding: 50px;
  height: 40px; /* Should be removed. Only for demonstration */
  
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Style the footer */
.footer {
  background-color: #f1f1f1;
  padding: 10px;
  text-align: center;
}

/* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
@media (max-width: 600px) {
  .column {
    width: 50%;
  }
}
</style>
</head>
<body>
<?php include '../header.php';  ?>



<p><i>nyabar merupakan fasilitas saling info stock antar mitra, stock terlampir bukan realtime stock melainkan stok update terakhir yang diupdate mitra. <br>klik <b>nama barang</b> untuk mengetahui posisi barang,Data ini tidak menampilkan stok anda,hanya menampilkan stok yang berada di mitra lainnya</i></p><br>

<div class="container">
		<table class="table table-striped table-bordered data">
			<thead>
				<tr>			
					<th>No</th>
					<th>Nama Artikel</th>
					
					<th>Stok</th>
					
				</tr>
			</thead>
			<tbody>
			     <?php
			     $kode=$_SESSION['admin_mitra']['kodemitra'];
			     $no=1;
		

		$ambil=$koneksi->query("SELECT * FROM produk WHERE kodemitra NOT IN('$kode')  ORDER BY idproduk DESC");
		while($pecah=$ambil->fetch_assoc()){

		 ?>
				<tr>				
					<td><?php echo $no++; ?></td>
					<td><a href="info-barang?id=<?php echo $pecah['idproduk']; ?>"><?php echo $pecah["namaproduk"] ?></a></td>
      
      <td><?php echo $pecah["stok"] ?></td>
      
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>



</body>
<script type="text/javascript">
	$(document).ready(function(){
		$('.data').DataTable();
	});
</script>
</html>

