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

    $iduser         = $_SESSION['logistik']['id'];
    $sql_user       = $portal->query("SELECT * FROM user WHERE id = '$iduser'");
    $user           = $sql_user->fetch_assoc();
    $username       = $user['username'];
    $tgl            = $_GET['tgl'];
    $ekspedisi      = $_GET['eks'];
    $tanggal        = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Portal | Surat Jalan Ekspedisi</title>
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

            <?php if (isset($ekspedisi)) : ?>
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0">Surat Jalan Ekspedisi <p class="d-inline text-capitalize"><?= $ekspedisi ?></p></h1>
                                </div><!-- /.col -->
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="resi.php">Resi</a></li>
                                        <li class="breadcrumb-item active"><?= $tgl ?></li>
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
                                    <a href="surat_jalan_eks.php" class="btn btn-warning btn-xs"><i class="fa fa-chevron-left"></i> Kembali</a>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <form id="form-checklist" method="post" action="backend/ekspedisi.php">
                                        <input type="hidden" value="<?= $tgl ?>" name="tgl">
                                        <input type="hidden" value="<?= $ekspedisi ?>" name="eks">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="check-all"></th>
                                                    <th>Tanggal</th>
                                                    <th>Penerima</th>
                                                    <th>Nama CS</th>
                                                    <th>Ekspedisi</th>
                                                    <th>No Resi</th>
                                                    <th>Biaya Kirim</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $sql    = $koneksi->query("SELECT * FROM logistik3 WHERE tgl = '$tgl' AND ekspedisi LIKE '%$ekspedisi%' ORDER BY idlogistik");
                                                    while ($data = $sql->fetch_assoc()) {
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="check-item" name="checked_ids[]" value="<?= $data['idlogistik'] ?>">
                                                        </td>
                                                        <td><?= $data['tgl'] ?></td>
                                                        <td><?= $data['penerima'] ?></td>
                                                        <td><?= $data['namacs'] ?></td>
                                                        <td>
                                                            <a href="" class="text-capitalize"><?= $data['ekspedisi'] ?></a>
                                                        </td>
                                                        <td>
                                                            <?= $data['noresi'] ?>
                                                        </td>
                                                        <td>
                                                            <?= $data['biayakirim'] ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($data['status'] == '' || $data['status'] == NULL) :?>
                                                                <p class="text-red">Belum Terkirim</p>
                                                            <?php else : ?>
                                                                <p class="text-success"><?= $data['status']; ?></p>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <button type="submit" class="btn btn-primary mt-2" name="terkirim">Terkirim</button>
                                        <button type="submit" class="btn btn-danger mt-2" name="belum_terkirim">Belum Terkirim</button>
                                    </form>

                                    <script>
                                        // Handle "check all" functionality
                                        document.getElementById('check-all').addEventListener('change', function () {
                                            const checkItems = document.querySelectorAll('.check-item');
                                            checkItems.forEach(item => {
                                                item.checked = this.checked;
                                            });
                                        });

                                        // Ensure "check all" updates if individual checkboxes are changed
                                        const checkItems = document.querySelectorAll('.check-item');
                                        checkItems.forEach(item => {
                                            item.addEventListener('change', function () {
                                                const allChecked = Array.from(checkItems).every(checkbox => checkbox.checked);
                                                document.getElementById('check-all').checked = allChecked;
                                            });
                                        });
                                    </script>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </section>
                    <!-- /.content -->
                </div>
            <?php else : ?>
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0">Surat Jalan Ekspedisi</h1>
                                </div><!-- /.col -->
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="resi.php">Resi</a></li>
                                        <li class="breadcrumb-item active"><?= $tgl ?></li>
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
                                    <a href="#" class="btn btn-success btn-xs"><i class="fa fa-file-excel"></i> Print</a>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Ekspedisi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $sql    = $koneksi->query("SELECT DISTINCT ekspedisi FROM logistik3 
                                                                            WHERE ekspedisi IS NOT NULL 
                                                                            AND ekspedisi <> '' 
                                                                            AND ekspedisi <> '-' 
                                                                            AND tgl = '$tanggal';");
                                                while ($data = $sql->fetch_assoc()) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <a href="surat_jalan_eks.php?eks=<?= $data['ekspedisi'] ?>&tgl=<?= $tanggal ?>"><?= $data['ekspedisi']; ?></a>
                                                    
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </section>
                    <!-- /.content -->
                </div>
            <?php endif; ?>
            <!-- Content Wrapper. Contains page content -->

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
                    "responsive": true, "lengthChange": true, "autoWidth": true,
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                $('#example2').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": false,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                });
            });
        </script>
    </body>
</html>
