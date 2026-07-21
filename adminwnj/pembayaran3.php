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
        
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
              
              <h3><strong>Order Pembayaran</strong></h3>

          <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Distributor</a></li>
          <li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Agen</a></li>
          <li><a data-toggle="tab" href="#menu2" class="nav-item nav-link">Reseller</a></li>
          <li><a data-toggle="tab" href="#menu3" class="nav-item nav-link"> Marketer</a></li>
        </ul>      
    <div class="tab-content">
    <div id="home" class="tab-pane fade show active" id="home"  role="tabpanel">                     
 <p>Pembayaran DB</p>  
    
    <div class="table-responsive">
    <table class="table table-bordered" id="tb_pembayaran_db">
      <thead>
      <tr>
      <th>No</th>
      <th>Check</th>
      <th>Payment</th>
      <th>Nama Mitra</th>
      <th>Bank Pengirim</th>
      <th>Rekening / Nama Pengirim</th>
      <th>Jumlah Transfer</th>
      <th>Metode Pembayaran</th>
      <th>No Order</th>
      <th>Tanggal / Waktu TF</th>
      </tr>
      </thead>
      <tbody>
      <?php 
      
        $datapo=$koneksi->query("SELECT admin_mitra.namamitra,
          ordermitra.tgl as tglorder,
          ordermitra.invoice,
          ordermitra.payment,
          orderpembayaran.bankpengirim,
          orderpembayaran.rekeningpengirim,
          orderpembayaran.jmlhtransfer,
          orderpembayaran.metodebayar,
          orderpembayaran.tgl as tgltf,
          orderpembayaran.waktu 
          FROM `ordermitra` 
          inner join orderpembayaran on ordermitra.invoice=orderpembayaran.invoice
          inner join admin_mitra on ordermitra.idmitra=admin_mitra.idadmin
          WHERE orderpembayaran.tgl > '2022-05-01'
          Group by ordermitra.invoice 
          ORDER BY orderpembayaran.idpembayaran DESC LIMIT 100");
        $no=1;
      
      while($tampilkan=$datapo->fetch_assoc()){
      ?>
      <tr>
      <td><?php echo $no++; ?></td>
      <td>
        <?php if ($tampilkan['payment']=="Lunas" or $tampilkan['payment']=="LUNAS") {
          echo "OK";
        }else{ ?>
        <input type="checkbox" class="check-item" name="invoice[]" value="<?php echo $tampilkan['invoice']; ?>" class="form-control">
      <?php } ?>
      </td>     
      <td><?php echo $tampilkan['payment']; ?></td>
      <td><?php echo $tampilkan['namamitra']; ?></td>
      <td><?php echo $tampilkan['bankpengirim']; ?></td>
      <td><?php echo $tampilkan['rekeningpengirim']; ?></td>
      <td><?php echo $tampilkan['jmlhtransfer']; ?></td>
      <td><?php echo $tampilkan['metodebayar']; ?></td>
      <td><a href="detailorder.php?invoice=<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['invoice']; ?></a></td>
      <td><?php echo $tampilkan['tgltf']; ?> / <?php echo $tampilkan['waktu']; ?></td>
      
      </tr>
      <?php } ?>
      </tbody>
    </table>
    <button type="submit" class="btn btn-success" name="done">Selesai</button>
    </div>

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
  <?php include 'settingdatatables.php'; ?>

<script type="text/javascript">

    $(document).ready(function(){
        $('#db').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var iddb = $('#db').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_jenis_mitra.php',
                data :  'iddb=' + iddb,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tabel").html(data);
                }
                
            });
        });


        
    });
</script> 

</body>

</html>

                                                    