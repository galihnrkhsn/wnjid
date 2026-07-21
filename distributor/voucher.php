<?php 
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
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| Wanoja </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| Wanoja </title>


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
  <div class="col-8" ><p>voucher</p></div>
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
    <?php 
      $mitra= $_SESSION['admin_mitra']['idadmin'];

      $ambil=$koneksi->query("SELECT (sum(debit) - sum(credit)) AS selisih FROM voucher where idadmin= '$mitra' "); 

      while($data=$ambil->fetch_assoc()){
      ?>

<div class="panel-body">
      <center><h3>Sisa voucher <br>Rp. 
      <?php echo number_format($data['selisih']); ?></h3></center> 
      <br>
      <?php } ?>
    <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="">
    <thead>
      <tr>          <th>Tanggal</th>
          <th>Transaksi</th>
          <th>Masuk</th>
          <th>Keluar</th>
      </tr>
    </thead>
    <tbody>
        <?php 
      $no=1;
      $mitra= $_SESSION['admin_mitra']['idadmin'];
if ($mitra==233) {
      $ambil=$koneksi->query("SELECT * FROM voucher  WHERE idadmin = 233
            AND voucher.transaksi NOT LIKE '%Fee Order Agen%'  
                                    AND voucher.transaksi NOT LIKE '%Fee Order Reseller%'
                                    AND voucher.transaksi NOT LIKE '%Fee Order marketer%'
             order by id_voucher asc"); 
}
else{

      $ambil=$koneksi->query("SELECT * FROM voucher where idadmin= '$mitra' ORDER BY id_voucher asc"); 
}
      while($data=$ambil->fetch_assoc()){
      ?>
      <tr>
          <td><?php echo $data['tgl'];?></td>
          <td><?php echo $data['transaksi'];?></td>
          <td>Rp. <?php echo number_format($data['debit']);?></td>
          <td>Rp. -<?php echo number_format($data['credit']);?></td>
      </tr>
      <?php } ?>
    </tbody>
</table>      
<br>
<br>
<br>
<br>
</div>			
		<?php include 'settingdatatables.php'; ?>