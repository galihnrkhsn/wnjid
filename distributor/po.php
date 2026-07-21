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
  <div class="col-8" ><p>PRE ORDER</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<!--================ NAVBARU END =================-->


<div class="container" align="center">
     <button type="submit" class="btn btn-warning btn-lg" name="cari" id="linkmiki"><a  style="color:white" href="formpoku.php?id=29">Link PO Miki Hat</a></button><p id="demomiki"></p><br>
    <button type="submit" class="btn btn-primary btn-lg" name="cari" id="linkkolibri"><a  style="color:white" href="formdropship_kolibri.php">Link PO KOLIBRI 2021</a></button><p id="demokolibri"></p><br>

<div style="padding: 0 15px;">
    <div style="padding: 0 15px;">
    <ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#home" class="btn btn-primary">List PO Reguler</a></li>
		<li><a data-toggle="tab" href="#menu1" class="btn btn-primary">List PO KOLIBRI 2021</a></li>
		<li><a data-toggle="tab" href="#menu2" class="btn btn-warning">List PO Kolibri Agen</a></li>
		<li><a data-toggle="tab" href="#menu3" class="btn btn-warning">List PO Kolibri Reseller</a></li>
		<li><a data-toggle="tab" href="#menu4" class="btn btn-warning">List PO Kolibri Marketer</a></li>
	</ul>		
		
		<div class="tab-content">
			<div id="home" class="tab-pane fade in active">
			
			<br><center><h3>PO Regular</h3></center><br>
			
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>Tanggal</th>
						<th>Nama PO</th>
						<th>Status</th>
					    <th>Invoice</th>
					</tr>
					<?php
					// Include / load file koneksi.php
					include "koneksi.php";
                    $idmitra=$_SESSION['admin_mitra']['idadmin'];

					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT DISTINCT pomitra.tgl,pomitra.status,pomitra.invoice,poproduk.namapo,poproduk.idpoproduk FROM `pomitra` inner join poproduk on pomitra.idpoproduk=poproduk.idpoproduk where pomitra.idmitra='$idmitra' and poproduk.idpoproduk<>'28' ORDER BY pomitra.tgl  DESC ");

					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							
							<td class="align-middle"><?php echo $data['tgl']; ?></td>
							<td class="align-middle"><?php echo $data['namapo']; ?></td>
							<td class="align-middle"><?php echo $data['status']; ?></td>
							<td class="align-middle"><a href="datapo.php?idmitra=<?php echo $idmitra; ?>&id=<?php echo $data['idpoproduk']; ?>"><?php echo $data['invoice']; ?></a></td>
							
						</tr>
					<?php }	?>
				</table>
			</div>
			</div>
	
<!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
			
			<div id="menu1" class="tab-pane fade">
			
			<br><center><h3>PO Kolibri</h3></center><br> 
			  
			<a href="sumkolibridb.php" class="btn btn-info">Total PO KOLIBRI</a><br>
			
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
					    <th>Opsi</th>
						<th>Tanggal</th>
						<th>Nama PO</th>
						<th>Nama Keluarga</th>
						<th>Status</th>
					    <th>Invoice</th>
					</tr>
					<?php
					// Include / load file koneksi.php
					include "koneksi.php";
					$idadmin=$_SESSION['admin_mitra']['idadmin'];
					
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT DISTINCT pokolibri.tgl,pokolibri.status,pokolibri.invoice,poproduk.namapo,poproduk.idpoproduk,podropship_kolibri.namapenerima FROM `pokolibri` inner join poproduk on pokolibri.idpoproduk=poproduk.idpoproduk left join podropship_kolibri on pokolibri.invoice=podropship_kolibri.invoice where pokolibri.idadmin='$idadmin' and poproduk.idpoproduk='28' ORDER BY pokolibri.tgl DESC");
				
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td class="align-left">
							<?php if($data['status']=='Belum DP'){
							      echo "<form method='post'><a href='ubahpokolibri.php?invoice=$data[invoice]' class='btn btn-success'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a> <input type='hidden' name='id' value='$data[invoice]'><button class='btn btn-danger' name='hapus' type='submit'><i class='fa fa-trash' aria-hidden='true'></i></button> <a href='datapokolibri.php?invoice=$data[invoice]' class='btn btn-warning'><i class='fa fa-eye' aria-hidden='true'></i></a> <a href='cetakpokolibri.php?invoice=$data[invoice]' class='btn btn-info'><i class='fa fa-download' aria-hidden='true'></i></a></form></td>";
							      }else {
							          echo "<a href='datapokolibri.php?invoice=$data[invoice]' class='btn btn-warning'><i class='fa fa-eye' aria-hidden='true'></i></a> <a href='cetakpokolibri.php?invoice=$data[invoice]' class='btn btn-info'><i class='fa fa-download' aria-hidden='true'></i></a>";
							      }
							 ?>
							<td class="align-middle"><?php echo $data['tgl']; ?></td>
							<td class="align-middle"><?php echo $data['namapo']; ?></td>
							<td class="align-middle"><?php echo $data['namapenerima']; ?></td>
							<td class="align-middle"><?php echo $data['status']; ?></td>
							<td class="align-middle"><a href="datapokolibri.php?invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a></td>
							
						</tr>
					<?php	}	?>
				</table>
				
			</div><br><br>
			
			<img src="img/kalenderklb.jpg" width="100%">
			<?php
			if(isset($_POST["hapus"])){
			 $id=$_POST["id"];
			 $koneksi->query("DELETE FROM pokolibri WHERE invoice='$id'");
        				echo "<script>alert('Data berhasil Dihapus');</script>";
		                echo "<script>location='listnewpo.php';</script>";
			    
			}
			?>
			
			
			</div>

