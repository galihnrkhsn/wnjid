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




  $query = "SELECT poproduk.idpoproduk,
            poproduk.namapo,
            pomitra.waktu,
            pomitra.tgl,
            pomitra.status,
            pomitra.idpodetail,
            pomitra.idmitra,
            pomitra.idmitraagen,
            pomitra.idmitrareseller,
            pomitra.idmitramarketer

        FROM pomitra 
        inner join poproduk on poproduk.idpoproduk=pomitra.idpoproduk    
        WHERE pomitra.invoice='$invoice'";
  $sqlpo = mysqli_query($koneksi, $query);  
  $datapo = mysqli_fetch_array($sqlpo);

$idpoproduk = $datapo['idpoproduk'];
$idmitra = $datapo['idmitra'];
$idmitraagen = $datapo['idmitraagen'];
$idmitrareseller = $datapo['idmitrareseller'];
$idmitramarketer = $datapo['idmitramarketer'];
$tgl = $datapo['tgl'];
$waktu = $datapo['waktu'];
$status = $datapo['status'];

if ($idmitra=="") {
  $idmitra="0";
}
if ($idmitraagen=="") {
  $idmitraagen="0";
}
if ($idmitrareseller=="") {
  $idmitrareseller="0";
}
if ($idmitramarketer=="") {
  $idmitramarketer="0";
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
  <meta name="viewport" content="width=device-width, initial-scale=1">


  <title>WNJ.ID</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-metro.css">
  

  
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

<?php if($_SESSION["administrator"]["nama"]=='Produksi'){
include "sidebar2.php";     
} else{
include "sidebar.php";     
}
?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><a class="link" href="detailinvoice.php?invoice=<?= $invoice; ?>&idpoproduk=<?= $idpoproduk; ?>"><i class="fa fa-arrow-left"></i> Kembali</a>   </h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>
          
          <!-- MULAI KONTEN AWS -->
            <div class="row w3-container">
                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Form PO Tambah</h6>

                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                        <form method="POST">
                                          <div class="form-group">
                                            <label>Jenis PO</label>
                                            <select class="form-control" name="jenis" required style="width:300px;">
                                              <option value="">Pilih Jenis PO</option>
                                              <option value="Stok">Dengan Stok</option>
                                              <option value="Tanpa">Tanpa Stok</option>
                                            </select>
                                          </div>
        <?php
$no = 1;
            $sql = "SELECT podetail.idpo, podetail.idpodetail, podetail.variant, podetail.harga FROM podetail
                  join pokategori on pokategori.idpo=podetail.idpo
                  where pokategori.idpoproduk='$idpoproduk'
                  order by podetail.variant asc";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){

  $query_detail = "SELECT 
            pomitra.idpodetail

        FROM pomitra  
        WHERE pomitra.invoice='$invoice'
        and pomitra.idpodetail='$row[idpodetail]'
        ";
  $sqlpo_detail = mysqli_query($koneksi, $query_detail);  
  $datapo_detail = mysqli_fetch_array($sqlpo_detail);
  $idpodetail = $datapo_detail['idpodetail'];                
                ?>                                   
<?php if ($idpodetail==""): ?>
                     
                                   
                <div class="form-group">
                  <label></label>
                    <label><?php echo $row['variant']; ?></label>
                    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                    <input type="number" min="0" name="jmlh[]" class="form-control" style="width:300px;" value=0 required>
                </div>  
<?php endif ?>                   
                        <?php
                          } ?>
                          <button type='submit' class='btn btn-primary' name='save'>Kirim</button>
                                  </form>
                              </div>             
</div>                        
                                </div>
                            </div>
                        </div>  

                                   

                        </div>   
            
  
            
<?php
  if(isset($_POST["save"])){
    $jenis=$_POST["jenis"];
    $idpodetail=$_POST["idpodetail"];
    $jmlh=$_POST["jmlh"];                           
    $jumlah_dipilih = count($jmlh);
                       
  for($x=0;$x<$jumlah_dipilih;$x++){
  $query_variant = "SELECT podetail.harga, podetail.idpo
            FROM podetail
            WHERE podetail.idpodetail='$idpodetail[$x]'";
  $sql_variant = mysqli_query($koneksi, $query_variant);  
  $data_variant = mysqli_fetch_array($sql_variant);
  $harga = $data_variant['harga'];
  $idpo = $data_variant['idpo'];  
  $total=$jmlh[$x]*$harga;
      if ($jmlh[$x]>0) {
        if ($jenis=="Tanpa") {       
           $sql = $koneksi->query("INSERT into pomitra (idpomitra,
                                                      idmitra,idmitraagen,idmitrareseller,idmitramarketer,
                                                      idpoproduk,idpo,idpodetail,
                                                      jumlah,total,invoice,
                                                      status,tgl,waktu) 
                                  values
                                                      (null,
                                                      '$idmitra','$idmitraagen','$idmitrareseller','$idmitramarketer',
                                                      '$idpoproduk','$idpo','$idpodetail[$x]',
                                                      '$jmlh[$x]','$total','$invoice',
                                                      '$status','$tgl','$waktu')");
        }
        if ($jenis=="Stok") { 
        $sql = "SELECT stok from pokategori where idpo='$idpo'";
        $query = $koneksi->query($sql);
        $sisa = $query->fetch_assoc(); 
          if($sisa['stok']>$jmlh[$x]){
           $sql = $koneksi->query("INSERT into pomitra (idpomitra,
                                                      idmitra,idmitraagen,idmitrareseller,idmitramarketer,
                                                      idpoproduk,idpo,idpodetail,
                                                      jumlah,total,invoice,
                                                      status,tgl,waktu) 
                                  values
                                                      (null,
                                                      '$idmitra','$idmitraagen','$idmitrareseller','$idmitramarketer',
                                                      '$idpoproduk','$idpo','$idpodetail[$x]',
                                                      '$jmlh[$x]','$total','$invoice',
                                                      '$status','$tgl','$waktu')");
           $sqlpo = $koneksi->query("UPDATE pokategori set stok=stok-'$jmlh[$x]' where idpo='$idpo'");
          }else{
           $sql = $koneksi->query("INSERT into pomitra (idpomitra,
                                                      idmitra,idmitraagen,idmitrareseller,idmitramarketer,
                                                      idpoproduk,idpo,idpodetail,
                                                      jumlah,total,invoice,
                                                      status,tgl,waktu) 
                                  values
                                                      (null,
                                                      '$idmitra','$idmitraagen','$idmitrareseller','$idmitramarketer',
                                                      '$idpoproduk','$idpo','$idpodetail[$x]',
                                                      '0','0','$invoice',
                                                      '$status','$tgl','$waktu')");
            }     


        }          
      }
    }                                      
if ($sql) {
  echo "<script>alert('data berhasil dikirim');</script>";
  echo "<script>location='detailinvoice.php?invoice=$invoice&id=$idpoproduk';</script>";
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
            <span>Copyright &copy; WNJ.ID Development 2020</span>
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
