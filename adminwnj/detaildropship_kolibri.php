<?php 
session_start();

include 'koneksi.php'; 
$invoice=$_GET["id"];
$datamitra=$koneksi->query("SELECT pomitra.idpoproduk,admin_mitra.namamitra,
                              admin_mitra.idadmin,
                              admin_mitra_cs.namacs,
                              mitraagen.namaagen as agen, 
                              mitrareseller.namaagen as reseller, 
                              mitramarketer.namaagen as marketer
                            FROM pomitra
                            LEFT JOIN mitraagen on pomitra.idmitraagen=mitraagen.idmitraagen 
                              LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=pomitra.idmitrareseller 
                              LEFT JOIN mitramarketer on pomitra.idmitramarketer=mitramarketer.idmitramarketer 
                              LEFT JOIN admin_mitra on admin_mitra.idadmin=pomitra.idmitra or mitraagen.idadmin=admin_mitra.idadmin or mitrareseller.idadmin=admin_mitra.idadmin or mitramarketer.idadmin=admin_mitra.idadmin 
                              LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin
                            where pomitra.invoice='$invoice'");  
$mitra=$datamitra->fetch_array();


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

</head>

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
          
<h3><strong><a href="listpodropship.php?id=<?= $mitra['idpoproduk'] ?>"><span class="fa fa-chevron-left"></span> Kembali</a></strong></h3>
          <!-- Content Row -->
          <div class="row">
              
<div>
<table>
  <tr>
    <th>Invoice</th>
    <td>:</td>
    <td><?php echo $invoice; ?></td>
  </tr>
  <tr>
    <th>Mitra DB</th>
    <td>:</td>
    <td><?= $mitra['namamitra']; ?>(<?= $mitra['idadmin']; ?>)</td>
  </tr>
  <tr>
    <th>Mitra Sub-DB</th>
    <td>:</td>
    <td><?= $mitra['agen']; ?><?= $mitra['reseller']; ?><?= $mitra['marketer']; ?></td>
  </tr>
  <tr>
    <th>CS</th>
    <td>:</td>
    <td><?= $mitra['namacs']; ?></td>
  </tr>      
</table>  

  <div class="table-responsive">
        <table class="table table-bordered">
          <tr>
              <th>No</th>
              <th>Action</th>
              <th>Status</td>
              <th>Nama PO</th>
              <th>invoice</th>
            <th>Nama Pengirim</th>
            <th>Telepon Pengirim</th>
            <th>Nama Penerima</th>
              <th>Telepon Penerima</th>
                          <th width="20">
                           Alamat Penerima
                          </th>
                          <th width="20">Keterangan</th>
                          <th>Ekspedisi</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                          
                                    $datapodropship=$koneksi->query("SELECT * FROM podropship INNER JOIN poproduk ON poproduk.idpoproduk=podropship.idpoproduk where podropship.invoice='$_GET[id]'");
                                    $no=1;
                          
                            while($tampilkan=$datapodropship->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>    
                          <form method="post"><input type="hidden" name="id" value="<?php echo $tampilkan['iddropship']; ?>"><td>
                            <a href="cetakdropship.php?id=<?php echo $tampilkan['iddropship']; ?>" class="btn btn-primary" target="blank">Cetak</a><br>
                             <button type="submit" class="btn btn-success" name="kirim">Kirim</button>
                          </td>
                           <td>
                            <?php echo $tampilkan['statuskirim']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['namapo']; ?>
                          </td>
                            <td>
                            <?php echo $tampilkan['invoice']; ?>
                          </td>
                           <td>
                            <?php echo $tampilkan['namapengirim']; ?>
                          </td>
                           <td>
                           <?php echo $tampilkan['tlppengirim']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['namapenerima']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['tlppenerima']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['alamatpenerima']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['keterangan']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['ekspedisi']; ?> | <?php echo $tampilkan['layanan']; ?>
                          </td>
                          <!--<form method="post"><input type="hidden" name="id" value="<?php echo $tampilkan['idpreorder']; ?>">
                                  <td class="align-middle"><select name="status">
                                                              <option value="proses">proses</option>
                                                              <option value="selesai">selesai</option>
                                                            </select>  
                                  <button type="submit" class="btn btn-primary" name="update">Update</button>
                                  </td>
              </form>-->
              
              <?php
                            if(isset($_POST["kirim"])){
  
                            $iddropship = $_POST['id'];
                          $statuskirim = $_POST['statuskirim'];
                     
                            $query = "UPDATE podropship SET statuskirim= '".$statuskirim."' where iddropship='$iddropship'";
                            $sql = mysqli_query( $koneksi, $query);
                            
                                if($sql){ // Cek jika proses simpan ke database sukses atau tidak
                                    // Jika Sukses, Lakukan :
                                        header("location: listpodropship.php"); // Redirect ke halaman index.php
                                }else{
                                    // Jika Gagal, Lakukan :
                                    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
                                    echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
                                    }
                            }    
                             ?>
              
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>

                        
                         <?php
                        if(isset($_POST["proses"])){
  
                                   include "koneksi.php";
                         $invoice= $_POST['invoice'];
                         $koneksi->query("update pomitra set status='Proses' where invoice='$invoice';");
                                echo "<script>alert('data sudah terupdate');</script>";
                                echo "<script>location='listpoinvoice.php';</script>";
                                       } 
                                       
                   if(isset($_POST["selesai"])){
  
                                   include "koneksi.php";
                         $invoice= $_POST['invoice'];
                         $koneksi->query("update pomitra set status='Selesai' where invoice='$invoice';");
                                echo "<script>alert('data sudah terupdate');</script>";
                                echo "<script>location='listpoinvoice.php';</script>";
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

</body>

</html>
