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
 <title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| Wanoja </title>

		<!-- Load File bootstrap.min.css yang ada difolder css -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">


<link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/dataTables.bootstrap.css">
		<!-- Load File bootstrap.min.css yang ada difolder css -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
		
	</head>
	<body>
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="listpreorder"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
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
    		<td><?php echo $datapengiriman['namapengirim'] ?></td>
    	</tr>
    	<tr>
    		<th>Keluarga / Penerima</th>
    		<td>:</td>
    		<td><?php echo $datapengiriman['namapenerima'] ?></td>
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
    		<td><?php echo strtoupper($datapengiriman['ekspedisi']); ?></td>
    	</tr>    	
    </table>
<br>
<?php if ($datapengiriman['province_name']==""): ?>

<b>Alamat belum diisi. Silahkan isi alamat <a class='link' href='formdropship_kolibri?&id=<?= $invoice; ?>'>disini.</a></b>	
<?php endif ?>
</div>

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
														  pomitra.jumlah
													FROM pomitra 
													JOIN podetail on podetail.idpodetail=pomitra.idpodetail 
													WHERE pomitra.invoice='$invoice' 
													AND pomitra.jumlah>0");
					
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
					<tr>
						<td><?php echo $no++; ?></td>
						<td><?php echo $data['variant']; ?></td>
						<td>Rp. <?php echo number_format($data['harga']); ?></td>
						<td><?php echo $data['jumlah']; ?></td>
						<td>Rp. <?php echo number_format($data['harga']*$data['jumlah']); ?></td>						
					</tr>
					<?php
					$sum+= $data['jumlah'];
					$total += $data['jumlah']*$data['harga'];
					}
					
					?>
				</table>
			</div>
			<div>
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
  $persen=25;
  $diskon=25/100*$total;


  $ongkir = $datapengiriman['ongkir'];
  $dropship = $datapengiriman['dropship'];

  $subtotal=$total+$ongkir+$dropship-$diskon;   
  // $subtotal=$total-$diskon; 
                    ?>

    <tr>
        <th>Ongkir</th>
        <td>:</td>
        <td>
Rp. <?php echo number_format($ongkir); ?>           
        </td>
    </tr> 
    <tr>
        <th>Dropship</th>
        <td>:</td>
        <td>
Rp. <?php echo number_format($dropship); ?>           
        </td>
    </tr>  
    <tr>
        <th style="padding-bottom: 5%;">Diskon <?= $persen; ?>%</th>
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
</tbody>
</table> 				
			</div>			

<?php 

  $harinya='+1 days'; 

date_default_timezone_set('Asia/Jakarta');
$tgl11 = $datapo['tgl'];// pendefinisian tanggal awal
$tgl22 = date('Y-m-d', strtotime($harinya, strtotime($tgl11)));
$sekarang = date('Y-m-d');

$tgl3 = new DateTime($tgl11);
$tgl4 = new DateTime($sekarang);
$jarak = $tgl4->diff($tgl3);
$jaraknya = $jarak->d; 
 ?>
<?php if ($datapo['status']=="Belum Acc DB"): ?>  
  <center>
<!--<a href="" data-toggle="modal" data-target="#modalView" class="btn btn-success btn-sm text-center">Ubah Variant</a> -->
<!--  <a href="formpo_kolibri?invoice=<?= $invoice ?>" class="btn btn-primary btn-sm text-center">Tambah Variant</a>-->
  <!--<a href="ubahpostok_kolibri?invoice=<?= $invoice ?>" class="btn btn-success btn-sm text-center">Ubah Variant</a> -->
  <!--  <a href="formpo_konin?invoice=<?= $invoice ?>" class="btn btn-primary btn-sm text-center">Tambah Variant</a>   -->
  </center>



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

<?php endif ?>

<?php if ($datapo['status']=='Belum Acc DB'): ?>
	

     <center><button type="submit" class="btn btn-sm" name="cari" id="linkmiki">
      <?php if ($datapengiriman['province_name']==""): ?>
        <a class='btn btn-info btn-sm' href='formdropship_kolibri?&id=<?= $invoice; ?>'>Isi Alamat</a>
        <?php else: ?>
          <h4>Tunggu Konfirmasi DB</h4>
          
      <?php endif ?>
       
      </button>
      <!--<p id="link2" >Batas Waktu Konfirmasi</p>-->

      <!--<p id="demomiki" style="color: red;"></p>-->
      <br>
      </center>
<?php 
date_default_timezone_set('Asia/Jakarta');
$tgl = $datapo['tgl'];
$waktu = $datapo['waktu'];
  $jumlahhari='+1 days';

// $tgl2 = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl))); //operasi penjumlahan tanggal sebanyak 6 hari  
$tgl2 = $databukapo['tgl_bayar']

	// $result_explode = explode(':', $waktu);
	// $jam=$result_explode[0];
	// $menit=$result_explode[1];
	// $detik=$result_explode[2];

	// $result_explode_tgl = explode('-', $tgl);
	// $tahun=$result_explode_tgl[0];
	// $bulan=$result_explode_tgl[1];
	// $hari=$result_explode_tgl[2];	

	// $hasil_jam = $jam+4;

// if ($hasil_jam==25) {
// 	$hasil_jam=01;
// 	$hari = $hari+1;
// }
// if ($hasil_jam==26) {
// 	$hasil_jam=02;
// 	$hari = $hari+1;
// }
// if ($hasil_jam==27) {
// 	$hasil_jam=03;
// 	$hari = $hari+1;
// }
// if ($hasil_jam==28) {
// 	$hasil_jam=04;
// 	$hari = $hari+1;
// }
// 	$waktunya = $hasil_jam.':'.$menit.':'.$detik;
// 	$tanggalnya = $tahun.'-'.$bulan.'-'.$hari;

 ?>

<script>

// Mengatur waktu akhir perhitungan mundur
var countDownDatemiki= new Date("<?= $tgl2; ?> <?= $waktu; ?>").getTime();

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
    document.getElementById("demomiki").innerHTML = "<a class='btn btn-danger btn-sm' href='pobatal.php?invoice=<?=$invoice; ?>'><i class='fa fa-times'></i> Batalkan Pesanan</a><br><p style='color: black;'>Batas Pembayaran Sudah Lewat</p>";
    // document.getElementById("demomiki").innerHTML = "";
      var x = document.getElementById("linkmiki");
      var y = document.getElementById("link2");
 
    y.style.display = "none";
    x.style.display = "none";
    }
}, 1000);
</script>
<?php endif ?>
		         
	</div>
	</body>
</html>