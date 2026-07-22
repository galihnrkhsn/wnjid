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

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          <div class="row">

<h3><strong>Saldo Agen</strong></h3>

<table class="table table-striped">
                      <thead>
                        <tr>
                             <th>
                            No
                          </th>
                            <th>
                            Nama Agen
                          </th> <th>
                            Nama DB
                          </th>
                          <th>
                            Tanggal
                          </th>
                          <th>
                           Keterangan
                          </th>
                          <th>
                           Masuk
                          </th>
                          <th>
                            Keluar
                          </th>
                           <th>
                            Opsi
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                           include "koneksi.php";
                           if(isset($_POST["cari"])){
                          $namaagen=$_POST["namaagen"];
                           $halaman = 50; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT * FROM saldoagen");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
        
                        $datamitra=$koneksi->query("SELECT mitraagen.namaagen,saldoagen.id_saldo,saldoagen.tgl,saldoagen.transaksi,saldoagen.debit,saldoagen.credit FROM saldoagen inner join mitraagen ON 
                                                    saldoagen.idmitraagen=mitraagen.idmitraagen where mitraagen.namaagen = '$namaagen' order by saldoagen.tgl desc LIMIT $mulai, $halaman");
                         $datasisa=$koneksi->query("SELECT mitraagen.namaagen,saldoagen.debit,saldoagen.credit,(sum(saldoagen.debit) - sum(saldoagen.credit)) AS sisa FROM saldoagen inner join mitraagen ON 
        saldoagen.idmitraagen=mitraagen.idmitraagen where mitraagen.namaagen = '$namaagen'");                            
                            $no    =$mulai+1;
                          $tampilin=$datasisa->fetch_assoc();
                          }elseif(isset($_POST["tampil"])){
                           $halaman = 50; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT * FROM saldoagen");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
        
                        $datamitra=$koneksi->query("SELECT mitraagen.namaagen,saldoagen.id_saldo,saldoagen.tgl,saldoagen.transaksi,saldoagen.debit,saldoagen.credit FROM saldoagen inner join mitraagen ON 
                                                    saldoagen.idmitraagen=mitraagen.idmitraagen order by saldoagen.tgl desc LIMIT $mulai, $halaman");
                            $no    =$mulai+1;
                            
                          }else{
                                $halaman = 50; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT * FROM saldoagen");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
        
                        $datamitra=$koneksi->query("SELECT mitraagen.namaagen,saldoagen.id_saldo,saldoagen.tgl,saldoagen.transaksi,saldoagen.debit,saldoagen.credit FROM saldoagen inner join mitraagen ON 
                                                    saldoagen.idmitraagen=mitraagen.idmitraagen order by saldoagen.tgl desc LIMIT $mulai, $halaman");
                        
                             $datasisa=$koneksi->query("SELECT mitraagen.namaagen,saldoagen.debit,saldoagen.credit,(sum(saldoagen.debit) - sum(saldoagen.credit)) AS sisa FROM saldoagen inner join mitraagen ON 
        saldoagen.idmitraagen=mitraagen.idmitraagen where mitraagen.namaagen ");                            
                            $no    =$mulai+1;
                          $tampilin=$datasisa->fetch_assoc();
                          }    
                        while($tampilkan=$datamitra->fetch_assoc()){
                         ?>
                        <tr>
                          <td><?php echo $no++; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['namaagen']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['tgl']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['transaksi']; ?>
                          </td>
                          <td>
                           <?php echo number_format($tampilkan['debit']); ?>
                          </td>
                          <td>
                            <?php echo number_format($tampilkan['credit']); ?>
                          </td>
                          <td>
                           <a href="editsaldoagen.php?id_saldo=<?php echo $tampilkan['id_saldo']; ?>">Edit</a>
                          </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="5"><strong>SISA SALDO</strong></td>
                            <td><strong>Rp. <?php echo number_format($tampilin['sisa']); ?></strong></td>
                        </tr>    
                      </tbody>
                    </table>
                    
                      <div style="font-weight:bold;">
                            Halaman
                            <?php
                            for ($i=1; $i<=$pages ; $i++){
                            ?>
                            <a href="saldoagen.php?halaman=<?php echo $i; ?>" style="text-decoration:none">   <u><?php echo $i; ?></u></a>
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

		                    