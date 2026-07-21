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


 $invoice = $_GET['invoice'];
 $idpoproduk = $_GET['id'];
 $idmitramarketer=$_SESSION["mitraagen"]["idmitramarketer"];
  $query = "SELECT COUNT(*) as jumlah,
  poproduk.idpoproduk,
  poproduk.namapo,
  poproduk.status,
  poproduk.note,
  poproduk.diskon,
  poproduk.pembayaran,
  pomitra.ket,
  pomitra.tgl,
  pomitra.waktu,
  pomitra.proses,
  pomitra.status as statuspo
  FROM poproduk 
  inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
  WHERE poproduk.idpoproduk='$idpoproduk' AND pomitra.invoice='$invoice'";
  $sql = mysqli_query($koneksi, $query);  
  $datapo = mysqli_fetch_array($sql);
$statuspo = $datapo['statuspo'];
$persen_tambahan = $datapo['diskon'];
$pembayaranpo = $datapo['pembayaran'];
$note=$datapo['note'];
$prosespo=$datapo['proses'];


  $query_tgl = "SELECT bukapo.idpoproduk,
            bukapo.tgl_bayar
        FROM bukapo  
        WHERE bukapo.idpoproduk='$idpoproduk'
        
        ";
  $sqlpo_tgl = mysqli_query($koneksi, $query_tgl);  
  $datapo_tgl = mysqli_fetch_array($sqlpo_tgl); 
  $tgl_bayar = $datapo_tgl['tgl_bayar']; 
     $waktu_bayar = '23:59:59';

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

  <div class="col-2"><a href="listpreorder"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
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
      <p align="left">Alamat  : <?php echo $_SESSION["mitraagen"]["alamat"]; ?> </p>
	  <p align="left">No Invoice  : <?php echo $invoice; ?> </p>

			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
					 <th>No</th>
					 <th>Nama Barang</th>
					 <th>Custom</th>
					 <!-- <th>Font Teks</th> -->
					 <th>Qty</th>
					 <th>Satuan</th>
					 <th>Total</th>
					</tr>
					<?php
					
					$no=1;
					$sql = mysqli_query($koneksi, "SELECT 
						podetail.variant,
						pomitra.idpomitra,
						pomitra.jumlah,
						pomitra.invoice,
						pomitra.total,
						podetail.harga,
						pomitra.custom,
            pomitra.template,
						pomitra.font
					FROM pomitra 
					inner JOIN pokategori on pokategori.idpo=pomitra.idpo 
					inner join podetail on podetail.idpodetail=pomitra.idpodetail
					WHERE pomitra.invoice='$invoice' 
					and pomitra.jumlah>0 
					ORDER BY pomitra.idpomitra ASC");
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td class="align-middle"><?php echo $no++; ?></td>
							
							<td class="align-middle"><?php echo $data['variant']; ?></td>
					    	<td class="align-middle">
                  <?php if ($data['template']): ?>
                    <b>Template : </b>
                    <br>
                    <?= $data['template'] ?>
                    <br>
                  <?php endif ?>
								<?php 
								if (substr($invoice,0,2)=="RM") {
									 $datacustom=$data['custom'];
									  $result_explode = explode('|', $datacustom);
								    $baris1=$result_explode[0];
								    $baris2=$result_explode[1];
								   echo  nl2br($baris1);
								   echo "<br>";
								   echo  nl2br($baris2);	
								}else{

								 ?>
                 <b>Karakter : </b>
                 <br>					    	
													    	<?php echo  nl2br($data['custom']); ?>
								<?php 
								}
								?>

					    	</td>
					     	<!-- <td class="align-middle"><?php echo $data['font']; ?></td> -->
					     	<td class="align-middle"><?php echo $data['jumlah']; ?></td>
					     	<td class="align-middle"><?php echo $data['harga']; ?></td>
					     	<td class="align-middle"><?php echo $data['harga']*$data['jumlah']; ?></td>
					     <?php
              $sum+= $data['jumlah'];     
              $jumlah+=$data['jumlah']*$data['harga'];
							?>
						</tr>
					<?php
					}
					
					?>
				</table>
			</div>
        <br>
