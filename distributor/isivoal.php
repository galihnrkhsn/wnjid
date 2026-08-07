<?php 
session_start();

include 'koneksi.php'; 
// include 'floatingbutton.php';
if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}
$invoice = $_GET['id'];

  $sql = "SELECT SUM(jumlah) as jumlah FROM ordermitra WHERE invoice='$invoice'";
  $query = $koneksi->query($sql);
  $order = $query->fetch_assoc();
  $jmlh_semua = $order['jumlah'];

  $querypods2 = "SELECT * FROM hampers WHERE hampers.no_ds= '$invoice'";
  $sqlpods2 = mysqli_query($koneksi, $querypods2);  
  $datapods2 = mysqli_fetch_array($sqlpods2); 

$datanya = $datapods2['no_ds'];
?>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 

    
<style>

.navbaru {
   
    background: #eee   center center;
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
  
  </head>
  <body>
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="detailorder?id=<?= $invoice; ?>"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>Box Voal</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<!--================ NAVBARU END =================-->


<div class="container">
<table class="table table-bordered">
  <tr>
    <th>No</th>
    <th>Nama Barang</th>
    <th>QTY</th>
  </tr>

   
    <?php
   // $total=0;
    $nomorurut = 1;
    $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 ";
  $query = $koneksi->query($sql);
  while ($order = $query->fetch_assoc()){
    ?>
<tr>
  <td><?= $nomorurut++; ?></td>
  <td><?php echo $order['namaproduk']; ?> </td>
  <td><?php echo $order['jumlah'];?>Pcs</td>
</tr>    

    <?php $sum_jumlah+=$order['jumlah']; ?>
    <?php } ?>

</table> 

<?php if ($datanya): ?>
<?php 
      $ambil_box=$koneksi->query("SELECT *
                                FROM hampers
                                WHERE hampers.no_ds= '$invoice'
                                ORDER BY nobox ASC"); 
      while($data_box=$ambil_box->fetch_assoc()){
    $result_explode = explode('|', $data_box['ucapan']);
    $dari=$result_explode[0];    
    $kepada=$result_explode[1];    
    $ucapan=$result_explode[2];  
    $result_explode1 = explode('|', $data_box['idpodetail']);
    $detail1=$result_explode1[0];    
    $detail2=$result_explode1[1];    
    $detail3=$result_explode1[2];            
 ?>
<div class=""> 
<label>Box <?= $data_box['nobox'] ?></label>
<hr style="  margin-top: 0px;">
<style type="text/css">

</style>
<table style="width: 100%; ">
 
  <tr style="">
    <th style="">Item</th>
    <th style="width: 50%">Kartu Ucapan</th>
  </tr>
<tr>
  <td style="vertical-align: top;">
    <?php echo str_replace("|","<br>",$data_box['idpodetail']); ?>
  </td>  
  <td>
<table>
  <tr style="border-bottom: ;">
    <th>Dari</th>
    <td>:</td>
    <td><?= $dari; ?></td>
  </tr>
  <tr style="border-bottom: ;">
    <th>Kepada</th>    
    <td>:</td>
    <td><?= $kepada; ?></td>
  </tr>  
  <tr >
    <th style=" vertical-align: top;">Ucapan</th>    
    <td>:</td>
    <td><?= $ucapan; ?>
      
      <br>
     <!--  <a href="kartuucapan?id=<?php echo $data_box[id]; ?>" target="_blank()" >Kartu Ucapan</a>  -->
    </td>
  </tr>   
</table>    
  </td>

</tr>   
</table>

</div>
<br>
<?php } ?>

<?php else: ?>

  <form method="post">
<?php  
$angka = $jmlh_semua/3;
for ($x = 1; $x <= $angka; $x++) {?>
<label>Box : <?= $x ?></label>
<input type="hidden" name="box[]" value="<?= $x ?>" style="width: 40px" readonly>
<br>
<div class="col-4"> 
<label>Format Kartu Ucapan</label>  
<div class="form-group">
    <label>Dari</label>
    <input type="text" class="form-control" name="dari[]">
</div>    
<div class="form-group">
    <label>Kepada</label>
    <input type="text" class="form-control" name="kepada[]">
</div>    
<div class="form-group">
    <label>Ucapan</label>
    <textarea class="form-control" name="keterangan[]"></textarea>
</div>   
</div>
<br>
<label>Item 1</label>
  <select style="width: auto;" class="form-control" name="idpodetail1[]" required>
    <option value="">~ Pilih Produk ~</option>
                  
    <?php 
      $ambil=$koneksi->query("SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0
                                ");     

                  while($data=$ambil->fetch_assoc()){
                  ?>
    <option><?php echo $data['namaproduk']; ?></option>
                  <?php } ?>  
  </select>                  
<br>
<label>Item 2</label>
  <select style="width: auto;" class="form-control" name="idpodetail2[]" required>
    <option value="">~ Pilih Produk ~</option>
                  
    <?php 
      $ambil=$koneksi->query("SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0
                                ");     

                  while($data=$ambil->fetch_assoc()){
                  ?>
    <option><?php echo $data['namaproduk']; ?></option>
                  <?php } ?>  
  </select>                  
<br>
<label>Item 3</label>
  <select style="width: auto;" class="form-control" name="idpodetail3[]" required>
    <option value="">~ Pilih Produk ~</option>
                  
    <?php 
      $ambil=$koneksi->query("SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0
                                ");     

                  while($data=$ambil->fetch_assoc()){
                  ?>
    <option><?php echo $data['namaproduk']; ?></option>
                  <?php } ?>  
  </select>                  
<br>
<?php } ?>
    <div class='form-group row'>    
        <div class="col-sm-3">    
         <button type="submit" name="simpan" class="btn btn-info">Kirim</button>
        </div>
    </div>

</form>
 <?php
  if(isset($_POST["simpan"])){

  $idpodetail1=$_POST["idpodetail1"];
  $idpodetail2=$_POST["idpodetail2"];
  $idpodetail3=$_POST["idpodetail3"];
  $dari=$_POST["dari"];
  $kepada=$_POST["kepada"];
  $keterangan=$_POST["keterangan"];
  $box=$_POST["box"];
  $jmlh=count($box);
date_default_timezone_set('Asia/Jakarta');
$today = date("Y-m-d H:i:s");   
      for($x=0;$x<$jmlh;$x++){
        $keterangannya = $dari[$x].'|'.$kepada[$x].'|'.$keterangan[$x];
        $produk = $idpodetail1[$x].'|'.$idpodetail2[$x].'|'.$idpodetail3[$x];
        $sqlds = $koneksi->query("INSERT INTO hampers (id,no_ds,idpodetail,nobox,ucapan) 
                                          values (null,'$invoice','$produk','$box[$x]','$keterangannya') ");  
          // echo "<script>alert('$box[$x], $dari[$x], $kepada[$x], $keterangan[$x], $idpodetail1[$x], $idpodetail2[$x], $idpodetail3[$x]');</script>";
      }
      if ($sqlds) {
        echo "<script>alert('Data Berhasil Disimpan');</script>";
        echo "<script>location='detailorder?id=$invoice';</script>";
      }else{
        echo "<script>location='detailorder?id=$invoice';</script>";
      }
      
  }    
?>  

<?php endif; ?>

</div>        
  </body>
</html>


