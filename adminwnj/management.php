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
             <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Email</th>
                        <th><i class="fas fa-cog"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $queryManage = $koneksi->query("SELECT * FROM management");
                        while($dataManage = $queryManage->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?= $dataManage['namalengkap'] ?></td>
                        <td><?= $dataManage['tipe'] ?></td>
                        <td><?= $dataManage['email'] ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="id" value="<?= $dataManage['id'] ?>">
                                <button type="submit" class="btn btn-info" name="resetPassword"><span class="fa fa-power-off"></span></button>
                            </form>
                        </td>
                    </tr>
                    <? } ?>
                </tbody>
             </table>
            <!-- CONTENT END -->
        </div>
    </div>
      <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- PHP -->
   <?php
    if (isset($_POST["resetPassword"])) {
        $idManage           = $_POST['id'];
        $queryReset         = $koneksi->query("SELECT * FROM management WHERE id = '$idManage'");
        
        if ($queryReset && $queryReset->num_rows > 0) {
            $dataReset = $queryReset->fetch_assoc();
            $email  = $dataReset['email'];

            // Pastikan variabel $email tidak kosong
            if ($email) {
                $newPassword = $email . '_' . $idManage;
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updatePassword = $koneksi->query("UPDATE management SET password = '$hashedPassword' WHERE id = '$idManage'");
                
                if ($updatePassword) {
                    echo "<script>alert('Password berhasil diubah');</script>";
                    echo "<script>location='management.php';</script>";
                } else {
                    echo "<script>alert('Gagal mengubah password');</script>";
                }
            } else {
                echo "<script>alert('Email tidak ditemukan');</script>";
            }
        } else {
            echo "<script>alert('Data admin tidak ditemukan');</script>";
        }
    }
   ?>
  <!-- PHP END -->
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

		                    