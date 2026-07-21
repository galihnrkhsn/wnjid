<?php
    session_start();

    include 'koneksi.php';

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
        echo "
            <script>alert('Anda harus login terlebih dahulu!');</script>
            <script>location='login-multi.php';</script>
        ";
        header("Location: login-multi.php");
        exit();
    }

    $id         = $_SESSION['user_id'];
    $role       = $_SESSION['user_level'];

    $query      = $koneksi->query("SELECT * FROM user_manajemen INNER JOIN role WHERE user_manajemen.id = '$id'");
    $user       = $query->fetch_assoc();
    $username   = $user['username'];


    date_default_timezone_set('Asia/Jakarta');
    $dateNow    = date("Y-m-d");
    $dayKemarin = date( 'Y-m-d', strtotime( $dateNow . ' -1 day' ) );
    $dayLusa    = date( 'Y-m-d', strtotime( $dateNow . ' -2 day' ) );

    function convertToIndonesianDate($date) {
        // Create a timestamp from the provided date
        $timestamp = strtotime($date);

        // Define arrays for Indonesian days and months
        $days   = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // Get the day of the week and month
        $day        = $days[date('w', $timestamp)];
        $day_number = date('d', $timestamp);
        $month      = $months[date('n', $timestamp) - 1];
        $year       = date('Y', $timestamp);

        // Return the formatted date
        return "$day, $day_number $month $year";
    }

    $day        = convertToIndonesianDate($dateNow);
    $yesterday  = convertToIndonesianDate($dayKemarin);
    $days       = convertToIndonesianDate($dayLusa);

    $getRek     = $koneksi->query("SELECT SUM(kredit) - SUM(debit) as sisa FROM rekeningkoran WHERE tipe = '$userTipe'");
    $queryRek   = $getRek->fetch_assoc();

    $ambilp     = $koneksi->query("SELECT sum(kredit) - sum(debit) as sisa FROM rekeningkoran WHERE  tipe='P'"); 
    $datap      = $ambilp->fetch_assoc();

    $ambilaf    = $koneksi->query("SELECT sum(kredit) - sum(debit) as sisa FROM rekeningkoran WHERE  tipe='AF'"); 
    $dataaf     = $ambilaf->fetch_assoc();

    $ambilpm    = $koneksi->query("SELECT sum(kredit) - sum(debit) as sisa FROM rekeningkoran WHERE  tipe='PM'"); 
    $datapm     = $ambilpm->fetch_assoc();

    $ambilm     = $koneksi->query("SELECT sum(kredit) - sum(debit) as sisa FROM rekeningkoran WHERE  tipe='M'"); 
    $datam      = $ambilm->fetch_assoc();

    $ambil_cash     = $koneksi->query("SELECT (bca+bni+bri+bsi+mandiri+muamalat) as total, waktu FROM rekeningbank ORDER BY id DESC LIMIT 1"); 
    $tampil_cash    = $ambil_cash->fetch_assoc();

    $ambil_aset     = $koneksi->query("SELECT sum(nilai) as total FROM aset"); 
    $tampil_aset    = $ambil_aset->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wanoja | <?= $username ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="template/images/logo.png">
    <link rel="stylesheet" href="template/vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="template/vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="template/vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="template/css/style.css" rel="stylesheet">
    <link href="template/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <style>
        .dataTables_wrapper .dataTables_scroll {
            padding: 0;
        }
    </style>
