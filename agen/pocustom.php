<?php
    session_start();
    error_reporting (0);
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesAgen.php';

    $invoice        = $_GET['invoice'];
    $idpoproduk     = $_GET['id'];
    $idmitraagen    = $_SESSION["idmitraagen"];
    $ambil          = $koneksi->query("SELECT mitraagen.idmitraagen, mitraagen.namaagen FROM mitraagen where idmitraagen = '$idmitraagen'");
    $mode           = $ambil->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WNJ | <?= $mode["namaagen"] ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid row">
            <div class="col-2 text-center">
                <a class="navbar-brand" href="listnewpo.php"><i class="bi bi-chevron-left"></i></a>
            </div>

            <div class="col-8 text-center">
                <h5 class="p-0 m-0 fw-semibold text-uppercase">pre order</h5>
            </div>
            
            <div class="col-2"></div>
        </div>
    </nav>

    <div class="container my-3">
        <div class="row">
            <?php
                $dataproduk = $koneksi->query("SELECT bukapo.idbpo,
                          bukapo.jenis_mitra,
                          bukapo.jenis_po,
                          bukapo.idpoproduk,
                          bukapo.tgl,
                          bukapo.tgl_dropship,
                          bukapo.status,
                          poproduk.namapo 
                          FROM bukapo inner join poproduk on bukapo.idpoproduk = poproduk.idpoproduk
                          WHERE poproduk.idpoproduk = '$idpoproduk'");
                $tampilkan = $dataproduk->fetch_assoc()
            ?>

            <?php if ($idpoproduk == 331) : ?>
                <div class="col-sm-6 text-center">
                    <a class="btn btn-primary d-block" href="formpocustom.php?id=<?= $idpoproduk ?>&jenis=Satuan">Link Satuan <?= $tampilkan['namapo'] ?></a>
                </div>
                <div class="col-sm-6 text-center">
                    <a class="btn btn-primary d-block" href="formpocustom.php?id=<?= $idpoproduk ?>&jenis=Bundling">Link Bundling <?= $tampilkan['namapo'] ?></a>
                </div>
            <?php elseif ($tampilkan['jenis_po'] === 'PO Custom' || $tampilkan['jenis_po'] === 'PO Custom Stok') : ?>
                <div class="col-sm-6 text-center">
                    <a class="btn btn-primary d-block" href="formpocustom5.php?id=<?= $idpoproduk ?>&jenis=Satuan">Link Satuan <?= $tampilkan['namapo'] ?></a>
                </div>
                <div class="col-sm-6 text-center">
                    <?php if ($idpoproduk == 506) : ?>
                        <a class="btn btn-primary d-block" href="formpobundling2.php?id=<?= $idpoproduk ?>&jenis=Bundling">Link Bundling <?= $tampilkan['namapo'] ?></a>
                    <?php else : ?>
                    <a class="btn btn-primary d-block" href="formpocustom5.php?id=<?= $idpoproduk ?>&jenis=Bundling">Link Bundling <?= $tampilkan['namapo'] ?></a>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <div class="col-sm-6 text-center">
                    <a class="btn btn-primary d-block" href="formpocustom5.php?id=<?= $idpoproduk ?>&jenis=Satuan">Link Satuan <?= $tampilkan['namapo'] ?></a>
                </div>
                <div class="col-sm-6 text-center">
                    <a class="btn btn-primary d-block" href="formpocustom5.php?id=<?= $idpoproduk ?>&jenis=Bundling">Link Bundling <?= $tampilkan['namapo'] ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>