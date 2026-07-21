<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$idpesan = $_GET['idpesan'];
$datapesan=$koneksi->query("SELECT idpesan,judul,isi, tujuan, tgl,status FROM tinbox where idpesan='$idpesan'");
$tampilkan=$datapesan->fetch_assoc();

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
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
       

<h3><strong>Pesan Mitra</strong></h3>
<form method="post">
  <div class="mb-3">
    <label for="judul">Judul</label> 
     <input type="hidden" class="form-control" placeholder="Isi Judul" name="idpesan" id="idpesan" value="<?= $tampilkan['idpesan']; ?>">
    <input type="text" class="form-control" placeholder="Isi Judul" name="judul" id="judul" value="<?= $tampilkan['judul']; ?>">
  </div>
  <div class="mb-3">
    <label for="isi">Isi</label> 
    <textarea type="text" class="form-control" name="isi" id="isi" ><?= $tampilkan['isi']; ?></textarea>
  </div>
  <div class="mb-3">
    <label for="tgl">Tanggal</label> 
    <input type="date" class="form-control" name="tgl" id="tgl" value="<?= $tampilkan['tgl']; ?>">
  </div>


  <button type="submit" class="btn btn-success" name="edit">Edit Pesan</button>
  <button type="submit" class="btn btn-danger" name="hapus">Hapus Pesan</button>
</form>

<?php
if(isset($_POST["edit"])){
  
  $idpesan=$_POST["idpesan"];
  $judul = $_POST["judul"];
  $tgl = $_POST["tgl"];
  $isi = addslashes($_POST["isi"]);
  

      $sql=$koneksi->query("UPDATE tinbox set judul='$judul',tgl='$tgl',isi='$isi' where idpesan='$idpesan'");

      if ($sql) {
       
      echo "<script>alert('data berhasil diubah');</script>";
    echo "<script>location='pesan.php';</script>";
      }else{

      echo "<script>alert('data gagal diubah');</script>";
    echo "<script>location='pesan.php';</script>";
      }

  }

  if(isset($_POST["hapus"])){
    $idpesan=$_POST["idpesan"];
    $sql = $koneksi->query("delete from tinbox where idpesan='$idpesan';");
    if ($sql) {
       
      echo "<script>alert('data berhasil dihapus');</script>";
    echo "<script>location='pesan.php';</script>";
      }else{

      echo "<script>alert('data gagal dihapus');</script>";
    echo "<script>location='pesan.php';</script>";
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



  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>
