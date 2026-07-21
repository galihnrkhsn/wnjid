<?php 
session_start();

include 'koneksi.php'; 

if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

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
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	</head>
	<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- END NAVBAR -->

    <!-- MAIN SECTION -->
    <div class="container">
      <h2 class="mb-4 mt-12">Support Ticket</h2>
      <form method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="select1">Masalah:</label>
          <select class="form-control" name="masalah" required>
            <option disabled selected>Pilih Masalah</option>
            <option>Retur & Refund</option>
            <option>Barang Tidak Sampai</option>
            <option>Masalah Website</option>
            <option>Service CS</option>
            <option>Lain-lain</option>
          </select>
        </div>
        <div class="form-group">
          <label for="select2">Pilih CS</label>
          <select class="form-control" name="namacs" required>
            <option disabled selected>Pilih CS</option>
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
          <label for="text">Note</label>
          <textarea name="note" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary" name="tambah">Simpan</button>
        <a class="btn btn-default" href='return'>Batal</a>
      </form>
    </div>
    <!-- END MAIN SECTION -->
  <?php
    if(isset($_POST["tambah"])){
     include 'koneksi.php';
    // Ambil Data yang Dikirim dari Form
    $idadmin= $_SESSION["admin_mitra"]["idadmin"];
    $namacs=$_POST['namacs'];
    $masalah=$_POST['masalah'];
    $note=addslashes(htmlspecialchars($_POST['note']));
    $status=$_POST['status'];
      
      $koneksi->query("INSERT INTO `support_ticket` (`idsupport`,`idadmin`, `masalah`, `namacs`, `tgl`,`note`, `status`) VALUES 
      (null,'$idadmin','$masalah','$namacs',NOW(),'$note','Ticket Diajukan')");    
      

      echo "<script>alert('Terimakasih .. Data sudah berhasil terkirim');</script>";
      echo "<script>location='return'</script>";


    } 
  ?>
</body>
</html>  