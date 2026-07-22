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
 
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search 
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" method="post">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Cari Berdasarkan Status..." aria-label="Search" aria-describedby="basic-addon2" name="status">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit" name="cari">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>-->

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" method="post">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Cari Berdasarkan Status..." aria-label="Search" aria-describedby="basic-addon2" name="status">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit" name="cari">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>
              </div>
            </li>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin Wanoja</span>
                <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
              <!--  <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings 
                </a> 
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>-->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
       

<h3><strong>Tutorial WEB</strong></h3>

<form method="post" enctype="multipart/form-data">
    
    <label>Judul</label><br>  
    <input type="text" name="judul"><br><br>
    
    <label>Teks Atas</label><br>  
    <textarea name="teksatas"></textarea><br><br>
    
        <label>File PDF</label><br> 
    <input type="file" name="nama_file" ><br><br>
    
     <label>Teks Bawah</label><br>  
    <textarea name="teksbawah"></textarea><br><br>
   	
    <label>Link Video</label><br>   
    <textarea name="link"></textarea><br><br>
    
    <label>Kategori</label><br>   
    <select name="kategori">
    <option value="Tutorial WEB">Tutorial WEB</option>
    <option value="Market Tools">Market Tools</option>
    </select><br><br>
    
    <label>Kode Mitra</label><br>   
    <select name="mitra">
    <option value="0">DB</option>
    <option value="1">Agen</option>
    <option value="2">Reseller</option>
    <option value="3">Marketer</option>
    </select><br><br>
   	
   	<button class="btn btn-primary" name="save">Simpan</button>
</form>


<?php
if(isset($_POST["save"])){
	
$tipe_file = $_FILES['nama_file']['type']; //mendapatkan mime type
if ($tipe_file == "application/pdf") //mengecek apakah file tersebu pdf atau bukan
{
 $judul     = trim($_POST['judul']);
  $teksatas     = trim($_POST['teksatas']);
 $nama_file = trim($_FILES['nama_file']['name']);
 $teksbawah     = trim($_POST['teksbawah']);
  $link     = trim($_POST['link']);
  $mitra     = trim($_POST['mitra']);
  $kategori     = trim($_POST['kategori']);

 $sql = "INSERT INTO tutorial (idtutorial,kategori,judul,teksatas,teksbawah,link,file,idmitra) VALUES (null,'$kategori','$judul','$teksatas','$teksbawah','$link','','$mitra')";
 mysqli_query($koneksi,$sql); //simpan data judul dahulu untuk mendapatkan id

 //dapatkan id terkahir
 $query = mysqli_query($koneksi,"SELECT idtutorial FROM tutorial ORDER BY idtutorial DESC LIMIT 1");
 $data  = mysqli_fetch_array($query);

 //mengganti nama pdf
 $nama_baru = "file_".$data['idtutorial'].".pdf"; //hasil contoh: file_1.pdf
 $file_temp = $_FILES['nama_file']['tmp_name']; //data temp yang di upload
 $folder    = "file"; //folder tujuan

 move_uploaded_file($file_temp, "$folder/$nama_baru"); //fungsi upload
 //update nama file di database
 mysqli_query($koneksi,"UPDATE tutorial SET file='$nama_baru' WHERE idtutorial='$data[idtutorial]' ");

 header('location:inputtutor.php?pesan=upload-berhasil');

} elseif ($tipe_file == "") //mengecek apakah file tersebu pdf atau bukan
{
$judul     = $_POST['judul'];
 $teksatas     = $_POST['teksatas'];
 $teksbawah     = $_POST['teksbawah'];
  $link     = $_POST['link'];
  $mitra     = $_POST['mitra'];
  $kategori     = $_POST['kategori'];
 $sql = "INSERT INTO tutorial (judul,teksatas,teksbawah,link,idmitra,kategori) VALUES ('$judul','$teksatas','$teksbawah','$link','$mitra','$kategori')";
 mysqli_query($koneksi,$sql); //simpan data judul dahulu untuk mendapatkan id
 
 header('location:inputtutor.php?pesan=upload-berhasil');
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

		                    