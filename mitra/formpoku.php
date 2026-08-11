<?php
    include 'koneksi.php';
    include 'session_guard.php';
    include '../includes/mitra_role_helper.php';

    $idpoproduk = (int) ($_GET['id'] ?? 0);
    $kolomRole  = mitraKolomPomitra($mitraRole);

    $stmtProduk = $koneksi->prepare("SELECT idpoproduk, namapo FROM poproduk WHERE idpoproduk = ?");
    $stmtProduk->bind_param('i', $idpoproduk);
    $stmtProduk->execute();
    $produk = $stmtProduk->get_result()->fetch_assoc();

    $variantExcluded = [4413, 4382, 4351];
    $stmtVariant = $koneksi->prepare("SELECT podetail.idpo, podetail.idpodetail, podetail.variant, podetail.harga
                                        FROM podetail
                                        INNER JOIN pokategori ON pokategori.idpo = podetail.idpo
                                        WHERE pokategori.idpoproduk = ?
                                          AND podetail.variant NOT LIKE '%Custom%'
                                          AND podetail.idpo NOT IN (4413, 4382, 4351)
                                        ORDER BY podetail.idpodetail ASC");
    $stmtVariant->bind_param('i', $idpoproduk);
    $stmtVariant->execute();
    $variantList = $stmtVariant->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmtCekInvoice = $koneksi->prepare("SELECT invoice FROM pomitra WHERE idpoproduk = ? AND $kolomRole = ? LIMIT 1");
    $stmtCekInvoice->bind_param('ii', $idpoproduk, $idMitra);
    $stmtCekInvoice->execute();
    $invoiceLama = $stmtCekInvoice->get_result()->fetch_assoc();

    $pesan = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save']) && !$invoiceLama) {
        $idpodetail  = $_POST['idpodetail'] ?? [];
        $jmlh        = $_POST['jmlh'] ?? [];
        $jumlahBaris = count($jmlh);
        $totalQty    = array_sum(array_map('intval', $jmlh));

        if ($idpoproduk === 486 && $totalQty > 2) {
            $pesan = 'Total qty tidak boleh lebih dari 2.';
        } elseif ($idpoproduk === 545 && $totalQty % 2 !== 0) {
            $pesan = 'Total qty harus kelipatan 2.';
        } else {
            $invoice = mitraBuatInvoicePO($mitraRole, $idMitra, $idpoproduk) . mt_rand(10000, 99999);

            $koneksi->begin_transaction();
            try {
                $stmtDetail = $koneksi->prepare("SELECT idpo, harga FROM podetail WHERE idpodetail = ?");
                $stmtInsert = $koneksi->prepare("INSERT INTO pomitra ($kolomRole, idpoproduk, idpo, idpodetail, jumlah, total, invoice, status, tgl, waktu)
                                                    VALUES (?, ?, ?, ?, ?, ?, ?, 'Belum DP', NOW(), ?)");
                $waktu = date('H:i:s');

                for ($x = 0; $x < $jumlahBaris; $x++) {
                    $qty = (int) $jmlh[$x];
                    $stmtDetail->bind_param('i', $idpodetail[$x]);
                    $stmtDetail->execute();
                    $detail = $stmtDetail->get_result()->fetch_assoc();
                    if (!$detail) {
                        continue;
                    }
                    $total = $qty * (int) $detail['harga'];

                    $stmtInsert->bind_param('iiiiidss', $idMitra, $idpoproduk, $detail['idpo'], $idpodetail[$x], $qty, $total, $invoice, $waktu);
                    $stmtInsert->execute();
                }

                $koneksi->commit();

                // Beberapa jenis PO lama punya halaman invoice sendiri (belum ikut dimigrasi).
                if ($idpoproduk === 271) {
                    $tujuan = '../distributor/datapom3.php';
                } elseif (in_array($idpoproduk, [405, 406, 407], true)) {
                    $tujuan = '../distributor/datapokolibri3.php';
                } else {
                    $tujuan = 'datapo.php';
                }
                header('Location: ' . $tujuan . '?id=' . $idpoproduk . '&invoice=' . urlencode($invoice));
                exit;
            } catch (Exception $e) {
                $koneksi->rollback();
                error_log($e->getMessage());
                $pesan = 'Gagal menyimpan pesanan, silakan coba lagi.';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Form PO Tanpa Stok | WNJ.ID</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <style>
        body { background: var(--wnj-bg); }
        .form-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.25rem;
            margin-bottom: 1rem;
        }
        .variant-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            padding: .6rem 0;
            border-bottom: 1px solid var(--wnj-border);
        }
        .variant-row:last-child { border-bottom: none; }
        .qty-control {
            display: flex;
            align-items: center;
            gap: .4rem;
            flex-shrink: 0;
        }
        .qty-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid var(--wnj-border);
            background: #fff;
            font-weight: 700;
            color: var(--wnj-cta);
            line-height: 1;
        }
        .qty-input {
            width: 64px;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 640px;">
        <div class="d-flex align-items-center mt-3 mb-3" style="gap:.75rem;">
            <a href="preorder.php" class="text-muted"><i class="bi bi-arrow-left"></i></a>
            <h5 class="font-weight-bold mb-0">Formulir Pemesanan <?= htmlspecialchars($produk['namapo'] ?? '') ?></h5>
        </div>

        <?php if ($pesan !== ''): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($pesan) ?></div>
        <?php endif; ?>

        <?php if ($invoiceLama): ?>
            <?php
                if ($idpoproduk === 271) {
                    $tujuanInvoice = '../distributor/datapom3.php';
                } elseif (in_array($idpoproduk, [405, 406, 407], true)) {
                    $tujuanInvoice = '../distributor/datapokolibri3.php';
                } else {
                    $tujuanInvoice = 'datapo.php';
                }
            ?>
            <div class="form-card text-center">
                <p class="text-muted mb-3">Kamu sudah memesan PO ini.</p>
                <a href="<?= $tujuanInvoice ?>?id=<?= $idpoproduk ?>&invoice=<?= urlencode($invoiceLama['invoice']) ?>" class="btn btn-primary">Lihat Invoice</a>
            </div>
        <?php else: ?>
            <form method="post" id="formPoku">
                <div class="form-card">
                    <?php foreach ($variantList as $v): ?>
                        <div class="variant-row">
                            <div><?= htmlspecialchars($v['variant']) ?></div>
                            <input type="hidden" name="idpodetail[]" value="<?= (int) $v['idpodetail'] ?>">
                            <div class="qty-control">
                                <button type="button" class="qty-btn qty-minus">&minus;</button>
                                <input type="number" min="0" name="jmlh[]" class="form-control qty-input" value="0" required>
                                <button type="button" class="qty-btn qty-plus">&plus;</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p class="text-muted small">* Biarkan angka 0 pada variant yang tidak dipesan.</p>
                <button type="submit" name="save" class="btn btn-primary btn-block">Kirim Pesanan</button>
            </form>
            <script>
                document.getElementById('formPoku').addEventListener('click', function (e) {
                    if (!e.target.classList.contains('qty-plus') && !e.target.classList.contains('qty-minus')) {
                        return;
                    }
                    var input = e.target.parentElement.querySelector('.qty-input');
                    var value = parseInt(input.value, 10) || 0;
                    input.value = e.target.classList.contains('qty-plus') ? value + 1 : Math.max(0, value - 1);
                });
            </script>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
