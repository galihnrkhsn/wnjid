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

  <title>Admin Pusat | WNJ CORP</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>


</head>

<body id="page-top">

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
             <h1 class="h3 mb-0 text-gray-800"><strong>Input PO Variant</strong></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          <hr>

          <!-- Content Row -->

  
  <form method="POST">
<div class="control-group col-3">
            <label>Nama PO</label>	        
	            <select style="width: 300px" class="form-control" name="idpoproduk" id="idpoproduk" required>
	                <option value="">~ Pilih Nama PO ~</option>
	                
	                <?php
	                include "koneksi.php";
	                $ambil=$koneksi->query("SELECT * FROM poproduk order by idpoproduk desc limit 10"); 
	                while($data=$ambil->fetch_assoc()){
	                ?>
	                <option value='<?php echo $data['idpoproduk']; ?>'><?php echo $data['namapo']; ?></option>
	                <?php } ?>  
	                
	            </select><br>
</div>              
          <div class="control-group col-3">
            <label>Jumlah Variant</label>
<input type="number" min="0" value="0" class="form-control" name="jml_variant" id="jml_variant">
          </div>               
          <br>
          <br>
	            <button class="btn btn-primary" type="submit" name="pilih">Pilih</button><br><br>
</form>	             
	   <?php
	   include "koneksi.php";
	if(isset($_POST["pilih"])){
	$idpoproduk=$_POST["idpoproduk"];
  $jml_variant=$_POST["jml_variant"];
	echo "lanjut pilih variant";
	}
?>
  <form method="POST">
<?php

for ($x = 0; $x < $jml_variant; $x++) {

?>

<div  class="input-group control-group">        
  <select style="width: auto;" class="form-control" name="idpo[]" required>
    <option value="">~ Pilih Kategori/Warna ~</option>
                  
    <?php 
    
      $ambil=$koneksi->query("SELECT * FROM pokategori where idpoproduk='$idpoproduk'"); 
                  while($data=$ambil->fetch_assoc()){
                  ?>
    <option value='<?php echo $data['idpo']; ?>'><?php echo $data['namakategori']; ?></option>
                  <?php } ?>  
  </select> 
  <br>
  <hr>
  <input style="width: auto;" type="text" name="variant[]" class="form-control" placeholder="Variant" required>
  <input style="width: auto;" type="text" name="harga[]" class="form-control" placeholder="Harga" required>
  <input style="width: auto;" type="text" name="berat[]" class="form-control" placeholder="Berat" required>
</div>
<br><br>
<?php
}
 ?>
 
	            <button class="btn btn-primary" type="submit" name="save">Simpan</button> 
	   	</form>
 		

    </div>
  </div>
</div>


 <?php
 	include "koneksi.php";
	if(isset($_POST["save"])){

	$idpo=$_POST["idpo"];
	$variant=$_POST["variant"];
	$harga=$_POST["harga"];
    $berat=$_POST["berat"];
	$jmlh=count($variant);
	
    	for($x=0;$x<$jmlh;$x++){
    	$sql = $koneksi->query("insert into podetail (idpodetail,idpo,variant,harga,berat) VALUES (null,'$idpo[$x]','$variant[$x]','$harga[$x]','$berat[$x]') ");
    	 // 	echo "<script>alert('$idpo[$x], $variant[$x], $harga[$x]');</script>";
		  // echo "<script>location='produkpo.php';</script>";
	    }
      if ($sql) {
        echo "<script>alert('Data Berhasil Disimpan');</script>";
        echo "<script>location='produkpo.php';</script>";
      }else{
        echo "<script>alert('Data Gagal Disimpan');</script>";
        echo "<script>location='produkpo.php';</script>";
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



</body>

</html>

		                    