<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$sum=0;
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
            <h1 class="h3 mb-0 text-gray-800"><a href="listpoinvoicemegameli.php">
              <- Kembali</a></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          
<?php
$id=$_GET["id"];
$invoice=$_GET["invoice"];
$kode=substr($invoice, 0,1);
?>
<center><h3><strong>Invoice <?php echo $invoice; ?> </strong></h3></center>

<div class="row">
    <a class="btn btn-success" href="cetakpomitra.php?invoice=<?php //echo $invoice; ?>" target="blank">Cetak</a> 

	<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>No</th>
						<th>Qty</th>
						<th>Nama Barang</th>
						<th>Custom</th>
					    <th>Satuan</th>
					    <th style="text-align:center">Jumlah</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php  
                         $jumlah=0;
					               $subtotal=0;
                         if($kode=='D') {
                         $datapo=$koneksi->query("
                              SELECT 
                              poproduk.namapo,
                              pokategori.namakategori,
                              podetail.variant,
                              pomitra.idpomitra,
                              pomitra.jumlah,
                              pomitra.invoice,
                              pomitra.total,
                              pomitra.custom,
                              podetail.harga 
                               FROM poproduk 
                               inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                               inner JOIN pokategori on pokategori.idpo=pomitra.idpo
                               inner join podetail on podetail.idpodetail=pomitra.idpodetail
                               WHERE pomitra.idmitra='$id' AND pomitra.jumlah>0 
                               AND (pomitra.idpoproduk='73' OR pomitra.idpoproduk='74' 
                               OR pomitra.idpoproduk='76')");
                         }
                         else if($kode=='A'){
                          $datapo=$koneksi->query("
                              SELECT 
                              poproduk.namapo,
                              pokategori.namakategori,
                              podetail.variant,
                              pomitra.idpomitra,
                              pomitra.jumlah,
                              pomitra.invoice,
                              pomitra.total,
                              pomitra.custom,
                              podetail.harga 
                               FROM poproduk 
                               inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                               inner JOIN pokategori on pokategori.idpo=pomitra.idpo
                               inner join podetail on podetail.idpodetail=pomitra.idpodetail
                               WHERE pomitra.idmitraagen='$id' AND pomitra.jumlah>0 
                               AND (pomitra.idpoproduk='73' OR pomitra.idpoproduk='74' 
                               OR pomitra.idpoproduk='76')");
                         }
                         else if($kode=='R'){
                          $datapo=$koneksi->query("
                              SELECT 
                              poproduk.namapo,
                              pokategori.namakategori,
                              podetail.variant,
                              pomitra.idpomitra,
                              pomitra.jumlah,
                              pomitra.invoice,
                              pomitra.total,
                              pomitra.custom,
                              podetail.harga 
                               FROM poproduk 
                               inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                               inner JOIN pokategori on pokategori.idpo=pomitra.idpo
                               inner join podetail on podetail.idpodetail=pomitra.idpodetail
                               WHERE pomitra.idmitrareseller='$id' AND pomitra.jumlah>0 
                               AND (pomitra.idpoproduk='73' OR pomitra.idpoproduk='74' 
                               OR pomitra.idpoproduk='76')");
                         }
                         else if($kode=='M'){
                          $datapo=$koneksi->query("
                              SELECT 
                              poproduk.namapo,
                              pokategori.namakategori,
                              podetail.variant,
                              pomitra.idpomitra,
                              pomitra.jumlah,
                              pomitra.invoice,
                              pomitra.total,
                              pomitra.custom,
                              podetail.harga 
                               FROM poproduk 
                               inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                               inner JOIN pokategori on pokategori.idpo=pomitra.idpo
                               inner join podetail on podetail.idpodetail=pomitra.idpodetail
                               WHERE pomitra.idmitramarketer='$id' AND pomitra.jumlah>0 
                               AND (pomitra.idpoproduk='73' OR pomitra.idpoproduk='74' 
                               OR pomitra.idpoproduk='76')");
                         }
                            $no=1;
                          
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>     
                          <td>
                            <?php echo $tampilkan['jumlah']; ?>
                          </td>
                           <td>
                            <?php echo $tampilkan['variant']; ?>
                          </td>
                          <td>
                            <?php if($tampilkan['custom']<>''){
                            echo nl2br($tampilkan['custom']);}
                            else{ echo " - "; }?>
                          </td>
                           <td>
                           Rp. <?php echo number_format($tampilkan['harga']); ?>
                          </td>
                          <td>
                            Rp. <?php echo number_format($tampilkan['total']); ?>
                          </td>
            
                        <?php
							$sum=$sum+$tampilkan['jumlah'];
              $idpomitra=array($tampilkan['idpomitra']);						
							$jumlah=$jumlah+$tampilkan['total'];
							$invoice=$tampilkan['invoice'];
							//$subtotal=$subtotal+$jumlah;
							?>
							
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    Link Share Invoice 
                     <input type="text" class="form-control" style="width:450px;" value="http://mitra.wanoja.com/shareinvoice.php?id=<?php echo $invoice; ?>">  <br>
				             <p align="right"><strong>Total Qty : <?php echo $sum; ?> </strong></p> 
				            <p align="right"><strong>JUMLAH  Rp. <?php echo number_format($jumlah); ?> </strong></p>
				            
				            <?php $diskon=35/100*$jumlah;
				                  $subtotal=$jumlah-$diskon; ?>
				            <p align="right" style="color: red">Diskon DB 35% Rp. -<?php echo number_format($diskon); ?> </p><hr>      
				            <p align="right" style="color: green"><strong>TOTAL  Rp. <?php echo number_format($subtotal); ?> <strong></p>
				            
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

</body>

</html>

		                                                      

                    