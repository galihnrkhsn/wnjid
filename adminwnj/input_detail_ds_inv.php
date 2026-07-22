<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$iddropship = $_GET['id'];

$data_po=$koneksi->query("SELECT podropship.invoice 
                                FROM podropship 
                            where podropship.iddropship='$iddropship'");
$tampilkan_po=$data_po->fetch_assoc();
$invoice = $tampilkan_po['invoice'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">


  <title>Admin Pusat | Wanoja</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-metro.css">
  
  <style>
  .aws {
      border:8px solid #eff4ff;;
      
      padding: 10px;
      
      }
    .aws text
    {
        color: white;
        font-size: x-large;
        text-align: right;
    }
    .aws p
    {
        color: white;
        text-align: left;
        font-size: ;
       
    }
    .aws button 
    {
        text-align: left;
    }
       .aws a 
    {
        text-align: left;
    }
  </style>
  
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">
<!-- ==========================THIS========================== -->
<?php 
if($_SESSION["administrator"]["nama"]=='Produksi'){
include "sidebar2.php";     
} else{
include "sidebar.php";     
}
?>
<!-- ==========================THIS========================== -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard Admin Wanoja </h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>
          
          <!-- MULAI KONTEN AWS -->
            <div class="row w3-container">
<div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Invoice <?= $invoice;?></h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
  <div class="table-responsive">
<form method="post">    
<table class="table">
        <thead>
            <tr>
                <th><input type='checkbox' id='checkAll' > Check</th>
                <th>Nama Produk</th>
                <th>Stok Invoice</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
<?php 
      $ambil=$koneksi->query("SELECT podetail.variant, pomitra.jumlah , podetail.idpodetail, pomitra.custom, pomitra.idpomitra
                                FROM pomitra
                                JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                WHERE pomitra.invoice= '$invoice' 
                                AND pomitra.jumlah>0
                                ORDER BY pomitra.idpomitra ASC"); 
      while($data=$ambil->fetch_assoc()){
          $id = $data['idpodetail'];
          $idpomitra = $data['idpomitra'];
if ($id==8920) {
$data_jumlah=$koneksi->query("SELECT SUM(pods.jumlah) as progresnya 
                            FROM pods
                            JOIN pomitra on pomitra.idpomitra = pods.idpomitra
                            WHERE pods.invoice='$invoice'
                            and pods.idpodetail = '$id'
                            and pomitra.idpomitra = '$idpomitra'

                            ");
                            $tampilprogres=$data_jumlah->fetch_assoc();                                   
                                $kurang = $data['jumlah']-$tampilprogres['progresnya'];
}else{


$data_jumlah=$koneksi->query("SELECT SUM(pods.jumlah) as progresnya 
                            FROM pods
                            WHERE pods.invoice='$invoice'
                            and pods.idpodetail = '$id'
                            ");
                            $tampilprogres=$data_jumlah->fetch_assoc();                                   
                                $kurang = $data['jumlah']-$tampilprogres['progresnya'];             
}          
?>            
            <tr>
                <td>
<?php if($kurang>0): ?>
                    <input type='checkbox'  name='update[]' value='<?= $id ?>' >
                    <input type="hidden" name='idpomitra<?= $id ?>' value='<?= $idpomitra ?>'>
<?php endif; ?>
                </td>
                <td><?= $data['variant'];?> <?= $data['custom'];?></td>
                <td><input type='number' class="form-control" min="0" max="<?= $data['jumlah'];?>" name='jumlah<?= $id ?>' value='0' required></td>
                <td><?= $kurang;?></td>
            </tr>
      <?php } ?>            
        </tbody>
    </table><button type="submit" class="btn btn-primary" name="kirim">Kirim</button>
  </form>                                    
                                    
                                </div>
                            </div>
                        </div>                          
            </div>   
            
<?php 
if(isset($_POST['kirim'])){
      $sql_ds = mysqli_query($koneksi, "SELECT iddropship FROM podropship order by iddropship desc limit 1");
      $data = mysqli_fetch_array($sql_ds);
      $no=$data['iddropship'];
      $ab=1;
      $nobaru=$no+$ab;
      
      $no_ds = $invoice.'-'.$nobaru;

date_default_timezone_set('Asia/Jakarta');
$today = date("Y-m-d H:i:s");    
    
            if(isset($_POST['update'])){
$totaljum=3;
  if ($idpoproduk==186 or $idpoproduk==187) {
                  foreach($_POST['update'] as $updateid){

                      $jumlah = $_POST['jumlah'.$updateid];

                      $totaljum +=$jumlah;
                  }              
                }              
  if ($totaljum % 3 == 0){  
        foreach($_POST['update'] as $updateid){

                      $jumlah = $_POST['jumlah'.$updateid];
                      $idpomitra = $_POST['idpomitra'.$updateid];

                      if($jumlah<>0){

          $sql=$koneksi->query("INSERT into pods (id,no_ds,invoice,idpodetail,idpomitra,jumlah,waktu) 
          values (null,'$no_ds','$invoice','$updateid','$idpomitra','$jumlah','$today')"); 

                      }

                  }
  }else{
    echo "<script>alert('data gagal ditambah, Karena Belum Kelipatan 3');</script>";
      echo "<script>location='input_detail_ds_inv.php?id=$invoice'</script>";
      return false;
  }

    if ($sql) {
      $sql = $koneksi->query("UPDATE podropship SET no_ds='$no_ds' WHERE iddropship='$iddropship'" );
     echo "<script>alert('data berhasil ditambah');</script>";
    echo "<script>location='detaildropship.php?id=$invoice'</script>";
    }else{
      echo "<script>alert('data gagal ditambah');</script>";
    echo "<script>location='detaildropship.php?id=$invoice'</script>";
    }  
               
            }
}
 ?>  
            

            
            
                  
              
            </div>
          <!-- AKHIR KONTEN AWS -->
          
          
          <!-- Content Row -->
          <div class="row">

           
      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Wanoja Development 2020</span>
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
          <a class="btn btn-primary" href="login.php">Logout</a>
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