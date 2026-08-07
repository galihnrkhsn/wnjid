<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$idmitrareseller=$_SESSION["mitraagen"]["idmitrareseller"];
  $keranjang=0;
$sikat=$koneksi->query("SELECT * FROM keranjang where idreseller='$idmitrareseller' and jmlh>0 "); 
while($count=$sikat->fetch_assoc()){
     $keranjang+=$count['jmlh'];
}

$ambiljmlh=$koneksi->query("SELECT COUNT(*) as jmlh FROM tinbox where idmitrareseller='$idmitrareseller' and status='Belum Dibaca'"); 
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
<title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| WNJ.ID </title>
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
 
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> -->


<style>
* {
  box-sizing: border-box;
}

body {
  font-family: "Poppins",sans-serif;
}

/* Style the header */
.header {
  background-color: #f1f1f1;

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
  padding: 2px;
 
}

/* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
@media (max-width: 600px) {
  .column {
    width: 50%;
  }
}


/* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}


p {font-size:14px; line-height: 1.5em; font-family: "Poppins",sans-serif;
    
}

.navbar-default .navbar-nav > li.clr1 a{
        color:#ffffff;
}
.navbar-default .navbar-nav > li.clr2 a{
    color: #FFEB3B;;
}
.navbar-default .navbar-nav > li.clr3 a{
    color: #5EC64D;
}
.navbar-default .navbar-nav > li.clr4 a{
    color: #29AAE2;
}
.navbar-default .navbar-nav > li.clr5 a{
    color: #CEE229;
}

.navbar-default .navbar-nav > li.clr1.active a{
    color:#fff;
    background: #F55;
}
.navbar-default .navbar-nav > li.clr1 a:hover{
    background: rgba(255, 85, 85, 0.75);
} 
.navbar-default .navbar-nav > li.clr2.active a{
    color: #fff;
    background:#973CB6;
}
.navbar-default .navbar-nav > li.clr2 a:hover{
 background: rgba(151, 60, 182, 0.66)
}


.navbar-default .navbar-nav > li.clr3.active a{
    color: #fff;
    background:#5EC64D;
}
.navbar-default .navbar-nav > li.clr3 a:hover
{
  background: rgba(94, 198, 77, 0.78);
}
.navbar-default .navbar-nav > li.clr4.active a{
    color: #fff;
    background: #29AAE2;
}
.navbar-default .navbar-nav > li.clr4 a:hover{
    background: rgba(41, 170, 226, 0.62);
}

.navbar-default .navbar-nav > li.clr5.active a{
    color: #fff;
    background: #CEE229;
}
.navbar-default .navbar-nav > li.clr5 a:hover{
    background: rgba(206, 226, 41, 0.63);
}
.navbar-default{
    background-color: #3b5998;
    font-size:18px;
    border-color:none;
}
.navbar-default .navbar-brand {
    color: #ffffff;
    font-weight:bold;
}
.navbar-default .navbar-text {
    color:#ffffff;
}

.pemisah-btnnav{
    margin-left: 20px;
    margin-right: 20px;
}

.recent{
    padding-top:20px;
    
}
.info-meta{
    padding-top: 10px;
    color:#9999;
}
a:focus, 
a:hover {
text-decoration: none;
outline: none;
color: #9c9c9c;
}
.footer-bottom {
background-color:#3b5998;
color:#fff;
padding-top:10px;
padding-bottom:10px;
}

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
.navbawah {
  background-color: #fefbd8;
  height: 80px;
  position: absolute;
  right: 0px;
  
 
  padding: 15px;
 
  display: block;

}
.navbawah a {
  position: absolute;
  padding: 5px;
 

}
.carousel-inner img {
    width: 100%;
   
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

/* Extra small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
  .custommargin {
  margin-top: 20%;
}
}

/* Small devices (portrait tablets and large phones, 600px and up) */
@media only screen and (min-width: 600px) {
  .custommargin {
  margin-top: 20%;
}
}

/* Medium devices (landscape tablets, 768px and up) */
@media only screen and (min-width: 768px) {
  .custommargin {
  margin-top: 13%;
}
} 

/* Large devices (laptops/desktops, 992px and up) */
@media only screen and (min-width: 992px) {
  .custommargin {
  margin-top: 9%;
}
} 

