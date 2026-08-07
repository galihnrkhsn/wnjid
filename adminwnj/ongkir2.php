<?php 
    session_start();

    include 'koneksi.php'; 


    if(!isset($_SESSION["administrator"])){
    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login.php';</script>";
    header('location:login.php');
    exit();
    }

    // if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
    //     echo "<script>alert('anda harus login terlebih dahulu');</script>";
    //     echo "<script>location='login2.php';</script>";
    //     header('Location: login2.php');
    //     exit;
    // }
    // $idUser = $_SESSION['user_id'];
    // $userLevel = $_SESSION['user_level'];
    // if ($userLevel == 'admin' && isset($_SESSION['administrator'])) {
    //     $idAdmin = $_SESSION['administrator'];
    // } else {
    //     header("Location: login2.php");
    //     exit();
    // }

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


    <title>Data Ongkir | WNJ.ID</title>

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
                        <h1 class="h3 mb-0 text-gray-800">Ongkir</h1>
                    </div>

                    <div class="table-responsive">
                        <table id="table-ongkir" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Distributor</th>
                                    <th>Total Ongkir</th>
                                    <th>Belum Lunas</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sqlongkir = $koneksi->query("SELECT 
                                                                        ongkir.idongkir, admin_mitra.idadmin, admin_mitra.namamitra, ongkir.status,
                                                                        SUM(ongkir.nominal) AS total_ongkir,
                                                                        SUM(ongkir.nominal) - SUM(CASE 
                                                                                                    WHEN ongkir.status = 'Lunas' THEN ongkir.nominal
                                                                                                    ELSE 0
                                                                                                END) AS ongkir_belum_lunas
                                                                    FROM
                                                                        ongkir
                                                                    LEFT JOIN
                                                                        admin_mitra ON admin_mitra.idadmin = ongkir.idadmin
                                                                    GROUP BY 
                                                                        ongkir.idadmin, admin_mitra.namamitra
                                                                    ORDER BY
                                                                        ongkir.idongkir DESC
                                                                ");
                                    $no = 1;
                                    while ($dataongkir = $sqlongkir->fetch_assoc()) {
                                        $idadmin    = $dataongkir['idadmin'];
                                        $sqlds      = $koneksi->query("SELECT SUM(dropship) AS total_dropship, ekspedisi FROM podropship WHERE idadmin = '$idadmin' AND idpoproduk = '343'");
                                        $datads     = $sqlds->fetch_assoc();
                                        // echo $datads['total_dropship'];
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $dataongkir['namamitra'] ?> (<?= $idadmin ?>)</td>
                                        <td>Rp. <?= number_format(num: $dataongkir['total_ongkir']) ?></td>
                                        <td>Rp. <?= number_format(num: $dataongkir['ongkir_belum_lunas']) ?></td>
                                        <td>
                                            <a href="detailongkir.php?id=<?= $dataongkir['idadmin'] ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
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
