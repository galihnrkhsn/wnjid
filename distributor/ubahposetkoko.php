<?php
  session_start();
  include 'koneksi.php';
  if(!isset($_SESSION["admin_mitra"])){
    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login2.php';</script>";
    header('location:login2.php');
    exit();
  }

  $idpoproduk = $_GET['id'];
  $invoice = $_GET['invoice'];
  $idadmin = $_SESSION["admin_mitra"]["idadmin"];
  $query = "SELECT COUNT(*) AS jumlah,
            poproduk.idpoproduk,
            poproduk.namapo,
            poproduk.status
            FROM poproduk
            INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
            WHERE poproduk.idpoproduk = '$idpoproduk'
            AND pomitra.idmitra = '$idadmin'
            AND pomitra.invoice = '$invoice'
          ";
  $sql = mysqli_query($koneksi, $query);
  $data = mysqli_fetch_array($sql);
  $id = $data['idpoproduk'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WNJ | Mitra <?= $_SESSION['admin_mitra']['namamitra']; ?></title>
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

  <div class="row fixed-top navbaru py-3">
    <div class="col-2">
      <a href="datapom3.php?id=<?= $idpodetail ?>&invoice=<?= $invoice ?>">
        <i class="bi bi-chevron-left"></i>
      </a>
    </div>
    <div class="col-8">
      <h4>PRE ORDER</h4>
    </div>
    <div class="col-2"></div>
  </div>

  <div class="container" style="margin-top: 5rem; margin-bottom: 5rem; font-size: .85rem">
    <div class="row">
      <div class="col-sm-12 text-center my-2">
        <h5 class="text-uppercase">Formulir Ubah pesanan</h5>
        <p class="mb-1 fw-semibold text-secondary">Invoice #<?= $invoice ?></p>
      </div>
      <hr />
      <form method="POST">
        <div class="row">
          <div class="col-sm-12">
            <h6 class="text-uppercase">Broken White</h6>
          </div>
          <?php
            $no = 1;
            $sql = mysqli_query($koneksi, "SELECT * FROM poproduk
                                INNER JOIN pomitra
                                INNER JOIN pokategori
                                INNER JOIN podetail
                                ON poproduk.idpoproduk = pomitra.idpoproduk
                                AND pokategori.idpo = pomitra.idpo
                                AND podetail.idpodetail = pomitra.idpodetail
                                WHERE pomitra.invoice = '$invoice'
                                AND pomitra.idmitra = '$idadmin'
                                AND pokategori.idpo = '2713'
                                AND pomitra.jumlah > 0
                                ORDER BY podetail.idpodetail ASC
                              ");
            while($data = mysqli_fetch_array($sql)) {
          ?>
            <?php if ($data['variant'] !== "Sarung Etnic Broken White") : ?>
              <div class="col-sm-6">
                <p class="text-secondary fw-semibold mb-1">Set <?= $no++ ?></p>
                <div class="row">
                  <div class="col-sm-10">
                    <input type="hidden" name="idpomitra[]" class="form-control form-control-sm" value="<?= $data['idpomitra'] ?>">
                    <input type="text" class="form-control form-control-sm" value="<?= $data['variant'] ?>" disabled>
                    <input type="hidden" name="idpodetail[]" class="form-control form-control-sm" value="<?= $data['idpodetail'] ?>">
                    <input type="hidden" name="idpo[]" class="form-control form-control-sm" value="<?= $data['idpo'] ?>">
                  </div>
                  <div class="col-sm-2 mt-2 mt-sm-0">
                    <input type="number" name="qty[]" class="form-control form-control-sm" value="<?= $data['jumlah'] ?>">
                  </div>
                </div>
              </div>
            <?php else : ?>
              <div class="col-sm-12">
                <input type="hidden" name="idpomitra[]" class="form-control form-control-sm" value="<?= $data['idpomitra'] ?>">
                <input type="hidden" name="variant_sarung[]" class="form-control form-control-sm" value="<?= $data['idpodetail'] ?>">
                <input type="hidden" name="idpodetail[]" class="form-control form-control-sm" value="<?= $data['idpodetail'] ?>">
                <input type="hidden" name="idpo_sarung[]" class="form-control form-control-sm" value="<?= $data['idpo'] ?>">
                <input type="hidden" name="qty[]" class="form-control form-control-sm" value="<?= $data['jumlah'] ?>">
              </div>
            <?php endif; ?>
          <?php } ?>

          <hr class="mt-2 mb-1" />

          <div class="col-sm-12">
            <h6 class="text-uppercase">Royal Black</h6>
          </div>
          <?php
            $no = 1;
            $sql = mysqli_query($koneksi, "SELECT * FROM poproduk
                                INNER JOIN pomitra
                                INNER JOIN pokategori
                                INNER JOIN podetail
                                ON poproduk.idpoproduk = pomitra.idpoproduk
                                AND pokategori.idpo = pomitra.idpo
                                AND podetail.idpodetail = pomitra.idpodetail
                                WHERE pomitra.invoice = '$invoice'
                                AND pomitra.idmitra = '$idadmin'
                                AND pokategori.idpo = '2714'
                                AND pomitra.jumlah > 0
                                ORDER BY podetail.idpodetail ASC
                              ");
            while($data = mysqli_fetch_array($sql)) {
          ?>
            <?php if ($data['variant'] !== "Sarung Etnic Royal Black") : ?>
              <div class="col-sm-6">
                <p class="text-secondary fw-semibold mb-1">Set <?= $no++ ?></p>
                <div class="row">
                  <div class="col-sm-10">
                    <input type="hidden" name="idpomitra[]" class="form-control form-control-sm" value="<?= $data['idpomitra'] ?>">
                    <input type="text" class="form-control form-control-sm" value="<?= $data['variant'] ?>" disabled>
                    <input type="hidden" name="idpodetail[]" class="form-control form-control-sm" value="<?= $data['idpodetail'] ?>">
                    <input type="hidden" name="idpo[]" class="form-control form-control-sm" value="<?= $data['idpo'] ?>">
                  </div>
                  <div class="col-sm-2 mt-2 mt-sm-0">
                    <input type="number" name="qty[]" class="form-control form-control-sm" value="<?= $data['jumlah'] ?>">
                  </div>
                </div>
              </div>
            <?php else : ?>
              <div class="col-sm-12">
                <input type="hidden" name="idpomitra[]" class="form-control form-control-sm" value="<?= $data['idpomitra'] ?>">
                <input type="hidden" name="variant_sarung[]" class="form-control form-control-sm" value="<?= $data['idpodetail'] ?>">
                <input type="hidden" name="idpodetail[]" class="form-control form-control-sm" value="<?= $data['idpodetail'] ?>">
                <input type="hidden" name="idpo_sarung[]" class="form-control form-control-sm" value="<?= $data['idpo'] ?>">
                <input type="hidden" name="qty[]" class="form-control form-control-sm" value="<?= $data['jumlah'] ?>">
              </div>
            <?php endif; ?>
          <?php } ?>

          <hr class="mt-2 mb-1" />

          <div class="col-sm-12">
            <h6 class="text-uppercase">Soft Lavender & Soft Choco</h6>
          </div>
          <?php
            $no = 1;
            $sql = mysqli_query($koneksi, "SELECT * FROM poproduk
                                INNER JOIN pomitra
                                INNER JOIN pokategori
                                INNER JOIN podetail
                                ON poproduk.idpoproduk = pomitra.idpoproduk
                                AND pokategori.idpo = pomitra.idpo
                                AND podetail.idpodetail = pomitra.idpodetail
                                WHERE pomitra.invoice = '$invoice'
                                AND pomitra.idmitra = '$idadmin'
                                AND pokategori.idpo = '2715'
                                AND pomitra.jumlah > 0
                                ORDER BY podetail.idpodetail ASC
                              ");
            while($data = mysqli_fetch_array($sql)) {
          ?>
            <?php if ($data['variant'] !== "Sarung Etnic Soft Lavender") : ?>
              <div class="col-sm-6">
                <p class="text-secondary fw-semibold mb-1">Set <?= $no++ ?></p>
                <div class="row">
                  <div class="col-sm-10">
                    <input type="hidden" name="idpomitra[]" class="form-control form-control-sm" value="<?= $data['idpomitra'] ?>">
                    <input type="text" class="form-control form-control-sm" value="<?= $data['variant'] ?>" disabled>
                    <input type="hidden" name="idpodetail[]" class="form-control form-control-sm" value="<?= $data['idpodetail'] ?>">
                    <input type="hidden" name="idpo[]" class="form-control form-control-sm" value="<?= $data['idpo'] ?>">
                  </div>
                  <div class="col-sm-2 mt-2 mt-sm-0">
                    <input type="number" name="qty[]" class="form-control form-control-sm" value="<?= $data['jumlah'] ?>">
                  </div>
                </div>
              </div>
            <?php else : ?>
              <div class="col-sm-12">
                <input type="hidden" name="idpomitra[]" class="form-control form-control-sm" value="<?= $data['idpomitra'] ?>">
                <input type="hidden" name="variant_sarung[]" class="form-control form-control-sm" value="<?= $data['idpodetail'] ?>">
                <input type="hidden" name="idpodetail[]" class="form-control form-control-sm" value="<?= $data['idpodetail'] ?>">
                <input type="hidden" name="idpo_sarung[]" class="form-control form-control-sm" value="<?= $data['idpo'] ?>">
                <input type="hidden" name="qty[]" class="form-control form-control-sm" value="<?= $data['jumlah'] ?>">
              </div>
            <?php endif; ?>
          <?php } ?>

          <hr class="mt-2 mb-1" />

          <div class="col-sm-12">
            <h6 class="text-uppercase">Soft Tosca</h6>
          </div>
          <?php
            $no = 1;
            $sql = mysqli_query($koneksi, "SELECT * FROM poproduk
                                INNER JOIN pomitra
                                INNER JOIN pokategori
                                INNER JOIN podetail
                                ON poproduk.idpoproduk = pomitra.idpoproduk
                                AND pokategori.idpo = pomitra.idpo
                                AND podetail.idpodetail = pomitra.idpodetail
                                WHERE pomitra.invoice = '$invoice'
                                AND pomitra.idmitra = '$idadmin'
                                AND pokategori.idpo = '2716'
                                AND pomitra.jumlah > 0
                                ORDER BY podetail.idpodetail ASC
                              ");
            while($data = mysqli_fetch_array($sql)) {
          ?>
            <?php if ($data['variant'] !== "Sarung Etnic Soft Tosca") : ?>
              <div class="col-sm-6">
                <p class="text-secondary fw-semibold mb-1">Set <?= $no++ ?></p>
                <div class="row">
                  <div class="col-sm-10">
                    <input type="hidden" name="idpomitra[]" class="form-control form-control-sm" value="<?= $data['idpomitra'] ?>">
                    <input type="text" class="form-control form-control-sm" value="<?= $data['variant'] ?>" disabled>
                    <input type="hidden" name="idpodetail[]" class="form-control form-control-sm" value="<?= $data['idpodetail'] ?>">
                    <input type="hidden" name="idpo[]" class="form-control form-control-sm" value="<?= $data['idpo'] ?>">
                  </div>
                  <div class="col-sm-2 mt-2 mt-sm-0">
                    <input type="number" name="qty[]" class="form-control form-control-sm" value="<?= $data['jumlah'] ?>">
                  </div>
                </div>
              </div>
            <?php else : ?>
              <div class="col-sm-12">
                <input type="hidden" name="idpomitra[]" class="form-control form-control-sm" value="<?= $data['idpomitra'] ?>">
                <input type="hidden" name="variant_sarung[]" class="form-control form-control-sm" value="<?= $data['idpodetail'] ?>">
                <input type="hidden" name="idpodetail[]" class="form-control form-control-sm" value="<?= $data['idpodetail'] ?>">
                <input type="hidden" name="idpo_sarung[]" class="form-control form-control-sm" value="<?= $data['idpo'] ?>">
                <input type="hidden" name="qty[]" class="form-control form-control-sm" value="<?= $data['jumlah'] ?>">
              </div>
            <?php endif; ?>
          <?php } ?>
        </div>

        <hr class="mt-2 mb-2" />
        
        <div class="col-sm-12">
          <button class="btn btn-success" name="save">Ubah</button>
          <button class="btn btn-primary" name="simpan">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <?php
    if(isset($_POST["save"])){
      date_default_timezone_set('Asia/Jakarta');
      $today = date("s");
      $waktu = date("H:i:s");
      $idpodetail = $_POST["idpodetail"];
      $idpo = $_POST["idpo"];
      $idpomitra = $_POST["idpomitra"];
      $idpodetail_sarung = $_POST["idpodetail_sarung"];
      $jmlh = $_POST["qty"];
      $jumlah_dipilih = count($idpomitra);
      $invoice = $_GET['invoice'];
      $sarung = $_POST['variant_sarung'];

      $total_sarung = 0;

      for($x = 0; $x < $jumlah_dipilih; $x++) {
        $idpodetail_item = $idpodetail[$x];
        $idpo_item = $idpo[$x];
        $idpomitra_item = $idpomitra[$x];
        $sarung_item = $idpodetail_sarung[$x];
        $jmlh_item = $jmlh[$x];
        
        $sql = "SELECT * FROM podetail WHERE idpodetail = '$idpodetail_item'";
        $query = $koneksi->query($sql);
        $detail = $query->fetch_assoc();
        $harga = $detail["harga"];
        $variant = $detail['variant'];

        $total = $jmlh_item * $harga;
        
        $ambil_koko = $koneksi->query("SELECT * FROM pomitra WHERE invoice = '$invoice' AND idpodetail = '$idpodetail_item'");
        $datacocok_koko = $ambil_koko->num_rows;
        $sql_update_koko = $koneksi->query("UPDATE pomitra SET jumlah = '$jmlh_item' WHERE invoice = '$invoice' AND idpodetail = '$idpodetail_item'");
      }
      echo "<script>alert('Data Berhasil Di Update!');</script>";
      echo "<script>location='ubahposetkoko.php?id=$idpoproduk&invoice=$invoice';</script>";
    }

    if(isset($_POST["simpan"])) {
      date_default_timezone_set('Asia/Jakarta');
      $today = date("s");
      $waktu = date("H:i:s");
      $idpodetail = $_POST["idpodetail"];
      $idpo = $_POST["idpo_sarung"];
      $idpomitra = $_POST["idpomitra"];
      $idpodetail_sarung = $_POST["idpodetail_sarung"];
      $jmlh = $_POST["qty"];
      $sarung = $_POST['variant_sarung'];
      $invoice = $_GET['invoice'];
      
      $jumlah_dipilih = count($sarung);
      $total_sarung = 0;

      for($x = 0; $x < $jumlah_dipilih; $x++) {
        $idpodetail_item = $idpodetail[$x];
        $idpo_item = $idpo[$x];
        $idpomitra_item = $idpomitra[$x];
        $sarung_item = $idpodetail_sarung[$x];
        $jmlh_item = $jmlh[$x];
        $idsarung = $sarung[$x];
        $total = $jmlh_item * $harga;
        
        $ambil_koko = $koneksi->query("SELECT *, SUM(jumlah) AS total_koko FROM pomitra WHERE idpo = '$idpo_item' AND idpodetail != '$idsarung' AND invoice = '$invoice'");
        $datacocok_koko = $ambil_koko->num_rows;
        $jumlah_koko = $ambil_koko->fetch_assoc();
        $total_koko = $jumlah_koko['total_koko'];
        $sql_update_koko = $koneksi->query("UPDATE pomitra SET jumlah = '$total_koko' WHERE invoice = '$invoice' AND idpodetail = '$idsarung'");
      }
      
      echo "<script>location='datapom3.php?id=$idpoproduk&invoice=$invoice';</script>";
    }
  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
