<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$idpoproduk = $_GET['id'];
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
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Voal Hampers</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div>

<form method="post" class="col-4">   
    <div class="form-group">
          <label>Pilih Nama DB</label>
          <select class="form-control" name="idmitra" id="idmitra" required>
              <option value="" selected>- Pilih Nama DB -</option>
              <?php
              $datadb=$koneksi->query("SELECT * FROM admin_mitra ORDER BY namamitra asc");
              while($tampilkan=$datadb->fetch_assoc()){
              ?>
          <option value="<?php echo $tampilkan['idadmin']; ?>"><?php echo $tampilkan['namamitra']; ?></option>
          <?php } ?>                       
          </select>      
    </div> 
<br>
<div  class="form-group">
  <label>Jumlah Seri</label>
  <input type="number" class="form-control" value="0" name="jmlh" required>
        <?php
            $sql = "SELECT * FROM poproduk 
            inner join pokategori on poproduk.idpoproduk=pokategori.idpoproduk 
            inner join podetail  on pokategori.idpo=podetail.idpo 
            where poproduk.idpoproduk='$idpoproduk' 
            order by podetail.idpodetail asc";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>                        
                <div class="form-group d-none">
                    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                </div>  
                
<?php } ?>  
</div>   
  <br>
<button type="submit" name="but_save_po" class="btn btn-primary"><i class="fa fa-plus"></i> Simpan</button>  
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
$waktu = date("H:i:s");
$tanggal = date("Y-m-d");
$tglnya = date("dm");
$waktunya = date("Hi");
$idpodetail=$_POST["idpodetail"];
$jmlh=$_POST["jmlh"];
$idadmin = $_POST['idmitra'];

  $jumlah_dipilih = count($idpodetail);
                                    
  for($x=0;$x<$jumlah_dipilih;$x++){
  $query_variant = "SELECT podetail.harga, podetail.idpo
            FROM podetail
            WHERE podetail.idpodetail='$idpodetail[$x]'";
  $sql_variant = mysqli_query($koneksi, $query_variant);  
  $data_variant = mysqli_fetch_array($sql_variant);
  $harga = $data_variant['harga'];
  $idpo = $data_variant['idpo'];
$total=$jmlh*$harga;                

$ambil=$koneksi->query("SELECT idpodetail, idmitra FROM pomitra WHERE idpodetail='$idpodetail[$x]' and idmitra ='$idadmin' and idpoproduk='$idpoproduk' ");
    $datacocok=$ambil->num_rows;
    if($datacocok>=1){
      echo "<script>alert('data sudah ada');</script>";
echo "<script>location='hampers.php';</script>";
    }else{
// echo "<script>alert('$idadmin $idpoproduk $idpo $idpodetail[$x] $jmlh $total D$idpoproduk-$idadmin $waktu');</script>";
  $sql = $koneksi->query("INSERT into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) values
                                     (null,'$idadmin','$idpoproduk','$idpo','$idpodetail[$x]','$jmlh','$total','D$idpoproduk-$idadmin','Belum DP',NOW(),'$waktu')");
      
    }
                                      
                                                    }
if ($sql) {
         echo "<script>alert('data berhasil dikirim');</script>";
                                            echo "<script>location='hampers.php';</script>";
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



</body>

</html>

		                                                