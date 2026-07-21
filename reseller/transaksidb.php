<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
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
		.align-middle{
			vertical-align: middle !important;
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
<style type="text/css">
   .left    { text-align: left;}
   .right   { text-align: right;}
   .center  { text-align: center;}
   .justify { text-align: justify;}
</style>	
	
		
	</head>
	<body>
		<!-- Membuat Menu Header / Navbar -->
		
<!--================ NAVBARU =================-->
 


<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="store2.php"><span class="glyphicon glyphicon-chevron-left"</span></a></div>
  <div class="col-8" ><p>Transaksi</p></div>
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
 
	
	


<!--================ SEARCH =================-->
<style>
/* Place the navbar at the bottom of the page, and make it stick */

.caricari {
  margin-top: 80px;
  
}

.jumbotron {
  
}

</style>
<div class="container caricari">

 <!-- <div class="jumbotron"; margin-top: "0px"; margin-bottom: "0px";>
      <br>
      
  <div class="row ">
      <div class="col-2"></div>
  <div class="col-10">
      <form method="get">
<input type="text" name="namaproduk" placeholder="  tulis nama produk"><br> <button type="submit" class="btn btn-default" name="cari">
				<span class="glyphicon glyphicon-search"></span></button><button type="submit" class="btn btn-default" name="tampil">Tampil Semua</button></form>
  </div>
  
  
</div>
</div> -->


<!--================ SEARCH END =================-->		
<div class="container">
        
        <form method="post">
            <input type="text" name="invoice" placeholder="  tulis nomor invoice" class="form-control"><button type="submit" class="btn btn-primary" name="cari">Cari</button><br><br>
            </form>
        

			<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#home">Belum Bayar</a></li>
			<li><a data-toggle="tab" href="#menu1">Lunas</a></li>
		</ul>
		<div class="tab-content">
			<div id="home" class="tab-pane fade in active">
				<h3><p>Order Mitra</p></h3>
				
				<p>		<?php
					// Include / load file koneksi.php
					include "koneksi.php";
					if(isset($_POST["cari"])){
                        $invoice=$_POST["invoice"];
					    // Cek apakah terdapat data page pada URL
					    $page = (isset($_GET['page']))? $_GET['page'] : 1;
    					$limit = 50; // Jumlah data per halamannya
					
    	   				// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
    					$limit_start = ($page - 1) * $limit;
    					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
    					$sql = mysqli_query($koneksi, "SELECT DISTINCT * FROM `orderagendb` where invoice='$invoice' GROUP BY invoice");
    					
    					$no = $limit_start + 1; // Untuk penomoran tabel
    					}elseif(isset($_GET["tampil"])){
					    	$idmitra=$_SESSION['mitraagen']['idmitrareseller'];
					// Cek apakah terdapat data page pada URL
					$page = (isset($_GET['page']))? $_GET['page'] : 1;
					
					$limit = 15; // Jumlah data per halamannya
					
					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
					$limit_start = ($page - 1) * $limit;
					$idmitra=$_SESSION['mitraagen']['idmitrareseller'];
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT pomitra.tgl,pomitra.status,pomitra.invoice,poproduk.namapo,poproduk.idpoproduk FROM `pomitra` inner join poproduk
                    on pomitra.idpoproduk=poproduk.idpoproduk where pomitra.idmitra='$idmitra' and poproduk.status='open' order by pomitra.tgl desc");
					
					$no = $limit_start + 1; // Untuk penomoran tabel
					}
					else{
					    	$idmitra=$_SESSION['mitraagen']['idmitrareseller'];
					// Cek apakah terdapat data page pada URL
					$page = (isset($_GET['page']))? $_GET['page'] : 1;
					
					$limit = 10; // Jumlah data per halamannya
					
					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
					$limit_start = ($page - 1) * $limit;
					$idmitra=$_SESSION['mitraagen']['idmitrareseller'];
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT DISTINCT * FROM `orderagendb` inner join mitrareseller on mitrareseller.idmitrareseller=orderagendb.idmitrareseller where orderagendb.idmitrareseller='$idmitra' and orderagendb.payment='Belum Bayar' GROUP BY invoice ORDER BY idorderdb DESC limit 20 ");
					
					$no = $limit_start + 1; // Untuk penomoran tabel
					}
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
					 <div class="table-responsive">
                <table class="table" id="dataTables-example">
                <thead>
					<tr>
                          <td><b><?php echo $data['tgl']; ?></b><br>DB: <?php echo $data['namaagen'];?><br> <b>Payment : <?php echo $data['payment']; ?></b></td>
						  <td>Inv: <a href="detailorderdb.php?id=<?php echo $data['invoice']; ?>">#<?php echo $data['invoice'];?></a><br>Status : <?php echo $data['status']; ?></td>
						  
						  <!--
							<td class="align-middle"><?php echo $data['namaagen']; ?></td>
							<td class="align-middle"><?php echo $data['payment']; ?></td>
							<td class="align-middle"><?php echo $data['status']; ?></td>
							<td class="align-middle"><a href="detailorder.php?id=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a></td>
						-->
						</tr>
							</table>
                        </div>
					<?php
					}
					?></p>
			</div>
			
			<div id="menu1" class="tab-pane fade">
				<h3>Order Mitra</h3>
				<p>		<?php
					// Include / load file koneksi.php
					include "koneksi.php";
					if(isset($_POST["cari"])){
                        $invoice=$_POST["invoice"];
					    // Cek apakah terdapat data page pada URL
					    $page = (isset($_GET['page']))? $_GET['page'] : 1;
    					$limit = 50; // Jumlah data per halamannya
					
    	   				// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
    					$limit_start = ($page - 1) * $limit;
    					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
    					$sql = mysqli_query($koneksi, "SELECT DISTINCT mitrareseller.namaagen,orderagendb.invoice,orderagendb.payment,orderagendb.status,orderagendb.tgl FROM `orderagendb` inner join mitrareseller on mitrareseller.idmitrareseller=orderagendb.idmitrareseller where orderagendb.idmitrareseller='$idmitra' and orderagendb.payment='Lunas' and invoice='$invoice' GROUP BY invoice ORDER BY idorderdb DESC");
    					
    					$no = $limit_start + 1; // Untuk penomoran tabel
    					}elseif(isset($_GET["tampil"])){
					    	$idmitra=$_SESSION['mitraagen']['idmitrareseller'];
					// Cek apakah terdapat data page pada URL
					$page = (isset($_GET['page']))? $_GET['page'] : 1;
					
					$limit = 15; // Jumlah data per halamannya
					
					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
					$limit_start = ($page - 1) * $limit;
					$idmitra=$_SESSION['mitraagen']['idmitrareseller'];
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT mitrareseller.namaagen,orderagendb.invoice,orderagendb.payment,orderagendb.status,orderagendb.tgl FROM `pomitra` inner join poproduk
                    on pomitra.idpoproduk=poproduk.idpoproduk where pomitra.idmitra='$idmitra' and poproduk.status='open' order by pomitra.tgl desc");
					
					$no = $limit_start + 1; // Untuk penomoran tabel
					}
					else{
					    	$idmitra=$_SESSION['mitraagen']['idmitrareseller'];
					// Cek apakah terdapat data page pada URL
					$page = (isset($_GET['page']))? $_GET['page'] : 1;
					
					$limit = 10; // Jumlah data per halamannya
					
					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
					$limit_start = ($page - 1) * $limit;
					$idmitra=$_SESSION['mitraagen']['idmitrareseller'];
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT DISTINCT mitrareseller.namaagen,orderagendb.invoice,orderagendb.payment,orderagendb.status,orderagendb.tgl FROM `orderagendb` inner join mitrareseller on mitrareseller.idmitrareseller=orderagendb.idmitrareseller where orderagendb.idmitrareseller='$idmitra' and orderagendb.payment='Lunas'  GROUP BY invoice ORDER BY idorderdb DESC limit 20 ");
					
					$no = $limit_start + 1; // Untuk penomoran tabel
					}
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
					 <div class="table-responsive">
                <table class="table" id="dataTables-example">
                <thead>
					<tr>
                          <td><b><?php echo $data['tgl']; ?></b><br>DB: <?php echo $data['namaagen'];?><br> <b>Payment : <?php echo $data['payment']; ?></b></td>
						  <td>Inv: <a href="detailorderdb.php?id=<?php echo $data['invoice']; ?>">#<?php echo $data['invoice'];?></a><br>Status : <?php echo $data['status']; ?><br><form method="post"><input type="hidden" name="id" value="<?php echo $data['invoice']; ?>"><button class="btn btn-primary btn-xs" name="done">Sudah Terima Barang</button></form></td>
						  
						  <!--
							<td class="align-middle"><?php echo $data['namaagen']; ?></td>
							<td class="align-middle"><?php echo $data['payment']; ?></td>
							<td class="align-middle"><?php echo $data['status']; ?></td>
							<td class="align-middle"><a href="detailorder.php?id=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a></td>
						-->
						</tr>
							</table>
                        </div>
					<?php
					}
					?></p>
			</div>
			
		</div>	
			
					
			
				
			</div>
		
			<!--
			-- Buat Paginationnya
			-- Dengan bootstrap, kita jadi dimudahkan untuk membuat tombol-tombol pagination dengan design yang bagus tentunya
			-->
			<ul class="pagination">
				<!-- LINK FIRST AND PREV -->
				<?php
				if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
				?>
					<li class="disabled"><a href="#">First</a></li>
					<li class="disabled"><a href="#">&laquo;</a></li>
				<?php
				}else{ // Jika page bukan page ke 1
					$link_prev = ($page > 1)? $page - 1 : 1;
				?>
					<li><a href="?page=1">First</a></li>
					<li><a href="?page=<?php echo $link_prev; ?>">&laquo;</a></li>
				<?php
				}
				?>
				
				<!-- LINK NUMBER -->
				<?php
				// Buat query untuk menghitung semua jumlah data
				$sql2 = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM produk where stock>0");
				$get_jumlah = mysqli_fetch_array($sql2);
				
				$jumlah_page = ceil($get_jumlah['jumlah'] / $limit); // Hitung jumlah halamannya
				$jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
				$start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
				$end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number
				
				for($i = $start_number; $i <= $end_number; $i++){
					$link_active = ($page == $i)? ' class="active"' : '';
				?>
					<li<?php echo $link_active; ?>><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
				<?php
				}
				?>
				
				<!-- LINK NEXT AND LAST -->
				<?php
				// Jika page sama dengan jumlah page, maka disable link NEXT nya
				// Artinya page tersebut adalah page terakhir 
				if($page == $jumlah_page){ // Jika page terakhir
				?>
					<li class="disabled"><a href="#">&raquo;</a></li>
					<li class="disabled"><a href="#">Last</a></li>
				<?php
				}else{ // Jika Bukan page terakhir
					$link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
				?>
					<li><a href="?page=<?php echo $link_next; ?>">&raquo;</a></li>
					<li><a href="?page=<?php echo $jumlah_page; ?>">Last</a></li>
				<?php
				}
				?>
			</ul>
		</div>

<?php include "menubawahstoredb.php"; ?>

<style>
/* Place the navbar at the bottom of the page, and make it stick */



</style>
	</body>
	<?php
	if(isset($_POST["done"])){
	$invoice = $_POST['id'];
	
	$koneksi->query("UPDATE orderagendb SET status='Selesai',payment='LUNAS' where invoice='$invoice' "); 
	echo "<script>location='transaksi.php'</script>";
	}
	?>
	
	<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.js"></script>
</html>

