 <?php 


include 'koneksi.php'; 


$idmitra=$_SESSION["mitraagen"]["idmitraagen"];
$ambil=$koneksi->query("SELECT COUNT(*) as jumlah FROM orderagen  where orderagen.idmitraagen='$idmitra' and orderagen.status='Pending' group by invoice ");
        $data=$ambil->fetch_assoc();
$count=$data['jumlah'];        
?>

<div class="container row fixed-bottom jumbotron2">
  <div class="col-4"><a href="formpembayarandb.php"><p>Konfirmasi</p></a></div>
  <div class="col-4" ><a href="storedb.php"><p><img src="img/logo-simbol-putih.png" style="width:30px"></p></a></div>
  <div class="col-4"><a href="transaksidb.php"><p class="text-center">Transaksi</p></a></div>
</div>	

<!--<div class="container row fixed-bottom jumbotron3">
  <div class="col-4"></div>
  <div class="col-4"></div>
  <div class="col-4"></div>
</div>	
-->



<style>
/* Place the navbar at the bottom of the page, and make it stick */

.jumbotron2 {
   
     background: linear-gradient(to bottom, #488cf6 0%, #488cf6 10%, #488cf6 60%,  #ffffff 100%);
   center center;
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