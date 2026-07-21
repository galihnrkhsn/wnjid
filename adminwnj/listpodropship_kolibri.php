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

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
<h3><strong>PO Mitra Dropship</strong></h3>
          <!-- Content Row -->
          <div class="row">
              
            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" method="post">
            <div class="input-group">
             <select name="idpoproduk" class="form-control bg-light border-1 small" aria-label="Search" aria-describedby="basic-addon2">
                    <?php
                    $datapo=$koneksi->query("SELECT * FROM poproduk order by idpoproduk");  
                    while($tampil=$datapo->fetch_assoc()){
                    ?>
                  <option value="<?php echo $tampil['idpoproduk']; ?>"><?php echo $tampil['namapo']; ?></option>
                  <?php } ?>
                  </select>
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit" name="caripo">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div><br>
          </form>

  <div class="table-responsive">
<table class="table table-striped" id="tblistdropship">
<thead>
					<tr>
					    <th>No</th>
						<th>Nama PO</th>
						<th>Nama DB</th>
            <th>Nama Sub DB</th>
                          <th>
                           Invoice
                          </th>
                            <th>
                           List Dropship
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                            $datapo=$koneksi->query("SELECT 
                              poproduk.namapo,
                              admin_mitra.namamitra,
                              mitraagen.namaagen as agen, 
                              mitrareseller.namaagen as reseller, 
                              mitramarketer.namaagen as marketer,
                              podropship_kolibri.invoice 
                              FROM podropship_kolibri 
                              LEFT JOIN mitraagen on podropship_kolibri.idmitraagen=mitraagen.idmitraagen 
                              LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=podropship_kolibri.idmitrareseller 
                              LEFT JOIN mitramarketer on podropship_kolibri.idmitramarketer=mitramarketer.idmitramarketer 
                              LEFT JOIN admin_mitra on admin_mitra.idadmin=podropship_kolibri.idadmin or mitraagen.idadmin=admin_mitra.idadmin or mitrareseller.idadmin=admin_mitra.idadmin or mitramarketer.idadmin=admin_mitra.idadmin 
                              INNER JOIN poproduk on podropship_kolibri.idpoproduk=poproduk.idpoproduk GROUP BY podropship_kolibri.invoice order by podropship_kolibri.iddropship desc");
                            $no=$mulai+1;
                          
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>     
                           <td>
                            <?php echo $tampilkan['namapo']; ?>
                          </td>
                           <td>
                           <?php echo $tampilkan['namamitra']; ?> 
                          </td>
                          <td>
                           <?php echo $tampilkan['agen']; ?> <?php echo $tampilkan['reseller']; ?> <?php echo $tampilkan['marketer']; ?> 
                          </td>
                          <td>
                            <?php echo $tampilkan['invoice']; ?>
                          </td>
                          <td>
                           <a href="detaildropship_kolibri.php?id=<?php echo $tampilkan['invoice']; ?>">Klik Disini</a>
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
                            if(isset($_POST["update"])){
	
                            $idpreorder = $_POST['id'];
	                        $status = $_POST['status'];
	                   
                            $query = "UPDATE list_po_mitra SET status= '".$status."' where idpreorder='$idpreorder'";
                            $sql = mysqli_query( $koneksi, $query);
                            
                                if($sql){ // Cek jika proses simpan ke database sukses atau tidak
                                    // Jika Sukses, Lakukan :
                                        header("location: index.php?page=listpomitra"); // Redirect ke halaman index.php
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
                    
                  <!--   <div style="font-weight:bold;">
                            Halaman
                            <?php
                            for ($i=1; $i<=$pages ; $i++){
                            ?>
                            <a href="listpodropship_kolibri.php?halaman=<?php echo $i; ?>" style="text-decoration:none">   <u><?php echo $i; ?></u></a>
                            <?php
                                }
                            ?>
                        </div>-->
                        
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

  <?php include "settingdatatables.php"; ?>

</body>

</html>

		                                                