<?php
    session_start();
    include 'koneksi.php';

    if (!isset($_SESSION["administrator"])) {
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $invoice = $_GET["invoice"];
    $jenis   = $_GET['jenis'] ?? null;

    $sql        = "SELECT * FROM orderpengiriman WHERE invoice = '$invoice' ";
    $query      = $koneksi->query($sql);
    $pengiriman = $query->fetch_assoc();

    // Hanya admin tertentu yang boleh mengubah isi order (sama seperti pembatasan lama).
    $namaAdmin = $_SESSION["administrator"]["nama"] ?? null;
    $bisaEdit  = in_array($namaAdmin, ['Delita', 'Master'], true);

    $statusBisaEdit = ['Pending', 'Proses', 'Tunggu Confirm Admin', 'Tunggu Confrim Admin'];

    if (isset($_POST["done"])) {
        $koneksi->query("update ordermitra set status='Sedang DiKirim' where invoice='$invoice';");
        echo "<script>alert('data sudah terupdate');</script>";
        echo "<script>location='ordermitra.php';</script>";
        exit();
    }

    // ---- Ambil item order utk ditampilkan (dikelompokkan per variant utk invoice tipe "F") ----
    $items      = [];
    $total_qty  = 0;
    $namamitra  = null;

    if (substr($invoice, 0, 1) == "F") {
        $datapo = $koneksi->query("SELECT
                                        admin_mitra.namamitra,
                                        products.namaproduk,
                                        ordermitra.idorder,
                                        ordermitra.idproduk,
                                        ordermitra.invoice,
                                        ordermitra.harga,
                                        SUM(ordermitra.jumlah) as jumlah,
                                        SUM(ordermitra.subtotal) as subtotal,
                                        variants.variant, variants.size
                                    FROM ordermitra
                                    INNER JOIN admin_mitra on ordermitra.idmitra = admin_mitra.idadmin
                                    INNER JOIN variants on variants.id = ordermitra.idproduk
                                    INNER JOIN products on products.id = variants.idproducts
                                    WHERE ordermitra.invoice = '$invoice'
                                    and ordermitra.jumlah > 0
                                    GROUP BY variants.id");
    } else {
        $datapo = $koneksi->query("SELECT
                                        admin_mitra.namamitra,
                                        products.namaproduk,
                                        ordermitra.idorder,
                                        ordermitra.idproduk,
                                        ordermitra.invoice,
                                        ordermitra.harga,
                                        ordermitra.jumlah,
                                        ordermitra.subtotal,
                                        variants.variant, variants.size
                                    FROM ordermitra
                                    INNER JOIN admin_mitra on ordermitra.idmitra=admin_mitra.idadmin
                                    INNER JOIN variants on ordermitra.idproduk = variants.id
                                    INNER JOIN products on products.id = variants.idproducts
                                    WHERE ordermitra.invoice = '$invoice'
                                    AND ordermitra.jumlah > 0");
    }

    while ($tampilkan = $datapo->fetch_assoc()) {
        $tampilkan['sub'] = $tampilkan['harga'] * $tampilkan['jumlah'];
        $items[]           = $tampilkan;
        $total_qty        += $tampilkan['jumlah'];
        $namamitra         = $tampilkan['namamitra'];
    }

    $statusOrder = $items ? ($koneksi->query("SELECT status FROM ordermitra WHERE invoice = '$invoice' LIMIT 1")->fetch_assoc()['status'] ?? '') : '';
    $editAktif   = $bisaEdit && in_array($statusOrder, $statusBisaEdit, true);

    // ================================================================================
    // PERHITUNGAN DISKON - LOGIKA ASLI, TIDAK DIUBAH (cuma tampilannya di-refactor di bawah)
    // ================================================================================
    $totala     = 0;
    $jenisCheck = null;
    $disc       = null;

    if (substr($invoice, 0, 1) == "F") {
        $jumlah_produknya = $total_qty / 2;
        $sql = "SELECT variants.harga AS subtotal
                FROM ordermitra
                INNER JOIN variants ON variants.id = ordermitra.idproduk
                INNER JOIN products ON products.id = variants.idproducts
                WHERE ordermitra.invoice = '$invoice'
                AND ordermitra.jumlah > 0
                AND products.idkategori BETWEEN 11 AND 13
                ORDER BY variants.harga desc
                LIMIT " . $jumlah_produknya;
    } else {
        $jenisCheck = $koneksi->query("SELECT variants.jenis FROM ordermitra
                    INNER JOIN variants ON variants.id = ordermitra.idproduk
                    INNER JOIN products ON variants.idproducts = products.id
                    WHERE ordermitra.invoice = '$invoice'
                ")->fetch_assoc()['jenis'];

        if ($jenisCheck == 'b1g1') {
            $total_b1g1 = $koneksi->query("SELECT ordermitra.*, variants.harga as harga_variant
                                                FROM ordermitra
                                                INNER JOIN variants ON variants.id = ordermitra.idproduk
                                                INNER JOIN products ON variants.idproducts = products.id
                                                WHERE ordermitra.invoice = '$invoice'
                                                AND variants.jenis = 'b1g1'
                                            ");

            $expanded = [];
            while ($row = $total_b1g1->fetch_assoc()) {
                for ($i = 0; $i < $row['jumlah']; $i++) {
                    $expanded[] = $row;
                }
            }

            usort($expanded, function ($a, $b) {
                return $b['harga'] <=> $a['harga'];
            });

            $total = count($expanded);
            $limit = $total / 2;

            $top_items = array_slice($expanded, 0, $limit);
        } else {
            $sql = "SELECT * FROM ordermitra
                    INNER JOIN variants on variants.id = ordermitra.idproduk
                    INNER JOIN products on products.id = variants.idproducts
                    WHERE ordermitra.invoice = '$invoice'
                    AND ordermitra.jumlah > 0
                    AND products.idkategori > 0
                    AND products.idkategori <> 2
                    ";
        }
    }
    if ($jenisCheck == 'b1g1') {
        foreach ($top_items as $item) {
            $totala += $item['harga'];
        }
    } else {
        $query = $koneksi->query($sql);
        while ($ga = $query->fetch_assoc()) {
            $totala += $ga['subtotal'];
            $disc    = $ga['disc'];
        }
    }

    $totalb = 0;
    $sql    = "SELECT * FROM ordermitra
                INNER JOIN variants on variants.id = ordermitra.idproduk
                INNER JOIN products on products.id = variants.idproducts
                WHERE ordermitra.invoice = '$invoice'
                AND ordermitra.jumlah > 0
                AND products.idkategori = 2";
    $query  = $koneksi->query($sql);
    while ($gb = $query->fetch_assoc()) {
        $totalb += $gb['subtotal'];
    }

    $kategoriDiskonTier = [5 => 0, 10 => 0, 15 => 0, 17 => 0, 20 => 0, 25 => 0];
    foreach (array_keys($kategoriDiskonTier) as $idkategoriTier) {
        $sql   = "SELECT * FROM ordermitra
                    INNER JOIN variants on variants.id = ordermitra.idproduk
                    INNER JOIN products on products.id = variants.idproducts
                    WHERE ordermitra.invoice = '$invoice'
                    AND ordermitra.jumlah > 0
                    AND products.idkategori = $idkategoriTier";
        $query = $koneksi->query($sql);
        while ($row = $query->fetch_assoc()) {
            $kategoriDiskonTier[$idkategoriTier] += $row['subtotal'];
        }
    }
    $totald5  = $kategoriDiskonTier[5];
    $totald10 = $kategoriDiskonTier[10];
    $totald15 = $kategoriDiskonTier[15];
    $totald17 = $kategoriDiskonTier[17];
    $totald20 = $kategoriDiskonTier[20];
    $totald25 = $kategoriDiskonTier[25];

    $apaja    = $pengiriman['dropship'];
    $dropship = $pengiriman['berat'];

    if ($dropship <= 5000 && $dropship >= 0 && $apaja == 'ya') {
        $biayad = 3000;
    } elseif ($dropship <= 10000 && $dropship >= 6000 && $apaja == 'ya') {
        $biayad = 5000;
    } elseif ($dropship <= 20000 && $dropship >= 11000 && $apaja == 'ya') {
        $biayad = 10000;
    } elseif ($dropship <= 30000 && $dropship >= 21000 && $apaja == 'ya') {
        $biayad = 15000;
    } elseif ($dropship <= 40000 && $dropship >= 31000 && $apaja == 'ya') {
        $biayad = 20000;
    } elseif ($dropship <= 50000 && $dropship >= 41000 && $apaja == 'ya') {
        $biayad = 25000;
    } elseif ($dropship <= 60000 && $dropship >= 51000 && $apaja == 'ya') {
        $biayad = 30000;
    } elseif ($dropship <= 70000 && $dropship >= 61000 && $apaja == 'ya') {
        $biayad = 35000;
    } elseif ($dropship <= 80000 && $dropship >= 71000 && $apaja == 'ya') {
        $biayad = 40000;
    } elseif ($dropship <= 90000 && $dropship >= 81000 && $apaja == 'ya') {
        $biayad = 45000;
    } elseif ($dropship <= 100000 && $dropship >= 91000 && $apaja == 'ya') {
        $biayad = 50000;
    } elseif ($dropship <= 110000 && $dropship >= 101000 && $apaja == 'ya') {
        $biayad = 55000;
    } elseif ($dropship <= 120000 && $dropship >= 111000 && $apaja == 'ya') {
        $biayad = 60000;
    } elseif ($dropship <= 130000 && $dropship >= 121000 && $apaja == 'ya') {
        $biayad = 65000;
    } elseif ($dropship <= 140000 && $dropship >= 131000 && $apaja == 'ya') {
        $biayad = 70000;
    } elseif ($dropship <= 150000 && $dropship >= 141000 && $apaja == 'ya') {
        $biayad = 75000;
    } elseif ($dropship <= 160000 && $dropship >= 151000 && $apaja == 'ya') {
        $biayad = 80000;
    } elseif ($dropship <= 170000 && $dropship >= 161000 && $apaja == 'ya') {
        $biayad = 85000;
    } elseif ($dropship <= 180000 && $dropship >= 171000 && $apaja == 'ya') {
        $biayad = 90000;
    } elseif ($dropship <= 190000 && $dropship >= 181000 && $apaja == 'ya') {
        $biayad = 95000;
    } elseif ($dropship <= 200000 && $dropship >= 191000 && $apaja == 'ya') {
        $biayad = 100000;
    } elseif ($apaja == 'tidak') {
        $biayad = 0;
    } else {
        $biayad = 0;
    }

    $ongkir         = $pengiriman['ongkir'];
    $kurir          = $pengiriman['ekspedisi'];
    $diskonramadhan = $pengiriman['diskonramadhan'];

    $diskona  = $totala * 35 / 100;
    $diskonb  = $totalb * 55 / 100;
    $diskon5  = $totald5 * 5 / 100;
    $diskon10 = $totald10 * 10 / 100;
    $diskon15 = $totald15 * 15 / 100;
    $diskon17 = $totald17 * 17 / 100;
    $diskon20 = $totald20 * 20 / 100;
    $diskon25 = $totald25 * 25 / 100;

    $diskonTambahan = (isset($disc) && $disc > 0) ? $totala * $disc / 100 : 0;
    $diskonFlash    = ($jenis == 'Flash') ? $totala * 20 / 100 : 0;

    $grandtotal = ($totala + $totalb + $ongkir + $biayad)
                - ($diskona + $diskonTambahan + $diskonFlash + $diskonb + $diskon5 + $diskon10 + $diskon15 + $diskon17 + $diskon20 + $diskon25);
    $total      = $totala + $totalb;

    // Baris diskon yang benar-benar kepakai saja yang ditampilkan (bukan baris 0/kosong).
    $diskonLines = [];
    if ($diskona > 0)        $diskonLines[] = ['Diskon DB 35%', $diskona];
    if ($diskonTambahan > 0) $diskonLines[] = ["Diskon Tambahan {$disc}%", $diskonTambahan];
    if ($diskonFlash > 0)    $diskonLines[] = ['Diskon Flash 20%', $diskonFlash];
    if ($diskonb > 0)        $diskonLines[] = ['Diskon Grade B 55%', $diskonb];
    if ($diskon5 > 0)        $diskonLines[] = ['Diskon 5%', $diskon5];
    if ($diskon10 > 0)       $diskonLines[] = ['Diskon 10%', $diskon10];
    if ($diskon15 > 0)       $diskonLines[] = ['Diskon 15%', $diskon15];
    if ($diskon17 > 0)       $diskonLines[] = ['Diskon 17%', $diskon17];
    if ($diskon20 > 0)       $diskonLines[] = ['Diskon 20%', $diskon20];
    if ($diskon25 > 0)       $diskonLines[] = ['Diskon 25%', $diskon25];

    $totalDiskon = array_sum(array_column($diskonLines, 1));

    // Ongkir belum diisi admin: hanya utk kurir yang bukan tipe self-pickup/sudah pasti Rp 0.
    $ongkirBelumDiisi = ($ongkir == 0) && !in_array($kurir, ['Ahsan', 'Gosend', 'Ambil ke Pusat', 'Disatukan'], true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Pusat | Wanoja</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .ringkasan-line {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
        }
        .ringkasan-line.diskon {
            color: #e74a3b;
        }
        .ringkasan-line.grandtotal {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1cc88a;
            border-top: 2px solid #e3e6f0;
            margin-top: 8px;
            padding-top: 8px;
        }
        .qty-form {
            display: flex;
            gap: 4px;
            align-items: center;
        }
        .qty-form input {
            width: 70px;
        }
        #searchHasil .list-group-item {
            cursor: pointer;
        }
    </style>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <?php include "sidebar.php"; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-3">
            <div>
                <h3 class="mb-0"><strong>Invoice <?= htmlspecialchars($invoice) ?></strong></h3>
                <div class="text-muted small">
                    <?= htmlspecialchars($namamitra ?? '-') ?>
                    &middot; Status: <span class="badge badge-info"><?= htmlspecialchars($statusOrder ?: '-') ?></span>
                </div>
            </div>
            <a class="btn btn-success" href="cetakordermitra2.php?invoice=<?= urlencode($invoice) ?>" target="_blank">
                <i class="fas fa-print"></i> Cetak
            </a>
          </div>

          <div class="row">
            <div class="col-lg-8">
                <!-- Daftar Item -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0" id="itemTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <?php if ($bisaEdit): ?>
                                        <th style="width: 110px;" class="text-right"><i class="fas fa-cog"></i></th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($items as $tampilkan): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($tampilkan['namaproduk'] . ' ' . $tampilkan['variant'] . ' ' . $tampilkan['size']) ?></td>
                                        <td><?= number_format($tampilkan['harga']) ?></td>
                                        <td>
                                            <?php if ($editAktif): ?>
                                            <span class="qty-form">
                                                <input type="number" min="1" class="form-control form-control-sm input-jumlah" value="<?= (int) $tampilkan['jumlah'] ?>" data-idproduk="<?= (int) $tampilkan['idproduk'] ?>">
                                                <button type="button" class="btn btn-sm btn-outline-primary btn-simpan-jumlah" data-idproduk="<?= (int) $tampilkan['idproduk'] ?>" title="Simpan jumlah"><i class="fas fa-check"></i></button>
                                            </span>
                                            <?php else: ?>
                                                <?= (int) $tampilkan['jumlah'] ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= number_format($tampilkan['sub']) ?></td>
                                        <?php if ($bisaEdit): ?>
                                        <td class="text-right">
                                            <?php if ($editAktif): ?>
                                            <button type="button" class="btn btn-sm btn-danger btn-hapus-item" data-idproduk="<?= (int) $tampilkan['idproduk'] ?>" title="Hapus item"><i class="fas fa-trash"></i></button>
                                            <?php endif; ?>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php if ($editAktif): ?>
                <!-- Tambah Produk -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tambah Produk ke Order</h6>
                        <small class="text-muted">Hanya menampilkan variant dengan stock &gt; 0.</small>
                    </div>
                    <div class="card-body">
                        <input type="text" id="searchProduk" class="form-control form-control-sm mb-2" placeholder="Cari nama produk / variant / size...">
                        <div id="searchHasil" class="list-group"></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <!-- Ringkasan -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Ringkasan</h6>
                    </div>
                    <div class="card-body">
                        <div class="ringkasan-line">
                            <span>Subtotal Produk</span>
                            <span>Rp <?= number_format($total) ?></span>
                        </div>

                        <?php if ($biayad > 0): ?>
                        <div class="ringkasan-line">
                            <span>Biaya Dropship</span>
                            <span>Rp <?= number_format($biayad) ?></span>
                        </div>
                        <?php endif; ?>

                        <div class="ringkasan-line">
                            <span>Ongkir</span>
                            <span><?= $ongkirBelumDiisi ? 'Menunggu diisi Admin' : 'Rp ' . number_format($ongkir) ?></span>
                        </div>

                        <?php foreach ($diskonLines as [$label, $amount]): ?>
                        <div class="ringkasan-line diskon">
                            <span><?= htmlspecialchars($label) ?></span>
                            <span>- Rp <?= number_format($amount) ?></span>
                        </div>
                        <?php endforeach; ?>

                        <?php if ($totalDiskon > 0): ?>
                        <div class="ringkasan-line diskon">
                            <span><strong>Total Diskon</strong></span>
                            <span><strong>- Rp <?= number_format($totalDiskon) ?></strong></span>
                        </div>
                        <?php endif; ?>

                        <div class="ringkasan-line grandtotal">
                            <span>Grand Total</span>
                            <span>Rp <?= number_format($grandtotal) ?></span>
                        </div>
                    </div>
                </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2020</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
  <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <?php if ($editAktif): ?>
  <script>
    const INVOICE = <?= json_encode($invoice) ?>;
    let searchTimer = null;

    $('#searchProduk').on('input', function () {
        clearTimeout(searchTimer);
        const q = $(this).val().trim();
        if (q === '') { $('#searchHasil').empty(); return; }

        searchTimer = setTimeout(function () {
            $.getJSON('api/detailorder_variant_search.php', { q: q }, function (res) {
                $('#searchHasil').empty();
                if (!res.success || res.data.length === 0) {
                    $('#searchHasil').html('<div class="text-muted small p-2">Tidak ada produk dengan stock tersedia</div>');
                    return;
                }
                res.data.forEach(function (v) {
                    const row = $(`
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div>${v.namaproduk} ${v.variant} ${v.size}</div>
                                <small class="text-muted">Rp ${v.harga.toLocaleString('id-ID')} &middot; Stock ${v.stock}</small>
                            </div>
                            <span class="qty-form">
                                <input type="number" min="1" max="${v.stock}" value="1" class="form-control form-control-sm input-qty-tambah">
                                <button type="button" class="btn btn-sm btn-primary btn-tambah-item" data-idproduk="${v.id}">Tambah</button>
                            </span>
                        </div>
                    `);
                    $('#searchHasil').append(row);
                });
            });
        }, 300);
    });

    $(document).on('click', '.btn-tambah-item', function () {
        const idproduk = $(this).data('idproduk');
        const jumlah   = $(this).closest('.list-group-item').find('.input-qty-tambah').val();

        $.post('api/detailorder_add_item.php', { invoice: INVOICE, idproduk: idproduk, jumlah: jumlah }, function (res) {
            if (res.success) {
                location.reload();
            } else {
                alert('Gagal menambah: ' + res.message);
            }
        }, 'json').fail(function () {
            alert('Gagal terhubung ke server');
        });
    });

    $(document).on('click', '.btn-simpan-jumlah', function () {
        const idproduk = $(this).data('idproduk');
        const jumlah   = $(this).closest('.qty-form').find('.input-jumlah').val();

        $.post('api/detailorder_update_qty.php', { invoice: INVOICE, idproduk: idproduk, jumlah: jumlah }, function (res) {
            if (res.success) {
                location.reload();
            } else {
                alert('Gagal mengubah jumlah: ' + res.message);
            }
        }, 'json').fail(function () {
            alert('Gagal terhubung ke server');
        });
    });

    $(document).on('click', '.btn-hapus-item', function () {
        if (!confirm('Hapus item ini dari order? Stock akan dikembalikan.')) return;

        const idproduk = $(this).data('idproduk');

        $.post('api/detailorder_remove_item.php', { invoice: INVOICE, idproduk: idproduk }, function (res) {
            if (res.success) {
                location.reload();
            } else {
                alert('Gagal menghapus: ' + res.message);
            }
        }, 'json').fail(function () {
            alert('Gagal terhubung ke server');
        });
    });
  </script>
  <?php endif; ?>

</body>

</html>
