<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}
$id = $_GET['id'];
$idpoproduk = $_GET['id'];

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
            <h1 class="h3 mb-0 text-gray-800">Produk PO </h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          <div class="">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Kategori PO</a></li>
          <li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Detail PO</a></li>
        </ul>

    <div class="tab-content">
    <div id="home" class="tab-pane fade show active" role="tabpanel"> 
<div class="table-responsive">
<form method="post">    
<table class="table" id="tb_price_list">
                      <thead>
                        <tr>
                        <th>No</th>
                        <th><input type='checkbox' id='checkAll' ></th>
                        <th>Nama Variant</th>
                        <th>Opsi Variant</th>
                        <th>Stok</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                          include "koneksi.php";
                          $datapo=$koneksi->query("SELECT * from pokategori where idpoproduk = '$id' order by idpo desc");
                            $no=1;
                           
                        while($tampilkan=$datapo->fetch_assoc()){
                          $idpo = $tampilkan['idpo'];
                         ?>
                        <tr>
                          <td><?= $no++; ?></td>
                          <td><input type='checkbox' name='update[]' value='<?= $idpo ?>' ></td>
                          <td><?= $tampilkan['namakategori']; ?></td>
                          <td>
                            <input type="text" name="namakategori<?= $idpo ?>" value="<?= $tampilkan['namakategori'] ?>" class="form-control">                            
                          </td>
                          <td>
                            <input type="number" name="stok<?= $idpo ?>" value="<?= $tampilkan['stok'] ?>" class="form-control">
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
<div class="mt-4">                    
                    <input type='submit' class="btn btn-success" value='Ubah Data' name='but_update' onclick="return confirm('Yakin Akan Ubah Data?');">
                    &nbsp;&nbsp;&nbsp;
                    <input type='submit' class="btn btn-danger" value='Hapus Data' name='but_hapus' onclick="return confirm('Yakin Akan Hapus Data?');">     
                    <a href="produkpo.php" class="btn btn-info float-right" >Kembali</a>               
</div>
</form>
</div>
<?php 
        if(isset($_POST['but_update'])){

            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){

                    $namakategori = $_POST['namakategori'.$updateid];
                    $stok = $_POST['stok'.$updateid];

                    $sqlnya = $koneksi->query("UPDATE pokategori set namakategori='$namakategori',stok='$stok' where idpo='$updateid'");
                    
                    
                }
                if ($sqlnya) {
                  echo "<script>alert('data berhasil diubah');</script>";
                  echo "<script>location='produkpo_kategori.php?id=$idpoproduk';</script>";  
                  }else{
                    echo "<script>alert('data gagal diubah');</script>";
                    echo "<script>location='produkpo_kategori.php?id=$idpoproduk';</script>";
                }
               
            }
            
        }

        if(isset($_POST['but_hapus'])){

            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){

                  
                    $sqlnya = $koneksi->query("DELETE FROM pokategori WHERE idpo='$updateid'" );
                    
                    
                }
                if ($sqlnya) {
                  echo "<script>alert('data berhasil dihapus');</script>";
                  echo "<script>location='produkpo_kategori.php?id=$idpoproduk';</script>";  
                  }else{
                    echo "<script>alert('data gagal dihapus');</script>";
                    echo "<script>location='produkpo_kategori.php?id=$idpoproduk';</script>";
                }
               
            }
            
        }
        ?>        
</div>
                    
      <div id="menu1" class="tab-pane fade">

