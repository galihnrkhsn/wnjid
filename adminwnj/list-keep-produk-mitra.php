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
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          <div class="row">

<h3><strong>Keep Produk Mitra</strong></h3>
              </div>
              
<a class="btn btn-primary" href="inputkeep.php"><span class="fas fa-plus"></span> Tambah Keep Produk</a><br>

<table class="table table-striped">
                      <thead>
                        <tr>
                            <th>
                            No    
                            </th>    
                          <th>
                            Tanggal
                          </th>
                          <th>
                            Nama Produk
                          </th>
                          <th>
                           Qty
                          </th>
                          <th>
                           EXP
                          </th>
                          <th>
                           Mitra
                          </th>
                          <th>
                           Status
                          </th>
                          <th>
                           Update Pembayaran
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                           $halaman = 20; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT * FROM keepproduk");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
        
        $datamitra=$koneksi->query("SELECT * FROM keepproduk INNER JOIN admin_mitra ON keepproduk.idadmin = admin_mitra.idadmin LIMIT $mulai, $halaman");
         $no    =$mulai+1;
        while($tampilkan=$datamitra->fetch_assoc()){
        ?>
                        <tr>
                         <td>
                             <?php echo $no++; ?>
                         </td>     
                          <td>
                            <?php echo $tampilkan['tgl']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['namaproduk']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['qty']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['expire']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['namamitra']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['statuspembayaran']; ?>
                          </td>
                              <form method="post"><input type="hidden" name="id" value="<?php echo $tampilkan['idkeep']; ?>">
							                    <td class="align-middle"><select name="status">
							                                                <option value="lunas">Lunas</option>
							                                                <option value="belum lunas">Belum Lunas</option>
							                                              </select>  
							                    <button type="submit" class="btn btn-primary" name="update">Update</button>
							                    </td>
							</form>
							
							
							<?php
                            if(isset($_POST["update"])){
	
                            $idkeep = $_POST['id'];
	                        $status = $_POST['status'];
	                   
                            $query = "UPDATE keepproduk SET statuspembayaran= '".$status."' where idkeep='$idkeep'";
                            $sql = mysqli_query( $koneksi, $query);
                            
                                if($sql){ // Cek jika proses simpan ke database sukses atau tidak
                                    // Jika Sukses, Lakukan :
                                        header("location: index.php?page=keepproduk"); // Redirect ke halaman index.php
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
                            <a href="index.php?page=keepproduk&halaman=<?php echo $i; ?>" style="text-decoration:none">   <u><?php echo $i; ?></u></a>
                            <?php
                                }
                            ?>
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

		                    