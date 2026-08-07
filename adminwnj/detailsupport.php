<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$idsupport=$_GET['id'];
$tampil =$koneksi->query("SELECT admin_mitra.namamitra,support_ticket.* 
  FROM support_ticket inner join admin_mitra ON support_ticket.idadmin=admin_mitra.idadmin
  WHERE support_ticket.idsupport='$idsupport'
  ");
         $tampilMas=$tampil->fetch_assoc();
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

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

<?php include "sidebar.php"; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          
<h3><strong>Input Data Mitra</strong></h3>

<form method="post" enctype="multipart/form-data" class="form-group">
     <label>Tanggal</label><br>
    <input type="date" name="tgl" class="form-control" value="<?php echo $tampilMas['tgl']; ?>" readonly><br>

      <label>ID Support Ticket</label><br>
    <input type="text" name="idsupport" class="form-control" value="<?php echo $tampilMas['idsupport']; ?>" readonly><br>
    
        <label>Nama Mitra</label><br>
    <input type="text" name="namamitra" class="form-control" value="<?php echo $tampilMas['namamitra']; ?>" readonly><br>
    
      <label>Masalah</label><br>
    <input type="text" name="masalah" class="form-control" value="<?php echo $tampilMas['masalah']; ?>" readonly><br>
    
      <label>Nama CS</label><br>
    <input type="text" name="namacs" class="form-control" value="<?php echo $tampilMas['namacs']; ?>" readonly><br>
      <label>Nominal</label><br>
    <input type="text" name="nominal" class="form-control" value="<?php echo $tampilMas['nominal']; ?>" readonly><br>
      <label>Ekspedisi</label><br>
    <input type="text" name="ekspedisi" class="form-control" value="<?php echo $tampilMas['ekspedisi']; ?>" readonly><br>
      <label>No Resi</label><br>
    <input type="text" name="noresi" class="form-control" value="<?php echo $tampilMas['noresi']; ?>" readonly><br>            


    <label>Note</label><br>
    <textarea name="alamat" class="form-control" readonly><?php echo $tampilMas['note']; ?></textarea><br>     
    <label>Status Ticket Support</label><br>
    <input type="text" name="status" class="form-control" value="<?php echo $tampilMas['status']; ?>" readonly><br>
    
    <button class="btn btn-success" name="proses">Proses</button> &nbsp;&nbsp;
    <button class="btn btn-primary" name="selesai">Selesai</button>
    <a href="return.php" class="btn btn-danger" style="float: right;">Kembali</a>    
</form>
<?php
include "koneksi.php";
    if(isset($_POST["proses"])){

    $sql = $koneksi->query("UPDATE support_ticket SET status='Ticket Diproses' where idsupport='$idsupport'");

    if ($sql) {
      echo "<script>alert('Ticket Support Diproses');</script>";
      echo "<script>location='return.php';</script>";
    }else{
      echo "<script>alert('Ticket Support Gagal Diproses');</script>";
      echo "<script>location='return.php';</script>";
    }
    }

    if(isset($_POST["selesai"])){

    $sql = $koneksi->query("UPDATE support_ticket SET status='Ticket Selesai' where idsupport='$idsupport'");

    if ($sql) {
      echo "<script>alert('Ticket Support Selesai');</script>";
      echo "<script>location='return.php';</script>";
    }else{
      echo "<script>alert('Ticket Support Gagal Diproses');</script>";
      echo "<script>location='return.php';</script>";
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

                        