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
            <div class="col-sm-2 text-center">
                <a class="navbar-brand" href="listnewpo.php"><i class="bi bi-chevron-left"></i></a>
            </div>

            <div class="col-sm-8 text-center">
                <h5 class="p-0 m-0 fw-semibold text-uppercase">pre order</h5>
            </div>
            
            <div class="col-sm-2"></div>
        </div>
    </nav>

    <div class="container my-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="fw-semibold h6 text-center">Formulir <?= $namapo; ?></p>

                <form method="post" class="container">
                    <!-- Avocado -->
                    <div class="row my-2">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Dress Avocado</label>
                                <select class="form-select form-select-sm" name="idpodetail[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND (podetail.variant LIKE '%Dress%' AND podetail.variant LIKE '%Avocado%')
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Bergo Avocado</label>
                                <select class="form-select form-select-sm" name="custom[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND podetail.variant LIKE '%Bergo%'
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?> | <?= $data_dress['variant'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>QTY</label>
                                <input type="number" name="qty[]" class="form-control form-control-sm" value="0" min="0">
                                <input type="hidden" name="is_custom[]" class="form-control form-control-sm" value="1">
                            </div>
                        </div>
                    </div>

                    <!-- Black -->
                    <div class="row my-2">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Dress Black</label>
                                <select class="form-select form-select-sm" name="idpodetail[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND (podetail.variant LIKE '%Dress%' AND podetail.variant LIKE '%Black%')
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Bergo Black</label>
                                <select class="form-select form-select-sm" name="custom[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND podetail.variant LIKE '%Bergo%'
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?> | <?= $data_dress['variant'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>QTY</label>
                                <input type="number" name="qty[]" class="form-control form-control-sm" value="0" min="0">
                                <input type="hidden" name="is_custom[]" class="form-control form-control-sm" value="2">
                            </div>
                        </div>
                    </div>

                    <!-- Brick Red -->
                    <div class="row my-2">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Dress Brick Red</label>
                                <select class="form-select form-select-sm" name="idpodetail[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND (podetail.variant LIKE '%Dress%' AND podetail.variant LIKE '%Brick Red%')
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Bergo Brick Red</label>
                                <select class="form-select form-select-sm" name="custom[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND podetail.variant LIKE '%Bergo%'
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?> | <?= $data_dress['variant'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>QTY</label>
                                <input type="number" name="qty[]" class="form-control form-control-sm" value="0" min="0">
                                <input type="hidden" name="is_custom[]" class="form-control form-control-sm" value="3">
                            </div>
                        </div>
                    </div>

                    <!-- Denim -->
                    <div class="row my-2">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Dress Denim</label>
                                <select class="form-select form-select-sm" name="idpodetail[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND (podetail.variant LIKE '%Dress%' AND podetail.variant LIKE '%Denim%')
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Bergo Denim</label>
                                <select class="form-select form-select-sm" name="custom[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND podetail.variant LIKE '%Bergo%'
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?> | <?= $data_dress['variant'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>QTY</label>
                                <input type="number" name="qty[]" class="form-control form-control-sm" value="0" min="0">
                                <input type="hidden" name="is_custom[]" class="form-control form-control-sm" value="4">
                            </div>
                        </div>
                    </div>

                    <!-- Dusty Mauve -->
                    <div class="row my-2">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Dress Dusty Mauve</label>
                                <select class="form-select form-select-sm" name="idpodetail[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND (podetail.variant LIKE '%Dress%' AND podetail.variant LIKE '%Dusty Mauve%')
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Bergo Dusty Mauve</label>
                                <select class="form-select form-select-sm" name="custom[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND podetail.variant LIKE '%Bergo%'
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?> | <?= $data_dress['variant'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>QTY</label>
                                <input type="number" name="qty[]" class="form-control form-control-sm" value="0" min="0">
                                <input type="hidden" name="is_custom[]" class="form-control form-control-sm" value="5">
                            </div>
                        </div>
                    </div>

                    <!-- Grey -->
                    <div class="row my-2">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Dress Grey</label>
                                <select class="form-select form-select-sm" name="idpodetail[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND (podetail.variant LIKE '%Dress%' AND podetail.variant LIKE '%Grey%')
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Bergo Grey</label>
                                <select class="form-select form-select-sm" name="custom[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND podetail.variant LIKE '%Bergo%'
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?> | <?= $data_dress['variant'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>QTY</label>
                                <input type="number" name="qty[]" class="form-control form-control-sm" value="0" min="0">
                                <input type="hidden" name="is_custom[]" class="form-control form-control-sm" value="6">
                            </div>
                        </div>
                    </div>

                    <!-- Ginger -->
                    <div class="row my-2">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Dress Ginger</label>
                                <select class="form-select form-select-sm" name="idpodetail[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND (podetail.variant LIKE '%Dress%' AND podetail.variant LIKE '%Ginger%')
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Bergo Ginger</label>
                                <select class="form-select form-select-sm" name="custom[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND podetail.variant LIKE '%Bergo%'
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?> | <?= $data_dress['variant'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>QTY</label>
                                <input type="number" name="qty[]" class="form-control form-control-sm" value="0" min="0">
                                <input type="hidden" name="is_custom[]" class="form-control form-control-sm" value="7">
                            </div>
                        </div>
                    </div>

                    <!-- Ivory -->
                    <div class="row my-2">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Dress Ivory</label>
                                <select class="form-select form-select-sm" name="idpodetail[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND (podetail.variant LIKE '%Dress%' AND podetail.variant LIKE '%Ivory%')
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Bergo Ivory</label>
                                <select class="form-select form-select-sm" name="custom[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND podetail.variant LIKE '%Bergo%'
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?> | <?= $data_dress['variant'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>QTY</label>
                                <input type="number" name="qty[]" class="form-control form-control-sm" value="0" min="0">
                                <input type="hidden" name="is_custom[]" class="form-control form-control-sm" value="8">
                            </div>
                        </div>
                    </div>

                    <!-- Midnight Blue -->
                    <div class="row my-2">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Dress Midnight Blue</label>
                                <select class="form-select form-select-sm" name="idpodetail[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND (podetail.variant LIKE '%Dress%' AND podetail.variant LIKE '%Midnight Blue%')
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Bergo Midnight Blue</label>
                                <select class="form-select form-select-sm" name="custom[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND podetail.variant LIKE '%Bergo%'
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?> | <?= $data_dress['variant'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>QTY</label>
                                <input type="number" name="qty[]" class="form-control form-control-sm" value="0" min="0">
                                <input type="hidden" name="is_custom[]" class="form-control form-control-sm" value="9">
                            </div>
                        </div>
                    </div>

                    <!-- Rubby Maroon -->
                    <div class="row my-2">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Dress Rubby Maroon</label>
                                <select class="form-select form-select-sm" name="idpodetail[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND (podetail.variant LIKE '%Dress%' AND podetail.variant LIKE '%Rubby Maroon%')
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Bergo Rubby Maroon</label>
                                <select class="form-select form-select-sm" name="custom[]">
                                    <option>~ Default Selected ~</option>
                                    <?php
                                        $query_dress = $koneksi->query("SELECT
                                                            pokategori.idpo,
                                                            pokategori.stok, 
                                                            podetail.idpodetail,
                                                            podetail.variant,
                                                            podetail.harga,
                                                            podetail.berat,
                                                            poproduk.idpoproduk
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        AND podetail.variant LIKE '%Bergo%'
                                                        AND pokategori.stok > 0
                                                    ");
                                        while ($data_dress = $query_dress->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data_dress['idpodetail'] ?> | <?= $data_dress['variant'] ?>"><?= $data_dress['variant']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>QTY</label>
                                <input type="number" name="qty[]" class="form-control form-control-sm" value="0" min="0">
                                <input type="hidden" name="is_custom[]" class="form-control form-control-sm" value="10">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12 my-2">
                            <button type="submit" class="btn btn-sm btn-primary" name="kirim">
                                Kirim
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
        if (isset($_POST['kirim'])) {
            try {
                date_default_timezone_set('Asia/Jakarta');
                $today = date('s');
                $waktu = date('H:i:s');
                $idpodetail = $_POST['idpodetail'];
                $jumlah = $_POST['qty'];
                $custom = $_POST['custom'];
                $is_custom = $_POST['is_custom'];

                if (isset($_GET['invoice'])) {
                    $invoice = $_GET['invoice'];
                } else {  
                    // Fungsi untuk menghasilkan angka acak dengan panjang tertentu
                    function generateAngkaAcak($length) {
                        $angka_acak = '';
                        for ($i = 0; $i < $length; $i++) {
                        // Menggunakan mt_rand untuk angka acak dari 0 hingga 9
                        $angka_acak .= mt_rand(0, 9);
                        }
                        return $angka_acak;
                    }
            
                    // Menghasilkan angka acak dengan panjang minimal 7 dan maksimal 7 angka
                    $angka_acak = generateAngkaAcak(5);
                    $invoice = 'D' . $idpoproduk . '-' . $idadmin . $angka_acak;
                }

                $sum = count($is_custom);
                for ($i = 0; $i < $sum; $i++) {
                    if ($jumlah[$i] > 0) {
                        $idpodetail_item = $idpodetail[$i];
                        $custom_item = $custom[$i];
                        $bergo = explode(" | ", $custom_item);
                        $id_bergo = $bergo[0];
                        $variant_bergo = $bergo[1];
                        $total_qty = $jumlah[$i];

                        $query = $koneksi->query("SELECT podetail.*, pokategori.stok FROM podetail INNER JOIN pokategori ON podetail.idpo = pokategori.idpo WHERE idpodetail = '$idpodetail_item'");
                        $data = $query->fetch_assoc();
                        $idpo = $data['idpo'];

                        $query_bergo = $koneksi->query("SELECT podetail.*, pokategori.stok FROM podetail INNER JOIN pokategori ON podetail.idpo = pokategori.idpo WHERE idpodetail = '$id_bergo'");
                        $data_bergo = $query_bergo->fetch_assoc();
                        $idpo_bergo = $data_bergo['idpo'];

                        $total_harga = $total_qty * 514000;
                        
                        if ($data_bergo['stok'] > 0 && $data['stok'] > 0) {
                            $stok_bergo = $koneksi->query("UPDATE pokategori SET stok = stok - $total_qty WHERE idpo = '$idpo_bergo'");
                            $stok_dress = $koneksi->query("UPDATE pokategori SET stok = stok - $total_qty WHERE idpo = '$idpo'");
                            
                            $sql = $koneksi->query("INSERT INTO pomitra (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, custom, total, invoice, status, tgl, waktu) VALUES (NULL, '$idadmin', '$idpoproduk', '$idpo', '$idpodetail_item', '$total_qty', '$variant_bergo', '$total_harga', '$invoice', 'Belum DP', NOW(), '$waktu')");
                            echo "<script>alert('Data berhasil di kirim!');</script>";
                            echo "<script>location='datapocustom2.php?id=$idpoproduk&invoice=$invoice'</script>";
                        } elseif ($data_bergo['stok'] <= 0 || $data['stok'] <= 0) {
                            echo "<script>alert('Stok barang tidak ada!');</script>";
                            echo "<script>location='formpocustom3.php?id=$idpoproduk'</script>";
                        }
                    }
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    ?>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>