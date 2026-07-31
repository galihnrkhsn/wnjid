<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';
    include '../includes/order_status_helper.php';

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pesanan Saya | Wanoja</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f5f6fa; }
        .panel {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .order-card {
            border: 1px solid #eef1f5;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: .75rem;
            display: block;
            color: inherit;
            text-decoration: none;
        }
        .order-card:hover {
            border-color: #0d6efd;
            text-decoration: none;
            color: inherit;
        }
        .order-card:last-child { margin-bottom: 0; }
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
            color: #2b2f42;
            font-size: .85rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid #eef1f5;
            white-space: nowrap;
        }
        .status-tabs a:hover {
            text-decoration: none;
            border-color: #0d6efd;
        }
        .status-tabs a.active {
            background: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
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
                <a href="detail.php?invoice=<?= urlencode($order['invoice']) ?>" class="order-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="mb-2 badge badge-<?= $badgeColor ?> p-2"><?= htmlspecialchars($badgeLabel) ?></span>
                            <div class="font-weight-bold"><?= htmlspecialchars($order['invoice']) ?></div>
                            <div class="text-muted small"><?= date('d M Y H:i', strtotime($order['tgl'])) ?></div>
                        </div>
                    </div>
                    <div class="mt-2 font-weight-bold">Rp <?= number_format($order['total']) ?></div>
                </a>
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

        <div class="text-center mb-4">
            <a href="profile.php" class="small">&larr; Kembali ke Profil</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
