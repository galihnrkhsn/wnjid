<?php
    session_start();

    include 'koneksi.php';

    if (!isset($_SESSION["administrator"])) {
        echo "<script>
            alert('Anda harus login terlebih dahulu!');
            location='login.php';
        </script>";
        header('location:login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pusat | Wanoja</title>
    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include 'sidebar.php'?>

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="h3 font-weight-bold mb-0 text-gray-800">Input Catatan</h1>
            </div>

            <hr />

            <form method="post">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="tgl" class="mb-0">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" id="tgl" name="tgl" required>
                        </div>

                        <div class="form-group">
                            <label for="rekening" class="mb-0">Rekening <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" name="rekening" id="rekening" required>
                                <option>~ Default Selected ~</option>
                                <?php
                                    $query = $koneksi->query("SELECT * FROM rekeningwnj ORDER BY namabank");
                                    while ($data = $query->fetch_assoc()) {
                                ?>
                                    <option value="<?= $data['namabank'] ?>"><?= $data['namabank'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nominal" class="mb-0">Nominal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" id="nominal" name="nominal" required>
                        </div>

                        <div class="form-group">
                            <label for="keterangan" class="mb-0">Keterangan</label>
                            <textarea class="form-control form-control-sm" name="keterangan"></textarea>
                        </div>

                        <button class="btn btn-primary btn-sm" name="kirim">Kirim</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
        if (isset($_POST['kirim'])) {
            try {
                date_default_timezone_set('Asia/Jakarta');
                $waktu      = date('H:i:s');
                $tgl        = $_POST['tgl'];
                $rekening   = $_POST['rekening'];
                $nominal    = $_POST['nominal'];
                $keterangan = $_POST['keterangan'];
                
                $sql = $koneksi->query("INSERT INTO catatan VALUES (NULL, '$tgl', '$rekening', '$nominal', '$keterangan', '$waktu', NOW())");
                if ($sql) {
                    echo "<script>
                        alert('Data berhasil di kirim!');
                        location='catatan.php';
                    </script>";
                } else {
                    echo "<script>
                        alert('Data gagal di tambahkan!');
                        location='catatan.php';
                    </script>";
                }
            } catch(Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    ?>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script>

</body>
</html>