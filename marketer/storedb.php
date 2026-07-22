<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$idmitramarketer=$_SESSION["mitraagen"]["idmitramarketer"];
	$keranjangdb=0;
$sikat=$koneksi->query("SELECT * FROM keranjangdb where idmarketer='$idmitramarketer' and jmlh>0 "); 
while($count=$sikat->fetch_assoc()){
     $keranjangdb+=$count['jmlh'];
}
						   
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.js"></script>
	<script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.dataTables.js"></script>
	<link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/dataTables.bootstrap.css">
		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
     <!-- CSS here -->
    <link rel="stylesheet" href="assets2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets2/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets2/css/slicknav.css">
    <link rel="stylesheet" href="assets2/css/flaticon.css">
    <link rel="stylesheet" href="assets2/css/progressbar_barfiller.css">
    <link rel="stylesheet" href="assets2/css/gijgo.css">
    <link rel="stylesheet" href="assets2/css/animate.min.css">
    <link rel="stylesheet" href="assets2/css/animated-headline.css">
    <link rel="stylesheet" href="assets2/css/magnific-popup.css">
    <link rel="stylesheet" href="assets2/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets2/css/themify-icons.css">
    <link rel="stylesheet" href="assets2/css/slick.css">
    <link rel="stylesheet" href="assets2/css/nice-select.css">
    <link rel="stylesheet" href="assets2/css/style.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		
		<style>
		.align-middle{
			vertical-align: middle !important;
		}
		
        

		
</style>
<style type="text/css">
		p.dotted {
			border-style: dotted;
		}
		p.dashed {
			border-style: dashed;
		}
		p.solid {
			border-style: solid;
		}
		p.double {
			border-style: double;
		}
		p.groove {
			border-style: groove;
		}
		p.ridge {
			border-style: ridge;
		}
		p.inset {
			border-style: inset;
		}
		p.outset {
			border-style: outset;
		}
		p.none {
			border-style: none;
		}
		p.hidden {
			border-style: hidden;
		}
		p.mix {
			border-style: dotted dashed solid double;
		}
	</style>
<style type="text/css">
   .left    { text-align: left;}
   .right   { text-align: right;}
   .center  { text-align: center;}
   .justify { text-align: justify;}
</style>	
	
		
	</head>
	<body>
		<!-- Membuat Menu Header / Navbar -->
		
<!--================ NAVBARU =================-->
 


<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="index.php"><span class="glyphicon glyphicon-chevron-left"</span></a></div>
  <div class="col-7" ><p>STOCK DB</p></div>
  <div class="col-2"><a href="view_cartdb.php"><span class="glyphicon glyphicon-shopping-cart"></span></a></div>
</div>
<div class="container row fixed-top navbaru2" >

  <div class="col-2"></div>
  <div class="col-7" ></div>
  <div class="col-2"><a href="view_cartdb.php"><span class="badge"><?php echo $keranjangdb; ?></span></a></div>
</div>





<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #eee  url("jumbotron-bg.png") center center;
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

