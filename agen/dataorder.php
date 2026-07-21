<?php 
    session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesAgen.php';

    $invoice = $_GET["id"];
    $idmitraagen = $_SESSION['idmitraagen'];
    $queryAgen = $koneksi->query("SELECT * FROM mitraagen WHERE idmitraagen = '$idmitraagen'");
	$getUser = $queryAgen->fetch_assoc();
	$sql = "SELECT * FROM orderpengiriman WHERE invoice = '$invoice' ";
	$query = $koneksi->query($sql);
	$pengiriman = $query->fetch_assoc();
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Data Order | <?= $invoice ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <style>
            p {
                margin-bottom: 0;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container row d-flex align-items-center justify-content-between mx-auto">
                <div class="col-1">
                    <a class="navbar-brand" href="transaksi.php"><i class="bi bi-chevron-left"></i></a>
                </div>
                <div class="col-10 text-center">
                    <h3 class="my-0">ORDER</h3>
                </div>
                <div class="col-1"></div>
            </div>
        </nav>

        <div class="container mx-auto mt-2 mb-3">
            <h5 class="text-uppercase text-center">Detail Order</h5>

            <div class="my-2">
                <p class="mb-2">Invoice: <span class="fw-medium text-secondary"><?= $invoice ?></span></p>
                <p class="fw-semibold">Status Pesanan</p>
                <p class=""><?= $pengiriman['tgl']; ?></p>
                
                <?php
                    $sql = "SELECT status, payment FROM orderagen WHERE invoice ='$invoice' ";
                    $query = $koneksi->query($sql);
                    $status = $query->fetch_assoc();
                ?>
                <p>Payment: <span class="text-secondary fw-medium"><?= $status['payment'] ?></span></p>
                <p>Status: <span class="text-secondary fw-medium"><?= $status['status'] ?></span></p>
            </div>

            <hr />

            <div class="my-2">
                <p class="fw-semibold">Data Pengiriman</p>
                <p>Dari: <span class="text-secondary"><?= $pengiriman['namapengirim']; ?>, <?= $pengiriman['tlppengirim']; ?></span></p>
                <p>Dikirim Ke: <span class="text-secondary"><?= $pengiriman['namapenerima']; ?>, <?= $pengiriman['tlppenerima']; ?></span></p>
                <p>Alamat: <span class="text-secondary"><?= $pengiriman['alamat']; ?></span></p>
                <p>Provinsi: <span class="text-secondary"><?= $pengiriman['provinsi']; ?></span></p>
                <p>Kota/Kab: <span class="text-secondary"><?= $pengiriman['kota']; ?></span></p>
                <p>Kecamatan: <span class="text-secondary"><?= $pengiriman['kecamatan']; ?></span></p>
                <p>Ekspedisi: <span class="text-secondary"><?= strtoupper($pengiriman['ekspedisi']); ?></span></p>
            </div>

            <?php
                $sqlcheck = $koneksi->query("SELECT * FROM orderpengiriman WHERE invoice = '$invoice' ORDER BY orderpengiriman.idorderp DESC LIMIT 1");
                $pengirimancek = $sqlcheck->fetch_assoc();

                $sqlcheck2 = $koneksi->query("SELECT * FROM orderagen WHERE invoice = '$invoice'");
                while ($pengirimancek2 = $sqlcheck2->fetch_assoc()) {
                    $beratcek = $pengirimancek2['berat'];
                    $beratnya += $beratcek;
                }

                if (!$pengirimancek) {
            ?>
                <p>Alamat belum di isi. Silahkan isi alamat <a class="link text-danger" href="formpengiriman.php?id=<?= $invoice ?>&berat=<?= $beratnya ?>">disini.</a></p>
            <?php } ?>

            <hr />

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            $sql = $koneksi->query("SELECT 
                                                            *
                                                        FROM
                                                            orderagen
                                                                INNER JOIN
                                                            produk ON produk.idproduk = orderagen.idproduk
                                                        WHERE
                                                            orderagen.invoice = '$invoice'
                                                                AND orderagen.jumlah > 0
                                                ");
                            while ($order = $sql->fetch_assoc()) {
                                if ($order['jenis'] === "Sale") {
                                    $subtotal = $order['jumlah'] * 100000 / 4;
                                } else {
                                    $subtotal = $order['subtotal'];
                                }
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $order['namaproduk']; ?></td>
                                <td><?= $order['jumlah']; ?></td>
                                <td>Rp. <?= number_format($order['harga']) ?></td>
                            </tr>
                        <?php
                                $total_akhir += $subtotal;
                            }

                            $totala = 0;
                            $sql = $koneksi->query("SELECT 
                                                            *
                                                        FROM
                                                            orderagen
                                                                INNER JOIN
                                                            produk ON produk.idproduk = orderagen.idproduk
                                                        WHERE
                                                            orderagen.invoice = '$invoice'
                                                                AND orderagen.jumlah > 0
                                                                AND produk.idkategori > 0
                                                                AND produk.idkategori <> 2
                                                ");
                            while ($ga = $sql->fetch_assoc()) {
                                $jenis = $ga['jenis'];
                            }
                        ?>
                    </tbody>
                </table>

                <?php
                    $apaja = $pengiriman['dropship'];
                    $dropship = $pengiriman['berat'];
                    if ($dropship <= 5000 && $dropship >= 0 && $apaja == 'ya') {
                        $biayad = 3000;
                    } elseif ($dropship <= 10000 && $dropship >= 6000 && $apaja == 'ya') {
                        $biayad = 5000;
                    } elseif ($dropship <= 20000 && $dropship >= 11000 && $apaja == 'ya') {
                        $biayad = 10000;
                    } elseif ($dropship <= 30000 && $dropship >= 21000 && $apaja == 'ya') {
                        $biayad = 15000;
                    } elseif ($dropship <= 40000 && $dropship >= 31000 && $apaja == 'ya') {
                        $biayad = 20000;
                    } elseif ($dropship <= 50000 && $dropship >= 41000 && $apaja == 'ya') {
                        $biayad = 25000;
                    } elseif ($dropship <= 60000 && $dropship >= 51000 && $apaja == 'ya') {
                        $biayad = 30000;
                    } elseif ($dropship <= 70000 && $dropship >= 61000 && $apaja == 'ya') {
                        $biayad = 35000;
                    } elseif ($dropship <= 80000 && $dropship >= 71000 && $apaja == 'ya') {
                        $biayad = 40000;
                    } elseif ($dropship <= 90000 && $dropship >= 81000 && $apaja == 'ya') {
                        $biayad = 45000;
                    } elseif ($dropship <= 100000 && $dropship >= 91000 && $apaja == 'ya') {
                        $biayad = 50000;
                    } elseif ($dropship <= 110000 && $dropship >= 101000 && $apaja == 'ya') {
                        $biayad = 55000;
                    } elseif ($dropship <= 120000 && $dropship >= 111000 && $apaja == 'ya') {
                        $biayad = 60000;
                    } elseif ($dropship <= 130000 && $dropship >= 121000 && $apaja == 'ya') {
                        $biayad = 65000;
                    } elseif ($dropship <= 140000 && $dropship >= 131000 && $apaja == 'ya') {
                        $biayad = 70000;
                    } elseif ($dropship <= 150000 && $dropship >= 141000 && $apaja == 'ya') {
                        $biayad = 75000;
                    } elseif ($dropship <= 160000 && $dropship >= 151000 && $apaja == 'ya') {
                        $biayad = 80000;
                    } elseif ($dropship <= 170000 && $dropship >= 161000 && $apaja == 'ya') {
                        $biayad = 85000;
                    } elseif ($dropship <= 180000 && $dropship >= 171000 && $apaja == 'ya') {
                        $biayad = 90000;
                    } elseif ($dropship <= 190000 && $dropship >= 181000 && $apaja == 'ya') {
                        $biayad = 95000;
                    } elseif ($dropship <= 200000 && $dropship >= 191000 && $apaja == 'ya') {
                        $biayad = 100000;
                    } elseif ($apaja == 'tidak'){
                        $biayad = 0;
                    }

                    $kurir = $pengiriman['ekspedisi'];
                    $diskona = $total_akhir * 25/100;
                    $tdiskona = number_format($diskona);
                    $tongkir = number_format($pengiriman['ongkir']);
                    $grandtotal = ($total_akhir + $pengiriman['ongkir'] + $biayad) - $diskona;
                    $tgrandtotal = number_format($grandtotal);

                    if ($pengiriman['ongkir'] == 0) :
                        if ($kurir == 'Ahsan' || $kurir=='Gosend' || $kurir=='Ambil ke Pusat' || $kurir=='Disatukan') {
                ?>
                            <div class="d-flex align-items-center justify-content-end">
                                <table>
                                    <tr>
                                        <th>Total</th>
                                        <th class="text-center" width="30">:</th>
                                        <td width="20"></td>
                                        <td>Rp. </td>
                                        <td class="text-end"><?= number_format($total_akhir) ?></td>
                                    </tr>
                                    <?php if ($apaja === "ya") : ?>
                                        <tr>
                                            <th>Biaya Dropship</th>
                                            <th class="text-center" width="30">:</th>
                                            <td width="20"></td>
                                            <td>Rp. </td>
                                            <td class="text-end"><?= number_format($biayad) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th>Estimasi Ongkir</th>
                                        <th class="text-center">:</th>
                                        <td width="20"></td>
                                        <td>Rp. </td>
                                        <td class="text-end"><?= $tongkir ?></td>
                                    </tr>
                                    <tr>
                                        <th>Diskon Sub DB 25%</th>
                                        <th class="text-center">:</th>
                                        <td>-</td>
                                        <td>Rp. </td>
                                        <td class="text-end"><?= number_format($diskona) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Bayar</th>
                                        <th class="text-center">:</th>
                                        <td></td>
                                        <td>Rp. </td>
                                        <td class="text-end"><?= $tgrandtotal ?></td>
                                    </tr>
                                </table>
                            </div>
                        <?php } else { ?>
                            <div class="d-flex align-items-center justify-content-end">
                                <table>
                                    <tr>
                                        <th>Total</th>
                                        <th class="text-center" width="30">:</th>
                                        <td width="20"></td>
                                        <td>Rp. </td>
                                        <td class="text-end"><?= number_format($total_akhir) ?></td>
                                    </tr>
                                    <?php if ($apaja === "ya") : ?>
                                        <tr>
                                            <th>Biaya Dropship</th>
                                            <th class="text-center" width="30">:</th>
                                            <td width="20"></td>
                                            <td>Rp. </td>
                                            <td class="text-end"><?= number_format($biayad) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th>Estimasi Ongkir</th>
                                        <th class="text-center">:</th>
                                        <td width="20"></td>
                                        <td></td>
                                        <td class="text-end">Menunggu Konfirmasi Admin</td>
                                    </tr>
                                    <tr>
                                        <th>Diskon Sub DB 25%</th>
                                        <th class="text-center">:</th>
                                        <td>-</td>
                                        <td>Rp. </td>
                                        <td class="text-end"><?= number_format($diskona) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Bayar</th>
                                        <th class="text-center">:</th>
                                        <td></td>
                                        <td>Rp. </td>
                                        <td class="text-end"><?= $tgrandtotal ?></td>
                                    </tr>
                                </table>
                            </div>
                <?php
                        }
                    else :
                ?>
                    <div class="d-flex align-items-center justify-content-end">
                        <table>
                            <tr>
                                <th>Total</th>
                                <th class="text-center" width="30">:</th>
                                <td width="20"></td>
                                <td>Rp. </td>
                                <td class="text-end"><?= number_format($total_akhir) ?></td>
                            </tr>
                            <?php if ($apaja === "ya") : ?>
                                <tr>
                                    <th>Biaya Dropship</th>
                                    <th class="text-center" width="30">:</th>
                                    <td width="20"></td>
                                    <td>Rp. </td>
                                    <td class="text-end"><?= number_format($biayad) ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Estimasi Ongkir</th>
                                <th class="text-center">:</th>
                                <td width="20"></td>
                                <td>Rp. </td>
                                <td class="text-end"><?= $tongkir ?></td>
                            </tr>
                            <tr>
                                <th>Diskon Sub DB 25%</th>
                                <th class="text-center">:</th>
                                <td>-</td>
                                <td>Rp. </td>
                                <td class="text-end"><?= number_format($diskona) ?></td>
                            </tr>
                            <tr>
                                <th>Total Bayar</th>
                                <th class="text-center">:</th>
                                <td></td>
                                <td>Rp. </td>
                                <td class="text-end"><?= $tgrandtotal ?></td>
                            </tr>
                        </table>
                    </div>
                <?php
                    endif;
                ?>

                <div class="text-center mt-3">
                    <a href="formpembayaran.php?id=<?= $invoice;?>&total=<?= $grandtotal ?>" class="btn btn-sm btn-success">Konfirmasi Pembayaran</a>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    </body>
</html>