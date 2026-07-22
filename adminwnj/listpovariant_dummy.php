<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$id=$_GET["id"];
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

<?php if($_SESSION["administrator"]["nama"]=='Produksi'){
include "sidebar2.php";     
} else{
include "sidebar.php";     
}
?>

  
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          <div class="row">
              <?PHP
 $datapo2=$koneksi->query("SELECT * FROM poproduk where idpoproduk='$id'");

                          
                        $tampilkan2=$datapo2->fetch_assoc();
                            ?>
<h3><strong><?php echo $tampilkan2['namapo'] ?></strong></h3>
      
        
	<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="tb_variant">
					<thead>
					<tr>
					    <th style="width:1px">No</th>
						<th>Variant</th>

						<th>Jumlah</th>
            <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                          $subtotal=0;

        
                            $datapo=$koneksi->query("SELECT podetail.*,
                            	sum(pomitra.jumlah) as jumlah, 
                            	REPLACE(right(podetail.variant,2),' ', '') as ukuran,
                            	pomitra.idpodetail as detail
                            	FROM poproduk 
                            	INNER JOIN pomitra on poproduk.idpoproduk=pomitra.idpoproduk
                            	INNER JOIN pokategori on pokategori.idpo=pomitra.idpo 
                            	right JOIN podetail  on podetail.idpodetail=pomitra.idpodetail
                            	
                            	 
                            	where poproduk.idpoproduk='$id' 
                            	GROUP by podetail.variant order by podetail.idpodetail ");

                            $no=1;
                          
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>     
                          <td>
                            <?php echo $tampilkan['ukuran']; ?>                        
                          </td>

                           <td>
                            <?php echo $tampilkan['jumlah']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['harga']*$tampilkan['jumlah']; ?>
                          </td>
							<?php 
              $subtotal=$subtotal+$tampilkan['jumlah'];
              $totalseluruh += $tampilkan['harga']*$tampilkan['jumlah'];
               ?>
                        </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>	
                                    <tr>
                            <td colspan="2">
                              <b>Total</b>
                            </td>
                            <td>
                            <b><?php echo $subtotal; ?> </b> 
                            </td>  
                            <td>
                            <b><?php echo number_format($totalseluruh); ?> </b> 
                            </td>    
                        </tr>
                        </tfoot>
                      
                    </table><br><br>
                    
                    <p><strong>Summary</strong></p>
                    <div class="table-responsive">
				<table class="table table-bordered" id="tb_kategori">
					<thead>
					<tr>
					    <th style="width:1px">No</th>
						<th>Variant</th>
						<th>Jumlah</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                         
                            $datapo2=$koneksi->query("SELECT pokategori.namakategori, sum(pomitra.jumlah) as jumlah, podetail.*, pomitra.idpodetail as detail FROM poproduk INNER JOIN pomitra INNER JOIN pokategori INNER JOIN podetail on poproduk.idpoproduk=pomitra.idpoproduk and pokategori.idpo=pomitra.idpo and podetail.idpodetail=pomitra.idpodetail where poproduk.idpoproduk='$id' GROUP by podetail.variant order by podetail.idpodetail ");
                            $no2=1;
                          
                            while($tampilkan2=$datapo2->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no2++; ?>
                        </td>     
                          <td>
                            <?php echo $tampilkan2['variant']; ?>
                          </td>
                           <td>
                            <?php echo $tampilkan2['jumlah']; ?>
                          </td>
                          <?php $total =$total+$tampilkan2['jumlah']; ?>
							
                        </tr>
                        <?php } ?>
                      </tbody>
                      <tfoot>
                        <tr>
                            <td colspan="2">
                               <b> Total</b>
                            </td>
                            <td>
                            <b><?php echo $total; ?></b>
                            </td>    
                        </tr>
                        </tfoot>
                    </table>
                    
                    <?php
                      $idpoproduk = $_GET['id'];
                        if ($idpoproduk == 251 || $idpoproduk == 250 || $idpoproduk == 245) :
                    ?>
                      <div class="my-4">
                        <h6 style="font-weight: 700; text-transform: uppercase;">Total Pcs Per Variant</h6>
                
                        <table class="table table-striped table-bordered table-hover">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>Variant</th>
                              <th>Pcs</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $variantCounts = [];
                              $no = 1;
                              $sql = $koneksi->query("SELECT pomitra.*, podetail.* FROM pomitra LEFT JOIN podetail ON podetail.idpodetail = pomitra.idpodetail WHERE pomitra.idpoproduk = '$idpoproduk' ORDER BY podetail.variant");
                              while($query = $sql->fetch_assoc()) {
                                $queryCustom = $query['custom'];
                                $explode = explode(" | ", $queryCustom);
                
                                foreach ($explode as $variant) {
                                  if (isset($variantCounts[$variant])) {
                                    $variantCounts[$variant] += $query['jumlah'];
                                  } else {
                                    $variantCounts[$variant] = $query['jumlah'];
                                  }
                                }
                              }
                            ?>
                            <?php
                              $total = 0;
                              foreach ($variantCounts as $variant => $totalJumlah) : 
                            ?>
                              <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $variant ?></td>
                                <td><?= $totalJumlah; ?></td>
                              </tr>
                            <?php 
                              $total += $totalJumlah;
                              endforeach;
                            ?>
                
                            <tr>
                              <th colspan="2">Total</th>
                              <td><?= $total; ?></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    <?php else : ?>
            
                    <?php
                        endif;
                    ?>
                    
                <a class="btn btn-primary" href="cetakpovariant.php?id=<?php echo $id; ?>" target="blank">Cetak</a> <a class="btn btn-success" href="cetakpovariantexcel.php?id=<?php echo $id; ?>" target="blank">Export Excel</a> <!--<a class="btn btn-info" href="cetakpivotexcel.php?id=<?php echo $id; ?>" target="blank">Export Pivot Excel</a>-->
                
    <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Wanoja 2020</span>
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



<script type="text/javascript">
        $(document).ready( function () {
    $('#tb_variant').DataTable({
    	iDisplayLength: -1,
    	"bLengthChange" : false,
    	info: false,
    	paging: false
    });
} );
</script>


<script type="text/javascript">
        $(document).ready( function () {
    $('#tb_kategori').DataTable({
    	iDisplayLength: -1,
    	"bLengthChange" : false,
    	info: false,
    	paging: false
    });
} );
</script>
</body>

</html>

		                    