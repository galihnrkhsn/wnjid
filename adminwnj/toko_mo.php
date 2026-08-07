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
            <h1 class="h3 mb-0 text-gray-800">Toko Mall Online</h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>


<div class="row">
<a href="input_toko.php" class="btn btn-primary mb-5"><i class="fa fa-plus"></i> Tambah Data</a> 



<div class="table-responsive">
<form method="post">     
 <table class="table table-bordered" id="tb_surat_manual">
  <thead>
    <tr>
      <th><input type='checkbox' id='checkAll' > Check</th>
      <th>Nama Toko</th>
    </tr>
  </thead>
  <tbody>
<?php 
  $no=1;
  $data=$koneksi->query("SELECT * FROM tokomo");
  while($tampilkan=$data->fetch_assoc()){ 
    $id = $tampilkan['id'];
                  ?>
    <tr>
      <td>
        <input type='checkbox' name='update[]' value='<?= $id ?>' >
      </td>
      <td>
        <div class="col-4">  
          <input type='text' class="form-control" name='nama<?= $id ?>' value='<?php echo $tampilkan['nama_toko']; ?>' >
        </div>
      </td>
    </tr>
<?php } ?>

  </tbody>  
 </table>
  <input type='submit' class="btn btn-success" value='Ubah Data' name='but_update' onclick="return confirm('Yakin Akan Ubah Data?');">
&nbsp;&nbsp;&nbsp;
  <input type='submit' class="btn btn-danger" value='Hapus Data' name='but_hapus' onclick="return confirm('Yakin Akan Hapus Data?');"> 
</form>
</div>  

</div>


<?php 
        if(isset($_POST['but_update'])){

            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){

                    $nama = addslashes(htmlspecialchars($_POST['nama'.$updateid]));

                    $sqlnya = $koneksi->query("UPDATE tokomo set nama_toko='$nama' where id='$updateid'");
                    
                    
                }
                if ($sqlnya) {
                  echo "<script>alert('data berhasil diubah');</script>";
                  echo "<script>location='toko_mo.php';</script>";  
                  }else{
                    echo "<script>alert('data gagal diubah');</script>";
                    echo "<script>location='toko_mo.php';</script>";
                }
               
            }
            
        }

        if(isset($_POST['but_hapus'])){

            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){

                  
                    $sqlnya = $koneksi->query("DELETE FROM tokomo WHERE id='$updateid'" );
                    
                    
                }
                if ($sqlnya) {
                  echo "<script>alert('data berhasil dihapus');</script>";
                  echo "<script>location='toko_mo.php';</script>";  
                  }else{
                    echo "<script>alert('data gagal dihapus');</script>";
                    echo "<script>location='toko_mo.php';</script>";
                }
               
            }
            
        }
 ?>



          </div>


        <!-- </div> -->
                                
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

<?php include"settingdatatables.php"; ?>
<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_surat_manual').DataTable({
        "lengthMenu": [[25, 50, -1], [25, 50, "All"]]
});
} );
</script>
</body>

</html>

                                                    