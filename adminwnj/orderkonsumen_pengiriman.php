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
    date_default_timezone_set('Asia/Jakarta');

    $pesan = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cetak'])) {
        $idorder     = (int) $_POST['idorder'];
        $namacs      = trim($_POST['namacs'] ?? '');
        $jumlahKoli  = (int) ($_POST['jumlah_koli'] ?? 0);

        $stmtOrder = $koneksi->prepare("SELECT * FROM orderkonsumen WHERE idorder = ? AND status = 'Diproses'");
        $stmtOrder->bind_param('i', $idorder);
        $stmtOrder->execute();
        $order = $stmtOrder->get_result()->fetch_assoc();

        if ($order && $namacs !== '') {
            $today   = date('Y-m-d');
            $alamat  = $order['alamat_lengkap'] . ', ' . $order['provinsi'] . ', ' . $order['kota'] . ', ' . $order['kecamatan'];
            $ongkir  = (string) $order['ongkir'];

            $koneksi->begin_transaction();
            try {
                $stmtLogistik = $koneksi->prepare("INSERT INTO logistik3
                                                        (idlogistik, tgl, penerima, ekspedisi, noresi, biayakirim, jumlah_koli,
                                                         keterangan, status, idadmin, idmitraagen, idmitrareseller, idmitramarketer,
                                                         namacs, no_sj, jenis_mitra, jenis_pengiriman, status_pengiriman)
                                                    VALUES (NULL, ?, ?, ?, '', ?, ?, ?, NULL, NULL, NULL, NULL, NULL, ?, NULL, 'WNJ', 'ReadyStok', 'Bayar')");
                $stmtLogistik->bind_param(
                    'ssssiss',
                    $today, $order['nama_penerima'], $order['ekspedisi'], $ongkir, $jumlahKoli, $order['invoice'], $namacs
                );
                $stmtLogistik->execute();

                $idlogistik = $koneksi->insert_id;

                $stmtTuser = $koneksi->prepare("INSERT INTO t_user
                                                    (id_user, idlogistik, namacs, nama, teleponpengirim, nama_penerima,
                                                     teleponpenerima, alamat, keterangan, ekspedisi, invoice, status,
                                                     created_date, modified_date, resi_pengiriman, ongkir, pcs,
                                                     marketplace, namamitra, idadmin, no_sj, jenis_mitra)
                                                VALUES (NULL, ?, ?, 'Wanoja', '', ?,
                                                        ?, ?, ?, ?, ?, NULL,
                                                        ?, NOW(), NULL, ?, NULL,
                                                        NULL, ?, NULL, NULL, 'WNJ')");
                $idlogistikStr = (string) $idlogistik;
                $stmtTuser->bind_param(
                    'sssssssssss',
                    $idlogistikStr, $namacs, $order['nama_penerima'],
                    $order['telepon_penerima'], $alamat, $order['invoice'], $order['ekspedisi'], $order['invoice'],
                    $today, $ongkir, $order['nama_penerima']
                );
                $stmtTuser->execute();

                $stmtStatus = $koneksi->prepare("UPDATE orderkonsumen SET status = 'Menunggu Resi' WHERE idorder = ?");
                $stmtStatus->bind_param('i', $idorder);
                $stmtStatus->execute();

                // Baris orderpengiriman dibuat juga supaya bisa pakai ulang cetakpengiriman2.php apa adanya
                // (halaman cetak itu & API QR-nya keyed ke orderpengiriman.idorderp, bukan logistik3.idlogistik)
                $beratStr = (string) $order['berat'];
                $ongkirInt = (int) $order['ongkir'];
                $totalInt  = (int) $order['total'];

                $stmtPengiriman = $koneksi->prepare("INSERT INTO orderpengiriman
                                                        (idorderp, namapengirim, tlppengirim, namapenerima, tlppenerima, alamat,
                                                         provinsi, kota, kecamatan, ekspedisi, layanan, berat, ongkir, dropship,
                                                         kodepos, invoice, total, diskonramadhan, tgl)
                                                    VALUES (NULL, 'Wanoja', '', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'tidak', ?, ?, ?, 0, NOW())");
                $stmtPengiriman->bind_param(
                    'sssssssssissi',
                    $order['nama_penerima'], $order['telepon_penerima'], $order['alamat_lengkap'],
                    $order['provinsi'], $order['kota'], $order['kecamatan'],
                    $order['ekspedisi'], $order['layanan'], $beratStr, $ongkirInt,
                    $order['kodepos'], $order['invoice'], $totalInt
                );
                $stmtPengiriman->execute();
                $idorderp = $koneksi->insert_id;

                $koneksi->commit();

                header('Location: cetakpengiriman2.php?idpengiriman=' . $idorderp . '&mitra=wnj');
                exit();
            } catch (Exception $e) {
                $koneksi->rollback();
                error_log($e->getMessage());
                $pesan = 'Gagal mengirim ke portal kurir, silakan coba lagi.';
            }
        }
    }

    $stmt = $koneksi->prepare("SELECT * FROM orderkonsumen WHERE status = 'Diproses' ORDER BY tgl ASC");
    $stmt->execute();
    $daftarOrder = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $daftarCs = $koneksi->query("SELECT * FROM namacs ORDER BY namacs ASC");
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
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
                    <h3><strong>Pengiriman Order Konsumen</strong></h3><br>
                    <p class="text-muted">Order konsumen status <strong>Diproses</strong> yang siap dikirim. Klik "Cetak" untuk mengirim data ke portal kurir (resi nanti diisi di portal kurir).</p>

                    <?php if ($pesan !== ''): ?>
                        <div class="alert alert-info"><?= htmlspecialchars($pesan) ?></div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="tb_pengiriman_konsumen">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Invoice</th>
                                    <th>Nama Penerima</th>
                                    <th>Ekspedisi / Layanan</th>
                                    <th>Ongkir</th>
                                    <th>Alamat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($daftarOrder as $order): ?>
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalCetak-<?= (int) $order['idorder'] ?>">
                                                Cetak
                                            </button>
                                            <div class="modal fade" id="modalCetak-<?= (int) $order['idorder'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Data Pengiriman <?= htmlspecialchars($order['invoice']) ?></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="idorder" value="<?= (int) $order['idorder'] ?>">
                                                                <div class="form-group">
                                                                    <label class="mb-0">Nama CS <span class="text-danger">*</span></label>
                                                                    <select name="namacs" class="form-control form-control-sm" required>
                                                                        <option value="" selected>~ Pilih CS ~</option>
                                                                        <?php $daftarCs->data_seek(0); while ($cs = $daftarCs->fetch_assoc()): ?>
                                                                            <option value="<?= htmlspecialchars($cs['namacs']) ?>"><?= htmlspecialchars($cs['namacs']) ?></option>
                                                                        <?php endwhile; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="mb-0">Jumlah Koli <span class="text-danger">*</span></label>
                                                                    <input type="number" name="jumlah_koli" class="form-control form-control-sm" value="1" min="1" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" name="cetak" class="btn btn-success btn-sm">Cetak</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><a href="orderkonsumen_detail.php?invoice=<?= urlencode($order['invoice']) ?>" target="_blank"><?= htmlspecialchars($order['invoice']) ?></a></td>
                                        <td><?= htmlspecialchars($order['nama_penerima']) ?> (<?= htmlspecialchars($order['telepon_penerima']) ?>)</td>
                                        <td><?= htmlspecialchars($order['ekspedisi']) ?> (<?= htmlspecialchars($order['layanan']) ?>)</td>
                                        <td><?= number_format($order['ongkir']) ?></td>
                                        <td><?= htmlspecialchars($order['alamat_lengkap']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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

    <?php include "settingdatatables.php"; ?>
</body>

</html>
