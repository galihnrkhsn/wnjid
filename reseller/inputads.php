<?php 
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

<html lang="en">
<head>
	<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>


<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	</head>
	<body>
		<!-- Membuat Menu Header / Navbar -->
	<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="dataads.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
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


  
  <div class="panel panel-default">
   
            <div class="panel-body">
            <div class="row">
            <div class="col-md-6">


 <form method="post" enctype="multipart/form-data">
      
     
      <div class="form-group">
          <label>Nama</label>
    <input class="form-control" name="nama">
    </div>
    
    <div class="form-group">
          <label>Whatsapp</label>  
          <input class="form-control" name="whatsapp">
    </div>
    
    <div class="form-group">
          <label>Target Kota</label>
    <textarea class="form-control" name="targetkota"></textarea>
    </div>
    
    <div class="form-group">
          <label>Program</label>  
          <select class="form-control" name="program">
              <option>1 Pekan 7x50rb @Rp. 350.000,-</option>
          </select>          
    </div>
  
  <hr>  
   <button type="submit" class="btn btn-primary" name="tambah">Tambah</button>
  <a class="btn btn-default" href='index.php')>Batal</a>
  </form>
  
<?php


 if(isset($_POST["tambah"])){
     include 'koneksi.php';
     
// Ambil Data yang Dikirim dari Form
$idmitra= $_SESSION["admin_mitra"]["idadmin"];
$nama=$_POST['nama'];
$whatsapp=$_POST['whatsapp'];
$targetkota=$_POST['targetkota'];
$program=$_POST['program'];
    
  $query = "INSERT INTO adsmitra (idads,idmitra,tgl,nama,whatsapp,targetkota,program,status) VALUES ('null','$idmitra',NOW(),'$nama','$whatsapp','$targetkota','$program','antrian')";    
  $sql = mysqli_query( $koneksi, $query);
  if($sql){ // Cek jika proses simpan ke database sukses atau tidak
    // Jika Sukses, Lakukan :
    header("location: dataads.php"); // Redirect ke halaman index.php
  }else{
    // Jika Gagal, Lakukan :
    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
    echo "<br><a href='inputads.php'>Kembali Ke Form</a>";
  }
}  
?>  
  
</body>
</html>  