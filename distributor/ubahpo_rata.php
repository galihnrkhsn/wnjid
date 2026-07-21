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

 $idpoproduk = $_GET['id'];
  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
  $query = "SELECT COUNT(*) as jumlah,poproduk.idpoproduk,poproduk.namapo,poproduk.status FROM poproduk inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk WHERE poproduk.idpoproduk='$idpoproduk' AND pomitra.idmitra='$idadmin'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);
  ?> 

 <title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| Wanoja </title>
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

  <div class="col-2"><a href="listnewpo.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>PRE ORDER</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

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

		<div class="container" align="center">

	
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
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql2 = mysqli_query($koneksi, "SELECT
						pomitra.invoice
					FROM pomitra WHERE pomitra.idmitra='$idadmin' and pomitra.idpoproduk='$idpoproduk'");
					
					$data2 = mysqli_fetch_array($sql2);
					$invoice = $data2['invoice']
					?>
			<p align="left">No Invoice  : <?php echo $invoice; ?> </p>
			
			 <b>	Sisa Stock Kain: </b>
 <div class="row">
              <?php
            //initialize total
              $id = $_GET['id'];
          
            $sql = "SELECT * from pokategori where idpoproduk='$id' ORDER BY namakategori";
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

			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>No</th>
						<th>Ubah Qty</th>
						<th>Qty</th>
						<th>Nama Barang</th>
					    <th>Satuan</th>
					    <th>Jumlah</th>
					    <!--<th>Opsi</th>-->
					</tr>
					<?php
					// Include / load file koneksi.php
					include "koneksi.php";
					
					$idadmin=$_SESSION['admin_mitra']['idadmin'];
					$idpoproduk=$_GET["id"];
					$no=1;
					$jumlah=0;
					$subtotal=0;

					$sql = mysqli_query($koneksi, "SELECT poproduk.namapo,
													pokategori.namakategori,
													podetail.variant,
													pomitra.idpomitra,
													pomitra.idpo,
													pomitra.jumlah,
													pomitra.invoice,
													pomitra.total,
													podetail.harga 
					FROM poproduk 
					inner JOIN pokategori 
					inner join podetail 
					inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
					and pokategori.idpo=pomitra.idpo 
					and podetail.idpodetail=pomitra.idpodetail 
					WHERE pomitra.idmitra='$idadmin' 
					and pomitra.idpoproduk='$idpoproduk'");
					
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td class="align-middle"><?php echo $no++; ?></td>
							<form method="POST">
								<input type="hidden" name="harga" value=<?php echo $data['harga']; ?>>
								<input type="hidden" name="idpo" value=<?php echo $data['idpo']; ?>>
								<input type="hidden" name="idpomitra" value=<?php echo $data['idpomitra']; ?>>
								<input type="hidden" name="jumlahsebelum" value=<?php echo $data['jumlah']; ?>>
							<td class="align-middle">
								<input type="number" min="0" name="jmlh" style="width: auto;"> 
								<button type="submit" class="btn btn-primary" name="tambah">+</button>  
								<button type="submit" class="btn btn-danger" name="kurang">-</button>
							</td>
		                    </form>
							<td class="align-middle"><?php echo $data['jumlah']; ?></td>
							<td class="align-middle"><?php echo $data['variant']; ?></td>
							<td class="align-middle"><?php echo $data['harga']; ?></td>
							<td class="align-middle"><?php echo $data['total']; ?></td>
											<?php
					}
					
					?>
		                     <?php
		                     include "koneksi.php";
		                    
                            if(isset($_POST["tambah"])){
                                 $idpomitra= $_POST['idpomitra'];
					               $idpo=$_POST["idpo"];
					               $jmlh=$_POST["jmlh"];
			                       $harga=$_POST["harga"];    
			                       $total=$jmlh*$harga;
			                       
			                        $sql_jumlah = "SELECT SUM(jumlah) as sjmlh FROM pomitra WHERE invoice = '$invoice' and idpomitra<>'$idpomitra'";
			                        $query_jumlah = $koneksi->query($sql_jumlah);
			                        $sjumlah = $query_jumlah->fetch_assoc();

			                        $sum_jumlah = $sjumlah['sjmlh'];

			                        if ($sum_jumlah+$jmlh>4) {
			                        	echo "<script>alert('Jumlah seluruh barang melebihi 4 Pcs');</script>";
		             					echo "<script>location='ubahpo_rata?id=$idpoproduk';</script>";
			                        }else{
			                        $sql = "SELECT stok from pokategori where idpo='$idpo'";
						            $query = $koneksi->query($sql);
							        $sisa = $query->fetch_assoc();
					                          if($sisa['stok']>=$jmlh){
        					                   $koneksi->query("UPDATE pomitra set jumlah=jumlah+'$jmlh' where idpomitra='$idpomitra';");
        					                   $koneksi->query("UPDATE pomitra set total=jumlah*'$harga' where idpomitra='$idpomitra';");
        					                   $koneksi->query("UPDATE pokategori set stok=stok-'$jmlh' where idpo='$idpo'");
        					                   	echo "<script>alert('data berhasil diubah');</script>";
		                                        echo "<script>location='ubahpo_rata.php?id=$idpoproduk';</script>";
        					                     }
        					                     else{
        					                    echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
		                                        echo "<script>location='ubahpo_rata.php?id=$idpoproduk';</script>";
        					                     }
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
		                                        echo "<script>location='ubahpo_rata.php?id=$idpoproduk';</script>";
					               }else{
					               	
        					                   $koneksi->query("UPDATE pomitra set jumlah=jumlah-'$jmlh' where idpomitra='$idpomitra';");
        					                   $koneksi->query("UPDATE pomitra set total=jumlah*'$harga' where idpomitra='$idpomitra';");
        					                   $koneksi->query("UPDATE pokategori set stok=stok+'$jmlh' where idpo='$idpo'");
        					                   	echo "<script>alert('data berhasil diubah');</script>";
		                                        echo "<script>location='ubahpo_rata.php?id=$idpoproduk';</script>";
					               }
			                       
        					                     }                  
                                 
                            ?>
		                    
							
						</tr>

				</table><br>
<a class="btn btn-primary" href="datapo.php?id=<?php echo $idpoproduk ?>">Simpan</a>
		</div>
		</div>
	</body>
</html>