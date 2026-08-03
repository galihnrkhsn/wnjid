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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

<?php include "sidebar.php"; ?>

      <!-- Begin Page Content -->
      <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800"></h1>
          <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
        </div>
        <h3><strong>Order Mitra</strong></h3><br>
        <ul class="nav nav-tabs">
        <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#agen">Agen</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#reseller">Reseller</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#marketer">Marketer</a>
            </li>
        </ul>
        <div class="tab-content mt-3">
          <!-- Tab Content for Agen -->
          <div id="agen" class="tab-pane fade show active" id="home"  role="tabpanel">
              <h3 class="mb-3">Data Agen</h3>
              <button class="btn btn-outline-success btn-icon-split mb-3"  onclick="JavaScript:window.location.href='subdb/excel_agen.php';">
                  <span class="icon text-white-50 bg-success">
                      <i class="fas fa-download" style="margin-top:20%;"></i>
                  </span>
                  <span class="text">Download Excel Agen</span>
              </button>     
              <div class="table-responsive">
                  <table class="table table-striped table-bordered" id="tbagen">
                      <thead>
                          <tr>
                            <th style="text-align: center">No</th>
                            <th style="text-align: center">Nama SubDB</th>
                            <th style="text-align: center">Nama DB</th>
                            <th style="text-align: center">Email</th>
                            <th style="text-align: center">Password</th>
                            <th style="text-align: center">Whatsapp</th>
                            <th style="text-align: center"> Alamat</th>
                            <th style="text-align: right"><i class="fas fa-cog"></i></th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php
                            $no=1;
                            $tampil =$koneksi->query("SELECT *, mitraagen.email as agenmail FROM mitraagen INNER JOIN admin_mitra ON mitraagen.idadmin = admin_mitra.idadmin ORDER BY idmitraagen DESC");
                            while($tampilMas=$tampil->fetch_assoc()){
                          ?>
                          <tr>
                            <td><?php echo $no++; ?></td>
                            <td><i class="fas fa-users"></i> <?php echo $tampilMas['namaagen']; ?> (<?php echo $tampilMas['idmitraagen']; ?>)</td>
                            <td><i class="fas fa-user"></i> <?php echo $tampilMas['namamitra']; ?></td>
                            <td><i class="fas fa-envelope" style="color: red"></i> 
                              <?php echo $tampilMas['agenmail']; ?></td>
                            <td> 
                              <?php echo $tampilMas['password']; ?></td>
                            <td><i class="fa fa-whatsapp" style="color: green"></i> 
                              <?php echo $tampilMas['whatsapp']; ?></td>
                            <td><i class="fas fa-map-marker-alt" style="color: red"></i>
                              <?php echo $tampilMas['alamat']; ?></td>
                            <td>
                              <!-- <form method="post"><input type="hidden" value="">
                                <input type="hidden" name="idagen" value="<?php echo $tampilMas['idagen']; ?>">
                                <button type="submit" class="btn btn-danger" name="hapusagen" onclick="return confirm('Yakin Ingin Menghapus Data?');"><span class="fa fa-trash"></span></button>
                                <a class="btn btn-success" href="mitraubah.php?id=<?php echo $tampilMas['idagen']; ?>&jenis=<?php echo $tampilMas['kodeakses']; ?>"><span class="fa fa-edit"></span></a>
                              </form> -->
                              <form method="post"><input type="hidden" name="id" value=<?php echo $tampilMas['idmitraagen']; ?>>
                                 <button type="submit" class="btn btn-info" name="resetPassword"><span class="fa fa-power-off"></span></button>
                             </form>
                             <a href="ubah_password.php?id=<?= $tampilMas['idmitraagen']; ?>" type="button" class="btn btn-warning"><span class="fa fa-pencil"></span></a>
                            </td>
                          </tr>
                          <?php } ?>
                      </tbody>
                  </table>
              </div>
          </div>
          <!-- Tab Content for Reseller -->
          <div id="reseller" class="tab-pane fade show active">
              <h3 class="mb-3">Data Reseller</h3>
              <button class="btn btn-outline-success btn-icon-split mb-3"  onclick="JavaScript:window.location.href='subdb/excel_reseller.php';">
                  <span class="icon text-white-50 bg-success">
                      <i class="fas fa-download" style="margin-top:20%;"></i>
                  </span>
                  <span class="text">Download Excel Reseller</span>
              </button>
              <div class="table-responsive">
                  <table class="table table-striped table-bordered" id="tbreseller">
                      <thead>
                          <tr>
                          <th style="text-align: center">No</th>
                            <th style="text-align: center">Nama SubDB</th>
                            <th style="text-align: center"> Nama DB</th>
                            <th style="text-align: center">Email</th>
                            <th style="text-align: center">Password</th>
                            <th style="text-align: center">Whatsapp</th>
                            <th style="text-align: center"> Alamat</th>
                            <th style="text-align: right"><i class="fas fa-cog"></i></th>
                          </tr>
                      </thead>
                      <tbody>
                      <?php
                        $no=1;
                        $tampil =$koneksi->query("SELECT * FROM mitrareseller INNER JOIN admin_mitra ON mitrareseller.idadmin=admin_mitra.idadmin
                          ORDER BY mitrareseller.idmitrareseller DESC");
                        while($tampilMas=$tampil->fetch_assoc()){  
                      ?>
                      <tr>
                      <td><?php echo $no++; ?></td>
                          <td><i class="fas fa-users"></i> <?php echo $tampilMas['namaagen']; ?> (<?php echo $tampilMas['idmitrareseller']; ?>)</td>
                          <td><i class="fas fa-user"></i> <?php echo $tampilMas['namamitra']; ?></td>
                          <td><i class="fas fa-envelope" style="color: red"></i> 
                            <?php echo $tampilMas['email']; ?></td>
                            <td> 
                            <?php echo $tampilMas['password']; ?></td>
                          <td><i class="fa fa-whatsapp" style="color: green"></i> 
                            <?php echo $tampilMas['whatsapp']; ?></td>
                          <td><i class="fas fa-map-marker-alt" style="color: red"></i>
                            <?php echo $tampilMas['alamat']; ?></td>
                          <td>
                      </tr>
                      <?php } ?>
                      </tbody>
                  </table>
              </div>
          </div>
          <!-- Tab Content for Marketer -->
          <div id="marketer" class="tab-pane fade show active">
              <h3 class="mb-3">Data Marketer</h3>
              <button class="btn btn-outline-success btn-icon-split mb-3"  onclick="JavaScript:window.location.href='subdb/excel_marketer.php';">
                  <span class="icon text-white-50 bg-success">
                      <i class="fas fa-download" style="margin-top:20%;"></i>
                  </span>
                  <span class="text">Download Excel Marketer</span>
              </button>
              <div class="table-responsive">
                  <table class="table table-striped table-bordered" id="tbmarketer">
                      <thead>
                          <tr>
                          <th style="text-align: center">No</th>
                            <th style="text-align: center">Nama SubDB</th>
                            <th style="text-align: center"> Nama DB</th>
                            <th style="text-align: center">Email</th>
                            <th style="text-align: center">Password</th>
                            <th style="text-align: center">Whatsapp</th>
                            <th style="text-align: center"> Alamat</th>
                            <th style="text-align: right"><i class="fas fa-cog"></i></th>
                          </tr>
                      </thead>
                      <tbody>
                      <?php
                        $no = 1;
                        $tampil = $koneksi->query("SELECT * FROM mitramarketer INNER JOIN admin_mitra ON mitramarketer.idadmin=admin_mitra.idadmin ORDER BY idmitramarketer DESC");
                        while($tampilMas=$tampil->fetch_assoc()){  
                      ?>
                          <tr>
                            <td><?php echo $no++; ?></td>
                            <td><i class="fas fa-users"></i> <?php echo $tampilMas['namaagen']; ?> (<?= $tampilMas['idmitramarketer'] ?>)</td>
                            <td><i class="fas fa-user"></i> <?php echo $tampilMas['namamitra']; ?></td>
                            <td><i class="fas fa-envelope" style="color: red"></i> 
                              <?php echo $tampilMas['email']; ?></td>
                              <td> 
                              <?php echo $tampilMas['password']; ?></td>
                            <td><i class="fa fa-whatsapp" style="color: green"></i> 
                              <?php echo $tampilMas['whatsapp']; ?></td>
                            <td><i class="fas fa-map-marker-alt" style="color: red"></i>
                              <?php echo $tampilMas['alamat']; ?></td>
                          </tr>
                          <?php } ?>
                      </tbody>
                  </table>
              </div>
          </div>
        </div>
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

  <!-- PHP -->
   <?php
        if (isset($_POST["resetPassword"])) {
            $idmitraagen = $_POST['id'];
            $queryDb     = $koneksi->query("SELECT * FROM mitraagen WHERE idmitraagen = '$idmitraagen'");
            
            if ($queryDb && $queryDb->num_rows > 0) {
                $dataDb = $queryDb->fetch_assoc();
                $email  = $dataDb['email'];
                $idUser = $dataDb['iduser'];                             
                // Pastikan variabel $email tidak kosong
                if ($email) {
                    $newPassword = $email . '_' . $idmitraagen;
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updatePassword = $koneksi->query("UPDATE users SET password = '$hashedPassword' WHERE id = '$idUser'");
                    
                    if ($updatePassword) {
                        echo "<script>alert('Password berhasil diubah');</script>";
                        echo "<script>location='mitra.php';</script>";
                    } else {
                        echo "<script>alert('Gagal mengubah password');</script>";
                    }
                } else {
                    echo "<script>alert('Email tidak ditemukan');</script>";
                }
            } else {
                echo "<script>alert('Data admin tidak ditemukan');</script>";
            }
        }
   ?>
   
  <!-- PHP END -->

  <!-- Scroll to Top Button-->
<!--   <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a> -->

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
  <script type="text/javascript">
          $(document).ready( function () {
      $('#tbagen').DataTable({
          "pageLength": 10
      });
  } );
  </script>
  <script type="text/javascript">
          $(document).ready( function () {
      $('#tbmarketer').DataTable({
          "pageLength": 10
      });
  } );
  </script>
  <script type="text/javascript">
          $(document).ready( function () {
      $('#tbreseller').DataTable({
          "pageLength": 10
      });
  } );
  </script>
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

		                    