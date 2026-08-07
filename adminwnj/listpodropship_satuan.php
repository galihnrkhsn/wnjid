<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}


$id=$_GET['id'];

  $query = "SELECT poproduk.idpoproduk,
            poproduk.namapo
        FROM poproduk 
        WHERE poproduk.idpoproduk='$id'";
  $sqlpo = mysqli_query($koneksi, $query);  
  $datapo = mysqli_fetch_array($sqlpo);
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

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

 <?php include "sidebar.php"; ?> 

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Semua Dropship<br><?= $datapo['namapo']; ?></h1>
            <h1 class="h3 mb-0 text-gray-800"><a href="listpodropship.php?id=<?= $id; ?>"><span class="fa fa-chevron-left"></span> Kembali</a></h1>

           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>
          <!-- Content Row -->
          <div class="row">

  <div class="table-responsive">
<form method="post" action="multiprintds.php" target="_blank">  
<input type='submit' class="btn btn-success mb-4" value='Cetak Alamat' name='but_export'>  
        <table class="table table-striped" id="tb_multiprint">
        <thead>
					<tr>
            <th><input type='checkbox' id='checkAll' > Check</th>
            <th>No</th>
            <th>Nama DB</th>
            <th>Nama Sub DB</th>
            <th>Invoice/No DS</th>
            <th>Data Pengiriman</th>
          </tr>
                      </thead>
                      <tbody>
                          <?php 
                            $datapo=$koneksi->query("SELECT 
                              admin_mitra.namamitra,
                              mitraagen.namaagen as agen, 
                              mitrareseller.namaagen as reseller, 
                              mitramarketer.namaagen as marketer,
                              podropship.invoice,
                              podropship.no_ds,
                              podropship.namapengirim,
                              podropship.tlppengirim,
                              podropship.namapenerima,
                              podropship.tlppenerima,
                              podropship.provinsi,
                              podropship.kota,
                              podropship.kecamatan,
                              podropship.iddropship 
                              FROM podropship
                              LEFT JOIN mitraagen on podropship.idmitraagen=mitraagen.idmitraagen 
                              LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=podropship.idmitrareseller 
                              LEFT JOIN mitramarketer on podropship.idmitramarketer=mitramarketer.idmitramarketer 
                              LEFT JOIN admin_mitra on (admin_mitra.idadmin=podropship.idadmin 
                                                      or mitraagen.idadmin=admin_mitra.idadmin 
                                                      or mitrareseller.idadmin=admin_mitra.idadmin 
                                                      or mitramarketer.idadmin=admin_mitra.idadmin)    
                              WHERE podropship.idpoproduk = '$id'
                              
                              GROUP BY podropship.iddropship order by podropship.iddropship desc");
                            $no=$mulai+1;
                          
                            while($tampilkan=$datapo->fetch_assoc()){
                              $id = $tampilkan['iddropship'];
                            ?>
                        <tr>
                         
                          <td><input type='checkbox' name='update[]' value='<?= $id ?>' ></td>
                          <td><?php echo $no++; ?></td>   
                          <td><?php echo $tampilkan['namamitra']; ?> </td>
                          <td><?php echo $tampilkan['agen']; ?> <?php echo $tampilkan['reseller']; ?> <?php echo $tampilkan['marketer']; ?></td>
                          <td><?php echo $tampilkan['invoice']; ?>/<?php echo $tampilkan['no_ds']; ?></td>
                          <td>
                              <strong>Pengirim</strong>
                          <br>
                              <?php echo $tampilkan['namapengirim']; ?><br><?php echo $tampilkan['tlppengirim']; ?>
                          <br>
                              <strong>Penerima</strong>
                          <br>
                              <?php echo $tampilkan['namapenerima']; ?><br><?php echo $tampilkan['tlppenerima']; ?>                            
                          </td>	
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
</form>                            
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
<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update[]"]').prop('checked',true);
                    }else{
                        $('input[name="update[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update[]"]').click(function(){
                    var total_checkboxes = $('input[name="update[]"]').length;
                    var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll').prop('checked',true);
                    }else{
                        $('#checkAll').prop('checked',false);
                    }
                });
            });
        </script>
</body>

</html>

		                                                