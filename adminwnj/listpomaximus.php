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

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
           
          </div>
          <h3><strong>List PO Maximus</strong></h3><br>
<a class="btn btn-warning" href="sumpomaximus.php">Summary PO Maximus</a> <a class="btn btn-info" href="sumpomaximusdb.php">Summary PO PerDB</a>

<hr>
          <!-- Content Row -->
          <div class="row">


</div>
<div class="table-responsive">
<table class="table table-striped" id="tbmaximus">
                     <thead>
          <tr>       
            <th>Check</th>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama DB</th>
            <th>Nama Sub DB</th>
            <th>Status Mitra</th>
            <!--<th>Nama Keluarga</th>
            <th>Alamat</th>-->
            <th>Status</th>
            <th>Invoice</th>
            <th>Opsi</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            $datapo=$koneksi->query("SELECT admin_mitra.namamitra,poproduk.namapo,mitraagen.namaagen as agen,mitrareseller.namaagen as reseller,mitramarketer.namaagen as marketer,pomaximus.invoice,pomaximus.status,pomaximus.tgl FROM pomaximus LEFT JOIN mitraagen on pomaximus.idmitraagen=mitraagen.idmitraagen LEFT JOIN mitrareseller on pomaximus.idmitrareseller=mitrareseller.idmitrareseller LEFT JOIN mitramarketer on pomaximus.idmitramarketer=mitramarketer.idmitramarketer LEFT JOIN admin_mitra on pomaximus.idadmin=admin_mitra.idadmin or mitraagen.idadmin=admin_mitra.idadmin or mitrareseller.idadmin=admin_mitra.idadmin or mitramarketer.idadmin=admin_mitra.idadmin INNER JOIN poproduk on pomaximus.idpoproduk=poproduk.idpoproduk 
                               GROUP BY pomaximus.invoice ORDER BY pomaximus.idpomaximus DESC");
                            $no=$mulai+1;
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
                <?php echo $tampilkan['agen']; ?> <?php echo $tampilkan['reseller']; ?> <?php echo $tampilkan['marketer']; ?>
              </td>
              <td class="align-middle"><?php if($tampilkan['agen']<>''){ echo "Agen";}
                  if($tampilkan['reseller']<>''){ echo "Reseller";}
                  if($tampilkan['marketer']<>''){ echo "Marketer";}
                  if($tampilkan['agen']=='' and $tampilkan['reseller']=='' and $tampilkan['marketer']=='' ){ echo "Distributor";} ?></td>
                <!--<td>
                <?php echo $tampilkan['namapenerima']; ?>
              </td>
              <td>
                <?php echo $tampilkan['alamatpenerima']; ?>
              </td>>-->
              <td>
                <?php echo $tampilkan['status']; ?>
              </td>
              <td>
                <a href="detailinvoice_maximus.php?invoice=<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['invoice']; ?></a>
              </td>
              <td>
              <form method="post">
              <input type="hidden" value="<?php echo $tampilkan['invoice']; ?>" name="invoice">
              <button class="btn btn-danger" name="reset">Reset</button>
              </form>
              </td>                  
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

      <?php
      if(isset($_POST["reset"])){
	
      $invoice = $_POST['invoice'];

      $query = "DELETE FROM pomaximus where invoice='$invoice'";
      $sql = mysqli_query( $koneksi, $query);
      
      echo "<script>alert('Invoice telah di reset');</script>";
      echo "<script>location='listpomaximus.php';</script>";
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

<?php include "settingdatatables.php"; ?>

</body>

</html>

                        