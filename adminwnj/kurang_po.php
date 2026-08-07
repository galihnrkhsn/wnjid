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

  $sql = "SELECT namapo FROM poproduk WHERE idpoproduk='$id' ";
  $query = $koneksi->query($sql);
  $datapo = $query->fetch_assoc();

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
  
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>  

</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

 <?php include "sidebar.php"; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

   <h3><strong>Data <?= $datapo['namapo'] ?></strong></h3><br>
<br>
<form method="post">
    <div class="col-6">
      <label>Pilih Variant</label>
      <select class="form-control" name="idpodetail"  id="theSelect2"  required>
    <?php 
      $datavariant=$koneksi->query("SELECT podetail.idpodetail, podetail.variant 
                                    FROM podetail
                                    JOIN pokategori ON podetail.idpo = pokategori.idpo
                                    WHERE pokategori.idpoproduk = '$id'
                                  ");
                                while($tampilkan=$datavariant->fetch_assoc()){
     ?>    
        <option value="<?= $tampilkan['idpodetail'] ?>">
          <?= $tampilkan['variant'] ?>
        </option>
    <?php } ?>    
      </select>
    </div>
  <script>
    $("#theSelect2").select2();
  </script> 
    <div class="col-6 mt-4">
      <label>Pilih Status</label>
      <select class="form-control" name="status" required>
        <option>
          Belum Ambil Barang
        </option>      
        <option>
          Ambil Barang
        </option>
        <option>
          Checker
        </option>
      
      </select>

      <button type="submit" name="cari" class="btn btn-primary btn-sm mt-3">Cari</button>
    </div>
</form> 

<?php 

if(isset($_POST['cari'])) {
  $idpodetail = $_POST['idpodetail'];
  $status = $_POST['status'];

  $sql_variant = "SELECT variant FROM podetail WHERE idpodetail='$idpodetail' ";
  $query_variant = $koneksi->query($sql_variant);
  $datapo_variant = $query_variant->fetch_assoc();

 ?>

<h2 class="mt-4"><?= $datapo_variant['variant'] ?></h2>
<div class="table-responsive">
  <a href="excel_kurang.php?id=<?=$idpodetail;  ?>&status=<?= $status ?>" class="btn btn-success btn-sm" target="_blank">Excel</a>
  <table class="table table-striped" id="tb_listpo_artikel">
                      <thead>
                        <tr>
                            <th style="width:1%">
                             No
                            </th>  
                          <th>
                            Invoce  
                          </th> 
                           <th>
                            Progres
                          </th>
                          <th>Nama DB</th>
                          <th>Nama Sub-DB</th>
                          <th>Nama CS</th>                            
<?php if ($status=="Checker"): ?>                            
                          <th>Surat Jalan</th>   
<?php endif ?>                          
                        </tr>
                      </thead>
                      <tbody>
                          <?php 

if ($status=="Belum Ambil Barang") {
                            $data_ambil=$koneksi->query("SELECT invoice, jumlah as progres
                              FROM pomitra
                              WHERE idpodetail = '$idpodetail'
                              and jumlah > 0
                              ORDER BY invoice asc
                              ");
  }else{
                            $data_ambil=$koneksi->query("SELECT invoice, progres
                              FROM surat_jalan_po
                              WHERE idpodetail = '$idpodetail'
                              AND status = '$status'
                              ORDER BY invoice asc
                              ");    
  }                          

                            $no=1;
                          
                            while($tampilkan_ambil=$data_ambil->fetch_assoc()){
$invoice = $tampilkan_ambil['invoice'];                              
 $mitra=$koneksi->query("
 SELECT admin_mitra.namamitra,
        admin_mitra.idadmin,
        mitraagen.namaagen as agen,
        mitraagen.idmitraagen,
        mitrareseller.namaagen as reseller,
        mitrareseller.idmitrareseller,
        mitramarketer.namaagen as marketer,
        mitramarketer.idmitramarketer,
        admin_mitra_cs.namacs
      FROM pomitra 
      inner join podetail on podetail.idpodetail=pomitra.idpodetail
      LEFT JOIN mitraagen ON pomitra.idmitraagen=mitraagen.idmitraagen
      LEFT JOIN mitrareseller ON pomitra.idmitrareseller=mitrareseller.idmitrareseller
      LEFT JOIN mitramarketer ON pomitra.idmitramarketer=mitramarketer.idmitramarketer
      LEFT JOIN admin_mitra ON (pomitra.idmitra=admin_mitra.idadmin 
                            or mitraagen.idadmin=admin_mitra.idadmin 
                            or mitrareseller.idadmin=admin_mitra.idadmin
                            or mitramarketer.idadmin=admin_mitra.idadmin)
      LEFT JOIN admin_mitra_cs ON (admin_mitra.idadmin = admin_mitra_cs.idadmin 
                            or mitraagen.idadmin=admin_mitra_cs.idadmin 
                            or mitrareseller.idadmin=admin_mitra_cs.idadmin
                            or mitramarketer.idadmin=admin_mitra_cs.idadmin)
    WHERE pomitra.invoice='$invoice'
 ");
 $namamitra=$mitra->fetch_assoc();  


if ($status=="Belum Ambil Barang") {
$datamitra=$koneksi->query("
  SELECT SUM(surat_jalan_po.progres) as progresnya 
  FROM surat_jalan_po
 where surat_jalan_po.invoice='$invoice'
 and surat_jalan_po.idpodetail = '$idpodetail'
 ");
 $tampilprogres=$datamitra->fetch_assoc();    
 $total += $tampilkan_ambil['progres']-$tampilprogres['progresnya'];   
 }else{
  $total += $tampilkan_ambil['progres']; 
 }                         
                            ?>
                        <tr>
                         
                          <td>
                             <?php echo $no++; ?>
                          </td>    
                          <td>
                           <?= $tampilkan_ambil['invoice'] ?>
                          </td>
                          <td>
<?php if ($status=="Belum Ambil Barang"): ?>
                            <?= $tampilkan_ambil['progres']-$tampilprogres['progresnya']; ?>
<?php else: ?>
                            <?= $tampilkan_ambil['progres'] ?>                            
<?php endif ?>                          
                           
                          </td>
                          <td><?php echo $namamitra['namamitra']; ?></td>
                          <td>
                            
                            <?php echo $namamitra['agen']; ?>
                            <?php echo $namamitra['reseller']; ?>
                            <?php echo $namamitra['marketer']; ?>
                          </td>
                          <td>

                            <?php echo $namamitra['namacs']; ?>
                          </td>                           
<?php if ($status=="Checker"): ?>                 
                          <td>
            <?php 
            $data_invoice_sj=$koneksi->query("SELECT surat_jalan_po.no_sj
                                            
                                            FROM surat_jalan_po
                                            
                                            WHERE surat_jalan_po.invoice = '$invoice'
                                            GROUP BY surat_jalan_po.invoice

                                    ");
                            while($tampilkan_invoice_sj=$data_invoice_sj->fetch_assoc()){
                              echo $tampilkan_invoice_sj['no_sj'];
                              echo "<br>";
                            }
            ?>                            
                          </td>
<?php endif ?>               
                        </tr>                     
                        <?php } ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2">Total</td>
                          <td><?= $total; ?></td>
                        </tr>
                      </tfoot>
  </table>
</div>         
 
<?php 
}
 ?>                        
    

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
          <a class="btn btn-primary" href="login.php">Logout</a>
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
  
</body>

</html>

                                                      