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
            <h3><strong>Input CS Sales</strong></h3>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->



<form method="post">
       
    <label>Nama CS</label><br>
    <input type="text" required name="namacs" class="form-control"><br>
      
    <label>Email</label><br>
    <input type="email" required name="email" class="form-control"><br>
    
    <label>Password</label><br>
    <input type="password" required name="password" class="form-control"><br>

    <label>No Tlp</label><br>
    <input type="number" required name="notlp" class="form-control"><br>
    
    <label>Alamat</label><br>
    <textarea name="alamat" required class="form-control"></textarea><br>
    
    <input type="hidden" name="role" value="cs" class="form-control"><br>
   	
    <button class="btn btn-primary" name="save">Simpan</button>
</form>

<?php
  include "koneksi.php";
  if(isset($_POST["save"])){
    
    $namacs = $_POST["namacs"];
    $email = $_POST["email"];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $notlp = $_POST["notlp"];
    $alamat = $_POST["alamat"];
    $role = $_POST["role"];

      $checkEmailQuery = "SELECT email FROM users WHERE email = '$email'";
      $checkEmailResult = $koneksi->query($checkEmailQuery);

      if ($checkEmailResult->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar. Silakan gunakan email lain.'); window.location.href = 'inputagen.php';</script>";
        exit();
      }

      $queryUsers = "INSERT INTO users (id, name, email, password, role, created_at, updated_at) VALUES (NULL, '$namacs', '$email', '$password', '$role', NOW(), NOW())";
      $resultUsers = $koneksi->query($queryUsers);

      if ($resultUsers) {
        $iduser = mysqli_insert_id($koneksi);

        $queryCS = "INSERT INTO cssales (idcssales, iduser, namacs, email, notlp, alamat)
        VALUES (null, '$iduser', '$namacs', '$email', '$notlp', '$alamat')"; 

        $resultCS = $koneksi->query($queryCS);
        if ($resultCS) {
            echo "<script>alert('Data berhasil disimpan.'); window.location.href = 'cssales.php';</script>";
            exit();
        } else {
            echo "<script>alert('Gagal menyimpan data.'); window.location.href = 'cssales.php';</script>";
        } 
      } else {
      echo "<script>alert('Gagal menyimpan data ke tabel users.'); window.location.href = 'cssales.php';</script>";
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

		                    