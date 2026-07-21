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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  
  <style type="text/css">
    .switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
  </style>

</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

<?php include "sidebar.php"; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><strong>Data CS Sales</strong></h1>
          </div>

           
          <!-- Content Row -->
          <div class="row">
             
            
<a class="btn btn-primary btn-icon-split" href="input_cssales.php">
	<span class="texy-white-50 icon"><i class="fas fa-plus"></i></span>
	<span class="text">Tambah CS Sales</span>
</a>


<div class="table-responsive" style="margin-top: 3%">
    <table class="table table-striped table-bordered table-hover" id="tbmitra">
                      <thead>
                        <tr>
                            <th>
                            Status    
                            </th>
                          <th style="text-align: center">
                            Nama CS Sales
                          </th>
                          <th style="text-align: center">
                            Email
                          </th>
                          <th style="text-align: center">
                            Whatsapp
                          </th>
                          <th style="text-align: center">
                            Alamat
                          </th>
                           <th style="text-align: right;">
                            <i class="fas fa-cog"></i>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        include "koneksi.php";
                        $tampil = $koneksi->query("SELECT * FROM `cssales`");
                        while ($tampilMas = $tampil->fetch_assoc()) {
                          ?>
                          <tr>
                            <td class="py-1">
                              <form method="post">
                                <input type="hidden" name="id" value="<?php echo $tampilMas['idcssales']; ?>">
                                <input type="hidden" name="nilaistatus" value="<?php echo $tampilMas['status']; ?>">

                                <label class="switch">
                                  <input type="checkbox" name="gantistatus" onclick="this.form.submit()" <?= $tampilMas['status'] == 0 ? 'checked' : ''; ?>>
                                  <span class="slider round"></span>
                                </label>
                              </form>
                            </td>
                            <td>
                              <i class="fas fa-user"></i> <?php echo $tampilMas['namacs']; ?>
                            </td>
                            <td>
                              <i class="fas fa-envelope" style="color: red"></i> <?php echo $tampilMas['email']; ?>
                            </td>
                            <td>
                              <i class="fa fa-whatsapp" style="color: green"></i> <?php echo $tampilMas['notlp']; ?>
                            </td>
                            <td>
                              <i class="fas fa-map-marker-alt" style="color: red"></i> <?php echo $tampilMas['alamat']; ?>
                            </td>
                            <td>
                              <form method="post">
                                <input type="hidden" name="id" value="<?php echo $tampilMas['idcssales']; ?>">
                                <input type="hidden" name="iduser" value="<?php echo $tampilMas['iduser']; ?>">
                                <button type="submit" class="btn btn-danger" name="hapus" onclick="return confirm('Yakin Hapus CS <?php echo $tampilMas['namacs']; ?> ?')">
                                  <span class="fa fa-trash"></span>
                                </button>
                                <button type="submit" class="btn btn-warning" name="reset" onclick="return confirm('Reset Password Akun <?php echo $tampilMas['namacs']; ?> ?')">
                                  <span class="fa fa-key"></span>
                                </button>
                              </form>
                            </td>
                          </tr>
                        <?php } ?>
                      </tbody>

                    </table>
                    
                    
                        
                        <?php
                          if(isset($_POST["hapus"])){
                            include "koneksi.php";
                            $idcssales = $_POST['id'];
                            $iduser = $_POST['iduser'];
                            $queryDel1 = "DELETE from cssales where idcssales='$idcssales'";
                            $queryDel2 = "DELETE from users where id='$iduser'";
                            $sql = mysqli_query($koneksi, $queryDel1);
                            $sql2 = mysqli_query($koneksi, $queryDel2);
                            if ($sql && $sql2) { 
                              echo "<script>alert('Data berhasil dihapus.'); window.location.href = 'cssales.php';</script>";
                              exit();
                            } else {
                              echo "<script>alert('Gagal menyimpan data.'); window.location.href = 'cssales.php';</script>";
                            }
                          }

                        if(isset($_POST["reset"])){
  
                         include "koneksi.php";
                         $idcssales= $_POST['id'];
                         $koneksi->query("UPDATE cssales SET password='cswnjweb' where idcssales='$idcssales';");
                                echo "<script>alert('Password Akun Telah direset');</script>";
                                echo "<script>location='cssales.php';</script>";
                                       }                 

            				   if(isset($_POST["gantistatus"])){
                           include "koneksi.php";    
                           $idcssales= $_POST['id'];
                           $nilaistatus= $_POST['nilaistatus'];

                           if($nilaistatus=="1"){
                                $koneksi->query("UPDATE cssales SET status=0 where idcssales='$idcssales';");
                                    echo "<script>alert('data sudah terupdate');</script>";
                                    echo "<script>location='cssales.php';</script>";
                            }
                            else{
                                $koneksi->query("UPDATE cssales SET status=1 where idcssales='$idcssales';");
                                    echo "<script>alert('cssales sudah terupdate');</script>";
                                    echo "<script>location='cssales.php';</script>";
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

<?php include "settingdatatables.php"; ?>

</body>

</html>

		                    