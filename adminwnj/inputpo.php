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

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Pusat | WNJ CORP</title>

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
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"><strong>Input Produk PO</strong></h1>
                    <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                </div>

                <!-- Content Row -->
                <hr>
                <div class="row">
                    <a class="btn btn-warning btn-icon-split" href="inputpokategori.php">
                        <span class="texy-white-50 icon"><i class="fas fa-plus"></i></span>
                        <span class="text">Tambah PO Kategori</span>
                    </a>
                    &nbsp;&nbsp;&nbsp;
                    <a class="btn btn-danger btn-icon-split" href="inputvariant.php">
                        <span class="texy-white-50 icon"><i class="fas fa-plus"></i></span>
                        <span class="text">Tambah PO Variant</span>
                    </a>
                </div>
                <br>

                <form method="post" class="col-4">
                    <div class="form-group">  
                        <label>Nama PO</label><br>   
                        <input type="text" name="namapo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label><br>
                        <select name="status" class="form-control" required>
                            <option value="open">Open</option>
                            <option value="close">Close</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jenis PO</label><br>
                        <select name="jenis" class="form-control" required>
                            <option>Normal</option>
                            <option>Custom</option>
                            <option>Kolibri</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tipe PO</label><br>
                        <select name="tipe" class="form-control" required>
                            <option>Normal</option>
                            <option>Hide</option>
                        </select>
                    </div> 
                    <div class="form-group">
                        <label>Pembayaran PO</label><br>
                        <select name="pembayaran" class="form-control" required>
                            <option>DP</option>
                            <option>Lunas</option>
                            <option>Payment</option>
                        </select>
                    </div> 
                    <div class="form-group">  
                        <label>Diskon Tambahan</label><br>   
                        <input type="number" min="0" value="0" name="diskon" class="form-control" required>
                    </div>    
                    <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <input type="date" name="tglselesai" class="form-control" required>
                    </div>
                    <button class="btn btn-primary" name="save">Tambah</button>
                </form>
                <?php
                    include "koneksi.php";
                        if(isset($_POST["save"])){
                            $namapo             = $_POST["namapo"];
                            $status             = $_POST["status"];
                            $jenis              = $_POST["jenis"];
                            $tipe               = $_POST["tipe"];
                            $pembayaran         = $_POST["pembayaran"];
                            $diskon             = $_POST["diskon"];
                            $tglselesai         = $_POST["tglselesai"];
                            $newDate            = date("M d, Y", strtotime($tglselesai));

                            $store              = $koneksi->query("INSERT INTO poproduk 
                                                                        (idpoproduk, namapo, status, tglselesai, jenis, diskon, tipe, pembayaran, updated_at, created_at)
                                                                    VALUES 
                                                                        (null,'$namapo','$status','$newDate 23:59:00','$jenis','$diskon','$tipe','$pembayaran', NOW(), NOW())");
                            if ($store) {
                                echo "<script>alert('data berhasil ditambah, silahkan input kategori/warna');</script>";
                                echo "<script>location='inputpokategori.php';</script>";
                            } else {
                                echo "<script>alert('data gagal di tambahkan!');</script>";
                                echo "<script>location='inputpo.php';</script>";
                            }
                    }
                ?>

 <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2020</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

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

</body>

</html>

		                    