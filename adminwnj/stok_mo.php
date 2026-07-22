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
 
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">


        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Stok Mall Online</h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>


<div class="">
<a href="input_stok_toko.php" class="btn btn-primary mb-5"><i class="fa fa-plus"></i> Tambah Data</a> 

        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Stok Per Toko</a></li>
          <li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Stok Per Produk</a></li>
        </ul>

    <div class="tab-content">
      <div id="home" class="tab-pane fade show active"  role="tabpanel"> 

        <div class="table-responsive">
         <table class="table table-bordered" id="tb_surat_manual">
          <thead>
            <tr>
              <th>Nama Toko</th>
              <th>Stok</th>
            </tr>
          </thead>
          <tbody>
        <?php 
          $data=$koneksi->query("SELECT tokomo.nama_toko,tokomo.id,
                                        SUM(stokmo.stok) as stok
                                FROM stokmo
                                RIGHT JOIN tokomo ON tokomo.id = stokmo.idtoko 
                                GROUP BY tokomo.id
                                ");
          while($tampilkan=$data->fetch_assoc()){ 
                          ?>
            <tr>
              <td>
                <div class="col-4">  
                  <?php echo $tampilkan['nama_toko']; ?>
                </div>
              </td>
              <td>
                <a href="detail_stok_mo.php?id=<?= $tampilkan['id']; ?>">
                <?php echo $tampilkan['stok']; ?>
                </a>
              </td>
            </tr>
        <?php } ?>

          </tbody>  
         </table>
        </div>  

      </div>
      <div id="menu1" class="tab-pane fade">
      
        <div class="table-responsive">
         <table class="table table-bordered" id="tb_surat_manual1">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama Produk</th>
              <th>Stok Produk</th>
              <th>Stok Toko</th>
              <th>Status</th>
              <th>Daftar Toko</th>
            </tr>
          </thead>
          <tbody>
        <?php 
          $no=1;
          $data_produk=$koneksi->query("SELECT produk.namaproduk,
                                        produkmo.stok as stokmo,
                                        SUM(stokmo.stok) as stok,
                                        produkmo.idproduk
                                FROM produkmo
                                JOIN produk ON produk.idproduk = produkmo.idproduk
                                LEFT JOIN stokmo ON produkmo.idproduk = stokmo.idproduk 
                                GROUP BY produkmo.idproduk
                                ");
          while($tampilkan_produk=$data_produk->fetch_assoc()){ 
            $idproduk = $tampilkan_produk['idproduk'];
  $sisa = $tampilkan_produk['stokmo']-$tampilkan_produk['stok'];              
                          ?>
            <tr>
              <td style="width: 1%"><?= $no++; ?></td>
              <td>
                  <?php echo $tampilkan_produk['namaproduk']; ?>

              </td>
              <td>
                <?php echo $tampilkan_produk['stokmo']; ?>
              </td>
              <td>
                <?php echo number_format($tampilkan_produk['stok']); ?>
              </td>  
              <td>
<?php if ($sisa==0): ?>                          
            <span class="badge bg-success text-white">Sesuai</span>
  <?php elseif($sisa>0): ?>                     
            <span class="badge bg-info text-white">Ada Stok</span>
    <?php elseif($sisa<0): ?>                     
            <span class="badge bg-danger text-white">Stok Habis</span>

<?php endif ?>                  
              </td>  
              <td>
<table style="border: 0px">
<?php 
          $data_toko=$koneksi->query("SELECT tokomo.nama_toko, tokomo.id, stokmo.stok
                                FROM tokomo
                                JOIN stokmo ON stokmo.idtoko = tokomo.id
                                WHERE stokmo.idproduk =  '$idproduk'
                                GROUP BY stokmo.idtoko
                                ");
          while($tampilkan_toko=$data_toko->fetch_assoc()){ 

 ?>
  <tr style="border: 0px">
    <td style="border: 0px"><a href="detail_stok_mo.php?id=<?= $tampilkan_toko['id'] ?>"><?= $tampilkan_toko['nama_toko'] ?></a></td>
    <td style="border: 0px"><?= $tampilkan_toko['stok'] ?></td>
  </tr>
<?php } ?>
</table> 
              </td>          
            </tr>
        <?php } ?>

          </tbody>  
         </table>
        </div>       

      </div>
    </div>



</div>




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
  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
  <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

<?php include "settingdatatables.php"; ?>
<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_surat_manual').DataTable({
        "lengthMenu": [[25, 50, -1], [25, 50, "All"]]
});
} );
</script>
<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_surat_manual1').DataTable({
        "lengthMenu": [[25, 50, -1], [25, 50, "All"]]
});
} );
</script>
</body>

</html>

                                                    