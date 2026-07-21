<?php 
session_start();

include 'koneksi.php'; 

if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
  echo "<script>location='login.php';</script>";
  header('location:login.php');
  exit();
}

 $invoice = $_GET['invoice'];
//   $idadmin=$_GET['idadmin'];
$idadmin=$_SESSION["admin_mitra"]["idadmin"];
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

  $querybukapo = "SELECT bukapo.idbpo,
                              bukapo.jenis_mitra,
                              bukapo.jenis_po,
                              bukapo.idpoproduk,
                              bukapo.tgl,
                              bukapo.tgl_dropship,
                              bukapo.tgl_bayar,
                              bukapo.status,
                              poproduk.namapo 
                              FROM bukapo inner join poproduk on bukapo.idpoproduk = poproduk.idpoproduk 
                              WHERE poproduk.idpoproduk='$idpoproduk' and (bukapo.jenis_mitra = 'Semua Mitra' or bukapo.jenis_mitra = 'Distributor')";
  $sqlbukapo = mysqli_query($koneksi, $querybukapo);  
  $databukapo = mysqli_fetch_array($sqlbukapo);

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
 <title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| Wanoja </title>
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


	<div class="container">


      <center> 
      	<h3><?= $datapo['namapo']; ?></h3> 
		<h4>Inv. #<?= $invoice; ?></h4> 
      </center>

<div style="float: left;">
    <b>Info Pesanan</b><br>
    <table>
    	<tr>
    		<th>Tanggal</th>
    		<td>:</td>
    		<td><?php echo $datapo['tgl']; ?></td>
    	</tr>
    	<tr>
    		<th>Status</th>
    		<td>:</td>
    		<td><?php echo $datapo['status'] ?></td>
    	</tr>
    </table>
    <hr>	

    <b>Info Pengiriman</b><br>
    <table>
    	<tr>
    		<th>Pengirim</th>
    		<td>:</td>
    		<td><?php echo $datapengiriman['namapengirim'] ?>, <?php echo $datapengiriman['tlppengirim'] ?></td>
    	</tr>
    	<tr>
    		<th>Keluarga / Penerima</th>
    		<td>:</td>
    		<td><?php echo $datapengiriman['namapenerima'] ?>, <?php echo $datapengiriman['tlppenerima'] ?></td>
    	</tr>
    	<tr>
    		<th>Alamat</th>
    		<td>:</td>
    		<td><?php echo $datapengiriman['alamatpenerima'] ?>
          <br>
          <?= $datapengiriman['subdistrict_name'] ?>,
           
          <?= $datapengiriman['city_name'] ?>,

          <?= $datapengiriman['province_name'] ?> 
        </td>
    	</tr>    	
    	<tr>
    		<th>Ekspedisi</th>
    		<td>:</td>
    		<td><?php echo strtoupper($datapengiriman['ekspedisi']); ?> <?php echo strtoupper($datapengiriman['layanan']); ?></td>
    	</tr>    	
    </table>
<br>
<?php if ($datapengiriman['province_name']==""): ?>

<b>Alamat belum diisi. Silahkan isi alamat <a class='link' href='formdropship_kolibri?&id=<?= $invoice; ?>&idadmin=<?= $idadmin;?>'>disini.</a></b>	
<?php endif ?>
</div>
</div>

<div class="container">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Info Invoice</a></li>
          <li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Info Progres</a></li>
        </ul>

 <div class="tab-content">
    <div id="home" class="tab-pane fade in active show" role="tabpanel">  
      <br>        

			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>No</th>						
						<th>Nama Barang</th>						
					    <th>Satuan</th>
					    <th>Qty</th>
					    <th>Total</th>
					</tr>
					<?php
					$no=1;
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql = mysqli_query($koneksi, "SELECT podetail.variant,
														  podetail.harga,
														  pomitra.jumlah,
														  pomitra.idpomitra
													FROM pomitra 
													JOIN podetail on podetail.idpodetail=pomitra.idpodetail 
													WHERE pomitra.invoice='$invoice' 
													AND pomitra.jumlah>0");
					
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
					<tr>
						<td><?php echo $data['idpomitra']; ?></td>
						<td><?php echo $data['variant']; ?></td>
						<td>Rp. <?php echo number_format($data['harga']); ?></td>
						<td>
						    <form method="post">
						        <input type="hidden" name="idpomitra" value="<?php echo $data['idpomitra']; ?>" />
						        <input type="number" name="jumlah" value="<?php echo $data['jumlah']; ?>" />
						        <button class="btn btn-primary" type="submit" name="ubah_jumlah">Ubah QTY</button>
						    </form>
						</td>
						<td>Rp. <?php echo number_format($data['harga']*$data['jumlah']); ?></td>						
					</tr>
					<?php
					$sum+= $data['jumlah'];
					$total += $data['jumlah']*$data['harga'];
					}
					
					?>
				</table>
			</div>


			<div class="table-responsive">
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


  $ongkir = $datapengiriman['ongkir'];
  $dropship = $datapengiriman['dropship'];

  $subtotal=$total+$dropship+$ongkir-$diskon; 
  // $subtotal=$total-$diskon;  
  $dp1 = $subtotal * 50/100;
  $dp2 = $subtotal * 50/100;
