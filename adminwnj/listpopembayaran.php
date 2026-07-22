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
<style type="text/css">
   body{
      padding-right: 0px ! important;
   }  
</style>
<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

  <?php include "sidebar.php"; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
        
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          <div class="row">
              
                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Pembayaran DP PO</h6>

                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div>
<a href="bayardp.php" class="btn btn-success"><i class="fa fa-plus"></i> Data PO</a> 
<a href="bayarlunas.php" class="btn btn-primary"><i class="fa fa-plus"></i> Data Pelunasan</a> 
<a href="" class="btn btn-info" data-toggle="modal" data-target="#modalView"><i class="fa fa-file-excel"></i> Excel Pembayaran PO</a>    



<!-------Modal VIEW--------------->
        <div class="modal fade" id="modalView" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
            <!-----ModalHeader-------------->
              <div class="modal-header">
                <h4 class="modal-title" id="labelModalKu">Excel</h4>
                  <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Tutup</span>
                  </button>
              </div>
            <!------ModalBody-------------->
              <form method="POST" action="excel_popembayaran.php" target="_blank()" enctype="multipart/form-data">          
                <div class="modal-body">
                  <div class="form-group">
                        <label>Nama PO</label>
                        <select class="form-control" name="namapo1" id="namapo1">
                            <option enabled selected>- Pilih Nama PO -</option>
                            <?php
                            $datadb=$koneksi->query("SELECT * FROM poproduk  ORDER BY idpoproduk DESC");
                            while($tampilkan=$datadb->fetch_assoc()){
                            ?>
                        <option value="<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></option>
                        <?php } ?>  
                        </select>      
                  </div>                 
                </div>
              <!-------ModalFooter------------>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success" name="excel">&plus; Excel</button>          
                  <button type="button" class="btn btn-danger" data-dismiss="modal">&times; Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>                                    
                                      
<div class="table-responsive">
  <table class="table table-striped" id="tb_listpo_artikel">
                      <thead>
                        <tr>
                            <th style="width:1%">
                             No
                            </th>
                            <th>
                            ID PO  
                          </th>    
                          <th>
                            Nama PO  
                          </th>         
                        </tr>
                      </thead>
                      <tbody>
                          <?php 

        
                            $datapo=$koneksi->query("SELECT poproduk.idpoproduk,
                                                            poproduk.namapo, 
                                                            sum(pomitra.jumlah) as jumlahnya,
                                                            poproduk.jenis
                              FROM poproduk INNER JOIN pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                              WHERE pomitra.jumlah>0
                              GROUP BY pomitra.idpoproduk ORDER BY poproduk.idpoproduk DESC ");
                            $no=1;
                          
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan['idpoproduk']; ?>
                        </td>     
                          <td>
                              <?php if ($tampilkan['idpoproduk'] == '343') : ?>
                                <a href="popembayarankonin.php?id=<?= $tampilkan['idpoproduk'] ?>"><?= $tampilkan['namapo'] ?></a>
                              <?php else : ?>
                              <a href="popembayaran.php?id=<?php echo $tampilkan['idpoproduk']; ?>"> <?php echo $tampilkan['namapo']; ?> </a>
                              <?php endif; ?>
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

        $('#namapo').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var namapo = $('#namapo').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_dp_po.php',
                data :  'namapo=' + namapo,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tabel_dp").html(data);
                }
                
            });
        });
        
    });
</script> 



</body>

</html>

		                                                