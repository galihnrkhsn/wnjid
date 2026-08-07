<?php 
session_start();

include 'koneksi.php'; 


$invoice    = $_GET['invoice'];
$jenis      = $_GET['jenis'];

if ($jenis == 'D') {
$datapo=$koneksi->query("SELECT admin_mitra.namamitra, 
                                admin_mitra_cs.namacs, 
                                admin_mitra.idadmin,
                                orderpembayaran.foto,
                                orderpembayaran.bankpengirim,
                                orderpembayaran.rekeningpengirim,
                                orderpembayaran.jmlhtransfer,
                                orderpembayaran.metodebayar
                            FROM orderpembayaran
                            JOIN ordermitra on orderpembayaran.invoice = ordermitra.invoice
                            JOIN admin_mitra on admin_mitra.idadmin = ordermitra.idmitra
                            JOIN admin_mitra_cs on admin_mitra_cs.idadmin = ordermitra.idmitra
                            where orderpembayaran.invoice='$invoice'
                            LIMIT 1
                            ");
    $jenis_diskon = "DB";
}
if ($jenis=='A') {
$datapo=$koneksi->query("SELECT admin_mitra.namamitra, 
                                admin_mitra_cs.namacs, 
                                admin_mitra.idadmin,
                                mitraagen.namaagen,
                                mitraagen.idmitraagen,
                                orderpembayaran.foto,
                                orderpembayaran.bankpengirim,
                                orderpembayaran.rekeningpengirim,
                                orderpembayaran.jmlhtransfer,
                                orderpembayaran.metodebayar
                            FROM orderpembayaran
                            JOIN orderagen on orderpembayaran.invoice = orderagen.invoice
                            JOIN mitraagen on mitraagen.idmitraagen = orderagen.idmitraagen
                            JOIN admin_mitra on admin_mitra.idadmin = mitraagen.idadmin
                            JOIN admin_mitra_cs on admin_mitra_cs.idadmin = mitraagen.idadmin
                            where orderpembayaran.invoice='$invoice'
                            LIMIT 1
                            ");
                            $jenis_diskon = "Agen";
}

if ($jenis=='R') {
$datapo=$koneksi->query("SELECT admin_mitra.namamitra, 
                                admin_mitra_cs.namacs, 
                                admin_mitra.idadmin,
                                mitrareseller.namaagen,
                                mitrareseller.idmitrareseller,
                                orderpembayaran.foto,
                                orderpembayaran.bankpengirim,
                                orderpembayaran.rekeningpengirim,
                                orderpembayaran.jmlhtransfer,
                                orderpembayaran.metodebayar
                            FROM orderpembayaran
                            JOIN orderreseller on orderpembayaran.invoice = orderreseller.invoice
                            JOIN mitrareseller on mitrareseller.idmitrareseller = orderreseller.idmitrareseller
                            JOIN admin_mitra on admin_mitra.idadmin = mitrareseller.idadmin
                            JOIN admin_mitra_cs on admin_mitra_cs.idadmin = mitrareseller.idadmin
                            where orderpembayaran.invoice='$invoice'
                            LIMIT 1
                            ");
                            $jenis_diskon = "Reseller";
}

if ($jenis=='M') {
    $datapo = $koneksi->query("SELECT admin_mitra.namamitra, admin_mitra_cs.namacs, admin_mitra.idadmin,
                                    mitramarketer.namaagen, mitramarketer.idmitramarketer,
                                    orderpembayaran.foto, orderpembayaran.bankpengirim,
                                    orderpembayaran.rekeningpengirim, orderpembayaran.jmlhtransfer, orderpembayaran.metodebayar
                                FROM orderpembayaran
                                JOIN ordermarketer on orderpembayaran.invoice = ordermarketer.invoice
                                JOIN mitramarketer on mitramarketer.idmitramarketer = ordermarketer.idmitramarketer
                                JOIN admin_mitra on admin_mitra.idadmin = mitramarketer.idadmin
                                JOIN admin_mitra_cs on admin_mitra_cs.idadmin = mitramarketer.idadmin
                                where orderpembayaran.invoice = '$invoice'
                                LIMIT 1
                            ");
                            $jenis_diskon = "Marketer";
}


$tampilpo = $datapo->fetch_assoc(); 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>WNJ.ID</title>
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style type="text/css">
        body {
            padding-right: 0px !important;
        }
    </style>
</head>

<body id="page-top" class="sidebar-toggled">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Detail Pembayaran</h6>
                            <a href="pembayaran.php" class="btn btn-primary btn-sm"><i class="fa fa-chevron-left"></i> Kembali</a>
                        </div>
                        <div class="card-body">
                            <h3><?= $invoice; ?></h3>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Pesanan</th>
                                        <th>QTY</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total                  = 0; // Inisialisasi total keseluruhan
                                    $totalDiskon            = 0; // Inisialisasi total diskon
                                    $jumlahBundlingShort    = 0; // Inisialisasi jumlah untuk "Bundling Short"

                                    switch ($jenis) {
                                        case 'D':
                                            $queryOrder = mysqli_query($koneksi, "SELECT *, variants.disc, products.namaproduk FROM ordermitra 
                                                                                    INNER JOIN variants ON variants.id = ordermitra.idproduk
                                                                                    INNER JOIN products ON products.id = variants.idproducts
                                                                                    INNER JOIN kategori ON products.idkategori = kategori.idkategori
                                                                                    WHERE ordermitra.invoice = '$invoice'");
                                            break;
                                        case 'R':
                                            $queryOrder = mysqli_query($koneksi, "SELECT *, variants.disc, products.namaproduk FROM orderreseller 
                                                                                    INNER JOIN variants ON variants.id = orderreseller.idproduk
                                                                                    INNER JOIN products ON products.id = variants.idproducts
                                                                                    INNER JOIN kategori ON products.idkategori = kategori.idkategori
                                                                                    WHERE orderreseller.invoice = '$invoice'");
                                            break;
                                        case 'A':
                                            $queryOrder = mysqli_query($koneksi, "SELECT *, variants.disc, products.namaproduk FROM orderagen 
                                                                                    INNER JOIN variants ON variants.id = orderagen.idproduk
                                                                                    INNER JOIN products ON products.id = variants.idproducts
                                                                                    INNER JOIN kategori ON products.idkategori = kategori.idkategori
                                                                                    WHERE orderagen.invoice = '$invoice'");
                                            break;
                                        case 'M':
                                            $queryOrder = mysqli_query($koneksi, "SELECT *, variants.disc, products.namaproduk FROM ordermarketer 
                                                                                    INNER JOIN variants ON variants.id = ordermarketer.idproduk
                                                                                    INNER JOIN products ON products.id = variants.idproducts
                                                                                    INNER JOIN kategori ON products.idkategori = kategori.idkategori
                                                                                    WHERE ordermarketer.invoice = '$invoice'");
                                            break;
                                        default:
                                            echo "<script>alert('Data jenis tidak ada!');</script>";
                                            break;
                                    }

                                    if (mysqli_num_rows($queryOrder) > 0) {
                                        while ($getOrder = mysqli_fetch_array($queryOrder)) {
                                            $jumlah     = $getOrder['jumlah'];
                                            $subtotal   = $getOrder['subtotal'];
                                            $disc       = $getOrder['disc'];

                                            $diskonPerKelipatan = 35000; // Diskon untuk setiap kelipatan 3
                                            // Periksa apakah jenis adalah "Bundling Short"
                                            if ($getOrder['jenis'] == 'Bundling Short') {
                                                $jumlahBundlingShort += $jumlah; // Jumlahkan QTY untuk Bundling Short
                                            }

                                            // Tambahkan subtotal ke total keseluruhan
                                            $total += $subtotal;

                                            // Tampilkan baris produk
                                    ?>
                                            <tr>
                                                <td><?= $getOrder['namaproduk']; ?> <?= $getOrder['variant'] ?> <?= $getOrder['size'] ?></td>
                                                <td><?= $jumlah; ?></td>
                                                <td>Rp. <?= number_format($subtotal); ?></td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    $dataOrder = $queryOrder->fetch_assoc();

                                    // Hitung diskon berdasarkan jumlah Bundling Short
                                    $kelipatan = floor($jumlahBundlingShort / 3); // Hitung kelipatan 3
                                    $totalDiskon = $kelipatan * $diskonPerKelipatan; // Total diskon

                                    $total = $total - $totalDiskon;
                                    ?>
                                </tbody>
                                <tfoot>
                                    <?php if ($getOrder['jenis'] == 'Bundling Short') : ?>
                                    <tr>
                                        <th colspan="2">Total Diskon</th>
                                        <th>Rp. <?= number_format($totalDiskon); ?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Total Keseluruhan (Setelah Diskon)</th>
                                        <th>Rp. <?= number_format($total); ?></th>
                                    </tr>
                                    <?php endif; ?>
                                </tfoot>
                            </table>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Biaya Ongkir</th>
                                        <th>Biaya Dropship</th>
                                        <?php if ($grade === "GB") : ?>
                                            <th>Grade Produk</th>
                                        <?php else : ?>
                                            <th>Diskon <?= $jenis_diskon ?></th>
                                        <?php endif; ?>
                                        <?php if (isset($disc)) : ?>
                                            <th>Diskon Tambahan</th>
                                        <?php endif; ?>
                                        <th>Total</th>
                                        <th>Total Bayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $koneksi->query("SELECT 
                                                                        orderpengiriman.ongkir, SUM(ordermitra.subtotal)
                                                                    FROM
                                                                        ordermitra
                                                                            INNER JOIN
                                                                        orderpengiriman ON orderpengiriman.invoice = ordermitra.invoice
                                                                    WHERE
                                                                        ordermitra.invoice = '$invoice'
                                                            ");
                                        $data = $sql->fetch_assoc();
                                        if ($jenis == "D" && $grade !== "GB") {
                                            $diskon = $total * 35/100;
                                        } elseif ($jenis == "A") {
                                            $diskon = $total * 25/100;
                                        } elseif ($jenis == "M") {
                                            $diskon = $total * 15/100;
                                        } elseif ($jenis == "R") {
                                            $diskon = $total * 10/100;
                                        }

                                        if (isset($disc)) {
                                            $diskonTambahan = $total*$disc/100;
                                        } else {
                                            $diskonTambahan = 0;
                                        }

                                        $pengiriman     = $koneksi->query("SELECT * FROM orderpengiriman WHERE invoice = '$invoice'");
                                        $data_pengirim  = $pengiriman->fetch_assoc();
                                        $is_dropship    = $data_pengirim['dropship'];
                                        $dropship       = $data_pengirim['berat'];

                                        if ($dropship <= 5000 and $dropship >= 0 and $is_dropship == 'ya') {
                                            $biayad = 3000;
                                        } else if ($dropship <= 10000 and $dropship >= 6000 and $is_dropship == 'ya') {
                                            $biayad = 5000;
                                        } else if ($dropship <= 20000 and $dropship >= 11000 and $is_dropship == 'ya') {
                                            $biayad = 10000;
                                        } else if ($dropship <= 30000 and $dropship >= 21000 and $is_dropship == 'ya') {
                                            $biayad = 15000;
                                        } else if ($dropship <= 40000 and $dropship >= 31000 and $is_dropship == 'ya') {
                                            $biayad = 20000;
                                        } else if ($dropship <= 50000 and $dropship >= 41000 and $is_dropship == 'ya') {
                                            $biayad = 25000;
                                        } else if ($dropship <= 60000 and $dropship >= 51000 and $is_dropship == 'ya') {
                                            $biayad = 30000;
                                        } else if ($dropship <= 70000 and $dropship >= 61000 and $is_dropship == 'ya') {
                                            $biayad = 35000;
                                        } else if ($dropship <= 80000 and $dropship >= 71000 and $is_dropship == 'ya') {
                                            $biayad = 40000;
                                        } else if ($dropship <= 90000 and $dropship >= 81000 and $is_dropship == 'ya') {
                                            $biayad = 45000;
                                        } else if ($dropship <= 100000 and $dropship >= 91000 and $is_dropship == 'ya') {
                                            $biayad = 50000;
                                        } else if ($dropship <= 110000 and $dropship >= 101000 and $is_dropship == 'ya') {
                                            $biayad = 55000;
                                        } else if ($dropship <= 120000 and $dropship >= 111000 and $is_dropship == 'ya') {
                                            $biayad = 60000;
                                        } else if ($dropship <= 130000 and $dropship >= 121000 and $is_dropship == 'ya') {
                                            $biayad = 65000;
                                        } else if ($dropship <= 140000 and $dropship >= 131000 and $is_dropship == 'ya') {
                                            $biayad = 70000;
                                        } else if ($dropship <= 150000 and $dropship >= 141000 and $is_dropship == 'ya') {
                                            $biayad = 75000;
                                        } else if ($dropship <= 160000 and $dropship >= 151000 and $is_dropship == 'ya') {
                                            $biayad = 80000;
                                        } else if ($dropship <= 170000 and $dropship >= 161000 and $is_dropship == 'ya') {
                                            $biayad = 85000;
                                        } else if ($dropship <= 180000 and $dropship >= 171000 and $is_dropship == 'ya') {
                                            $biayad = 90000;
                                        } else if ($dropship <= 190000 and $dropship >= 181000 and $is_dropship == 'ya') {
                                            $biayad = 95000;
                                        } else if ($dropship <= 200000 and $dropship >= 191000 and $is_dropship == 'ya') {
                                            $biayad = 100000;
                                        } else if ($is_dropship == 'tidak') {
                                            $biayad = 0;
                                        } else {
                                            $biayad = '0';
                                        }

                                        if ($grade === "GB") {
                                            $gb = $total * 55/100;
                                        }
                                    ?>
                                    <tr>
                                        <td>Rp. <?= number_format($data['ongkir']) ?></td>
                                        <td>Rp. <?= number_format($biayad) ?></td>
                                        <?php if ($grade === "GB") : ?>
                                            <td>Rp. <?= number_format($gb) ?></td>
                                        <?php else : ?>
                                            <td>Rp. <?= number_format($diskon) ?></td>
                                        <?php endif; ?>
                                        <?php if (isset($disc)) :?>
                                            <td>Rp. <?= number_format($diskonTambahan) ?></td>
                                        <?php endif; ?>
                                        <td>Rp. <?= number_format($total - $diskon) ?></td>
                                        <td>Rp. <?= number_format($total + $data['ongkir'] - $diskon - $gb + $biayad - $diskonTambahan) ?></td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered">
                                <tr>
                                    <th>Mitra</th>
                                    <td>:</td>
                                    <td>
                                        <?php if ($jenis == 'D') : ?>
                                            <?= $tampilpo['namamitra']; ?> (<?= $tampilpo['idadmin']; ?>)
                                        <?php elseif ($jenis == 'A') : ?>
                                            <?= $tampilpo['namaagen']; ?> (<?= $tampilpo['idmitraagen']; ?>)
                                        <?php elseif ($jenis == 'R') : ?>
                                            <?= $tampilpo['namaagen']; ?> (<?= $tampilpo['idmitrareseller']; ?>)
                                        <?php elseif ($jenis == 'M') : ?>
                                            <?= $tampilpo['namaagen']; ?> (<?= $tampilpo['idmitramarketer']; ?>)
                                        <?php endif ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>CS</th>
                                    <td>:</td>
                                    <td><?= $tampilpo['namacs']; ?></td>
                                </tr>
                            </table>
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="card mt-4">
                                        <a href="../image/bukti_transfer/<?= $tampilpo['foto']; ?>" target="_blank">
                                            <img src="../image/bukti_transfer/<?= $tampilpo['foto']; ?>" class="card-img-top" alt="Bukti Transfer" style="object-fit: contain; width: 100%; height: 300px;">
                                        </a>
                                        <div class="card-body">
                                            <h5 class="card-title">Data Transfer</h5>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Bank Pengirim</th>
                                                    <td><?= $tampilpo['bankpengirim']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Rek. Pengirim</th>
                                                    <td><?= $tampilpo['rekeningpengirim']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Tujuan</th>
                                                    <td><?= $tampilpo['metodebayar']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Jumlah Transfer</th>
                                                    <td>Rp. <?= number_format($tampilpo['jmlhtransfer']); ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
