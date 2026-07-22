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

  <title>Admin Pusat | WNJ CORP</title>

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
            <h1 class="h3 mb-0 text-gray-800"><strong>Data PO Mitra</strong></h1>           
          </div>

          <hr>

          <div class="row">
            <a class="btn btn-primary btn-icon-split" href="inputpo.php">
              <span class="texy-white-50 icon"><i class="fas fa-plus"></i></span>
              <span class="text">Tambah PO</span>
            </a>
          </div>

          <br>
          <!-- Content Row -->
          

<div class="table-responsive">
<!--  <center><a class="btn btn-info" href="listpoinvoicemegameli.php">SUMMARY PO MEGA & MELI</a></center><br> -->
<form method="post" action="multipleprint.php" target="_blank">  
<table class="table table-striped" id="tb_multiprint">
                     <thead>
          <tr>       
           <th><input type='checkbox' id='checkAll' > Check</th>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama PO</th>
            <th>Nama CS</th>
            <th>Nama DB</th>
            <th>Sub DB</th>
            <th>Kemitraan</th>
            <!--<th>Nama Keluarga</th>
            <th>Alamat</th>-->
            <th>Status PO</th>
            <th>Invoice</th>
            <th style="text-align: right;"><i class="fas fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
            <?php 
            $idpoproduk=$_GET["id"];
            $datapo=$koneksi->query("SELECT 
        admin_mitra_cs.namacs,admin_mitra.idadmin,admin_mitra.namamitra,poproduk.namapo,mitraagen.namaagen as agen,mitrareseller.namaagen as reseller,mitramarketer.namaagen as marketer,pomitra.invoice,pomitra.status,pomitra.tgl,poproduk.namapo,poproduk.idpoproduk FROM pomitra LEFT JOIN mitraagen on pomitra.idmitraagen=mitraagen.idmitraagen 
              LEFT JOIN mitrareseller on pomitra.idmitrareseller=mitrareseller.idmitrareseller 
              LEFT JOIN mitramarketer on pomitra.idmitramarketer=mitramarketer.idmitramarketer 
              LEFT JOIN admin_mitra on pomitra.idmitra=admin_mitra.idadmin or mitraagen.idadmin=admin_mitra.idadmin 
              or mitrareseller.idadmin=admin_mitra.idadmin or mitramarketer.idadmin=admin_mitra.idadmin 
              INNER JOIN poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
              LEFT JOIN admin_mitra_cs ON (admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                   or mitraagen.idadmin=admin_mitra_cs.idadmin 
                                   or mitrareseller.idadmin=admin_mitra_cs.idadmin
                                   or mitramarketer.idadmin=admin_mitra_cs.idadmin)
              WHERE pomitra.jumlah>0 and (pomitra.idpoproduk=167 or pomitra.idpoproduk=171 or pomitra.idpoproduk=173 or pomitra.idpoproduk=174 or pomitra.idpoproduk=175 or pomitra.idpoproduk=176) 
              GROUP BY pomitra.invoice ORDER BY pomitra.idpoproduk ASC,pomitra.idpomitra DESC");
                            $no=1;
                            while($tampilkan=$datapo->fetch_assoc()){
                              $id = $tampilkan['invoice'];
            ?>
            <tr>
                <!-- <td>
                  <input type="checkbox" class="check-item" name="invoice[]" value="<?php echo $tampilkan['invoice']; ?>">
                </td> -->
            <td><input type='checkbox' name='update[]' value='<?= $id ?>' >
              <input type='hidden' name='invoice<?= $id ?>' value='<?php echo $tampilkan['invoice']; ?>' >
            </td>
              <td>
                  <strong><?php echo $no++; ?></strong>
            </td>     
              <td>
               <i class="fas fa-calendar" style="color: red"></i> <?php echo $tampilkan['tgl']; ?>
              </td>
              <td>
                <?php echo $tampilkan['namapo']; ?>
              </td>
              <td><?= $tampilkan['namacs']; ?></td>
                <td>
               <a href="sumpodb.php?idpoproduk=<?php echo $tampilkan['idpoproduk']; ?>&idadmin=<?php echo $tampilkan['idadmin']; ?>">
                <i class="fas fa-user"></i> <?php echo $tampilkan['namamitra']; ?>
               </a> 
              </td>
                <td>
               <i class="fas fa-users"></i> <?php echo $tampilkan['agen']; ?> <?php echo $tampilkan['reseller']; ?> <?php echo $tampilkan['marketer']; ?>
              </td>
              <td class="align-middle"><?php if($tampilkan['agen']<>''){ echo " 
              <div class='badge bg-info text-white rounded-pill'>Agen</div>";}
                  if($tampilkan['reseller']<>''){ echo "
                  <div class='badge bg-warning text-white rounded-pill'>Reseller</div>";}
                  if($tampilkan['marketer']<>''){ echo "
                  <div class='badge bg-danger text-white rounded-pill'>Marketer</div>";}
                  if($tampilkan['agen']=='' and $tampilkan['reseller']=='' and $tampilkan['marketer']=='' ){ 
                    echo "
                    <div class='badge bg-success text-white rounded-pill'>Distributor</div>";} ?></td>
                <!--<td>
                <?php echo $tampilkan['namapenerima']; ?>
              </td>
              <td>
                <?php echo $tampilkan['alamatpenerima']; ?>
              </td>>-->
              <td>
                <?php if ($tampilkan['status']=='Belum DP'): ?>
                 <div class="badge bg-danger text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>
                
                <?php if ($tampilkan['status']=='Belum Acc DB'): ?>
                 <div class="badge bg-warning text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?> 

                <?php if ($tampilkan['status']=='Sudah Confirm DP'): ?>
                 <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>  
                <?php if ($tampilkan['status']=='Sudah Konfirmasi Pembayaran 1'): ?>
                 <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?> 
                <?php if ($tampilkan['status']=='Sudah Konfirmasi Pembayaran 2'): ?>
                 <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?> 
                <?php if ($tampilkan['status']=='Sudah Konfirmasi Pembayaran 3'): ?>
                 <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>  
              </td>
              <td>
                <a href="detailinvoice.php?invoice=<?php echo $tampilkan['invoice']; ?>&idpoproduk=<?php echo $tampilkan['idpoproduk']; ?>">
                  <?php echo $tampilkan['invoice']; ?>   
                </a>
              </td>
              <td>
              <?php if ($_SESSION["administrator"]["nama"]=='AdminWNJ' or $_SESSION["administrator"]["nama"]=='Master') { ?>
              <form method="post">
              <input type="hidden" value="<?php echo $tampilkan['invoice']; ?>" name="invoice">
              <button class="btn btn-danger" name="reset"><i class="fa fa-times"></i></button>
              </form>
              <?php } ?>  
              </td>                  
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <input type='submit' class="btn btn-success" value='Export Data' name='but_export'>
</form>        
      </div>

      <?php
      if(isset($_POST["reset"])){
  
      $invoice = $_POST['invoice'];

      $query = "DELETE FROM pomitra where invoice='$invoice'";
      $sql = mysqli_query( $koneksi, $query);
      
      echo "<script>alert('Invoice telah di reset');</script>";
      echo "<script>location='listpoinvoice.php?id=$idpoproduk';</script>";
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

<?php include "settingdatatables.php"; ?>

</body>

</html>

                       
                        