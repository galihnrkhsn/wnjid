<?php
    session_start();
    error_reporting (0);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesAgen.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $invoice = $_GET['invoice'];
    $idmitraagen = $_SESSION["idmitraagen"];
    $querymitra = $koneksi->query("SELECT * FROM mitraagen WHERE idmitraagen = '$idmitraagen'");
    $datamitra = $querymitra->fetch_assoc();
    $namamitra = $datamitra['namaagen'];
    $query = "SELECT poproduk.idpoproduk,
                    poproduk.namapo, pomitra.tgl, pomitra.waktu, pomitra.status, pomitra.custom
                FROM poproduk
                INNER JOIN pomitra ON poproduk.idpoproduk=pomitra.idpoproduk 
                WHERE pomitra.invoice='$invoice'
            ";
    $sqlpo = mysqli_query($koneksi, $query);  
    $datapo = mysqli_fetch_array($sqlpo);

    $idpoproduk = $_GET['id'];
    $jenis_po = $datapo['custom'];

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
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $namamitra ?> | WNJ</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid row">
            <div class="col-2 text-center">
                <a class="navbar-brand" href="detailpo.php?id=<?= $idpoproduk ?>"><i class="bi bi-chevron-left"></i></a>
            </div>

            <div class="col-8 text-center">
                <h5 class="p-0 m-0 fw-semibold text-uppercase">pre order</h5>
            </div>
            
            <div class="col-2"></div>
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
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <?php if ($jenis_po !== "Set") : ?>
                                    <th>Total Harga</th>
                                <?php endif; ?>
                            </tr>
                        </thead>

                        <?php
                            $no = 1;
                            $sql = mysqli_query($koneksi, "SELECT podetail.*, pomitra.*
                                                            FROM pomitra INNER JOIN podetail
                                                            ON podetail.idpodetail = pomitra.idpodetail
                                                            WHERE pomitra.invoice = '$invoice'
                                                            AND pomitra.jumlah > 0
                                                            ORDER BY pomitra.idpodetail
                                                            "
                                                );
                            while($data = mysqli_fetch_array($sql)) {
                                $variant = $data['variant'];
                        ?>
                            <tbody>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <?php if (is_null($data['custom'])) : ?>
                                        <td><?= $data['variant'] ?></td>
                                    <?php else : ?>
                                        <?php if ($idpoproduk == 331) : ?>
                                            <td><?= $data['variant'] ?></td>
                                        <?php else : ?>
                                            <td><?= $data['variant'] ?> + <?= $data['custom'] ?></td>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <td><?= $data['jumlah'] ?></td>
                                    <?php if ($jenis_po !== "Set") : ?>
                                        <td>Rp. <?= number_format($data['total']) ?></td>
                                    <?php endif; ?>
                                </tr>
                            </tbody>
                        <?php 
                                $sum += $data['jumlah'];

                                if ($jenis_po === "Set") {
                                    $total = 100000 * $sum / 3;
                                } else {
                                    $total += $data['total'];
                                }
                            }
                        ?>
                    </table>
                </div>

                <div class="col-sm-12 d-flex justify-content-end">
                    <div class="table-responsive">
                        <table border="0">
                            <tr>
                                <th width="345">Total Qty</th>
                                <td width="20">:</td>
                                <td><?= $sum; ?></td>
                            </tr>
                            <tr>
                                <?php if ($jenis_po === "Set") : ?>
                                    <th>Jumlah Harga Set</th>
                                <?php else : ?>
                                    <th>Jumlah</th>
                                <?php endif; ?>
                                <td>:</td>
                                <td>Rp. <?= number_format($total) ?></td>
                            </tr>
                            <?php
                                $persen = 25;
                                $diskon = 25/100*$total;
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
                                } elseif ($idpoproduk == 331) {
                                    $dp1 = $subtotal * 50/100;
                                }
                            ?>
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
                                <td coslpan="3">
                                    <hr />
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>:</td>
                                <td><?= $datapo['status']; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-sm-12 my-3 text-center">
                    <div class="d-flex align-items-center justify-content-center">
                        <?php if ($datapo['status'] == "Belum Acc DB") : ?>
                            <?php if ($jenis_po === "Satuan") : ?>
                                <a href="ubahpo.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>" class="btn btn-secondary btn-sm">Ubah Data</a>
                            <?php else : ?>
                                <a href="ubahpocustom.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>&jenis=<?= $jenis_po ?>" class="btn btn-secondary btn-sm">Ubah Data</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>