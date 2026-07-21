<?php
include "koneksi.php";
$invoice=$_GET["invoice"];
$sum=0;

  $datamitra=$koneksi->query("SELECT mitraagen.namaagen,admin_mitra.namamitra,poproduk.namapo,pokategori.namakategori,podetail.variant,pokolibri.idpokolibri,pokolibri.jumlah,pokolibri.invoice,pokolibri.total,podetail.harga,podropship_kolibri.namapenerima
					FROM poproduk inner JOIN pokategori inner join podetail inner join pokolibri inner join mitraagen on pokolibri.idmitraagen=mitraagen.idmitraagen inner join admin_mitra on admin_mitra.idadmin=mitraagen.idmitraagen inner join podropship_kolibri on poproduk.idpoproduk=pokolibri.idpoproduk and pokategori.idpo=pokolibri.idpo and podetail.idpodetail=pokolibri.idpodetail and pokolibri.invoice=podropship_kolibri.invoice WHERE pokolibri.invoice='$invoice' and pokolibri.jumlah>0");
                            $tampilnama=$datamitra->fetch_assoc()
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
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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

    <strong> Nama Mitra : <?php echo $tampilnama['namamitra']; ?> </strong><br>
    <strong> Nama Keluarga : <?php echo $tampilnama['namapenerima']; ?> </strong>
	<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>No</th>
						<th>Qty</th>
						<th>Nama Barang</th>
					    <th>Satuan</th>
					    <th style:"text-align:center">Jumlah</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                         // $invoice=$_GET["invoice"];
                          	$jumlah=0;
					$subtotal=0;
                          if(isset($_POST["cari"])){
                             $namapo=$_POST["namapo"];
                             $halaman = 20; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT admin_mitra.namamitra,pokolibri.idpokolibri,pokolibri.jumlah,podetail.variant,pokolibri.idadmin,pokolibri.tgl,pokolibri.status,pokolibri.invoice,poproduk.namapo,pokategori.namakategori FROM `pokolibri` inner join pokategori inner join admin_mitra inner join podetail inner join poproduk on pokolibri.idpo=pokategori.idpo and admin_mitra.idadmin=pokolibri.idadmin and pokolibri.idpodetail=podetail.idpodetail and pokolibri.idpoproduk=poproduk.idpoproduk where pokolibri.jumlah>0");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
        
                            $datapo=$koneksi->query("SELECT admin_mitra.namamitra,pokolibri.idpokolibri,pokolibri.jumlah,podetail.variant,pokolibri.idadmin,pokolibri.tgl,pokolibri.status,pokolibri.invoice,poproduk.namapo,pokategori.namakategori FROM `pokolibri` inner join pokategori inner join admin_mitra inner join podetail inner join poproduk on pokolibri.idpo=pokategori.idpo and admin_mitra.idadmin=pokolibri.idadmin and pokolibri.idpodetail=podetail.idpodetail and pokolibri.idpoproduk=poproduk.idpoproduk where poproduk.namapo LIKE '%$namapo%' and pokolibri.jumlah>0 order by pokolibri.tgl desc LIMIT $mulai, $halaman");
                            $no=$mulai+1;
                          } else if(isset($_POST["tampil"])) {  
                             $halaman = 20; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT admin_mitra.namamitra,pokolibri.idpokolibri,pokolibri.jumlah,podetail.variant,pokolibri.idadmin,pokolibri.tgl,pokolibri.status,pokolibri.invoice,poproduk.namapo,pokategori.namakategori FROM `pokolibri` inner join pokategori inner join admin_mitra inner join podetail inner join poproduk on pokolibri.idpo=pokategori.idpo and admin_mitra.idadmin=pokolibri.idadmin and pokolibri.idpodetail=podetail.idpodetail and pokolibri.idpoproduk=poproduk.idpoproduk where pokolibri.jumlah>0");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
        
                            $datapo=$koneksi->query("SELECT admin_mitra.namamitra,pokolibri.idpokolibri,pokolibri.jumlah,podetail.variant,pokolibri.idadmin,pokolibri.tgl,pokolibri.status,pokolibri.invoice,poproduk.namapo,pokategori.namakategori FROM `pokolibri` inner join pokategori inner join admin_mitra inner join podetail inner join poproduk on pokolibri.idpo=pokategori.idpo and admin_mitra.idadmin=pokolibri.idadmin and pokolibri.idpodetail=podetail.idpodetail and pokolibri.idpoproduk=poproduk.idpoproduk where pokolibri.jumlah>0 order by pokolibri.tgl desc LIMIT $mulai, $halaman");
                            $no=$mulai+1;
                          } else {
                                   $halaman = 20; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT admin_mitra.namamitra,poproduk.namapo,pokategori.namakategori,podetail.variant,pokolibri.idpokolibri,pokolibri.jumlah,pokolibri.invoice,pokolibri.total,podetail.harga 
					FROM poproduk inner JOIN pokategori inner join podetail inner join pokolibri inner join admin_mitra on poproduk.idpoproduk=pokolibri.idpoproduk and pokategori.idpo=pokolibri.idpo and podetail.idpodetail=pokolibri.idpodetail and admin_mitra.idadmin=pokolibri.idadmin WHERE pokolibri.invoice='$invoice' and pokolibri.jumlah>0");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
        
                            $datapo=$koneksi->query("SELECT admin_mitra.namamitra,poproduk.namapo,pokategori.namakategori,podetail.variant,pokolibri.idpokolibri,pokolibri.jumlah,pokolibri.invoice,pokolibri.total,podetail.harga 
					FROM poproduk inner JOIN pokategori inner join podetail inner join pokolibri inner join admin_mitra on poproduk.idpoproduk=pokolibri.idpoproduk and pokategori.idpo=pokolibri.idpo and podetail.idpodetail=pokolibri.idpodetail and admin_mitra.idadmin=pokolibri.idadmin WHERE pokolibri.invoice='$invoice' and pokolibri.jumlah>0 ");
                            $no=$mulai+1;
                          }
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
                           Rp. <?php echo number_format($tampilkan['harga']); ?>
                          </td>
                          <td>
                            Rp. <?php echo number_format($tampilkan['total']); ?>
                          </td>
            
                        <?php
							//$sum=array_sum($data['jumlah']);
							$sum=$sum+$tampilkan['jumlah'];
                            $idpokolibri=array($tampilkan['idpokolibri']);						
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
            <span>Copyright &copy; Wanoja 2020</span>
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

</body>

<script>
window.print();
</script>

</html>

		                                                      

                    