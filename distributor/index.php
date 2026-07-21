<?php
    header('Location: https://wnj.id/distributor/login2.php');
    exit;
?>

<?php 
session_start();

include 'koneksi.php'; 

if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
  echo "<script>location='login2.php';</script>";
  header('location:login2.php');
  exit();
}

  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
    $ambil=$koneksi->query("SELECT admin_mitra.idadmin, admin_mitra.namamitra,admin_mitra.foto,admin_mitra.mode FROM admin_mitra where idadmin='$idadmin' ");
    $mode=$ambil->fetch_assoc();
    // header('location:listnewpo.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

<meta property="og:image:alt" content="A shiny red apple with a bite taken out" />

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script> -->
    <title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ </title>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link href="css/font-more-awesome.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
  
  
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


.kotak {
  color: #2288ee;
  padding: 10px;
  margin: 15px;
    box-shadow: 0px 0px 10px #000000;
}
.seperempat {
  width: 26%;
  float: left;
}
.bulat {
  border-radius: 20px;
}
.atas-kiri {
  border-top-left-radius: 20px;
}
.atas-kanan {
  border-top-right-radius: 20px;
}
.bawah-kiri {
  border-bottom-left-radius: 20px;
}
.bawah-kanan {
  border-bottom-right-radius: 20px;
}


.navbaru {
    padding: 1px;
    background: #eee center center;
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
  padding: 0px 0;
 
   padding-right: 3%;
   padding-left: 3%;
   padding-top: 2%;
   padding-bottom: 2%;
   text-align: center;
   
}

.navbaru i {

  font-size:30px;
  color:primary;
   
}

.navbaru input {
  margin-top: 2%;
   
}

.navbaru2 {
   
    
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

#search {
  margin-top: 3.5%;
}
.inputcari{
  width:70%
}

@media only screen and (max-width: 600px) {
  #search {
  margin-top: 11%;
}
.navbaru i {

  font-size:22px;
  color:primary;
   
}

.logout-mobile{
  margin-top: 50%;
}
.inputcari{
  width:60%;
}
.navbaru input {
  margin-top: 12%;
   
}

}


</style>
</head>

