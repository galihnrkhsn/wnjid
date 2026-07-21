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
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
        
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          <div class="row">
              
              <h3><strong>Pembayaran DP PO</strong></h3>

              <?php
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://app.moota.co/api/v1/bank/{bank_id}/mutation/');;
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Authorization: Bearer MJCwtSWXoyy8KhuDMSN7dSFKtu7IZeYETDaOuLEB1Qj3ANgcsg'
]);
$response = curl_exec($curl);

echo $response;
?>
        
        
	<div class="table-responsive">
				<table class="table table-bordered" id="tbslider">
          <thead>
					<tr>
					    <th>No</th>
					    <th>Opsi</th>
						<th>Nama Mitra</th>
            <th>Nama PO</th>
					    <th>Bank Pengirim</th>
					    <th>Rekening / Nama Pengirim</th>
						<th>Jumlah Transfer</th>
						<th>Metode Pembayaran</th>
						<th>No Order</th>
					    <th>Tanggal TF</th>
					    <th>Waktu</th>
					    
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                            $no=1;
                            $datapo=$koneksi->query("SELECT 
                              admin_mitra.namamitra,
                              
                              poproduk.namapo,
                              popembayaran.invoice,
                              popembayaran.bankpengirim,
                              popembayaran.rekeningpengirim,
                              popembayaran.jmlhtransfer,
                              popembayaran.metodebayar,
                              popembayaran.tgl,
                              popembayaran.waktu 
                              FROM popembayaran 
                              LEFT JOIN pomitra on popembayaran.invoice=pomitra.invoice 
                              LEFT JOIN pomaximus on popembayaran.invoice=pomaximus.invoice 
                              LEFT JOIN admin_mitra on pomitra.idmitra=admin_mitra.idadmin or pomaximus.idadmin= admin_mitra.idadmin
                              
                              INNER JOIN poproduk on popembayaran.idpoproduk=poproduk.idpoproduk 
                              GROUP BY popembayaran.invoice 
                              ORDER BY popembayaran.idpembayaran DESC");
                           
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                             <td>
                             <?php echo $no++; ?>
                        </td>     
                           <td>
                            <form method="post"><input type="hidden" name="invoice" value=<?php echo $tampilkan['invoice']; ?>><button type="submit" class="btn btn-success" name="done">Done</button></form>
                          </td>
                          <!--<td>
                            <?php echo $tampilkan['tglorder']; ?>
                          </td>-->
                           <td>
                           <?php echo $tampilkan['namamitra']; ?>
                           <?php //echo $tampilkan['agen']; ?>
                           <?php //echo $tampilkan['reseller']; ?>
                           <?php //echo $tampilkan['marketer']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['namapo']; ?>
                          </td>
                            <td>
                           <?php echo $tampilkan['bankpengirim']; ?>
                          </td>
                            <td>
                           <?php echo $tampilkan['rekeningpengirim']; ?>
                          </td>
                           <td>
                           <?php echo $tampilkan['jmlhtransfer']; ?>
                          </td>
                            <td>
                           <?php echo $tampilkan['metodebayar']; ?>
                          </td>
                          <td>
                           <a href="detailinvoice.php?invoice=<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['invoice']; ?></a>
                          </td>
                          <td>
                           <?php echo $tampilkan['tgl']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['waktu']; ?>
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                    
                        
                         <?php
                        if(isset($_POST["done"])){
	
	                                 include "koneksi.php";
					               $invoice= $_POST['invoice'];
					               $koneksi->query("update pomitra set status='Proses' where invoice='$invoice';");
        		                   	echo "<script>alert('data sudah terupdate');</script>";
        		                   	echo "<script>location='listpopembayaran.php';</script>";
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
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>
<?php include "settingdatatables.php" ?>

</body>

</html>

		                                                