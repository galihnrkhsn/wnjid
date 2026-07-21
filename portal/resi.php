<?php
    session_start();
    
    include 'koneksi.php';
    if (!isset($_SESSION['logistik'])) {
        echo "
            <script>
                alert('Login terlebih dahulu')
                location='login.php'
            </script>
        ";
        exit();
    }

    $tgl    = $_GET['tgl'];
    $jenis  = $_GET['jenis'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Portal | Invoice</title>
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="template/plugins/fontawesome-free/css/all.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="template/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="template/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- JQVMap -->
        <link rel="stylesheet" href="template/plugins/jqvmap/jqvmap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="template/dist/css/adminlte.min.css">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="template/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="template/plugins/daterangepicker/daterangepicker.css">
        <!-- summernote -->
        <link rel="stylesheet" href="template/plugins/summernote/summernote-bs4.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include 'template/component/navbar.php'?>

            <?php include 'template/component/sidebar.php' ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Data Resi</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active">Resi</li>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Export Data Resi</h6>
                            </div>
                            <div class="card-body">
                                <form method="post" class="row">
                                    <div class="col-sm-3">
                                        <label for="from" class="mb-0">Tanggal Awal</label>
                                        <input type="date" class="form-control form-control-sm" id="from" name="from" required>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="to" class="mb-0">Tanggal Akhir</label>
                                        <input type="date" class="form-control form-control-sm" id="to" name="to" required>
                                    </div>
                                    <div class="col-sm-12 mt-2">
                                        <button type="submit" class="btn btn-success btn-sm" name="export">Export</button>
                                    </div>
                                </form>
                                <?php
                                    if (isset($_POST['export'])) {
                                        try {
                                            $from = $_POST['from'];
                                            $to = $_POST['to'];
                                            
                                            echo "
                                                <script>
                                                    location='export.php?f=$from&t=$to'
                                                </script>
                                            ";
                                        } catch (Exception $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                    }
                                ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-8">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h6 class="card-title">Data Resi</h6>
                                            <a href="form_resi.php" class="btn btn-primary btn-sm">Tambah</a>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="example2" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th width="20">#</th>
                                                    <th>Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $tahun = date('Y');
                                                    // $sql = $koneksi->query("SELECT DISTINCT tgl FROM logistik3 WHERE YEAR(tgl) = '$tahun' ORDER BY tgl DESC");
                                                    $sql = $koneksi->query("SELECT DISTINCT tgl FROM logistik3 WHERE YEAR(tgl) > '2024' ORDER BY tgl DESC");
                                                    $no = 1;
                                                    while ($query = $sql->fetch_assoc()) {
                                                ?>
                                                    <tr>
                                                        <td><?= $no++ ?></td>
                                                        <td><a href="detail_resi.php?tgl=<?= $query['tgl'] ?>"><?= $query['tgl'] ?></a></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h6 class="card-title">Ekspedisi</h6>
                                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#ekspedisi">
                                                Tambah
                                            </button>
                                            <div class="modal fade" id="ekspedisi">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title">Form Tambah Ekspedisi</h6>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form method="post" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <div class="form-group mb-2">
                                                                    <label for="ekspedisi" class="mb-0">Ekspedisi <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control form-control-sm" name="ekspedisi" placeholder="Ekspedisi" required>
                                                                </div>
                                                                <div class="form-group mb-0">
                                                                    <label for="ekspedisi" class="mb-0 d-block">Image</label>
                                                                    <input type="file" name="file">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-start">
                                                                <button type="submit" class="btn btn-primary btn-sm" name="tambah_ekspedisi">Tambah Data</button>
                                                            </div>
                                                        </form>
                                                        <?php
                                                            if (isset($_POST['tambah_ekspedisi'])) {
                                                                try {
                                                                    date_default_timezone_set('Asia/Jakarta');
                                                                    $w = date('Hid');
                                                                    $t = date('Ymd');
                                                                    $ekspedisi = $_POST['ekspedisi'];
                                                                    $image = $_FILES['file']['tmp_name'];                   
                                                                    $tmp = $_FILES['file']['tmp_name'];
                                                                    $size = $_FILES['file']['size'];
                                                                } catch (Exception $e) {
                                                                    $_SESSION['message'] = $e->getMessage();
                                                                    header("Location: index.php");
                                                                    exit();
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                            <!-- /.modal -->
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="example3" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Ekspedisi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $sql = $koneksi->query("SELECT * FROM ekspedisi ORDER BY name DESC");
                                                    while ($ekspedisi = $sql->fetch_assoc()) {
                                                ?>
                                                    <tr>
                                                        <td><a href="resi_ekspedisi.php?ekspedisi=<?= $ekspedisi['name'] ?>" class="text-capitalize"><?= $ekspedisi['name'] ?></a></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                    </div>
                </section>
                <!-- /.content -->
            </div>

            <?php include 'template/component/footer.php' ?>
        </div>
        <!-- ./wrapper -->

        <!-- jQuery -->
        <script src="template/plugins/jquery/jquery.min.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="template/plugins/jquery-ui/jquery-ui.min.js"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- ChartJS -->
        <script src="template/plugins/chart.js/Chart.min.js"></script>
        <!-- Sparkline -->
        <script src="template/plugins/sparklines/sparkline.js"></script>
        <!-- JQVMap -->
        <script src="template/plugins/jqvmap/jquery.vmap.min.js"></script>
        <script src="template/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
        <!-- jQuery Knob Chart -->
        <script src="template/plugins/jquery-knob/jquery.knob.min.js"></script>
        <!-- daterangepicker -->
        <script src="template/plugins/moment/moment.min.js"></script>
        <script src="template/plugins/daterangepicker/daterangepicker.js"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="template/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
        <!-- Summernote -->
        <script src="template/plugins/summernote/summernote-bs4.min.js"></script>
        <!-- overlayScrollbars -->
        <script src="template/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <!-- AdminLTE App -->
        <script src="template/dist/js/adminlte.js"></script>
        <!-- DataTables  & Plugins -->
        <script src="template/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="template/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="template/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="template/plugins/jszip/jszip.min.js"></script>
        <script src="template/plugins/pdfmake/pdfmake.min.js"></script>
        <script src="template/plugins/pdfmake/vfs_fonts.js"></script>
        <script src="template/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="template/plugins/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="template/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
        <script>
            $(function () {
                $("#example1").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": true,
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                $('#example2').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "searching": false,
                    "ordering": true,
                    "info": true,
                    "autoWidth": true,
                    "responsive": true,
                });
                $('#example3').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "searching": false,
                    "ordering": true,
                    "info": true,
                    "autoWidth": true,
                    "responsive": true,
                });
            });
        </script>
    </body>
</html>
