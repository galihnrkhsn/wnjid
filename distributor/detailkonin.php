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
$idpoproduk = $_GET['id'];
  $query = "SELECT poproduk.idpoproduk,
            poproduk.namapo,
            poproduk.jenis
        FROM poproduk  
        WHERE poproduk.idpoproduk='$idpoproduk'";
  $sqlpo = mysqli_query($koneksi, $query);  
  $datapo = mysqli_fetch_array($sqlpo);

  $querybukapo = "SELECT bukapo.idbpo,
                              bukapo.jenis_mitra,
                              bukapo.jenis_po,
                              bukapo.idpoproduk,
                              bukapo.tgl,
                              bukapo.tgl_ubah,
                              bukapo.tgl_dropship,
                              bukapo.tgl_bayar,
                              bukapo.status,
                              poproduk.namapo 
                              FROM bukapo inner join poproduk on bukapo.idpoproduk = poproduk.idpoproduk 
                              WHERE poproduk.idpoproduk='$idpoproduk' and (bukapo.jenis_mitra = 'Semua Mitra' or bukapo.jenis_mitra = 'Distributor')";
  $sqlbukapo = mysqli_query($koneksi, $querybukapo);  
  $databukapo = mysqli_fetch_array($sqlbukapo);

?>
<title>WNJ</title>
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

  <div class="col-2"><a href="listnewpo.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>PRE ORDER</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<!--================ NAVBARU END =================-->


<div class="container" align="center">

      
      <br><center><h3>Detail <?= $datapo['namapo']; ?></h3></center><br>
      <div class="table-responsive">
        <table class="table table-bordered">
          <tr>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Invoice</th>
            <th>Keluarga</th>
          </tr>
          <?php
          // Include / load file koneksi.php
          include "koneksi.php";
                    $idmitra=$_SESSION['admin_mitra']['idadmin'];
                    $idpoproduk=$_GET['id'];


          $sql = mysqli_query($koneksi, "SELECT DISTINCT pomitra.tgl,
                                        pomitra.waktu,
                                        pomitra.idpomitra,
                                        pomitra.status,
                                        pomitra.invoice,
                                        poproduk.namapo,
                                        poproduk.jenis,
                                        poproduk.idpoproduk,
                                        podropship.namapenerima 
                                      FROM `pomitra` 
                                      inner join poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
                                      left join podropship on podropship.invoice=pomitra.invoice
                                      where pomitra.idmitra='$idmitra' 
                                      and poproduk.idpoproduk='$idpoproduk' 
                                      GROUP BY pomitra.invoice");
          while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
          ?>
            <tr>
              
              <td class="align-middle"><?php echo $data['tgl']; ?></td>
              <td class="align-middle">
                <?php echo $data['status']; ?>                
              </td>
              <td class="align-middle">
<?php if ($data['status']=='Belum DP'): ?>
<?php 

  $jumlahhari='+1 days'; 

?>
<center>
      <div class=""  id="link2<?= $data['idpomitra']; ?>">
      <a href="datapokonin2?invoice=<?= $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
      </div>

      <!--<p id="demomiki<?= $data['idpomitra']; ?>"></p>-->
</center>      
<?php 
date_default_timezone_set('Asia/Jakarta');
$tgl1 = $data['tgl'];// pendefinisian tanggal awal
// $tgl2 = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari
$tgl2 = $databukapo['tgl_bayar'];

 ?>
<?php //echo $data['waktu'] ?>
<script>

// Mengatur waktu akhir perhitungan mundur
var countDownDatemiki<?= $data['idpomitra']; ?>= new Date("<?php echo $tgl2; ?> 23:59:59").getTime();

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
    document.getElementById("demomiki<?= $data['idpomitra']; ?>").innerHTML = "<?php echo $data['invoice']; ?><br><a href='pobatal.php?invoice=<?= $data['invoice']; ?>' class='text-danger' <?php echo 'onclick=\"return confirm(\'Yakin Akan Batalkan Pesanan?\');\" '?>><i class='fa fa-times'></i> Batalkan Pesanan</a>";
      var x = document.getElementById("linkmiki<?= $data['idpomitra']; ?>");
      var y = document.getElementById("link2<?= $data['idpomitra']; ?>");
 
    y.style.display = "none";
    x.style.display = "none";
    }
}, 1000);
</script>   
<?php else: ?>
            <center>
              <a href="datapokonin2?invoice=<?= $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>               
            </center>
