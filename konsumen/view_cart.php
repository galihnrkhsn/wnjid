<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';
    include '../includes/foto_helper.php';

    $idKonsumen = $_SESSION['idkonsumen'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
        $idkeranjang = (int) $_POST['hapus'];

        $koneksi->begin_transaction();
        $stmtRow = $koneksi->prepare("SELECT idproduk, jmlh FROM keranjang WHERE idkeranjang = ? AND idkonsumen = ? FOR UPDATE");
        $stmtRow->bind_param('is', $idkeranjang, $idKonsumen);
        $stmtRow->execute();
        $row = $stmtRow->get_result()->fetch_assoc();

        if ($row) {
            $stmtDelete = $koneksi->prepare("DELETE FROM keranjang WHERE idkeranjang = ? AND idkonsumen = ?");
            $stmtDelete->bind_param('is', $idkeranjang, $idKonsumen);
            $stmtDelete->execute();

            // Kembalikan stok karena batal dibeli
            $stmtRestock = $koneksi->prepare("UPDATE variants SET stock = stock + ? WHERE id = ?");
            $stmtRestock->bind_param('ii', $row['jmlh'], $row['idproduk']);
            $stmtRestock->execute();
        }
        $koneksi->commit();

        header('Location: view_cart.php');
        exit;
    }

    $stmt = $koneksi->prepare("SELECT k.idkeranjang, k.jmlh, k.harga, k.subtotal,
                                    v.variant, v.size, v.foto, v.folder,
                                    p.namaproduk, mf.name AS nama_folder
                                FROM keranjang k
                                JOIN variants v ON k.idproduk = v.id
                                LEFT JOIN products p ON v.idproducts = p.id
                                LEFT JOIN master_folder mf ON v.folder = mf.id
                                WHERE k.idkonsumen = ? AND k.status = 'Active'
                                ORDER BY k.tgl DESC, k.idkeranjang DESC");
    $stmt->bind_param('s', $idKonsumen);
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $total = 0;
    foreach ($items as $item) {
        $total += (float) $item['subtotal'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Keranjang | Wanoja</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <style>
        body { background: #f5f6fa; }
        .cart-item {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .cart-item img {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: 8px;
        }
        .cart-summary {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
            padding: 1.25rem;
        }
        .empty-state {
            padding: 4rem 1rem;
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container my-4">
        <h5 class="font-weight-bold mb-3">Keranjang Saya</h5>

        <?php if (empty($items)): ?>
            <div class="empty-state">
                <i class="bi bi-cart-x" style="font-size:3rem;"></i>
                <p class="mt-2">Keranjang kamu masih kosong.</p>
                <a href="index.php" class="btn btn-primary btn-sm">Belanja Sekarang</a>
            </div>
        <?php else: ?>
            <?php foreach ($items as $item): ?>
                <div class="cart-item">
                    <img src="<?= fotoProdukSrc($item['nama_folder'] ?? null, $item['foto'] ?? null) ?>" alt="">
                    <div class="flex-grow-1">
                        <div class="font-weight-bold"><?= htmlspecialchars($item['namaproduk'] ?? '') ?></div>
                        <div class="text-muted small"><?= htmlspecialchars($item['variant']) ?> <?= htmlspecialchars($item['size'] ?? '') ?></div>
                        <div>Rp <?= number_format($item['harga']) ?> &times; <?= (int) $item['jmlh'] ?></div>
                    </div>
                    <div class="text-right">
                        <div class="font-weight-bold mb-2">Rp <?= number_format($item['subtotal']) ?></div>
                        <form method="post" onsubmit="return confirm('Hapus item ini dari keranjang?');">
                            <input type="hidden" name="hapus" value="<?= (int) $item['idkeranjang'] ?>">
                            <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="cart-summary d-flex align-items-center justify-content-between mt-3">
                <div>
                    <div class="text-muted small">Total</div>
                    <div class="h5 font-weight-bold mb-0">Rp <?= number_format($total) ?></div>
                </div>
                <button class="btn btn-primary" disabled title="Segera hadir">Checkout (Segera Hadir)</button>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
