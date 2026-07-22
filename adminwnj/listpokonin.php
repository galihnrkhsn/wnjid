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
                <input type="text" class="form-control bg-light border-0 small" placeholder="Cari Nama Mitra..." aria-label="Search" aria-describedby="basic-addon2" name="namamitra">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit" name="carimitra">
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
    <h3><strong><center>PO DB konin 2021 </center></strong></h3>
          <!-- Content Row -->
          <div class="row">


    <a class="btn btn-warning" href="sumpokonin.php" target="blank">Summary PO Konin</a><br><br>
	<form method="post">  
	<div class="input-group">
        <input type="text" class="form-control" placeholder="Tulis no invoice..." aria-label="Search" aria-describedby="basic-addon2" name="invoice">
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit" name="cari"><i class="fas fa-search fa-sm"></i></button> <button class="btn btn-success" type="submit" name="tampil">Tampil Semua</button>
    </div></div>
    
	<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
					    <th>Check</th>
					    <th>No</th>
						<th>Tanggal</th>
						<th>Nama DB</th>
						<th>Nama Keluarga</th>
						<th>Alamat</th>
					    <th>Status</th>
                          <th>
                           Invoice
                          </th>
                           <th>Opsi</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                         if(isset($_POST["carimitra"])){
                               $namamitra=$_POST["namamitra"];
                                     $halaman = 50; /* page halaman*/
                                   $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                                   $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                                    $datasaldo=$koneksi->query("SELECT admin_mitra.namamitra,pokonin.invoice,pokonin.tgl,podropship_konin.namapenerima,podropship_konin.alamatpenerima FROM pokonin INNER JOIN admin_mitra on admin_mitra.idadmin=pokonin.idadmin INNER JOIN podropship_konin on pokonin.invoice=podropship_konin.invoice GROUP by pokonin.invoice ORDER BY podropship_konin.iddropship DESC");
                                    $total = mysqli_num_rows($datasaldo);
                                    $pages = ceil($total/$halaman);
                                    $datapo=$koneksi->query("SELECT admin_mitra.namamitra,pokonin.invoice,pokonin.status,pokonin.tgl,podropship_konin.namapenerima,podropship_konin.alamatpenerima FROM pokonin INNER JOIN admin_mitra on admin_mitra.idadmin=pokonin.idadmin INNER JOIN podropship_konin on pokonin.invoice=podropship_konin.invoice where admin_mitra.namamitra LIKE '%$namamitra%' GROUP by pokonin.invoice ORDER BY podropship_konin.iddropship DESC");  
                                    $no=$mulai+1;             
                         } else {
                                   $halaman = 50; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT admin_mitra.namamitra,pokonin.invoice,pokonin.tgl,podropship_konin.namapenerima,podropship_konin.alamatpenerima FROM pokonin INNER JOIN admin_mitra on admin_mitra.idadmin=pokonin.idadmin INNER JOIN podropship_konin on pokonin.invoice=podropship_konin.invoice GROUP by pokonin.invoice ORDER BY podropship_konin.iddropship DESC");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
        
                            $datapo=$koneksi->query("SELECT admin_mitra.namamitra,pokonin.invoice,pokonin.status,pokonin.tgl,podropship_konin.namapenerima,podropship_konin.alamatpenerima FROM pokonin INNER JOIN admin_mitra on admin_mitra.idadmin=pokonin.idadmin INNER JOIN podropship_konin on pokonin.invoice=podropship_konin.invoice GROUP by pokonin.invoice ORDER BY podropship_konin.iddropship DESC LIMIT $mulai, $halaman");
                            $no=$mulai+1;
                          }
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td><input type="checkbox" class="check-item" name="invoice[]" value="<?php echo $tampilkan['invoice']; ?>"></td>
                         <td>
                             <?php echo $no++; ?>
                        </td>     
                          <td>
                            <?php echo $tampilkan['tgl']; ?>
                          </td>
                           <td>
                           <?php echo $tampilkan['namamitra']; ?>
                          </td>
                           <td>
                           <?php echo $tampilkan['namapenerima']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['alamatpenerima']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['status']; ?>
                          </td>
                          <td>
                           <a href="detailinvoice_konin.php?invoice=<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['invoice']; ?></a>
                          </td>
                           <td>
                           <a href="cetakdropship2.php?id=<?php echo $tampilkan['invoice']; ?>" class="btn btn-warning" target="blank">Cetak</a>
                          </td>
                        </tr>
                        
                        <?php } ?>
                        <tr>
                            <td colspan="7"><button type="submit" class="btn btn-warning" name="proses">Proses</button>  <button type="submit" class="btn btn-success" name="selesai">Selesai</button> 
                        </td>
                        </tr>
                      </tbody>
                    </table>
                    </div>
                    </form>
                    
                     <div style="font-weight:bold;">
                            Halaman
                            <?php
                            for ($i=1; $i<=$pages ; $i++){
                            ?>
                            <a href="listpokonin.php?halaman=<?php echo $i; ?>" style="text-decoration:none">   <u><?php echo $i; ?></u></a>
                            <?php
                                }
                            ?>
                        </div>
                        
                         <?php
                           include "koneksi.php";
					      
					       
                        if(isset($_POST["proses"])){
                             $invoice= $_POST['invoice'];
					       $jumlah_dipilih=count($invoice);
	                        for($x=0;$x<$jumlah_dipilih;$x++){
	                               
					               $koneksi->query("update pokonin set status='Proses' where invoice='$invoice';");
        		                  echo "<script>alert('data sudah terupdate');</script>";
        		                  echo "<script>location='listpoinvoice.php';</script>";
        					                     } 
                        }
        					                     
        				   if(isset($_POST["selesai"])){
        				        $invoice= $_POST['invoice'];
					       $jumlah_dipilih=count($invoice);
	                            for($x=0;$x<$jumlah_dipilih;$x++){
	                                
					           $koneksi->query("update pokonin set status='Selesai' where invoice='$invoice';");
        		                echo "<script>alert('data sudah terupdate');</script>";
        		                echo "<script>location='listpoinvoice.php';</script>";
        					                     } 	                     
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

		                                                