<?php endif ?>
                
                  
              
              </td>
             
                  <td>
              <?= $data['namapenerima'];?>      
              </td>      
            </tr>
          <?php } ?>
        </table>
      </div>

      <br><hr>
        
        <center><h3>PO mitra Sub DB</h3></center><br> 

        <a href="sumposubdb.php?id=<?php echo $idpoproduk ?>" class="btn btn-info">Summary PO SubDB</a><br><br>

        <div class="table-responsive">
        <table class="table table-bordered">
          <tr>
          
            <th>Tanggal</th>
            <th>Nama PO</th>
            <th>Nama Mitra</th>
            <th>Status Mitra</th>
            <th>Status</th>
              <th>Invoice</th>
            <th>List Dropship</th>
            <th>Approve</th>
          </tr>
          <?php
          // Include / load file koneksi.php
          include "koneksi.php";
          $idadmin=$_SESSION['admin_mitra']['idadmin'];
          
          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $sql = mysqli_query($koneksi, "SELECT pomitra.tgl,
            pomitra.status,
            pomitra.idpomitra,
            pomitra.waktu,
            pomitra.invoice,
            poproduk.namapo,
            poproduk.idpoproduk,
            mitraagen.namaagen as agen,
            mitrareseller.namaagen as reseller, 
            mitramarketer.namaagen as marketer 
            FROM `pomitra` 
            inner join poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
            LEFT JOIN mitraagen on pomitra.idmitraagen=mitraagen.idmitraagen 
            LEFT JOIN mitrareseller on pomitra.idmitrareseller=mitrareseller.idmitrareseller 
            LEFT JOIN mitramarketer on pomitra.idmitramarketer=mitramarketer.idmitramarketer 
            where (mitraagen.idadmin='$idadmin' or 
                  mitrareseller.idadmin='$idadmin' or 
                  mitramarketer.idadmin='$idadmin') 
            and poproduk.idpoproduk='$idpoproduk' 
            GROUP BY pomitra.invoice
            ORDER BY pomitra.tgl DESC");
        
          while($tampilkan = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
          ?>  

            <tr>
              
              <td class="align-middle"><?php echo $tampilkan['tgl']; ?></td>
              <td class="align-middle"><?php echo $tampilkan['namapo']; ?></td>
              <td class="align-middle"><?php echo $tampilkan['agen']; ?> <?php echo $tampilkan['reseller']; ?> <?php echo $tampilkan['marketer']; ?></td>
              <td class="align-middle"><?php if($tampilkan['agen']<>''){ echo "Agen";}
                              if($tampilkan['reseller']<>''){ echo "Reseller";}
                              if($tampilkan['marketer']<>''){ echo "Marketer";} ?></td>
              <td class="align-middle"><?php echo $tampilkan['status']; ?></td>
              <td class="align-middle"><a href="dataposub.php?invoice=<?php echo $tampilkan['invoice']; ?>&namasub=<?php echo $tampilkan['agen']; ?><?php echo $tampilkan['reseller']; ?><?php echo $tampilkan['marketer']; ?>&idpoproduk=<?=$idpoproduk;?>">
              <?php echo $tampilkan['invoice']; ?></a></td>
              <td class="align-middle">
              <?php
              $sql2 = mysqli_query($koneksi, "SELECT COUNT(*) as dropship FROM podropship where invoice='$tampilkan[invoice]'");
                        $dropship = mysqli_fetch_array($sql2);
              ?><?php echo $dropship['dropship']; ?></td>
              
              <td class="align-middle">
                 <?php 
                            if ($tampilkan['status']=='Belum Acc DB'){ ?>
<?php 
  $jumlahharinya='+1 days'; 
date_default_timezone_set('Asia/Jakarta');
$tgl1nya = $tampilkan['tgl'];// pendefinisian tanggal awal
// $tgl2nya = date('Y-m-d', strtotime($jumlahharinya, strtotime($tgl1nya)));

$tgl2nya = $databukapo['tgl_bayar'];
?>

                            <form method='post'><input type='hidden' name='invoice' value='<?= $tampilkan['invoice'];?>'>
                            <button id='link3<?= $tampilkan['invoice'];?>' type='submit' class='btn btn-primary btn-xs' name='approve'>Approve</button><p id='demo3<?= $tampilkan['invoice'];?>'></p> 
                            <button type='submit' class='btn btn-danger btn-xs' name='cancel'>Cancel</button></form>

<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDateinner23<?= $tampilkan['idpomitra']; ?>= new Date("<?= $tgl2nya; ?> <?php //echo $tampilkan['waktu']; ?> 23:59:59").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDateinner23<?= $tampilkan['idpomitra']; ?> - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demo3<?= $tampilkan['invoice'];?>").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo3<?= $tampilkan['invoice'];?>").innerHTML = "Waktu Habis";
      var x = document.getElementById("link3<?= $tampilkan['invoice'];?>");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>                               
                                                            <?php
                                                             }                                   
                                                                                         
                            elseif($tampilkan['status']<>'Belum Acc DB')  { ?>
                        <form method='post'>
                            <input type='hidden' name='invoice' value='<?= $tampilkan['invoice'];?>'>
                            <button id='link3<?= $tampilkan['invoice'];?>' type='submit' class='btn btn-primary btn-xs' name='approve'>Approve</button><p id='demo3<?= $tampilkan['invoice'];?>'></p> 
                            <button type='submit' class='btn btn-danger btn-xs' name='cancel_approve'>Cancel Approve</button>
                        </form>
                        <?php }  

                        else { ?>
                        <form method='post'><input type='hidden' name='invoice' value='<?= $tampilkan['invoice'];?>'>
                        <button type='submit' class='btn btn-danger btn-xs' name='cancel'>Cancel</button>
                      </form>
                         <?php   } ?>
                      
                    </td>
            </tr>
          <?php } ?>
        </table>
        </div>
        <br><br>
