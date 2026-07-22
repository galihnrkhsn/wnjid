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
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->

<h3><strong>Tambah Ads</strong></h3>

<form method="post" enctype="multipart/form-data">
  
    <div class="form-group">
          <label>Akun Distributor</label>
          <select class="form-control" name="db">
              <option enabled selected>- Pilih Distibutor -</option>
              <?php
              $datadb=$koneksi->query("SELECT * FROM admin_mitra order by namamitra");
              while($tampilkan=$datadb->fetch_assoc()){
              ?>
          <option value="<?php echo $tampilkan['idadmin']; ?>"><?php echo $tampilkan['namamitra']; ?></option>
          <?php } ?>                       
          </select>      
    </div>

    <div class="form-group">
          <label>Nama Lengkap</label>  
          <input class="form-control" name="nama">
    </div>
    
    <div class="form-group">
          <label>Whatsapp</label>  
          <input class="form-control" name="whatsapp">
    </div>
    
    <div class="form-group">
          <label>Target Kota</label>
    <textarea class="form-control" name="targetkota"></textarea>
    </div>
    
    <div class="form-group">
          <label>Program</label>  
          <select class="form-control" name="program">
              <option>1 Pekan 7x50rb @Rp. 350.000,-</option>
          </select> 
    </div>
   
   <button type="submit" class="btn btn-primary" name="tambah">Tambah</button>
  <a class="btn btn-default" href='dataads.php')>Batal</a>
  </form>
  

  <?php
  if(isset($_POST["tambah"])){
     include 'koneksi.php';
      // Ambil Data yang Dikirim dari Form
      $db=$_POST['db'];
      $nama=$_POST['nama'];
      $whatsapp=$_POST['whatsapp'];
      $targetkota=$_POST['targetkota'];
      $program=$_POST['program'];
    
      $query = "INSERT INTO adsmitra VALUES (null,'$db',NOW(),'$nama','$whatsapp','$targetkota','$program','antrian')";    
      $sql = mysqli_query( $koneksi, $query);

      echo "<script>alert('data berhasil ditambah');</script>";
		echo "<script>location='dataads.php';</script>";

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

		                    