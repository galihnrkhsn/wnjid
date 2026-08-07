<?php 
error_reporting (0);
session_start();

include 'koneksi.php'; 
include 'floatingbutton.php';


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>



<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	
</head>
<body>

<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="index.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>ADSENSE MITRA</p></div>
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
    
    <center>
       
        </center><br>
      <!--   <pre>
<form method="get">
<label>Cari Nama Produk</label>

<input type="text" name="namaproduk">  <button type="submit" class="btn btn-primary" name="cari">Cari</button>  <button type="submit" class="btn btn-primary" name="tampil">Tampil Semua</button></form></pre>-->
        <a class="btn btn-default" href="inputads.php">Ads Baru</a><br><br>
        <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="tb_ads">
    <thead>
      <tr>
          <th width="1">No</th>
          <th>Status</th>
          <th>Tanggal</th>
          <th>Program Ads</th>
          <th>Target Kota</th>
          <th>Nama</th>
          <th>Whatsapp</th>
          

      </tr>
    </thead>
    <tbody>

     <?php
      $idadmin= $_SESSION["admin_mitra"]["idadmin"];
      $ambil=$koneksi->query("SELECT * FROM adsmitra where idmitra='$idadmin' order by tgl "); 
      $no=1;
      while($data=$ambil->fetch_assoc()){
      ?>
        
     <tr>
          <td><?php echo $no++;?></td>
          <td><?php echo $data['status'];?></td>
          <td><?php echo $data['tgl'];?></td>
          <td><?php echo $data['program'];?></td>
          <td><?php echo $data['targetkota'];?></td>
          <td><?php echo $data['nama'];?></td>
          <td><?php echo $data['whatsapp'];?></td>
      </tr>
      <?php } ?>
    </tbody>
</table>    
     
  


  
  
</div>

<div class="container">
 <h4>Program Ads Rekrut Tim Mitra</h4>
<p>
Biaya : 50rb/hari kali 7 hari jadi 350rb.
<br>
Setting Ads seluruhnya dikelola pusat.
<br>
setting konversi ke nomor WA.
<br>
DB tinggal terima WA saja dari calon mitra.
<br>
ads pakai landingpage, fanspage dan IG pusat.
<br>
Lokasi target sesuai pulau DB bersangkutan.
<br>
Persiapan DB hanya nomor WA aktif dan eksekusi trafik yang masuk ke WA supaya bisa closing jadi mitra agen, reseller atau marketer.
<br>
karena mayoritas yang klik adalah cold market jadi perlu edukasi dari basic hingga memahami value produk wanoja.
<br>
nanti dikasih copywriting standar untuk menjawab calon mitra, bisa disesuaikan dengan kebutuhan masing2.
<br>
<br>
program ads kami hanya menggiring calon konsumen untuk masuk ke kontak DB, adapun hasil/closing tergantung eksekusi DB masing2.
<br>
Jika berminat silahkan isi 
<br>
nama: 
<br>
asal kota : 
<br>
nomor WA aktif : 
<br>
<br>
transfer  ke rekening  90012889937 
<br>
a.n. Maria Ulfah Fathimah
<br>
Bank BTPN (kode bank 213).
<br>
Rp.350.000,-
<br>
<br>
Tahapannya:
<br>
1. Waiting List
<br>
2.  Payment 
<br>
3.  Running.
<br>
<br>
ads segera kami running setelah payment. tiap awal pekan, bisa hari senin atau selasa karena ads perlu riset dulu untuk dapat audience yang tepat dan efektif.
<br>
<br>
Barakallahufikunna.
<br>
Wanoja ITSupport
</p>

<br>
<br>
<br>
<br>
<br>
<br>

</div>

<?php include "menubawah.php"; ?>
<?php include "settingdatatables.php"; ?>

</body>
</html>

