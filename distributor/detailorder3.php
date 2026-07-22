<?php
    include 'koneksi.php';
    include 'floatingbutton.php';
    include 'assets/components/Sessions/sesDistri.php';
    $idadmin = $_SESSION['idadmin'];
    $invoice = $_GET["id"] ?? '';

    // invoice hanya boleh alfanumerik supaya query di bawah (yang masih menyisipkan
    // $invoice langsung ke string SQL) tidak bisa disalahgunakan untuk SQL injection
    if ($invoice === '' )) {
        header('Location: view_cart.php');
        exit;
    }

    // Pastikan invoice ini benar-benar milik mitra yang sedang login
    $stmtOwn = $koneksi->prepare("SELECT COUNT(*) AS jumlah FROM ordermitra WHERE invoice = ? AND idmitra = ?");
    $stmtOwn->bind_param('ss', $invoice, $idadmin);
    $stmtOwn->execute();
    $ownCheck = $stmtOwn->get_result()->fetch_assoc();
    if (($ownCheck['jumlah'] ?? 0) == 0) {
        header('Location: view_cart.php');
        exit;
    }

    $stmtUser = $koneksi->prepare("SELECT * FROM admin_mitra WHERE idadmin = ?");
    $stmtUser->bind_param('s', $idadmin);
    $stmtUser->execute();
    $getUser  = $stmtUser->get_result()->fetch_assoc();

    $stmtPengiriman = $koneksi->prepare("SELECT * FROM orderpengiriman WHERE invoice = ? ORDER BY orderpengiriman.idorderp DESC LIMIT 1");
    $stmtPengiriman->bind_param('s', $invoice);
    $stmtPengiriman->execute();
    $pengiriman = $stmtPengiriman->get_result()->fetch_assoc();

    if (substr($invoice, 0, 1) == "F") {
        header('Location: detailorder_get.php?id=' . rawurlencode($invoice));
        exit;
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
  <?php include "assets/components/Navbar/navbar.php"; ?>

<div class="container mt-2">
    <h3 class="text-center">Detail Order</h3>
    <h3 class="text-center">Invoice #<?= $invoice; ?></h3>
    <hr>
    <h4 class="text-center"><?= $getUser['namamitra'] ?> (Cust ID : <?= $getUser['idadmin']; ?> )</h4><br>
    <b>Status Pesanan</b><br>
    <?= $pengiriman['tgl']; ?><br>
    <?php
        $sql    = "SELECT status, payment FROM ordermitra WHERE invoice = '$invoice' ";
        $query  = $koneksi->query($sql);
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
        $sqlcek         = "SELECT * FROM orderpengiriman WHERE invoice = '$invoice' ORDER BY orderpengiriman.idorderp DESC LIMIT 1 ";
        $query          = $koneksi->query($sqlcek);
        $pengirimancek  = $query->fetch_assoc();

        $sqlcek2        = "SELECT * FROM ordermitra WHERE invoice = '$invoice' ";
        $query          = $koneksi->query($sqlcek2);
        while ($pengirimancek2 = $query->fetch_assoc()){
            $beratcek = $pengirimancek2['berat'];
            $beratnya += $beratcek;
        }    

        if (!$pengirimancek) {        
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
            </tr>
            <?php
                $nomorurut = 1;
                $sum_jumlah = 0; // Inisialisasi awal
                $harga = 0;      // Inisialisasi awal
                $qty = 0;        // Inisialisasi awal

                $sql = "SELECT * FROM ordermitra
                        INNER JOIN variants ON variants.id = ordermitra.idproduk
                        INNER JOIN products ON products.id = variants.idproducts
                        WHERE ordermitra.invoice = '$invoice'";
                $query = $koneksi->query($sql);

                while ($order = $query->fetch_assoc()) {
            ?>
                <tr>
                    <td><?= $nomorurut++; ?></td>
                    <td><?= $order['namaproduk'] . ' ' . $order['variant'] . ' - ' . $order['size']; ?></td>
                    <td><?= $order['jumlah']; ?></td>
                </tr>
            <?php
                $sum_jumlah += $order['jumlah'];
                $harga += $order['subtotal'];
                $qty += $order['jumlah'];
            }

            $totalDiskon = 0;
                if ($qty >= 3) {
                    $diskonPerKelipatan = 15000; // Nilai diskon untuk setiap 3 qty
                    $jumlahKelipatan = floor($qty / 3); // Hitung berapa kali kelipatan 3
                    $totalDiskon = $jumlahKelipatan * $diskonPerKelipatan; // Total diskon
                    $harga -= $totalDiskon; // Kurangi total dengan diskon
                }
            ?>
            <tr>
                <td colspan="3"><b>Total QTY : <?= $qty ?></b></td>
            </tr>
            <tr>
                <td colspan="3"><b>Total Harga Setelah Diskon : <?= number_format($harga) ?></b></td>
            </tr>
        </table>     
    </div>   
    <?php $totala =  $harga;  ?>
    
    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
        $totalb = 0;
        $sql    = "SELECT * FROM ordermitra 
                    INNER JOIN variants ON variants.id = ordermitra.idproduk
                    INNER JOIN products ON variants.idproducts = products.id 
                    WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori = 2";
        $query  = $koneksi->query($sql);
        while ($gb = $query->fetch_assoc()){
    ?>
    <?php $totalb += $gb['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
        $totald5    = 0;
        $sql        = "SELECT * FROM ordermitra 
                        INNER JOIN variants ON variants.id = ordermitra.idproduk
                        INNER JOIN products ON variants.idproducts = products.id 
                        WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori = 5";
        $query      = $koneksi->query($sql);
        while ($d5 = $query->fetch_assoc()){
    ?>
    <?php $totald5 += $d5['subtotal']; } ?>
    
    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
        $totald10   = 0;
        $sql        = "SELECT * FROM ordermitra 
                        INNER JOIN variants ON variants.id = ordermitra.idproduk
                        INNER JOIN products ON variants.idproducts = products.id 
                        WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori = 10";
        $query      = $koneksi->query($sql);
        while ($d10 = $query->fetch_assoc()){
    ?>
    <?php  $totald10 += $d10['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
        $totald15   = 0;
        $sql        = "SELECT * FROM ordermitra 
                        INNER JOIN variants ON variants.id = ordermitra.idproduk
                        INNER JOIN products ON variants.idproducts = products.id 
                        WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori = 15";
        $query      = $koneksi->query($sql);
        while ($d15 = $query->fetch_assoc()){
    ?>
    <?php  $totald15 +=  $d15['subtotal']; } ?> 

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
        $totald17   = 0;
        $sql        = "SELECT * FROM ordermitra 
                        INNER JOIN variants ON variants.id = ordermitra.idproduk
                        INNER JOIN products ON variants.idproducts = products.id 
                        WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori = 17";
        $query      = $koneksi->query($sql);
        while ($d17 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald17 +=  $d17['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
        $totald20   = 0;
        $sql        = "SELECT * FROM ordermitra 
                        INNER JOIN variants ON variants.id = ordermitra.idproduk
                        INNER JOIN products ON variants.idproducts = products.id 
                        WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori = 20";
        $query      = $koneksi->query($sql);
        while ($d20 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald20 +=  $d20['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
        $totald25   = 0;
        $sql        = "SELECT * FROM ordermitra 
                        INNER JOIN variants ON variants.id = ordermitra.idproduk
                        INNER JOIN products ON variants.idproducts = products.id 
                        WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori = 25";
        $query      = $koneksi->query($sql);
        while ($d25 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald25 +=  $d25['subtotal']; } ?>   

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
        $ttl52  = 0;
        $sql    = "SELECT MAX(variants.harga) AS subtotal 
                    FROM ordermitra 
                    INNER JOIN variants ON variants.id = ordermitra.idproduk
                    INNER JOIN products ON variants.idproducts = products.id 
                    WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori = 52";
        $query  = $koneksi->query($sql);
        $d52    = $query->fetch_assoc();
        $ttl52 += $d52['subtotal'];
        // $sql = "SELECT * FROM ordermitra INNER JOIN produk ON produk.idproduk = ordermitra.idproduk WHERE ordermitra.invoice AND ordermitra.jumlah > 0 AND products.idkategori = 52";
    ?>

    <?php
        $totald51   = 0;
        $sql        = "SELECT * FROM ordermitra 
                        INNER JOIN variants ON variants.id = ordermitra.idproduk
                        INNER JOIN products ON variants.idproducts = products.id 
                        WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori = 51";
        $query      = $koneksi->query($sql);
        while ($d51 = $query->fetch_assoc()){  
            $totald51=2*($sum_jumlah/3);
        }
    ?>      
    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
        $totaldfree=0;
        $sql = "SELECT * FROM ordermitra 
                    INNER JOIN variants ON variants.id = ordermitra.idproduk
                    INNER JOIN products ON variants.idproducts = products.id  
                    WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori BETWEEN 52 AND 80";
        $query = $koneksi->query($sql);
        while ($dfree = $query->fetch_assoc()){
    ?>
    
    <?php  $totaldfree +=  $dfree['subtotal']; } ?>   
    <!----------------------------------------------------------------------------------------------------------------------------->

    <hr>
    <?php
        $apaja      = $pengiriman['dropship'];
        $dropship   = $pengiriman['berat'];
    
        if($dropship <= 5000 && $dropship >= 0 && $apaja == 'ya') {
            $biayad=3000;
        } else if($dropship <= 10000 && $dropship >= 6000 && $apaja == 'ya') {
            $biayad=5000;
        } else if($dropship <= 20000 && $dropship >= 11000 && $apaja == 'ya') {
            $biayad=10000;
        } else if($dropship <= 30000 && $dropship >= 21000 && $apaja == 'ya') {
            $biayad=15000;
        } else if($dropship <= 40000 && $dropship >= 31000 && $apaja == 'ya') {
            $biayad=20000;
        } else if($dropship <= 50000 && $dropship >= 41000 && $apaja == 'ya') {
            $biayad=25000;
        } else if($dropship <= 60000 && $dropship >= 51000 && $apaja == 'ya') {
            $biayad=30000;
        } else if($dropship <= 70000 && $dropship >= 61000 && $apaja == 'ya') {
            $biayad=35000;
        } else if($dropship <= 80000 && $dropship >= 71000 && $apaja == 'ya') {
            $biayad=40000;
        } else if($dropship <= 90000 && $dropship >= 81000 && $apaja == 'ya') {
            $biayad=45000;
        } else if($dropship <= 100000 && $dropship >= 91000 && $apaja == 'ya') {
            $biayad=50000;
        } else if($dropship <= 110000 && $dropship >= 101000 && $apaja == 'ya') {
            $biayad=55000;
        } else if($dropship <= 120000 && $dropship >= 111000 && $apaja == 'ya') {
            $biayad=60000;
        } else if($dropship <= 130000 && $dropship >= 121000 && $apaja == 'ya') {
            $biayad=65000;
        } else if($dropship <= 140000 && $dropship >= 131000 && $apaja == 'ya') {
            $biayad=70000;
        } else if($dropship <= 150000 && $dropship >= 141000 && $apaja == 'ya') {
            $biayad=75000;
        } else if($dropship <= 160000 && $dropship >= 151000 && $apaja == 'ya') {
            $biayad=80000;
        } else if($dropship <= 170000 && $dropship >= 161000 && $apaja == 'ya') {
            $biayad=85000;
        } else if($dropship <= 180000 && $dropship >= 171000 && $apaja == 'ya') {
            $biayad=90000;
        } else if($dropship <= 190000 && $dropship >= 181000 && $apaja == 'ya') {
            $biayad=95000;
        } else if($dropship <= 200000 && $dropship >= 191000 && $apaja == 'ya') {
            $biayad=100000;
        } else if($apaja=='tidak'){
            $biayad=0;
        }
        
        $diskonfree     = $totaldfree*50/100; 
        $totala         = $totala+$totald51-$diskonfree;
        $tbiayad        = number_format($biayad); 
        $ongkir         = $pengiriman['ongkir'];
        $kurir          = $pengiriman['ekspedisi'];
        $diskonramadhan = $pengiriman['diskonramadhan'];
        $idpengiriman   = $pengiriman['idorderp'];
        
        $diskonbg       = $ttl52*35/100;
        $diskona        = $totala*35/100;
        $diskonb        = $totalb*55/100;
        $diskon5        = $totald5*5/100;
        $diskon10       = $totald10*10/100;
        $diskon15       = $totald15*15/100;
        $diskon17       = $totald17*17/100;
        $diskon20       = $totald20*20/100;
        $diskon25       = $totald25*25/100;
        
        $tdiskona       = number_format($diskona);
        $tdiskonb       = number_format($diskonb);
        $tdiskon5       = number_format($diskon5);
        $tdiskon52      = number_format($diskonbg);
        $tdiskon10      = number_format($diskon10);
        $tdiskon15      = number_format($diskon15);
        $tdiskon17      = number_format($diskon17);
        $tdiskon20      = number_format($diskon20);
        $tdiskon25      = number_format($diskon25);
        $grandtotal     = ($totala + $totalb + $ongkir + $biayad)-( $diskona + $diskonb + $diskon5 + $diskon10 + $diskon15 + $diskon17 + $diskon20 + $diskon25);
        $tongkir        = number_format($ongkir);
        $tgrandtotal    = number_format($grandtotal);
        $test           = $grandtotal-$diskonramadhan;
        $total          = $totala+$totalb;
        $ttotal         = number_format($total);
        
        $p              = 0;
        $dummy          = "SELECT MAX(variants.harga) as harga FROM ordermitra 
                            INNER JOIN variants ON variants.id = ordermitra.idproduk
                            INNER JOIN products ON products.id = variants.idproducts
                            WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori = 52";
        $q              = $koneksi->query($dummy);
        $s              = $q->fetch_assoc();
        $r              = $p+$s['harga'];
        $t              = number_format($r);

        $totalbg        = number_format($ttl52);
        $grandtotalbg   = ($ttl52 + $biayad + $ongkir + $totala + $totalb) - ($diskonbg + $diskona + $diskonb + $diskon5 + $diskon10 + $dikson15 + $diskon17 + $diskon20 + $diskon25);
        $tgrandtotalbg  = number_format($grandtotalbg);

        if($ongkir == 0){
            if($kurir == 'Ahsan' or $kurir == 'Gosend' or $kurir == 'Ambil ke Pusat' or $kurir == 'Disatukan') {
                $ceksql = "SELECT * FROM ordermitra 
                            INNER JOIN variants ON variants.id = ordermitra.idproduk
                            INNER JOIN products ON variants.idproducts = products.id 
                            WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori = 52";
                $kategori       = $koneksi->query($ceksql);
                $idkategoricek  = $kategori->fetch_assoc();
                
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
                echo "<hr>";
                    echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
            }else{

                $ceksql         = "SELECT * FROM ordermitra 
                                    INNER JOIN variants ON variants.id = ordermitra.idproduk
                                    INNER JOIN products ON variants.idproducts = products.id 
                                    WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori = 52";
                $kategori       = $koneksi->query($ceksql);
                $idkategoricek  = $kategori->fetch_assoc();
                
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
                
                if($diskonb > 0){
                    echo "Diskon Grade B 55% : Rp. -$tdiskonb<br>";
                }
                if($diskon5 > 0){
                    echo "Diskon 5% : Rp. -$tdiskon5<br>";
                }
                if($diskon10 > 0){
                    echo "Diskon 10% : Rp. -$tdiskon10<br>";
                }
                if($diskon15 > 0){
                    echo "Diskon 15% : Rp. -$tdiskon15<br>";
                }
                if($diskon17 > 0){
                    echo "Diskon 17% : Rp. -$tdiskon17<br>";
                }                    
                if($diskon20 > 0){
                    echo "Diskon 20% : Rp. -$tdiskon20<br>";
                }  
                if($diskon25 > 0){
                    echo "Diskon 25% : Rp. -$tdiskon25<br></p>";
                }             
                echo "<hr>";
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
            $ceksql         = "SELECT * FROM ordermitra 
                                INNER JOIN variants ON variants.id = ordermitra.idproduk 
                                INNER JOIN products ON variants.idproducts = products.id
                                WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori = 52";
            $kategori       = $koneksi->query($ceksql);
            $idkategoricek  = $kategori->fetch_assoc();
            
            if($idkategoricek) {
                echo "<p align='right'><b>Total : Rp. $t </b><br>";
            } else {
                echo "<p align='right'><b>Total : Rp. $ttotal </b><br>";
            }
            if($biayad > 0){
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
        
            // $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE namaproduk LIKE '%Esacus Dress%' AND ordermitra.invoice='$invoice' and ordermitra.jumlah>0 ";
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
            if($status['status'] == 'Pending' && ($kurir == 'Ahsan' or $kurir == 'Gosend' or $kurir == 'Ambil ke Pusat' or $kurir == 'Disatukan' or $kurir == 'idetruck')){
    ?>
  
    <?php
        $jumlahhari = '+1 days'; 
        $no         = 1;
        $dataproduk = $koneksi->query("SELECT * FROM ordermitra WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 GROUP BY invoice");
        while($tampilkan = $dataproduk->fetch_assoc()){
    ?>
    <center>
        <button type="submit" class="btn btn-sm" name="cari" id="linkmiki<?= $tampilkan['idorder']; ?>">
            <a class='btn btn-info btn-lg' href='formpembayaran2.php?id=<?= $invoice;?>&total=<?= $grandtotal;?>'>Konfimasi Pembayaran</a>
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
  $dataproduk=$koneksi->query("SELECT * FROM ordermitra  WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 GROUP BY invoice");
  while($tampilkan=$dataproduk->fetch_assoc()){

?>
      <center><button type="submit" class="btn btn-sm" name="cari" id="linkmiki<?= $tampilkan['idorder']; ?>">        
        <?php
            $total_barang = str_replace(',', '', $tgrandtotal);
            $totalForLink = $total_barang;
        ?>

        <a class='btn btn-info btn-lg' href='formpembayaran2.php?id=<?= $invoice;?>&total=<?= $totalForLink;?>'>Konfirmasi Pembayaran</a>

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