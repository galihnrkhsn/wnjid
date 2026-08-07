<?php 
    error_reporting(E_ALL);
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    
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
  <meta name="viewport" content="width=device-width, initial-scale=1">


  <title>WNJ.ID</title>

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
    .aws text
    {
        color: white;
        font-size: x-large;
        text-align: right;
    }
    .aws p
    {
        color: white;
        text-align: left;
        font-size: ;
       
    }
    .aws button 
    {
        text-align: left;
    }
       .aws a 
    {
        text-align: left;
    }
  </style>
  
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">
<!-- ==========================THIS========================== -->
<?php 
if($_SESSION["administrator"]){
include "sidebar.php";
}
?>
<!-- ==========================THIS========================== -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard Admin WNJ.ID </h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>
          <!-- MULAI KONTEN AWS -->
<div class="row">

  <!-- Ready Stock -->
  <div class="col-12 mb-4">
    <div class="card shadow">
      <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Ready Stock</h6>
      </div>
      <div class="card-body">
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
          <div class="col text-center">
            <a href="list_ambil_barang2.php">
              <img src="img/ambilBarangOrder.png" class="img-fluid mb-2" alt="Ambil Barang Order">
              <div class="small">Ambil Barang Order</div>
            </a>
          </div>
          <div class="col text-center">
            <a href="list_ambilbarang_print.php">
              <img src="img/printAmbilBarang.png" class="img-fluid mb-2" alt="Print Ambil Barang">
              <div class="small">Print Ambil Barang</div>
            </a>
          </div>
          <div class="col text-center">
            <a href="suratjalan.php">
              <img src="img/suratJalan.png" class="img-fluid mb-2" alt="Surat Jalan">
              <div class="small">Surat Jalan</div>
            </a>
          </div>
          <div class="col text-center">
            <a href="suratjalan_manual.php">
              <img src="img/suratJalanManual.png" class="img-fluid mb-2" alt="Surat Jalan Manual">
              <div class="small">Surat Jalan Manual</div>
            </a>
          </div>
          <div class="col text-center">
            <a href="invoice_manual.php">
              <img src="img/invoice.png" class="img-fluid mb-2" alt="Invoice Manual">
              <div class="small">Invoice Manual</div>
            </a>
          </div>
          <div class="col text-center">
            <a href="pilih_penjualan.php">
              <img src="img/penjualan.png" class="img-fluid mb-2" alt="Penjualan Ready Stok">
              <div class="small">Penjualan Ready Stok</div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Preorder -->
  <div class="col-12 mb-4">
    <div class="card shadow">
      <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Preorder</h6>
      </div>
      <div class="card-body">
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
          <div class="col text-center">
            <a href="list_ambilbarang_po.php">
              <img src="img/ambilBarangOrder.png" class="img-fluid mb-2" alt="Ambil Barang PO">
              <div class="small">Ambil Barang PO</div>
            </a>
          </div>
          <div class="col text-center">
            <a href="suratjalan_po.php">
              <img src="img/suratJalanCek.png" class="img-fluid mb-2" alt="Surat Jalan Checker">
              <div class="small">Surat Jalan Checker</div>
            </a>
          </div>
          <div class="col text-center">
            <a href="suratjalan_manualpo.php">
              <img src="img/suratJalanManual.png" class="img-fluid mb-2" alt="Surat Jalan Manual">
              <div class="small">Surat Jalan Manual</div>
            </a>
          </div>
          <div class="col text-center">
            <a href="po_manual.php">
              <img src="img/invoice.png" class="img-fluid mb-2" alt="Invoice Manual">
              <div class="small">Invoice Manual</div>
            </a>
          </div>
          <div class="col text-center">
            <a href="ambil_barang_kurang.php">
              <img src="img/kekurangan.png" class="img-fluid mb-2" alt="Kekurangan PO">
              <div class="small">Kekurangan PO</div>
            </a>
          </div>
          <div class="col text-center">
            <a href="penjualan_po.php">
              <img src="img/penjualan.png" class="img-fluid mb-2" alt="Penjualan PO">
              <div class="small">Penjualan PO</div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Toko MO -->
  <div class="col-12 mb-4">
    <div class="card shadow">
      <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Toko Mall Online</h6>
      </div>
      <div class="card-body">
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
          <div class="col text-center">
            <a href="toko_mo.php">
              <img src="img/toko.png" class="img-fluid mb-2" alt="Toko MO">
              <div class="small">Toko MO</div>
            </a>
          </div>
          <div class="col text-center">
            <a href="produk_mo.php">
              <img src="img/produk.png" class="img-fluid mb-2" alt="Produk MO">
              <div class="small">Produk MO</div>
            </a>
          </div>
          <div class="col text-center">
            <a href="stok_mo.php">
              <img src="img/stokToko.png" class="img-fluid mb-2" alt="Stok Toko">
              <div class="small">Stok Toko</div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
<!-- AKHIR KONTEN AWS -->

          <!-- Content Row -->
          <div class="row">

           
      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; WNJ.ID Development 2020</span>
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
