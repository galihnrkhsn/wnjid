<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$no_sj=$_GET["no_sj"];

  $sql = "SELECT admin_mitra.namamitra, surat_jalan_manual.status, surat_jalan_manual.invoice FROM surat_jalan_manual JOIN admin_mitra on admin_mitra.idadmin = surat_jalan_manual.idadmin WHERE surat_jalan_manual.no_sj='$no_sj' ";
  $query = $koneksi->query($sql);
  $datadb = $query->fetch_assoc();
$invoice = $datadb['invoice']
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

<body id="page-top">

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
          <div class="d-sm-flex mb-4">
			<div class="input-group mb-3" style="float: left;">				
          	<form method="post">
			  		<input type="number" min="0" max="100" class="form-control" placeholder="Diskon" name="diskon" value="35">
			  		<button class="btn btn-outline-primary" type="submit" id="simpan" name="simpan">Diskon</button>			  	
			</form>
			</div>  

       
          </div>                                   
            <br> 


          <!-- Content Row -->
          <div class="row mt-3">
        	
  <?php 
$statusnya = $datadb['status'];
   ?>
  <div class="table-responsive col">
                                    <a href="cetak_surat_manual.php?no_sj=<?php echo $no_sj; ?>" class="btn btn-success btn-icon-split" target="_blank()">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-print"></i>
                                        </span>
                                        <span class="text">Cetak</span>
                                    </a>   	
<?php if ($statusnya=='INV' or $statusnya=='IPO'): ?>
<h3><strong>Inv. <?php echo $invoice ?></strong></h3>

<?php else: ?>
<h3><strong>No. <?php echo $no_sj ?></strong></h3>    
    <?php endif ?>    
    <h4>Nama Mitra : <?= $datadb['namamitra']; ?></h4>
        <table class="table table-bordered bg-white">
					<tr>
					    <th>No</th>
  						<th>Nama Produk</th>
              <th>Jumlah</th>
<?php if ($statusnya=='INV' or $statusnya=='PO' or $statusnya=='IPO'): ?>                
					    <th>Harga</th>
					    <th>Subtotal</th>       
              <?php endif ?>              
                        </tr>
                      </thead>
                      <tbody>
                      <?php 

if ($statusnya=='RS' or $statusnya=='INV') {     
                        $datapo=$koneksi->query("SELECT 
                          surat_jalan_manual.no_sj,
                          produk.namaproduk,
                          produk.harga,
                          surat_jalan_manual.progres
                          FROM surat_jalan_manual
                          JOIN produk on produk.idproduk = surat_jalan_manual.idproduk
                          WHERE surat_jalan_manual.no_sj = '$no_sj'
                          ORDER BY produk.namaproduk asc
                          ");
}    
if ($statusnya=='PO' or $statusnya=='IPO' ) {     
                        $datapo=$koneksi->query("SELECT 
                          surat_jalan_manual.no_sj,
                          podetail.variant as namaproduk,
                          podetail.harga,
                          surat_jalan_manual.progres
                          FROM surat_jalan_manual
                          JOIN podetail on podetail.idpodetail = surat_jalan_manual.idproduk
                          WHERE surat_jalan_manual.no_sj = '$no_sj'
                          ORDER BY podetail.variant asc
                          ");
}                            
                          $no=1;
                        while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>   
                        <td>
                          <?= $tampilkan['namaproduk']; ?>
                        </td>  
         
                        <td>
                          <?= $tampilkan['progres']; ?>
                        </td>
<?php if ($statusnya=='INV' or $statusnya=='PO' or $statusnya=='IPO'): ?>                         
                        <td>
                          <?= $tampilkan['harga']; ?>
                        </td>
                        <td>
                        	<?php $subtotal = $tampilkan['harga']* $tampilkan['progres'];?>
                          <?= $subtotal; ?>
                        </td>
              <?php endif ?>                           
                        </tr>
                        <?php 
                        $totalnya = $subtotal + $totalnya;
                        $jumlah_qty += $tampilkan['progres'];
                         ?>
                        <?php } ?>
                      </tbody>
<?php if ($statusnya!='INV' and $statusnya!='PO' and $statusnya!='IPO'): ?>                        
                    <tr>
                      <td colspan="2">Total</td>
                      <td><?= $jumlah_qty ?></td>
                    </tr>
               <?php endif ?>                     
                    </table>

<?php


$sqlharga2 = "SELECT MAX(podetail.harga) as harga, poproduk.idpoproduk  
              FROM surat_jalan_manual 
              JOIN podetail on podetail.idpodetail = surat_jalan_manual.idproduk
              JOIN pokategori on podetail.idpo = pokategori.idpo
              JOIN poproduk on pokategori.idpoproduk = poproduk.idpoproduk
                          WHERE surat_jalan_manual.no_sj = '$no_sj'";
$queryharga2 = $koneksi->query($sqlharga2);
$sisaharga2 = $queryharga2->fetch_assoc(); 
$stokharga2 = $sisaharga2['harga']; 
$idpoproduk = $sisaharga2['idpoproduk'];
// if ($idpoproduk=="120") {
//   $totalnya = $stokharga2;
// }
$diskon = 35;

    if(isset($_POST["simpan"])){
    	$diskon = $_POST["diskon"];
    }

$diskonya=$totalnya*$diskon/100;

$tgrandtotal = $totalnya - $diskonya;
if ($statusnya=='INV' or $statusnya=='PO' or $statusnya=='IPO') {
?>

<table style="float: right;width: 100%">
 <tbody  style="float: right;">
    <tr>
        <th style="padding-bottom: 5%;">Total Qty</th>
        <td style="padding-bottom: 5%;">:</td>
        <td style="padding-bottom: 5%;">
        <?php echo $jumlah_qty; ?>

        </td>
    </tr>
    <tr>
        <th>Total</th>
        <td>:</td>
        <td>       
Rp. <?php echo number_format($totalnya); ?>                     
        </td>
    </tr>
    <tr>
        <th>Diskon DB <?= $diskon; ?>%</th>
        <td>:</td>
        <td>
          Rp. <?php echo number_format($diskonya); ?>               
        </td>
    </tr>
    <tr>
        <th>GrandTotal</th>
        <td>:</td>
        <td>
          Rp. <?php echo number_format($tgrandtotal); ?>               
        </td>
    </tr>
</tbody>
</table> 
<?php
}
?>
			   </div>


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

		                                                