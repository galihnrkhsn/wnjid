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
    $idvendor = $_GET['vendor'];

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

    $sql_vendor = $koneksi->query("SELECT * FROM vendor WHERE id = '$idvendor'");
    $data_vendor = $sql_vendor->fetch_assoc();
    $nama_vendor = $data_vendor['vendor'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wanoja | <?= $username ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="template/images/logo.png">
    <link rel="stylesheet" href="template/vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="template/vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="template/vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="template/css/style.css" rel="stylesheet">
    <link href="template/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

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
                            <h4 class="text-uppercase">data vendor</h4>
                            <p class="mb-0">Vendor <?= $nama_vendor ?></p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="home.php">...</a></li>
                            <li class="breadcrumb-item"><a href="detail_sjv.php?id=<?= $idpoproduk ?>">Detail</a></li>
                            <li class="breadcrumb-item active"><?= $nama_vendor ?></li>
                        </ol>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display pt-0 pb-1">
                                <thead>
                                    <tr>
                                        <th width="50">No</th>
                                        <th>No SJK</th>
                                        <th>Nama PO</th>
                                        <th>Jumlah</th>
                                        <th>Surat Jalan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $koneksi->query("SELECT 
                                                                        sjk.idsjk,
                                                                        sjk.sjk,
                                                                        SUM(sjk.jumlah) AS jumlah,
                                                                        poproduk.namapo,
                                                                        MAX(sjk.image) AS image
                                                                    FROM
                                                                        sjk
                                                                            INNER JOIN
                                                                        poproduk ON poproduk.idpoproduk = sjk.idpoproduk
                                                                    WHERE
                                                                        sjk.idpoproduk = '$idpoproduk'
                                                                            AND sjk.vendor = '$idvendor'
                                                                    GROUP BY sjk.sjk , poproduk.namapo
                                                                ");
                                        $no = 1;
                                        while ($data = $sql->fetch_assoc()) {
                                            $idsjk = $data['idsjk'];
                                            $sjk = $data['sjk'];
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><a href="info_sjk.php?id=<?= $idpoproduk ?>&sjk=<?= $sjk ?>" class="text-primary"><?= $data['sjk'] ?></a></td>
                                            <td><?= $data['namapo'] ?></td>
                                            <td><?= $data['jumlah'] ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#basicModal_<?= $idsjk ?>"><i class="bi bi-eye"></i></button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="basicModal_<?= $idsjk ?>">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <?php
                                                                $sql_img = $koneksi->query("SELECT image FROM sjk WHERE idsjk = '$idsjk'");
                                                                $data_img = $sql_img->fetch_assoc();
                                                                $image = $data_img['image'];
                                                            ?>
                                                            <div class="modal-body p-4">
                                                                <img src="buktivendor/<?= $image ?>" alt="..." class="img-fluid rounded">
                                                            </div>
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
    <script src="template/vendor/global/global.min.js"></script>
    <script src="template/js/quixnav-init.js"></script>
    <script src="template/js/custom.min.js"></script>


    <!-- Vectormap -->
    <script src="template/vendor/raphael/raphael.min.js"></script>
    <script src="template/vendor/morris/morris.min.js"></script>
    <script src="template/vendor/circle-progress/circle-progress.min.js"></script>
    <script src="template/vendor/chart.js/Chart.bundle.min.js"></script>
    <script src="template/vendor/gaugeJS/dist/gauge.min.js"></script>

    <!--  flot-chart js -->
    <script src="template/vendor/flot/jquery.flot.js"></script>
    <script src="template/vendor/flot/jquery.flot.resize.js"></script>

    <!-- Owl Carousel -->
    <script src="template/vendor/owl-carousel/js/owl.carousel.min.js"></script>

    <!-- Counter Up -->
    <script src="template/vendor/jqvmap/js/jquery.vmap.min.js"></script>
    <script src="template/vendor/jqvmap/js/jquery.vmap.usa.js"></script>
    <script src="template/vendor/jquery.counterup/jquery.counterup.min.js"></script>

    <script src="template/js/dashboard/dashboard-1.js"></script>

    <!-- Datatable -->
    <script src="template/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="template/js/plugins-init/datatables.init.js"></script>
    <script src="template/vendor/global/global.min.js"></script>
    <script src="template/js/quixnav-init.js"></script>
    <script src="template/js/custom.min.js"></script>
</body>
</html>