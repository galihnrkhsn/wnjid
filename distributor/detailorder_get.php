<?php 
session_start();

include 'koneksi.php'; 
include 'floatingbutton.php'; 

if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

$invoice=$_GET["id"];

  $sql = "SELECT * FROM orderpengiriman WHERE invoice='$invoice' order by orderpengiriman.idorderp desc limit 1 ";
  $query = $koneksi->query($sql);
  $pengiriman = $query->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WNJ</title>

    <!-- Load File bootstrap.min.css yang ada difolder css -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- Load File bootstrap.min.css yang ada difolder css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <script src="https://kit.fontawesome.com/b812ba9ae3.js" crossorigin="anonymous"></script>

  
    
  </head>
  <body>
    <!-- Membuat Menu Header / Navbar -->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="transaksi.php"><span class="glyphicon glyphicon-chevron-left"></span></a></div>
  <div class="col-8" ><p>DETAIL ORDERAN</p></div>
  <div class="col-2"></div>
</div><br><br><br><br>

<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #eee   center center;
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

.navhitam {
   
    background: black  center center;
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

.navbaru p {
  
  padding: 10px 0;
  font-size: 20px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru span {
  
  padding: 5px 0;
  font-size: 30px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru2 {
   
    
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

</style>

<div class="container">
    
    
    <h3><center>Invoice #<?php echo $invoice; ?></center></h3>
    
    <hr>
    
    <h4><center><?php echo $_SESSION["admin_mitra"]["namamitra"]; ?> (Cust ID : <?php echo $_SESSION["admin_mitra"]["idadmin"]; ?> )</center></h4><br>
    
    <b>Status Pesanan</b><br>
    <?php echo $pengiriman['tgl']; ?><br>
    
    <?php
    $sql = "SELECT status,payment FROM ordermitra WHERE invoice='$invoice' ";
  $query = $koneksi->query($sql);
  $status = $query->fetch_assoc();
    ?>
    
    Payment : <?php echo $status['payment'] ?><br>
    Status  : <?php echo $status['status'] ?>
    <hr>
    <b>Data Pengiriman</b><br>
    Dari        : <?php echo $pengiriman['namapengirim']; ?><br>
    Dikirim Ke  : <?php echo $pengiriman['namapenerima']; ?><br>
    Alamat      : <?php echo $pengiriman['alamat']; ?><br>
    Provinsi    : <?php echo $pengiriman['provinsi']; ?><br>
    Kota/Kab    : <?php echo $pengiriman['kota']; ?><br>
    Kecamatan   : <?php echo $pengiriman['kecamatan']; ?><br>
    Ekspedisi   : <?php echo $pengiriman['ekspedisi']; ?><br>

    <?php 
    $sqlcek = "SELECT * FROM orderpengiriman WHERE invoice='$invoice' order by orderpengiriman.idorderp desc limit 1 ";
    $query = $koneksi->query($sqlcek);
    $pengirimancek = $query->fetch_assoc();

    $sqlcek2 = "SELECT * FROM ordermitra WHERE invoice='$invoice' ";
    $query = $koneksi->query($sqlcek2);
    while ($pengirimancek2 = $query->fetch_assoc()){
        $beratcek=$pengirimancek2['berat'];
        $beratnya+= $beratcek;
    }    

    if ($pengirimancek) {
        
      }
      else{
          echo "<b>Alamat belum diisi. Silahkan isi alamat <a class='link' href='formpengiriman.php?id=$invoice&berat=$beratnya'>disini.</a></b>";
            
      }

    ?>
    <hr>
<div class="table-responsive">    
    <b>Item Pesanan</b><br>
<table class="table table-bordered">
  <tr>
    <th>No</th>
    <th>Nama Barang</th>
    <th>QTY</th>
    <th>Total</th>
  </tr>

   
    <?php
   // $total=0;
    $nomorurut = 1;
    $sql = "SELECT produk.namaproduk, SUM(ordermitra.jumlah) as jumlah, SUM(ordermitra.subtotal) as subtotal 
            FROM ordermitra 
            inner join 
            produk on produk.idproduk=ordermitra.idproduk 
            WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 
            GROUP BY produk.idproduk
            ";
  $query = $koneksi->query($sql);
  while ($order = $query->fetch_assoc()){
    ?>
<tr>
  <td><?= $nomorurut++; ?></td>
  <td><?php echo $order['namaproduk']; ?> </td>
  <td><?php echo $order['jumlah'];?>Pcs</td>
  <td>Rp. <?php echo number_format($order['subtotal']); ?></td>
</tr>    

<?php  $total_qty +=  $order['jumlah']; ?>    
    <?php } ?>

</table>     
</div>   
  <?php
  $jumlah_produknya = $total_qty/2;
    $totala=0;
    $sql = "SELECT produk.harga FROM ordermitra 
            inner join produk on produk.idproduk=ordermitra.idproduk 
            WHERE ordermitra.invoice='$invoice' 
            and ordermitra.jumlah>0 
            and (produk.idkategori=11 or produk.idkategori=12 or produk.idkategori=13)
            ORDER BY produk.harga desc
            LIMIT ".$jumlah_produknya."
            ";
  $query = $koneksi->query($sql);
  while ($ga = $query->fetch_assoc()){
$totala +=  $ga['harga']; 
    } ?>
    
               
    
    <hr>
     <?php
     $apaja=$pengiriman['dropship'];
    $dropship=$pengiriman['berat'];
    
        if($dropship<=5000 and $dropship>=0 and $apaja=='ya') {
            $biayad=3000;
        }
        else if($dropship<=10000 and $dropship>=6000 and $apaja=='ya') {
            $biayad=5000;
        }
        else if($dropship<=20000 and $dropship>=11000 and $apaja=='ya') {
            $biayad=10000;
        }
         else if($dropship<=30000 and $dropship>=21000 and $apaja=='ya') {
            $biayad=15000;
        }
        else if($dropship<=40000 and $dropship>=31000 and $apaja=='ya') {
            $biayad=20000;
        }
         else if($dropship<=50000 and $dropship>=41000 and $apaja=='ya') {
            $biayad=25000;
        }
        else if($dropship<=60000 and $dropship>=51000 and $apaja=='ya') {
            $biayad=30000;
        }
         else if($dropship<=70000 and $dropship>=61000 and $apaja=='ya') {
            $biayad=35000;
        }
        else if($dropship<=80000 and $dropship>=71000 and $apaja=='ya') {
            $biayad=40000;
        }
         else if($dropship<=90000 and $dropship>=81000 and $apaja=='ya') {
            $biayad=45000;
         }
        else if($dropship<=100000 and $dropship>=91000 and $apaja=='ya') {
            $biayad=50000;
         }
        else if($dropship<=110000 and $dropship>=101000 and $apaja=='ya') {
            $biayad=55000;
        }
         else if($dropship<=120000 and $dropship>=111000 and $apaja=='ya') {
            $biayad=60000;
        }
        else if($dropship<=130000 and $dropship>=121000 and $apaja=='ya') {
            $biayad=65000;
        }
         else if($dropship<=140000 and $dropship>=131000 and $apaja=='ya') {
            $biayad=70000;
        }
        else if($dropship<=150000 and $dropship>=141000 and $apaja=='ya') {
            $biayad=75000;
        }
         else if($dropship<=160000 and $dropship>=151000 and $apaja=='ya') {
            $biayad=80000;
        }
        else if($dropship<=170000 and $dropship>=161000 and $apaja=='ya') {
            $biayad=85000;
        }
         else if($dropship<=180000 and $dropship>=171000 and $apaja=='ya') {
            $biayad=90000;
         }
        else if($dropship<=190000 and $dropship>=181000 and $apaja=='ya') {
            $biayad=95000;
        }
         else if($dropship<=200000 and $dropship>=191000 and $apaja=='ya') {
            $biayad=100000;
         }
        else if($apaja=='tidak'){
            $biayad=0;
        }        
        
        $tbiayad=number_format($biayad); 
        $ongkir=$pengiriman['ongkir'];
        $kurir=$pengiriman['ekspedisi'];
        $diskonramadhan=$pengiriman['diskonramadhan'];
        $idpengiriman=$pengiriman['idorderp'];
        $diskona=$totala*35/100;
        $tdiskona=number_format($diskona);
        
        $grandtotal=($totala+$ongkir+$biayad)-($diskona);
        $tongkir=number_format($ongkir);
        $tgrandtotal=number_format($grandtotal);
        $test=$grandtotal;
        $total=$totala;
        $ttotal=number_format($total);
        
    if($ongkir==0){
      if($kurir=='Ahsan' or $kurir=='Gosend' or $kurir=='Ambil ke Pusat' or $kurir=='Disatukan') {
           echo "<p align='right'><b>Total : Rp. $ttotal</b><br>";
            if($biayad>0){
         echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
            }
           
        echo "Estimasi Ongkir : Rp. $tongkir<br>";
           
        echo "Diskon DB 35% : Rp. -$tdiskona<br>";
            if($diskonb>0){
         echo "Diskon Grade B 55% : Rp. -$tdiskonb<br>";
            }
            if($diskon5>0){
                echo "Diskon 5% : Rp. -$tdiskon5<br>";
                   }
            if($diskon10>0){
         echo "Diskon 10% : Rp. -$tdiskon10<br>";
            }
            if($diskon15>0){
                echo "Diskon 15% : Rp. -$tdiskon15<br>";
                   }
            if($diskon20>0){
         echo "Diskon 20% : Rp. -$tdiskon20<br>";
            }  
            if($diskon25>0){
                echo "Diskon 25% : Rp. -$tdiskon25<br></p>";
                   }                               
      // echo "<hr>";
      // echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
      echo "<hr>";
      //echo "<p align='right'>Diskon Manual : Rp. -$diskonramadhan<br>";
      echo "<p align='right'><b>GrandTotal : Rp. $test</b><br></br></p>";
        }else{
              echo "<p align='right'><b>Total : $ttotal</b><br>";
                if($biayad>0){
            echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
                }             
            echo "Estimasi Ongkir : Menunggu di Isi Admin<br>";
                 
            echo "Diskon DB 35% : Rp. -$tdiskona<br>";
            if($diskonb>0){
            echo "Diskon Grade B 55% : Rp. -$tdiskonb<br>";
            }
            if($diskon5>0){
                echo "Diskon 5% : Rp. -$tdiskon5<br>";
                   }
            if($diskon10>0){
         echo "Diskon 10% : Rp. -$tdiskon10<br>";
            }
            if($diskon15>0){
                echo "Diskon 15% : Rp. -$tdiskon15<br>";
                   }
            if($diskon20>0){
         echo "Diskon 20% : Rp. -$tdiskon20<br>";
            }  
            if($diskon25>0){
                echo "Diskon 25% : Rp. -$tdiskon25<br></p>";
                   }             
            //echo "<hr>";
            //echo "<p align='right'><b>GrandTotal : Pending</b><br></br></p>";
            echo "<hr>";
       //echo "<p align='right'>Diskon Manual : Rp. -$diskonramadhan<br>";
       echo "<p align='right'><b>GrandTotal : Rp. $test</b><br></br></p>";
       }
    }
    
    else{
    echo "<p align='right'><b>Total : Rp. $ttotal</b><br>";
     if($biayad>0){
    echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
     }
    
    echo "Estimasi Ongkir : Rp. $tongkir<br>";
     
    echo "Diskon DB 35% : Rp. -$tdiskona<br>";
    if($diskonb>0){
    echo "Diskon Grade B 55% : Rp. -$tdiskonb<br>";
    }
    if($diskon5>0){
        echo "Diskon 5% : Rp. -$tdiskon5<br>";
           }
    if($diskon10>0){
 echo "Diskon 10% : Rp. -$tdiskon10<br>";
    }
    if($diskon15>0){
        echo "Diskon 15% : Rp. -$tdiskon15<br>";
           }
    if($diskon20>0){
 echo "Diskon 20% : Rp. -$tdiskon20<br>";
    }  
    if($diskon25>0){
        echo "Diskon 25% : Rp. -$tdiskon25<br>";
           }     
    //echo "<hr>";
    //echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
    echo "<hr>";
       //echo "<p align='right'>Diskon Manual : Rp. -$diskonramadhan<br>";
       echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
    }
        ?>
    
        <?php
        if($ongkir==0){
            if($status['status']=='Pending' and ($kurir=='Ahsan' or $kurir=='Gosend' or $kurir=='Ambil ke Pusat' or $kurir=='Disatukan' or $kurir=='idetruck')){?>
  
   <?php 

  $jumlahhari='+1 days'; 
  $no=1;
  $dataproduk=$koneksi->query("SELECT * FROM ordermitra  WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 GROUP BY invoice");
  while($tampilkan=$dataproduk->fetch_assoc()){

?>
      <center><button type="submit" class="btn btn-sm" name="cari" id="linkmiki<?= $tampilkan['idorder']; ?>">
       <a class='btn btn-info btn-lg' href='formpembayaran.php?id=<?= $invoice;?>&total=<?= $grandtotal;?>'>Konfimasi Pembayaran</a>
      </button>
      <p id="link2<?= $tampilkan['idorder']; ?>" >Batas Waktu Pembayaran</p>

      <p id="demomiki<?= $tampilkan['idorder']; ?>" style="color: red;"></p>
      <br>
      </center>
<?php 
date_default_timezone_set('Asia/Jakarta');
$tgl1 = $tampilkan['tgl'];// pendefinisian tanggal awal
$tgl2 = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari


 ?>

<script>

// Mengatur waktu akhir perhitungan mundur
var countDownDatemiki<?= $tampilkan['idorder']; ?>= new Date("<?php echo $tgl2; ?> 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDatemiki<?= $tampilkan['idorder']; ?> - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demomiki<?= $tampilkan['idorder']; ?>").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demomiki<?= $tampilkan['idorder']; ?>").innerHTML = "<a class='btn btn-danger btn-lg' href='orderbatal.php?invoice=<?= $tampilkan['invoice'];?>'><i class='fa fa-times'></i> Batalkan Pesanan</a><br><p style='color: black;'>Batas Pembayaran Sudah Lewat</p>";
      var x = document.getElementById("linkmiki<?= $tampilkan['idorder']; ?>");
      var y = document.getElementById("link2<?= $tampilkan['idorder']; ?>");
 
    y.style.display = "none";
    x.style.display = "none";
    }
}, 1000);
</script>

<?php } ?> 

<?php            }
            else if($kurir=='Ahsan' or $kurir=='Gosend' or $kurir=='Ambil ke Pusat' or $kurir=='Disatukan' or $kurir=='idetruck'
              and ($status['status']=='Proses' or $status['status']=='Selesai')){
            echo "<center>Sudah Konfirmasi Pembayaran</center>";
            } 
            else{
            echo "<center>Alamat Pengiriman belum di isi <a class='link' href='formpengiriman.php?id=$invoice&berat=$beratcek'>disini.</a></center>";
            }
    }
    else if($status['status']=='Pending'){ ?>

   <?php 
  $jumlahhari='+1 days'; 
  $dataproduk=$koneksi->query("SELECT * FROM ordermitra  WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 GROUP BY invoice");
  while($tampilkan=$dataproduk->fetch_assoc()){

?>
      <center><button type="submit" class="btn btn-sm" name="cari" id="linkmiki<?= $tampilkan['idorder']; ?>">
       <a class='btn btn-info btn-lg' href='formpembayaran.php?id=<?= $invoice;?>&total=<?= $grandtotal;?>'>Konfimasi Pembayaran</a>
      </button>
      <p id="link2<?= $tampilkan['idorder']; ?>" >Batas Waktu Pembayaran</p>

      <p id="demomiki<?= $tampilkan['idorder']; ?>" style="color: red;"></p>
      <br>
      </center>
<?php 
date_default_timezone_set('Asia/Jakarta');
$tgl1 = $tampilkan['tgl'];// pendefinisian tanggal awal
$tgl2 = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari


 ?>

<script>

// Mengatur waktu akhir perhitungan mundur
var countDownDatemiki<?= $tampilkan['idorder']; ?>= new Date("<?php echo $tgl2; ?> 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDatemiki<?= $tampilkan['idorder']; ?> - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demomiki<?= $tampilkan['idorder']; ?>").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demomiki<?= $tampilkan['idorder']; ?>").innerHTML = "<a class='btn btn-danger btn-lg' href='orderbatal.php?invoice=<?= $tampilkan['invoice'];?>'><i class='fa fa-times'></i> Batalkan Pesanan</a><br><p style='color: black;'>Batas Pembayaran Sudah Lewat</p>";
      var x = document.getElementById("linkmiki<?= $tampilkan['idorder']; ?>");
      var y = document.getElementById("link2<?= $tampilkan['idorder']; ?>");
 
    y.style.display = "none";
    x.style.display = "none";
    }
}, 1000);
</script>

<?php } ?>     

<?php 
    echo "";
    } 
     else if($status['status']=='Proses' or $status['status']=='Selesai'){
    echo "<center>Sudah Konfirmasi Pembayaran</center>";
    } 
    else{
         echo "<center>Menunggu Pengiriman</center>";
    } 
    ?>
    
           
</div><br>

<br><br>
  </div>
  </div>
</div>
</body>
</html>