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

    $stmtCekInvoice = $koneksi->prepare("SELECT invoice FROM pomitra WHERE idpoproduk = ? AND $kolomRole = ? LIMIT 1");
    $stmtCekInvoice->bind_param('ii', $idpoproduk, $idMitra);
    $stmtCekInvoice->execute();
    $invoiceLama = $stmtCekInvoice->get_result()->fetch_assoc();

    $stmtTab = $koneksi->prepare("SELECT id, nama_tab, id_awal, id_akhir FROM bukapo_tab WHERE idpoproduk = ? ORDER BY id ASC");
    $stmtTab->bind_param('i', $idpoproduk);
    $stmtTab->execute();
    $tabList = $stmtTab->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmtVariantTab = $koneksi->prepare("SELECT podetail.idpodetail, podetail.variant
                                            FROM podetail
                                            INNER JOIN pokategori ON pokategori.idpo = podetail.idpo
                                            WHERE pokategori.idpoproduk = ? AND podetail.idpodetail BETWEEN ? AND ?
                                            ORDER BY pokategori.idpo ASC");

    $pesan = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save']) && !$invoiceLama) {
        $idpodetail  = $_POST['idpodetail'] ?? [];
        $jmlh        = $_POST['jmlh'] ?? [];
        $jumlahBaris = count($jmlh);

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

            // PO id 499 ke atas punya halaman invoice sendiri (belum ikut dimigrasi).
            $tujuan = $idpoproduk >= 499 ? '../distributor/datapo2.php' : 'datapo.php';
            header('Location: ' . $tujuan . '?id=' . $idpoproduk . '&invoice=' . urlencode($invoice));
            exit;
        } catch (Exception $e) {
            $koneksi->rollback();
            error_log($e->getMessage());
            $pesan = 'Gagal menyimpan pesanan, silakan coba lagi.';
        }
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Form PO Custom Tab | WNJ.ID</title>
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
        .nav-tabs {
            border-bottom-color: var(--wnj-border);
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .nav-tabs .nav-link {
            white-space: nowrap;
            color: var(--wnj-text-secondary);
            border: none;
            border-bottom: 2px solid transparent;
        }
        .nav-tabs .nav-link.active {
            color: var(--wnj-cta);
            border-color: var(--wnj-cta);
            background: none;
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
        .variant-row input[type="number"] {
            width: 80px;
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
            <?php $tujuanInvoice = $idpoproduk >= 499 ? '../distributor/datapo2.php' : 'datapo.php'; ?>
            <div class="form-card text-center">
                <p class="text-muted mb-3">Kamu sudah memesan PO ini.</p>
                <a href="<?= $tujuanInvoice ?>?id=<?= $idpoproduk ?>&invoice=<?= urlencode($invoiceLama['invoice']) ?>" class="btn btn-primary">Lihat Invoice</a>
            </div>
        <?php else: ?>
            <form method="post">
                <ul class="nav nav-tabs mb-3">
                    <?php foreach ($tabList as $i => $tab): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $i === 0 ? 'active' : '' ?>" data-toggle="tab" href="#tab<?= (int) $tab['id'] ?>"><?= htmlspecialchars($tab['nama_tab']) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="tab-content">
                    <?php foreach ($tabList as $i => $tab): ?>
                        <div id="tab<?= (int) $tab['id'] ?>" class="tab-pane fade <?= $i === 0 ? 'show active' : '' ?>">
                            <div class="form-card">
                                <?php
                                    $stmtVariantTab->bind_param('iii', $idpoproduk, $tab['id_awal'], $tab['id_akhir']);
                                    $stmtVariantTab->execute();
                                    $variantTab = $stmtVariantTab->get_result()->fetch_all(MYSQLI_ASSOC);
                                ?>
                                <?php foreach ($variantTab as $v): ?>
                                    <div class="variant-row">
                                        <div><?= htmlspecialchars($v['variant']) ?></div>
                                        <input type="hidden" name="idpodetail[]" value="<?= (int) $v['idpodetail'] ?>">
                                        <input type="number" min="0" required name="jmlh[]" class="form-control form-control-sm" value="0">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p class="text-muted small">* Isi 0 pada variant yang tidak dipesan.</p>
                <button type="submit" name="save" class="btn btn-primary btn-block">Kirim Pesanan</button>
            </form>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
