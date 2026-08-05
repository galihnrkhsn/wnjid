<?php
    $idKonsumen = $_SESSION['idkonsumen'] ?? null;

    $namaKonsumen = '';
    $jumlahKeranjang = 0;

    if ($idKonsumen) {
        $stmtNama = $koneksi->prepare("SELECT namamitra FROM konsumen WHERE idkonsumen = ?");
        $stmtNama->bind_param('i', $idKonsumen);
        $stmtNama->execute();
        $namaKonsumen = $stmtNama->get_result()->fetch_assoc()['namamitra'] ?? '';

        $stmtCart = $koneksi->prepare("SELECT COALESCE(SUM(jmlh), 0) AS jumlah FROM keranjang WHERE idkonsumen = ? AND status = 'Active'");
        $stmtCart->bind_param('s', $idKonsumen);
        $stmtCart->execute();
        $jumlahKeranjang = (int) ($stmtCart->get_result()->fetch_assoc()['jumlah'] ?? 0);
    }
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="assets/css/wnj-theme.css">
<style>
    .konsumen-navbar {
        background: var(--wnj-bg);
        border-bottom: 1px solid var(--wnj-border);
        padding: .75rem 0;
        position: sticky;
        top: 0;
        z-index: 1030;
    }
    .konsumen-navbar .brand {
        font-weight: 700;
        color: var(--wnj-text);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    .konsumen-navbar .brand-logo {
        height: 28px;
        width: auto;
    }
    .konsumen-navbar .cart-link {
        position: relative;
        color: var(--wnj-text);
        font-size: 1.3rem;
    }
    .konsumen-navbar .cart-badge {
        position: absolute;
        top: -6px;
        right: -10px;
        background: #dc3545;
        color: #fff;
        border-radius: 999px;
        font-size: .65rem;
        padding: 2px 6px;
        line-height: 1;
    }
</style>
<nav class="konsumen-navbar">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="index.php" class="brand"><img src="../image/wnjid.PNG" alt="WNJ.ID" class="brand-logo mr-1"> WNJ.ID</a>
        <div class="d-flex align-items-center" style="gap: 1.25rem;">
            <?php if ($namaKonsumen !== ''): ?>
                <span class="d-none d-sm-inline text-muted">Halo, <?= htmlspecialchars($namaKonsumen) ?></span>
            <?php endif; ?>
            <a href="view_cart.php" class="cart-link" title="Keranjang">
                <i class="bi bi-cart3"></i>
                <?php if ($jumlahKeranjang > 0): ?>
                    <span class="cart-badge"><?= $jumlahKeranjang ?></span>
                <?php endif; ?>
            </a>
            <a href="logout.php" class="text-muted" title="Keluar"><i class="bi bi-box-arrow-right"></i></a>
        </div>
    </div>
</nav>
