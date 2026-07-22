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

<?php $idadmin=$_GET['id']; ?>

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
            <h1 class="h3 mb-0 text-gray-800">Detail Voucher</h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          <div class="row">

<h3><strong>voucher Mitra</strong></h3>
<div class="table-responsive">
<form method="post">
<table class="table table-striped">
                      <thead>
                        <tr>
                          <th><input type='checkbox' id='checkAll' ></th>
                          <th>
                            No
                          </th>
                            <th>
                            Nama Mitra
                          </th>
                          <th>
                            Tanggal
                          </th>
                          <th>
                           Keterangan
                          </th>
                          <th>
                           Masuk
                          </th>
                          <th>
                            Keluar
                          </th>
                          <th>
                            Voucher
                          </th>
                           <th>
                            Opsi
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                           include "koneksi.php";
$no=1;
$datamitra=$koneksi->query("SELECT admin_mitra.namamitra,
                                    voucher.id_voucher,
                                    voucher.tgl,
                                    voucher.transaksi,
                                    voucher.debit,
                                    voucher.credit 
                                    FROM voucher 
                                    inner join admin_mitra ON voucher.idadmin=admin_mitra.idadmin 
                                    where admin_mitra.idadmin='$idadmin' ORDER BY `voucher`.`id_voucher` ASC ");
                     
                          
                        while($tampilkan=$datamitra->fetch_assoc()){
                          $id = $tampilkan['id_voucher'];
                         ?>
                        <tr>
                          <td>
                            <input type='checkbox' name='update[]' value='<?= $id ?>' ></td>
                         
                          <td><?php echo $no++; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['namamitra']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['tgl']; ?>
                          </td>
                          <td>
                            <?php echo nl2br($tampilkan['transaksi']); ?>
                          </td>
                          <td>
                           <?php echo number_format($tampilkan['debit']); ?>
                          </td>
                          <td>
                            <?php echo number_format($tampilkan['credit']); ?>
                          </td>
                          <td>
                            <?php 
                              $finalvoucher = $finalvoucher + $tampilkan['debit'] - $tampilkan['credit'];     
                            echo number_format($finalvoucher); 
                            ?>
                          </td>
                          <td>
                           <a href="editvoucher.php?id_voucher=<?php echo $tampilkan['id_voucher']; ?>">Edit</a>
                          </td>
                        </tr>

<?php 
$jumlah_debit += $tampilkan['debit'];
$jumlah_credit += $tampilkan['credit'];

 ?>

                        <?php } ?>
<?php $jumlah_voucher = $jumlah_debit - $jumlah_credit; ?>                        
                        <tr>
                            <td colspan="5"><strong>SISA Voucher</strong></td>
                            <td><strong>Rp. <?php echo number_format($jumlah_debit); ?></strong></td>
                            <td><strong>Rp. <?php echo number_format($jumlah_credit); ?></strong></td>
                            <td><strong>Rp. <?php echo number_format($jumlah_voucher); ?></strong></td>
                        </tr>    
                      </tbody>
                    </table>
<input type='submit' class="btn btn-danger" value='Hapus Data' name='but_hapus' onclick="return confirm('Yakin Akan Hapus Data?');">                    
 </form>
</div>
 <?php 
        if(isset($_POST['but_hapus'])){

            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){

                  
                     $sqlnya = $koneksi->query("DELETE FROM voucher WHERE id_voucher='$updateid'" );
                    // echo "<script>alert('$updateid');</script>";
                    
                }
                if ($sqlnya) {
                  echo "<script>alert('data berhasil dihapus');</script>";
                  echo "<script>location='detailvoucher.php?nama=$namamitra';</script>";  
                  }else{
                    echo "<script>alert('data gagal dihapus');</script>";
                    echo "<script>location='detailvoucher.php?nama=$namamitra';</script>";
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

                        