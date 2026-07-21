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
                                <h1 class="m-0">Invoice</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active">Invoice</li>
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
                                <a href="form_resi.php" class="btn btn-primary btn-sm">Tambah Data</a>
                                <a href="form_resi2.php" class="btn btn-success btn-sm">Resi Manual</a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama CS</th>
                                            <th>ID mitra</th>
                                            <th>Nama Pengirim (Telp)</th>
                                            <th>Nama Penerima (Telp)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sql    = $koneksi->query("SELECT * FROM `t_user` ORDER BY id_user DESC LIMIT 2000");
                                            $no     = 1;
                                            while($data = $sql->fetch_assoc()) {
                                                $createdTimestamp = strtotime($data['created_date']);
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $data['namacs'] ?></td>
                                                <td><?= $data['idadmin'] ?></td>
                                                <td><?= $data['nama'] ?> (<?= $data['teleponpengirim'] ?>)</td>
                                                <td><?= $data['nama_penerima'] ?> (<?= $data['teleponpenerima'] ?>)</td>
                                                <td>
                                                    <div class="d-flex flex-row align-items-center">
                                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-default-<?= $data['id_user'] ?>"><i class="fas fa-eye"></i></button>
                                                        <div class="modal fade" id="modal-default-<?= $data['id_user'] ?>">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="mb-0">Penerima: <?= $data['nama_penerima'] ?></h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-sm-3">Alamat</div>
                                                                            <div class="col-sm-1">:</div>
                                                                            <div class="col-sm-8">
                                                                                <p class="mb-0 text-capitalize"><?= $data['alamat'] ?></p>
                                                                            </div>
                                                                            
                                                                            <div class="col-sm-3">Ekspedisi</div>
                                                                            <div class="col-sm-1">:</div>
                                                                            <div class="col-sm-8">
                                                                                <p class="mb-0 text-uppercase"><?= $data['ekspedisi'] ?></p>
                                                                            </div>

                                                                            <div class="col-sm-3">Keterangan</div>
                                                                            <div class="col-sm-1">:</div>
                                                                            <div class="col-sm-8">
                                                                                <p class="mb-0 text-capitalize"><?= $data['keterangan'] ?></p>
                                                                            </div>
                                                                            
                                                                            <div class="col-sm-3">Tanggal / Waktu</div>
                                                                            <div class="col-sm-1">:</div>
                                                                            <div class="col-sm-8">
                                                                                <p class="mb-0"><?= $data['modified_date'] ?></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer d-flex align-items-center justify-content-start">
                                                                    </div>
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </div>
                                                            <!-- /.modal-dialog -->
                                                        </div>
                                                        <!-- /.modal -->
                                                        <a href="delete.php?id=<?= $data['id_user'] ?>" class="btn mx-2 btn-xs btn-danger"><i class="fas fa-trash"></i></a>
                                                        <a href="form_resi.php?id=<?= $data['id_user'] ?>" data-created="<?= $createdTimestamp ?>" class="btn btn-xs btn-warning text-white"><i class="fas fa-pen-square"></i></a>
                                                        <?php if ($data['jenis_mitra'] == 'WNJ' ) : ?>
                                                            <?php if (!isset($data['nama'])) :?>
                                                        <a href="print2.php?id=<?= $data['id_user'] ?>" class="btn btn-xs mx-2 btn-secondary" target="_blank" data-created="<?= $createdTimestamp ?>" id="btnWNJ-<?= $data['id_user'] ?>">WNJ</a>
                                                        <?php else :?>
                                                            <a href="print.php?id=<?= $data['id_user'] ?>" class="btn btn-xs mx-2 btn-secondary" target="_blank" data-created="<?= $createdTimestamp ?>" id="btnWNJ-<?= $data['id_user'] ?>">WNJ</a>
                                                        <?php endif; ?>
                                                        <?php else : ?>
                                                            <a href="print.php?id=<?= $data['id_user'] ?>" class="ml-2 btn btn-xs btn-success" target="_blank" data-created="<?= $createdTimestamp ?>" id="btnZizazu-<?= $data['id_user'] ?>">Zizazu</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <script>
                                            // Mendapatkan semua tombol yang memiliki atribut data-created
                                            const buttons = document.querySelectorAll('[data-created]');

                                            // Fungsi untuk menghapus tombol jika sudah lebih dari 24 jam
                                            buttons.forEach(button => {
                                                const createdTime = parseInt(button.getAttribute("data-created"), 10);
                                                const currentTime = Math.floor(Date.now() / 1000); // Waktu saat ini dalam detik
                                                const timeDifference = currentTime - createdTime;
                                                const hoursDifference = timeDifference / 3600;

                                                // Jika lebih dari 24 jam, sembunyikan tombol
                                                if (hoursDifference > 24) {
                                                    button.style.display = "none"; // Menghilangkan tombol dari tampilan
                                                }
                                            });
                                        </script>
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
                    "autoWidth": false,
                    "responsive": true,
                });
            });
        </script>
    </body>
</html>
