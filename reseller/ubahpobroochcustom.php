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
  $idmitrareseller=$_SESSION["mitraagen"]["idmitrareseller"];
  $query = "SELECT COUNT(*) as jumlah,poproduk.idpoproduk,poproduk.namapo,poproduk.status FROM poproduk inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk WHERE poproduk.idpoproduk='$idpoproduk' AND pomitra.idmitra='$idadmin' and pomitra.invoice='$invoice'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);
  ?> 

 <title>Mitra <?php echo $_SESSION['mitraagen']['namagen']; ?>| WNJ </title>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		
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
			<p align="left">Nama Mitra  : <?php echo $_SESSION["mitraagen"]["namaagen"]; ?> </p>
			<p align="left">Alamat  : <?php echo $_SESSION["mitraagen"]["alamat"]; ?> </p>
			

			<?php
					// Include / load file koneksi.php
					include "koneksi.php";
					
          $idmitrareseller=$_SESSION["mitraagen"]["idmitrareseller"];
					$idpoproduk=$_GET["id"];
					$no=1;
					$jumlah=0;
					$subtotal=0;
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql2 = mysqli_query($koneksi, "SELECT poproduk.namapo,pokategori.namakategori,podetail.variant,pomitra.idpomitra,pomitra.jumlah,pomitra.invoice,pomitra.total,podetail.harga,pomitra.custom
					FROM poproduk 
          inner JOIN pokategori 
          inner join podetail 
          inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk and pokategori.idpo=pomitra.idpo and podetail.idpodetail=pomitra.idpodetail
           WHERE pomitra.idmitrareseller='$idmitrareseller' and pomitra.idpoproduk='$idpoproduk' and pomitra.invoice='$invoice'");
					
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
						<th>Custom</th>
					     
						<th>Qty/pcs</th>
						<th>Nama Barang</th>
					    <th>Satuan</th>
					    <th>Jumlah</th>

					    
					    <th>Opsi</th>
					    
					</tr>
					<?php
					// Include / load file koneksi.php
					include "koneksi.php";
					
					$idmitrareseller=$_SESSION['mitraagen']['idmitrareseller'];
					$idpoproduk=$_GET["id"];
					$no=1;
					$jumlah=0;
					$subtotal=0;
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT poproduk.namapo,pokategori.namakategori,podetail.variant,pomitra.idpomitra,pomitra.idpo,pomitra.jumlah,pomitra.invoice,pomitra.total,podetail.harga,pomitra.custom,pomitra.font
					FROM poproduk 
          inner JOIN pokategori 
          inner join podetail 
          inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk and pokategori.idpo=pomitra.idpo and podetail.idpodetail=pomitra.idpodetail 
          WHERE pomitra.idmitrareseller='$idmitrareseller' and pomitra.idpoproduk='$idpoproduk' and pomitra.invoice='$invoice' ORDER BY pomitra.idpomitra ASC");
					
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td class="align-middle"><?php echo $no++; ?></td>
							<!-- <td class="align-middle">
								<button type="submit" class="btn btn-success btn-xs" name="edit2">Ubah</button>
							</td> -->
							
							<td class="align-middle">
							<input type="text" name="custom[]" class='form-control' value="<?php echo $data['custom']; ?>" required maxlength='1'>
		                    <input type="hidden" name="idpomitra[]" value="<?php echo $data['idpomitra']; ?>">
							</td>
		                   
		                    
							<td class="align-middle"><input type='number' class='form-control' name='jumlah[]' min='0' required placeholder='Qty/pcs' value="<?php echo $data['jumlah']; ?>"></td>
							<td class="align-middle"><?php echo $data['variant']; ?></td>
							<td class="align-middle"><?php echo $data['harga']; ?></td>
							<td class="align-middle"><?php echo $data['total']; ?></td>
							
					    <td class="align-middle">
								<a class="btn btn-danger" href="hapusbrooch.php?id=<?php echo $data['idpomitra']; ?>" 
									onclick="return confirm('Yakin Ingin Menghapus Data <?php echo $data['variant']; ?> dengan nama custom <?php echo $data['custom']; ?> ?');">Hapus</a>
							</td>
							
													<?php					
							$jumlah=$jumlah+$data['total'];
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
				          
		                   <button class="btn btn-primary" type="submit" name="simpan">Simpan</button>
		        </form>        		            
				          <!--  <p align="left" size="1">Note:   PO Bergo Julang menggunakan Akad Istishna (Bayar 50% di awal 50% di akhir) Esitimasi Pemgiriman insyaallah mulai tanggal 25 Agustus 2020 jika lebih cepat dari pengerjaan akan dikabari sebelumnya Barrakallahu Fii Kum </p>
			--></div>
		    <?php } ?>		               
		</div>
			<?php
                if(isset($_POST["simpan"])){

                         include "koneksi.php";
		               $idpomitra= $_POST['idpomitra'];
		               $custom=$_POST['custom']; 
  						$jumlah=$_POST["jumlah"]; 

                       $jmlhcustom=count($idpomitra);

for($x=0;$x<$jmlhcustom;$x++){
  $namanya = addslashes($custom[$x]);

$query_variant = "SELECT podetail.harga, podetail.idpo
                  FROM pomitra
                  JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                  WHERE pomitra.idpomitra='$idpomitra[$x]'";
  $sql_variant = mysqli_query($koneksi, $query_variant);  
  $data_variant = mysqli_fetch_array($sql_variant);
  $harga = $data_variant['harga'];
  $idpo = $data_variant['idpo'];  
  $total=$jumlah[$x]*$harga;

            if(!preg_match("/^\S{1,}$/", $namanya)){
              echo "<script>alert('Nama Custom ($namanya) tidak boleh mengandung spasi ...! ');</script>";
              return false;
            }
} 

                       for($x=0;$x<$jmlhcustom;$x++){
                       	$namanya = addslashes($custom[$x]);
		                   $koneksi->query("UPDATE pomitra set custom='$namanya', jumlah='$jumlah[$x]', total='$total'  WHERE idpomitra='$idpomitra[$x]';");
							
		                   	echo "<script>alert('data po berhasil disimpan ');</script>";
	                        echo "<script>location='datapom.php?id=$idpoproduk&invoice=$invoice';</script>";
		                   	}
	                     }
           
			?>	

<br>
<br>			                     
	</body>
</html>