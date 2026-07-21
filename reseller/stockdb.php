<?php 
session_start();

include 'koneksi.php'; 
//include 'floatingbutton.php';


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| Wanoja </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| Wanoja </title>


<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	</head>
	<body>
		<!-- Membuat Menu Header / Navbar -->
	<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="index.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>Stock DB</p></div>
  <div class="col-2"><a href="whatsapp://send?text=http://mitra.wanoja.com/profil.php?user=<?php echo $tampilkan['instagram'] ?>"><i class="fa fa-share-alt" aria-hidden="true"></i></a></div>
</div>

<br><br><br><br>

<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #eee  url("jumbotron-bg.png") center center;
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

.navbaru p {
  
  padding: 12px 0;
  font-size: 20px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru i {
  margin-top: 15px;
  padding: 2px 0;
  font-size: 23px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru2 {
   
    
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

</style>

<!--================ NAVBARU END =================-->
    

        <div class="container">
         <center><h6 style="font-family:verdana;" class="ridge">My-stock adalah fitur inventory mitra, stock mitra bisa dishare
         ke mitra agen atau reseller, dan juga bisa diinfokan ke mitra DB lainnya </h6>
        </center><br>
	     
	     <?php 
      $ambil=$koneksi->query("SELECT tgl FROM produk order by tgl desc limit 1 ");
        while($tampilkan=$ambil->fetch_assoc()){
        ?>
		
		<div style="padding: 0 15px;">
		  <!--      <form method="get"><input type="text" name="namaproduk" style="width: 200px">  <button type="submit" class="btn btn-primary" name="cari">Cari</button></form><br>
		        <a class="btn btn-default" href="tambahproduk.php">Tambah Produk</a> <a class="btn btn-default" href="requestproduk.php">Request Produk</a> -->
		        <p class="right">Last Update: <?php echo $tampilkan['tgl']; ?></p> 
		         <?php } ?>
		      
			    <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
					<tr>
						<th width="10px">NO</th>
						<th class="center" width="150px">Nama Product</th>
						<th width="10px">Stock</th>
					
					</tr>
					</thead>
					<tbody>
					<?php
					// Include / load file koneksi.php
					include "koneksi.php";
				
					if(isset($_GET["cari"])){
					$idadmin=$_SESSION['mitraagen']['idadmin'];
					 $nama=$_GET["namaproduk"];
					// Cek apakah terdapat data page pada URL
					$page = (isset($_GET['page']))? $_GET['page'] : 1;
					
					$limit = 8; // Jumlah data per halamannya
					
					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
					$limit_start = ($page - 1) * $limit;
					
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT DISTINCT stockmitra.idstockmitra,produk.idproduk,produk.namaproduk,stockmitra.stock FROM stockmitra inner join produk inner join mitraagen
					ON stockmitra.idproduk=produk.idproduk and stockmitra.idadmin=mitraagen.idadmin WHERE mitraagen.idadmin='$idadmin' and produk.namaproduk LIKE '%$nama%' ");
					
					$no = $limit_start + 1; // Untuk penomoran tabel
					}else{
					   		$idadmin=$_SESSION['mitraagen']['idadmin'];
					// Cek apakah terdapat data page pada URL
					$page = (isset($_GET['page']))? $_GET['page'] : 1;
					
					$limit = 20; // Jumlah data per halamannya
					
					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
					$limit_start = ($page - 1) * $limit;
					
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT DISTINCT stockmitra.idstockmitra,produk.idproduk,produk.namaproduk,stockmitra.stock FROM stockmitra inner join produk inner join mitraagen
					ON stockmitra.idproduk=produk.idproduk and stockmitra.idadmin=mitraagen.idadmin WHERE mitraagen.idadmin='$idadmin' LIMIT ".$limit_start.",".$limit);
					
					$no = $limit_start + 1; // Untuk penomoran tabel
					}
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td><?php echo $no; ?></td>
							<td class="align-middle"><?php echo $data['namaproduk']; ?></td>
							<td class="align-middle"><?php echo $data['stock']; ?></td>
						
						</tr>
					<?php
						$no++; // Tambah 1 setiap kali looping
					}
					?>
					</tbody>
				</table>
			</div>
			
			<!--
			-- Buat Paginationnya
			-- Dengan bootstrap, kita jadi dimudahkan untuk membuat tombol-tombol pagination dengan design yang bagus tentunya
			-->
			<ul class="pagination">
				<!-- LINK FIRST AND PREV -->
				<?php
						$idadmin=$_SESSION['mitraagen']['idadmin'];
				if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
				?>
					<li class="disabled"><a href="#">First</a></li>
					<li class="disabled"><a href="#">&laquo;</a></li>
				<?php
				}else{ // Jika page bukan page ke 1
					$link_prev = ($page > 1)? $page - 1 : 1;
				?>
					<li><a href="?page=1">First</a></li>
					<li><a href="?page=<?php echo $link_prev; ?>">&laquo;</a></li>
				<?php
				}
				?>
				
				<!-- LINK NUMBER -->
				<?php
				// Buat query untuk menghitung semua jumlah data
				$sql2 = mysqli_query($koneksi, "SELECT DISTINCT COUNT(*) AS jumlah FROM stockmitra inner join produk inner join mitraagen
					ON stockmitra.idproduk=produk.idproduk and stockmitra.idadmin=mitraagen.idadmin WHERE mitraagen.idadmin='$idadmin'");
				$get_jumlah = mysqli_fetch_array($sql2);
				
				$jumlah_page = ceil($get_jumlah['jumlah'] / $limit); // Hitung jumlah halamannya
				$jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
				$start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
				$end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number
				
				for($i = $start_number; $i <= $end_number; $i++){
					$link_active = ($page == $i)? ' class="active"' : '';
				?>
					<li<?php echo $link_active; ?>><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
				<?php
				}
				?>
				
				<!-- LINK NEXT AND LAST -->
				<?php
				// Jika page sama dengan jumlah page, maka disable link NEXT nya
				// Artinya page tersebut adalah page terakhir 
				if($page == $jumlah_page){ // Jika page terakhir
				?>
					<li class="disabled"><a href="#">&raquo;</a></li>
					<li class="disabled"><a href="#">Last</a></li>
				<?php
				}else{ // Jika Bukan page terakhir
					$link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
				?>
					<li><a href="?page=<?php echo $link_next; ?>">&raquo;</a></li>
					<li><a href="?page=<?php echo $jumlah_page; ?>">Last</a></li>
				<?php
				}
				?>
			</ul>
		</div>
	</body>
</html>

