<?php
    session_start();
    include 'koneksi.php';
    if (!isset($_SESSION['administrator'])) {
        echo "
            <script>
                alert('Anda harus login terlebih dahulu')
                location='login.php'
            </script>
        ";
        header('location:login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pusat | Wanoja</title>
    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
</head>
<body id="page-top" class="sidebar-toggled">
    <div id="wrapper">
        <?php include 'sidebar.php' ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <h4>Pengiriman</h4>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="tb_pengiriman">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Invoice</th>
                                    <th>Data Pengirim</th>
                                    <th>Data Penerima</th>
                                    <th>Ekspedisi / Layanan</th>
                                    <th>Ongkir</th>
                                    <th>Alamat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    date_default_timezone_set('Asia/Jakarta');
                                    $jumlahhari = '-60 days';
                                    $tgl1   = date('Y-m-d');
                                    $tgl2   = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1)));
                                    $sql    = $koneksi->query("SELECT 
                                                                            *, LEFT(invoice, 1) AS hurufdepan
                                                                        FROM
                                                                            orderpengiriman
                                                                        WHERE
                                                                            tgl > '$tgl2'
                                                                        ORDER BY idorderp DESC
                                                                        LIMIT 4000
                                                                    ");
                                    while ($data = $sql->fetch_assoc()) {
                                ?>
                                    <tr style="font-size: .875rem">
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm mb-1" data-toggle="modal" data-target="#exampleModal-<?= $data['idorderp'] ?>">
                                                Cetak
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal-<?= $data['idorderp'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Data Pengiriman <?= $data['idorderp'] ?></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form method="post" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <input type="hidden" class="form-control form-control-sm" name="idorderp" value="<?= $data['idorderp'] ?>" readonly>
                                                                <input type="hidden" class="form-control form-control-sm" name="kode_mitra" value="<?= $data['hurufdepan'] ?>" readonly>
                                                                <div class="form-group">
                                                                    <label for="namacs" class="mb-0">Nama CS <span class="text-danger">*</span></label>
                                                                    <select id="namacs" name="namacs" class="form-control form-control-sm" required>
                                                                        <option selected>~ Pilih CS ~</option>
                                                                        <?php
                                                                            $scs = $koneksi->query("SELECT * FROM namacs ORDER BY namacs ASC");
                                                                            while ($dcs = $scs->fetch_assoc()) {
                                                                        ?>
                                                                            <option value="<?= $dcs['namacs'] ?>"><?= $dcs['namacs'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="jumlah-koli" class="mb-0">Jumlah Koli <span class="text-danger">*</span></label>
                                                                    <input type="number" name="jumlah_koli" class="form-control form-control-sm" value="0" min="0" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success btn-sm" name="cetak">Cetak</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="ubahpengirim.php?invoice=<?= $data['invoice'] ?>" class="btn btn-sm btn-success">Ubah</a>
                                        </td>
                                        <td>
                                            <?php if ($data['hurufdepan'] == 'A') : ?>
                                                <?php if (strtotime($data['tgl']) > strtotime('2024-11-02 23:59:59')): ?>
                                                    <a href="detailorderagen2.php?invoice=<?= $data['invoice'] ?>" target="_blank"><?= $data['invoice'] ?></a>
                                                <?php else :?>
                                                    <a href="detailorderagen.php?invoice=<?= $data['invoice'] ?>" target="_blank"><?= $data['invoice'] ?></a>
                                                <?php endif; ?>
                                            <?php elseif ($data['hurufdepan'] == 'R') : ?>
                                                <?php if (strtotime($data['tgl']) > strtotime('2024-11-02 23:59:59')): ?>
                                                    <a href="detailreseller2.php?invoice=<?= $data['invoice'] ?>" target="_blank"><?= $data['invoice'] ?></a>
                                                <?php else :?>
                                                    <a href="detailreseller.php?invoice=<?= $data['invoice'] ?>" target="_blank"><?= $data['invoice'] ?></a>
                                                <?php endif; ?>
                                            <?php elseif ($data['hurufdepan'] == 'M') : ?>
                                                <?php if (strtotime($data['tgl']) > strtotime('2024-11-02 23:59:59')): ?>
                                                    <a href="detailmarketer2.php?invoice=<?= $data['invoice'] ?>" target="_blank"><?= $data['invoice'] ?></a>
                                                <?php else :?>
                                                    <a href="detailmarketer.php?invoice=<?= $data['invoice'] ?>" target="_blank"><?= $data['invoice'] ?></a>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <?php if (strtotime($data['tgl']) > strtotime('2024-11-02 23:59:59')): ?>
                                                    <a href="detailorder2.php?invoice=<?= $data['invoice'] ?>" target="_blank"><?= $data['invoice'] ?></a>
                                                <?php else :?>
                                                    <a href="detailorder.php?invoice=<?= $data['invoice'] ?>" target="_blank"><?= $data['invoice'] ?></a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $data['namapengirim'] ?> (<?= $data['tlppengirim'] ?>)</td>
                                        <td><?= $data['namapenerima'] ?> (<?= $data['tlppenerima'] ?>)</td>
                                        <td><?= $data['ekspedisi'] ?> (<?= $data['layanan'] ?>)</td>
                                        <td>
                                            <?php if ($data['ongkir'] == '0') : ?>
                                                <?php if ($_SESSION['administrator']['id'] == 3) : ?>
                                                <a href="updateongkir.php?id=<?= $data['idorderp'] ?>"><?= $data['ongkir'] ?></a>
                                                <?php else : ?>
                                                <?= $data['ongkir'] ?>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <?= $data['ongkir'] ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $data['alamat'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <footer class="sticky-footer">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2020</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->
    </div>

    <?php
        if (isset($_POST['cetak'])) {
            try {
                date_default_timezone_set('Asia/Jakarta');
                $today          = date('Y-m-d');
                $idorderp       = $_POST['idorderp'];
                $namacs         = $_POST['namacs'];
                $kode_mitra     = $_POST['kode_mitra'];
                $jumlah_koli    = $_POST['jumlah_koli'];

                $sql            = $koneksi->query("SELECT * FROM orderpengiriman WHERE idorderp = '$idorderp'");
                $data           = $sql->fetch_assoc();
                $invoice        = $data['invoice'];
                $tanggal        = $data['tgl'];

                if ($kode_mitra == "D") {
                    $cid = $koneksi->query("SELECT idmitra AS idmitra, namamitra AS namamitra FROM ordermitra INNER JOIN admin_mitra ON admin_mitra.idadmin = ordermitra.idmitra WHERE invoice = '$invoice'");
                } elseif ($kode_mitra == "A") {
                    $cid = $koneksi->query("SELECT iddb AS idmitra, namamitra AS namamitra, idmitraagen FROM orderagen INNER JOIN admin_mitra ON admin_mitra.idadmin = orderagen.iddb WHERE invoice = '$invoice'");
                } elseif ($kode_mitra == "R") {
                    $cid = $koneksi->query("SELECT iddb AS idmitra, namamitra AS namamitra, idmitrareseller FROM orderreseller INNER JOIN admin_mitra ON admin_mitra.idadmin = orderreseller.iddb WHERE invoice = '$invoice'");
                } elseif ($kode_mitra == "M") {
                    $cid = $koneksi->query("SELECT iddb AS idmitra, namamitra AS namamitra, idmitramarketer FROM ordermarketer INNER JOIN admin_mitra ON admin_mitra.idadmin = ordermarketer.iddb WHERE invoice = '$invoice'");
                }

                $did = $cid->fetch_assoc();

                $penerima           = $data['namapenerima'];
                $ekspedisi          = $data['ekspedisi'];
                $biayakirim         = $data['ongkir'];
                $nama_pengirim      = $data['namapengirim'];
                $telepon_pengirim   = $data['tlppengirim'];
                $telepon_penerima   = $data['tlppenerima'];
                $alamat             = $data['alamat'];
                $provinsi           = $data['provinsi'];
                $kota               = $data['kota'];
                $kecamatan          = $data['kecamatan'];
                $idadmin            = $did['idmitra'];

                $idmitraagen        = isset($did['idmitraagen']) ? $did['idmitraagen'] : NULL;
                $idmitrareseller    = isset($did['idmitrareseller']) ? $did['idmitrareseller'] : NULL;
                $idmitramarketer    = isset($did['idmitramarketer']) ? $did['idmitramarketer'] : NULL;

                $namamitra          = $did['namamitra'];
                $jenis_mitra        = "WNJ";

                $insertLogistik = $koneksi->query("INSERT INTO logistik3 (`idlogistik`, `tgl`,`penerima`,`ekspedisi`,`noresi`,
                                                                            `biayakirim`,`jumlah_koli`,`keterangan`,`status`,
                                                                            `idadmin`,`idmitraagen`,`idmitrareseller`,`idmitramarketer`,
                                                                            `namacs`,`no_sj`,`jenis_mitra`, `jenis_pengiriman`, `status_pengiriman`) 
                                                                        VALUES 
                                                                            (NULL, '$today', '$penerima', '$ekspedisi', '', '$biayakirim',
                                                                            '$jumlah_koli', '$invoice', NULL, '$idadmin', '$idmitraagen', '$idmitrareseller', '$idmitramarketer', '$namacs', 
                                                                            '$no_sj', '$jenis_mitra', 'ReadyStok', 'Bayar')
                                                                        ");
                if ($insertLogistik) {
                    $clog       = $koneksi->query("SELECT MAX(idlogistik) AS idlogistik FROM logistik3");
                    $dlog       = $clog->fetch_assoc();
                    $idlogistik = $dlog['idlogistik'];

                    $queryTuser = $koneksi->query("INSERT INTO t_user 
                                                        (`id_user`,`idlogistik`,`namacs`,`nama`,`teleponpengirim`,`nama_penerima`,
                                                        `teleponpenerima`,`alamat`,`keterangan`,`ekspedisi`,`invoice`,`status`,
                                                        `created_date`,`modified_date`,`resi_pengiriman`,`ongkir`,`pcs`,
                                                        `marketplace`,`namamitra`,`idadmin`,`no_sj`,`jenis_mitra`) 
                                                    VALUES
                                                        (NULL, '$idlogistik', '$namacs',
                                                        '$nama_pengirim', '$telepon_pengirim', '$penerima',
                                                        '$telepon_penerima', '$alamat, $provinsi, $kota, $kecamatan', '$invoice',
                                                        '$ekspedisi', '$invoice', NULL,
                                                        '$today', NOW(), NULL, '$ongkir', NULL,
                                                        NULL, '$namamitra', '$idadmin', NULL, '$jenis_mitra')
                                                 ");
                    if ($queryTuser) {
                        if (strtotime($tanggal) > strtotime('2024-11-02 23:59:59')) {
                            echo "<script>
                                alert('Data berhasil ditambahkan ke portal!')
                                location='cetakpengiriman2.php?idpengiriman=$idorderp&mitra=wnj'
                                </script>";
                        } else {
                            echo "<script>
                                alert('Data berhasil ditambahkan ke portal!')
                                location='cetakpengiriman.php?idpengiriman=$idorderp'
                                </script>";
                        }
                    } else {
                        echo "<script>
                                 alert('Data gagal ditambahkan ke portal!')
                                 location='pengiriman.php'
                             </script>";
                    } 
                } else {
                    echo "<script>
                             alert('Terjadi kesalahan saat input logistik!')
                             location='pengiriman.php'
                         </script>";
                }
            } catch(Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    ?>

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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script src="assets/dist/js/jquery.min.js"></script>
    <script src="assets/dist/js/bootstrap.min.js"></script>
    <script src="assets/dist/DataTables/datatables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tb_pengiriman').DataTable();
        });
        $(document).ready(function() {
            $('#tb_ongkirmanual').DataTable();
        });
    </script>
</body>
</html>