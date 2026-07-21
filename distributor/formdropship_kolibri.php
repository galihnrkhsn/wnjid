<?php
session_start();
include 'koneksi.php'; 
include 'assets/components/Sessions/sesDistri.php';
$idadmin = $_SESSION['idadmin'];
$invoice = $_GET['id'];

  $query = "SELECT poproduk.idpoproduk,
            poproduk.namapo,
            pomitra.tgl,
            pomitra.waktu,
            podropship.namapengirim,
            podropship.tlppengirim,
            podropship.namapenerima,
            podropship.tlppenerima
        FROM poproduk 
        join pomitra on poproduk.idpoproduk=pomitra.idpoproduk
        join podropship on podropship.invoice = pomitra.invoice
        WHERE pomitra.invoice='$invoice'";
  $sqlpo = mysqli_query($koneksi, $query);  
  $datapo = mysqli_fetch_array($sqlpo);

 $idpoproduk = $datapo['idpoproduk'];


 
  ?> 
  
 <title>WNJ</title>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> 

</head>
<body>

<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="datapokolibri?invoice=<?= $invoice; ?>&idadmin=<?= $idadmin;?>"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>Alamat Pengiriman PO</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #eee  center center;
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

  <form method="post">  

        <div class="form-group">
          <label>Nama Pengirim</label>
          <input type="text" class="form-control" value="<?= $datapo['namapengirim'] ?>" name="namapengirim" required >
        </div>
    
        <div class="form-group">
          <label>Telepon Pengirim</label>
          <input type="number" class="form-control" value="<?= $datapo['tlppengirim'] ?>" name="tlppengirim" required maxlength="17" >
        </div>    
<hr>
    
      <div class="form-group">
          <label>Nama Penerima</label>
          <input type="text" class="form-control" value="<?= $datapo['namapenerima'] ?>" name="namapenerima" required >
      </div>
    
      <div class="form-group">
        <label>Telepon Penerima</label>
        <input type="number" class="form-control" value="<?= $datapo['tlppenerima'] ?>" name="tlppenerima" required maxlength="17" >
      </div>

  
                    <div class="form-group">
                        <label for="jenis">Jenis Pengiriman</label><br>
                        <select class="form-control" name="jenis" id="jenis" required>
                          <option value="Dropship|<?= $invoice; ?>">Dropship</option> 
                          <option value="Pribadi|<?= $invoice; ?>">Alamat Pribadi</option>           
                        </select>
                    </div>
                    <div class="form-group"  id="tabel_alamat" name="tabel_alamat"></div>  

                   

<div class="form-group">
 <center><button type="submit" class="btn btn-primary btn-lg" name="kirim">Kirim</button></center>  
</div>                    

 </form>
 
    </div>


