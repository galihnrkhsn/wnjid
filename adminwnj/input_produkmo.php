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

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Input Data Produk Mall Online</h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          
<form method="post">  


<button type="submit" name="but_save" class="btn btn-primary"><i class="fa fa-plus"></i> Import</button> 
<a href="produk_mo.php" class="btn btn-danger" style="float: right;"><i class="fa fa-arrow-left"></i> Kembali</a>      
<br>
<br> 
 <div class="table-responsive" >
    
    <table class="table table-bordered" id="tb_surat_manual">
        <thead>
        <tr>
          <th>Check</th>
          <th>Nama Produk</th>
                </tr>
        </thead>
        <tbody>
              <?php 
             
                $datapo=$koneksi->query("SELECT *
                  FROM produk 
                  WHERE idproduk NOT IN ( SELECT idproduk FROM produkmo)
                  ORDER BY idproduk desc
                  limit 1000
                  ");
                $no=1;
              
                while($tampilkan=$datapo->fetch_assoc()){
                  $id = $tampilkan['idproduk'];
                ?>
                <tr>
        <td><input type='checkbox' name='update[]' value='<?= $id ?>'>
 
                       
        </td>           
                <td>
                  <?= $tampilkan['namaproduk'] ?>
                </td>

                        </tr>
                    
                        <?php } ?>
                      </tbody>
                    </table>
                    
                    </div>
</form>
<?php
        if(isset($_POST['but_save'])){

            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){

$ambil=$koneksi->query("SELECT * FROM produkmo WHERE idproduk='$updateid' ");
$produk=$ambil->num_rows;

// echo "<script>alert('data $produk');</script>";

if ($produk==0) {
                    $sqlnya = $koneksi->query("INSERT INTO produkmo (id,idproduk,stok) 
                VALUES (null,'$updateid',0)");
}

                }
                if ($sqlnya) {
                  echo "<script>alert('data berhasil disimpan');</script>";
                  echo "<script>location='produk_mo.php';</script>";  
                  }else{
                    echo "<script>alert('data gagal disimpan');</script>";
                    echo "<script>location='produk_mo.php';</script>";
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
        "lengthMenu": [[25, 50], [25, 50]]
});
} );
</script>

</body>

</html>

		                    