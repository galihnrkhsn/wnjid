SELECT podetail.variant, sum(pokolibri.jumlah) as jumlah FROM poproduk INNER JOIN pokolibri INNER JOIN pokategori INNER JOIN podetail INNER JOIN mitramarketer INNER JOIN admin_mitra on poproduk.idpoproduk=pokolibri.idpoproduk and pokategori.idpo=pokolibri.idpo and podetail.idpodetail=pokolibri.idpodetail and pokolibri.idmitramarketer=mitramarketer.idmitramarketer and admin_mitra.idadmin=mitramarketer.idadmin where poproduk.idpoproduk='28' and (
admin_mitra.idadmin<>13 and	
admin_mitra.idadmin<>44 and	
admin_mitra.idadmin<>46 and	
admin_mitra.idadmin<>54	and
admin_mitra.idadmin<>56	and
admin_mitra.idadmin<>67 and
admin_mitra.idadmin<>79	and
admin_mitra.idadmin<>82	and
admin_mitra.idadmin<>108	and
admin_mitra.idadmin<>110	and
admin_mitra.idadmin<>111	and
admin_mitra.idadmin<>113	and
admin_mitra.idadmin<>127	and
admin_mitra.idadmin<>135	and
admin_mitra.idadmin<>144	and
admin_mitra.idadmin<>148	and
admin_mitra.idadmin<>149	and
admin_mitra.idadmin<>150	and
admin_mitra.idadmin<>160	and
admin_mitra.idadmin<>180	and
admin_mitra.idadmin<>200	and
admin_mitra.idadmin<>210	and
admin_mitra.idadmin<>217	and
admin_mitra.idadmin<>216	and
admin_mitra.idadmin<>221	and
admin_mitra.idadmin<>228	and
admin_mitra.idadmin<>232	and
admin_mitra.idadmin<>241	and
admin_mitra.idadmin<>245	and
admin_mitra.idadmin<>282	and
admin_mitra.idadmin<>290	and
admin_mitra.idadmin<>292	and
admin_mitra.idadmin<>300	and
admin_mitra.idadmin<>302	and
admin_mitra.idadmin<>308	and
admin_mitra.idadmin<>336)
GROUP by podetail.variant

<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}
$idcssales=$_GET["id"];
if ($idcssales=="" or $idcssales=="Pilih Nama CS") {
  $idcssales="Pilih Nama CS";
  $namacs="Pilih Nama CS";
}else{
  $query = "SELECT idcssales,namacs
        FROM cssales
        WHERE idcssales='$idcssales'";
  $sqlcs = mysqli_query($koneksi, $query);  
  $datacs = mysqli_fetch_array($sqlcs);
  $namacs=$datacs['namacs'];
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
  <meta name="viewport" content="width=device-width, initial-scale=1">


  <title>WNJ.ID</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-metro.css">
  

  
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

<?php
include "sidebar.php";    
?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Catatan</h1>
           </div>

    <div class="form-group col-4">
          <label>Pilih Nama CS</label>
          <select class="form-control" name="jenis_filter" id="jenis_filter">
              <option value="<?= $idcssales; ?>" selected><?= $namacs; ?> (<?= $idcssales; ?>)</option>
        <?php 
        $ambil=$koneksi->query("SELECT * FROM cssales where idcssales>13 order by namacs");
        while($data=$ambil->fetch_assoc()){
        ?>
        <option value="<?= $data['idcssales']; ?>"><?= $data['namacs']; ?> (<?= $data['idcssales']; ?>)</option>
        <?php } ?>
          </select>      
    </div> 
    <div class="col"  id="tabel_po" name="tabel_po"></div>         

                <?php 
                  if(isset($_POST["simpan"])){

                    if(isset($_POST['update'])){
                        foreach($_POST['update'] as $updateid){

                            $idcs = $_POST['idcssales'.$updateid];
                            $ket = $_POST['ket'.$updateid];

                            $sql = $koneksi->query("UPDATE catatan set ket='$ket'
                                                    WHERE idcatatan='$updateid';");
                            
                            
                        }
                    if ($sql) {
                     echo "<script>alert('data berhasil disimpan');</script>";
                     echo "<script>location='catatan.php?id=$idcs';</script>";
                    }else{
                     echo "<script>alert('data gagal disimpan');</script>";
                     echo "<script>location='catatan.php?id=$idcs';</script>";      
                    }
                       
                    }                 
                  }

                  if(isset($_POST["hapus"])){

                    if(isset($_POST['update'])){
                        foreach($_POST['update'] as $updateid){

                            $idcs = $_POST['idcssales'.$updateid];
                            $sql = $koneksi->query("DELETE FROM catatan WHERE idcatatan='$updateid'" );                                                        
                        }
                    if ($sql) {
                     echo "<script>alert('data berhasil dihapus');</script>";
                     echo "<script>location='catatan.php?id=$idcs';</script>";
                    }else{
                     echo "<script>alert('data gagal dihapus');</script>";
                     echo "<script>location='catatan.php?id=$idcs';</script>";      
                    }
                       
                    }                 
                  }                  

                  if(isset($_POST["done"])){

                    if(isset($_POST['update'])){
                        foreach($_POST['update'] as $updateid){

                            $idcs = $_POST['idcssales'.$updateid];
                            $ket = $_POST['ket'.$updateid];

                            $sql = $koneksi->query("UPDATE catatan set status='Done'
                                                    WHERE idcatatan='$updateid';");
                            
                            
                        }
                    if ($sql) {
                     echo "<script>alert('data berhasil disimpan');</script>";
                     echo "<script>location='catatan.php?id=$idcs';</script>";
                    }else{
                     echo "<script>alert('data gagal disimpan');</script>";
                     echo "<script>location='catatan.php?id=$idcs';</script>";      
                    }
                       
                    }                 
                  }

                  if(isset($_POST["undone"])){

                    if(isset($_POST['update'])){
                        foreach($_POST['update'] as $updateid){

                            $idcs = $_POST['idcssales'.$updateid];
                            $ket = $_POST['ket'.$updateid];

                            $sql = $koneksi->query("UPDATE catatan set status='Undone'
                                                    WHERE idcatatan='$updateid';");
                            
                            
                        }
                    if ($sql) {
                     echo "<script>alert('data berhasil disimpan');</script>";
                     echo "<script>location='catatan.php?id=$idcs';</script>";
                    }else{
                     echo "<script>alert('data gagal disimpan');</script>";
                     echo "<script>location='catatan.php?id=$idcs';</script>";      
                    }
                       
                    }                 
                  }                  

                 ?>           
          
          <!-- Content Row -->
          <div class="row">

           
      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; WNJ.ID Development 2020</span>
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
  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
  <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

<script type="text/javascript">

    $(document).ready(function(){

        $('#jenis_filter').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var jenis_filter = $('#jenis_filter').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_catatan.php',
                data :  'jenis_filter=' + jenis_filter,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tabel_po").html(data);
                }
                
            });
        });

    $('#jenis_filter').ready(function(){

      //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
      var jenis_filter = $('#jenis_filter').val();

          $.ajax({
              type : 'GET',
              url : 'cek_catatan.php',
              data :  'jenis_filter=' + jenis_filter,
          success: function (data) {

          //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
          $("#tabel_po").html(data);
        }
            });
    });



        
    });
</script> 

</body>
</html>


		                    