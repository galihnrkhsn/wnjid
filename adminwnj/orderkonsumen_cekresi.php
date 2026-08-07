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
    include '../includes/rajaongkir_helper.php';

    $hasil = null; // null = tombol belum diklik di request ini

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cek_resi'])) {
        $orders = $koneksi->query("
            SELECT o.idorder, o.invoice, o.status, o.ekspedisi,
                   (SELECT resi_pengiriman FROM t_user
                    WHERE invoice = o.invoice AND resi_pengiriman IS NOT NULL AND resi_pengiriman != ''
                    ORDER BY id_user DESC LIMIT 1) AS no_resi
            FROM orderkonsumen o
            WHERE o.status IN ('Diproses', 'Menunggu Resi', 'Sedang dalam perjalanan')
        ")->fetch_all(MYSQLI_ASSOC);

        $hasil = ['dicek' => 0, 'terkirim' => 0, 'detail' => []];

        foreach ($orders as $order) {
            $noResi    = $order['no_resi'] ?? '';
            $kurirKode = kodeKurirDariEkspedisi($order['ekspedisi'] ?? '');

            if ($noResi === '' || $kurirKode === '') {
                continue;
            }

            $hasil['dicek']++;
            $tracking = cekDanTandaiTerkirim($koneksi, (int) $order['idorder'], $order['status'], $noResi, $kurirKode);

            $sudahDelivered = ($tracking['delivered'] ?? false) === true || strtoupper($tracking['status'] ?? '') === 'DELIVERED';
            if ($sudahDelivered) {
                $hasil['terkirim']++;
            }

            $hasil['detail'][] = [
                'invoice'    => $order['invoice'],
                'no_resi'    => $noResi,
                'ekspedisi'  => $order['ekspedisi'],
                'status_api' => $tracking['status'] !== '' ? $tracking['status'] : '-',
                'terkirim'   => $sudahDelivered,
            ];
        }
    }

    $totalEligible = $koneksi->query("
        SELECT COUNT(*) AS c FROM orderkonsumen o
        WHERE o.status IN ('Diproses', 'Menunggu Resi', 'Sedang dalam perjalanan')
          AND EXISTS (
              SELECT 1 FROM t_user
              WHERE invoice = o.invoice AND resi_pengiriman IS NOT NULL AND resi_pengiriman != ''
          )
    ")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>WNJ.ID</title>

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
                    <h3><strong>Cek &amp; Tandai Terkirim</strong></h3><br>
                    <p class="text-muted">
                        Cek status resi order yang masih berjalan (Diproses / Menunggu Resi / Sedang dalam perjalanan) lewat API tracking kurir.
                        Order yang sudah <strong>DELIVERED</strong> otomatis ditandai <strong>Terkirim</strong>.
                        Dijalankan manual (klik tombol) supaya tidak boros limit harian API tracking.
                    </p>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <p class="mb-3">
                                Order dengan resi yang siap dicek saat ini: <strong><?= (int) $totalEligible ?></strong>
                            </p>
                            <form method="post" onsubmit="return confirm('Cek resi untuk <?= (int) $totalEligible ?> order sekarang? Ini akan memanggil API tracking kurir.');">
                                <button type="submit" name="cek_resi" class="btn btn-primary">
                                    <i class="fas fa-sync-alt"></i> Cek &amp; Tandai Terkirim Sekarang
                                </button>
                            </form>
                        </div>
                    </div>

                    <?php if ($hasil !== null): ?>
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Hasil Pengecekan</h5>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="alert alert-info mb-0">Total Dicek: <strong><?= $hasil['dicek'] ?></strong></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-success mb-0">Ditandai Terkirim: <strong><?= $hasil['terkirim'] ?></strong></div>
                                    </div>
                                </div>

                                <?php if (!empty($hasil['detail'])): ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Invoice</th>
                                                    <th>Ekspedisi</th>
                                                    <th>No. Resi</th>
                                                    <th>Status API</th>
                                                    <th>Hasil</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($hasil['detail'] as $d): ?>
                                                    <tr class="<?= $d['terkirim'] ? 'table-success' : '' ?>">
                                                        <td><a href="orderkonsumen_detail.php?invoice=<?= urlencode($d['invoice']) ?>" target="_blank"><?= htmlspecialchars($d['invoice']) ?></a></td>
                                                        <td><?= htmlspecialchars($d['ekspedisi']) ?></td>
                                                        <td><?= htmlspecialchars($d['no_resi']) ?></td>
                                                        <td><?= htmlspecialchars($d['status_api']) ?></td>
                                                        <td><?= $d['terkirim'] ? '<span class="badge badge-success">Ditandai Terkirim</span>' : '<span class="badge badge-secondary">Belum</span>' ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-muted">Tidak ada order dengan resi yang bisa dicek saat ini.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

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
