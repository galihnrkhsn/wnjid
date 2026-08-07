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

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

<?php include "sidebar.php"; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php include "topbar.php"; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->          
          <div class="row">
              <h3><strong>Data Pendaftaran Calon Mitra</strong></h3>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="tbpendaftaran">
                      <thead>
                        <tr>
                        <th>No</th>
                        <th style="text-align: center; width:15%" >Nama</th>
                        <th style="text-align: center; width:10%">Email</th>
                        <th style="text-align: center; width:10%">No Telp / WA</th>
                        <!-- <th>No. KTP</th> -->
                        <th style="text-align: center; width:15%">Alasan Bergabung</th>
                        <th style="text-align: center; width:5%">Daftar Sebagai</th>
                        <th style="text-align: center; width:5%">Status</th>
                        <!-- <th>Keterangan</th> -->
                        <th style="text-align: right; width:10%"><i class="fas fa-cog"></i></th>
                      </tr>
                      </thead>
                      <tbody>
                          <?php 
                          include "koneksi.php";

                             $tampil =$koneksi->query("SELECT * FROM pendaftaran order by status asc");
                              $no=1;  
                            while($tampilMas=$tampil->fetch_assoc()){
                            ?>
                        <tr>
                        <td><?php echo $no++; ?></td>
                        <td>
                           <i class="fas fa-user-plus"></i> <?php echo $tampilMas['namalengkap']; ?>
                        </td>
                        <td>
                          <i class="fas fa-envelope" style="color: red"></i> <?php echo $tampilMas['email']; ?>
                        </td>
                        <td>
                      <i class="fa fa-whatsapp" style="color: green"></i><?php echo $tampilMas['nomortelp']; ?>
                        </td>
                         <!--<td><i class="fas fa-id-card"></i> <?php echo $tampilMas['noktp']; ?></td> -->
                        <td>
                          <?php echo $tampilMas['alasangabung']; ?>
                        </td>
                        <td>
                         <?php 
                          	if($tampilMas['daftarsebagai']=='Agen'){ echo " 
	              			<div class='badge bg-info text-white rounded-pill'>$tampilMas[daftarsebagai]</div>";}

		                  if($tampilMas['daftarsebagai']=='Reseller'){ echo " 
		                  <div class='badge bg-warning text-white rounded-pill'>$tampilMas[daftarsebagai]</div>";}

		                  if($tampilMas['daftarsebagai']=='Marketer'){ echo " 
		                  <div class='badge bg-danger text-white rounded-pill'>$tampilMas[daftarsebagai]</div>";}

		                  if($tampilMas['daftarsebagai']<>'Agen' and $tampilMas['daftarsebagai']<>'Reseller' 
		                  	and $tampilMas['daftarsebagai']<>'Marketer'){ echo "
	                    	<div class='badge bg-success text-white rounded-pill'>$tampilMas[daftarsebagai]</div>";} 
                    	?>
                          </td>
                          <td>
                          	<?php 
                          	if($tampilMas['status']=='Pendaftar Baru'){ echo " 
	              			<div class='badge bg-primary text-white rounded-pill'>$tampilMas[status]</div>";}

	              			if($tampilMas['status']=='Terdaftar'){ echo " 
	              			<div class='badge bg-success text-white rounded-pill'>$tampilMas[status]</div>";}

	              			if($tampilMas['status']=='Reject'){ echo " 
	              			<div class='badge bg-danger text-white rounded-pill'>$tampilMas[status]</div>";}
                            ?>
                          </td>
                          <!-- <td>
                            <?php echo $tampilMas['keterangan']; ?>
                          </td> -->
                          <?php
                            if($tampilMas['daftarsebagai']=='Distributor' and $tampilMas['status']=='Pendaftar Baru'){
                                echo "
                                <td>
                                <form method='post'> 
                                 <input type='hidden' name='id' value=$tampilMas[idpendaftaran]>
                                 <a style='width:50px;' href='daftarbaru.php?id=$tampilMas[idpendaftaran]' class='btn btn-success' role='button' aria-pressed='true'><span class='fa fa-check'></span></a>
                                  <button style='width:50px;' type='submit' class='btn btn-danger' name='hapus'><span class='fas fa-times'></span></button>
                            </form>";
                            }
                            elseif($tampilMas['status']=='Pendaftar Baru') {
                                echo "<td>
                                <form method='post'> 
                                 <input type='hidden' name='id' value=$tampilMas[idpendaftaran]>
                                 <a style='width:50px;' href='daftarbarusubdb.php?id=$tampilMas[idpendaftaran]' class='btn btn-info' role='button' aria-pressed='true'><span class='fa fa-check'></span></a>
                                <button style='width:50px;' type='submit' class='btn btn-danger' name='hapus'><span class='fas fa-times'></span>
                                </form>";
                            }

                            elseif($tampilMas['status']<>'Pendaftar Baru') { ?>
                              <td><input type='hidden' name='id' 
                                value="<?php echo $tampilMas['idpendaftaran'] ?>" >
                                <input type="text" id="update" value="<?php echo $tampilMas['keterangan'] ?>"'
                                class="form-control">
                              <!-- <p style="color:grey;size: 2px">*keterangan</p> -->
                              <button class="btn btn-success btn-xs" name="update" type="submit">
                              update keterangan</button>
                              </td>
                            <?php } ?>

                          <!-- </td> -->
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
</div>
                    
                    
                    <?php
                        if(isset($_POST["hapus"])){
	
	                        include "koneksi.php";
					               $idpendaftaran= $_POST['id'];
					               $koneksi->query("UPDATE pendaftaran SET status='Reject' where idpendaftaran='$idpendaftaran'");
        		                   	echo "<script>alert('Status telah diubah');</script>";
        		                   	echo "<script>location='datapendaftaran.php';</script>";
        					                     }   

                         if(isset($_POST["update"])){
  
                         include "koneksi.php";
                         $idpendaftaran= $_POST['id'];
                         $koneksi->query("UPDATE pendaftaran SET status='Reject' where idpendaftaran='$idpendaftaran'");
                                echo "<script>alert('Status telah diubah');</script>";
                                echo "<script>location='datapendaftaran.php';</script>";
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
  
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">

$(document).ready(function() {
    $('#tbpendaftaran').DataTable();
} );

</script>

</body>

</html>

		                    