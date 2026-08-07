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

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          <h3><strong>Daftar Dropship</strong></h3>
<div class="row">

<div class="table-responsive">
  <table class="table table-striped" id="tb_listpo_artikel">
                      <thead>
                        <tr>
                            <th style="width:1%">
                             No
                            </th>
                            <th>
                            ID PO  
                          </th>    
                          <th>
                            Nama PO  
                          </th>     
                        </tr>
                      </thead>
                      <tbody>
                          <?php 

        
                            $datapo=$koneksi->query("SELECT poproduk.idpoproduk,poproduk.namapo
                              FROM poproduk 
                              GROUP BY poproduk.idpoproduk ORDER BY poproduk.idpoproduk DESC ");
                            $no=1;
                          
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan['idpoproduk']; ?>
                        </td>     
                          <td>
                           <a href="listpodropship.php?id=<?php echo $tampilkan['idpoproduk']; ?>"> <?php echo $tampilkan['namapo']; ?> </a>
                          </td>

              
                        </tr>
                        <?php } ?>
                      </tbody>
  </table>
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

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

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

                                                                                