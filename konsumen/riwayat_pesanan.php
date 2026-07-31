<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';
    include '../includes/order_status_helper.php';

    $idKonsumen = $_SESSION['idkonsumen'];

    $perHalaman = 10;
    $page       = max(1, (int) ($_GET['page'] ?? 1));
    $offset     = ($page - 1) * $perHalaman;

    $stmtCount = $koneksi->prepare("SELECT COUNT(*) AS jumlah FROM orderkonsumen WHERE idkonsumen = ?");
    $stmtCount->bind_param('i', $idKonsumen);
    $stmtCount->execute();
    $totalOrder = (int) ($stmtCount->get_result()->fetch_assoc()['jumlah'] ?? 0);
    $totalHalaman = max(1, (int) ceil($totalOrder / $perHalaman));

    $stmtList = $koneksi->prepare("SELECT * FROM orderkonsumen WHERE idkonsumen = ? ORDER BY tgl DESC LIMIT ? OFFSET ?");
    $stmtList->bind_param('iii', $idKonsumen, $perHalaman, $offset);
    $stmtList->execute();
    $daftarOrder = $stmtList->get_result()->fetch_all(MYSQLI_ASSOC);
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
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 720px;">
        <h5 class="font-weight-bold mt-3 mb-2">Pesanan Saya</h5>

        <div class="panel">
            <?php if (empty($daftarOrder)): ?>
                <p class="text-muted mb-0 text-center">Belum ada pesanan.</p>
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
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
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
