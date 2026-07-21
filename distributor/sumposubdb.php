<?php 
session_start();

include 'koneksi.php'; 

if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

  $idadmin=$_SESSION["admin_mitra"]["idadmin"];

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>WNJ</title>


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

			<p align="center"><strong>SALES INVOICE</strong></p>
			<p align="center"><strong><?php echo $data['namapo']; ?></strong></p><br>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>No</th>
						<th>Invoice</th>
						<th>Nama Barang</th>
					    <th>Satuan</th>					    
						<th>Qty</th>
					    <th>Total</th>
					    
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
					$sql = mysqli_query($koneksi, "SELECT poproduk.namapo,
						pokategori.namakategori,
						podetail.variant,
						pomitra.idpomitra,
						pomitra.jumlah,
						pomitra.invoice,
						pomitra.total,
						pomitra.invoice,
						podetail.harga 
					FROM `pomitra` inner join poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
					inner join pokategori on pomitra.idpo=pokategori.idpo
					inner join podetail on pomitra.idpodetail=podetail.idpodetail
					LEFT JOIN mitraagen on pomitra.idmitraagen=mitraagen.idmitraagen 
					LEFT JOIN mitrareseller on pomitra.idmitrareseller=mitrareseller.idmitrareseller 
					LEFT JOIN mitramarketer on pomitra.idmitramarketer=mitramarketer.idmitramarketer 
					where (mitraagen.idadmin='$idadmin' or 
							mitrareseller.idadmin='$idadmin' or 
							mitramarketer.idadmin='$idadmin') 
					and poproduk.idpoproduk='$idpoproduk' 
					and pomitra.jumlah>0
					and pomitra.status <> 'Belum Acc DB'");
					
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td class="align-middle"><?php echo $no++; ?></td>
							<td class="align-middle"><?php echo $data['invoice']; ?></td>
							<td class="align-middle"><?php echo $data['variant']; ?></td>
							<td class="align-middle">Rp. <?php echo number_format($data['harga']); ?></td>							
							<td class="align-middle"><?php echo $data['jumlah']; ?></td>
							<td class="align-middle">Rp. <?php echo number_format($data['total']); ?></td>
							

	<?php
					$sum+= $data['jumlah'];
					$total += $data['jumlah']*$data['harga'];
							?>
						
							
						</tr>
					<?php
					}
					
					?>
				</table><br>
<table style="float: right;width: 100%">
 <tbody  style="float: right;">
    <tr>
        <th style="padding-bottom: 5%;">Total Qty</th>
        <td style="padding-bottom: 5%;">:</td>
        <td style="padding-bottom: 5%;">
        <?php echo $sum; ?>

        </td>
    </tr>
    <tr>
        <th>Jumlah</th>
        <td>:</td>
        <td>      
Rp. <?php echo number_format($total); ?>                     
        </td>
    </tr>
                    
<?php 
  $persen=35;
  $diskon=35/100*$total;
  $subtotal=$total+$ongkir+$dropship-$diskon;  
                    ?>
    <tr>
        <th>Diskon DB <?= $persen; ?>%</th>
        <td>:</td>
        <td>
          Rp. <?php echo number_format($diskon); ?>               
        </td>
    </tr>
      
    <tr>
        <th style="padding-bottom: 5%;">Total Bayar</th>
        <td style="padding-bottom: 5%;">:</td>
        <td style="padding-bottom: 5%;">
Rp. <?php echo number_format($subtotal); ?>           
        </td>
    </tr>
          
</tbody>
</table> 	
		<br><br>

</div>
		         
		</div>
	</body>
</html>