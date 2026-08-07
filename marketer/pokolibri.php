<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}


 $idpoproduk = $_GET['id'];
  $query = "SELECT poproduk.idpoproduk,poproduk.namapo 
            FROM poproduk 
            WHERE poproduk.idpoproduk='$idpoproduk'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);

  $idadmin=$_SESSION["mitraagen"]["idmitramarketer"];
 
  ?> 
 <title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| WNJ.ID </title>
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

  <div class="col-2"><a href="listpreorder.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>PRE ORDER</p></div>
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
  
<!--================ CONTAINER =================-->

  <div class="container"> 
      <center> <h4> <b>Formulir Pemesanan <br> <?= $data['namapo']; ?> </b> </h4> </center>


    <form method="POST">
      <div class="col-6">
    
      <div class="form-group">
          <label>Nama Penerima / Keluarga</label>
          <input type="text" class="form-control" name="namapenerima" required>
      </div>
    
      <div class="form-group">
        <label>Telepon Penerima</label>
        <input type="number" class="form-control" name="tlppenerima" required maxlength="17">
      </div>

        <div class="form-group">
          <label>Nama Pengirim</label>
          <input type="text" class="form-control" name="namapengirim" required>
        </div>
    
        <div class="form-group">
          <label>Telepon Pengirim</label>
          <input type="number" class="form-control" name="tlppengirim" required maxlength="17">
        </div>    

<div class="form-group">
  <button type="submit" class="btn btn-primary btn-lg" name="save">Kirim</button>  
</div>        
      </div>
    </form>

<?php
  if(isset($_POST["save"])){
    $namapengirim= addslashes(htmlspecialchars($_POST["namapengirim"]));
    $tlppengirim= addslashes(htmlspecialchars($_POST["tlppengirim"]));
    $namapenerima= addslashes(htmlspecialchars($_POST["namapenerima"]));
    $tlppenerima= addslashes(htmlspecialchars($_POST["tlppenerima"]));

    $sql = mysqli_query($koneksi, "SELECT iddropship FROM podropship order by iddropship desc limit 1");
      $data = mysqli_fetch_array($sql);

      $no=$data['iddropship'];
      
      $ab=1;
      $nobaru=$no+$ab;
      
      $huruf = 'M';
      
      $invoice=$huruf.$idadmin.'-'.$nobaru;

        $sql = $koneksi->query("INSERT into podropship (iddropship,
                                          idadmin,
                                          idmitraagen,
                                          idmitrareseller,
                                          idmitramarketer,
                                          idpoproduk,
                                          invoice,
                                          namapengirim,
                                          tlppengirim,
                                          namapenerima,
                                          tlppenerima,
                                          alamatpenerima,
                                          keterangan,
                                          ekspedisi) 
                  VALUES
                  (null,
                  '0',
                  '0',
                  '0',
                  '$idadmin',
                  '$idpoproduk',
                  '$invoice',
                  '$namapengirim',
                  '$tlppengirim',
                  '$namapenerima',
                  '$tlppenerima',
                  '-',
                  '-',
                  '-'
                  )");

         if ($sql) {
            echo "<script>alert('data berhasil dikirim');</script>";
            echo "<script>location='formpo_kolibri?invoice=$invoice';</script>";
         }else{
            echo "<script>alert('data gagal dikirim');</script>";
            echo "<script>location='pokolibri?id=$idpoproduk';</script>";
         }

  }       
?>

  </div>

<!--================ CONTAINER END=================-->

</body>
</html>