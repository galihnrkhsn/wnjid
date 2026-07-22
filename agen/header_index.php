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
      <!-- <a href="#"> -->
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

      ?>
      
  <div class="container"> 
  
  <div class="jumbotron">
      <br>
      
  <div class="row ">
      

  <div class="col-2"><!--<img src="foto/<?php echo $data['foto'] ?>" style="width:55px;height:55px;border-radius: 50%;"></a>--></div>
  <div class="col-7"><b>Agen <?php echo $data['namaagen'] ?></b><br> </div>

  
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
        <img src="../image/carousel/<?php echo $foto1['foto'] ?>" alt="slider1" style="width:100%;">
      </div>
        <?php 
     $ambil3=$koneksi->query("SELECT foto FROM slider where id=2"); 
     $foto2=$ambil3->fetch_assoc();
      ?>
      <div class="item gbrlengkung">
        <img src="../image/carousel/<?php echo $foto2['foto'] ?>" alt="slider2" style="width:100%;">
      </div>
     <?php 
     $ambil4=$koneksi->query("SELECT foto FROM slider where id=3"); 
     $foto3=$ambil4->fetch_assoc();
      ?>
      <div class="item gbrlengkung">
        <img src="../image/carousel/<?php echo $foto3['foto'] ?>" alt="slider3" style="width:100%;">
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
    
     <div class="col-3 col-lg-2">
      <a href="listpreorder.php">
        <!-- <a href="#"> -->
        <img src="img/preorder2.png" class="d-block w-100"> <p class="text-center">Preorder</p></a>
    </div>
    
        <div class="col-3 col-lg-2"><a href="dataagen.php">
        <img src="img/submitra.png" class="d-block w-100"><p class="text-center">SubAgen</p></a>
    </div>

     <div class="col-3 col-lg-2"><a href="http://wnj.web.id/inkubator/katalog.php" target="blank()">
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