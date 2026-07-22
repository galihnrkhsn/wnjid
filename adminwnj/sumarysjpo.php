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


        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Surat Jalan PO</h1>
            <h1 class="h3 mb-0 text-gray-800"><a href="suratjalan_po.php"><i class="fa fa-arrow-left"></i> Kembali</a></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Surat Jalan</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#menu1Ambil" class="nav-item nav-link active">Per Ambil Barang</a></li>
          <li><a data-toggle="tab" href="#menu2Checker" class="nav-item nav-link">Per Checker</a></li>
        </ul>                                    
    <div class="tab-content">
<div id="menu1Ambil" class="tab-pane fade show active">    
<br>
<label>Per Ambil Barang</label>

<div class="table-responsive">
    <table class="table table-bordered" id="tbmitra">
        <thead>
        <tr>
            <th>No</th>
                <th>Invoice</th>
            <th>Nama DB</th>
            <th>Nama Sub-DB</th>
                <th>Variant</th>
                <th>QTY</th>
                <th>Status</th>
                <th>Waktu</th>
                </tr>
        </thead>
        <tbody>
              <?php 
             
                $data_sj=$koneksi->query("SELECT surat_jalan_po.invoice,
                                            surat_jalan_po.progres,
                                            surat_jalan_po.status,
                                            surat_jalan_po.waktu,
                                            SUM(surat_jalan_po.progres) as jumlahnya,
                                            surat_jalan_po.id_sj,
                                            surat_jalan_po.no_sj,
                                            pomitra.idpoproduk,
                                            admin_mitra.idadmin,
                                            admin_mitra.namamitra,
                                            mitraagen.idmitraagen as idagen,
                                            mitrareseller.idmitrareseller as idreseller,
                                            mitramarketer.idmitramarketer as idmarketer,
                                            mitraagen.namaagen as agen,
                                            mitrareseller.namaagen as reseller,
                                            mitramarketer.namaagen as marketer,
                                            podetail.variant
                                            FROM surat_jalan_po
                                            JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                            JOIN podetail ON podetail.idpodetail = surat_jalan_po.idpodetail
                                            left join mitraagen on mitraagen.idmitraagen = pomitra.idmitraagen
                                            left join mitrareseller on mitrareseller.idmitrareseller = pomitra.idmitrareseller
                                            left join mitramarketer on mitramarketer.idmitramarketer = pomitra.idmitramarketer
                                            left join admin_mitra on (admin_mitra.idadmin = pomitra.idmitra or 
                                                                      admin_mitra.idadmin = mitraagen.idadmin or
                                                                      admin_mitra.idadmin = mitrareseller.idadmin or
                                                                      admin_mitra.idadmin = mitramarketer.idadmin)
                                            WHERE surat_jalan_po.status = 'Ambil Barang' and surat_jalan_po.progres>0
                                            GROUP BY surat_jalan_po.id_sj ORDER BY surat_jalan_po.invoice asc
                                            LIMIT 1000
                                            ");
                $no=1;
              
                while($tampilkan_sj=$data_sj->fetch_assoc()){
                   $no_sj=$tampilkan_sj['no_sj'];
                ?>
                <tr>
                    <td>
                     <?php echo $no++; ?>
                </td>   
                <td>
                    <?= $tampilkan_sj['invoice'] ?>
                  </td>
                <td><?php echo $tampilkan_sj['namamitra']; ?> (<?php echo $tampilkan_sj['idadmin']; ?>)</td>
                <td>
                  <?=  $tampilkan_sj['agen']; ?> 
                  <?=  $tampilkan_sj['reseller']; ?>
                  <?=  $tampilkan_sj['marketer']; ?>
                  (
                  <?=  $tampilkan_sj['idagen']; ?> 
                  <?=  $tampilkan_sj['idreseller']; ?>
                  <?=  $tampilkan_sj['idmarketer']; ?>
                  )
                </td>  
                  
                  <td>
                    <?= $tampilkan_sj['variant'] ?>     
                  </td>
                  <td>
                   <?php echo $tampilkan_sj['jumlahnya']; ?>
                  </td> 
                  <td>
                   <?php echo $tampilkan_sj['status']; ?>
                  </td> 
                                   
                  <td>
                   <?php echo $tampilkan_sj['waktu']; ?>
                  </td>
                        </tr>
            <?php } ?>
          </tbody>
        </table>
</div>
</div>
<div id="menu2Checker" class="tab-pane fade">    
<br>
<label>Per Checker</label>

<div class="table-responsive">
    <table class="table table-bordered" id="tborderdb_">
        <thead>
        <tr>
            <th>No</th>
            <th>No Surat Jalan</th>
                <th>Invoice</th>
            <th>Nama DB</th>
            <th>Nama Sub-DB</th>
                <th>QTY</th>
                <th>Status</th>
                <th>Waktu</th>
                </tr>
        </thead>
        <tbody>
              <?php 
             
                $data_sj=$koneksi->query("SELECT surat_jalan_po.invoice,
                                            surat_jalan_po.progres,
                                            surat_jalan_po.status,
                                            surat_jalan_po.waktu,
                                            SUM(surat_jalan_po.progres) as jumlahnya,
                                            surat_jalan_po.id_sj,
                                            surat_jalan_po.no_sj,
                                            pomitra.idpoproduk,
                                            admin_mitra.idadmin,
                                            admin_mitra.namamitra,
                                            mitraagen.idmitraagen as idagen,
                                            mitrareseller.idmitrareseller as idreseller,
                                            mitramarketer.idmitramarketer as idmarketer,
                                            mitraagen.namaagen as agen,
                                            mitrareseller.namaagen as reseller,
                                            mitramarketer.namaagen as marketer
                                            FROM surat_jalan_po
                                            JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                            left join mitraagen on mitraagen.idmitraagen = pomitra.idmitraagen
                                            left join mitrareseller on mitrareseller.idmitrareseller = pomitra.idmitrareseller
                                            left join mitramarketer on mitramarketer.idmitramarketer = pomitra.idmitramarketer
                                            left join admin_mitra on (admin_mitra.idadmin = pomitra.idmitra or 
                                                                      admin_mitra.idadmin = mitraagen.idadmin or
                                                                      admin_mitra.idadmin = mitrareseller.idadmin or
                                                                      admin_mitra.idadmin = mitramarketer.idadmin)
                                            WHERE surat_jalan_po.status = 'Checker' and surat_jalan_po.progres>0
                                            GROUP BY surat_jalan_po.no_sj ORDER BY surat_jalan_po.id_sj desc
                                            LIMIT 1000
                                            ");
                $no=1;
              
                while($tampilkan_sj=$data_sj->fetch_assoc()){
                   $no_sj=$tampilkan_sj['no_sj'];
if ($tampilkan_sj['marketer']!='') {
$idmitra = $tampilkan_sj['idmarketer'];   
$mitra = 'M';              
  }
  elseif ($tampilkan_sj['reseller']!='') {
$idmitra = $tampilkan_sj['idreseller'];   
$mitra = 'R'; 
  }
  elseif ($tampilkan_sj['agen']!='') {
$idmitra = $tampilkan_sj['idagen'];   
$mitra = 'A'; 
  }
  else{
$idmitra = $tampilkan_sj['idadmin'];   
$mitra = 'D';     
  }                  
                ?>
                <tr>
                    <td>
                     <?php echo $no++; ?>
                </td>  
                <td>
                  <a href="suratjalan_po_print_checker.php?no_sj=<?= $tampilkan_sj['no_sj'] ?>&idadmin=<?= $idmitra ?>&mitra=<?= $mitra; ?>" target="_blank"><i class="fa fa-print"></i> <?php echo $tampilkan_sj['no_sj']; ?></a>
                  </td>                 
                <td>
            <?php 
            $data_invoice_sj=$koneksi->query("SELECT surat_jalan_po.invoice
                                            
                                            FROM surat_jalan_po
                                            
                                            WHERE surat_jalan_po.no_sj = '$no_sj'
                                            GROUP BY surat_jalan_po.invoice

                                    ");
                            while($tampilkan_invoice_sj=$data_invoice_sj->fetch_assoc()){
                              echo $tampilkan_invoice_sj['invoice'];
                              echo "<br>";
                            }
            ?>     
                  </td>
                <td><?php echo $tampilkan_sj['namamitra']; ?> (<?php echo $tampilkan_sj['idadmin']; ?>)</td>
                <td>
                  <?=  $tampilkan_sj['agen']; ?> 
                  <?=  $tampilkan_sj['reseller']; ?>
                  <?=  $tampilkan_sj['marketer']; ?>
                  (
                  <?=  $tampilkan_sj['idagen']; ?> 
                  <?=  $tampilkan_sj['idreseller']; ?>
                  <?=  $tampilkan_sj['idmarketer']; ?>
                  )
                </td>  
                  
                  <td>
                   <?php echo $tampilkan_sj['jumlahnya']; ?>
                  </td> 
                  <td>
                   <?php echo $tampilkan_sj['status']; ?>
                  </td> 
                                   
                  <td>
                   <?php echo $tampilkan_sj['waktu']; ?>
                  </td>
                        </tr>
            <?php } ?>
          </tbody>
        </table>
</div>
</div>


</div>



                                </div>
                            </div>
                        </div>           



    


   



          </div>


        <!-- </div> -->
                                
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



<script type="text/javascript">

    $(document).ready(function(){

        $('#jenis_mitra').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var jenis_mitra = $('#jenis_mitra').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_per_jenismitra_po.php',
                data :  'jenis_mitra=' + jenis_mitra,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tabel_suratjalan_po").html(data);
                }
                
            });
        });




        
    });
</script> 
<?php include "settingdatatables.php"; ?>

</body>

</html>

                                                    