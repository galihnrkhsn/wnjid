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
$hak = $_GET['hak'];
 $idpoproduk = $_GET['id'];
  $invoice = $_GET['invoice'];
  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
  $query = "SELECT COUNT(*) as jumlah,poproduk.idpoproduk,poproduk.namapo,poproduk.status FROM poproduk inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk WHERE poproduk.idpoproduk='$idpoproduk' AND pomitra.idmitra='$idadmin' and pomitra.invoice='$invoice'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);
  ?> 

<!DOCTYPE html>
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
						<?php 
				           if($idpoproduk==62 and $hak =='admin') {
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
					$sql2 = mysqli_query($koneksi, "SELECT poproduk.namapo,pokategori.namakategori,podetail.variant,pomitra.idpomitra,pomitra.jumlah,pomitra.invoice,pomitra.total,podetail.harga,pomitra.custom
					FROM poproduk inner JOIN pokategori inner join podetail inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk and pokategori.idpo=pomitra.idpo and podetail.idpodetail=pomitra.idpodetail WHERE pomitra.idmitra='$idadmin' and pomitra.idpoproduk='$idpoproduk' and pomitra.invoice='$invoice'");
					
					$data2 = mysqli_fetch_array($sql2) // Ambil semua data dari hasil eksekusi $sql
					?>
			<p align="left">No Invoice  : <?php echo $data2['invoice']; ?> </p>
			<p align="left">
<!--       <b> Sisa Stock: </b>
              <?php
            //initialize total
              $idpoproduk = 62;
          
            $sql = "Select * from pokategori where idpoproduk='$idpoproduk'";
            $query = $koneksi->query($sql);
              while($stok = $query->fetch_assoc()){
                ?>
                
<?php echo $stok['namakategori'] ?> (<?php echo $stok['stok'] ?>) |
  <?php $stok= $stok['stok']; } ?> -->
 </p>
 			<form method="POST">	
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>No</th>
						<!-- <th>Opsi</th> -->
						<th>Nama Custom</th>
					     <th>Font Teks</th>
						<th>Qty</th>
						<th>Nama Barang</th>
					    <th>Satuan</th>
					    <th>Jumlah</th>
					    <th>Opsi</th>
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
					$sql = mysqli_query($koneksi, "SELECT poproduk.namapo,pokategori.namakategori,podetail.variant,pomitra.idpomitra,pomitra.idpo,pomitra.jumlah,pomitra.invoice,pomitra.total,podetail.harga,pomitra.custom,pomitra.font
					FROM poproduk inner JOIN pokategori inner join podetail inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk and pokategori.idpo=pomitra.idpo and podetail.idpodetail=pomitra.idpodetail WHERE pomitra.idmitra='$idadmin' and pomitra.idpoproduk='$idpoproduk' and pomitra.invoice='$invoice' order by podetail.idpodetail asc");
					
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td class="align-middle"><?php echo $no++; ?></td>
							<!-- <td class="align-middle">
								<button type="submit" class="btn btn-success btn-xs" name="edit2">Ubah</button>
							</td> -->
							
							<td class="align-middle">
<?php 
	 $datacustom=$data['custom'];
	  $result_explode = explode('|', $datacustom);
    $baris1=$result_explode[0];
    $baris2=$result_explode[1];

 ?>								
<form method="POST">
	<label>Baris Ke 1</label>
							<input type="text" class="form-control" name="custom1" value="<?php echo $baris1; ?>">
<label>Baris Ke 2</label>							
							<input type="text"  class="form-control" name="custom2" value="<?php echo $baris2; ?>">
							<input type="hidden" name="harga" value="<?php echo $data['harga']; ?>">
		                    <input type="hidden" name="idpomitra" value="<?php echo $data['idpomitra']; ?>">
		                    <input type="hidden" name="idpo" value="<?php echo $data['idpo']; ?>">
		                    <input type="hidden" name="invoice" value="<?php echo $data['invoice']; ?>">
							                    <!--
							                    <input type="text" name="jmlh" size="1">
							                    <button type="submit" class="btn btn-primary" name="tambah">+</button>  
							                    <button type="submit" class="btn btn-danger" name="kurang">-</button> -->
							</td>
		                   <td class="align-middle">
		                   	<select name="fontku">
	                   		<option value="<?php echo $data['font']; ?>" selected><?php echo $data['font']; ?></option>          
							<option value='a amazing mother'>a amazing mother</option>
							<option value='a awal Ramadan'>a awal Ramadan</option>
							<option value='blackjack'>blackjack</option>
							<option value='halaney demo'>halaney demo</option>

		                   	</select>
		                    
		                   </td>
		                    
							<td class="align-middle"><?php echo $data['jumlah']; ?></td>
							<td class="align-middle"><?php echo $data['variant']; ?></td>
							<td class="align-middle">Rp. <?php echo number_format($data['harga']); ?></td>
							<td class="align-middle">Rp. <?php echo number_format($data['total']); ?></td>
							<td class="align-middle">
<button type="submit" class="btn btn-success" name="edit">Ubah</button>								
								<a class="btn btn-danger" href="hapusrompi.php?id=<?php echo $data['idpomitra']; ?>" 
									onclick="return confirm('Yakin Ingin Menghapus Data <?php echo $data['variant']; ?> ?');">Hapus</a>
							</td>
</form>							
													<?php
							//$sum=array_sum($data['jumlah']);
                            $idpomitra=array($data['idpomitra']);						
							$jumlah=$jumlah+$data['total'];
							$invoice=$data['invoice'];
							//$subtotal=$subtotal+$jumlah;
							?>
		                  			<?php } ?>  
							
						</tr>
		
				</table>

				<br>
				            <!--<p align="left">Qty  <?php //echo $sum; ?> </p>-->
				            <p align="right">JUMLAH  Rp. <?php echo number_format($jumlah); ?> </p>
				            <?php $diskon=35/100*$jumlah;
				                  $subtotal=$jumlah-$diskon; ?>
				            <p align="right">Diskon DB 35% Rp. -<?php echo number_format($diskon); ?> </p><br>  
				            <p align="right">TOTAL  Rp. <?php echo number_format($subtotal); ?> </p>
				          
		                  <a href="datapom.php?id=<?= $idpoproduk; ?>&invoice=<?= $invoice; ?>" class="btn btn-primary">Simpan</a>
		        </form>        		            
				          <!--  <p align="left" size="1">Note:   PO Bergo Julang menggunakan Akad Istishna (Bayar 50% di awal 50% di akhir) Esitimasi Pemgiriman insyaallah mulai tanggal 25 Agustus 2020 jika lebih cepat dari pengerjaan akan dikabari sebelumnya Barrakallahu Fii Kum </p>
			--></div>
		    <?php } ?>		               
		</div>
			<?php
                if(isset($_POST["edit"])){

                         include "koneksi.php";
		               $idpomitra= $_POST['idpomitra'];
		               $invoice=$_POST['invoice'];
		               $custom1=addslashes($_POST['custom1']);
		               $custom2=addslashes($_POST['custom2']);
		               $fontku=$_POST['fontku'];

		                   $sql = $koneksi->query("UPDATE pomitra set custom='$custom1|$custom2',font='$fontku'  WHERE idpomitra='$idpomitra'");
		                   	
if ($sql) {
echo "<script>alert('data po berhasil disimpan ');</script>";
	                        echo "<script>location='ubahporompicustom.php?id=$idpoproduk&invoice=$invoice';</script>";
}else{
	echo "<script>alert('data po gagal disimpan ');</script>";
	                        echo "<script>location='ubahporompicustom.php?id=$idpoproduk&invoice=$invoice';</script>";
}
		                   	
		                   	
	                     }
           
			?>	                     
	</body>
</html>