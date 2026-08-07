<?php 
session_start();

include 'koneksi.php'; 


$invoice=$_GET['invoice'];
$jenis = $_GET['jenis'];

if ($jenis=='D') {
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
}

if ($jenis=='M') {
$datapo=$koneksi->query("SELECT admin_mitra.namamitra, 
                                admin_mitra_cs.namacs, 
                                admin_mitra.idadmin,
                                mitramarketer.namaagen,
                                mitramarketer.idmitramarketer,
                                orderpembayaran.foto,
                                orderpembayaran.bankpengirim,
                                orderpembayaran.rekeningpengirim,
                                orderpembayaran.jmlhtransfer,
                                orderpembayaran.metodebayar
                            FROM orderpembayaran
                            JOIN ordermarketer on orderpembayaran.invoice = ordermarketer.invoice
                            JOIN mitramarketer on mitramarketer.idmitramarketer = ordermarketer.idmitramarketer
                            JOIN admin_mitra on admin_mitra.idadmin = mitramarketer.idadmin
                            JOIN admin_mitra_cs on admin_mitra_cs.idadmin = mitramarketer.idadmin
                            where orderpembayaran.invoice='$invoice'
                            LIMIT 1
                            ");
}


$tampilpo=$datapo->fetch_assoc(); 

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
                                    switch ($jenis) {
                                        case 'D':
                                            $queryOrder = mysqli_query($koneksi, "SELECT *, produk.namaproduk FROM ordermitra 
                                                                                INNER JOIN produk ON produk.idproduk = ordermitra.idproduk
                                                                                WHERE ordermitra.invoice = '$invoice'");
                                            break;
                                        case 'R':
                                            $queryOrder = mysqli_query($koneksi, "SELECT *, produk.namaproduk FROM orderreseller 
                                                                                INNER JOIN produk ON produk.idproduk = orderreseller.idproduk
                                                                                WHERE orderreseller.invoice = '$invoice'");
                                            break;
                                        case 'A':
                                            $queryOrder = mysqli_query($koneksi, "SELECT *, produk.namaproduk FROM orderagen 
                                                                                INNER JOIN produk ON produk.idproduk = orderagen.idproduk
                                                                                WHERE orderagen.invoice = '$invoice'");
                                            break;
                                        case 'M':
                                            $queryOrder = mysqli_query($koneksi, "SELECT *, produk.namaproduk FROM ordermarketer 
                                                                                INNER JOIN produk ON produk.idproduk = ordermarketer.idproduk
                                                                                WHERE ordermarketer.invoice = '$invoice'");
                                            break;
                                        default:
                                            echo "<script>alert('Data jenis tidak ada!');</script>";
                                            break;
                                    }
                                    
                                    if (mysqli_num_rows($queryOrder) > 0) {
                                        while ($getOrder = mysqli_fetch_array($queryOrder)) {
                                            ?>
                                            <tr>
                                                <td><?= $getOrder['namaproduk']; ?></td>
                                                <td><?= $getOrder['jumlah']; ?></td>
                                                <td><?= $getOrder['subtotal']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
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
                                                    <td><?= $tampilpo['jmlhtransfer']; ?></td>
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
