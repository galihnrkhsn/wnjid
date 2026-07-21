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

<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Admin Pusat | WNJ CORP</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

<?php include "sidebar.php"; ?>

   

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><strong>Input PO Kategori</strong></h1>           
          </div>

          <hr>
          
          <!-- Content Row -->
          <div class="row">
            <a class="btn btn-danger btn-icon-split" href="inputvariant.php">
              <span class="texy-white-50 icon"><i class="fas fa-plus"></i></span>
              <span class="text">Tambah PO Variant</span>
            </a>
          </div>
          <br>
          <!-- Content Row -->

 <form method="POST">
          <div class="control-group col-3">
            <label>Jumlah Produk</label>
            <input type="number" min="0" value="0" class="form-control" name="jml_produk" id="jml_produk">

            <button class="btn btn-primary mt-2" type="submit" name="pilih">Pilih</button>
          </div>  	      
</form>

<?php
  if(isset($_POST["pilih"])):
  $jml_produk=$_POST["jml_produk"];
?>
<form method="post" enctype="multipart/form-data">   
  <div class="control-group col-3 mb-3">
    <label for="floatingInput">Nama Kategori</label>
    <select class="form-control" name="idpkategori" id="idpkategori" required>      
      <?php 
      $ambil=$koneksi->query("SELECT * FROM pkategori ORDER BY idpkategori ASC"); 
      while($data=$ambil->fetch_assoc()){
      ?>
        <option value='<?php echo $data['idpkategori']; ?>'><?php echo $data['namakategori']; ?></option>
      <?php } ?>  
    </select>
  </div>  
  <div class="control-group col-3 mb-3">
    <label for="floatingInput">Kategori Diskon</label>
    <select class="form-control" name="idkategori" id="idkategori" required>      
      <?php 
      $ambil=$koneksi->query("SELECT * FROM kategori ORDER BY idkategori ASC"); 
      while($data=$ambil->fetch_assoc()){
      ?>
        <option value='<?php echo $data['idkategori']; ?>'><?php echo $data['namakategori']; ?></option>
      <?php } ?>  
    </select>
  </div>      

  <div class="control-group col-3 mb-3">
  	<input type="date" name="tanggal" class="form-control" required>
  </div>  

  <div class="control-group col-3 mb-3">
  	<input type="file" name="foto" class="form-control" required>
  </div>    
         
<?php for ($x = 0; $x < $jml_produk; $x++) { ?>
           

  <div  class="input-group control-group col-12">   
<div class="form-floating mb-3 col-4">
  <label for="floatingInput">Nama Produk</label>
  <input type="text" name="namaproduk[]" class="form-control" placeholder="Nama Produk" required>
</div>
&nbsp;&nbsp;&nbsp;

<div class="form-floating mb-3 col-2">
  <label for="floatingInput">Harga Produk</label>
  <input type="number" min="0" name="harga[]" class="form-control" placeholder="Harga Produk" required>
</div>
&nbsp;&nbsp;&nbsp;

<div class="form-floating mb-3 col-2">
  <label for="floatingInput">Berat</label>
  <input type="number" min="0" name="berat[]" class="form-control" placeholder="Berat" required>
</div>
&nbsp;&nbsp;&nbsp;

<div class="form-floating mb-3 col-2">
  <label for="floatingPassword">Stok</label>
  <input type="number" value="0" name="stock[]" class="form-control" placeholder="Stok" required>
</div>    
    
  </div>              
              
 <hr>             

<?php } ?>

  <div class="control-group col-3">
    <button class="btn btn-primary" type="submit" name="save">Simpan</button>
  </div>
</form>   
<?php endif; ?>

           

 <?php
	if(isset($_POST["save"])){
		$idpkategori=$_POST["idpkategori"];
		$idkategori=$_POST["idkategori"];
		$tgl=$_POST["tanggal"];

		$namaproduk=$_POST["namaproduk"];
		$harga=$_POST["harga"];
		$berat=$_POST["berat"];
		$stock=$_POST["stock"];


    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $ukuranFile = $_FILES['foto']['size'];
    // cek apakah yang diupload adalah gambar
        $ekstensiGambarValid = ['jpg','jpeg','png','svg'];
        $ekstensiGambar = explode('.', $foto);
        $ekstensiGambar = strtolower(end($ekstensiGambar));
        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            echo "<script>
                    alert('Yang anda upload bukan gambar');
                  </script>";
                  echo "<script>location='dummy3.php';</script>";
        return false;
        }
    $namaFileBaru = uniqid();
    $namaFileBaru.='.';
    $namaFileBaru.= $ekstensiGambar;


		$jmlh=count($namaproduk);

  if(move_uploaded_file($tmp, '../distributor/foto/'. $namaFileBaru)){ 
		      for($x=0;$x<$jmlh;$x++){
		      $sql = $koneksi->query("INSERT into produk (idproduk,idpkategori,idkategori,namaproduk,harga,hargacoret,berat,stock,tgl,foto,status) 
		                              values (null,'$idpkategori','$idkategori','$namaproduk[$x]','$harga[$x]','0','$berat[$x]','$stock[$x]','$tgl','$namaFileBaru',1) ");

		      }  	
			if ($sql) {
		    echo "<script>alert('data berhasil dikirim');</script>";
		        echo "<script>location='produk.php?id=1';</script>";
			  }else{
			      echo "<script>alert('data gagal dikirim');</script>";
			        echo "<script>location='dummy3.php';</script>";
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

</body>

</html>

		                    