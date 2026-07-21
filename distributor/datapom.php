<?php 
session_start();

include 'koneksi.php'; 
include 'floatingbutton.php'; 

include 'assets/components/Sessions/sesDistri.php';

 $invoice = $_GET['invoice'];
 $idpoproduk = $_GET['id'];
  $idadmin=$_SESSION  ["idadmin"];
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

     $queryUser = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin = '$idadmin'");
     $dataUser = $queryUser->fetch_assoc();

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

  <div class="col-2"><a href="detailpo?id=<?= $idpoproduk; ?>"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
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
      <p align="left">Nama Mitra  : <?php echo $dataUser  ["namamitra"]; ?> </p>
      <p align="left">Alamat  : <?php echo $dataUser  ["alamat"]; ?> </p>
	  <p align="left">No Invoice  : <?php echo $invoice; ?> </p>

			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
                        <th>No</th>
                        <?php if ($idpoproduk === '237' or $idpoproduk === '240') : ?>
                            <th width="300">Nama Barang</th>
                            <th width="300">Jenis PO</th>
                        <?php elseif ($idpoproduk === '234' or $idpoproduk === '250' or $idpoproduk === '251') : ?>	 
                            <th>Barang</th>
                        <?php elseif ($idpoproduk == '261') : ?>
                            <th>Nama Barang</th>
                            <th>Custom</th>
                        <? elseif ($idpoproduk == '310' || $idpoproduk == '320') : ?>
                            <th>Nama Barang</th>
                            <th>Custom</th>
                            <th>Font</th>
                        <?php endif; ?>
                            <!-- <th>Font Teks</th> -->
                        <?php if($idpoproduk === '234' or $idpoproduk === '250' or $idpoproduk === '251') : ?>
                            <th width="200">Pack</th>
                        <?php elseif($idpoproduk === '237' or $idpoproduk === '240') :?>
                            <th width="50">Qty</th>
                            <th>Font</th>
                        <?php else : ?>
                            <th width="200">Qty</th>
                        <?php endif; ?>
                        <th>Satuan</th>
                        <th>Total</th>
                    </tr>
                <?php
					
					$no=1;
					$sql = mysqli_query($koneksi, "SELECT 
						podetail.variant,
						pokategori.namakategori,
						pomitra.idpomitra,
						pomitra.jumlah,
						pomitra.invoice,
						pomitra.total,
						podetail.harga,
						pomitra.custom,
            pomitra.template,
						pomitra.font,
            pomitra.is_custom
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
							
							<?php if($idpoproduk === '237' or $idpoproduk === '240') : ?>

					    	<?php elseif ($idpoproduk === '234' or $idpoproduk === '250' or $idpoproduk === '251') : ?>
					    	
                            <? elseif ($idpoproduk == '310' || $idpoproduk == '320') : ?>
                                <td><?= $data['variant'] ?></td>
            					<td><?= $data['custom'] ?></td>
            					<td><?= $data['font'] ?></td>
					    	<?php else : ?>
							    <td class="align-middle"><?php echo $data['variant']; ?></td>
					    	<?php endif; ?>
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
                 <!--<b>Karakter : </b>-->
                 <!--<br>					    	-->
													    	<?php echo  nl2br($data['custom']); ?>
								<?php 
								}
								?>

					    	</td>
                <?php if($idpoproduk === '237' or $idpoproduk === '240') : ?>
                  <td class="align-middle">
                    <form method="POST">
                      <input type="hidden" name="is_idpomitra" value="<?= $data['idpomitra'] ?>">
                      <select name="is_custom" id="">
                        <?php
                          $selectedValue = isset($data['is_custom']) ? $data['is_custom'] : '';
                        ?>
                        <option value="" <?= ($selectedValue === '') ? 'selected' : '' ?>>Pilih Jenis PO</option>
                        <option value="0" <?= ($selectedValue === '0') ? 'selected' : '' ?>>Polos Rocela + Polos Goura</option>
                        <option value="1" <?= ($selectedValue === '1') ? 'selected' : '' ?>>Polos Rocela + Custom Goura</option>
                        <option value="2" <?= ($selectedValue === '2') ? 'selected' : '' ?>>Custom Rocela + Polos Goura</option>
                        <option value="3" <?= ($selectedValue === '3') ? 'selected' : '' ?>>Custom Rocela + Custom Goura</option>
                      </select>
                      <button class="btn btn-sm btn-default" type="submit" name="btn_isCustom">Ubah</button>
                    </form>
                  </td>
                <?php endif; ?>
					     	<!-- <td class="align-middle"><?php echo $data['font']; ?></td> -->
                <?php if ($idpoproduk === '237' or $idpoproduk === '240') : ?>
                  <td><?= $data['jumlah'] ?></td>
                  <td><?= $data['font']; ?></td>
                <?php else : ?>
                  <td class="align-middle">
					     	    <form method="post">
                      <input type="hidden" name="idpomitra" value="<?php echo $data['idpomitra']; ?>" />
                      <input type="number" name="qty" value="<?php echo $data['jumlah']; ?>" />
                      <button class="btn btn-sm btn-default" type="submit" name="ubah_qty">Ubah</button>
                    </form>
                  </td>
                <?php endif; ?>
					     	<td class="align-middle"><?php echo $data['harga'] ?></td>
					     	<!-- <td class="align-middle"><?php echo $data['harga']*$data['jumlah']; ?></td> -->
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
<?php if ($idpoproduk<>163): ?>
          
     
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
        <th>JUMLAH</th>
        <td>:</td>
        <td>