<div class="table-responsive">
<form method="post">    
<table class="table" id="tb_po_variant">
                      <thead>
                        <tr>
                        <th>No</th>
                        <th><input type='checkbox' id='checkAll_idpodetail' ></th>
                        <th>Nama Variant</th>
                        <th>Opsi Variant</th>
                        <th>harga</th>
                        <th>Kategori</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                          include "koneksi.php";
                          $datapo_variant=$koneksi->query("SELECT * from podetail 
                                                  join pokategori on pokategori.idpo = podetail.idpo
                                                  where pokategori.idpoproduk = '$id' order by podetail.idpodetail desc");
                            $no=1;
                           
                        while($tampilkan_variant=$datapo_variant->fetch_assoc()){
                          $idpodetail = $tampilkan_variant['idpodetail'];
                         ?>
                        <tr>
                          <td><?php echo $no++; ?></td>
                          <td><input type='checkbox' name='update_idpodetail[]' value='<?= $idpodetail ?>' ></td>
                          <td><?= $tampilkan_variant['variant'] ?></td>
                          <td>
                            <input type="text" name="variant<?= $idpodetail ?>" value="<?= $tampilkan_variant['variant'] ?>" class="form-control">                            
                          </td>
                          <td>
                            <input type="number" name="harga<?= $idpodetail ?>" value="<?= $tampilkan_variant['harga'] ?>" class="form-control">
                          </td>
                          <td>
                            <select class="form-control" name="idpo<?= $idpodetail ?>">
                              <option value="<?= $tampilkan_variant['idpo']; ?>"><?= $tampilkan_variant['namakategori']; ?></option>
                              <?php 
                        $data_po=$koneksi->query("SELECT * from pokategori where idpoproduk = '$id' order by idpo desc");                          
                        while($tampilkan_kategori=$data_po->fetch_assoc()){
                               ?>
                              <option value="<?= $tampilkan_kategori['idpo']; ?>"><?= $tampilkan_kategori['namakategori']; ?></option>
                              <?php } ?>                              
                            </select>
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
<div class="mt-4">                    
                    <input type='submit' class="btn btn-success" value='Ubah Data' name='detail_update' onclick="return confirm('Yakin Akan Ubah Data?');">
                    &nbsp;&nbsp;&nbsp;
                    <input type='submit' class="btn btn-danger" value='Hapus Data' name='detail_hapus' onclick="return confirm('Yakin Akan Hapus Data?');">     
                    <a href="produkpo.php" class="btn btn-info float-right" >Kembali</a>
</div>
</form>
<?php 
        if(isset($_POST['detail_update'])){

            if(isset($_POST['update_idpodetail'])){
                foreach($_POST['update_idpodetail'] as $updateid){

                    $variant = $_POST['variant'.$updateid];
                    $harga = $_POST['harga'.$updateid];
                    $idpo = $_POST['idpo'.$updateid];

                    $sqlnya = $koneksi->query("UPDATE podetail set variant='$variant',harga='$harga',idpo='$idpo' where idpodetail='$updateid'");
                    
                    
                }
                if ($sqlnya) {
                  echo "<script>alert('data berhasil diubah');</script>";
                  echo "<script>location='produkpo_kategori.php?id=$idpoproduk';</script>";  
                  }else{
                    echo "<script>alert('data gagal diubah');</script>";
                    echo "<script>location='produkpo_kategori.php?id=$idpoproduk';</script>";
                }
               
            }
            
        }

        if(isset($_POST['detail_hapus'])){

            if(isset($_POST['update_idpodetail'])){
                foreach($_POST['update_idpodetail'] as $updateid){

                  
                    $sqlnya = $koneksi->query("DELETE FROM podetail WHERE idpodetail='$updateid'" );
                    
                    
                }
                if ($sqlnya) {
                  echo "<script>alert('data berhasil dihapus');</script>";
                  echo "<script>location='produkpo_kategori.php?id=$idpoproduk';</script>";  
                  }else{
                    echo "<script>alert('data gagal dihapus');</script>";
                    echo "<script>location='produkpo_kategori.php?id=$idpoproduk';</script>";
                }
               
            }
            
        }
        ?>  

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

<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll_idpodetail').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update_idpodetail[]"]').prop('checked',true);
                    }else{
                        $('input[name="update_idpodetail[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update_idpodetail[]"]').click(function(){
                    var total_checkboxes = $('input[name="update_idpodetail[]"]').length;
                    var total_checkboxes_checked = $('input[name="update_idpodetail[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll_idpodetail').prop('checked',true);
                    }else{
                        $('#checkAll_idpodetail').prop('checked',false);
                    }
                });
            });
        </script> 

  <?php include "settingdatatables.php"; ?>

</body>

</html>

		                    