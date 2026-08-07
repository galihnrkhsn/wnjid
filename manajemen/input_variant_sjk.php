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

    $ambil_cash=$koneksi->query("SELECT (bca+bni+bri+bsi+mandiri+muamalat) as total, waktu FROM rekeningbank ORDER BY id DESC LIMIT 1"); 
    $tampil_cash=$ambil_cash->fetch_assoc();

    $ambil_aset=$koneksi->query("SELECT sum(nilai) as total FROM aset"); 
    $tampil_aset=$ambil_aset->fetch_assoc();

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

        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
            background-color: #f8f9fa;
            border-radius: 4px;
        }

        .custom-file-input {
            display: none;
        }

        .custom-file-upload:hover {
            background-color: #e2e6ea;
        }

        .custom-file-upload:active {
            background-color: #dae0e5;
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
                            <h4 class="text-uppercase">Tambah Variant</h4>
                            <p class="mb-0"><?= $namapo ?></p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="home.php">...</a></li>
                            <li class="breadcrumb-item"><a href="info_sjk.php?id=<?= $idpoproduk ?>&sjk=<?= $sjk ?>"><?= $sjk ?></a></li>
                            <li class="breadcrumb-item active">Tambah Variant</li>
                        </ol>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h6 class="text-uppercase text-muted">sjk <?= $sjk ?></h6>
                                        </div>

                                        <div class="col-sm-12">
                                            <input type="hidden" name="sjk" value="<?= $sjk ?>" class="form-control form-control-sm">
                                        </div>

                                        <?php
                                            $sql_produk = $koneksi->query("SELECT 
                                                                                *
                                                                            FROM
                                                                                poproduk
                                                                                    JOIN
                                                                                pokategori
                                                                                    JOIN
                                                                                podetail ON poproduk.idpoproduk = pokategori.idpoproduk
                                                                                    AND pokategori.idpo = podetail.idpo
                                                                            WHERE
                                                                                poproduk.idpoproduk = '$idpoproduk'
                                                                            ORDER BY podetail.idpodetail ASC
                                                                        ");
                                            while ($produk = $sql_produk->fetch_assoc()) {
                                        ?>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="<?= $produk['variant'] ?>" class="mb-1"><?= $produk['variant'] ?></label>
                                                    <input type="hidden" class="form-control form-control-sm" min="0" name="idpodetail[]" value="<?= $produk['idpodetail'] ?>" id="<?= $produk['variant'] ?>" required>
                                                    <input type="number" class="form-control form-control-sm" min="0" name="qty[]" value="0" id="<?= $produk['variant'] ?>" required>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <button class="btn btn-primary btn-sm" type="submit" name="kirim">Kirim</button>
                                </div>
                            </div>
                        </form>
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

    <?php
        if (isset($_POST['kirim'])) {
            try {
                date_default_timezone_set('Asia/Jakarta');
                $today = date('Y-m-d');
                $waktu = date('H:i:s');

                $idpodetail = $_POST['idpodetail'];
                $jumlah = $_POST['qty'];
                $jumlah_dipilih = count($jumlah);

                for ($x = 0; $x < $jumlah_dipilih; $x++) {
                    if ($jumlah[$x] > 0) {
                        $sql_podetail = $koneksi->query("SELECT idpodetail, jumlah, brggb FROM sjk WHERE idpodetail = '$idpodetail[$x]' AND sjk = '$sjk'");
                        $podetail = $sql_podetail->fetch_assoc();
                        $jumlah_awal = $podetail['jumlah'];
                        $jumlah_akhir = $jumlah_awal + $jumlah[$x];
                        $brggb = $podetail['brggb'];
                        $brgga = $jumlah_akhir - $brggb;

                        if ($sql_podetail->num_rows > 0) {
                            $sql = $koneksi->query("UPDATE sjk SET jumlah = '$jumlah_akhir', brgga = '$brgga', waktu_update = NOW() WHERE idpodetail = '$idpodetail[$x]' AND sjk = '$sjk'");
                            if ($sql) {
                                echo "
                                    <script>
                                        alert('SJK berhasil ditambahkan data baru!')
                                        location='info_sjk.php?id=$idpoproduk&sjk=$sjk'
                                    </script>
                                ";
                            }
                        } else {
                            $sql_dexist = $koneksi->query("SELECT MAX(no_sjk) AS no_sjk, MAX(sjk) AS sjk, sjk.* FROM sjk WHERE sjk = '$sjk'");
                            $data_dexist = $sql_dexist->fetch_assoc();
                            $image = $data_dexist['image'];
                            $no_sjk = $data_dexist['no_sjk'];
                            $vendor = $data_dexist['vendor'];

                            $sql = $koneksi->query("INSERT INTO sjk VALUES (NULL, '$no_sjk', '$sjk', '$today', '$vendor', '$idpoproduk', '$idpodetail[$x]', '$jumlah[$x]', '$jumlah[$x]', '0', NOW(), NOW(), '$image')");

                            if ($sql) {
                                echo "
                                    <script>
                                        alert('SJK berhasil ditambahkan data baru!')
                                        location='info_sjk.php?id=$idpoproduk&sjk=$sjk'
                                    </script>
                                ";
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                // echo "Error: " . $e->getMessage();
                echo "
                    <script>
                        alert('SJV gagal ditambahkan!')
                        location='home.php'
                    </script>
                ";
            }
        }
    ?>
</body>
</html>