<?php 
$sqlharga2 = "SELECT MAX(total) as totalnya,invoice FROM `pomitra` WHERE `idpoproduk`= '$idpoproduk' and idmitra = '$idadmin' and invoice = '$invoice'";
$queryharga2 = $koneksi->query($sqlharga2);
$sisaharga2 = $queryharga2->fetch_assoc(); 
$stokharga2 = $sisaharga2['totalnya']; 
if ($idpoproduk=="120") {

  $jumlah=$stokharga2;

}
 ?>        
Rp. <?php echo number_format($jumlah); ?>                     
        </td>
    </tr>
                    
<?php 
  $persen=35;
  $diskon=35/100*$jumlah;
  $subtotal=$jumlah-$diskon; 
$diskon_tambahan = $persen_tambahan/100*$jumlah;
$subtotal=$jumlah-$diskon-$diskon_tambahan;
?>
    <tr>
        <th>Diskon DB <?= $persen; ?>%</th>
        <td>:</td>
        <td>
          Rp. <?php echo number_format($diskon); ?>               
        </td>
    </tr>
<?php if ($diskon_tambahan>0): ?>
      
    <tr>
        <th>Diskon Tambahan</th>
        <td>:</td>
        <td>      
        Rp. <?= number_format($diskon_tambahan); ?>                     
        </td>
    </tr>                           
    <?php endif ?>      
    <tr>
        <th style="padding-bottom: 5%;">Total Bayar</th>
        <td style="padding-bottom: 5%;">:</td>
        <td style="padding-bottom: 5%;">
Rp. <?php echo number_format($subtotal); ?>           
        </td>
    </tr>

                    
                <?php
                $dp=$subtotal*50/100;
                $namapayemnt='DP';
                $jenispayment='dp';
                if ($pembayaranpo=='Lunas') {
                  $dp = $subtotal;
                  $namapayemnt='Pembayaran';
                }
?>
<?php if ($pembayaranpo=='DP'): ?>
  

    <tr>
        <th>Jumlah DP PO 50%</th>
        <td>:</td>
        <td>
Rp. <?php echo number_format($dp); ?>       
        </td>
    </tr>  
<?php endif ?>

      <?php