/* Extra large devices (large laptops and desktops, 1200px and up) */
@media only screen and (min-width: 1200px) {
  .custommargin {
  margin-top: 7%;
}
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
font-size: 18px;
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


.gaya i {
  margin-top: 14%;
  font-size:24px;
  color:primary;
   
}

.gaya span {
   padding-right: 3%;
   padding-left: 3%;
   padding-top: 2%;
   padding-bottom: 2%;
   text-align: center;
   margin-top: 8%;
}



/*.glyphicon.glyphicon-shopping-cart {
    font-size: 15px;
}*/
</style>
</head>

<body>


   <?php 
     $mitra= $_SESSION['mitraagen']['idmitrareseller'];

     $ambil=$koneksi->query("SELECT namaagen FROM mitrareseller  where idmitrareseller= '$mitra'"); 
     while($data=$ambil->fetch_assoc()){
      ?>




<div class="container">
<div class="container row fixed-top jumbotron2" >

  <div class="col-4"><p><a href="formpembayaran.php" ><i class="fa fa-check-circle" aria-hidden="true"></i> <br> Konfirmasi</a></p></div>
  <div class="col-4" ><p><a href="transaksi.php"><i class="fa fa-sign-language" aria-hidden="true"></i> <br>Transaksi</a></p></div>
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
  <div class="col-2 gaya"><p>
    <a href="pesan.php">
      <i class="fa fa-envelope" ></i>
        <?php if ($datajmlh['jmlh']==0) {
      
    }else{?>
  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

  <?php echo $datajmlh['jmlh']; ?> 
  </span>
    <?php } ?>
    </a>
  </p></div>

</div>

      


<div class="jumbotron custommargin">
  <div class="row ">
    <div class="col-2">
            <!-- <img src="foto/<?php echo $data['foto'] ?>" style="width:55px;height:55px;border-radius: 50%;"> -->
    </div>
    <div class="col-7">
      <b>Reseller <?php echo $data['namaagen'] ?></b><br>Mitra Reseller
    </div>

    <div class="col-2">
      <a href="logout.php" style="color:#2874A6;"><i class="fa fa-power-off logout-mobile" style="font-size:36px;color:primary;"></i></a>
    </div>
    <div class="col-1"> 

    </div>
  </div>
</div>


  <?php } ?>

<div class="container">
 
  <div id="myCarousel" class="carousel slide" data-ride="carousel" >
  
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>

   
    <div class="carousel-inner">
         <?php 
     $ambil2=$koneksi->query("SELECT foto FROM slider where id=1"); 
     $foto1=$ambil2->fetch_assoc();
      ?>
      <div class="item active">
        <img src="../image/carousel/<?php echo $foto1['foto'] ?>" alt="slider1" style="width:100%;">
      </div>
        <?php 
     $ambil3=$koneksi->query("SELECT foto FROM slider where id=2"); 
     $foto2=$ambil3->fetch_assoc();
      ?>
      <div class="item">
        <img src="../image/carousel/<?php echo $foto2['foto'] ?>" alt="slider2" style="width:100%;">
      </div>
     <?php 
     $ambil4=$koneksi->query("SELECT foto FROM slider where id=3"); 
     $foto3=$ambil4->fetch_assoc();
      ?>
      <div class="item">
        <img src="../image/carousel/<?php echo $foto3['foto'] ?>" alt="slider3" style="width:100%;">
      </div>
    </div>

   
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>

</body>
</html>
<!--================Slider Area End =================-->
<br>
<!--================Icon Area =================-->
  <!--<center><button type="submit" class="btn btn-primary btn-lg" name="cari" id="linkkolibri"><a  style="color:white" href="formdropship_kolibri.php">Link PO KOLIBRI 2021</a></button><p id="demokolibri"></p><br></center>-->



  <div class="row " >
    
    <?php if($_SESSION["mitraagen"]["idmitraagen"]<>0){
  echo "<div class='col-3 col-lg-3'><a href='stockagen.php'>
        <img src='img/stockagen.png' class='d-block w-100' > <p class='text-center'>Stock Agen</p></a>
    </div>";
    }
    ?>
    
    <!--<div class="col-3 col-lg-2"><a href="#">
        <img src="img/stockpusat-off.png" class="d-block w-100" > <p class="text-center">StockDB</p></a>
    </div>
  
     <div class="col-3 col-lg-3"><a href="store2.php">
        <img src="img/stockpusat.png" class="d-block w-100" > <p class="text-center">Stock Pusat</p></a>
    </div>-->
    
    <div class="col-3 col-lg-3">
      <a href="listpreorder.php">
        <!-- <a href="#"> -->
        <img src="img/preorder2.png" class="d-block w-100" > <p class="text-center">Preorder</p></a>
    </div>


     <div class="col-3 col-lg-3"><a href="http://wnj.web.id/inkubator/katalog.php" target="blank()">
        <img src="img/katalog.png" class="d-block w-100" > <p class="text-center">Katalog</p></a>
    </div>
    
     <!-- <div class="col-3 col-lg-2"><a href="store2.php">
        <img src="img/readystock.png" class="d-block w-100" ><p class="text-center"> Order Pusat</p></a>
    </div> -->
   
     <div class="col-3 col-lg-3"><a href="pricelist.php">
        <img src="img/pricelist.png" class="d-block w-100" > <p class="text-center">Pricelist</p></a>
    </div>
   
    <!--<div class="col-3 col-lg-2"><a href="nyabar.php">
        <img src="img/nabar.png" class="d-block w-100"> <p class="text-center">Nyabar</p></a>
    </div>-->
    
      <div class="col-3 col-lg-3"><a href="resi.php">
        <img src="img/resi.png" class="d-block w-100" > <p class="text-center">Resi</p></a>
    </div>
 
  </div>

  

  <br>
<hr>

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