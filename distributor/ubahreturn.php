<?php 
session_start();

include 'koneksi.php'; 

if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

$idsupport=$_GET["idsupport"];
$ambil=$koneksi->query("SELECT * FROM support_ticket where idsupport='$idsupport'"); 
       $data=$ambil->fetch_assoc();

?>

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
		<!-- Membuat Menu Header / Navbar -->
	<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

   <div class="col-2"><a href="return"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
   <div class="col-8" ><p>Support Ticket</p></div>
   <div class="col-2"></div>
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

 <form method="post" enctype="multipart/form-data">
     
     <div class="form-group">
          <label>Masalah</label>
    <select name="masalah" class="form-control">
      <option selected="<?php echo $data['masalah'] ?>"><?php echo $data["masalah"] ?></option>
      <option disabled>Pilih Masalah</option>
      <option>Retur & Refund</option>
      <option>Barang Tidak Sampai</option>
      <option>Masalah Website</option>
      <option>Service CS</option>
      <option>Lain-lain</option>
    </select>
    </div>
    
    <div class="form-group">
    <label>Nama CS</label>
    <select name="namacs" class="form-control">
      <option selected="<?php echo $data['namacs'] ?>"><?php echo $data["namacs"] ?></option>
      <option disabled>Pilih CS</option>
      <option>Yara</option>
      <option>Intan</option>
      <option>Daniar</option>
      <option>Zahra</option>
      <option>Tya</option>
      <option>IT Support</option>
      <option>Lainnya</option>
    </select>
    </div>
    
    <div class="form-group">
          <label>Note</label>
    <textarea name="note" class="form-control"><?php echo $data["note"]; ?></textarea>
    </div>
  
  <hr>  
   <button type="submit" class="btn btn-primary" name="tambah">Simpan</button>
  <a class="btn btn-default" href='return'>Batal</a>
  </form>
  
  <?php


 if(isset($_POST["tambah"])){
     include 'koneksi.php';
     
// Ambil Data yang Dikirim dari Form
$idadmin= $_SESSION["admin_mitra"]["idadmin"];
$namacs=$_POST['namacs'];
$masalah=$_POST['masalah'];
$note=$_POST['note'];
   
  $koneksi->query("UPDATE support_ticket SET namacs='$namacs',masalah='$masalah',note='$note' WHERE idsupport='$idsupport'");    
  

  echo "<script>alert('Terimakasih .. Data sudah berhasil tersimpan');</script>";
  echo "<script>location='return'</script>";


} 
?>
  
</body>
</html>  