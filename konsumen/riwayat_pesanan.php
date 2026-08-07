<?php
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';
    include '../includes/order_status_helper.php';
    include '../includes/foto_helper.php';

    $idKonsumen = $_SESSION['idkonsumen'];

    $tabStatus = [
        'Semua',
        'Menunggu Pembayaran',
        'Menunggu Konfirmasi Admin',
        'Diproses',
        'Menunggu Resi',
        'Sedang dalam perjalanan',
        'Selesai',
        'Dibatalkan',
    ];
    $status = $_GET['status'] ?? 'Semua';
    if (!in_array($status, $tabStatus, true)) {
        $status = 'Semua';
    }

    // Jumlah per status buat badge angka di tiap tab, sekali query buat semuanya
    $stmtJumlah = $koneksi->prepare("SELECT status, COUNT(*) AS jumlah FROM orderkonsumen WHERE idkonsumen = ? GROUP BY status");
    $stmtJumlah->bind_param('i', $idKonsumen);
    $stmtJumlah->execute();
    $jumlahPerStatus = [];
    $totalSemua = 0;
    $resultJumlah = $stmtJumlah->get_result();
    while ($row = $resultJumlah->fetch_assoc()) {
        $jumlahPerStatus[$row['status']] = (int) $row['jumlah'];
        $totalSemua += (int) $row['jumlah'];
    }

    $perHalaman = 10;
    $page       = max(1, (int) ($_GET['page'] ?? 1));
    $offset     = ($page - 1) * $perHalaman;

    if ($status === 'Semua') {
        $totalOrder = $totalSemua;
        $stmtList = $koneksi->prepare("SELECT * FROM orderkonsumen WHERE idkonsumen = ? ORDER BY tgl DESC LIMIT ? OFFSET ?");
        $stmtList->bind_param('iii', $idKonsumen, $perHalaman, $offset);
    } else {
        $totalOrder = $jumlahPerStatus[$status] ?? 0;
        $stmtList = $koneksi->prepare("SELECT * FROM orderkonsumen WHERE idkonsumen = ? AND status = ? ORDER BY tgl DESC LIMIT ? OFFSET ?");
        $stmtList->bind_param('isii', $idKonsumen, $status, $perHalaman, $offset);
    }
    $stmtList->execute();
    $daftarOrder = $stmtList->get_result()->fetch_all(MYSQLI_ASSOC);
    $totalHalaman = max(1, (int) ceil($totalOrder / $perHalaman));

    // Barang per order, buat preview "1 barang + N lainnya" di tiap kartu
    $stmtItems = $koneksi->prepare("SELECT namaproduk, variant, size, jumlah, idvariant
                                        FROM orderkonsumen_detail WHERE idorder = ? ORDER BY iddetail ASC");
    foreach ($daftarOrder as &$order) {
        $stmtItems->bind_param('i', $order['idorder']);
        $stmtItems->execute();
        $order['items'] = $stmtItems->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach ($order['items'] as &$item) {
            $item['foto_src'] = fotoUntukVariant($koneksi, (int) $item['idvariant']);
        }
        unset($item);
    }
    unset($order);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pesanan Saya | WNJ.ID</title>
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
        .order-card {
            border: 1px solid var(--wnj-border);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: .75rem;
        }
        .order-card:hover {
            border-color: var(--wnj-cta);
        }
        .order-card:last-child { margin-bottom: 0; }
        .order-card-link {
            display: block;
            color: inherit;
            text-decoration: none;
        }
        .order-card-link:hover {
            color: inherit;
            text-decoration: none;
        }
        .order-item-preview {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding-top: .6rem;
            margin-top: .6rem;
            border-top: 1px dashed var(--wnj-border);
        }
        .order-item-preview img {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 6px;
            flex-shrink: 0;
        }
        .order-item-info {
            min-width: 0;
        }
        .order-item-toggle {
            width: 100%;
            background: none;
            border: none;
            padding: .5rem 0 0;
            text-align: left;
            font-size: .8rem;
            font-weight: 600;
            color: var(--wnj-cta);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .order-item-toggle .bi {
            transition: transform .15s ease-in-out;
        }
        .order-item-toggle.open .bi {
            transform: rotate(180deg);
        }
        .order-item-more {
            display: none;
        }
        .order-item-more.open {
            display: block;
        }
        .status-tabs {
            display: flex;
            gap: .5rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding: .25rem .1rem 1rem;
            margin-bottom: -.25rem;
        }
        .status-tabs::-webkit-scrollbar {
            display: none;
        }
        .status-tabs a {
            flex-shrink: 0;
            display: inline-block;
            padding: .45rem 1rem;
            border-radius: 999px;
            background: #fff;
            color: var(--wnj-text);
            font-size: .85rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid var(--wnj-border);
            white-space: nowrap;
        }
        .status-tabs a:hover {
            text-decoration: none;
            border-color: var(--wnj-cta);
        }
        .status-tabs a.active {
            background: var(--wnj-cta);
            color: #fff;
            border-color: var(--wnj-cta);
        }
        .status-tabs a .count {
            opacity: .8;
            margin-left: .2rem;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 720px;">
        <h5 class="font-weight-bold mt-3 mb-2">Pesanan Saya</h5>

        <div class="status-tabs">
            <?php foreach ($tabStatus as $tab): ?>
                <?php $jumlahTab = $tab === 'Semua' ? $totalSemua : ($jumlahPerStatus[$tab] ?? 0); ?>
                <a href="?status=<?= urlencode($tab) ?>" class="<?= $tab === $status ? 'active' : '' ?>">
                    <?= htmlspecialchars($tab) ?><?php if ($jumlahTab > 0): ?><span class="count">(<?= $jumlahTab ?>)</span><?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="panel">
            <?php if (empty($daftarOrder)): ?>
                <p class="text-muted mb-0 text-center">Belum ada pesanan<?= $status !== 'Semua' ? ' dengan status ini' : '' ?>.</p>
            <?php endif; ?>

            <?php foreach ($daftarOrder as $order): ?>
                <?php [$badgeColor, $badgeLabel] = orderKonsumenStatusBadge($order['status']); ?>
                <div class="order-card">
                    <a href="detail.php?invoice=<?= urlencode($order['invoice']) ?>" class="order-card-link">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="mb-2 badge badge-<?= $badgeColor ?> p-2"><?= htmlspecialchars($badgeLabel) ?></span>
                                <div class="font-weight-bold"><?= htmlspecialchars($order['invoice']) ?></div>
                                <div class="text-muted small"><?= date('d M Y H:i', strtotime($order['tgl'])) ?></div>
                            </div>
                        </div>
                        <div class="mt-2 font-weight-bold">Rp <?= number_format($order['total']) ?></div>
                    </a>

                    <?php if (!empty($order['items'])): ?>
                        <?php
                            $itemPertama = $order['items'][0];
                            $sisaItem    = array_slice($order['items'], 1);
                        ?>
                        <div class="order-item-preview">
                            <img src="<?= htmlspecialchars($itemPertama['foto_src']) ?>" alt="">
                            <div class="order-item-info">
                                <div class="font-weight-bold"><?= htmlspecialchars($itemPertama['namaproduk']) ?></div>
                                <div class="text-muted small"><?= htmlspecialchars($itemPertama['variant']) ?> <?= htmlspecialchars($itemPertama['size'] ?? '') ?> &times; <?= (int) $itemPertama['jumlah'] ?></div>
                            </div>
                        </div>

                        <?php if (!empty($sisaItem)): ?>
                            <button type="button" class="order-item-toggle" onclick="toggleOrderItems(this)"
                                    data-label-closed="Lihat <?= count($sisaItem) ?> barang lainnya" data-label-open="Sembunyikan">
                                <span>Lihat <?= count($sisaItem) ?> barang lainnya</span>
                                <i class="bi bi-chevron-down"></i>
                            </button>
                            <div class="order-item-more">
                                <?php foreach ($sisaItem as $item): ?>
                                    <div class="order-item-preview">
                                        <img src="<?= htmlspecialchars($item['foto_src']) ?>" alt="">
                                        <div class="order-item-info">
                                            <div class="font-weight-bold"><?= htmlspecialchars($item['namaproduk']) ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($item['variant']) ?> <?= htmlspecialchars($item['size'] ?? '') ?> &times; <?= (int) $item['jumlah'] ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($totalHalaman > 1): ?>
            <nav class="mb-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&status=<?= urlencode($status) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function toggleOrderItems(btn) {
            var more = btn.nextElementSibling;
            var isOpen = more.classList.toggle('open');
            btn.classList.toggle('open', isOpen);
            btn.querySelector('span').textContent = isOpen ? btn.dataset.labelOpen : btn.dataset.labelClosed;
        }
    </script>
    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
