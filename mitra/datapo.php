<?php
    include 'koneksi.php';
    include 'session_guard.php';
    include '../includes/mitra_role_helper.php';

    // CATATAN MIGRASI: logic bisnis di file ini (diskon per idpoproduk, aturan kelipatan
    // paket, termin, dll) di-porting APA ADANYA dari distributor/datapo.php - sengaja
    // TIDAK dirapikan/diubah supaya angka di invoice tidak pernah berubah. Yang direfactor
    // cuma tema tampilan, keamanan (prepared statement penuh), dan role-awareness
    // (dulu selalu query kolom idmitra/admin_mitra langsung, sekarang ikut role login).
    $kolomRole = mitraKolomPomitra($mitraRole);

    $idpoproduk = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $invoice    = $_GET['invoice'] ?? '';

    if ($idpoproduk <= 0 || $invoice === '') {
        header('Location: preorder.php');
        exit;
    }

    $stmtDatapo = $koneksi->prepare("SELECT COUNT(*) as jumlah,
                    poproduk.idpoproduk, poproduk.namapo, poproduk.status, poproduk.note, poproduk.pembayaran,
                    pomitra.ket, pomitra.tgl, pomitra.waktu, pomitra.invoice
                FROM poproduk
                INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                WHERE poproduk.idpoproduk = ? AND pomitra.$kolomRole = ? AND pomitra.invoice = ?
                GROUP BY poproduk.idpoproduk, poproduk.namapo, poproduk.status, poproduk.note, poproduk.pembayaran,
                    pomitra.ket, pomitra.tgl, pomitra.waktu, pomitra.invoice");
    $stmtDatapo->bind_param('iis', $idpoproduk, $idMitra, $invoice);
    $stmtDatapo->execute();
    $datapo = $stmtDatapo->get_result()->fetch_assoc();

    if (!$datapo) {
        header('Location: preorder.php');
        exit;
    }

    $stmtStatus = $koneksi->prepare("SELECT pomitra.status, pomitra.invoice
                FROM poproduk
                INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                WHERE poproduk.idpoproduk = ? AND pomitra.$kolomRole = ? AND pomitra.invoice = ?");
    $stmtStatus->bind_param('iis', $idpoproduk, $idMitra, $invoice);
    $stmtStatus->execute();
    $data2 = $stmtStatus->get_result()->fetch_assoc();

    $note         = $datapo['note'];
    $pembayaranpo = $datapo['pembayaran'];
    $invoice      = $datapo['invoice'];

    $stmtTglBayar = $koneksi->prepare("SELECT bukapo.idpoproduk, bukapo.tgl_bayar FROM bukapo WHERE bukapo.idpoproduk = ?");
    $stmtTglBayar->bind_param('i', $idpoproduk);
    $stmtTglBayar->execute();
    $datapo_tgl  = $stmtTglBayar->get_result()->fetch_assoc();
    $tgl_bayar   = $datapo_tgl['tgl_bayar'] ?? '';
    $waktu_bayar = '23:59:59';

    // Aturan khusus per idpoproduk: paket harus kelipatan 3, dicek lewat total qty variant "Paket"-nya
    $angka1 = 3;
    $angka2 = 3;
    $angka3 = 3;

    if ($idpoproduk == 167 || $idpoproduk == 173 || $idpoproduk == 176) {
        $stmtAngka1 = $koneksi->prepare("SELECT SUM(pomitra.jumlah) as jumlahnya
                    FROM pomitra INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                    WHERE (podetail.variant LIKE '%Sz M Paket%' or podetail.variant LIKE '%Sz L Paket%') and pomitra.invoice = ?
                    GROUP BY pomitra.invoice");
        $stmtAngka1->bind_param('s', $invoice);
        $stmtAngka1->execute();
        $data3  = $stmtAngka1->get_result()->fetch_assoc();
        $angka1 = $data3['jumlahnya'] ?? 3;

        $stmtAngka2 = $koneksi->prepare("SELECT SUM(pomitra.jumlah) as jumlahnyaa
                    FROM pomitra INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                    WHERE (podetail.variant LIKE '%Sz XL Paket%' or podetail.variant LIKE '%Sz JMB Paket%') and pomitra.invoice = ?
                    GROUP BY pomitra.invoice");
        $stmtAngka2->bind_param('s', $invoice);
        $stmtAngka2->execute();
        $data4  = $stmtAngka2->get_result()->fetch_assoc();
        $angka2 = $data4['jumlahnyaa'] ?? 3;
    }

    if ($idpoproduk == 174) {
        $stmtAngka3 = $koneksi->prepare("SELECT SUM(pomitra.jumlah) as jumlahnya
                    FROM pomitra INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                    WHERE (podetail.variant LIKE '%Paket%') and pomitra.invoice = ?
                    GROUP BY pomitra.invoice");
        $stmtAngka3->bind_param('s', $invoice);
        $stmtAngka3->execute();
        $data7  = $stmtAngka3->get_result()->fetch_assoc();
        $angka3 = $data7['jumlahnya'] ?? 3;
    }

    $identitas = mitraDetailIdentitas($koneksi, $mitraRole, $idMitra);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Invoice PO | WNJ.ID</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <style>
        body { background: var(--wnj-bg); }
        .invoice-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.25rem;
            margin-bottom: 1rem;
        }
        .nav-tabs {
            border-bottom-color: var(--wnj-border);
        }
        .nav-tabs .nav-link {
            color: var(--wnj-text-secondary);
            border: none;
            border-bottom: 2px solid transparent;
        }
        .nav-tabs .nav-link.active {
            color: var(--wnj-cta);
            border-color: var(--wnj-cta);
            background: none;
        }
        .rekening-card {
            border: 1px solid var(--wnj-border);
            border-radius: 10px;
            padding: 1rem;
        }
        .rekening-card h6 {
            color: var(--wnj-cta);
            font-weight: 700;
            font-size: .8rem;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 720px;">
        <div class="d-flex align-items-center mt-3 mb-3" style="gap:.75rem;">
            <a href="preorder.php" class="text-muted"><i class="bi bi-arrow-left"></i></a>
            <h5 class="font-weight-bold mb-0">Invoice PO</h5>
        </div>

        <div class="invoice-card">
            <p class="text-center font-weight-bold mb-3"><?= htmlspecialchars($datapo['namapo']); ?></p>
            <p class="mb-1">Nama Mitra: <?= htmlspecialchars($identitas['nama']); ?></p>
            <p class="mb-1">Alamat: <?= htmlspecialchars($identitas['alamat']); ?></p>
            <p class="mb-0">No Invoice: <?= htmlspecialchars($invoice); ?></p>
        </div>

        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tabinvoice">Info Invoice</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tabprogres">Info Progres</a></li>
        </ul>
        <div class="tab-content">
            <div id="tabinvoice" class="tab-pane active">
                <div class="invoice-card">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-sm">
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
                                    $sum      = 0;
                                    $qty      = 0;

                                    $stmtItems = $koneksi->prepare("SELECT podetail.variant, podetail.harga, pomitra.jumlah, pomitra.total,
                                                                            pomitra.idpomitra, pomitra.custom, poproduk.namapo, poproduk.diskon
                                                                        FROM pomitra
                                                                        JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                        JOIN poproduk ON poproduk.idpoproduk = pomitra.idpoproduk
                                                                        WHERE pomitra.invoice = ? AND pomitra.jumlah > 0");
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
                                        <td class="align-middle"><?= (int) $data['jumlah']; ?></td>
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
                        <table style="width: 100%">
                            <tbody>
                                <?php if ($idpoproduk == 186 || $idpoproduk == 187): ?>
                                    <tr>
                                        <th>Harga Box</th><td>:</td><td>Rp. 110.000</td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah Seri</th><td>:</td><td><?= (int) ($angkavoal ?? 0); ?></td>
                                    </tr>
                                <?php endif ?>
                                <tr>
                                    <th>Total Qty</th><td>:</td><td><?= $sum; ?></td>
                                </tr>

                                <?php if ($angka1 % 3 == 0 && $angka2 % 3 == 0 && $angka3 % 3 == 0): ?>
                                    <tr>
                                        <th>JUMLAH</th><td>:</td>
                                        <td>
                                            <?php
                                                $stmtHarga2 = $koneksi->prepare("SELECT MAX(total) as totalnya, invoice FROM `pomitra` WHERE `idpoproduk` = ? AND $kolomRole = ? GROUP BY invoice");
                                                $stmtHarga2->bind_param('ii', $idpoproduk, $idMitra);
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
                                            $persen = 50; $diskon = 50 / 100 * $jumlah;
                                        } elseif ($idpoproduk == 486) {
                                            $persen = 40; $diskon = 40 / 100 * $jumlah;
                                        } elseif ($idpoproduk == 518 || $idpoproduk == 521) {
                                            $diskon = 10000 * $qty;
                                        } else {
                                            $persen = 35; $diskon = 35 / 100 * $jumlah;
                                        }
                                        $diskon_tambahan = $persen_tambahan / 100 * $jumlah;
                                        $subtotal        = $jumlah - $diskon - $diskon_tambahan;
                                    ?>
                                    <?php if ($idpoproduk == 518): ?>
                                        <tr><th>Diskon</th><td>:</td><td>Rp. <?= number_format($diskon); ?></td></tr>
                                    <?php else : ?>
                                        <tr><th>Diskon <?= htmlspecialchars($mitraCfg['label']) ?> <?= $persen; ?>%</th><td>:</td><td>Rp. <?= number_format($diskon); ?></td></tr>
                                    <?php endif; ?>
                                    <?php if ($diskon_tambahan > 0): ?>
                                        <tr><th>Diskon Tambahan <?= $persen_tambahan ?>%</th><td>:</td><td>Rp. <?= number_format($diskon_tambahan); ?></td></tr>
                                    <?php endif ?>
                                    <tr><th>Total Bayar</th><td>:</td><td>Rp. <?= number_format($subtotal); ?></td></tr>
                                <?php endif ?>

                                <?php
                                    $stmtTermin = $koneksi->prepare("SELECT * FROM termin_po WHERE idpoproduk = ? ORDER BY seq ASC");
                                    $stmtTermin->bind_param('i', $idpoproduk);
                                    $stmtTermin->execute();
                                    $data_termin = $stmtTermin->get_result()->fetch_all(MYSQLI_ASSOC);

                                    $terminSelesai = [];
                                    if (!empty($data_termin)) {
                                        $stmtTerminBayar = $koneksi->prepare("SELECT termin_seq FROM popembayaran WHERE invoice = ?");
                                        $stmtTerminBayar->bind_param('s', $invoice);
                                        $stmtTerminBayar->execute();
                                        foreach ($stmtTerminBayar->get_result()->fetch_all(MYSQLI_ASSOC) as $rowBayar) {
                                            $terminSelesai[] = $rowBayar['termin_seq'];
                                        }
                                    }

                                    if ($idpoproduk == 220) {
                                        $angkadp = 30;
                                    } else {
                                        $angkadp = 50;
                                    }
                                    $dp = $subtotal * $angkadp / 100;
                                    $namapayemnt = ($idpoproduk == 228) ? 'Pembayaran' : 'DP';
                                    $jenispayment = 'dp';
                                    if ($pembayaranpo == 'Lunas') {
                                        $dp          = $subtotal;
                                        $namapayemnt = 'Pembayaran';
                                    }
                                ?>
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
                                    <tr><th>Status PO</th><td>:</td><td><?= htmlspecialchars($data2['status'] ?? ''); ?></td></tr>
                                <?php elseif ($pembayaranpo != 'Lunas'): ?>
                                    <tr><th>Jumlah DP PO <?= $angkadp; ?>%</th><td>:</td><td>Rp. <?= number_format($dp); ?></td></tr>
                                    <?php
                                        $stmtDp = $koneksi->prepare("SELECT popembayaran.invoice, popembayaran.jmlhtransfer, popembayaran.jmlh_lunas FROM `popembayaran` WHERE popembayaran.invoice = ?");
                                        $stmtDp->bind_param('s', $invoice);
                                        $stmtDp->execute();
                                        $datadp = $stmtDp->get_result()->fetch_assoc();
                                    ?>
                                    <tr>
                                        <th>Status PO</th><td>:</td>
                                        <td><?= $idpoproduk != 161 ? htmlspecialchars($data2['status'] ?? '') : 'Sudah Confirm Payment' ?></td>
                                    </tr>
                                    <?php if (!empty($datadp['invoice']) && $idpoproduk != 161): ?>
                                        <tr><th>Konfirmasi DP</th><td>:</td><td>Rp. <?= number_format($datadp['jmlhtransfer']); ?></td></tr>
                                        <tr><th>Konfirmasi Pelunasan</th><td>:</td><td>Rp. <?= number_format($datadp['jmlh_lunas']); ?></td></tr>
                                    <?php endif; ?>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                    <p><strong>Note: </strong><?= htmlspecialchars($note ?? '') ?></p>

                    <div class="rekening-card mb-3">
                        <h6>Rekening Transfer Bank WNJ.ID Pusat</h6>
                        <div class="small mb-2">
                            <strong>Mandiri</strong> - CV KAFAA BILLAHI SYAHIDA - 1300026727597
                        </div>
                        <div class="small mb-2">
                            <strong>BCA</strong> - MARIA ULFAH FATHIMAH - 7751616671
                        </div>
                        <div class="small mb-2">
                            <strong>BRI</strong> - MARIA ULFAH FATHIMAH - 114101000949560
                        </div>
                        <div class="small mb-2">
                            <strong>Muamalat</strong> - MARIA ULFAH FATHIMAH - 1100003930
                        </div>
                        <div class="small mb-0">
                            <strong>BSI</strong> - MARIA ULFAH FATHIMAH - 7105696706
                        </div>
                    </div>

                    <div class="text-center mb-3">
                        <a class="btn btn-outline-success btn-sm" href="../distributor/invoice.php?idmitra=<?= urlencode($idMitra); ?>&id=<?= $idpoproduk ?>&invoice=<?= urlencode($invoice) ?>">Download PDF</a>
                    </div>

                    <?php if ($idpoproduk == 167 || $idpoproduk == 173 || $idpoproduk == 176): ?>
                        <?php if ($angka1 % 3 != 0): ?>
                            <div class="alert alert-danger">Khimar Anas Paket M+L Belum Kelipatan 3, Silahkan Ubah Stok</div>
                        <?php endif; ?>
                        <?php if ($angka2 % 3 != 0):?>
                            <div class="alert alert-danger">Khimar Anas Paket XL+JMB Belum Kelipatan 3, Silahkan Ubah Stok</div>
                        <?php endif; ?>
                        <?php if ($angka3 % 3 != 0): ?>
                            <div class="alert alert-danger">Dress Anas Paket Belum Kelipatan 3, Silahkan Ubah Stok</div>
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
                                <div class="text-center mb-2">
                                    <a class="btn btn-primary btn-block" href="popembayaran.php?invoice=<?= urlencode($invoice) ?>&total=<?= urlencode($jumlahBayarTermin) ?>&idpo=<?= $idpoproduk ?>&jenis=<?= urlencode($jenisPaymentTermin) ?>&termin=<?= (int) $nextTermin['seq'] ?>">Konfirmasi <?= htmlspecialchars($jenisPaymentTermin) ?> (Rp. <?= number_format($jumlahBayarTermin) ?>)</a>
                                </div>
                            <?php else: ?>
                                <div class="text-center mb-2"><span class="badge badge-success p-2">LUNAS</span></div>
                            <?php endif; ?>
                        <?php elseif (empty($datadp['invoice']) && $idpoproduk != 183 && $idpoproduk != 186 && $idpoproduk != 187 && $idpoproduk != 194): ?>
                            <?php if ($datapo['ket'] == 'Perpanjang' || $tgl_bayar == ""): ?>
                                <?php
                                    date_default_timezone_set('Asia/Jakarta');
                                    $tgl_bayar   = date('Y-m-d', strtotime('+1 days', strtotime($datapo['tgl'])));
                                    $waktu_bayar = $datapo['waktu'];
                                ?>
                            <?php endif; ?>
                            <div class="text-center mb-2">
                                <a class="btn btn-primary btn-block" href="popembayaran.php?invoice=<?= urlencode($invoice) ?>&total=<?= urlencode($dp) ?>&bayar=<?= urlencode($subtotal) ?>&idpo=<?= $idpoproduk ?>&jenis=<?= urlencode($jenispayment) ?>">Konfirmasi <?= htmlspecialchars($namapayemnt) ?></a>
                            </div>
                            <?php if ($idpoproduk != 257): ?>
                                <p id="demomiki" class="text-center small text-muted"></p>
                            <?php endif; ?>
                            <script>
                                var countDownDatemiki = new Date("<?= htmlspecialchars($tgl_bayar); ?> <?= htmlspecialchars($waktu_bayar); ?>").getTime();
                                var xInterval = setInterval(function() {
                                    var distance = countDownDatemiki - new Date().getTime();
                                    var days = Math.floor(distance / 86400000);
                                    var hours = Math.floor((distance % 86400000) / 3600000);
                                    var minutes = Math.floor((distance % 3600000) / 60000);
                                    var seconds = Math.floor((distance % 60000) / 1000);
                                    var el = document.getElementById("demomiki");
                                    if (el) el.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                                    if (distance < 0) {
                                        clearInterval(xInterval);
                                        if (el) el.innerHTML = "Melebihi batas waktu konfirmasi Payment PO";
                                    }
                                }, 1000);
                            </script>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (empty($data_termin) && ($data2['status'] ?? '') == "Sudah DP"): ?>
                        <div class="text-center mb-2">
                            <a class="btn btn-primary btn-block" href="popembayaran.php?invoice=<?= urlencode($invoice) ?>&total=<?= urlencode($sisalunas ?? '') ?>&idpo=<?= $idpoproduk ?>&jenis=lunas">Konfirmasi Pelunasan</a>
                        </div>
                    <?php endif ?>

                    <?php if (($data2['status'] ?? '') == "Belum DP"): ?>
                        <?php
                            $stmtBukapo = $koneksi->prepare("SELECT bukapo.idbpo, bukapo.jenis_mitra, bukapo.jenis_po, bukapo.idpoproduk,
                                                            bukapo.tgl, bukapo.tgl_acc_db, bukapo.tgl_ubah, bukapo.tgl_dropship, bukapo.status, poproduk.namapo
                                                            FROM bukapo
                                                            INNER JOIN poproduk ON bukapo.idpoproduk = poproduk.idpoproduk
                                                            WHERE poproduk.idpoproduk = ? AND bukapo.status = 'PUBLISH'
                                                            AND (bukapo.jenis_mitra = 'Semua Mitra' OR bukapo.jenis_mitra = ?)");
                            $stmtBukapo->bind_param('is', $idpoproduk, $mitraCfg['label']);
                            $stmtBukapo->execute();
                            $dataproduk = $stmtBukapo->get_result();
                            while ($tampilkan = $dataproduk->fetch_assoc()) {
                        ?>
                            <?php if ($tampilkan['jenis_po'] == "PO dengan Stok"): ?>
                                <div class="text-center mb-2">
                                    <a class="btn btn-outline-primary btn-sm" href="../distributor/ubahpostok.php?id=<?= $idpoproduk; ?>&invoice=<?= urlencode($invoice); ?>">Ubah <?= htmlspecialchars($tampilkan['namapo']); ?></a>
                                </div>
                            <?php elseif ($tampilkan['jenis_po'] == "PO Mandiri"): ?>
                                <div class="text-center mb-2">
                                    <a class="btn btn-outline-primary btn-sm" href="../distributor/ubahpo_mandiri.php?id=<?= $idpoproduk; ?>&invoice=<?= urlencode($invoice); ?>">Ubah <?= htmlspecialchars($tampilkan['namapo']); ?></a>
                                </div>
                            <?php endif ?>

                            <?php if ($tampilkan['jenis_po'] == "PO tanpa Stok" || $tampilkan['jenis_po'] == "PO Custom Tab" || $tampilkan['idpoproduk'] == 292 || $tampilkan['idpoproduk'] == 333 || $tampilkan['jenis_po'] == "PO Konin") : ?>
                                <div class="text-center mb-2">
                                    <a class="btn btn-outline-primary btn-sm" href="ubahpo.php?id=<?= $idpoproduk; ?>&invoice=<?= urlencode($invoice); ?>">Ubah <?= htmlspecialchars($tampilkan['namapo']); ?></a>
                                </div>
                            <?php endif ?>

                            <?php if ($tampilkan['jenis_po'] == "PO Custom Tab Stok"): ?>
                                <div class="d-flex justify-content-center mb-2" style="gap:.5rem;">
                                    <a class="btn btn-outline-primary btn-sm" href="../distributor/ubahpostok.php?id=<?= $idpoproduk; ?>&invoice=<?= urlencode($invoice) ?>">Ubah <?= htmlspecialchars($tampilkan['namapo']); ?></a>
                                    <a class="btn btn-outline-secondary btn-sm" href="../distributor/formpo_tabtambahstok.php?id=<?= (int) $tampilkan['idpoproduk']; ?>&invoice=<?= urlencode($invoice); ?>">Tambah Variant</a>
                                </div>
                            <?php endif ?>

                            <?php if ($tampilkan['jenis_po'] == "PO Custom Tab Stok Max"): ?>
                                <div class="text-center mb-2">
                                    <a class="btn btn-outline-primary btn-sm" href="../distributor/ubahpo_tabmax.php?id=<?= $idpoproduk; ?>">Ubah <?= htmlspecialchars($tampilkan['namapo']); ?></a>
                                </div>
                            <?php endif ?>
                        <?php } ?>
                    <?php endif ?>

                    <?php if (($data2['status'] ?? '') != "Belum DP"): ?>
                        <div class="text-center">
                            <a href="../distributor/excel_po.php?invoice=<?= urlencode($invoice); ?>" class="btn btn-outline-success btn-sm">Excel Invoice</a>
                        </div>
                    <?php endif ?>
                </div>
            </div>

            <div id="tabprogres" class="tab-pane fade">
                <div class="invoice-card">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>No</th><th>Nama Barang</th><th>Qty PO</th><th>Progres</th><th>Sisa</th><th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no          = 1;
                                    $sum_progres = 0;
                                    $sum_jumlah  = 0;
                                    $sum_sisa    = 0;

                                    $stmtProgres = $koneksi->prepare("SELECT pomitra.idpomitra, pomitra.jumlah, pomitra.invoice, pomitra.total, pomitra.custom,
                                                                            podetail.variant, podetail.harga, SUM(surat_jalan_po.progres) AS progres
                                                                        FROM pomitra
                                                                        INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                        LEFT JOIN surat_jalan_po ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                                        WHERE pomitra.$kolomRole = ? AND pomitra.idpoproduk = ? AND pomitra.invoice = ? AND pomitra.jumlah > 0
                                                                        GROUP BY podetail.variant, pomitra.idpomitra, pomitra.jumlah, pomitra.invoice, pomitra.total, podetail.harga, pomitra.custom
                                                                        ORDER BY podetail.variant ASC");
                                    $stmtProgres->bind_param('iis', $idMitra, $idpoproduk, $invoice);
                                    $stmtProgres->execute();
                                    $progresResult = $stmtProgres->get_result();

                                    while ($row = $progresResult->fetch_assoc()) {
                                        $sisa = $row['jumlah'] - $row['progres'];
                                ?>
                                <tr>
                                    <td class="align-middle"><?= $no++; ?></td>
                                    <td class="align-middle"><?= htmlspecialchars($row['variant']); ?></td>
                                    <td class="align-middle"><?= (int) $row['jumlah']; ?></td>
                                    <td class="align-middle"><?= $row['progres'] == "" ? 0 : (int) $row['progres']; ?></td>
                                    <td class="align-middle"><?= $sisa; ?></td>
                                    <td class="align-middle">
                                        <?php if ($sisa == 0) : ?>
                                            <span class="badge badge-success p-1">Selesai</span>
                                        <?php else : ?>
                                            <span class="badge badge-warning p-1">Progres</span>
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
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
