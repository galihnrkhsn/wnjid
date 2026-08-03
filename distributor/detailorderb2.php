<?php
    include 'koneksi.php';
    include 'floatingbutton.php';
    include 'assets/components/Sessions/sesDistri.php';
    include '../includes/discount_rules.php';
    $idadmin = $_SESSION['idadmin'];
    $jenis   = $_GET['jenis'] ?? '';
    $invoice = $_GET["id"] ?? '';

    // invoice hanya boleh alfanumerik supaya query di bawah (yang masih menyisipkan
    // $invoice langsung ke string SQL) tidak bisa disalahgunakan untuk SQL injection
    if ($invoice === '' ) {
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
        $beratnya       = 0;
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
                <th>Total</th>
            </tr>
            <?php
                $nomorurut = 1;
                $sql = "SELECT 
                            ordermitra.*, variants.id, variants.idproducts, variants.variant, variants.size, variants.berat,
                            variants.harga, products.id as product_id, products.idpkategori, products.idkategori, products.namaproduk
                        FROM ordermitra 
                        INNER JOIN variants ON variants.id = ordermitra.idproduk
                        INNER JOIN products ON products.id = variants.idproducts
                        WHERE ordermitra.invoice = '$invoice'";
                $query = $koneksi->query($sql);
                $sum_jumlah = 0;
                while ($order = $query->fetch_assoc()){
            ?>
            <tr>
                <td><?= $nomorurut++; ?></td>
                <td><?= $order['namaproduk'] . ' ' . $order['variant'] . ' - ' . $order['size']; ?></td>
                <td><?= $order['jumlah'];?>Pcs</td>
                <td>
                    <?php if ($order['idkategori']>=53): ?>
                        -
                    <?php else: ?>
                        Rp. <?= number_format($order['subtotal']); ?>
                    <?php endif ?>    
                </td>
            </tr>
            <?php 
                $sum_jumlah+=$order['jumlah']; 
                $idproducts = $order['idproducts'];
            ?>
            <?php } ?>

        </table>     
    </div>   
    <?php
        $jenisCheck = $koneksi->query("SELECT variants.jenis FROM ordermitra 
                                        INNER JOIN variants ON variants.id = ordermitra.idproduk
                                        INNER JOIN products ON variants.idproducts = products.id
                                        WHERE ordermitra.invoice = '$invoice'
                                    ")->fetch_assoc()['jenis'];
        $totala = 0;

        if ($jenisCheck == 'b1g1') {
            $total_b1g1     = $koneksi->query("SELECT ordermitra.*, variants.harga as harga_variant
                                                FROM ordermitra
                                                INNER JOIN variants ON variants.id = ordermitra.idproduk
                                                INNER JOIN products ON variants.idproducts = products.id
                                                WHERE ordermitra.invoice = '$invoice' 
                                                AND variants.jenis = 'b1g1'
                                            ");
            while ($row = $total_b1g1->fetch_assoc()) {
                for ($i = 0; $i < $row['jumlah']; $i++) {
                    $expanded[] = $row;
                }
            }

            usort($expanded, function($a, $b) {
                return $b['harga'] <=> $a['harga'];
            });

            $total = count($expanded);
            $limit = $total/2;

            $top_items = array_slice($expanded, 0, $limit);

            foreach ($top_items as $item) {
                $totala += $item['harga'];
            }
        } else {
            $sql    = "SELECT 
                            ordermitra.*, variants.id, variants.idproducts, variants.variant, variants.size, variants.berat,
                            variants.harga, products.id as product_id, products.idpkategori, products.idkategori, products.namaproduk
                        FROM ordermitra 
                        INNER JOIN variants ON variants.id = ordermitra.idproduk
                        INNER JOIN products ON variants.idproducts = products.id
                        WHERE ordermitra.invoice = '$invoice' 
                        AND ordermitra.jumlah > 0 AND products.idkategori > 0 AND products.idkategori <> 2";
            $query = $koneksi->query($sql);
            while ($ga = $query->fetch_assoc()){
                $disc   =  $ga['disc']; 
                $totala +=  $ga['subtotal']; 
            } 
        }
    ?>
    
    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
        $totalb = 0;
        $sql    = "SELECT
                            ordermitra.*, variants.id, variants.idproducts, variants.variant, variants.size, variants.berat,
                            variants.harga, products.id as product_id, products.idpkategori, products.idkategori, products.namaproduk
                        FROM ordermitra
                    INNER JOIN variants ON variants.id = ordermitra.idproduk
                    INNER JOIN products ON variants.idproducts = products.id
                    WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori = 2";
        $query  = $koneksi->query($sql);
        while ($gb = $query->fetch_assoc()){
    ?>
    <?php $totalb += $gb['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
        // Tier diskon tambahan per-kategori (D5/D10/D15/D17/D20/D25 di tabel kategori) - satu
        // query+loop dipakai ulang untuk semua tier lewat $categoryDiscountRules
        // (includes/discount_rules.php), gantiin 6 blok yang isinya sama persis sebelumnya.
        $totalPerCategory = [];
        foreach ($categoryDiscountRules as $idkategoriRule => $persenRule) {
            $totalPerCategory[$idkategoriRule] = 0;
            $sql    = "SELECT
                            ordermitra.*, variants.id, variants.idproducts, variants.variant, variants.size, variants.berat,
                            variants.harga, products.id as product_id, products.idpkategori, products.idkategori, products.namaproduk
                        FROM ordermitra
                        INNER JOIN variants ON variants.id = ordermitra.idproduk
                        INNER JOIN products ON variants.idproducts = products.id
                        WHERE ordermitra.invoice = '$invoice' AND ordermitra.jumlah > 0 AND products.idkategori = $idkategoriRule";
            $query  = $koneksi->query($sql);
            while ($d = $query->fetch_assoc()) {
                $totalPerCategory[$idkategoriRule] += $d['subtotal'];
            }
        }
        $totald5  = $totalPerCategory[5];
        $totald10 = $totalPerCategory[10];
        $totald15 = $totalPerCategory[15];
        $totald17 = $totalPerCategory[17];
        $totald20 = $totalPerCategory[20];
        $totald25 = $totalPerCategory[25];
    ?>

    <?php
        $totald51   = 0;
        $sql        = "SELECT 
                            ordermitra.*, variants.id, variants.idproducts, variants.variant, variants.size, variants.berat,
                            variants.harga, products.id as product_id, products.idpkategori, products.idkategori, products.namaproduk
                        FROM ordermitra  
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
        $sql = "SELECT 
                        ordermitra.*, variants.id, variants.idproducts, variants.variant, variants.size, variants.berat,
                        variants.harga, products.id as product_id, products.idpkategori, products.idkategori, products.namaproduk
                    FROM ordermitra  
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

        // Tangga biaya dropship per-band berat - band & biaya dipindah ke $dropshipFeeTiers
        // (includes/discount_rules.php), gantiin 20 blok if/elseif yang isinya sama persis
        // sebelumnya. Celah antar band (mis. 5001-5999 gram tidak kena band manapun) sengaja
        // dipertahankan sama seperti kondisi if/elseif aslinya.
        if ($apaja == 'ya') {
            foreach ($dropshipFeeTiers as [$beratMin, $beratMax, $biayaTier]) {
                if ($dropship >= $beratMin && $dropship <= $beratMax) {
                    $biayad = $biayaTier;
                    break;
                }
            }
        } elseif ($apaja == 'tidak') {
            $biayad = 0;
        }

        $diskonfree     = $totaldfree*50/100; 
        $totala         = $totala+$totald51-$diskonfree;
        $tbiayad        = number_format($biayad); 
        $ongkir         = $pengiriman['ongkir'];
        $kurir          = $pengiriman['ekspedisi'];
        $diskonramadhan = $pengiriman['diskonramadhan'];
        $idpengiriman   = $pengiriman['idorderp'];
        
        $diskona        = $totala*35/100;
        $diskonb        = $totalb*70/100;
        $diskon5        = $totald5*5/100;
        $diskon10       = $totald10*10/100;
        $diskon15       = $totald15*15/100;
        $diskon17       = $totald17*17/100;
        $diskon20       = $totald20*20/100;
        $diskon25       = $totald25*25/100;

        // var_dump($disc);

        if (isset($disc)) {
            $diskonTambahan = $totala*$disc/100;
        } else {
            $diskonTambahan = 0;
        }
        
        if ($jenis == 'Flash') {
            $diskonFlash    = $totala*20/100;
        } else {
            $diskonFlash    = 0;
        }
        
        $tdTambahan     = number_format($diskonTambahan);
        $tdiskona       = number_format($diskona);
        $flashSale      = number_format($diskonFlash);
        $tdiskonb       = number_format($diskonb);
        $tdiskon5       = number_format($diskon5);
        $tdiskon10      = number_format($diskon10);
        $tdiskon15      = number_format($diskon15);
        $tdiskon17      = number_format($diskon17);
        $tdiskon20      = number_format($diskon20);
        $tdiskon25      = number_format($diskon25);
        $d2574          = 10000*$sum_jumlah;
        $df2574         = number_format($d2574);
        $grandtotal     = ($totala+$totalb+$ongkir+$biayad)-($diskona+$diskonTambahan+$diskonFlash+$diskonb+$diskon5+$diskon10+$diskon15+$diskon17+$diskon20+$diskon25);
        $grandtotal2574 = ($totala+$totalb+$ongkir+$biayad)-($d2574);
        $hargaTotal     = ($totala+$totalb)-($diskona+$diskonTambahan+$diskonFlash+$diskonb+$diskon5+$diskon10+$diskon15+$diskon17+$diskon20+$diskon25);
        $tongkir        = number_format($ongkir);
        $tgrandtotal    = number_format($grandtotal);
        $tgrandtotal2574= number_format($grandtotal2574);
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
                if ($idproducts == 2574) {
                    echo "Diskon : Rp. -$df2574<br>";
                } else {
                    if (empty($totalb)) {
                        echo "Diskon DB 35% : Rp. -$tdiskona<br>";
                    }
                }
                
                if (isset($disc) && $disc > 0) {
                    echo "Diskon Tambahan $disc% : Rp. -$tdTambahan<br>";
                }
                if ($jenis == 'Flash') {
                    echo "Diskon Tambahan 20% : Rp. -$flashSale";
                }
                if($diskonb>0){
                    echo "Diskon Grade B 70% : Rp. -$tdiskonb<br>";
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
                if ($idproducts == 25754 ) {
                    echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal2574</b><br></br></p>";
                } else {
                    echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
                }
                    
            }else{
                $ceksql         = "SELECT 
                                        ordermitra.*, variants.id, variants.idproducts, variants.variant, variants.size, variants.berat,
                                        variants.harga, products.id as product_id, products.idpkategori, products.idkategori, products.namaproduk
                                    FROM ordermitra  
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
                if ($idproducts == 2574) {
                    echo "Diskon : Rp. -$df2574<br>";
                } else {
                    if (empty($totalb)) {
                        echo "Diskon DB 35% : Rp. -$tdiskona<br>";
                    }
                }

                if (isset($disc) && $disc > 0) {
                    echo "Diskon Tambahan $disc% : Rp. -$tdTambahan<br>";
                }
                if ($jenis == 'Flash') {
                    echo "Diskon Tambahan 20% : Rp. -$flashSale";
                }
                if($diskonb > 0){
                    echo "Diskon Grade B 70% : Rp. -$tdiskonb<br>";
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
        } else {
            $ceksql         = "SELECT 
                                    ordermitra.*, variants.id, variants.idproducts, variants.variant, variants.size, variants.berat,
                                    variants.harga, products.id as product_id, products.idpkategori, products.idkategori, products.namaproduk
                                FROM ordermitra 
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
            if ($idproducts == 2574) {
                echo "Diskon : Rp. -$df2574<br>";
            } else {
                if (empty($totalb)) {
                    echo "Diskon DB 35% : Rp. -$tdiskona<br>";
                }
            }

            if (isset($disc) && $disc > 0) {
                echo "Diskon Tambahan $disc% : Rp. -$tdTambahan<br>";
            }
            if ($jenis == 'Flash') {
                echo "Diskon Tambahan 20% : Rp. -$flashSale";
            }
            if($diskonb>0){
                echo "Diskon Grade B 70% : Rp. -$tdiskonb<br>";
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
                    if ($idproducts == 2574) {
                        echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal2574</b><br></br></p>";
                    } else {
                        echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
                    }
                ?>
                <?php
            // }
        }
    ?>
                        <div class="card border-danger mb-3">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">Info Update Rekening Transfer Bank WNJ.ID Pusat Tahun 2025</h5>
                        </div>
                        <div class="card-body text-dark">
                            <h6 class="text-uppercase">Rekening Mandiri Perusahaan</h6>
                            <ul class="mb-3">
                                <li><strong>Nama:</strong> CV KAFAA BILLAHI SYAHIDA</li>
                                <li><strong>No. Rek:</strong> 1300026727597</li>
                            </ul>

                            <h6 class="text-uppercase">Rekening BCA (BARU)</h6>
                            <ul class="mb-3">
                                <li><strong>Nama:</strong> MARIA ULFAH FATHIMAH</li>
                                <li><strong>No. Rek:</strong> 7751616671</li>
                            </ul>

                            <h6 class="text-uppercase">Rekening BRI (BARU)</h6>
                            <ul class="mb-3">
                                <li><strong>Nama:</strong> MARIA ULFAH FATHIMAH</li>
                                <li><strong>No. Rek:</strong> 114101000949560</li>
                            </ul>

                            <h6 class="text-uppercase">Rekening MUAMALAT (BARU)</h6>
                            <ul class="mb-3">
                                <li><strong>Nama:</strong> MARIA ULFAH FATHIMAH</li>
                                <li><strong>No. Rek:</strong> 1100003930</li>
                            </ul>

                            <h6 class="text-uppercase">Rekening BSI (BARU)</h6>
                            <ul class="mb-3">
                                <li><strong>Nama:</strong> MARIA ULFAH FATHIMAH</li>
                                <li><strong>No. Rek:</strong> 7105696706</li>
                            </ul>

                        </div>
                    </div>
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
            if($status['status'] == 'Pending' 
                // && ($kurir == 'Ahsan' or $kurir == 'Gosend' or $kurir == 'Ambil ke Pusat' or $kurir == 'Disatukan' or $kurir == 'idetruck' or $kurir == 'wahana')
            ){
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

    <?php
            } else if($kurir=='Ahsan' 
                        or $kurir=='Gosend' 
                        or $kurir=='Ambil ke Pusat' 
                        or $kurir=='Disatukan' 
                        or $kurir=='idetruck'
                        and ($status['status']=='Proses' or $status['status']=='Selesai')
                    ) {
                echo "<center>Sudah Konfirmasi Pembayaran</center>";
            }
             else{
                echo "<center>Alamat Pengiriman belum di isi <a class='link' href='formpengiriman.php?id=$invoice&berat=$beratcek'>disini.</a></center>";
            }
        }
        else if($status['status']=='Pending'){ 
            $jumlahhari='+1 days'; 
            $dataproduk=$koneksi->query("SELECT * FROM ordermitra  WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 GROUP BY invoice");
            while($tampilkan=$dataproduk->fetch_assoc()){
    ?>
        <center><button type="submit" class="btn btn-sm" name="cari" id="linkmiki<?= $tampilkan['idorder']; ?>">
        <?php
            if ($idproducts == 2574) {
                $total_barang = str_replace(',', '', $tgrandtotal2574);
            } else {
                $total_barang = str_replace(',', '', $tgrandtotal);
            }
            $totalForLink = $total_barang;
        ?>

        <a class='btn btn-info btn-lg' href='formpembayaran2.php?id=<?= $invoice;?>&total=<?= $totalForLink;?>&jenis=<?= $jenis ?>'>Konfirmasi Pembayaran</a>

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