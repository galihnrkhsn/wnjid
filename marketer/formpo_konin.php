<?php
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesMarketer.php';

    $idpoproduk = $_GET['id'];
    $invoice = $_GET['invoice'];
    $idmitramarketer = $_SESSION["idmitramarketer"];
    
    $query = $koneksi->query("SELECT 
                                    poproduk.idpoproduk, poproduk.namapo, podropship.invoice
                                FROM
                                    poproduk
                                        JOIN
                                    podropship ON podropship.idpoproduk = poproduk.idpoproduk
                                WHERE
                                    podropship.invoice = '$invoice'
                            ");
    $data = $query->fetch_assoc();
    $idpoproduk = $data['idpoproduk'];
    $namapo = $data['namapo'];

    $sqlmitra = $koneksi->query("SELECT * FROM mitramarketer WHERE idmitramarketer = '$idmitramarketer'");
    $datamitra = $sqlmitra->fetch_assoc();
    $idadmin = $datamitra['idadmin'];
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

    <body>
        <nav class="navbar bg-body-tertiary">
            <div class="container d-flex align-items-center">
                <a href="pokonin.php?id=<?= $idpoproduk ?>" class="text-muted fw-semibold"><i class="bi bi-chevron-left"></i></a>
                <p class="navbar-brand text-uppercase fw-semibold mb-0" href="#">Pre Order</p>
                <i class="opacity-0 bi bi-chevron-right"></i>
            </div>
        </nav>

        <div class="container mt-3">
            <div class="col-sm-12 text-center">
                <h4>Formulir Pemesanan <?= $namapo ?><br /><?= $data['invoice'] ?></h4>
            </div>

            <div class="card mb-5">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#koko-dewasa" type="button" role="tab" aria-controls="koko-dewasa" aria-selected="true">Koko Dewasa</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#koko-anak" type="button" role="tab" aria-controls="koko-anak" aria-selected="false">Koko Anak</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#gamis" type="button" role="tab" aria-controls="gamis" aria-selected="false">Gamis</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#tunik" type="button" role="tab" aria-controls="tunik" aria-selected="false">Tunik</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#french-khimar" type="button" role="tab" aria-controls="french-khimar" aria-selected="false">French Khimar</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#khimar" type="button" role="tab" aria-controls="khimar" aria-selected="false">Khimar</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#pashmina-voal" type="button" role="tab" aria-controls="pashmina-voal" aria-selected="false">Pashmina & Voal</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#celana" type="button" role="tab" aria-controls="celana" aria-selected="false">Celana</button>
                        </li>
                    </ul>

                    <form method="post" enctype="multipart/form-data">
                        <div class="tab-content mt-2" id="myTabContent">
                            <div class="tab-pane fade show active" id="koko-dewasa" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                <div class="row">
                                    <?php
                                        $sql = $koneksi->query("SELECT 
                                                                        podetail.idpodetail,
                                                                        podetail.variant,
                                                                        podetail.harga,
                                                                        podetail.berat,
                                                                        podetail.idpo
                                                                    FROM
                                                                        poproduk
                                                                            INNER JOIN
                                                                        pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                            INNER JOIN
                                                                        podetail ON podetail.idpo = pokategori.idpo
                                                                    WHERE
                                                                        poproduk.idpoproduk = '$idpoproduk'
                                                                            AND (podetail.variant LIKE '%Koko%'
                                                                            AND podetail.variant LIKE '%Dewasa%')
                                                            ");
                                        while ($dataproduk = $sql->fetch_assoc()) {
                                    ?>
                                        <div class="col-lg-4 mb-2">
                                            <div class="form-group">
                                                <label class="form-label mb-0 text-muted"><?= str_replace('Konin 2025', '', $dataproduk['variant']) ?></label>
                                                <input type="number" class="form-control form-control-sm" name="qty[]" min="0" value="0">
                                                <input type="hidden" class="form-control form-control-sm" name="idpodetail[]" min="0" value="<?= $dataproduk['idpodetail'] ?>">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="koko-anak" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                <div class="row">
                                    <?php
                                        $sql = $koneksi->query("SELECT 
                                                                        podetail.idpodetail,
                                                                        podetail.variant,
                                                                        podetail.harga,
                                                                        podetail.berat,
                                                                        podetail.idpo
                                                                    FROM
                                                                        poproduk
                                                                            INNER JOIN
                                                                        pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                            INNER JOIN
                                                                        podetail ON podetail.idpo = pokategori.idpo
                                                                    WHERE
                                                                        poproduk.idpoproduk = '$idpoproduk'
                                                                            AND (podetail.variant LIKE '%Koko%'
                                                                            AND podetail.variant LIKE '%Anak%')
                                                            ");
                                        while ($dataproduk = $sql->fetch_assoc()) {
                                    ?>
                                        <div class="col-lg-4 mb-2">
                                            <div class="form-group">
                                                <label class="form-label mb-0 text-muted"><?= str_replace('Konin 2025', '', $dataproduk['variant']) ?></label>
                                                <input type="number" class="form-control form-control-sm" name="qty[]" min="0" value="0">
                                                <input type="hidden" class="form-control form-control-sm" name="idpodetail[]" min="0" value="<?= $dataproduk['idpodetail'] ?>">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="gamis" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                <div class="row">
                                    <?php
                                        $sql = $koneksi->query("SELECT 
                                                                        podetail.idpodetail,
                                                                        podetail.variant,
                                                                        podetail.harga,
                                                                        podetail.berat,
                                                                        podetail.idpo
                                                                    FROM
                                                                        poproduk
                                                                            INNER JOIN
                                                                        pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                            INNER JOIN
                                                                        podetail ON podetail.idpo = pokategori.idpo
                                                                    WHERE
                                                                        poproduk.idpoproduk = '$idpoproduk'
                                                                            AND podetail.variant LIKE '%Gamis%'
                                                            ");
                                        while ($dataproduk = $sql->fetch_assoc()) {
                                    ?>
                                        <div class="col-lg-4 mb-2">
                                            <div class="form-group">
                                                <label class="form-label mb-0 text-muted"><?= str_replace('Konin 2025', '', $dataproduk['variant']) ?></label>
                                                <input type="number" class="form-control form-control-sm" name="qty[]" min="0" value="0">
                                                <input type="hidden" class="form-control form-control-sm" name="idpodetail[]" min="0" value="<?= $dataproduk['idpodetail'] ?>">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tunik" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                <div class="row">
                                    <?php
                                        $sql = $koneksi->query("SELECT 
                                                                        podetail.idpodetail,
                                                                        podetail.variant,
                                                                        podetail.harga,
                                                                        podetail.berat,
                                                                        podetail.idpo
                                                                    FROM
                                                                        poproduk
                                                                            INNER JOIN
                                                                        pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                            INNER JOIN
                                                                        podetail ON podetail.idpo = pokategori.idpo
                                                                    WHERE
                                                                        poproduk.idpoproduk = '$idpoproduk'
                                                                            AND podetail.variant LIKE '%Tunik%'
                                                            ");
                                        while ($dataproduk = $sql->fetch_assoc()) {
                                    ?>
                                        <div class="col-lg-4 mb-2">
                                            <div class="form-group">
                                                <label class="form-label mb-0 text-muted"><?= str_replace('Konin 2025', '', $dataproduk['variant']) ?></label>
                                                <input type="number" class="form-control form-control-sm" name="qty[]" min="0" value="0">
                                                <input type="hidden" class="form-control form-control-sm" name="idpodetail[]" min="0" value="<?= $dataproduk['idpodetail'] ?>">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="french-khimar" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                <div class="row">
                                    <?php
                                        $sql = $koneksi->query("SELECT 
                                                                        podetail.idpodetail,
                                                                        podetail.variant,
                                                                        podetail.harga,
                                                                        podetail.berat,
                                                                        podetail.idpo
                                                                    FROM
                                                                        poproduk
                                                                            INNER JOIN
                                                                        pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                            INNER JOIN
                                                                        podetail ON podetail.idpo = pokategori.idpo
                                                                    WHERE
                                                                        poproduk.idpoproduk = '$idpoproduk'
                                                                            AND podetail.variant LIKE '%French Khimar%'
                                                            ");
                                        while ($dataproduk = $sql->fetch_assoc()) {
                                    ?>
                                        <div class="col-lg-4 mb-2">
                                            <div class="form-group">
                                                <label class="form-label mb-0 text-muted"><?= str_replace('Konin 2025', '', $dataproduk['variant']) ?></label>
                                                <input type="number" class="form-control form-control-sm" name="qty[]" min="0" value="0">
                                                <input type="hidden" class="form-control form-control-sm" name="idpodetail[]" min="0" value="<?= $dataproduk['idpodetail'] ?>">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="khimar" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                <div class="row">
                                    <?php
                                        $sql = $koneksi->query("SELECT 
                                                                        podetail.idpodetail,
                                                                        podetail.variant,
                                                                        podetail.harga,
                                                                        podetail.berat,
                                                                        podetail.idpo
                                                                    FROM
                                                                        poproduk
                                                                            INNER JOIN
                                                                        pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                            INNER JOIN
                                                                        podetail ON podetail.idpo = pokategori.idpo
                                                                    WHERE
                                                                        poproduk.idpoproduk = '$idpoproduk'
                                                                            AND (podetail.variant LIKE '%Khimar%' AND podetail.variant NOT LIKE '%French Khimar%')
                                                            ");
                                        while ($dataproduk = $sql->fetch_assoc()) {
                                    ?>
                                        <div class="col-lg-4 mb-2">
                                            <div class="form-group">
                                                <label class="form-label mb-0 text-muted"><?= str_replace('Konin 2025', '', $dataproduk['variant']) ?></label>
                                                <input type="number" class="form-control form-control-sm" name="qty[]" min="0" value="0">
                                                <input type="hidden" class="form-control form-control-sm" name="idpodetail[]" min="0" value="<?= $dataproduk['idpodetail'] ?>">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pashmina-voal" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                <div class="row">
                                    <?php
                                        $sql = $koneksi->query("SELECT 
                                                                        podetail.idpodetail,
                                                                        podetail.variant,
                                                                        podetail.harga,
                                                                        podetail.berat,
                                                                        podetail.idpo
                                                                    FROM
                                                                        poproduk
                                                                            INNER JOIN
                                                                        pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                            INNER JOIN
                                                                        podetail ON podetail.idpo = pokategori.idpo
                                                                    WHERE
                                                                        poproduk.idpoproduk = '$idpoproduk'
                                                                            AND (podetail.variant LIKE '%Pashmina%'
                                                                                OR podetail.variant LIKE '%Voal%')
                                                            ");
                                        while ($dataproduk = $sql->fetch_assoc()) {
                                    ?>
                                        <div class="col-lg-4 mb-2">
                                            <div class="form-group">
                                                <label class="form-label mb-0 text-muted"><?= str_replace('Konin 2025', '', $dataproduk['variant']) ?></label>
                                                <input type="number" class="form-control form-control-sm" name="qty[]" min="0" value="0">
                                                <input type="hidden" class="form-control form-control-sm" name="idpodetail[]" min="0" value="<?= $dataproduk['idpodetail'] ?>">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="celana" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                <div class="row">
                                    <?php
                                        $sql = $koneksi->query("SELECT 
                                                                        podetail.idpodetail,
                                                                        podetail.variant,
                                                                        podetail.harga,
                                                                        podetail.berat,
                                                                        podetail.idpo
                                                                    FROM
                                                                        poproduk
                                                                            INNER JOIN
                                                                        pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                            INNER JOIN
                                                                        podetail ON podetail.idpo = pokategori.idpo
                                                                    WHERE
                                                                        poproduk.idpoproduk = '$idpoproduk'
                                                                            AND podetail.variant LIKE '%Celana%'
                                                            ");
                                        while ($dataproduk = $sql->fetch_assoc()) {
                                    ?>
                                        <div class="col-lg-4 mb-2">
                                            <div class="form-group">
                                                <label class="form-label mb-0 text-muted"><?= str_replace('Konin 2025', '', $dataproduk['variant']) ?></label>
                                                <input type="number" class="form-control form-control-sm" name="qty[]" min="0" value="0">
                                                <input type="hidden" class="form-control form-control-sm" name="idpodetail[]" min="0" value="<?= $dataproduk['idpodetail'] ?>">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-sm btn-primary mt-2" name="kirim">Kirim</button>
                    </form>
                </div>
            </div>
        </div>

        <?php
            if (isset($_POST['kirim'])) {
                try {
                    // echo "<pre>";
                    //     print_r($_POST);
                    // echo "</pre>";
                    date_default_timezone_set('Asia/Jakarta');
                    $waktu = date("H:i:s");
                    $tanggal = date("Y-m-d");

                    $count = count($_POST['qty']);
                    for ($x = 0; $x < $count; $x++) {
                        $jumlah = $_POST['qty'][$x];
                        if ($jumlah > 0) {
                            $idpodetail = $_POST['idpodetail'][$x];
                            $select = $koneksi->query("SELECT 
                                                            *
                                                        FROM
                                                            podetail
                                                                INNER JOIN
                                                            pokategori ON podetail.idpo = pokategori.idpo
                                                        WHERE
                                                            podetail.idpodetail = '$idpodetail'
                                                    ");
                            $data = $select->fetch_assoc();
                            $harga = $data['harga'];
                            $idpo = $data['idpo'];
                            $total = $harga * $jumlah;
                            $ins_po = $koneksi->query("INSERT INTO pomitra VALUES (
                                                            NULL, '$idadmin', NULL, NULL, '$idmitramarketer',
                                                            '$idpoproduk', '$idpo', '$idpodetail',
                                                            '$jumlah', NULL, NULL, NULL,
                                                            '$total', '$invoice', 'Belum Acc DB',
                                                            NULL, NULL, '$tanggal', '$waktu',
                                                            NULL
                                                        )
                                                    ");
                            if ($ins_po) {
                                $idpomitra = $koneksi->insert_id;
                                
                                $sqlidds = $koneksi->query("SELECT * FROM podropship WHERE invoice = '$invoice'");
                                $datads = $sqlidds->fetch_assoc();
                                $iddropship = $datads['iddropship'];
                                $nods = $invoice . "-" . $iddropship;
                                $ins_pods = $koneksi->query("INSERT INTO pods VALUES
                                                                (
                                                                    NULL, '$nods', '$invoice',
                                                                    '$idpodetail', '$idpomitra', '$jumlah',
                                                                    NOW()
                                                                )
                                                            ");
                                $upd_pods = $koneksi->query("UPDATE podropship SET no_ds = '$nods' WHERE invoice = '$invoice'");
                                if ($ins_pods) {
                                    echo "
                                        <script>
                                            alert('Data berhasil disimpan')
                                            location='datapokonin.php?id=$idpoproduk&invoice=$invoice'
                                        </script>
                                    ";
                                } else {
                                    echo "
                                        <script>
                                            alert('Gagal ditambahkan!')
                                            location='datapokonin.php?id=$idpoproduk&invoice=$invoice'
                                        </script>
                                    ";
                                }
                            } else {
                                echo "
                                    <script>
                                        alert('Data gagal disimpan!')
                                        location='formpo_konin?id=$idpoproduk&invoice=$invoice'
                                    </script>
                                ";
                            }
                        }
                    }
                } catch (Exception $e) {
                    echo "Error: ". $e->getMessage();
                }
            }
        ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>