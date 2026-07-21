<?php
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesReseller.php';

    $idpoproduk = $_GET['id'];
    $query = $koneksi->query("SELECT * FROM poproduk INNER JOIN bukapo ON bukapo.idpoproduk = poproduk.idpoproduk WHERE poproduk.idpoproduk = '$idpoproduk'");
    $datapo = $query->fetch_assoc();

    $tgl_bayar = $datapo['tgl_bayar']; 
    $waktu_bayar = '23:59:59';
    $namapo = $datapo['namapo'];
    $idmitrareseller = $_SESSION['idmitrareseller'];
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Data <?= $namapo ?></title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <style>
            * {
                font-family: "Inter", sans-serif;
            }

            .custom-width {
                width: 60%; /* Default width untuk layar kecil */
            }

            @media (min-width: 768px) { /* Mulai dari layar ukuran medium (tablet) */
                .custom-width {
                    width: 40%;
                }
            }

            @media (min-width: 992px) { /* Mulai dari layar ukuran besar (desktop) */
                .custom-width {
                    width: 20%;
                }
            }
        </style>
    </head>
    <body>
        <nav class="navbar bg-body-tertiary">
            <div class="container d-flex align-items-center">
                <a href="listnewpo.php" class="text-muted"><i class="bi bi-chevron-left"></i></a>
                <p class="navbar-brand text-uppercase fw-bold mb-0">Detail <?= $namapo ?></p>
                <i class="opacity-0 bi bi-chevron-right"></i>
            </div>
        </nav>

        <div class="container mt-3">
            <div class="col-sm-12 text-center">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h6 class="text-uppercase mb-0 card-title">Reseller</h6>
                    </div>
                    <div class="card-body">
                        <a href="summarydb.php?id=<?= $idpoproduk ?>" class="btn btn-success btn-sm" style="font-size: .725rem">Summary Barang</a>
                        
                        <div class="table-responsive mt-2">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Ongkir</th>
                                        <th>Invoice</th>
                                        <th class="d-none">Alamat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $koneksi->query("SELECT 
                                                                        pomitra.invoice,
                                                                        pomitra.status,
                                                                        pomitra.tgl,
                                                                        ongkir.status AS status_ongkir
                                                                    FROM
                                                                        pomitra
                                                                            INNER JOIN
                                                                        poproduk ON poproduk.idpoproduk = pomitra.idpoproduk
                                                                            INNER JOIN
                                                                        bukapo ON bukapo.idpoproduk = pomitra.idpoproduk
                                                                            INNER JOIN
                                                                        ongkir ON ongkir.invoice = pomitra.invoice
                                                                    WHERE
                                                                        pomitra.idpoproduk = '$idpoproduk'
                                                                            AND pomitra.idmitrareseller = '$idmitrareseller'
                                                                    GROUP BY pomitra.invoice
                                                                ");
                                        if ($sql->num_rows > 0) {
                                            while($datapreorder = $sql->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td><?= $datapreorder['tgl'] ?></td>
                                            <td><?= $datapreorder['status'] ?></td>
                                            <td><?= $datapreorder['status_ongkir'] ?></td>
                                            <td>
                                                <a href="datapokonin.php?id=<?= $idpoproduk ?>&invoice=<?= $datapreorder['invoice'] ?>" class="text-decoration-none"><?= $datapreorder['invoice'] ?></a>
                                            </td>
                                            <td class="d-none">
                                                <?php if ($datapreorder['status'] == 'Belum Acc DB') : ?>
                                                    <a href="ubahalamat.php?id=<?= $idpoproduk ?>&invoice=<?= $datapreorder['invoice'] ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i></a>
                                                <?php else : ?>
                                                    <p class="mb-0">Alamat tidak bisa dirubah</p>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php
                                            }
                                        } else {
                                    ?>
                                        <td colspan="5" class="text-center">Tidak ada data invoice</td>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>