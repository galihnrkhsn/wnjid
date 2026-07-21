<?php
  session_start();
  include 'koneksi.php';

  if(!isset($_SESSION['admin_mitra'])) {
    echo "<script>alert('Anda harus login terlebih dahulu');</script>";
    echo "<script>location='login2.php';</script>";
    header('location:login2.php');
    exit();
  }

  $idpoproduk = $_GET['id'];
  $query = "SELECT poproduk.* FROM poproduk WHERE poproduk.idpoproduk='$idpoproduk'";
  $sqlpo = mysqli_query($koneksi, $query);
  $datapo = mysqli_fetch_array($sqlpo);

  $querybukapo = "SELECT bukapo.*, poproduk.* FROM bukapo
                  INNER JOIN poproduk ON bukapo.idpoproduk = poproduk.idpoproduk
                  WHERE poproduk.idpoproduk = '$idpoproduk'
                  AND (bukapo.jenis_mitra = 'Semua Mitra' or bukapo.jenis_mitra = 'Distributor')
                ";
  $sqlbukapo = mysqli_query($koneksi, $querybukapo);
  $databukapo = mysqli_fetch_array($sqlbukapo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WNJ | Data Kolibri 2024</title>
  
  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
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

  <style>
    .navbaru {
      background: #eee   center center;
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
</head>
<body>
  
  <!-- Navbar -->
  <div class="row fixed-top navbaru">
    <div class="col-2"><a href="listnewpo.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
    <div class="col-8" ><p>PRE ORDER</p></div>
    <div class="col-2"></div>
  </div>

  <div class="container pt-5" align="center" style="margin-top: 5rem">
    <?php
      $idadmin=$_SESSION["admin_mitra"]["idadmin"];
      $ambil=$koneksi->query("SELECT * FROM admin_mitra where idadmin='$idadmin' ");
      while($tampilkan=$ambil->fetch_assoc()){
    ?>
      <h4 class="text-uppercase">Data Summary <?= $tampilkan['namamitra'] ?></h4>
    <?php } ?>

    <hr>
    
    <table class="table table-striped">
      <thead>
        <tr class="text-uppercase">
          <th>No</th>
          <th>Variant</th>
          <th>Invoice</th>
          <th>QTY</th>
          <th>Harga</th>
        </tr>
      </thead>
      
      <tbody>
        <?php
          $idmitra = $_SESSION["admin_mitra"]['idadmin'];
          $sqlData = $koneksi->query("SELECT podetail.*, pomitra.* FROM pomitra 
                                      INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                      WHERE pomitra.idmitra = '$idmitra'
                                      AND pomitra.idpoproduk = '$idpoproduk'
                                      ORDER BY pomitra.invoice ASC 
                                    ");
          $no = 1;
          while($tampil = $sqlData->fetch_assoc()) {
        ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= $tampil['variant']; ?></td>
            <td><?= $tampil['invoice']; ?></td>
            <td><?= $tampil['jumlah']; ?></td>
            <td><?= $tampil['total']; ?></td>
          </tr>
        <?php
                $qty += $tampil['jumlah'];
                $harga += $tampil['jumlah'] * $tampil['total'];
            }
        ?>
        <tr>
            <th class="text-uppercase" colspan="3">Total</th>
            <td><?= $qty; ?></td>
            <td><?= $harga; ?></td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Script -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>