.navbaru p {
  
  padding: 10px 0;
  font-size: 20px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru span {
  
  padding: 5px 0;
  font-size: 30px;
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
 
	
	


<!--================ SEARCH =================-->
<style>
/* Place the navbar at the bottom of the page, and make it stick */

.caricari {
  margin-top: 80px;
  
}

.jumbotron {
  
}

</style>
<div class="container caricari">

 <!-- <div class="jumbotron"; margin-top: "0px"; margin-bottom: "0px";>
      <br>
      
  <div class="row ">
      <div class="col-2"></div>
  <div class="col-10">
      <form method="get">
<input type="text" name="namaproduk" placeholder="  tulis nama produk"><br> <button type="submit" class="btn btn-default" name="cari">
				<span class="glyphicon glyphicon-search"></span></button><button type="submit" class="btn btn-default" name="tampil">Tampil Semua</button></form>
  </div>
  
  
</div>
</div> -->


<!--================ SEARCH END =================-->



<div class="container">

		<div >
		      <?php
		//info message
		if(isset($_SESSION['message'])){
			?>
			<div class="row">
				<div class="col-sm-6 col-sm-offset-6">
					<div class="alert alert-info text-center">
						<?php echo $_SESSION['message']; ?>
					</div>
				</div>
			</div>
			<?php
			unset($_SESSION['message']);
		}
		?>	    
	<!--	<p align="right"><input type="radio" onclick="javascript:window.location.href='store.php'; "> Mode Hemat  &nbsp&nbsp<input type="radio" onclick="javascript:window.location.href='store2.php'; " checked="checked"> Mode Cantik</p>-->
         
         	<form method="post"><p align="left"><input type="text" name="namaproduk" placeholder="Masukan Nama Produk ..." /> <button class="btn btn-primary" name="cari" type="submit"><span class="glyphicon glyphicon-search"></span></button> <button class="btn btn-primary" name="tampil" type="submit">Tampil Semua</button></p></form>     
				
			</div>
	</div>
			
<!-- MODE  -->

<div class="container">
       
            <div class="row">
                    	<?php
					if(isset($_POST["cari"])){
        				  // Include / load file koneksi.php
        					include "koneksi.php";
        					$idmitramarketer=$_SESSION['mitraagen']['idmitramarketer'];
        						$idadmin=$_SESSION['mitraagen']['idadmin'];
        					$namaproduk=$_POST['namaproduk'];
        					//$idkategori=$_GET['idkategori'];					
        					// Cek apakah terdapat data page pada URL
        					$page = (isset($_GET['page']))? $_GET['page'] : 1;
        					
        					$limit = 20; // Jumlah data per halamannya
        					
        					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
        					$limit_start = ($page - 1) * $limit;
        					
        					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
        					$sql = mysqli_query($koneksi, "SELECT * from produkdb WHERE stock>0 and harga>0 and idadmin='$idadmin' and namaproduk LIKE '%$namaproduk%' ");
        					
        					$no = $limit_start + 1; // Untuk penomoran tabel
    					
					}elseif(isset($_POST["tampil"])){
        				 // Include / load file koneksi.php
        					include "koneksi.php";
        					$idmitramarketer=$_SESSION['mitraagen']['idmitramarketer'];
        					$idadmin=$_SESSION['mitraagen']['idadmin'];
        					//$idkategori=$_GET['idkategori'];					
        					// Cek apakah terdapat data page pada URL
        					$page = (isset($_GET['page']))? $_GET['page'] : 1;
        					
        					$limit = 20; // Jumlah data per halamannya
        					
        					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
        					$limit_start = ($page - 1) * $limit;
        					
        					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
        					$sql = mysqli_query($koneksi, "SELECT * from produkdb WHERE stock>0 and harga>0 and idadmin='$idadmin' LIMIT ".$limit_start.",".$limit);
        					
        					$no = $limit_start + 1; // Untuk penomoran tabel
					}else{
        					    // Include / load file koneksi.php
        					include "koneksi.php";
        					$idmitramarketer=$_SESSION['mitraagen']['idmitramarketer'];
        					$idadmin=$_SESSION['mitraagen']['idadmin'];
        					//$idkategori=$_GET['idkategori'];					
        					// Cek apakah terdapat data page pada URL
        					$page = (isset($_GET['page']))? $_GET['page'] : 1;
        					
        					$limit = 20; // Jumlah data per halamannya
        					
        					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
        					$limit_start = ($page - 1) * $limit;
        					
        					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
        					$sql = mysqli_query($koneksi, "SELECT * from produkdb WHERE stock>0 and harga>0 and idadmin='$idadmin' order by namaproduk asc LIMIT ".$limit_start.",".$limit);
        					
        					$no = $limit_start + 1; // Untuk penomoran tabel
					}
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-6">
                    <!-- MODIF AWAL -->
                   <div class="card img" style="width:500px">
                    <img class="card-img-top" src="http://www.mitra.wanoja.com/foto/<?php echo $data['foto']; ?>" alt="Card image" style="width:100%">
                    <div class="card-body">
                      <h4 class="card-title"><?php echo $data['namaproduk']; ?> <br></h4>
                      <p class="card-text">
						(<?php echo $data['stock']; ?>)Pcs 	&nbsp&nbsp&nbsp
                      <a href="add_chartdb.php?id=<?php echo $data['idprodukdb']; ?>&harga=<?php echo $data['harga']; ?>" class="btn btn-primary">Beli</a></p>
                    </div>
                    </div>
                    <!-- MODIF AKHIR -->
                    
                    <!-- ASLINA AWAL 
                    <div class="single-popular-items mb-50 text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s">
                        <div class="popular-img">
                                <img src="foto/<?php echo $data['foto']; ?>" alt="">
                            <div class="img-cap">
                                 <span><?php echo $data['namaproduk']; ?> <br>
						(<?php echo $data['stock']; ?>)Pcs <br>	<a class="btn btn-info btn-lg" href="add_chart.php?id=<?php echo $data['idprodukdb']; ?>&harga=<?php echo $data['harga']; ?>"> +<i class="glyphicon glyphicon-shopping-cart"></i> </a></span>
				
                            </div>
                            
                        </div>
                    </div>
                      ASLINA AKHIR -->
                </div>
                
                 <?php } ?>
            
        
    </div>
			
			<!--
			-- Buat Paginationnya
			-- Dengan bootstrap, kita jadi dimudahkan untuk membuat tombol-tombol pagination dengan design yang bagus tentunya
			-->
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
				$sql2 = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM produkdb WHERE stock>0 and harga>0 and idadmin='$idadmin'");
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
			<br>
			<br>
			<br>
			<br>
			<br>
		
		</div>
		
<?php include "menubawahstoredb.php"; ?>
		
<!-- AKHIR MODE  -->		

		<!-- JS here -->
<!-- Jquery, Popper, Bootstrap -->
<script src="../vendor/legacy-js/modernizr-3.5.0.min.js"></script>
<script src="../vendor/legacy-js/jquery-1.12.4.min.js"></script>
<script src="./assets2/js/popper.min.js"></script>
<script src="./assets2/js/bootstrap.min.js"></script>

<!-- Slick-slider , Owl-Carousel ,slick-nav -->
<script src="./assets2/js/owl.carousel.min.js"></script>
<script src="./assets2/js/slick.min.js"></script>
<script src="./assets2/js/jquery.slicknav.min.js"></script>

<!-- One Page, Animated-HeadLin, Date Picker -->
<script src="./assets2/js/wow.min.js"></script>
<script src="./assets2/js/animated.headline.js"></script>
<script src="./assets2/js/jquery.magnific-popup.js"></script>
<script src="./assets2/js/gijgo.min.js"></script>

<!-- Nice-select, sticky,Progress -->
<script src="./assets2/js/jquery.nice-select.min.js"></script>
<script src="./assets2/js/jquery.sticky.js"></script>
<script src="./assets2/js/jquery.barfiller.js"></script>

<!-- counter , waypoint,Hover Direction -->
<script src="./assets2/js/jquery.counterup.min.js"></script>
<script src="./assets2/js/waypoints.min.js"></script>
<script src="./assets2/js/jquery.countdown.min.js"></script>
<script src="./assets2/js/hover-direction-snake.min.js"></script>

<!-- contact js -->
<script src="./assets2/js/contact.js"></script>
<script src="./assets2/js/jquery.form.js"></script>
<script src="./assets2/js/jquery.validate.min.js"></script>
<script src="./assets2/js/mail-script.js"></script>
<script src="./assets2/js/jquery.ajaxchimp.min.js"></script>

<!-- Jquery Plugins, main Jquery -->	
<script src="./assets2/js/plugins.js"></script>
<script src="./assets2/js/main.js"></script>
	</body>
	<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.js"></script>
</html>

