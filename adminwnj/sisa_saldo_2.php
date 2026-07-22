<?php 
    session_start();

    include 'koneksi.php'; 

    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
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

    <title>Admin Pusat | Wanoja</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Content Row -->
            <div class="row">
                <div class="table-responsive">
                    <a class="btn btn-success" href="excelsisasaldo.php"><span class="fas fa-print"></span> Export Excel</a><br><br>
                    <table class="table table-striped table-bordered table-hover" id="tb_sisasaldo">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th style="text-align: center;" width="20%">Nama CS</th>
                                <th style="text-align: center;" width="20%">Nama Distributor</th>
                                <th style="text-align: center;" width="20%">Sisa Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $total_saldo = 0;
                                $no = 1;
                                $query = $koneksi->query("SELECT 
                                                                admin_mitra_cs.idadmin,
                                                                admin_mitra_cs.namamitra,
                                                                admin_mitra_cs.namacs,
                                                                saldo.debit,
                                                                saldo.credit,
                                                                (SUM(saldo.debit) - SUM(saldo.credit)) AS sisa
                                                            FROM
                                                                saldo
                                                                    INNER JOIN
                                                                admin_mitra_cs ON saldo.idadmin = admin_mitra_cs.idadmin
                                                            WHERE
                                                                admin_mitra_cs.idadmin NOT LIKE 233
                                                            GROUP BY admin_mitra_cs.idadmin
                                                            ORDER BY sisa DESC
                                                        ");
                                while ($data = $query->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $data['namacs']; ?></td>
                                    <td><?= $data['namamitra']; ?></td>
                                    <td>Rp. <?= number_format($data['sisa']); ?></td>
                                </tr>
                            <?php 
                                    $total_saldo = $total_saldo + $data['sisa'];
                                } 
                            ?>
                        </tbody>
                    </table>

                    <p>Total Saldo: Rp. <?= number_format($total_saldo); ?></p>
                </div>
            </div>
        </div>
        <!-- End of Content Wrapper -->

        <!-- Footer -->
        <footer class="sticky-footer">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                <span>Copyright &copy; Your Website 2020</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->
    </div>
    <!-- End of Page Wrapper -->

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

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <?php include 'settingdatatables.php';  ?>
</body>
</html>
