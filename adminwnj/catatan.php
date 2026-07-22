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
                <h1 class="h3 font-weight-bold mb-0 text-gray-800">Catatan</h1>
            </div>

            <hr />
            
            <a href="input_catatan.php" class="btn btn-primary btn-sm mb-2">Tambah Catatan</a>
            <h6 style="font-weight: 600;">Catatan Per Tanggal</h6>
            <?php
                $query = $koneksi->query("SELECT * FROM catatan GROUP BY created_at ORDER BY created_at DESC");
                while ($data = $query->fetch_assoc()) {
            ?>
                <p class="mb-0"><a href="detail_catatan.php?tanggal=<?= $data['created_at'] ?>"><?= $data['created_at'] ?></a></p>
            <?php } ?>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

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