</div>

<?php
    $namapo=$_GET["namapo"];
        if(isset($_POST["approve"])){
            $invoice=$_POST['invoice'];
            $koneksi->query("UPDATE pomitra set status='Approve DB' where invoice='$invoice' ");
                  echo "<script>alert('PO Sub DB Anda telah di Approve');</script>";
                echo "<script>location='detailkolibri?id=$idpoproduk';</script>";
        }
        if(isset($_POST["cancel"])){
          $invoice=$_POST['invoice'];
          $sqlpo = $koneksi->query("DELETE FROM pomitra where invoice='$invoice' ");
          $sqlds = $koneksi->query("DELETE FROM podropship where invoice='$invoice' ");
          echo "<script>alert('PO Sub DB telah di Batalkan');</script>";
          echo "<script>location='detailkolibri?id=$idpoproduk';</script>";
        }
                if(isset($_POST["cancel_approve"])){
            $invoice=$_POST['invoice'];
            $koneksi->query("UPDATE pomitra set status='Belum Acc DB' where invoice='$invoice' ");
                  echo "<script>alert('PO Sub DB Anda telah di Cancel Approve');</script>";
                echo "<script>location='detailkolibri?id=$idpoproduk';</script>";
        }
        ?>



<!-- 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> -->
      
  </body>
</html>