if ($idpoproduk==153 or $idpoproduk==239) {
  $dp1 = $subtotal * 30/100;
  $dp2 = $subtotal * 25/100;
  $dp3 = $subtotal * 25/100; 
  $dp4 = $subtotal * 20/100; 
  }  
                    ?>

    <tr>
        <th>Ongkir</th>
        <td>:</td>
        <td>
Rp. <?php echo number_format($ongkir); ?>           
        </td>
    </tr> 
    <tr>
        <th >Dropship</th>
        <td >:</td>
        <td >
Rp. <?php echo number_format($dropship); ?>           
        </td>
    </tr>  
    <tr>
        <th style="padding-bottom: 5%;">Diskon DB <?= $persen; ?>%</th>
        <td style="padding-bottom: 5%;">:</td>
        <td style="padding-bottom: 5%;">
          - Rp. <?php echo number_format($diskon); ?>               
        </td>
    </tr>


    <tr>
        <th style="padding-bottom: 5%;">Total Bayar</th>
        <td style="padding-bottom: 5%;">:</td>
        <td style="padding-bottom: 5%;">
Rp. <?php echo number_format($subtotal); ?>           
        </td>
    </tr>
    <tr>
        <th>Payment 1 </th>
        <td>:</td>
        <td>
            <?php if($idpoproduk == 259) {?>
                Rp. <?php echo number_format(100000); ?>            
            <?php } else {?>
                Rp. <?php echo number_format($dp1); ?>           
            <?php } ?>
        </td>
    </tr>
    <tr>
        <th>Payment 2 </th>
        <td>:</td>
        <td>
            <?php if($idpoproduk == 259) {?>
                Rp. <?php echo number_format(30/100 * ($subtotal)); ?>            
            <?php } else {?>
                Rp. <?php echo number_format($dp2); ?>           
            <?php } ?>
        </td>
    </tr>
