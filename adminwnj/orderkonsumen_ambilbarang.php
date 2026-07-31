<?php
    session_start();
    include 'koneksi.php';

    if (!isset($_SESSION['administrator'])) {
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $stmt = $koneksi->prepare("SELECT idorder, invoice, nama_penerima, tgl FROM orderkonsumen WHERE status = 'Diproses' ORDER BY tgl ASC");
    $stmt->execute();
    $daftarOrder = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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
                    <h3><strong>Ambil Barang Order Konsumen</strong></h3><br>
                    <p class="text-muted">Order konsumen dengan status <strong>Diproses</strong> (sudah dibayar, barang siap disiapkan). Pilih order lalu klik "Print Data" untuk mencetak daftar barang yang perlu diambil.</p>

                    <form method="post" action="orderkonsumen_ambilbarang_print.php" target="_blank">
                        <button type="submit" class="btn btn-primary mb-2" name="print"><span class="fas fa-print"></span> Print Data</button>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="tb_ambilbarang_konsumen">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>Invoice</th>
                                        <th>Nama Penerima</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($daftarOrder as $order): ?>
                                        <tr>
                                            <td><input type="checkbox" name="invoice[]" value="<?= htmlspecialchars($order['invoice']) ?>"></td>
                                            <td><?= htmlspecialchars($order['invoice']) ?></td>
                                            <td><?= htmlspecialchars($order['nama_penerima']) ?></td>
                                            <td><?= date('d-m-Y H:i', strtotime($order['tgl'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
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

    <?php include "settingdatatables.php"; ?>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#checkAll').change(function () {
                $('input[name="invoice[]"]').prop('checked', $(this).is(':checked'));
            });
        });
    </script>
</body>

</html>