<?php
include "koneksi.php";

   
  if(isset($_POST['kirim'])){
    $namapengirim=addslashes(htmlspecialchars($_POST["namapengirim"]));
    $tlppengirim=addslashes(htmlspecialchars($_POST["tlppengirim"]));
    $namapenerima=addslashes(htmlspecialchars($_POST["namapenerima"]));
    $tlppenerima=addslashes(htmlspecialchars($_POST["tlppenerima"]));
    $alamat= addslashes(htmlspecialchars($_POST["alamat"]));
    $keterangan=addslashes(htmlspecialchars($_POST["keterangan"]));
    $berat=$_POST["berat"];
    $jenis=$_POST["jenisnya"];
  
    $provinsi_id=$_POST["prov"];
    $result_explode = explode('|', $provinsi_id);
    $provinsi=$result_explode[0];
   
    $kabupaten_id=$_POST["kabupaten"];
    $result_explode = explode('|', $kabupaten_id);
    $kabupaten=$result_explode[0];
   
    $kecamatan_id=$_POST["kecamatan"];
    $result_explode = explode('|', $kecamatan_id);
    $kecamatan=$result_explode[0];

    $layanan=$_POST["layanan"];
    $result_explode = explode('|', $layanan);
    $layananku=$result_explode[0]; 

    $ekspedisinya=$_POST["kurir"];
    $result_explode = explode('|', $ekspedisinya);
    $om=$result_explode[0];
    $ekspedisi=$result_explode[1];

    if ($layanan=='layanan') {
         echo "<script>alert('Gagal Simpan, Jenis layanan masih kosong');</script>";
        echo "<script>location='formdropship_kolibri?&id=$invoice'</script>";
        return false;
      }
      // if ($layanan=='' and $om<>'OM') {
      //     echo "<script>alert('Gagal Simpan, Jenis layanan masih kosong');</script>";
      //     echo "<script>location='formpengiriman.php?id=$_GET[id]&berat=$_GET[berat]'</script>";
      //   return false;
      // }
   
   
   
    $result_explode = explode('|', $layanan);
    $ongkir2=$result_explode[1];
    $ongkir=(int)"$ongkir2";


if($berat<=5000 and $berat>=0 and $jenis=='Dropship') {
            $biayad=3000;
        }
        else if($berat<=10000 and $berat>=6000 and $jenis=='Dropship') {
            $biayad=5000;
        }
        else if($berat<=20000 and $berat>=11000 and $jenis=='Dropship') {
            $biayad=10000;
        }
         else if($berat<=30000 and $berat>=21000 and $jenis=='Dropship') {
            $biayad=15000;
        }
        else if($berat<=40000 and $berat>=31000 and $jenis=='Dropship') {
            $biayad=20000;
        }
         else if($berat<=50000 and $berat>=41000 and $jenis=='Dropship') {
            $biayad=25000;
        }
        else if($berat<=60000 and $berat>=51000 and $jenis=='Dropship') {
            $biayad=30000;
        }
         else if($berat<=70000 and $berat>=61000 and $jenis=='Dropship') {
            $biayad=35000;
        }
        else if($berat<=80000 and $berat>=71000 and $jenis=='Dropship') {
            $biayad=40000;
        }
         else if($berat<=90000 and $berat>=81000 and $jenis=='Dropship') {
            $biayad=45000;
         }
        else if($berat<=100000 and $berat>=91000 and $jenis=='Dropship') {
            $biayad=50000;
         }
        else if($berat<=110000 and $berat>=101000 and $jenis=='Dropship') {
            $biayad=55000;
        }
         else if($berat<=120000 and $berat>=111000 and $jenis=='Dropship') {
            $biayad=60000;
        }
        else if($berat<=130000 and $berat>=121000 and $jenis=='Dropship') {
            $biayad=65000;
        }
         else if($berat<=140000 and $berat>=131000 and $jenis=='Dropship') {
            $biayad=70000;
        }
        else if($berat<=150000 and $berat>=141000 and $jenis=='Dropship') {
            $biayad=75000;
        }
         else if($berat<=160000 and $berat>=151000 and $jenis=='Dropship') {
            $biayad=80000;
        }
        else if($berat<=170000 and $berat>=161000 and $jenis=='Dropship') {
            $biayad=85000;
        }
         else if($berat<=180000 and $berat>=171000 and $jenis=='Dropship') {
            $biayad=90000;
         }
        else if($berat<=190000 and $berat>=181000 and $jenis=='Dropship') {
            $biayad=95000;
        }
         else if($berat<=200000 and $berat>=191000 and $jenis=='Dropship') {
            $biayad=100000;
         }
        else if($jenis=='Pribadi'){
            $biayad=0;
        }      

// echo "<script>alert('$invoice, $alamat, $provinsi, $kabupaten, $kecamatan, $keterangan, $ekspedisi, $layananku, $ongkir, $biayad, $jenis');</script>";

    $query = "UPDATE podropship SET namapengirim='$namapengirim',
                                    tlppengirim='$tlppengirim',
                                    namapenerima='$namapenerima',
                                    tlppenerima='$tlppenerima',
                                    alamatpenerima='$alamat',
                                    provinsi='$provinsi',
                                    kota='$kabupaten',
                                    kecamatan='$kecamatan',
                                    ekspedisi='$ekspedisi',
                                    layanan='$layananku',
                                    ongkir='$ongkir',
                                    dropship='$biayad' 
                                    WHERE invoice='$invoice' ";        
     $sql = mysqli_query( $koneksi, $query);
  
    if ($sql) {
      echo "<script>alert('data berhasil ditambah $namapengirim');</script>";
      echo "<script>location='datapokonin2?invoice=$invoice&idadmin=$idadmin'</script>";
    }else{
      echo "<script>alert('data gagal ditambah');</script>";
      echo "<script>location='datapokolibri?invoice=$invoice&idadmin=$idadmin'</script>";
    }

  
  } 
?>  

<script type="text/javascript">

    $(document).ready(function(){

        $('#jenis').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var jenis = $('#jenis').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_dropship.php',
                data :  'jenis=' + jenis,
                    success: function (data) {

                    //jika data berhasil didapatkan, agen_tampilkan ke dalam option select kabupaten
                    $("#tabel_alamat").html(data);
                }
                
            });
        });

        $('#jenis').ready(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var jenis = $('#jenis').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_dropship.php',
                data :  'jenis=' + jenis,
                    success: function (data) {

                    //jika data berhasil didapatkan, agen_tampilkan ke dalam option select kabupaten
                    $("#tabel_alamat").html(data);
                }
                
            });
        });
    });
</script> 

</body>
</html>