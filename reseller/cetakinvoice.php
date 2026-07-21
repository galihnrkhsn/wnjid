<?php 
session_start();

include 'koneksi.php'; 
include 'floatingbutton.php'; 

if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

 $idpoproduk = $_GET['id'];
  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
  $query = "SELECT COUNT(*) as jumlah,poproduk.idpoproduk,poproduk.namapo,poproduk.status FROM poproduk inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk WHERE poproduk.idpoproduk='$idpoproduk' AND pomitra.idmitra='$idadmin'";
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
		<!-- Membuat Menu Header / Navbar -->
	<?php include '../header.php'; ?>

<div class="row">

    <table id="myJudul">
      <tr>
      <table id="myJudul">
      <tr class="header">
          <th style="width:1%;"><a class="glyphicon glyphicon-chevron-left" href='listpreorder.php')<</a><th>
          <th style="width:65%;">WANOJA HIJAB</th>
  </tr>
    <td></td>
  </tr>
</table>

		<div class="container" align="center">
	
		<!--  <p class=""><h6 style="font-family:verdana;" class="ridge">Klik Nomor invoice untuk melihat invoice Pre-Order yang sedang diproses maupun sudah selesai </h6></p><br> 
		      
		      <pre>
<form method="get">
<label>Cari Produk PO</label>

<input type="text" name="namaproduk">  <button type="submit" class="btn btn-primary" name="cari">Cari</button>  <button type="submit" class="btn btn-primary" name="tampil">Tampil Semua</button></form></pre>-->  
			<p align="center"><strong>SALES INVOICE</strong></p>
			<p align="center"><strong><?php echo $data['namapo']; ?></strong></p><br>
			<p align="left">Nama Mitra  : <?php echo $_SESSION["admin_mitra"]["namamitra"]; ?> </p>
			<p align="left">Alamat  : <?php echo $_SESSION["admin_mitra"]["alamat"]; ?> </p>
			
			<?php
					// Include / load file koneksi.php
					include "koneksi.php";
					
					$idadmin=$_SESSION['admin_mitra']['idadmin'];
					$idpoproduk=$_GET["id"];
					$no=1;
					$jumlah=0;
					$subtotal=0;
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql2 = mysqli_query($koneksi, "SELECT poproduk.namapo,pokategori.namakategori,podetail.variant,pomitra.idpomitra,pomitra.jumlah,pomitra.invoice,pomitra.total,podetail.harga 
					FROM poproduk inner JOIN pokategori inner join podetail inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk and pokategori.idpo=pomitra.idpo and podetail.idpodetail=pomitra.idpodetail WHERE pomitra.idmitra='$idadmin' and pomitra.idpoproduk='$idpoproduk'");
					
					$data2 = mysqli_fetch_array($sql2) // Ambil semua data dari hasil eksekusi $sql
					?>
			<p align="left">No Invoice  : <?php echo $data2['invoice']; ?> </p>
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
					// Include / load file koneksi.php
					include "koneksi.php";
					
					$idadmin=$_SESSION['admin_mitra']['idadmin'];
					$idpoproduk=$_GET["id"];
					$no=1;
					$jumlah=0;
					$subtotal=0;
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT poproduk.namapo,pokategori.namakategori,podetail.variant,pomitra.idpomitra,pomitra.jumlah,pomitra.invoice,pomitra.total,podetail.harga 
					FROM poproduk inner JOIN pokategori inner join podetail inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk and pokategori.idpo=pomitra.idpo and podetail.idpodetail=pomitra.idpodetail WHERE pomitra.idmitra='$idadmin' and pomitra.idpoproduk='$idpoproduk'");
					
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							
							<td class="align-middle"><?php echo $no++; ?></td>
							<td class="align-middle"><?php echo $data['jumlah']; ?></td>
							<td class="align-middle"><?php echo $data['variant']; ?></td>
							<td class="align-middle"><?php echo $data['harga']; ?></td>
							<td class="align-middle"><?php echo $data['total']; ?></td>
						<!--	<td class="align-middle"> <a class="btn btn-success" href="editpo?idpo=<?php echo $idpo; ?>&idpomitra=<?php echo $data['idpomitra']; ?>$idpodetail=<?php echo $data['idpodetail']; ?>">Edit</a></td>
						-->	<?php
							//$sum=array_sum($data['jumlah']);
                            $idpomitra=array($data['idpomitra']);						
							$jumlah=$jumlah+$data['total'];
							$invoice=$data['invoice'];
							//$subtotal=$subtotal+$jumlah;
							?>
						
							
						</tr>
					<?php
					}
					
					?>
				</table><br>
				            <!--<p align="left">Qty  <?php //echo $sum; ?> </p>-->
				            <p align="right">JUMLAH  Rp. <?php echo number_format($jumlah); ?> </p>
				            
				            <?php $diskon=35/100*$jumlah;
				                  $subtotal=$jumlah-$diskon; ?>
				            <p align="right">Diskon  Rp. <?php echo number_format($diskon); ?> </p><br>      
				            <p align="right">TOTAL  Rp. <?php echo number_format($subtotal); ?> </p>
				          

				          <!--  <p align="left" size="1">Note:   PO Bergo Julang menggunakan Akad Istishna (Bayar 50% di awal 50% di akhir) Esitimasi Pemgiriman insyaallah mulai tanggal 25 Agustus 2020 jika lebih cepat dari pengerjaan akan dikabari sebelumnya Barrakallahu Fii Kum </p>
			--></div>
		         
		</div>
			<script>
		window.print();
	</script>
		
	</body>
</html>

