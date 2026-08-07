<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

    $id = $_GET["id"];



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

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          
<h3><strong>Input Data Mitra</strong></h3>

<?php
$ambil=$koneksi->query("SELECT * FROM pendaftaran where idpendaftaran='$id'");
while($data=$ambil->fetch_assoc()){
?>
<form method="post" enctype="multipart/form-data" class="form-group">
      <label>Email</label><br>
    <input type="text" name="email" class="form-control" value="<?php echo $data['email'];?>"><br>
    
      <label>Password</label><br>
    <input type="text" name="password" class="form-control" required><br>
    
        <label>Nama Mitra</label><br>
    <input type="text" name="namamitra" class="form-control" value="<?php echo $data['namalengkap'];?>"><br>
    
      <label>Whatsapp</label><br>
    <input type="text" name="whatsapp" class="form-control" value="<?php echo $data['nomortelp'];?>"><br>
    
      <label>Telegram</label><br>
    <input type="text" name="telegram" class="form-control" value="<?php echo $data['nomortelp'];?>"><br>
   	
        <label>Facebook</label><br>
    <input type="text" name="facebook" class="form-control" value="<?php echo $data['fbpribadi'];?>"><br>
      
      <label>Instagram</label><br>
    <input type="text" name="instagram" class="form-control" value="<?php echo $data['instagram'];?>"><br>
      
    <label>Alamat</label><br>
    <textarea name="alamat" class="form-control" value=""><?php echo $data['alamat'];?></textarea><br>   	
   	
   	<label>Foto</label><br>
   	<input type="file" name="foto" required><br>
   	<label><font color="red">*wajib di isi</font></label><br><br>
   	
   	<button class="btn btn-primary" name="save">Tambah</button>
</form>
<?php
}
?>

<?php

    if(isset($_POST["save"])){
	
	$email = $_POST["email"];
	$password = $_POST["password"];
	$namamitra = $_POST["namamitra"];
	$whatsapp = $_POST["whatsapp"];
	$telegram = $_POST["telegram"];
	$facebook = $_POST["facebook"];
	$instagram = $_POST["instagram"];
	$alamat = $_POST["alamat"];
  // Ambil data foto yang dipilih dari form
  $foto = $_FILES['foto']['name'];
  $tmp = $_FILES['foto']['tmp_name'];
  // Set path folder tempat menyimpan fotonya
  $path = "foto/".$foto;
  // Proses upload
  if(move_uploaded_file($tmp, $path)){ // Cek apakah gambar berhasil diupload atau tidak
        
    $ambil2=$koneksi->query("SELECT COUNT(*) as jmlh FROM admin_mitra where email='$email'");
    $data2=$ambil2->fetch_assoc(); 
        
		if($data2['jmlh']==0){
    $koneksi->query("INSERT INTO admin_mitra (idadmin,email,password,namamitra,whatsapp,telegram,facebook,instagram,alamat,foto,status,privateorder,tgl_daftar)
			VALUES (null,'$email','$password','$namamitra','$whatsapp','$telegram','$facebook','$instagram','$alamat','$foto',
      '0','off',NOW())");
    $koneksi->query("UPDATE pendaftaran SET status='Terdaftar' where idpendaftaran='$id'");
    echo "<script>alert('Data Mitra Berhasil Di Tambahkan');</script>";
		echo "<script>location='mitra.php';</script>";
    }
    else{
      echo "<script>alert('Email Sudah Terdaftar, Silahkan Periksa Kembali');</script>";
		echo "<script>location='datapendaftaran.php';</script>";
    }
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