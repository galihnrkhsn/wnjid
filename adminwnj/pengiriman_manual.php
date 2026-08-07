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
          
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
        
              
              <h3><strong>Pengiriman Ongkir Manual</strong></h3>
              
                    
                    	
                    	    	<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="tb_ongkirmanual">
          <thead>
					<tr>
					    <th>Action</th>
					    <th>No Order</th>
						<th>Data Pengirim</th>
					    <th>Data Penerima</th>
					    <th>Ekspedisi / Layanan</th>
					    <th>Ongkir</th>
					    <th>Alamat</th>
					    <th>Lokasi</th>
					    
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
date_default_timezone_set('Asia/Jakarta');
// ==============Set Tanggal +1 hari==============
  $jumlahhari='-60 days'; 
$tgl1 = date('Y-m-d');
$tgl2 = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1)));                
                            $datapo=$koneksi->query("SELECT *,LEFT(invoice,1) as hurufdepan FROM orderpengiriman 
                                                      where orderpengiriman.ongkir=0 
                                                      and orderpengiriman.tgl>'$tgl2'
                                                      order by orderpengiriman.idorderp DESC limit 1000 ");
                            
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                      </td>    
                          <td>
                            <a href="updateongkir.php?id=<?php echo $tampilkan['idorderp'] ?>" class="btn btn-primary" target="blank">+Ongkir</a>
                          </td>   
                          <td>
                            
                            <?php
                            if( $tampilkan['hurufdepan']=='A'){  ?>
                            <a href="detailorderagen.php?invoice=<?php echo $tampilkan['invoice']; ?>" target="blank()">
                          <?php echo $tampilkan['invoice'];
                           ?>
                            </a>
                          <?php } ?>
                           <?php
                            if( $tampilkan['hurufdepan']=='R'){  ?>
                            <a href="detailorderreseller.php?invoice=<?php echo $tampilkan['invoice']; ?>" target="blank()">
                          <?php echo $tampilkan['invoice'];
                           ?>
                            </a>
                          <?php } ?>
                           <?php
                            if( $tampilkan['hurufdepan']=='M'){  ?>
                            <a href="detailordermarketer.php?invoice=<?php echo $tampilkan['invoice']; ?>" target="blank()">
                          <?php echo $tampilkan['invoice'];
                           ?>
                            </a>
                          <?php } ?>
                            <?php
                            if( $tampilkan['hurufdepan']<>'M' and $tampilkan['hurufdepan']<>'A' and $tampilkan['hurufdepan']<>'R'){  ?>
                            <a href="detailorder.php?invoice=<?php echo $tampilkan['invoice']; ?>" target="blank()">
                          <?php echo $tampilkan['invoice'];
                           ?>
                            </a>
                          <?php } ?>
                          </td>
                           <td>
                           <?php echo $tampilkan['namapengirim']; ?>, <?php echo $tampilkan['tlppengirim']; ?>
                          </td>
                            <td>
                           <?php echo $tampilkan['namapenerima']; ?>, <?php echo $tampilkan['tlppenerima']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['ekspedisi']; ?> / <?php echo $tampilkan['layanan']; ?>
                          </td>
                          <td>
                           <?php if($tampilkan['ongkir']=='0') { 
                           echo "<a href='updateongkir.php?id=$tampilkan[idorderp]'>$tampilkan[ongkir]</a>";
                           }else {
                           echo "$tampilkan[ongkir]";
                           } 
                           ?>
                          </td>
                           <td>
                           <?php echo $tampilkan['alamat']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['provinsi']; ?>, <?php echo $tampilkan['kota']; ?>, <?php echo $tampilkan['kecamatan']; ?>
                          </td>
							
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script src="assets/dist/js/jquery.min.js"></script>
    <script src="assets/dist/js/bootstrap.min.js"></script>
    <script src="assets/dist/DataTables/datatables.min.js"></script>
  
 <script type="text/javascript">
        $(document).ready( function () {
    $('#tb_pengiriman').DataTable();
} );
</script>
<script type="text/javascript">
        $(document).ready( function () {
    $('#tb_ongkirmanual').DataTable();
} );
</script>
</body>

</html>

		                                                