<!------------------------------------------------------------------------------------------------------------------------------------------------------------------->			
			
			<div id="menu2" class="tab-pane fade">
			
			<br><center><h3>PO Kolibri Agen</h3></center><br> 
			    	
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
					    <th>Opsi</th>
						<th>Tanggal</th>
						<th>Nama PO</th>
						<th>Nama Agen</th>
						<th>Nama Keluarga</th>
						<th>Status</th>
					    <th>Invoice</th>
					    <th>Opsi</th>
					</tr>
					<?php
					// Include / load file koneksi.php
					include "koneksi.php";
					$idadmin=$_SESSION['admin_mitra']['idadmin'];
					
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT DISTINCT pokolibri.tgl,pokolibri.status,pokolibri.invoice,poproduk.namapo,poproduk.idpoproduk,podropship_kolibri.namapenerima,mitraagen.namaagen as namaagen FROM `pokolibri` inner join poproduk on pokolibri.idpoproduk=poproduk.idpoproduk left join podropship_kolibri on pokolibri.invoice=podropship_kolibri.invoice LEFT JOIN mitraagen on pokolibri.idmitraagen=mitraagen.idmitraagen LEFT JOIN admin_mitra on mitraagen.idadmin=admin_mitra.idadmin where poproduk.idpoproduk='28' and mitraagen.idadmin='$idadmin' ORDER BY pokolibri.tgl DESC");
				
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td class="align-left"><a href="datapokolibri.php?invoice=<?php echo $data['invoice'] ?>" class='btn btn-warning'><i class='fa fa-eye' aria-hidden='true'></i></a> <a href="cetakpokolibri.php?invoice=<?php echo $data['invoice']?>" class='btn btn-info'><i class='fa fa-download' aria-hidden='true'></i></a></td>
							<td class="align-middle"><?php echo $data['tgl']; ?></td>
							<td class="align-middle"><?php echo $data['namapo']; ?></td>
							<td class="align-middle"><?php echo $data['namaagen']; ?></td>
							<td class="align-middle"><?php echo $data['namapenerima']; ?></td>
							<td class="align-middle"><?php echo $data['status']; ?></td>
							<td class="align-middle"><a href="datapokolibri.php?invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a></td>
							<td class="align-middle"><?php if ($data['status']=='Belum ACC DB'){
                                                            echo "<form method='post'><input type='hidden' name='invoice' value='$data[invoice]'><button type='submit' class='btn btn-primary btn-xs' name='approve'>Approve</button></form>";
                                                            }
                                                            else {
                                                                echo "#";
                                                            }
                                                    ?></td>
						</tr>
					<?php	}	?>
				</table>
				
			</div>
			
		<img src="img/kalenderklb.jpg" width="100%">	
        
				<?php
				if(isset($_POST["approve"])){
				    $invoice=$_POST['invoice'];
				    $koneksi->query("UPDATE pokolibri set status='Belum DP' where invoice='$invoice' ");
    	            echo "<script>alert('PO Agen telah di Approve');</script>";
		            echo "<script>location='listnewpo.php';</script>";
				}
				
				?>
			</div>			
			
