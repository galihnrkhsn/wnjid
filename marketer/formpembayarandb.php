<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}


$idmitra=$_SESSION["mitraagen"]["idmitramarketer"];
$idadmin=$_SESSION["mitraagen"]["idadmin"];


?>
  
<html lang="en">
<head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		
<style>

body {
  background: #F1F3FA;
}

/* Profile container */
.profile {
  margin: 20px 0;
}

/* Profile sidebar */
.profile-sidebar {
  padding: 20px 0 10px 0;
  background: #fff;
}

.profile-userpic img {
  float: none;
  margin: 0 auto;
  width: 50%;
  height: 50%;
  -webkit-border-radius: 50% !important;
  -moz-border-radius: 50% !important;
  border-radius: 50% !important;
}

.profile-usertitle {
  text-align: center;
  margin-top: 20px;
}

.profile-usertitle-name {
  color: #5a7391;
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 7px;
}

.profile-usertitle-job {
  text-transform: uppercase;
  color: #5b9bd1;
  font-size: 12px;
  font-weight: 600;
  margin-bottom: 15px;
}

.profile-userbuttons {
  text-align: center;
  margin-top: 10px;
}

.profile-userbuttons .btn {
  text-transform: uppercase;
  font-size: 11px;
  font-weight: 600;
  padding: 6px 15px;
  margin-right: 5px;
}

.profile-userbuttons .btn:last-child {
  margin-right: 0px;
}
    
.profile-usermenu {
  margin-top: 30px;
}

.profile-usermenu ul li {
  border-bottom: 1px solid #f0f4f7;
}

.profile-usermenu ul li:last-child {
  border-bottom: none;
}

.profile-usermenu ul li a {
  color: #93a3b5;
  font-size: 14px;
  font-weight: 400;
}

.profile-usermenu ul li a i {
  margin-right: 8px;
  font-size: 14px;
}

.profile-usermenu ul li a:hover {
  background-color: #fafcfd;
  color: #5b9bd1;
}

.profile-usermenu ul li.active {
  border-bottom: none;
}

.profile-usermenu ul li.active a {
  color: #5b9bd1;
  background-color: #f6f9fb;
  border-left: 2px solid #5b9bd1;
  margin-left: -2px;
}

/* Profile Content */
.profile-content {
  padding: 20px;
  background: #fff;
  min-height: 460px;
}

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
img {
  border-radius: 50%;
}


.link:hover{
	color: blue;
}
.link:link{
	color: white;
}
.link:active{
	color: white;
}
.link:visited{
    color: white;
	background: white;
}
	#myJudul {
    
  text-align: center;
  border-collapse: collapse;
  width: 100%;
  font-size: 18px;
  
  
}
#myJudul th  {
  text-align: center;
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
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="transaksidb.php"><span class="glyphicon glyphicon-chevron-left"</span></a></div>
  <div class="col-8" ><p>Konfirmasi Pembayaran</p></div>
  <div class="col-2"></div>
</div>
<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #eee  url("jumbotron-bg.png") center center;
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

