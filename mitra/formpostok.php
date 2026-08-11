<?php
    include 'koneksi.php';
    include 'session_guard.php';
    include '../includes/mitra_role_helper.php';

    $idpoproduk = (int) ($_GET['id'] ?? 0);
    $kolomRole  = mitraKolomPomitra($mitraRole);

    $stmtProduk = $koneksi->prepare("SELECT idpoproduk, namapo, status FROM poproduk WHERE idpoproduk = ?");
    $stmtProduk->bind_param('i', $idpoproduk);
    $stmtProduk->execute();
    $produk = $stmtProduk->get_result()->fetch_assoc();

    // Sebagian produk/variant memang sengaja tidak nampilin sisa stok (aturan lama, dipertahankan).
    $excludedIds     = [181, 182, 183, 362, 382, 395, 394, 399, 400, 405];
    $excludedVariant = [6356, 6357, 6358, 6359, 6360, 6366, 6367, 6368, 6369, 6370, 6371, 6372, 6373, 6540, 6541, 6542, 6543, 6544, 6550, 6551, 6552, 6553, 6554, 6555, 6556, 6557];
    $tampilkanStok   = !in_array($idpoproduk, $excludedIds, true);

    $stmtVariant = $koneksi->prepare("SELECT pokategori.idpo, pokategori.namakategori, pokategori.stok,
                                            podetail.idpodetail, podetail.variant, podetail.harga
                                        FROM pokategori
                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                        WHERE pokategori.idpoproduk = ? AND podetail.variant NOT LIKE '%Custom%'
                                        ORDER BY podetail.idpodetail ASC");
    $stmtVariant->bind_param('i', $idpoproduk);
    $stmtVariant->execute();
    $variantList = $stmtVariant->get_result()->fetch_all(MYSQLI_ASSOC);

    // Kalau sudah pernah order utk PO ini, tampilkan link ke invoice yang sudah ada
    // (bukan form baru) - dicek per role, bukan cuma kolom idmitra kayak versi lama.
    $stmtCekInvoice = $koneksi->prepare("SELECT invoice FROM pomitra WHERE idpoproduk = ? AND $kolomRole = ? LIMIT 1");
    $stmtCekInvoice->bind_param('ii', $idpoproduk, $idMitra);
    $stmtCekInvoice->execute();
    $invoiceLama = $stmtCekInvoice->get_result()->fetch_assoc();

    $pesan = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save']) && !$invoiceLama) {
        $idpo       = $_POST['idpo'] ?? [];
        $idpodetail = $_POST['idpodetail'] ?? [];
        $jmlh       = $_POST['jmlh'] ?? [];
        $harga      = $_POST['harga'] ?? [];
        $jumlahBaris = count($jmlh);
        $totalQty    = array_sum(array_map('intval', $jmlh));

        // Aturan lama: PO id 486 dibatasi maksimal 2 qty per order.
        if ($idpoproduk === 486 && $totalQty > 2) {
            $pesan = 'Total qty tidak boleh lebih dari 2.';
        } else {
            $invoice = mitraBuatInvoicePO($mitraRole, $idMitra, $idpoproduk) . mt_rand(10000, 99999);

            $koneksi->begin_transaction();
            try {
                $stmtCekStok = $koneksi->prepare("SELECT stok FROM pokategori WHERE idpo = ? FOR UPDATE");
                $stmtInsert  = $koneksi->prepare("INSERT INTO pomitra ($kolomRole, idpoproduk, idpo, idpodetail, jumlah, total, invoice, status, tgl, waktu)
                                                    VALUES (?, ?, ?, ?, ?, ?, ?, 'Belum DP', NOW(), ?)");
                $stmtKurangiStok = $koneksi->prepare("UPDATE pokategori SET stok = stok - ? WHERE idpo = ?");
                $waktu = date('H:i:s');

                for ($x = 0; $x < $jumlahBaris; $x++) {
                    $qty = (int) $jmlh[$x];
                    $stmtCekStok->bind_param('i', $idpo[$x]);
                    $stmtCekStok->execute();
                    $sisa = (int) ($stmtCekStok->get_result()->fetch_assoc()['stok'] ?? 0);

                    $qtyDisimpan = $sisa >= $qty ? $qty : 0;
                    $totalBaris  = $qtyDisimpan * (int) $harga[$x];

                    $stmtInsert->bind_param('iiiiidss', $idMitra, $idpoproduk, $idpo[$x], $idpodetail[$x], $qtyDisimpan, $totalBaris, $invoice, $waktu);
                    $stmtInsert->execute();

                    if ($qtyDisimpan > 0) {
                        $stmtKurangiStok->bind_param('ii', $qtyDisimpan, $idpo[$x]);
                        $stmtKurangiStok->execute();
                    }
                }

                $koneksi->commit();
                header('Location: datapo.php?id=' . $idpoproduk . '&invoice=' . urlencode($invoice));
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
    <title>Form PO Stok | WNJ.ID</title>
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
        .variant-row input[type="number"] {
            width: 80px;
        }
        .stok-pill {
            font-size: .72rem;
            color: var(--wnj-text-secondary);
            background: var(--wnj-bg-soft);
            padding: 1px 8px;
            border-radius: 999px;
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
            <div class="form-card text-center">
                <p class="text-muted mb-3">Kamu sudah memesan PO ini.</p>
                <a href="datapo.php?id=<?= $idpoproduk ?>&invoice=<?= urlencode($invoiceLama['invoice']) ?>" class="btn btn-primary">Lihat Invoice</a>
            </div>
        <?php else: ?>
            <form method="post">
                <div class="form-card">
                    <?php foreach ($variantList as $v): ?>
                        <div class="variant-row">
                            <div>
                                <?= htmlspecialchars($v['variant']) ?>
                                <?php if ($tampilkanStok && !in_array($v['idpo'], $excludedVariant, true)): ?>
                                    <span class="stok-pill">sisa <?= (int) $v['stok'] ?></span>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="idpo[]" value="<?= (int) $v['idpo'] ?>">
                            <input type="hidden" name="idpodetail[]" value="<?= (int) $v['idpodetail'] ?>">
                            <input type="hidden" name="harga[]" value="<?= (int) $v['harga'] ?>">
                            <input type="number" min="0" max="<?= (int) $v['stok'] ?>" required name="jmlh[]" class="form-control form-control-sm" value="0">
                        </div>
                    <?php endforeach; ?>
                </div>
                <p class="text-muted small">* Jangan kosongkan kolom, isi 0 kalau tidak memesan variant tersebut.</p>
                <button type="submit" name="save" class="btn btn-primary btn-block">Kirim Pesanan</button>
            </form>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
