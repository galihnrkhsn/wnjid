<?php
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';
    include '../includes/order_status_helper.php';
    include '../includes/rajaongkir_helper.php';

    $idKonsumen = $_SESSION['idkonsumen'];
    $invoice    = trim($_GET['invoice'] ?? '');

    $stmtOrder = $koneksi->prepare("SELECT * FROM orderkonsumen WHERE invoice = ? AND idkonsumen = ?");
    $stmtOrder->bind_param('si', $invoice, $idKonsumen);
    $stmtOrder->execute();
    $order = $stmtOrder->get_result()->fetch_assoc();

    if (!$order) {
        header('Location: view_cart.php');
        exit;
    }

    if ($order['status'] === 'Menunggu Alamat') {
        header('Location: alamat.php?invoice=' . urlencode($invoice));
        exit;
    }

    $stmtDetail = $koneksi->prepare("SELECT * FROM orderkonsumen_detail WHERE idorder = ? ORDER BY iddetail ASC");
    $stmtDetail->bind_param('i', $order['idorder']);
    $stmtDetail->execute();
    $items = $stmtDetail->get_result()->fetch_all(MYSQLI_ASSOC);

    // No. resi hidup di t_user (diisi lewat portal kurir), bukan di orderkonsumen sendiri
    $stmtResi = $koneksi->prepare("SELECT resi_pengiriman FROM t_user WHERE invoice = ? AND resi_pengiriman IS NOT NULL AND resi_pengiriman != '' ORDER BY id_user DESC LIMIT 1");
    $stmtResi->bind_param('s', $invoice);
    $stmtResi->execute();
    $noResi = $stmtResi->get_result()->fetch_assoc()['resi_pengiriman'] ?? '';

    $kurirKode = kodeKurirDariEkspedisi($order['ekspedisi'] ?? '');

    $tracking = [];
    if ($noResi !== '' && $kurirKode !== '') {
        $tracking = cekDanTandaiTerkirim($koneksi, $order['idorder'], $order['status'], $noResi, $kurirKode);

        if (!empty($tracking['manifest'])) {
            usort($tracking['manifest'], function ($a, $b) {
                $tglA = strtotime(trim($a['date'] . ' ' . $a['time']));
                $tglB = strtotime(trim($b['date'] . ' ' . $b['time']));
                return $tglB <=> $tglA; // descending: terbaru dulu
            });
        }

        $sudahDelivered = ($tracking['delivered'] ?? false) === true || strtoupper($tracking['status'] ?? '') === 'DELIVERED';
        if ($sudahDelivered && !in_array($order['status'], ['Terkirim', 'Selesai', 'Dibatalkan'], true)) {
            $order['status'] = 'Terkirim'; // reflect the just-applied update immediately on this page load
        }
    }

    [$badgeColor, $badgeLabel] = orderKonsumenStatusBadge($order['status']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Ringkasan Pesanan | WNJ.ID</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: var(--wnj-bg); }
        .panel {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: .6rem 0;
            border-bottom: 1px solid var(--wnj-border);
        }
        .item-row:last-child {
            border-bottom: none;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: .3rem 0;
        }
        .total-row.grand {
            font-weight: 700;
            font-size: 1.1rem;
            border-top: 1px solid var(--wnj-border);
            margin-top: .5rem;
            padding-top: .75rem;
        }
        .tracking-event {
            display: flex;
            gap: .75rem;
            padding-bottom: 1rem;
            margin-left: .25rem;
            border-left: 2px solid var(--wnj-border);
            padding-left: 1rem;
            position: relative;
        }
        .tracking-event:last-child {
            padding-bottom: 0;
            border-left: 2px solid transparent;
        }
        .tracking-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--wnj-border);
            position: absolute;
            left: -6px;
            top: 4px;
        }
        .tracking-event.terbaru .tracking-dot {
            background: var(--wnj-cta);
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 720px;">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info text-center mt-3">
                <?= htmlspecialchars($_SESSION['message']) ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="panel mt-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <div class="text-muted small">Invoice</div>
                    <div class="font-weight-bold"><?= htmlspecialchars($order['invoice']) ?></div>
                </div>
                <span class="badge badge-<?= $badgeColor ?> p-2"><?= htmlspecialchars($badgeLabel) ?></span>
            </div>
            <div class="text-muted small">Dipesan pada <?= date('d M Y H:i', strtotime($order['tgl'])) ?></div>
        </div>

        <div class="panel">
            <h6 class="font-weight-bold mb-3">Barang Dipesan</h6>
            <?php foreach ($items as $item): ?>
                <div class="item-row">
                    <div>
                        <div class="font-weight-bold"><?= htmlspecialchars($item['namaproduk']) ?></div>
                        <div class="text-muted small"><?= htmlspecialchars($item['variant']) ?> <?= htmlspecialchars($item['size'] ?? '') ?></div>
                        <div class="small">Rp <?= number_format($item['harga']) ?> &times; <?= (int) $item['jumlah'] ?></div>
                    </div>
                    <div class="font-weight-bold">Rp <?= number_format($item['subtotal']) ?></div>
                </div>
            <?php endforeach; ?>

            <div class="total-row mt-2">
                <span>Subtotal</span>
                <span>Rp <?= number_format($order['subtotal']) ?></span>
            </div>
            <div class="total-row">
                <span>Ongkos Kirim</span>
                <span><?= $order['ongkir'] > 0 ? 'Rp ' . number_format($order['ongkir']) : 'Dikonfirmasi admin' ?></span>
            </div>
            <div class="total-row grand">
                <span>Total</span>
                <span>Rp <?= number_format($order['total']) ?></span>
            </div>
        </div>

        <div class="panel">
            <h6 class="font-weight-bold mb-2">Alamat Pengiriman</h6>
            <div class="font-weight-bold"><?= htmlspecialchars($order['nama_penerima']) ?></div>
            <div><?= htmlspecialchars($order['telepon_penerima']) ?></div>
            <div><?= htmlspecialchars($order['alamat_lengkap']) ?></div>
            <div class="text-muted small">
                <?= htmlspecialchars(trim(implode(', ', array_filter([$order['kecamatan'], $order['kota'], $order['provinsi'], $order['kodepos']]))))?>
            </div>
            <?php if (!empty($order['ekspedisi'])): ?>
                <div class="mt-2"><strong>Ekspedisi:</strong> <?= htmlspecialchars($order['ekspedisi']) ?></div>
            <?php endif; ?>
            <?php if ($noResi !== ''): ?>
                <div class="mt-2"><strong>No. Resi:</strong> <?= htmlspecialchars($noResi) ?></div>
            <?php endif; ?>
            <?php if (!empty($order['catatan'])): ?>
                <div class="mt-2"><strong>Catatan:</strong> <?= htmlspecialchars($order['catatan']) ?></div>
            <?php endif; ?>
            <!-- <a href="alamat.php?invoice=<?= urlencode($order['invoice']) ?>" class="small">Ubah alamat</a> -->
        </div>
        <?php if (empty($tracking)) : ?>
            <div class="panel text-center">
                <?php if ($order['status'] === 'Menunggu Pembayaran'): ?>
                    <a href="pembayaran.php?invoice=<?= urlencode($order['invoice']) ?>" class="btn btn-primary btn-block">
                        <i class="bi bi-credit-card"></i> Bayar Sekarang
                    </a>
                <?php elseif ($order['status'] === 'Menunggu Konfirmasi Admin'): ?>
                    <i class="bi bi-hourglass-split" style="font-size:2rem;color:var(--wnj-text-secondary);"></i>
                    <p class="text-muted mb-0 mt-2">Bukti pembayaran sudah diterima, menunggu konfirmasi admin.</p>
                <?php elseif ($order['status'] === 'Diproses'): ?>
                    <i class="bi bi-box-seam" style="font-size:2rem;color:var(--wnj-cta);"></i>
                    <p class="text-muted mb-0 mt-2">Pesanan sedang diproses & disiapkan untuk dikirim.</p>
                <?php elseif ($order['status'] === 'Menunggu Resi'): ?>
                    <i class="bi bi-box-seam" style="font-size:2rem;color:#fd7e14;"></i>
                    <p class="text-muted mb-0 mt-2">Pesanan sudah diserahkan ke kurir, menunggu nomor resi.</p>
                <?php elseif ($order['status'] === 'Sedang dalam perjalanan'): ?>
                    <i class="bi bi-truck" style="font-size:2rem;color:#0dcaf0;"></i>
                    <p class="text-muted mb-0 mt-2">Pesanan sedang dalam perjalanan menuju alamatmu.</p>
                <?php elseif ($order['status'] === 'Terkirim'): ?>
                    <i class="bi bi-check-circle" style="font-size:2rem;color:var(--wnj-success);"></i>
                    <p class="text-muted mb-0 mt-2">Pesanan sudah sampai. Terima kasih sudah berbelanja!</p>
                <?php elseif ($order['status'] === 'Selesai'): ?>
                    <i class="bi bi-check-circle" style="font-size:2rem;color:var(--wnj-success);"></i>
                    <p class="text-muted mb-0 mt-2">Pesanan selesai. Terima kasih sudah berbelanja!</p>
                <?php elseif ($order['status'] === 'Dibatalkan'): ?>
                    <i class="bi bi-x-circle" style="font-size:2rem;color:#dc3545;"></i>
                    <p class="text-muted mb-0 mt-2">Pesanan ini dibatalkan.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($tracking['manifest'])): ?>
            <div class="panel">
                <h6 class="font-weight-bold mb-3">Lacak Perjalanan Paket</h6>
                <?php foreach ($tracking['manifest'] as $i => $event): ?>
                    <div class="tracking-event<?= $i === 0 ? ' terbaru' : '' ?>">
                        <div class="tracking-dot"></div>
                        <div>
                            <div class="<?= $i === 0 ? 'font-weight-bold' : '' ?>"><?= htmlspecialchars($event['description']) ?></div>
                            <div class="text-muted small">
                                <?= htmlspecialchars(trim($event['date'] . ' ' . $event['time'])) ?>
                                <?= $event['city'] !== '' ? '&middot; ' . htmlspecialchars($event['city']) : '' ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="text-center mb-4">
            <a href="index.php" class="small">&larr; Kembali Belanja</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
