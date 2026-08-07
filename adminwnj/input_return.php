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
          
<h3><strong>Tambah Data Return</strong></h3>

<form method="post" enctype="multipart/form-data" class="form-group">
     <label>Tanggal</label><br>
    <input type="date" name="tgl" class="form-control" ><br>

        <label>Nama Mitra</label><br>
          <select class="form-control" name="idadmin" id="idadmin">
              <option enabled selected>- Pilih Nama Mitra -</option>
<?php 
 $datapo=$koneksi->query("SELECT * FROM admin_mitra ORDER BY namamitra asc");
                $no=1;
              
                while($tampilkan=$datapo->fetch_assoc()){
 ?>     
              <option value="<?= $tampilkan['idadmin']; ?>"><?= $tampilkan['namamitra']; ?></option>
<?php } ?>
          </select>     
      <label>Masalah</label><br>
      <textarea name="masalah" class="form-control" required></textarea>
<br>
    
      <label>Nama CS</label><br>
          <select class="form-control" name="namacs" id="namacs">
              <option enabled selected>- Pilih Nama CS -</option>
<?php 
 $datapo=$koneksi->query("SELECT * FROM admin_mitra_cs WHERE namacs<>'' GROUP BY namacs ORDER BY namacs asc");
                $no=1;
              
                while($tampilkan=$datapo->fetch_assoc()){
 ?>     
              <option value="<?= $tampilkan['namacs']; ?>"><?= $tampilkan['namacs']; ?></option>
<?php } ?>
          </select>         

      <label>Nominal</label><br>
    <input type="number" name="nominal" class="form-control"><br>
    
                        <label for="ekspedisi">Ekspedisi</label><br>
                        <select class="form-control" id="ekspedisi" name="ekspedisi" required>
                             <option disabled='disabled' value="" selected>~Pilih Ekspedisi~</option>
                             <option value="JNE">JNE</option>
                              <option value="TIKI">TIKI</option>
                          <option value="POS INDONESIA">POS INDONESIA</option>
                          <option value="WAHANA">WAHANA</option>
                          <option value="SICEPAT">SICEPAT</option>
                          <option value='J&T'>J&T</option>
                          <option value='LION'>LION</option>
                          <option value='Anteraja'>Anteraja</option>
                          <option value='ID Express'>ID Express</option>
                          <optgroup label="Lainnya (Ongkir Manual)">
                                          <option value='ID Express Truck'>ID Express Truck</option>
                                          <option value='J&T Cargo'>J&T Cargo</option>

                                          <option value='Ahsan'>Ahsan</option>
                                          <option value='Baraka'>Baraka</option>
                                          <option value='Dakota'>Dakota</option>
                                          <option value='IndahCargo'>IndahCargo</option>
                                          <option value='Adam Cargo'>Adam Cargo</option>
                                          <option value='Pegasus'>Pegasus</option>
                                          <option value='Gosend'>GoSend</option>
                                          <option value='KALOG'>KALOG</option>
                                          <option value='Sentral'>Sentral</option>
                                          <option value='CMC KARGO'>CMC CARGO</option>
                                          <option value='Triplogic'>Triplogic</option>
                                          <option value='Ambil ke Pusat'>Ambil Ke Pusat</option>
                                          <option value='Disatukan'>Disatukan Paket Lainnya</option>
                        </select>
  
          <label>No Resi</label><br>
    <input type="text" name="noresi" class="form-control"><br>
    
  
  

    <label>Note</label><br>
    <textarea name="note" class="form-control"></textarea><br>  

    <button class="btn btn-success" name="tambah">Tambah Return</button>

    <a href="return.php" class="btn btn-danger" style="float: right;">Kembali</a>
                      </div>
    <!--  &nbsp;&nbsp;
    <button class="btn btn-primary" name="selesai">Selesai</button> -->
</form>
<?php
include "koneksi.php";
    if(isset($_POST["tambah"])){

  $idadmin = $_POST["idadmin"];
  $masalah = $_POST["masalah"];
  $namacs = $_POST["namacs"];
  $tgl = $_POST["tgl"];
  $nominal = $_POST["nominal"];
  $ekspedisi = $_POST["ekspedisi"];  
  $noresi = $_POST["noresi"];  
  $note = $_POST["note"];      

   $sql=$koneksi->query("INSERT INTO `support_ticket` (`idsupport`,`idadmin`, `masalah`, `namacs`, `tgl`, `nominal`, `ekspedisi`,`noresi`,`note`, `status`) 
      VALUES (NULL, '$idadmin','$masalah', '$namacs', '$tgl', '$nominal', '$ekspedisi','$noresi','$note','Ticket Diajukan');");

    if ($sql) {
      echo "<script>alert('Ticket Support Diproses');</script>";
      echo "<script>location='return.php';</script>";
    }else{
      echo "<script>alert('Ticket Support Gagal Diproses');</script>";
      echo "<script>location='return.php';</script>";
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

                        