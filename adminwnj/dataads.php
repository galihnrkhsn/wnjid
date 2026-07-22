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

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

 <?php include "sidebar.php"; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><strong>Ads Mitra</strong></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          <div class="row">
<br>
<a class="btn btn-primary btn-icon-split" href="inputads.php">
  <span class="texy-white-50 icon"><i class="fas fa-plus"></i></span>
  <span class="text">Tambah Ads</span>
</a>

<div class="table-responsive" style="margin-top: 3%">
<table class="table table-striped" id="tbslider">
                      <thead>
                        <tr>
                            <th>
                                No
                            </th>    
                          <th>
                            Status
                          </th>
                          <th width="15%">
                            Tanggal
                          </th>
                          <th>
                           Program Ads
                          </th>
                          <th style="text-align: center;">
                           Target Kota
                          </th>
                          <th>
                           Nama DB
                          </th>
                          <th>
                           Whatsapp
                          </th>
                            <th>
                           Update Status
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                            $dataads=$koneksi->query("SELECT * FROM adsmitra order by tgl desc");
                            $no=1;
                            while($tampilkan=$dataads->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>     
                          <td>
                            <?php echo $tampilkan['status']; ?>
                          </td>
                          <td>
                            <i class="fas fa-calendar" style="color: red"></i> <?php echo $tampilkan['tgl']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['program']; ?>
                          </td>
                          <td>
                            <i class="fas fa-map-marker-alt" style="color: red"></i> <?php echo $tampilkan['targetkota']; ?>
                          </td>
                          <td>
                            <i class="fas fa-user"></i> <?php echo $tampilkan['nama']; ?>
                          </td>
                          <td>
                            <i class="fa fa-whatsapp" style="color: green"></i> <?php echo $tampilkan['whatsapp']; ?>
                          </td>
                          <form method="post"><input type="hidden" name="id" value="<?php echo $tampilkan['idads']; ?>">
							                    <td class="align-middle"><select name="status" class="form-control">
                                                              <option value="<?php echo $tampilkan['status']; ?>"><?php echo $tampilkan['status']; ?></option>
							                                                <option value="Antrian">Antrian</option>
							                                                <option value="Setting">Setting</option>
							                                                <option value="Running">Running</option>
							                                                <option value="Selesai">Selesai</option>
							                                              </select>  
							                    <button type="submit" class="btn btn-primary" name="update">Update</button>
							                    </td>
							</form>
							
							<?php
                            if(isset($_POST["update"])){
	
                            $idads = $_POST['id'];
	                        $status = $_POST['status'];
	                   
                            $query = "UPDATE adsmitra SET status= '$status' where idads='$idads'";
                            $sql = mysqli_query( $koneksi, $query);
                            
                            echo "<script>alert('data berhasil diubah');</script>";
                            echo "<script>location='dataads.php';</script>";
                            }
                         
                             ?>
							
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                        
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
  <?php include "settingdatatables.php"; ?>

</body>

</html>

		                    