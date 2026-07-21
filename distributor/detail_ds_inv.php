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

 $no_ds=$_GET["no_ds"];  
$idpoproduk=$_GET["id"];      

  $querynamapo = "SELECT namapo FROM poproduk WHERE idpoproduk='".$idpoproduk."'";
  $sqlnamapo = mysqli_query($koneksi, $querynamapo);  
  $datanamapo = mysqli_fetch_array($sqlnamapo); 

$namapo=$datanamapo['namapo'];

  $query_ds = "SELECT podropship.namapengirim, 
                      podropship.tlppengirim, 
                      podropship.namapenerima, 
                      podropship.tlppenerima, 
                      podropship.alamatpenerima,
                      podropship.invoice,
                      podropship.idpoproduk,
                      tb_ro_provinces.province_name as provinsi,
                      tb_ro_cities.city_name as kota,
                      tb_ro_subdistricts.subdistrict_name as kecamatan 

                      FROM podropship 
                      LEFT JOIN tb_ro_provinces on podropship.provinsi = tb_ro_provinces.province_id
                      LEFT JOIN tb_ro_cities on podropship.kota = tb_ro_cities.city_id
                      LEFT JOIN tb_ro_subdistricts on podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
                      WHERE podropship.no_ds='$no_ds'";
  $sql_ds = mysqli_query($koneksi, $query_ds);  
  $data_ds = mysqli_fetch_array($sql_ds); 

$invocenya = $data_ds['invoice'];
$idnya = $data_ds['idpoproduk'];
?>
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
   

		

	</head>
	<body>
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="listds?id=<?= $idnya; ?>&invoice=<?= $invocenya; ?>"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>LIST DROPSHIP</p></div>
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


	<div class="container">
<p>Invoice <strong><?= $data_ds['no_ds']; ?></strong></p>
<strong>Pengirim</strong>
<p>
<?= $data_ds['namapengirim']; ?> / <?= $data_ds['tlppengirim']; ?>    
</p>

<strong>Penerima</strong>
<p>
<?= $data_ds['namapenerima']; ?> / <?= $data_ds['tlppenerima']; ?>    
</p>

<strong>Alamat Penerima</strong>
<p><?= $data_ds['alamatpenerima']; ?></p>
<p><?= $data_ds['provinsi']; ?>, <?= $data_ds['kota']; ?>, <?= $data_ds['kecamatan']; ?></p>


    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <?php if ($idnya<>186): ?>
                <th>Harga</th>
                <th>Total</th>
                <?php endif ?>
            </tr>
        </thead>
        <tbody>
<?php 
$no=1;
      $ambil=$koneksi->query("SELECT podetail.harga,podetail.variant, pods.jumlah, pods.invoice, pods.idpodetail, pods.id
                                FROM pods
                                JOIN podetail ON podetail.idpodetail = pods.idpodetail
                                WHERE pods.no_ds= '$no_ds'
                                AND pods.jumlah>0
                                ORDER BY pods.id ASC"); 
      while($data=$ambil->fetch_assoc()){
          $idpodetail = $data['idpodetail'];
          $invoice = $data['invoice'];
?>            
            <tr>
                <td><?= $no++;?></td>
                <td><?= $data['variant'];?></td>
                <td><?= $data['jumlah'];?></td>
                <?php if ($idnya<>186): ?>
                <td>Rp. <?= number_format($data['harga']);?></td>
                <td>Rp. <?= number_format($total = $data['jumlah']*$data['harga']);?>
                </td>
                <?php endif ?>
            </tr>
<?php 
$qty += $data['jumlah'];
$totalbayar +=$total;
?>            
      <?php } ?>            
        </tbody>
    </table>
<table style="float:right;">
    <tr>
        <th style="padding-bottom: 5%;">Qty</th>
        <td style="padding-bottom: 5%;">:</td>
        <td style="padding-bottom: 5%;">
            <?=$qty; ?>           
        </td>
    </tr> 
<?php if ($idnya<>186): ?>     
    <tr>
        <th style="padding-bottom: 5%;">Total Bayar</th>
        <td style="padding-bottom: 5%;">:</td>
        <td style="padding-bottom: 5%;">
            Rp. <?php echo number_format($totalbayar); ?>           
        </td>
    </tr>  
<?php endif ?>      
</table>
    
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>    
    <center>
<a href="detail_ds?no_ds=<?php echo $no_ds; ?>" class="btn btn-success btn-xs">Ubah List Produk</a>
<?php if ($idnya<>186): ?>
  
<a href="print_nods?id=<?php echo $no_ds; ?>" target="_blank" class="btn btn-info btn-xs">Print Inv</a>
<?php endif ?>
<!--<a href=" class="btn btn-success btn-xs">Ubah List Produk</a>-->
    </center>
  </div>
<?php include "settingdatatables.php"; ?>    
	</body>
</html>

