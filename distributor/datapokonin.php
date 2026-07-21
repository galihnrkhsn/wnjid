<?php
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesDistri.php';
    $idpoproduk = $_GET['id'];
    $invoice = $_GET['invoice'];
    $idadmin = $_SESSION['idadmin'];

    $query = $koneksi->query("SELECT 
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
    $data = $query->fetch_assoc();
    $namapo = $data['namapo'];

    $sql_alamat = $koneksi->query("SELECT
                                        podropship.iddropship,
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
    $datapengiriman = $sql_alamat->fetch_assoc();

    $sql_po = $koneksi->query("SELECT * FROM bukapo WHERE idpoproduk = '$idpoproduk'");
    $dtpo = $sql_po->fetch_assoc();
    $now = date('Y-m-d');
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Data Konin <?= $invoice ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <style>
            td, th {
                white-space: nowrap;
                width: auto;
            }

            p {
                margin-bottom: 0;
            }

            .custom-width {
                width: 60%; /* Default width untuk layar kecil */
            }

            @media (min-width: 768px) { /* Mulai dari layar ukuran medium (tablet) */
                .custom-width {
                    width: 40%;
                }
            }

            @media (min-width: 992px) { /* Mulai dari layar ukuran besar (desktop) */
                .custom-width {
                    width: 20%;
                }
            }
        </style>
    </head>
    <body>
        <nav class="navbar bg-body-tertiary">
            <div class="container d-flex align-items-center">
                <a href="detailpokonin.php?id=<?= $idpoproduk ?>" class="text-muted fw-semibold"><i class="bi bi-chevron-left"></i></a>
                <p class="navbar-brand text-uppercase fw-semibold mb-0" href="#">Pre Order</p>
                <i class="opacity-0 bi bi-chevron-right"></i>
            </div>
        </nav>

        <div class="container mt-2" id="container">
            <div class="col-sm-12 text-center">
                <h5 class="text-uppercase mb-0 fw-normal">Invoice <?= $namapo ?></h5>
                <h3><?= $invoice ?></h3>
            </div>

            <div class="card shadow-sm mb-5">
                <div class="card-body" style="font-size: .875rem">
                    <div>
                        <p class="mb-0 fw-semibold" style="font-size: 1rem"><u>Informasi Pesanan:</u></p>
                        <p class="mb-0 fw-medium">Tanggal: <span class="fw-normal"><?= $data['tgl'] ?></span></p>
                        <p class="mb-0 fw-medium">Status: <span class="fw-normal"><?= $data['status'] ?></span></p>
                    </div>
                    <hr />
                    <div>
                        <p class="mb-0 fw-semibold" style="font-size: 1rem"><u>Informasi Pengiriman:</u></p>
                        <p class="mb-0 fw-medium">Pengirim: <span class="fw-normal"><?= $datapengiriman['namapengirim'] ?></span></p>
                        <p class="mb-0 fw-medium">Penerima: <span class="fw-normal"><?= $datapengiriman['namapenerima'] ?></span></p>
                        <p class="mb-0 fw-medium">
                            Alamat:
                            <span class="fw-normal">
                                <?php if ($datapengiriman['alamatpenerima'] == '-') : ?>
                                    Alamat Belum Diisi
                                <?php else : ?>
                                    <br />
                                    <?= $datapengiriman['alamatpenerima'] ?><br />
                                    <?= $datapengiriman['subdistrict_name'] ?>,<br />
                                    <?= $datapengiriman['city_name'] ?>.<br />
                                    <?= $datapengiriman['province_name'] ?>.<br />
                                <?php endif; ?>
                            </span>
                        </p>
                        <p class="mb-0 fw-medium">
                            Ekspedisi:
                            <span class="fw-normal">
                                <?php if ($datapengiriman['ekspedisi'] == '-') : ?>
                                    Ekspedisi Belum Diisi
                                <?php else : ?>
                                    <?= ucfirst($datapengiriman['ekspedisi']) ?>
                                <?php endif; ?>
                            </span>
                        </p>
                        <?php if ($datapengiriman['ekspedisi'] == '-') : ?>
                            <hr />
                            <p class="mb-0">Alamat belum diisi, silahkan isi alamat <a href="formdropship.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>">disini.</a></p>
                        <?php else : ?>
                            <?php if ($data['status'] == "Belum DP") : ?>
                                <hr />
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    Ubah Alamat
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Ubah Alamat</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <p class="mb-0">Apakah anda yakin akan mengubah alamat?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tidak</button>
                                                <form method="post" enctype="multipart/form-data">
                                                    <input type="hidden" class="form-control form-control-sm" value="<?= $datapengiriman['iddropship'] ?>" name="iddropship">
                                                    <button type="submit" class="btn btn-primary btn-sm" name="confirm_button">Setuju</button>
                                                </form>
                                                <?php
                                                    if (isset($_POST['confirm_button'])) {
                                                        try {
                                                            date_default_timezone_set('Asia/Jakarta');
                                                            $current_time = date('Y-m-d H:i:s');
                                                            $iddropship = $_POST['iddropship'];
                                                            $sqlupd = $koneksi->query("UPDATE podropship
                                                                                            SET
                                                                                                alamatpenerima = '-',
                                                                                                provinsi = NULL,
                                                                                                kota = NULL,
                                                                                                kecamatan = NULL,
                                                                                                ekspedisi = '-',
                                                                                                layanan = NULL,
                                                                                                ongkir = NULL,
                                                                                                dropship = NULL
                                                                                            WHERE
                                                                                                iddropship = '$iddropship'
                                                                                    ");
                                                            $sqldel = $koneksi->query("UPDATE ongkir
                                                                                            SET
                                                                                                nominal = 0,
                                                                                                updated_at = '$current_time'
                                                                                            WHERE
                                                                                                iddropship = '$iddropship'
                                                                                    ");
                                                            if ($sqlupd && $sqldel) {
                                                                echo "
                                                                    <script>
                                                                        alert('Alamat pengiriman akan diubah!');
                                                                        location='formdropship.php?id=$idpoproduk&invoice=$invoice';
                                                                    </script>
                                                                ";
                                                            } else {
                                                                echo "
                                                                    <script>
                                                                        alert('Gagal mengubah alamat pengiriman!');
                                                                        location='datapokonin.php?id=$idpoproduk&invoice=$invoice';
                                                                    </script>
                                                                ";
                                                            }
                                                        } catch (Exception $e) {
                                                            echo "Error: " . $e->getMessage();
                                                        }
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Invoice</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Progres</button>
                        </li>
                    </ul>
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
                                                                            pomitra.status,
                                                                            pods.id AS idpods
                                                                        FROM
                                                                            pomitra
                                                                                LEFT JOIN
                                                                            podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                                LEFT JOIN
                                                                            pods ON pods.idpodetail = pomitra.idpodetail
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
                                                <td><?= str_replace('Konin 2025', '', $datapo['variant']) ?></td>
                                                <td>Rp. <?= number_format(num: $datapo['harga']) ?></td>
                                                <td>
                                                    <?php if ($now <= $dtpo['tgl_ubah']) : ?>
                                                        <?php if ($datapo['status'] == "Belum DP") : ?>
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="form-group d-flex align-items-center">
                                                                    <input type="hidden" class="form-control form-control-sm" value="<?= $datapo['idpomitra'] ?>" name="idpomitra">
                                                                    <input type="hidden" class="form-control form-control-sm" value="<?= $datapo['harga'] ?>" name="harga">
                                                                    <input type="hidden" class="form-control form-control-sm" value="<?= $datapo['idpods'] ?>" name="idpods">
                                                                    <input type="number" class="form-control form-control-sm" value="<?= $datapo['jumlah'] ?>" name="qty">
                                                                    <button type="submit" name="update" class="py-1 px-2 btn-sm btn btn-success mx-2">Ubah</button>
                                                                </div>
                                                            </form>

                                                            <?php
                                                                if (isset($_POST['update'])) {
                                                                    try {
                                                                        $idpomitra = $_POST['idpomitra'];
                                                                        $qty = $_POST['qty'];
                                                                        $harga = $_POST['harga'];
                                                                        $totalharga = $qty * $harga;
                                                                        $idpods = $_POST['idpods'];

                                                                        if ($qty == 0) {
                                                                            $delpomitra = $koneksi->query("DELETE FROM pomitra WHERE idpomitra = '$idpomitra'");
                                                                            $delpods = $koneksi->query("DELETE FROM pods WHERE id = '$idpods'");

                                                                            if ($delpomitra && $delpods) {
                                                                                echo "
                                                                                    <script>
                                                                                        alert('Data berhasil diperbarui!')
                                                                                        location='datapokonin.php?id=$idpoproduk&invoice=$invoice'
                                                                                    </script>
                                                                                ";
                                                                            } else {
                                                                                echo "
                                                                                    <script>
                                                                                        alert('Data gagal diperbarui!')
                                                                                        location='datapokonin.php?id=$idpoproduk&invoice=$invoice'
                                                                                    </script>
                                                                                ";
                                                                            }
                                                                        } else {
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
                                                                                            location='datapokonin.php?id=$idpoproduk&invoice=$invoice'
                                                                                        </script>
                                                                                    ";
                                                                                } else {
                                                                                    echo "
                                                                                        <script>
                                                                                            alert('Terjadi kesalahan!')
                                                                                            location='datapokonin.php?id=$idpoproduk&invoice=$invoice'
                                                                                        </script>
                                                                                    ";
                                                                                }
                                                                            } else {
                                                                                echo "
                                                                                    <script>
                                                                                        alert('Data gagal diubah!')
                                                                                        location='datapokonin2.php?id=$idpoproduk&invoice=$invoice'
                                                                                    </script>
                                                                                ";
                                                                            }
                                                                        }
                                                                    } catch (Exception $e) {
                                                                        echo "
                                                                            <script>
                                                                                alert('Data gagal diubah! Terjadi Kesalahan')
                                                                                location='datapokonin2.php?id=$idpoproduk&invoice=$invoice'
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

                                $dp1                = $hargaAkhir * 50/100;
                                $dp2                = $hargaAkhir * 30/100;
                                // $dp3                = $hargaAkhir * 20/100;
                                $pelunasan          = $hargaAkhir * 20/100;
                                
                                $sql_pembayaran     = $koneksi->query("SELECT SUM(jmlhtransfer) AS jmlhtransfer FROM popembayaran WHERE invoice = '$invoice'");
                                $data_pemabayaran   = $sql_pembayaran->fetch_assoc();
                                $sql_ongkir         = $koneksi->query("SELECT nominal, status FROM ongkir WHERE invoice = '$invoice'");
                                $data_ongkir        = $sql_ongkir->fetch_assoc();
                                $biayaongkir        = $data_ongkir['nominal'];
                                $status_ongkir      = $data_ongkir['status'];
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
                                            <th>Ongkir</th>
                                            <td class="text-center">:</td>
                                            <td class="text-end">
                                                <?php if ($datapengiriman['layanan'] == "Layanan Ongkir Manual" && $datapengiriman['ongkir'] == 0) : ?>
                                                    Ongkir Manual
                                                <?php else : ?>
                                                    Rp. <?= number_format($ongkir) ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Biaya Dropship</th>
                                            <td class="text-center">:</td>
                                            <td class="text-end">Rp. <?= number_format($dropship) ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <hr />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Payment DP 1 50%</th>
                                            <td>:</td>
                                            <td class="text-end">
                                                <p>Rp. <?= number_format($dp1) ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Payment DP 2 30%</th>
                                            <td>:</td>
                                            <td class="text-end">
                                                <p>Rp. <?= number_format(num: $dp2) ?></p>
                                            </td>
                                        </tr>
                                        <!-- <tr>
                                            <th>Payment DP 3 20%</th>
                                            <td>:</td>
                                            <td class="text-end">
                                                <p>Rp. <?= number_format($dp3) ?></p>
                                            </td>
                                        </tr> -->
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
                            <p class="text-center text-danger mb-3">NOTE: TOTAL PAYMENT DP BELUM TERMASUK DENGAN ESTIMASI ONGKIR & BIAYA DROPSHIP</p>
                            
                            <div class="d-flex text-center flex-column">
                                <div class="mb-3">
                                    <?php if ($now <= $dtpo['tgl_ubah']) : ?>
                                        <?php if ($status == "Belum DP") : ?>
                                            <a href="tambahan_pokonin.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>" class="btn btn-success btn-sm">Tambah Variant</a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <?php if ($now <= $dtpo['tgl_bayar']) : ?>
                                        <?php if ($datapengiriman['alamatpenerima'] == '-') : ?>
                                            <p class="mb-0 d-inline mx-2">Isi alamat terlebih dahulu!</p>
                                        <?php else : ?>
                                            <?php if ($status == "Lunas") : ?>
                                                <p class="mb-2">Pembayaran Sudah Lunas</p>
                                            <?php else : ?>
                                                <?php if ($status == 'Belum DP' ||$status == 'Sudah Payment DP 1' || $status == 'Sudah Payment DP 2') : ?>
                                                    <a href="popembayarankonin.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>" class="btn btn-primary btn-sm">Konfirmasi Pembayaran</a>
                                                <?php else:  ?>
                                                    <p>Tunggu Acc Admin sebelum melanjutkan pembayaran</p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php
                                            $sqlongkir = $koneksi->query("SELECT * FROM ongkir WHERE invoice = '$invoice'");
                                            $dataongkir = $sqlongkir->fetch_assoc();
                                            if ($dataongkir['status'] == 'Belum Bayar') {
                                                if ($datapengiriman['ekspedisi'] == 'wahana') {
                                                    $assurance_wahana = 550;
                                                }
                                        ?>
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#bayar-ongkir">
                                                Bayar Ongkir
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="bayar-ongkir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Pembayaran Ongkir</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form method="post" enctype="multipart/form-data">
                                                            <div class="modal-body text-start">
                                                                <div class="form-group mb-2">
                                                                    <label class="form-label mb-0" for="nominal">Biaya Ongkir</label>
                                                                    <input type="hidden" class="form-control form-control-sm" name="idongkir" value="<?= $dataongkir['idongkir'] ?>">
                                                                    <input type="number" class="form-control form-control-sm bg-secondary bg-opacity-10 pe-none" value="<?= $dataongkir['nominal'] + $assurance_wahana + $dropship ?>" readonly>
                                                                </div>
                                                                <div class="form-group mb-2">
                                                                    <label class="form-label mb-0" for="buktitf">Bukti Transfer</label>
                                                                    <input type="file" class="form-control form-control-sm" name="buktitf" id="buktitf">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-start">
                                                                <button type="submit" class="btn btn-primary btn-sm" name="kirim">Kirim</button>
                                                            </div>
                                                        </form>
                                                        <?php
                                                            if (isset($_POST['kirim'])) {
                                                                // Set timezone PHP ke Indonesia (WIB)
                                                                date_default_timezone_set('Asia/Jakarta');
                                                                $current_time = date('Y-m-d H:i:s');

                                                                $idongkir = $_POST['idongkir'];
                                                                $foto = $_FILES['buktitf']['name'];
                                                                $tmp = $_FILES['buktitf']['tmp_name'];
                                                                $size = $_FILES['buktitf']['size'];
                                                                $ekstensiGambarValid = ['jpg', 'jpeg', 'png', 'svg'];
                                                                $ekstensiGambar = explode('.', $foto);
                                                                $ekstensiGambar = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
                                                                $errors = [];

                                                                if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
                                                                    $errors[] = "Ekstensi file tidak valid!";
                                                                }
                                                                if ($size > 2000000) {
                                                                    $errors[] = "Ukuran file terlalu besar!";
                                                                }

                                                                if (empty($errors)) {
                                                                    $filebaru = 'DB' . uniqid();
                                                                    $filebaru .= '.';
                                                                    $filebaru .= $ekstensiGambar;

                                                                    if (move_uploaded_file($tmp, '../adminwnj/ongkir/' . $filebaru)) {
                                                                        try {
                                                                            $sqlupt = $koneksi->query("UPDATE ongkir SET status = 'Konfirmasi Admin', buktitf = '$filebaru',updated_at = '$current_time' WHERE idongkir = '$idongkir'");
                                                                            if ($sqlupt) {
                                                                                echo "
                                                                                    <script>
                                                                                        alert('Berhasil mengkonfirmasi pembayaran ongkir.')
                                                                                        location='datapokonin.php?id=$idpoproduk&invoice=$invoice'
                                                                                    </script>
                                                                                ";
                                                                            } else {
                                                                                echo "
                                                                                    <script>
                                                                                        alert('Gagal mengkonfirmasi pembayaran ongkir!')
                                                                                        location='datapokonin.php?id=$idpoproduk&invoice=$invoice'
                                                                                    </script>
                                                                                ";
                                                                            }
                                                                        } catch (Exception $e) {
                                                                            echo "Error: " . $e->getMessage();
                                                                        }
                                                                    } else {
                                                                        echo "Data gagal ditambahkan!";
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } elseif ($dataongkir['status'] == "Lunas") { ?>
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#bukti-transfer">
                                                Bukti Pembayaran Ongkir
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="bukti-transfer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Bukti Pemabyaran Ongkir</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <img width="50%" src="../adminwnj/ongkir/<?= $dataongkir['buktitf'] ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">...</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="d-flex justify-content-center text-center">
                <button id="downloadPdfBtn" class="btn btn-success btn-sm mb-3">Unduh PDF</button>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script>
            document.getElementById('downloadPdfBtn').addEventListener('click', function() {
                const element = document.getElementById('container'); // Target elemen UI Anda
                const originalContent = element.innerHTML; // Simpan konten asli

                // --- Persiapan: Sembunyikan elemen yang tidak perlu dalam PDF ---

                // 1. Sembunyikan elemen form dan tombol "Ubah"
                const forms = element.querySelectorAll('form');
                forms.forEach(form => form.style.display = 'none');

                // 2. Jika ada elemen lain yang tidak ingin tampil di PDF (misalnya, teks peringatan), sembunyikan di sini

                // 3. (Opsional) Beri nama file yang dinamis, misalnya menggunakan nomor invoice dari PHP
                // Asumsi variabel 'invoice' tersedia di scope JavaScript (jika Anda mencetaknya di file)
                const fileName = 'Laporan_PO_Invoice_<?php echo $invoice; ?>.pdf';
                
                // --- Konversi menggunakan html2canvas dan jsPDF ---
                
                // Pilihan konfigurasi html2canvas
                const options = {
                    scale: 2, // Meningkatkan kualitas gambar
                    useCORS: true, // Untuk menangani gambar dari sumber eksternal
                };

                html2canvas(element, options).then(canvas => {
                    const { jsPDF } = window.jspdf;
                    const pdf = new jsPDF('p', 'mm', 'a4'); // 'p' for portrait, 'mm' for units, 'a4' for size
                    
                    // Ukuran A4 dalam mm: [210, 297]
                    const imgWidth = 200; // Lebar gambar dalam PDF (mm)
                    const pageHeight = 297; // Tinggi A4 (mm)
                    const imgHeight = canvas.height * imgWidth / canvas.width; // Hitung tinggi proporsional
                    let heightLeft = imgHeight;
                    let position = 5; // Posisi awal Y (margin atas 5mm)
                    const marginX = 5; // Margin kiri/kanan 5mm

                    // Konversi canvas ke format gambar (JPEG atau PNG)
                    const imgData = canvas.toDataURL('image/jpeg', 1.0);
                    
                    // Jika konten lebih panjang dari 1 halaman, ulangi untuk membuat halaman baru
                    pdf.addImage(imgData, 'JPEG', marginX, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                    
                    while (heightLeft >= 0) {
                        position = heightLeft - imgHeight + (pageHeight);
                        pdf.addPage();
                        pdf.addImage(imgData, 'JPEG', marginX, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }

                    pdf.save(fileName);
                    
                    // --- Pembersihan: Kembalikan konten asli setelah diunduh ---

                    // Tampilkan kembali elemen form dan tombol "Ubah"
                    forms.forEach(form => form.style.display = '');

                }).catch(error => {
                    console.error('Terjadi kesalahan saat membuat PDF:', error);
                    alert('Gagal membuat PDF. Periksa konsol untuk detail.');
                    
                    // Pastikan elemen kembali normal meskipun ada error
                    const forms = element.querySelectorAll('form');
                    forms.forEach(form => form.style.display = '');
                });
            });
        </script>
    </body>
</html>