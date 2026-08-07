<?php
    session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesReseller.php';

    $idpoproduk = $_GET['id'];
    $invoice=$_GET['invoice'];
    $idmitrareseller=$_SESSION["idmitrareseller"];
    $query = "SELECT COUNT(*) as jumlah,
                    poproduk.idpoproduk,
                    poproduk.namapo,
                    poproduk.status,
                    poproduk.note,
                    poproduk.pembayaran,
                    pomitra.ket,
                    pomitra.tgl,
                    pomitra.waktu,
                    pomitra.invoice
                FROM poproduk 
                INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk 
                WHERE poproduk.idpoproduk = '$idpoproduk' 
                AND pomitra.idmitrareseller = '$idmitrareseller' 
                AND pomitra.invoice = '$invoice'
                GROUP BY poproduk.idpoproduk,
                    poproduk.namapo,
                    poproduk.status,
                    poproduk.note,
                    poproduk.pembayaran,
                    pomitra.ket,
                    pomitra.tgl,
                    pomitra.waktu,
                    pomitra.invoice";
    $sql = mysqli_query($koneksi, $query);  
    $datapo = mysqli_fetch_array($sql);

    $query2 = "SELECT 
                    pomitra.status,
                    pomitra.invoice
                FROM poproduk 
                inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                WHERE poproduk.idpoproduk='$idpoproduk' AND pomitra.idmitrareseller='$idmitrareseller' AND pomitra.invoice = '$invoice'";
    $sql2 = mysqli_query($koneksi, $query2);  
    $data2 = mysqli_fetch_array($sql2);  

    $note=$datapo['note'];
    $pembayaranpo = $datapo['pembayaran'];
    $invoice = $datapo['invoice'];
    $query_tgl = "SELECT bukapo.idpoproduk, bukapo.tgl_bayar
                    FROM bukapo  
                    WHERE bukapo.idpoproduk='$idpoproduk'";
    $sqlpo_tgl = mysqli_query($koneksi, $query_tgl);  
    $datapo_tgl = mysqli_fetch_array($sqlpo_tgl); 
    $tgl_bayar = $datapo_tgl['tgl_bayar']; 
    $waktu_bayar = '23:59:59';

    $angka1 = 3;
    $angka2 = 3;
    $angka3 = 3;
    $angka5 = 0;
    $angka6 = 0;

    if ($idpoproduk==167 or $idpoproduk==173 or $idpoproduk==176) {
        $query3 = "SELECT SUM(pomitra.jumlah) as jumlahnya
                    FROM pomitra
                    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail 
                    WHERE (podetail.variant LIKE '%Sz M Paket%' or podetail.variant LIKE '%Sz L Paket%') and pomitra.invoice='$invoice'
                    GROUP BY pomitra.invoice";
        $sql3 = mysqli_query($koneksi, $query3);  
        $data3 = mysqli_fetch_array($sql3); 
        $angka1 = $data3['jumlahnya'];
        $query4 = "SELECT SUM(pomitra.jumlah) as jumlahnyaa
                    FROM pomitra
                    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail 
                    WHERE (podetail.variant LIKE '%Sz XL Paket%' or podetail.variant LIKE '%Sz JMB Paket%') and pomitra.invoice='$invoice'
                    GROUP BY pomitra.invoice";
        $sql4 = mysqli_query($koneksi, $query4);  
        $data4 = mysqli_fetch_array($sql4); 
        $angka2 = $data4['jumlahnyaa'];    
    }

    if ($idpoproduk==174) {
        $query7 = "SELECT SUM(pomitra.jumlah) as jumlahnya
                    FROM pomitra
                    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail 
                    WHERE (podetail.variant LIKE '%Paket%') and pomitra.invoice='$invoice'
                    GROUP BY pomitra.invoice";
        $sql7 = mysqli_query($koneksi, $query7);  
        $data7 = mysqli_fetch_array($sql7);
        $angka3 = $data7['jumlahnya'];
        
    }

    $findUser = $koneksi->query("SELECT * FROM mitrareseller WHERE idmitrareseller='$idmitrareseller'");
    $queryUser = $findUser->fetch_assoc(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseller | WNJ.ID</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">

</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar2.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-3">
        <h2 class="text-center mb-4">SALES INVOICE</h2>
        <p class="text-center"><strong><?php echo $data['namapo']; ?></strong></p><br>
        <p class="text-left">Nama Mitra  : <?php echo $queryUser["namaagen"]; ?> </p>
        <p class="text-left">Alamat  : <?php echo $queryUser["alamat"]; ?> </p>
        <p class="text-left">No Invoice  : <?php echo $invoice ; ?> </p>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#agen">Info Invoice</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#reseller">Info Progres</a>
            </li>
        </ul>
        <div class="tab-content mt-5">
            <!-- Tab Content for Agen -->
            <div id="agen" class="container tab-pane active">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <?php if ($idpoproduk <> 186 && $idpoproduk <> 187): ?>
                                    <th>Satuan</th>
                                    <th>Total</th>
                                <?php endif ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                include "koneksi.php";

                                $idmitrareseller = $_SESSION['idmitrareseller'];
                                $idpoproduk = $_GET["id"];
                                $no = 1;
                                $jumlah = 0;
                                $subtotal = 0;

                                $sql = mysqli_query($koneksi, "SELECT podetail.variant,
                                                                        podetail.harga,
                                                                        pomitra.jumlah,
                                                                        pomitra.total,
                                                                        pomitra.idpomitra,
                                                                        poproduk.namapo,
                                                                        poproduk.diskon
                                                                    FROM pomitra 
                                                                    JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                    JOIN poproduk ON poproduk.idpoproduk = pomitra.idpoproduk
                                                                    WHERE pomitra.invoice = '$invoice' 
                                                                    AND pomitra.jumlah > 0");

                                while($data = mysqli_fetch_array($sql)){ ?>
                                <tr>
                                    <td class="align-middle"><?php echo $no++; ?></td>
                                    <td class="align-middle">
                                        <?php echo $data['variant']; ?>
                                        <?php if ($idpoproduk == 186 && $idpoproduk == 186): ?>
                                            <?= $data['custom']; ?>
                                        <?php endif ?>
                                    </td>
                                    <td class="align-middle">
                                        <?php echo $data['jumlah']; ?>
                                    </td>
                                    <?php if ($idpoproduk <> 186 && $idpoproduk <> 187): ?>
                                        <td class="align-middle">Rp. <?php echo number_format($data['harga']); ?></td>
                                        <td class="align-middle">Rp. <?php echo number_format($data['total']); ?></td>
                                    <?php endif ?>
                                    <?php
                                    $angkavoal = $data['jumlah'];
                                    $sum += $data['jumlah'];
                                    $jumlah += $data['jumlah'] * $data['harga'];
                                    $invoice = $data['invoice'];
                                    $persen_tambahan = $data['diskon'];
                                    ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <table style="float: right;width: 100%">
                        <tbody style="float: right;">
                            <?php if ($idpoproduk == 186 || $idpoproduk == 187): ?>
                                <tr>
                                    <th style="padding-bottom: 5%;">Harga Box</th>
                                    <td style="padding-bottom: 5%;">:</td>
                                    <td style="padding-bottom: 5%;">Rp. 110.000</td>
                                </tr>
                                <tr>
                                    <th style="padding-bottom: 5%;">Jumlah Seri</th>
                                    <td style="padding-bottom: 5%;">:</td>
                                    <td style="padding-bottom: 5%;"><?php echo $angkavoal; ?></td>
                                </tr>
                            <?php endif ?>
                                <tr>
                                    <th style="padding-bottom: 5%;">Total Qty</th>
                                    <td style="padding-bottom: 5%;">:</td>
                                    <td style="padding-bottom: 5%;"><?php echo $sum; ?></td>
                                </tr>

                            <?php if ($angka1 % 3 == 0 and $angka2 % 3 == 0 and $angka3 % 3 == 0): ?>
                                <tr>
                                    <th>JUMLAH</th>
                                    <td>:</td>
                                    <td>
                                        <?php 
                                            $sqlharga2 = "SELECT MAX(total) as totalnya, invoice FROM `pomitra` WHERE `idpoproduk`= '$idpoproduk' AND idmitrareseller = '$idmitrareseller' GROUP BY invoice;";
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
                                    if ($idpoproduk=='96') {
                                    $persen=50;
                                    $diskon=50/100*$jumlah;
                                    } else {
                                    $persen=15;
                                    $diskon=$persen/100*$jumlah;
                                    }
                                    $diskon_tambahan = $persen_tambahan/100*$jumlah;
                                    $subtotal=$jumlah-$diskon-$diskon_tambahan;  
                                ?>
                                <tr>
                                    <th>Diskon Reseller <?= $persen; ?>%</th>
                                    <td>:</td>
                                    <td>Rp. <?php echo number_format($diskon); ?></td>
                                </tr>
                                <?php if ($diskon_tambahan>0): ?>
                                <tr>
                                    <th>Diskon Tambahan</th>
                                    <td>:</td>
                                    <td>Rp. <?= number_format($diskon_tambahan); ?></td>
                                </tr>                           
                                <?php endif ?>     
                                <tr>
                                    <th style="padding-bottom: 5%;">Total Bayar</th>
                                    <td style="padding-bottom: 5%;">:</td>
                                    <td style="padding-bottom: 5%;">Rp. <?php echo number_format($subtotal); ?></td>
                                </tr>
                            <?php endif ?>
                            <?php
                                if ($idpoproduk == '220') {
                                    $angkadp = 30;
                                    $dp = $subtotal*$angkadp/100;
                                } 
                                else {
                                    $angkadp = 50;
                                    $dp = $subtotal*$angkadp/100;
                                }
                                $invoice=$data2['invoice'];
                                if ($idpoproduk == '228') {
                                    $namapayemnt='Pembayaran';
                                } else {
                                    $namapayemnt='DP';
                                }
                                $jenispayment='dp';
                                if ($pembayaranpo=='Lunas') {
                                    $dp = $subtotal;
                                    $namapayemnt='Pembayaran';
                                } ?>
                            <?php if ($pembayaranpo<>'Lunas'): ?>
                                <tr>
                                    <th>Jumlah DP PO <?= $angkadp;?>%</th>
                                    <td>:</td>
                                    <td>Rp. <?php echo number_format($dp); ?></td>
                                </tr>
                                <?php
                                    include "koneksi.php";      
                                    $invoice = $data2['invoice']; 
                                    $sqldp = mysqli_query($koneksi, "SELECT popembayaran.invoice, popembayaran.jmlhtransfer,popembayaran.jmlh_lunas
                                                                    FROM `popembayaran` 
                                                                    WHERE popembayaran.invoice ='$invoice' ");
                                    $datadp = mysqli_fetch_array($sqldp);
                                ?>
                                <tr>
                                    <th style="padding-top: 5%;">Status PO</th>
                                    <td style="padding-top: 5%;">:</td>
                                    <td style="padding-top: 5%;">
                                <?php if ($idpoproduk<>'161'): ?>
                                        <?= $data2['status']; ?>
                                <?php else: ?>
                                        Sudah Confirm Payment
                                <?php endif ?>
                                    </td>
                                </tr>
                            <?php if ($datadp['invoice']<>""): ?> 
                                <?php if ($idpoproduk<>'161'): ?>   
                                <tr>
                                    <th>Konfirmasi DP</th>
                                    <td>:</td>
                                    <td>Rp. <?php echo number_format($datadp['jmlhtransfer']); ?></td>
                                </tr>
                                <tr>
                                    <th>Konfirmasi Pelunasan</th>
                                    <td>:</td>
                                    <td>Rp. <?php echo number_format($datadp['jmlh_lunas']); ?></td>
                                </tr>        
                                <tr>
                                    <th>Sisa Tagihan</th>
                                    <td>:</td>
                                    <td>
                                    Rp. 
                                    <?php if ($sisa>0): ?>
                                        +             
                                    <?php endif; ?> 
                                    <?php echo number_format($sisa); ?> 
                                    </td>
                                </tr>
                                <?php endif; ?>  
                            <?php endif; ?>	       
                            <?php endif; ?>    
                        </tbody>
                    </table>
                    <p align="left"><strong>Note: </strong><?php echo $note ?></p>
                    <br>
                    <? if ($idpoproduk == 167 or $idpoproduk == 173 or $idpoproduk == 176): ?>
                        <? if ($angka1 % 3 <> 0): ?>
                            <div class="alert alert-danger" role="alert">
                                Khimar Anas Paket M+L Belum Kelipatan 3, Silahkan Ubah Stok
                            </div>
                        <? endif; ?>
                        <? if ($angka2 % 3 <> 0):?>
                            <div class="alert alert-danger" role="alert">
                                Khimar Anas Paket XL+JMB Belum Kelipatan 3, Silahkan Ubah Stok
                            </div>
                        <? endif; ?>
                        <? if ($angka3 % 3 <> 0): ?>
                            <div class="alert alert-danger" role="alert">
                                Dress Anas Paket Belum Kelipatan 3, Silahkan Ubah Stok
                            </div> 
                        <? endif; ?>
                    <? endif; ?>
                    <? if ($angka1 % 3 == 0 and $angka2 % 3 == 0 and $angka3 % 3 == 0): ?>
                        <? if ($datadp['invoice']=="" and $idpoproduk<>183 and $idpoproduk<>186 and $idpoproduk<>187 and $idpoproduk<>194): ?>
                            <?php if ($datapo['ket']=='Perpanjang' or $tgl_bayar==""): ?>
                                <?php
                                    date_default_timezone_set('Asia/Jakarta');
                                    $jumlahhari = '+1 days';
                                    $tgl1 = $datapo['tgl'];
                                    $tgl2 = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1)));
                                    $tgl_bayar = $tgl2;
                                    $waktu_bayar = $datapo['waktu'];    
                                ?>
                            <? endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Tab Content for Agen END -->
            <!-- Tab Content for Reseller -->
            <?php
                $sqlprogres = mysqli_query($koneksi, "SELECT 
                                                            SUM(surat_jalan_po.progres) as progres,
                                                            SUM(pomitra.jumlah) as jumlah
                                                        FROM surat_jalan_po
                                                        INNER JOIN pomitra on pomitra.idpomitra=surat_jalan_po.idpomitra
                                                        INNER JOIN podetail on podetail.idpodetail=surat_jalan_po.idpodetail
                                                        WHERE pomitra.idmitrareseller='$idmitrareseller' 
                                                        AND pomitra.idpoproduk='$idpoproduk' 
                                                        AND pomitra.jumlah>0 
                                                        AND surat_jalan_po.invoice='$invoice' 
                                                        AND (surat_jalan_po.status='Checker' OR surat_jalan_po.status='Ambil Barang')
                                                        GROUP BY pomitra.idpodetail");
                $dataprogres = mysqli_fetch_array($sqlprogres); // Ambil semua data dari hasil eksekusi $sql                    
            ?>  
            <?php 
                $sqlinvoice = mysqli_query($koneksi, "SELECT SUM(pomitra.jumlah) as jumlah
                                                        FROM pomitra
                                                        WHERE pomitra.idmitrareseller='$idmitrareseller' 
                                                        AND pomitra.idpoproduk='$idpoproduk' 
                                                        AND pomitra.jumlah>0 ");
                $datainvoice = mysqli_fetch_assoc($sqlinvoice);
                $progres = $dataprogres['progres'] / $datainvoice['jumlah'] * 100; 
            ?>    
            <div id="reseller" class="container tab-pane fade">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Qty PO</th>
                                <th>Progres</th>
                                <th>Sisa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                $sum_progres = $sum_jumlah = $sum_sisa = 0;

                                $sql = mysqli_query($koneksi, "SELECT 
                                    podetail.variant,
                                    pomitra.idpomitra,
                                    pomitra.jumlah,
                                    pomitra.invoice,
                                    pomitra.total,
                                    podetail.harga,
                                    pomitra.custom,
                                    SUM(surat_jalan_po.progres) AS progres
                                FROM 
                                    pomitra 
                                INNER JOIN 
                                    podetail ON podetail.idpodetail = pomitra.idpodetail 
                                LEFT JOIN 
                                    surat_jalan_po ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                WHERE 
                                    pomitra.idmitrareseller = '$idmitrareseller'
                                    AND pomitra.idpoproduk = '$idpoproduk'
                                    AND pomitra.invoice = '$invoice'
                                    AND pomitra.jumlah > 0
                                GROUP BY 
                                    podetail.variant,
                                    pomitra.idpomitra,
                                    pomitra.jumlah,
                                    pomitra.invoice,
                                    pomitra.total,
                                    podetail.harga,
                                    pomitra.custom
                                ORDER BY 
                                    podetail.variant ASC");

                                while($data = mysqli_fetch_assoc($sql)) { 
                                    $id = $data['idpomitra'];
                                    $sisa = $data['jumlah'] - $data['progres'];
                            ?>
                                <tr>
                                    <td class="align-middle"><?php echo $no++; ?></td>
                                    <td class="align-middle"><?php echo $data['variant']; ?></td>
                                    <td class="align-middle"><?php echo $data['jumlah']; ?></td>
                                    <td class="align-middle">
                                        <?php echo $data['progres'] ?: 0; ?>
                                    </td>
                                    <td class="align-middle"><?= $sisa; ?></td>
                                    <td class="align-middle">
                                        <?php if ($sisa == 0): ?>
                                            <span class="badge bg-success">Selesai</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Progress</span>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php
                                $sum_progres += $data['progres'];
                                $sum_jumlah += $data['jumlah'];
                                $sum_sisa += $sisa;
                                }
                            ?>
                        </tbody>
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
            <!-- Tab Content for Reseller END -->
        </div>
    </div>
    <?php if ($data2['status'] == "Belum DP"): ?>
        <?php 
            $dataproduk = $koneksi->query("SELECT bukapo.idbpo,
                                            bukapo.jenis_mitra,
                                            bukapo.jenis_po,
                                            bukapo.idpoproduk,
                                            bukapo.tgl,
                                            bukapo.tgl_acc_db,
                                            bukapo.tgl_ubah,
                                            bukapo.tgl_dropship,
                                            bukapo.status,
                                            poproduk.namapo 
                                            FROM bukapo 
                                            INNER JOIN poproduk ON bukapo.idpoproduk = poproduk.idpoproduk 
                                            WHERE poproduk.idpoproduk='$idpoproduk' 
                                            AND bukapo.status = 'PUBLISH' 
                                            AND (bukapo.jenis_mitra = 'Semua Mitra' OR bukapo.jenis_mitra = 'Distributor')"
                                        );
            while ($tampilkan = $dataproduk->fetch_assoc()) {
        ?>
            <div class="d-flex justify-content-center">
                <?php if ($tampilkan['jenis_po'] == "PO dengan Stok"): ?>
                    <button type="submit" class="btn btn-success btn-sm text-center" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">
                        <a style="color:white" href="ubahpostok.php?id=<?= $idpoproduk; ?>&invoice=<?= $invoice; ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>
                    </button>
                    <p id="demomiki<? // = $tampilkan['idbpo']; ?>"></p>
                <?php elseif ($tampilkan['jenis_po'] == "PO Mandiri"): ?>
                    <button type="submit" class="btn btn-success btn-sm text-center" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">
                        <a style="color:white" href="ubahpo_mandiri?id=<?= $idpoproduk; ?>&invoice=<?= $invoice; ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>
                    </button>
                <?php endif ?>
            </div>
            <div class="d-flex justify-content-center">
                <?php if ($tampilkan['jenis_po'] == "PO tanpa Stok" || $tampilkan['idpoproduk'] == '292'): ?>
                    <button type="submit" class="btn btn-success btn-sm text-center" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">
                        <a style="color:white" href="ubahpo.php?id=<?= $idpoproduk; ?>&invoice=<?= $invoice; ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>
                    </button>
                    <?php if ($idpoproduk == 257) : ?>
                    <?php else : ?>
                        <p id="demomiki<?= $tampilkan['idbpo']; ?>"></p>
                    <?php endif; ?>
                <?php endif ?> 
            </div>
            <div class="d-flex justify-content-center">
                <?php if ($tampilkan['jenis_po'] == "PO Custom Tab"): ?>
                    <div name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">	
                        <button type="submit" class="btn btn-success btn-sm">
                            <a href="" data-toggle="modal" data-target="#modalView" style="color:white">Ubah Variant</a> 
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <a style="color:white" href="formpo_tabtambah?id=<?php echo $tampilkan['idpoproduk']; ?>&invoice=<?= $invoice; ?>">Tambah Variant</a>
                        </button>
                        <p id="demomiki<?= $tampilkan['idbpo']; ?>"></p>    
                    </div>     
                <?php endif ?>  
            </div>
            <div class="d-flex justify-content-center">
                <?php if ($tampilkan['jenis_po'] == "PO Custom Tab Stok"): ?>
                    <div name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">
                        <button type="submit" class="btn btn-success btn-sm">
                            <a style="color:white" href="ubahpostok.php?id=<?= $idpoproduk; ?>&invoice=<?= $invoice ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <a style="color:white" href="formpo_tabtambahstok?id=<?php echo $tampilkan['idpoproduk']; ?>&invoice=<?= $invoice; ?>">Tambah Variant</a>
                        </button>
                        <p id="demomiki<?= $tampilkan['idbpo']; ?>"></p>    
                    </div>          
                <?php endif ?>
            </div>
            <div class="d-flex justify-content-center">
                <?php if ($tampilkan['jenis_po'] == "PO Custom Tab Stok Max"): ?>
                    <button type="submit" class="btn btn-success btn-sm" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">
                        <a style="color:white" href="ubahpo_tabmax.php?id=<?= $idpoproduk; ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>
                    </button>
                    <p id="demomiki<?= $tampilkan['idbpo']; ?>"></p>
                <?php endif ?>
            </div>
            <br>
            <script>
                // Mengatur waktu akhir perhitungan mundur
                var countDownDatemiki<?= $tampilkan['idbpo']; ?> = new Date("<?php echo $tampilkan['tgl_ubah']; ?> 23:59:00").getTime();

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
                    document.getElementById("demomiki<?= $tampilkan['idbpo']; ?>").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

                    // Jika hitungan mundur selesai, tulis beberapa teks 
                    if (distance < 0) {
                        clearInterval(x);
                        document.getElementById("demomiki<?= $tampilkan['idbpo']; ?>").innerHTML = " ";
                        var x = document.getElementById("linkmiki<?= $tampilkan['idbpo']; ?>");
                        x.style.display = "none";
                    }
                }, 1000);
            </script>
        <?php } ?> 
        <?php endif ?>
        <?php if ($data2['status'] != "Belum DP"): ?>
            <div class="d-flex justify-content-center">
                <a href="excel_po?invoice=<?= $invoice; ?>" class="btn btn-success btn-sm">Excel Invoice</a>
            </div>
        <?php endif ?>
    <div class="modal fade" id="modalView" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="labelModalKu">Ubah Qty Variant</h4>
                <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Tutup</span>
                </button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                <table>
                    <tr>
                    <th>Variant</th>
                    <th>Qty</th>
                    </tr>
                    <?php
                    $sql_qty = mysqli_query($koneksi, "SELECT podetail.variant,
                                            podetail.harga,
                                            pomitra.jumlah,
                                            pomitra.idpomitra
                                        FROM pomitra 
                                        JOIN podetail on podetail.idpodetail=pomitra.idpodetail 
                                        WHERE pomitra.invoice='$invoice' 
                                        AND pomitra.jumlah>0
                                        ORDER BY podetail.idpodetail
                                        ");
                    while($data_qty = mysqli_fetch_array($sql_qty)){ 
                    ?>
                    <tr>
                    <td><label><?= $data_qty['variant'] ?></label></td>
                    <td>
                        <div class="form-group">
                        <input type="hidden" class="form-control" name="idpomitra[]" value="<?= $data_qty['idpomitra'] ?>">
                        <input type="number" class="form-control" name="jumlah[]" value="<?= $data_qty['jumlah'] ?>" style="width: 100px" placeholder="Qty">
                        </div>
                    </td>
                    </tr>
                    <?php } ?>
                </table>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="simpan_qty">&plus; Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">&times; Close</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
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
            echo "<script>location='dummy?id=$idpoproduk&invoice=$invoice';</script>";
            } else {
            echo "<script>alert('Data gagal disimpan ');</script>";
            echo "<script>location='dummy?id=$idpoproduk&invoice=$invoice';</script>";
            }
        }
    ?>     
    <!-- PHP END -->

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>