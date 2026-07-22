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
    $iduser             = $_SESSION['logistik']['id'];
    $sql_user           = $koneksi->query("SELECT * FROM user_manajemen WHERE id = '$iduser'");
    $user               = $sql_user->fetch_assoc();
    $username           = $user['username'];
    $id                 = $_GET['id'];
    $sql                = $koneksi->query("SELECT * FROM
                                                t_user
                                                    INNER JOIN
                                                logistik3 ON logistik3.idlogistik = t_user.idlogistik
                                            WHERE
                                                t_user.id_user = '$id'
                                            ORDER BY t_user.id_user DESC
                                        ");
    $data               = $sql->fetch_assoc();
    $nama_pengirim      = $data['nama'];
    $telepon_pengirim   = $data['teleponpengirim'];
    $nama_penerima      = $data['nama_penerima'];
    $telepon_penerima   = $data['teleponpenerima'];
    $alamat             = $data['alamat'];
    $keterangan         = $data['keterangan'];
    $ekspedisi          = $data['ekspedisi'];
    $jenis_mitra        = $data['jenis_mitra'];
    $namamitra          = $data['namamitra'];
    $jumlah_koli        = $data['jumlah_koli'];
    $tgl                = $data['tgl'];
    $namacs             = $data['namacs'];
    $no_sj              = $data['no_sj'];
    $kode_booking       = $data['kode_booking'];
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

            <?php include 'template/component/sidebar.php'?>


            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <?php if (isset($_GET['id'])) : ?>
                                    <h1 class="m-0">Formulir Ubah Resi Manual & SJ</h1>
                                <?php else : ?>
                                    <h1 class="m-0">Formulir Resi Manual & SJ</h1>
                                <?php endif; ?>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active">Resi Manual & SJ</li>
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
                                <?php if (isset($_GET['id'])) : ?>
                                    <h5 class="mb-0">Formulir Ubah Resi Manual</h5>
                                <?php else : ?>
                                    <h5 class="mb-0">Formulir Resi Manual</h5>
                                <?php endif; ?>
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
                                                <label for="nama-cs" class="mb-1">Nama CS <span class="text-danger">*</span></label>
                                                <select id="nama-cs" class="form-control form-control-sm" name="namacs" required>
                                                    <?php if (isset($_GET['id'])) : ?>
                                                        <option value="<?= $namacs ?>" selected><?= $namacs ?></option>
                                                    <?php else : ?>
                                                        <option selected>~ Default Selected ~</option>
                                                    <?php endif; ?>
                                                    <?php
                                                        $sql = $koneksi->query(query: "SELECT * FROM namacs ORDER BY namacs ASC");
                                                        while ($data = $sql->fetch_assoc()) {
                                                    ?>
                                                        <option value="<?= $data['namacs'] ?>"><?= $data['namacs'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="keterangan" class="mb-1">Keterangan</label>
                                                <textarea class="form-control form-control-sm" id="keterangan" name="keterangan" rows="4" placeholder="Keterangan"><?= $keterangan ?></textarea>
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
                                        </div>
                                        <div class="col-sm-12">
                                            <?php if (isset($_GET['id'])) : ?>
                                                <button type="submit" class="btn btn-sm btn-success" name="update">Ubah Data</button>
                                            <?php else : ?>
                                                <button type="submit" class="btn btn-sm btn-primary" name="kirim">Kirim</button>
                                            <?php endif; ?>
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
                    $today              = date('Y-m-d');
                    $waktu              = date('H:i:s');
                    $tanggal            = $_POST['tanggal'];
                    $namacs             = $_POST['namacs'];
                    $keterangan         = htmlspecialchars($_POST['keterangan']);
                    $jenis_mitra        = $_POST['jenis_mitra'];

                    $sql = $koneksi->query("INSERT INTO logistik3 VALUES
                                                (
                                                    NULL, '$tanggal', NULL,
                                                    NULL, '', '0',
                                                    NULL, '$keterangan', NULL,
                                                    NULL, NULL, NULL, NULL, '$namacs', NULL,
                                                    '$jenis_mitra'
                                                )
                                            ");
                    if ($sql) {
                        $check      = $koneksi->query("SELECT MAX(idlogistik) AS idlogistik FROM logistik3");
                        $data       = $check->fetch_assoc();
                        $idlogistik = $data['idlogistik'];
                        
                        $sql2 = $koneksi->query("INSERT INTO t_user VALUES
                                                    (
                                                        NULL, '$idlogistik', '$namacs',
                                                        NULL, NULL,
                                                        NULL, NULL,
                                                        NULL, '$keterangan', NULL, NULL,
                                                        NULL, '$tanggal', NOW(),
                                                        NULL, '0', NULL, NULL,
                                                        NULL, NULL, NULL, 'WNJ'
                                                    )
                                                ");
                        if ($sql2) {
                            echo "
                                <script>
                                    alert('Data berhasil dikirim!')
                                    location='invoice.php'
                                </script>
                            ";
                        } else {
                            echo "
                                <script>
                                    alert('Data gagal dikirim!')
                                    location='form_resi.php'
                                </script>
                            ";
                        }
                    } else {
                        echo "
                            <script>
                                alert('Data gagal dikirim!')
                                location='form_resi.php'
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
            } elseif (isset($_POST['update'])) {
                $nama_pengirim      = htmlspecialchars($_POST['pengirim']);
                $penerima           = htmlspecialchars($_POST['penerima']);
                $alamat             = htmlspecialchars($_POST['alamat']);
                $data_mitra         = explode(" | ", $mitra);
                $keterangan         = htmlspecialchars($_POST['keterangan']);
                $tanggal            = $_POST['tanggal'];
                $namacs             = $_POST['namacs'];
                $telepon_pengirim   = $_POST['telp_pengirim'];
                $telepon_penerima   = $_POST['telp_penerima'];
                $ekspedisi          = $_POST['ekspedisi'];
                $mitra              = $_POST['namamitra'];
                $jenis_mitra        = $_POST['jenis_mitra'];
                $no_sj              = $_POST['no_sj'];
                $jumlah_koli        = $_POST['jumlah_koli'];
                $idadmin            = $data_mitra[0];
                $namamitra          = $data_mitra[1];
                $query_check        = $koneksi->query("SELECT idlogistik FROM t_user WHERE id_user = '$id'");
                $data_logistik      = $query_check->fetch_assoc();
                $idlogistik         = $data_logistik['idlogistik'];

                $koneksi->begin_transaction();
                try {
                    $sql = "UPDATE t_user 
                                SET 
                                    created_date = '$tanggal',
                                    modified_date = NOW(),
                                    namacs = '$namacs',
                                    nama = '$nama_pengirim',
                                    teleponpengirim = '$telepon_pengirim',
                                    nama_penerima = '$nama_penerima',
                                    teleponpenerima = '$telepon_penerima',
                                    alamat = '$alamat',
                                    keterangan = '$keterangan',
                                    ekspedisi = '$ekspedisi',
                                    idadmin = '$idadmin',
                                    namamitra = '$namamitra',
                                    jenis_mitra = '$jenis_mitra'
                                WHERE
                                    id_user = '$id'
                            ";
                    $sql2 = "UPDATE logistik3
                                SET
                                    tgl = '$tanggal',
                                    penerima = '$nama_penerima',
                                    ekspedisi = '$ekspedisi',
                                    jumlah_koli = '$jumlah_koli',
                                    keterangan = '$keterangan',
                                    idadmin = '$idadmin',
                                    namacs = '$namacs',
                                    no_sj = '$no_sj',
                                    jenis_mitra = '$jenis_mitra'
                                WHERE
                                    idlogistik = '$idlogistik'
                            ";
                    if ($koneksi->query($sql) === TRUE && $koneksi->query($sql2) === TRUE) {
                        $koneksi->commit();
                        echo "
                            <script>
                                alert('Data berhasil diupdate!');
                                location='invoice.php';
                            </script>
                        ";
                    } else {
                        // throw new Exception("Gagal mengupdate data: " . $koneksi->error);
                        echo "
                            <script>
                                alert('Data gagal diupdate!');
                                location='form_resi.php?id=$id';
                            </script>
                        ";
                    }
                } catch (Exception $er) {
                    $koneksi->rollback();
                    echo "
                        <script>
                            alert('500 Internal Server Error!');
                            location='form_resi.php?id=$id';
                        </script>
                    ";
                }
                $koneksi->close();
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
