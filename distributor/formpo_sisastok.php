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
                font-size: 20px;
                color: #0f0f0a;
                text-align: center;
            }
            .navbaru i {
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

                <form method="POST" class="row mx-auto">
                    <div class="col-sm-12 card py-4 px-3">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Koko Apodiformes</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Dress Apodiformes</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Koko Matari</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="dress-matari-tab" data-bs-toggle="tab" data-bs-target="#dress-matari-tab-pane" type="button" role="tab" aria-controls="dress-matari-tab-pane" aria-selected="false">Dress Matari</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pashmina-tab" data-bs-toggle="tab" data-bs-target="#pashmina-tab-pane" type="button" role="tab" aria-controls="pashmina-tab-pane" aria-selected="false">Pashmina Matari</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="khimar-tab" data-bs-toggle="tab" data-bs-target="#khimar-tab-pane" type="button" role="tab" aria-controls="khimar-tab-pane" aria-selected="false">Khimar Matari</button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane row mx-1 my-2 fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                <div class="row">
                                    <?php
                                        $sql = $koneksi->query("SELECT poproduk.namapo, podetail.idpodetail,
                                                                        podetail.variant, pokategori.idpo, pokategori.stok
                                                                        FROM poproduk
                                                                        INNER JOIN pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                        INNER JOIN podetail ON podetail.idpo = pokategori.idpo
                                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                                        AND podetail.variant LIKE '%Koko Apodiformes%'
                                                                ");
                                        while ($query = $sql->fetch_assoc()) {
                                    ?>
                                        <div class="col-lg-4">
                                            <div class="form-group my-2">
                                                <label for="<?= $query['idpodetail'] ?>"><?= $query['variant'] ?></label>
                                                <input type="number" name="jumlah[]" id="<?= $query['idpodetail'] ?>" class="form-control form-control-sm" min="0" value="0" max="<?= $query['stok'] ?>">
                                                <input type="hidden" name="idpodetail[]" value="<?= $query['idpodetail'] ?>" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="tab-pane row mx-1 my-2 fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                                <div class="row">
                                    <?php
                                        $sql = $koneksi->query("SELECT poproduk.namapo, podetail.idpodetail,
                                                                        podetail.variant, pokategori.idpo, pokategori.stok
                                                                        FROM poproduk
                                                                        INNER JOIN pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                        INNER JOIN podetail ON podetail.idpo = pokategori.idpo
                                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                                        AND podetail.variant LIKE '%Dress Apodiformes%'
                                                                ");
                                        while ($query = $sql->fetch_assoc()) {
                                    ?>
                                        <div class="col-lg-4">
                                            <div class="form-group my-2">
                                                <label for="<?= $query['idpodetail'] ?>"><?= $query['variant'] ?></label>
                                                <input type="number" name="jumlah[]" id="<?= $query['idpodetail'] ?>" class="form-control form-control-sm" min="0" value="0" max="<?= $query['stok'] ?>">
                                                <input type="hidden" name="idpodetail[]" value="<?= $query['idpodetail'] ?>" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="tab-pane row mx-1 my-2 fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                                <div class="row">
                                    <?php
                                        $sql = $koneksi->query("SELECT poproduk.namapo, podetail.idpodetail,
                                                                        podetail.variant, pokategori.idpo, pokategori.stok
                                                                        FROM poproduk
                                                                        INNER JOIN pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                        INNER JOIN podetail ON podetail.idpo = pokategori.idpo
                                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                                        AND pokategori.idpo BETWEEN 3203 AND 3244
                                                                ");
                                        while ($query = $sql->fetch_assoc()) {
                                    ?>
                                        <div class="col-lg-4">
                                            <div class="form-group my-2">
                                                <label for="<?= $query['idpodetail'] ?>"><?= $query['variant'] ?></label>
                                                <input type="number" name="jumlah[]" id="<?= $query['idpodetail'] ?>" class="form-control form-control-sm" min="0" value="0" max="<?= $query['stok'] ?>">
                                                <input type="hidden" name="idpodetail[]" value="<?= $query['idpodetail'] ?>" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="tab-pane row mx-1 my-2 fade" id="dress-matari-tab-pane" role="tabpanel" aria-labelledby="dress-matari-tab" tabindex="0">Dress Matari</div>
                            <div class="tab-pane row mx-1 my-2 fade" id="pashmina-tab-pane" role="tabpanel" aria-labelledby="pashmina-tab" tabindex="0">Pashmina</div>
                            <div class="tab-pane row mx-1 my-2 fade" id="khimar-tab-pane" role="tabpanel" aria-labelledby="khimar-tab" tabindex="0">Khimar</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    </body>
</html>