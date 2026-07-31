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

    $tabStatus = [
        'Menunggu Konfirmasi Admin',
        'Menunggu Pembayaran',
        'Diproses',
        'Menunggu Resi',
        'Sedang dalam perjalanan',
        'Dibatalkan',
        'Semua',
    ];
    $status = $_GET['status'] ?? 'Menunggu Konfirmasi Admin';
    if (!in_array($status, $tabStatus, true)) {
        $status = 'Menunggu Konfirmasi Admin';
    }

    if ($status === 'Semua') {
        $stmtList = $koneksi->prepare("SELECT * FROM orderkonsumen ORDER BY tgl DESC LIMIT 2000");
        $stmtList->execute();
    } else {
        $stmtList = $koneksi->prepare("SELECT * FROM orderkonsumen WHERE status = ? ORDER BY tgl DESC LIMIT 2000");
        $stmtList->bind_param('s', $status);
        $stmtList->execute();
    }
    $daftarOrder = $stmtList->get_result()->fetch_all(MYSQLI_ASSOC);
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
    <style type="text/css">
        body{
            padding-right: 0px !important;
        }
    </style>
</head>

<body id="page-top" class="sidebar-toggled">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include "sidebar.php"; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"></h1>
                    </div>
                    <h3><strong>Order Konsumen</strong></h3><br>

                    <ul class="nav nav-tabs">
                        <?php foreach ($tabStatus as $tab): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $tab === $status ? 'active' : '' ?>" href="orderkonsumen.php?status=<?= urlencode($tab) ?>"><?= htmlspecialchars($tab) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered" id="tb_orderkonsumen">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Invoice</th>
                                    <th>Nama Penerima</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($daftarOrder as $order): ?>
                                    <?php [$badgeColor, $badgeLabel] = orderKonsumenStatusBadge($order['status']); ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= date('d-m-Y H:i', strtotime($order['tgl'])) ?></td>
                                        <td><?= htmlspecialchars($order['invoice']) ?></td>
                                        <td><?= htmlspecialchars($order['nama_penerima']) ?></td>
                                        <td>Rp <?= number_format($order['total']) ?></td>
                                        <td><span class="badge badge-<?= $badgeColor ?>"><?= htmlspecialchars($badgeLabel) ?></span></td>
                                        <td><a href="orderkonsumen_detail.php?invoice=<?= urlencode($order['invoice']) ?>" class="btn btn-primary btn-sm">Detail</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- End of Main Content -->
            </div>

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

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

    <?php include "settingdatatables.php"; ?>

</body>

</html>
