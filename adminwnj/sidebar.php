<?php
  include "koneksi.php";
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/wnj-theme.css">
<?php
  // Role 'creative' (tim editor foto) cuma boleh akses Foto Produk - tampilkan sidebar
  // minimal saja, bukan menu admin lengkap yang isinya kebanyakan link tidak bisa diakses.
  if (($_SESSION['administrator']['role'] ?? null) === 'creative') {
?>
<ul class="navbar-nav sidebar sidebar-dark accordion toggled" id="accordionSidebar">
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="foto_produk.php">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-laugh-wink"></i>
    </div>
    <div class="sidebar-brand-text mx-3">WNJ CORP</div>
  </a>
  <hr class="sidebar-divider my-0">
  <li class="nav-item active">
    <a class="nav-link" href="foto_produk.php">
      <i class="fas fa-image"></i>
      <span>Foto Produk</span>
    </a>
  </li>
  <hr class="sidebar-divider">
  <li class="nav-item">
    <a class="nav-link" href="logout.php">
      <i class="fas fa-sign-out-alt"></i>
      <span>Logout</span>
    </a>
  </li>
</ul>
<?php
    return;
  }
?>

    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-dark accordion toggled" id="accordionSidebar">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">WNJ CORP</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="index.php">
        <i class="fa fa-television" aria-hidden="true"></i>
          <span>Admin Pusat</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

        <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

      <!-- Heading -->
      <div class="sidebar-heading">
        USER
      </div>
      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwocs" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-user-cog"></i>
          <span>CS</span>
        </a>
        <div id="collapseTwocs" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="cssales.php">CS Sales</a>
            <a class="collapse-item" href="input_cssales.php">Tambah CS Sales</a>
            <a class="collapse-item" href="update_cssales.php">Update CS Sales</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwocscs" aria-expanded="true" aria-controls="collapseTwo">
        <i class="fa fa-sticky-note" aria-hidden="true"></i>
          <span>Catatan</span>
        </a>
        <div id="collapseTwocscs" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="catatan.php">Data Catatan</a>
            <a class="collapse-item" href="input_catatan.php">Input Catatan</a>
          </div>
        </div>
      </li>      

      <li class="nav-item">
        <a href="user_manajemen.php" class="nav-link">
          <i class="fas fa-users"></i>
          <span>Manajemen</span>
        </a>
      </li>

      <!-- Heading -->
      <div class="sidebar-heading">
        KEMITRAAN
      </div>


      <!-- Nav Item - Pages Collapse Menu -->
      
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-user"></i>
          <span>Distributor</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="mitra.php">Data Distributor</a>
            <a class="collapse-item" href="input_mitra.php">Tambah DB</a>
            <a class="collapse-item" href="datapendaftaran.php">Daftar Calon Mitra</a>
            <a class="collapse-item" href="return.php">Support Ticket</a>
            <a class="collapse-item" href="news.php">News</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsesub" 
        aria-expanded="true" aria-controls="collapsesub">
          <i class="fas fa-fw fa-users"></i>
          <span>Sub DB</span>
        </a>
        <div id="collapsesub" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="subdb.php">Data Sub DB</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsetagihan" 
        aria-expanded="true" aria-controls="collapsesub">
          <i class="fas fa-fw fa-file-invoice-dollar"></i>
          <span>Tagihan</span>
        </a>
        <div id="collapsetagihan" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="tagihan.php">Tagihan DB</a>
          </div>
        </div>
      </li>
      
       <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-wallet"></i>
          <span>Saldo DB</span>
        </a>
        <div id="collapseThree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="saldo.php">Data Saldo</a>
            <a class="collapse-item" href="input_saldo.php">Input Saldo</a>
            <a class="collapse-item" href="sisa_saldo.php">Sisa Saldo</a>
          </div>
        </div>
      </li>

       <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVoucher" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-money"></i>
          <span>Voucher DB</span>
        </a>
        <div id="collapseVoucher" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="voucher.php">Data Voucher</a>
            <a class="collapse-item" href="input_voucher.php">Input Voucher</a>
          </div>
        </div>
      </li>      

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAM" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-line-chart"></i>
          <span>Ads Mitra</span>
        </a>
        <div id="collapseAM" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="dataads.php">Data Ads Mitra</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGN" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-envelope"></i>
          <span>Pesan Mitra</span>
        </a>
        <div id="collapseGN" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="pesan.php">Daftar Pesan</a>
            <a class="collapse-item" href="inputgoodnews.php">Input Pesan</a>
          </div>
        </div>
      </li>

      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
       Transaksi
      </div>
        <li class="nav-item">
          <a href="ongkir.php" class="nav-link">
            <i class="fas fa-usd"></i>
            <span>Ongkir</span>
          </a>
        </li>
        <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFive" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fa fa-fw fa-shopping-cart"></i>
          <span>List PO Mitra</span>
        </a>
        <div id="collapseFive" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
             <a class="collapse-item" href="listpoartikel.php"> List PO Mitra Per Artikel</a>
            <a class="collapse-item" href="daftarpoproduk.php">List PO Per Invoice</a>
            <a class="collapse-item" href="daftards.php">List PO Dropship</a>
            <a class="collapse-item" href="listpopembayaran.php"> List Pembayaran DP PO</a>
              <a class="collapse-item" href="notepo.php">Note PO</a>
          </div>
        </div>
      </li>
      
       
      
        <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOrder" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-shopping-bag"></i>
          <span>List Order Mitra</span>
        </a>
        <div id="collapseOrder" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="keranjang.php">Keranjang</a>
            <a class="collapse-item" href="ordermitra.php">Order Mitra</a>
            <a class="collapse-item" href="ordermitra_keep.php">Order Mitra Keep</a>
            <a class="collapse-item" href="pengiriman.php">Order Pengiriman</a>
             <a class="collapse-item" href="pengiriman_manual.php">Pengiriman (Ongkir Manual)</a>
            <a class="collapse-item" href="pembayaran.php">Order Pembayaran</a>
             <a class="collapse-item" href="suratjalan.php">Surat Jalan</a>
          </div>
        </div>
      </li>

        <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOrderKonsumen" aria-expanded="true" aria-controls="collapseOrderKonsumen">
          <i class="fas fa-fw fa-shopping-basket"></i>
          <span>List Order Konsumen</span>
        </a>
        <div id="collapseOrderKonsumen" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="orderkonsumen.php">Order Konsumen</a>
            <a class="collapse-item" href="orderkonsumen_ambilbarang.php">Ambil Barang Konsumen</a>
            <a class="collapse-item" href="orderkonsumen_pengiriman.php">Pengiriman Konsumen</a>
            <a class="collapse-item" href="orderkonsumen_cekresi.php">Cek &amp; Tandai Terkirim</a>
          </div>
        </div>
      </li>

      <hr class="sidebar-divider">
      
      <!-- Heading -->
      <div class="sidebar-heading">
        Invoice
      </div>
      
      <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInvoice" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fa fa-cart-plus" aria-hidden="true"></i>
              <span>Pre Order</span>
          </a>
            <div id="collapseInvoice" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Pilihan:</h6>
                    <a class="collapse-item" href="ordermitra.php">Ready Stock</a>
                    <a class="collapse-item" href="formpo.php">Pre Order</a>
                </div>
            </div>
      </li>
      
      <hr class="sidebar-divider">

      
      <!-- Heading -->
      <div class="sidebar-heading">
        Produk
      </div>
      
         <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cube"></i>
          <span>Produk PO</span>
        </a>
      <div id="collapseFour" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="produkpo.php">Produk PO</a>
            <a class="collapse-item" href="distribusipo.php">Distribusi PO</a>
            <a class="collapse-item" href="inputpo2.php">Tambah PO</a>
            <a class="collapse-item" href="data_bukapo.php">Buka PO</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseKatalog" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-picture-o"></i>
          <span>Katalog</span>
        </a>
        <div id="collapseKatalog" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="katalog.php">Data Katalog</a>
            <a class="collapse-item" href="inputkatalog.php">Tambah Katalog</a>
          </div>
        </div>
      </li>
      
                        <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseKP" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-tasks"></i>
          <span>Kategori Produk</span>
        </a>
        <div id="collapseKP" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="kategori.php">Data Kategori Produk</a>
            <a class="collapse-item" href="inputkategori.php">Tambah Kategori</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a href="master_size.php" class="nav-link">
          <i class="fas fa-ruler"></i>
          <span>Master Size</span>
        </a>
      </li>

      <li class="nav-item">
        <a href="master_jenis.php" class="nav-link">
          <i class="fas fa-tags"></i>
          <span>Master Jenis Promo</span>
        </a>
      </li>

      <li class="nav-item">
        <a href="faq.php" class="nav-link">
          <i class="fas fa-question-circle"></i>
          <span>Kelola Bantuan</span>
        </a>
      </li>

            <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePP" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-database"></i>
          <span>Produk Ready Stock</span>
        </a>
        <div id="collapsePP" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="produk3.php">Produk Pusat</a>
            <a class="collapse-item" href="produkb2.php">Produk GB</a>
            <a class="collapse-item" href="requestproduk.php">Request Produk</a>
            <a class="collapse-item" href="list-keep-produk-mitra.php">Keep Produk</a>
             <a class="collapse-item" href="slider.php">Image Slider</a>
            <a class="collapse-item" href="tambah_produk.php">Tambah Produk</a>
            <a class="collapse-item" href="maintenance_produk.php">Maintenance Produk</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSix" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-tags"></i>
          <span>Pricelist</span>
        </a>
        <div id="collapseSix" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="pricelist-artikel.php">Data Pricelist</a>
            <a class="collapse-item" href="input_pricelist.php">Tambah Pricelist</a>
          </div>
        </div>
      </li>

      <hr class="sidebar-divider">

       <!-- Heading -->
       <div class="sidebar-heading">
       Lainnya
      </div>
      
       <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapselearning" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-book"></i>
          <span>E-Learning</span>
        </a>
        <div id="collapselearning" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="inputtutor.php">Tutorial WEB</a>
            <a class="collapse-item" href="">Belajar Ads</a>
            <a class="collapse-item" href="">Market Tools</a>
          </div>
        </div>
      </li>

       <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsecp" aria-expanded="true" aria-controls="collapsecp">
          <i class="fas fa-fw fa-book"></i>
          <span>Company Profile</span>
        </a>
        <div id="collapsecp" class="collapse" aria-labelledby="headingcp" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pilihan:</h6>
            <a class="collapse-item" href="slider.php">Silder</a>
            <a class="collapse-item" href="banner.php">Banner</a>
          </div>
        </div>
      </li>
    </ul>
    <!-- End of Sidebar -->

 <!-- Content Wrapper -->
 <div id="content-wrapper" class="d-flex flex-column">

