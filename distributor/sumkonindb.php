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
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="listnewpo.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>PRE ORDER</p></div>
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
	
		<!--  <p class=""><h6 style="font-family:verdana;" class="ridge">Klik Nomor invoice untuk melihat invoice Pre-Order yang sedang diproses maupun sudah selesai </h6></p><br> 
		      
		      <pre>
<form method="get">
<label>Cari Produk PO</label>

<input type="text" name="namaproduk">  <button type="submit" class="btn btn-primary" name="cari">Cari</button>  <button type="submit" class="btn btn-primary" name="tampil">Tampil Semua</button></form></pre>-->  
			<p align="center"><strong>SALES INVOICE</strong></p>
			<p align="center"><strong><?php echo $data['namapo']; ?></strong></p><br>
			<!-- <p align="left">Nama Mitra  : <?php echo $_SESSION["admin_mitra"]["namamitra"]; ?> </p>
			<p align="left">Alamat  : <?php echo $_SESSION["admin_mitra"]["alamat"]; ?> </p> -->
			
			<?php
					// Include / load file koneksi.php
					include "koneksi.php";
					
					$idadmin=$_SESSION['admin_mitra']['idadmin'];
					$idpoproduk=$_GET["id"];
					$no=1;
					$sum=0;
					$jumlah=0;
					$subtotal=0;
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql2 = mysqli_query($koneksi, "SELECT poproduk.namapo,pokategori.namakategori,podetail.variant,pomitra.idpomitra,pomitra.jumlah,pomitra.invoice,pomitra.total,podetail.harga
					FROM poproduk inner JOIN pokategori inner join podetail inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk and pokategori.idpo=pomitra.idpo and podetail.idpodetail=pomitra.idpodetail WHERE pomitra.invoice='$invoice'");
					
					$data2 = mysqli_fetch_array($sql2) // Ambil semua data dari hasil eksekusi $sql
					?>
			<!-- <p align="left">No Invoice  : <?php echo $data2['invoice']; ?> </p> -->
			 <?php 
		  //  if($idpoproduk==18) {
		//	echo "<p align='right'><a class='btn btn-info' href='formdropship.php?idadmin=$idadmin&idpo=$idpoproduk&invoice=$data2[invoice]' id='linkdropship'>Dropship</a>  <p align='right' id='demodropship'></p> </p>";
		//	}else if($idpoproduk==19) {
		//	echo "<p align='right'><a class='btn btn-info' href='formdropship.php?idadmin=$idadmin&idpo=$idpoproduk&invoice=$data2[invoice]' id='linkdropshipz'>Dropship</a>  <p align='right' id='demodropshipz'></p> </p>";
		//	}else 
	if($idpoproduk==50 or $idpoproduk==47 or $idpoproduk==46) {
		echo "Dropship bisa di lihat di <a href='listnewpo.php'>Menu Listdropship</a>";
  }
			?>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>No</th>
						<th>Qty</th>
						<th>Nama Barang</th>

					     <!--<th>List Nama</th>-->
					  
					    <th>Satuan</th>
					    <th>Jumlah</th>
					    <th>Invoice</th>
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
					FROM `pomitra` inner join poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
					inner join pokategori on pomitra.idpo=pokategori.idpo
					inner join podetail on pomitra.idpodetail=podetail.idpodetail
					LEFT JOIN mitraagen on pomitra.idmitraagen=mitraagen.idmitraagen LEFT JOIN mitrareseller on pomitra.idmitrareseller=mitrareseller.idmitrareseller LEFT JOIN mitramarketer on pomitra.idmitramarketer=mitramarketer.idmitramarketer 
					LEFT JOIN admin_mitra on pomitra.idmitra=admin_mitra.idadmin
					where (admin_mitra.idadmin='$idadmin' or mitraagen.idadmin='$idadmin' or mitrareseller.idadmin='$idadmin' 
					or mitramarketer.idadmin='$idadmin') and poproduk.idpoproduk='$idpoproduk' and pomitra.jumlah>0
					and pomitra.status <> 'Belum Acc DB'");
					
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td class="align-middle"><?php echo $no++; ?></td>
							<td class="align-middle"><?php echo $data['jumlah']; ?></td>
							<td class="align-middle"><?php echo $data['variant']; ?></td>
							 <?php //if($data['custom']<>''){
					            ?>
					   <!-- <td class="align-middle"><?php echo  nl2br($data['custom']); ?></td>
					     <?php //} else { ?>
					     <td class="align-middle"> - </td>-->
					     <?php //} ?>
							<td class="align-middle">Rp. <?php echo number_format($data['harga']); ?></td>
							<td class="align-middle">Rp. <?php echo number_format($data['total']); ?></td>
							<td class="align-middle"><a href="detailpo.php?idpoproduk=97&namapo=<?php echo $data['namapo']; ?>">
								<?php echo $data['invoice']; ?></a></td>
						<!--	<td class="align-middle"> <a class="btn btn-success" href="editpo?idpo=<?php echo $idpo; ?>&idpomitra=<?php echo $data['idpomitra']; ?>$idpodetail=<?php echo $data['idpodetail']; ?>">Edit</a></td>
						-->	<?php
							$sum=$sum+$data['jumlah'];
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
				           <p align="left">Total Qty : <?php echo $sum; ?> </p>  
				            <p align="right">JUMLAH  Rp. <?php echo number_format($jumlah); ?> </p>
				            
				            <?php $diskon=35/100*$jumlah;
				                  $subtotal=$jumlah-$diskon; ?>
				            <p align="right">Diskon DB 35% Rp. -<?php echo number_format($diskon); ?> </p><br>      
				            <p align="right">TOTAL  Rp. <?php echo number_format($subtotal); ?> </p>
				          	<p align="left"><strong>Note : </strong><?php echo $note ?></p><br>
				        <?php
				        $dp=$subtotal*50/100;
				        $invoice=$data2['invoice'];
				        ?>
				        <?php
				        $dp1=$subtotal*40/100;
				        $dp2=$subtotal*30/100;
				        $dp3=$subtotal*30/100;
				        $invoice=$data2['invoice'];
				        ?>
				        <p align="center"><strong>Pembayaran 1 40% : </strong>Rp. <?php echo number_format($dp1); ?></p><br>
				        <p align="center"><strong>Pembayaran 2 30% : </strong>Rp. <?php echo number_format($dp2); ?></p><br>
				        <p align="center"><strong>Pembayaran 3 30% : </strong>Rp. <?php echo number_format($dp3); ?></p><br>
				           <br>
				           <br>
				          
                   <?php 
				         
                    echo "<a class='btn btn-primary' href='popembayaransubdb.php?total=$dp1&idpo=$idpoproduk'>Konfirmasi Pembayaran 1</a>";
				         
				          
				          ?>
		<br><br>

<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDateme = new Date("May 31, 2021 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDateme - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demome").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demome").innerHTML = "<a class='btn btn-primary' href='popembayaran.php?invoice=<?php echo $invoice ?>&total=<?php echo $dp ?>$idpo=<?php echo $idpoproduk ?>'>Konfirmasi DP</a>";
      var x = document.getElementById("linkme");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>       

		                   
<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDatele = new Date("May 31, 2021 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDatele - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demole").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demole").innerHTML = "Link PO tidak tersedia";
      var x = document.getElementById("linkle");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>       


<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDate = new Date("Oct 21, 2020 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDate - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demoz").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demoz").innerHTML = "Link PO tidak tersedia";
      var x = document.getElementById("linkz");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>       

<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDate2 = new Date("Oct 14, 2020 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDate2 - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demoo").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demoo").innerHTML = "Link PO tidak tersedia";
      var x = document.getElementById("link2");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>

<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDatedropp = new Date("June 06, 2021 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDatedropp - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demodropshipp").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demodropshipp").innerHTML = "Dropship Ditutup";
      var x = document.getElementById("linkdropshipp");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>

<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDatedrop = new Date("Nov 28, 2020 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDatedrop - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demodropship").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demodropship").innerHTML = "Dropship Ditutup";
      var x = document.getElementById("linkdropship");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>

<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDatedropl = new Date("Oct 28, 2020 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDatedropl - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demodropshipl").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demodropshipl").innerHTML = "Dropship Ditutup";
      var x = document.getElementById("linkdropshipl");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>

<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDatedropz = new Date("Oct 27, 2020 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDatedropz - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demodropshipz").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demodropshipz").innerHTML = "Dropship Ditutup";
      var x = document.getElementById("linkdropshipz");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>

<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDate3 = new Date("Sep 30, 2020 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDate3 - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demo3").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo3").innerHTML = "Link PO tidak tersedia";
      var x = document.getElementById("link3");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script> 
				          <!--  <p align="left" size="1">Note:   PO Bergo Julang menggunakan Akad Istishna (Bayar 50% di awal 50% di akhir) Esitimasi Pemgiriman insyaallah mulai tanggal 25 Agustus 2020 jika lebih cepat dari pengerjaan akan dikabari sebelumnya Barrakallahu Fii Kum </p>
			--></div>
		         
		</div>
	</body>
</html>