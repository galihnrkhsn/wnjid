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
  $idadmin = $_SESSION["mitraagen"]["idmitraagen"];
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
  <title>WNJ | Form PO</title>
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
        <h4 class="text-uppercase">formulir pemesanan <?= $data['namapo'] ?></h4>
      </div>

      <form method="POST">
        <div class="col-sm-12">
          <div class="card card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Rok Span Custom Nama</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Bergo Goura Custom Nama</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Rok Span Insial</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="preview-tab" data-bs-toggle="tab" data-bs-target="#preview-tab-pane" type="button" role="tab" aria-controls="preview-tab-pane" aria-selected="false">Bergo Goura Insial</button>
              </li>
            </ul>

            <div class="tab-content mt-3" id="myTabContent">
              <!-- TAB ROK SPAN CUSTOM NAMA -->
              <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <div class="row">
                  <?php
                    $sql = "SELECT * FROM poproduk
                            INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                            INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                            WHERE poproduk.idpoproduk = '$idpoproduk'
                            AND podetail.variant LIKE '%Rok Span%'
                            ORDER BY podetail.idpodetail ASC
                          ";
                    $query = $koneksi->query($sql);
                    while($row = $query->fetch_assoc()) {
                  ?>
                    <div class="col-sm-6 mb-3">
                      <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                      <input type="number" name="provinsi[]" onChange="tampil<?php echo $row['idpodetail']; ?>(this.value)" min="0" value="0" class="form-control form-control-sm" id="<?= $row['variant'] ?>" required>

                      <div id="demo<?php echo $row['idpodetail']; ?>"></div>
                      <hr />
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
                                echo "<input type='hidden' name='idpo[]' value='$row[idpo]'>";
                                echo "<input type='hidden' name='idpodetail[]' value='$row[idpodetail]'>";
                                echo "<input type='hidden' name='harga[]' value='123000'>";
                                echo "<input type='text' class='form-control' name='nama[]' required maxlength='10' placeholder='Tulis Nama Custom'><br>";
                              echo "</div>"; 
                              echo "<div class='col-sm-6'>";
                                echo "<select class='form-control' name='font[]' required>";
                                echo "<option value='' disabled='disabled' selected>~Pilih Jenis Huruf~</option>";            
                                echo "<option value='Junegull'>Junegull</option>";
                                echo "<option value='Poetsen One'>Poetsen One</option>";
                                echo "<option value='Dream MMA'>Dream MMA</option>";
                              echo "</select>";      
                              echo "</div>";
                            echo "</div>"; 
                          ?>";
                        }
                        document.getElementById("demo<?php echo $row['idpodetail']; ?>").innerHTML = text;
                      }
                    </script>
                  <?php } ?>
                </div>
              </div>

              <!-- TAB BERGO GOURA CUSTOM NAMA -->
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
                    <div class="col-sm-6 mb-3">
                      <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                      <input type="number" name="provinsi[]" onChange="tampil<?php echo $row['idpodetail']; ?>(this.value)" min="0" value="0" class="form-control form-control-sm" id="<?= $row['variant'] ?>" required>

                      <div id="demo<?php echo $row['idpodetail']; ?>"></div>
                      <hr />
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
                                echo "<input type='hidden' name='idpo[]' value='$row[idpo]'>";
                                echo "<input type='hidden' name='idpodetail[]' value='$row[idpodetail]'>";
                                echo "<input type='hidden' name='harga[]' value='109000'>";
                                echo "<input type='text' class='form-control' name='nama[]' required maxlength='10' placeholder='Tulis Nama Custom'><br>";
                              echo "</div>"; 
                              echo "<div class='col-sm-6'>";
                                echo "<select class='form-control' name='font[]' required>";
                                echo "<option value='' disabled='disabled' selected>~Pilih Jenis Huruf~</option>";
                                echo "<option value='Junegull'>Junegull</option>";
                                echo "<option value='Poetsen One'>Poetsen One</option>";
                                echo "<option value='Dream MMA'>Dream MMA</option>";
                              echo "</select>";      
                              echo "</div>";
                            echo "</div>"; 
                          ?>";
                        }
                        document.getElementById("demo<?php echo $row['idpodetail']; ?>").innerHTML = text;
                      }
                    </script>
                  <?php } ?>
                </div>
              </div>

              <!-- TAB ROK SPAN INISIAL -->
              <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                <div class="row">
                  <?php
                    $sql = "SELECT * FROM poproduk
                            INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                            INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                            WHERE poproduk.idpoproduk = '$idpoproduk'
                            AND podetail.variant LIKE '%Rok Span%'
                            ORDER BY podetail.idpodetail ASC
                          ";
                    $query = $koneksi->query($sql);
                    while($row = $query->fetch_assoc()) {
                  ?>
                    <div class="col-sm-6 mb-3">
                      <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                      <input type="number" name="provinsi[]" onChange="tampilll<?php echo $row['idpodetail']; ?>(this.value)" min="0" value="0" class="form-control form-control-sm" id="<?= $row['variant'] ?>" required>

                      <div id="demooo<?php echo $row['idpodetail']; ?>"></div>
                      <hr />
                    </div>

                    <script type="text/javascript">
                      function tampilll<?php echo $row['idpodetail']; ?>(provinsi<?php echo $row['idpodetail']; ?>)
                      {
                        var text = "";
                        var i;
                      
                        for (i = 0; i < provinsi<?php echo $row['idpodetail']; ?>; i++) {
                          text += "No. "+ (i+1) +"<?php
                            echo "<div class='form-group row'>";
                              echo "<div class='col-sm-12'>";
                                echo "<input type='hidden' name='idpo[]' value='$row[idpo]'>";
                                echo "<input type='hidden' name='idpodetail[]' value='$row[idpodetail]'>";
                                echo "<input type='hidden' name='harga[]' value='118000'>";
                                echo "<input type='hidden' name='font[]' value='-'>";
                                echo "<input type='text' class='form-control' name='nama[]' required maxlength='1' placeholder='Tulis Inisial'><br>";
                              echo "</div>";
                            echo "</div>"; 
                          ?>";
                        }
                        document.getElementById("demooo<?php echo $row['idpodetail']; ?>").innerHTML = text;
                      }
                    </script>
                  <?php } ?>
                </div>
              </div>

              <!-- TAB BERGO GOURA INISIAL -->
              <div class="tab-pane fade" id="preview-tab-pane" role="tabpanel" aria-labelledby="preview-tab" tabindex="0">
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
                    <div class="col-sm-6 mb-3">
                      <label for="<?= $row['variant'] ?>" class="form-label"><?= $row['variant'] ?></label>
                      <input type="number" name="provinsi[]" onChange="tampillll<?php echo $row['idpodetail']; ?>(this.value)" min="0" value="0" class="form-control form-control-sm" id="<?= $row['variant'] ?>" required>

                      <div id="demoooo<?php echo $row['idpodetail']; ?>"></div>
                      <hr />
                    </div>

                    <script type="text/javascript">
                      function tampillll<?php echo $row['idpodetail']; ?>(provinsi<?php echo $row['idpodetail']; ?>)
                      {
                        var text = "";
                        var i;
                      
                        for (i = 0; i < provinsi<?php echo $row['idpodetail']; ?>; i++) {
                          text += "No. "+ (i+1) +"<?php
                            echo "<div class='form-group row'>";
                              echo "<div class='col-sm-12'>";
                                echo "<input type='hidden' name='idpo[]' value='$row[idpo]'>";
                                echo "<input type='hidden' name='idpodetail[]' value='$row[idpodetail]'>";
                                echo "<input type='hidden' name='harga[]' value='104000'>";
                                echo "<input type='hidden' name='font[]' value='-'>";
                                echo "<input type='text' class='form-control' name='nama[]' required maxlength='1' placeholder='Tulis Inisial'><br>";
                              echo "</div>";
                            echo "</div>"; 
                          ?>";
                        }
                        document.getElementById("demoooo<?php echo $row['idpodetail']; ?>").innerHTML = text;
                      }
                    </script>
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
      $idpo = $_POST["idpo"];
      $idpodetail = $_POST["idpodetail"];
      $jmlh = $_POST["provinsi"];
      $custom = $_POST["nama"];
      $harga = $_POST["harga"];
      $font = $_POST["font"];

      $invoice = 'ALC' . $idadmin . '-' . $idpoproduk;
      $queries = array();
      $countQty = count($custom);

      for ($i = 0; $i < $countQty; $i++) {
        $idpo_item = $idpo[$i];
        $idpodetail_item = $idpodetail[$i];
        $jmlh_item = $jmlh[$i];
        $custom_item = $custom[$i];
        $harga_item = $harga[$i];
        $font_item = $font[$i];

        if (!empty($idpodetail_item) && $jmlh_item >= 0 && $custom_item !== null && $font_item !== null) {
          $queries[] = "INSERT INTO pomitra (idpomitra, idmitraagen, idpoproduk, idpo, idpodetail, jumlah, custom, font, total, invoice, status, tgl, waktu) VALUES
                        (NULL, '$idadmin', '$idpoproduk', '$idpo_item', '$idpodetail_item', '1', '$custom_item', '$font_item', '$harga_item', '$invoice', 'Belum DP', NOW(), '$waktu')
                      ";
        }
      }
      if (!empty($queries)) {
        $queries_string = implode("; ", $queries);
        if (mysqli_multi_query($koneksi, $queries_string)) {
          echo "<script>location='datapom2.php?id=$idpoproduk&invoice=$invoice&idadmin=$idadmin'; </script>";
        } else {
          echo "Gagal!";
        }
      } else {
        echo "Data tidak terkirim!";
      }
    }
  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
