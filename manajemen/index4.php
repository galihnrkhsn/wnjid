    <?php 
    session_start();
    include 'koneksi.php'; 
    // if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
    //     echo "
    //         <script>alert('Anda harus login terlebih dahulu!');</script>
    //         <script>location='login-multi.php';</script>
    //     ";
    //     header("Location: login-multi.php");
    //     exit();
    // }
    $id             = $_SESSION['user_id'];
    $role           = $_SESSION['user_level'];

    $ambilp         = $koneksi->query("SELECT sum(kredit) - sum(debit) as sisa FROM rekeningkoran WHERE tipe='P'"); 
    $datap          = $ambilp->fetch_assoc();

    $ambilaf        = $koneksi->query("SELECT sum(kredit) - sum(debit) as sisa FROM rekeningkoran WHERE tipe='AF'"); 
    $dataaf         = $ambilaf->fetch_assoc();

    $ambilpm        = $koneksi->query("SELECT sum(kredit) - sum(debit) as sisa FROM rekeningkoran WHERE tipe='PM'"); 
    $datapm         = $ambilpm->fetch_assoc();

    $ambilm         = $koneksi->query("SELECT sum(kredit) - sum(debit) as sisa FROM rekeningkoran WHERE tipe='M'"); 
    $datam          = $ambilm->fetch_assoc();

    date_default_timezone_set('Asia/Jakarta');
    $dateNow        = date("Y-m-d");
    $dayKemarin     = date( 'Y-m-d', strtotime( $dateNow . ' -1 day' ) );
    $dayLusa        = date( 'Y-m-d', strtotime( $dateNow . ' -2 day' ) );

    $getCash        = $koneksi->query("SELECT (bca+bni+bri+bsi+mandiri+muamalat) as total, waktu FROM rekeningbank ORDER BY id DESC LIMIT 1");
    $tampil_cash    = $getCash->fetch_assoc();

    $getAset        = $koneksi->query("SELECT sum(nilai) as total FROM aset");
    $tampil_aset    = $getAset->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Management | WNJ.ID</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <div class="col-xl-8 col-lg-7">
            <?php if ($role == "Owner") : ?>
            <!-- OWNER -->
                <!-- KAS KECIL -->
                    <div class="card shadow mb-4">
                        <div class="card border-left-success shadow h-100">
                            <!-- Card Header - Accordion -->
                            <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample" style="margin-top: -2%;">
                                <h5 class="m-0 font-weight-bold text-dark">Kas Kecil</h5>
                            </a>
                            <!-- Card Content - Collapse -->
                            <div class="collapse" id="collapseCardExample">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        <a href="finance.php?tipe=P">Kas Produksi</a>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            Rp <?= number_format($datap['sisa']); ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        <a href="finance.php?tipe=M">Kas Manajemen</a>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            Rp <?= number_format($datam['sisa']); ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        <a href="finance.php?tipe=AF">Kas Admin</a>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            Rp <?= number_format($dataaf['sisa']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- PEMASUKAN HARIAN -->
                    <div class="card shadow mb-4">
                        <div class="card border-left-success shadow h-100">
                            <!-- Card Header - Accordion -->
                            <a href="#collapseCardPemasukan" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardPemasukan" style="margin-top: -2%;">
                                <h5 class="m-0 font-weight-bold text-dark">Pemasukan</h5>
                            </a>
                            <!-- Card Content - Collapse -->
                            <div class="collapse" id="collapseCardPemasukan">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        <a href="pemasukan.php">Pemasukan</a>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            <?php
                                                $getHarian  = $koneksi->query("SELECT SUM(jumlah) AS total_jumlah 
                                                                                FROM pemasukan_record 
                                                                                WHERE deleted_at IS NULL
                                                                                GROUP BY tanggal");
                                                while($dataHarian = $getHarian->fetch_assoc()) {
                                                    var_dump($dataHarian);
                                                }
                                            ?>
                                            Rp. 0
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END PEMASUKAN HARIAN -->
                <!-- PENJUALAN WNJ -->
                    <div class="card shadow mb-4">
                        <div class="card border-left-primary shadow h-100" >
                            <!-- Card Header - Accordion -->
                            <a href="#collapsePenjualan" class="d-block card-header py-3" data-toggle="collapse"role="button" aria-expanded="true" aria-controls="collapsePenjualan" style="margin-top: -2%;">
                                <h5 class="m-0 font-weight-bold text-dark">Penjualan WNJ</h5>
                            </a>
                            <!-- Card Content - Collapse -->
                            <div class="collapse" id="collapsePenjualan">
                                <div class="card-body">
                                    <?php 
                                        // $getHarian = $koneksi->query("SELECT 
                                        //                                     surat_jalan.invoice,
                                        //                                     SUM(surat_jalan.progres) as jumlah,
                                        //                                     MAX(surat_jalan.progres) as progres, -- Atau fungsi agregat lain yang sesuai
                                        //                                     surat_jalan.status,
                                        //                                     surat_jalan.waktu,
                                        //                                     surat_jalan.id_sj,
                                        //                                     surat_jalan.no_sj
                                        //                                 FROM 
                                        //                                     surat_jalan
                                        //                                 WHERE 
                                        //                                     surat_jalan.status <> 'Ambil Barang' 
                                        //                                     AND SUBSTRING(surat_jalan.waktu, 1, 10) LIKE '%$dateNow%'
                                        //                                 GROUP BY 
                                        //                                     surat_jalan.invoice, surat_jalan.status, surat_jalan.waktu, surat_jalan.id_sj, surat_jalan.no_sj
                                        //                                 ORDER BY 
                                        //                                     surat_jalan.waktu DESC, surat_jalan.invoice;
                                        //                             ");
                                        // while($queryHarian = $getHarian->fetch_assoc()){
                                        //     $no_sj = $queryHarian['no_sj'];
                                        //     $totala = 0;
                                        //     $getHarga = $koneksi->query("SELECT produk.harga, surat_jalan.progres 
                                        //                 FROM surat_jalan 
                                        //                 INNER JOIN produk
                                        //                 ON produk.idproduk=surat_jalan.idproduk 
                                        //                 WHERE surat_jalan.no_sj='$no_sj'");
                                        //     while ($queryHarga = $getHarga->fetch_assoc()) {
                                        //         $subtotal = $queryHarga['harga'] * $queryHarga['progres'];
                                        //         $totala = $totala + $subtotal;
                                        //     };

                                        //     $diskon = 35/100*$totala;
                                        //     $totalnya = $totala - $diskon;
                                        //     $totalsemua += $totalnya;
                                        // }

                                        // $getPoHarian = $koneksi->query("SELECT 
                                        //                                     surat_jalan_po.invoice,
                                        //                                     MAX(surat_jalan_po.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                     surat_jalan_po.status,
                                        //                                     surat_jalan_po.waktu,
                                        //                                     SUM(surat_jalan_po.progres) as jumlah,
                                        //                                     surat_jalan_po.id_sj,
                                        //                                     surat_jalan_po.no_sj,
                                        //                                     pomitra.idpoproduk
                                        //                                 FROM 
                                        //                                     surat_jalan_po
                                        //                                 JOIN 
                                        //                                     pomitra 
                                        //                                 ON 
                                        //                                     pomitra.idpomitra = surat_jalan_po.idpomitra
                                        //                                 WHERE 
                                        //                                     surat_jalan_po.status <> 'Ambil Barang' 
                                        //                                     AND SUBSTRING(surat_jalan_po.waktu, 1, 10) LIKE '$dateNow'
                                        //                                 GROUP BY 
                                        //                                     surat_jalan_po.invoice,
                                        //                                     surat_jalan_po.status,
                                        //                                     surat_jalan_po.waktu,
                                        //                                     surat_jalan_po.id_sj,
                                        //                                     surat_jalan_po.no_sj,
                                        //                                     pomitra.idpoproduk
                                        //                                 ORDER BY 
                                        //                                     surat_jalan_po.waktu DESC, surat_jalan_po.invoice");
                                        // while($queryPoHarian = $getPoHarian->fetch_assoc()) {
                                        //     $no_sj_po = $queryPoHarian['no_sj']; 
                                        //     $totala_po = 0;
                                        //     $getPo = $koneksi->query("SELECT podetail.harga, surat_jalan_po.progres 
                                        //         FROM surat_jalan_po 
                                        //         inner join podetail on podetail.idpodetail=surat_jalan_po.idpodetail
                                        //         WHERE surat_jalan_po.no_sj='$no_sj_po'");
                                        //     while ($queryHargaPo = $getPo->fetch_assoc()) {
                                        //         $subtotalPo = $queryHargaPo['harga'] * $queryHargaPo['progres'];
                                        //         $totala_po = $totala_po + $subtotalPo;
                                        //     }

                                        //     $diskon_po = 35/100*$totala_po;
                                        //     $totalpo_nya = $totala_po - $diskon_po;
                                        //     $totalpo_semua += $totalpo_nya;
                                        // }

                                        // $getharianManual=$koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'INV' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) 
                                        //                                         LIKE '%$dateNow%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice, 
                                        //                                         surat_jalan_manual.status, 
                                        //                                         surat_jalan_manual.waktu, 
                                        //                                         surat_jalan_manual.id_sj, 
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                     ");
                                        // while($queryHarianManual=$getharianManual->fetch_assoc()) {
                                        //     $no_sj_manual = $queryHarianManual['no_sj']; 
                                        //     $totala_manual = 0;
                                        //     $getManual = $koneksi->query("SELECT produk.harga, surat_jalan_manual.progres 
                                        //                     FROM surat_jalan_manual 
                                        //                     INNER JOIN produk ON produk.idproduk=surat_jalan_manual.idproduk 
                                        //                     WHERE surat_jalan_manual.no_sj='$no_sj_manual'");
                                        //     while ($ga_manual = $getManual->fetch_assoc()){
                                        //         $subtotal_manual = $ga_manual['harga'] * $ga_manual['progres'];
                                        //         $totala_manual = $totala_manual + $subtotal_manual;
                                        //     }

                                        //     $diskon_manual = 35/100*$totala_manual;
                                        //     $totalnya_manual = $totala_manual - $diskon_manual;    
                                        //     $totalsemua_manual += $totalnya_manual;
                                        // }    

                                        // $getManualPo=$koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'IPO' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) LIKE '%$dataNow%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                 ");
                                        // while($queryManualPo=$getManualPo->fetch_assoc()) {
                                        //     $no_sj_manual_po = $queryManualPo['no_sj']; 
                                        //     $totala_manual_po = 0;

                                        //     $getManual_po = $koneksi->query("SELECT podetail.harga, surat_jalan_manual.progres 
                                        //                         FROM surat_jalan_manual 
                                        //                         inner join podetail on podetail.idpodetail=surat_jalan_manual.idproduk 
                                        //                         WHERE surat_jalan_manual.no_sj='$no_sj_manual_po'");

                                        //     while ($ga_manual_po = $getManual_po->fetch_assoc()){
                                        //         $subtotal_manual_po = $ga_manual_po['harga'] * $ga_manual_po['progres'];
                                        //         $totala_manual_po = $totala_manual_po + $subtotal_manual_po;
                                        //     }

                                        //     $diskon_manual_po = 35/100*$totala_manual_po;
                                        //     $totalnya_manual_po = $totala_manual_po - $diskon_manual_po;
                                        //     $totalsemua_manual_po += $totalnya_manual_po;
                                        // } 

                                        // $data_harian_1 = $koneksi->query("SELECT 
                                        //                                     surat_jalan.invoice,
                                        //                                     SUM(surat_jalan.progres) as jumlah,
                                        //                                     MAX(surat_jalan.progres) as progres, -- Atau fungsi agregat lain yang sesuai
                                        //                                     surat_jalan.status,
                                        //                                     surat_jalan.waktu,
                                        //                                     surat_jalan.id_sj,
                                        //                                     surat_jalan.no_sj
                                        //                                 FROM 
                                        //                                     surat_jalan
                                        //                                 WHERE 
                                        //                                     surat_jalan.status <> 'Ambil Barang' 
                                        //                                     AND SUBSTRING(surat_jalan.waktu, 1, 10) LIKE '%$dayKemarin%'
                                        //                                 GROUP BY 
                                        //                                     surat_jalan.invoice, surat_jalan.status, surat_jalan.waktu, surat_jalan.id_sj, surat_jalan.no_sj
                                        //                                 ORDER BY 
                                        //                                     surat_jalan.waktu DESC, surat_jalan.invoice;
                                        //                                 ");
                                        // while($tampilkan_harian_1=$data_harian_1->fetch_assoc()) {
                                        //     $no_sj_1 = $tampilkan_harian_1['no_sj'];
                                        //     $totala_1 = 0;
                                        //     $sql_1 = $koneksi->query("SELECT produk.harga, surat_jalan.progres
                                        //                                 FROM surat_jalan
                                        //                                 inner join produk on produk.idproduk=surat_jalan.idproduk 
                                        //                                 WHERE surat_jalan.no_sj='$no_sj_1' ");
                                        //     while ($ga_1 = $sql_1->fetch_assoc()){
                                        //         $subtotal_1 = $ga_1['harga'] * $ga_1['progres'];
                                        //         $totala_1 = $totala_1 + $subtotal_1;
                                        //     }
                                        //     $diskon_1 = 35/100*$totala_1;
                                        //     $totalnya_1 = $totala_1 - $diskon_1;
                                        //     $totalsemua_1 += $totalnya_1;
                                        // }

                                        // $datapo_harian_1=$koneksi->query("SELECT 
                                        //                                         surat_jalan_po.invoice,
                                        //                                         MAX(surat_jalan_po.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                         surat_jalan_po.status,
                                        //                                         surat_jalan_po.waktu,
                                        //                                         SUM(surat_jalan_po.progres) as jumlah,
                                        //                                         surat_jalan_po.id_sj,
                                        //                                         surat_jalan_po.no_sj,
                                        //                                         pomitra.idpoproduk
                                        //                                     FROM 
                                        //                                         surat_jalan_po
                                        //                                     JOIN 
                                        //                                         pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                        //                                     WHERE 
                                        //                                         surat_jalan_po.status <> 'Ambil Barang' 
                                        //                                         AND SUBSTRING(surat_jalan_po.waktu, 1, 10) LIKE '%$dayKemarin%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_po.invoice,
                                        //                                         surat_jalan_po.status,
                                        //                                         surat_jalan_po.waktu,
                                        //                                         surat_jalan_po.id_sj,
                                        //                                         surat_jalan_po.no_sj,
                                        //                                         pomitra.idpoproduk
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_po.waktu DESC, 
                                        //                                         surat_jalan_po.invoice;
                                        //                                 ");
                                        // while($tampilkanpo_harian_1=$datapo_harian_1->fetch_assoc()) {
                                        //     $no_sj_1_po = $tampilkanpo_harian_1['no_sj']; 
                                        //     $totala_1_po = 0;
                                        //     $query_1_po = $koneksi->query("SELECT podetail.harga, surat_jalan_po.progres
                                        //                     FROM surat_jalan_po
                                        //                     INNER JOIN podetail 
                                        //                     ON podetail.idpodetail=surat_jalan_po.idpodetail 
                                        //                     WHERE surat_jalan_po.no_sj='$no_sj_1_po'
                                        //                 ");
                                        //     while ($ga_1_po = $query_1_po->fetch_assoc()) {
                                        //         $subtotal_1_po = $ga_1_po['harga'] * $ga_1_po['progres'];
                                        //         $totala_1_po = $totala_1_po + $subtotal_1_po;
                                        //     }

                                        //     $diskon_1_po = 35/100*$totala_1_po;
                                        //     $totalpo_nya_1 = $totala_1_po - $diskon_1_po;
                                        //     $totalpo_semua_1 += $totalpo_nya_1;
                                        // }

                                        // $data_harian_manual_1 = $koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'INV' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) 
                                        //                                         LIKE '%$dayKemarin%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice, 
                                        //                                         surat_jalan_manual.status, 
                                        //                                         surat_jalan_manual.waktu, 
                                        //                                         surat_jalan_manual.id_sj, 
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                         ");
                                        // while($tampilkan_harian_manual_1=$data_harian_manual_1->fetch_assoc()){
                                        //     $no_sj_manual_1 = $tampilkan_harian_manual_1['no_sj'];
                                        //     $totala_manual_1 = 0;
                                        //     $sql_manual_1 = $koneksi->query("SELECT produk.harga, surat_jalan_manual.progres 
                                        //                         FROM surat_jalan_manual 
                                        //                         INNER JOIN produk 
                                        //                         ON produk.idproduk=surat_jalan_manual.idproduk 
                                        //                         WHERE surat_jalan_manual.no_sj='$no_sj_manual_1'
                                        //                     ");
                                        //     while ($ga_manual_1 = $sql_manual_1->fetch_assoc()){
                                        //         $subtotal_manual_1 = $ga_manual_1['harga'] * $ga_manual_1['progres'];
                                        //         $totala_manual_1 = $totala_manual_1 + $subtotal_manual_1;
                                        //     }

                                        //     $diskon_manual_1 = 35/100*$totala_manual_1;
                                        //     $totalnya_manual_1 = $totala_manual_1 - $diskon_manual_1;
                                        //     $totalsemua_manual_1 += $totalnya_manual_1;
                                        // }

                                        // $data_harian_manual_po1 = $koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'IPO' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) LIKE '%$dayKemarin%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                         ");
                                        // while($tampilkan_harian_manual_po1=$data_harian_manual_po1->fetch_assoc()){
                                        //     $no_sj_manual_po1 = $tampilkan_harian_manual_po1['no_sj']; 
                                        //     $totala_manual_po1 = 0;
                                        //     $sql_manual_po1 = $koneksi->query("SELECT podetail.harga, surat_jalan_manual.progres 
                                        //                         FROM surat_jalan_manual 
                                        //                         INNER JOIN podetail
                                        //                         ON podetail.idpodetail=surat_jalan_manual.idproduk 
                                        //                         WHERE surat_jalan_manual.no_sj='$no_sj_manual_po1'
                                        //                     ");
                                        //     while ($ga_manual_po1 = $sql_manual_po1->fetch_assoc()){
                                        //         $subtotal_manual_po1 = $ga_manual_po1['harga'] * $ga_manual_po1['progres'];
                                        //         $totala_manual_po1 = $totala_manual_po1 + $subtotal_manual_po1;
                                        //     }
                                        //     $diskon_manual_po1 = 35/100*$totala_manual_po1;
                                        //     $totalnya_manual_po1 = $totala_manual_po1 - $diskon_manual_po1;    
                                        //     $totalsemua_manual_po1 += $totalnya_manual_po1;   
                                        // } 

                                        // $data_harian_2 = $koneksi->query("SELECT 
                                        //                                     surat_jalan.invoice,
                                        //                                     SUM(surat_jalan.progres) as jumlah,
                                        //                                     MAX(surat_jalan.progres) as progres, -- Atau fungsi agregat lain yang sesuai
                                        //                                     surat_jalan.status,
                                        //                                     surat_jalan.waktu,
                                        //                                     surat_jalan.id_sj,
                                        //                                     surat_jalan.no_sj
                                        //                                 FROM 
                                        //                                     surat_jalan
                                        //                                 WHERE 
                                        //                                     surat_jalan.status <> 'Ambil Barang' 
                                        //                                     AND SUBSTRING(surat_jalan.waktu, 1, 10) LIKE '%$dayLusa%'
                                        //                                 GROUP BY 
                                        //                                     surat_jalan.invoice, surat_jalan.status, surat_jalan.waktu, surat_jalan.id_sj, surat_jalan.no_sj
                                        //                                 ORDER BY 
                                        //                                     surat_jalan.waktu DESC, surat_jalan.invoice;
                                        //                                 ");
                                        // while($tampilkan_harian_2=$data_harian_2->fetch_assoc()) {
                                        //     $no_sj_2 = $tampilkan_harian_2['no_sj']; 
                                        //     $totala_2 = 0;
                                        //     $sql_2 = $koneksi->query("SELECT produk.harga, surat_jalan.progres 
                                        //                 FROM surat_jalan 
                                        //                 inner join produk on produk.idproduk=surat_jalan.idproduk 
                                        //                 WHERE surat_jalan.no_sj='$no_sj_2'
                                        //             ");
                                        //     while ($ga_2 = $sql_2->fetch_assoc()){
                                        //         $subtotal_2 = $ga_2['harga'] * $ga_2['progres'];
                                        //         $totala_2 = $totala_2 + $subtotal_2;
                                        //     }
                                        //     $diskon_2 = 35/100*$totala_2;
                                        //     $totalnya_2 = $totala_2 - $diskon_2;
                                        //     $totalsemua_2 += $totalnya_2;
                                        // }

                                        // $datapo_harian_2=$koneksi->query("SELECT 
                                        //                                         surat_jalan_po.invoice,
                                        //                                         MAX(surat_jalan_po.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                         surat_jalan_po.status,
                                        //                                         surat_jalan_po.waktu,
                                        //                                         SUM(surat_jalan_po.progres) as jumlah,
                                        //                                         surat_jalan_po.id_sj,
                                        //                                         surat_jalan_po.no_sj,
                                        //                                         pomitra.idpoproduk
                                        //                                     FROM 
                                        //                                         surat_jalan_po
                                        //                                     JOIN 
                                        //                                         pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                        //                                     WHERE 
                                        //                                         surat_jalan_po.status <> 'Ambil Barang' 
                                        //                                         AND SUBSTRING(surat_jalan_po.waktu, 1, 10) LIKE '%$dayLusa%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_po.invoice,
                                        //                                         surat_jalan_po.status,
                                        //                                         surat_jalan_po.waktu,
                                        //                                         surat_jalan_po.id_sj,
                                        //                                         surat_jalan_po.no_sj,
                                        //                                         pomitra.idpoproduk
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_po.waktu DESC, 
                                        //                                         surat_jalan_po.invoice;
                                        //                                 ");
                                        // while($tampilkanpo_harian_2=$datapo_harian_2->fetch_assoc()){                                  
                                        //     $no_sj_2_po = $tampilkanpo_harian_2['no_sj']; 
                                        //     $totala_2_po = 0;
                                        //     $sql_2_po = $koneksi->query("SELECT podetail.harga, surat_jalan_po.progres 
                                        //                     FROM surat_jalan_po
                                        //                     INNER JOIN podetail 
                                        //                     ON podetail.idpodetail=surat_jalan_po.idpodetail 
                                        //                     WHERE surat_jalan_po.no_sj='$no_sj_2_po'
                                        //                 ");
                                        //     while ($ga_2_po = $sql_2_po->fetch_assoc()){
                                        //     $subtotal_2_po = $ga_2_po['harga'] * $ga_2_po['progres'];
                                        //     $totala_2_po = $totala_2_po + $subtotal_2_po;

                                        //     }
                                        //     $diskon_2_po = 35/100*$totala_2_po;
                                        //     $totalpo_nya_2 = $totala_2_po - $diskon_2_po;    

                                        //     $totalpo_semua_2 += $totalpo_nya_2;             
                                        // }

                                        // $data_harian_manual_2 = $koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'INV' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) 
                                        //                                         LIKE '%$dayLusa%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice, 
                                        //                                         surat_jalan_manual.status, 
                                        //                                         surat_jalan_manual.waktu, 
                                        //                                         surat_jalan_manual.id_sj, 
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                     ");
                                        // while($tampilkan_harian_manual_2=$data_harian_manual_2->fetch_assoc()) {
                                        //     $no_sj_manual_2 = $tampilkan_harian_manual_2['no_sj'];
                                        //     $totala_manual_2 = 0;
                                        //     $sql_manual_2 = $koneksi->query("SELECT produk.harga, surat_jalan_manual.progres 
                                        //                         FROM surat_jalan_manual 
                                        //                         INNER JOIN produk
                                        //                         ON produk.idproduk = surat_jalan_manual.idproduk 
                                        //                         WHERE surat_jalan_manual.no_sj='$no_sj_manual_2'");
                                        //     while ($ga_manual_2 = $sql_manual_2->fetch_assoc()){
                                        //         $subtotal_manual_2 = $ga_manual_2['harga'] * $ga_manual_2['progres'];
                                        //         $totala_manual_2 = $totala_manual_2 + $subtotal_manual_2;
                                        //     }
                                        //     $diskon_manual_2 = 35/100*$totala_manual_2;
                                        //     $totalnya_manual_2 = $totala_manual_2 - $diskon_manual_2;    
                                        //     $totalsemua_manual_2 += $totalnya_manual_2;   
                                        // }

                                        // $data_harian_manual_po2 = $koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'IPO' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) LIKE '%$dayLusa%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                         ");
                                        // while($tampilkan_harian_manual_po2=$data_harian_manual_po2->fetch_assoc()) {
                                        //     $no_sj_manual_po2 = $tampilkan_harian_manual_po2['no_sj'];
                                        //     $totala_manual_po2 = 0;
                                        //     $sql_manual_po2 = $koneksi->query("SELECT podetail.harga, surat_jalan_manual.progres
                                        //                         FROM surat_jalan_manual
                                        //                         INNER JOIN podetail
                                        //                         ON podetail.idpodetail=surat_jalan_manual.idproduk
                                        //                         WHERE surat_jalan_manual.no_sj='$no_sj_manual_po2'");
                                        //     while ($ga_manual_po2 = $sql_manual_po2->fetch_assoc()){
                                        //         $subtotal_manual_po2 = $ga_manual_po2['harga'] * $ga_manual_po2['progres'];
                                        //         $totala_manual_po2 = $totala_manual_po2 + $subtotal_manual_po2;
                                        //     }
                                        //     $diskon_manual_po2 = 35/100*$totala_manual_po2;
                                        //     $totalnya_manual_po2 = $totala_manual_po2 - $diskon_manual_po2;
                                        //     $totalsemua_manual_po2 += $totalnya_manual_po2;
                                        // }
                                    ?>
                                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true" data-interval="false">
                                        <div class="carousel-indicators">
                                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active btn btn-ligth" aria-current="true" aria-label="Slide 1"></button>
                                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" class="btn btn-ligth" aria-label="Slide 2"></button>
                                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" class="btn btn-ligth" aria-label="Slide 3"></button>
                                        </div>

                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="img/bg-white.png" class="d-block w-100" style="width:100%;height: 100px">
                                                <div class="centered">
                                                    <strong>
                                                        Penjualan Hari ini (<?= $dateNow; ?>)<br>
                                                        Rp <?= number_format($total_harian = $totalsemua + $totalpo_semua + $totalsemua_manual + $totalsemua_manual_po); ?>
                                                    </strong>
                                                </div>       
                                                    
                                            </div>

                                            <div class="carousel-item">
                                                <img src="img/bg-white.png" class="d-block w-100" style="width:100%;height: 100px">
                                                <div class="centered">
                                                    <strong>
                                                        Penjualan Kemarin (<?= $dayKemarin; ?>)<br>
                                                        Rp <?= number_format($total_harian_1 = $totalsemua_1 + $totalpo_semua_1 + $totalsemua_manual_1 + $totalsemua_manual_po1); ?>
                                                    </strong>
                                                </div> 
                                            </div>
                                            
                                            <div class="carousel-item">
                                                <img src="img/bg-white.png" class="d-block w-100" style="width:100%;height: 100px">
                                                <div class="centered">
                                                    <strong>
                                                        Penjualan Kemarin Lusa (<?= $dayLusa; ?>)<br>
                                                        Rp <?= number_format($total_harian_2 = $totalsemua_2 + $totalpo_semua_2 + $totalsemua_manual_2 + $totalsemua_manual_po2); ?>
                                                    </strong>
                                                </div> 
                                            </div>
                                        </div>

                                        <button class="carousel-control-prev btn btn-ligth" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fas fa-arrow-left" style="color: black"></i></span>
                                        </button>
                                        <button class="carousel-control-next btn btn-ligth" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"><i class="fas fa-arrow-right" style="color: black"></i></span>
                                        </button>
                                    </div>
                                    <hr>
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1 aText">
                                        <a href="readystok2.php" class="d-block">Ready Stok</a>
                                        <label>Rp <?= number_format($totalsemua); ?></label>
                                    </div>
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1 aText">
                                        <a href="readystok2.php" class="d-block">Ready Stok Manual</a>
                                        <label>Rp <?= number_format($totalsemua_manual); ?></label>
                                    </div>
                                    <hr>
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1 aText">
                                        <a href="preorder.php" class="d-block">Pre Order</a>
                                        <label>Rp <?= number_format($totalpo_semua); ?></label>
                                    </div>
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1 aText">
                                        <a href="preorder.php" class="d-block">Pre Order Manual</a>
                                        <label>Rp <?= number_format($totalsemua_manual_po); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
                <!-- END PENJUALAN WNJ -->
                <!-- ASET -->
                    <?php
                        $ambil_stock = $koneksi->query("SELECT stock, harga from produk
                                                        WHERE stock > 0 
                                                        AND harga > 0
                                                        AND idkategori > 0
                                                        AND status = 0 
                                                        ORDER BY idproduk DESC");
                        while($tampil_stock = $ambil_stock->fetch_assoc()) {
                            $hpp            = $tampil_stock['harga']/2;
                            $total          = $tampil_stock['stock']*$hpp;
                            $total_inventory += $total;
                        }
                    ?>
                    <div class="card shadow mb-4">
                        <div class="card border-left-danger shadow h-100">
                            <!-- Card Header - Accordion -->
                            <a href="#collapseStok" class="d-block card-header py-3" data-toggle="collapse"
                                role="button" aria-expanded="true" aria-controls="collapseStok" style="margin-top: -2%;">
                                <h5 class="m-0 font-weight-bold text-dark">Aset</h5>
                            </a>
                            <!-- Card Content - Collapse -->
                            <div class="collapse" id="collapseStok">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                        <center>
                                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                                <strong>
                                                    Total Aset <br> 
                                                    Rp <?= number_format($total_aset = $total_inventory + $tampil_cash["total"] + $tampil_aset["total"]); ?>
                                                </strong>
                                            </div>
                                        </center>
                                    </div>
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                        <a href="inventorystok.php">Inventory Stock</a>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            Rp <?= number_format($total_inventory); ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                        <a href="cashbank.php">Cash Bank</a>
                                        <div class="h8 mb-0 font-weight-bold" style="color: #8a8a8a">
                                            <?= $tampil_cash["waktu"]; ?>
                                        </div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            Rp <?= number_format($tampil_cash["total"]); ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                        <a href="aset2.php">Aset Tetap</a>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            Rp <?= number_format($tampil_aset["total"]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END ASET -->
                <!-- PRE ORDER -->
                    <div class="card shadow mb-4">
                        <div class="card border-left-warning shadow h-100" >                                
                            <!-- Card Header - Accordion -->
                            <a href="#collapsePO" class="d-block card-header py-3" data-toggle="collapse"
                                role="button" aria-expanded="true" aria-controls="collapsePO" style="margin-top: -2%;">
                                <h5 class="m-0 font-weight-bold text-dark">Pre Order WNJ</h5>
                            </a>
                            <!-- Card Content - Collapse -->
                            <div class="collapse" id="collapsePO">
                                <div class="card-body">
                                    <table class="table" id="tb_po">
                                        <thead>
                                            <tr>  
                                                <th>No</th> 
                                                <th>Nama PO (ID)</th>
                                                <th>Jumlah PO (Pcs/Pack)</th>  
                                                <th>Periode Order</th>  
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $datapo=$koneksi->query("SELECT poproduk.idpoproduk,
                                                                                poproduk.namapo, 
                                                                                poproduk.status,
                                                                                poproduk.tglselesai,
                                                                                sum(pomitra.jumlah) as jumlahnya
                                                                            FROM poproduk 
                                                                            INNER JOIN pomitra
                                                                            ON poproduk.idpoproduk=pomitra.idpoproduk 
                                                                            WHERE poproduk.tipe ='Normal'
                                                                            GROUP BY pomitra.idpoproduk 
                                                                            ORDER BY pomitra.idpoproduk DESC
                                                                        ");
                                                $no=1;
                                                while($tampilkan=$datapo->fetch_assoc()){
                                            ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>
                                                <?php echo $tampilkan['namapo']; ?> (<?php echo $tampilkan['idpoproduk']; ?>)
                                            </td>  
                                            <td>
                                                <?php echo $tampilkan['jumlahnya']; ?>
                                                <?php if ($tampilkan['idpoproduk']==186 or $tampilkan['idpoproduk']==187): ?>
                                                    (<?= $tampilkan['jumlahnya']/12; ?> Seri)
                                                <?php endif ?>
                                            </td>
                                            <td class="align-middle" style="text-align: center;">
                                                <?php if ($tampilkan['status']=='open'): ?>
                                                    <div class="badge bg-light rounded-pill" style='color:green' id="demo1<?php echo $tampilkan['idpoproduk']; ?>">
                                                        <?php echo strtoupper($tampilkan['status']); ?>
                                                    </div>
                                                    <p align='center' id='demo<?php echo $tampilkan['idpoproduk']; ?>'></p>
                                                <?php else: ?>
                                                    <div class="badge bg-light rounded-pill" style='color:red'>
                                                        <?php echo strtoupper($tampilkan['status']); ?>
                                                    </div>
                                                <?php endif ?>

                                                <script>
                                                    // Mengatur waktu akhir perhitungan mundur
                                                    var countDownDate<?php echo $tampilkan['idpoproduk']; ?> = new Date("<?php echo $tampilkan['tglselesai']; ?>").getTime();

                                                    // Memperbarui hitungan mundur setiap 1 detik
                                                    var x = setInterval(function() {

                                                    // Untuk mendapatkan tanggal dan waktu hari ini
                                                    var now = new Date().getTime();
                                                        
                                                    // Temukan jarak antara sekarang dan tanggal hitung mundur
                                                    var distance = countDownDate<?php echo $tampilkan['idpoproduk']; ?> - now;
                                                        
                                                    // Perhitungan waktu untuk hari, jam, menit dan detik
                                                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                                        
                                                    // Keluarkan hasil dalam elemen dengan id = "demo"
                                                    document.getElementById("demo<?php echo $tampilkan['idpoproduk']; ?>").innerHTML = days + "d " + hours + "h "
                                                    + minutes + "m " + seconds + "s ";
                                                        
                                                    // Jika hitungan mundur selesai, tulis beberapa teks 
                                                    if (distance < 0) {
                                                        clearInterval(x);
                                                        document.getElementById("demo<?php echo $tampilkan['idpoproduk']; ?>").innerHTML = "PO Selesai";
                                                        document.getElementById("demo1<?php echo $tampilkan['idpoproduk']; ?>").innerHTML = "<div style='color:red'>CLOSE</div>";
                                                        }
                                                    }, 1000);
                                                </script>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END PRE ORDER -->
                <!-- PROGRES PO -->
                    <div class="card shadow mb-4">
                        <div class="card shadow h-100">
                            <!-- Card Header - Accordion -->
                            <a 
                                href="#collapseCardProgres" 
                                class="d-block card-header py-3" 
                                data-toggle="collapse"
                                role="button" 
                                aria-expanded="true" 
                                aria-controls="collapseCardProgres" 
                                style="margin-top: -2%;"
                            >
                                <?php if($role == "Produksi") : ?>
                                    <h5 class="m-0 font-weight-bold text-dark">SJV</h5>
                                <?php else : ?>
                                    <h5 class="m-0 font-weight-bold text-dark">Progres PO</h5>
                                <?php endif; ?>
                            </a>
                            
                            <div class="collapse" id="collapseCardProgres">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="tb_stock">
                                        <thead>
                                            <tr>
                                            <th class="d-none">No</th>
                                            <!--<th>Nama PO</th>-->
                                            <!--<th>Jumlah PO</th>-->
                                            <!--<th>Masuk</th>-->
                                            <!--<th>Kekurangan</th>-->
                                            <!--<th>Vendor</th>-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                                    <?php  
                                                    $no=1;
                                                    $ambil=$koneksi->query("SELECT poproduk.idpoproduk,
                                                                                    poproduk.namapo, 
                                                                                    sum(pomitra.jumlah) as jumlahnya,
                                                                                    poproduk.jenis
                                                                FROM poproduk 
                                                                INNER JOIN pomitra on poproduk.idpoproduk=pomitra.idpoproduk
                                                                GROUP BY poproduk.idpoproduk ORDER BY poproduk.updated_at DESC LIMIT 15"); 
                                                    while($tampil=$ambil->fetch_assoc()){
                                                    $idpoproduk = $tampil['idpoproduk'];
                                                            $query_ambil = $koneksi->query("SELECT 
                                                                                                idpoproduk,
                                                                                                SUM(jumlah) as total_masuk
                                                                                            FROM 
                                                                                                sjk
                                                                                            WHERE 
                                                                                                idpoproduk = '$idpoproduk'
                                                                                            GROUP BY 
                                                                                                idpoproduk");
                                                            while($data_masuk = $query_ambil->fetch_assoc()) {
                                                                $barang_masuk = $data_masuk['total_masuk'];   
                                                                $query_kekurangan = $tampil['jumlahnya'] - $data_masuk['total_masuk'];
                                                            }
                                                        
                                                        $persentase = $barang_masuk/$tampil['jumlahnya'] * 100;
                                                    ?>      
                                                <tr>
                                                <td class="d-none"><?= $no++; ?></td>
                                                <td>
                                                        <p><?= $tampil['namapo']; ?></p>
                                                        <div class="progress bg-secondary-subtle" role="progressbar" aria-label="Warning example" aria-valuenow="<?= $barang_masuk ?>" aria-valuemin="0" aria-valuemax="<?= $tampil['jumlahnya'] ?>">
                                                        <div class="progress-bar bg-primary" style="width: <?= $persentase ?>%"><?= round($persentase) ?>%</div>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <p><?= $barang_masuk ?></p>
                                                            <p>-<?= $query_kekurangan ?></p>
                                                            <p><?= $tampil['jumlahnya'] ?></p>
                                                        </div>
                                                        <span id="dots<?php echo $tampil['idpoproduk']; ?>"></span>
                                                        <span id="more<?php echo $tampil['idpoproduk']; ?>" style="display:none;">
                                                            <?php 
                                                                $vendor = $koneksi->query("SELECT vendor.vendor, sjk.vendor as idvendor, sjk.idpoproduk as idpoproduk
                                                                                        FROM sjk 
                                                                                        INNER JOIN vendor on vendor.id=sjk.vendor
                                                                                        WHERE sjk.idpoproduk='$idpoproduk'
                                                                                        GROUP BY sjk.vendor
                                                                                        "); 
                                                                while($data_vendor = $vendor->fetch_assoc()) {
                                                                    $idpoproduk_vendor = $data_vendor['idpoproduk'];
                                                                    $idvendor = $data_vendor['idvendor'];
                                                            ?>
                                                                <!-- Button trigger modal -->
                                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-<?= $idpoproduk_vendor ?>-<?= $idvendor ?>" data-idvendor="<?= $idvendor ?>">
                                                                    <?= $data_vendor['vendor'] ?>
                                                                </button>
                                                                
                                                                <!-- Modal -->
                                                                <div class="modal fade" id="modal-<?= $idpoproduk_vendor; ?>-<?= $idvendor ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <table class="table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th scope="col">No SJK</th>
                                                                                            <th scope="col">Nama PO</th>
                                                                                            <th scope="col">Jumlah</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php
                                                                                        $sql = $koneksi->query("SELECT sjk.sjk,
                                                                                                                    SUM(sjk.jumlah) as jumlah,
                                                                                                                    poproduk.namapo
                                                                                                                    FROM sjk
                                                                                                                    INNER JOIN poproduk on poproduk.idpoproduk=sjk.idpoproduk
                                                                                                                    WHERE sjk.idpoproduk = '$idpoproduk_vendor' AND sjk.vendor = '$idvendor'
                                                                                                                    GROUP BY sjk.sjk
                                                                                                                    LIMIT 15
                                                                                                                ");
                                                                                        while ($row = $sql->fetch_assoc()) {
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td><?= $row['sjk'] ?></td>
                                                                                                <td><?= $row['namapo'] ?></td>
                                                                                                <td><?= $row['jumlah'] ?></td>
                                                                                            </tr>
                                                                                        <?php } ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <a href="data_barang_masuk.php?id=<?= $idpoproduk ?>&vendor=<?= $idvendor ?>">Lihat selengkapnya</a>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                                <button type="button" class="btn btn-primary">Save changes</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </span>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <button onclick="myFunction<?php echo $tampil['idpoproduk']; ?>()" id="myBtn<?php echo $tampil['idpoproduk']; ?>" class="btn btn-outline-secondary btn-sm d-block my-2">
                                                                <i class='fa fa-eye'></i>
                                                            </button>
                                                            <div class="my-1">
                                                                <!-- Button trigger modal -->
                                                                <a href="barang_masuk.php?id=<?= $idpoproduk ?>" class="btn btn-success btn-sm">
                                                                    <span class="bi bi-ui-radios"></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            function myFunction<?php echo $tampil['idpoproduk']; ?>() {
                                                                var dots = document.getElementById("dots<?php echo $tampil['idpoproduk']; ?>");
                                                                var moreText = document.getElementById("more<?php echo $tampil['idpoproduk']; ?>");
                                                                var btnText = document.getElementById("myBtn<?php echo $tampil['idpoproduk']; ?>");
                                        
                                                                if (dots.style.display === "none") {
                                                                    dots.style.display = "inline";
                                                                    btnText.innerHTML = "<i class='fa fa-eye'></i>"; 
                                                                    moreText.style.display = "none";
                                                                } else {
                                                                    dots.style.display = "none";
                                                                    btnText.innerHTML = "<i class='fa fa-eye-slash'></i>"; 
                                                                    moreText.style.display = "inline";
                                                                }
                                                            }
                                                        </script>
                                                </td>
                                                <!--<td><?//= $tampil['jumlahnya']; ?></td>-->
                                                <!--<td>-->
                                                <!--    <?//= $barang_masuk ?>-->
                                                <!--</td>-->
                                                <!--<td>-->
                                                <!--    <?//= $query_kekurangan ?>-->
                                                <!--</td>-->
                                                <!--<td>-->
                                                    
                                                <!--</td>-->
                                                </tr>
                                            <?php } ?>          
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END PROGRES PO -->
                <!-- ADS -->
                    <div class="card shadow mb-4">
                        <div class="card border-left-secondary shadow h-100">
                            <!-- Card Header - Accordion -->
                            <a href="#collapseAds" class="d-block card-header py-3" data-toggle="collapse"
                                role="button" aria-expanded="true" aria-controls="collapseAds" style="margin-top: -2%;">
                                <h5 class="m-0 font-weight-bold text-dark">Ads</h5>
                            </a>
                            <!-- Card Content - Collapse -->
                            <div class="collapse" id="collapseAds">
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>   
                                                <th>Tanggal</th> 
                                                <th>Nama DB</th>  
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $data_ads=$koneksi->query("SELECT adsmitra.tgl, admin_mitra.namamitra, adsmitra.status 
                                                                            FROM adsmitra 
                                                                            LEFT JOIN admin_mitra
                                                                            ON admin_mitra.idadmin = adsmitra.idmitra    
                                                                            ORDER BY adsmitra.tgl DESC LIMIT 5
                                                                        ");
                                                $no=1;
                                                while($tampilkan_ads=$data_ads->fetch_assoc()){
                                            ?>
                                                <tr>

                                                    <td><?= $newDate = date("y-m-d", strtotime($tampilkan_ads['tgl'])) ?></td>
                                                    <td><?= $tampilkan_ads['namamitra'] ?></td>
                                                    <td><?= $tampilkan_ads['status'] ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <center>
                                        <a href="dataads.php">Selengkapnya</a>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END ADS -->
                <!-- PENJUALAN ZIZAZU -->
                    <iframe src="https://zizazu.id/manajemen/penjualan_harian.php" style="width: 100%;height: 600px;overflow:hidden;border: none;"></iframe>
                <!-- END PENJUALAN ZIZAZU --> 
            <!-- END OWNER -->
            <?php elseif ($role == "Admin Finance") : ?>
            <!-- ADMIN FINANCE -->
                <!-- FINANCE -->
                    <div class="card shadow mb-4">
                        <div class="card border-left-success shadow h-100" >                              
                            <!-- Card Header - Accordion -->
                            <a href="finance.php?tipe=AF" class="d-block card-header py-3" 
                                role="button" aria-expanded="true" aria-controls="collapseCardExample" style="margin-top: -2%;">
                                <h5 class="m-0 font-weight-bold text-dark">Finance</h5>
                            </a>
                            <!-- Card Content - Collapse -->
                            <div id="collapseCardExample">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        <a href="finance.php?tipe=AF">Kas Admin</a>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            Rp <?= number_format($dataaf['sisa']); ?>
                                        </div>
                                    </div>

                                    <hr>
                                    
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        <a href="finance.php?tipe=PM">Kas Pemotretan</a>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            Rp <?= number_format($datapm['sisa']); ?>
                                        </div>
                                    </div>                                            
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END FINANCE -->
                <!-- PEMASUKAN -->
                    <div class="card shadow mb-4">
                        <div class="card border-left-success shadow h-100">
                            <!-- Card Header - Accordion -->
                            <a href="#collapseCardPemasukan" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardPemasukan" style="margin-top: -2%;">
                                <h5 class="m-0 font-weight-bold text-dark">Pemasukan</h5>
                            </a>
                            <!-- Card Content - Collapse -->
                            <div class="collapse" id="collapseCardPemasukan">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        <a href="pemasukan.php">Pemasukan</a>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            Rp. 0
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END PEMASUKAN -->
                <!-- CASH BANK -->
                    <div class="card shadow mb-4">
                        <div class="card border-left-dark shadow h-100" >                              
                            <!-- Card Header - Accordion -->
                            <a href="cashbank.php" class="d-block card-header py-3"
                                role="button" aria-expanded="true" aria-controls="collapseCashBank" style="margin-top: -2%;">
                                <h5 class="m-0 font-weight-bold text-dark">Cash Bank</h5>
                            </a>
                            <!-- Card Content - Collapse -->
                            <div  id="collapseCashBank">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        <a href="cashbank.php">Cash Bank</a>
                                        <div class="h8 mb-0 font-weight-bold" style="color: #8a8a8a">
                                            <?= $tampil_cash["waktu"]; ?>
                                        </div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            Rp <?= number_format($tampil_cash["total"]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END CASHBANK -->
                <!-- LAPORAN -->
                    <div class="card shadow mb-4">
                        <div class="card border-left-success shadow h-100" >                              
                            <!-- Card Header - Accordion -->
                            <a href="laporan.php" class="d-block card-header py-3" 
                                role="button" aria-expanded="true" aria-controls="collapseCardExample" style="margin-top: -2%;">
                                <h5 class="m-0 font-weight-bold text-dark">Laporan</h5>
                            </a>
                            <!-- Card Content - Collapse -->
                            <div id="collapseCardExample">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        <a href="laporan.php">Laporan</a>
                                    </div>                                            
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END LAPORAN -->
            <!-- END ADMIN FINANCE -->
            <?php elseif ($role == "Manajemen") : ?>
            <!-- MANAJEMEN -->
                <!-- PENJUALAN WNJ -->
                    <div class="card shadow mb-4">
                        <div class="card border-left-primary shadow h-100" >
                            <!-- Card Header - Accordion -->
                            <a href="#collapsePenjualan" class="d-block card-header py-3" data-toggle="collapse"role="button" aria-expanded="true" aria-controls="collapsePenjualan" style="margin-top: -2%;">
                                <h5 class="m-0 font-weight-bold text-dark">Penjualan WNJ</h5>
                            </a>
                            <!-- Card Content - Collapse -->
                            <div class="collapse" id="collapsePenjualan">
                                <div class="card-body">
                                    <?php 
                                        // $getHarian = $koneksi->query("SELECT 
                                        //                                     surat_jalan.invoice,
                                        //                                     SUM(surat_jalan.progres) as jumlah,
                                        //                                     MAX(surat_jalan.progres) as progres, -- Atau fungsi agregat lain yang sesuai
                                        //                                     surat_jalan.status,
                                        //                                     surat_jalan.waktu,
                                        //                                     surat_jalan.id_sj,
                                        //                                     surat_jalan.no_sj
                                        //                                 FROM 
                                        //                                     surat_jalan
                                        //                                 WHERE 
                                        //                                     surat_jalan.status <> 'Ambil Barang' 
                                        //                                     AND SUBSTRING(surat_jalan.waktu, 1, 10) LIKE '%$dateNow%'
                                        //                                 GROUP BY 
                                        //                                     surat_jalan.invoice, surat_jalan.status, surat_jalan.waktu, surat_jalan.id_sj, surat_jalan.no_sj
                                        //                                 ORDER BY 
                                        //                                     surat_jalan.waktu DESC, surat_jalan.invoice;
                                        //                             ");
                                        // while($queryHarian = $getHarian->fetch_assoc()){
                                        //     $no_sj = $queryHarian['no_sj'];
                                        //     $totala = 0;
                                        //     $getHarga = $koneksi->query("SELECT produk.harga, surat_jalan.progres 
                                        //                 FROM surat_jalan 
                                        //                 INNER JOIN produk
                                        //                 ON produk.idproduk=surat_jalan.idproduk 
                                        //                 WHERE surat_jalan.no_sj='$no_sj'");
                                        //     while ($queryHarga = $getHarga->fetch_assoc()) {
                                        //         $subtotal = $queryHarga['harga'] * $queryHarga['progres'];
                                        //         $totala = $totala + $subtotal;
                                        //     };

                                        //     $diskon = 35/100*$totala;
                                        //     $totalnya = $totala - $diskon;
                                        //     $totalsemua += $totalnya;
                                        // }

                                        // $getPoHarian = $koneksi->query("SELECT 
                                        //                                     surat_jalan_po.invoice,
                                        //                                     MAX(surat_jalan_po.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                     surat_jalan_po.status,
                                        //                                     surat_jalan_po.waktu,
                                        //                                     SUM(surat_jalan_po.progres) as jumlah,
                                        //                                     surat_jalan_po.id_sj,
                                        //                                     surat_jalan_po.no_sj,
                                        //                                     pomitra.idpoproduk
                                        //                                 FROM 
                                        //                                     surat_jalan_po
                                        //                                 JOIN 
                                        //                                     pomitra 
                                        //                                 ON 
                                        //                                     pomitra.idpomitra = surat_jalan_po.idpomitra
                                        //                                 WHERE 
                                        //                                     surat_jalan_po.status <> 'Ambil Barang' 
                                        //                                     AND SUBSTRING(surat_jalan_po.waktu, 1, 10) LIKE '$dateNow'
                                        //                                 GROUP BY 
                                        //                                     surat_jalan_po.invoice,
                                        //                                     surat_jalan_po.status,
                                        //                                     surat_jalan_po.waktu,
                                        //                                     surat_jalan_po.id_sj,
                                        //                                     surat_jalan_po.no_sj,
                                        //                                     pomitra.idpoproduk
                                        //                                 ORDER BY 
                                        //                                     surat_jalan_po.waktu DESC, surat_jalan_po.invoice");
                                        // while($queryPoHarian = $getPoHarian->fetch_assoc()) {
                                        //     $no_sj_po = $queryPoHarian['no_sj']; 
                                        //     $totala_po = 0;
                                        //     $getPo = $koneksi->query("SELECT podetail.harga, surat_jalan_po.progres 
                                        //         FROM surat_jalan_po 
                                        //         inner join podetail on podetail.idpodetail=surat_jalan_po.idpodetail
                                        //         WHERE surat_jalan_po.no_sj='$no_sj_po'");
                                        //     while ($queryHargaPo = $getPo->fetch_assoc()) {
                                        //         $subtotalPo = $queryHargaPo['harga'] * $queryHargaPo['progres'];
                                        //         $totala_po = $totala_po + $subtotalPo;
                                        //     }

                                        //     $diskon_po = 35/100*$totala_po;
                                        //     $totalpo_nya = $totala_po - $diskon_po;
                                        //     $totalpo_semua += $totalpo_nya;
                                        // }

                                        // $getharianManual=$koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'INV' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) 
                                        //                                         LIKE '%$dateNow%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice, 
                                        //                                         surat_jalan_manual.status, 
                                        //                                         surat_jalan_manual.waktu, 
                                        //                                         surat_jalan_manual.id_sj, 
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                     ");
                                        // while($queryHarianManual=$getharianManual->fetch_assoc()) {
                                        //     $no_sj_manual = $queryHarianManual['no_sj']; 
                                        //     $totala_manual = 0;
                                        //     $getManual = $koneksi->query("SELECT produk.harga, surat_jalan_manual.progres 
                                        //                     FROM surat_jalan_manual 
                                        //                     INNER JOIN produk ON produk.idproduk=surat_jalan_manual.idproduk 
                                        //                     WHERE surat_jalan_manual.no_sj='$no_sj_manual'");
                                        //     while ($ga_manual = $getManual->fetch_assoc()){
                                        //         $subtotal_manual = $ga_manual['harga'] * $ga_manual['progres'];
                                        //         $totala_manual = $totala_manual + $subtotal_manual;
                                        //     }

                                        //     $diskon_manual = 35/100*$totala_manual;
                                        //     $totalnya_manual = $totala_manual - $diskon_manual;    
                                        //     $totalsemua_manual += $totalnya_manual;
                                        // }    

                                        // $getManualPo=$koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'IPO' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) LIKE '%$dataNow%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                 ");
                                        // while($queryManualPo=$getManualPo->fetch_assoc()) {
                                        //     $no_sj_manual_po = $queryManualPo['no_sj']; 
                                        //     $totala_manual_po = 0;

                                        //     $getManual_po = $koneksi->query("SELECT podetail.harga, surat_jalan_manual.progres 
                                        //                         FROM surat_jalan_manual 
                                        //                         inner join podetail on podetail.idpodetail=surat_jalan_manual.idproduk 
                                        //                         WHERE surat_jalan_manual.no_sj='$no_sj_manual_po'");

                                        //     while ($ga_manual_po = $getManual_po->fetch_assoc()){
                                        //         $subtotal_manual_po = $ga_manual_po['harga'] * $ga_manual_po['progres'];
                                        //         $totala_manual_po = $totala_manual_po + $subtotal_manual_po;
                                        //     }

                                        //     $diskon_manual_po = 35/100*$totala_manual_po;
                                        //     $totalnya_manual_po = $totala_manual_po - $diskon_manual_po;
                                        //     $totalsemua_manual_po += $totalnya_manual_po;
                                        // } 

                                        // $data_harian_1 = $koneksi->query("SELECT 
                                        //                                     surat_jalan.invoice,
                                        //                                     SUM(surat_jalan.progres) as jumlah,
                                        //                                     MAX(surat_jalan.progres) as progres, -- Atau fungsi agregat lain yang sesuai
                                        //                                     surat_jalan.status,
                                        //                                     surat_jalan.waktu,
                                        //                                     surat_jalan.id_sj,
                                        //                                     surat_jalan.no_sj
                                        //                                 FROM 
                                        //                                     surat_jalan
                                        //                                 WHERE 
                                        //                                     surat_jalan.status <> 'Ambil Barang' 
                                        //                                     AND SUBSTRING(surat_jalan.waktu, 1, 10) LIKE '%$dayKemarin%'
                                        //                                 GROUP BY 
                                        //                                     surat_jalan.invoice, surat_jalan.status, surat_jalan.waktu, surat_jalan.id_sj, surat_jalan.no_sj
                                        //                                 ORDER BY 
                                        //                                     surat_jalan.waktu DESC, surat_jalan.invoice;
                                        //                                 ");
                                        // while($tampilkan_harian_1=$data_harian_1->fetch_assoc()) {
                                        //     $no_sj_1 = $tampilkan_harian_1['no_sj'];
                                        //     $totala_1 = 0;
                                        //     $sql_1 = $koneksi->query("SELECT produk.harga, surat_jalan.progres
                                        //                                 FROM surat_jalan
                                        //                                 inner join produk on produk.idproduk=surat_jalan.idproduk 
                                        //                                 WHERE surat_jalan.no_sj='$no_sj_1' ");
                                        //     while ($ga_1 = $sql_1->fetch_assoc()){
                                        //         $subtotal_1 = $ga_1['harga'] * $ga_1['progres'];
                                        //         $totala_1 = $totala_1 + $subtotal_1;
                                        //     }
                                        //     $diskon_1 = 35/100*$totala_1;
                                        //     $totalnya_1 = $totala_1 - $diskon_1;
                                        //     $totalsemua_1 += $totalnya_1;
                                        // }

                                        // $datapo_harian_1=$koneksi->query("SELECT 
                                        //                                         surat_jalan_po.invoice,
                                        //                                         MAX(surat_jalan_po.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                         surat_jalan_po.status,
                                        //                                         surat_jalan_po.waktu,
                                        //                                         SUM(surat_jalan_po.progres) as jumlah,
                                        //                                         surat_jalan_po.id_sj,
                                        //                                         surat_jalan_po.no_sj,
                                        //                                         pomitra.idpoproduk
                                        //                                     FROM 
                                        //                                         surat_jalan_po
                                        //                                     JOIN 
                                        //                                         pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                        //                                     WHERE 
                                        //                                         surat_jalan_po.status <> 'Ambil Barang' 
                                        //                                         AND SUBSTRING(surat_jalan_po.waktu, 1, 10) LIKE '%$dayKemarin%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_po.invoice,
                                        //                                         surat_jalan_po.status,
                                        //                                         surat_jalan_po.waktu,
                                        //                                         surat_jalan_po.id_sj,
                                        //                                         surat_jalan_po.no_sj,
                                        //                                         pomitra.idpoproduk
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_po.waktu DESC, 
                                        //                                         surat_jalan_po.invoice;
                                        //                                 ");
                                        // while($tampilkanpo_harian_1=$datapo_harian_1->fetch_assoc()) {
                                        //     $no_sj_1_po = $tampilkanpo_harian_1['no_sj']; 
                                        //     $totala_1_po = 0;
                                        //     $query_1_po = $koneksi->query("SELECT podetail.harga, surat_jalan_po.progres
                                        //                     FROM surat_jalan_po
                                        //                     INNER JOIN podetail 
                                        //                     ON podetail.idpodetail=surat_jalan_po.idpodetail 
                                        //                     WHERE surat_jalan_po.no_sj='$no_sj_1_po'
                                        //                 ");
                                        //     while ($ga_1_po = $query_1_po->fetch_assoc()) {
                                        //         $subtotal_1_po = $ga_1_po['harga'] * $ga_1_po['progres'];
                                        //         $totala_1_po = $totala_1_po + $subtotal_1_po;
                                        //     }

                                        //     $diskon_1_po = 35/100*$totala_1_po;
                                        //     $totalpo_nya_1 = $totala_1_po - $diskon_1_po;
                                        //     $totalpo_semua_1 += $totalpo_nya_1;
                                        // }

                                        // $data_harian_manual_1 = $koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'INV' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) 
                                        //                                         LIKE '%$dayKemarin%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice, 
                                        //                                         surat_jalan_manual.status, 
                                        //                                         surat_jalan_manual.waktu, 
                                        //                                         surat_jalan_manual.id_sj, 
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                         ");
                                        // while($tampilkan_harian_manual_1=$data_harian_manual_1->fetch_assoc()){
                                        //     $no_sj_manual_1 = $tampilkan_harian_manual_1['no_sj'];
                                        //     $totala_manual_1 = 0;
                                        //     $sql_manual_1 = $koneksi->query("SELECT produk.harga, surat_jalan_manual.progres 
                                        //                         FROM surat_jalan_manual 
                                        //                         INNER JOIN produk 
                                        //                         ON produk.idproduk=surat_jalan_manual.idproduk 
                                        //                         WHERE surat_jalan_manual.no_sj='$no_sj_manual_1'
                                        //                     ");
                                        //     while ($ga_manual_1 = $sql_manual_1->fetch_assoc()){
                                        //         $subtotal_manual_1 = $ga_manual_1['harga'] * $ga_manual_1['progres'];
                                        //         $totala_manual_1 = $totala_manual_1 + $subtotal_manual_1;
                                        //     }

                                        //     $diskon_manual_1 = 35/100*$totala_manual_1;
                                        //     $totalnya_manual_1 = $totala_manual_1 - $diskon_manual_1;
                                        //     $totalsemua_manual_1 += $totalnya_manual_1;
                                        // }

                                        // $data_harian_manual_po1 = $koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'IPO' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) LIKE '%$dayKemarin%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                         ");
                                        // while($tampilkan_harian_manual_po1=$data_harian_manual_po1->fetch_assoc()){
                                        //     $no_sj_manual_po1 = $tampilkan_harian_manual_po1['no_sj']; 
                                        //     $totala_manual_po1 = 0;
                                        //     $sql_manual_po1 = $koneksi->query("SELECT podetail.harga, surat_jalan_manual.progres 
                                        //                         FROM surat_jalan_manual 
                                        //                         INNER JOIN podetail
                                        //                         ON podetail.idpodetail=surat_jalan_manual.idproduk 
                                        //                         WHERE surat_jalan_manual.no_sj='$no_sj_manual_po1'
                                        //                     ");
                                        //     while ($ga_manual_po1 = $sql_manual_po1->fetch_assoc()){
                                        //         $subtotal_manual_po1 = $ga_manual_po1['harga'] * $ga_manual_po1['progres'];
                                        //         $totala_manual_po1 = $totala_manual_po1 + $subtotal_manual_po1;
                                        //     }
                                        //     $diskon_manual_po1 = 35/100*$totala_manual_po1;
                                        //     $totalnya_manual_po1 = $totala_manual_po1 - $diskon_manual_po1;    
                                        //     $totalsemua_manual_po1 += $totalnya_manual_po1;   
                                        // } 

                                        // $data_harian_2 = $koneksi->query("SELECT 
                                        //                                     surat_jalan.invoice,
                                        //                                     SUM(surat_jalan.progres) as jumlah,
                                        //                                     MAX(surat_jalan.progres) as progres, -- Atau fungsi agregat lain yang sesuai
                                        //                                     surat_jalan.status,
                                        //                                     surat_jalan.waktu,
                                        //                                     surat_jalan.id_sj,
                                        //                                     surat_jalan.no_sj
                                        //                                 FROM 
                                        //                                     surat_jalan
                                        //                                 WHERE 
                                        //                                     surat_jalan.status <> 'Ambil Barang' 
                                        //                                     AND SUBSTRING(surat_jalan.waktu, 1, 10) LIKE '%$dayLusa%'
                                        //                                 GROUP BY 
                                        //                                     surat_jalan.invoice, surat_jalan.status, surat_jalan.waktu, surat_jalan.id_sj, surat_jalan.no_sj
                                        //                                 ORDER BY 
                                        //                                     surat_jalan.waktu DESC, surat_jalan.invoice;
                                        //                                 ");
                                        // while($tampilkan_harian_2=$data_harian_2->fetch_assoc()) {
                                        //     $no_sj_2 = $tampilkan_harian_2['no_sj']; 
                                        //     $totala_2 = 0;
                                        //     $sql_2 = $koneksi->query("SELECT produk.harga, surat_jalan.progres 
                                        //                 FROM surat_jalan 
                                        //                 inner join produk on produk.idproduk=surat_jalan.idproduk 
                                        //                 WHERE surat_jalan.no_sj='$no_sj_2'
                                        //             ");
                                        //     while ($ga_2 = $sql_2->fetch_assoc()){
                                        //         $subtotal_2 = $ga_2['harga'] * $ga_2['progres'];
                                        //         $totala_2 = $totala_2 + $subtotal_2;
                                        //     }
                                        //     $diskon_2 = 35/100*$totala_2;
                                        //     $totalnya_2 = $totala_2 - $diskon_2;
                                        //     $totalsemua_2 += $totalnya_2;
                                        // }

                                        // $datapo_harian_2=$koneksi->query("SELECT 
                                        //                                         surat_jalan_po.invoice,
                                        //                                         MAX(surat_jalan_po.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                         surat_jalan_po.status,
                                        //                                         surat_jalan_po.waktu,
                                        //                                         SUM(surat_jalan_po.progres) as jumlah,
                                        //                                         surat_jalan_po.id_sj,
                                        //                                         surat_jalan_po.no_sj,
                                        //                                         pomitra.idpoproduk
                                        //                                     FROM 
                                        //                                         surat_jalan_po
                                        //                                     JOIN 
                                        //                                         pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                        //                                     WHERE 
                                        //                                         surat_jalan_po.status <> 'Ambil Barang' 
                                        //                                         AND SUBSTRING(surat_jalan_po.waktu, 1, 10) LIKE '%$dayLusa%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_po.invoice,
                                        //                                         surat_jalan_po.status,
                                        //                                         surat_jalan_po.waktu,
                                        //                                         surat_jalan_po.id_sj,
                                        //                                         surat_jalan_po.no_sj,
                                        //                                         pomitra.idpoproduk
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_po.waktu DESC, 
                                        //                                         surat_jalan_po.invoice;
                                        //                                 ");
                                        // while($tampilkanpo_harian_2=$datapo_harian_2->fetch_assoc()){                                  
                                        //     $no_sj_2_po = $tampilkanpo_harian_2['no_sj']; 
                                        //     $totala_2_po = 0;
                                        //     $sql_2_po = $koneksi->query("SELECT podetail.harga, surat_jalan_po.progres 
                                        //                     FROM surat_jalan_po
                                        //                     INNER JOIN podetail 
                                        //                     ON podetail.idpodetail=surat_jalan_po.idpodetail 
                                        //                     WHERE surat_jalan_po.no_sj='$no_sj_2_po'
                                        //                 ");
                                        //     while ($ga_2_po = $sql_2_po->fetch_assoc()){
                                        //     $subtotal_2_po = $ga_2_po['harga'] * $ga_2_po['progres'];
                                        //     $totala_2_po = $totala_2_po + $subtotal_2_po;

                                        //     }
                                        //     $diskon_2_po = 35/100*$totala_2_po;
                                        //     $totalpo_nya_2 = $totala_2_po - $diskon_2_po;    

                                        //     $totalpo_semua_2 += $totalpo_nya_2;             
                                        // }

                                        // $data_harian_manual_2 = $koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'INV' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) 
                                        //                                         LIKE '%$dayLusa%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice, 
                                        //                                         surat_jalan_manual.status, 
                                        //                                         surat_jalan_manual.waktu, 
                                        //                                         surat_jalan_manual.id_sj, 
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                     ");
                                        // while($tampilkan_harian_manual_2=$data_harian_manual_2->fetch_assoc()) {
                                        //     $no_sj_manual_2 = $tampilkan_harian_manual_2['no_sj'];
                                        //     $totala_manual_2 = 0;
                                        //     $sql_manual_2 = $koneksi->query("SELECT produk.harga, surat_jalan_manual.progres 
                                        //                         FROM surat_jalan_manual 
                                        //                         INNER JOIN produk
                                        //                         ON produk.idproduk = surat_jalan_manual.idproduk 
                                        //                         WHERE surat_jalan_manual.no_sj='$no_sj_manual_2'");
                                        //     while ($ga_manual_2 = $sql_manual_2->fetch_assoc()){
                                        //         $subtotal_manual_2 = $ga_manual_2['harga'] * $ga_manual_2['progres'];
                                        //         $totala_manual_2 = $totala_manual_2 + $subtotal_manual_2;
                                        //     }
                                        //     $diskon_manual_2 = 35/100*$totala_manual_2;
                                        //     $totalnya_manual_2 = $totala_manual_2 - $diskon_manual_2;    
                                        //     $totalsemua_manual_2 += $totalnya_manual_2;   
                                        // }

                                        // $data_harian_manual_po2 = $koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'IPO' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) LIKE '%$dayLusa%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                         ");
                                        // while($tampilkan_harian_manual_po2=$data_harian_manual_po2->fetch_assoc()) {
                                        //     $no_sj_manual_po2 = $tampilkan_harian_manual_po2['no_sj'];
                                        //     $totala_manual_po2 = 0;
                                        //     $sql_manual_po2 = $koneksi->query("SELECT podetail.harga, surat_jalan_manual.progres
                                        //                         FROM surat_jalan_manual
                                        //                         INNER JOIN podetail
                                        //                         ON podetail.idpodetail=surat_jalan_manual.idproduk
                                        //                         WHERE surat_jalan_manual.no_sj='$no_sj_manual_po2'");
                                        //     while ($ga_manual_po2 = $sql_manual_po2->fetch_assoc()){
                                        //         $subtotal_manual_po2 = $ga_manual_po2['harga'] * $ga_manual_po2['progres'];
                                        //         $totala_manual_po2 = $totala_manual_po2 + $subtotal_manual_po2;
                                        //     }
                                        //     $diskon_manual_po2 = 35/100*$totala_manual_po2;
                                        //     $totalnya_manual_po2 = $totala_manual_po2 - $diskon_manual_po2;
                                        //     $totalsemua_manual_po2 += $totalnya_manual_po2;
                                        // }
                                    ?>
                                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true" data-interval="false">
                                        <div class="carousel-indicators">
                                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active btn btn-ligth" aria-current="true" aria-label="Slide 1"></button>
                                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" class="btn btn-ligth" aria-label="Slide 2"></button>
                                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" class="btn btn-ligth" aria-label="Slide 3"></button>
                                        </div>

                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="img/bg-white.png" class="d-block w-100" style="width:100%;height: 100px">
                                                <div class="centered">
                                                    <strong>
                                                        Penjualan Hari ini (<?= $dateNow; ?>)<br>
                                                        Rp <?= number_format($total_harian = $totalsemua + $totalpo_semua + $totalsemua_manual + $totalsemua_manual_po); ?>
                                                    </strong>
                                                </div>       
                                                    
                                            </div>

                                            <div class="carousel-item">
                                                <img src="img/bg-white.png" class="d-block w-100" style="width:100%;height: 100px">
                                                <div class="centered">
                                                    <strong>
                                                        Penjualan Kemarin (<?= $dayKemarin; ?>)<br>
                                                        Rp <?= number_format($total_harian_1 = $totalsemua_1 + $totalpo_semua_1 + $totalsemua_manual_1 + $totalsemua_manual_po1); ?>
                                                    </strong>
                                                </div> 
                                            </div>
                                            
                                            <div class="carousel-item">
                                                <img src="img/bg-white.png" class="d-block w-100" style="width:100%;height: 100px">
                                                <div class="centered">
                                                    <strong>
                                                        Penjualan Kemarin Lusa (<?= $dayLusa; ?>)<br>
                                                        Rp <?= number_format($total_harian_2 = $totalsemua_2 + $totalpo_semua_2 + $totalsemua_manual_2 + $totalsemua_manual_po2); ?>
                                                    </strong>
                                                </div> 
                                            </div>
                                        </div>

                                        <button class="carousel-control-prev btn btn-ligth" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fas fa-arrow-left" style="color: black"></i></span>
                                        </button>
                                        <button class="carousel-control-next btn btn-ligth" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"><i class="fas fa-arrow-right" style="color: black"></i></span>
                                        </button>
                                    </div>
                                    <hr>
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1 aText">
                                        <a href="readystok2.php" class="d-block">Ready Stok</a>
                                        <label>Rp <?= number_format($totalsemua); ?></label>
                                    </div>
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1 aText">
                                        <a href="readystok2.php" class="d-block">Ready Stok Manual</a>
                                        <label>Rp <?= number_format($totalsemua_manual); ?></label>
                                    </div>
                                    <hr>
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1 aText">
                                        <a href="preorder.php" class="d-block">Pre Order</a>
                                        <label>Rp <?= number_format($totalpo_semua); ?></label>
                                    </div>
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1 aText">
                                        <a href="preorder.php" class="d-block">Pre Order Manual</a>
                                        <label>Rp <?= number_format($totalsemua_manual_po); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END PENJUALAN WNJ -->
                <!-- FINANCE -->
                    <div class="card shadow mb-4">
                        <div class="card border-left-success shadow h-100" >                              
                            <!-- Card Header - Accordion -->
                            <a href="#collapseCardExample" class="d-block card-header py-3" 
                                role="button" aria-expanded="true" aria-controls="collapseCardExample" style="margin-top: -2%;">
                                <h5 class="m-0 font-weight-bold text-dark">Finance</h5>
                            </a>
                            <!-- Card Content - Collapse -->
                            <div id="collapseCardExample">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        <a href="finance.php">Kas Manajemen</a>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            Rp <?= number_format($datam["sisa"]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END FINANCE -->
                <!-- PROGRES PO -->
                    <div class="card shadow mb-4">
                        <div class="card shadow h-100">
                            <!-- Card Header - Accordion -->
                            <a 
                                href="#collapseCardProgres" 
                                class="d-block card-header py-3" 
                                data-toggle="collapse"
                                role="button" 
                                aria-expanded="true" 
                                aria-controls="collapseCardProgres" 
                                style="margin-top: -2%;"
                            >
                                <?php if($role == "Produksi") : ?>
                                    <h5 class="m-0 font-weight-bold text-dark">SJV</h5>
                                <?php else : ?>
                                    <h5 class="m-0 font-weight-bold text-dark">Progres PO</h5>
                                <?php endif; ?>
                            </a>
                            
                            <div class="collapse" id="collapseCardProgres">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="tb_stock">
                                        <thead>
                                            <tr>
                                            <th class="d-none">No</th>
                                            <!--<th>Nama PO</th>-->
                                            <!--<th>Jumlah PO</th>-->
                                            <!--<th>Masuk</th>-->
                                            <!--<th>Kekurangan</th>-->
                                            <!--<th>Vendor</th>-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                                    <?php  
                                                    $no=1;
                                                    $ambil=$koneksi->query("SELECT poproduk.idpoproduk,
                                                                                    poproduk.namapo, 
                                                                                    sum(pomitra.jumlah) as jumlahnya,
                                                                                    poproduk.jenis
                                                                FROM poproduk 
                                                                INNER JOIN pomitra on poproduk.idpoproduk=pomitra.idpoproduk
                                                                GROUP BY poproduk.idpoproduk ORDER BY poproduk.updated_at DESC LIMIT 15"); 
                                                    while($tampil=$ambil->fetch_assoc()){
                                                    $idpoproduk = $tampil['idpoproduk'];
                                                            $query_ambil = $koneksi->query("SELECT 
                                                                                                idpoproduk,
                                                                                                SUM(jumlah) as total_masuk
                                                                                            FROM 
                                                                                                sjk
                                                                                            WHERE 
                                                                                                idpoproduk = '$idpoproduk'
                                                                                            GROUP BY 
                                                                                                idpoproduk");
                                                            while($data_masuk = $query_ambil->fetch_assoc()) {
                                                                $barang_masuk = $data_masuk['total_masuk'];   
                                                                $query_kekurangan = $tampil['jumlahnya'] - $data_masuk['total_masuk'];
                                                            }
                                                        
                                                        $persentase = $barang_masuk/$tampil['jumlahnya'] * 100;
                                                    ?>      
                                                <tr>
                                                <td class="d-none"><?= $no++; ?></td>
                                                <td>
                                                        <p><?= $tampil['namapo']; ?></p>
                                                        <div class="progress bg-secondary-subtle" role="progressbar" aria-label="Warning example" aria-valuenow="<?= $barang_masuk ?>" aria-valuemin="0" aria-valuemax="<?= $tampil['jumlahnya'] ?>">
                                                        <div class="progress-bar bg-primary" style="width: <?= $persentase ?>%"><?= round($persentase) ?>%</div>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <p><?= $barang_masuk ?></p>
                                                            <p>-<?= $query_kekurangan ?></p>
                                                            <p><?= $tampil['jumlahnya'] ?></p>
                                                        </div>
                                                        <span id="dots<?php echo $tampil['idpoproduk']; ?>"></span>
                                                        <span id="more<?php echo $tampil['idpoproduk']; ?>" style="display:none;">
                                                            <?php 
                                                                $vendor = $koneksi->query("SELECT vendor.vendor, sjk.vendor as idvendor, sjk.idpoproduk as idpoproduk
                                                                                        FROM sjk 
                                                                                        INNER JOIN vendor on vendor.id=sjk.vendor
                                                                                        WHERE sjk.idpoproduk='$idpoproduk'
                                                                                        GROUP BY sjk.vendor
                                                                                        "); 
                                                                while($data_vendor = $vendor->fetch_assoc()) {
                                                                    $idpoproduk_vendor = $data_vendor['idpoproduk'];
                                                                    $idvendor = $data_vendor['idvendor'];
                                                            ?>
                                                                <!-- Button trigger modal -->
                                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-<?= $idpoproduk_vendor ?>-<?= $idvendor ?>" data-idvendor="<?= $idvendor ?>">
                                                                    <?= $data_vendor['vendor'] ?>
                                                                </button>
                                                                
                                                                <!-- Modal -->
                                                                <div class="modal fade" id="modal-<?= $idpoproduk_vendor; ?>-<?= $idvendor ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <table class="table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th scope="col">No SJK</th>
                                                                                            <th scope="col">Nama PO</th>
                                                                                            <th scope="col">Jumlah</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php
                                                                                        $sql = $koneksi->query("SELECT sjk.sjk,
                                                                                                                    SUM(sjk.jumlah) as jumlah,
                                                                                                                    poproduk.namapo
                                                                                                                    FROM sjk
                                                                                                                    INNER JOIN poproduk on poproduk.idpoproduk=sjk.idpoproduk
                                                                                                                    WHERE sjk.idpoproduk = '$idpoproduk_vendor' AND sjk.vendor = '$idvendor'
                                                                                                                    GROUP BY sjk.sjk
                                                                                                                    LIMIT 15
                                                                                                                ");
                                                                                        while ($row = $sql->fetch_assoc()) {
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td><?= $row['sjk'] ?></td>
                                                                                                <td><?= $row['namapo'] ?></td>
                                                                                                <td><?= $row['jumlah'] ?></td>
                                                                                            </tr>
                                                                                        <?php } ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <a href="data_barang_masuk.php?id=<?= $idpoproduk ?>&vendor=<?= $idvendor ?>">Lihat selengkapnya</a>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                                <button type="button" class="btn btn-primary">Save changes</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </span>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <button onclick="myFunction<?php echo $tampil['idpoproduk']; ?>()" id="myBtn<?php echo $tampil['idpoproduk']; ?>" class="btn btn-outline-secondary btn-sm d-block my-2">
                                                                <i class='fa fa-eye'></i>
                                                            </button>
                                                            <div class="my-1">
                                                                <!-- Button trigger modal -->
                                                                <a href="barang_masuk.php?id=<?= $idpoproduk ?>" class="btn btn-success btn-sm">
                                                                    <span class="bi bi-ui-radios"></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            function myFunction<?php echo $tampil['idpoproduk']; ?>() {
                                                                var dots = document.getElementById("dots<?php echo $tampil['idpoproduk']; ?>");
                                                                var moreText = document.getElementById("more<?php echo $tampil['idpoproduk']; ?>");
                                                                var btnText = document.getElementById("myBtn<?php echo $tampil['idpoproduk']; ?>");
                                        
                                                                if (dots.style.display === "none") {
                                                                    dots.style.display = "inline";
                                                                    btnText.innerHTML = "<i class='fa fa-eye'></i>"; 
                                                                    moreText.style.display = "none";
                                                                } else {
                                                                    dots.style.display = "none";
                                                                    btnText.innerHTML = "<i class='fa fa-eye-slash'></i>"; 
                                                                    moreText.style.display = "inline";
                                                                }
                                                            }
                                                        </script>
                                                </td>
                                                <!--<td><?//= $tampil['jumlahnya']; ?></td>-->
                                                <!--<td>-->
                                                <!--    <?//= $barang_masuk ?>-->
                                                <!--</td>-->
                                                <!--<td>-->
                                                <!--    <?//= $query_kekurangan ?>-->
                                                <!--</td>-->
                                                <!--<td>-->
                                                    
                                                <!--</td>-->
                                                </tr>
                                            <?php } ?>          
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END PROGRES PO -->
                <!-- PRE ORDER -->
                    <div class="card shadow mb-4">
                        <div class="card border-left-warning shadow h-100">
                            <!-- Card Header - Accordion -->
                            <a 
                                href="#collapsePO" 
                                class="d-block card-header py-3" 
                                data-toggle="collapse"
                                role="button" 
                                aria-expanded="true" 
                                aria-controls="collapsePO" 
                                style="margin-top: -2%;"
                            >
                                <h5 class="m-0 font-weight-bold text-dark">Pre Order</h5>
                            </a>
                            <!-- Card Content - Collapse -->
                            <div class="collapse" id="collapsePO">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        <a href="listpoartikel.php">
                                        List PO Per Artikel
                                        </a>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        <a href="daftarpoproduk.php">
                                        List PO Per Invoice
                                        </a>
                                    </div>

                                    <hr>
                                    
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        <a href="listpodropship.php">
                                        List PO Per Dropship
                                        </a>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        <a href="listpopembayaran.php">
                                        List PO Pembayaran DP
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END PRE ORDER -->
            <!-- END MANAJEMEN -->
            <?php elseif ($role == "Produksi") :?>
            <!-- PRODUKSI -->
                <!-- FINANCE -->
                    <div class="card shadow my-4">
                        <div class="card shadow h-100">
                            <a 
                                href="finance.php"
                                class="d-block card-header py-3" 
                                role="button"
                                aria-expanded="true"
                                aria-controls="collapseCardExample"
                                style="margin-top: -2%;"
                            >
                                <h5 class="m-0 font-weight-bold text-dark">Finance</h5>
                            </a>
                        </div>
                    </div>
                <!-- END FINANCE -->
                <!-- SJV -->
                    <div class="card shadow mb-4">
                        <div class="card shadow h-100">
                            <!-- Card Header - Accordion -->
                            <a 
                                href="#collapseCardProgres" 
                                class="d-block card-header py-3" 
                                data-toggle="collapse"
                                role="button" 
                                aria-expanded="true" 
                                aria-controls="collapseCardProgres" 
                                style="margin-top: -2%;"
                            >
                                <?php if($role == "Produksi") : ?>
                                    <h5 class="m-0 font-weight-bold text-dark">SJV</h5>
                                <?php else : ?>
                                    <h5 class="m-0 font-weight-bold text-dark">Progres PO</h5>
                                <?php endif; ?>
                            </a>
                            
                            <div class="collapse" id="collapseCardProgres">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="tb_stock">
                                        <thead>
                                            <tr>
                                            <th class="d-none">No</th>
                                            <!--<th>Nama PO</th>-->
                                            <!--<th>Jumlah PO</th>-->
                                            <!--<th>Masuk</th>-->
                                            <!--<th>Kekurangan</th>-->
                                            <!--<th>Vendor</th>-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                                    <?php  
                                                    $no=1;
                                                    $ambil=$koneksi->query("SELECT poproduk.idpoproduk,
                                                                                    poproduk.namapo, 
                                                                                    sum(pomitra.jumlah) as jumlahnya,
                                                                                    poproduk.jenis
                                                                FROM poproduk 
                                                                INNER JOIN pomitra on poproduk.idpoproduk=pomitra.idpoproduk
                                                                GROUP BY poproduk.idpoproduk ORDER BY poproduk.updated_at DESC LIMIT 15"); 
                                                    while($tampil=$ambil->fetch_assoc()){
                                                    $idpoproduk = $tampil['idpoproduk'];
                                                            $query_ambil = $koneksi->query("SELECT 
                                                                                                idpoproduk,
                                                                                                SUM(jumlah) as total_masuk
                                                                                            FROM 
                                                                                                sjk
                                                                                            WHERE 
                                                                                                idpoproduk = '$idpoproduk'
                                                                                            GROUP BY 
                                                                                                idpoproduk");
                                                            while($data_masuk = $query_ambil->fetch_assoc()) {
                                                                $barang_masuk = $data_masuk['total_masuk'];   
                                                                $query_kekurangan = $tampil['jumlahnya'] - $data_masuk['total_masuk'];
                                                            }
                                                        
                                                        $persentase = $barang_masuk/$tampil['jumlahnya'] * 100;
                                                    ?>      
                                                <tr>
                                                <td class="d-none"><?= $no++; ?></td>
                                                <td>
                                                        <p><?= $tampil['namapo']; ?></p>
                                                        <div class="progress bg-secondary-subtle" role="progressbar" aria-label="Warning example" aria-valuenow="<?= $barang_masuk ?>" aria-valuemin="0" aria-valuemax="<?= $tampil['jumlahnya'] ?>">
                                                        <div class="progress-bar bg-primary" style="width: <?= $persentase ?>%"><?= round($persentase) ?>%</div>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <p><?= $barang_masuk ?></p>
                                                            <p>-<?= $query_kekurangan ?></p>
                                                            <p><?= $tampil['jumlahnya'] ?></p>
                                                        </div>
                                                        <span id="dots<?php echo $tampil['idpoproduk']; ?>"></span>
                                                        <span id="more<?php echo $tampil['idpoproduk']; ?>" style="display:none;">
                                                            <?php 
                                                                $vendor = $koneksi->query("SELECT vendor.vendor, sjk.vendor as idvendor, sjk.idpoproduk as idpoproduk
                                                                                        FROM sjk 
                                                                                        INNER JOIN vendor on vendor.id=sjk.vendor
                                                                                        WHERE sjk.idpoproduk='$idpoproduk'
                                                                                        GROUP BY sjk.vendor
                                                                                        "); 
                                                                while($data_vendor = $vendor->fetch_assoc()) {
                                                                    $idpoproduk_vendor = $data_vendor['idpoproduk'];
                                                                    $idvendor = $data_vendor['idvendor'];
                                                            ?>
                                                                <!-- Button trigger modal -->
                                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-<?= $idpoproduk_vendor ?>-<?= $idvendor ?>" data-idvendor="<?= $idvendor ?>">
                                                                    <?= $data_vendor['vendor'] ?>
                                                                </button>
                                                                
                                                                <!-- Modal -->
                                                                <div class="modal fade" id="modal-<?= $idpoproduk_vendor; ?>-<?= $idvendor ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <table class="table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th scope="col">No SJK</th>
                                                                                            <th scope="col">Nama PO</th>
                                                                                            <th scope="col">Jumlah</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php
                                                                                        $sql = $koneksi->query("SELECT sjk.sjk,
                                                                                                                    SUM(sjk.jumlah) as jumlah,
                                                                                                                    poproduk.namapo
                                                                                                                    FROM sjk
                                                                                                                    INNER JOIN poproduk on poproduk.idpoproduk=sjk.idpoproduk
                                                                                                                    WHERE sjk.idpoproduk = '$idpoproduk_vendor' AND sjk.vendor = '$idvendor'
                                                                                                                    GROUP BY sjk.sjk
                                                                                                                    LIMIT 15
                                                                                                                ");
                                                                                        while ($row = $sql->fetch_assoc()) {
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td><?= $row['sjk'] ?></td>
                                                                                                <td><?= $row['namapo'] ?></td>
                                                                                                <td><?= $row['jumlah'] ?></td>
                                                                                            </tr>
                                                                                        <?php } ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <a href="data_barang_masuk.php?id=<?= $idpoproduk ?>&vendor=<?= $idvendor ?>">Lihat selengkapnya</a>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                                <button type="button" class="btn btn-primary">Save changes</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </span>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <button onclick="myFunction<?php echo $tampil['idpoproduk']; ?>()" id="myBtn<?php echo $tampil['idpoproduk']; ?>" class="btn btn-outline-secondary btn-sm d-block my-2">
                                                                <i class='fa fa-eye'></i>
                                                            </button>
                                                            <div class="my-1">
                                                                <!-- Button trigger modal -->
                                                                <a href="barang_masuk.php?id=<?= $idpoproduk ?>" class="btn btn-success btn-sm">
                                                                    <span class="bi bi-ui-radios"></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            function myFunction<?php echo $tampil['idpoproduk']; ?>() {
                                                                var dots = document.getElementById("dots<?php echo $tampil['idpoproduk']; ?>");
                                                                var moreText = document.getElementById("more<?php echo $tampil['idpoproduk']; ?>");
                                                                var btnText = document.getElementById("myBtn<?php echo $tampil['idpoproduk']; ?>");
                                        
                                                                if (dots.style.display === "none") {
                                                                    dots.style.display = "inline";
                                                                    btnText.innerHTML = "<i class='fa fa-eye'></i>"; 
                                                                    moreText.style.display = "none";
                                                                } else {
                                                                    dots.style.display = "none";
                                                                    btnText.innerHTML = "<i class='fa fa-eye-slash'></i>"; 
                                                                    moreText.style.display = "inline";
                                                                }
                                                            }
                                                        </script>
                                                </td>
                                                <!--<td><?//= $tampil['jumlahnya']; ?></td>-->
                                                <!--<td>-->
                                                <!--    <?//= $barang_masuk ?>-->
                                                <!--</td>-->
                                                <!--<td>-->
                                                <!--    <?//= $query_kekurangan ?>-->
                                                <!--</td>-->
                                                <!--<td>-->
                                                    
                                                <!--</td>-->
                                                </tr>
                                            <?php } ?>          
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END SJV -->
            <!-- END PRODUKSI -->
            <?php elseif ($role == "ADS") : ?>
            <!-- ADS -->
                <!-- ADS -->
                    <div class="card shadow mb-4">
                        <div class="card border-left-secondary shadow h-100">
                            <!-- Card Header - Accordion -->
                            <a href="#collapseAds" class="d-block card-header py-3" data-toggle="collapse"
                                role="button" aria-expanded="true" aria-controls="collapseAds" style="margin-top: -2%;">
                                <h5 class="m-0 font-weight-bold text-dark">Ads</h5>
                            </a>
                            <!-- Card Content - Collapse -->
                            <div class="collapse" id="collapseAds">
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>   
                                                <th>Tanggal</th> 
                                                <th>Nama DB</th>  
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $data_ads=$koneksi->query("SELECT adsmitra.tgl, admin_mitra.namamitra, adsmitra.status 
                                                                            FROM adsmitra 
                                                                            LEFT JOIN admin_mitra
                                                                            ON admin_mitra.idadmin = adsmitra.idmitra    
                                                                            ORDER BY adsmitra.tgl DESC LIMIT 5
                                                                        ");
                                                $no=1;
                                                while($tampilkan_ads=$data_ads->fetch_assoc()){
                                            ?>
                                                <tr>

                                                    <td><?= $newDate = date("y-m-d", strtotime($tampilkan_ads['tgl'])) ?></td>
                                                    <td><?= $tampilkan_ads['namamitra'] ?></td>
                                                    <td><?= $tampilkan_ads['status'] ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <center>
                                        <a href="dataads.php">Selengkapnya</a>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- END ADS -->
            <!-- END ADS -->
            <?php endif; ?>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <!-- PHP END -->

    <!-- FOOTER -->
    <?php include "assets/components/Footer/footer.php"; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <!-- END SCRIPT -->
</body>
</html>


<?php 
                                        // $getHarian = $koneksi->query("SELECT 
                                        //                                     surat_jalan.invoice,
                                        //                                     SUM(surat_jalan.progres) as jumlah,
                                        //                                     MAX(surat_jalan.progres) as progres, -- Atau fungsi agregat lain yang sesuai
                                        //                                     surat_jalan.status,
                                        //                                     surat_jalan.waktu,
                                        //                                     surat_jalan.id_sj,
                                        //                                     surat_jalan.no_sj
                                        //                                 FROM 
                                        //                                     surat_jalan
                                        //                                 WHERE 
                                        //                                     surat_jalan.status <> 'Ambil Barang' 
                                        //                                     AND SUBSTRING(surat_jalan.waktu, 1, 10) LIKE '%$dateNow%'
                                        //                                 GROUP BY 
                                        //                                     surat_jalan.invoice, surat_jalan.status, surat_jalan.waktu, surat_jalan.id_sj, surat_jalan.no_sj
                                        //                                 ORDER BY 
                                        //                                     surat_jalan.waktu DESC, surat_jalan.invoice;
                                        //                             ");
                                        // while($queryHarian = $getHarian->fetch_assoc()){
                                        //     $no_sj = $queryHarian['no_sj'];
                                        //     $totala = 0;
                                        //     $getHarga = $koneksi->query("SELECT produk.harga, surat_jalan.progres 
                                        //                 FROM surat_jalan 
                                        //                 INNER JOIN produk
                                        //                 ON produk.idproduk=surat_jalan.idproduk 
                                        //                 WHERE surat_jalan.no_sj='$no_sj'");
                                        //     while ($queryHarga = $getHarga->fetch_assoc()) {
                                        //         $subtotal = $queryHarga['harga'] * $queryHarga['progres'];
                                        //         $totala = $totala + $subtotal;
                                        //     };

                                        //     $diskon = 35/100*$totala;
                                        //     $totalnya = $totala - $diskon;
                                        //     $totalsemua += $totalnya;
                                        // }

                                        // $getPoHarian = $koneksi->query("SELECT 
                                        //                                     surat_jalan_po.invoice,
                                        //                                     MAX(surat_jalan_po.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                     surat_jalan_po.status,
                                        //                                     surat_jalan_po.waktu,
                                        //                                     SUM(surat_jalan_po.progres) as jumlah,
                                        //                                     surat_jalan_po.id_sj,
                                        //                                     surat_jalan_po.no_sj,
                                        //                                     pomitra.idpoproduk
                                        //                                 FROM 
                                        //                                     surat_jalan_po
                                        //                                 JOIN 
                                        //                                     pomitra 
                                        //                                 ON 
                                        //                                     pomitra.idpomitra = surat_jalan_po.idpomitra
                                        //                                 WHERE 
                                        //                                     surat_jalan_po.status <> 'Ambil Barang' 
                                        //                                     AND SUBSTRING(surat_jalan_po.waktu, 1, 10) LIKE '$dateNow'
                                        //                                 GROUP BY 
                                        //                                     surat_jalan_po.invoice,
                                        //                                     surat_jalan_po.status,
                                        //                                     surat_jalan_po.waktu,
                                        //                                     surat_jalan_po.id_sj,
                                        //                                     surat_jalan_po.no_sj,
                                        //                                     pomitra.idpoproduk
                                        //                                 ORDER BY 
                                        //                                     surat_jalan_po.waktu DESC, surat_jalan_po.invoice");
                                        // while($queryPoHarian = $getPoHarian->fetch_assoc()) {
                                        //     $no_sj_po = $queryPoHarian['no_sj']; 
                                        //     $totala_po = 0;
                                        //     $getPo = $koneksi->query("SELECT podetail.harga, surat_jalan_po.progres 
                                        //         FROM surat_jalan_po 
                                        //         inner join podetail on podetail.idpodetail=surat_jalan_po.idpodetail
                                        //         WHERE surat_jalan_po.no_sj='$no_sj_po'");
                                        //     while ($queryHargaPo = $getPo->fetch_assoc()) {
                                        //         $subtotalPo = $queryHargaPo['harga'] * $queryHargaPo['progres'];
                                        //         $totala_po = $totala_po + $subtotalPo;
                                        //     }

                                        //     $diskon_po = 35/100*$totala_po;
                                        //     $totalpo_nya = $totala_po - $diskon_po;
                                        //     $totalpo_semua += $totalpo_nya;
                                        // }

                                        // $getharianManual=$koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'INV' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) 
                                        //                                         LIKE '%$dateNow%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice, 
                                        //                                         surat_jalan_manual.status, 
                                        //                                         surat_jalan_manual.waktu, 
                                        //                                         surat_jalan_manual.id_sj, 
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                     ");
                                        // while($queryHarianManual=$getharianManual->fetch_assoc()) {
                                        //     $no_sj_manual = $queryHarianManual['no_sj']; 
                                        //     $totala_manual = 0;
                                        //     $getManual = $koneksi->query("SELECT produk.harga, surat_jalan_manual.progres 
                                        //                     FROM surat_jalan_manual 
                                        //                     INNER JOIN produk ON produk.idproduk=surat_jalan_manual.idproduk 
                                        //                     WHERE surat_jalan_manual.no_sj='$no_sj_manual'");
                                        //     while ($ga_manual = $getManual->fetch_assoc()){
                                        //         $subtotal_manual = $ga_manual['harga'] * $ga_manual['progres'];
                                        //         $totala_manual = $totala_manual + $subtotal_manual;
                                        //     }

                                        //     $diskon_manual = 35/100*$totala_manual;
                                        //     $totalnya_manual = $totala_manual - $diskon_manual;    
                                        //     $totalsemua_manual += $totalnya_manual;
                                        // }    

                                        // $getManualPo=$koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'IPO' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) LIKE '%$dataNow%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                 ");
                                        // while($queryManualPo=$getManualPo->fetch_assoc()) {
                                        //     $no_sj_manual_po = $queryManualPo['no_sj']; 
                                        //     $totala_manual_po = 0;

                                        //     $getManual_po = $koneksi->query("SELECT podetail.harga, surat_jalan_manual.progres 
                                        //                         FROM surat_jalan_manual 
                                        //                         inner join podetail on podetail.idpodetail=surat_jalan_manual.idproduk 
                                        //                         WHERE surat_jalan_manual.no_sj='$no_sj_manual_po'");

                                        //     while ($ga_manual_po = $getManual_po->fetch_assoc()){
                                        //         $subtotal_manual_po = $ga_manual_po['harga'] * $ga_manual_po['progres'];
                                        //         $totala_manual_po = $totala_manual_po + $subtotal_manual_po;
                                        //     }

                                        //     $diskon_manual_po = 35/100*$totala_manual_po;
                                        //     $totalnya_manual_po = $totala_manual_po - $diskon_manual_po;
                                        //     $totalsemua_manual_po += $totalnya_manual_po;
                                        // } 

                                        // $data_harian_1 = $koneksi->query("SELECT 
                                        //                                     surat_jalan.invoice,
                                        //                                     SUM(surat_jalan.progres) as jumlah,
                                        //                                     MAX(surat_jalan.progres) as progres, -- Atau fungsi agregat lain yang sesuai
                                        //                                     surat_jalan.status,
                                        //                                     surat_jalan.waktu,
                                        //                                     surat_jalan.id_sj,
                                        //                                     surat_jalan.no_sj
                                        //                                 FROM 
                                        //                                     surat_jalan
                                        //                                 WHERE 
                                        //                                     surat_jalan.status <> 'Ambil Barang' 
                                        //                                     AND SUBSTRING(surat_jalan.waktu, 1, 10) LIKE '%$dayKemarin%'
                                        //                                 GROUP BY 
                                        //                                     surat_jalan.invoice, surat_jalan.status, surat_jalan.waktu, surat_jalan.id_sj, surat_jalan.no_sj
                                        //                                 ORDER BY 
                                        //                                     surat_jalan.waktu DESC, surat_jalan.invoice;
                                        //                                 ");
                                        // while($tampilkan_harian_1=$data_harian_1->fetch_assoc()) {
                                        //     $no_sj_1 = $tampilkan_harian_1['no_sj'];
                                        //     $totala_1 = 0;
                                        //     $sql_1 = $koneksi->query("SELECT produk.harga, surat_jalan.progres
                                        //                                 FROM surat_jalan
                                        //                                 inner join produk on produk.idproduk=surat_jalan.idproduk 
                                        //                                 WHERE surat_jalan.no_sj='$no_sj_1' ");
                                        //     while ($ga_1 = $sql_1->fetch_assoc()){
                                        //         $subtotal_1 = $ga_1['harga'] * $ga_1['progres'];
                                        //         $totala_1 = $totala_1 + $subtotal_1;
                                        //     }
                                        //     $diskon_1 = 35/100*$totala_1;
                                        //     $totalnya_1 = $totala_1 - $diskon_1;
                                        //     $totalsemua_1 += $totalnya_1;
                                        // }

                                        // $datapo_harian_1=$koneksi->query("SELECT 
                                        //                                         surat_jalan_po.invoice,
                                        //                                         MAX(surat_jalan_po.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                         surat_jalan_po.status,
                                        //                                         surat_jalan_po.waktu,
                                        //                                         SUM(surat_jalan_po.progres) as jumlah,
                                        //                                         surat_jalan_po.id_sj,
                                        //                                         surat_jalan_po.no_sj,
                                        //                                         pomitra.idpoproduk
                                        //                                     FROM 
                                        //                                         surat_jalan_po
                                        //                                     JOIN 
                                        //                                         pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                        //                                     WHERE 
                                        //                                         surat_jalan_po.status <> 'Ambil Barang' 
                                        //                                         AND SUBSTRING(surat_jalan_po.waktu, 1, 10) LIKE '%$dayKemarin%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_po.invoice,
                                        //                                         surat_jalan_po.status,
                                        //                                         surat_jalan_po.waktu,
                                        //                                         surat_jalan_po.id_sj,
                                        //                                         surat_jalan_po.no_sj,
                                        //                                         pomitra.idpoproduk
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_po.waktu DESC, 
                                        //                                         surat_jalan_po.invoice;
                                        //                                 ");
                                        // while($tampilkanpo_harian_1=$datapo_harian_1->fetch_assoc()) {
                                        //     $no_sj_1_po = $tampilkanpo_harian_1['no_sj']; 
                                        //     $totala_1_po = 0;
                                        //     $query_1_po = $koneksi->query("SELECT podetail.harga, surat_jalan_po.progres
                                        //                     FROM surat_jalan_po
                                        //                     INNER JOIN podetail 
                                        //                     ON podetail.idpodetail=surat_jalan_po.idpodetail 
                                        //                     WHERE surat_jalan_po.no_sj='$no_sj_1_po'
                                        //                 ");
                                        //     while ($ga_1_po = $query_1_po->fetch_assoc()) {
                                        //         $subtotal_1_po = $ga_1_po['harga'] * $ga_1_po['progres'];
                                        //         $totala_1_po = $totala_1_po + $subtotal_1_po;
                                        //     }

                                        //     $diskon_1_po = 35/100*$totala_1_po;
                                        //     $totalpo_nya_1 = $totala_1_po - $diskon_1_po;
                                        //     $totalpo_semua_1 += $totalpo_nya_1;
                                        // }

                                        // $data_harian_manual_1 = $koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'INV' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) 
                                        //                                         LIKE '%$dayKemarin%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice, 
                                        //                                         surat_jalan_manual.status, 
                                        //                                         surat_jalan_manual.waktu, 
                                        //                                         surat_jalan_manual.id_sj, 
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                         ");
                                        // while($tampilkan_harian_manual_1=$data_harian_manual_1->fetch_assoc()){
                                        //     $no_sj_manual_1 = $tampilkan_harian_manual_1['no_sj'];
                                        //     $totala_manual_1 = 0;
                                        //     $sql_manual_1 = $koneksi->query("SELECT produk.harga, surat_jalan_manual.progres 
                                        //                         FROM surat_jalan_manual 
                                        //                         INNER JOIN produk 
                                        //                         ON produk.idproduk=surat_jalan_manual.idproduk 
                                        //                         WHERE surat_jalan_manual.no_sj='$no_sj_manual_1'
                                        //                     ");
                                        //     while ($ga_manual_1 = $sql_manual_1->fetch_assoc()){
                                        //         $subtotal_manual_1 = $ga_manual_1['harga'] * $ga_manual_1['progres'];
                                        //         $totala_manual_1 = $totala_manual_1 + $subtotal_manual_1;
                                        //     }

                                        //     $diskon_manual_1 = 35/100*$totala_manual_1;
                                        //     $totalnya_manual_1 = $totala_manual_1 - $diskon_manual_1;
                                        //     $totalsemua_manual_1 += $totalnya_manual_1;
                                        // }

                                        // $data_harian_manual_po1 = $koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'IPO' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) LIKE '%$dayKemarin%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                         ");
                                        // while($tampilkan_harian_manual_po1=$data_harian_manual_po1->fetch_assoc()){
                                        //     $no_sj_manual_po1 = $tampilkan_harian_manual_po1['no_sj']; 
                                        //     $totala_manual_po1 = 0;
                                        //     $sql_manual_po1 = $koneksi->query("SELECT podetail.harga, surat_jalan_manual.progres 
                                        //                         FROM surat_jalan_manual 
                                        //                         INNER JOIN podetail
                                        //                         ON podetail.idpodetail=surat_jalan_manual.idproduk 
                                        //                         WHERE surat_jalan_manual.no_sj='$no_sj_manual_po1'
                                        //                     ");
                                        //     while ($ga_manual_po1 = $sql_manual_po1->fetch_assoc()){
                                        //         $subtotal_manual_po1 = $ga_manual_po1['harga'] * $ga_manual_po1['progres'];
                                        //         $totala_manual_po1 = $totala_manual_po1 + $subtotal_manual_po1;
                                        //     }
                                        //     $diskon_manual_po1 = 35/100*$totala_manual_po1;
                                        //     $totalnya_manual_po1 = $totala_manual_po1 - $diskon_manual_po1;    
                                        //     $totalsemua_manual_po1 += $totalnya_manual_po1;   
                                        // } 

                                        // $data_harian_2 = $koneksi->query("SELECT 
                                        //                                     surat_jalan.invoice,
                                        //                                     SUM(surat_jalan.progres) as jumlah,
                                        //                                     MAX(surat_jalan.progres) as progres, -- Atau fungsi agregat lain yang sesuai
                                        //                                     surat_jalan.status,
                                        //                                     surat_jalan.waktu,
                                        //                                     surat_jalan.id_sj,
                                        //                                     surat_jalan.no_sj
                                        //                                 FROM 
                                        //                                     surat_jalan
                                        //                                 WHERE 
                                        //                                     surat_jalan.status <> 'Ambil Barang' 
                                        //                                     AND SUBSTRING(surat_jalan.waktu, 1, 10) LIKE '%$dayLusa%'
                                        //                                 GROUP BY 
                                        //                                     surat_jalan.invoice, surat_jalan.status, surat_jalan.waktu, surat_jalan.id_sj, surat_jalan.no_sj
                                        //                                 ORDER BY 
                                        //                                     surat_jalan.waktu DESC, surat_jalan.invoice;
                                        //                                 ");
                                        // while($tampilkan_harian_2=$data_harian_2->fetch_assoc()) {
                                        //     $no_sj_2 = $tampilkan_harian_2['no_sj']; 
                                        //     $totala_2 = 0;
                                        //     $sql_2 = $koneksi->query("SELECT produk.harga, surat_jalan.progres 
                                        //                 FROM surat_jalan 
                                        //                 inner join produk on produk.idproduk=surat_jalan.idproduk 
                                        //                 WHERE surat_jalan.no_sj='$no_sj_2'
                                        //             ");
                                        //     while ($ga_2 = $sql_2->fetch_assoc()){
                                        //         $subtotal_2 = $ga_2['harga'] * $ga_2['progres'];
                                        //         $totala_2 = $totala_2 + $subtotal_2;
                                        //     }
                                        //     $diskon_2 = 35/100*$totala_2;
                                        //     $totalnya_2 = $totala_2 - $diskon_2;
                                        //     $totalsemua_2 += $totalnya_2;
                                        // }

                                        // $datapo_harian_2=$koneksi->query("SELECT 
                                        //                                         surat_jalan_po.invoice,
                                        //                                         MAX(surat_jalan_po.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                         surat_jalan_po.status,
                                        //                                         surat_jalan_po.waktu,
                                        //                                         SUM(surat_jalan_po.progres) as jumlah,
                                        //                                         surat_jalan_po.id_sj,
                                        //                                         surat_jalan_po.no_sj,
                                        //                                         pomitra.idpoproduk
                                        //                                     FROM 
                                        //                                         surat_jalan_po
                                        //                                     JOIN 
                                        //                                         pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                        //                                     WHERE 
                                        //                                         surat_jalan_po.status <> 'Ambil Barang' 
                                        //                                         AND SUBSTRING(surat_jalan_po.waktu, 1, 10) LIKE '%$dayLusa%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_po.invoice,
                                        //                                         surat_jalan_po.status,
                                        //                                         surat_jalan_po.waktu,
                                        //                                         surat_jalan_po.id_sj,
                                        //                                         surat_jalan_po.no_sj,
                                        //                                         pomitra.idpoproduk
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_po.waktu DESC, 
                                        //                                         surat_jalan_po.invoice;
                                        //                                 ");
                                        // while($tampilkanpo_harian_2=$datapo_harian_2->fetch_assoc()){                                  
                                        //     $no_sj_2_po = $tampilkanpo_harian_2['no_sj']; 
                                        //     $totala_2_po = 0;
                                        //     $sql_2_po = $koneksi->query("SELECT podetail.harga, surat_jalan_po.progres 
                                        //                     FROM surat_jalan_po
                                        //                     INNER JOIN podetail 
                                        //                     ON podetail.idpodetail=surat_jalan_po.idpodetail 
                                        //                     WHERE surat_jalan_po.no_sj='$no_sj_2_po'
                                        //                 ");
                                        //     while ($ga_2_po = $sql_2_po->fetch_assoc()){
                                        //     $subtotal_2_po = $ga_2_po['harga'] * $ga_2_po['progres'];
                                        //     $totala_2_po = $totala_2_po + $subtotal_2_po;

                                        //     }
                                        //     $diskon_2_po = 35/100*$totala_2_po;
                                        //     $totalpo_nya_2 = $totala_2_po - $diskon_2_po;    

                                        //     $totalpo_semua_2 += $totalpo_nya_2;             
                                        // }

                                        // $data_harian_manual_2 = $koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres, -- Menggunakan fungsi agregat
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'INV' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) 
                                        //                                         LIKE '%$dayLusa%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice, 
                                        //                                         surat_jalan_manual.status, 
                                        //                                         surat_jalan_manual.waktu, 
                                        //                                         surat_jalan_manual.id_sj, 
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                     ");
                                        // while($tampilkan_harian_manual_2=$data_harian_manual_2->fetch_assoc()) {
                                        //     $no_sj_manual_2 = $tampilkan_harian_manual_2['no_sj'];
                                        //     $totala_manual_2 = 0;
                                        //     $sql_manual_2 = $koneksi->query("SELECT produk.harga, surat_jalan_manual.progres 
                                        //                         FROM surat_jalan_manual 
                                        //                         INNER JOIN produk
                                        //                         ON produk.idproduk = surat_jalan_manual.idproduk 
                                        //                         WHERE surat_jalan_manual.no_sj='$no_sj_manual_2'");
                                        //     while ($ga_manual_2 = $sql_manual_2->fetch_assoc()){
                                        //         $subtotal_manual_2 = $ga_manual_2['harga'] * $ga_manual_2['progres'];
                                        //         $totala_manual_2 = $totala_manual_2 + $subtotal_manual_2;
                                        //     }
                                        //     $diskon_manual_2 = 35/100*$totala_manual_2;
                                        //     $totalnya_manual_2 = $totala_manual_2 - $diskon_manual_2;    
                                        //     $totalsemua_manual_2 += $totalnya_manual_2;   
                                        // }

                                        // $data_harian_manual_po2 = $koneksi->query("SELECT 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         SUM(surat_jalan_manual.progres) as jumlah,
                                        //                                         MAX(surat_jalan_manual.progres) as progres,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     FROM 
                                        //                                         surat_jalan_manual
                                        //                                     WHERE 
                                        //                                         surat_jalan_manual.status = 'IPO' 
                                        //                                         AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) LIKE '%$dayLusa%'
                                        //                                     GROUP BY 
                                        //                                         surat_jalan_manual.invoice,
                                        //                                         surat_jalan_manual.status,
                                        //                                         surat_jalan_manual.waktu,
                                        //                                         surat_jalan_manual.id_sj,
                                        //                                         surat_jalan_manual.no_sj
                                        //                                     ORDER BY 
                                        //                                         surat_jalan_manual.waktu DESC, 
                                        //                                         surat_jalan_manual.invoice;
                                        //                                         ");
                                        // while($tampilkan_harian_manual_po2=$data_harian_manual_po2->fetch_assoc()) {
                                        //     $no_sj_manual_po2 = $tampilkan_harian_manual_po2['no_sj'];
                                        //     $totala_manual_po2 = 0;
                                        //     $sql_manual_po2 = $koneksi->query("SELECT podetail.harga, surat_jalan_manual.progres
                                        //                         FROM surat_jalan_manual
                                        //                         INNER JOIN podetail
                                        //                         ON podetail.idpodetail=surat_jalan_manual.idproduk
                                        //                         WHERE surat_jalan_manual.no_sj='$no_sj_manual_po2'");
                                        //     while ($ga_manual_po2 = $sql_manual_po2->fetch_assoc()){
                                        //         $subtotal_manual_po2 = $ga_manual_po2['harga'] * $ga_manual_po2['progres'];
                                        //         $totala_manual_po2 = $totala_manual_po2 + $subtotal_manual_po2;
                                        //     }
                                        //     $diskon_manual_po2 = 35/100*$totala_manual_po2;
                                        //     $totalnya_manual_po2 = $totala_manual_po2 - $diskon_manual_po2;
                                        //     $totalsemua_manual_po2 += $totalnya_manual_po2;
                                        // }
                                    ?>