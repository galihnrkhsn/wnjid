<?php 
session_start();
$namalengkap= $_SESSION["management"]["namalengkap"];
$title = $namalengkap;
include 'template/header.php'; 


if(!isset($_SESSION["management"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$iduser= $_SESSION["management"]["id"];

  $sql = "SELECT * FROM management WHERE id='$iduser' ";
  $query = $koneksi->query($sql);
  $data = $query->fetch_assoc();

$tipe = $data['tipe'];

$ambilp=$koneksi->query("SELECT sum(kredit) - sum(debit) as sisa FROM rekeningkoran WHERE  tipe='P'"); 
$datap=$ambilp->fetch_assoc();

$ambilaf=$koneksi->query("SELECT sum(kredit) - sum(debit) as sisa FROM rekeningkoran WHERE  tipe='AF'"); 
$dataaf=$ambilaf->fetch_assoc();

$ambilm=$koneksi->query("SELECT sum(kredit) - sum(debit) as sisa FROM rekeningkoran WHERE  tipe='M'"); 
$datam=$ambilm->fetch_assoc();

?>


<?php 
include 'template/topbar.php'; 
 ?>    


                    <!-- Page Heading -->

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h2 class="m-0 font-weight-bold text-secondary">Daftar Finance</h2> 
                    </div>   

                      
                 
                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xl font-weight-bold text-success text-uppercase mb-1">
                                              <a href="finance.php?tipe=P">
                                                Kas Produksi
                                              </a>
                                              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($datap['sisa']); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   


                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xl font-weight-bold text-info text-uppercase mb-1">
                                              <a href="finance.php?tipe=AF">
                                                Kas Admin
                                              </a>
                                              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($dataaf['sisa']); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xl font-weight-bold text-primary text-uppercase mb-1">
                                              <a href="finance.php?tipe=M">
                                                Kas Manajemen
                                              </a>
                                              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($datam['sisa']); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>      

                                                                          


                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xl font-weight-bold text-danger text-uppercase mb-1">
                                              <a href="index.php">
                                                Kembali
                                              </a>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="index.php">
                                            <i class="fas fa-arrow-left fa-2x text-gray-300"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                                                                            


                    </div><!-- Content Row -->



<?php 
include 'template/footer.php'; 
 ?>