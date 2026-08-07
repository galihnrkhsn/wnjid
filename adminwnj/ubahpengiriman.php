<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}
$invoice=$_GET['invoice'];

  $query = "SELECT *
        FROM orderpengiriman 
        WHERE orderpengiriman.invoice='$invoice'";
  $sqlpo = mysqli_query($koneksi, $query);  
  $datapo = mysqli_fetch_array($sqlpo);

  $berat = $datapo['berat'];
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
  
  <style>
  .aws {
      border:8px solid #eff4ff;;
      
      padding: 10px;
      
      }
    .aws text
    {
        color: white;
        font-size: x-large;
        text-align: right;
    }
    .aws p
    {
        color: white;
        text-align: left;
        font-size: ;
       
    }
    .aws button 
    {
        text-align: left;
    }
       .aws a 
    {
        text-align: left;
    }
  </style>
  
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">
<!-- ==========================THIS========================== -->
<?php 
if($_SESSION["administrator"]["nama"]=='Produksi'){
include "sidebar2.php";     
} else{
include "sidebar.php";     
}
?>
<!-- ==========================THIS========================== -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Ubah Pengiriman</h1>

            <h1 class="h3 mb-0 text-gray-800"><a href="pengiriman.php"><span class="fa fa-chevron-left"></span> Kembali</a></h1>
          </div>

	<div class="table-responsive">
             
<form method="post">
 
      <div class="form-group">
          <label>Nama Pengirim</label>
    <input type="text" class="form-control" name="namapengirim" required value="<?=$datapo['namapengirim'];  ?>">
    </div>
    
     <div class="form-group">
          <label>Telepon Pengirim</label>
    <input type="text" class="form-control" name="tlppengirim" required maxlength="17" value="<?= $datapo['tlppengirim']; ?>">
    </div>    
    
    <hr>
    
      <div class="form-group">
          <label>Nama Penerima</label>
    <input type="text" class="form-control" name="namapenerima" required value="<?= $datapo['namapenerima']; ?>">
    </div>
    
    <div class="form-group">
    <label>Telepon Penerima</label>
    <input type="text" class="form-control" name="tlppenerima" required maxlength="17" value="<?= $datapo['tlppenerima']; ?>">
    </div>
    
        <div class="form-group">
    <label>Alamat</label>
    <textarea class="form-control" name="alamat" required><?= $datapo['alamat']; ?></textarea>
    </div>
    
     <div class="form-group">
    <label>Kode POS</label>
    <input type="text" class="form-control" name="kodepos" required value="<?= $datapo['kodepos']; ?>">
    </div>

    <div class="form-group">
  <label for="prov">Provinsi Tujuan</label><br>
  <select class="form-control" id="prov" name="prov" required>
     <option disabled='disabled' selected>~Pilih Provinsi Tujuan~</option>
   <?php
   include "koneksi.php";
   $idprov=$_SESSION["admin_mitra"]["provinsi"];
   
  
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
                        <label for="berat">Berat (gram)</label><br>
                        <input class="form-control" id="berat" type="text" name="berat" value="<?php echo $berat; ?>" readonly />
                      </div>
                      
                      <div class="form-group">
                        <label for="kurir">Kurir</label><br>
                        <select class="form-control" id="kurir" name="kurir" required>
                             <option disabled='disabled' value="" selected>~Pilih Kurir Pengiriman~</option>
                             <option value="OR|jne">JNE</option>
                              <option value="OR|tiki">TIKI</option>
                          <option value="OR|pos">POS INDONESIA</option>
                          <option value="OR|wahana">WAHANA</option>
                          <option value="OR|sicepat">SICEPAT</option>
                          <option value='OR|jnt'>J&T</option>
                          <option value='OR|lion'>LION</option>
                          <option value='OR|anteraja'>Anteraja</option>
                          <option value='OR|ide'>ID Express</option>
                          <optgroup label="Lainnya (Ongkir Manual)">
                                          <option value='OM|idetruck'>ID Express Truck</option>
                                          <option value='OM|jntcargo'>J&T Cargo</option>
                                          <option value='OM|jtr'>JTR</option>
                                          <!-- <option value='OM|anteraja'>Anteraja</option> -->
                                          <option value='OM|Ahsan'>Ahsan</option>
                                          <option value='OM|Baraka'>Baraka</option>
                                          <option value='OM|Dakota'>Dakota</option>
                                          <option value='OM|IndahCargo'>IndahCargo</option>
                                          <option value='OM|Adam Cargo'>Adam Cargo</option>
                                          <option value='OM|Pegasus'>Pegasus</option>
                                          <option value='OM|Gosend'>GoSend</option>
                                          <option value='OM|KALOG'>KALOG</option>
                                          <option value='OM|Sentral'>Sentral</option>
                                          <option value='OM|CMC KARGO'>CMC CARGO</option>
                                          <option value='OM|Triplogic'>Triplogic</option>
                                          <option value='OM|Ambil ke Pusat'>Ambil Ke Pusat</option>
                                          <option value='OM|Disatukan'>Disatukan Paket Lainnya</option>
                                        </optgroup>
                        </select>
                      </div>
                      
                    <div class="form-group" id="ongkir">
                        <label for="layanan">Layanan</label><br>
                        <select class="form-control" name="layanan" id="layanan" >
                      <option value="layanan">-kosong-</option>           
                        </select>
                        <label><font color="grey">*Jika Memilih Kurir dengan Kategori "Lainnya (Ongkir Manual)" lanjut pilih "Kirim" jika opsi layanan masih kosong</font></label>
                    </div>

                    <div class="form-group">
                        <label for="dropship">Dropship</label><br>
                        <select class="form-control" name="dropship" id="dropship">
                      <option value="ya">Ya</option>
                      <option value="tidak">Tidak</option>           
                        </select>
                      </div>

 <center><button type="submit" class="btn btn-primary btn-lg" name="kirim">Kirim</button></center>
 </form>			

