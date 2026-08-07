<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}
?>

<html lang="en">
<head>
    <title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| WNJ.ID </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

<title>Mitra <?php echo $_SESSION['mitraagen']['namamitra']; ?>| WNJ.ID </title>


<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<!–- library jQuery -–>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<!–- bootstrap js -–>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
  padding: 30px;
  text-align: center;
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
  padding: 10px;
  text-align: center;
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

#myJudul {
    
  text-align: center;
  border-collapse: collapse;
  width: 100%;
  font-size: 18px;
  
  
}
#myJudul th  {

  padding: 12px;
  background-color: #f1f1f1;
  font-size: 18px;
}
#myJudul td {
  text-align: center;
  padding: 12px;
 
  
}
</style>

</head>
<body>
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="setting.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>Pilih Mode Default</p></div>
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
     <?php
      $idmitrareseller=$_SESSION["mitraagen"]["idmitrareseller"];
       $ambil=$koneksi->query("SELECT mode FROM mitrareseller where idmitrareseller='$idmitrareseller' "); 
      $mode=$ambil->fetch_assoc();
          ?>
          <font color="grey" size="2">Ready Stock Anda sekarang dalam <?php if($mode['mode']=='store.php'){
                                echo "Mode Hemat";
                                }else {
                                echo "Mode Cantik"; }
                                ?></font>
    <hr> 
    <center> Pilih Mode Ready Stock-mu : <br>
  <form method="post">
  <input type="radio" id="hemat" name="mode" value="store.php">
    <label>Mode Hemat</label><br>
  <input type="radio" id="cantik" name="mode" value="store2.php">
  <label>Mode Cantik</label><br><br>
  <button type="submit" class="btn-primary" name="kirim">Simpan</button>
</form></center> 

<?php
if(isset($_POST['kirim'])){
    $mode=$_POST['mode'];
    $idmitrareseller=$_SESSION["mitraagen"]["idmitrareseller"];
    
    $koneksi->query("UPDATE mitrareseller set mode='$mode' where idmitrareseller='$idmitrareseller' ");
    	echo "<script>alert('Mode Berhasil Di Ubah');</script>";
		    echo "<script>location='ubahmode.php';</script>";
}
?>
    