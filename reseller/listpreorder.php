<?php 
session_start();

include 'koneksi.php'; 
// if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] !== 'reseller') {
//   echo "<script>alert('anda harus login terlebih dahulu');</script>";
//   echo "<script>location='login2.php';</script>";
//   header("Location: login2.php");
//   exit();
// }
include 'assets/components/Sessions/sesReseller.php';
// Simpan informasi penting dari sesi ke dalam variabel lokal
$user_id = $_SESSION['user_id'];
$user_level = $_SESSION['user_level'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mitra <?= $_SESSION['user_level']['user_id']; ?>| WNJ </title>
    <!-- Load File bootstrap.min.css yang ada difolder css -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Load File bootstrap.min.css yang ada difolder css -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    
<style>

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
  
  </head>
  <body>
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="logout.php"><i class="glyphicon glyphicon-off"></i></a></div>
  <div class="col-8" ><p>PRE ORDER</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<!--================ NAVBARU END =================-->


<div class="container" align="center">

<!--     <button type="submit" class="btn btn-info btn-lg" name="cari" id="linkinner">
    <a  style="color:white" href="formpo_bergo.php?id=117">Link PO Khimar Limosa</a>
  </button>
  <p id="demoinner"></p>
  <br>  -->

<script>
// Mengatur waktu akhir perhitungan mundur
// var countDownDateinner = new Date("Jun 28, 2022 23:59:00").getTime();

// // Memperbarui hitungan mundur setiap 1 detik
// var x = setInterval(function() {

//   // Untuk mendapatkan tanggal dan waktu hari ini
//   var now = new Date().getTime();
    
//   // Temukan jarak antara sekarang dan tanggal hitung mundur
//   var distance = countDownDateinner - now;
    
//   // Perhitungan waktu untuk hari, jam, menit dan detik
//   var days = Math.floor(distance / (1000 * 60 * 60 * 24));
//   var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
//   var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
//   var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
//   // Keluarkan hasil dalam elemen dengan id = "demo"
//   document.getElementById("demoinner").innerHTML = days + "d " + hours + "h "
//   + minutes + "m " + seconds + "s ";
    
//   // Jika hitungan mundur selesai, tulis beberapa teks 
//   if (distance < 0) {
//     clearInterval(x);
//     document.getElementById("demoinner").innerHTML = "Link PO tidak tersedia";
//       var x = document.getElementById("linkinner");
 
//     //x.style.display = "block";
//     x.style.display = "none";
//     }
// }, 1000);
</script>

<?php 
  $dataproduk=$koneksi->query("SELECT bukapo.idbpo,
                              bukapo.jenis_mitra,
                              bukapo.jenis_po,
                              bukapo.idpoproduk,
                              bukapo.tgl,
                              bukapo.tgl_dropship,
                              bukapo.status,
                              poproduk.namapo 
                              FROM bukapo inner join poproduk on bukapo.idpoproduk = poproduk.idpoproduk 
                              WHERE bukapo.status = 'PUBLISH' and bukapo.jenis_mitra = 'Semua Mitra' and bukapo.jenis_po <>'PO Karakter Stok' ");
  while($tampilkan=$dataproduk->fetch_assoc()){

?>

      <button type="submit" class="btn btn-primary btn-lg" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">
        <?php if ($tampilkan['jenis_po']=="PO dengan Stok"): ?>
            <a  style="color:white" href="formpostok.php?id=<?= $tampilkan['idpoproduk']; ?>">Link <?= $tampilkan['namapo']; ?></a>
        <?php endif ?>
        <?php if ($tampilkan['jenis_po']=="PO tanpa Stok"): ?>
            <a  style="color:white" href="formpoku.php?id=<?= $tampilkan['idpoproduk']; ?>">Link <?= $tampilkan['namapo']; ?></a>
        <?php endif ?>
        <?php if ($tampilkan['jenis_po']=="PO Custom Tab"): ?>
            <a  style="color:white" href="formpo_tab.php?id=<?= $tampilkan['idpoproduk']; ?>">Link <?= $tampilkan['namapo']; ?></a>
        <?php endif ?>   
        <?php if ($tampilkan['jenis_po']=="PO Custom Tab Stok"): ?>
            <a  style="color:white" href="formpo_tabstok.php?id=<?= $tampilkan['idpoproduk']; ?>">Link <?= $tampilkan['namapo']; ?></a>
        <?php endif ?>                  
        <?php if ($tampilkan['jenis_po']=="PO Konin"): ?>
            <a  style="color:white" href="pokonin?id=<?= $tampilkan['idpoproduk']; ?>">Link <?= $tampilkan['namapo']; ?></a>
        <?php endif ?>
        <?php if ($tampilkan['jenis_po']=="PO Kolibri"): ?>
            <a  style="color:white" href="pokolibri?id=<?= $tampilkan['idpoproduk']; ?>">Link <?= $tampilkan['namapo']; ?></a>
        <?php endif ?>        
        <?php if ($tampilkan['jenis_po']=="PO Brooch Custom"): ?>
            <a  style="color:white" href="formpobrooch_custom?id=<?= $tampilkan['idpoproduk']; ?>">Link <?= $tampilkan['namapo']; ?></a>
        <?php endif ?>
        <?php if ($tampilkan['jenis_po']=="PO Karakter Stok"): ?>
            <a  style="color:white" href="formpo_karakterstok?id=<?php echo $tampilkan['idpoproduk']; ?>">Link <?php echo $tampilkan['namapo']; ?> (Custom)</a>
        <?php endif ?>
        
        <?php if ($tampilkan['idpoproduk'] == '260') : ?>
            <a  style="color:white" href="formpocustomlegging?id=<?php echo $tampilkan['idpoproduk']; ?>">Link <?php echo $tampilkan['namapo']; ?></a>
        <?php elseif ($tampilkan['idpoproduk'] == '261') : ?>
            <a  style="color:white" href="formpocustomlegging2?id=<?php echo $tampilkan['idpoproduk']; ?>">Link <?php echo $tampilkan['namapo']; ?></a>
        <?php elseif ($tampilkan['idpoproduk'] == '292') : ?>
            <a  style="color:white" href="formpotazmahal?id=<?php echo $tampilkan['idpoproduk']; ?>">Link <?php echo $tampilkan['namapo']; ?></a>
        <?php endif; ?>

      </button>
      <p id="demomiki<?= $tampilkan['idbpo']; ?>">


      </p>
      <br>


<script>

// Mengatur waktu akhir perhitungan mundur
var countDownDatemiki<?= $tampilkan['idbpo']; ?>= new Date("<?= $tampilkan['tgl']; ?> 23:59:00").getTime();

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
    document.getElementById("demomiki<?= $tampilkan['idbpo']; ?>").innerHTML = "Link PO tidak tersedia";
      var x = document.getElementById("linkmiki<?= $tampilkan['idbpo']; ?>");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>

<?php } ?> 

      <br><center><h3>List PO</h3></center><br> 
            
      <div class="table-responsive">
        <table class="table table-bordered">
          <tr>
            <th>Tanggal</th>
            <th>Nama PO</th>
            <th>Status</th>
            <th>Invoice</th>
            <th>Keluarga/Penerima</th>
            <th>List Dropship</th>
          </tr>
          <?php

          $sql = mysqli_query($koneksi, "SELECT pomitra.tgl,
            pomitra.waktu,
            pomitra.status,
            pomitra.invoice,
            poproduk.namapo,
            poproduk.idpoproduk,
            poproduk.jenis 
            FROM `pomitra` 
            inner join poproduk on pomitra.idpoproduk=poproduk.idpoproduk  
            where pomitra.idmitrareseller='$idmitrareseller' 
            and poproduk.idpoproduk > 233
            GROUP BY pomitra.invoice
            ORDER BY pomitra.idpoproduk DESC");
        
          while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
          ?>
            <tr>
              <td class="align-middle"><?= $data['tgl']; ?></td>
              <td class="align-middle"><?= $data['namapo']; ?></td>
              <td class="align-middle"><?= $data['status']; ?></td>
              <td class="align-middle">
<?php if ($data['jenis']=='Kolibri') { ?>
<?php if ($data['status']=='Belum Acc DB'): ?>
<?php 

  $jumlahhari='+1 days'; 

?>
                  <center>
                        <div class=""  id="link2<?= $data['idpomitra']; ?>">
                        <a href="datapokolibri?invoice=<?= $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                        </div>

                        <p id="demomiki<?= $data['idpomitra']; ?>"></p>
                  </center>      
<?php 
$idpoproduk = $data['idpoproduk'];

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
                              WHERE poproduk.idpoproduk='$idpoproduk'";
  $sqlbukapo = mysqli_query($koneksi, $querybukapo);  
  $databukapo = mysqli_fetch_array($sqlbukapo);

date_default_timezone_set('Asia/Jakarta');
$tgl1 = $data['tgl'];// pendefinisian tanggal awal
// $tgl2 = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari
$tgl2 = $databukapo['tgl_bayar'];


 ?>

<script>

// Mengatur waktu akhir perhitungan mundur
var countDownDatemiki<?= $data['idpomitra']; ?>= new Date("<?php echo $tgl2; ?> <?= $data['waktu'] ?>").getTime();

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
    document.getElementById("demomiki<?= $data['idpomitra']; ?>").innerHTML = "<?php echo $data['invoice']; ?><br><a href='pobatal.php?invoice=<?= $data['invoice']; ?>' class='text-danger'><i class='fa fa-times'></i> Batalkan Pesanan</a>";
      var x = document.getElementById("linkmiki<?= $data['idpomitra']; ?>");
      var y = document.getElementById("link2<?= $data['idpomitra']; ?>");
 
    y.style.display = "none";
    x.style.display = "none";
    }
}, 1000);
</script>   
<?php else: ?>
            <center>
              <a href="datapokolibri?invoice=<?= $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>               
            </center>
<?php endif ?>
<?php }elseif (substr($data['invoice'],0,1)<>"R"){?>
                <center>
                  <a href="datapom.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                </center>
                
                <?php } elseif ($data['idpoproduk'] == '260' or $data['idpoproduk'] == '261') { ?>
                    <center>
                        <a href="datapom2.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                    </center>
                <?php } elseif ($data['idpoproduk'] == '259') { ?>
                    <center>
                        <a href="datapokonin2.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                    </center>
               <?php }else{ ?>
            <center>
                <a href="datapo.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
              </center>
              <?php } ?>
              </td>
              <td class="align-middle">
                <?php if ($data['jenis']=='Kolibri') { ?>
              <?php
              $sql3 = mysqli_query($koneksi, "SELECT namapenerima FROM podropship where invoice='$data[invoice]'");
                        $namadropship = mysqli_fetch_array($sql3);
                        echo $namadropship['namapenerima'];
              ?>
               <?php }else{ ?>
                  <p>-</p>
              <?php } ?>
              </td>              
              <td class="align-middle">
                <?php if ($data['jenis']=='Kolibri') { ?>
                    <p>-</p>
               <?php }else{ ?>
              <a href="listdropship.php?&invoice=<?= $data['invoice']; ?>&idmitra=<?= $idmitrareseller; ?>&id=<?= $data['idpoproduk']; ?>" class="btn btn-sm btn-primary">List Dropship</a>
              <?php } ?>                
            </td>

            </tr>
          <?php } ?>
        </table>
        
      </div><br><br>

      <br><br>
      
      
    </div>
  <?php // include "menubawah2.php" ?> 
  

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.js"></script>
<script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.dataTables.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> -->
      
  </body>
</html>

