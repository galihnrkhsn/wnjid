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
      
      <div class="col-sm-6">
        <img src="img/mikifont.jpeg" width="100%">
      </div>

      <div class="col-sm-6">
        <p class="text-uppercase fw-bold">Contoh Font Teks : </p>
        <span>
          Warna Font Teks ada 2 Black & Gold. <br>
          Warna Font Teks Black : <br>
          Untuk Miki Hat<br>
          - Silver<br>
          - Grey<br>
          <br>
          Warna Font Teks Gold :<br>
          Untuk Miki Hat<br>
          - Black<br>
          - Maroon<br>
          - Navy<br>
          <br />
          <p class="fw-medium"><span class="text-danger">*</span> Maksimal 15 Karekter</p>
        </span>
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
                      AND podetail.variant LIKE '%Custom%'
                      ORDER BY podetail.idpodetail DESC
                    ";
              $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
            ?>
              <div class="col-sm-6">
                <div class="form-group">
                  <label><?= $row['variant']; ?></label>
                  <input type="number" name="provinsi[]" class="form-control" onChange="tampil<?php echo $row['idpodetail']; ?>(this.value)" value="0">
                </div>
                <div id="demo<?php echo $row['idpodetail']; ?>"></div>
              </div>

              <script type="text/javascript">
                function tampil<?php echo $row['idpodetail']; ?>(provinsi<?php echo $row['idpodetail']; ?>)
                  {
                    var text = "";
                    var i;
                    for (i = 0; i < provinsi<?php echo $row['idpodetail']; ?>; i++) {
                    text += "No. "+ (i+1) +"<?php
                      echo "<div class='form-group row'>";
                      echo "<div class='col-sm-6'>";
                      echo "<input type='text' name='idpodetail[]' value='$row[idpodetail]'>";
                      echo "<input type='text' name='idpo[]' value='$row[idpo]'>";
                      echo "<input type='text' name='harga[]' value='$row[harga]'>";
                      echo "<input type='text' class='form-control' name='nama[]' required maxlength='15' placeholder='Tulis Nama Custom'><br>";
                      echo "</div>"; 
                      echo "<div class='col-sm-6 mb-3 mb-sm-0'>";
                      echo "<select class='form-control' name='font[]' required>";
                      echo "<option value='' disabled='disabled' selected>~Pilih Jenis Huruf~</option>";            
                      echo "<option value='a amazing mother'>A Amazing Mother</option>";
                      echo "<option value='a awal Ramadan'>A Awal Ramadan</option>";
                      echo "<option value='blackjack'>Blackjack</option>";
                      echo "<option value='halaney demo'>Halaney Demo</option>";
                      echo "</select>";
                      echo "</div>";
                      if ($row['namakategori'] == "Cream" or $row['namakategori'] == "Silver" or $row['namakategori'] == "White") {
                        echo "<input type='hidden' name='warna[]' value='Black' readonly>";
                      } else{
                        echo "<input type='hidden' name='warna[]' value='Gold' readonly>";           
                      }
                      echo "</div>";
                    ?>";
                    }
                    document.getElementById("demo<?php echo $row['idpodetail']; ?>").innerHTML = text;
                  }
              </script>
            <?php } ?>
          </div>
          <button type="submit" name="save" class="btn btn-primary btn-sm">Kirim</button>
        </form>
      </div>

    </div>
  </div>
  <?php
    if (isset($_POST["save"])) {
      include 'koneksi.php';

      if (!$koneksi) {
          die("Koneksi Gagal : " . mysqli_connect_error());
      // } else {
      //     die("Koneksi Gagal : " . mysqli_connect_error());
      }
      
      $idadmin = $_GET["idadmin"];
      
      date_default_timezone_set('Asia/Jakarta');
      $today = date('s');
      $waktu = date('H:i:s');
      $idpo = $_POST["idpo"];
      $qty = $_POST["provinsi"];
      $custom = $_POST["nama"];
      $font = $_POST["font"];
      $harga = $_POST["harga"];
      $idpodetail = $_POST["idpodetail"];
      
      // var_dump($today, $waktu, $idpo, $qty, $custom, $font, $harga, $idpodetail);
      $invoice =  'MHC' . $idadmin . '-' . $idpoproduk;
      $queries = array();
      $countQty = count($custom);

      for ($i = 0; $i < $countQty; $i++) {
        $idpodetail_item = $idpodetail[$i];
        $idpo_item = $idpo[$i];
        $qty_item = $qty[$i];
        $harga_item = $harga[$i];
        $custom_item = $custom[$i];
        $font_item = $font[$i];
        
        $total = $qty_item * $harga_item;
        $queries[] = "INSERT INTO pomitra (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, custom, font, total, invoice, status, tgl, waktu) VALUES (NULL, '$idadmin', '$idpoproduk', '$idpo_item', '$idpodetail_item', '1', '$custom_item', '$font_item', '$total', '$invoice', 'Belum DP', NOW(), '$waktu')";
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