<!------------------------------------------------------------------------------------------------------------------------------------------------------------------->			
			
			<div id="menu3" class="tab-pane fade">
			
			<br><center><h3>PO Kolibri Reseller</h3></center><br> 
			    	
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
					    <th>Opsi</th>
						<th>Tanggal</th>
						<th>Nama PO</th>
						<th>Nama Agen</th>
						<th>Nama Keluarga</th>
						<th>Status</th>
					    <th>Invoice</th>
					    <th>Opsi</th>
					</tr>
					<?php
					// Include / load file koneksi.php
					include "koneksi.php";
					$idadmin=$_SESSION['admin_mitra']['idadmin'];
					
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT DISTINCT pokolibri.tgl,pokolibri.status,pokolibri.invoice,poproduk.namapo,poproduk.idpoproduk,podropship_kolibri.namapenerima,mitrareseller.namaagen as namareseller FROM `pokolibri` inner join poproduk on pokolibri.idpoproduk=poproduk.idpoproduk left join podropship_kolibri on pokolibri.invoice=podropship_kolibri.invoice LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=pokolibri.idmitrareseller LEFT JOIN admin_mitra on mitrareseller.idadmin=admin_mitra.idadmin where poproduk.idpoproduk='28' and mitrareseller.idadmin='$idadmin' ORDER BY pokolibri.tgl DESC");
				
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td class="align-left"><a href="datapokolibri.php?invoice=<?php echo $data['invoice'] ?>" class='btn btn-warning'><i class='fa fa-eye' aria-hidden='true'></i></a> <a href="cetakpokolibri.php?invoice=<?php echo $data['invoice']?>" class='btn btn-info'><i class='fa fa-download' aria-hidden='true'></i></a></td>
							<td class="align-middle"><?php echo $data['tgl']; ?></td>
							<td class="align-middle"><?php echo $data['namapo']; ?></td>
							<td class="align-middle"><?php echo $data['namareseller']; ?></td>
							<td class="align-middle"><?php echo $data['namapenerima']; ?></td>
							<td class="align-middle"><?php echo $data['status']; ?></td>
							<td class="align-middle"><a href="datapokolibri.php?invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a></td>
							<td class="align-middle"><?php if ($data['status']=='Belum ACC DB'){
                                                            echo "<form method='post'><input type='hidden' name='invoice' value='$data[invoice]'><button type='submit' class='btn btn-primary btn-xs' name='approve'>Approve</button></form>";
                                                            }
                                                            else {
                                                                echo "#";
                                                            }
                                                    ?></td>
						</tr>
					<?php	}	?>
				</table>
				
			</div>
			
		<img src="img/kalenderklb.jpg" width="100%">	
        
				<?php
				if(isset($_POST["approve"])){
				    $invoice=$_POST['invoice'];
				    $koneksi->query("UPDATE pokolibri set status='Belum DP' where invoice='$invoice' ");
    	            echo "<script>alert('PO Agen telah di Approve');</script>";
		            echo "<script>location='listnewpo.php';</script>";
				}
				
				?>
			</div>	

<!------------------------------------------------------------------------------------------------------------------------------------------------------------------->			
			
			<div id="menu4" class="tab-pane fade">
			
			<br><center><h3>PO Kolibri Marketer</h3></center><br> 
			    	
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
					    <th>Opsi</th>
						<th>Tanggal</th>
						<th>Nama PO</th>
						<th>Nama Agen</th>
						<th>Nama Keluarga</th>
						<th>Status</th>
					    <th>Invoice</th>
					    <th>Opsi</th>
					</tr>
					<?php
					// Include / load file koneksi.php
					include "koneksi.php";
					$idadmin=$_SESSION['admin_mitra']['idadmin'];
					
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT DISTINCT pokolibri.tgl,pokolibri.status,pokolibri.invoice,poproduk.namapo,poproduk.idpoproduk,podropship_kolibri.namapenerima,mitramarketer.namaagen as namamarketer FROM `pokolibri` inner join poproduk on pokolibri.idpoproduk=poproduk.idpoproduk left join podropship_kolibri on pokolibri.invoice=podropship_kolibri.invoice LEFT JOIN mitramarketer on mitramarketer.idmitramarketer=pokolibri.idmitramarketer LEFT JOIN admin_mitra on mitramarketer.idadmin=admin_mitra.idadmin where poproduk.idpoproduk='28' and mitramarketer.idadmin='$idadmin' ORDER BY pokolibri.tgl DESC");
				
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td class="align-left"><a href="datapokolibri.php?invoice=<?php echo $data['invoice'] ?>" class='btn btn-warning'><i class='fa fa-eye' aria-hidden='true'></i></a> <a href="cetakpokolibri.php?invoice=<?php echo $data['invoice']?>" class='btn btn-info'><i class='fa fa-download' aria-hidden='true'></i></a></td>
							<td class="align-middle"><?php echo $data['tgl']; ?></td>
							<td class="align-middle"><?php echo $data['namapo']; ?></td>
							<td class="align-middle"><?php echo $data['namamarketer']; ?></td>
							<td class="align-middle"><?php echo $data['namapenerima']; ?></td>
							<td class="align-middle"><?php echo $data['status']; ?></td>
							<td class="align-middle"><a href="datapokolibri.php?invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a></td>
							<td class="align-middle"><?php if ($data['status']=='Belum ACC DB'){
                                                            echo "<form method='post'><input type='hidden' name='invoice' value='$data[invoice]'><button type='submit' class='btn btn-primary btn-xs' name='approve'>Approve</button></form>";
                                                            }
                                                            else {
                                                                echo "#";
                                                            }
                                                    ?></td>
						</tr>
					<?php	}	?>
				</table>
				
			</div>
			
		<img src="img/kalenderklb.jpg" width="100%">	
        
				<?php
				if(isset($_POST["approve"])){
				    $invoice=$_POST['invoice'];
				    $koneksi->query("UPDATE pokolibri set status='Belum DP' where invoice='$invoice' ");
    	            echo "<script>alert('PO Agen telah di Approve');</script>";
		            echo "<script>location='listnewpo.php';</script>";
				}
				
				?>
			</div>	
			
			
			</div>

		</div>
		<br><br><br><br>
	<?php include "menubawah.php" ?>	

<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDatemiki = new Date("Jan 31, 2021 23:59:00").getTime();

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
var countDownDate = new Date("Feb 01, 2021 23:59:00").getTime();

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

