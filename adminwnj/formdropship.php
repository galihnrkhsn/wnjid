<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}
$invoice=$_GET["invoice"];
$datamitra=$koneksi->query("SELECT pomitra.idpoproduk, pomitra.idmitra
                            FROM pomitra  
                            where pomitra.invoice='$invoice'");  
$mitra=$datamitra->fetch_array();
$idpoproduk=$mitra['idpoproduk'];
$idadmin=$mitra['idmitra'];
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

<?php if($_SESSION["administrator"]["nama"]=='Produksi'){
include "sidebar2.php";     
} else{
include "sidebar.php";     
}
?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><a class="link" href="detaildropship.php?id=<?= $invoice; ?>"><i class="fa fa-arrow-left"></i> Kembali</a>   </h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>
          
          <!-- MULAI KONTEN AWS -->
            <div class="row w3-container">
                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Form PO Dropship</h6>

                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
<form method="post" class="col-6">      
  <div class="form-group">
    <label>Nama Pengirim</label>
    <input type="text" class="form-control" name="namapengirim" required>
  </div>
    
  <div class="form-group">
    <label>Telepon Pengirim</label>
    <input type="number" class="form-control" name="tlppengirim" required>
  </div>    
    
<hr>
    
  <div class="form-group">
    <label>Nama Penerima</label>
    <input type="text" class="form-control" name="namapenerima" required>
  </div>
    
  <div class="form-group">
    <label>Telepon Penerima</label>
    <input type="number" class="form-control" name="tlppenerima" required>
  </div>
    
  <div class="form-group">
    <label>Alamat Penerima</label>
    <textarea class="form-control" name="alamatpenerima" required></textarea>
  </div>

  <div class="form-group">
    <label for="prov">Provinsi Tujuan</label><br>
    <select class="form-control" id="prov" name="prov" required>
      <option disabled='disabled' selected>~Pilih Provinsi Tujuan~</option>
      <?php
        $ambil=$koneksi->query("SELECT * FROM tb_ro_provinces");
        while($row=$ambil->fetch_assoc()){
      ?>
      <option value="<?php echo $row['province_id']; ?>|<?php echo $row['province_name']; ?>"><?php echo $row['province_name']; ?></option>
      <?php } ?>
    </select>
  </div>    

                      <div class="form-group">
                        <label for="kabupaten">Kota/Kabupaten Tujuan</label><br>
                        <select class="form-control" id="kabupaten" name="kabupaten" required></select>
                      </div>
                      
                        <div class="form-group">
                        <label for="kecamatan">Kecamatan Tujuan</label><br>
                        <select class="form-control" id="kecamatan" name="kecamatan" required></select>
                      </div>   
    
  <div class="form-group">
    <label>Keterangan</label>
    <textarea class="form-control" name="keterangan"></textarea>
  </div>
    
  <div class="form-group">
          <label>Ekspedisi</label>  
          <select id="ekspedisi" class="form-control" name="ekspedisi">
            <option selected>~ Default Selected ~</option>
            <optgroup label="JNE">
                <option value="JNE Reg">JNE Reg</option>
                <option value="JNE YES">JNE YES</option>
                <option value="JNE Oke">JNE Oke</option>
                <option value="JNE CTC">JNE CTC</option>
                <option value="JTR">JNE Trucking (JTR)</option>
            <optgroup label="J&T">
                <option value="J&T">J&T</option>
                <option value="J&T Cargo">J&T Cargo</option>
            <optgroup label="WAHANA">
                <option value="Wahana Ekspres">Wahana Ekspres</option>
                <option value="Wahana Kargo">Wahana Kargo</option>
            <optgroup label="SICEPAT">
                <option value="Sicepat BEST">Sicepat BEST</option>
                <option value="Sicepat REG">Sicepat Reg</option>
                <option value="Sicepat Kargo">Sicepat Cargo</option>
            <optgroup label="POS">
                <option value="Pos Ekonomi Jumbo">Pos Ekonomi Jumbo</option>
                <option value="Pos Kilat">Pos Kilat</option>
            <optgroup label="LAINNYA">
                <option value="Paxel">Paxel</option>
                <option value="SPX Express">SPX Express</option>
                <option value="IDE">ID Express</option>
                <option value="Ahsan">Ahsan</option>
                <option value="Baraka">Baraka</option>
                <option value="Pegasus">Pegasus</option>
                <option value="Sentral">Sentral</option>
                <option value="Lion Parcel">Lion Parcel</option>
                <option value="Dakota">Dakota</option>
                <option value="Indah Cargo">IndahCargo</option>
                <option value="Adam Cargo">Adam Cargo</option>
                <option value="Gosend">GoSend</option>
                <option value="Kalog">Kalog</option>
                <option value="CMC KARGO">CMC CARGO</option>
                <option value="Tiki">Tiki</option>
                <option value="Triplogic">Triplogic</option>
                <option value="Anteraja">Anteraja</option>
                <option value="Ambil ke Pusat">Ambil Ke Pusat</option>
        </select>     
  </div>
    
   <center><button type="submit" class="btn btn-primary" name="kirim">Kirim</button></center>
</form>                                        
                                </div>             
                            </div>
                        </div>
            </div>  

                                   

        </div>   
            
<?php 
if(isset($_POST['kirim'])){
    $namapengirim=addslashes(htmlspecialchars($_POST["namapengirim"]));
   $tlppengirim=addslashes(htmlspecialchars($_POST["tlppengirim"]));
    $namapenerima=addslashes(htmlspecialchars($_POST["namapenerima"]));
   $tlppenerima=addslashes(htmlspecialchars($_POST["tlppenerima"]));
   $alamatpenerima=addslashes(htmlspecialchars($_POST["alamatpenerima"]));
   $keterangan=addslashes(htmlspecialchars($_POST["keterangan"]));
   $ekspedisi=$_POST["ekspedisi"];


   $provinsi_id=$_POST["prov"];
    $result_explode = explode('|', $provinsi_id);
    $provinsi=$result_explode[0];
   
   $kabupaten_id=$_POST["kabupaten"];
   $result_explode = explode('|', $kabupaten_id);
    $kabupaten=$result_explode[0];
   
   $kecamatan_id=$_POST["kecamatan"];
   $result_explode = explode('|', $kecamatan_id);
    $kecamatan=$result_explode[0];
   

   
    $query = "INSERT into podropship (iddropship,
                                      idadmin,
                                      idmitraagen,
                                      idmitrareseller,
                                      idmitramarketer,
                                      idpoproduk,invoice,
                                      namapengirim,tlppengirim,
                                      namapenerima,tlppenerima,
                                      alamatpenerima,
                                      provinsi,kota,kecamatan,
                                      keterangan,ekspedisi) values
    (null,'$idadmin','0','0','0',
    '$idpoproduk','$invoice',
    '$namapengirim','$tlppengirim',
    '$namapenerima','$tlppenerima',
    '$alamatpenerima',
    '$provinsi','$kabupaten','$kecamatan',
    '$keterangan','$ekspedisi')";
    $sql = mysqli_query( $koneksi, $query);  

    if ($sql) {
     echo "<script>alert('data berhasil ditambah');</script>";
    echo "<script>location='detaildropship.php?id=$invoice'</script>";
    }else{
      echo "<script>alert('data gagal ditambah');</script>";
    echo "<script>location='detaildropship.php?id=$invoice'</script>";
    }
    
    }

 ?>  
            
                  
              
            </div>
          <!-- AKHIR KONTEN AWS -->
          
          
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
          <a class="btn btn-primary" href="login.php">Logout</a>
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

  <script type="text/javascript">

  $(document).ready(function(){
    $('#prov').change(function(){
      //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
      var provinsi = $('#prov').val();
          $.ajax({
              type : 'GET',
              url : 'cek_kabupaten2.php',
              data :  'prov_id=' + provinsi,
          success: function (data) {
          //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
          $("#kabupaten").html(data);
        }
            });
    });
    
  $('#kabupaten').change(function(){
      //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
      var kabupaten = $('#kabupaten').val();
          $.ajax({
              type : 'GET',
              url : 'cek_kecamatan2.php',
              data :  'kabupaten_id=' + kabupaten,
          success: function (data) {
          //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
          $("#kecamatan").html(data);
        }
            });
    });

  });
  </script>
