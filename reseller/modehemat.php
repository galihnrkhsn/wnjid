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
 
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> -->


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
body {font-family: Arial;}

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


p {font-size:14px; line-height: 1.5em; font-family:verdana;
    
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
   

  background-color: #fefbd8;

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

.jumbotron2 {
   
   background: linear-gradient(to bottom, #488cf6 0%, #488cf6 10%, #488cf6 60%,  #ffffff 100%);

  margin: auto;
text-align: center;
  overflow: hidden;
  
}

.jumbotron2 p {

text-decoration: none;
padding: 10px 0;
font-size: 18px;
 color: #f2f2f2;
 text-align: center;
 
}

.jumbotron2 sup {

text-decoration: none;
padding: 10px 0;
font-size: 18px;
 color: #f2f2f2;
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

  <div class="col-5"><p><a href="formpembayaran.php" ><i class="fa fa-check-circle" aria-hidden="true"> Konfirmasi</i></a></p></div>
  <div class="col-5" ><p><a href="transaksi.php"><i class="fa fa-sign-language" aria-hidden="true"> Transaksi</i></a></b></div>
  <div class="col-2"><p><a href="view_cart.php"><span class="glyphicon glyphicon-shopping-cart fa-lg"><sup><?php echo $keranjang; ?></sup></span></a></p></div>
  <hr>
</div>
  
  <div class="jumbotron";>
      <br>
      
  <div class="row ">
      

  <div class="col-2"><!--<img src="foto/<?php echo $data['foto'] ?>" style="width:55px;height:55px;border-radius: 50%;"></a>--></div>
  <div class="col-7"><b>Reseller <?php echo $data['namaagen'] ?></b><br>Mitra Reseller</div>

  <div class="col-1"> <a href="logout.php"><img src="img/logout.png" alt="" width="40" height="40" title="Bootstrap"></a>
  </div>
  <div class="col-2"></div>
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

      <div class="item active">
        <img src="img/slider1.jpeg" alt="Los Angeles" style="width:100%;">
        <div class="carousel-caption">
         
        </div>
      </div>

      <div class="item">
        <img src="img/slider2.jpeg" alt="Chicago" style="width:100%;">
        <div class="carousel-caption">
         
        </div>
      </div>
    
      <div class="item">
        <img src="img/slider2.jpeg" alt="New York" style="width:100%;">
        <div class="carousel-caption">
        
        </div>
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
  echo "<div class='col-3 col-lg-2'><a href='stockagen.php'>
        <img src='img/stockagen.png' class='d-block w-100' > <p class='text-center'>Stock Agen</p></a>
    </div>";
    }
    ?>
    
    <!--<div class="col-3 col-lg-2"><a href="#">
        <img src="img/stockpusat-off.png" class="d-block w-100" > <p class="text-center">StockDB</p></a>
    </div>-->
  
     <div class="col-3 col-lg-3"><a href="store2.php">
        <img src="img/stockpusat.png" class="d-block w-100" > <p class="text-center">Stock Pusat</p></a>
    </div>
    
    <!--<div class="col-3 col-lg-2"><a href="listpreorder.php">
        <img src="img/preorder2.png" class="d-block w-100" > <p class="text-center">Preorder</p></a>
    </div>-->


     <div class="col-3 col-lg-3"><a href="katalog.php">
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
    <table style="width:100%">
      <tr>
        <th>
          <p align="left"><input type="radio" onclick="javascript:window.location.href='modehemat.php'; " checked="checked"> Mode Hemat  &nbsp&nbsp<input type="radio" onclick="javascript:window.location.href='index.php'; "> Mode Cantik
        </p>
        </th>
        <th>
          <p align="right">
          <a href="view_cart.php" align="right"><span class="glyphicon glyphicon-shopping-cart badge fa-lg"><?php echo $keranjang; ?></span></a>
          </p>
        </th>
      </tr>
    </table>
    
        <form method="post"><p align="left"><input type="text" name="namaproduk" placeholder="Masukan Nama Produk ..." /> <button class="btn btn-primary" name="cari" type="submit"><span class="glyphicon glyphicon-search"></span></button> <button class="btn btn-primary" name="tampil" type="submit">Tampil Semua</button> <a class="btn btn-success" href="excelproduk.php">Export</a></p></form>
                <table class="w3-table-all" id="dataTables-example" border="0" >
                    <tr>
            <td><span class="glyphicon glyphicon-shopping-cart"></span></td>
            <td>stock</td>
            <td>produk</td>
            </tr>
            
          <tbody>
          <?php
          if(isset($_POST["cari"])){
          // Include / load file koneksi.php
          include "koneksi.php";
          $idmitrareseller=$_SESSION['mitraagen']['idmitrareseller'];
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
          $idmitrareseller=$_SESSION['mitraagen']['idmitrareseller'];
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
          $idmitrareseller=$_SESSION['mitraagen']['idmitrareseller'];
          //$idkategori=$_GET['idkategori'];          
          // Cek apakah terdapat data page pada URL
          $page = (isset($_GET['page']))? $_GET['page'] : 1;
          
          $limit = 50; // Jumlah data per halamannya
          
          // Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
          $limit_start = ($page - 1) * $limit;
          
          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $sql = mysqli_query($koneksi, "SELECT * from produk WHERE stock>0 and harga>0 and idkategori>0 and status=0 order by namaproduk asc LIMIT ".$limit_start.",".$limit);
          
          $no = $limit_start + 1; // Untuk penomoran tabel
          }
          while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
          ?>
          
            <tr>
            <td><a class="btn btn-info btn-xs" href="add_chart.php?id=<?php echo $data['idproduk']; ?>&harga=<?php echo $data['harga']; ?>"> <span class="glyphicon glyphicon-plus"></span></a> </td>
            <td><?php echo $data['stock']; ?> </td>
            <td><?php echo $data['namaproduk']; ?></td>
            </tr>
          
          
        </tbody>
        <?php } ?>
        </table>
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



  
</div><br><br><br><br>

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
   center center;
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


[class*="col-"] {
  
}


.col-20 {width: 20%;}


</style>

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


</body>

</html>

