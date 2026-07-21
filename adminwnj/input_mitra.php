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
          
<h3><strong>Input Data Mitra</strong></h3>

<form method="post" enctype="multipart/form-data" class="form-group">
    <input type="hidden" name="role" value="distributor" class="form-control" required><br>
    <label>Email</label><br>
      <input type="email" name="email" class="form-control" required><br>
    
    <label>Password</label><br>
      <input type="password" name="password" class="form-control" required><br>
    
    <label>Nama Mitra</label><br>
      <input type="text" name="namamitra" class="form-control" required><br>
    
    <label>Whatsapp</label><br>
      <input type="number" name="whatsapp" class="form-control" required><br>
    
    <label>Telegram</label><br>
      <input type="text" name="telegram" class="form-control"><br>
   	
    <label>Facebook</label><br>
      <input type="text" name="facebook" class="form-control"><br>
      
    <label>Instagram</label><br>
      <input type="text" name="instagram" class="form-control"><br>

     <div class="form-group">
        <label for="prov">Provinsi</label><br>
        <select class="form-control" id="prov" name="prov" required>
          <option disabled='disabled' selected>~Pilih Provinsi Tujuan~</option>
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
          <select class="form-control" id="kabupaten" name="kabupaten" required></select>
      </div>                      
      <div class="form-group">
        <label for="kecamatan">Kecamatan</label><br>
          <select class="form-control" id="kecamatan" name="kecamatan" required></select>
      </div>       
    <label>Alamat Lengkap</label><br>
    <textarea name="alamat" class="form-control" required></textarea><br>   	   	
   	<button class="btn btn-primary" name="save">tambah</button>
</form>
<?php
  include "koneksi.php";
    if(isset($_POST["save"])){
	
      $email = $_POST["email"];
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $namamitra = $_POST["namamitra"];
      $whatsapp = $_POST["whatsapp"];
      $telegram = $_POST["telegram"];
      $facebook = $_POST["facebook"];
      $instagram = $_POST["instagram"];
      $alamat = $_POST["alamat"];
      $role = $_POST["role"];

        $provinsi_id=$_POST["prov"];
        $result_explode = explode('|', $provinsi_id);
        $provinsi=$result_explode[0];

        $kabupaten_id=$_POST["kabupaten"];
        $result_explode = explode('|', $kabupaten_id);
        $kabupaten=$result_explode[0];

        $kecamatan_id=$_POST["kecamatan"];
        $result_explode = explode('|', $kecamatan_id);
        $kecamatan=$result_explode[0];

      $checkEmailQuery = "SELECT email FROM users WHERE email = '$email'";
      $checkEmailResult = $koneksi->query($checkEmailQuery);

      if ($checkEmailResult->num_rows > 0) {
          echo "<script>alert('Email sudah terdaftar. Silakan gunakan email lain.'); window.location.href = 'input_mitra.php';</script>";
          exit();
      }

      $queryUsers = "INSERT INTO users (id, name, email, password, role, created_at, updated_at) VALUES (NULL, '$namamitra', '$email', '$password', '$role', NOW(), NOW())";
      $resultUsers = $koneksi->query($queryUsers);

      if ($resultUsers) {
        $iduser = mysqli_insert_id($koneksi);
          $queryDistri = "INSERT INTO admin_mitra (idadmin, iduser, kodeakses, email, namamitra, whatsapp, telegram, facebook, instagram, alamat, provinsi, kota, kecamatan, status, tgl_daftar) 
          VALUES (NULL, $iduser, 'db', '$email', '$namamitra', '$whatsapp', '$telegram', '$facebook', '$instagram', '$alamat', '$provinsi', '$kabupaten', '$kecamatan', '1',  NOW())";
        $resultDistri = $koneksi->query($queryDistri);
          if ($resultDistri) {
              echo "<script>alert('Data berhasil disimpan.'); window.location.href = 'mitra.php';</script>";
              exit();
          } else {
            echo "<script>alert('Gagal menyimpan data.'); window.location.href = 'mitra.php';</script>";
          } 
      } else {
          echo "<script>alert('Gagal menyimpan data ke tabel users.'); window.location.href = 'mitra.php';</script>";
      }

    }

      // Ambil data foto yang dipilih dari form
  // $foto = $_FILES['foto']['name'];
  // $tmp = $_FILES['foto']['tmp_name'];
  // // Set path folder tempat menyimpan fotonya
  // Proses upload

    // $foto = $_FILES['foto']['name'];
    // $tmp = $_FILES['foto']['tmp_name'];
    // $ukuranFile = $_FILES['foto']['size'];
    // // cek apakah yang diupload adalah gambar
    //     $ekstensiGambarValid = ['jpg','jpeg','png'];
    //     $ekstensiGambar = explode('.', $foto);
    //     $ekstensiGambar = strtolower(end($ekstensiGambar));
    //     if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
    //         echo "<script>
    //                 alert('Yang anda upload bukan gambar');
    //               </script>";
    //               echo "<script>location='input_mitra.php';</script>";
    //     return false;
    //     }
    // $path = "foto/".$foto;
  //   $namaFileBaru = $foto;
  //   $namaFileBaru.='.';
  //   $namaFileBaru.= $ekstensiGambar;

  // if(move_uploaded_file($tmp, 'foto/'. $namaFileBaru)){ // Cek apakah gambar berhasil diupload atau tidak    
  
  // if(move_uploaded_file($tmp, $path)){ // Cek apakah gambar berhasil diupload atau tidak
  // }

		// $koneksi->query("INSERT INTO admin_mitra (idadmin,email,password,namamitra,whatsapp,telegram,
    //   facebook,instagram,alamat,provinsi,kota,kecamatan,foto,status,privateorder,tgl_daftar)
		// 	VALUES (null,'$email','$password','$namamitra','$whatsapp','$telegram',
    //   '$facebook','$instagram','$alamat','$provinsi','$kabupaten','$kecamatan','wnj.jpg','1','off',NOW())");

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

		                    