<?php 
error_reporting (0);
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}
$id=$_GET['idadmin'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| Wanoja </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
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
</style>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>

<table id="myJudul" class="w3-table-all w3-centered">

    <tr>
      <th style="width:5%;"><a class="glyphicon glyphicon-chevron-left" href='profile.php')></a></th>
      <th style="width:90%;">Profile</th>
      <th style="width:5%;"></th>
    </tr>
   
  </table>

<?php $ambil=$koneksi->query("SELECT * FROM admin_mitra WHERE idadmin='$_GET[id]'");
$tampilkan=$ambil->fetch_assoc();
?>


<div class="row">
    <center>
        <h2>Edit Profile</h2>

<form method="POST">
  <label for="namamitra" >Nama:</label><br>
  <input type="text" id="namamitra" name="namamitra" value="<?php echo $tampilkan['namamitra']; ?>"><br>
  <label for="email">Email:</label><br>
  <input type="text" id="email" name="email" value="<?php echo $tampilkan['email']; ?>"><br>
    <label for="whatsapp">Whatsapp:</label><br>
  <input type="text" id="whatsapp" name="whatsapp" value="<?php echo $tampilkan['whatsapp']; ?>"><br>
    <label for="telegram">Telegram:</label><br>
  <input type="text" id="telegram" name="telegram" value="<?php echo $tampilkan['telegram']; ?>"><br>
    <label for="facebook">Facebook:</label><br>
  <input type="text" id="facebook" name="facebook" value="<?php echo $tampilkan['facebook']; ?>"><br>
    <label for="instagram">Instagram:</label><br>
  <input type="text" id="instagram" name="instagram" value="<?php echo $tampilkan['instagram']; ?>"><br>
    <label for="alamat">Alamat:</label><br>
  <textarea name="alamat" value="<?php echo $tampilkan['alamat']; ?>"><?php echo $tampilkan['alamat']; ?></textarea><br>
 <button class="btn btn-primary" name="edit">ubah</button>
</form>
<?php
if(isset($_POST["edit"])){
	
	$koneksi->query("UPDATE admin_mitra SET email='$_POST[email]',namamitra='$_POST[namamitra]',whatsapp='$_POST[whatsapp]',telegram='$_POST[telegram]',facebook='$_POST[facebook]',instagram='$_POST[instagram]',alamat='$_POST[alamat]' WHERE idadmin='$_GET[id]'");

echo "<script>alert('data berhasil di ubah');</script>";
echo "<script>location='logout.php';</script>";
}

?>
<p><i>Email Dipergunakan untuk login</i>.</p>

    </center>
  
</div>



</body>
</html>

