<?php 
error_reporting (0);
session_start();

include 'koneksi.php'; 
include 'floatingbutton.php';


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
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>


<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	
	
</head>
<body>

<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="index.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>STOCK PUSAT</p></div>
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
    
    <center>
        <h6 style="font-family:verdana;" class="outset">List Stok Pusat yang tertera hanya sebagai informasi stok per periode, stok tersebut merupakan stok yang terdapat pada Ready Stock WEB MITRA</h6>
        </center><br>
         <pre>
<form method="get">
<label>Cari Nama Produk</label>

<input type="text" name="namaproduk">  <button type="submit" class="btn btn-primary" name="cari">Cari</button>  <button type="submit" class="btn btn-primary" name="tampil">Tampil Semua</button></form></pre>
        <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
    <thead>
      <tr>
          <th width="1">No</th>
          <th>Nama Produk</th>
          <th width="2">Qty</th>

      </tr>
    </thead>
    <tbody>

     <?php
         if(isset($_GET["cari"])){
      $no=1;
      $nama=$_GET["namaproduk"];
      $page = (isset($_GET['page']))? $_GET['page'] : 1;
	  $limit = 20; 
	  $limit_start = ($page - 1) * $limit;
      $ambil=$koneksi->query("SELECT * FROM produk where namaproduk LIKE '%$nama%' and stock>0 order by tgl desc ");       
             
      }elseif(isset($_GET["tampil"])){
      $no=1;
      $page = (isset($_GET['page']))? $_GET['page'] : 1;
	  $limit = 20; 
	  $limit_start = ($page - 1) * $limit;
      $ambil=$koneksi->query("SELECT * FROM produk where stock>0 order by tgl desc LIMIT ".$limit_start.",".$limit." "); 
      }else{
           $no=1;
      $page = (isset($_GET['page']))? $_GET['page'] : 1;
	  $limit = 20; 
	  $limit_start = ($page - 1) * $limit;
      $ambil=$koneksi->query("SELECT * FROM produk where stock>0 order by tgl desc LIMIT ".$limit_start.",".$limit." "); 
      }
      while($data=$ambil->fetch_assoc()){
      ?>
        
     <tr>
          <td><?php echo $no++;?></td>
          <td><?php echo $data['namaproduk'];?></td>
          <td><?php echo $data['stock'];?></td>
      </tr>
      <?php } ?>
    </tbody>
</table>    
     
  
<ul class="pagination">
				<!-- LINK FIRST AND PREV -->
				<?php
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
				$sql2 = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM produk");
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

