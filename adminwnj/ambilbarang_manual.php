<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$invoice=$_GET["invoice"];

	$sql = "SELECT * FROM orderpengiriman WHERE invoice='$invoice' ";
	$query = $koneksi->query($sql);
	$pengiriman = $query->fetch_assoc();
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

<body id="page-top" class="sidebar-toggled">

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
            <h1 class="h3 mb-0 text-gray-800">Ambil Barang Manual Ready Stock</h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Ready Stock</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div>
<form method="post">  


<button type="submit" name="but_save" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Ke Keranjang</button>  
<a href="keranjang_manual.php" class="btn btn-success" style="float: right;"><i class="fa fa-shopping-cart"></i> Keranjang Manual</a> 
<br>
<br> 
 <div class="table-responsive" >
    
    <table class="table table-bordered" id="tb_surat_manual">
        <thead>
        <tr>
          <th>Check</th>
            <th>No</th>
          <th>Nama Produk</th>
          <th>Jumlah</th>
                </tr>
        </thead>
        <tbody>
              <?php 
             
                $datapo=$koneksi->query("SELECT *
                  FROM produk ORDER BY idproduk desc
                  ");
                $no=1;
              
                while($tampilkan = $datapo->fetch_assoc()){
                  $id = $tampilkan['idproduk'];
                ?>
                <tr>
        <td><input type='checkbox' name='update[]' value='<?= $id ?>'>
                            <input type='hidden' name='idproduk<?= $id ?>' value='<?php echo $tampilkan['idproduk']; ?>' >
 
                            
        </td>                 
                <td>
                  <?= $no++; ?>
                </td>
                <td>
                  <?= $tampilkan['namaproduk'] ?>
                </td>
                <td>
                  <input type="number" name='progres<?= $id ?>' class="form-control" min="0" value="0">
                </td>

                        </tr>
                    
                        <?php } ?>
                      </tbody>
                    </table>
                    
                    </div>
</form>

             
                                    </div>
                                </div>
                            </div>
                        </div>  



<?php 
        if(isset($_POST['but_save'])){
          date_default_timezone_set('Asia/Jakarta');
          $waktu = date("H:i:s");
          $tanggal = date("Y-m-d");
          $tglnya = date("dm");
          $waktunya = date("Hi");
          $idadmin = $_POST['idadmin'];

          if(isset($_POST['update'])){
            foreach($_POST['update'] as $updateid){
              $progres = $_POST['progres'.$updateid];
              // echo "<script>alert('$idadmin, $updateid, $progres');</script>";
              if ($progres>0) {
                $sqlnya = $koneksi->query("INSERT INTO surat_jalan_manual (id_sj, no_sj, idadmin, idproduk, progres, status, waktu) 
                VALUES (null,null,'0','$updateid','$progres','RS','$tanggal $waktu')");
              }
            }
            if ($sqlnya) {
              echo "<script>alert('data berhasil disimpan');</script>";
              echo "<script>location='ambilbarang_manual.php';</script>";  
            }else{
              echo "<script>alert('data gagal disimpan');</script>";
              echo "<script>location='ambilbarang_manual.php';</script>";
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


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script src="assets/dist/js/jquery.min.js"></script>
    <script src="assets/dist/js/bootstrap.min.js"></script>
    <script src="assets/dist/DataTables/datatables.min.js"></script>
<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_surat_manual').DataTable({
        "lengthMenu": [[25, 50, 100, 200, 300, 400], [25, 50, 100, 200 , 300, 400]],
          columnDefs: [
    { orderable: false, targets: 0 }
  ]
});
} );
</script>



</body>

</html>

		                                                