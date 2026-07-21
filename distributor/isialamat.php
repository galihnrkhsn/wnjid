<?php 
session_start();

include 'koneksi.php'; 
include 'assets/components/Sessions/sesDistri.php';

// if(!isset($_SESSION)){
//   echo "<script>alert('anda harus login terlebih dahulu');</script>";
//    echo "<script>location='login2.php';</script>";
//    header('location:login2.php');
//    exit();
// }

$idadmin = $_SESSION['admin_mitra']['idadmin'];
  $query = "SELECT * FROM admin_mitra 
  LEFT JOIN tb_ro_provinces on admin_mitra.provinsi = tb_ro_provinces.province_id
  LEFT JOIN tb_ro_cities on admin_mitra.kota = tb_ro_cities.city_id
  LEFT JOIN tb_ro_subdistricts on admin_mitra.kecamatan = tb_ro_subdistricts.subdistrict_id

  WHERE admin_mitra.idadmin='$idadmin'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql); 
?>

<html lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<!--================ NAVBARU  =================-->
    <!-- NAVBAR -->
    <? include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

  <div class="container">
    <form method="post">
      <div class="form-group">
        <label>Alamat</label>
        <textarea class="form-control" name="alamat"><?php echo $data['alamat']; ?></textarea>
      </div>
      <div class="form-group">
        <label>Kode POS</label>
        <input type="number" class="form-control" name="kodepos" value="<?php echo $data['kodepos']; ?>" maxlength="6">
      </div>
      <div class="form-group">
        <label>Titik Koordinat</label>  
        <input class="form-control" name="titikkordinat" value="<?php echo $data['titikkordinat']; ?>">
        <label><font size="2" color="silver">1.Buka Aplikasi Google Maps<br>
          2.Cari Lokasi kita, Zoom sampai lokasi terlihat detail<br>
          3.Tekan dan Tahan sampai muncul titik kordinat berada di atas (contoh:-6,121.232.....)<br>
          4.Copy Paste Nomor tersebut, lalu masukan pada form ubah</font>
        </label>
      </div>
      <div class="form-group">
        <label for="prov">Provinsi Tujuan</label><br>
        <select class="form-control" id="prov" name="prov" required>
          <optgroup label="~Pilih Provinsi Tujuan~">
            <option value="<?= $data['provinsi'];?>|<?= $data['province_name'];?>" selected><?= $data['province_name'];?></option>
              <?php
                $ambil=$koneksi->query("SELECT * FROM tb_ro_provinces ");
                while($row=$ambil->fetch_assoc()){
              ?>
            <option value="<?php echo $row['province_id']; ?>|<?php echo $row['province_name']; ?>"><?php echo $row['province_name']; ?></option>
              <?php } ?>
          </optgroup>
        </select>
      </div>
      <div class="form-group">
        <label for="kabupaten">Kota/Kabupaten Tujuan</label><br>
        <select class="form-control" id="kabupaten" name="kabupaten" required>
          <option value="<?= $data['kota'];?>" selected><?= $data['city_name'];?></option> 
        </select>
      </div>
      <div class="form-group">
        <label for="kecamatan">Kecamatan Tujuan</label><br>
        <select class="form-control" id="kecamatan" name="kecamatan" required>
          <option value="<?= $data['kecamatan'];?>" selected><?= $data['subdistrict_name'];?></option>
        </select>
      </div>
      
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
      <center><button type="submit" class="btn btn-primary btn-lg" name="kirim">Kirim</button></center>
    </form>
  </div>

  <!-- FOOTER -->
  <br><br><br><br>
  <? include 'menubawah.php'; ?>
  <!-- FOOTER END -->
 
<?php
  include "koneksi.php";
  if(isset($_POST['kirim'])){
    $idadmin=$_SESSION["idadmin"];   
    $alamat=$_POST["alamat"];
  
    $provinsi_id=$_POST["prov"];
    $result_explode = explode('|', $provinsi_id);
    $provinsi=$result_explode[0];
   
    $kabupaten_id=$_POST["kabupaten"];
    $result_explode = explode('|', $kabupaten_id);
    $kabupaten=$result_explode[0];
   
    $kecamatan_id=$_POST["kecamatan"];
    $result_explode = explode('|', $kecamatan_id);
    $kecamatan=$result_explode[0];
   
    $kodepos=$_POST["kodepos"];
    $titikkordinat=$_POST["titikkordinat"];
   
    $koneksi->query("UPDATE admin_mitra set alamat='$alamat',provinsi='$provinsi',kota='$kabupaten',kecamatan='$kecamatan',kodepos='$kodepos',titikkordinat='$titikkordinat' where idadmin='$idadmin' ");
    echo "<script>alert('Alamat Berhasil Di Ubah, silahkan re-login Kembali untuk Refresh data Anda');</script>";
        echo "<script>location='logout.php';</script>";}
?>