<?php
    $current_page = basename($_SERVER['PHP_SELF']);
    $invoice_pages = ['invoice.php', 'giveaway.php', 'buku_alamat.php'];
    $pengiriman_pages = ['detail_resi.php', 'resi.php', 'ekspedisi.php'];
    $date = date('Y-m-d');
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <img src="template/dist/img/logo-square.png" alt="Wanoja Logo" class="p-2 brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">PORTAL WANOJA</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block text-capitalize"><?= $username ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header text-uppercase">Main Menu</li>
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item <?= in_array($current_page, $invoice_pages) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= in_array($current_page, $invoice_pages) ? 'active' : '' ?>">
                        <i class="nav-icon fa fa-barcode"></i>
                        <p>
                            Data Invoice
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="invoice.php" class="nav-link <?= $current_page == 'invoice.php' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Invoice</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="giveaway.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Giveaway</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="buku_alamat.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Address Book</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item <?= in_array($current_page, $pengiriman_pages) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= in_array($current_page, $pengiriman_pages) ? 'active' : '' ?>">
                        <i class="nav-icon fa fa-cubes"></i>
                        <p>
                            Pengiriman
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="resi.php" class="nav-link <?= $current_page == 'resi.php' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Resi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="detail_resi.php?tgl=<?= $date ?>" class="nav-link <?= $current_page == 'detail_resi.php' || $current_page == 'ekspedisi.php' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rekap Resi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="surat_jalan_eks.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Surat Jalan Ekspedisi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tracking Resi</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="form_resi.php" class="nav-link <?= $current_page == 'form_resi.php' ? 'active' : '' ?>">
                        <i class="nav-icon fa fa-server"></i>
                        <p>Input Resi & SJ</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="form_pengiriman.php" class="nav-link <?= $current_page == 'form_pengiriman.php' ? 'active' : '' ?>">
                        <i class="nav-icon fa fa-server"></i>
                        <p>Input Pengiriman</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>