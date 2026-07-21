<?php 
    include 'koneksi.php'; 


    $idmitra  = $_SESSION["idmitramarketer"];
    $ambil    = $koneksi->query("SELECT COUNT(*) as jumlah FROM ordermitra  where ordermitra.idmitra='$idmitra' and ordermitra.status='Pending' group by invoice ");
    $data     = $ambil->fetch_assoc();
    $count    = $data['jumlah'];
?>
<script src="https://kit.fontawesome.com/b812ba9ae3.js" crossorigin="anonymous"></script>
<div class="container row fixed-bottom jumbotron2">
    <div class="col-4"><a href="formpembayaran"><p><i class="fas fa-check-circle"></i> Konfirmasi</p></a></div>
    <div class="col-4" ><a href="index2"><p><img src="img/logo-simbol-putih.png" style="width:30px"></p></a></div>
    <div class="col-4"><a href="transaksi"><p class="text-center"><i class="fas fa-shopping-bag"></i> Transaksi</p></a></div>
</div>
<style>
/* Place the navbar at the bottom of the page, and make it stick */

.jumbotron2 {
   
     background: linear-gradient(to bottom, #488cf6 0%, #488cf6 10%, #488cf6 60%,  #ffffff 100%);
    center: center;
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

</style>

<style>
/* Place the navbar at the bottom of the page, and make it stick */

.jumbotron3 {
   
    background: linear-gradient(to bottom, #488cf6 0%, #488cf6 10%, #488cf6 60%,  #ffffff 100%);
    center center;
    margin: auto;
    text-align: center;
    overflow: hidden;
    
}

.jumbotron3 p {
  
    text-decoration: none;
    padding: 10px 0;
    font-size: 20px;
    color: #f2f2f2;
    text-align: center;
   
}

</style>