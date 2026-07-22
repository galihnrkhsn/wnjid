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
    $sql_user       = $koneksi->query("SELECT * FROM user_manajemen WHERE id = '$iduser'");
    $user           = $sql_user->fetch_assoc();
    $username       = $user['username'];
    $tgl            = $_GET['tgl'];
    $ekspedisi      = $_GET['eks'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Portal | Detail Resi</title>
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
                                <h1 class="m-0">Detail Resi Per Ekspedisi <p class="d-inline text-capitalize"><?= $ekspedisi ?></p></h1>
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
                                <a href="excel_resi.php?eks=<?= $ekspedisi ?>&tgl=<?= $tgl ?>" target="blank" class="btn btn-success btn-xs">Print Semua Data</a>
                                <a href="surat_jalan_eks.php?eks=<?= $ekspedisi ?>&tgl=<?= $tgl ?>" target="blank" class="btn btn-primary btn-xs">Surat Jalan Ekspedisi</a>
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
                                                <th>#</th>
                                                <th>Tanggal</th>
                                                <th>Nama CS</th>
                                                <th>Penerima</th>
                                                <th>Ekspedisi</th>
                                                <th>No Resi</th>
                                                <th>Biaya Kirim</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $sql    = $koneksi->query("SELECT * FROM logistik3 WHERE tgl = '$tgl' AND ekspedisi LIKE '%$ekspedisi%' ORDER BY idlogistik");
                                                $no     = 1;
                                                while ($data = $sql->fetch_assoc()) {
                                                    if ($data['ekspedisi'] == 'Wahana' || $data['ekspedisi'] == 'Wahana Ekspres') {
                                                        $data['ekspedisi'] = 'Wahana';
                                                    } else {
                                                        $data['ekspedisi'];
                                                    }
                                            ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="check-item" name="checked_ids[]" value="<?= $data['idlogistik'] ?>">
                                                    </td>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $data['tgl'] ?></td>
                                                    <td><?= $data['namacs'] ?></td>
                                                    <td><?= $data['penerima'] ?></td>
                                                    <td>
                                                        <a href="" class="text-capitalize"><?= $data['ekspedisi'] ?></a>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" name="noresi[<?= $data['idlogistik'] ?>]" value="<?= $data['noresi'] ?>" placeholder="Masukkan No Resi">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control form-control-sm" name="biayakirim[<?= $data['idlogistik'] ?>]" value="<?= $data['biayakirim'] ?>" placeholder="Masukkan Biaya Kirim">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary mt-2" name="update_resi">Update Data</button>
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