.navbaru p {
  
  padding: 10px 0;
  font-size: 20px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru span {
  
  padding: 5px 0;
  font-size: 30px;
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
               
  <h4><center><?php echo $_SESSION["mitraagen"]["namaagen"]; ?> (Cust ID : <?php echo $_SESSION["mitraagen"]["idmitramarketer"]; ?> )</center></h4><br>               
  
  <div class="container">               
  <form method="post">
      
      <div class="form-group">
      <label>Invoice</label>
    
        <select name="invoice" class="form-control">
        <?php $total=0; $grandtotal=$_GET["total"] ; $invoice=$_GET["id"]; //$ambil=$koneksi->query("SELECT ordermitra.invoice,orderpengiriman.total FROM orderpengiriman INNER JOIN ordermitra on ordermitra.invoice=orderpengiriman.invoice where ordermitra.idmitra='$idmitra' and ordermitra.status='Pending' group by invoice order by idorder desc ");
       // while($data=$ambil->fetch_assoc()){
            //$total = $total+$data['subtotal'];
        ?>
        <option value="<?php echo $invoice; ?>"><?php echo $invoice; ?> | Rp. <?php echo number_format($grandtotal); ?></option>
        <?php //} ?>
    </select><br><br>
    </div>
     
       <?php  $idmitramarketer=$_SESSION["mitraagen"]["idmitramarketer"];				
                  $ambil=$koneksi->query("SELECT count(*) as bank FROM rekeningku where idmitramarketer='$idmitramarketer'"); 
                  $bank=$ambil->fetch_assoc();
                if ($bank['bank']==0){
                echo "<label>Nama Bank Pengirim</label><div class='form-group'><input type='text' class='form-control' name='bankpengirim' required></div>";
                }else{
                  echo  "<div class='form-group'>
          <label>Nama Bank Pengirim</label>
          <select name='bankpengirim' class='form-control'>";
                  $ambil=$koneksi->query("SELECT * FROM rekeningku where idmitramarketer='$idmitramarketer'"); 
                  while($distributor=$ambil->fetch_assoc()){
                      ?>
                  <option value="<?php echo $distributor['bank']?>"><?php echo $distributor['bank'] ?></option>
                     <?php }?>
           </select><br></div>
             <?php   }
                ?>
    
    
        <?php  $idmitramarketer=$_SESSION["mitraagen"]["idmitramarketer"];				
                  $ambil=$koneksi->query("SELECT count(*) as bank FROM rekeningku where idmitramarketer='$idmitramarketer'"); 
                  $bank=$ambil->fetch_assoc();
                if ($bank['bank']==0){
                echo "<div class='form-group'><label>Nama / Nomor Rekening Pengirim</label><input type='text' class='form-control' name='rekeningpengirim' required></div> ";
                }else{
                  echo  "<div class='form-group'>
          <label>Nama / Nomor Rekening Pengirim</label><
          <select name='rekeningpengirim' class='form-control'>";
                  $ambil=$koneksi->query("SELECT * FROM rekeningku where idmitramarketer='$idmitramarketer'"); 
                  while($distributor=$ambil->fetch_assoc()){
                      ?>
                  <option value="<?php echo $distributor['namapemilik']." ".$distributor['rekening']?>"><?php echo $distributor['namapemilik']." ".$distributor['rekening']?></option>
                     <?php }?>
           </select><br></div>
             <?php   }
                ?>
    
    
    <hr>
    
      <div class="form-group">
          <label>Jumlah Transfer</label>
    <input type="text" class="form-control" name="jmlhtransfer" required>
    </div>
    
    <div class="form-group">
    <label>Metode Pembayaran</label>
    <select class="form-control" name="metodebayar" required>
        <option>~Pilih Metode Bayar~</option>
       <?php 
                $idadmin=$_SESSION["mitraagen"]["idadmin"];				
                 $ambil=$koneksi->query("SELECT * FROM rekeningku where idadmin='$idadmin'"); 
                 while($bank2=$ambil->fetch_assoc()){
        ?>
        <option><?php echo $bank2['bank'] ?> / <?php echo $bank2['rekening'] ?></option>
   <?php } ?>
    </select>    
    </div>
      
   <center><button type="submit" class="btn btn-primary" name="kirim">Kirim</button></center>
  </form>
  
<?php include "menubawahstore.php"; ?>
</body>

<?php
include "koneksi.php";

	 
	if(isset($_POST['kirim'])){
	    date_default_timezone_set('Asia/Jakarta');
        $tgl=date('H:i:s');
	  $invoice=$_POST["invoice"];
	 $bankpengirim=$_POST["bankpengirim"];
	  $rekeningpengirim=$_POST["rekeningpengirim"];
	 $jmlhtransfer=$_POST["jmlhtransfer"];
	 $metodebayar=$_POST["metodebayar"];
    $grandtotal=$_GET["total"];
    
	  $koneksi->query("insert into orderpembayarandb (idpembayaran,idadmin,invoice,bankpengirim,rekeningpengirim,jmlhtransfer,metodebayar,tgl,waktu) values
	  ('null','$idadmin','$invoice','$bankpengirim','$rekeningpengirim','$jmlhtransfer','$metodebayar',NOW(),'$tgl')");
     $koneksi->query("UPDATE orderpengirimandb SET total='$grandtotal' where invoice='$invoice' ");
     $koneksi->query("UPDATE orderagendb SET status='Tunggu Confrim Admin' where invoice='$invoice' ");
                
        echo "<script>alert('Terimakasih .. Konfirmasi Pembayaran sudah kami terima, selanjutnya setiap invoice bisa dilihat pada menu transaksi');</script>";
		echo "<script>location='detailorderdb.php?id=$invoice'</script>";
	} 
?>	
</html>