<?php if ($idpoproduk==153 or $idpoproduk==239) { ?>    
    <tr>
        <th>Payment 3</th>
        <td>:</td>
        <td>
Rp. <?php echo number_format($dp3); ?>           
        </td>
    </tr>   
    <tr>
        <th style="padding-bottom: 5%;">Payment 4</th>
        <td style="padding-bottom: 5%;">:</td>
        <td style="padding-bottom: 5%;">
Rp. <?php echo number_format($dp4); ?>           
        </td>
    </tr>       
<?php } ?>
<!--     <tr>
        <th>Konfirmasi Payment</th>
        <td></td>
        <td>        
        </td>
    </tr>    -->  
      <?php     

          $sqldp = mysqli_query($koneksi, "SELECT jmlhtransfer,jenis
                          FROM popembayaran 
                          WHERE invoice='$invoice' 
                          ");
          
          while($datadp = mysqli_fetch_array($sqldp)){
$payment += $datadp['jmlhtransfer'];
          ?>
    <?php 
         

     ?> 
<!--     <tr>
        <th><?= $datadp['jenis']; ?> </th>
        <td>:</td>
        <td>        
Rp. <?= number_format($datadp['jmlhtransfer']); ?> 
        </td>
    </tr>     --> 
<?php } ?>
    <tr>
        <th>Konfirmasi Payment</th>
        <td>:</td>
        <td>
Rp. <?= number_format($payment); ?>                
        </td>
    </tr>      
    <tr>
        <th>Sisa Tagihan</th>
        <td>:</td>
        <td>
<?php 
$sisa = $payment - $subtotal;


 ?>
          
Rp. 
<?php if ($sisa>0): ?>
+             
<?php endif ?> 
<?php echo number_format($sisa); ?> 
        </td>
    </tr>           
</tbody>
</table> 				
			</div>			

<?php 

  $harinya='+2 days'; 

date_default_timezone_set('Asia/Jakarta');
$tgl11 = $datapo['tgl'];// pendefinisian tanggal awal
$tgl22 = date('Y-m-d', strtotime($harinya, strtotime($tgl11)));
$sekarang = date('Y-m-d');

$tgl3 = new DateTime($tgl11);
$tgl4 = new DateTime($sekarang);
$jarak = $tgl4->diff($tgl3);
$jaraknya = $jarak->d; 
 ?>

<?php if ($datapo['status']=="Belum DP" and $idpoproduk!=239): ?>  
  <center>
   <a href="" data-toggle="modal" data-target="#modalView" class="btn btn-success btn-sm text-center">Ubah Variant</a>  
   <!--<a href="formpo_kolibri?invoice=<?= $invoice ?>" class="btn btn-primary btn-sm text-center">Tambah Variant</a>   -->
   <!--<a href="ubahpostok_kolibri?invoice=<?= $invoice ?>" class="btn btn-success btn-sm text-center">Ubah Variant</a> -->
    <a href="formpo_konin?invoice=<?= $invoice ?>" class="btn btn-primary btn-sm text-center">Tambah Variant</a>
  </center>





<?php endif ?>


<!-------Modal VIEW--------------->
        <div class="modal fade" id="modalView" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
            <!-----ModalHeader-------------->
              <div class="modal-header">
                <h4 class="modal-title" id="labelModalKu">Ubah Qty Variant</h4>
                  <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Tutup</span>
                  </button>
              </div>
            <!------ModalBody-------------->
              <form method="POST" enctype="multipart/form-data">          
                <div class="modal-body">
          <?php
          $sql_qty = mysqli_query($koneksi, "SELECT podetail.variant,
                              podetail.harga,
                              pomitra.jumlah,
                              pomitra.idpomitra
                          FROM pomitra 
                          JOIN podetail on podetail.idpodetail=pomitra.idpodetail 
                          WHERE pomitra.invoice='$invoice' 
                          AND pomitra.jumlah>0");
          
          while($data_qty = mysqli_fetch_array($sql_qty)){ // Ambil semua data dari hasil eksekusi $sql
          ?>                  
                  <div class="form-group">
                    <label><?= $data_qty['variant'] ?></label>
                    <input type="hidden" class="form-control" name="idpomitra[]" value="<?= $data_qty['idpomitra'] ?>">
                    <input type="number" class="form-control" name="jumlah[]" value="<?= $data_qty['jumlah'] ?>" placeholder="Qty">
                  </div>
<?php } ?>                
                </div>
              <!-------ModalFooter------------>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" name="simpan_qty">&plus; Simpan</button>          
                  <button type="button" class="btn btn-danger" data-dismiss="modal">&times; Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>  

<?php
  if(isset($_POST["simpan_qty"])){
      $idpomitra= $_POST['idpomitra'];
      $jumlah=$_POST["jumlah"];    
      $jmlhcustom=count($idpomitra);

      for($x=0;$x<$jmlhcustom;$x++){
        $sql = $koneksi->query("UPDATE pomitra set jumlah='$jumlah[$x]'  WHERE idpomitra='$idpomitra[$x]';");
        }

if ($sql) {
  echo "<script>alert('Data berhasil disimpan ');</script>";
  echo "<script>location='datapokolibri?invoice=$invoice';</script>";
}else{
  echo "<script>alert('Data gagal disimpan ');</script>";
  echo "<script>location='datapokolibri?invoice=$invoice';</script>";
}

  }
           
      ?>

  <?php if ($datapo['status']<>"Belum DP"): ?>
<center>
<?php  
 {
echo "<a class='btn btn-info' href='print_dsinv?id=$invoice' target=_blank()>Print Invoice</a>";    
             
               } ?>    


</center>

  <?php endif ?>
  
  
  <?php
  if(isset($_POST["ubah_jumlah"])){
      $idpomitra= $_POST['idpomitra'];
      $jumlah=$_POST["jumlah"];    

      $sql = $koneksi->query("UPDATE pomitra set jumlah='$jumlah'  WHERE idpomitra='$idpomitra'");

if ($sql) {
  echo "<script>alert('Data berhasil disimpan ');</script>";
  echo "<script>location='datapokolibri?invoice=$invoice';</script>";
}else{
  echo "<script>alert('Data gagal disimpan ');</script>";
  echo "<script>location='datapokolibri?invoice=$invoice';</script>";
}

  }
           
      ?>
  


<?php if ($datapo['status']=='Belum DP' or $datapo['status']=="Sudah Payment 1"): ?>
 <div class="alert alert-danger container mt-4" role="alert">
      <?php if ($datapengiriman['province_name']==""): ?>
 <p class="text-center">
  Silahkan isi alamat 
  <br>
  dan segera lakukan pembayaran Payment 1.
 </p>
<?php endif ?>

     <center>
      <button type="submit" class="btn btn-sm" name="cari" id="linkmiki">
      <?php if ($datapengiriman['province_name']==""): ?>
        <a class='btn btn-info btn-sm' href='formdropship_kolibri?&id=<?= $invoice; ?>'>Isi Alamat</a>
        <?php else: ?>
            <?php if ($datapo['status']=="Belum DP"): ?>
                    <p class="text-center">
                        Silahkan lalukan Payment 1 
                        <br>
                        Rp. <?php echo number_format(100000); ?> 
                    </p>           
                <a class='btn btn-info btn-sm' href='popembayaran.php?invoice=<?= $invoice ?>&total=<?= $idpoproduk == 259 ? 100000 : $dp1 ?>&bayar=<?= $subtotal ?>&idpo=<?= $idpoproduk ?>&jenis=Payment 1'>Konfimasi Pembayaran</a>
            <?php endif ?>
            
            <?php if ($datapo['status']=="Sudah Payment 1"): ?>
                <center>
                    <?php if($idpoproduk == 259) {?>
                        Rp. <?php echo number_format(30/100 * ($subtotal)); ?>
                        <a class="btn btn-primary" href="popembayaran.php?invoice=<?php echo $invoice.'&total='.(30/100 * ($subtotal)).'&idpo='.$idpoproduk.'&jenis=Payment 2'?>">Konfirmasi Payment 2</a>
                    <?php } else {?>
                        <?= "<a class='btn btn-primary' href='popembayaran.php?invoice=$invoice&total=$dp2&idpo=$idpoproduk&jenis=Payment 2'>Konfirmasi Payment 2</a>";?>
                    <?php } ?>
                </center>
            <?php endif ?>
            <?php if ($datapo['status']=="Sudah Payment 2"): ?>
                <center>
                    <?= "<a class='btn btn-primary' href='popembayaran.php?invoice=$invoice&total=$dp3&idpo=$idpoproduk&jenis=Payment 3'>Konfirmasi Payment 3</a>";?>    
                </center>
            <?php endif ?>  
            <?php if ($datapo['status']=="Sudah Payment 3"): ?>
                <center>
                    <?= "<a class='btn btn-primary' href='popembayaran.php?invoice=$invoice&total=$dp4&idpo=$idpoproduk&jenis=Payment 4'>Konfirmasi Payment 4</a>";?>    
                </center>
            <?php endif ?>  
      
      <?php endif ?>
       
      </button>
<!--      <p>-->
<!--  Invoice ini akan hilang dalam 1 x 24 jam-->
<!--  <br>-->
<!--  jika tidak ada konfirmasi Payment.-->
<!--</p>-->

      <!--<p id="link2" >Batas Waktu Pembayaran </p>-->

      <!--<p id="demomiki" style="color: red;"></p>-->
      <!--</center>-->
     
<?php 
date_default_timezone_set('Asia/Jakarta');
$tgl = $datapo['tgl'];
$waktu = $datapo['waktu'];
  $jumlahhari='+1 days';

// $tgl2 = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl))); //operasi penjumlahan tanggal sebanyak 6 hari  
$tgl2 = $databukapo['tgl_bayar'];

 ?>

<script>

// Mengatur waktu akhir perhitungan mundur
var countDownDatemiki= new Date("<?= $tgl2; ?> <?php //echo $waktu; ?>23:59:59").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDatemiki - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demomiki").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demomiki").innerHTML = "<a class='btn btn-danger btn-lg' href='pobatal.php?invoice=<?=$invoice; ?>'><i class='fa fa-times'></i> Batalkan Pesanan</a><br><p style='color: black;'>Batas Pembayaran Sudah Lewat</p>";
    // document.getElementById("demomiki").innerHTML = "";
      var x = document.getElementById("linkmiki");
      var y = document.getElementById("link2");
 
    y.style.display = "none";
    x.style.display = "none";
    }
}, 1000);
</script>
</div>
<?php endif ?>
              
		         
	</div>


