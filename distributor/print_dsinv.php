<?php 
session_start();

include 'koneksi.php'; 

if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

 $invoice=$_GET["id"];  

  $queryds = "SELECT podropship.namapengirim,
            podropship.tlppengirim,
                    podropship.namapenerima,
            podropship.tlppenerima,
                    podropship.alamatpenerima,
                    podropship.ekspedisi,
                    podropship.layanan,
            podropship.ongkir,
            podropship.dropship,
            podropship.invoice,
                    tb_ro_provinces.province_name,
                    tb_ro_cities.city_name,
                    tb_ro_subdistricts.subdistrict_name
            FROM podropship 
            LEFT JOIN tb_ro_provinces on podropship.provinsi = tb_ro_provinces.province_id
            LEFT JOIN tb_ro_cities on podropship.kota = tb_ro_cities.city_id
            LEFT JOIN tb_ro_subdistricts on podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
            WHERE podropship.invoice='$invoice'";
  $sqlds = mysqli_query($koneksi, $queryds);  
  $data_ds = mysqli_fetch_array($sqlds); 

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<style>

</style>
<body>
<center>
 <h2 style="margin-bottom: 0px;">Invoice</h2>
 <label><?= $data_ds['invoice']; ?></label>
</center>

<table style="float:left">
    <tr>
        <td>Pengirim</td>
        <td>:</td>
        <td>
            <?= $data_ds['namapengirim']; ?> / <?= $data_ds['tlppengirim']; ?>         
        </td>
    </tr>  
    <tr>
        <td>Penerima</td>
        <td>:</td>
        <td>
            <?= $data_ds['namapenerima']; ?> / <?= $data_ds['tlppenerima']; ?>          
        </td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>:</td>
        <td>
            <p>
            <?= $data_ds['alamatpenerima']; ?>
            <br>
            <?= $data_ds['province_name']; ?>, <?= $data_ds['city_name']; ?>, <?= $data_ds['subdistrict_name']; ?>
            </p>
        </td>
    </tr>    
</table> 
<br> 
<br> 
<br> 
    <table class="table" style="width: 100%;border-collapse: collapse;">
        <thead>
            <tr>
                <th style="border: 2px solid;">#</th>
                <th style="border: 2px solid;width:30%">Nama Produk</th>
                <th style="border: 2px solid;">Jumlah</th>
                <th style="border: 2px solid;">Harga</th>
                <th style="border: 2px solid;">Total</th>
            </tr>
        </thead>
        <tbody>
<?php 
$no=1;
      $ambil=$koneksi->query("SELECT podetail.harga,podetail.variant, pomitra.jumlah, pomitra.invoice, pomitra.idpodetail
                                FROM pomitra
                                JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                WHERE pomitra.invoice= '$invoice'
                                AND pomitra.jumlah>0"); 
      while($data=$ambil->fetch_assoc()){
          $idpodetail = $data['idpodetail'];
          $invoice = $data['invoice'];
?>            
            <tr>
                <td style="border: 2px solid;"><?= $no++;?></td>
                <td style="border: 2px solid;"><?= $data['variant'];?></td>
                <td style="border: 2px solid;text-align:center"><?= $data['jumlah'];?></td>
                <td style="border: 2px solid;">Rp. <?= number_format($data['harga']);?></td>
                <td style="border: 2px solid;">Rp. <?= number_format($total = $data['jumlah']*$data['harga']);?>
                </td>
            </tr>
<?php 
$qty += $data['jumlah'];
$totalbayar +=$total;
?>            
      <?php } ?>            
        </tbody>
    </table>
<br> 
<br> 
<table style="float:right;">
    <tr>
        <td style="float : left">Qty</td>
        <td>:</td>
        <th style="float : left">
            <?=$qty; ?>           
        </th>
    </tr>  
    <tr>
        <td style="float : left">Total Bayar</td>
        <td>:</td>
        <th style="float : left">
            Rp. <?php echo number_format($totalbayar); ?>           
        </th>
    </tr>    
</table> 
	<script>
		window.print();
	</script>
	
</body>
</html>