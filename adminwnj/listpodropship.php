<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}


$id = (int) ($_GET['id'] ?? 0);

  $stmtPo = $koneksi->prepare("SELECT poproduk.idpoproduk,
            poproduk.namapo
        FROM poproduk
        WHERE poproduk.idpoproduk = ?");
  $stmtPo->bind_param('i', $id);
  $stmtPo->execute();
  $poInfo = $stmtPo->get_result()->fetch_assoc();
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
            <h1 class="h3 mb-0 text-gray-800">Dropship<br><?= htmlspecialchars($poInfo['namapo'] ?? '') ?></h1>
            <h1 class="h3 mb-0 text-gray-800"><a href="daftards.php"><span class="fa fa-chevron-left"></span> Kembali</a></h1>

           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>
          <!-- Content Row -->
          <div class="row">
<a href="excel_ds_jawa.php?id=<?= $id ?>" class="btn btn-primary btn-sm">Excel Pulau Jawa</a>
&nbsp;&nbsp;
<a href="excel_ds_ljawa.php?id=<?= $id ?>" class="btn btn-success btn-sm">Excel Luar Pulau Jawa</a>
&nbsp;&nbsp;
<a href="excel_ds_semua.php?id=<?= $id ?>" class="btn btn-info btn-sm">Excel Semua</a>
&nbsp;&nbsp;
<a href="listpodropship_satuan.php?id=<?= $id ?>" class="btn btn-warning btn-sm">Semua Dropship</a>

  <div class="table-responsive">
<table class="table table-striped" id="tblistdropship">
<thead>
					<tr>
					    <th>No</th>
						<th>Nama DB</th>
            <th>Nama Sub DB</th>
                          <th>
                           Invoice
                          </th>
                            <th>
                           List Dropship
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php
                            $stmtDs = $koneksi->prepare("SELECT
                              admin_mitra.namamitra,
                              mitraagen.namaagen as agen,
                              mitrareseller.namaagen as reseller,
                              mitramarketer.namaagen as marketer,
                              pomitra.invoice
                              FROM pomitra
                              LEFT JOIN mitraagen on pomitra.idmitraagen=mitraagen.idmitraagen
                              LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=pomitra.idmitrareseller
                              LEFT JOIN mitramarketer on pomitra.idmitramarketer=mitramarketer.idmitramarketer
                              LEFT JOIN admin_mitra on admin_mitra.idadmin = COALESCE(pomitra.idmitra, mitraagen.idadmin, mitrareseller.idadmin, mitramarketer.idadmin)
                              WHERE pomitra.idpoproduk = ?
                              and pomitra.jumlah>0
                              GROUP BY pomitra.invoice order by admin_mitra.namamitra asc");
                            $stmtDs->bind_param('i', $id);
                            $stmtDs->execute();
                            $dsResult = $stmtDs->get_result();
                            $no = 1;

                            while($tampilkan=$dsResult->fetch_assoc()){
                            ?>
                        <tr>

                         <td>
                             <?php echo $no++; ?>
                        </td>
                           <td>
                           <?php echo htmlspecialchars($tampilkan['namamitra'] ?? ''); ?>
                          </td>
                          <td>
                           <?php echo htmlspecialchars($tampilkan['agen'] ?? ''); ?> <?php echo htmlspecialchars($tampilkan['reseller'] ?? ''); ?> <?php echo htmlspecialchars($tampilkan['marketer'] ?? ''); ?>
                          </td>
                          <td>
                            <?php echo htmlspecialchars($tampilkan['invoice']); ?>
                          </td>
                          <td>
                            <?php if ($id==153): ?>
                              <a href="detaildropship_kolibri.php?id=<?php echo urlencode($tampilkan['invoice']); ?>">Klik Disini</a>
                              <?php else: ?>

                           <a href="detaildropship.php?id=<?php echo urlencode($tampilkan['invoice']); ?>">Klik Disini</a>
                            <?php endif ?>
                            <hr>
                           <a href="formdropship.php?invoice=<?php echo urlencode($tampilkan['invoice']); ?>" class="btn btn-sm btn-primary">Tambah Dropship</a>

                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                            
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

  <?php include "settingdatatables.php"; ?>

</body>

</html>

		                                                