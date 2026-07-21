<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesMarketer.php';
    include "settingdatatables.php";

    $idpoproduk = $_GET['id'];
    $invoice = $_GET['invoice'];
    $idmitramarketer = $_SESSION["idmitramarketer"];
    $jenis_po = $_GET['jenis'];

    $query_po = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = '$idpoproduk'");
    $sql = $query_po->fetch_assoc();
    $namapo = $sql['namapo'];

    $query_mitra = $koneksi->query("SELECT * FROM mitramarketer WHERE idmitramarketer = '$idmitramarketer'");
    $data_mitra = $query_mitra->fetch_assoc();
    $namamitra = $data_mitra['namaagen'];

    $namapo = $sql['namapo'];
    $query = "SELECT poproduk.idpoproduk,
                    poproduk.namapo, pomitra.tgl, pomitra.waktu, pomitra.status
                FROM poproduk
                INNER JOIN pomitra ON poproduk.idpoproduk=pomitra.idpoproduk 
                WHERE pomitra.invoice='$invoice'
            ";
    $sqlpo = mysqli_query($koneksi, $query);  
    $datapo = mysqli_fetch_array($sqlpo);

    $idpoproduk = $_GET['id'];

    $querypengiriman = "SELECT podropship.namapengirim, podropship.tlppengirim,
                                podropship.namapenerima, podropship.tlppenerima,
                                podropship.alamatpenerima, podropship.ekspedisi,
                                podropship.layanan, podropship.ongkir,
                                podropship.dropship, tb_ro_provinces.province_name,
                                tb_ro_cities.city_name, tb_ro_subdistricts.subdistrict_name
                            FROM podropship 
                            LEFT JOIN tb_ro_provinces ON podropship.provinsi = tb_ro_provinces.province_id
                            LEFT JOIN tb_ro_cities ON podropship.kota = tb_ro_cities.city_id
                            LEFT JOIN tb_ro_subdistricts ON podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
                            WHERE podropship.invoice='$invoice'
                        ";
    $sqlpengiriman = mysqli_query($koneksi, $querypengiriman);  
    $datapengiriman = mysqli_fetch_array($sqlpengiriman);

    $querybukapo = "SELECT bukapo.idbpo, bukapo.jenis_mitra, bukapo.jenis_po,
                            bukapo.idpoproduk, bukapo.tgl, bukapo.tgl_dropship,
                            bukapo.tgl_bayar, bukapo.status, poproduk.namapo 
                    FROM bukapo INNER JOIN poproduk ON bukapo.idpoproduk = poproduk.idpoproduk
                    WHERE poproduk.idpoproduk = '$idpoproduk'
                    AND (bukapo.jenis_mitra = 'Semua Mitra' or bukapo.jenis_mitra = 'Distributor')
                    ";
    $sqlbukapo = mysqli_query($koneksi, $querybukapo);  
    $databukapo = mysqli_fetch_array($sqlbukapo);
    $sqldp = mysqli_query($koneksi, "SELECT jmlhtransfer, jenis
                            FROM popembayaran
                            WHERE invoice = '$invoice'
                            ");
    while ($datadp = mysqli_fetch_array($sqldp)) {
        $payment += $datadp['jmlhtransfer'];
        $sisa = $payment - $subtotal;
        $jenispayment = $datap['jenis'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WNJ | <?= ucwords($namamitra) ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar bg-body-secondary">
        <div class="container">
            <a class="navbar-brand" href="detailpo.php?id=<?= $idpoproduk ?>"><i class="bi bi-chevron-left"></i></a>
            <span class="fw-bold text-uppercase fs-6">
                Pre Order
            </span>
            <span></span>
        </div>
    </nav>
    <div class="container my-3">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h5 class="text-uppercase"><?= $datapo['namapo']; ?></h5>
                <h6>Inv. #<?= $invoice; ?></h6>
                <hr />
            </div>

            <div class="col-sm-12 mb-3">
                <p class="fw-bold">Info Pesanan</p>
                <hr width="20%" />
                <table border="0" style="font-size: 0.85rem">
                    <tr>
                        <th width="70">Tanggal</th>
                        <td width="20">:</td>
                        <td><?= $datapo['tgl']; ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>:</td>
                        <td><?= $datapo['status']; ?></td>
                    </tr>
                </table>
            </div>

            <div class="col-sm-12">
                <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Info Invoice</button>
                    </li>
                    <!-- <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Progres</button>
                    </li> -->
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Pack</th>
                                        <th>Harga /pack</th>
                                        <th>#</th>
                                    </tr>
                                </thead>

                                <?php
                                    $no = 1;
                                    $packs = [];
                                    $sql = $koneksi->query("SELECT 
                                                                    pomitra.custom
                                                                FROM
                                                                    pomitra
                                                                WHERE
                                                                    pomitra.invoice = '$invoice'
                                                                        AND pomitra.jumlah > 0
                                                                GROUP BY pomitra.custom
                                                            ");
                                    while($data = $sql->fetch_assoc()) {
                                        $custom = $data['custom'];
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
                                                    <?= $data_produk['variant'] ?>
                                                <?php 
                                                        $pack = $data_produk['jumlah'];
                                                        $harga = $data_produk['harga'];
                                                        $idpomitra = $data_produk['idpomitra'];
                                                        $status = $data_produk['status'];
                                                    }
                                                ?>
                                            </td>
                                            <td><?= $pack ?></td>
                                            <td>Rp. <?= number_format($harga) ?></td>
                                            <td>
                                                <?php if ($status === "Belum Acc DB") : ?>
                                                    <a href="ubahpoinner.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>&pack=<?= $string ?>" class="btn btn-success btn-sm"><i class="bi bi-pencil-square"></i></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                <?php
                                        $sum    += $pack;
                                        $total  += $pack * $harga;
                                    }
                                ?>
                            </table>
                        </div>

                        <div class="col-sm-12 d-flex justify-content-end">
                            <div class="table-responsive">
                                <table border="0">
                                    <tr>
                                        <th width="150">Total Qty</th>
                                        <td width="20">:</td>
                                        <td><?= $sum; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah</th>
                                        <td>:</td>
                                        <td>Rp. <?= number_format($total) ?></td>
                                    </tr>
                                    <?php
                                        $persen = 10;
                                        $diskon = 10/100*$total;
                                        $ongkir = $datapengiriman['ongkir'];
                                        $dropship = $datapengiriman['dropship'];
                                        $subtotal = $total + $dropship + $ongkir - $diskon; 
                                        $dp1 = $subtotal * 30/100;
                                        $dp2 = $subtotal * 40/100;
                                        $dp3 = $subtotal * 30/100;
                                    
                                        if ($idpoproduk == 153 or $idpoproduk == 259) {
                                            $dp1 = $subtotal - 100000;
                                            $dp2 = $dp1 * 30/100;
                                            $dp3 = $dp1 * 25/100;
                                        }
                                    ?>
                                    <tr>
                                        <th>Ongkir</th>
                                        <td>:</td>
                                        <td>Rp. <?= number_format($ongkir) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Dropship</th>
                                        <td>:</td>
                                        <td>Rp. <?= number_format($dropship) ?></td>
                                    </tr>
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
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                    <?php
                            $sqlprogres = mysqli_query($koneksi,
                                        "SELECT 
                                            SUM(surat_jalan_po.progres) AS progres,
                                            SUM(pomitra.jumlah) AS jumlah
                                            FROM surat_jalan_po
                                            INNER JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                            INNER JOIN podetail ON podetail.idpodetail = surat_jalan_po.idpodetail
                                            WHERE pomitra.idmitra = '$idadmin' 
                                            AND pomitra.idpoproduk = '$idpoproduk' 
                                            AND pomitra.jumlah > 0 
                                            AND surat_jalan_po.invoice = '$invoice' 
                                            AND (surat_jalan_po.status = 'Checker' OR surat_jalan_po.status = 'Ambil Barang')
                                            GROUP BY pomitra.idpodetail
                                        ");
                            $dataprogres = mysqli_fetch_array($sqlprogres);

                            $sqlinvoice = mysqli_query($koneksi, "SELECT
                                                                    SUM(pomitra.jumlah) as jumlah
                                                                    FROM pomitra
                                                                    WHERE pomitra.idmitra = '$idadmin'
                                                                    AND pomitra.idpoproduk = '$idpoproduk'
                                                                    AND pomitra.jumlah > 0
                            ");
                            $datainvoice = mysqli_fetch_array($sqlinvoice);
                        ?>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>QTY</th>
                                    <th>Progres</th>
                                    <th>Sisa</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    $sql = mysqli_query($koneksi, "SELECT 
                                                                        podetail.variant,
                                                                        podetail.harga,
                                                                        pomitra.*,
                                                                        SUM(surat_jalan_po.progres) AS progres
                                                                    FROM
                                                                        pomitra
                                                                            INNER JOIN
                                                                        podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                            LEFT JOIN
                                                                        surat_jalan_po ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                                    WHERE
                                                                        pomitra.idmitra = '120'
                                                                            AND pomitra.idpoproduk = '335'
                                                                            AND pomitra.invoice = 'D335-1207521859'
                                                                            AND pomitra.jumlah > 0
                                                                    GROUP BY pomitra.custom
                                                                    ORDER BY podetail.variant ASC
                                                        ");
                                    while ($data = mysqli_fetch_array($sql)) {
                                        $custom = $data['custom'];
                                        $string = $custom;
                                        // Mengubah semua huruf menjadi huruf kecil
                                        $string = strtolower($string);
                                        // Mengganti spasi dengan tanda hubung
                                        $string = str_replace(' ', '-', $string);
                                        // Menghilangkan tanda - di awal string
                                        $string = ltrim($string, '-');
                                        // Menghapus karakter yang tidak diperlukan (opsional, jika diperlukan)
                                        $string = preg_replace('/[^a-z0-9\-]/', '', $string);

                                        $id     = $data['idpomitra'];
                                        $sisa   = $data['jumlah'] - $data['progres'];
                                ?>
                                    <tr>
                                        <td class="align-middle"><?= $no++; ?></td>
                                        <td class="align-middle">
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
                                                    echo " - ";
                                                }
                                                $first = false;
                                            ?>
                                                <?= $data_produk['variant'] ?>
                                            <?php 
                                                }
                                            ?>
                                        </td>
                                        <td class="align-middle"><?= $data['jumlah'] ?></td>
                                        <td class="align-middle">
                                        <?php if($data['progres'] == "") : ?>
                                            0
                                        <?php else : ?>
                                            <?= $data['progres'] ?>
                                        <?php endif; ?>
                                        </td>
                                        <td class="align-middle"><?= $sisa ?></td>
                                        <td class="align-middle">
                                        <?php if($sisa == 0) : ?>
                                            <span class="badge text-bg-success">Selesai</span>
                                        <?php else : ?>
                                            <span class="badge text-bg-warning">Progres</span>
                                        <?php endif; ?>
                                        </td>
                                    </tr>   
                                <?php
                                        $sum_progres += $data['progres'];
                                        $sum_jumlah += $data['jumlah'];
                                        $sum_sisa += $sisa;
                                        $jumlah_progres = $jumlah_progres + $totalnya;
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <td><?= $sum_jumlah ?></td>
                                    <td><?= $sum_progres ?></td>
                                    <td colspan="2"><?= $sum_sisa ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>