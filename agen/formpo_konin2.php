<?php
    session_start();
    include 'koneksi.php';
    if(!isset($_SESSION["mitraagen"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login2.php';</script>";
        header('location:login2.php');
        exit();
    }
    
    $invoice = $_GET['invoice'];
    $query = "SELECT poproduk.idpoproduk,poproduk.namapo,podropship.invoice 
              FROM poproduk 
              JOIN podropship on podropship.idpoproduk = poproduk.idpoproduk
              WHERE podropship.invoice='$invoice'";
    $sql = mysqli_query($koneksi, $query);  
    $data = mysqli_fetch_array($sql);
    $idpoproduk = $data['idpoproduk'];
    $idadmin=$_SESSION["mitraagen"]["idmitraagen"]; 
    $namapo = $data['namapo'];
  ?>
  <!doctype html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>WNJ | Form <?= $namapo ?></title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    </head>

    <style>
      .navbaru {
        background: #eee  center center;
        margin: auto;
        text-align: center;
        overflow: hidden;
        padding: 0.8rem 1.5rem;
      }

      .navbaru p {
        padding: 12px 0;
        font-size: 20px;
        text-transform: uppercase;
        color: #0f0f0a;
        text-align: center;
      }

      .navbaru i {
        padding: 15px 0;
        font-size: 23px;
        color: #0f0f0a;
        text-align: center; 
      }
    </style>

    <body>
      <div class="row fixed-top d-flex align-items-center navbaru">
        <div class="col-2"></div>
        <div class="col-8"><h5>PRE ORDER</h5></div>
        <div class="col-2"></div>
      </div>

      <div class="container" style="padding-top: 4.5rem">
        <div class="row">
          <div class="col-sm-12 text-center">
            <h4>Formulir Pemesanan <?= $namapo ?><br /><?= $data['invoice'] ?></h4>
          </div>

          <div class="card mb-5">
            <div class="card-body">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Dress Dewasa Gaza Abaya</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Dress Dewasa Masjidil Sakhrah</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Koko Dewasa Pendek</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="koko-tab" data-bs-toggle="tab" data-bs-target="#koko-tab-pane" type="button" role="tab" aria-controls="koko-tab-pane" aria-selected="false">Koko Dewasa Panjang</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="khimar-tab" data-bs-toggle="tab" data-bs-target="#khimar-tab-pane" type="button" role="tab" aria-controls="khimar-tab-pane" aria-selected="false">Khimar Dewasa</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="french-tab" data-bs-toggle="tab" data-bs-target="#french-tab-pane" type="button" role="tab" aria-controls="french-tab-pane" aria-selected="false">French Khimar Dewasa</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="dress-tab" data-bs-toggle="tab" data-bs-target="#dress-tab-pane" type="button" role="tab" aria-controls="dress-tab-pane" aria-selected="false">Dress Konin Anak</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="khimar-anak-tab" data-bs-toggle="tab" data-bs-target="#khimar-anak-tab-pane" type="button" role="tab" aria-controls="khimar-anak-tab-pane" aria-selected="false">Khimar Anak</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="french-anak-tab" data-bs-toggle="tab" data-bs-target="#french-anak-tab-pane" type="button" role="tab" aria-controls="french-anak-tab-pane" aria-selected="false">French Khimar Anak</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="koko-anak-tab" data-bs-toggle="tab" data-bs-target="#koko-anak-tab-pane" type="button" role="tab" aria-controls="koko-anak-tab-pane" aria-selected="false">Koko Lengan Pendek Anak</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="panjang-anak-tab" data-bs-toggle="tab" data-bs-target="#panjang-anak-tab-pane" type="button" role="tab" aria-controls="panjang-anak-tab-pane" aria-selected="false">Koko Lengan Panjang Anak</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="celana-anak-tab" data-bs-toggle="tab" data-bs-target="#celana-anak-tab-pane" type="button" role="tab" aria-controls="celana-anak-tab-pane" aria-selected="false">Celana Anak</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="celana-koko-anak-tab" data-bs-toggle="tab" data-bs-target="#celana-koko-anak-tab-pane" type="button" role="tab" aria-controls="celana-koko-anak-tab-pane" aria-selected="false">Celana + Koko Anak Panjang</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="celana-pendek-anak-tab" data-bs-toggle="tab" data-bs-target="#celana-pendek-anak-tab-pane" type="button" role="tab" aria-controls="celana-pendek-anak-tab-pane" aria-selected="false">Celana + Koko Anak Pendek</button>
                </li>
              </ul>

              <form method="POST">
                <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <div class="row">
                      <?php
                        
                        $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                ON poproduk.idpoproduk = pokategori.idpoproduk
                                AND pokategori.idpo = podetail.idpo
                                WHERE poproduk.idpoproduk = '259'
                                AND podetail.variant LIKE '%Dress Dewasa Gaza Abaya%'
                                ORDER BY podetail.idpodetail ASC
                              ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()) {
                      ?>
                        <div class="col-sm-6 mt-3 mb-1">
                          <label for="<?= $row['variant']; ?>" class="form-label"><?= $row['variant']; ?></label>
                          <input type="number" class="form-control form-control-md" id="<?= $row['variant']; ?>" value="0" min="0" name="jmlh[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpo']; ?>" value="<?= $row['idpo']; ?>" name="idpo[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpodetail']; ?>" value="<?= $row['idpodetail']; ?>" name="idpodetail[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['harga']; ?>" value="<?= $row['harga']; ?>" name="harga[]">
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- End Dress Gaza Abaya -->

                  <!-- START DRESS MASJIDIL SAKHRAH -->
                  <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                    <div class="row">
                      <?php
                        
                        $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                ON poproduk.idpoproduk = pokategori.idpoproduk
                                AND pokategori.idpo = podetail.idpo
                                WHERE poproduk.idpoproduk = '259'
                                AND podetail.variant LIKE '%Dress Dewasa Masjidil Sakhrah%'
                                ORDER BY podetail.idpodetail ASC
                              ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()) {
                      ?>
                        <div class="col-sm-6 mt-3 mb-1">
                          <label for="<?= $row['variant']; ?>" class="form-label"><?= $row['variant']; ?></label>
                          <input type="number" class="form-control form-control-md" id="<?= $row['variant']; ?>" value="0" min="0" name="jmlh[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpo']; ?>" value="<?= $row['idpo']; ?>" name="idpo[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpodetail']; ?>" value="<?= $row['idpodetail']; ?>" name="idpodetail[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['harga']; ?>" value="<?= $row['harga']; ?>" name="harga[]">
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- END DRESS MASJIDIL SAKHRAH -->

                  <!-- START KOKO DEWASA LENGAN PENDEK -->
                  <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                    <div class="row">
                      <?php
                        
                        $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                ON poproduk.idpoproduk = pokategori.idpoproduk
                                AND pokategori.idpo = podetail.idpo
                                WHERE poproduk.idpoproduk = '259'
                                AND podetail.variant LIKE '%Koko Dewasa Lengan Pendek%'
                                ORDER BY podetail.idpodetail ASC
                              ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()) {
                      ?>
                        <div class="col-sm-6 mt-3 mb-1">
                          <label for="<?= $row['variant']; ?>" class="form-label"><?= $row['variant']; ?></label>
                          <input type="number" class="form-control form-control-md" id="<?= $row['variant']; ?>" value="0" min="0" name="jmlh[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpo']; ?>" value="<?= $row['idpo']; ?>" name="idpo[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpodetail']; ?>" value="<?= $row['idpodetail']; ?>" name="idpodetail[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['harga']; ?>" value="<?= $row['harga']; ?>" name="harga[]">
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- END KOKO DEWASA LENGAN PENDEK -->

                  <!-- START KOKO DEWASA LENGAN PANJANG -->
                  <div class="tab-pane fade" id="koko-tab-pane" role="tabpanel" aria-labelledby="koko-tab" tabindex="0">
                    <div class="row">
                      <?php
                        
                        $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                ON poproduk.idpoproduk = pokategori.idpoproduk
                                AND pokategori.idpo = podetail.idpo
                                WHERE poproduk.idpoproduk = '259'
                                AND podetail.variant LIKE '%Koko Dewasa Lengan Panjang%'
                                ORDER BY podetail.idpodetail ASC
                              ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()) {
                      ?>
                        <div class="col-sm-6 mt-3 mb-1">
                          <label for="<?= $row['variant']; ?>" class="form-label"><?= $row['variant']; ?></label>
                          <input type="number" class="form-control form-control-md" id="<?= $row['variant']; ?>" value="0" min="0" name="jmlh[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpo']; ?>" value="<?= $row['idpo']; ?>" name="idpo[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpodetail']; ?>" value="<?= $row['idpodetail']; ?>" name="idpodetail[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['harga']; ?>" value="<?= $row['harga']; ?>" name="harga[]">
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- END KOKO DEWASA LENGAN PANJANG -->

                  <!-- START KHIMAR DEWASA -->
                  <div class="tab-pane fade" id="khimar-tab-pane" role="tabpanel" aria-labelledby="khimar-tab" tabindex="0">
                    <div class="row">
                      <?php
                        
                        $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                ON poproduk.idpoproduk = pokategori.idpoproduk
                                AND pokategori.idpo = podetail.idpo
                                WHERE poproduk.idpoproduk = '259'
                                AND podetail.variant LIKE '%Khimar Dewasa%'
                                AND podetail.variant NOT LIKE '%French Khimar Dewasa%'
                                ORDER BY podetail.idpodetail ASC
                              ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()) {
                      ?>
                        <div class="col-sm-6 mt-3 mb-1">
                          <label for="<?= $row['variant']; ?>" class="form-label"><?= $row['variant']; ?></label>
                          <input type="number" class="form-control form-control-md" id="<?= $row['variant']; ?>" value="0" min="0" name="jmlh[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpo']; ?>" value="<?= $row['idpo']; ?>" name="idpo[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpodetail']; ?>" value="<?= $row['idpodetail']; ?>" name="idpodetail[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['harga']; ?>" value="<?= $row['harga']; ?>" name="harga[]">
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- END KHIMAR DEWASA -->

                  <!-- START FRENCH KHIMAR DEWASA -->
                  <div class="tab-pane fade" id="french-tab-pane" role="tabpanel" aria-labelledby="french-tab" tabindex="0">
                    <div class="row">
                      <?php
                        
                        $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                ON poproduk.idpoproduk = pokategori.idpoproduk
                                AND pokategori.idpo = podetail.idpo
                                WHERE poproduk.idpoproduk = '259'
                                AND podetail.variant LIKE '%French Khimar Dewasa%'
                                ORDER BY podetail.idpodetail ASC
                              ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()) {
                      ?>
                        <div class="col-sm-6 mt-3 mb-1">
                          <label for="<?= $row['variant']; ?>" class="form-label"><?= $row['variant']; ?></label>
                          <input type="number" class="form-control form-control-md" id="<?= $row['variant']; ?>" value="0" min="0" name="jmlh[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpo']; ?>" value="<?= $row['idpo']; ?>" name="idpo[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpodetail']; ?>" value="<?= $row['idpodetail']; ?>" name="idpodetail[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['harga']; ?>" value="<?= $row['harga']; ?>" name="harga[]">
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- END FRENCH KHIMAR DEWASA -->

                  <!-- START DRESS KONIN ANAK -->
                  <div class="tab-pane fade" id="dress-tab-pane" role="tabpanel" aria-labelledby="dress-tab" tabindex="0">
                    <div class="row">
                      <?php
                        
                        $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                ON poproduk.idpoproduk = pokategori.idpoproduk
                                AND pokategori.idpo = podetail.idpo
                                WHERE poproduk.idpoproduk = '259'
                                AND podetail.variant LIKE '%Dress Konin Anak%'
                                ORDER BY podetail.idpodetail ASC
                              ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()) {
                      ?>
                        <div class="col-sm-6 mt-3 mb-1">
                          <label for="<?= $row['variant']; ?>" class="form-label"><?= $row['variant']; ?></label>
                          <input type="number" class="form-control form-control-md" id="<?= $row['variant']; ?>" value="0" min="0" name="jmlh[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpo']; ?>" value="<?= $row['idpo']; ?>" name="idpo[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpodetail']; ?>" value="<?= $row['idpodetail']; ?>" name="idpodetail[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['harga']; ?>" value="<?= $row['harga']; ?>" name="harga[]">
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- END DRESS KONIN ANAK -->

                  <!-- START KHIMAR ANAK  -->
                  <div class="tab-pane fade" id="khimar-anak-tab-pane" role="tabpanel" aria-labelledby="khimar-anak-tab" tabindex="0">
                    <div class="row">
                      <?php
                        
                        $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                ON poproduk.idpoproduk = pokategori.idpoproduk
                                AND pokategori.idpo = podetail.idpo
                                WHERE poproduk.idpoproduk = '259'
                                AND podetail.variant LIKE '%Khimar Anak%'
                                AND podetail.variant NOT LIKE '%French Khimar Anak%'
                                ORDER BY podetail.idpodetail ASC
                              ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()) {
                      ?>
                        <div class="col-sm-6 mt-3 mb-1">
                          <label for="<?= $row['variant']; ?>" class="form-label"><?= $row['variant']; ?></label>
                          <input type="number" class="form-control form-control-md" id="<?= $row['variant']; ?>" value="0" min="0" name="jmlh[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpo']; ?>" value="<?= $row['idpo']; ?>" name="idpo[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpodetail']; ?>" value="<?= $row['idpodetail']; ?>" name="idpodetail[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['harga']; ?>" value="<?= $row['harga']; ?>" name="harga[]">
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- END KHIMAR ANAK  -->

                  <!-- START FRENCH KHIMAR ANAK -->
                  <div class="tab-pane fade" id="french-anak-tab-pane" role="tabpanel" aria-labelledby="french-anak-tab" tabindex="0">
                    <div class="row">
                      <?php
                        
                        $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                ON poproduk.idpoproduk = pokategori.idpoproduk
                                AND pokategori.idpo = podetail.idpo
                                WHERE poproduk.idpoproduk = '259'
                                AND podetail.variant LIKE '%French Khimar Anak%'
                                ORDER BY podetail.idpodetail ASC
                              ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()) {
                      ?>
                        <div class="col-sm-6 mt-3 mb-1">
                          <label for="<?= $row['variant']; ?>" class="form-label"><?= $row['variant']; ?></label>
                          <input type="number" class="form-control form-control-md" id="<?= $row['variant']; ?>" value="0" min="0" name="jmlh[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpo']; ?>" value="<?= $row['idpo']; ?>" name="idpo[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpodetail']; ?>" value="<?= $row['idpodetail']; ?>" name="idpodetail[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['harga']; ?>" value="<?= $row['harga']; ?>" name="harga[]">
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- END FRENCH KHIMAR ANAK -->
                  
                  <!-- START KOKO LENGAN PENDEK ANAK -->
                  <div class="tab-pane fade" id="koko-anak-tab-pane" role="tabpanel" aria-labelledby="koko-anak-tab" tabindex="0">
                    <div class="row">
                      <?php
                        
                        $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                ON poproduk.idpoproduk = pokategori.idpoproduk
                                AND pokategori.idpo = podetail.idpo
                                WHERE poproduk.idpoproduk = '259'
                                AND podetail.variant LIKE '%Koko Lengan Pendek Anak%'
                                ORDER BY podetail.idpodetail ASC
                              ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()) {
                      ?>
                        <div class="col-sm-6 mt-3 mb-1">
                          <label for="<?= $row['variant']; ?>" class="form-label"><?= $row['variant']; ?></label>
                          <input type="number" class="form-control form-control-md" id="<?= $row['variant']; ?>" value="0" min="0" name="jmlh[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpo']; ?>" value="<?= $row['idpo']; ?>" name="idpo[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpodetail']; ?>" value="<?= $row['idpodetail']; ?>" name="idpodetail[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['harga']; ?>" value="<?= $row['harga']; ?>" name="harga[]">
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- END KOKO LENGAN PENDEK ANAK -->

                  <!-- START KOKO LENGAN PANJANG ANAK -->
                  <div class="tab-pane fade" id="panjang-anak-tab-pane" role="tabpanel" aria-labelledby="panjang-anak-tab" tabindex="0">
                    <div class="row">
                      <?php
                        
                        $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                ON poproduk.idpoproduk = pokategori.idpoproduk
                                AND pokategori.idpo = podetail.idpo
                                WHERE poproduk.idpoproduk = '259'
                                AND podetail.variant LIKE '%Koko Lengan Panjang Anak%'
                                ORDER BY podetail.idpodetail ASC
                              ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()) {
                      ?>
                        <div class="col-sm-6 mt-3 mb-1">
                          <label for="<?= $row['variant']; ?>" class="form-label"><?= $row['variant']; ?></label>
                          <input type="number" class="form-control form-control-md" id="<?= $row['variant']; ?>" value="0" min="0" name="jmlh[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpo']; ?>" value="<?= $row['idpo']; ?>" name="idpo[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpodetail']; ?>" value="<?= $row['idpodetail']; ?>" name="idpodetail[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['harga']; ?>" value="<?= $row['harga']; ?>" name="harga[]">
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- END KOKO LENGAN PANJANG ANAK -->

                  <!-- START CELANA ANAK -->
                  <div class="tab-pane fade" id="celana-anak-tab-pane" role="tabpanel" aria-labelledby="celana-anak-tab" tabindex="0">
                    <div class="row">
                      <?php
                        
                        $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                ON poproduk.idpoproduk = pokategori.idpoproduk
                                AND pokategori.idpo = podetail.idpo
                                WHERE poproduk.idpoproduk = '259'
                                AND podetail.variant LIKE '%Celana Anak%'
                                ORDER BY podetail.idpodetail ASC
                              ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()) {
                      ?>
                        <div class="col-sm-6 mt-3 mb-1">
                          <label for="<?= $row['variant']; ?>" class="form-label"><?= $row['variant']; ?></label>
                          <input type="number" class="form-control form-control-md" id="<?= $row['variant']; ?>" value="0" min="0" name="jmlh[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpo']; ?>" value="<?= $row['idpo']; ?>" name="idpo[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpodetail']; ?>" value="<?= $row['idpodetail']; ?>" name="idpodetail[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['harga']; ?>" value="<?= $row['harga']; ?>" name="harga[]">
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- END CELANA ANAK -->

                  <!-- START CELANA + KOKO PANJANG ANAK -->
                  <div class="tab-pane fade" id="celana-koko-anak-tab-pane" role="tabpanel" aria-labelledby="celana-koko-anak-tab" tabindex="0">
                    <div class="row">
                      <?php
                        
                        $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                ON poproduk.idpoproduk = pokategori.idpoproduk
                                AND pokategori.idpo = podetail.idpo
                                WHERE poproduk.idpoproduk = '259'
                                AND podetail.variant LIKE '%+ Koko Anak Panjang%'
                                ORDER BY podetail.idpodetail ASC
                              ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()) {
                      ?>
                        <div class="col-sm-6 mt-3 mb-1">
                          <label for="<?= $row['variant']; ?>" class="form-label"><?= $row['variant']; ?></label>
                          <input type="number" class="form-control form-control-md" id="<?= $row['variant']; ?>" value="0" min="0" name="jmlh[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpo']; ?>" value="<?= $row['idpo']; ?>" name="idpo[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpodetail']; ?>" value="<?= $row['idpodetail']; ?>" name="idpodetail[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['harga']; ?>" value="<?= $row['harga']; ?>" name="harga[]">
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- END CELANA + KOKO PANJANG ANAK -->

                  <!-- START CELANA + KOKO PENDEK ANAK -->
                  <div class="tab-pane fade" id="celana-pendek-anak-tab-pane" role="tabpanel" aria-labelledby="celana-pendek-anak-tab" tabindex="0">
                    <div class="row">
                      <?php
                        
                        $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                ON poproduk.idpoproduk = pokategori.idpoproduk
                                AND pokategori.idpo = podetail.idpo
                                WHERE poproduk.idpoproduk = '259'
                                AND podetail.variant LIKE '%+ Koko Anak Pendek%'
                                ORDER BY podetail.idpodetail ASC
                              ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()) {
                      ?>
                        <div class="col-sm-6 mt-3 mb-1">
                          <label for="<?= $row['variant']; ?>" class="form-label"><?= $row['variant']; ?></label>
                          <input type="number" class="form-control form-control-md" id="<?= $row['variant']; ?>" value="0" min="0" name="jmlh[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpo']; ?>" value="<?= $row['idpo']; ?>" name="idpo[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['idpodetail']; ?>" value="<?= $row['idpodetail']; ?>" name="idpodetail[]">
                          <input type="hidden" class="form-control form-control-md" id="<?= $row['harga']; ?>" value="<?= $row['harga']; ?>" name="harga[]">
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  <!-- START CELANA + KOKO PENDEK ANAK -->

                  <div class="mt-3">
                    <button type="submit" class="btn btn-primary" name='save'>Kirim</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>

      <?php
        if(isset($_POST["save"])) {
          include "koneksi.php";
          date_default_timezone_set('Asia/Jakarta');
          $today = date("s");
          $waktu = date("H:i:s");
          $idadmin = $_SESSION["mitraagen"]["idmitraagen"];
          $idpo = $_POST["idpo"];
          $idpodetail = $_POST["idpodetail"];
          $jmlh = $_POST["jmlh"];
          $harga = $_POST["harga"];
                                      
          $jumlah_dipilih = count($jmlh);
          $subtotal = 0;  
          $total = 0;
          $jmlhakhir = 0;

          for($x = 0; $x < $jumlah_dipilih; $x++) {
            $total = $jmlh[$x] * $harga[$x];
            $tot = $total;
            $jmlhakhir += $jmlhakhir + $jmlh[$x];
            $tot = 0;

            $sql = "SELECT stok FROM pokategori WHERE idpo='$idpo[$x]'";
            $query = $koneksi->query($sql);
            $sisa = $query->fetch_assoc();

            if ($jmlh[$x] > 0) {
              $ambil = $koneksi->query("SELECT idpodetail, invoice FROM pomitra 
                                        WHERE idpodetail='$idpodetail[$x]'
                                        AND invoice ='$invoice'
                                      ");
              $datacocok = $ambil->num_rows;
              
              if ($datacocok >= 1){
                $sql = $koneksi->query("UPDATE pomitra SET jumlah=jumlah + '$jmlh[$x]'
                                        WHERE idpodetail='$idpodetail[$x]'
                                        AND invoice ='$invoice'
                                      ");
                $koneksi->query("UPDATE pokategori SET stok=stok-'$jmlh[$x]' WHERE idpo='$idpo[$x]'");
              } else{
                $sql = $koneksi->query("INSERT INTO pomitra (idpomitra,idmitraagen,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) VALUES
                (NULL,'$idadmin','$idpoproduk','$idpo[$x]','$idpodetail[$x]','$jmlh[$x]','$total','$invoice','Belum DP',NOW(),'$waktu')");  
                $koneksi->query("UPDATE pokategori SET stok=stok-'$jmlh[$x]' WHERE idpo='$idpo[$x]'");
              }
            }
          }

          if ($sql) {
            echo "<script>alert('Data Berhasil Dikirim');</script>";
            echo "<script>location='datapokonin?invoice=$invoice';</script>";
          } else {
            echo "<script>alert('Data Gagal Dikirim');</script>";
            echo "<script>location='formpo_konin?invoice=$invoice';</script>";
          }
        }
      ?>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
  </html>