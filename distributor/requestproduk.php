
<?php 
error_reporting (0);
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

?>
<!DOCTYPE>
<html>
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
		<!-- Membuat Menu Header / Navbar -->
	<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="index.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>MY STOCK</p></div>
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
      
  <div class="panel panel-default">
   
      
    
    <div class="panel panel-default">
        
    <div class="panel-heading">
       <center><b> Request Produk</b></center>
            </div>
            <div class="panel-body">
            <div class="row">
            <div class="col-md-6">
  <p><center>Request Produk merupakan menu untuk menambah produk yang belum masuk dalam list produk kami</center></p><br>
  
  <form method="post" action="prosesrequest.php">
      
      <div class="form-group">
      <label>Nama Produk</label><br>
         <input type="text" name="namaproduk" id="namaproduk" class="form-control" placeholder="Tulis Nama Produk" />
         <label><font size="1" color="grey"> *lengkap dengan warna dan size, misal: bergo julang XL Teracota</font></label>
          
    </div>
      
      <input type="hidden" name="idadmin" class="form-control"  value="<?php echo $_SESSION['admin_mitra']['idadmin']; ?>">
   <button type="submit" class="btn btn-primary">Kirim</button>
  </form>   
  <hr>  
   
  
  
<div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
					<tr>
						<th width="10px">NO</th>
						<th class="center" width="150px">Nama Product</th>
						<th width="10px">Status</th>
					
					</tr>
					</thead>
					<tbody>
					 <?php   
					  $idadmin=$_SESSION['admin_mitra']['idadmin'];
					// Cek apakah terdapat data page pada URL
					$page = (isset($_GET['page']))? $_GET['page'] : 1;
					
					$limit = 10; // Jumlah data per halamannya
					
					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
					$limit_start = ($page - 1) * $limit;
					
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT * from requestproduk WHERE idadmin='$idadmin' order by tgl desc LIMIT ".$limit_start.",".$limit);
					
					$no = $limit_start + 1; // Untuk penomoran tabel
					
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td><?php echo $no; ?></td>
							<td class="align-middle"><?php echo $data['namaproduk']; ?></td>
							<td class="align-middle"><?php echo $data['status']; ?></td>
						</tr>
					<?php
						$no++; // Tambah 1 setiap kali looping
					}
					?>
					</tbody>
				</table>
			</div>
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
				$sql2 = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM requestproduk");
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
</body>
</html>