<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitra_agen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

?>
<?php $idproduk = $_GET['id']; 
        $namaproduk = $_GET['idd'];
?>
<!-- Header -->
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Mitra agen Wanojahijab</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    
<nav class="navbar navbar-inverse navbar-fixed-top">
  
   <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      
      
    </div>

   
  </div><!-- /.container-fluid -->
   
</nav>

<!-- akhir Header -->




		    <div class="col-sm-4 col-xs-1"></div>
			<div class="col-sm-4 col-xs-10">
				<div>
					
					<p>isi form pembelian</p>
				</div>
				 <div>
					<form method="post">
						<?php echo $_SESSION['mitra_agen']['kodemitra']; ?>
						<div >
							
							<input type="text" name="kodemitra" value="<?php echo $_SESSION['mitra_agen']['kodemitra']; ?>" hidden>
						</div>
						
						<div >
							
							<input type="text" name="namamitra" value="<?php echo $_SESSION['mitra_agen']['namamitra']; ?>" hidden>
						</div>
						
						<div >
							<label>nama agen</label><br>
							<input type="text" name="namaagen" value="<?php echo $_SESSION['mitra_agen']['namaagen']; ?>">
						</div>
						<br>
						<div >
							<label>no whatsapp/notelp</label><br>
							<input type="text" name="notelp" >
						</div>
						<br>
						<?php $ambil=$koneksi->query("SELECT * FROM produk WHERE idproduk='$idproduk' AND namaproduk='$namaproduk'"); 
						$databeli=$ambil->fetch_assoc();
						?>
						<div >
							<label>nama artikel</label><br>
							<input type="text" name="namaproduk" readonly value="<?php echo $databeli['namaproduk']; ?>">
						</div>
						<br>
						<div >
							<label>Size</label><br>
							<input type="text" name="size" >
						</div>
						<br>
						<div >
							<label>Pesanan</label><br>
							<input type="text" name="pesanan">
						</div>
						<br>
						<button class="btn btn-info" name="kirim">kirim</button>
					</form>
                    <?php 
if(isset($_POST['kirim'])){
	
	$koneksi->query("INSERT INTO form_pesanan (kodemitra,namamitra,namaagen,notelp,namaproduk,size,pesanan,tgl) VALUES ('$_POST[kodemitra]','$_POST[namamitra]','$_POST[namaagen]','$_POST[notelp]','$_POST[namaproduk]','$_POST[size]','$_POST[pesanan]',NOW())");

	echo "<div class='alert alert-info'>terkirim</div>";
	echo "<script>location='index.php';</script>";
}



?>

				

				  </div>
			</div>
		
	