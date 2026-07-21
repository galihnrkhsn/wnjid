<?php
  session_start();
  include 'koneksi.php'; 
  if(!isset($_SESSION["admin_mitra"])){
    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login2.php';</script>";
    header('location:login2.php');
    exit();
  }

  $idpoproduk = $_GET["id"];
  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
  $query = "SELECT COUNT(*) as jumlah, poproduk.idpoproduk, poproduk.namapo, poproduk.status
            FROM poproduk inner join pomitra
            ON poproduk.idpoproduk = pomitra.idpoproduk
            WHERE poproduk.idpoproduk = '$idpoproduk' AND pomitra.idmitra='$idadmin'
          ";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WNJ | Form PO Miki Hat Custom</title>

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
</head>
<body>

  <!-- Navbar -->
  <div class="row fixed-top navbaru">
    <div class="col-2"><a href="listnewpo.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
    <div class="col-8"><p>PRE ORDER</p></div>
    <div class="col-2"></div>
  </div>

  <div class="container" style="margin-top: 10rem; margin-bottom: 10rem">
    <div class="row">
      
      <div class="col-sm-12 d-flex justify-content-center">
        <img src="img/mikifont.jpeg" width="50%">
      </div>
      <div class="col-sm-12">
        <form method="POST">
          <div class="row">
            <?php
              $sql = "SELECT * FROM poproduk
                      INNER JOIN pokategori INNER JOIN podetail
                      ON poproduk.idpoproduk = pokategori.idpoproduk
                      AND pokategori.idpo = podetail.idpo 
                      WHERE poproduk.idpoproduk = '$idpoproduk'  
                      AND podetail.variant LIKE '%Polos%'
                      ORDER BY podetail.idpodetail DESC
                    ";
              $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
            ?>
              <div class="col-sm-6">
                <div class="form-group">
                  <label><?= $row['variant']; ?></label>
                  <input type="number" name="qty[]" class="form-control" value="0" required>
                  <input type="hidden" name="idpodetail[]" value="<?= $row['idpodetail']; ?>">
                  <input type="hidden" name="idpo[]" value="<?= $row['idpo']; ?>">
                  <input type="hidden" name="harga[]" value="<?= $row['harga']; ?>">
                </div>
              </div>
            <?php } ?>
          </div>
          <button type="submit" name="save" class="btn btn-primary btn-sm">Kirim</button>
        </form>
      </div>

    </div>
  </div>
  <?php
    if (isset($_POST["save"])) {
      include "koneksi.php";

      if (!$koneksi) {
        die("Koneksi Gagal : " . mysqli_connect_error());
      }
      
      $idadmin = $_GET["idadmin"];
      
      date_default_timezone_set('Asia/Jakarta');
      $today = date('s');
      $waktu = date('H:i:s');
      $idpodetail = $_POST["idpodetail"];
      $harga = $_POST["harga"];
      $idpo = $_POST["idpo"];
      $qty = $_POST["qty"];
      
      // $quantities = count($_POST["qty"]);
      $invoice =  'MHP' . $idadmin . '-' . $idpoproduk;
      $countQty = count($qty);
      $queries = array();

      for ($i = 0; $i < $countQty; $i++) {
        $idpodetail_item = $idpodetail[$i];
        $idpo_item = $idpo[$i];
        $qty_item = $qty[$i];
        $harga_item = $harga[$i];

        $total = $qty_item * $harga_item;
        
        $queries[] = "INSERT INTO pomitra (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, custom, font, total, invoice, status, tgl, waktu) VALUES (NULL, '$idadmin', '$idpoproduk', '$idpo_item', '$idpodetail_item', '$qty_item', NULL, NULL, '$total', '$invoice', 'Belum DP', NOW(), '$waktu')";
      }

      if (!empty($queries)) {
        $queries_string = implode("; ", $queries);
        if (mysqli_multi_query($koneksi, $queries_string)) {
          echo "<script>location='datapom.php?id=$idpoproduk&invoice=$invoice&idadmin=$idadmin'; </script>";
        } else {
            echo "Gagal!";
        }
      } else {
        echo "Data tidak terkirim!";
      }
    }
  ?>
  
  <!-- Script JS -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>