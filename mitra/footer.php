<?php
    // Akun/Profil selalu ada buat semua role (distributor/agen/reseller/marketer).
    // Sub-Mitra (khusus distributor & agen) ditaruh di grid menu dashboard, bukan di sini,
    // supaya bottom nav tetap konsisten 4 item yang sama buat semua mitra.
    $halamanAktif = basename($_SERVER['SCRIPT_NAME']);
?>
<style>
    body {
        padding-bottom: 70px;
    }
    .mitra-bottomnav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1030;
        background: var(--wnj-bg);
        border-top: 1px solid var(--wnj-border);
        box-shadow: 0 -2px 10px rgba(0,0,0,.05);
        display: flex;
    }
    .mitra-bottomnav a {
        flex: 1;
        text-align: center;
        padding: .5rem 0 .4rem;
        color: var(--wnj-text-secondary);
        text-decoration: none;
        font-size: .68rem;
    }
    .mitra-bottomnav a.active {
        color: var(--wnj-cta);
    }
    .mitra-bottomnav a i {
        display: block;
        font-size: 1.25rem;
        margin-bottom: .1rem;
    }
</style>
<nav class="mitra-bottomnav">
    <a href="index.php" class="<?= $halamanAktif === 'index.php' ? 'active' : '' ?>">
        <i class="bi bi-house"></i> Home
    </a>
    <a href="store.php" class="<?= $halamanAktif === 'store.php' ? 'active' : '' ?>">
        <i class="bi bi-shop"></i> Ready Stock
    </a>
    <a href="preorder.php" class="<?= $halamanAktif === 'preorder.php' ? 'active' : '' ?>">
        <i class="bi bi-bag-plus"></i> Pre-Order
    </a>
    <a href="akun.php" class="<?= $halamanAktif === 'akun.php' ? 'active' : '' ?>">
        <i class="bi bi-person"></i> Akun
    </a>
</nav>
