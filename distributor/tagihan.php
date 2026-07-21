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
    
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ </title>

          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/500/fabric.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

  
  
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

</style>

</head>
<body>
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="index.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>TAGIHAN</p></div>
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
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#home">Belum Lunas</a></li>
      <li><a data-toggle="tab" href="#menu1">Lunas</a></li>
    </ul>  
    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">             
               <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="tbpesan">
                <thead>
                  <tr>
                      <th>Invoice</th>
                      <th>Tanggal Tagihan</th>
                      <th>Jatuh Tempo</th>
                      <th>Total</th>
                      <th>DP</th>
                      <th>Sisa</th>                    
                      <!-- <th><i class="fa fa-cog" aria-hidden="true"></i></th>  -->
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                  $idadmin=$_SESSION["admin_mitra"]["idadmin"];				                  
                  $ambil=$koneksi->query("SELECT * FROM tagihan where idadmin='$idadmin' "); 
                  while($data=$ambil->fetch_assoc()){
                    ?>
                    <tr>
                        <td><?php echo $data['invoice'];?></td>
                        <td><?php echo $data['tgltagihan'];?></td>
                        <td><?php echo $data['jatuhtempo'];?></td>
                        <td><?php echo $data['totaltagihan'];?></td>
                        <td><?php echo $data['dpmasuk'];?></td>
                        <td></td>
                  </tr>
  
                  
                  <?php } ?>
                  </tbody>
              </table>
              </div>

      </div>
      <div id="menu1" class="tab-pane fade">
               <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="tb_lunas">
                <thead>
                  <tr>
                      <th>Invoice</th>
                      <th>Tanggal Tagihan</th>
                      <th>Jatuh Tempo</th>
                      <th>Total</th>
                      <th>DP</th>
                      <th>Sisa</th>                    
                      <!-- <th><i class="fa fa-cog" aria-hidden="true"></i></th>  -->
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                  $idadmin=$_SESSION["admin_mitra"]["idadmin"];                         
                  $ambil=$koneksi->query("SELECT * FROM tagihan where idadmin='$idadmin' "); 
                  while($data=$ambil->fetch_assoc()){
                    ?>
                    <tr>
                        <td><?php echo $data['invoice'];?></td>
                        <td><?php echo $data['tgltagihan'];?></td>
                        <td><?php echo $data['jatuhtempo'];?></td>
                        <td><?php echo $data['totaltagihan'];?></td>
                        <td><?php echo $data['dpmasuk'];?></td>
                        <td></td>
                  </tr>
  
                  
                  <?php } ?>
                  </tbody>
              </table>
              </div>        
      </div>


	
		</div>


      

</div>
  <?php include "menubawah.php"; ?>
<?php include "settingdatatables.php"; ?>
</body>

</html>

