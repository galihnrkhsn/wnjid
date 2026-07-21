<?php 
session_start();

include 'koneksi.php'; 


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
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| Wanoja </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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

<?php include '../header.php';  ?>

<div class="row">
  <h5>pesanan hari ini </h5>
  <table>
      <tr>
          <td>Namaagen</td>
          <td>Notelp</td>
          <td>namaproduk</td>
          <td>size</td>
          <td>qty</td>
      </tr>
      <?php 
  $kodemitra=$_SESSION['admin_mitra']['kodemitra'];
  $tgl=date("Y-m-d");
  $ambil=$koneksi->query("SELECT * FROM form_pesanan WHERE kodemitra='$kodemitra' AND tgl='$tgl'");
  while($tampilkan=$ambil->fetch_assoc()){
  ?>
      <tr>
          <td><?php echo $tampilkan['namaagen']; ?></td>
          <td><?php echo $tampilkan['notelp']; ?></td>
          <td><?php echo $tampilkan['namaproduk']; ?></td>
          <td><?php echo $tampilkan['size']; ?></td>
          <td><?php echo $tampilkan['pesanan']; ?></td>
      </tr>
      <?php } ?>
  </table>
</div>

<div class="footer">
  <p>Footer</p>
</div>

</body>
</html>

