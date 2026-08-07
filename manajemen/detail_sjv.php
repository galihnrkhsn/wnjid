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
                            <h4 class="text-uppercase">detail sjv</h4>
                            <p class="mb-0"><?= $namapo ?></p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="home.php">SJV</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="input_sjv.php?id=<?= $idpoproduk ?>" class="btn btn-light btn-xs">Tambah Data</a>
                            <a href="totalanbarang.php?id=<?= $idpoproduk ?>" class="btn btn-primary btn-xs">Totalan Barang Vendor</a>
                        </div>

                        <div class="table-responsive">
                            <table id="example" class="display pt-0 pb-1">
                                <thead>
                                    <tr>
                                        <th width="50">No</th>
                                        <th>No SJK</th>
                                        <th>Vendor</th>
                                        <th>Jumlah</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $koneksi->query("SELECT 
                                                                        sjk.*, SUM(sjk.jumlah) AS total_barang, vendor.vendor AS nama_vendor
                                                                    FROM
                                                                        sjk
                                                                            INNER JOIN
                                                                        vendor ON sjk.vendor = vendor.id
                                                                    WHERE
                                                                        sjk.idpoproduk = '$idpoproduk'
                                                                    GROUP BY sjk.sjk
                                                                ");
                                        $no = 1;
                                        while ($data = $sql->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><a href="info_sjk.php?id=<?= $idpoproduk ?>&sjk=<?= $data['sjk'] ?><??>" class="text-secondary"><?= $data['sjk'] ?></a></td>
                                            <td><a href="info_vendor.php?id=<?= $data['idpoproduk'] ?>&vendor=<?= $data['vendor'] ?>" class="text-primary"><?= $data['nama_vendor'] ?></a></td>
                                            <td><?= $data['total_barang'] ?></td>
                                            <td>
                                                <form method="post">
                                                    <input type="hidden" value="<?= $data['sjk'] ?>" name="sjk">
                                                    <button type="submit" class="btn btn-danger btn-sm" name="delete"><i class="bi bi-trash"></i></button>
                                                </form>
                                                <?php
                                                    if (isset($_POST['delete'])) {
                                                        try {
                                                            $sjk = $_POST['sjk'];
                                                            $sql = $koneksi->query("DELETE FROM sjk WHERE sjk = '$sjk'");
                                                            if ($sql) {
                                                                echo "
                                                                    <script>
                                                                        alert('Data berhasil dihapus!')
                                                                        location='detail_sjv.php?id=$idpoproduk'
                                                                    </script>
                                                                ";
                                                            } else {
                                                                echo "
                                                                    <script>
                                                                        alert('Data gagal dihapus!')
                                                                        location='detail_sjv.php?id=$idpoproduk'
                                                                    </script>
                                                                ";
                                                            }
                                                        } catch (Exception $e) {
                                                            echo 'Error: '. $e->getMessage() .'';
                                                        }
                                                    }
                                                ?>
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