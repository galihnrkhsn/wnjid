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

$invoice=$_GET["invoice"];  
$idpoproduk=$_GET["id"];					 
$idadmin=$_SESSION['admin_mitra']['idadmin'];	
  $query = "SELECT * FROM admin_mitra WHERE idadmin='".$idadmin."'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql); 

  $querynamapo = "SELECT namapo FROM poproduk WHERE idpoproduk='".$idpoproduk."'";
  $sqlnamapo = mysqli_query($koneksi, $querynamapo);  
  $datanamapo = mysqli_fetch_array($sqlnamapo); 

$namapo=$datanamapo['namapo'];

?>
<html lang="en">
<head>
<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
<title>WNJ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 
   

		

	</head>
	<body>
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="detailpo?id=<?= $idpoproduk?>"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>LIST DROPSHIP</p></div>
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


	<div class="container">
<?php 
  $no=1;
  $dataproduk=$koneksi->query("SELECT bukapo.idbpo,
                              bukapo.jenis_mitra,
                              bukapo.jenis_po,
                              bukapo.idpoproduk,
                              bukapo.tgl,
                              bukapo.tgl_dropship,
                              bukapo.status,
                              poproduk.namapo 
                              FROM bukapo inner join poproduk on bukapo.idpoproduk = poproduk.idpoproduk 
                              WHERE poproduk.idpoproduk='$idpoproduk' and (bukapo.jenis_mitra = 'Semua Mitra' or bukapo.jenis_mitra = 'Distributor')");
  while($tampilkan=$dataproduk->fetch_assoc()){

?>

      <button type="submit" class="btn btn-primary btn-sm" name="cari" id="linkmiki1<?= $tampilkan['idbpo']; ?>">
        <a  style="color:white" href="formdropship.php?idpo=<?php echo $tampilkan['idpoproduk'];?>&invoice=<?php echo$invoice;?>">Tambah Dropship <?php echo $tampilkan['namapo']; ?></a>
      </button>
      <p id="demomiki1<?= $tampilkan['idbpo']; ?>">


      </p>
      <br>


<script>

// Mengatur waktu akhir perhitungan mundur
var countDownDatemiki1<?= $tampilkan['idbpo']; ?>= new Date("<?php echo $tampilkan['tgl_dropship']; ?> 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDatemiki1<?= $tampilkan['idbpo']; ?> - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demomiki1<?= $tampilkan['idbpo']; ?>").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demomiki1<?= $tampilkan['idbpo']; ?>").innerHTML = "Link PO tidak tersedia";
      var x = document.getElementById("linkmiki1<?= $tampilkan['idbpo']; ?>");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>

<?php } ?>	
	
	<?php

	echo "<h2><center>Dropship $namapo</center></h2><hr>";    

?>	
	<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
					    <th>No</th>
					    <th>Opsi</th>
					    <th>Status Kirim</th>
						<th>Invoice</th>
						<th>Nama Pengirim</th>
						<th>Telepon Pengirim</th>
					    <th>Nama Penerima</th>
					    <th>Telepon Penerima</th>
					    <th>Alamat Penerima</th>
						<th>Keterangan</th>
					    <th>Ekspedisi</th>
					  
					</tr>
					<?php
					// Include / load file koneksi.php
					include "koneksi.php";
			
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT * FROM podropship where invoice='$invoice' and idadmin='$idadmin' ");
					$no = 1;
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					$idds = $data['iddropship'];
					?>
						<tr>
							<td><?php echo $no++ ?></td>
							<td>


<?php 
  $no=1;
  $dataproduk=$koneksi->query("SELECT bukapo.idbpo,
                              bukapo.jenis_mitra,
                              bukapo.jenis_po,
                              bukapo.idpoproduk,
                              bukapo.tgl,
                              bukapo.tgl_dropship,
                              bukapo.status,
                              poproduk.namapo 
                              FROM bukapo inner join poproduk on bukapo.idpoproduk = poproduk.idpoproduk 
                              WHERE poproduk.idpoproduk='$idpoproduk' and bukapo.jenis_mitra = 'Semua Mitra'");
  while($tampilkan=$dataproduk->fetch_assoc()){

?>

      <div id="linkmiki<?= $idds; ?>">
                <a href="ubahdropship.php?iddropship=<?php echo $data['iddropship']; ?>" class="btn btn-success btn-xs">Ubah</a>&nbsp;
                <form method="POST">
                  <input type="hidden" name="iddropship" value="<?php echo $data['iddropship']; ?>">
                  <button type="submit" class="btn btn-danger btn-xs" name="hapus"  onclick="return confirm('Yakin Akan Menghapus Dropship?');">Hapus</button>
               </form>
      </div>
      <p id="demomiki<?= $idds; ?>">


      </p>
      <br>


<script>

// Mengatur waktu akhir perhitungan mundur
var countDownDatemiki<?= $idds; ?>= new Date("<?php echo $tampilkan['tgl_dropship']; ?> 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDatemiki<?= $idds; ?> - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demomiki<?= $idds; ?>").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demomiki<?= $idds; ?>").innerHTML = "Opsi PO tidak tersedia";
      var x = document.getElementById("linkmiki<?= $idds; ?>");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>

<?php } ?>  
							</td>
							<td class="align-middle"><?php echo $data['statuskirim']; ?></td>
							<td class="align-middle"><?php echo $data['invoice']; ?></td>
							<td class="align-middle"><?php echo $data['namapengirim']; ?></td>
							<td class="align-middle"><?php echo $data['tlppengirim']; ?></td>
							<td class="align-middle"><?php echo $data['namapenerima']; ?></td>
							<td class="align-middle"><?php echo $data['tlppenerima']; ?></td>
							<td class="align-middle"><?php echo $data['alamatpenerima']; ?></td>
							<td class="align-middle"><?php echo $data['keterangan']; ?></td>
							<td class="align-middle"><?php echo $data['ekspedisi']; ?></td>
						</tr>
					<?php
						
					}
					?>
				</table>
			</div>
			
			 <?php 

              if(isset($_POST["hapus"])){
              $iddropship = $_POST['iddropship'];

                    $delete = "DELETE FROM podropship where iddropship='$iddropship'";
                    $sql = mysqli_query( $koneksi, $delete);
if ($sql) {
              echo "<script>alert('data berhasil dihapus');</script>";
                          echo "<script>location='listdropship.php?invoice=$invoice&id=$idpoproduk';</script>";
}
else {
              echo "<script>alert('data gagal dihapus');</script>";
                          echo "<script>location='listdropship.php?invoice=$invoice&id=$idpoproduk';</script>";
}
  
              }   
   ?>

		</div>
	</body>
</html>

