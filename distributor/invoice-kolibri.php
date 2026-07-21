<?php
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesDistri.php';
    $idpoproduk     = $_GET['id'];
    $invoice        = $_GET['invoice'];
    $idadmin        = $_SESSION['idadmin'];

    $query          = $koneksi->query("SELECT 
                                            poproduk.idpoproduk,
                                            poproduk.namapo,
                                            pomitra.tgl,
                                            pomitra.waktu,
                                            pomitra.status
                                        FROM
                                            poproduk
                                                INNER JOIN
                                            pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                                        WHERE
                                            pomitra.invoice = '$invoice'
                                    ");
    $data           = $query->fetch_assoc();
    $namapo         = $data['namapo'];

    $sql_po         = $koneksi->query("SELECT * FROM bukapo WHERE idpoproduk = '$idpoproduk'");
    $dtpo           = $sql_po->fetch_assoc();
    $now            = date('Y-m-d');

    $findUser       = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin='$idadmin'");
    $queryUser      = $findUser->fetch_assoc(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        td, th {
            white-space: nowrap;
            width: auto;
        }

        p {
            margin-bottom: 0;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>    
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <div class="container mt-3" id="invoice-content">
        <div class="col-sm-12 text-center">
            <h5 class="text-uppercase mb-0 fw-normal">Invoice <?= $namapo ?></h5>
            <h3><?= $invoice ?></h3>
        </div>
        <div class="card-body" style="font-size: .875rem">
            <div>
                <p class="mb-0 fw-semibold" style="font-size: 1rem"><u>Informasi Pesanan:</u></p>
                <p class="mb-0 fw-medium">Tanggal: <span class="fw-normal"><?= $data['tgl'] ?></span></p>
                <p class="mb-0 fw-medium">Status: <span class="fw-normal"><?= $data['status'] ?></span></p>
            </div>
            <hr />
            <div>
                <p class="mb-0 fw-semibold" style="font-size: 1rem"><u>Informasi Pengiriman:</u></p>
                <p class="mb-0 fw-medium">Penerima: <span class="fw-normal"><?= $queryUser['namamitra'] ?></span></p>
                <p class="mb-0 fw-medium">
                    Alamat:
                    <span class="fw-normal">
                        <?= $queryUser['alamat']; ?>
                    </span>
                </p>
            </div>
            <hr>
            <div class="tab-content mt-4" id="myTabContent">
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <div class="table-responsive mb-2">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Satuan</th>
                                    <th scope="col" width="200">Jumlah</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql = $koneksi->query("SELECT 
                                                                    podetail.variant,
                                                                    podetail.harga,
                                                                    pomitra.idpomitra,
                                                                    pomitra.jumlah,
                                                                    pomitra.total,
                                                                    pomitra.status
                                                                FROM
                                                                    pomitra
                                                                        LEFT JOIN
                                                                    podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                WHERE
                                                                    pomitra.invoice = '$invoice'
                                                                        AND pomitra.jumlah > 0
                                                                GROUP BY pomitra.idpodetail
                                                        ");
                                    $no = 1;
                                    while ($datapo = $sql->fetch_assoc()) {
                                        $total_harga = $datapo['harga'] * $datapo['jumlah'];
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $datapo['variant'] ?></td>
                                        <td>Rp. <?= number_format(num: $datapo['harga']) ?></td>
                                        <td>
                                            <?php if ($now <= $dtpo['tgl_ubah']) : ?>
                                                <?php if ($datapo['status'] == "Belum DP") : ?>
                                                    <form method="post" enctype="multipart/form-data">
                                                        <div class="form-group d-flex align-items-center">
                                                            <input type="hidden" class="form-control form-control-sm" value="<?= $datapo['idpomitra'] ?>" name="idpomitra">
                                                            <input type="hidden" class="form-control form-control-sm" value="<?= $datapo['harga'] ?>" name="harga">
                                                            <input type="number" class="form-control form-control-sm" value="<?= $datapo['jumlah'] ?>" name="qty">
                                                            <button type="submit" name="update" class="py-1 px-2 btn-sm btn btn-success mx-2">Ubah</button>
                                                        </div>
                                                    </form>

                                                    <?php
                                                        if (isset($_POST['update'])) {
                                                            try {
                                                                $idpomitra      = $_POST['idpomitra'];
                                                                $qty            = $_POST['qty'];
                                                                $harga          = $_POST['harga'];
                                                                $totalharga     = $qty * $harga;

                                                                $sql_upt = $koneksi->query("UPDATE
                                                                                                pomitra
                                                                                            SET
                                                                                                jumlah = '$qty',
                                                                                                total = '$totalharga'
                                                                                            WHERE
                                                                                                idpomitra = '$idpomitra'
                                                                                        ");
                                                                if ($sql_upt) {
                                                                    $updatepods = $koneksi->query("UPDATE
                                                                                                        pods
                                                                                                    SET
                                                                                                        jumlah = '$qty'
                                                                                                    WHERE
                                                                                                        id = '$idpods'
                                                                                                ");
                                                                    if ($updatepods) {
                                                                        echo "
                                                                            <script>
                                                                                alert('Data berhasil diubah!')
                                                                                location='datapokolibri3.php?id=$idpoproduk&invoice=$invoice'
                                                                            </script>
                                                                        ";
                                                                    } else {
                                                                        echo "
                                                                            <script>
                                                                                alert('Terjadi kesalahan!')
                                                                                location='datapokolibri3.php?id=$idpoproduk&invoice=$invoice'
                                                                            </script>
                                                                        ";
                                                                    }
                                                                } else {
                                                                    echo "
                                                                        <script>
                                                                            alert('Data gagal diubah!')
                                                                            location='datapokolibri32.php?id=$idpoproduk&invoice=$invoice'
                                                                        </script>
                                                                    ";
                                                                }
                                                            } catch (Exception $e) {
                                                                echo "
                                                                    <script>
                                                                        alert('Data gagal diubah! Terjadi Kesalahan')
                                                                        location='datapokolibri32.php?id=$idpoproduk&invoice=$invoice'
                                                                    </script>
                                                                ";
                                                            }
                                                        }
                                                    ?>
                                                <?php else : ?>
                                                    <?= $datapo['jumlah'] ?>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <?= $datapo['jumlah'] ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>Rp. <?= number_format($total_harga) ?></td>
                                    </tr>
                                <?php
                                        $total_barang += $datapo['jumlah'];
                                        $toha += $datapo['total'];
                                        $status = $datapo['status'];
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                        $persen             = 35;
                        $diskon             = $persen/100 * $toha;
                        $hargaAkhir         = $toha - $diskon;
                        $ongkir             = $datapengiriman['ongkir'];
                        $dropship           = $datapengiriman['dropship'];
                        $subtotal           = $toha + $dropship + $ongkir - $diskon;

                        if ($datapengiriman['ekspedisi'] == 'wahana') {
                            $subtotal = $subtotal + 550;
                        }

                        $dp1                = $hargaAkhir * 40/100;
                        $dp2                = $hargaAkhir * 40/100;
                        $pelunasan          = $hargaAkhir * 20/100;
                        
                        $sql_pembayaran     = $koneksi->query("SELECT SUM(jmlhtransfer) AS jmlhtransfer FROM popembayaran WHERE invoice = '$invoice'");
                        $data_pemabayaran   = $sql_pembayaran->fetch_assoc();
                        $dibayar            = $data_pemabayaran['jmlhtransfer'];
                        
                        if ($status_ongkir == "Lunas") {
                            $sisa_tagihan = $subtotal - $dibayar - $biayaongkir;
                        } else {
                            $sisa_tagihan = $subtotal - $dibayar;
                        }
                        // Jika sisa tagihan kurang dari 0, setel ke 0 dan hitung lebihnya
                        if ($sisa_tagihan < 0) {
                            $lebih = abs($sisa_tagihan); // Ambil nilai positif dari sisa_tagihan
                            $sisa_tagihan = 0; // Setel sisa_tagihan menjadi 0
                        } else {
                            $lebih = 0; // Jika tidak ada lebihnya
                        }
                    ?>

                    <div class="d-flex justify-content-end">
                        <div class="table-responsive mb-4 custom-width">
                            <table border="0" width="100%">
                                <tr>
                                    <th width="60">Qty</th>
                                    <td class="text-center" width="10">:</td>
                                    <td class="text-end" width="40"><?= $total_barang ?></td>
                                </tr>
                                <tr>
                                    <th>Total Barang</th>
                                    <td class="text-center">:</td>
                                    <td class="text-end">Rp. <?= number_format($toha) ?></td>
                                </tr>
                                <tr>
                                    <th>Diskon DB <?= $persen ?>%</th>
                                    <td class="text-center">:</td>
                                    <td class="text-end">- Rp. <?= number_format($diskon) ?></td>
                                </tr>
                                <tr>
                                    <th>Total Harga</th>
                                    <td class="text-center">:</td>
                                    <td class="text-end">Rp. <?= number_format($toha - $diskon) ?></td>
                                </tr>
                                <?php if ($datapengiriman['ekspedisi'] == 'wahana') : ?>
                                    <tr>
                                        <th>Biaya Asuransi</th>
                                        <td class="text-center">:</td>
                                        <td class="text-end">Rp. 550</td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td colspan="3">
                                        <hr />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment DP 1 40%</th>
                                    <td>:</td>
                                    <td class="text-end">
                                        <p>Rp. <?= number_format($dp1) ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment DP 2 40%</th>
                                    <td>:</td>
                                    <td class="text-end">
                                        <p>Rp. <?= number_format(num: $dp2) ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Pelunasan 20%</th>
                                    <td>:</td>
                                    <td class="text-end">
                                        <p>Rp. <?= number_format($pelunasan) ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <hr />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Bayar</th>
                                    <td class="text-center">:</td>
                                    <td class="text-end">Rp. <?= number_format($subtotal) ?></td>
                                </tr>
                                <tr>
                                    <th>Sisa Tagihan</th>
                                    <td class="text-center">:</td>
                                    <td class="text-end <?php echo ($sisa_tagihan > 0) ? "text-danger" : "text-success"; ?>">
                                        Rp. <?= number_format($sisa_tagihan) ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex text-center flex-column">
                        <div class="mb-3">
                            <?php if ($now <= $dtpo['tgl_ubah']) : ?>
                                <?php if ($status == "Belum DP") : ?>
                                    <a href="ubahpo.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>" class="btn btn-success btn-sm">Tambah Variant</a>
                                <?php endif; ?>
                            <?php endif; ?>
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
                window.location.href = "datapokolibri3.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>";
            });
        };
    </script>
</body>
</html>