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

    $namalengkap    = $data['namalengkap'];
    $iduser         = $data["id"];
    $tipe           = $data['tipe'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WNJ.ID | Ready Stok</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="template/images/logo.png">
    <link rel="stylesheet" href="../vendor/manajemen-template/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../vendor/manajemen-template/owl-carousel/css/owl.theme.default.min.css">
    <link href="../vendor/manajemen-template/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="template/css/style.css" rel="stylesheet">
    <link href="../vendor/manajemen-template/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

        <!-- Begin Page Content -->
        <div class="content-body">
            <div class="container-fluid mt-5">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h2 class="m-0 font-weight-bold text-secondary">
                        Ready Stok
                        <a href="index.php" class="btn btn-secondary btn-sm ml-3">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </h2>
                </div> 

                <!-- Content Row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="filter">Filter</label>
                                    <select class="form-control" name="filter" id="filter">
                                        <option value="Harian">Harian</option>
                                        <option value="Bulanan">Bulanan</option>
                                    </select>  
                                </div>
                                <div id="tfilter" name="tfilter"></div> 
                            </div>
                        </div>
                    </div>
                </div><!-- End Content Row -->
            </div><!-- End Page Content -->
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
    <script type="text/javascript">
        $(document).ready(function() {
            function fetchFilterData() {
                var filter = $('#filter').val();
                
                $.ajax({
                    type: 'GET',
                    url: 'cek_filter.php',
                    data: { filter: filter },
                    success: function(data) {
                        $("#tfilter").html(data);
                    }
                });
            }

            // Event ketika pilihan diubah
            $('#filter').change(fetchFilterData);
            
            // Panggil fungsi saat halaman dimuat
            $('#filter').trigger('change');
        });
    </script>

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