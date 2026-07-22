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

  <title>Admin Pusat | WNJ CORP</title>

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
    <h1 class="h3 mb-0 text-gray-800"><strong>Input PO Kategori</strong></h1>
</div>

<hr>

<!-- Content Row -->
<div class="row">
    <a class="btn btn-danger btn-icon-split" href="inputvariant.php">
        <span class="text-white-50 icon"><i class="fas fa-plus"></i></span>
        <span class="text">Tambah PO Variant</span>
    </a>
</div>
<br>

<!-- Form for selecting the number of categories -->
<form method="POST">
    <div class="control-group col-3">
        <label>Jumlah Kategori</label>
        <input type="number" min="0" value="0" class="form-control" name="jml_kategori" id="jml_kategori">
        <button class="btn btn-primary mt-2" type="submit" name="pilih">Pilih</button>
    </div>
</form>

<?php
if (isset($_POST["pilih"])): 
    $jml_kategori = $_POST["jml_kategori"];
?>

<!-- Form for inputting category details -->
<form method="POST">     
    <div class="control-group col-3 mb-3">
        <label for="idpoproduk">Nama PO</label>
        <select style="width: 300px" class="form-control" name="idpoproduk" id="idpoproduk">      
            <?php 
            $ambil = $koneksi->query("SELECT * FROM poproduk ORDER BY idpoproduk DESC LIMIT 10"); 
            while ($data = $ambil->fetch_assoc()) {
            ?>
                <option value='<?php echo $data['idpoproduk']; ?>'><?php echo $data['namapo']; ?></option>
            <?php } ?>  
        </select>
    </div>        

    <?php for ($x = 0; $x < $jml_kategori; $x++) { ?>
    <div class="input-group control-group col-8">   
        <div class="form-floating mb-3">
            <label for="namakategori<?php echo $x; ?>">Nama Kategori</label>
            <input style="width: 300px" type="text" name="namakategori[]" class="form-control" placeholder="Nama Kategori/Warna" id="namakategori<?php echo $x; ?>">
        </div>
        &nbsp;&nbsp;&nbsp;
        <div class="form-floating">
            <label for="stok<?php echo $x; ?>">Stok</label>
            <input style="width: 300px" type="number" value="0" name="stok[]" class="form-control" placeholder="Stok" id="stok<?php echo $x; ?>">
        </div>
    </div>
    <?php } ?>

    <div class="control-group col-3">
        <button class="btn btn-primary" type="submit" name="save">Simpan</button>
    </div>
</form>   

<?php endif; ?>

<?php
if (isset($_POST["save"])) {
    $idpoproduk = $_POST["idpoproduk"];
    $nama = $_POST["namakategori"];
    $stok = $_POST["stok"];
    $jmlh = count($nama);

    $sql_success = true; // Variabel untuk mengecek apakah semua query berhasil

    for ($x = 0; $x < $jmlh; $x++) {
        $sql = $koneksi->query("INSERT INTO pokategori (idpo, idpoproduk, namakategori, stok) 
                                VALUES (null, '$idpoproduk', '$nama[$x]', '$stok[$x]')");
        if (!$sql) {
            $sql_success = false;
            break; // Keluar dari loop jika ada kesalahan
        }
    }

    if ($sql_success) {
        echo "<div class='alert alert-success'>Data berhasil dikirim</div>";
        echo "<script>location='inputpokategori.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Data gagal dikirim</div>";
        echo "<script>location='inputpokategori.php';</script>";
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

		                    