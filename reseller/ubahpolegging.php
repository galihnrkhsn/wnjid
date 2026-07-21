<?php 
  session_start();
  include 'koneksi.php';
  include 'floatingbutton.php';
  if(!isset($_SESSION["mitraagen"])){
    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login2.php';</script>";
    header('location:login2.php');
    exit();
  }
  $invoice = $_GET['invoice'];
  $idpoproduk = $_GET['id'];
  $idadmin = $_SESSION["mitraagen"]["idmitramarketer"];
  $query = "SELECT COUNT(*) AS jumlah, poproduk.idpoproduk, poproduk.namapo, poproduk.status
            FROM poproduk INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk WHERE pomitra.invoice = '$invoice'
          ";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WNJ | Form Ubah <?= $invoice ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
      .navbaru {
        background: #eee center center;
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
    
    <div class="row fixed-top navbaru py-2">
      <div class="col-2">
        <a href="datapom2.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>&idadmin=<?= $idadmin ?>">
          <i class="bi bi-chevron-left"></i>
        </a>
      </div>
      <div class="col-8"><h4>PRE ORDER</h4></div>
      <div class="col-2"></div>
    </div>

    <div class="container" style="margin: 4rem auto;">
      <div class="row">
        <div class="col-sm-12 text-center">
          <h6 class="text-uppercase">Ubah Formulir <?= $namapo ?> | <?= $invoice ?></h6>
        </div>

        <div class="col-sm-12 card">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-5">
                <p class="fw-semibold">Variant</p>
              </div>
              <div class="col-sm-4">
                <p class="fw-semibold">Custom Name</p>
              </div>
              <div class="col-sm-3">
                <p class="fw-semibold">Jenis Font</p>
              </div>
            </div>

            <form method="POST">
              <?php
                $sql = mysqli_query($koneksi, "SELECT poproduk.namapo, pokategori.namakategori, podetail.variant, pomitra.*
                                                FROM poproduk
                                                INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                                                INNER JOIN pokategori ON pomitra.idpo = pokategori.idpo
                                                INNER JOIN podetail ON pomitra.idpodetail = podetail.idpodetail
                                                WHERE pomitra.idmitramarketer = '$idadmin'
                                                AND pomitra.invoice = '$invoice'
                                                ORDER BY podetail.idpodetail ASC
                                              ");
                while($data = mysqli_fetch_array($sql)) {
              ?>
                <div class="row">
                  <div class="col-sm-5">
                    <div class="mb-3">
                      <input type="hidden" name="idpomitra[]" class="form-control form-control-sm" value="<?= $data['idpomitra'] ?>">
                      <input type="hidden" name="total[]" class="form-control form-control-sm" value="<?= $data['total'] ?>">
                      <input type="text" name="variant[]" class="form-control form-control-sm" value="<?= $data['variant'] ?>" disabled>
                    </div>
                  </div>
                  
                  <div class="col-sm-4">
                    <div class="mb-3">
                      <input type="text" name="custom[]" class="form-control form-control-sm" value="<?= $data['custom'] ?>">
                    </div>
                  </div>

                  <div class="col-sm-3">
                    <select class="form-select form-select-sm" name="font[]" aria-label="Default select example">
                      <option selected><?= $data['font'] ?></option>
                      <option value="Junegull">Junegull</option>
                      <option value="Poetsen One">Poetsen One</option>
                      <option value="Dream MMA">Dream MMA</option>
                    </select>
                  </div>
                </div>
              <?php } ?>
              
              <button type="submit" name="edit" class="btn btn-primary btn-sm">Kirim</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php
      if(isset($_POST["edit"])) {
        include "koneksi.php";
        $idadmin = $_SESSION["mitraagen"]["idmitramarketer"];
        $idpomitra = $_POST["idpomitra"];
        $total = $_POST["total"];
        $invoice = $_GET["invoice"];
        $custom = $_POST["custom"];
        $font = $_POST["font"];

        for ($i = 0; $i < count($idpomitra); $i++) {
          $idpomitra_item = $idpomitra[$i];
          $custom_item = $custom[$i];
          $font_item = $font[$i];

          // var_dump($idpomitra_item, $custom_item, $font_item);

          $koneksi->query("UPDATE pomitra SET custom = '$custom_item', font = '$font_item' WHERE idpomitra = '$idpomitra_item'");
        }

        echo "<script>alert('Data berhasil di update!');</script>";
        echo "<script>location='datapom2.php?id=$idpoproduk&invoice=$invoice'</script>";
      }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
  </body>
</html>