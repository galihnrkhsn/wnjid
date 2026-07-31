<?php
    // navbar.php (di-include lebih dulu di setiap halaman) sudah menghitung $jumlahKeranjang,
    // dipakai ulang di sini supaya tidak query dua kali di request yang sama.
    $jumlahKeranjangFooter = $jumlahKeranjang ?? 0;
    $halamanAktif = basename($_SERVER['SCRIPT_NAME']);
?>
<style>
    body {
        padding-bottom: 70px;
    }
    .konsumen-bottomnav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1030;
        background: #fff;
        border-top: 1px solid #eef1f5;
        box-shadow: 0 -2px 10px rgba(0,0,0,.05);
        display: flex;
    }
    .konsumen-bottomnav a {
        flex: 1;
        text-align: center;
        padding: .5rem 0 .4rem;
        color: #6c757d;
        text-decoration: none;
        font-size: .68rem;
        position: relative;
    }
    .konsumen-bottomnav a.active {
        color: #0d6efd;
    }
    .konsumen-bottomnav a i {
        display: block;
        font-size: 1.25rem;
        margin-bottom: .1rem;
    }
    .konsumen-bottomnav .nav-badge {
        position: absolute;
        top: -2px;
        right: 22%;
        background: #dc3545;
        color: #fff;
        border-radius: 999px;
        font-size: .6rem;
        padding: 1px 5px;
        line-height: 1;
    }
</style>
<nav class="konsumen-bottomnav">
    <a href="index.php" class="<?= $halamanAktif === 'index.php' ? 'active' : '' ?>">
        <i class="bi bi-house"></i> Home
    </a>
    <a href="view_cart.php" class="<?= $halamanAktif === 'view_cart.php' ? 'active' : '' ?>">
        <i class="bi bi-cart3"></i> Keranjang
        <?php if ($jumlahKeranjangFooter > 0): ?>
            <span class="nav-badge"><?= $jumlahKeranjangFooter ?></span>
        <?php endif; ?>
    </a>
    <a href="riwayat_pesanan.php" class="<?= $halamanAktif === 'riwayat_pesanan.php' ? 'active' : '' ?>">
        <i class="bi bi-bag-check"></i> Pesanan
    </a>
    <a href="profile.php" class="<?= in_array($halamanAktif, ['profile.php', 'alamat_saya.php', 'alamat_form.php']) ? 'active' : '' ?>">
        <i class="bi bi-person"></i> Profil
    </a>
</nav>
