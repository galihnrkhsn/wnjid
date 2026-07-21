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

 $no_ds=$_GET["id"];  

  $queryds = "SELECT * FROM podropship WHERE no_ds='".$no_ds."'";
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
 <label><?= $data_ds['no_ds']; ?></label>
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
            <?= $data_ds['provinsi']; ?>, <?= $data_ds['kota']; ?>, <?= $data_ds['kecamatan']; ?>
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