<body>
<!-- JUMBOTRON  -->
   <?php 
     $mitra= $_SESSION['admin_mitra']['idadmin'];

            $datamitra2=$koneksi->query("SELECT admin_mitra.namamitra,saldo.debit,saldo.credit,(sum(saldo.debit) - sum(saldo.credit)) AS sisa FROM saldo inner join admin_mitra ON 
            saldo.idadmin=admin_mitra.idadmin
            WHERE admin_mitra.idadmin = 233
            AND saldo.transaksi NOT LIKE '%Fee Order Agen%'  
                                    AND saldo.transaksi NOT LIKE '%Fee Order Reseller%'
                                    AND saldo.transaksi NOT LIKE '%Fee Order marketer%'
             GROUP BY admin_mitra.idadmin order by sisa desc ");
        
            while($tampilkan2=$datamitra2->fetch_assoc()){
                                $totalsaldo2=$tampilkan2['sisa']; 

                              }

     $ambil=$koneksi->query("SELECT (sum(saldo.debit) - sum(saldo.credit)) AS selisih FROM admin_mitra inner join saldo on admin_mitra.idadmin=saldo.idadmin where saldo.idadmin= '$mitra'"); 
     while($data=$ambil->fetch_assoc()){
      $selisih = $data['selisih'];
      ?>
        <?php } ?>
<div class="container ">    

<div class="container row fixed-top navbaru" >

  
  <div class="col-6">
    <div class="input-group mb-3">
      <form action="store2.php" method="get">
      <input type="text" placeholder="Search.." name="namaproduk" id="namaproduk" class="inputcari" style="">
      <button class="btn" name="cari"><i class="fa fa-search" style="color: #23527c;"></i></button>
    </form>
      
    </div>
    
  </div>
  <div class="col-2" >
    <div class="input-group mb-3">
      <br>
<?php
$tglsekarang = date('Y-m-d');
date_default_timezone_set('Asia/Jakarta');
$waktusekarang=date('H:i:s');
$ambil1=$koneksi->query("SELECT * FROM keranjang order by idkeranjang desc"); 
while($stock=$ambil1->fetch_assoc()){ 
    $tgl=$stock['tgl'];
    $waktu=$stock['waktu'];

$koneksi->query("UPDATE `keranjang` SET status='Expired' WHERE TIMEDIFF('$tglsekarang $waktusekarang',concat(tgl,' ',waktu))>'24:00:00' 
ORDER BY `keranjang`.`idkeranjang`  DESC");
$koneksi->query("DELETE FROM `keranjang` WHERE jmlh=0");
} 
?>        
    <a href="view_cart">
      <!-- <a href="#"> -->
      <i class="fa fa-shopping-cart" ></i>
          <?php 
      $idmitra=$_SESSION["admin_mitra"]["idadmin"];
        $keranjang=0;

      $sikat=$koneksi->query("SELECT count(keranjang.jmlh) as jmlh 
            FROM keranjang 
            JOIN produk on produk.idproduk = keranjang.idproduk

            WHERE keranjang.idmitra='$idmitra' 
            and keranjang.jmlh>0
            and produk.idkategori<>11"); 
      while($count=$sikat->fetch_assoc()){
           $keranjang+=$count['jmlh'];
}
     ?>
    <?php if ($keranjang==0) {
      
    }else{?>
  <span class="position-absolute top-0 start-100 translate-middle badge bg-danger" style="border-radius: .25rem;padding: 3px 3px 3px 3px;">

  <?php echo $keranjang; ?>
  </span>
    <?php } ?>
    </a>
    </div>
  </div>


  <div class="col-2">
    <div class="input-group mb-3">
      <br>
    <a href="pesan"><i class="fa fa-envelope" ></i>
                <?php 
       $mitra= $_SESSION['admin_mitra']['idadmin'];

       $ambiljmlh=$koneksi->query("SELECT COUNT(*) as jmlh FROM tinbox where idadmin='$mitra' and status='Belum Dibaca'"); 
       $datajmlh=$ambiljmlh->fetch_assoc();
    ?>

    <?php if ($datajmlh['jmlh']==0) {
      
    }else{?>
  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

  <?php echo $datajmlh['jmlh']; ?> 
  </span>
    <?php } ?>
    </a>
    <br>
    </div>
  </div>

      <div class="col-2">
    <div class="input-group mb-3">
      <br>
    <a href="listnewpo"><i class="fa fa-bell" ></i>
                <?php 
       $mitra= $_SESSION['admin_mitra']['idadmin'];

       $ambilpo=$koneksi->query("SELECT COUNT(DISTINCT pomitra.invoice) jmlh FROM `pomitra` inner join poproduk on pomitra.idpoproduk=poproduk.idpoproduk LEFT JOIN mitraagen on pomitra.idmitraagen=mitraagen.idmitraagen LEFT JOIN mitrareseller on pomitra.idmitrareseller=mitrareseller.idmitrareseller LEFT JOIN mitramarketer on pomitra.idmitramarketer=mitramarketer.idmitramarketer 
        where (mitraagen.idadmin='$mitra' or mitrareseller.idadmin='$mitra' or mitramarketer.idadmin='$mitra') and pomitra.status='Belum Acc DB'"); 
       $datapo=$ambilpo->fetch_assoc();
    ?>

    <?php if ($datapo['jmlh']==0) {
      
    }else{?>
  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

  <?php echo $datapo['jmlh']; ?> 
  </span>
    <?php } ?>
    </a>
    <br>
    </div>
  </div>
  
</div>
<div class="container row fixed-top navbaru2" >

  
  <div class="col-8"></div>
  <div class="col-2" >
    <div class="input-group mb-3">
      <br>

    <a href="view_cart"></a>
  </div>
  </div>
  <div class="col-2">
    <div class="input-group mb-3">
  
  </div>
  </div>
  
</div>

<br>
<br>
<br>
<br>

<div class="jumbotron" >
     
      
  <div class="row ">
      

  <div class="col-2">
    <img src="foto/<?php echo $mode['foto'] ?>" style="width:55px;height:55px;border-radius: 50%;">
  </div>
  <div class="col-6"><b><?php echo $mode['namamitra'] ?></b><br>
<b style="font-size: 13px"><?php echo "(Distributor_".$mode['idadmin'].')';?></b><br>

  </div>

  <div class="col-1"> 
      
  </div>
  <div class="col-2">
    <a href="logout.php"><i class="fa fa-power-off logout-mobile" style="font-size:36px;color:primary;"></i></a>
  </div>
  <div class="col-1"> 
      
  </div>
</div>
</div>

<div class="col" style="background: #eee; border-radius: 6px;margin-top: -2%; ">

<div class="d-flex justify-content-center">
  <div class="col-6" style="height: 50px;">
    <div style="margin-top: 5px">
    <b style="margin-left: 19px;font-size: 15px; ">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet" viewBox="0 0 16 16">
      <path d="M0 3a2 2 0 0 1 2-2h13.5a.5.5 0 0 1 0 1H15v2a1 1 0 0 1 1 1v8.5a1.5 1.5 0 0 1-1.5 1.5h-12A2.5 2.5 0 0 1 0 12.5V3zm1 1.732V12.5A1.5 1.5 0 0 0 2.5 14h12a.5.5 0 0 0 .5-.5V5H2a1.99 1.99 0 0 1-1-.268zM1 3a1 1 0 0 0 1 1h12V2H2a1 1 0 0 0-1 1z"/>
      </svg> 
      Saldo</b>
    <br>
    <a href="saldo" style="margin-left: 19px;">
    <?php if ($idadmin==233): ?>
      Rp. <?= number_format($totalsaldo2); ?> ,- 
      <?php else: ?>
      Rp. <?= number_format($selisih); ?> ,- 
    <?php endif ?>

    </a>
    </div>
  </div>
  <div class="col-1" style="background: white; max-width: 0.1% !important;">
  </div>  
  <div class="col-6">
    <div style="margin-top: 5px">    
      <b style="margin-left: 19px;font-size: 15px;">
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ticket-perforated" viewBox="0 0 16 16">
  <path d="M4 4.85v.9h1v-.9H4Zm7 0v.9h1v-.9h-1Zm-7 1.8v.9h1v-.9H4Zm7 0v.9h1v-.9h-1Zm-7 1.8v.9h1v-.9H4Zm7 0v.9h1v-.9h-1Zm-7 1.8v.9h1v-.9H4Zm7 0v.9h1v-.9h-1Z"/>
  <path d="M1.5 3A1.5 1.5 0 0 0 0 4.5V6a.5.5 0 0 0 .5.5 1.5 1.5 0 1 1 0 3 .5.5 0 0 0-.5.5v1.5A1.5 1.5 0 0 0 1.5 13h13a1.5 1.5 0 0 0 1.5-1.5V10a.5.5 0 0 0-.5-.5 1.5 1.5 0 0 1 0-3A.5.5 0 0 0 16 6V4.5A1.5 1.5 0 0 0 14.5 3h-13ZM1 4.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v1.05a2.5 2.5 0 0 0 0 4.9v1.05a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-1.05a2.5 2.5 0 0 0 0-4.9V4.5Z"/>
</svg>

        Voucher
      </b>
      <br>
<?php 
     $ambil_voucher=$koneksi->query("SELECT (sum(voucher.debit) - sum(voucher.credit)) AS selisih 
                                    FROM voucher 
                                    inner join admin_mitra on admin_mitra.idadmin=voucher.idadmin 
                                    where voucher.idadmin= '$mitra'

                                    "); 
     while($data_voucher=$ambil_voucher->fetch_assoc()){
      $selisih_voucher = $data_voucher['selisih'];
    }
 ?>
      <a href="voucher" style="margin-left: 19px;">

        Rp. <?= number_format($selisih_voucher); ?> ,- 


      </a>
    </div>
  </div>

</div>


</div>

<br>
<br>
 <!-- END JUMBOTRON  -->

 
 <!--================Slider Area  =================-->
   
  <div id="myCarousel" class="carousel slide" data-ride="carousel" style="margin-top:-20px">
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
      <div class="item active">
        <img src="slider/<?php echo $foto1['foto'] ?>" alt="slider1" style="width:100%;">
      </div>
        <?php 
     $ambil3=$koneksi->query("SELECT foto FROM slider where id=2"); 
     $foto2=$ambil3->fetch_assoc();
      ?>
      <div class="item">
        <img src="slider/<?php echo $foto2['foto'] ?>" alt="slider2" style="width:100%;">
      </div>
     <?php 
     $ambil4=$koneksi->query("SELECT foto FROM slider where id=3"); 
     $foto3=$ambil4->fetch_assoc();
      ?>
      <div class="item">
        <img src="slider/<?php echo $foto3['foto'] ?>" alt="slider3" style="width:100%;">
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
<!--================Slider Area End =================-->
<br>



<!--================Icon Area =================-->
<div class="mb-4">
        <a href="https://wnj.web.id/distributor/formpoku?id=228">
            <img src="img/benner-legging.png"  style="width:100%;height: auto">
        </a>    
</div>
<div class="row ">
    <div class="col-12"><a href="listnewpo">
        <img src="img/preorder.png" class="d-block w-100"></a>
    </div> 
</div>
<br>

<!--<br>-->


<!--  <div class="row " style="margin-top:-25px">-->
 
<!--  <div class="col-3 col-lg-2"><a href="dataagen">-->
<!--        <img src="img/submitra.png" class="d-block w-100"><p class="text-center">SubDB</p></a>-->
<!--    </div> -->
    
<!--    <div class="col-3 col-lg-2"><a href="#">-->
<!--        <img src="img/mystock.png" class="d-block w-100" > <p class="text-center">My Stock</p></a>-->
<!--    </div>-->

<!--    <div class="col-3 col-lg-2"><a href="https://t.me/joinchat/HI-N0pw7f3rlIxKe" target="blank()">-->
<!--        <img src="img/nabar.png" class="d-block w-100"> <p class="text-center">Nyabar</p></a>-->
<!--    </div>-->
 
     <!--<div class="col-3 col-lg-2"><a href="keep-produk">
<!--        <img src="img/keep.png" class="d-block w-100" > <p class="text-center">Keep</p></a>-->
<!--    </div>-->

<!--    <div class="col-3 col-lg-2"><a href="return">-->
<!--        <img src="img/support_ticket.png" class="d-block w-100" > <p class="text-center">Support Ticket</p></a>-->
<!--    </div>-->
     
<!--     <div class="col-3 col-lg-2"><a href="dataads">-->
<!--        <img src="img/ads.png" class="d-block w-100" > <p class="text-center">AdsMitra</p></a>-->
<!--    </div> -->
    
 
<!--    <div class="col-3 col-lg-2"><a href="https://wnj.web.id/inkubator/katalog">-->
<!--        <img src="img/katalog.png" class="d-block w-100" > <p class="text-center">Katalog</p></a>-->
<!--    </div>-->
<!--      <div class="col-3 col-lg-2"><a href="pricelist">-->
<!--        <img src="img/pricelist.png" class="d-block w-100" > <p class="text-center">Pricelist</p></a>-->
<!--    </div>-->
<!--    <div class="col-3 col-lg-2"><a href="resi">-->
<!--        <img src="img/resi.png" class="d-block w-100" > <p class="text-center">Resi</p></a>-->
<!--    </div>-->
   
    
   
<!--  </div>-->
  
  

<!--================ End Icon Area =================-->
<br>
<!--================ Eks Area =================-->

  <center>
  <?php include "../inkubator/ekspedisi.php"; ?>
  </center>

<!--================ END Eks Area =================-->

<!--================ MAP MITRA =================-->



<!--================ END MAP MITRA =================-->
<br>
<br>
<?php
$tglsekarang=date('Y');

?>
<c>Copyright &copy; <?php echo $tglsekarang; ?><br>
by Wanoja IT Support</c>
<br>
<br>
<br>
<br>


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

<?php include "menubawah.php"; ?>
</body>



</html>
