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
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          <div class="row">


<h3><strong>Pricelist</strong></h3>
<div class="table-responsive">
<form method="post">   
<table class="table table-striped" id="tb_price_list">
                      <thead>
                        <tr>
                          <th>
                            No
                          </th>
                          <th><input type='checkbox' id='checkAll' > Check</th>
                          <th>
                            Nama Artikel
                          </th>
                          <th>
                            Harga Konsumen
                          </th>
                          <th>
                           Harga Distributor
                          </th>
                          <th>
                           Harga Agen
                          </th>
                          <th>
                           Harga Reseller
                          </th>
                          <th>
                           Harga Marketer
                          </th>
                          <!-- <th>
                           Opsi
                          </th> -->
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                             
                            
                             $tampil =$koneksi->query("SELECT * FROM pricelist order by idharga desc");
                               $no=1;     
                                 //}
                            while($tampilMas=$tampil->fetch_assoc()){
                              $id = $tampilMas['idharga'];
                        ?>
                        <tr>
                         <td>
                            <?php echo $no++; ?>
                          </td>
                          <td><input type='checkbox' name='update[]' value='<?= $id ?>' ></td>
                          <td>
                            <p style="display: none"><?php echo $tampilMas['namaartikel']; ?></p>
                            <input type='text' name='fname_<?= $id ?>' value='<?php echo $tampilMas['namaartikel']; ?>' >
                            
                          </td>
                          <td>
                            <p style="display: none"><?php echo $tampilMas['harga_ecer_d']; ?></p>
                            <input type='number' min="0" name='fharga_<?= $id ?>' value='<?php echo $tampilMas['harga_ecer_d']; ?>' >
                          </td>
                          <td>
                           <?php echo $tampilMas['harga_d']; ?>
                          </td>
                          <td>
                            <?php echo $tampilMas['harga_a']; ?>
                          </td>
                          <td>
                            <?php echo $tampilMas['harga_ecer_a']; ?>
                          </td>
                          <td>
                            <?php echo $tampilMas['harga_marketer']; ?>
                          </td>
                          <!-- <td>
                             
                            <input type="hidden" name="id" value=<?php echo $tampilMas['idharga']; ?>>
                            <button class="btn btn-danger" name="hapus"><span class="fa fa-trash"></span></button>
                            <a href="editpricelist.php?idharga=<?php echo $tampilMas['idharga']; ?>" class="btn btn-success"><span class="fa fa-edit"></span></a>
                            
                          </td> -->
                        </tr>
                        
                        <?php } ?>
                      </tbody>
                    </table>
                    <input type='submit' class="btn btn-success" value='Ubah Data' name='but_update' onclick="return confirm('Yakin Akan Ubah Data?');">
                    &nbsp;&nbsp;&nbsp;
                    <input type='submit' class="btn btn-danger" value='Hapus Data' name='but_hapus' onclick="return confirm('Yakin Akan Hapus Data?');">
                    <br><br>
</form>                    
       </div>             
                    
        <?php 
        if(isset($_POST['but_update'])){

            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){

                    $fname = $_POST['fname_'.$updateid];
                    $fharga_ = $_POST['fharga_'.$updateid];
                    $harga_d = $fharga_*65/100;
                    $harga_a = $fharga_*75/100;
                    $harga_ecer_a = $fharga_*85/100;
                    $harga_marketer = $fharga_*90/100;

                    $sqlnya = $koneksi->query("UPDATE pricelist set namaartikel='$fname',harga_ecer_d='$fharga_',harga_d='$harga_d',harga_a='$harga_a',harga_ecer_a='$harga_ecer_a',harga_marketer='$harga_marketer' where idharga='$updateid'");
                    
                    
                }
                if ($sqlnya) {
                  echo "<script>alert('data berhasil diubah');</script>";
                  echo "<script>location='pricelist-artikel.php';</script>";  
                  }else{
                    echo "<script>alert('data gagal diubah');</script>";
                    echo "<script>location='pricelist-artikel.php';</script>";
                }
               
            }
            
        }

        if(isset($_POST['but_hapus'])){

            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){

                  
                    $sqlnya = $koneksi->query("DELETE FROM pricelist WHERE idharga='$updateid'" );
                    
                    
                }
                if ($sqlnya) {
                  echo "<script>alert('data berhasil dihapus');</script>";
                  echo "<script>location='pricelist-artikel.php';</script>";  
                  }else{
                    echo "<script>alert('data gagal dihapus');</script>";
                    echo "<script>location='pricelist-artikel.php';</script>";
                }
               
            }
            
        }
        ?>                    
                
    
    <?php                
    if(isset($_POST["hapus"])){
         
             $id = $_POST['id'];
            $koneksi->query("DELETE FROM pricelist WHERE idharga='$id'" );
            echo "<script>alert('data berhasil dihapus');</script>";
		    echo "<script>location='index.php?page=pricelist';</script>";


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

<?php include 'settingdatatables.php'; ?>
</body>

</html>

		                        