<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

  $iddropship = $_GET['iddropship'];
  $sql = mysqli_query($koneksi, "SELECT * FROM podropship where iddropship='$iddropship' ");
          
      
        $data = mysqli_fetch_array($sql); // Ambil semua data dari hasil eksekusi $sql
 
  ?> 
  
<html lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>

    <!-- Load File bootstrap.min.css yang ada difolder css -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.js"></script>
  <script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.dataTables.js"></script>
  <link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/dataTables.bootstrap.css">
    <!-- Load File bootstrap.min.css yang ada difolder css -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<title>Mitra <?php echo $_SESSION['mitraagen']['namamitra']; ?>| Wanoja </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>


  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">  

</head>
<body>

<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="listpreorder.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>Dropship</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #eee  url("jumbotron-bg.png") center center;
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

.navbaru p {
  
  padding: 12px 0;
  font-size: 20px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru i {
  
  padding: 15px 0;
  font-size: 23px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru2 {
   
    
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

</style>

<!--================ NAVBARU END =================-->
   <div class="container"> 

 
  <div class="container panel panel-default">
     
 
            <div class="panel-body">
            <div class="row">
            <div class="col-md-6">
                                
    
    
    <div style="padding: 0 15px;">
              
    <form method="post">
      
      <div class="form-group">
          <label>Nama Pengirim</label>
    <input type="text" class="form-control" name="namapengirim" value="<?php echo $data['namapengirim']; ?>">
    </div>
    
     <div class="form-group">
          <label>Telepon Pengirim</label>
    <input type="text" class="form-control" name="tlppengirim" value="<?php echo $data['tlppengirim']; ?>">
    </div>    
    
    <hr>
    
      <div class="form-group">
          <label>Nama Penerima</label>
    <input type="text" class="form-control" name="namapenerima" value="<?php echo $data['namapenerima']; ?>">
    </div>
    
    <div class="form-group">
    <label>Telepon Penerima</label>
    <input type="text" class="form-control" name="tlppenerima" value="<?php echo $data['tlppenerima']; ?>">
    </div>
    
        <div class="form-group">
    <label>Alamat Penerima</label>
    <textarea class="form-control" name="alamatpenerima"><?php echo $data['alamatpenerima']; ?></textarea>
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
    <label>Keterangan</label>
    <textarea class="form-control" name="keterangan"><?php echo $data['keterangan']; ?></textarea>
    </div>
    
             <div class="form-group">
          <label>Ekspedisi</label>  
          <select class="form-control" name="ekspedisi">
              <option><?php echo $data['ekspedisi']; ?></option>
              <option value="jne oke">JNE OKE</option>
              <option value="jne reg">JNE REG</option>
              <option value="jtr">JTR</option>
             <option value="jne yes">JNE YES</option>
              <option value="wahana">WAHANA</option>
              <option value="sicepat">SICEPAT</option>
              <option value="lion">LION PARCEL</option>
              <option value="j&t">J&T</option>
              <option value="tiki">TIKI</option>
              <option value="pos kilat">POS KILAT</option>
              <option value="pos ekonomi jumbo">POS EKONOMI JUMBO</option>
              <option value="gosend">Gosend</option>
              <option value='ide'>ID Express</option>                 
          </select>      
            </div>
    
   <center><button type="submit" class="btn btn-primary" name="kirim">Ubah</button></center>
  </form>
</body>
<script type="text/javascript">

    $(document).ready(function(){

        $('#prov').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var provinsi = $('#prov').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_kabupaten_dropship.php',
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
              url : 'cek_kecamatan_dropship.php',
              data :  'kabupaten_id=' + kabupaten,
          success: function (data) {

          //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
          $("#kecamatan").html(data);
        }
            });
    });


        
    });  
  
</script>
<?php
include "koneksi.php";

  if(isset($_POST['kirim'])){
 $namapengirim=addslashes($_POST["namapengirim"]);
   $tlppengirim=addslashes($_POST["tlppengirim"]);
    $namapenerima=addslashes($_POST["namapenerima"]);
   $tlppenerima=addslashes($_POST["tlppenerima"]);
   $alamatpenerima=addslashes($_POST["alamatpenerima"]);
   $keterangan=addslashes($_POST["keterangan"]);
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
   
   
    $query = "UPDATE podropship set namapengirim='$namapengirim',
                                    tlppengirim='$tlppengirim',
                                    namapenerima='$namapenerima',
                                    tlppenerima='$tlppenerima',
                                    alamatpenerima='$alamatpenerima',
                                    provinsi='$provinsi',
                                    kota='$kabupaten',
                                    kecamatan='$kecamatan',
                                    keterangan='$keterangan',
                                    ekspedisi='$ekspedisi' 

                                    where iddropship='$iddropship' ";
            $sql = mysqli_query( $koneksi, $query); 
            if ($sql) {
        echo "<script>alert('data berhasil diubah');</script>";
    echo "<script>location='listpreorder.php'</script>";
             } else{
                      echo "<script>alert('data gagal diubah');</script>";
    echo "<script>location='listpreorder.php'</script>";
             }

  } 
?>  
</html>