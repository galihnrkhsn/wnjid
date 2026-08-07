<?php 
error_reporting (0);
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}
$id=$_GET['idstock'];
$idd=$_GET['namaartikel'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>
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
</head>
<body>
<?php 
include '../header.php';  ?>

<?php $ambil=$koneksi->query("SELECT * FROM stock_pusat WHERE idstock='$_GET[id]' AND namaartikel='$_GET[idd]'");
$tampilkan=$ambil->fetch_assoc();
?>


<div class="row">
    <center>
        <h4>Detail Stok <?php echo $tampilkan['namaartikel']; ?></h4>

<textarea readonly style="resize:none;width:300px;height:250px;"><?php echo $tampilkan['keterangan']; ?></textarea>


    </center>
  
</div>



</body>
</html>

