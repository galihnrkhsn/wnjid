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

  <title>WNJ.ID</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/500/fabric.min.js"></script>



</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

<?php include "sidebar.php"; ?>


        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tambah Tagihan</h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->

<form method="post" enctype="multipart/form-data">
  
    <div class="form-group">
          <label>Akun Distributor</label>
          <select class="form-control" name="db" id="db">
              <option enabled selected>- Pilih Distibutor -</option>
              <?php
              $datadb=$koneksi->query("SELECT * FROM admin_mitra ORDER BY namamitra");
              while($tampilkan=$datadb->fetch_assoc()){
              ?>
          <option value="<?php echo $tampilkan['idadmin']; ?>"><?php echo $tampilkan['namamitra']; ?></option>
          <?php } ?>                       
          </select>      
    </div>

      <div class="form-group">
        <label for="invoice">Nomor Invoice</label><br>
        <select class="form-control" id="invoice" name="invoice" required></select>
    </div>  
<div class="form-group">
  <label for="total">Total Tagihan</label><br>
  <select class="form-control"  id="total" name="total" required></select>
  
  
</div>      
   
<script type="text/javascript">

    $(document).ready(function(){
        $('#db').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var iddb = $('#db').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_invoice.php',
                data :  'iddb=' + iddb,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#invoice").html(data);
                }
                
            });
        });

        $('#invoice').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var invoice = $('#invoice').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_total.php',
                data :  'invoice=' + invoice,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#total").html(data);
                }
                
            });
        }); 

        $('#invoice').ready(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var invoice = $('#invoice').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_total.php',
                data :  'invoice=' + invoice,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#total").html(data);
                }
                
            });
        });             


        
    });
</script>



    <div class="form-group">
          <label>Jatuh Tempo</label>  
          <input class="form-control" name="jatuhtempo" type="date">
    </div>
    
    <div class="form-group">
          <label>DP masuk</label>  
          <input class="form-control" name="dpmasuk">
    </div>
    
    <div class="form-group">
          <label>Metode Pembayaran</label>
    <select name="metodebayar" class="form-control">
        <option>Transfer Rekening Bank</option>
    </select>
    </div>
    
   
   <button type="submit" class="btn btn-primary" name="tambah">Tambah</button>
  <a class="btn btn-default" href='dataads.php')>Batal</a>
  </form>
  

  <?php
  if(isset($_POST["tambah"])){
     include 'koneksi.php';
      // Ambil Data yang Dikirim dari Form
      $db=$_POST['db'];
      $nama=$_POST['nama'];
      $whatsapp=$_POST['whatsapp'];
      $targetkota=$_POST['targetkota'];
      $program=$_POST['program'];
    
      $query = "INSERT INTO adsmitra VALUES (null,'$db',NOW(),'$nama','$whatsapp','$targetkota','$program','antrian')";    
      $sql = mysqli_query( $koneksi, $query);

      echo "<script>alert('data berhasil ditambah');</script>";
		echo "<script>location='dataads.php';</script>";

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

		                    