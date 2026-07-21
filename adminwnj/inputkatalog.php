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

<h3><strong>Input Katalog</strong></h3>

 <form method="post" enctype="multipart/form-data">
    
   <!--  <label>Pilih Kategori</label><br> -->
<!--     <select name="idkategori" class="form-control" style="display: none;">
        <?php $ambil=$koneksi->query("SELECT * FROM kategori");
        while($data=$ambil->fetch_assoc()){
        ?>
        <option value="<?php echo $data['idkategori']; ?>"><?php echo $data['namakategori']; ?></option>
        <?php } ?>
    </select>  -->
    
    <label>Foto</label>
    <input type="file" name="foto"><br><br>
    
    <label>Link Foto Lengkap</label><br>   
    <input type="text" name="linkfoto" class="form-control"><br>
    
    <label>Nama Produk</label><br>   
    <input type="text" name="namaproduk" class="form-control"><br>
    
    <label>Deskripsi</label><br>
    <textarea name="deskripsi" class="form-control"></textarea><br>
    
      <label>Harga</label><br>
    <input type="text" name="harga" class="form-control"><br><br>
    
   	
   	<button class="btn btn-primary" name="save">Tambah</button>
</form>
<?php
if(isset($_POST["save"])){
	
	$idkategori = 1;
	$linkfoto = $_POST["linkfoto"];
	$namaproduk = $_POST["namaproduk"];
	$deskripsi = addslashes($_POST["deskripsi"]);
	$harga = $_POST["harga"];
	
	// Ambil data foto yang dipilih dari form
    // $foto = $_FILES['foto']['name'];
    // $tmp = $_FILES['foto']['tmp_name'];
    // // Set path folder tempat menyimpan fotonya
    // $path = "foto/".$foto;
    // Proses upload

    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $ukuranFile = $_FILES['foto']['size'];
    // cek apakah yang diupload adalah gambar
        $ekstensiGambarValid = ['jpg','jpeg','png'];
        $ekstensiGambar = explode('.', $foto);
        $ekstensiGambar = strtolower(end($ekstensiGambar));
        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            echo "<script>
                    alert('Yang anda upload bukan gambar');
                  </script>";
                  echo "<script>location='inputkatalog.php';</script>";
        return false;
        }
    $namaFileBaru = uniqid();
    $namaFileBaru.='.';
    $namaFileBaru.= $ekstensiGambar;

    if(move_uploaded_file($tmp, 'foto/'. $namaFileBaru)){ // Cek apakah gambar berhasil diupload atau tidak

		$sql = $koneksi->query("INSERT INTO katalog (idkatalog,idkategori,namaproduk,deskripsi,harga,foto,link_drive)
			VALUES (null,'$idkategori','$namaproduk','$deskripsi','$harga','$namaFileBaru','$linkfoto')");

    if ($sql) {
      echo "<script>alert('data berhasil ditambah');</script>";
    echo "<script>location='inputkatalog.php';</script>";
    }else{

			echo "<script>alert('data gagal ditambah');</script>";
    echo "<script>location='inputkatalog.php';</script>";

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

		                    