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

    $bukapo     = $koneksi->query("SELECT jenis_po FROM bukapo WHERE idpoproduk = '$idpoproduk'")->fetch_assoc();
    $jenisPO    = $bukapo['jenis_po'];
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
                                        <th>#</th>
                                        <th>Nama Barang</th>
                                        <?php if ($idpoproduk == '331' && $datapo['custom'] !== "Set") : ?>
                                            <th>Satuan</th>
                                        <?php endif; ?>
                                        <?php if ($jenisPO === 'PO Custom Inisial') : ?>
                                            <th>Custom</th>
                                            <th>Font</th>
                                        <?php elseif ($jenisPO === 'PO Custom Template') : ?>
                                            <th>Template</th>
                                        <?php endif; ?>
                                        <th>QTY</th>
                                        <?php if ($datapo['custom'] !== "Set") : ?>
                                            <th>Total Harga</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $idpoproduk = $_GET['idpoproduk'];
                                        $jumlah = 0;
                                        $subtotal = 0;
                                        if ($idpoproduk == '339') {
                                            $jumlah     = 0;
                                            $subtotal   = 0;
                                            $packs      = [];
                                            $datapo     = $koneksi->query("SELECT 
                                                                        pomitra.custom
                                                                    FROM
                                                                        pomitra
                                                                    WHERE
                                                                        pomitra.invoice = '$invoice'
                                                                            AND pomitra.jumlah > 0
                                                                    GROUP BY pomitra.custom
                                                                    ");
                                            $no = 1;
                                            while ($tampilkan = $datapo->fetch_assoc()) {
                                                $custom = $tampilkan['custom'];
                                                $string = $custom;
                                                // Mengubah semua huruf menjadi huruf kecil
                                                $string = strtolower($string);
                                                // Mengganti spasi dengan tanda hubung
                                                $string = str_replace(' ', '-', $string);
                                                // Menghilangkan tanda - di awal string
                                                $string = ltrim($string, '-');
                                                // Menghapus karakter yang tidak diperlukan (opsional, jika diperlukan)
                                                $string = preg_replace('/[^a-z0-9\-]/', '', $string);
                                                ?>
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
                                                                                    pomitra.invoice = '$invoice'
                                                                                        AND pomitra.jumlah > 0
                                                                                        AND pomitra.custom = '$custom'
                                                                            ");
                                                    $first = true;
                                                    while ($data_produk = $query->fetch_assoc()) {
                                                        if (!$first) {
                                                            echo " | ";
                                                        }
                                                        $first = false;
                                                ?>
                                                    <?php if (is_null($data_produk['custom']) && $idpoproduk == '328') : ?>
                                                        <?= $data_produk['variant'] ?>
                                                    <?php elseif ($idpoproduk == '328') : ?>
                                                        <?= $data_produk['variant'] ?> + <?= $data_produk['custom'] ?>
                                                    <?php else : ?>
                                                        <?= $data_produk['variant'] ?>
                                                    <?php endif; ?>
                                                <?php 
                                                    $pack       = $data_produk['jumlah'];
                                                    $harga      = $data_produk['harga'];
                                                    $idpomitra  = $data_produk['idpomitra'];
                                                    }
                                                ?>
                                                </td>
                                                <?php if (is_null($data_produk['custom']) && $idpoproduk == '328') : ?>
                                                    <td>Rp. <?= number_format($harga) ?></td>
                                                <?php elseif ($idpoproduk == '328') : ?>
                                                    <td>Rp. <?= number_format(514000); ?></td>
                                                <?php elseif ($idpoproduk == '331' && $data_produk['custom'] === "Satuan") : ?>
                                                    <td>Rp. <?= number_format($harga) ?></td>
                                                <?php endif; ?>
        
                                                <td><?= $pack ?></td>
                                                <?php if ($data_produk['custom'] !== "Set") : ?>
                                                    <td><?= number_format($harga) ?></td>
                                                <?php endif; ?>
                                            </tr>
                                                <?php
                                                $sum    += $pack;
                                                $jumlah += $pack * $harga;
                                            }
                                        } else {
                                            $datapo = $koneksi->query("SELECT 
                                                                            poproduk.diskon,
                                                                            podetail.variant,
                                                                            pomitra.idpomitra,
                                                                            pomitra.jumlah,
                                                                            pomitra.invoice,
                                                                            pomitra.total,
                                                                            pomitra.custom,
                                                                            pomitra.template,
                                                                            pomitra.font,
                                                                            podetail.harga
                                                                        FROM
                                                                            poproduk
                                                                                INNER JOIN
                                                                            pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                                                                                INNER JOIN
                                                                            pokategori ON pokategori.idpo = pomitra.idpo
                                                                                INNER JOIN
                                                                            podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                        WHERE
                                                                            pomitra.invoice = '$invoice'
                                                                                AND pomitra.jumlah > 0
                                                                        ORDER BY pomitra.idpomitra ASC
                                                                    ");
                                            $no = 1;
                                            while ($tampilkan = $datapo->fetch_assoc()) {
                                                ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td>
                                                    <?php if (is_null($tampilkan['custom']) && $idpoproduk == '328') : ?>
                                                        <?= $tampilkan['variant'] ?>
                                                    <?php elseif ($idpoproduk == '328' || $idpoproduk == '377') : ?>
                                                        <?= $tampilkan['variant'] ?> + <?= $tampilkan['custom'] ?>
                                                    <?php else : ?>
                                                        <?= $tampilkan['variant'] ?>
                                                    <?php endif; ?>
                                                </td>
                                                <?php if ($jenisPO === 'PO Custom Inisial') : ?>
                                                    <td><?= $tampilkan['custom'] ?></td>
                                                    <td><?= $tampilkan['font'] ?></td>
                                                <?php elseif ($jenisPO === 'PO Custom Template') : ?>
                                                    <td><?= $tampilkan['template'] ?></td>
                                                <?php endif; ?>
                                                <?php if (is_null($tampilkan['custom']) && $idpoproduk == '328') : ?>
                                                    <td>Rp. <?= number_format($tampilkan['harga']) ?></td>
                                                <?php elseif ($idpoproduk == '328') : ?>
                                                    <td>Rp. <?= number_format(514000); ?></td>
                                                <?php elseif ($idpoproduk == '331' && $tampilkan['custom'] === "Satuan") : ?>
                                                    <td>Rp. <?= number_format($tampilkan['harga']) ?></td>
                                                <?php endif; ?>
        
                                                <td><?= $tampilkan['jumlah'] ?></td>
                                                <?php if ($tampilkan['custom'] !== "Set") : ?>
                                                    <td><?= number_format($tampilkan['total']) ?></td>
                                                <?php endif; ?>
                                            </tr>
                                    <?php
                                            $sum += $tampilkan['jumlah'];
                                            if ($idpoproduk == '331' && $tampilkan['custom'] === "Set") {
                                                $jumlah += $tampilkan['total'] / 3;
                                            } elseif ($idpoproduk == '331' && $tampilkan['custom'] !== "Set") {
                                                $jumlah += $tampilkan['jumlah'] * $tampilkan['total'];
                                            } else {
                                                $jumlah += $tampilkan['total'];
                                            }
                                        }
                                    ?>
                                    <?php 
                                        }
                                    ?>
                                </tbody>
                            </table>

                            <?php 
                                $ongkir             = $datapengiriman['ongkir'];
                                $dropship           = $datapengiriman['dropship'];
                                $diskon_tambahan    = $persen_tambahan / 100 * $jumlah;
                                $persen             = 35;
                                $diskon             = 35/100 * $jumlah;

                                $subtotal = $jumlah - $diskon - $diskon_tambahan;
                                $dp1 = $subtotal * 30/100;
                                $dp2 = $subtotal * 40/100;
                                $dp3 = $subtotal * 30/100;
                            ?>

                            <table style="float: right;">
                                <tbody>
                                <tr>
                                    <th width="250">Total Qty</th>
                                    <th width="10">:</th>
                                    <td width="150" style="text-align: right"><?= $sum; ?></td>
                                </tr>
                                <tr>
                                    <th width="250">Jumlah</th>
                                    <th width="10">:</th>
                                    <td width="150" style="text-align: right">Rp. <?= number_format($jumlah) ?></td>
                                </tr>
                                <tr>
                                    <th>Ongkir</th>
                                    <th>:</th>
                                    <td width="150" style="text-align: right">Rp. <?= number_format($ongkir); ?></td>
                                </tr>
                                <tr>
                                    <th>Dropship</th>
                                    <th>:</th>
                                    <td width="150" style="text-align: right">Rp. <?= number_format($dropship); ?></td>
                                </tr>
                                <?php if ($diskon_tambahan > 0) : ?>
                                    <tr>
                                        <th>Diskon Tambahan <?= $persen_tambahan; ?>%</th>
                                        <th>:</th>
                                        <td width="150" style="text-align: right">Rp. <?= number_format($diskon_tambahan); ?></td>
                                    </tr>
                                <?php endif ?>  
                                <tr>
                                    <th>Diskon DB <?= $persen; ?>%</th>
                                    <th>:</th>
                                    <td width="150" style="text-align: right">Rp. <?= number_format($diskon); ?></td>
                                </tr>  
                                <tr>
                                    <th>Total Bayar</th>
                                    <th>:</th>
                                    <td width="150" style="text-align: right">Rp. <?= number_format($subtotal); ?></td>
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