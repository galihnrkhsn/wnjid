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

<body id="page-top">

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
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
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
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
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
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Hai <?php echo $_SESSION["administrator"]["nama"] ?></span>
                <img class="img-profile rounded-circle" src="img/logo+TEXTwanoja.jpg" >
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
                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
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
          
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          <div class="row">
              
              <h3><strong>Pengiriman</strong></h3>

	<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="dataTables-example">
					<tr>
					    <th>Action</th>
					    <th>No Order</th>
						<th>Nama Pengirim</th>
						<th>Telepon Pengirim</th>
					    <th>Nama Penerima</th>
					    <th>Telepon Penerima</th>
					    <th>Ekspedisi</th>
					    <th>Layanan</th>
					    <th>Ongkir</th>
					    <th>Alamat</th>
					    <th>Provinsi</th>
					    <th>Kota/Kab</th>
					    <th>Kecamatan</th>
					    
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                          if(isset($_POST["cari"])){
                                     $namapo=$_POST["namapo"];
                                     $halaman = 50; /* page halaman*/
                                   $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                                   $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                                    $datasaldo=$koneksi->query("SELECT DISTINCT admin_mitra.namamitra,pomitra.tgl,pomitra.status,pomitra.invoice,poproduk.namapo,poproduk.idpoproduk FROM `pomitra` inner join poproduk inner join admin_mitra on pomitra.idpoproduk=poproduk.idpoproduk and pomitra.idmitra=admin_mitra.idadmin where poproduk.status='open' and poproduk.namapo LIKE '%$namapo%' ORDER BY pomitra.tgl  DESC");
                                    $total = mysqli_num_rows($datasaldo);
                                    $pages = ceil($total/$halaman);
                
                                    $datapo=$koneksi->query("SELECT DISTINCT admin_mitra.namamitra,pomitra.tgl,pomitra.status,pomitra.invoice,poproduk.namapo,poproduk.idpoproduk FROM `pomitra` inner join poproduk INNER JOIN admin_mitra on pomitra.idpoproduk=poproduk.idpoproduk and pomitra.idmitra=admin_mitra.idadmin where poproduk.status='open' and poproduk.namapo LIKE '%$namapo%' ORDER BY pomitra.tgl DESC LIMIT $mulai, $halaman");
                                    $no=$mulai+1;
                          } else if(isset($_POST["carimitra"])){
                               $namamitra=$_POST["namamitra"];
                                     $halaman = 50; /* page halaman*/
                                   $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                                   $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                                    $datasaldo=$koneksi->query("SELECT DISTINCT admin_mitra.namamitra,pomitra.tgl,pomitra.status,pomitra.invoice,poproduk.namapo,poproduk.idpoproduk FROM `pomitra` inner join poproduk INNER JOIN admin_mitra on pomitra.idpoproduk=poproduk.idpoproduk and pomitra.idmitra=admin_mitra.idadmin where poproduk.status='open' and admin_mitra.namamitra LIKE '%$namamitra%' ORDER BY pomitra.tgl DESC");
                                    $total = mysqli_num_rows($datasaldo);
                                    $pages = ceil($total/$halaman);
                             $datapo=$koneksi->query("SELECT DISTINCT admin_mitra.namamitra,pomitra.tgl,pomitra.status,pomitra.invoice,poproduk.namapo,poproduk.idpoproduk FROM `pomitra` inner join poproduk INNER JOIN admin_mitra on pomitra.idpoproduk=poproduk.idpoproduk and pomitra.idmitra=admin_mitra.idadmin where poproduk.status='open' and admin_mitra.namamitra LIKE '%$namamitra%' ORDER BY pomitra.tgl DESC LIMIT $mulai, $halaman");  
                                    $no=$mulai+1;             
                          } else if(isset($_POST["tampil"])) {  
                             $halaman = 50; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT DISTINCT admin_mitra.nama,pomitra.tgl,pomitra.status,pomitra.invoice,poproduk.namapo,poproduk.idpoproduk FROM `pomitra` inner join poproduk inner join admin_mitra on pomitra.idpoproduk=poproduk.idpoproduk and pomitra.idmitra=admin_mitra.idadmin where poproduk.status='open' ORDER BY pomitra.tgl  DESC");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
        
                            $datapo=$koneksi->query("SELECT DISTINCT admin_mitra.namamitra,pomitra.tgl,pomitra.status,pomitra.invoice,poproduk.namapo,poproduk.idpoproduk FROM `pomitra` inner join poproduk INNER JOIN admin_mitra on pomitra.idpoproduk=poproduk.idpoproduk and pomitra.idmitra=admin_mitra.idadmin where poproduk.status='open' ORDER BY pomitra.tgl DESC LIMIT $mulai, $halaman");
                            $no=$mulai+1;
                          } else {
                                   $halaman = 50; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT * FROM orderpengiriman");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
        
                            $datapo=$koneksi->query("SELECT * FROM orderpengiriman where ongkir=0 and ekspedisi<>'Disatukan' and ekspedisi<>'Ambil ke Pusat' and ekspedisi<>'Gosend' and ekspedisi<>'Ahsan'  order by idorderp DESC LIMIT $mulai, $halaman");
                            $no=$mulai+1;
                          }
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                      </td>    
                          <form method="post"><input type="hidden" name="id" value="<?php echo $tampilkan['idorderp']; ?>"><td>
                            <a href="updateongkir.php?id=<?php echo $tampilkan['idorderp'] ?>" class="btn btn-primary" target="blank">+Ongkir</a>
                          </td>   
                          <td>
                            <?php echo $tampilkan['invoice']; ?>
                          </td>
                           <td>
                           <?php echo $tampilkan['namapengirim']; ?>
                          </td>
                            <td>
                           <?php echo $tampilkan['tlppengirim']; ?>
                          </td>
                            <td>
                           <?php echo $tampilkan['namapenerima']; ?>
                          </td>
                           <td>
                           <?php echo $tampilkan['tlppenerima']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['ekspedisi']; ?>
                          </td>
                        <td>
                           <?php echo $tampilkan['layanan']; ?>
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
                           <?php echo $tampilkan['provinsi']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['kota']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['kecamatan']; ?>
                          </td>
                        
                     <!--   <td>
                            <form method="post"><input type="hidden" name="invoice" value=<?php echo $tampilkan['invoice']; ?>><button type="submit" class="btn btn-success" name="done">Done</button></form>
                          </td>
                         <form method="post"><input type="hidden" name="id" value="<?php echo $tampilkan['idpreorder']; ?>">
							                    <td class="align-middle"><select name="status">
							                                                <option value="proses">proses</option>
							                                                <option value="selesai">selesai</option>
							                                              </select>  
							                    <button type="submit" class="btn btn-primary" name="update">Update</button>
							                    </td>
							</form>-->
							
							<?php
                            if(isset($_POST["update"])){
	
                            $idpreorder = $_POST['id'];
	                        $status = $_POST['status'];
	                   
                            $query = "UPDATE list_po_mitra SET status= '".$status."' where idpreorder='$idpreorder'";
                            $sql = mysqli_query( $koneksi, $query);
                            
                                if($sql){ // Cek jika proses simpan ke database sukses atau tidak
                                    // Jika Sukses, Lakukan :
                                        header("location: index.php?page=listpomitra"); // Redirect ke halaman index.php
                                }else{
                                    // Jika Gagal, Lakukan :
                                    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
                                    echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
                                    }
                            }    
                             ?>
							
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    
                     <div style="font-weight:bold;">
                            Halaman
                            <?php
                            for ($i=1; $i<=$pages ; $i++){
                            ?>
                            <a href="ongkirmanual.php?halaman=<?php echo $i; ?>" style="text-decoration:none">   <u><?php echo $i; ?></u></a>
                            <?php
                                }
                            ?>
                        </div>
                        
                         <?php
                        if(isset($_POST["done"])){
	
	                                 include "koneksi.php";
					               $invoice= $_POST['invoice'];
					               $koneksi->query("update pomitra set status='selesai' where invoice='$invoice';");
        		                   	echo "<script>alert('data sudah terupdate');</script>";
        		                   	echo "<script>location='index.php?page=listpoinvoice';</script>";
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

</body>

</html>

		                                                