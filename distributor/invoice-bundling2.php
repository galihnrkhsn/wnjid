<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    date_default_timezone_set('Asia/Jakarta'); // Mengatur zona waktu
    $todayRaw           = date("Y-m-d");
    $idpoproduk         = $_GET['id'];
    $invoice            = $_GET['invoice'];
    $idadmin            = $_SESSION["idadmin"];

    $query_mitra        = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin = '$idadmin'");
    $data_mitra         = $query_mitra->fetch_assoc();
    $namamitra          = $data_mitra['namamitra'];

    $namapo             = $sql['namapo'];
    $query              = "SELECT poproduk.idpoproduk, poproduk.tglselesai,
                                poproduk.namapo, pomitra.tgl, pomitra.waktu, pomitra.status
                            FROM poproduk
                            INNER JOIN pomitra ON poproduk.idpoproduk=pomitra.idpoproduk 
                            WHERE pomitra.invoice = '$invoice'
                        ";
    $sqlpo              = mysqli_query($koneksi, $query);  
    $datapo             = mysqli_fetch_array($sqlpo);

    $selesai            = $datapo['tglselesai'];
    $tglselesai         = strtotime($selesai);

    $today              = strtotime($todayRaw. '23:59:59');
    
    $idpoproduk         = $_GET['id'];

    $querypengiriman    = "SELECT podropship.namapengirim, podropship.tlppengirim,
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
    $sqlpengiriman      = mysqli_query($koneksi, $querypengiriman);  
    $datapengiriman     = mysqli_fetch_array($sqlpengiriman);

    $querybukapo        = "SELECT bukapo.idbpo, bukapo.jenis_mitra, bukapo.jenis_po,
                                    bukapo.idpoproduk, bukapo.tgl, bukapo.tgl_dropship,
                                    bukapo.tgl_bayar, bukapo.status, poproduk.namapo 
                            FROM bukapo INNER JOIN poproduk ON bukapo.idpoproduk = poproduk.idpoproduk
                            WHERE poproduk.idpoproduk = '$idpoproduk'
                            AND (bukapo.jenis_mitra = 'Semua Mitra' or bukapo.jenis_mitra = 'Distributor')
                        ";
    $sqlbukapo          = mysqli_query($koneksi, $querybukapo);  
    $databukapo         = mysqli_fetch_array($sqlbukapo);
    $sqldp              = mysqli_query($koneksi, "SELECT jmlhtransfer, jenis
                                                FROM popembayaran
                                                WHERE invoice = '$invoice'
                                    ");
    while ($datadp      = mysqli_fetch_array($sqldp)) {
        $payment        += $datadp['jmlhtransfer'];
        $sisa           = $payment - $subtotal;
        $jenispayment   = $datap['jenis'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    
    <title>Distributor | WNJ.ID</title>
</head> 
<body>
    <div class="container mt-3" id="invoice-content">
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
                                                        $harga = $data_produk['harga'] * $pack;
                                                        $idpomitra = $data_produk['idpomitra'];
                                                    }
                                                ?>
                                            </td>
                                            <td><?= $pack ?></td>
                                            <td>Rp. <?= number_format($harga) ?></td>
                                        </tr>
                                    </tbody>
                                <?php
                                        $sum    += $pack;
                                        $total  += $harga;
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
                                        $persen = 35;
                                        $diskon = 35/100*$total;
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
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Tunggu halaman benar-benar siap
        window.onload = function () {
            const element = document.getElementById('invoice-content');

            const opt = {
                margin:       0.5,
                filename:     'Invoice-<?= $invoice ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            // Buat PDF dan auto-download
            html2pdf().set(opt).from(element).save().then(() => {
                // Setelah selesai download PDF, redirect ke datapo.php
                window.location.href = "datapobundling2.php?id=<?= $idmitra ?>&id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>";
            });
        };
    </script>
</body>
</html>