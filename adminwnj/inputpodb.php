<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$idpoproduk = $_GET['id'];
  $query = "SELECT namapo FROM poproduk  WHERE idpoproduk='$idpoproduk'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);
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
 
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">


        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary"><?= $data['namapo']; ?></h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div>

<form method="post" class="col-4">  
    <div class="form-group">
          <label>Pilih Nama DB</label>
          <select class="form-control" name="idmitra" id="idmitra" required>
              <option value="" selected>- Pilih Nama DB -</option>
              <?php
              $datadb=$koneksi->query("SELECT * FROM admin_mitra ORDER BY namamitra asc");
              while($tampilkan=$datadb->fetch_assoc()){
              ?>
          <option value="<?php echo $tampilkan['idadmin']; ?>"><?php echo $tampilkan['namamitra']; ?></option>
          <?php } ?>                       
          </select>      
    </div> 

<button class="btn btn-primary" type="submit" name="pilih">Pilih</button>
</form>

     <?php
  if(isset($_POST["pilih"])){
  $idmitra=$_POST["idmitra"];
$tampil =$koneksi->query("SELECT * FROM admin_mitra where idadmin='$idmitra' ");
         $tampilMas=$tampil->fetch_assoc();  
  }
?>
<br>
<form method="post" class="col-4">   
<div  class="form-group">
  <label>Variant</label>  
  <select class="form-control" name="idpodetail[]" id="idpodetail[]" required>

        <?php
            $sql = "SELECT * FROM poproduk 
            inner join pokategori on poproduk.idpoproduk=pokategori.idpoproduk 
            inner join podetail  on pokategori.idpo=podetail.idpo 
            where poproduk.idpoproduk='$idpoproduk' 
            order by podetail.idpodetail asc";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>                        
<option value="<?= $row['idpodetail']; ?>"><?= $row['variant']; ?></option>
                
<?php } ?> 
</select> 
</div>

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
<input type="hidden" name="idadmin" value="<?= $tampilMas['idadmin']; ?>">
              <div class="form-group">
          <label for="prov">Provinsi</label><br>
          <select class="form-control" id="prov" name="prov" required>
          <?php
          $provinsi=$tampilMas['provinsi'];
          $ambil2=$koneksi->query("SELECT * FROM tb_ro_provinces where province_id='$provinsi' ");
          $row2=$ambil2->fetch_assoc();
          ?>
          <option value="<?php echo $row2['province_id']; ?>|<?php echo $row2['province_name']; ?>" selected><?php echo $row2['province_name']; ?></option>
          <?php
          $ambil=$koneksi->query("SELECT * FROM tb_ro_provinces ");
          while($row=$ambil->fetch_assoc()){
          ?>
          <option value="<?php echo $row['province_id']; ?>|<?php echo $row['province_name']; ?>"><?php echo $row['province_name']; ?>
          </option>
          <?php } ?>
          </select>
          </div>
   
          <div class="form-group">
          <label for="kabupaten">Kota/Kabupaten</label><br>
          <select class="form-control" id="kabupaten" name="kabupaten" required>
          <?php
          $kota=$tampilMas['kota'];
          $ambil3=$koneksi->query("SELECT * FROM tb_ro_cities where city_id='$kota' ");
          $row3=$ambil3->fetch_assoc();
          ?>
          <option value="<?php echo $row3['city_id']; ?>|<?php echo $row3['city_name']; ?>" selected><?php echo $row3['city_name']; ?></option>
          </select>
          </div>
                      
          <div class="form-group">
          <label for="kecamatan">Kecamatan</label><br>
          <select class="form-control" id="kecamatan" name="kecamatan" required>
            <?php
          $kecamatan=$tampilMas['kecamatan'];
          $ambil4=$koneksi->query("SELECT * FROM tb_ro_subdistricts where subdistrict_id='$kecamatan' ");
          $row4=$ambil4->fetch_assoc();
          ?>
          <option value="<?php echo $row4['subdistrict_id']; ?>|<?php echo $row4['subdistrict_name']; ?>" selected><?php echo $row4['subdistrict_name']; ?></option>
          </select>
          </div>
    
            <div class="form-group">
    <label>Keterangan</label>
    <textarea class="form-control" name="keterangan"></textarea>
    </div>
    
             <div class="form-group">
          <label>Ekspedisi</label>  
          <select class="form-control" name="ekspedisi">
              <option value="jne oke">JNE OKE</option>
              <option value="jne reg">JNE REG</option>
              <option value="jtr">JTR</option>
             <option value="jne yes">JNE YES</option>
              <option value="wahana">WAHANA</option>
              <option value="sicepat">SICEPAT</option>
              <option value="lion">LION PARCEL</option>
              <option value="j&t">J&T</option>
              <option value="tiki">TIKI</option>
              <option value='ide'>ID Express</option>
              <option value="pos kilat">POS KILAT</option>
              <option value="pos ekonomi jumbo">POS EKONOMI JUMBO</option>
              <option value="gosend">Gosend</option>              
          </select>      
            </div>

  
  <br>
