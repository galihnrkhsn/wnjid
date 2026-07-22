<?php 
    session_start();
    include 'koneksi.php'; 

    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $tanggal        = $_GET['tgl'];
    $statusList     = ['COD', 'Bayar', 'Deposit', 'Tanggung Pusat', 'Belum Bayar'];
    $results        = [];

    foreach ($statusList as $status) {
        if ($status == 'Belum Bayar') {
            $query = $koneksi->query("SELECT SUM(biayakirim) AS total
                                        FROM logistik3
                                        WHERE status_pengiriman IS NULL
                                        AND DATE(tgl) = '$tanggal'
                                    ");
        } else {
            $query = $koneksi->query("SELECT SUM(biayakirim) AS total 
                                        FROM logistik3 
                                        WHERE status_pengiriman = '$status' 
                                        AND DATE(tgl) = '$tanggal'");
        }
        $data = $query->fetch_assoc();
        $results[$status] = $data['total'] ?? 0;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Data Ongkir | Wanoja</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-metro.css">
    
    <style>
        .aws {
            border:8px solid #eff4ff;;
            
            padding: 10px;
            
        }
        .aws text {
            color: white;
            font-size: x-large;
            text-align: right;
        }
        .aws p {
            color: white;
            text-align: left;
            font-size: ;
        
        }
        .aws button {
            text-align: left;
        }
        .aws a {
            text-align: left;
        }
    </style>
</head>
<body id="page-top" class="sidebar-toggled">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php 
            if ($_SESSION["administrator"]) {
                include "sidebar.php";
            }
        ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Ongkir Tanggal : <?= $_GET['tgl']; ?></h1>
                    </div>
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <?php foreach ($statusList as $status): ?>
                                    <th><?= $status ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php foreach ($statusList as $status): ?>
                                    <td>Rp <?= number_format($results[$status], 0, ',', '.') ?></td>
                                <?php endforeach; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->
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
    <?php include "settingdatatables.php"; ?>
</body>
</html>
