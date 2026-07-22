<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$idtoko=$_GET['id'];

  $data_toko=$koneksi->query("SELECT nama_toko
                        FROM tokomo 
                        WHERE id = '$idtoko'
                        ");
  $tampilkan_toko=$data_toko->fetch_assoc();

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
            <h1 class="h3 mb-0 text-gray-800">Stok Toko <strong><?= $tampilkan_toko['nama_toko'] ?></strong></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>


<div class="row">



<div class="table-responsive">
  <form method="post">     
    <table class="table table-bordered" id="tb_surat_manual">
      <thead>
        <tr>
          <th><input type='checkbox' id='checkAll' ></th>
          <th>Nama Produk</th>
          <th>Stok Toko</th>
          <th>Stok Seluruh</th>
          <th>Status</th>
          <th>Opsi</th>
        </tr>
      </thead>
      <tbody>
<?php 
  $no=1;
  $data=$koneksi->query("SELECT stokmo.stok,
                                produk.namaproduk,
                                stokmo.id,
                                stokmo.idproduk
                        FROM stokmo
                        JOIN produk ON produk.idproduk = stokmo.idproduk 
                        WHERE stokmo.idtoko = '$idtoko'
                        GROUP BY produk.idproduk DESC
                        ");
  while($tampilkan=$data->fetch_assoc()){ 
    $id = $tampilkan['id'];

    $idproduk = $tampilkan['idproduk'];

$data_stok=$koneksi->query("SELECT produkmo.stok as stoknya
                            FROM produkmo
                            where produkmo.idproduk='$idproduk'
                            GROUP BY produkmo.idproduk
                            ");
  $tampil_stok=$data_stok->fetch_assoc();                                   
  $sisa = $tampil_stok['stoknya']-$tampilkan['stok'];     
                  ?>
        <tr>
          <td>            
            <input type='checkbox' name='update[]' value='<?= $id ?>' >                
          </td>      
          <td>
            <div class="col-4">  
              <?php echo $tampilkan['namaproduk']; ?>
            </div>
          </td>
          <td>
            <div class="col-4">  
              <?php echo $tampilkan['stok']; ?>
            </div>          
          </td>
          <td>
            <div class="col-4">  
              <?php echo $sisa; ?>
            </div>          
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
            <div class="col-4">
              <input type="number" min="0" name="stok<?=$id;?>" value="<?= $tampilkan['stok']; ?>" class="form-control">
            </div>
          </td>
        </tr>
<?php } ?>

      </tbody>  
    </table>
 <input type='submit' class="btn btn-success" value='Ubah Stok' name='but_save' >

 <a href="stok_mo.php" class="btn btn-warning" style="float: right;"><i class="fa fa-arrow-left"></i> Kembali</a>     
  </form>
</div>  

</div>


          </div>

<?php 
  if(isset($_POST['but_save'])){
date_default_timezone_set('Asia/Jakarta');
$waktu = date("Y-m-d H:i:s");


            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){
$stok = $_POST['stok'.$updateid];


$sqlnya = $koneksi->query("UPDATE stokmo set stok='$stok', 
                              waktu='$waktu' 
                              where id = '$updateid'
                              ");

                }
                if ($sqlnya) {
                  echo "<script>alert('data berhasil disimpan');</script>";
                  echo "<script>location='detail_stok_mo.php?id=$idtoko';</script>";  
                  }else{
                    echo "<script>alert('data gagal disimpan');</script>";
                    echo "<script>location='detail_stok_mo.php?id=$idtoko';</script>";
                }
               

               
            }
            
        }
              
 ?>  

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
        "lengthMenu": [[25, 50, 100], [25, 50, 100]],
          columnDefs: [
    { orderable: false, targets: 0 }
  ]
});
} );
</script>

<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update[]"]').prop('checked',true);
                    }else{
                        $('input[name="update[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update[]"]').click(function(){
                    var total_checkboxes = $('input[name="update[]"]').length;
                    var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll').prop('checked',true);
                    }else{
                        $('#checkAll').prop('checked',false);
                    }
                });
            });
        </script>
</body>

</html>

                                                    