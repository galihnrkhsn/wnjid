<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}


$query = mysqli_query($koneksi, "SELECT max(idpesan) as kodeTerbesar FROM tinbox");
$data = mysqli_fetch_array($query);
$kodeBarang = $data['kodeTerbesar'];
 
// mengambil angka dari kode barang terbesar, menggunakan fungsi substr
// dan diubah ke integer dengan (int)
$urutan = (int) substr($kodeBarang, 3, 3);
 
// bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
$urutan++;
 
// membentuk kode barang baru
// perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
// misalnya perintah sprintf("%03s", 15); maka akan menghasilkan '015'
// angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya BRG 
$huruf = "P";
$kodeBarang = $huruf . sprintf("%03s", $urutan);

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
       

<h3><strong>Pesan Mitra</strong></h3>
<form method="post">
  <div class="mb-3">
    <label for="judul">ID Pesan</label> 
    <input type="text" class="form-control" placeholder="" name="idpesan" id="idpesan" value="<?= $kodeBarang; ?>" readonly>
  </div>
  <div class="mb-3">
    <label for="judul">Judul</label> 
    <input type="text" class="form-control" placeholder="Isi Judul" name="judul" id="judul">
  </div>
  <div class="mb-3">
    <label for="isi">Isi</label> 
    <textarea type="text" class="form-control" name="isi" id="isi"></textarea>
  </div>
  <div class="mb-3">
    <label for="tgl">Tanggal</label> 
    <input type="date" class="form-control" name="tgl" id="tgl">
  </div>
  <div class="mb-3">
    <select class="form-select" name="tujuan" id="tujuan" onchange="myTujuan()">
      <option value="" selected>Tujuan</option>
      <option value="Distributor">Distributor</option>
      <option value="All">All</option>
      <option value="Personal">Personal</option>
    </select>
  </div>
  <div class="mb-3">
    <select class="form-select" name="admin" id="admin" style="display:none">
      <option value="" selected>Silahkan Pilih</option>
      <?php 
      $ambildb=$koneksi->query("SELECT idadmin,namamitra FROM admin_mitra ORDER BY namamitra ASC");
      while($datadb=$ambildb->fetch_assoc()){
      ?>
      <option value="<?php echo $datadb['idadmin']; ?>"><?php echo $datadb['namamitra']; ?></option>
      <?php } ?>
    </select>
  </div>

  <button type="submit" class="btn btn-primary" name="save">Tambah</button>
</form>

<?php
if(isset($_POST["save"])){
  $idpesan = $_POST["idpesan"];
  $judul = $_POST["judul"];
  $isi = $_POST["isi"];
  $tgl = $_POST["tgl"];
  $tujuan = $_POST["tujuan"];
  $admin = $_POST["admin"];


  
  if ($tujuan=="Distributor") {
    $ambiliddb=$koneksi->query("SELECT idadmin,namamitra FROM admin_mitra");
    while($dataiddb=$ambiliddb->fetch_assoc()){
      $sql=$koneksi->query("INSERT INTO `tinbox` (`idnbox`,`idpesan`, `judul`, `isi`, `tgl`, `tujuan`, `idadmin`,`idmitraagen`,`idmitrareseller`,`idmitramarketer`, `status`) 
      VALUES (NULL,'$idpesan' ,'$judul', '$isi', '$tgl', '$tujuan', '$dataiddb[idadmin]',0,0,0, 'Belum Dibaca');");
    }
  }else if($tujuan=="Personal"){
    if ($admin=='') {
      echo "<script>alert('Distributor Belum Dipilih');</script>";
    echo "<script>location='inputgoodnews.php?';</script>";
    }else{
    $sql=$koneksi->query("INSERT INTO `tinbox` (`idnbox`,`idpesan`, `judul`, `isi`, `tgl`, `tujuan`, `idadmin`,`idmitraagen`,`idmitrareseller`,`idmitramarketer`, `status`) 
      VALUES (NULL, '$idpesan','$judul', '$isi', '$tgl', '$tujuan', '$admin',0,0,0, 'Belum Dibaca');");
    }
  }else if($tujuan=="All"){

    $ambiliddb=$koneksi->query("SELECT idadmin,namamitra FROM admin_mitra");
    while($dataiddb=$ambiliddb->fetch_assoc()){
      $sql=$koneksi->query("INSERT INTO `tinbox` (`idnbox`,`idpesan`,`judul`, `isi`, `tgl`, `tujuan`, `idadmin`,`idmitraagen`,`idmitrareseller`,`idmitramarketer`, `status`) 
      VALUES (NULL,'$idpesan','$judul', '$isi', '$tgl', '$tujuan', '$dataiddb[idadmin]',0,0,0, 'Belum Dibaca');");
    }

      $ambilidagen=$koneksi->query("SELECT idmitraagen FROM mitraagen");
    while($dataidagen=$ambilidagen->fetch_assoc()){
      $sql=$koneksi->query("INSERT INTO `tinbox` (`idnbox`,`idpesan`,`judul`, `isi`, `tgl`, `tujuan`, `idadmin`,`idmitraagen`,`idmitrareseller`,`idmitramarketer`, `status`) 
      VALUES (NULL,'$idpesan','$judul', '$isi', '$tgl', '$tujuan', '0','$dataidagen[idmitraagen]',0,0, 'Belum Dibaca');");
    }

      $ambilidreseller=$koneksi->query("SELECT idmitrareseller FROM mitrareseller");
    while($dataidreseller=$ambilidreseller->fetch_assoc()){
      $sql=$koneksi->query("INSERT INTO `tinbox` (`idnbox`,`idpesan`, `judul`, `isi`, `tgl`, `tujuan`, `idadmin`,`idmitraagen`,`idmitrareseller`,`idmitramarketer`, `status`) 
      VALUES (NULL,'$idpesan', '$judul', '$isi', '$tgl', '$tujuan', '0',0,'$dataidreseller[idmitrareseller]',0, 'Belum Dibaca');");
    }

      $ambilidmarketer=$koneksi->query("SELECT idmitramarketer FROM mitramarketer");
    while($dataidmarketer=$ambilidmarketer->fetch_assoc()){
      $sql=$koneksi->query("INSERT INTO `tinbox` (`idnbox`,`idpesan`, `judul`, `isi`, `tgl`, `tujuan`, `idadmin`,`idmitraagen`,`idmitrareseller`,`idmitramarketer`, `status`) 
      VALUES (NULL,'$idpesan' ,'$judul', '$isi', '$tgl', '$tujuan', '0',0,0,'$dataidmarketer[idmitramarketer]', 'Belum Dibaca');");     
    }
  }
    

  
if ($sql) {
      echo "<script>alert('data berhasil ditambah');</script>";
    echo "<script>location='inputgoodnews.php?';</script>";
  }
  else{
        echo "<script>alert('data gagal ditambah');</script>";
    echo "<script>location='inputgoodnews.php?';</script>";

  }
  

}

?>


<script>
function myTujuan() {
  $("#tujuan").on('change', function() {
    if ($(this).val() == 'Personal'){
        document.getElementById('admin').style.display='block';
    } else {
        document.getElementById('admin').style.display='none';
    }
});

}
</script>

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

