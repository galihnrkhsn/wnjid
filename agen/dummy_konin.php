<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

 $idpoproduk = 97;
 //  $invoice = $_GET['invoice'];
  $idmitraagen=$_SESSION["mitraagen"]["idmitraagen"];
 
  ?> 
  
<html lang="en">
<head>
<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title></title>

		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
        	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.js"></script>
	<script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.dataTables.js"></script>
	<link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/dataTables.bootstrap.css">
		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| Wanoja </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>


  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">  

</head>
<body>

<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="listpreorder.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>isi biodata penerima Konin WNJ</p></div>
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
  
  padding: 15px 0;
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

 
  <div class="container panel panel-default">
     
 
            <div class="panel-body">
            <div class="row">
            <div class="col-md-6">
                                
		
		
		<div style="padding: 0 15px;">
            	
		<form method="post">
      
      <div class="form-group">
          <label>Nama Pengirim</label>
    <input type="text" class="form-control" name="namapengirim" value="<?= $_SESSION['mitraagen']['namaagen'] ?>" required>
    </div>
    
     <div class="form-group">
          <label>Telepon Pengirim</label>
    <input type="text" class="form-control" name="tlppengirim" value="<?= $_SESSION['mitraagen']['whatsapp'] ?>" required>
    </div>    
    
    <hr>
    
      <div class="form-group">
          <label>Nama Penerima / Keluarga</label>
    <input type="text" class="form-control" name="namapenerima" required>
    </div>
    
    <div class="form-group">
    <label>Telepon Penerima</label>
    <input type="text" class="form-control" name="tlppenerima" required>
    </div>
    
        <div class="form-group">
    <label>Alamat Penerima</label>
    <textarea class="form-control" name="alamatpenerima" required></textarea>
    </div>
    
            <div class="form-group">
    <label>Keterangan</label>
    <textarea class="form-control" name="keterangan"> - </textarea>
    </div>
    
             <div class="form-group">
          <label>Ekspedisi</label>  
          <select class="form-control" name="ekspedisi" required>
          	<option value="">PILIH EKSPEDISI</option>
              <option value="jne oke">JNE OKE</option>
              <option value="jne reg">JNE REG</option>
              <option value="jtr">JTR</option>
             <option value="jne yes">JNE YES</option>
              <option value="wahana">WAHANA</option>
              <option value="sicepat">SICEPAT</option>
              <option value="lion">LION PARCEL</option>
              <option value="j&t">J&T</option>
              <option value="tiki">TIKI</option>
              <option value="pos kilat">POS KILAT</option>
              <option value="pos ekonomi jumbo">POS EKONOMI JUMBO</option>
              <option value="gosend">Gosend</option>              
          </select>      
            </div>
    
   <center><button type="submit" class="btn btn-primary" name="kirim">Kirim</button></center>
  </form>
</body>

<?php
include "koneksi.php";

	 
	if(isset($_POST['kirim'])){
	  $namapengirim=$_POST["namapengirim"];
	  $tlppengirim=$_POST["tlppengirim"];
	  $namapenerima=$_POST["namapenerima"];
	  $tlppenerima=$_POST["tlppenerima"];
	  $alamatpenerima=$_POST["alamatpenerima"];
	  $keterangan=$_POST["keterangan"];
	  $ekspedisi=$_POST["ekspedisi"];
	 
	  $sql = mysqli_query($koneksi, "SELECT iddropship FROM podropship order by iddropship desc limit 1");
			$data = mysqli_fetch_array($sql);
			$no=$data['iddropship'];
			$ab=1;
			$nobaru=$no+$ab;

	  $query = "INSERT into podropship (iddropship,idadmin,idmitraagen,idmitrareseller,idmitramarketer,idpoproduk,invoice,namapengirim,tlppengirim,namapenerima,tlppenerima,alamatpenerima,keterangan,ekspedisi) values
	  (null,'0','$idmitraagen','0','0','$idpoproduk','KN-A$idmitraagen-$nobaru','$namapengirim','$tlppengirim','$namapenerima','$tlppenerima','$alamatpenerima','$keterangan','$ekspedisi')";
    $sql = mysqli_query( $koneksi, $query);  
    echo "<script>alert('Silahkan isi formulir PO Konin');</script>";
    echo "<script>location='formpoori.php?id=$idpoproduk&invoice=KN-A$idmitraagen-$nobaru'</script>";
	  } 
?>	
</html>