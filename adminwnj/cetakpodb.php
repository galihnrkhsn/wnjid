<?php
include "koneksi.php";
$idadmin=$_GET["idadmin"];
$idpoproduk=$_GET["idpoproduk"];
$sum=0;

  $datamitra=$koneksi->query("SELECT poproduk.namapo,admin_mitra.namamitra as db,
                              mitraagen.namaagen as agen,mitrareseller.namaagen as reseller,
                              mitramarketer.namaagen as marketer FROM `pomitra` 
                              LEFT JOIN mitraagen on mitraagen.idmitraagen=pomitra.idmitraagen 
                              LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=pomitra.idmitrareseller 
                              LEFT JOIN mitramarketer on mitramarketer.idmitramarketer=pomitra.idmitramarketer 
                              LEFT JOIN admin_mitra on (mitraagen.idadmin=admin_mitra.idadmin or mitrareseller.idadmin=admin_mitra.idadmin or mitramarketer.idadmin=admin_mitra.idadmin or pomitra.idmitra=admin_mitra.idadmin) 
                              INNER JOIN poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
                              WHERE poproduk.idpoproduk='$idpoproduk' and pomitra.idmitra='$idadmin'");
                            $tampilnama=$datamitra->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Laporan PO</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

          <!-- Content Row -->
<br>
<br>
<br>

<center><h3><strong><?php echo $tampilnama['namapo']; ?></strong></h3></center>
<center><h5>Invoice <?php echo $invoice; ?></h5></center>

    <strong> Nama Distributor : <?php echo $tampilnama['db']; ?> </strong><br>
    <strong> Nama Sub DB : <?php echo $tampilnama['agen']; ?> <?php echo $tampilnama['reseller']; ?>  <?php echo $tampilnama['marketer']; ?></strong>
	<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>No</th>
						<th>Qty</th>
						<th>Nama Barang</th>
            <th>#Invoice</th>
						<?php
            if ($idpoproduk=='87') { ?>
            <th>Custom Nama</th>
            <th>Font Teks</th>
            <th>Warna Teks</th>
            <?php } ?>
					    <th>Satuan</th>
					    <th style="text-align:center">Jumlah</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                         // $invoice=$_GET["invoice"];
                          
        
                            $datapo=$koneksi->query(" SELECT 
                          poproduk.namapo,
                          admin_mitra.namamitra,
                          pokategori.namakategori,
                          podetail.variant,
                          pomitra.idpomitra,
                          pomitra.jumlah,
                          pomitra.invoice,
                          pomitra.total,
                          pomitra.custom,
                          pomitra.font,
                          podetail.harga 
                           FROM poproduk 
                           inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                           inner JOIN pokategori on pokategori.idpo=pomitra.idpo
                           inner join podetail on podetail.idpodetail=pomitra.idpodetail
                           Inner Join admin_mitra on pomitra.idmitra=admin_mitra.idadmin
                           WHERE pomitra.idpoproduk='$idpoproduk' and pomitra.idmitra='$idadmin' and pomitra.jumlah>0");
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
                            <?php echo $tampilkan['invoice']; ?>
                          </td>
                          <?php if ($idpoproduk=='87') { ?>
                          <td>
                            <?php echo $tampilkan['custom']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['font']; ?>
                          </td> 
                          <td>
                            <?php if ($tampilkan['namakategori']=='Cream' or $tampilkan['namakategori']=='Silver' 
                              or $tampilkan['namakategori']=='White' or $tampilkan['namakategori']=='Grey') {
                              echo "Black"; 
                              } else {
                              echo "Gold";  
                              } 
                            ?>
                          </td> 
                          <?php } ?>
                           <td>
                           Rp. <?php echo number_format($tampilkan['harga']); ?>
                          </td>
                          <td>
                            Rp. <?php echo number_format($tampilkan['total']); ?>
                          </td>
            
                        <?php
							//$sum=array_sum($data['jumlah']);
							$sum=$sum+$tampilkan['jumlah'];
                            $idpomitra=array($tampilkan['idpomitra']);						
							$jumlah=$jumlah+$tampilkan['total'];
							$invoice=$tampilkan['invoice'];
							$namamitra=$tampilkan['namamitra'];
							//$subtotal=$subtotal+$jumlah;
							?>
							
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table><br>
                           
				            <p align="right">Total Qty : <?php echo $sum; ?> </p>  
				            <p align="right">JUMLAH  Rp. <?php echo number_format($jumlah); ?> </p>
				            
				            <?php $diskon=35/100*$jumlah;
				                  $subtotal=$jumlah-$diskon; ?>
				            <p align="right">Diskon DB  Rp. <?php echo number_format($diskon); ?> </p><br>      
				            <p align="right">TOTAL  Rp. <?php echo number_format($subtotal); ?> </p>
              <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; WNJ.ID 2020</span>
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

<script>
window.print();
</script>

</html>

		                                                      

                    