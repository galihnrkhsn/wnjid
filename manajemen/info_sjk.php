<?php
    session_start();

    include 'koneksi.php';

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
        echo "
            <script>alert('Anda harus login terlebih dahulu!');</script>
            <script>location='login-multi.php';</script>
        ";
        header("Location: login-multi.php");
        exit();
    }

    $id = $_SESSION['user_id'];
    $role = $_SESSION['user_level'];
    $idpoproduk = $_GET['id'];
    $sjk = $_GET['sjk'];

    $query = $koneksi->query("SELECT * FROM user_manajemen INNER JOIN role WHERE user_manajemen.id = '$id'");
    $user = $query->fetch_assoc();
    $username = $user['username'];


    date_default_timezone_set('Asia/Jakarta');
    $dateNow = date("Y-m-d");
    $dayKemarin = date( 'Y-m-d', strtotime( $dateNow . ' -1 day' ) );
    $dayLusa = date( 'Y-m-d', strtotime( $dateNow . ' -2 day' ) );

    function convertToIndonesianDate($date) {
        // Create a timestamp from the provided date
        $timestamp = strtotime($date);

        // Define arrays for Indonesian days and months
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // Get the day of the week and month
        $day = $days[date('w', $timestamp)];
        $day_number = date('d', $timestamp);
        $month = $months[date('n', $timestamp) - 1];
        $year = date('Y', $timestamp);

        // Return the formatted date
        return "$day, $day_number $month $year";
    }

    $day = convertToIndonesianDate($dateNow);
    $yesterday = convertToIndonesianDate($dayKemarin);
    $days = convertToIndonesianDate($dayLusa);

    $poproduk = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = '$idpoproduk'");
    $datapo = $poproduk->fetch_assoc();
    $namapo = $datapo['namapo'];

    $sql_vendor = $koneksi->query("SELECT vendor.vendor FROM sjk INNER JOIN vendor ON vendor.id = sjk.vendor WHERE sjk.sjk = '$sjk'");
    $data_vendor = $sql_vendor->fetch_assoc();
    $vendor = $data_vendor['vendor'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WNJ.ID | <?= $username ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="template/images/logo.png">
    <link rel="stylesheet" href="../vendor/manajemen-template/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../vendor/manajemen-template/owl-carousel/css/owl.theme.default.min.css">
    <link href="../vendor/manajemen-template/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="template/css/style.css" rel="stylesheet">
    <link href="../vendor/manajemen-template/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <style>
        .dataTables_wrapper .dataTables_scroll {
            padding: 0;
        }
    </style>
</head>
<body>

    <div id="main-wrapper">
        <!--**********************************
            Nav header start
        ***********************************-->
        <?php include 'template/component/humberger.php'; ?>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <?php include 'template/component/header.php'; ?>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <?php include 'template/component/sidebar.php'; ?>
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4 class="text-uppercase">data barang vendor</h4>
                            <p class="mb-0">Barang <?= $sjk ?></p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="home.php">...</a></li>
                            <li class="breadcrumb-item"><a href="detail_sjv.php?id=<?= $idpoproduk ?>">Detail</a></li>
                            <li class="breadcrumb-item active"><?= $sjk ?></li>
                        </ol>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                            <p class="p-0 m-0">Vendor: <span class="font-weight-medium text-dark"><?= $vendor ?></span></p>
                            <p class="p-0 m-0">Nama PO: <span class="font-weight-medium text-dark"><?= $namapo ?></span></p>

                            <div class="d-flex algin-items-center mt-1">
                                <a href="ubah_sjk.php?id=<?= $idpoproduk ?>&sjk=<?= $sjk ?>" class="btn btn-dark btn-xs">Ubah Data</a>
                                <a href="input_variant_sjk.php?id=<?= $idpoproduk ?>&sjk=<?= $sjk ?>" class="btn btn-light btn-xs mx-2">Tambah Data</a>
                            </div>
                        </div>
                        
                        <hr class="mb-3" />

                        <div class="table-responsive">
                            <table id="example" class="display pt-0 pb-1">
                                <thead>
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Nama Produk</th>
                                        <th>QTY</th>
                                        <th>Diupdate Pada</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $koneksi->query("SELECT 
                                                                        sjk.*, sjk.jumlah, podetail.variant, sjk.waktu_update
                                                                    FROM
                                                                        sjk
                                                                            JOIN
                                                                        podetail ON podetail.idpodetail = sjk.idpodetail
                                                                    WHERE
                                                                        sjk.sjk = '$sjk'
                                                                ");
                                        $no = 1;
                                        while ($data = $sql->fetch_assoc()) {
                                            $sjk = $data['sjk'];
                                            $idsjk = $data['idsjk'];
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $data['variant'] ?></td>
                                            <td><?= $data['jumlah'] ?></td>
                                            <td><?= $data['waktu_update'] ?></td>
                                            <td>
                                                <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-warning btn-xs text-light" data-toggle="modal" data-target="#basicModal<?= $idsjk ?>"><i class="bi bi-pencil-square"></i></button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="basicModal<?= $idsjk ?>">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><?= $data['variant'] ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="<?= $data['idpodetail'] ?>" class="mb-1">Qty</label>
                                                                        <input type="hidden" class="form-control form-control-sm bg-light" name="idpodetail" value="<?= $data['idpodetail'] ?>" readonly>
                                                                        <input type="number" class="form-control form-control-sm" min="0" name="qty" value="<?= $data['jumlah'] ?>">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="<?= $data['idpodetail'] ?>" class="mb-1">Barang GB</label>
                                                                        <input type="number" class="form-control form-control-sm" min="0" name="gb" value="<?= $data['brggb'] ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary btn-xs" name="edit">Save changes</button>
                                                                </div>
                                                            </form>

                                                            <?php
                                                                if (isset($_POST['edit'])) {
                                                                    try {
                                                                        $idpodetail = $_POST['idpodetail'];
                                                                        $qty = $_POST['qty'];
                                                                        $gb = $_POST['gb'];

                                                                        $sql_exist = $koneksi->query("SELECT * FROM sjk WHERE sjk = '$sjk' AND idpodetail = '$idpodetail'");
                                                                        $data_sjk = $sql_exist->fetch_assoc();
                                                                        $dd = $data_sjk['idsjk'];
                                                                        $ga = $qty - $gb;

                                                                        $sql = $koneksi->query("UPDATE sjk SET jumlah = '$qty', brgga = '$ga', brggb = '$gb', waktu_update = NOW() WHERE sjk = '$sjk' AND idpodetail = '$idpodetail'");

                                                                        if ($sql) {
                                                                            echo "
                                                                                <script>
                                                                                    alert('Data berhasil diupdate')
                                                                                    location='info_sjk.php?id=$idpoproduk&sjk=$sjk'
                                                                                </script>
                                                                            ";
                                                                        } else {
                                                                             echo "
                                                                                <script>
                                                                                    alert('Data gagal diupdate')
                                                                                    location='info_sjk.php?id=$idpoproduk&sjk=$sjk'
                                                                                </script>
                                                                            ";
                                                                        }
                                                                    } catch (Exception $e) {
                                                                        echo "
                                                                            <script>
                                                                                alert('Error!')
                                                                                location='home.php'
                                                                            </script>
                                                                        ";
                                                                    }
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="#" target="_blank">Quixkit</a> 2019</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->
    </div>

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="../vendor/manajemen-template/global/global.min.js"></script>
    <script src="template/js/quixnav-init.js"></script>
    <script src="template/js/custom.min.js"></script>


    <!-- Vectormap -->
    <script src="../vendor/manajemen-template/raphael/raphael.min.js"></script>
    <script src="../vendor/manajemen-template/morris/morris.min.js"></script>
    <script src="../vendor/manajemen-template/circle-progress/circle-progress.min.js"></script>
    <script src="../vendor/manajemen-template/chart.js/Chart.bundle.min.js"></script>
    <script src="../vendor/manajemen-template/gaugeJS/dist/gauge.min.js"></script>

    <!--  flot-chart js -->
    <script src="../vendor/manajemen-template/flot/jquery.flot.js"></script>
    <script src="../vendor/manajemen-template/flot/jquery.flot.resize.js"></script>

    <!-- Owl Carousel -->
    <script src="../vendor/manajemen-template/owl-carousel/js/owl.carousel.min.js"></script>

    <!-- Counter Up -->
    <script src="../vendor/manajemen-template/jqvmap/js/jquery.vmap.min.js"></script>
    <script src="../vendor/manajemen-template/jqvmap/js/jquery.vmap.usa.js"></script>
    <script src="../vendor/manajemen-template/jquery.counterup/jquery.counterup.min.js"></script>

    <script src="template/js/dashboard/dashboard-1.js"></script>

    <!-- Datatable -->
    <script src="../vendor/manajemen-template/datatables/js/jquery.dataTables.min.js"></script>
    <script src="template/js/plugins-init/datatables.init.js"></script>
    <script src="../vendor/manajemen-template/global/global.min.js"></script>
    <script src="template/js/quixnav-init.js"></script>
    <script src="template/js/custom.min.js"></script>
</body>
</html>