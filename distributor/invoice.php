<?php
    error_reporting(0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $invoice    = $_GET['invoice'] ?? '';
    $idadmin    = $_SESSION["idadmin"];

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

    if (!$datapo) {
        header('Location: listnewpo.php');
        exit;
    }

    $stmtData2 = $koneksi->prepare("SELECT
                    pomitra.status,
                    pomitra.invoice,
                    pomitra.custom
                FROM poproduk
                inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk
                WHERE poproduk.idpoproduk = ? AND pomitra.idmitra = ? AND pomitra.invoice = ?");
    $stmtData2->bind_param('iss', $idpoproduk, $idadmin, $invoice);
    $stmtData2->execute();
    $data2 = $stmtData2->get_result()->fetch_assoc();

    $note         = $datapo['note'];
    $pembayaranpo = $datapo['pembayaran'];
    $invoice      = $datapo['invoice'];

    $stmtTgl = $koneksi->prepare("SELECT bukapo.idpoproduk, bukapo.tgl_bayar, jenis_po
                    FROM bukapo
                    WHERE bukapo.idpoproduk = ?");
    $stmtTgl->bind_param('i', $idpoproduk);
    $stmtTgl->execute();
    $datapo_tgl  = $stmtTgl->get_result()->fetch_assoc();
    $tgl_bayar   = $datapo_tgl['tgl_bayar'] ?? '';
    $waktu_bayar = '23:59:59';

    $angka1 = 3;
    $angka2 = 3;
    $angka3 = 3;
    $angka5 = 0;
    $angka6 = 0;

    if ($idpoproduk == 167 || $idpoproduk == 173 || $idpoproduk == 176) {
        $stmtA1 = $koneksi->prepare("SELECT SUM(pomitra.jumlah) as jumlahnya
                    FROM pomitra
                    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                    WHERE (podetail.variant LIKE '%Sz M Paket%' or podetail.variant LIKE '%Sz L Paket%') and pomitra.invoice = ?
                    GROUP BY pomitra.invoice");
        $stmtA1->bind_param('s', $invoice);
        $stmtA1->execute();
        $data3  = $stmtA1->get_result()->fetch_assoc();
        $angka1 = $data3['jumlahnya'] ?? 3;

        $stmtA2 = $koneksi->prepare("SELECT SUM(pomitra.jumlah) as jumlahnyaa
                    FROM pomitra
                    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                    WHERE (podetail.variant LIKE '%Sz XL Paket%' or podetail.variant LIKE '%Sz JMB Paket%') and pomitra.invoice = ?
                    GROUP BY pomitra.invoice");
        $stmtA2->bind_param('s', $invoice);
        $stmtA2->execute();
        $data4  = $stmtA2->get_result()->fetch_assoc();
        $angka2 = $data4['jumlahnyaa'] ?? 3;
    }

    if ($idpoproduk == 174) {
        $stmtA3 = $koneksi->prepare("SELECT SUM(pomitra.jumlah) as jumlahnya
                    FROM pomitra
                    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                    WHERE (podetail.variant LIKE '%Paket%') and pomitra.invoice = ?
                    GROUP BY pomitra.invoice");
        $stmtA3->bind_param('s', $invoice);
        $stmtA3->execute();
        $data7  = $stmtA3->get_result()->fetch_assoc();
        $angka3 = $data7['jumlahnya'] ?? 3;
    }

    $stmtUser  = $koneksi->prepare("SELECT * FROM admin_mitra WHERE idadmin = ?");
    $stmtUser->bind_param('s', $idadmin);
    $stmtUser->execute();
    $queryUser = $stmtUser->get_result()->fetch_assoc();

    $custom = $data2['custom'] ?? null;
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
    <div class="container mt-3" id="invoice-content">
        <h2 class="text-center mb-4">SALES INVOICE</h2>
        <p class="text-center"><strong><?= htmlspecialchars($datapo['namapo']) ?></strong></p><br>
        <p class="text-left">Nama Mitra  : <?= htmlspecialchars($queryUser["namamitra"] ?? '') ?> </p>
        <p class="text-left">Alamat  : <?= htmlspecialchars($queryUser["alamat"] ?? '') ?> </p>
        <p class="text-left">No Invoice  : <?= htmlspecialchars($invoice) ?> </p>
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
                            <?php if (!empty($custom)) : ?>
                                <th>Custom</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no      = 1;
                            $jumlah  = 0;
                            $sum     = 0;

                            $stmtItems = $koneksi->prepare("SELECT podetail.variant,
                                                                    podetail.harga,
                                                                    pomitra.jumlah,
                                                                    pomitra.total,
                                                                    pomitra.idpomitra,
                                                                    poproduk.namapo,
                                                                    poproduk.diskon,
                                                                    pomitra.custom
                                                                FROM pomitra
                                                                JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                JOIN poproduk ON poproduk.idpoproduk = pomitra.idpoproduk
                                                                WHERE pomitra.invoice = ?
                                                                AND pomitra.jumlah > 0");
                            $stmtItems->bind_param('s', $invoice);
                            $stmtItems->execute();
                            $itemsResult = $stmtItems->get_result();

                            while ($row = $itemsResult->fetch_assoc()) { ?>
                            <tr>
                                <td class="align-middle"><?= $no++; ?></td>
                                <td class="align-middle">
                                    <?= htmlspecialchars($row['variant']); ?>
                                    <?php if ($idpoproduk == 186): ?>
                                        <?= htmlspecialchars($row['custom'] ?? ''); ?>
                                    <?php endif ?>
                                </td>
                                <td class="align-middle">
                                    <?= (int) $row['jumlah']; ?>
                                </td>
                                <?php if ($idpoproduk != 186 && $idpoproduk != 187): ?>
                                    <td class="align-middle">Rp. <?= number_format($row['harga']); ?></td>
                                    <td class="align-middle">Rp. <?= number_format($row['total']); ?></td>
                                <?php endif ?>
                                <?php if (!empty($custom)) : ?>
                                    <td><?= htmlspecialchars($row['custom'] ?? '') ?></td>
                                <?php endif ?>
                                <?php
                                $angkavoal = $row['jumlah'];
                                $sum += $row['jumlah'];
                                $jumlah += $row['jumlah'] * $row['harga'];
                                $persen_tambahan = $row['diskon'];
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
                                } else {
                                    $persen = 35;
                                    $diskon = 35 / 100 * $jumlah;
                                }
                                $diskon_tambahan = $persen_tambahan / 100 * $jumlah;
                                $subtotal = $jumlah - $diskon - $diskon_tambahan;
                            ?>
                            <tr>
                                <th>Diskon DB <?= $persen; ?>%</th>
                                <td>:</td>
                                <td>Rp. <?= number_format($diskon); ?></td>
                            </tr>
                            <?php if ($diskon_tambahan > 0): ?>
                            <tr>
                                <th>Diskon Tambahan</th>
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
                            if ($idpoproduk == 220) {
                                $angkadp = 30;
                                $dp = ($subtotal ?? 0) * $angkadp / 100;
                            } else {
                                $angkadp = 50;
                                $dp = ($subtotal ?? 0) * $angkadp / 100;
                            }
                            if ($idpoproduk == 228) {
                                $namapayemnt = 'Pembayaran';
                            } else {
                                $namapayemnt = 'DP';
                            }
                            $jenispayment = 'dp';
                            if ($pembayaranpo == 'Lunas') {
                                $dp = $subtotal ?? 0;
                                $namapayemnt = 'Pembayaran';
                            } ?>
                        <?php if ($pembayaranpo != 'Lunas'): ?>
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
                                    <?= htmlspecialchars($data2['status'] ?? '') ?>
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
                                <?php echo number_format($sisa ?? 0); ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <p align="left"><strong>Note: </strong><?= htmlspecialchars($note ?? '') ?></p>
            </div>
        </div>
    </div>
    <?php
        $jenis_po = $datapo_tgl['jenis_po'] ?? '';
        $redirect = ($jenis_po == 'PO Custom Inisial') ? 'datapocustom3.php' : 'datapo.php';
    ?>
    <script>
        // Tunggu halaman benar-benar siap
        window.onload = function () {
            const element = document.getElementById('invoice-content');

            const opt = {
                margin:       0.5,
                filename:     'Invoice-<?= htmlspecialchars($invoice) ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            // Buat PDF dan auto-download
            html2pdf().set(opt).from(element).save().then(() => {
                // Redirect sesuai jenis_po
                window.location.href = "<?= $redirect ?>?idmitra=<?= urlencode($idadmin) ?>&id=<?= $idpoproduk ?>&invoice=<?= urlencode($invoice) ?>";
            });
        };
    </script>
</body>
</html>
