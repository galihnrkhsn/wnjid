<?php 
  session_start();

  include 'koneksi.php'; 


  if(!isset($_SESSION["administrator"])){
    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login.php';</script>";
    header('location:login.php');
    exit();
  }

  $idpoproduk = $_GET['id'];
  $query = "SELECT poproduk.idpoproduk,
                poproduk.namapo, bukapo.jenis_po
            FROM poproduk 
            INNER JOIN bukapo ON poproduk.idpoproduk = bukapo.idpoproduk
            WHERE poproduk.idpoproduk='$idpoproduk'";
  $sqlpo = mysqli_query($koneksi, $query);  
  $datapo = mysqli_fetch_array($sqlpo);
  $jenis_po = $datapo['jenis_po'];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>WNJ.ID</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
  </head>

<body id="page-top" class="sidebar-toggled">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php include "sidebar.php"; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><?= $datapo['namapo'] ?></h1>
            <br>
            <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>
            <?php 
              $idpoproduk = $_GET['id'];
              if ($idpoproduk==186 or $idpoproduk==187): ?>
              <h6 class="h6 mb-0 text-gray-800"><a href="hampers.php?id=<?= $idpoproduk; ?>">Tambah Voal Hampers</a></h6>
            <?php endif ?>
            <?php if ($idpoproduk==190): ?>
              <h6 class="h6 mb-0 text-gray-800"><a href="inputpodb.php?id=<?= $idpoproduk; ?>">Tambah <?= $datapo['namapo']; ?></a></h6>
            <?php else :?>
                <div class="my-3">
                  <a href="formpo.php?id=<?= $idpoproduk ?>" class="btn btn-primary btn-sm">Tambah Invoice</a>
                </div>
            <?php endif ?>
          <div class="">
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Semua PO</a></li>
              <li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Belum Bayar</a></li>
            </ul>
            <div class="tab-content">
              <div id="home" class="tab-pane fade show active" role="tabpanel">
                <div class="table-responsive">
                  <?php include "listpokolibri3.php"; ?>
                </div>
              </div>

              <div id="menu1" class="tab-pane fade">
                <div class="table-responsive">
                  <?php include "listpokolibri2.php"; ?>
                </div>
              </div>
            </div>
          </div>
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
          <a class="btn btn-primary" href="login.php">Logout</a>
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
  
  <?php include "settingdatatables.php"; ?>

    <script type="text/javascript">
     function checkAllDB(box) 
  {

   if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
    var idpomitra_dbcheck = document.getElementsByName("idpomitra_dbcheck[]");
    var jml=idpomitra_dbcheck.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idpomitra_dbcheck[b].checked=true;
        
    }
   } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
    var idpomitra_dbcheck = document.getElementsByName("idpomitra_dbcheck[]");
    var jml=idpomitra_dbcheck.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idpomitra_dbcheck[b].checked=false;
        
    }
   }
  }
  
 </script>

</body>

</html>

                                                    