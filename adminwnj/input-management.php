<?php 
session_start();

include 'koneksi.php'; 

if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

if (isset($_POST["submit"])) {
    $email = $koneksi->real_escape_string($_POST["email"]);
    $password = password_hash($koneksi->real_escape_string($_POST["password"]), PASSWORD_BCRYPT); // Menggunakan bcrypt untuk mengenkripsi password
    $nama = $koneksi->real_escape_string($_POST["nama"]);
    $tipe = $koneksi->real_escape_string($_POST["tipe"]);

    // Insert data ke database
    $query = "INSERT INTO management (id, namalengkap, email, password, tipe) VALUES (NULL, '$nama', '$email', '$password', '$tipe')";

    if ($koneksi->query($query) === TRUE) {
      echo "<script>alert('Berhasil'); location='test.php';</script>";
    } else {
      echo "<script>alert('Gagal: " . $koneksi->error . "'); location='test.php';</script>";
    }
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

  <title>WNJ.ID</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div class="container-fluid">
            <!-- CONTENT -->
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2 class="text-center mt-5">Form Input</h2>
                    <form method="POST" class="mt-4">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="tipe">tipe</label>
                        <select class="form-control" id="tipe" name="tipe" required>
                        <option value="" disabled selected>Pilih tipe</option>
                        <option value="O">Owner</option>
                        <option value="AF">Admin Finance</option>
                        <option value="M">Management</option>
                        <option value="P">Produksi</option>
                        <option value="ADS">Adsense</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="submit">Submit</button>
                    </form>
                </div>
            </div>
            <!-- CONTENT END -->
        </div>
    </div>
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
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <?php include "settingdatatables.php" ?>
</body>

</html>

		                    