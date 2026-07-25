<?php 
session_start();

include 'koneksi.php'; 


$invoice=$_GET['id'];
$datapo=$koneksi->query("SELECT poproduk.namapo,poproduk.idpoproduk, popembayaran.jenis, admin_mitra.namamitra, admin_mitra_cs.namacs, admin_mitra.idadmin
                            FROM popembayaran
                            JOIN poproduk on poproduk.idpoproduk = popembayaran.idpoproduk
                            JOIN pomitra on pomitra.invoice = popembayaran.invoice
                            JOIN admin_mitra on admin_mitra.idadmin = pomitra.idmitra
                            JOIN admin_mitra_cs on admin_mitra_cs.idadmin = pomitra.idmitra
                            where popembayaran.invoice='$invoice'
                            LIMIT 1
                            ");
$tampilpo=$datapo->fetch_assoc(); 
$nama = $tampilpo['namapo'];
$jenis = $tampilpo['jenis'];
$idpoproduk = $tampilpo['idpoproduk'];

?>
<?php
// header("Content-type: application/vnd-ms-excel");
// header("Content-Disposition: attachment; filename=Pembayaran PO $nama.xls");
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
<style type="text/css">
   body{
      padding-right: 0px ! important;
   }  
</style>
<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

  <?php include "sidebar.php"; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
        
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          <div class="row">
              
                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Detail Pembayaran <?= $tampilpo['namapo']; ?></h6>
                                    <?php if ($tampilpo['idpoproduk'] == '343') : ?>
                                      <h6><strong><a href="popembayarankonin.php?id=<?= $tampilpo['idpoproduk']; ?>"><span class="fa fa-chevron-left"></span> Kembali</a></strong></h6>
                                    <?php else : ?>
                                      <h6><strong><a href="popembayaran.php?id=<?= $tampilpo['idpoproduk']; ?>"><span class="fa fa-chevron-left"></span> Kembali</a></strong></h6>
                                    <?php endif; ?>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body mb-4">
                                    <div>
                                      <h3><?php   echo $invoice; ?></h3>         
                                      <table>
                                        <tr>
                                          <td>Mitra</td>
                                          <td>:</td>
                                          <td><?= $tampilpo['namamitra']; ?> (<?= $tampilpo['idadmin']; ?>)</td>
                                        </tr>
                                        <tr>
                                          <td>CS</td>
                                          <td>:</td>
                                          <td><?= $tampilpo['namacs']; ?></td>
                                        </tr>
                                      </table> 
                                      <div class="row">
                                      <?php 
                                        $datapo=$koneksi->query("SELECT * FROM buktitf WHERE invoice = '$invoice'");
                                          while($tampilkan=$datapo->fetch_assoc()){
                                          $keterangan = $tampilkan['ket'];
                                          $result_explode = explode('-', $keterangan);
                                          $bank_pengirim=$result_explode[0]; 
                                          $rek_pengirim=$result_explode[1];
                                          $rek_tujuan=$result_explode[2];
                                          $jumlahtf=$result_explode[3];                                              
                                      ?>                                      
                                          <div class="card" style="width: 500px; margin-left: 2%; margin-top: 2%;">
                                            <?php if ($tampilpo['idpoproduk'] == '343' || $tampilpo['idpoproduk'] == '494') : ?>
                                              <a href="../image/buktikonin/<?= $tampilkan['gambar']; ?>" target="blank_()">
                                                <img src="../image/buktikonin/<?= $tampilkan['gambar']; ?>" class="card-img-top" alt="..." style="object-fit: contain;width: 100%;height: 300px;">
                                              </a>
                                            <?php else : ?>
                                              <a href="../image/bukti/<?= $tampilkan['gambar']; ?>" target="blank_()">
                                                <img src="../image/bukti/<?= $tampilkan['gambar']; ?>" class="card-img-top" alt="..." style="object-fit: contain;width: 100%;height: 300px;">
                                              </a>
                                            <?php endif; ?>
                                            <div class="card-body">
                                              <p class="card-text">
                                                <table>
                                                  <tr>
                                                    <th colspan="3">Data Transfer (<?= $tampilkan['jenis']; ?>)</th>
                                                  </tr>
                                                  <tr>
                                                    <td>Bank Pengirim</td>
                                                    <td>:</td>
                                                    <td><?= $bank_pengirim; ?></td>                                              
                                                  </tr>
                                                  <tr>
                                                    <td>Rek. Pengirim</td>
                                                    <td>:</td>
                                                    <td><?= $rek_pengirim; ?></td>                                              
                                                  </tr>
                                                  <tr>
                                                    <td>Tujuan</td>
                                                    <td>:</td>
                                                    <td><?= $rek_tujuan; ?></td>                                              
                                                  </tr>
                                                  <tr>
                                                    <td>Jumlah Transfer</td>
                                                    <td>:</td>
                                                    <td><?= $jumlahtf; ?></td>                                              
                                                  </tr>
                                                </table>
                                                
                                                  
                                                </p>
                                            </div>
                                          </div>                                  
                                      <?php } ?>
                                      </div>
                                    </div>
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

  <!-- Page level plugins -->
  <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>
