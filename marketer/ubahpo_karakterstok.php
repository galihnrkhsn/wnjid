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

  $query2 = "SELECT 
  bukapo.custom
  FROM bukapo
  WHERE bukapo.idpoproduk='$idpoproduk'
  and bukapo.jenis_po ='PO Karakter Stok'
  and bukapo.custom IS NOT NULL
  ";
  $sql2 = mysqli_query($koneksi, $query2);  
  $data2 = mysqli_fetch_array($sql2);  

  $datacustom=$data2['custom'];
  $result_explode = explode('|', $datacustom);
  $karakter=$result_explode[0];
  $kapital=$result_explode[1];
  $huruf = '';
  if ($kapital=="Ya") {
    $huruf = 'text-transform:uppercase';
  }

  ?> 

 <title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| WNJ </title>
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
			<p align="center"><strong>SALES INVOICE</strong></p>
			<p align="center"><strong><?php echo $data['namapo']; ?></strong></p><br>
			<p align="left">Nama Mitra  : <?php echo $_SESSION["mitraagen"]["namaagen"]; ?> </p>
			<!-- <p align="left">Alamat  : <?php echo $_SESSION["admin_mitra"]["alamat"]; ?> </p> -->
			<p align="left">No Invoice  : <?php echo $invoice; ?> </p>
			<p align="left">
    | <b> Sisa Stock: </b>
 <div class="row">
              <?php
            $sql = "SELECT * from pokategori where idpoproduk='$idpoproduk' ORDER BY namakategori";
            $query = $koneksi->query($sql);
              while($stok = $query->fetch_assoc()){
                ?>

<div class="col-2">
   <?php echo $stok['namakategori']; ?>
</div>
<div class="col-2">
    (<?php echo $stok['stok']; ?>)
</div>
<br>
<?php } ?>
</div>
 			<form method="POST">	
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>No</th>
						<th>Custom</th>
					     
						<th>Qty/pcs</th>
						<th>Nama Barang</th>
					    <th>Satuan</th>
					    <th>Jumlah</th>
					    
					</tr>
					<?php
					$no=1;
					$sql = mysqli_query($koneksi, "SELECT pokategori.namakategori,
															podetail.variant,
															pomitra.idpomitra,
															pomitra.idpo,
															pomitra.jumlah,
															pomitra.invoice,
															pomitra.total,
															podetail.harga,
															pomitra.custom,
															pomitra.font
													FROM pomitra 
													inner JOIN pokategori on pokategori.idpo=pomitra.idpo 
													inner join podetail on podetail.idpodetail=pomitra.idpodetail
													WHERE pomitra.invoice='$invoice' 
													ORDER BY pomitra.idpomitra ASC");
					
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td class="align-middle"><?php echo $no++; ?></td>
							<td class="align-middle">
							<input type="text" name="custom[]" class='form-control' value="<?php echo $data['custom']; ?>" required maxlength='<?= $karakter; ?>' style='<?= $huruf; ?>'>
		                    <input type="hidden" name="idpomitra[]" value="<?php echo $data['idpomitra']; ?>">
							</td>
		                   
		                    
							<td class="align-middle"><input type='number' class='form-control' name='jumlah[]' min='0' max="1" required placeholder='Qty/pcs' value="<?php echo $data['jumlah']; ?>"></td>
							<td class="align-middle"><?php echo $data['variant']; ?></td>
							<td class="align-middle"><?php echo $data['harga']; ?></td>
							<td class="align-middle"><?php echo $data['total']; ?></td>
							
													<?php					
							$jumlah=$jumlah+$data['total'];
							?>
		                  			<?php } ?>  
							
						</tr>
		
				</table>

				<br>
				            <!--<p align="left">Qty  <?php //echo $sum; ?> </p>-->
				            <!-- <p align="right">JUMLAH  Rp. <?php echo number_format($jumlah); ?> </p>
				            <?php $diskon=35/100*$jumlah;
				                  $subtotal=$jumlah-$diskon; ?>
				            <p align="right">Diskon DB 35% Rp. -<?php echo number_format($diskon); ?> </p><br>  
				            <p align="right">TOTAL  Rp. <?php echo number_format($subtotal); ?> </p> -->
				          
		                   <button class="btn btn-success" type="submit" name="simpan">Ubah</button>
		                   <a class="btn btn-primary" href="datapom.php?id=<?php echo $idpoproduk; ?>&invoice=<?php echo $invoice; ?>">Simpan</a>
		        </form>  
		    </div>	               
		</div>
			

<?php
	if(isset($_POST["simpan"])){

		$idpomitra= $_POST['idpomitra'];
		$custom=$_POST['custom']; 
		$jumlah=$_POST["jumlah"]; 
		$jmlhcustom=count($idpomitra);

			for($y=0;$y<$jmlhcustom;$y++){
			  	$namanya = addslashes($custom[$y]);
				if(!preg_match("/^\S{1,}$/", $namanya)){
					echo "<script>alert('Nama Custom ($namanya) tidak boleh mengandung spasi ...! ');</script>";
					return false;
				}
			} 

                       for($x=0;$x<$jmlhcustom;$x++){
                       	$namanya = addslashes($custom[$x]);
                       	  if ($kapital=="Ya") {
						    $namanya = strtoupper(addslashes($custom[$x]));
						  }
						$query_variant = "SELECT podetail.harga, podetail.idpo, pomitra.jumlah
					                  FROM pomitra
					                  JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
					                  WHERE pomitra.idpomitra='$idpomitra[$x]'";
					  	$sql_variant = mysqli_query($koneksi, $query_variant);  
					  	$data_variant = mysqli_fetch_array($sql_variant);
					  	$harga = $data_variant['harga'];
					  	$idpo = $data_variant['idpo'];  
					  	$jmlh_lama = $data_variant['jumlah'];  
					  	$total=$jumlah[$x]*$harga;

					  	$sql = "SELECT stok from pokategori where idpo='$idpo'";
						$query = $koneksi->query($sql);
						$sisa = $query->fetch_assoc(); 
						$stok=$sisa["stok"];

						$selisih[$x]=$jumlah[$x]-$jmlh_lama;

						if($selisih[$x]<=$stok){
		                   $koneksi->query("UPDATE pomitra set custom='$namanya', jumlah='$jumlah[$x]', total='$total'  WHERE idpomitra='$idpomitra[$x]';");
		                   $hasil=$stok-$selisih[$x];
		                   $sqlstok = $koneksi->query("UPDATE pokategori set stok='$hasil' where idpo='$idpo'");
						}
							echo "<script>alert('data po berhasil diubah');</script>";
	                        echo "<script>location='ubahpo_karakterstok.php?id=$idpoproduk&invoice=$invoice';</script>";
		                   	}
	}
           
			?>	

<br>
<br>			                     
	</body>
</html>