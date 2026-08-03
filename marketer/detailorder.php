<?php 
    session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesMarketer.php';
    $invoice            = $_GET["id"];
    $idmitramarketer    = $_SESSION['idmitramarketer'];
        
    $queryUser          = $koneksi->query("SELECT * FROM mitramarketer WHERE idmitramarketer = '$idmitramarketer'");
    $getUser            = $queryUser->fetch_assoc();

    $sql                = "SELECT * FROM orderpengiriman WHERE invoice='$invoice' order by orderpengiriman.idorderp desc limit 1 ";
    $query              = $koneksi->query($sql);
    $pengiriman         = $query->fetch_assoc();

    if (substr($invoice,0,1)=="F") {
        echo "<script>location='detailorder_get.php?id=$invoice'</script>";
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>WNJ</title>
    </head>
  <body>
  <?php include "assets/components/Navbar/navbar2.php"; ?>

<div class="container mt-2">
    <h3 class="text-center">Detail Order</h3>
    <h3 class="text-center">Invoice #<?= $invoice; ?></h3>
    
    <hr>
    
    <h4 class="text-center"><?= $getUser['namaagen'] ?> (Cust ID : <?= $getUser['idmitramarketer']; ?> )</h4><br>
    
    <b>Status Pesanan</b><br>
    <?= $pengiriman['tgl']; ?><br>
    
    <?php
        $sql = "SELECT status, payment FROM ordermarketer WHERE invoice='$invoice' ";
        $query = $koneksi->query($sql);
        $status = $query->fetch_assoc();
    ?>
    
    Payment : <?= $status['payment'] ?><br>
    Status  : <?= $status['status'] ?>
    <hr>
    <b>Data Pengiriman</b><br>
    Dari        : <?= $pengiriman['namapengirim']; ?>, <?= $pengiriman['tlppengirim']; ?><br>
    Dikirim Ke  : <?= $pengiriman['namapenerima']; ?>, <?= $pengiriman['tlppenerima']; ?><br>
    Alamat      : <?= $pengiriman['alamat']; ?><br>
    Provinsi    : <?= $pengiriman['provinsi']; ?><br>
    Kota/Kab    : <?= $pengiriman['kota']; ?><br>
    Kecamatan   : <?= $pengiriman['kecamatan']; ?><br>
    Ekspedisi   : <?= strtoupper($pengiriman['ekspedisi']); ?><br>

    <?php 
    $sqlcek = "SELECT * FROM orderpengiriman WHERE invoice='$invoice' order by orderpengiriman.idorderp desc limit 1 ";
    $query = $koneksi->query($sqlcek);
    $pengirimancek = $query->fetch_assoc();

    $sqlcek2 = "SELECT * FROM ordermarketer WHERE invoice='$invoice' ";
    $query = $koneksi->query($sqlcek2);
    while ($pengirimancek2 = $query->fetch_assoc()){
        $beratcek=$pengirimancek2['berat'];
        $beratnya+= $beratcek;
    }    

    if ($pengirimancek) {
        
      }
      else{
          echo "<b>Alamat belum diisi. Silahkan isi alamat <a class='link text-danger' href='formpengirimanb.php?id=$invoice&berat=$beratnya'>disini.</a></b>";
            
      }

    ?>
    <hr>
<div class="table-responsive"> 
<?php if (substr($invoice,0,1)=="V"): ?>
  
<a href="isivoal?id=<?= $invoice; ?>" class="btn btn-danger btn-sm" style="width: 100%">Isi Box Voal & Request Kartu Ucapan</a>
<br>
<?php endif ?>
<br>
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
    $sql = "SELECT * FROM ordermarketer inner join produk on produk.idproduk=ordermarketer.idproduk 
            WHERE ordermarketer.invoice='$invoice' 
            and ordermarketer.jumlah>0
 ";
  $query = $koneksi->query($sql);
  while ($order = $query->fetch_assoc()){
    
    ?>
<tr>
  <td><?= $nomorurut++; ?></td>
  <td><?= $order['namaproduk']; ?></td>
  <td><?= $order['jumlah'];?>Pcs</td>
  <td>
  <?php if ($order['idkategori']>=53): ?>
    -
    <?php else: ?>
    Rp. <?= number_format($order['subtotal']); ?>
  <?php endif ?>
      
  </td>
</tr>    

    <?php $sum_jumlah+=$order['jumlah']; ?>
    <?php } ?>

</table>     
</div>   
    <?php
        $totala=0;
        $sql = "SELECT * FROM ordermarketer inner join produk on produk.idproduk = ordermarketer.idproduk 
                    WHERE ordermarketer.invoice='$invoice' 
                    and ordermarketer.jumlah > 0 and produk.idkategori > 0 
                    and produk.idkategori <> 2";
        $query = $koneksi->query($sql);
        while ($ga = $query->fetch_assoc()){
    ?>
    
    <?php  $totala +=  $ga['subtotal']; } ?>
    
    <!----------------------------------------------------------------------------------------------------------------------------->

      <?php
    $totalb=0;
    $sql = "SELECT * FROM ordermarketer inner join produk on produk.idproduk=ordermarketer.idproduk WHERE ordermarketer.invoice='$invoice' and ordermarketer.jumlah>0 and produk.idkategori=2";
  $query = $koneksi->query($sql);
  while ($gb = $query->fetch_assoc()){
    ?>
    
    <?php  $totalb +=  $gb['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald5=0;
    $sql = "SELECT * FROM ordermarketer inner join produk on produk.idproduk=ordermarketer.idproduk WHERE ordermarketer.invoice='$invoice' and ordermarketer.jumlah>0 and produk.idkategori=5";
  $query = $koneksi->query($sql);
  while ($d5 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald5 +=  $d5['subtotal']; } ?>
    
    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald10=0;
    $sql = "SELECT * FROM ordermarketer inner join produk on produk.idproduk=ordermarketer.idproduk WHERE ordermarketer.invoice='$invoice' and ordermarketer.jumlah>0 and produk.idkategori=10";
  $query = $koneksi->query($sql);
  while ($d10 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald10 +=  $d10['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald15=0;
    $sql = "SELECT * FROM ordermarketer inner join produk on produk.idproduk=ordermarketer.idproduk WHERE ordermarketer.invoice='$invoice' and ordermarketer.jumlah>0 and produk.idkategori=15";
  $query = $koneksi->query($sql);
  while ($d15 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald15 +=  $d15['subtotal']; } ?> 

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald17=0;
    $sql = "SELECT * FROM ordermarketer inner join produk on produk.idproduk=ordermarketer.idproduk WHERE ordermarketer.invoice='$invoice' and ordermarketer.jumlah>0 and produk.idkategori=17";
  $query = $koneksi->query($sql);
  while ($d17 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald17 +=  $d17['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald20=0;
    $sql = "SELECT * FROM ordermarketer inner join produk on produk.idproduk=ordermarketer.idproduk WHERE ordermarketer.invoice='$invoice' and ordermarketer.jumlah>0 and produk.idkategori=20";
  $query = $koneksi->query($sql);
  while ($d20 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald20 +=  $d20['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald25=0;
    $sql = "SELECT * FROM ordermarketer inner join produk on produk.idproduk=ordermarketer.idproduk WHERE ordermarketer.invoice='$invoice' and ordermarketer.jumlah>0 and produk.idkategori=25";
  $query = $koneksi->query($sql);
  while ($d25 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald25 +=  $d25['subtotal']; } ?>   

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
        $ttl52 = 0;
        $sql = "SELECT MAX(produk.harga) as subtotal FROM ordermarketer INNER JOIN produk ON produk.idproduk = ordermarketer.idproduk WHERE ordermarketer.invoice='$invoice' AND ordermarketer.jumlah > 0 AND produk.idkategori = 52";
        // $sql = "SELECT * FROM ordermarketer INNER JOIN produk ON produk.idproduk = ordermarketer.idproduk WHERE ordermarketer.invoice AND ordermarketer.jumlah > 0 AND produk.idkategori = 52";
        $query = $koneksi->query($sql);
        $d52 = $query->fetch_assoc();
        $ttl52 += $d52['subtotal'];
    ?>

    <?php
    $totald51=0;
    $sql = "SELECT * FROM ordermarketer inner join produk on produk.idproduk=ordermarketer.idproduk WHERE ordermarketer.invoice='$invoice' and ordermarketer.jumlah>0 and produk.idkategori=51";
  $query = $koneksi->query($sql);
  while ($d51 = $query->fetch_assoc()){  
  $totald51=2*($sum_jumlah/3);
}
    ?>      
    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totaldfree=0;
    $sql = "SELECT * FROM ordermarketer inner join produk on produk.idproduk=ordermarketer.idproduk 
            WHERE ordermarketer.invoice='$invoice' 
            and ordermarketer.jumlah>0 
            and produk.idkategori BETWEEN 52 AND 80";
  $query = $koneksi->query($sql);
  while ($dfree = $query->fetch_assoc()){
    ?>
    
    <?php  $totaldfree +=  $dfree['subtotal']; } ?>   

    <!----------------------------------------------------------------------------------------------------------------------------->

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
        
        $diskonfree = $totaldfree*50/100; 
        $totala=$totala+$totald51-$diskonfree;
        $tbiayad=number_format($biayad); 
        $ongkir=$pengiriman['ongkir'];
        $kurir=$pengiriman['ekspedisi'];
        $diskonramadhan=$pengiriman['diskonramadhan'];
        $idpengiriman=$pengiriman['idorderp'];
        
        $diskonbg = $ttl52*35/100;
        $diskona=$totala*35/100;
        $diskonb=$totalb*55/100;
        $diskon5=$totald5*5/100;
        $diskon10=$totald10*10/100;
        $diskon15=$totald15*15/100;
        $diskon17=$totald17*17/100;
        $diskon20=$totald20*20/100;
        $diskon25=$totald25*25/100;
        
        $tdiskona=number_format($diskona);
        $tdiskonb=number_format($diskonb);
        $tdiskon5=number_format($diskon5);
        $tdiskon52=number_format($diskonbg);
        $tdiskon10=number_format($diskon10);
        $tdiskon15=number_format($diskon15);
        $tdiskon17=number_format($diskon17);
        $tdiskon20=number_format($diskon20);
        $tdiskon25=number_format($diskon25);
        $grandtotal=($totala+$totalb+$ongkir+$biayad)-($diskona+$diskonb+$diskon5+$diskon10+$diskon15+$diskon17+$diskon20+$diskon25);
        $tongkir=number_format($ongkir);
        $tgrandtotal=number_format($grandtotal);
        $test=$grandtotal-$diskonramadhan;
        $total=$totala+$totalb;
        $ttotal=number_format($total);
        
        $p = 0;
        $dummy = "SELECT MAX(produk.harga) as harga FROM ordermarketer INNER JOIN produk ON produk.idproduk = ordermarketer.idproduk WHERE ordermarketer.invoice='$invoice' AND ordermarketer.jumlah > 0 AND produk.idkategori = 52";
        $q = $koneksi->query($dummy);
        $s = $q->fetch_assoc();
        $r = $p+$s['harga'];
        $t = number_format($r);
        
        // $sql_produk = "SELECT * FROM `produk` WHERE namaproduk LIKE '%Esacus Dress%'";
        // $query_produk = $koneksi->query($sql_produk);
        // $produk_cek = $query_produk->fetch_assoc();
        
        // if ($produk_cek) {
        //     $ttl = 179000;
        //     $total_beli = str_replace(',', '', $ttl);
        //     $diskon_tambahan = $total_beli * 5/100;
        //     $diskon_format = number_format($diskon_tambahan);
            
        //     $total_barang = $grandtotal - $diskon_tambahan;
        //     $ttl_barang = number_format($total_barang);
        // }
        
        $totalbg = number_format($ttl52);
        $grandtotalbg = ($ttl52 + $biayad + $ongkir + $totala + $totalb) - ($diskonbg + $diskona + $diskonb + $diskon5 + $diskon10 + $dikson15 + $diskon17 + $diskon20 + $diskon25);
        $tgrandtotalbg = number_format($grandtotalbg);
        
        // $totalbg = $totala+$ttl52;
        // $tbiayabg = number_format($biayad);
        // $ongkirbg = $pengiriman['ongkir'];
        // $kurirbg = $pengiriman['ekspedisi'];
        // $diskon52 = $ttl52*50/100;
        
        // $tdiskon52 = number_format($diskon52);
        // $grandtotal = 
        
    if($ongkir==0){
      if($kurir=='Ahsan' or $kurir=='Gosend' or $kurir=='Ambil ke Pusat' or $kurir=='Disatukan') {
            $ceksql = "SELECT * FROM ordermarketer INNER JOIN produk ON produk.idproduk = ordermarketer.idproduk WHERE ordermarketer.invoice='$invoice' AND ordermarketer.jumlah > 0 AND produk.idkategori = 52";
            $kategori = $koneksi->query($ceksql);
            $idkategoricek = $kategori->fetch_assoc();
            
            if($idkategoricek) {
                echo "<p align='right'><b>Total : Rp. $t </b><br>";
            } else {
                echo "<p align='right'><b>Total : Rp. $ttotal </b><br>";
            }
        //   echo "<p align='right'><b>Total : Rp. $ttotal</b><br>";
            if($biayad>0){
         echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
            }
           
        echo "Estimasi Ongkir : Rp. $tongkir<br>";
           
        echo "Diskon DB 35% : Rp. -$tdiskona<br>";
        
            // $sql_produk = "SELECT * FROM `produk` WHERE `namaproduk` LIKE '%Esacus Dress%'";
            // $query_produk = $koneksi->query($sql_produk);
            // $produk_cek = $query_produk->fetch_assoc();
            
            // if ($produk_cek['namaproduk'] == 'Esacus Dress') {
            //     echo "Diskon Tambahan 5% Hanya Untuk Sampel 1 Produk : Rp. $diskon_format";
            // }
        
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
            if($diskon17>0){
                echo "Diskon 17% : Rp. -$tdiskon17<br>";
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
            // $sql = "SELECT * FROM ordermarketer inner join produk on produk.idproduk=ordermarketer.idproduk WHERE namaproduk LIKE '%Esacus Dress%' AND ordermarketer.invoice='$invoice' and ordermarketer.jumlah>0 ";
            // $query = $koneksi->query($sql);
            // $grandTotalToShow = $tgrandtotal; // Default grand total value
            // $isEsacusDressFound = false;
            
            // while ($order = $query->fetch_assoc()) {
            //     if (strpos($order['namaproduk'], 'Esacus Dress') !== false) {
            //         $isEsacusDressFound = true;
            //         break; // No need to continue checking, we found Esacus Dress
            //     }
            // }
            
            // if ($isEsacusDressFound) {
            //     echo "<p align='right'><b>GrandTotal : Rp. $ttl_barang</b><br></br></p>";
            // } else {
                echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
            // }
        }else{
            $ceksql = "SELECT * FROM ordermarketer INNER JOIN produk ON produk.idproduk = ordermarketer.idproduk WHERE ordermarketer.invoice='$invoice' AND ordermarketer.jumlah > 0 AND produk.idkategori = 52";
            $kategori = $koneksi->query($ceksql);
            $idkategoricek = $kategori->fetch_assoc();
            
            if($idkategoricek) {
                echo "<p align='right'><b>Total : Rp. $t </b><br>";
            } else {
                echo "<p align='right'><b>Total : Rp. $ttotal </b><br>";
            }
                if($biayad>0){
            echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
                }             
            echo "Estimasi Ongkir : Menunggu di Isi Admin<br>";
                 
            echo "Diskon DB 35% : Rp. -$tdiskona<br>";
            
            // $sql = "SELECT * FROM ordermarketer inner join produk on produk.idproduk=ordermarketer.idproduk WHERE namaproduk LIKE '%Esacus Dress%' AND ordermarketer.invoice='$invoice' and ordermarketer.jumlah>0 ";
            // $query = $koneksi->query($sql);
            // while ($order = $query->fetch_assoc()){
            //     if ($order['namaproduk']) {
            //         echo "Diskon Tambahan 5% Hanya Untuk Sampel 1 Produk : Rp. " . $diskon_format;
            //     }
            // }
            
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
            if($diskon17>0){
                echo "Diskon 17% : Rp. -$tdiskon17<br>";
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
    
    // elseif($idkategori = 52) {
    //     echo "<p align='right'><b>Total Barang : $totalbg</b><br>";
    //     if ($biayad > 0) {
    //         echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
    //     }
    //     echo "Estimasi Ongkir : Rp. $tongkir<br>";
    //     echo "Diskon DB 35% : Rp. -$tdiskon52<br>";
        
    //     if($diskonb>0){
    //         echo "Diskon Grade B 55% : Rp. -$tdiskonb<br>";
    //     }
    //     if($diskon5>0){
    //         echo "Diskon 5% : Rp. -$tdiskon5<br>";
    //     }
    //     if($diskon10>0){
    //         echo "Diskon 10% : Rp. -$tdiskon10<br>";
    //     }
    //     if($diskon15>0){
    //       echo "Diskon 15% : Rp. -$tdiskon15<br>";
    //     }
    //     if($diskon17>0){
    //         echo "Diskon 17% : Rp. -$tdiskon17<br>";
    //     }            
    //     if($diskon20>0){
    //         echo "Diskon 20% : Rp. -$tdiskon20<br>";
    //     }
    //     if($diskon25>0){
    //         echo "Diskon 25% : Rp. -$tdiskon25<br>";
    //     }
        
    //     echo "<hr>";
    //     echo "<p align='right'><b>Grand Total : Rp. $tgrandtotalbg</b><br></br></p>";
    // }
    
    else {
        $ceksql = "SELECT * FROM ordermarketer INNER JOIN produk ON produk.idproduk = ordermarketer.idproduk WHERE ordermarketer.invoice='$invoice' AND ordermarketer.jumlah > 0 AND produk.idkategori = 52";
        $kategori = $koneksi->query($ceksql);
        $idkategoricek = $kategori->fetch_assoc();
        
        if($idkategoricek) {
            echo "<p align='right'><b>Total : Rp. $t </b><br>";
        } else {
            echo "<p align='right'><b>Total : Rp. $ttotal </b><br>";
        }
     if($biayad>0){
    echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
     }
    
    echo "Estimasi Ongkir : Rp. $tongkir<br>";
     
    echo "Diskon DB 35% : Rp. -$tdiskona<br>";
    
        // $sql = "SELECT * FROM ordermarketer inner join produk on produk.idproduk=ordermarketer.idproduk WHERE namaproduk LIKE '%Esacus Dress%' AND ordermarketer.invoice='$invoice' and ordermarketer.jumlah>0 ";
        // $query = $koneksi->query($sql);
        // while ($order = $query->fetch_assoc()){
        //     if ($order['namaproduk']) {
        //         echo "Diskon Tambahan 5% Hanya Untuk Sampel 1 Produk : Rp. " . $diskon_format;
        //     }
        // }
        
        
        
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
            if($diskon17>0){
                echo "Diskon 17% : Rp. -$tdiskon17<br>";
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
       
        // $sql = "SELECT * FROM ordermarketer inner join produk on produk.idproduk=ordermarketer.idproduk WHERE namaproduk LIKE '%Esacus Dress%' AND ordermarketer.invoice='$invoice' and ordermarketer.jumlah>0 ";
        // $query = $koneksi->query($sql);
        // $grandTotalToShow = $tgrandtotal; // Default grand total value
        // $isEsacusDressFound = false;
        
        // while ($order = $query->fetch_assoc()) {
        //     if (strpos($order['namaproduk'], 'Esacus Dress') !== false) {
        //         $isEsacusDressFound = true;
        //         break; // No need to continue checking, we found Esacus Dress
        //     }
        // }
        
        // if ($isEsacusDressFound) {
        //     echo "<p align='right'><b>GrandTotal : Rp. $ttl_barang</b><br></br></p>";
        // } else {
            echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
        // }
    }
        ?>
    
    <?php 
    // $sql = "SELECT *,COUNT(*) as jumlah FROM orderpembayaran WHERE invoice='$invoice' ";
    // $query = $koneksi->query($sql);
    // $pengirim = $query->fetch_assoc();
    
    // if($pengirim['jumlah']==0){

    //     echo "<center><a class='btn btn-info btn-lg' href='formpembayaran.php?id=$invoice&total=$grandtotal'>Konfimasi Pembayaran</a></center>
    //     ";
    // }else{
    //      echo "<center><p><b>Pembayaran sedang diproses silahkan tunggu.</b></p></center>";
    // }
    ?>

        <?php
        if($ongkir==0){
            if($status['status']=='Pending' and ($kurir=='Ahsan' or $kurir=='Gosend' or $kurir=='Ambil ke Pusat' or $kurir=='Disatukan' or $kurir=='idetruck')){?>
  
   <?php 

  $jumlahhari='+1 days'; 
  $no=1;
  $dataproduk=$koneksi->query("SELECT * FROM ordermarketer  WHERE ordermarketer.invoice='$invoice' and ordermarketer.jumlah>0 GROUP BY invoice");
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
var countDownDatemiki<?= $tampilkan['idorder']; ?>= new Date("<?= $tgl2; ?> 23:59:00").getTime();

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
  $dataproduk=$koneksi->query("SELECT * FROM ordermarketer  WHERE ordermarketer.invoice='$invoice' and ordermarketer.jumlah>0 GROUP BY invoice");
  while($tampilkan=$dataproduk->fetch_assoc()){

?>
      <center><button type="submit" class="btn btn-sm" name="cari" id="linkmiki<?= $tampilkan['idorder']; ?>">
        <?php
            // $sql = "SELECT * FROM ordermarketer 
            //         INNER JOIN produk ON produk.idproduk = ordermarketer.idproduk 
            //         WHERE ordermarketer.invoice = '$invoice' AND ordermarketer.jumlah > 0";
            // $query = $koneksi->query($sql);
            // $grandTotalToShow = $tgrandtotal; // Default grand total value
            // $totalForLink = $grandTotalToShow; // Total for the link
            
            // $isEsacusDressFound = false;
            
            // $harga_barang = str_replace(',', '', $ttl_barang);
            
            // $harga_barang2 = str_replace(',', '', $grandTotalToShow);
            
            // $totalForLink = $harga_barang2;
            // $esacusDressTotal = $harga_barang;
            
            // while ($order = $query->fetch_assoc()) {
            //     if (strpos($order['namaproduk'], 'Esacus Dress') !== false) {
            //         $isEsacusDressFound = true;
            //         $esacusDressTotal += $order['total']; // Add Esacus Dress total
            //     }
            // }
            
            // if ($isEsacusDressFound) {
            //     $totalForLink = $esacusDressTotal;
            // }
            
            // var_dump('Harga Barang ', $totalForLink);
            // var_dump('Harga Barang bukan string', $esacusDressTotal);
        ?>
        
        <?php
            $total_barang = str_replace(',', '', $tgrandtotal);
            $totalForLink = $total_barang;
        ?>

        <a class='btn btn-info btn-lg' href='formpembayaran.php?id=<?= $invoice;?>&total=<?= $totalForLink;?>'>Konfirmasi Pembayaran</a>

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
var countDownDatemiki<?= $tampilkan['idorder']; ?>= new Date("<?= $tgl2; ?> 23:59:00").getTime();

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