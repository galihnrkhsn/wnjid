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
<!DOCTYPE html>
<html lang="en">
<head>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>
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

<div class="row"><center>
    Mitra Agen Anda <hr>
    <table border='1'>
        <tr>
            <td>Mitra Agen</td>
            <td>Email</td>
            <td>Whtatsapp/Telp</td>
            <td>Alamat</td>
            <td>Username</td>
            <td>Password</td>
        </tr>
        <?php 
        $kode=$_SESSION['admin_mitra']['kodemitra'];
        $datamitra=$koneksi->query("SELECT * FROM mitra_agen WHERE kodemitra='$kode'");
        while($tampilkan=$datamitra->fetch_assoc()){
        ?>
        <tr>
            <td><?php echo $tampilkan['namaagen']; ?></td>
            <td><?php echo $tampilkan['email']; ?></td>
            <td><?php echo $tampilkan['notelp']; ?></td>
            <td><?php echo $tampilkan['alamat']; ?></td>
            <td><?php echo $tampilkan['username']; ?></td>
            <td><?php echo $tampilkan['password']; ?></td>
        </tr>
        <?php } ?>
    </table>
  </center>
</div>

<div class="footer">
  <p>Footer</p>
</div>

</body>
</html>

