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


  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
  $query = "SELECT COUNT(*) as jumlah,poproduk.idpoproduk,poproduk.namapo,poproduk.status FROM poproduk inner join pokolibri on poproduk.idpoproduk=pokolibri.idpoproduk WHERE pokolibri.idadmin='$idadmin'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);
 

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


<link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/dataTables.bootstrap.css">
		<!-- Load File bootstrap.min.css yang ada difolder css -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

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
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="listnewpo.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>PRE ORDER</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br><br>

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


		<div class="container" align="center">
	     <p align="center"><strong>PO KOLIBRI 2021</strong></p><br>
			<p align="center"><strong>SALES INVOICE SUMMARY DB :<br> <?php echo $_SESSION["admin_mitra"]["namamitra"]?></strong></p>
			
		
		
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>No</th>
						<th>Qty</th>
						<th>Nama Barang</th>
					    <th>Satuan</th>
					    <th>Jumlah</th>
					    <!--<th>Opsi</th>-->
					</tr>
					<?php
					$no=1;
					$jumlah=0;
					$subtotal=0;
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT DISTINCT pokolibri.tgl,pokolibri.status,pokolibri.invoice,poproduk.namapo,poproduk.idpoproduk,podropship_kolibri.namapenerima,admin_mitra.namamitra,mitraagen.namaagen as namaagen,mitrareseller.namaagen as namareseller,mitramarketer.namaagen as namamarketer,poproduk.namapo,pokategori.namakategori,podetail.variant,pokolibri.idpokolibri,pokolibri.jumlah,pokolibri.invoice,pokolibri.total,podetail.harga  FROM `pokolibri` inner join poproduk on pokolibri.idpoproduk=poproduk.idpoproduk INNER JOIN pokategori on pokolibri.idpo=pokategori.idpo INNER JOIN podetail on pokolibri.idpodetail=podetail.idpodetail left join podropship_kolibri on pokolibri.invoice=podropship_kolibri.invoice LEFT JOIN mitraagen on pokolibri.idmitraagen=mitraagen.idmitraagen LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=pokolibri.idmitrareseller LEFT JOIN mitramarketer on mitramarketer.idmitramarketer=pokolibri.idmitramarketer LEFT JOIN admin_mitra on admin_mitra.idadmin=pokolibri.idadmin where poproduk.idpoproduk='28' and (admin_mitra.idadmin='$idadmin' or mitraagen.idadmin='$idadmin' or mitrareseller.idadmin='$idadmin' or mitramarketer.idadmin='$idadmin') and pokolibri.jumlah>0 ORDER BY pokolibri.tgl DESC");
					
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							
							<td class="align-middle"><?php echo $no++; ?></td>
							<td class="align-middle"><?php echo $data['jumlah']; ?></td>
							<td class="align-middle"><?php echo $data['variant']; ?></td>
							<td class="align-middle">Rp. <?php echo number_format($data['harga']); ?></td>
							<td class="align-middle">Rp. <?php echo number_format($data['total']); ?></td>
						<!--	<td class="align-middle"> <a class="btn btn-success" href="editpo?idpo=<?php echo $idpo; ?>&idpokolibri=<?php echo $data['idpokolibri']; ?>$idpodetail=<?php echo $data['idpodetail']; ?>">Edit</a></td>
						-->	<?php
							//$sum=array_sum($data['jumlah']);
                            $idpokolibri=array($data['idpokolibri']);						
							$jumlah=$jumlah+$data['total'];
							$invoice=$data['invoice'];
							//$subtotal=$subtotal+$jumlah;
							
							$status=$data['status'];
							?>
						
							
						</tr>
					<?php
					}
					
					?>
				</table><br>
				            <!--<p align="left">Qty  <?php //echo $sum; ?> </p>-->
				            <p align="right">JUMLAH  Rp. <?php echo number_format($jumlah); ?> </p>
				            
				            <?php $diskon=35/100*$jumlah;
				                  $subtotal=$jumlah-$diskon;
				                  $payment1= $subtotal*30/100;
				                  $payment2= $subtotal*30/100;
				                  $payment3= $subtotal*40/100;
				                  
				            ?>
				            <p align="right">Diskon DB 35% Rp. -<?php echo number_format($diskon); ?> </p><br>      
				            <p align="right">TOTAL  Rp. <?php echo number_format($subtotal); ?> </p>
				            <hr>
				            <p align="right">Payment 1 : Rp. <?php echo number_format($payment1); ?> </p>
				            <p align="right">Payment 2 : Rp. <?php echo number_format($payment2); ?> </p>
				            <p align="right">Payment 3 : Rp. <?php echo number_format($payment3); ?> </p>
				

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.js"></script>
<script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.dataTables.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		         
		</div>
	</body>
</html>