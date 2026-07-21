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
          $sql = "SELECT * FROM poproduk WHERE idpoproduk = '$id'";
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
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Dress Matari Couple</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Koko Matari Couple</button>
              </li>
              <li class="nav-item" role="presentation">
               <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Pashmina Matari Couple</button>
              </li>
              <li class="nav-item" role="presentation">
               <button class="nav-link" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail-tab-pane" type="button" role="tab" aria-controls="detail-tab-pane" aria-selected="false">Khimar Matari Couple</button>
              </li>
            </ul>

            <div class="tab-content mt-3" id="myTabContent">
              <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <div class="row">
                  <?php
                    $sql = "SELECT * FROM poproduk
                            INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                            INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                            WHERE poproduk.idpoproduk = '$idpoproduk'
                            AND podetail.variant LIKE '%Dress Matari Couple%'
                            ORDER BY podetail.idpodetail ASC
                          ";
                    $query = $koneksi->query($sql);
                    while($row = $query->fetch_assoc()) {
                      $sqlBlack = "SELECT SUM(pomitra.jumlah) as total_pcs,
                                    pomitra.idpodetail, podetail.variant
                                    FROM pomitra INNER JOIN podetail
                                    ON podetail.idpodetail = pomitra.idpodetail
                                    WHERE pomitra.idpoproduk = '$idpoproduk'
                                    AND podetail.idpodetail BETWEEN '12213' AND '12219'
                                  ";
                      $queryBlack = mysqli_query($koneksi, $sqlBlack);
                      $dataBlack = mysqli_fetch_array($queryBlack);
                      $A = 297;
                      $totalBlack = $A - $dataBlack['total_pcs'];

                      $sqlBeige = "SELECT SUM(pomitra.jumlah) as total_pcs,
                                    pomitra.idpodetail, podetail.variant
                                    FROM pomitra INNER JOIN podetail
                                    ON podetail.idpodetail = pomitra.idpodetail
                                    WHERE pomitra.idpoproduk = '$idpoproduk'
                                    AND podetail.idpodetail BETWEEN '12206' AND '12212'
                                  ";
                      $queryBeige = mysqli_query($koneksi, $sqlBeige);
                      $dataBeige = mysqli_fetch_array($queryBeige);
                      $B = 297;
                      $totalBeige = $B - $dataBeige['total_pcs'];

                      $sqlEgplant = "SELECT SUM(pomitra.jumlah) as total_pcs,
                                    pomitra.idpodetail, podetail.variant
                                    FROM pomitra INNER JOIN podetail
                                    ON podetail.idpodetail = pomitra.idpodetail
                                    WHERE pomitra.idpoproduk = '$idpoproduk'
                                    AND podetail.idpodetail BETWEEN '12220' AND '12226'
                                  ";
                      $queryEgplant = mysqli_query($koneksi, $sqlEgplant);
                      $dataEgplant = mysqli_fetch_array($queryEgplant);
                      $C = 44;
                      $totalEgplant = $C - $dataEgplant['total_pcs'];
                      
                      $sqlMaroon = "SELECT SUM(pomitra.jumlah) as total_pcs,
                                    pomitra.idpodetail, podetail.variant
                                    FROM pomitra INNER JOIN podetail
                                    ON podetail.idpodetail = pomitra.idpodetail
                                    WHERE pomitra.idpoproduk = '$idpoproduk'
                                    AND podetail.idpodetail BETWEEN '12227' AND '12233'
                                  ";
                      $queryMaroon = mysqli_query($koneksi, $sqlMaroon);
                      $dataMaroon = mysqli_fetch_array($queryMaroon);
                      $C = 44;
                      $totalMaroon = $C - $dataMaroon['total_pcs'];

                      $sqlToscaB = "SELECT SUM(pomitra.jumlah) as total_pcs,
                                    pomitra.idpodetail, podetail.variant
                                    FROM pomitra INNER JOIN podetail
                                    ON podetail.idpodetail = pomitra.idpodetail
                                    WHERE pomitra.idpoproduk = '$idpoproduk'
                                    AND podetail.idpodetail BETWEEN '12234' AND '12240'
                                  ";
                      $queryToscaB = mysqli_query($koneksi, $sqlToscaB);
                      $dataToscaB = mysqli_fetch_array($queryToscaB);
                      $D = 265;
                      $totalToscaB = $D - $dataToscaB['total_pcs'];

                      $sqlTosca = "SELECT SUM(pomitra.jumlah) as total_pcs,
                                    pomitra.idpodetail, podetail.variant
                                    FROM pomitra INNER JOIN podetail
                                    ON podetail.idpodetail = pomitra.idpodetail
                                    WHERE pomitra.idpoproduk = '$idpoproduk'
                                    AND podetail.idpodetail BETWEEN '12241' AND '12247'
                                  ";
                      $queryTosca = mysqli_query($koneksi, $sqlTosca);
                      $dataTosca = mysqli_fetch_array($queryTosca);
                      $E = 265;
                      $totalTosca = $E - $dataTosca['total_pcs'];
                  ?>
                    <div class="col-sm-6">
                      <div class="mb-3">
                        <?php if ($row['idpodetail'] >= '12206' && $row['idpodetail'] <= '12212') : ?>
                          <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                        <?php elseif ($row['idpodetail'] >= '12213' && $row['idpodetail'] <= '12219') : ?>
                          <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                        <?php elseif ($row['idpodetail'] >= '12220' && $row['idpodetail'] <= '12226') : ?>
                          <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                        <?php elseif ($row['idpodetail'] >= '12227' && $row['idpodetail'] <= '12233') : ?>
                          <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                        <?php elseif ($row['idpodetail'] >= '12234' && $row['idpodetail'] <= '12240') : ?>
                          <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                        <?php elseif ($row['idpodetail'] >= '12241' && $row['idpodetail'] <= '12247') : ?>
                          <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                        <?php endif; ?>
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
                            AND podetail.variant LIKE '%Koko%'
                            ORDER BY podetail.idpodetail ASC
                          ";
                    $query = $koneksi->query($sql);
                    while($row = $query->fetch_assoc()) {
                      $sqlKopanBlack = "SELECT SUM(pomitra.jumlah) as total_pcs,
                                    pomitra.idpodetail, podetail.variant
                                    FROM pomitra INNER JOIN podetail
                                    ON podetail.idpodetail = pomitra.idpodetail
                                    WHERE pomitra.idpoproduk = '$idpoproduk'
                                    AND podetail.idpodetail BETWEEN '12269' AND '12275'
                                  ";
                      $queryKopanBlack = mysqli_query($koneksi, $sqlKopanBlack);
                      $dataKopanBlack = mysqli_fetch_array($queryKopanBlack);
                      $A = 297;
                      $totalKopanBlack = $A - $dataKopanBlack['total_pcs'];
                      
                      $sqlKopenBlack = "SELECT SUM(pomitra.jumlah) as total_pcs,
                                    pomitra.idpodetail, podetail.variant
                                    FROM pomitra INNER JOIN podetail
                                    ON podetail.idpodetail = pomitra.idpodetail
                                    WHERE pomitra.idpoproduk = '$idpoproduk'
                                    AND podetail.idpodetail BETWEEN '12248' AND '12254'
                                  ";
                      $queryKopenBlack = mysqli_query($koneksi, $sqlKopenBlack);
                      $dataKopenBlack = mysqli_fetch_array($queryKopenBlack);
                      $B = 297;
                      $totalKopenBlack = $B - $dataKopenBlack['total_pcs'];

                      $sqlKopanEgplant = "SELECT SUM(pomitra.jumlah) as total_pcs,
                                    pomitra.idpodetail, podetail.variant
                                    FROM pomitra INNER JOIN podetail
                                    ON podetail.idpodetail = pomitra.idpodetail
                                    WHERE pomitra.idpoproduk = '$idpoproduk'
                                    AND podetail.idpodetail BETWEEN '12276' AND '12282'
                                  ";
                      $queryKopanEgplant = mysqli_query($koneksi, $sqlKopanEgplant);
                      $dataKopanEgplant = mysqli_fetch_array($queryKopanEgplant);
                      $C = 45;
                      $totalKopanEgplant = $C - $dataKopanEgplant['total_pcs'];

                      $sqlKopenEgplant = "SELECT SUM(pomitra.jumlah) as total_pcs,
                                    pomitra.idpodetail, podetail.variant
                                    FROM pomitra INNER JOIN podetail
                                    ON podetail.idpodetail = pomitra.idpodetail
                                    WHERE pomitra.idpoproduk = '$idpoproduk'
                                    AND podetail.idpodetail BETWEEN '12255' AND '12261'
                                  ";
                      $queryKopenEgplant = mysqli_query($koneksi, $sqlKopenEgplant);
                      $dataKopenEgplant = mysqli_fetch_array($queryKopenEgplant);
                      $D = 44;
                      $totalKopenEgplant = $D - $dataKopenEgplant['total_pcs'];

                      $sqlKopanTosca = "SELECT SUM(pomitra.jumlah) as total_pcs,
                                    pomitra.idpodetail, podetail.variant
                                    FROM pomitra INNER JOIN podetail
                                    ON podetail.idpodetail = pomitra.idpodetail
                                    WHERE pomitra.idpoproduk = '$idpoproduk'
                                    AND podetail.idpodetail BETWEEN '12283' AND '12289'
                                  ";
                      $queryKopanTosca = mysqli_query($koneksi, $sqlKopanTosca);
                      $dataKopanTosca = mysqli_fetch_array($queryKopanTosca);
                      $E = 265;
                      $totalKopanTosca = $E - $dataKopanTosca['total_pcs'];

                      $sqlKopenTosca = "SELECT SUM(pomitra.jumlah) as total_pcs,
                                    pomitra.idpodetail, podetail.variant
                                    FROM pomitra INNER JOIN podetail
                                    ON podetail.idpodetail = pomitra.idpodetail
                                    WHERE pomitra.idpoproduk = '$idpoproduk'
                                    AND podetail.idpodetail BETWEEN '12262' AND '12268'
                                  ";
                      $queryKopenTosca = mysqli_query($koneksi, $sqlKopenTosca);
                      $dataKopenTosca = mysqli_fetch_array($queryKopenTosca);
                      $F = 265;
                      $totalKopenTosca = $F - $dataKopenTosca['total_pcs'];
                  ?>
                    <div class="col-sm-6">
                      <div class="mb-3">
                        <?php if ($row['idpodetail'] >= '12269' && $row['idpodetail'] <= '12275') : ?>
                          <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                          <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                          <input type="number" name="jmlh[]" min="0" max="<?= $totalKopanBlack ?>" value="0" class="form-control form-control-sm" id="<?= $row['variant'] ?>" required>
                        <?php elseif ($row['idpodetail'] >= '12248' && $row['idpodetail'] <= '12254') : ?>
                          <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                          <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                          <input type="number" name="jmlh[]" min="0" value="0" class="form-control form-control-sm" id="<?= $row['variant'] ?>" required>
                        <?php elseif ($row['idpodetail'] >= '12276' && $row['idpodetail'] <= '12282') : ?>
                          <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                          <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                          <input type="number" name="jmlh[]" min="0" value="0" class="form-control form-control-sm" id="<?= $row['variant'] ?>" required>
                        <?php elseif ($row['idpodetail'] >= '12255' && $row['idpodetail'] <= '12261') : ?>
                          <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                          <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                          <input type="number" name="jmlh[]" min="0" value="0" class="form-control form-control-sm" id="<?= $row['variant'] ?>" required>
                        <?php elseif ($row['idpodetail'] >= '12283' && $row['idpodetail'] <= '12289') : ?>
                          <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                          <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                          <input type="number" name="jmlh[]" min="0" value="0" class="form-control form-control-sm" id="<?= $row['variant'] ?>" required>
                        <?php elseif ($row['idpodetail'] >= '12262' && $row['idpodetail'] <= '12268') : ?>
                          <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                          <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                          <input type="number" name="jmlh[]" min="0" value="0" class="form-control form-control-sm" id="<?= $row['variant'] ?>" required>
                        <?php endif; ?>
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
                            AND podetail.variant LIKE '%Pashmina Matari Couple%'
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
              
              <div class="tab-pane fade" id="detail-tab-pane" role="tabpanel" aria-labelledby="detail-tab" tabindex="0">
                <div class="row">
                  <?php
                    $sql = "SELECT * FROM poproduk
                            INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                            INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                            WHERE poproduk.idpoproduk = '$idpoproduk'
                            AND podetail.variant LIKE '%Khimar%'
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
                <a href="datapom2.php?idmitra=<?= $idadmin; ?>&id=<?= $id; ?>&invoice=<?= $invoice; ?>" class="btn btn-sm btn-success">Invoice</a>
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
        echo "<script>location='datapom3.php?id=$idpoproduk&invoice=$invoice';</script>";
      }
    }
  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
