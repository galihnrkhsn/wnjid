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

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" method="post">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Cari Nama PO..." aria-label="Search" aria-describedby="basic-addon2" name="namapo">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit" name="cari">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" method="post">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Cari Nama PO..." aria-label="Search" aria-describedby="basic-addon2" name="namapo">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit" name="cari">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>
              </div>
            </li>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin Wanoja</span>
                <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
              <!--  <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings 
                </a> 
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>-->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          
<?php
$invoice=$_GET["invoice"];
?>
<center><h3><strong>Invoice <?php echo $invoice; ?> </strong></h3></center>

<div class="row">
    <a class="btn btn-success" href="cetakpokonin.php?invoice=<?php echo $invoice; ?>" target="blank">Cetak</a> <a class="btn btn-primary" href="cetakpokoninexcel.php?invoice=<?php echo $invoice; ?>" target="blank">Cetak Excel</a> 
    
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
                            $datasaldo=$koneksi->query("SELECT admin_mitra.namamitra,pokonin.idpokonin,pokonin.jumlah,podetail.variant,pokonin.idmitra,pokonin.tgl,pokonin.status,pokonin.invoice,poproduk.namapo,pokategori.namakategori FROM `pokonin` inner join pokategori inner join admin_mitra inner join podetail inner join poproduk on pokonin.idpo=pokategori.idpo and admin_mitra.idadmin=pokonin.idmitra and pokonin.idpodetail=podetail.idpodetail and pokonin.idpoproduk=poproduk.idpoproduk where pokonin.jumlah>0");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
        
                            $datapo=$koneksi->query("SELECT admin_mitra.namamitra,pokonin.idpokonin,pokonin.jumlah,podetail.variant,pokonin.idmitra,pokonin.tgl,pokonin.status,pokonin.invoice,poproduk.namapo,pokategori.namakategori FROM `pokonin` inner join pokategori inner join admin_mitra inner join podetail inner join poproduk on pokonin.idpo=pokategori.idpo and admin_mitra.idadmin=pokonin.idmitra and pokonin.idpodetail=podetail.idpodetail and pokonin.idpoproduk=poproduk.idpoproduk where poproduk.namapo LIKE '%$namapo%' and pokonin.jumlah>0 order by pokonin.tgl desc LIMIT $mulai, $halaman");
                            $no=$mulai+1;
                          } else if(isset($_POST["tampil"])) {  
                             $halaman = 20; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT admin_mitra.namamitra,pokonin.idpokonin,pokonin.jumlah,podetail.variant,pokonin.idmitra,pokonin.tgl,pokonin.status,pokonin.invoice,poproduk.namapo,pokategori.namakategori FROM `pokonin` inner join pokategori inner join admin_mitra inner join podetail inner join poproduk on pokonin.idpo=pokategori.idpo and admin_mitra.idadmin=pokonin.idmitra and pokonin.idpodetail=podetail.idpodetail and pokonin.idpoproduk=poproduk.idpoproduk where pokonin.jumlah>0");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
        
                            $datapo=$koneksi->query("SELECT admin_mitra.namamitra,pokonin.idpokonin,pokonin.jumlah,podetail.variant,pokonin.idmitra,pokonin.tgl,pokonin.status,pokonin.invoice,poproduk.namapo,pokategori.namakategori FROM `pokonin` inner join pokategori inner join admin_mitra inner join podetail inner join poproduk on pokonin.idpo=pokategori.idpo and admin_mitra.idadmin=pokonin.idmitra and pokonin.idpodetail=podetail.idpodetail and pokonin.idpoproduk=poproduk.idpoproduk where pokonin.jumlah>0 order by pokonin.tgl desc LIMIT $mulai, $halaman");
                            $no=$mulai+1;
                          } else {
                                   $halaman = 20; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT poproduk.namapo,pokategori.namakategori,podetail.variant,pokonin.idpokonin,pokonin.jumlah,pokonin.invoice,pokonin.total,podetail.harga 
					FROM poproduk inner JOIN pokategori inner join podetail inner join pokonin on poproduk.idpoproduk=pokonin.idpoproduk and pokategori.idpo=pokonin.idpo and podetail.idpodetail=pokonin.idpodetail WHERE pokonin.invoice='$invoice' and pokonin.jumlah>0");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
        
                            $datapo=$koneksi->query("SELECT poproduk.namapo,pokategori.namakategori,podetail.variant,pokonin.idpokonin,pokonin.jumlah,pokonin.invoice,pokonin.total,podetail.harga 
					FROM poproduk inner JOIN pokategori inner join podetail inner join pokonin on poproduk.idpoproduk=pokonin.idpoproduk and pokategori.idpo=pokonin.idpo and podetail.idpodetail=pokonin.idpodetail WHERE pokonin.invoice='$invoice' and pokonin.jumlah>0");
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
							$sum=$sum+$tampilkan['jumlah'];
                            $idpokonin=array($tampilkan['idpokonin']);						
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
				             <p align="right">Total Qty : <?php echo $sum; ?> </p> 
				            <p align="right">JUMLAH  Rp. <?php echo number_format($jumlah); ?> </p>
				            
				            <?php $diskon=35/100*$jumlah;
				                  $subtotal=$jumlah-$diskon;   
				                  $payment1= $subtotal*50/100;
				                  $payment2= $subtotal*30/100;
				                  $payment3= $subtotal*40/100;
				                  
				            ?>
				            <p align="right">Diskon DB 35% Rp. -<?php echo number_format($diskon); ?> </p><br>      
				            <p align="right">TOTAL  Rp. <?php echo number_format($subtotal); ?> </p>
				            <hr>
				            <p align="right">DP 50% : Rp. <?php echo number_format($payment1); ?> </p>
				            
				            
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

		                                                      

                    