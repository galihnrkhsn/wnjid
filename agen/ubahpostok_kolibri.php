<?php 
session_start();

include 'koneksi.php'; 

if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}
 $invoice = $_GET['invoice'];
  $idadmin=$_SESSION["mitraagen"]["idmitraagen"];
  $query = "SELECT poproduk.idpoproduk,
  					poproduk.namapo,
  					pomitra.tgl,
  					pomitra.waktu,
  					pomitra.status
  			FROM poproduk 
  			inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
  			WHERE pomitra.invoice='$invoice'";
  $sqlpo = mysqli_query($koneksi, $query);  
  $datapo = mysqli_fetch_array($sqlpo);
 
 $idpoproduk = $datapo['idpoproduk'];

   $querypengiriman = "SELECT podropship.namapengirim,
            podropship.tlppengirim,
  					podropship.namapenerima,
            podropship.tlppenerima,
  					podropship.alamatpenerima,
  					podropship.ekspedisi,
  					podropship.layanan,
            podropship.ongkir,
            podropship.dropship,
  					tb_ro_provinces.province_name,
  					tb_ro_cities.city_name,
  					tb_ro_subdistricts.subdistrict_name
  			FROM podropship 
  			LEFT JOIN tb_ro_provinces on podropship.provinsi = tb_ro_provinces.province_id
			LEFT JOIN tb_ro_cities on podropship.kota = tb_ro_cities.city_id
			LEFT JOIN tb_ro_subdistricts on podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
  			WHERE podropship.invoice='$invoice'";
  $sqlpengiriman = mysqli_query($koneksi, $querypengiriman);  
  $datapengiriman = mysqli_fetch_array($sqlpengiriman);

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
 <title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| Wanoja </title>
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

  <div class="col-2"><a href="datapokolibri?invoice=<?= $invoice; ?>"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>PRE ORDER</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br><br>

<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #eee center center;
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


      <center> 
      	<h3><?= $datapo['namapo']; ?></h3> 
		<h4>Inv. #<?= $invoice; ?></h4> 
      </center>

<!--  <div class="row">
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
</div> -->

			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
					 <th>No</th>	
           <th>Ubah Qty</th>					
           <th>Qty</th>
					 <th>Nama Barang</th>	
					</tr>
					<?php
					$no=1;
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT pokategori.namakategori,
													podetail.variant,
													pomitra.idpomitra,
													pomitra.idpo,
													pomitra.jumlah,
													pomitra.invoice,
													pomitra.total,
													podetail.harga 
													FROM pomitra 
													JOIN podetail on podetail.idpodetail=pomitra.idpodetail 
													inner JOIN pokategori on pokategori.idpo=pomitra.idpo 
													WHERE pomitra.invoice='$invoice' 
													ORDER BY pomitra.idpodetail
													");
					
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
					<tr>
						<td><?php echo $no++; ?></td>
            <td class="align-middle">
<form method="POST">            	
				<input type="hidden" name="harga" value=<?php echo $data['harga']; ?>>
				<input type="hidden" name="idpo" value=<?php echo $data['idpo']; ?>>
				<input type="hidden" name="idpomitra" value=<?php echo $data['idpomitra']; ?>>
				<input type="hidden" name="jumlahsebelum" value=<?php echo $data['jumlah']; ?>>            	
                <input type="number" min="0" name="jmlh" style="width: 80px;" class="form-control"> 
                <button type="submit" class="btn btn-primary" name="tambah">+</button>  
                <button type="submit" class="btn btn-danger" name="kurang">-</button>
</form>                
            </td>
            <td><?php echo $data['jumlah']; ?></td>         
						<td><?php echo $data['variant']; ?></td>
					</tr>

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
			                       
			                        $sql = "Select stok from pokategori where idpo='$idpo'";
						                      $query = $koneksi->query($sql);
							                  $sisa = $query->fetch_assoc();
					                          if($sisa['stok']>=$jmlh){
        					                   $koneksi->query("UPDATE pomitra set jumlah=jumlah+'$jmlh' where idpomitra='$idpomitra';");
        					                   $koneksi->query("UPDATE pomitra set total=jumlah*'$harga' where idpomitra='$idpomitra';");
        					                   $koneksi->query("UPDATE pokategori set stok=stok-'$jmlh' where idpo='$idpo'");
        					                   	echo "<script>alert('data berhasil diubah');</script>";
		                                        echo "<script>location='ubahpostok_kolibri?invoice=$invoice';</script>";
        					                     }
        					                     else{
        					                    echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
		                                        echo "<script>location='ubahpostok_kolibri?invoice=$invoice';</script>";
        					                     }
                            }

        					  else if(isset($_POST["kurang"])){
        					        $idpomitra= $_POST['idpomitra'];
					               $idpo=$_POST["idpo"];
					               $jmlh=$_POST["jmlh"];
			                       $harga=$_POST["harga"];    
			                       $total=$jmlh*$harga;

			                   $jumlahsebelum=$_POST["jumlahsebelum"];
					               $subjumlah = $jumlahsebelum - $jmlh;
					               if ($subjumlah<0) {
					               	echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
		                                        echo "<script>location='ubahpostok_kolibri?invoice=$invoice';</script>";
					               }else{
					               	
        					                   $koneksi->query("UPDATE pomitra set jumlah=jumlah-'$jmlh' where idpomitra='$idpomitra';");
        					                   $koneksi->query("UPDATE pomitra set total=jumlah*'$harga' where idpomitra='$idpomitra';");
        					                   $koneksi->query("UPDATE pokategori set stok=stok+'$jmlh' where idpo='$idpo'");
        					                   	echo "<script>alert('data berhasil diubah');</script>";
		                                        echo "<script>location='ubahpostok_kolibri?invoice=$invoice';</script>";
					               }
			                       
        					                     }                                
 ?>						
				</table>
			</div>
			<div>
			
			</div>			
<center><a href="datapokolibri?invoice=<?= $invoice; ?>" class="btn btn-primary">simpan</a></center>
<br>
<br>
<br>


	</body>
</html>
