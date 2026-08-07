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
            <h1 class="h3 mb-0 text-gray-800">Stok Mall Online</h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>


                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">List Stok</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div>

  <div class="table-responsive">
   
<form method="post">              
    <div class="form-group">
          <label>Pilih Toko</label>
          <select class="form-control" name="idtoko" id="idtoko" required>
              <option value="" selected>- Pilih Toko -</option>
              <?php
              $datadb=$koneksi->query("SELECT * FROM tokomo ORDER BY nama_toko");
              while($tampilkan=$datadb->fetch_assoc()){
              ?>
          <option value="<?php echo $tampilkan['id']; ?>"><?php echo $tampilkan['nama_toko']; ?></option>
          <?php } ?>                       
          </select>      
    </div>             
        <table class="table table-bordered" id="tb_surat_manual">
            <thead>
                    <tr>

                        <th><input type='checkbox' id='checkAll' ></th>
              <th>Nama Produk</th>
              <th>Stok</th>
              <th>Qty</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                            $datapo=$koneksi->query("SELECT produkmo.id, produk.namaproduk , produkmo.stok, produkmo.idproduk
                          FROM produkmo
                          JOIN produk ON produk.idproduk = produkmo.idproduk
                          WHERE produkmo.stok > 0
                            ");
                            while($tampilkan=$datapo->fetch_assoc()){
                                $id = $tampilkan['id'];
                                $idproduk = $tampilkan['idproduk'];

$data_stok=$koneksi->query("SELECT SUM(stokmo.stok) as stoknya
                            FROM stokmo
                            where stokmo.idproduk='$idproduk'
                            GROUP BY stokmo.idproduk
                            ");
  $tampil_stok=$data_stok->fetch_assoc();                                   
  $sisa = $tampilkan['stok']-$tampil_stok['stoknya'];                                  
                             ?>
                        <tr>
                         <td>
<?php if ($sisa>0): ?>                          
                          <input type='checkbox' name='update[]' value='<?= $id ?>' >
<?php endif ?>                            
                        </td> 
                          <td>
                            <?php echo $tampilkan['namaproduk']; ?>
                          </td>
                          <td>
                           <?php echo $sisa; ?>
                          </td>
                          <td>
<?php if ($sisa<=0): ?>
                            <div class="col-4">
                              <span class="badge bg-danger text-white">Stok Tidak Cukup</span> 
                              </div>             
<?php else: ?>
                            <div class="col-4">
                              <input type="hidden" name="idproduk<?= $id ?>" value="<?= $tampilkan['idproduk']; ?>" class="form-control">
                              <input type="number" name="stok<?= $id ?>" value="0" max="<?= $sisa ?>" min=0 class="form-control">
                            </div>

<?php endif ?>                                                        

                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
 <input type='submit' class="btn btn-success" value='Simpan Stok' name='but_save' >
 <a href="stok_mo.php" class="btn btn-warning" style="float: right;"><i class="fa fa-arrow-left"></i> Kembali</a>        
      </form>                    
</div>                  
                                    </div>
                                </div>
                            </div>
                        </div>           
       

<?php 
  if(isset($_POST['but_save'])){
date_default_timezone_set('Asia/Jakarta');
$waktu = date("Y-m-d H:i:s");

$idtoko = $_POST['idtoko'];

            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){
$stok = $_POST['stok'.$updateid];
$idproduk = $_POST['idproduk'.$updateid];
// echo "<script>alert('$idadmin, $updateid, $progres');</script>";

$ambil=$koneksi->query("SELECT * FROM stokmo WHERE idproduk='$idproduk' and idtoko='$idtoko' ");
$produk=$ambil->num_rows;



if ($produk==0 and $stok>0) {

// echo "<script>alert('Tidak ada $stok $idproduk');</script>";  
                    $sqlnya = $koneksi->query("INSERT INTO stokmo (id,idtoko,idproduk,stok,waktu) 
                VALUES (null,'$idtoko','$idproduk','$stok','$waktu')");
}
if ($produk>0 and $stok>0) {

$sqlnya = $koneksi->query("UPDATE stokmo set stok=stok + '$stok', 
                              waktu='$waktu' 
                              where idtoko='$idtoko'
                              and idproduk = '$idproduk'
                              ");
}

                }
                if ($sqlnya) {
                  echo "<script>alert('data berhasil disimpan');</script>";
                  echo "<script>location='stok_mo.php';</script>";  
                  }else{
                    echo "<script>alert('data gagal disimpan');</script>";
                    echo "<script>location='input_stok_toko.php';</script>";
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

  <!-- Page level plugins -->
  <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

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

                                                    