include "koneksi.php";      


          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $sqldp = mysqli_query($koneksi, "SELECT popembayaran.invoice,popembayaran.jmlhtransfer,popembayaran.jmlh_lunas
                                            FROM `popembayaran` 
                                            WHERE popembayaran.invoice ='$invoice' ");
          
          $datadp = mysqli_fetch_array($sqldp) // Ambil semua data dari hasil eksekusi $sql
         
          ?>

    <tr>
        <th style="padding-top: 5%;">Status PO</th>
        <td style="padding-top: 5%;">:</td>
        <td style="padding-top: 5%;"><?= $datapo['statuspo']; ?></td>
    </tr>  
<?php if ($datadp['invoice']<>""): ?>     
    <tr>
        <th>Konfirmasi DP</th>
        <td>:</td>
        <td>        
Rp. <?php echo number_format($datadp['jmlhtransfer']); ?> 
        </td>
    </tr>
    <tr>
        <th>Konfirmasi Pelunasan</th>
        <td>:</td>
        <td>        
Rp. <?php echo number_format($datadp['jmlh_lunas']); ?> 
        </td>
    </tr>     

   
    <tr>
        <th>Sisa Tagihan</th>
        <td>:</td>
        <td>
<?php 
$sisa = $datadp['jmlhtransfer'] + $datadp['jmlh_lunas'] - $subtotal;

$sisalunas = $subtotal - $datadp['jmlhtransfer']- $datadp['jmlh_lunas'];
 ?>
          
Rp. 
<?php if ($sisa>0): ?>
+             
<?php endif ?> 
<?php echo number_format($sisa); ?> 
        </td>
    </tr>      
 <?php endif ?>  
</tbody>
</table> 				          

        <?php endif ?>   
				          	<p align="left"><strong>Note : </strong><?php echo $note ?></p><br>


<?php if ($datadp['invoice']==""): ?>

<?php if ($datapo['ket']=='Perpanjang' or $tgl_bayar==""): ?>
<?php  
date_default_timezone_set('Asia/Jakarta');
$jumlahhari='+1 days'; 
$tgl1 = $datapo['tgl'];
$tgl2 = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1))); 
$tgl_bayar = $tgl2;
  $waktu_bayar = $datapo['waktu'];  
?>
<?php endif; ?>

<center>
      <div class=""  id="link2<?= $data['idpomitra']; ?>">
<?php echo "<a class='btn btn-primary' href='popembayaran.php?invoice=$invoice&total=$dp&bayar=$subtotal&idpo=$idpoproduk&jenis=$jenispayment'>Konfirmasi $namapayemnt</a>";   ?>
      </div>

      <p id="demomiki<?= $data['idpomitra']; ?>"></p>
</center>  
  
<script>

// Mengatur waktu akhir perhitungan mundur
var countDownDatemiki<?= $data['idpomitra']; ?>= new Date("<?php echo $tgl_bayar; ?> <?php echo $waktu_bayar; ?>").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDatemiki<?= $data['idpomitra']; ?> - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demomiki<?= $data['idpomitra']; ?>").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demomiki<?= $data['idpomitra']; ?>").innerHTML = "Melebihi batas waktu konfirmasi Payment PO";
      var x = document.getElementById("linkmiki<?= $data['idpomitra']; ?>");
      var y = document.getElementById("link2<?= $data['idpomitra']; ?>");
 
    y.style.display = "none";
    x.style.display = "none";
    }
}, 1000);
</script>   
<?php endif; ?>
<?php  
if ($statuspo=="Sudah DP") {
echo "<a class='btn btn-primary' href='popembayaran.php?invoice=$invoice&total=$sisalunas&idpo=$idpoproduk&jenis=lunas'>Konfirmasi Pelunasan</a>";                  
               }               
?> 
       
<?php if ($statuspo=='Belum DP' and $prosespo<>'Proses'): ?>
            
   

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
      <p id="demomiki<?= $tampilkan['idbpo']; ?>"></p>
<?php endif ?>
<?php if ($tampilkan['jenis_po']=="PO Brooch Custom"): ?>
      <button type="submit" class="btn btn-success btn-sm" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">

          <a  style="color:white" href="ubahpobroochcustom.php?id=<?= $idpoproduk;?>&invoice=<?= $invoice ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>
       
      </button>
      <p id="demomiki<?= $tampilkan['idbpo']; ?>"></p>
<?php endif ?>
<?php if ($tampilkan['jenis_po']=="PO Karakter Stok"): ?>
      <button type="submit" class="btn btn-success btn-sm" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">

          <a  style="color:white" href="ubahpo_karakterstok.php?id=<?= $idpoproduk;?>&invoice=<?= $invoice ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>
       
      </button>
      <p id="demomiki<?= $tampilkan['idbpo']; ?>"></p>
<?php endif ?>
      
      

      
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