<div id="menu1" class="tab-pane fade">


      <?php
          $sqlprogres = mysqli_query($koneksi, "SELECT 
                                            SUM(surat_jalan_po.progres) as progres,
                                            SUM(pomitra.jumlah) as jumlah
                                            FROM surat_jalan_po
                                            inner join pomitra on pomitra.idpomitra=surat_jalan_po.idpomitra
                                            inner join podetail on podetail.idpodetail=surat_jalan_po.idpodetail
                                        WHERE pomitra.idmitra='$idadmin' 
                                        and pomitra.idpoproduk='$idpoproduk' 
                                        and pomitra.jumlah>0 
                                        and surat_jalan_po.invoice='$invoice' 
                                        and (surat_jalan_po.status='Checker' OR surat_jalan_po.status='Ambil Barang')
                                        GROUP BY pomitra.idpodetail
                                        ");
          
          $dataprogres = mysqli_fetch_array($sqlprogres) // Ambil semua data dari hasil eksekusi $sql                    
          ?>  

<?php 
          $sqlinvoice = mysqli_query($koneksi, "SELECT 
                                            SUM(pomitra.jumlah) as jumlah
                                            FROM pomitra
                                           
                                        WHERE pomitra.idmitra='$idadmin' 
                                        and pomitra.idpoproduk='$idpoproduk' 
                                        and pomitra.jumlah>0 
                                        ");
          
          $datainvoice = mysqli_fetch_array($sqlinvoice) // Ambil semua data dari hasil eksekusi $sql   

 ?>          
       
        <table class="table table-bordered">
          <tr>
            <th>No</th>
            <th>Nama Barang</th>
              <th>Qty PO</th>
              <th>Progres</th>
              <th>Sisa</th>
              <th>Status</th>
          </tr>
          <?php
          include "koneksi.php";
          
          $no=1;

          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $sql = mysqli_query($koneksi, "SELECT 
                                                podetail.variant,
                                                pomitra.idpomitra,
                                                pomitra.jumlah,
                                                pomitra.invoice,
                                                pomitra.total,
                                                podetail.harga,
                                                pomitra.custom,
                                                SUM(surat_jalan_po.progres) as progres

                                        FROM pomitra 
                                        inner join podetail on podetail.idpodetail=pomitra.idpodetail 
                                        left join surat_jalan_po on pomitra.idpomitra=surat_jalan_po.idpomitra
                                        WHERE pomitra.idmitra='$idadmin' 
                                            and pomitra.idpoproduk='$idpoproduk' 
                                            and pomitra.invoice = '$invoice'
                                            and pomitra.jumlah>0
                                            GROUP BY pomitra.idpodetail                                          
                                            ORDER BY podetail.variant asc
                                        ");
          
          while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
            $id = $data['idpomitra'];
            $sisa = $data['jumlah']-$data['progres'];
          ?>
            <tr>
              <td class="align-middle"><?php echo $no++; ?></td>
              
              <td class="align-middle"><?php echo $data['variant']; ?></td>
              <td class="align-middle"><?php echo $data['jumlah']; ?></td>
                <td class="align-middle">
                  <?php if ($data['progres']==""): ?>
                    0
                    <?php else: ?>
                  <?php echo $data['progres']; ?>
                  <?php endif ?>
                    
                  </td>

                <td class="align-middle"><?= $sisa; ?></td>
                <td class="align-middle">
                  <?php if ($sisa==0): ?>
                      <span class="badge bg-success">Selesai</span>
                      <?php else: ?>
                      <span class="badge bg-warning">Progress</span>
                        
                  <?php endif ?>

                </td>

            </tr>

<?php
  $sum_progres+=$data['progres'];
  $sum_jumlah+=$data['jumlah'];
  $sum_sisa+=$sisa;
  $jumlah_progres=$jumlah_progres+$totalnya;
  $invoice=$data2['invoice'];
?>            
          <?php
          }
          
          ?>
          <tfoot>
            <tr>
              <td colspan="2">Total</td>
              <td><?= $sum_jumlah; ?></td>
              <td><?= $sum_progres; ?></td>
              <td><?= $sum_sisa; ?></td>
              <td></td>
            </tr>
          </tfoot>
        </table>
  </div>



</div>
    </div> <!-- tutup tab -->

  </div> <!-- tutupcontainer  -->

<br>
<br>
<br>
	</body>
</html>
