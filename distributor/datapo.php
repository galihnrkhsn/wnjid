<?php
    error_reporting(0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $invoice    = $_GET['invoice'] ?? '';
    $idadmin    = $_SESSION["idadmin"];

    // invoice hanya boleh alfanumerik supaya query di bawah (yang masih menyisipkan
    // $invoice langsung ke string SQL) tidak bisa disalahgunakan untuk SQL injection
    if ($idpoproduk <= 0 || $invoice === '') {
        header('Location: listnewpo.php');
        exit;
    }

    $stmtDatapo = $koneksi->prepare("SELECT COUNT(*) as jumlah,
                    poproduk.idpoproduk,
                    poproduk.namapo,
                    poproduk.status,
                    poproduk.note,
                    poproduk.pembayaran,
                    pomitra.ket,
                    pomitra.tgl,
                    pomitra.waktu,
                    pomitra.invoice
                FROM poproduk
                INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                WHERE poproduk.idpoproduk = ?
                AND pomitra.idmitra = ?
                AND pomitra.invoice = ?
                GROUP BY poproduk.idpoproduk,
                    poproduk.namapo,
                    poproduk.status,
                    poproduk.note,
                    poproduk.pembayaran,
                    pomitra.ket,
                    pomitra.tgl,
                    pomitra.waktu,
                    pomitra.invoice");
    $stmtDatapo->bind_param('iss', $idpoproduk, $idadmin, $invoice);
    $stmtDatapo->execute();
    $datapo = $stmtDatapo->get_result()->fetch_assoc();

    // Kalau tidak ketemu, invoice ini bukan milik mitra yang sedang login
    if (!$datapo) {
        header('Location: listnewpo.php');
        exit;
    }

    $stmtStatus = $koneksi->prepare("SELECT
                    pomitra.status,
                    pomitra.invoice
                FROM poproduk
                inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk
                WHERE poproduk.idpoproduk = ? AND pomitra.idmitra = ? AND pomitra.invoice = ?");
    $stmtStatus->bind_param('iss', $idpoproduk, $idadmin, $invoice);
    $stmtStatus->execute();
    $data2 = $stmtStatus->get_result()->fetch_assoc();

    $note         = $datapo['note'];
    $pembayaranpo = $datapo['pembayaran'];
    $invoice      = $datapo['invoice'];

    $stmtTglBayar = $koneksi->prepare("SELECT bukapo.idpoproduk, bukapo.tgl_bayar
                    FROM bukapo
                    WHERE bukapo.idpoproduk = ?");
    $stmtTglBayar->bind_param('i', $idpoproduk);
    $stmtTglBayar->execute();
    $datapo_tgl  = $stmtTglBayar->get_result()->fetch_assoc();
    $tgl_bayar   = $datapo_tgl['tgl_bayar'] ?? '';
    $waktu_bayar = '23:59:59';

    // Aturan khusus per idpoproduk: paket harus kelipatan 3, dicek lewat total qty variant "Paket"-nya
    $angka1 = 3;
    $angka2 = 3;
    $angka3 = 3;
    $angka5 = 0;
    $angka6 = 0;

    if ($idpoproduk == 167 || $idpoproduk == 173 || $idpoproduk == 176) {
        $stmtAngka1 = $koneksi->prepare("SELECT SUM(pomitra.jumlah) as jumlahnya
                    FROM pomitra
                    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                    WHERE (podetail.variant LIKE '%Sz M Paket%' or podetail.variant LIKE '%Sz L Paket%') and pomitra.invoice = ?
                    GROUP BY pomitra.invoice");
        $stmtAngka1->bind_param('s', $invoice);
        $stmtAngka1->execute();
        $data3  = $stmtAngka1->get_result()->fetch_assoc();
        $angka1 = $data3['jumlahnya'] ?? 3;

        $stmtAngka2 = $koneksi->prepare("SELECT SUM(pomitra.jumlah) as jumlahnyaa
                    FROM pomitra
                    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                    WHERE (podetail.variant LIKE '%Sz XL Paket%' or podetail.variant LIKE '%Sz JMB Paket%') and pomitra.invoice = ?
                    GROUP BY pomitra.invoice");
        $stmtAngka2->bind_param('s', $invoice);
        $stmtAngka2->execute();
        $data4  = $stmtAngka2->get_result()->fetch_assoc();
        $angka2 = $data4['jumlahnyaa'] ?? 3;
    }

    if ($idpoproduk == 174) {
        $stmtAngka3 = $koneksi->prepare("SELECT SUM(pomitra.jumlah) as jumlahnya
                    FROM pomitra
                    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                    WHERE (podetail.variant LIKE '%Paket%') and pomitra.invoice = ?
                    GROUP BY pomitra.invoice");
        $stmtAngka3->bind_param('s', $invoice);
        $stmtAngka3->execute();
        $data7  = $stmtAngka3->get_result()->fetch_assoc();
        $angka3 = $data7['jumlahnya'] ?? 3;
    }

    $stmtUser  = $koneksi->prepare("SELECT * FROM admin_mitra WHERE idadmin = ?");
    $stmtUser->bind_param('s', $idadmin);
    $stmtUser->execute();
    $queryUser = $stmtUser->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


    <title>Distributor | WNJ.ID</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-3">
        <h2 class="text-center mb-4">SALES INVOICE</h2>
        <p class="text-center"><strong><?= htmlspecialchars($datapo['namapo']); ?></strong></p><br>
        <p class="text-left">Nama Mitra  : <?= htmlspecialchars($queryUser["namamitra"] ?? ''); ?> </p>
        <p class="text-left">Alamat  : <?= htmlspecialchars($queryUser["alamat"] ?? ''); ?> </p>
        <p class="text-left">No Invoice  : <?= htmlspecialchars($invoice); ?> </p>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#agen">Info Invoice</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#reseller">Info Progres</a>
            </li>
        </ul>
        <div class="tab-content mt-5">
            <!-- Tab Content for Agen -->
            <div id="agen" class="container tab-pane active">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <?php if ($idpoproduk != 186 && $idpoproduk != 187): ?>
                                    <th>Satuan</th>
                                    <th>Total</th>
                                <?php endif ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no       = 1;
                                $jumlah   = 0;
                                $subtotal = 0;
                                $sum      = 0;
                                $qty      = 0;

                                $stmtItems = $koneksi->prepare("SELECT podetail.variant,
                                                                        podetail.harga,
                                                                        pomitra.jumlah,
                                                                        pomitra.total,
                                                                        pomitra.idpomitra,
                                                                        poproduk.namapo,
                                                                        poproduk.diskon
                                                                    FROM pomitra
                                                                    JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                    JOIN poproduk ON poproduk.idpoproduk = pomitra.idpoproduk
                                                                    WHERE pomitra.invoice = ?
                                                                    AND pomitra.jumlah > 0");
                                $stmtItems->bind_param('s', $invoice);
                                $stmtItems->execute();
                                $itemsResult = $stmtItems->get_result();

                                while ($data = $itemsResult->fetch_assoc()) { ?>
                                <tr>
                                    <td class="align-middle"><?= $no++; ?></td>
                                    <td class="align-middle">
                                        <?= htmlspecialchars($data['variant']); ?>
                                        <?php if ($idpoproduk == 186): ?>
                                            <?= htmlspecialchars($data['custom'] ?? ''); ?>
                                        <?php endif ?>
                                    </td>
                                    <td class="align-middle">
                                        <?= (int) $data['jumlah']; ?>
                                    </td>
                                    <?php if ($idpoproduk != 186 && $idpoproduk != 187): ?>
                                        <td class="align-middle">Rp. <?= number_format($data['harga']); ?></td>
                                        <td class="align-middle">Rp. <?= number_format($data['total']); ?></td>
                                    <?php endif ?>
                                    <?php
                                    $angkavoal        = $data['jumlah'];
                                    $sum             += $data['jumlah'];
                                    $jumlah          += $data['jumlah'] * $data['harga'];
                                    $persen_tambahan  = $data['diskon'];
                                    $qty             += $data['jumlah'];
                                    ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <table style="float: right;width: 100%">
                        <tbody style="float: right;">
                            <?php if ($idpoproduk == 186 || $idpoproduk == 187): ?>
                                <tr>
                                    <th style="padding-bottom: 5%;">Harga Box</th>
                                    <td style="padding-bottom: 5%;">:</td>
                                    <td style="padding-bottom: 5%;">Rp. 110.000</td>
                                </tr>
                                <tr>
                                    <th style="padding-bottom: 5%;">Jumlah Seri</th>
                                    <td style="padding-bottom: 5%;">:</td>
                                    <td style="padding-bottom: 5%;"><?= (int) ($angkavoal ?? 0); ?></td>
                                </tr>
                            <?php endif ?>
                                <tr>
                                    <th style="padding-bottom: 5%;">Total Qty</th>
                                    <td style="padding-bottom: 5%;">:</td>
                                    <td style="padding-bottom: 5%;"><?= $sum; ?></td>
                                </tr>

                            <?php if ($angka1 % 3 == 0 && $angka2 % 3 == 0 && $angka3 % 3 == 0): ?>
                                <tr>
                                    <th>JUMLAH</th>
                                    <td>:</td>
                                    <td>
                                        <?php
                                            $stmtHarga2 = $koneksi->prepare("SELECT MAX(total) as totalnya, invoice FROM `pomitra` WHERE `idpoproduk` = ? AND idmitra = ? GROUP BY invoice");
                                            $stmtHarga2->bind_param('is', $idpoproduk, $idadmin);
                                            $stmtHarga2->execute();
                                            $sisaharga2 = $stmtHarga2->get_result()->fetch_assoc();
                                            $stokharga2 = $sisaharga2['totalnya'] ?? 0;
                                            if ($idpoproduk == 120) {
                                                $jumlah = $stokharga2;
                                            }
                                        ?>
                                        Rp. <?= number_format($jumlah); ?>
                                    </td>
                                </tr>
                                <?php
                                    if ($idpoproduk == 96) {
                                        $persen = 50;
                                        $diskon = 50 / 100 * $jumlah;
                                    } elseif ($idpoproduk == 486) {
                                        $persen = 40;
                                        $diskon = 40 / 100 * $jumlah;
                                    } elseif ($idpoproduk == 518 || $idpoproduk == 521) {
                                        $diskon = 10000 * $qty;
                                    } else {
                                        $persen = 35;
                                        $diskon = 35 / 100 * $jumlah;
                                    }
                                    $diskon_tambahan = $persen_tambahan / 100 * $jumlah;
                                    $subtotal        = $jumlah - $diskon - $diskon_tambahan;
                                ?>
                                <?php if ($idpoproduk == 518): ?>
                                    <tr>
                                        <th>Diskon</th>
                                        <td>:</td>
                                        <td>Rp. <?= number_format($diskon); ?></td>
                                    </tr>
                                <?php else : ?>
                                    <tr>
                                        <th>Diskon DB <?= $persen; ?>%</th>
                                        <td>:</td>
                                        <td>Rp. <?= number_format($diskon); ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($diskon_tambahan > 0): ?>
                                <tr>
                                    <th>Diskon Tambahan <?= $persen_tambahan ?>%</th>
                                    <td>:</td>
                                    <td>Rp. <?= number_format($diskon_tambahan); ?></td>
                                </tr>
                                <?php endif ?>
                                <tr>
                                    <th style="padding-bottom: 5%;">Total Bayar</th>
                                    <td style="padding-bottom: 5%;">:</td>
                                    <td style="padding-bottom: 5%;">Rp. <?= number_format($subtotal); ?></td>
                                </tr>
                            <?php endif ?>
                            <?php
                                // Termin PO: skema cicilan (mis. 2x bayar 50%/50%) yang dikonfigurasi per produk
                                // di tabel termin_po. Kalau produk ini punya termin, jadwal & tombol bayar
                                // pakai skema termin; kalau tidak, tetap pakai alur DP + Pelunasan lama.
                                $stmtTermin = $koneksi->prepare("SELECT * FROM termin_po WHERE idpoproduk = ? ORDER BY seq ASC");
                                $stmtTermin->bind_param('i', $idpoproduk);
                                $stmtTermin->execute();
                                $terminResult = $stmtTermin->get_result();
                                $data_termin  = [];
                                while ($terminRow = $terminResult->fetch_assoc()) {
                                    $data_termin[] = $terminRow;
                                }

                                $terminSelesai = [];
                                if (!empty($data_termin)) {
                                    $stmtTerminBayar = $koneksi->prepare("SELECT termin_seq FROM popembayaran WHERE invoice = ?");
                                    $stmtTerminBayar->bind_param('s', $invoice);
                                    $stmtTerminBayar->execute();
                                    $terminBayarResult = $stmtTerminBayar->get_result();
                                    while ($rowBayar = $terminBayarResult->fetch_assoc()) {
                                        $terminSelesai[] = $rowBayar['termin_seq'];
                                    }
                                }
                            ?>
                            <?php
                                if ($idpoproduk == 220) {
                                    $angkadp = 30;
                                    $dp      = $subtotal * $angkadp / 100;
                                } else {
                                    $angkadp = 50;
                                    $dp      = $subtotal * $angkadp / 100;
                                }
                                if ($idpoproduk == 228) {
                                    $namapayemnt = 'Pembayaran';
                                } else {
                                    $namapayemnt = 'DP';
                                }
                                $jenispayment = 'dp';
                                if ($pembayaranpo == 'Lunas') {
                                    $dp          = $subtotal;
                                    $namapayemnt = 'Pembayaran';
                                } ?>
                            <?php if (!empty($data_termin)): ?>
                                <?php foreach ($data_termin as $termin): ?>
                                    <?php $sudahBayar = in_array($termin['seq'], $terminSelesai); ?>
                                    <tr style="<?= $sudahBayar ? 'color: gray;' : '' ?>">
                                        <th>
                                            <?php if ($sudahBayar): ?>&#10004; <s><?php endif; ?>
                                            <?php if ($termin['is_pelunasan'] == 1): ?>
                                                Pelunasan (<?= (int) $termin['dp'] ?>%)
                                            <?php else: ?>
                                                DP <?= (int) $termin['seq'] ?> (<?= (int) $termin['dp'] ?>%)
                                            <?php endif; ?>
                                            <?php if ($sudahBayar): ?></s><?php endif; ?>
                                        </th>
                                        <td>:</td>
                                        <td>
                                            <?php if ($sudahBayar): ?><s><?php endif; ?>
                                            Rp. <?= number_format(($termin['dp'] / 100) * $subtotal) ?>
                                            <?php if ($sudahBayar): ?></s><?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <th style="padding-top: 5%;">Status PO</th>
                                    <td style="padding-top: 5%;">:</td>
                                    <td style="padding-top: 5%;"><?= htmlspecialchars($data2['status'] ?? ''); ?></td>
                                </tr>
                            <?php elseif ($pembayaranpo != 'Lunas'): ?>
                                <tr>
                                    <th>Jumlah DP PO <?= $angkadp; ?>%</th>
                                    <td>:</td>
                                    <td>Rp. <?= number_format($dp); ?></td>
                                </tr>
                                <?php
                                    $stmtDp = $koneksi->prepare("SELECT popembayaran.invoice, popembayaran.jmlhtransfer, popembayaran.jmlh_lunas
                                                                    FROM `popembayaran`
                                                                    WHERE popembayaran.invoice = ?");
                                    $stmtDp->bind_param('s', $invoice);
                                    $stmtDp->execute();
                                    $datadp = $stmtDp->get_result()->fetch_assoc();
                                ?>
                                <tr>
                                    <th style="padding-top: 5%;">Status PO</th>
                                    <td style="padding-top: 5%;">:</td>
                                    <td style="padding-top: 5%;">
                                <?php if ($idpoproduk != 161): ?>
                                        <?= htmlspecialchars($data2['status'] ?? ''); ?>
                                <?php else: ?>
                                        Sudah Confirm Payment
                                <?php endif ?>
                                    </td>
                                </tr>
                            <?php if (!empty($datadp['invoice'])): ?>
                                <?php if ($idpoproduk != 161): ?>
                                <tr>
                                    <th>Konfirmasi DP</th>
                                    <td>:</td>
                                    <td>Rp. <?= number_format($datadp['jmlhtransfer']); ?></td>
                                </tr>
                                <tr>
                                    <th>Konfirmasi Pelunasan</th>
                                    <td>:</td>
                                    <td>Rp. <?= number_format($datadp['jmlh_lunas']); ?></td>
                                </tr>
                                <tr>
                                    <th>Sisa Tagihan</th>
                                    <td>:</td>
                                    <td>
                                    Rp.
                                    <?php if (($sisa ?? 0) > 0): ?>
                                        +
                                    <?php endif; ?>
                                    <?= number_format($sisa ?? 0); ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <p align="left"><strong>Note: </strong><?= htmlspecialchars($note ?? '') ?></p>
                    <br>
                    <div class="card border-danger mb-3">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">Info Update Rekening Transfer Bank WNJ.ID Pusat Tahun 2025</h5>
                        </div>
                        <div class="card-body text-dark">
                            <h6 class="text-uppercase">Rekening Mandiri Perusahaan</h6>
                            <ul class="mb-3">
                                <li><strong>Nama:</strong> CV KAFAA BILLAHI SYAHIDA</li>
                                <li><strong>No. Rek:</strong> 1300026727597</li>
                            </ul>

                            <h6 class="text-uppercase">Rekening BCA (BARU)</h6>
                            <ul class="mb-3">
                                <li><strong>Nama:</strong> MARIA ULFAH FATHIMAH</li>
                                <li><strong>No. Rek:</strong> 7751616671</li>
                            </ul>

                            <h6 class="text-uppercase">Rekening BRI (BARU)</h6>
                            <ul class="mb-3">
                                <li><strong>Nama:</strong> MARIA ULFAH FATHIMAH</li>
                                <li><strong>No. Rek:</strong> 114101000949560</li>
                            </ul>

                            <h6 class="text-uppercase">Rekening MUAMALAT (BARU)</h6>
                            <ul class="mb-3">
                                <li><strong>Nama:</strong> MARIA ULFAH FATHIMAH</li>
                                <li><strong>No. Rek:</strong> 1100003930</li>
                            </ul>

                            <h6 class="text-uppercase">Rekening BSI (BARU)</h6>
                            <ul class="mb-3">
                                <li><strong>Nama:</strong> MARIA ULFAH FATHIMAH</li>
                                <li><strong>No. Rek:</strong> 7105696706</li>
                            </ul>

                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center my-3">
                        <a class="btn btn-success btn-sm" href="invoice.php?idmitra=<?= urlencode($idadmin); ?>&id=<?= $idpoproduk ?>&invoice=<?= urlencode($invoice) ?>">Download PDF</a>
                    </div>
                    <br>
                    <?php if ($idpoproduk == 167 || $idpoproduk == 173 || $idpoproduk == 176): ?>
                        <?php if ($angka1 % 3 != 0): ?>
                            <div class="alert alert-danger" role="alert">
                                Khimar Anas Paket M+L Belum Kelipatan 3, Silahkan Ubah Stok
                            </div>
                        <?php endif; ?>
                        <?php if ($angka2 % 3 != 0):?>
                            <div class="alert alert-danger" role="alert">
                                Khimar Anas Paket XL+JMB Belum Kelipatan 3, Silahkan Ubah Stok
                            </div>
                        <?php endif; ?>
                        <?php if ($angka3 % 3 != 0): ?>
                            <div class="alert alert-danger" role="alert">
                                Dress Anas Paket Belum Kelipatan 3, Silahkan Ubah Stok
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($angka1 % 3 == 0 && $angka2 % 3 == 0 && $angka3 % 3 == 0): ?>
                        <?php if (!empty($data_termin)): ?>
                            <?php
                                $nextTermin = null;
                                foreach ($data_termin as $termin) {
                                    if (!in_array($termin['seq'], $terminSelesai)) {
                                        $nextTermin = $termin;
                                        break;
                                    }
                                }
                            ?>
                            <?php if ($nextTermin !== null): ?>
                                <?php
                                    $jenisPaymentTermin = ($nextTermin['is_pelunasan'] == 1) ? 'Pelunasan' : ('Payment' . $nextTermin['seq']);
                                    $jumlahBayarTermin  = ($nextTermin['dp'] / 100) * $subtotal;
                                ?>
                                <div class="d-flex justify-content-center">
                                    <a class='btn btn-primary' href='popembayaran.php?invoice=<?= urlencode($invoice) ?>&total=<?= urlencode($jumlahBayarTermin) ?>&idpo=<?= $idpoproduk ?>&jenis=<?= urlencode($jenisPaymentTermin) ?>&termin=<?= (int) $nextTermin['seq'] ?>'>Konfirmasi <?= htmlspecialchars($jenisPaymentTermin) ?> (Rp. <?= number_format($jumlahBayarTermin) ?>)</a>
                                </div>
                            <?php else: ?>
                                <div class="text-center"><span class="badge bg-success">LUNAS</span></div>
                            <?php endif; ?>
                        <?php elseif (empty($datadp['invoice']) && $idpoproduk != 183 && $idpoproduk != 186 && $idpoproduk != 187 && $idpoproduk != 194): ?>
                            <?php if ($datapo['ket'] == 'Perpanjang' || $tgl_bayar == ""): ?>
                                <?php
                                    date_default_timezone_set('Asia/Jakarta');
                                    $jumlahhari  = '+1 days';
                                    $tgl1        = $datapo['tgl'];
                                    $tgl2        = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1)));
                                    $tgl_bayar   = $tgl2;
                                    $waktu_bayar = $datapo['waktu'];
                                ?>
                            <?php endif; ?>

                            <div class="d-flex justify-content-center">
                                <div class="" id="link2<?= (int) ($data['idpomitra'] ?? 0) ?>">
                                    <a class='btn btn-primary' href='popembayaran.php?invoice=<?= urlencode($invoice) ?>&total=<?= urlencode($dp) ?>&bayar=<?= urlencode($subtotal) ?>&idpo=<?= $idpoproduk ?>&jenis=<?= urlencode($jenispayment) ?>'>Konfirmasi <?= htmlspecialchars($namapayemnt) ?></a>
                                </div>
                            </div>
                            <?php if ($idpoproduk != 257): ?>
                                <p id="demomiki<?= (int) ($data['idpomitra'] ?? 0); ?>" class="text-center"></p>
                            <?php endif; ?>
                            <script>
                                // Mengatur waktu akhir perhitungan mundur
                                var countDownDatemiki<?= (int) ($data['idpomitra'] ?? 0); ?>= new Date("<?= htmlspecialchars($tgl_bayar); ?> <?= htmlspecialchars($waktu_bayar); ?>").getTime();

                                // Memperbarui hitungan mundur setiap 1 detik
                                var x = setInterval(function() {
                                    // Untuk mendapatkan tanggal dan waktu hari ini
                                    var now = new Date().getTime();

                                    // Temukan jarak antara sekarang dan tanggal hitung mundur
                                    var distance = countDownDatemiki<?= (int) ($data['idpomitra'] ?? 0); ?> - now;

                                    // Perhitungan waktu untuk hari, jam, menit dan detik
                                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                    // Keluarkan hasil dalam elemen dengan id = "demo"
                                    document.getElementById("demomiki<?= (int) ($data['idpomitra'] ?? 0); ?>").innerHTML = days + "d " + hours + "h "
                                    + minutes + "m " + seconds + "s ";

                                    // Jika hitungan mundur selesai, tulis beberapa teks
                                    if (distance < 0) {
                                        clearInterval(x);
                                        document.getElementById("demomiki<?= (int) ($data['idpomitra'] ?? 0); ?>").innerHTML = "Melebihi batas waktu konfirmasi Payment PO";
                                        var x = document.getElementById("linkmiki<?= (int) ($data['idpomitra'] ?? 0); ?>");
                                        var y = document.getElementById("link2<?= (int) ($data['idpomitra'] ?? 0); ?>");
                                        y.style.display = "none";
                                        x.style.display = "none";
                                    }
                                }, 1000);
                            </script>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (empty($data_termin) && ($data2['status'] ?? '') == "Sudah DP"): ?>
                        <a class='btn btn-primary' href='popembayaran.php?invoice=<?= urlencode($invoice) ?>&total=<?= urlencode($sisalunas ?? '') ?>&idpo=<?= $idpoproduk ?>&jenis=lunas'>Konfirmasi Pelunasan</a>
                    <?php endif; ?>
                    <br>
                    <?php if (($data2['status'] ?? '') == "Belum DP"): ?>
                    <?php
                        $stmtBukapo = $koneksi->prepare("SELECT bukapo.idbpo,
                                                        bukapo.jenis_mitra,
                                                        bukapo.jenis_po,
                                                        bukapo.idpoproduk,
                                                        bukapo.tgl,
                                                        bukapo.tgl_acc_db,
                                                        bukapo.tgl_ubah,
                                                        bukapo.tgl_dropship,
                                                        bukapo.status,
                                                        poproduk.namapo
                                                        FROM bukapo
                                                        INNER JOIN poproduk ON bukapo.idpoproduk = poproduk.idpoproduk
                                                        WHERE poproduk.idpoproduk = ?
                                                        AND bukapo.status = 'PUBLISH'
                                                        AND (bukapo.jenis_mitra = 'Semua Mitra' OR bukapo.jenis_mitra = 'Distributor')");
                        $stmtBukapo->bind_param('i', $idpoproduk);
                        $stmtBukapo->execute();
                        $dataproduk = $stmtBukapo->get_result();
                        while ($tampilkan = $dataproduk->fetch_assoc()) {
                    ?>
                        <div class="d-flex justify-content-center">
                            <?php if ($tampilkan['jenis_po'] == "PO dengan Stok"): ?>
                                <button type="submit" class="btn btn-success btn-sm text-center" name="cari" id="linkmiki<?= (int) $tampilkan['idbpo']; ?>">
                                    <a style="color:white" href="ubahpostok?id=<?= $idpoproduk; ?>&invoice=<?= urlencode($invoice); ?>">Ubah <?= htmlspecialchars($tampilkan['namapo']); ?></a>
                                </button>
                                <p id="demomiki"></p>
                            <?php elseif ($tampilkan['jenis_po'] == "PO Mandiri"): ?>
                                <button type="submit" class="btn btn-success btn-sm text-center" name="cari" id="linkmiki<?= (int) $tampilkan['idbpo']; ?>">
                                    <a style="color:white" href="ubahpo_mandiri?id=<?= $idpoproduk; ?>&invoice=<?= urlencode($invoice); ?>">Ubah <?= htmlspecialchars($tampilkan['namapo']); ?></a>
                                </button>
                                <p id="demomiki"></p>
                            <?php endif ?>
                        </div>
                        <?php if ($tampilkan['jenis_po'] == "PO tanpa Stok" || $tampilkan['jenis_po'] == "PO Custom Tab" || $tampilkan['idpoproduk'] == 292 || $tampilkan['idpoproduk'] == 333 || $tampilkan['jenis_po'] == "PO Konin") : ?>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-success btn-sm text-center" name="cari" id="linkmiki<?= (int) $tampilkan['idbpo']; ?>">
                                    <a style="color:white" href="ubahpo?id=<?= $idpoproduk; ?>&invoice=<?= urlencode($invoice); ?>">Ubah <?= htmlspecialchars($tampilkan['namapo']); ?></a>
                                </button>
                            </div>
                            <?php if ($idpoproduk != 257): ?>
                                <p id="demomiki<?= (int) $tampilkan['idbpo']; ?>" class="text-center"></p>
                            <?php endif; ?>
                        <?php endif ?>
                        <div class="d-flex justify-content-center">
                            <?php if ($tampilkan['jenis_po'] == "PO Custom Tab Stok"): ?>
                                <div name="cari" id="linkmiki<?= (int) $tampilkan['idbpo']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <a style="color:white" href="ubahpostok.php?id=<?= $idpoproduk; ?>&invoice=<?= urlencode($invoice) ?>">Ubah <?= htmlspecialchars($tampilkan['namapo']); ?></a>
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <a style="color:white" href="formpo_tabtambahstok?id=<?= (int) $tampilkan['idpoproduk']; ?>&invoice=<?= urlencode($invoice); ?>">Tambah Variant</a>
                                    </button>
                                    <p id="demomiki<?= (int) $tampilkan['idbpo']; ?>"></p>
                                </div>
                            <?php endif ?>
                        </div>
                        <div class="d-flex justify-content-center">
                            <?php if ($tampilkan['jenis_po'] == "PO Custom Tab Stok Max"): ?>
                                <button type="submit" class="btn btn-success btn-sm" name="cari" id="linkmiki<?= (int) $tampilkan['idbpo']; ?>">
                                    <a style="color:white" href="ubahpo_tabmax.php?id=<?= $idpoproduk; ?>">Ubah <?= htmlspecialchars($tampilkan['namapo']); ?></a>
                                </button>
                                <p id="demomiki<?= (int) $tampilkan['idbpo']; ?>"></p>
                            <?php endif ?>
                        </div>
                        <br>
                        <script>
                            // Mengatur waktu akhir perhitungan mundur
                            var countDownDatemiki<?= (int) $tampilkan['idbpo']; ?> = new Date("<?= htmlspecialchars($tampilkan['tgl_ubah']); ?> 23:59:00").getTime();

                            // Memperbarui hitungan mundur setiap 1 detik
                            var x = setInterval(function() {
                                // Untuk mendapatkan tanggal dan waktu hari ini
                                var now = new Date().getTime();

                                // Temukan jarak antara sekarang dan tanggal hitung mundur
                                var distance = countDownDatemiki<?= (int) $tampilkan['idbpo']; ?> - now;

                                // Perhitungan waktu untuk hari, jam, menit dan detik
                                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                // Keluarkan hasil dalam elemen dengan id = "demo"
                                document.getElementById("demomiki<?= (int) $tampilkan['idbpo']; ?>").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

                                // Jika hitungan mundur selesai, tulis beberapa teks
                                if (distance < 0) {
                                    clearInterval(x);
                                    document.getElementById("demomiki<?= (int) $tampilkan['idbpo']; ?>").innerHTML = " ";
                                    var x = document.getElementById("linkmiki<?= (int) $tampilkan['idbpo']; ?>");
                                    x.style.display = "none";
                                }
                            }, 1000);
                        </script>
                    <?php } ?>
                    <?php endif ?>
                    <?php if (($data2['status'] ?? '') != "Belum DP"): ?>
                        <div class="d-flex justify-content-center">
                            <a href="excel_po?invoice=<?= urlencode($invoice); ?>" class="btn btn-success btn-sm">Excel Invoice</a>
                        </div>
                    <?php endif ?>
                </div>
            </div>
            <!-- Tab Content for Agen END -->
            <!-- Tab Content for Reseller -->
            <div id="reseller" class="container tab-pane fade">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Qty PO</th>
                                <th>Progres</th>
                                <th>Sisa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no          = 1;
                                $sum_progres = 0;
                                $sum_jumlah  = 0;
                                $sum_sisa    = 0;

                                $stmtProgres = $koneksi->prepare("SELECT
                                                                    pomitra.idpomitra,
                                                                    pomitra.jumlah,
                                                                    pomitra.invoice,
                                                                    pomitra.total,
                                                                    pomitra.custom,
                                                                    podetail.variant,
                                                                    podetail.harga,
                                                                    SUM(surat_jalan_po.progres) AS progres
                                                                FROM
                                                                    pomitra
                                                                INNER JOIN
                                                                    podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                LEFT JOIN
                                                                    surat_jalan_po ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                                WHERE
                                                                    pomitra.idmitra = ?
                                                                    AND pomitra.idpoproduk = ?
                                                                    AND pomitra.invoice = ?
                                                                    AND pomitra.jumlah > 0
                                                                GROUP BY
                                                                    podetail.variant,
                                                                    pomitra.idpomitra,
                                                                    pomitra.jumlah,
                                                                    pomitra.invoice,
                                                                    pomitra.total,
                                                                    podetail.harga,
                                                                    pomitra.custom
                                                                ORDER BY
                                                                    podetail.variant ASC");
                                $stmtProgres->bind_param('sis', $idadmin, $idpoproduk, $invoice);
                                $stmtProgres->execute();
                                $progresResult = $stmtProgres->get_result();

                                while ($row = $progresResult->fetch_assoc()) {
                                    $sisa = $row['jumlah'] - $row['progres'];
                            ?>
                            <tr>
                                <td class="align-middle"><?= $no++; ?></td>
                                <td class="align-middle"><?= htmlspecialchars($row['variant']); ?></td>
                                <td class="align-middle"><?= (int) $row['jumlah']; ?></td>
                                <td class="align-middle">
                                    <?php if ($row['progres'] == ""): ?>
                                        0
                                    <?php else: ?>
                                        <?= (int) $row['progres']; ?>
                                    <?php endif ?>
                                </td>
                                <td class="align-middle"><?= $sisa; ?></td>
                                <td class="align-middle">
                                    <?php if ($sisa == 0) : ?>
                                        <span class="p-1 bg-success rounded text-white">Selesai</span>
                                    <?php else : ?>
                                        <span class="p-1 bg-warning rounded text-dark">Progres</span>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <?php
                                $sum_progres += $row['progres'];
                                $sum_jumlah  += $row['jumlah'];
                                $sum_sisa    += $sisa;
                            } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">Total</td>
                                <td><?= $sum_jumlah; ?></td>
                                <td><?= $sum_progres; ?></td>
                                <td><?= $sum_sisa; ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- Tab Content for Reseller END -->
        </div>
    </div>

    <!-- MAIN CONTENT END -->

    <br><br><br><br>

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>
