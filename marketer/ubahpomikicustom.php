<?php 
session_start();

include 'koneksi.php'; 
include 'floatingbutton.php'; 

if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}
$hak = $_GET['hak'];
 $idpoproduk = $_GET['id'];
  $invoice = $_GET['invoice'];
  $idmitramarketer=$_SESSION["mitraagen"]["idmitramarketer"];
  $query = "SELECT COUNT(*) as jumlah,poproduk.idpoproduk,poproduk.namapo,poproduk.status FROM poproduk inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk WHERE poproduk.idpoproduk='$idpoproduk' AND pomitra.idmitramarketer='$idmitramarketer' and pomitra.invoice='$invoice'";
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

  <div class="col-2"><a href="listpreorder.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
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
										<?php 
				           if($idpoproduk==62 and $hak <>'admin') {
				            echo "<p>PO Sudah Ditutup</p>";
				          }
				          else {
				          ?>
	
		<!--  <p class=""><h6 style="font-family:verdana;" class="ridge">Klik Nomor invoice untuk melihat invoice Pre-Order yang sedang diproses maupun sudah selesai </h6></p><br> 
		      
		      <pre>
<form method="get">
<label>Cari Produk PO</label>

<input type="text" name="namaproduk">  <button type="submit" class="btn btn-primary" name="cari">Cari</button>  <button type="submit" class="btn btn-primary" name="tampil">Tampil Semua</button></form></pre>-->  
			<p align="center"><strong>SALES INVOICE</strong></p>
			<p align="center"><strong><?php echo $data['namapo']; ?></strong></p><br>
			<p align="left">Nama Mitra  : <?php echo $_SESSION["mitraagen"]["namamitra"]; ?> </p>
			<p align="left">Alamat  : <?php echo $_SESSION["mitraagen"]["alamat"]; ?> </p>
			

			<?php
					// Include / load file koneksi.php
					include "koneksi.php";
					
					$idmitramarketer=$_SESSION['mitraagen']['idmitramarketer'];
					$idpoproduk=$_GET["id"];
					$no=1;
					$jumlah=0;
					$subtotal=0;
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql2 = mysqli_query($koneksi, "SELECT poproduk.namapo,pokategori.namakategori,podetail.variant,pomitra.idpomitra,pomitra.jumlah,pomitra.invoice,pomitra.total,podetail.harga,pomitra.custom
					FROM poproduk inner JOIN pokategori inner join podetail inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk and pokategori.idpo=pomitra.idpo and podetail.idpodetail=pomitra.idpodetail WHERE pomitra.idmitramarketer='$idmitramarketer' and pomitra.idpoproduk='$idpoproduk' and pomitra.invoice='$invoice'");
					
					$data2 = mysqli_fetch_array($sql2) // Ambil semua data dari hasil eksekusi $sql
					?>
			<p align="left">No Invoice  : <?php echo $data2['invoice']; ?> </p>
			<p align="left">
      <b> Sisa Stock: </b>
              <?php
            //initialize total
              $idpoproduk = 62;
          
            $sql = "Select * from pokategori where idpoproduk='$idpoproduk'";
            $query = $koneksi->query($sql);
              while($stok = $query->fetch_assoc()){
                ?>
                
<?php echo $stok['namakategori'] ?> (<?php echo $stok['stok'] ?>) |
  <?php $stok= $stok['stok']; } ?>
 </p>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>No</th>
						<th>Ubah Qty</th>
					     <th>List Nama</th>
						<th>Qty</th>
						<th>Nama Barang</th>
					    <th>Satuan</th>
					    <th>Jumlah</th>
					    <!--<th>Opsi</th>-->
					</tr>
					<?php
					// Include / load file koneksi.php
					include "koneksi.php";
					
					$idmitramarketer=$_SESSION['mitraagen']['idmitramarketer'];
					$idpoproduk=$_GET["id"];
					$no=1;
					$jumlah=0;
					$subtotal=0;
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT poproduk.namapo,pokategori.namakategori,podetail.variant,pomitra.idpomitra,pomitra.idpo,pomitra.jumlah,pomitra.invoice,pomitra.total,podetail.harga,pomitra.custom
					FROM poproduk inner JOIN pokategori inner join podetail inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk and pokategori.idpo=pomitra.idpo and podetail.idpodetail=pomitra.idpodetail WHERE pomitra.idmitramarketer='$idmitramarketer' and pomitra.idpoproduk='$idpoproduk'");
					
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td class="align-middle"><?php echo $no++; ?></td>
							<form method="POST">
							                    	<input type="hidden" name="harga" value=<?php echo $data['harga']; ?>>
							                    <input type="hidden" name="idpomitra" value=<?php echo $data['idpomitra']; ?>>
							                    <input type="hidden" name="idpo" value=<?php echo $data['idpo']; ?>>
							                    <td class="align-middle">
							                    <input type="text" name="jmlh" size="1">
							                    <button type="submit" class="btn btn-primary" name="tambah">+</button>  
							                    <button type="submit" class="btn btn-danger" name="kurang">-</button>
							                   </td>
							                   <td class="align-middle"><textarea name="custom"><?php echo $data['custom']; ?></textarea>
							                    <button type="submit" class="btn btn-success btn-xs" name="edit2">Ubah</button>
							                   </td>
		                    </form>
							<td class="align-middle"><?php echo $data['jumlah']; ?></td>
							<td class="align-middle"><?php echo $data['variant']; ?></td>
							<td class="align-middle"><?php echo $data['harga']; ?></td>
							<td class="align-middle"><?php echo $data['total']; ?></td>
						<?php
							//$sum=array_sum($data['jumlah']);
                            $idpomitra=array($data['idpomitra']);						
							$jumlah=$jumlah+$data['total'];
							$invoice=$data['invoice'];
							//$subtotal=$subtotal+$jumlah;
							?>
							<?php
					}
					
					?>
		                    
		                     <?php

		                     if(isset($_POST["tambah"])){
                              $idpomitra= $_POST['idpomitra'];
					               			$idpo=$_POST["idpo"];
					               			$jmlh=$_POST["jmlh"];
			                       	$harga=$_POST["harga"];    
			                       	$total=$jmlh*$harga;
			                       
			                        $sql = "SELECT stok from pokategori where idpo='$idpo'";
						                      $query = $koneksi->query($sql);
							                  $sisa = $query->fetch_assoc();
					                          if($sisa['stok']>=$jmlh){
        					                   $koneksi->query("UPDATE pomitra set jumlah=jumlah+'$jmlh' where idpomitra='$idpomitra';");
        					                   $koneksi->query("UPDATE pomitra set total=jumlah*'$harga' where idpomitra='$idpomitra';");
        					                   $koneksi->query("UPDATE pokategori set stok=stok-'$jmlh' where idpo='$idpo'");
        					                   	echo "<script>alert('data berhasil diubah');</script>";
		                                        echo "<script>location='ubahpomikicustom.php?id=$idpoproduk&invoice=$invoice';</script>";
        					                     }
        					                     else{
        					                    echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
		                                        echo "<script>location='ubahpomikicustom.php?id=$idpoproduk&invoice=$invoice';</script>";
        					                     }
                            } 
        					  elseif(isset($_POST["kurang"])){
        					        $idpomitra= $_POST['idpomitra'];
					               $idpo=$_POST["idpo"];
					               $jmlh=$_POST["jmlh"];
			                       $harga=$_POST["harga"];    
			                       $total=$jmlh*$harga;
			                       
        					                   $koneksi->query("UPDATE pomitra set jumlah=jumlah-'$jmlh' where idpomitra='$idpomitra';");
        					                   $koneksi->query("UPDATE pomitra set total=jumlah*'$harga' where idpomitra='$idpomitra';");
        					                   $koneksi->query("UPDATE pokategori set stok=stok+'$jmlh' where idpo='$idpo'");
        					                   	echo "<script>alert('data berhasil diubah');</script>";
		                                        echo "<script>location='ubahpomikicustom.php?id=$idpoproduk&invoice=$invoice';</script>";
        					                     } 
                          
        					    elseif(isset($_POST["edit2"])){
	
	                                 include "koneksi.php";
					               $idpomitra= $_POST['idpomitra'];
					               $custom=$_POST["custom"];
        					                   $koneksi->query("UPDATE pomitra set custom='$custom' where idpomitra='$idpomitra';");
        					                   	echo "<script>alert('data berhasil diubah');</script>";
		                                        echo "<script>location='ubahpomikicustom.php?id=$idpoproduk&invoice=$invoice';</script>";
        					                     }                  
        					   elseif(isset($_POST["hapus"])){
	
	                                 include "koneksi.php";
					               $idpomitra= $_POST['idpomitra'];
					               $koneksi->query("delete from pomitra where idpomitra='$idpomitra';");
        		                   	echo "<script>alert('data berhasil diubah');</script>";
        		                   	echo "<script>location='ubahpom.php?id=$idpo';</script>";
        					                     }                  
                                 
                            ?>
		                    
							
						
							
						</tr>
					
				</table><br>
				            <!--<p align="left">Qty  <?php //echo $sum; ?> </p>-->
				            <p align="right">JUMLAH  Rp. <?php echo number_format($jumlah); ?> </p>
				            <?php $diskon=10/100*$jumlah;
				                  $subtotal=$jumlah-$diskon; ?>
				            <p align="right">Diskon Marketer 10% Rp. -<?php echo number_format($diskon); ?> </p><br>  
				            <p align="right">TOTAL  Rp. <?php echo number_format($subtotal); ?> </p>
				          
		                   <a class="btn btn-primary" href="datapom.php?id=<?php echo $idpoproduk ?>&invoice=<?php echo $invoice ?>">Simpan</a>
		                		            
				          <!--  <p align="left" size="1">Note:   PO Bergo Julang menggunakan Akad Istishna (Bayar 50% di awal 50% di akhir) Esitimasi Pemgiriman insyaallah mulai tanggal 25 Agustus 2020 jika lebih cepat dari pengerjaan akan dikabari sebelumnya Barrakallahu Fii Kum </p>
			--></div>
		<?php } ?>	                   
		</div>
	</body>
</html>