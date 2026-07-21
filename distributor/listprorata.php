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
	    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
         crossorigin="anonymous">  
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

		
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
	
	</head>
	<body>
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="index.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>PRORATA</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<!--================ NAVBARU END =================-->


<div class="container" align="center">
	<button type="submit" class="btn btn-primary btn-lg" id="linkinner"><a  style="color:white" href="formrata.php">Link PRORATA Kadenza Dress</a></button><p id="demoinner"></p><br>
  <!--<button type="submit" class="btn btn-primary btn-lg" id="linkmiki"><a  style="color:white" href="formratanew.php">Link PRORATA New Limosa</a></button><p id="demomiki"></p><br>
     <button type="submit" class="btn btn-primary btn-lg" name="cari" id="linkinner"><a  style="color:white" href="formpostok.php?id=38">Link PO Inner Bandana Batch V</a></button><p id="demoinner"></p><br>
     <button type="submit" class="btn btn-primary btn-lg" name="cari" id="linkmiki"><a  style="color:white" href="formpostok.php?id=40">Link PO Miki Hat Batch III</a></button><p id="demomiki"></p><br>
    <!--<button type="submit" class="btn btn-primary btn-lg" name="cari" id="linkkolibri"><a  style="color:white" href="formdropship_konin.php">Link PO KONIN 2021</a></button><p id="demokolibri"></p><br>-->
    <br><br>
    <p>List Prorata merupakan fasilitas untuk input produk yang akan ready stock dengan pilihan pembagian prorata, sesuai produk ready yang tersedia, tidak semua permintaan akan terpenuhi melainkan kami agi sesuai dengan prosentase permintaan dari mitra, terimakasih</p>
    

			
			<br><center><h3>List PRORATA</h3></center><br>
		
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>Tanggal</th>
						<th>Nama PRORATA</th>
						<th>Status</th>
					    <th>Invoice</th>
					</tr>
					<?php
					// Include / load file koneksi.php
					include "koneksi.php";
                    $idmitra=$_SESSION['admin_mitra']['idadmin'];

					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT DISTINCT pomitra.tgl,pomitra.status,pomitra.invoice,poproduk.namapo,poproduk.idpoproduk FROM `pomitra` inner join poproduk on pomitra.idpoproduk=poproduk.idpoproduk where pomitra.idmitra='$idmitra' and (poproduk.idpoproduk='42' or poproduk.idpoproduk='43' or poproduk.idpoproduk='44' or poproduk.idpoproduk='48') ORDER BY pomitra.tgl  DESC ");

					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							
							<td class="align-middle"><?php echo $data['tgl']; ?></td>
							<td class="align-middle"><?php echo $data['namapo']; ?></td>
							<td class="align-middle"><?php echo $data['status']; ?></td>
							<td class="align-middle"><a href="dataprorata.php?idmitra=<?php echo $idmitra; ?>&id=<?php echo $data['idpoproduk']; ?>"><?php echo $data['invoice']; ?></a></td>
							
						</tr>
					<?php }	?>
				</table>
			</div>
			

		</div>
		<br><br><br><br>
	<?php include "menubawah.php" ?>	

<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDatemiki = new Date("May 03, 2021 13:00:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDatemiki - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demomiki").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demomiki").innerHTML = "Link PO tidak tersedia";
      var x = document.getElementById("linkmiki");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>   		

<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDateinner = new Date("May 27, 2021 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDateinner - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demoinner").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demoinner").innerHTML = "Link PO tidak tersedia";
      var x = document.getElementById("linkinner");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>   		

<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDate = new Date("Mar 12, 2021 22:00:00").getTime();

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
  document.getElementById("demokolibri").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demokolibri").innerHTML = "Link PO tidak tersedia";
      var x = document.getElementById("linkkolibri");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>   	


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
	    
	</body>
</html>