<?php 
$diskon=10/100*$jumlah;
$subtotal=$jumlah-$diskon; 
?>
        <table style="float: right;width: 100%">
          <tbody  style="float: right;">
            <tr>
              <td>Qty</td>
              <td>:</td>
              <td><?php echo $sum; ?></td>
            </tr>
            <tr>
              <td>Jumlah</td>
              <td>:</td>
              <td>Rp. <?php echo number_format($jumlah); ?></td>
            </tr>
            <tr>
              <td>Diskon Marketer 10%</td>
              <td>:</td>
              <td>Rp. -<?php echo number_format($diskon); ?></td>
            </tr>
            <tr>
              <td>Total Tagihan</td>
              <td>:</td>
              <td>Rp. <?php echo number_format($subtotal); ?></td>
            </tr>
          </tbody>
        </table>
                    <p align="left"><strong>Note : </strong><?php echo $note ?></p><br>
                <?php
                $angkadp = 50;
                    if ($idpoproduk==208) {
                      $angkadp=30;
                    }
                    $dp=$subtotal*$angkadp/100;
                ?>
<p align="center"><strong>Jumlah DP PO <?= $angkadp;?>% : </strong>Rp. <?php echo number_format($dp); ?></p><br>
            
                   <br>

       
<?php if (($statuspo=='Belum DP' or $statuspo=='Belum Acc DB') and $prosespo<>'Proses'): ?>
            
   

<?php 
  $dataproduk=$koneksi->query("SELECT bukapo.idbpo,
                              bukapo.jenis_mitra,
                              bukapo.jenis_po,
                              bukapo.idpoproduk,
                              bukapo.tgl,
                              bukapo.tgl_acc_db,
                              bukapo.tgl_ubah,
                              bukapo.tgl_dropship,
                              bukapo.status,
                              poproduk.namapo 
                              FROM bukapo inner join poproduk on bukapo.idpoproduk = poproduk.idpoproduk 
                              WHERE poproduk.idpoproduk='$idpoproduk' and bukapo.status = 'PUBLISH' and (bukapo.jenis_mitra = 'Semua Mitra' or bukapo.jenis_mitra = 'Distributor')
                              GROUP BY bukapo.idpoproduk
                              ");
  while($tampilkan=$dataproduk->fetch_assoc()){

?>
<?php if ($tampilkan['jenis_po']=="PO Miki Custom"): ?>
      <button type="submit" class="btn btn-success btn-sm" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">
<?php if(substr($invoice,0,3)=="MHP"): ?>
          <a  style="color:white" href="ubahmikistock.php?id=<?= $idpoproduk;?>&invoice=<?= $invoice ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>
          <?php else: ?>
      <a  style="color:white" href="ubahpomikicustom.php?id=<?= $idpoproduk;?>&invoice=<?= $invoice ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>

        <?php endif ?>        
      </button>
<?php endif ?>
<?php if ($tampilkan['jenis_po']=="PO Brooch Custom"): ?>
      <button type="submit" class="btn btn-success btn-sm" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">

          <a  style="color:white" href="ubahpobroochcustom.php?id=<?= $idpoproduk;?>&invoice=<?= $invoice ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>
       
      </button>
<?php endif ?>
<?php if ($tampilkan['jenis_po']=="PO Karakter Stok"): ?>
      <button type="submit" class="btn btn-success btn-sm" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">

          <a  style="color:white" href="ubahpo_karakterstok.php?id=<?= $idpoproduk;?>&invoice=<?= $invoice ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>
       
      </button>
<?php endif ?>
      
      

      <p id="demomiki<?= $tampilkan['idbpo']; ?>"></p>
      <br>


<script>

// Mengatur waktu akhir perhitungan mundur
var countDownDatemiki<?= $tampilkan['idbpo']; ?>= new Date("<?php echo $tampilkan['tgl_ubah']; ?> 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDatemiki<?= $tampilkan['idbpo']; ?> - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demomiki<?= $tampilkan['idbpo']; ?>").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demomiki<?= $tampilkan['idbpo']; ?>").innerHTML = "Link Ubah PO Tidak Tersedia";
      var x = document.getElementById("linkmiki<?= $tampilkan['idbpo']; ?>");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>

<?php } ?> 
          <?php endif ?>       


		</div>
	</body>
</html>