<?php
include "koneksi.php";

   
  if(isset($_POST['kirim'])){
    $namapengirim= addslashes(htmlspecialchars($_POST["namapengirim"]));
   $tlppengirim= addslashes(htmlspecialchars($_POST["tlppengirim"]));
    $namapenerima= addslashes(htmlspecialchars($_POST["namapenerima"]));
   $tlppenerima= addslashes(htmlspecialchars($_POST["tlppenerima"]));
   $alamat= addslashes(htmlspecialchars($_POST["alamat"]));
   $dropship= addslashes(htmlspecialchars($_POST["dropship"]));
  
   $provinsi_id=$_POST["prov"];
    $result_explode = explode('|', $provinsi_id);
    $provinsi=$result_explode[1];
   
   $kabupaten_id=$_POST["kabupaten"];
   $result_explode = explode('|', $kabupaten_id);
    $kabupaten=$result_explode[1];
   
   $kecamatan_id=$_POST["kecamatan"];
   $result_explode = explode('|', $kecamatan_id);
    $kecamatan=$result_explode[1];

   $layanan=$_POST["layanan"];
  $result_explode = explode('|', $layanan);
    $layananku=$result_explode[0]; 

       $ekspedisinya=$_POST["kurir"];
    $result_explode = explode('|', $ekspedisinya);
    $om=$result_explode[0];
    $ekspedisi=$result_explode[1];

    if ($layanan=='layanan') {
         echo "<script>alert('Gagal Simpan, Jenis layanan masih kosong');</script>";
        echo "<script>location='ubahpengiriman.php?invoice=$invoice'</script>";
        return false;
      }
      if ($layanan=='' and $om<>'OM') {
          echo "<script>alert('Gagal Simpan, Jenis layanan masih kosong');</script>";
          echo "<script>location='ubahpengiriman.php?invoice=$invoice'</script>";
        return false;
      }
   
   $berat=$_POST["berat"];
   
  $result_explode = explode('|', $layanan);
    $ongkir2=$result_explode[1];
    $ongkir=(int)"$ongkir2";
   
   $kodepos=$_POST["kodepos"];
   $total=0;
   
  $sql = "SELECT subtotal FROM ordermitra WHERE invoice='$invoice' ";
  $query = $koneksi->query($sql);
  while($row = $query->fetch_assoc()){
      $total= $total+$row['subtotal'];
  }
   $total=$total+$ongkir;

$sqlcek = "SELECT * FROM orderpengiriman WHERE invoice='$invoice' ";
    $query = $koneksi->query($sqlcek);
    $pengirimancek = $query->fetch_assoc();

    if ($pengirimancek) {
        $querydelete = "DELETE FROM orderpengiriman WHERE invoice = '$invoice'";
        $sqldelete = mysqli_query( $koneksi, $querydelete); 
        $query = "INSERT into orderpengiriman (idorderp,namapengirim,tlppengirim,namapenerima,tlppenerima,alamat,provinsi,kota,kecamatan,ekspedisi,layanan,berat,ongkir,dropship,kodepos,invoice,total,diskonramadhan,tgl) values
    (null,'$namapengirim','$tlppengirim','$namapenerima','$tlppenerima','$alamat','$provinsi','$kabupaten','$kecamatan','$ekspedisi','$layananku','$berat','$ongkir','$dropship','$kodepos','$invoice','$total',0,NOW())"; 
     $sql = mysqli_query( $koneksi, $query); 
      }
      else{
          $query = "INSERT into orderpengiriman (idorderp,namapengirim,tlppengirim,namapenerima,tlppenerima,alamat,provinsi,kota,kecamatan,ekspedisi,layanan,berat,ongkir,dropship,kodepos,invoice,total,diskonramadhan,tgl) values
    (null,'$namapengirim','$tlppengirim','$namapenerima','$tlppenerima','$alamat','$provinsi','$kabupaten','$kecamatan','$ekspedisi','$layananku','$berat','$ongkir','$dropship','$kodepos','$invoice','$total',0,NOW())";
     $sql = mysqli_query( $koneksi, $query); 
            
      }      
  if ($sql) {
    echo "<script>alert('Berhasil Diubah');</script>";
        echo "<script>location='pengiriman.php'</script>";
  }else{
        echo "<script>alert('Gagal Diubah');</script>";
        echo "<script>location='pengiriman.php'</script>";
  }
  } 
?>  
                     </div>
             
            </div>
          <!-- AKHIR KONTEN AWS -->
          
          
          <!-- Content Row -->
          <div class="row">

           

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



    $("#kurir").change(function(){
      //Mengambil value dari option select provinsi asal, kabupaten, kurir, berat kemudian parameternya dikirim menggunakan ajax
      var asal = $('#asal').val();
      var kab = $('#kabupaten').val();
      var kec = $('#kecamatan').val();
      var kurir = $('#kurir').val();
      var berat = $('#berat').val();

          $.ajax({
              type : 'POST',
              url : 'cek_ongkir.php',
              data :  {'kab_id' : kab, 'kec_id' : kec, 'kurir' : kurir, 'asal' : asal, 'berat' : berat},
          success: function (data) {

          //jika data berhasil didapatkan, tampilkan ke dalam element div ongkir
          $("#layanan").html(data);
        }
            });
    });
  });
</script>
</body>

</html>

		                                                