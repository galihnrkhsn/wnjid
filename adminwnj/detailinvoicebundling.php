<?php
    session_start();

    include "koneksi.php";
    if (!isset($_SESSION["administrator"])) {
        echo "<script>alert('Anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $invoice = $_GET["invoice"];
    $idpoproduk = $_GET["idpoproduk"];

    $querypengiriman = $koneksi->query("SELECT 
                                                podropship.namapengirim,
                                                podropship.tlppengirim,
                                                podropship.namapenerima,
                                                podropship.tlppenerima,
                                                podropship.alamatpenerima,
                                                podropship.ekspedisi,
                                                podropship.layanan,
                                                podropship.ongkir,
                                                podropship.dropship,
                                                tb_ro_provinces.province_name,
                                                tb_ro_cities.city_name,
                                                tb_ro_subdistricts.subdistrict_name
                                            FROM
                                                podropship
                                                    LEFT JOIN
                                                tb_ro_provinces ON podropship.provinsi = tb_ro_provinces.province_id
                                                    LEFT JOIN
                                                tb_ro_cities ON podropship.kota = tb_ro_cities.city_id
                                                    LEFT JOIN
                                                tb_ro_subdistricts ON podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
                                            WHERE
                                                podropship.invoice = '$invoice'
                                        ");
    $datapengiriman = $querypengiriman->fetch_assoc();
    
    $query = $koneksi->query("SELECT 
                                    poproduk.idpoproduk,
                                    poproduk.namapo,
                                    pomitra.tgl,
                                    pomitra.waktu,
                                    pomitra.status,
                                    pomitra.custom
                                FROM
                                    poproduk
                                        INNER JOIN
                                    pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                                WHERE
                                    pomitra.invoice = '$invoice'
                            ");
    $datapo = $query->fetch_assoc();

    $datamitra = $koneksi->query("SELECT 
                                        poproduk.namapo,
                                        admin_mitra.namamitra AS db,
                                        admin_mitra.idadmin AS iddb,
                                        mitraagen.namaagen AS agen,
                                        mitraagen.idmitraagen AS idagen,
                                        mitrareseller.namaagen AS reseller,
                                        mitrareseller.idmitrareseller AS idreseller,
                                        mitramarketer.namaagen AS marketer,
                                        mitramarketer.idmitramarketer AS idmarketer,
                                        podropship.namapenerima
                                    FROM
                                        `pomitra`
                                            LEFT JOIN
                                        podropship ON pomitra.invoice = podropship.invoice
                                            LEFT JOIN
                                        mitraagen ON mitraagen.idmitraagen = pomitra.idmitraagen
                                            LEFT JOIN
                                        mitrareseller ON mitrareseller.idmitrareseller = pomitra.idmitrareseller
                                            LEFT JOIN
                                        mitramarketer ON mitramarketer.idmitramarketer = pomitra.idmitramarketer
                                            LEFT JOIN
                                        admin_mitra ON (mitraagen.idadmin = admin_mitra.idadmin
                                            OR mitrareseller.idadmin = admin_mitra.idadmin
                                            OR mitramarketer.idadmin = admin_mitra.idadmin
                                            OR pomitra.idmitra = admin_mitra.idadmin)
                                            INNER JOIN
                                        poproduk ON pomitra.idpoproduk = poproduk.idpoproduk
                                    WHERE
                                        pomitra.invoice = '$invoice'
                                ");
    $tampilnama = $datamitra->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Pusat | Wanoja</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top" class="sidebar-toggled">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <a class="btn btn-info" href="cetakpomitra.php?invoice=<?= $invoice; ?>&idpoproduk=<?= $idpoproduk ?>" target="blank"><i class="fa fa-print"></i> Print Inv</a>
                            <a class="btn btn-info" href="cetakpomitrasj.php?invoice=<?= $invoice; ?>&idpoproduk=<?= $idpoproduk ?>" target="blank"><i class="fa fa-print"></i> Print Sj</a>
                            <a class="btn btn-success" href="cetakpomitra_excel.php?invoice=<?= $invoice; ?>" target="blank"><i class="fa fa-file-excel"></i> Excel</a>
                            <a class="btn btn-primary" href="formpo_tambah.php?invoice=<?= $invoice; ?>"><i class="fa fa-plus"></i> Tambah Kekurangan</a> 
                            <a class="btn btn-warning" href="ubahpo.php?invoice=<?= $invoice; ?>"><i class="fa fa-edit"></i> Ubah PO</a>
                        </div>
                        <div>
                            <a class="link" href="listpokolibri.php?id=<?= $datapo['idpoproduk']; ?>" style="float: right;"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                    <div class="d-flex text-center flex-column">
                        <h5><?= $datapo['namapo'] ?></h5>
                        <h4>Invoice <span class="text-dark font-weight-bold">#<?= $invoice ?></span></h4>
                    </div>

                    <div>
                        <p class="font-weight-medium mb-0">Nama Distributor: <?= $tampilnama['db'] ?></p>
                        <?php if ($tampilnama['agen'] <> '') : ?>
                            <p class="font-weight-medium">Nama Sub DB: <?= $tampilnama['agen'] ?></p>
                        <?php elseif ($tampilnama['marketer'] <> '') : ?>
                            <p class="font-weight-medium">Nama Sub DB: <?= $tampilnama['marketer'] ?></p>
                        <?php elseif ($tampilnama['reseller'] <> '') : ?>
                            <p class="font-weight-medium">Nama Sub DB: <?= $tampilnama['reseller'] ?></p>
                        <?php endif;  ?>
                    </div>
                    <div>
                        <div class="table-responsive">
                        <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Bundling</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>

                                <?php
                                    $no = 1;
                                    $total_semua = 0; // Menyimpan total semua harga
                                    $sql = $koneksi->query("SELECT 
                                                                pomitra.custom
                                                            FROM
                                                                pomitra
                                                            WHERE
                                                                pomitra.invoice = '$invoice'
                                                                    AND pomitra.jumlah > 0
                                                            GROUP BY pomitra.custom
                                                        ");
                                    while ($data = $sql->fetch_assoc()) {
                                        $custom = $data['custom'];
                                        $string = $custom;
                                        // Mengubah semua huruf menjadi huruf kecil
                                        $string = strtolower($string);
                                        // Mengganti spasi dengan tanda hubung
                                        $string = str_replace(' ', '-', $string);
                                        // Menghilangkan tanda - di awal string
                                        $string = ltrim($string, '-');
                                        // Menghapus karakter yang tidak diperlukan
                                        $string = preg_replace('/[^a-z0-9\-]/', '', $string);

                                        // Reset variabel untuk total per custom
                                        $total_custom = 0;
                                        $pack = 0;
                                    ?>
                                        <tbody>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td>
                                                <?php
                                                    $query = $koneksi->query("SELECT 
                                                                                podetail.*, pomitra.*
                                                                            FROM
                                                                                pomitra
                                                                                    INNER JOIN
                                                                                podetail ON pomitra.idpodetail = podetail.idpodetail
                                                                            WHERE
                                                                                pomitra.custom = '$custom'
                                                                                    AND pomitra.invoice = '$invoice'
                                                                                    AND pomitra.jumlah > 0
                                                                            ");
                                                    $first = true;
                                                    while ($data_produk = $query->fetch_assoc()) {
                                                        if (!$first) {
                                                            echo " | ";
                                                        }
                                                        $first = false;

                                                        // Tambahkan data produk
                                                        echo $data_produk['variant'];

                                                        // Hitung jumlah dan harga per item
                                                        $pack = $data_produk['jumlah'];
                                                        $idpoproduk = $_GET['idpoproduk'];
                                                        $total_custom += (int)$data_produk['jumlah'] * (int)$data_produk['harga'];
                                                    }
                                                ?>
                                                </td>
                                                <td><?= $pack ?></td>
                                                <td>Rp. <?= number_format($total_custom) ?></td>
                                            </tr>
                                        </tbody>
                                    <?php
                                        $sum += $pack;
                                        $total_semua += $total_custom;
                                    }
                                        if ($idpoproduk == 506) {
                                            $diskonBundling = $sum * 15000;
                                            $total_awal = $total_semua;
                                            $total_semua = $total_semua - $diskonBundling;
                                        }
                                    ?>
                            </table>
                            <table style="float: right;">
                                <tbody>
                                    <tr>
                                        <th width="150">Total Bundling</th>
                                        <td width="20">:</td>
                                        <td><?= $sum; ?></td>
                                    </tr>
                                    <?php if ($idpoproduk == 506) : ?>
                                    <tr>
                                        <th width="150">Total Harga</th>
                                        <td width="20">:</td>
                                        <td><?= number_format($total_awal); ?></td>
                                    </tr>
                                    <tr>
                                        <th width="150">Diskon Bundling</th>
                                        <td width="20">:</td>
                                        <td><?= number_format($diskonBundling); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th>Jumlah</th>
                                        <td>:</td>
                                        <td>Rp. <?= number_format($total_semua) ?></td>
                                    </tr>
                                    <?php
                                        $total      = $total_semua;
                                        $diskon     = 35/100*$total;
                                        $persen     = 35;
                                        $jumlah     = 
                                        $ongkir     = $datapengiriman['ongkir'];
                                        $dropship   = $datapengiriman['dropship'];
                                        $subtotal   = $total + $dropship + $ongkir - $diskon; 
                                        $dp1        = $subtotal * 30/100;
                                        $dp2        = $subtotal * 40/100;
                                        $dp3        = $subtotal * 30/100;
                                    
                                        if ($idpoproduk == 153 or $idpoproduk == 259) {
                                            $dp1 = $subtotal - 100000;
                                            $dp2 = $dp1 * 30/100;
                                            $dp3 = $dp1 * 25/100;
                                        }
                                    ?>
                                    <!-- <tr>
                                        <th>Tambahan Diskon Bundling</th>
                                        <td>:</td>
                                        <td>- Rp. <?= number_format($diskonBundle) ?></td>
                                    </tr> -->
                                    <tr>
                                        <th>Diskon DB <?= $persen ?>%</th>
                                        <td>:</td>
                                        <td>- Rp. <?= number_format($diskon) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Bayar</th>
                                        <td>:</td>
                                        <td>Rp. <?= number_format($subtotal) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Konfirmasi Payment</th>
                                        <td>:</td>
                                        <td>Rp. <?= number_format($payment) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Sisa Tagihan</th>
                                        <td>:</td>
                                        <td>
                                            <?php 
                                                $sisa = $payment - $subtotal;
                                                if($sisa > 0) : 
                                            ?>
                                                +
                                            <?php endif; ?>
                                            Rp. <?= number_format($sisa); ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>