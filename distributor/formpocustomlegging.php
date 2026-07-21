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
  $idadmin = $_SESSION["admin_mitra"]["idadmin"];
  $invoice = 'D'.$idpoproduk.'-'.$idadmin;
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
      <a href="listnewpo.php">
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
      <div class="col-sm-12 text-center">
        <?php
          $sql = "SELECT * FROM poproduk WHERE idpoproduk = '$idpoproduk'";
          $query = mysqli_query($koneksi, $query);
          $data = mysqli_fetch_array($query);
        ?>
        <h4 class="text-uppercase">formulir pemesanan <?= $data['namapo'] ?></h4>
      </div>

      <form method="POST">
        <div class="col-sm-12">
          <div class="card card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Legging Rok Span</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Bergo Goura</button>
              </li>
              <!--<li class="nav-item" role="presentation">-->
              <!--  <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Manset</button>-->
              <!--</li>-->
            </ul>

            <div class="tab-content mt-3" id="myTabContent">
              <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <div class="row">
                  <?php
                    $sql = "SELECT * FROM poproduk
                            INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                            INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                            WHERE poproduk.idpoproduk = '$idpoproduk'
                            AND podetail.variant LIKE '%Legging Rok Span%'
                            ORDER BY podetail.idpodetail ASC
                          ";
                    $query = $koneksi->query($sql);
                    while($row = $query->fetch_assoc()) {
                  ?>
                    <div class="col-sm-6">
                      <div class="mb-3">
                        <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                        <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                        <input type="number" min="0" value="0" name="jmlh[]" class="form-control form-control-sm" id="<?= $row['variant'] ?>" required>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>

              <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <div class="row">
                  <?php
                    $sql = "SELECT * FROM poproduk
                            INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                            INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                            WHERE poproduk.idpoproduk = '$idpoproduk'
                            AND podetail.variant LIKE '%Bergo Goura%'
                            ORDER BY podetail.idpodetail ASC
                          ";
                    $query = $koneksi->query($sql);
                    while($row = $query->fetch_assoc()) {
                  ?>
                    <div class="col-sm-6">
                      <div class="mb-3">
                        <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                        <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                        <input type="number" name="jmlh[]" min="0" value="0" class="form-control form-control-sm" id="<?= $row['variant'] ?>" required>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>

              <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                <div class="row">
                  <?php
                    $sql = "SELECT * FROM poproduk
                            INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                            INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                            WHERE poproduk.idpoproduk = '$idpoproduk'
                            AND podetail.variant LIKE '%Manset%'
                            ORDER BY podetail.idpodetail ASC
                          ";
                    $query = $koneksi->query($sql);
                    while($row = $query->fetch_assoc()) {
                  ?>
                    <div class="col-sm-6">
                      <div class="mb-3">
                        <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                        <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                        <input type="number" name="jmlh[]" min="0" value="0" class="form-control form-control-sm" id="<?= $row['variant'] ?>">
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>

            <?php if($data['jumlah'] < 1) : ?>
              <div class="col-sm-12">
                <button type="submit" class="btn btn-primary btn-sm" name="save">Kirim</button>
              </div>
            <?php else : ?>
              <div class="col-sm-12">
                <button type="submit" class="btn btn-primary btn-sm" name="save" disabled>Kirim</button>
                <a href="datapom.php?idmitra=<?= $idadmin; ?>&id=<?= $id; ?>&invoice=<?= $invoice; ?>" class="btn btn-sm btn-success">Invoice</a>
              </div>
            <?php endif; ?>
          </div>
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
      $jmlh = $_POST["jmlh"];
      $jumlah_dipilih = count($jmlh);

      for($x=0; $x<$jumlah_dipilih; $x++){
        $sql = "SELECT * FROM podetail WHERE idpodetail = '$idpodetail[$x]'";
        $query = $koneksi->query($sql);
        $detail = $query->fetch_assoc();
        $harga = $detail['harga'];
        $idpo = $detail['idpo'];
        $total = $jmlh[$x]*$harga;
        $koneksi->query("INSERT INTO pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) VALUES
        (NULL,'$idadmin','$idpoproduk','$idpo','$idpodetail[$x]','$jmlh[$x]','$total','$invoice','Belum DP',NOW(),'$waktu')");

        echo "<script>alert('data berhasil dikirim');</script>";
        echo "<script>location='datapom2.php?id=$idpoproduk&invoice=$invoice';</script>";
      }
    }
  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