<?php if(isset($_POST['ubah_qty'])) {
    // echo "<script>alert('".$_POST['qty']."-".$_POST['idpomitra']."')</script>";
    if ($_POST['qty'] < 1) {
        $koneksi->query("DELETE FROM pomitra WHERE pomitra.idpomitra = ".$_POST['idpomitra']."");    
    } else {
        $koneksi->query("UPDATE pomitra SET jumlah = ".$_POST['qty']." WHERE pomitra.idpomitra = ".$_POST['idpomitra']."");
    }
    echo "<script>window.location.href='https://wnj.web.id/distributor/datapom.php?id=".$_GET['id']."&invoice=".$_GET['invoice']."'</script>";
} 
?>
      <!-- Submit Is Custom -->
      <?php
        if(isset($_POST['btn_isCustom'])) {
          include 'koneksi.php';

          if (!$koneksi) {
            die("Koneksi gagal : " . mysqli_connect_error());
          }

          $is_custom = $_POST['is_custom'];
          $is_idpomitra = $_POST['is_idpomitra'];

          // var_dump($is_custom . " || " . $is_idpomitra);

          $queryIsCustom = $koneksi->query("UPDATE pomitra SET is_custom = '$is_custom' WHERE pomitra.idpomitra = '$is_idpomitra'");

          $sqlQueryIsCustom = "SELECT * FROM pomitra WHERE idpomitra = '$is_idpomitra'";
          $querySqlIsCustom = mysqli_query($koneksi, $sqlQueryIsCustom);
          $valuePoMitra = mysqli_fetch_array($querySqlIsCustom);
          $valueIsCustom = $valuePoMitra['is_custom'];
          // var_dump($valueIsCustom['is_custom'] . " || " . $valueIsCustom['idpomitra']);
          // Conditional jika $valueIsCustom bernilai 0 maka munculkan nilai berikut "Polos Rocela + Polos Goura"
          if ($valueIsCustom == 0) {
            $sqlCustom = mysqli_query($koneksi, "SELECT custom FROM pomitra WHERE idpomitra = '$is_idpomitra'");
            $queryCustom = mysqli_fetch_array($sqlCustom);
            
            if ($queryCustom) {
              $customString = $queryCustom['custom'];
              $result_explode = explode(" | ", $customString);

              $lastString = $result_explode[1];

              if ($lastString) {
                $customName = $lastString;
                $customName = rtrim($customName, " )");

                $explodeCustomName = explode(" ( ", $customName);
              }
            }

            // var_dump($result_explode[1] . " || "  . $explodeCustomName[0] . " || " . $explodeCustomName[1]);
            $nilai1 = $result_explode[0];
            $nilai2 = $explodeCustomName[0];

            $nilai3 = implode(" | ", [$nilai1, $nilai2]);

            $updateQuery = $koneksi->query("UPDATE pomitra SET custom = '$nilai3' WHERE pomitra.idpomitra = '$is_idpomitra'");
            
            if ($updateQuery) {
              echo "<script>alert('Data berhasil di update');</script>";
              echo "<script>window.location.href='https://wnj.web.id/distributor/datapom.php?id=".$_GET['id']."&invoice=".$_GET['invoice']."';</script>";
            } else {
              echo "<script>alert('Data gagal di update');</script>";
            }

          }

          // Dan jika $valueIsCustom bernilai 1 maka munculkan nilai berikut "Polos Rocela + Custom Goura"
          elseif ($valueIsCustom == 1) {
            $sqlCustom = mysqli_query($koneksi, "SELECT custom FROM pomitra WHERE idpomitra = '$is_idpomitra'");
            $queryCustom = mysqli_fetch_array($sqlCustom);
            
            if ($queryCustom) {
              $customString = $queryCustom['custom'];
              $result_explode = explode(" | ", $customString);

              $lastString = $result_explode[1];
              
              if ($lastString) {
                $customName = $lastString;
                $customName = rtrim($customName, " )");
                $explodeCustomName = explode(" ( ", $customName);
              }
            }

            $nilai1 = $result_explode[0];
            $nilai2 = $explodeCustomName[0];
            $nilai3Temporary = $explodeCustomName[1];
            $nilai3 = "( " . $nilai3Temporary . " )";
            
            // Ini adalah nilai gabungan dari nilai1 dan nilai3
            $nilai4 = $nilai2 . " " . $nilai3;
            
            $temporary = implode(" | ", [$nilai1, $nilai4]);
            $updateQuery = $koneksi->query("UPDATE pomitra SET custom = '$temporary' WHERE pomitra.idpomitra = '$is_idpomitra'");
            if ($updateQuery) {
              echo "<script>alert('Data berhasil di update');</script>";
              echo "<script>window.location.href='https://wnj.web.id/distributor/datapom.php?id=".$_GET['id']."&invoice=".$_GET['invoice']."';</script>";
            } else {
              echo "<script>alert('Data gagal di update');</script>";
            }
          }

          // Dan jika $valueIsCustom bernilai 2 maka munculkan nilai berikut "Custom Rocela + Polos Goura"
          elseif ($valueIsCustom == 2) {
            $sqlCustom = mysqli_query($koneksi, "SELECT custom FROM pomitra WHERE idpomitra = '$is_idpomitra'");
            $queryCustom = mysqli_fetch_array($sqlCustom);
            
            if ($queryCustom) {
              $customString = $queryCustom['custom'];
              $result_explode = explode(" | ", $customString);

              $lastString = $result_explode[1];

              if ($lastString) {
                $customName = $lastString;
                $customName = rtrim($customName, " )");
                $explodeCustomName = explode(" ( ", $customName);
              }
            }
            
            $nilai1 = $result_explode[0];
            $nilai2 = $explodeCustomName[0];
            $nilai3Temporary = $explodeCustomName[1];
            $nilai3 = "( " . $nilai3Temporary . " )";
            
            // Ini adalah nilai gabungan dari nilai1 dan nilai3
            $nilai4 = $nilai1 . " " . $nilai3;

            // var_dump($nilai1 . " || " . $nilai2  . " || " . $nilai3 . " || " . $nilai4);
            
            $temporary = implode(" | ", [$nilai4, $nilai2]);
            $updateQuery = $koneksi->query("UPDATE pomitra SET custom = '$temporary' WHERE pomitra.idpomitra = '$is_idpomitra'");
            if ($updateQuery) {
              echo "<script>alert('Data berhasil di update');</script>";
              echo "<script>window.location.href='https://wnj.web.id/distributor/datapom.php?id=".$_GET['id']."&invoice=".$_GET['invoice']."';</script>";
            } else {
              echo "<script>alert('Data gagal di update');</script>";
            }
          }

          // Dan jika $valueIsCustom bernilai 3 maka munculkan nilai berikut "Custom Rocela + Custom Goura"
          elseif ($valueIsCustom == 3) {
            $sqlCustom = mysqli_query($koneksi, "SELECT custom FROM pomitra WHERE idpomitra = '$is_idpomitra'");
            $queryCustom = mysqli_fetch_array($sqlCustom);
            
            if ($queryCustom) {
              $customString = $queryCustom['custom'];
              $result_explode = explode(" | ", $customString);

              $lastString = $result_explode[1];
              
              if ($lastString) {
                $customName = $lastString;
                $customName = rtrim($customName, " )");
                $explodeCustomName = explode(" ( ", $customName);
              }
            }
            $nilai1 = $result_explode[0];
            $nilai2 = $explodeCustomName[0];
            $nilai3Temporary = $explodeCustomName[1];
            $nilai3 = "( " . $nilai3Temporary . " )";
            
            // Ini adalah nilai gabungan dari nilai1 dan nilai3
            $nilai4 = $nilai1 . " " . $nilai3;
            $nilai5 = $nilai2 . " " . $nilai3;

            // var_dump($nilai1 . " || " . $nilai2  . " || " . $nilai3 . " || " . $nilai4);
            
            $temporary = implode(" | ", [$nilai4, $nilai5]);
            $updateQuery = $koneksi->query("UPDATE pomitra SET custom = '$temporary' WHERE pomitra.idpomitra = '$is_idpomitra'");
            if ($updateQuery) {
              echo "<script>alert('Data berhasil di update');</script>";
              echo "<script>window.location.href='https://wnj.web.id/distributor/datapom.php?id=".$_GET['id']."&invoice=".$_GET['invoice']."';</script>";
            } else {
              echo "<script>alert('Data gagal di update');</script>";
            }
          }

          // Dan jika $valueIsCustom tidak ada selain di atas maka munculkan nilai berikut "Jenis PO Tidak ada"
          else {
            echo "Jenis PO Tidak Ada";
          }

        }
      ?>
		
    </div>
	</body>
</html>