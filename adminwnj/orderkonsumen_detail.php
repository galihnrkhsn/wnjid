<?php
    session_start();
    include 'koneksi.php';
    include '../includes/order_status_helper.php';

    if (!isset($_SESSION['administrator'])) {
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $invoice = trim($_GET['invoice'] ?? ($_POST['invoice'] ?? ''));
    $pesan   = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['konfirmasi_pembayaran'])) {
        $stmtOrder = $koneksi->prepare("SELECT idorder, status FROM orderkonsumen WHERE invoice = ?");
        $stmtOrder->bind_param('s', $invoice);
        $stmtOrder->execute();
        $order = $stmtOrder->get_result()->fetch_assoc();

        if ($order && $order['status'] === 'Menunggu Konfirmasi Admin') {
            $stmtUpdate = $koneksi->prepare("UPDATE orderkonsumen SET status = 'Diproses', payment_status = 'Lunas' WHERE idorder = ?");
            $stmtUpdate->bind_param('i', $order['idorder']);
            $stmtUpdate->execute();
            $pesan = 'Pembayaran berhasil dikonfirmasi, order masuk status Diproses.';

            kirimEmailStatusOrder($koneksi, $order['idorder'], 'Pembayaran Dikonfirmasi - ' . $invoice,
                '<p>Pembayaran untuk pesanan <strong>' . htmlspecialchars($invoice) . '</strong> sudah kami konfirmasi.</p>'
                . '<p>Pesananmu sekarang sedang diproses & disiapkan untuk dikirim.</p>');
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['batalkan'])) {
        $stmtOrder = $koneksi->prepare("SELECT idorder, status FROM orderkonsumen WHERE invoice = ?");
        $stmtOrder->bind_param('s', $invoice);
        $stmtOrder->execute();
        $order = $stmtOrder->get_result()->fetch_assoc();

        if ($order && !in_array($order['status'], ['Menunggu Resi', 'Sedang dalam perjalanan', 'Terkirim', 'Selesai', 'Dibatalkan'], true)) {
            $koneksi->begin_transaction();
            try {
                $stmtItems = $koneksi->prepare("SELECT idvariant, jumlah FROM orderkonsumen_detail WHERE idorder = ?");
                $stmtItems->bind_param('i', $order['idorder']);
                $stmtItems->execute();
                $items = $stmtItems->get_result()->fetch_all(MYSQLI_ASSOC);

                $stmtRestock = $koneksi->prepare("UPDATE variants SET stock = stock + ? WHERE id = ?");
                foreach ($items as $item) {
                    $stmtRestock->bind_param('ii', $item['jumlah'], $item['idvariant']);
                    $stmtRestock->execute();
                }

                $stmtCancel = $koneksi->prepare("UPDATE orderkonsumen SET status = 'Dibatalkan' WHERE idorder = ?");
                $stmtCancel->bind_param('i', $order['idorder']);
                $stmtCancel->execute();

                $koneksi->commit();
                $pesan = 'Order dibatalkan, stok sudah dikembalikan.';
            } catch (Exception $e) {
                $koneksi->rollback();
                error_log($e->getMessage());
                $pesan = 'Gagal membatalkan order, silakan coba lagi.';
            }
        }
    }

    $stmtOrder = $koneksi->prepare("SELECT * FROM orderkonsumen WHERE invoice = ?");
    $stmtOrder->bind_param('s', $invoice);
    $stmtOrder->execute();
    $order = $stmtOrder->get_result()->fetch_assoc();

    if (!$order) {
        echo "<script>alert('Order tidak ditemukan');</script>";
        echo "<script>location='orderkonsumen.php';</script>";
        exit();
    }

    $stmtItems = $koneksi->prepare("SELECT * FROM orderkonsumen_detail WHERE idorder = ? ORDER BY iddetail ASC");
    $stmtItems->bind_param('i', $order['idorder']);
    $stmtItems->execute();
    $items = $stmtItems->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmtBayar = $koneksi->prepare("SELECT * FROM orderkonsumen_pembayaran WHERE idorder = ? ORDER BY idpembayaran DESC LIMIT 1");
    $stmtBayar->bind_param('i', $order['idorder']);
    $stmtBayar->execute();
    $pembayaran = $stmtBayar->get_result()->fetch_assoc();

    [$badgeColor, $badgeLabel] = orderKonsumenStatusBadge($order['status']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Pusat | Wanoja</title>

    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style type="text/css">
        body{ padding-right: 0px !important; }
        .bukti-transfer-img { max-width: 320px; border: 1px solid #ddd; border-radius: 6px; }
    </style>
</head>

<body id="page-top" class="sidebar-toggled">

    <div id="wrapper">

        <?php include "sidebar.php"; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"></h1>
                    </div>
                    <h3><strong>Detail Order Konsumen</strong></h3><br>

                    <?php if ($pesan !== ''): ?>
                        <div class="alert alert-info"><?= htmlspecialchars($pesan) ?></div>
                    <?php endif; ?>

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small">Invoice</div>
                                    <div class="font-weight-bold"><?= htmlspecialchars($order['invoice']) ?></div>
                                </div>
                                <span class="badge badge-<?= $badgeColor ?> p-2"><?= htmlspecialchars($badgeLabel) ?></span>
                            </div>
                            <div class="text-muted small mt-2">Dipesan pada <?= date('d-m-Y H:i', strtotime($order['tgl'])) ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">Alamat Pengiriman</div>
                                <div class="card-body">
                                    <div class="font-weight-bold"><?= htmlspecialchars($order['nama_penerima']) ?></div>
                                    <div><?= htmlspecialchars($order['telepon_penerima']) ?></div>
                                    <div><?= htmlspecialchars($order['alamat_lengkap']) ?></div>
                                    <div class="text-muted small">
                                        <?= htmlspecialchars(trim(implode(', ', array_filter([$order['kecamatan'], $order['kota'], $order['provinsi'], $order['kodepos']])))) ?>
                                    </div>
                                    <?php if (!empty($order['ekspedisi'])): ?>
                                        <div class="mt-2"><strong>Ekspedisi:</strong> <?= htmlspecialchars($order['ekspedisi']) ?> <?= !empty($order['layanan']) ? '(' . htmlspecialchars($order['layanan']) . ')' : '' ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($order['catatan'])): ?>
                                        <div class="mt-2"><strong>Catatan:</strong> <?= htmlspecialchars($order['catatan']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if ($pembayaran): ?>
                                <div class="card mb-3">
                                    <div class="card-header">Bukti Transfer</div>
                                    <div class="card-body">
                                        <div><strong>Bank Pengirim:</strong> <?= htmlspecialchars($pembayaran['bank_pengirim']) ?></div>
                                        <div><strong>No. Rekening / Atas Nama:</strong> <?= htmlspecialchars($pembayaran['rekening_pengirim']) ?></div>
                                        <div><strong>Jumlah Transfer:</strong> Rp <?= number_format($pembayaran['jumlah_transfer']) ?></div>
                                        <?php if (!empty($pembayaran['foto'])): ?>
                                            <div class="mt-2">
                                                <a href="../image/bukti_transfer/<?= htmlspecialchars($pembayaran['foto']) ?>" target="_blank">
                                                    <img src="../image/bukti_transfer/<?= htmlspecialchars($pembayaran['foto']) ?>" class="bukti-transfer-img" alt="Bukti Transfer">
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">Barang Dipesan</div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th>Qty</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($items as $item): ?>
                                                <tr>
                                                    <td>
                                                        <?= htmlspecialchars($item['namaproduk']) ?>
                                                        <div class="text-muted small"><?= htmlspecialchars($item['variant']) ?> <?= htmlspecialchars($item['size'] ?? '') ?></div>
                                                    </td>
                                                    <td><?= (int) $item['jumlah'] ?></td>
                                                    <td>Rp <?= number_format($item['subtotal']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-between"><span>Subtotal</span><span>Rp <?= number_format($order['subtotal']) ?></span></div>
                                    <div class="d-flex justify-content-between"><span>Ongkir</span><span>Rp <?= number_format($order['ongkir']) ?></span></div>
                                    <div class="d-flex justify-content-between font-weight-bold"><span>Total</span><span>Rp <?= number_format($order['total']) ?></span></div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-body">
                                    <?php if ($order['status'] === 'Menunggu Konfirmasi Admin'): ?>
                                        <form method="post" class="mb-2" onsubmit="return confirm('Konfirmasi pembayaran order ini?');">
                                            <input type="hidden" name="invoice" value="<?= htmlspecialchars($order['invoice']) ?>">
                                            <button type="submit" name="konfirmasi_pembayaran" class="btn btn-success btn-block">
                                                <i class="fas fa-check-circle"></i> Konfirmasi Pembayaran
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if (!in_array($order['status'], ['Menunggu Resi', 'Sedang dalam perjalanan', 'Terkirim', 'Selesai', 'Dibatalkan'], true)): ?>
                                        <form method="post" onsubmit="return confirm('Batalkan order ini? Stok akan dikembalikan.');">
                                            <input type="hidden" name="invoice" value="<?= htmlspecialchars($order['invoice']) ?>">
                                            <button type="submit" name="batalkan" class="btn btn-outline-danger btn-block">
                                                <i class="fas fa-times-circle"></i> Batalkan Pesanan
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <a href="orderkonsumen.php" class="btn btn-link btn-block">&larr; Kembali ke List Order</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
        </div>

    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>
