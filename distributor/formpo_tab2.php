<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $invoice = $_GET['invoice'];
    $idadmin = $_SESSION["idadmin"];
    $jenis_po = $_GET['jenis'];

    $query_po = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = '$idpoproduk'");
    $sql = $query_po->fetch_assoc();
    $namapo = $sql['namapo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form <?= $namapo ?> | WNJ.ID</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <p class="fw-semibold h6 text-center">Form <?= $jenis_po ?> <?= $namapo; ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>