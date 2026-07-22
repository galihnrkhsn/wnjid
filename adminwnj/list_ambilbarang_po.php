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

<body id="page-top" class="sidebar-toggled">
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
      <div class="col-xl-12 col-lg-7">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div
              class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Ambil Barang PO</h6>
          </div>
          <!-- Card Body -->
          <div class="card-body">
            <div>
              <div class="form-group">
                    <label>Nama PO</label>
                    <select class="form-control" name="namapo" id="namapo">
                        <option enabled selected>- Pilih Nama PO -</option>
                        <?php
                        $datadb=$koneksi->query("SELECT * FROM poproduk WHERE idpoproduk > 100 ORDER BY idpoproduk DESC");
                        while($tampilkan=$datadb->fetch_assoc()){
                        ?>
                    <option value="<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?> (<?php echo $tampilkan['idpoproduk']; ?>)</option>
                    <?php } ?>  
                    </select>      
              </div> 
              <div class="form-group"  id="tabel_cs" name="tabel_cs"></div>   
            </div>
          </div>
        </div>
      </div> 
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
  
  <?php include "settingdatatables.php"; ?>




<script type="text/javascript">
    $(document).ready(function(){
        $('#namapo').change(function(){
            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var namapo = $('#namapo').val();
            if (namapo == "335" || namapo == '339') {
              $.ajax({
                type : 'GET',
                url : 'cek_data_po2.php',
                data :  'namapo=' + namapo,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tabel_cs").html(data);
                }
            });
            } else {
              $.ajax({
                  type : 'GET',
                  url : 'cek_data_po.php',
                  data :  'namapo=' + namapo,
                      success: function (data) {
  
                      //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                      $("#tabel_cs").html(data);
                  }
              });
            }
        });
    });
</script> 

</body>

</html>

                                                    