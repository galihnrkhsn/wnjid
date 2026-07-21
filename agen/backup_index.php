<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

$idmitraagen=$_SESSION["mitraagen"]["idmitraagen"];
	$keranjang=0;
$sikat=$koneksi->query("SELECT * FROM keranjang where idagen='$idmitraagen' and jmlh>0 "); 
while($count=$sikat->fetch_assoc()){
     $keranjang+=$count['jmlh'];
}

$ambiljmlh=$koneksi->query("SELECT COUNT(*) as jmlh FROM tinbox where idmitraagen='$idmitraagen' and status='Belum Dibaca'"); 
$datajmlh=$ambiljmlh->fetch_assoc();


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| Wanoja </title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

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


<style>

/* CSS MultiSlider */
.multi-item-carousel .carousel-inner > .item {
  -webkit-transition: 500ms ease-in-out left;
  transition: 500ms ease-in-out left;
}
.multi-item-carousel .carousel-inner .active.left {
  left: -33%;
}
.multi-item-carousel .carousel-inner .active.right {
  left: 33%;
}
.multi-item-carousel .carousel-inner .next {
  left: 33%;
}
.multi-item-carousel .carousel-inner .prev {
  left: -33%;
}
@media all and (transform-3d), (-webkit-transform-3d) {
  .multi-item-carousel .carousel-inner > .item {
    -webkit-transition: 500ms ease-in-out left;
    transition: 500ms ease-in-out left;
    -webkit-transition: 500ms ease-in-out all;
    transition: 500ms ease-in-out all;
    -webkit-backface-visibility: visible;
            backface-visibility: visible;
    -webkit-transform: none !important;
            transform: none !important;
  }
}
.multi-item-carousel .carouse-control.left,
.multi-item-carousel .carouse-control.right {
  background-image: none;
}
c {
 
  color: #ddd;
}
h1 {
  color: white;
  font-size: 2.25em;
  text-align: center;
  margin-top: 1em;
  margin-bottom: 2em;
  text-shadow: 0px 2px 0px #000000;
}
b {
  color: #2874A6 ;
  font-size: 1.25em;

}

.fa.fa-home{
    color: #337ab7;
    text-shadow: 1px 1px 1px #ccc;
    font-size: 1.5em;
}
.fa.fa-info-circle{
    color: #337ab7;
    text-shadow: 1px 1px 1px #ccc;
    font-size: 1.5em;
}
.fab.fa-leanpub{
    color: #337ab7;
    text-shadow: 1px 1px 1px #ccc;
    font-size: 1.5em;
}
.fa.fa-cog{
    color: #337ab7;
    text-shadow: 1px 1px 1px #ccc;
    font-size: 1.5em;
}
.fa.fa-user-circle{
    color: #337ab7;
    text-shadow: 1px 1px 1px #ccc;
    font-size: 1.5em;
}


