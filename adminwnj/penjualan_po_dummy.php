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
  <style type="text/css">
    body{
      padding-right: 0px !important;
    }
  </style>

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
            <h1 class="h3 mb-0 text-gray-800">Penjualan Pre Order</h1>
          </div>

          <div class="table-responsive">
            <div class="form-group col-4">
              <label>Filter</label>
              <select  class="form-control" name="filter" id="filter">
                  <option value="Harian">Harian</option>
                  <option value="Bulanan">Bulanan</option>
              </select>  
            </div>

            <div id="tfilter" name="tfilter"></div> 
          </div> 

          <?php if (isset($_POST["cari"])): ?>
          <?php
            $filter=$_POST["filter"];
            $tanggal=$_POST["tanggal"];
            $cs=$_POST["namacs"]; 

            if ($filter=='Bulanan') {
              $tahunsekarang = date('Y');
              $tanggalnyaaa = $tahunsekarang.'-'.$tanggal;
            }

            $namacs=$cs;
            if ($cs=="Semua CS") {
              $namacs = "";
            }
          ?>
            <div class="table-responsive col mt-2">
              <form method="post" action="excel_penjualan_po.php" target="_blank">
                <input type="hidden" name="filter" value="<?= $filter ?>">
                <input type="hidden" name="tanggal" value="<?= $tanggal ?>">
                <input type="hidden" name="namacs" value="<?= $namacs ?>">
                <?php if ($filter=="Harian"):
                  $tanggal2=$_POST["tanggal2"];
                ?>
                  <input type="hidden" name="tanggal2" value="<?= $tanggal2 ?>">      
                <?php endif ?>    
                <button type="submit" class="btn btn-success md-5" name="export_excel">Export Excel</button>
              </form>
              <br>
              <form method="post" action="excel_penjualan_podb.php" target="_blank">
                <input type="hidden" name="filter" value="<?= $filter ?>">
                <input type="hidden" name="tanggal" value="<?= $tanggal ?>">
                <input type="hidden" name="namacs" value="<?= $namacs ?>">
                <?php if ($filter=="Harian"): 
                  $tanggal2=$_POST["tanggal2"];
                ?>
                  <input type="hidden" name="tanggal2" value="<?= $tanggal2 ?>">      
                <?php endif ?>    
                <button type="submit" class="btn btn-success md-5" name="export_excel">Export Excel DB</button>
              </form> 
              <form method="post" action="excel_penjualan_podb_custom.php" target="_blank">
                <input type="hidden" name="filter" value="<?= $filter ?>">
                <input type="hidden" name="tanggal" value="<?= $tanggal ?>">
                <input type="hidden" name="namacs" value="<?= $namacs ?>">
                <?php if ($filter=="Harian"): 
                  $tanggal2=$_POST["tanggal2"];
                ?>
                  <input type="hidden" name="tanggal2" value="<?= $tanggal2 ?>">      
                <?php endif ?>    
                <button type="submit" class="btn btn-success md-5" name="export_excel">Export Excel DB Custom</button>
              </form>
              
              <br>

              <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Pre Order</a></li>
                <li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Inv Manual</a></li>
              </ul> 
              <div class="tab-content">
                <div id="home" class="tab-pane fade show active" role="tabpanel">     
                  <table class="table table-bordered table-striped mt-3" id="tbmaximus">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>CS</th>
                        <th>Nama Mitra</th>
                        <th>Count</th>
                        <th>No Surat Jalan</th>
                        <th>Invoice</th>
                        <th>Produk</th>
                        <th>Harga Ecer</th>
                        <th>Harga DB</th>
                        <th>QTY</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                        if ($filter=="Harian") {
                          $datapo=$koneksi->query("SELECT surat_jalan_po.invoice,
                                                          SUM(surat_jalan_po.progres) as jumlah,
                                                          surat_jalan_po.progres,
                                                          surat_jalan_po.status,
                                                          surat_jalan_po.waktu,
                                                          admin_mitra.namamitra, 
                                                          admin_mitra.idadmin, 
                                                          mitraagen.idmitraagen as idagen,
                                                          mitrareseller.idmitrareseller as idreseller,
                                                          mitramarketer.idmitramarketer as idmarketer,
                                                          mitraagen.namaagen as agen,
                                                          mitrareseller.namaagen as reseller,
                                                          mitramarketer.namaagen as marketer,
                                                          admin_mitra_cs.namacs, 
                                                          surat_jalan_po.id_sj,
                                                          surat_jalan_po.no_sj,
                                                          podetail.variant,
                                                          podetail.harga
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
                                                          LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                          WHERE surat_jalan_po.status <> 'Ambil Barang' 
                                                          AND SUBSTRING(surat_jalan_po.waktu, 1, 10) BETWEEN '$tanggal' AND '$tanggal2'
                                                          AND admin_mitra_cs.namacs LIKE '%$namacs%'
                                                          GROUP BY surat_jalan_po.id_sj
                                                          ORDER BY surat_jalan_po.waktu DESC, surat_jalan_po.idpodetail desc, surat_jalan_po.invoice
                                                  ");
                        }
                        
                        if ($filter=="Bulanan") {
                          $datapo=$koneksi->query("SELECT surat_jalan_po.invoice,
                                                          SUM(surat_jalan_po.progres) as jumlah,
                                                          surat_jalan_po.progres,
                                                          surat_jalan_po.status,
                                                          surat_jalan_po.waktu,
                                                          admin_mitra.namamitra, 
                                                          admin_mitra.idadmin, 
                                                          mitraagen.idmitraagen as idagen,
                                                          mitrareseller.idmitrareseller as idreseller,
                                                          mitramarketer.idmitramarketer as idmarketer,
                                                          mitraagen.namaagen as agen,
                                                          mitrareseller.namaagen as reseller,
                                                          mitramarketer.namaagen as marketer,
                                                          admin_mitra_cs.namacs, 
                                                          surat_jalan_po.id_sj,
                                                          surat_jalan_po.no_sj,
                                                          podetail.variant,
                                                          podetail.harga
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
                                                          LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                          WHERE surat_jalan_po.status <> 'Ambil Barang' 
                                                          AND SUBSTRING(surat_jalan_po.waktu, 1, 7) LIKE '%$tanggalnyaaa%'
                                                          AND admin_mitra_cs.namacs LIKE '%$namacs%' 
                                                          GROUP BY surat_jalan_po.id_sj
                                                          ORDER BY surat_jalan_po.waktu DESC, surat_jalan_po.idpodetail desc, surat_jalan_po.invoice
                                                  ");
                        }                         

                        $no=1;
                        $namaMitraCounts = array();
                        
                        while($tampilkan=$datapo->fetch_assoc()){
                          $result_explode = explode(' ', $tampilkan['waktu']);
                          $tanggal_po=$result_explode[0];

                          $namaMitra = $tampilkan['namamitra'];

                          $no_sj = $tampilkan['no_sj'];
                          $invoice = $tampilkan['invoice'];
                          
                      ?>
                        <tr>
                          <td><strong><?php echo $no++; ?></strong></td>     
                          <td><?= date('d F Y', strtotime($tanggal_po)); ?></td>
                          <td><?= $tampilkan['namacs']; ?></td>
                          <td>
                            <?php 
                              if ($tampilkan['marketer']!='') {
                                echo $tampilkan['marketer'].'('.$tampilkan['idmarketer'].')'; 
                                $mitra = 'Marketer';              
                              } elseif ($tampilkan['reseller']!='') {
                                echo $tampilkan['reseller'].'('.$tampilkan['idreseller'].')'; 
                                $mitra = 'Reseller'; 
                              } elseif ($tampilkan['agen']!='') {
                                echo $tampilkan['agen'].'('.$tampilkan['idagen'].')';   
                                $mitra = 'Agen'; 
                              } else{
                                echo $namaMitra.'('.$tampilkan['idadmin'].')'; 
                                $mitra = 'Distributor';
                              }  
                            ?>                  
                          </td>
                          <td><?= $namaMitraCounts[$namaMitra] ?></td>
                          <td><?= $tampilkan['no_sj']; ?></td>
                          <td><?= $tampilkan['invoice']; ?></td> 
                          <td><?= $tampilkan['variant']; ?></td>  
                          <td><?= $tampilkan['harga']; ?></td>
                          <td><?= $tampilkan['harga']-($tampilkan['harga']*35/100); ?></td>             
                          <td><?= $tampilkan['progres']; ?></td>
                          <td>Rp. <?= number_format(($tampilkan['harga']-($tampilkan['harga']*35/100))*$tampilkan['progres']); ?></td>                 
                        </tr>
                      <?php 
                        $semuanya +=$tampilkan['harga']*$tampilkan['progres'];  
                        $ps += $tampilkan['progres'];
                      ?>

                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="9">Total</td>
                        <td><?= $ps; ?></td>
                        <td>Rp. <?= number_format($semuanya-(35/100*$semuanya)); ?></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div> 	
            </div>
          <?php endif ?>
 
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



        $('#filter').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var filter = $('#filter').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_filter.php',
                data :  'filter=' + filter,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tfilter").html(data);
                }
                
            });
        });  

        $('#filter').ready(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var filter = $('#filter').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_filter.php',
                data :  'filter=' + filter,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tfilter").html(data);
                }
                
            });
        });                




        
    });
  </script>

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/dist/js/jquery.min.js"></script>
  <script src="assets/dist/js/bootstrap.min.js"></script>
  <script src="assets/dist/DataTables/datatables.min.js"></script>
  <script type="text/javascript">
      $(document).ready( function () {
      $('#tb_sj_pr').DataTable();
  } );
  </script>
  <script type="text/javascript">
          $(document).ready( function () {
      $('#tbmaximus').DataTable();
  } );
  </script>
  <script type="text/javascript">
          $(document).ready( function () {
      $('#tbmaximus2').DataTable();
  } );
  </script> 

</body>

</html>

                                                    