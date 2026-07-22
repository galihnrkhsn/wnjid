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

    $iduser = $_SESSION['logistik']['id'];
    $sql_user = $koneksi->query("SELECT * FROM user_manajemen WHERE id = '$iduser'");
    $user = $sql_user->fetch_assoc();
    $username = $user['username'];

    $tgl = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Portal | Formulir <?php if (isset($_GET['id'])) : ?>Ubah<?php endif; ?> Resi</title>
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
                                <h1 class="m-0">Formulir Pengiriman</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active">Resi & SJ</li>
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
                                <h5 class="mb-0">Formulir Pengiriman</h5>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label for="date" class="mb-1">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" id="date" class="form-control form-control-sm" name="tanggal" value="<?= $tgl ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="penerima" class="mb-1">Nama Penerima <span class="text-danger">*</span></label>
                                                <input type="text" id="penerima" class="form-control form-control-sm" name="penerima" placeholder="Nama Penerima" value="<?= $nama_penerima ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="ekspedisi" class="mb-1">Ekspedisi <span class="text-danger">*</span></label>
                                                <select id="ekspedisi" class="form-control form-control-sm" name="ekspedisi" required>
                                                    <?php if (isset($_GET['id'])) : ?>
                                                        <option value="<?= $ekspedisi ?>" selected><?= $ekspedisi ?></option>
                                                    <?php else : ?>
                                                        <option selected>~ Default Selected ~</option>
                                                    <?php endif; ?>
                                                    <optgroup label="JNE">
                                                        <option value="JNE Reg">JNE Reg</option>
                                                        <option value="JNE YES">JNE YES</option>
                                                        <option value="JNE Oke">JNE Oke</option>
                                                        <option value="JNE CTC">JNE CTC</option>
                                                        <option value="JTR">JNE Trucking (JTR)</option>
                                                    <optgroup label="J&T">
                                                        <option value="J&T">J&T</option>
                                                        <option value="J&T Cargo">J&T Cargo</option>
                                                    <optgroup label="WAHANA">
                                                        <option value="Wahana Ekspres">Wahana Ekspres</option>
                                                        <option value="Wahana Kargo">Wahana Kargo</option>
                                                    <optgroup label="SICEPAT">
                                                        <option value="Sicepat BEST">Sicepat BEST</option>
                                                        <option value="Sicepat REG">Sicepat Reg</option>
                                                        <option value="Sicepat Kargo">Sicepat Cargo</option>
                                                    <optgroup label="POS">
                                                        <option value="Pos Ekonomi Jumbo">Pos Ekonomi Jumbo</option>
                                                        <option value="Pos Kilat">Pos Kilat</option>
                                                    <optgroup label="LAINNYA">
                                                        <option value="Paxel">Paxel</option>
                                                        <option value="SPX Express">SPX Express</option>
                                                        <option value="IDE">ID Express</option>
                                                        <option value="Ahsan">Ahsan</option>
                                                        <option value="Baraka">Baraka</option>
                                                        <option value="Pegasus">Pegasus</option>
                                                        <option value="Sentral">Sentral</option>
                                                        <option value="Lion Parcel">Lion Parcel</option>
                                                        <option value="Dakota">Dakota</option>
                                                        <option value="Indah Cargo">IndahCargo</option>
                                                        <option value="Adam Cargo">Adam Cargo</option>
                                                        <option value="Gosend">GoSend</option>
                                                        <option value="Kalog">Kalog</option>
                                                        <option value="CMC KARGO">CMC CARGO</option>
                                                        <option value="Tiki">Tiki</option>
                                                        <option value="Triplogic">Triplogic</option>
                                                        <option value="Anteraja">Anteraja</option>
                                                        <option value="Ambil ke Pusat">Ambil Ke Pusat</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="nama-cs" class="mb-1">Nama CS <span class="text-danger">*</span></label>
                                                <select id="nama-cs" class="form-control form-control-sm" name="namacs" required>
                                                    <?php if (isset($_GET['id'])) : ?>
                                                        <option value="<?= $namacs ?>" selected><?= $namacs ?></option>
                                                    <?php else : ?>
                                                        <option selected>~ Default Selected ~</option>
                                                    <?php endif; ?>
                                                    <?php
                                                        $sql = $koneksi->query("SELECT * FROM namacs ORDER BY namacs ASC");
                                                        while ($data = $sql->fetch_assoc()) {
                                                    ?>
                                                        <option value="<?= $data['namacs'] ?>"><?= $data['namacs'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="jenis_mitra" class="mb-1">Jenis Mitra <span class="text-danger">*</span></label>
                                                <select id="jenis_mitra" name="jenis_mitra" class="form-control form-control-sm">
                                                    <?php if (isset($_GET['id'])) : ?>
                                                        <option value="<?= $jenis_mitra ?>" selected><?= $jenis_mitra ?></option>
                                                    <?php else : ?>
                                                        <option selected>~ Default Selected ~</option>
                                                    <?php endif; ?>
                                                    <option value="WNJ">WNJ</option>
                                                    <option value="Zizazu">Zizazu</option>
                                                </select>
                                            </div>
                                            <div class="form-group" id="tabel_jenis" name="tabel_jenis"></div>
                                            <div class="form-group">
                                                <label for="no-sj" class="mb-1">No Surat Jalan</label>
                                                <input type="text" class="form-control form-control-sm" id="no-sj" name="no_sj" placeholder="Nomor Surat Jalan" value="<?= $no_sj ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="keterangan" class="mb-1">Keterangan</label>
                                                <textarea class="form-control form-control-sm" id="keterangan" name="keterangan" rows="4" placeholder="Keterangan"><?= $keterangan ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-sm btn-primary" name="kirim">Kirim</button>
                                        </div>
                                    </div>
                                </form>
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

        <?php
            if (isset($_POST['kirim'])) {
                try {
                    // echo "<pre>";
                    //     print_r($_POST);
                    // echo "</pre>";
                    
                    date_default_timezone_set('Asia/Jakarta');
                    $today = date('Y-m-d');
                    $waktu = date('H:i:s');

                    $tanggal = $_POST['tanggal'];
                    $namacs = $_POST['namacs'];
                    $penerima = htmlspecialchars($_POST['penerima']);
                    $ekspedisi = $_POST['ekspedisi'];
                    $jenis_mitra = $_POST['jenis_mitra'];
                    $no_sj = $_POST['no_sj'];
                    $keterangan = htmlspecialchars($_POST['keterangan']);
                    
                    $sql = $koneksi->query("INSERT INTO logistik3 VALUES
                                                (
                                                    NULL, '$tanggal', '$penerima',
                                                    '$ekspedisi', '', '0',
                                                    '0', '$keterangan', NULL,
                                                    NULL, '$namacs', '$no_sj',
                                                    '$jenis_mitra'
                                                )
                                            ");
                    if ($sql) {
                        echo "
                            <script>
                                alert('Data berhasil dikirim!')
                                location='detail_resi.php?tgl=$today'
                            </script>
                        ";
                    } else {
                        echo "
                            <script>
                                alert('Data gagal dikirim!')
                                location='form_pengiriman.php'
                            </script>
                        ";
                    }
                } catch(Exception $e) {
                    // echo "Error: " . $e->getMessage();
                    echo "
                        <script>
                            alert('500 Internal Server Error!')
                            location='form_resi.php'
                        </script>
                    ";
                }
            }
        ?>

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

        <script type="text/javascript">
            $(document).ready(function() {
                $('#jenis_mitra').change(function() {
                    var jenis_mitra = $('#jenis_mitra').val();
                    $.ajax({
                        type : 'GET',
                        url : 'jenis.php',
                        data :  'jenis_mitra=' + jenis_mitra,
                            success: function (data) {
                            $("#tabel_jenis").html(data);
                        }
                    });
                });

                $('#jenis_mitra').ready(function() {
                    var jenis_mitra = $('#jenis_mitra').val();            
                    $.ajax({
                        type : 'GET',
                        url : 'jenis.php',
                        data :  'jenis_mitra=' + jenis_mitra,
                            success: function (data) {
                            $("#tabel_jenis").html(data);
                        }
                    });
                });
            });
        </script>
    </body>
</html>