<button type="submit" name="but_save_po" class="btn btn-primary"><i class="fa fa-plus"></i> Simpan</button>  
</form>
                                    </div>
                                </div>
                            </div>
                        </div>           





  </div>        

</div>

<?php 
     

 if(isset($_POST['but_save_po'])){
date_default_timezone_set('Asia/Jakarta');
$waktu = date("H:i:s");
$tanggal = date("Y-m-d");
$tglnya = date("dm");
$waktunya = date("Hi");
$idpodetail=$_POST["idpodetail"];
$jmlh=1;
$idadmin = $_POST["idadmin"];
$invoice = 'D'.$idpoproduk.'-'.$idadmin;
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

  $jumlah_dipilih = count($idpodetail);
                                    
  for($x=0;$x<$jumlah_dipilih;$x++){
  $query_variant = "SELECT podetail.harga, podetail.idpo
            FROM podetail
            WHERE podetail.idpodetail='$idpodetail[$x]'";
  $sql_variant = mysqli_query($koneksi, $query_variant);  
  $data_variant = mysqli_fetch_array($sql_variant);
  $harga = $data_variant['harga'];
  $idpo = $data_variant['idpo'];
$total=$jmlh*$harga;                

$ambil=$koneksi->query("SELECT idpodetail, idmitra FROM pomitra WHERE idpodetail='$idpodetail[$x]' and idmitra ='$idadmin' and idpoproduk='$idpoproduk' ");
    $datacocok=$ambil->num_rows;
    if($datacocok>=1){
      echo "<script>alert('data sudah ada');</script>";
echo "<script>location='inputpodb.php?id=$idpoproduk';</script>";
return false;
    }else{
// echo "<script>alert('$idadmin $idpoproduk $idpo $idpodetail[$x] $jmlh $total D$idpoproduk-$idadmin $waktu');</script>";
  $sql = $koneksi->query("INSERT into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) values
                                     (null,'$idadmin','$idpoproduk','$idpo','$idpodetail[$x]','$jmlh','$total','$invoice','Belum DP',NOW(),'$waktu')");

      $query = "INSERT into podropship (iddropship,
                                        idadmin,idmitraagen,idmitrareseller,idmitramarketer,
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
    $sql2 = mysqli_query( $koneksi, $query);  
      
    }
                                      
                                                    }
if ($sql) {
        echo "<script>alert('data berhasil dikirim');</script>";
        echo "<script>location='inputpodb.php?id=$idpoproduk';</script>";
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


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script src="assets/dist/js/jquery.min.js"></script>
    <script src="assets/dist/js/bootstrap.min.js"></script>
    <script src="assets/dist/DataTables/datatables.min.js"></script>

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

</body>

</html>

		                                                