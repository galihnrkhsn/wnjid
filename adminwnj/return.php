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
              
              <h3><strong>Support Ticket</strong></h3>
              </div>

<a href="input_return.php" class="btn btn-primary">Tambah Data Return</a>

<div class="table-responsive">
<form method="post">	
                <table class="table table-striped table-bordered table-hover" id="tbreturn">
                      <thead>
                        <tr>
                            <th>
                            No    
                            </th>
                            <th><input type='checkbox' id='checkAll' > Check</th>
                          <th>
                            Status
                          </th>
                          <th>
                            Id Support Ticket
                          </th>
                            <th>
                            	Tanggal
                            </th>
                            <th>
                             Nama DB
                            </th>    
                            <th>
                            Masalah
                          </th>
                           <th>
                            Nama CS
                          </th>
                          <th>
                            Nominal / Ekspedisi / No Resi
                          </th>
                          <th>
                            Note
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                            <?php
                            $no=1;  
                            $dataproduk=$koneksi->query("SELECT admin_mitra.namamitra,support_ticket.* FROM support_ticket
                            inner join admin_mitra ON support_ticket.idadmin=admin_mitra.idadmin
                            ORDER BY support_ticket.idsupport desc
                            ");
                              
                            while($tampilkan=$dataproduk->fetch_assoc()){
                            	$id=$tampilkan['idsupport'];
                            ?>
                        <tr>
                            <td>
                                <?php echo $no++; ?>
                            </td>
                            <td><input type='checkbox' name='update[]' value='<?= $id ?>' ></td>
                          <td>
                          	<?php if ($tampilkan['status']=="Ticket Diajukan"): ?>
                          		<span class="badge bg-danger text-white"><?php echo $tampilkan['status']; ?></span>	
                          		<?php elseif ($tampilkan['status']=="Ticket Diproses"): ?>
                          				<span class="badge bg-success text-white"><?php echo $tampilkan['status']; ?></span>

                          			<?php elseif ($tampilkan['status']=="Ticket Selesai"): ?>
                          					<span class="badge bg-primary text-white"><?php echo $tampilkan['status']; ?></span>
                          	<?php endif ?>
                          	
                            
                          </td>
                          <td>
                          	<a href="detailsupport.php?id=<?php echo $tampilkan['idsupport']; ?>"><?php echo $tampilkan['idsupport']; ?></a>
                           
                          </td>
                            <td>
                            <?php echo $tampilkan['tgl']; ?>
                          </td>    
                          <td>
                            <?php echo $tampilkan['namamitra']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['masalah']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['namacs']; ?>
                          </td>
                          <td>
Rp. 
<?php 
echo number_format($tampilkan['nominal']);
echo "<br>";
echo $tampilkan['ekspedisi'];
echo "<br>";
echo $tampilkan['noresi'];

 ?>                            

                          </td>
                          <td>
 <?php echo substr($tampilkan['note'],0,10); ?><span id="dots<?php echo $tampilkan['idsupport']; ?>">...</span>
<p>
  <span id="more<?php echo $tampilkan['idsupport']; ?>" style="display:none;">
    <?php echo substr($tampilkan['note'],10,200); ?>   
  </span>
</p>                            
<a class="btn btn-sm btn-outline-info" onclick="myFunction<?php echo $tampilkan['idsupport']; ?>()" id="myBtn<?php echo $tampilkan['idsupport']; ?>">
  <i class="fa fa-eye"></i>
</a> 
                          </td>

                         <script>
                            function myFunction<?php echo $tampilkan['idsupport']; ?>() {
                              var dots = document.getElementById("dots<?php echo $tampilkan['idsupport']; ?>");
                              var moreText = document.getElementById("more<?php echo $tampilkan['idsupport']; ?>");
                              var btnText = document.getElementById("myBtn<?php echo $tampilkan['idsupport']; ?>");
                            
                              if (dots.style.display === "none") {
                                dots.style.display = "inline";
                                btnText.innerHTML = "<i class='fa fa-eye'></i>"; 
                                moreText.style.display = "none";
                              } else {
                                dots.style.display = "none";
                                btnText.innerHTML = "<i class='fa fa-eye-slash'></i>"; 
                                moreText.style.display = "inline";
                              }
                            }
                            </script>


  				</tr>
  			<?php } ?>
                      </tbody>
                    </table>
                    <input type='submit' class="btn btn-success" value='Proses' name='but_proses' >
                    &nbsp;&nbsp;&nbsp;
                    <input type='submit' class="btn btn-primary" value='Selesai' name='but_selesai' >  
                    &nbsp;&nbsp;&nbsp;
                    <input type='submit' class="btn btn-danger" value='Hapus Data' name='but_hapus' onclick="return confirm('Yakin Akan Hapus Data?');">                  
                    </div>
</form>

                        
                    <?php 

        if(isset($_POST['but_proses'])){

            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){
                	// echo "<script>alert('$updateid');</script>";
                    $sql = $koneksi->query("UPDATE support_ticket SET status='Ticket Diproses' where idsupport='$updateid'");                    
                }
                if ($sql) {
                  echo "<script>alert('data berhasil diproses');</script>";
                  echo "<script>location='return.php';</script>";  
                  }else{
                    echo "<script>alert('data gagal diproses');</script>";
                    echo "<script>location='return.php';</script>";
                }
               
            }
            
        }  

        if(isset($_POST['but_selesai'])){

            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){
                	// echo "<script>alert('$updateid');</script>";
                    $sql = $koneksi->query("UPDATE support_ticket SET status='Ticket Selesai' where idsupport='$updateid'");                    
                }
                if ($sql) {
                  echo "<script>alert('data berhasil diproses');</script>";
                  echo "<script>location='return.php';</script>";  
                  }else{
                    echo "<script>alert('data gagal diproses');</script>";
                    echo "<script>location='return.php';</script>";
                }
               
            }
            
        }  

        if(isset($_POST['but_hapus'])){

            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){
                	// echo "<script>alert('$updateid');</script>";
                    $sql = $koneksi->query("DELETE FROM support_ticket WHERE idsupport='$updateid'" );                  
                }
                if ($sql) {
                  echo "<script>alert('data berhasil dihapus');</script>";
                  echo "<script>location='return.php';</script>";  
                  }else{
                    echo "<script>alert('data gagal dihapus');</script>";
                    echo "<script>location='return.php';</script>";
                }
               
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

<?php include "settingdatatables.php"; ?>

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


</body>

</html>

                                        