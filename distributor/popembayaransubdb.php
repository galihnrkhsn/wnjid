<?php 
session_start();

include 'koneksi.php'; 
include 'floatingbutton.php'; 

if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

 $invoice = $_GET['invoice'];
  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
  $query = "SELECT COUNT(*) as jumlah,poproduk.idpoproduk,poproduk.namapo,poproduk.status,poproduk.note FROM poproduk inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk WHERE pomitra.invoice='$invoice'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);
 
$note=$data['note'];
?>
<!DOCTYPE html>
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
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

		
		<style>
		.align-middle{
			vertical-align: middle !important;
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
<style type="text/css">
		p.dotted {
			border-style: dotted;
		}
		p.dashed {
			border-style: dashed;
		}
		p.solid {
			border-style: solid;
		}
		p.double {
			border-style: double;
		}
		p.groove {
			border-style: groove;
		}
		p.ridge {
			border-style: ridge;
		}
		p.inset {
			border-style: inset;
		}
		p.outset {
			border-style: outset;
		}
		p.none {
			border-style: none;
		}
		p.hidden {
			border-style: hidden;
		}
		p.mix {
			border-style: dotted dashed solid double;
		}
	</style>
		
		
	</head>
	<body>
<!--================ NAVBARU  =================-->

</head>
<body>
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="listnewpo.php"><span class="glyphicon glyphicon-chevron-left"</span></a></div>
  <div class="col-8" ><p>Konfirmasi Pembayaran SubDB</p></div>
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
  
  
   <br><br><br><br><br>        
  <h4><center><?php echo $_SESSION["admin_mitra"]["namamitra"]; ?> (Cust ID : <?php echo $_SESSION["admin_mitra"]["idadmin"]; ?> )</center></h4><br>               
  
  <div class="container">               
  <form method="post">
      
      <div class="form-group">
      <label>Invoice</label>
    
        <select name="invoice" class="form-control">
        <?php $total=0; $grandtotal=$_GET["total"];  ?>
        <option value="">SubDB-Allin | Rp. <?php echo number_format($grandtotal); ?></option>
    </select><br><br>
    </div>
     
       <?php  $idadmin=$_SESSION["admin_mitra"]["idadmin"];				
                  $ambil=$koneksi->query("SELECT count(*) as bank FROM rekeningku where idadmin='$idadmin'"); 
                  $bank=$ambil->fetch_assoc();
                if ($bank['bank']==0){
                echo "<label>Nama Bank Pengirim</label><div class='form-group'><input type='text' class='form-control' name='bankpengirim' required></div>";
                }else{
                  echo  "<div class='form-group'>
          <label>Nama Bank Pengirim</label>
          <select name='bankpengirim' class='form-control'>";
                  $ambil=$koneksi->query("SELECT * FROM rekeningku where idadmin='$idadmin'"); 
                  while($distributor=$ambil->fetch_assoc()){
                      ?>
                  <option value="<?php echo $distributor['bank']?>"><?php echo $distributor['bank'] ?></option>
                     <?php }?>
           </select><br></div>
             <?php   }
                ?>
    
    
        <?php  $idadmin=$_SESSION["admin_mitra"]["idadmin"];				
                  $ambil=$koneksi->query("SELECT count(*) as bank FROM rekeningku where idadmin='$idadmin'"); 
                  $bank=$ambil->fetch_assoc();
                if ($bank['bank']==0){
                echo "<div class='form-group'><label>Nama / Nomor Rekening Pengirim</label><input type='text' class='form-control' name='rekeningpengirim' required></div> ";
                }else{
                  echo  "<div class='form-group'>
          <label>Nama / Nomor Rekening Pengirim</label>
          <select name='rekeningpengirim' class='form-control' required>";
                  $ambil=$koneksi->query("SELECT * FROM rekeningku where idadmin='$idadmin'"); 
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
    <input type="number" min="<?php echo $_GET["total"]; ?>" class="form-control" name="jmlhtransfer" required>
    </div>
    
    <div class="form-group">
    <label>Metode Pembayaran</label>
    <select class="form-control" name="metodebayar" required>
        <option>~Pilih Metode Bayar~</option>
        <option>Mandiri 1300017715213</option>
        <option>BNI Syariah 0332797905</option>
        <option>Muamalat 1100003930</option>
        <option>Bank Syariah Mandiri (BSM) 7105696706</option>
        <option>BRI 076201007469504</option>
        <option>BCA 7751043434</option>        
    </select>    
    </div>
      
   <center><button type="submit" class="btn btn-primary" name="kirim">Kirim</button></center>
  </form>
  <br><br><br><br><br><br><br>
<?php //include "menubawahstore.php"; ?>
</body>

<?php
include "koneksi.php";

	 
	if(isset($_POST['kirim'])){
	    date_default_timezone_set('Asia/Jakarta');
        $tgl=date('H:i:s');
         $idadmin=$_SESSION["admin_mitra"]["idadmin"];
	  $invoice='inv-SubdbAllin';
	 $bankpengirim=$_POST["bankpengirim"];
	  $rekeningpengirim=$_POST["rekeningpengirim"];
	 $jmlhtransfer=$_POST["jmlhtransfer"];
	 $metodebayar=$_POST["metodebayar"];
    $grandtotal=$_GET["total"] ;
    $idpoproduk=$_GET["idpo"];
    
	  $koneksi->query("INSERT INTO popembayaran VALUES
	  (null,'$idpoproduk','$invoice ($idadmin)','$bankpengirim','$rekeningpengirim','$jmlhtransfer','$metodebayar',NOW(),'$tgl')");
             
                
        echo "<script>alert('Terimakasih .. Konfirmasi Pembayaran sudah kami terima, selanjutnya tunggu konfirmasi dari kami');</script>";
		echo "<script>location='listnewpo.php'</script>";
	} 
?>	
</html> 