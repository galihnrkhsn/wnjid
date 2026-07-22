<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$idadmin=$_GET['id'];
$tampil =$koneksi->query("SELECT * FROM admin_mitra where idadmin='$idadmin' ");
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
      <label>Email</label><br>
    <input type="text" name="email" class="form-control" value="<?php echo $tampilMas['email']; ?>"><br>
    
      <!-- <label>Password</label><br>
    <input type="text" name="password" class="form-control" value="<?php echo $tampilMas['password']; ?>"><br> -->
    
        <label>Nama Mitra</label><br>
    <input type="text" name="namamitra" class="form-control" value="<?php echo $tampilMas['namamitra']; ?>"><br>
    
      <label>Whatsapp</label><br>
    <input type="text" name="whatsapp" class="form-control" value="<?php echo $tampilMas['whatsapp']; ?>"><br>
    
      <label>Telegram</label><br>
    <input type="text" name="telegram" class="form-control" value="<?php echo $tampilMas['telegram']; ?>"><br>
   	
        <label>Facebook</label><br>
    <input type="text" name="facebook" class="form-control" value="<?php echo $tampilMas['facebook']; ?>"><br>
      
      <label>Instagram</label><br>
    <input type="text" name="instagram" class="form-control" value="<?php echo $tampilMas['instagram']; ?>"><br>

              <div class="form-group">
          <label for="prov">Provinsi</label><br>
          <select class="form-control" id="prov" name="prov" required>
          <?php
          $provinsi=$tampilMas['provinsi'];
          $ambil2=$koneksi->query("SELECT * FROM tb_ro_provinces where province_id='$provinsi' ");
          $row2=$ambil2->fetch_assoc();
          ?>
          <option value="<?php echo $row2['province_id']; ?>|<?php echo $row2['province_name']; ?>" selected><?php echo $row2['province_name']; ?></option>
          <?php
          $ambil=$koneksi->query("SELECT * FROM tb_ro_provinces ");
          while($row=$ambil->fetch_assoc()){
          ?>
          <option value="<?php echo $row['province_id']; ?>|<?php echo $row['province_name']; ?>"><?php echo $row['province_name']; ?>
          </option>
          <?php } ?>
          </select>
          </div>
   
          <div class="form-group">
          <label for="kabupaten">Kota/Kabupaten</label><br>
          <select class="form-control" id="kabupaten" name="kabupaten" required>
          <?php
          $kota=$tampilMas['kota'];
          $ambil3=$koneksi->query("SELECT * FROM tb_ro_cities where city_id='$kota' ");
          $row3=$ambil3->fetch_assoc();
          ?>
          <option value="<?php echo $row3['city_id']; ?>|<?php echo $row3['city_name']; ?>" selected><?php echo $row3['city_name']; ?></option>
          </select>
          </div>
                      
          <div class="form-group">
          <label for="kecamatan">Kecamatan</label><br>
          <select class="form-control" id="kecamatan" name="kecamatan" required>
            <?php
          $kecamatan=$tampilMas['kecamatan'];
          $ambil4=$koneksi->query("SELECT * FROM tb_ro_subdistricts where subdistrict_id='$kecamatan' ");
          $row4=$ambil4->fetch_assoc();
          ?>
          <option value="<?php echo $row4['subdistrict_id']; ?>|<?php echo $row4['subdistrict_name']; ?>" selected><?php echo $row4['subdistrict_name']; ?></option>
          </select>
          </div>
      
    <label>Alamat Lengkap</label><br>
    <textarea name="alamat" class="form-control"><?php echo $tampilMas['alamat']; ?></textarea><br>   	
   	
   	<button class="btn btn-primary" name="save">Ubah</button>
</form>
<?php
include "koneksi.php";
    if(isset($_POST["save"])){
	
	$email          = $_POST["email"];
	$password       = $_POST["password"];
	$namamitra      = $_POST["namamitra"];
	$whatsapp       = $_POST["whatsapp"];
	$telegram       = $_POST["telegram"];
	$facebook       = $_POST["facebook"];
	$instagram      = $_POST["instagram"];
	$alamat         = $_POST["alamat"];
    $provinsi_id    = $_POST["prov"];
    $result_explode = explode('|', $provinsi_id);
    $provinsi       = $result_explode[0];

    $kabupaten_id   = $_POST["kabupaten"];
    $result_explode = explode('|', $kabupaten_id);
    $kabupaten      = $result_explode[0];

    $kecamatan_id   = $_POST["kecamatan"];
    $result_explode = explode('|', $kecamatan_id);
    $kecamatan      = $result_explode[0];
    var_dump($email, $password, $alamat, $provinsi, $kabupaten, $kecamatan, $idadmin);
    // die();

    $koneksi->query("UPDATE admin_mitra SET 
                        email = '$email', 
                        -- password = '$password', 
                        namamitra = '$namamitra',
                        whatsapp = '$whatsapp',
                        telegram = '$telegram',
                        facebook = '$facebook',
                        instagram = '$instagram',
                        alamat = '$alamat',
                        provinsi = '$provinsi',
                        kota = '$kabupaten',
                        kecamatan = '$kecamatan' 
                    WHERE idadmin = '$idadmin'");

	    echo "<script>alert('data berhasil ditambah');</script>";
		echo "<script>location='mitra.php';</script>";

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

  <script type="text/javascript">

  $(document).ready(function(){
    $('#prov').change(function(){
      //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
      var provinsi = $('#prov').val();
          $.ajax({
              type : 'GET',
              url : 'cek_kabupaten2.php',
              data :  'prov_id=' + provinsi,
          success: function (data) {
          //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
          $("#kabupaten").html(data);
        }
            });
    });
    
  $('#kabupaten').change(function(){
      //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
      var kabupaten = $('#kabupaten').val();
          $.ajax({
              type : 'GET',
              url : 'cek_kecamatan2.php',
              data :  'kabupaten_id=' + kabupaten,
          success: function (data) {
          //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
          $("#kecamatan").html(data);
        }
            });
    });

  });
  </script>

</body>

</html>

		                    