<!-- Main Content -->
<div id="content">

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
  <!-- Sidebar Toggle (Topbar) -->
  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
  </button>

  <!-- Topbar Navbar -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown no-arrow mx-1">
      <a class="nav-link dropdown-toggle" href="#" id="alertsDropdownPO" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <p class="mb-0 text-primary" style="font-size: 2rem;">PO</p>
          <!-- Counter - Alerts -->
          <?php
            $sql = $koneksi->query("SELECT
                                        COUNT(*) AS jumlah
                                      FROM
                                        bukapo
                                      WHERE
                                        status = 'PUBLISH'
                                  ");
            $notifpo = $sql->fetch_assoc();
          ?>
          <h4>
            <span class="badge badge-danger badge-counter"><?= $notifpo['jumlah']; ?></span>
          </h4>
      </a>
      <!-- Dropdown - Alerts -->
      <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdownPO">
        <h6 class="dropdown-header text-center mt-0">
          Orderan Mitra PO
        </h6>
        <?php
          $tampil = $koneksi->query("SELECT 
                                        ordermitra.invoice,
                                        ordermitra.status,
                                        ordermitra.idorder,
                                        ordermitra.tgl,
                                        admin_mitra.namamitra
                                      FROM
                                        ordermitra
                                          INNER JOIN
                                        admin_mitra ON ordermitra.idmitra = admin_mitra.idadmin
                                      WHERE
                                        ordermitra.status = 'Pending'
                                      ORDER BY ordermitra.idorder DESC
                                      LIMIT 5
                                    ");
          while($dataordermitra = $tampil->fetch_assoc()){
            $tanggal = $dataordermitra['tgl'];
            $date = new DateTime($tanggal);
            $hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
            $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
            $formattedDate = $hari[$date->format('w')] . ', ' . $date->format(format: 'd') . ' ' . $bulan[(int)$date->format('m')] . ' ' . $date->format('Y');
        ?>
          <a class="dropdown-item d-flex align-items-center" href="detailorder2.php?invoice=<?= $dataordermitra['invoice'] ?>">
            <div>
              <p class="mb-0">Nama Mitra: <?= $dataordermitra['namamitra']; ?></p>
              <p class="mb-0">Inovice: <span class="text-primary"><?= $dataordermitra['invoice']; ?></span></p>
              <p class="mb-0">Status: <span class="text-danger"><?= $dataordermitra['status']; ?></span></p>
              <p class="mb-0 small"><?= $formattedDate; ?></p>
            </div>
          </a>
        <?php } ?>
        <a class="dropdown-item text-center small text-gray-500" href="ordermitra.php">Lihat Semua</a>
      </div>
    </li>

    <div class="topbar-divider d-none d-sm-block"></div>

    <li class="nav-item dropdown no-arrow mx-1">
      <a class="nav-link dropdown-toggle" href="#" id="alertsDropdownCart" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-shopping-cart fa-fw fa-2x text-primary"></i>
          <!-- Counter - Alerts -->
          <?php
            $sql = $koneksi->query("SELECT 
                                        COUNT(*) AS jumlah
                                      FROM
                                        ordermitra
                                      WHERE
                                        status = 'Pending'
                                            AND tgl >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                                      ORDER BY idorder DESC
                                  ");
            $notiforder = $sql->fetch_assoc();
          ?>
          <h4>
            <span class="badge badge-danger badge-counter"><?= $notiforder['jumlah']; ?></span>
          </h4>
      </a>
      <!-- Dropdown - Alerts -->
      <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdownCart">
        <h6 class="dropdown-header text-center mt-0">
          Orderan Mitra Ready Stock
        </h6>
        <?php
          $tampil = $koneksi->query("SELECT 
                                        ordermitra.invoice,
                                        ordermitra.status,
                                        ordermitra.idorder,
                                        ordermitra.tgl,
                                        admin_mitra.namamitra
                                      FROM
                                        ordermitra
                                          INNER JOIN
                                        admin_mitra ON ordermitra.idmitra = admin_mitra.idadmin
                                      WHERE
                                        ordermitra.status = 'Pending'
                                      ORDER BY ordermitra.idorder DESC
                                      LIMIT 5
                                    ");
          while($dataordermitra = $tampil->fetch_assoc()){
            $tanggal = $dataordermitra['tgl'];
            $date = new DateTime($tanggal);
            $hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
            $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
            $formattedDate = $hari[$date->format('w')] . ', ' . $date->format(format: 'd') . ' ' . $bulan[(int)$date->format('m')] . ' ' . $date->format('Y');
        ?>
          <a class="dropdown-item d-flex align-items-center" href="detailorder2.php?invoice=<?= $dataordermitra['invoice'] ?>">
            <div>
              <p class="mb-0">Nama Mitra: <?= $dataordermitra['namamitra']; ?></p>
              <p class="mb-0">Inovice: <span class="text-primary"><?= $dataordermitra['invoice']; ?></span></p>
              <p class="mb-0">Status: <span class="text-danger"><?= $dataordermitra['status']; ?></span></p>
              <p class="mb-0 small"><?= $formattedDate; ?></p>
            </div>
          </a>
        <?php } ?>
        <a class="dropdown-item text-center small text-gray-500" href="ordermitra.php">Lihat Semua</a>
      </div>
    </li>

    <div class="topbar-divider d-none d-sm-block"></div>

    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="mr-2 d-none d-lg-inline text-gray-600 small">Hai <?php echo $_SESSION["administrator"]["name"] ?></span>
        <img class="img-profile rounded-circle" src="img/logo+TEXTwanoja.jpg" >
      </a>
      <!-- Dropdown - User Information -->
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="#">
          <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
          Profile
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="logout.php">
          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
          Logout
        </a>
      </div>
    </li>

    

    
  </ul>

</nav>
<!-- End of Topbar -->