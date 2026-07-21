<?php 
session_start();

include 'koneksi.php'; 
//include 'floatingbutton.php';


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
		<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| Wanoja </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| Wanoja </title>


<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	</head>
	<body>
		<!-- Membuat Menu Header / Navbar -->
	<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="index.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>MY STOCK</p></div>
  <div class="col-2"><a href="whatsapp://send?text=http://mitra.wanoja.com/profil.php?user=<?php echo $tampilkan['instagram'] ?>"><i class="fa fa-share-alt" aria-hidden="true"></i></a></div>
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
  margin-top: 15px;
  padding: 2px 0;
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
        
        
        <div class="row " >
  
    <div class="col-6 col-lg-3" ><a href="konfirmasi.php">
        <img src="img/konfirmasitransfer.png" class="d-block w-100"></a>
    </div>
    <div class="col-6 col-lg-3" ><a href="transaksiagen.php">
        <img src="img/transaksisubDB.png" class="d-block w-100"></a>
    </div>
     </div>
    <br>
    
    
	     <?php 
      $ambil=$koneksi->query("SELECT tgl FROM produk order by tgl desc limit 1 ");
        while($tampilkan=$ambil->fetch_assoc()){
        ?>
		
		<div style="padding: 0 15px;">
		        <form method="get"><input type="text" name="namaproduk" style="width: 200px">  <button type="submit" class="btn btn-primary" name="cari">Cari</button></form><br>
		        <a class="btn btn-default" href="tambahproduk.php">Tambah Produk</a> <a class="btn btn-default" href="requestproduk.php">Request Produk</a>
		        <p class="right">Last Update: <?php echo $tampilkan['tgl']; ?></p> 
		         <?php } ?>
		      
			    <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
				 <tr>
                    <th>No</th>    
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Berat</th>
                    <th>Stock</th>
                    <th>Opsi Stock</th>
                    <th colspan="2">Opsi Produk</th>
                </tr>
                      </thead>
                      <tbody>
					<?php
					// Include / load file koneksi.php
					include "koneksi.php";
					if(isset($_GET["cari"])){
        					$idadmin=$_SESSION['admin_mitra']['idadmin'];
        					 $nama=$_GET["namaproduk"];
        					// Cek apakah terdapat data page pada URL
        					$page = (isset($_GET['page']))? $_GET['page'] : 1;
        					
        					$limit = 30; // Jumlah data per halamannya
        					
        					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
        					$limit_start = ($page - 1) * $limit;
        					
        					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
        					$sql = mysqli_query($koneksi, "SELECT * FROM produkdb WHERE idadmin='$idadmin' and namaproduk LIKE '%$nama%' LIMIT ".$limit_start.",".$limit);
        					
        					$no = $limit_start + 1; // Untuk penomoran tabel
					}else{
        					    $idadmin=$_SESSION['admin_mitra']['idadmin'];
        					// Cek apakah terdapat data page pada URL
        					$page = (isset($_GET['page']))? $_GET['page'] : 1;
        					
        					$limit = 30; // Jumlah data per halamannya
        					
        					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
        					$limit_start = ($page - 1) * $limit;
        					
        					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
        					$sql = mysqli_query($koneksi, "SELECT * FROM produkdb WHERE idadmin='$idadmin' LIMIT ".$limit_start.",".$limit);
        					
        					$no = $limit_start + 1; // Untuk penomoran tabel
					}
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td><?php echo $no; ?></td>
							<td class="align-middle"><?php echo $data['namaproduk']; ?></td>
							<td class="align-middle"><?php echo $data['harga']; ?></td>
							<td class="align-middle"><?php echo $data['berat']; ?></td>
							<td class="align-middle"><?php echo $data['stock']; ?></td>
							<form method="post"><input type="hidden" name="idprodukdb" value=<?php echo $data['idprodukdb']; ?>>
							                    <input type="hidden" name="idproduk" value=<?php echo $data['idproduk']; ?>>
							                    <td class="align-middle"><input type="text" name="stock" size="1"></td>
							                    <td class="align-middle"><button type="submit" class="btn btn-primary" name="tambah">+</button>
							                    <button type="submit" class="btn btn-danger" name="kurang">-</button></td>
							</form>
							
							<?php
                            if(isset($_POST["tambah"])){
	
                        	$idprodukdb = $_POST['idprodukdb'];
                        	$idproduk = $_POST['idproduk'];
	                        $stock = $_POST["stock"];
	                        
		                    $query = "UPDATE produkdb SET stock= stock+'".$stock."' where idprodukdb='$idprodukdb'";    
                            //$query2 = "UPDATE produk SET stock= stock+'".$stock."' where idproduk='$idproduk'";
                            $sql = mysqli_query( $koneksi, $query);
                            $sql2 = mysqli_query( $koneksi, $query2);
                                if($sql){ // Cek jika proses simpan ke database sukses atau tidak
                                    // Jika Sukses, Lakukan :
                                        header("location: mystock.php"); // Redirect ke halaman index.php
                                }else{
                                    // Jika Gagal, Lakukan :
                                    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
                                    echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
                                    }
                             
                                
                            }else if(isset($_POST["kurang"])){
                            	$idprodukdb = $_POST['idprodukdb'];
                        	$idproduk = $_POST['idproduk'];
	                        $stock = $_POST["stock"];
	                        $sql = mysqli_query($koneksi, "SELECT stock from produkdb WHERE idprodukdb='$idprodukdb'");
    				    	while($data = mysqli_fetch_array($sql)){
    				    	if ($data['stock']<$stock) {
                              echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
                                     header("location: mystock.php"); //
                            }
                            else{
		                    $query = "UPDATE produkdb SET stock= stock-'".$stock."' where idprodukdb='$idprodukdb'";    
                            //$query2 = "UPDATE produk SET stock= stock-'".$stock."' where idproduk='$idproduk'";
                            $sql = mysqli_query( $koneksi, $query);
                            //$sql2 = mysqli_query( $koneksi, $query2);
                                if($sql){ // Cek jika proses simpan ke database sukses atau tidak
                                    // Jika Sukses, Lakukan :
                                        header("location: mystock.php"); // Redirect ke halaman index.php
                                }else{
                                    // Jika Gagal, Lakukan :
                                    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
                                    echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
                                    }     
                                }
    				    	}
    				    	    
    				    	}
                            ?>
						</tr>
					<?php
						$no++; // Tambah 1 setiap kali looping
					}
					?>
					</tbody>
				</table>
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
				$sql2 = mysqli_query($koneksi, "SELECT COUNT(*) as jumlah FROM produkdb WHERE idadmin='$idadmin'");
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
			
			<?php include "menubawah.php"; ?>
		</div>
		
		
	</body>
</html>

