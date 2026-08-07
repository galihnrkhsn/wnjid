<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$invoice=$_GET["invoice"];

	$sql = "SELECT * FROM orderpengiriman WHERE invoice='$invoice' ";
	$query = $koneksi->query($sql);
	$pengiriman = $query->fetch_assoc();
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

                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Bayar PO Manual</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div>

<form method="post">  

  
    <div class="form-group">
          <label>Pilih Nama PO</label>
          <select class="form-control" name="idpoproduk" id="idpoproduk" required>
              <option value="" selected>- Pilih Nama PO -</option>
              <?php
              $datadb=$koneksi->query("SELECT * FROM poproduk ORDER BY idpoproduk desc");
              while($tampilkan=$datadb->fetch_assoc()){
              ?>
          <option value="<?php echo $tampilkan['idpoproduk']; ?>|Lunas"><?php echo $tampilkan['namapo']; ?></option>
          <?php } ?>                       
          </select>      
    </div>
<div  id="tabel" name="tabel"></div>       
    <div class="form-group">
          <label>Tanggal</label>
          <input type="date" class="form-control" name="tgl" required>    
    </div>      
     
<br>  
  <br>
<button type="submit" name="but_save_po" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Bayar Pelunasan</button>
<a href="listpopembayaran.php" class="btn btn-danger" style="float: right;">Kembali</a>     
</form>
                                    </div>
                                </div>
                            </div>
                        </div>           





  </div>        

</div>

<?php 

 if(isset($_POST['but_save_po'])){
      date_default_timezone_set('Asia/Jakarta');
        $waktu=date('H:i:s');

   $idadmin=$_POST["idadmin"];
  $result_explode = explode('|', $idadmin);
  $idadminnya=$result_explode[0];
  $invoicenya=$result_explode[1];  

   $id=$_POST["idpoproduk"];

$result_explode = explode('|', $id);
$idpoproduk=$result_explode[0];
   $tgl=$_POST["tgl"];
   $bank=$_POST["bank"];
   $norek=$_POST["norek"]; 
   $jmlh=$_POST["jmlh"]; 
   $metodebayar=$_POST["metodebayar"];

$explode_metode = explode(' ', $metodebayar);
$banknya = $explode_metode[0];

if ($banknya=="Bank") {
  $banknya="BSI";
}

$tgl_explode = explode('-', $tgl);

$bulan = date("F", mktime(0, 0, 0, $tgl_explode[1], 10));

$hari = $tgl_explode[2];   

$datapo=$koneksi->query("SELECT jmlh_lunas, jmlhtransfer FROM popembayaran
                            where invoice='$invoicenya'");
$tampilpo=$datapo->fetch_assoc();   

$jmlh_tf = $tampilpo['jmlhtransfer'];         

   $datatf=$koneksi->query("SELECT SUM(pomitra.total) as total, poproduk.namapo FROM pomitra
                                JOIN poproduk ON poproduk.idpoproduk = pomitra.idpoproduk

                                WHERE pomitra.invoice='$invoicenya'");
                    $tampilkantf=$datatf->fetch_assoc();
                    $total=$tampilkantf["total"];
                    $namapo=$tampilkantf["namapo"];
                    $diskon = $total * 35/100;
                    $totalbayar = $total - $diskon;

                    $jmlh_pelunasan = $jmlh_tf+$jmlh;

                    if ($jmlh_pelunasan >= $totalbayar) {
                      $status = "Lunas";
                    }else{
                      $status = "Sudah DP";
                    }          

         // echo "<script>alert('$idadminnya, $invoicenya, $idpoproduk, $tgl, $bank, $norek, $jmlh, $metodebayar, $status');</script>";
                      
     $sqlnya = $koneksi->query("UPDATE popembayaran set jmlh_lunas ='$jmlh', 
                                                      bankpengirim = '$bank', 
                                                      rekeningpengirim = '$norek', 
                                                      metodebayar='$metodebayar'
                                                      WHERE invoice='$invoicenya'");        
                if ($sqlnya) {

                  if ($metodebayar=="Deposit") {
$koneksi->query("INSERT INTO saldo (id_saldo,idadmin,tgl,transaksi,debit,credit)
                VALUES (null,'$idadminnya','$tgl','Bayar Pelunasan $banknya $hari $bulan $namapo #$invoicenya (Deposit)','0','$jmlh')");
                                    }
                                    else{
$koneksi->query("INSERT INTO saldo (id_saldo,idadmin,tgl,transaksi,debit,credit)
                VALUES (null,'$idadminnya','$tgl','Bayar Pelunasan $banknya $hari $bulan $namapo #$invoicenya','$jmlh','0')");                                                      
                                    }                  





$koneksi->query("UPDATE pomitra set status='$status' WHERE invoice='$invoicenya';"); 
                  echo "<script>alert('data berhasil disimpan');</script>";
                  echo "<script>location='bayarlunas.php';</script>";  
                  }else{
                    echo "<script>alert('data gagal disimpan');</script>";
                    echo "<script>location='bayarlunas.php';</script>";
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


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script src="assets/dist/js/jquery.min.js"></script>
    <script src="assets/dist/js/bootstrap.min.js"></script>
    <script src="assets/dist/DataTables/datatables.min.js"></script>


<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_surat_manual_po').DataTable({
        "lengthMenu": [[25, 50, 100, 200, 300, 400], [25, 50, 100, 200 , 300, 400]]
});
} );
</script>

<script type="text/javascript">

    $(document).ready(function(){
        $('#idpoproduk').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var idpoproduk = $('#idpoproduk').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_bayardp.php',
                data :  'idpoproduk=' + idpoproduk,
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

		                                                