</head>
<body>

    <div id="main-wrapper">
        <!--**********************************
            Nav header start
        ***********************************-->
        <?php include 'template/component/humberger.php'; ?>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <?php include 'template/component/header.php'; ?>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <?php include 'template/component/sidebar.php'; ?>
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-12 p-md-0">
                        <div class="welcome-text">
                            <h4>Hi, welcome back <?= $username ?>!</h4>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body pb-1">
                        <div id="accordion-seven" class="accordion accordion-header-shadow accordion-bordered">
                            <div class="row">
                                <?php if ($role == '1') : ?>
                                    <div class="col-lg-6">
                                        <div class="accordion__item">
                                            <div class="accordion__header collapsed" data-toggle="collapse" data-target="#header-bg_collapseOne">
                                                <span class="accordion__header--icon"></span>
                                                <span class="accordion__header--text">Kas Kecil</span>
                                                <span class="accordion__header--indicator"></span>
                                            </div>
                                            <div id="header-bg_collapseOne" class="collapse accordion__body" data-parent="#accordion-seven">
                                                <div class="accordion__body--text">
                                                    <div class="d-flex flex-column">
                                                        <div class="py-2">
                                                            <a href="finance.php?tipe=P" class="h4">Kas Produksi</a>
                                                            <p class="mb-0">Rp. <?= number_format($datap['sisa']); ?></p>
                                                        </div>
                                                        <div class="border-top border-2 py-2">
                                                            <a href="" class="h4">Kas Admin</a>
                                                            <p class="mb-0">Rp. <?= number_format($dataaf['sisa']); ?></p>
                                                        </div>
                                                        <div class="border-top border-2 py-2">
                                                            <a href="" class="h4">Kas Manajemen</a>
                                                            <p class="mb-0">Rp. <?= number_format($datam['sisa']); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="accordion__item">
                                            <div class="accordion__header collapsed" data-toggle="collapse" data-target="#penjualan-wnj">
                                                <span class="accordion__header--icon"></span>
                                                <span class="accordion__header--text">Penjualan Wanoja</span>
                                                <span class="accordion__header--indicator"></span>
                                            </div>
                                            <?php 
                                                $getHarian = $koneksi->query("SELECT 
                                                                                    surat_jalan.invoice,
                                                                                    SUM(surat_jalan.progres) as jumlah,
                                                                                    MAX(surat_jalan.progres) as progres, -- Atau fungsi agregat lain yang sesuai
                                                                                    surat_jalan.status,
                                                                                    surat_jalan.waktu,
                                                                                    surat_jalan.id_sj,
                                                                                    surat_jalan.no_sj
                                                                                FROM 
                                                                                    surat_jalan
                                                                                WHERE 
                                                                                    surat_jalan.status <> 'Ambil Barang' 
                                                                                    AND SUBSTRING(surat_jalan.waktu, 1, 10) LIKE '%$dateNow%'
                                                                                GROUP BY 
                                                                                    surat_jalan.invoice, surat_jalan.status, surat_jalan.waktu, surat_jalan.id_sj, surat_jalan.no_sj
                                                                                ORDER BY 
                                                                                    surat_jalan.waktu DESC, surat_jalan.invoice;
                                                                            ");
                                                while($queryHarian = $getHarian->fetch_assoc()){
                                                    $no_sj = $queryHarian['no_sj'];
                                                    $totala = 0;
                                                    $getHarga = $koneksi->query("SELECT produk.harga, surat_jalan.progres 
                                                                FROM surat_jalan 
                                                                INNER JOIN produk
                                                                ON produk.idproduk=surat_jalan.idproduk 
                                                                WHERE surat_jalan.no_sj='$no_sj'");
                                                    while ($queryHarga = $getHarga->fetch_assoc()) {
                                                        $subtotal = $queryHarga['harga'] * $queryHarga['progres'];
                                                        $totala = $totala + $subtotal;
                                                    };

                                                    $diskon = 35/100*$totala;
                                                    $totalnya = $totala - $diskon;
                                                    $totalsemua += $totalnya;
                                                }

                                                $getPoHarian = $koneksi->query("SELECT 
                                                                                    surat_jalan_po.invoice,
                                                                                    MAX(surat_jalan_po.progres) as progres, -- Menggunakan fungsi agregat
                                                                                    surat_jalan_po.status,
                                                                                    surat_jalan_po.waktu,
                                                                                    SUM(surat_jalan_po.progres) as jumlah,
                                                                                    surat_jalan_po.id_sj,
                                                                                    surat_jalan_po.no_sj,
                                                                                    pomitra.idpoproduk
                                                                                FROM 
                                                                                    surat_jalan_po
                                                                                JOIN 
                                                                                    pomitra 
                                                                                ON 
                                                                                    pomitra.idpomitra = surat_jalan_po.idpomitra
                                                                                WHERE 
                                                                                    surat_jalan_po.status <> 'Ambil Barang' 
                                                                                    AND SUBSTRING(surat_jalan_po.waktu, 1, 10) LIKE '$dateNow'
                                                                                GROUP BY 
                                                                                    surat_jalan_po.invoice,
                                                                                    surat_jalan_po.status,
                                                                                    surat_jalan_po.waktu,
                                                                                    surat_jalan_po.id_sj,
                                                                                    surat_jalan_po.no_sj,
                                                                                    pomitra.idpoproduk
                                                                                ORDER BY 
                                                                                    surat_jalan_po.waktu DESC, surat_jalan_po.invoice");
                                                while($queryPoHarian = $getPoHarian->fetch_assoc()) {
                                                    $no_sj_po = $queryPoHarian['no_sj']; 
                                                    $totala_po = 0;
                                                    $getPo = $koneksi->query("SELECT podetail.harga, surat_jalan_po.progres 
                                                        FROM surat_jalan_po 
                                                        inner join podetail on podetail.idpodetail=surat_jalan_po.idpodetail
                                                        WHERE surat_jalan_po.no_sj='$no_sj_po'");
                                                    while ($queryHargaPo = $getPo->fetch_assoc()) {
                                                        $subtotalPo = $queryHargaPo['harga'] * $queryHargaPo['progres'];
                                                        $totala_po = $totala_po + $subtotalPo;
                                                    }

                                                    $diskon_po = 35/100*$totala_po;
                                                    $totalpo_nya = $totala_po - $diskon_po;
                                                    $totalpo_semua += $totalpo_nya;
                                                }

                                                $getharianManual=$koneksi->query("SELECT 
                                                                                        surat_jalan_manual.invoice,
                                                                                        SUM(surat_jalan_manual.progres) as jumlah,
                                                                                        MAX(surat_jalan_manual.progres) as progres, -- Menggunakan fungsi agregat
                                                                                        surat_jalan_manual.status,
                                                                                        surat_jalan_manual.waktu,
                                                                                        surat_jalan_manual.id_sj,
                                                                                        surat_jalan_manual.no_sj
                                                                                    FROM 
                                                                                        surat_jalan_manual
                                                                                    WHERE 
                                                                                        surat_jalan_manual.status = 'INV' 
                                                                                        AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) 
                                                                                        LIKE '%$dateNow%'
                                                                                    GROUP BY 
                                                                                        surat_jalan_manual.invoice, 
                                                                                        surat_jalan_manual.status, 
                                                                                        surat_jalan_manual.waktu, 
                                                                                        surat_jalan_manual.id_sj, 
                                                                                        surat_jalan_manual.no_sj
                                                                                    ORDER BY 
                                                                                        surat_jalan_manual.waktu DESC, 
                                                                                        surat_jalan_manual.invoice;
                                                                                    ");
                                                while($queryHarianManual=$getharianManual->fetch_assoc()) {
                                                    $no_sj_manual = $queryHarianManual['no_sj']; 
                                                    $totala_manual = 0;
                                                    $getManual = $koneksi->query("SELECT produk.harga, surat_jalan_manual.progres 
                                                                    FROM surat_jalan_manual 
                                                                    INNER JOIN produk ON produk.idproduk=surat_jalan_manual.idproduk 
                                                                    WHERE surat_jalan_manual.no_sj='$no_sj_manual'");
                                                    while ($ga_manual = $getManual->fetch_assoc()){
                                                        $subtotal_manual = $ga_manual['harga'] * $ga_manual['progres'];
                                                        $totala_manual = $totala_manual + $subtotal_manual;
                                                    }

                                                    $diskon_manual = 35/100*$totala_manual;
                                                    $totalnya_manual = $totala_manual - $diskon_manual;    
                                                    $totalsemua_manual += $totalnya_manual;
                                                }    

                                                $getManualPo=$koneksi->query("SELECT 
                                                                                        surat_jalan_manual.invoice,
                                                                                        SUM(surat_jalan_manual.progres) as jumlah,
                                                                                        MAX(surat_jalan_manual.progres) as progres,
                                                                                        surat_jalan_manual.status,
                                                                                        surat_jalan_manual.waktu,
                                                                                        surat_jalan_manual.id_sj,
                                                                                        surat_jalan_manual.no_sj
                                                                                    FROM 
                                                                                        surat_jalan_manual
                                                                                    WHERE 
                                                                                        surat_jalan_manual.status = 'IPO' 
                                                                                        AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) LIKE '%$dataNow%'
                                                                                    GROUP BY 
                                                                                        surat_jalan_manual.invoice,
                                                                                        surat_jalan_manual.status,
                                                                                        surat_jalan_manual.waktu,
                                                                                        surat_jalan_manual.id_sj,
                                                                                        surat_jalan_manual.no_sj
                                                                                    ORDER BY 
                                                                                        surat_jalan_manual.waktu DESC, 
                                                                                        surat_jalan_manual.invoice;
                                                                                ");
                                                while($queryManualPo=$getManualPo->fetch_assoc()) {
                                                    $no_sj_manual_po = $queryManualPo['no_sj']; 
                                                    $totala_manual_po = 0;

                                                    $getManual_po = $koneksi->query("SELECT podetail.harga, surat_jalan_manual.progres 
                                                                        FROM surat_jalan_manual 
                                                                        inner join podetail on podetail.idpodetail=surat_jalan_manual.idproduk 
                                                                        WHERE surat_jalan_manual.no_sj='$no_sj_manual_po'");

                                                    while ($ga_manual_po = $getManual_po->fetch_assoc()){
                                                        $subtotal_manual_po = $ga_manual_po['harga'] * $ga_manual_po['progres'];
                                                        $totala_manual_po = $totala_manual_po + $subtotal_manual_po;
                                                    }

                                                    $diskon_manual_po = 35/100*$totala_manual_po;
                                                    $totalnya_manual_po = $totala_manual_po - $diskon_manual_po;
                                                    $totalsemua_manual_po += $totalnya_manual_po;
                                                } 

                                                $data_harian_1 = $koneksi->query("SELECT 
                                                                                    surat_jalan.invoice,
                                                                                    SUM(surat_jalan.progres) as jumlah,
                                                                                    MAX(surat_jalan.progres) as progres, -- Atau fungsi agregat lain yang sesuai
                                                                                    surat_jalan.status,
                                                                                    surat_jalan.waktu,
                                                                                    surat_jalan.id_sj,
                                                                                    surat_jalan.no_sj
                                                                                FROM 
                                                                                    surat_jalan
                                                                                WHERE 
                                                                                    surat_jalan.status <> 'Ambil Barang' 
                                                                                    AND SUBSTRING(surat_jalan.waktu, 1, 10) LIKE '%$dayKemarin%'
                                                                                GROUP BY 
                                                                                    surat_jalan.invoice, surat_jalan.status, surat_jalan.waktu, surat_jalan.id_sj, surat_jalan.no_sj
                                                                                ORDER BY 
                                                                                    surat_jalan.waktu DESC, surat_jalan.invoice;
                                                                                ");
                                                while($tampilkan_harian_1=$data_harian_1->fetch_assoc()) {
                                                    $no_sj_1 = $tampilkan_harian_1['no_sj'];
                                                    $totala_1 = 0;
                                                    $sql_1 = $koneksi->query("SELECT produk.harga, surat_jalan.progres
                                                                                FROM surat_jalan
                                                                                inner join produk on produk.idproduk=surat_jalan.idproduk 
                                                                                WHERE surat_jalan.no_sj='$no_sj_1' ");
                                                    while ($ga_1 = $sql_1->fetch_assoc()){
                                                        $subtotal_1 = $ga_1['harga'] * $ga_1['progres'];
                                                        $totala_1 = $totala_1 + $subtotal_1;
                                                    }
                                                    $diskon_1 = 35/100*$totala_1;
                                                    $totalnya_1 = $totala_1 - $diskon_1;
                                                    $totalsemua_1 += $totalnya_1;
                                                }

                                                $datapo_harian_1=$koneksi->query("SELECT 
                                                                                        surat_jalan_po.invoice,
                                                                                        MAX(surat_jalan_po.progres) as progres, -- Menggunakan fungsi agregat
                                                                                        surat_jalan_po.status,
                                                                                        surat_jalan_po.waktu,
                                                                                        SUM(surat_jalan_po.progres) as jumlah,
                                                                                        surat_jalan_po.id_sj,
                                                                                        surat_jalan_po.no_sj,
                                                                                        pomitra.idpoproduk
                                                                                    FROM 
                                                                                        surat_jalan_po
                                                                                    JOIN 
                                                                                        pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                                                    WHERE 
                                                                                        surat_jalan_po.status <> 'Ambil Barang' 
                                                                                        AND SUBSTRING(surat_jalan_po.waktu, 1, 10) LIKE '%$dayKemarin%'
                                                                                    GROUP BY 
                                                                                        surat_jalan_po.invoice,
                                                                                        surat_jalan_po.status,
                                                                                        surat_jalan_po.waktu,
                                                                                        surat_jalan_po.id_sj,
                                                                                        surat_jalan_po.no_sj,
                                                                                        pomitra.idpoproduk
                                                                                    ORDER BY 
                                                                                        surat_jalan_po.waktu DESC, 
                                                                                        surat_jalan_po.invoice;
                                                                                ");
                                                while($tampilkanpo_harian_1=$datapo_harian_1->fetch_assoc()) {
                                                    $no_sj_1_po = $tampilkanpo_harian_1['no_sj']; 
                                                    $totala_1_po = 0;
                                                    $query_1_po = $koneksi->query("SELECT podetail.harga, surat_jalan_po.progres
                                                                    FROM surat_jalan_po
                                                                    INNER JOIN podetail 
                                                                    ON podetail.idpodetail=surat_jalan_po.idpodetail 
                                                                    WHERE surat_jalan_po.no_sj='$no_sj_1_po'
                                                                ");
                                                    while ($ga_1_po = $query_1_po->fetch_assoc()) {
                                                        $subtotal_1_po = $ga_1_po['harga'] * $ga_1_po['progres'];
                                                        $totala_1_po = $totala_1_po + $subtotal_1_po;
                                                    }

                                                    $diskon_1_po = 35/100*$totala_1_po;
                                                    $totalpo_nya_1 = $totala_1_po - $diskon_1_po;
                                                    $totalpo_semua_1 += $totalpo_nya_1;
                                                }

                                                $data_harian_manual_1 = $koneksi->query("SELECT 
                                                                                        surat_jalan_manual.invoice,
                                                                                        SUM(surat_jalan_manual.progres) as jumlah,
                                                                                        MAX(surat_jalan_manual.progres) as progres, -- Menggunakan fungsi agregat
                                                                                        surat_jalan_manual.status,
                                                                                        surat_jalan_manual.waktu,
                                                                                        surat_jalan_manual.id_sj,
                                                                                        surat_jalan_manual.no_sj
                                                                                    FROM 
                                                                                        surat_jalan_manual
                                                                                    WHERE 
                                                                                        surat_jalan_manual.status = 'INV' 
                                                                                        AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) 
                                                                                        LIKE '%$dayKemarin%'
                                                                                    GROUP BY 
                                                                                        surat_jalan_manual.invoice, 
                                                                                        surat_jalan_manual.status, 
                                                                                        surat_jalan_manual.waktu, 
                                                                                        surat_jalan_manual.id_sj, 
                                                                                        surat_jalan_manual.no_sj
                                                                                    ORDER BY 
                                                                                        surat_jalan_manual.waktu DESC, 
                                                                                        surat_jalan_manual.invoice;
                                                                                        ");
                                                while($tampilkan_harian_manual_1=$data_harian_manual_1->fetch_assoc()){
                                                    $no_sj_manual_1 = $tampilkan_harian_manual_1['no_sj'];
                                                    $totala_manual_1 = 0;
                                                    $sql_manual_1 = $koneksi->query("SELECT produk.harga, surat_jalan_manual.progres 
                                                                        FROM surat_jalan_manual 
                                                                        INNER JOIN produk 
                                                                        ON produk.idproduk=surat_jalan_manual.idproduk 
                                                                        WHERE surat_jalan_manual.no_sj='$no_sj_manual_1'
                                                                    ");
                                                    while ($ga_manual_1 = $sql_manual_1->fetch_assoc()){
                                                        $subtotal_manual_1 = $ga_manual_1['harga'] * $ga_manual_1['progres'];
                                                        $totala_manual_1 = $totala_manual_1 + $subtotal_manual_1;
                                                    }

                                                    $diskon_manual_1 = 35/100*$totala_manual_1;
                                                    $totalnya_manual_1 = $totala_manual_1 - $diskon_manual_1;
                                                    $totalsemua_manual_1 += $totalnya_manual_1;
                                                }

                                                $data_harian_manual_po1 = $koneksi->query("SELECT 
                                                                                        surat_jalan_manual.invoice,
                                                                                        SUM(surat_jalan_manual.progres) as jumlah,
                                                                                        MAX(surat_jalan_manual.progres) as progres,
                                                                                        surat_jalan_manual.status,
                                                                                        surat_jalan_manual.waktu,
                                                                                        surat_jalan_manual.id_sj,
                                                                                        surat_jalan_manual.no_sj
                                                                                    FROM 
                                                                                        surat_jalan_manual
                                                                                    WHERE 
                                                                                        surat_jalan_manual.status = 'IPO' 
                                                                                        AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) LIKE '%$dayKemarin%'
                                                                                    GROUP BY 
                                                                                        surat_jalan_manual.invoice,
                                                                                        surat_jalan_manual.status,
                                                                                        surat_jalan_manual.waktu,
                                                                                        surat_jalan_manual.id_sj,
                                                                                        surat_jalan_manual.no_sj
                                                                                    ORDER BY 
                                                                                        surat_jalan_manual.waktu DESC, 
                                                                                        surat_jalan_manual.invoice;
                                                                                        ");
                                                while($tampilkan_harian_manual_po1=$data_harian_manual_po1->fetch_assoc()){
                                                    $no_sj_manual_po1 = $tampilkan_harian_manual_po1['no_sj']; 
                                                    $totala_manual_po1 = 0;
                                                    $sql_manual_po1 = $koneksi->query("SELECT podetail.harga, surat_jalan_manual.progres 
                                                                        FROM surat_jalan_manual 
                                                                        INNER JOIN podetail
                                                                        ON podetail.idpodetail=surat_jalan_manual.idproduk 
                                                                        WHERE surat_jalan_manual.no_sj='$no_sj_manual_po1'
                                                                    ");
                                                    while ($ga_manual_po1 = $sql_manual_po1->fetch_assoc()){
                                                        $subtotal_manual_po1 = $ga_manual_po1['harga'] * $ga_manual_po1['progres'];
                                                        $totala_manual_po1 = $totala_manual_po1 + $subtotal_manual_po1;
                                                    }
                                                    $diskon_manual_po1 = 35/100*$totala_manual_po1;
                                                    $totalnya_manual_po1 = $totala_manual_po1 - $diskon_manual_po1;    
                                                    $totalsemua_manual_po1 += $totalnya_manual_po1;   
                                                } 

                                                $data_harian_2 = $koneksi->query("SELECT 
                                                                                    surat_jalan.invoice,
                                                                                    SUM(surat_jalan.progres) as jumlah,
                                                                                    MAX(surat_jalan.progres) as progres, -- Atau fungsi agregat lain yang sesuai
                                                                                    surat_jalan.status,
                                                                                    surat_jalan.waktu,
                                                                                    surat_jalan.id_sj,
                                                                                    surat_jalan.no_sj
                                                                                FROM 
                                                                                    surat_jalan
                                                                                WHERE 
                                                                                    surat_jalan.status <> 'Ambil Barang' 
                                                                                    AND SUBSTRING(surat_jalan.waktu, 1, 10) LIKE '%$dayLusa%'
                                                                                GROUP BY 
                                                                                    surat_jalan.invoice, surat_jalan.status, surat_jalan.waktu, surat_jalan.id_sj, surat_jalan.no_sj
                                                                                ORDER BY 
                                                                                    surat_jalan.waktu DESC, surat_jalan.invoice;
                                                                                ");
                                                while($tampilkan_harian_2=$data_harian_2->fetch_assoc()) {
                                                    $no_sj_2 = $tampilkan_harian_2['no_sj']; 
                                                    $totala_2 = 0;
                                                    $sql_2 = $koneksi->query("SELECT produk.harga, surat_jalan.progres 
                                                                FROM surat_jalan 
                                                                inner join produk on produk.idproduk=surat_jalan.idproduk 
                                                                WHERE surat_jalan.no_sj='$no_sj_2'
                                                            ");
                                                    while ($ga_2 = $sql_2->fetch_assoc()){
                                                        $subtotal_2 = $ga_2['harga'] * $ga_2['progres'];
                                                        $totala_2 = $totala_2 + $subtotal_2;
                                                    }
                                                    $diskon_2 = 35/100*$totala_2;
                                                    $totalnya_2 = $totala_2 - $diskon_2;
                                                    $totalsemua_2 += $totalnya_2;
                                                }

                                                $datapo_harian_2=$koneksi->query("SELECT 
                                                                                        surat_jalan_po.invoice,
                                                                                        MAX(surat_jalan_po.progres) as progres, -- Menggunakan fungsi agregat
                                                                                        surat_jalan_po.status,
                                                                                        surat_jalan_po.waktu,
                                                                                        SUM(surat_jalan_po.progres) as jumlah,
                                                                                        surat_jalan_po.id_sj,
                                                                                        surat_jalan_po.no_sj,
                                                                                        pomitra.idpoproduk
                                                                                    FROM 
                                                                                        surat_jalan_po
                                                                                    JOIN 
                                                                                        pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                                                    WHERE 
                                                                                        surat_jalan_po.status <> 'Ambil Barang' 
                                                                                        AND SUBSTRING(surat_jalan_po.waktu, 1, 10) LIKE '%$dayLusa%'
                                                                                    GROUP BY 
                                                                                        surat_jalan_po.invoice,
                                                                                        surat_jalan_po.status,
                                                                                        surat_jalan_po.waktu,
                                                                                        surat_jalan_po.id_sj,
                                                                                        surat_jalan_po.no_sj,
                                                                                        pomitra.idpoproduk
                                                                                    ORDER BY 
                                                                                        surat_jalan_po.waktu DESC, 
                                                                                        surat_jalan_po.invoice;
                                                                                ");
                                                while($tampilkanpo_harian_2=$datapo_harian_2->fetch_assoc()){                                  
                                                    $no_sj_2_po = $tampilkanpo_harian_2['no_sj']; 
                                                    $totala_2_po = 0;
                                                    $sql_2_po = $koneksi->query("SELECT podetail.harga, surat_jalan_po.progres 
                                                                    FROM surat_jalan_po
                                                                    INNER JOIN podetail 
                                                                    ON podetail.idpodetail=surat_jalan_po.idpodetail 
                                                                    WHERE surat_jalan_po.no_sj='$no_sj_2_po'
                                                                ");
                                                    while ($ga_2_po = $sql_2_po->fetch_assoc()){
                                                    $subtotal_2_po = $ga_2_po['harga'] * $ga_2_po['progres'];
                                                    $totala_2_po = $totala_2_po + $subtotal_2_po;

                                                    }
                                                    $diskon_2_po = 35/100*$totala_2_po;
                                                    $totalpo_nya_2 = $totala_2_po - $diskon_2_po;    

                                                    $totalpo_semua_2 += $totalpo_nya_2;             
                                                }

                                                $data_harian_manual_2 = $koneksi->query("SELECT 
                                                                                        surat_jalan_manual.invoice,
                                                                                        SUM(surat_jalan_manual.progres) as jumlah,
                                                                                        MAX(surat_jalan_manual.progres) as progres, -- Menggunakan fungsi agregat
                                                                                        surat_jalan_manual.status,
                                                                                        surat_jalan_manual.waktu,
                                                                                        surat_jalan_manual.id_sj,
                                                                                        surat_jalan_manual.no_sj
                                                                                    FROM 
                                                                                        surat_jalan_manual
                                                                                    WHERE 
                                                                                        surat_jalan_manual.status = 'INV' 
                                                                                        AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) 
                                                                                        LIKE '%$dayLusa%'
                                                                                    GROUP BY 
                                                                                        surat_jalan_manual.invoice, 
                                                                                        surat_jalan_manual.status, 
                                                                                        surat_jalan_manual.waktu, 
                                                                                        surat_jalan_manual.id_sj, 
                                                                                        surat_jalan_manual.no_sj
                                                                                    ORDER BY 
                                                                                        surat_jalan_manual.waktu DESC, 
                                                                                        surat_jalan_manual.invoice;
                                                                                    ");
                                                while($tampilkan_harian_manual_2=$data_harian_manual_2->fetch_assoc()) {
                                                    $no_sj_manual_2 = $tampilkan_harian_manual_2['no_sj'];
                                                    $totala_manual_2 = 0;
                                                    $sql_manual_2 = $koneksi->query("SELECT produk.harga, surat_jalan_manual.progres 
                                                                        FROM surat_jalan_manual 
                                                                        INNER JOIN produk
                                                                        ON produk.idproduk = surat_jalan_manual.idproduk 
                                                                        WHERE surat_jalan_manual.no_sj='$no_sj_manual_2'");
                                                    while ($ga_manual_2 = $sql_manual_2->fetch_assoc()){
                                                        $subtotal_manual_2 = $ga_manual_2['harga'] * $ga_manual_2['progres'];
                                                        $totala_manual_2 = $totala_manual_2 + $subtotal_manual_2;
                                                    }
                                                    $diskon_manual_2 = 35/100*$totala_manual_2;
                                                    $totalnya_manual_2 = $totala_manual_2 - $diskon_manual_2;    
                                                    $totalsemua_manual_2 += $totalnya_manual_2;   
                                                }

                                                $data_harian_manual_po2 = $koneksi->query("SELECT 
                                                                                        surat_jalan_manual.invoice,
                                                                                        SUM(surat_jalan_manual.progres) as jumlah,
                                                                                        MAX(surat_jalan_manual.progres) as progres,
                                                                                        surat_jalan_manual.status,
                                                                                        surat_jalan_manual.waktu,
                                                                                        surat_jalan_manual.id_sj,
                                                                                        surat_jalan_manual.no_sj
                                                                                    FROM 
                                                                                        surat_jalan_manual
                                                                                    WHERE 
                                                                                        surat_jalan_manual.status = 'IPO' 
                                                                                        AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) LIKE '%$dayLusa%'
                                                                                    GROUP BY 
                                                                                        surat_jalan_manual.invoice,
                                                                                        surat_jalan_manual.status,
                                                                                        surat_jalan_manual.waktu,
                                                                                        surat_jalan_manual.id_sj,
                                                                                        surat_jalan_manual.no_sj
                                                                                    ORDER BY 
                                                                                        surat_jalan_manual.waktu DESC, 
                                                                                        surat_jalan_manual.invoice;
                                                                                        ");
                                                while($tampilkan_harian_manual_po2=$data_harian_manual_po2->fetch_assoc()) {
                                                    $no_sj_manual_po2 = $tampilkan_harian_manual_po2['no_sj'];
                                                    $totala_manual_po2 = 0;
                                                    $sql_manual_po2 = $koneksi->query("SELECT podetail.harga, surat_jalan_manual.progres
                                                                        FROM surat_jalan_manual
                                                                        INNER JOIN podetail
                                                                        ON podetail.idpodetail=surat_jalan_manual.idproduk
                                                                        WHERE surat_jalan_manual.no_sj='$no_sj_manual_po2'");
                                                    while ($ga_manual_po2 = $sql_manual_po2->fetch_assoc()){
                                                        $subtotal_manual_po2 = $ga_manual_po2['harga'] * $ga_manual_po2['progres'];
                                                        $totala_manual_po2 = $totala_manual_po2 + $subtotal_manual_po2;
                                                    }
                                                    $diskon_manual_po2 = 35/100*$totala_manual_po2;
                                                    $totalnya_manual_po2 = $totala_manual_po2 - $diskon_manual_po2;
                                                    $totalsemua_manual_po2 += $totalnya_manual_po2;
                                                }
                                            ?>
                                            <div id="penjualan-wnj" class="collapse accordion__body" data-parent="#accordion-seven">
                                                <div class="accordion__body--text">
                                                    <div class="bootstrap-carousel">
                                                        <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                                                            <div class="carousel-inner text-center">
                                                                <div class="carousel-item pt-4 px-3 active">
                                                                    <h6>Penjualan Hari <?= $day ?></h6>
                                                                    <p class="text-dark">Rp. <?= number_format($total_harian = $totalsemua + $totalpo_semua + $totalsemua_manual + $totalsemua_manual_po); ?></p>
                                                                </div>
                                                                <div class="carousel-item pt-4 px-3">
                                                                    <h6>Penjualan Kemarin Hari <?= $yesterday ?></h6>
                                                                    <p class="text-dark">Rp. <?= number_format($total_harian_1 = $totalsemua_1 + $totalpo_semua_1 + $totalsemua_manual_1 + $totalsemua_manual_po_1); ?></p>
                                                                </div>
                                                                <div class="carousel-item pt-4 px-3">
                                                                    <h6>Penjualan Kemarin Lusa <?= $days ?></h6>
                                                                    <p class="text-dark">Rp. <?= number_format($total_harian_2 = $totalsemua_2 + $totalpo_semua_2 + $totalsemua_manual_2 + $totalsemua_manual_po2); ?></p>
                                                                </div>
                                                            </div>

                                                            <a class="carousel-control-prev" href="#carouselExampleIndicators2" data-slide="prev">
                                                                <span class="carousel-control-prev-icon"></span>
                                                                <span class="sr-only">Previous</span>
                                                            </a>
                                                            <a class="carousel-control-next" href="#carouselExampleIndicators2" data-slide="next">
                                                                <span class="carousel-control-next-icon"></span>
                                                                <span class="sr-only">Next</span>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <hr />

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div>
                                                                <a href="" class="text-primary font-weight-medium">Ready Stock</a>
                                                                <p class="mb-0">Rp. <?= number_format($totalsemua) ?></p>
                                                            </div>
                                                            
                                                            <div class="mt-1">
                                                                <a href="" class="text-primary font-weight-medium">Ready Stock Manual</a>
                                                                <p class="mb-0">Rp. <?= number_format($totalsemua_manual) ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div>
                                                                <a href="" class="text-primary font-weight-medium">Pre-Order</a>
                                                                <p class="mb-0">Rp. <?= number_format($totalpo_semua) ?></p>
                                                            </div>
                                                            
                                                            <div class="mt-1">
                                                                <a href="" class="text-primary font-weight-medium">Pre-Order Manual</a>
                                                                <p class="mb-0">Rp. <?= number_format($totalsemua_manual_po) ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="accordion__item">
                                            <div class="accordion__header collapsed" data-toggle="collapse" data-target="#aset">
                                                <span class="accordion__header--icon"></span>
                                                <span class="accordion__header--text">Aset</span>
                                                <span class="accordion__header--indicator"></span>
                                            </div>

                                            <div id="aset" class="collapse accordion__body" data-parent="#accordion-seven">
                                                <div class="accordion__body--text">
                                                    <?php
                                                        $ambil_stock = $koneksi->query("SELECT 
                                                                                                stock, harga
                                                                                            FROM
                                                                                                produk
                                                                                            WHERE
                                                                                                stock > 0 AND harga > 0
                                                                                                    AND idkategori > 0
                                                                                                    AND status = 0
                                                                                            ORDER BY idproduk DESC
                                                                                        ");
                                                        while($tampil_stock=$ambil_stock->fetch_assoc()) {
                                                            $hpp = $tampil_stock['harga']/2;
                                                            $total = $tampil_stock['stock']*$hpp;
                                                            $total_inventory += $total;
                                                        }
                                                    ?>

                                                    <div>
                                                        <h5 class="text-center">Total Aset</h5>
                                                        <p class="text-center font-weight-medium mb-0">Rp. <?= number_format($total_aset = $total_inventory + $tampil_cash["total"] + $tampil_aset["total"]) ?></p>
                                                    </div>

                                                    <hr />

                                                    <div>
                                                        <a href="" class="text-primary font-weight-medium">Inventory Stock</a>
                                                        <p class="mb-0">Rp. <?= number_format($total_inventory) ?></p>
                                                    </div>

                                                    <hr />

                                                    <div>
                                                        <a href="" class="text-primary font-weight-medium">Cash Bank</a>
                                                        <p class="mb-0 text-danger" style="font-size: .7rem"><?= $tampil_cash['waktu']; ?></p>
                                                        <p class="mb-0">Rp. <?= number_format($tampil_cash['total']); ?></p>
                                                    </div>

                                                    <hr />

                                                    <div>
                                                        <a href="" class="text-primary font-weight-medium">Aset Tetap</a>
                                                        <p class="mb-0">Rp. <?= number_format($tampil_aset["total"]); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="accordion__item">
                                            <div class="accordion__header collapsed" data-toggle="collapse" data-target="#po">
                                                <span class="accordion__header--icon"></span>
                                                <span class="accordion__header--text">Pre Order WNJ</span>
                                                <span class="accordion__header--indicator"></span>
                                            </div>

                                            <div id="po" class="collapse accordion__body show" data-parent="#accordion-seven">
                                                <div class="accordion__body--text">
                                                    <div class="table-responsive my-3">
                                                        <table class="display" id="example2">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Nama Pre Order</th>
                                                                    <th>Jumlah PO</th>
                                                                    <th>Periode Order</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    $datapo = $koneksi->query("SELECT 
                                                                                                    poproduk.idpoproduk,
                                                                                                    poproduk.namapo,
                                                                                                    poproduk.status,
                                                                                                    poproduk.tglselesai,
                                                                                                    SUM(pomitra.jumlah) AS jumlahnya,
                                                                                                    bukapo.tgl
                                                                                                FROM
                                                                                                    poproduk
                                                                                                        INNER JOIN
                                                                                                    pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                                                                                                        INNER JOIN
                                                                                                    bukapo ON poproduk.idpoproduk = bukapo.idpoproduk
                                                                                                WHERE
                                                                                                    poproduk.tipe = 'Normal'
                                                                                                GROUP BY pomitra.idpoproduk
                                                                                                ORDER BY pomitra.idpoproduk DESC
                                                                                            ");
                                                                    $no = 1;
                                                                    while ($rowpo = $datapo->fetch_assoc()) {
                                                                        $tutup_po = $rowpo['tgl'];
                                                                ?>
                                                                    <tr>
                                                                        <td><?= $no++ ?></td>
                                                                        <td><?= $rowpo['namapo'] ?></td>
                                                                        <td><?= $rowpo['jumlahnya'] ?></td>
                                                                        <td class="text-center">
                                                                            <?php if ($dateNow < $tutup_po) : ?>
                                                                                <span class="badge badge-success">Open</span>
                                                                                <p align='center' id='demo<?php echo $rowpo['idpoproduk']; ?>'></p>
                                                                            <?php else : ?>
                                                                                <span class="badge badge-danger">Close</span>
                                                                                <p align='center' id='demo<?php echo $rowpo['idpoproduk']; ?>'></p>
                                                                            <?php endif; ?>

                                                                            <script>
                                                                                // Mengatur waktu akhir perhitungan mundur
                                                                                var countDownDate<?php echo $rowpo['idpoproduk']; ?> = new Date("<?php echo $rowpo['tgl']; ?>").getTime();

                                                                                // Memperbarui hitungan mundur setiap 1 detik
                                                                                var x = setInterval(function() {

                                                                                    // Untuk mendapatkan tanggal dan waktu hari ini
                                                                                    var now = new Date().getTime();
                                                                                        
                                                                                    // Temukan jarak antara sekarang dan tanggal hitung mundur
                                                                                    var distance = countDownDate<?php echo $rowpo['idpoproduk']; ?> - now;
                                                                                        
                                                                                    // Perhitungan waktu untuk hari, jam, menit dan detik
                                                                                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                                                                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                                                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                                                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                                                                        
                                                                                    // Keluarkan hasil dalam elemen dengan id = "demo"
                                                                                    document.getElementById("demo<?php echo $rowpo['idpoproduk']; ?>").innerHTML = days + "d " + hours + "h "
                                                                                    + minutes + "m " + seconds + "s ";
                                                                                        
                                                                                    // Jika hitungan mundur selesai, tulis beberapa teks 
                                                                                    if (distance < 0) {
                                                                                        clearInterval(x);
                                                                                        document.getElementById("demo<?php echo $rowpo['idpoproduk']; ?>").innerHTML = "PO Selesai";
                                                                                        document.getElementById("demo1<?php echo $rowpo['idpoproduk']; ?>").innerHTML = "<div style='color:red'>CLOSE</div>";
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
                                    </div>
                                <?php elseif ($role = '2') : ?>
                                    <?php
                                        include 'produksi/index.php';    
                                    ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="#" target="_blank">Quixkit</a> 2019</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->
    </div>

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="template/vendor/global/global.min.js"></script>
    <script src="template/js/quixnav-init.js"></script>
    <script src="template/js/custom.min.js"></script>


    <!-- Vectormap -->
    <script src="template/vendor/raphael/raphael.min.js"></script>
    <script src="template/vendor/morris/morris.min.js"></script>
    <script src="template/vendor/circle-progress/circle-progress.min.js"></script>
    <script src="template/vendor/chart.js/Chart.bundle.min.js"></script>
    <script src="template/vendor/gaugeJS/dist/gauge.min.js"></script>

    <!--  flot-chart js -->
    <script src="template/vendor/flot/jquery.flot.js"></script>
    <script src="template/vendor/flot/jquery.flot.resize.js"></script>

    <!-- Owl Carousel -->
    <script src="template/vendor/owl-carousel/js/owl.carousel.min.js"></script>

    <!-- Counter Up -->
    <script src="template/vendor/jqvmap/js/jquery.vmap.min.js"></script>
    <script src="template/vendor/jqvmap/js/jquery.vmap.usa.js"></script>
    <script src="template/vendor/jquery.counterup/jquery.counterup.min.js"></script>

    <script src="template/js/dashboard/dashboard-1.js"></script>

    <!-- Datatable -->
    <script src="template/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="template/js/plugins-init/datatables.init.js"></script>
    <script src="template/vendor/global/global.min.js"></script>
    <script src="template/js/quixnav-init.js"></script>
    <script src="template/js/custom.min.js"></script>
</body>
</html>