.jumbotron2 {
   
   background: linear-gradient(to bottom, #e9ecef 0%, #e9ecef 10%, #e9ecef 60%,  #ffffff 100%);

  margin: auto;
text-align: center;
  overflow: hidden;
  
}

.jumbotron2 p {

text-decoration: none;
padding: 10px 0;
font-size: 15px;
 color: #2874A6;
 text-align: center;
 
}
.jumbotron2 a {

text-decoration: none;
padding: 10px 0;
font-size: 15px;
 color: #2874A6;
 text-align: center;
 
}
.jumbotron2 sup {

text-decoration: none;
padding: 10px 0;
font-size: 18px;
 color: #2874A6;
 text-align: center;
 
}

.jumbotron4 {
   
   background: linear-gradient(to bottom, #488cf6 0%, #488cf6 10%, #488cf6 60%,  #ffffff 100%);

  margin: auto;
text-align: center;
  overflow: hidden;
  
}

.jumbotron4 p {

text-decoration: none;
padding: 10px 0;
font-size: 18px;
 color: #f2f2f2;
 text-align: center;
 
}

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
  font-size:30px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru span {
  padding: 0px 0;
 
   padding-right: 3%;
   padding-left: 3%;
   padding-top: 2%;
   padding-bottom: 2%;
   text-align: center;
   
}

.gaya i {
  margin-top: 8%;
  font-size:24px;
  color:primary;
   
}

.gaya span {
   padding-right: 3%;
   padding-left: 3%;
   padding-top: 2%;
   padding-bottom: 2%;
   text-align: center;
   margin-top: 4%;
}
.navbaru2 {
   
    
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}
</style>
</head>

<body>

<div class="container row fixed-top jumbotron2" >

  <div class="col-4"><p><a href="formpembayaran.php"><i class="fa fa-check-circle" aria-hidden="true"> </i><br>Konfirmasi</a></p></div>
  <div class="col-4" ><p><a href="transaksi.php"><i class="fa fa-sign-language" aria-hidden="true"> </i><br>Transaksi</a></p></div>
  <div class="col-2 gaya">
    <p>
    <a href="view_cart.php">
      <i class="fa fa-shopping-cart" ></i>
<?php if ($keranjang==0) {
    }else{?>
  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

  <?php echo $keranjang; ?>
  </span>
    <?php } ?>
    </a>
    </p>
  </div>
  <div class="col-2 gaya">
    <p>
      <a href="pesan.php">
    <i class="fa fa-envelope" ></i>
    <?php if ($datajmlh['jmlh']==0) {
      
    }else{?>
  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

  <?php echo $datajmlh['jmlh']; ?> 
  </span>
    <?php } ?>
  </a>
</p>
</div>
  <hr>
</div>

<br><br><br>



   <?php 
     $mitra= $_SESSION['mitraagen']['idmitraagen'];

     $ambil=$koneksi->query("SELECT namaagen FROM mitraagen where idmitraagen= '$mitra'"); 
     $data=$ambil->fetch_assoc();
     
      $ambil2=$koneksi->query("SELECT mitraagen.namaagen,(sum(saldoagen.debit) - sum(saldoagen.credit)) AS selisih FROM mitraagen inner join saldoagen on mitraagen.idmitraagen=saldoagen.idmitraagen where saldoagen.idmitraagen= '$mitra'"); 
     $data2=$ambil2->fetch_assoc();
      ?>
      
  <div class="container"> 
  
  <div class="jumbotron">
      <br>
      
  <div class="row ">
      

  <div class="col-2"><!--<img src="foto/<?php echo $data['foto'] ?>" style="width:55px;height:55px;border-radius: 50%;"></a>--></div>
  <div class="col-7"><b>Agen <?php echo $data['namaagen'] ?></b><br> <button type="button" class="btn btn-default"><i class="fa fa-money"></i> Saldo Rp. <?php echo $data2['selisih'] ?> ,- </button></div>

  
  <div class="col-2">
    <a href="logout.php" style="color:#2874A6;"><i class="fa fa-power-off logout-mobile" style="font-size:36px;color:primary;"></i></a>
        
  </div>
  <div class="col-1"> 
  </div>
</div>
</div>


 <!--================Slider Area  =================-->
   
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
         <?php 
     $ambil2=$koneksi->query("SELECT foto FROM slider where id=1"); 
     $foto1=$ambil2->fetch_assoc();
      ?>
      <div class="item active gbrlengkung">
        <img src="../adminwnj/slider/<?php echo $foto1['foto'] ?>" alt="slider1" style="width:100%;">
      </div>
        <?php 
     $ambil3=$koneksi->query("SELECT foto FROM slider where id=2"); 
     $foto2=$ambil3->fetch_assoc();
      ?>
      <div class="item gbrlengkung">
        <img src="../adminwnj/slider/<?php echo $foto2['foto'] ?>" alt="slider2" style="width:100%;">
      </div>
     <?php 
     $ambil4=$koneksi->query("SELECT foto FROM slider where id=3"); 
     $foto3=$ambil4->fetch_assoc();
      ?>
      <div class="item gbrlengkung">
        <img src="../adminwnj/slider/<?php echo $foto3['foto'] ?>" alt="slider3" style="width:100%;">
      </div>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  <br><br>
<!--================Slider Area End =================-->
<br>
<!--================Icon Area =================-->
  <!--  <center><button type="submit" class="btn btn-primary btn-lg" name="cari" id="linkkolibri"><a  style="color:white" href="formdropship_kolibri.php">Link PO KOLIBRI 2021</a></button><p id="demokolibri"></p><br></center> -->

  <div class="row " style="margin-top:-25px">

    
    <!-- <div class="col-3 col-lg-2"><a href="#">
        <img src="img/mystock-off.png" class="d-block w-100" > <p class="text-center">My Stock</p></a>
    </div> -->
    
    <!--<div class="col-3 col-lg-2"><a href="#">
        <img src="img/stockpusat-off.png" class="d-block w-100" > <p class="text-center">StockDB</p></a>
    </div>
    
     <div class="col-3 col-lg-2"><a href="store2.php">
        <img src="img/stockpusat.png" class="d-block w-100"> <p class="text-center">StockPusat</p></a>
    </div>-->
    
     <div class="col-3 col-lg-2"><a href="listpreorder.php">
        <img src="img/preorder2.png" class="d-block w-100"> <p class="text-center">Preorder</p></a>
    </div>
    
        <div class="col-3 col-lg-2"><a href="dataagen.php">
        <img src="img/submitra.png" class="d-block w-100"><p class="text-center">SubAgen</p></a>
    </div>

     <div class="col-3 col-lg-2"><a href="katalog.php">
        <img src="img/katalog.png" class="d-block w-100" > <p class="text-center">Katalog</p></a>
    </div>
   
     <div class="col-3 col-lg-2"><a href="pricelist.php">
        <img src="img/pricelist.png" class="d-block w-100" > <p class="text-center">Pricelist</p></a>
    </div>
   
     <div class="col-3 col-lg-2"><a href="resi.php">
        <img src="img/resi.png" class="d-block w-100" > <p class="text-center">Resi</p></a>
    </div>
     
  </div>

</div>

<!--<div class="container">
<div class="row " style="margin-top:-10px">
    
    <div class="col-6 col-lg-6"><a href="formpembayaran.php">
        <img src="img/konfirmasitransfer.png" class="d-block w-100"></a>
    </div> 
    
    <div class='col-6 col-lg-6'><a href='transaksi.php'>
        <img src='img/transaksi2.png' class='d-block w-100'></a>
    </div>
  
</div>
</div>-->
<!--================ END Eks Area =================-->



<!--================ SEARCH =================-->

<div class="container caricari">

<hr>

<div class="container">
		 
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

<!-- <p align="left"><input type="radio" onclick="javascript:window.location.href='index2.php'; "> Mode Hemat  &nbsp&nbsp<input type="radio" onclick="javascript:window.location.href='#'; " checked="checked"> Mode Cantik
        </p> -->
      
<form method="post"><p align="left"><input type="text" name="namaproduk" placeholder="Masukan Nama Produk ..." /> <button class="btn btn-primary" name="cari" type="submit"><span class="glyphicon glyphicon-search"></span></button> <button class="btn btn-primary" name="tampil" type="submit">Tampil Semua</button></p></form>     
				
			
<!-- MODE  -->

<div class="container">

            <div class="row">
                    	<?php
					if(isset($_POST["cari"])){
				  // Include / load file koneksi.php
					include "koneksi.php";
					$idmitraagen=$_SESSION['mitraagen']['idmitraagen'];
					$namaproduk=$_POST['namaproduk'];
					//$idkategori=$_GET['idkategori'];					
					// Cek apakah terdapat data page pada URL
					$page = (isset($_GET['page']))? $_GET['page'] : 1;
					
					$limit = 20; // Jumlah data per halamannya
					
					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
					$limit_start = ($page - 1) * $limit;
					
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT * from produk WHERE stock>0 and harga>0 and idkategori>0 and namaproduk LIKE '%$namaproduk%' and status=0");
					
					$no = $limit_start + 1; // Untuk penomoran tabel
					
					}elseif(isset($_POST["tampil"])){
				 // Include / load file koneksi.php
					include "koneksi.php";
					$idmitraagen=$_SESSION['mitraagen']['idmitraagen'];
					//$idkategori=$_GET['idkategori'];					
					// Cek apakah terdapat data page pada URL
					$page = (isset($_GET['page']))? $_GET['page'] : 1;
					
					$limit = 20; // Jumlah data per halamannya
					
					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
					$limit_start = ($page - 1) * $limit;
					
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT * from produk WHERE stock>0 and harga>0 and idkategori>0 and status=0 LIMIT ".$limit_start.",".$limit);
					
					$no = $limit_start + 1; // Untuk penomoran tabel
					}else{
					    // Include / load file koneksi.php
					include "koneksi.php";
					$idmitraagen=$_SESSION['mitraagen']['idmitraagen'];
					//$idkategori=$_GET['idkategori'];					
					// Cek apakah terdapat data page pada URL
					$page = (isset($_GET['page']))? $_GET['page'] : 1;
					
					$limit = 20; // Jumlah data per halamannya
					
					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
					$limit_start = ($page - 1) * $limit;
					
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT * from produk WHERE stock>0 and harga>0 and idkategori>0 and status=0 order by idproduk desc LIMIT ".$limit_start.",".$limit);
					
					$no = $limit_start + 1; // Untuk penomoran tabel
					}
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-6">
                    <!-- MODIF AWAL -->
                   <div class="card img" style="width:500px">
                    <img class="card-img-top" src="../distributor/foto/<?php echo $data['foto']; ?>" alt="Card image" style="width:100%">
                    <div class="card-body">
                      <h4 class="card-title"><p><strong><?php echo $data['namaproduk']; ?></strong><br>
                      Rp. <?php echo number_format($data['harga']); ?></p></h4>
                      <p class="card-text">
						(<?php echo $data['stock']; ?>)Pcs 	&nbsp&nbsp&nbsp
                      <a href="add_chart2.php?id=<?php echo $data['idproduk']; ?>&harga=<?php echo $data['harga']; ?>" class="btn btn-primary">Beli</a></p>
                    </div>
                    </div>
                      <br>
                      
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
				$sql2 = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM produk where stock>0 and harga>0 and idkategori>0 and status=0");
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
		
		</div>
		
<?php //include "menubawahstore.php"; ?>

<div class="container">
<div class="row">
    <div class="col-md-12">
      <div class="carousel slide multi-item-carousel" id="theCarousel">
        <div class="carousel-inner">
          <div class="item active">
            <div class="col-xs-4"><a href="#1"><img src="img/jne.png" class="img-responsive"></a></div>
          </div>
          <div class="item">
            <div class="col-xs-4"><a href="#1"><img src="img/jnt.png" class="img-responsive"></a></div>
          </div>
          <div class="item">
            <div class="col-xs-4"><a href="#1"><img src="img/tiki.png" class="img-responsive"></a></div>
          </div>
          <div class="item">
            <div class="col-xs-4"><a href="#1"><img src="img/wahana.png" class="img-responsive"></a></div>
          </div>
          <div class="item">
            <div class="col-xs-4"><a href="#1"><img src="img/lion.png" class="img-responsive"></a></div>
          </div>
          <div class="item">
            <div class="col-xs-4"><a href="#1"><img src="img/sicepat.png" class="img-responsive"></a></div>
          </div>
          <!-- add  more items here -->
          <!-- Example item start:  -->

          <div class="item">
            <div class="col-xs-4"><a href="#1"><img src="img/pegasus.png" class="img-responsive"></a></div>
          </div>

          <!--  Example item end -->
        </div>
        <a class="left carousel-control" href="#theCarousel" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
        <a class="right carousel-control" href="#theCarousel" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
      </div>
    </div>
  </div>
</div>
		
<!-- AKHIR MODE  -->		

		<!-- JS here -->
<!-- Jquery, Popper, Bootstrap -->
<script src="./assets2/js/vendor/modernizr-3.5.0.min.js"></script>
<script src="./assets2/js/vendor/jquery-1.12.4.min.js"></script>
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



<!--================ END MAP MITRA =================-->
<br>
<br>
<div class="container">
<c>Copyright &copy; 2020<br>
by Wanoja ITSupport</c>
</div>

  
</div>
<br><br><br><br>
<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDate = new Date("Feb 01, 2021 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDate - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demokolibri").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demokolibri").innerHTML = "Link PO tidak tersedia";
      var x = document.getElementById("linkkolibri");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>   	




<script>

// JAVASCRIPT SLIDER

// Instantiate the Bootstrap carousel
$('.multi-item-carousel').carousel({
  interval: false
});

// for every slide in carousel, copy the next slide's item in the slide.
// Do the same for the next, next item.
$('.multi-item-carousel .item').each(function(){
  var next = $(this).next();
  if (!next.length) {
    next = $(this).siblings(':first');
  }
  next.children(':first-child').clone().appendTo($(this));
  
  if (next.next().length>0) {
    next.next().children(':first-child').clone().appendTo($(this));
  } else {
  	$(this).siblings(':first').children(':first-child').clone().appendTo($(this));
  }
});  





</script> 
<?php
      $idmitraagen=$_SESSION["mitraagen"]["namaagen"];
       $ambil=$koneksi->query("SELECT mode FROM mitraagen where idmitraagen='$idmitraagen' "); 
      $mode=$ambil->fetch_assoc();
          ?>
 <!-- FOOTER 2 -->         
<div class="container row fixed-bottom jumbotron3" >
  <div class="col-20 "><a href="index.php"><i class="fa fa-home fa-lg "></i><p class="text-center">Home</p></a></div>
  <div class="col-20"><a href="wanoja-link.php"><i class="fa fa-info-circle fa-lg "></i><p class="text-center">W-Info</p></a></div>
  <div class="col-20"><a href="elearning.php"><i class="fab fa-leanpub fa-lg"></i><p class="text-center">Tutorial</p></a></div>
  <div class="col-20"><a href="setting.php"><i class="fa fa-cog fa-lg"></i> <p class="text-center">Setting</p></a></div>
  <div class="col-20"><a href="profile.php"><i class="fa fa-user-circle fa-lg"></i><p class="text-center">Profil</p></a></div>
</div>		
 <!-- FOOTER 2 END --> 

<style>
/* Place the navbar at the bottom of the page, and make it stick */

.jumbotron3 {
    background: #f2f2f2;
   
    margin: auto;
  text-align: center;
    overflow: hidden;
    padding: 10px 0px 0px 0px;
}

.jumbotron3 p {
  text-decoration: none;
  padding: 0px 0px 0px 0px;
  
   
   text-align: center;
   
}


.col-20 {width: 20%;}


</style>

           


</body>

</html>

