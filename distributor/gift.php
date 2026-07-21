<?php
    session_start();
    include "koneksi.php";
    if(!isset($_SESSION["admin_mitra"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login2.php';</script>";
        header('location:login2.php');
        exit();
    }

    $idpoproduk = $_GET['id'];
    $qty = $_GET['qty'];
    $idadmin = $_SESSION["admin_mitra"]["idadmin"];
    $query = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = $idpoproduk");
    $sql = $query->fetch_assoc();

    $db = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin = $idadmin");
    $user = $db->fetch_assoc();
    $namamitra = $user['namamitra'];

    $max = floor($qty / 3);
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mitra WNJ | <?= $namamitra ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <style>
            /* Place the navbar at the bottom of the page, and make it stick */
            .navbaru {
                background-color: #eee;
                margin: auto;
                text-align: center;
                overflow: hidden;
                font-size: 20px;
                font-weight: 600;
            }

            .navbaru p {
                color: #0f0f0a;
                text-align: center;
            }

            .navbaru i {
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
        <div class="row fixed-top navbaru py-2">
            <div class="col-2"><a href="listnewpo.php"><i class="bi bi-chevron-left"></i></a></div>
            <div class="col-8" ><p class="mb-0">PRE ORDER</p></div>
            <div class="col-2"></div>
        </div>

        <div class="container my-5 py-4">
            <h5 class="text-center"><?= $sql['namapo'] ?></h5>
            <div class="card py-4 px-3">
                <form method="post" class="row">
                    <?php
                        $query_produk = $koneksi->query("SELECT * FROM poproduk
                                                        INNER JOIN pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                        INNER JOIN podetail ON podetail.idpo = pokategori.idpo
                                                        WHERE poproduk.idpoproduk = $idpoproduk
                                                        ORDER BY podetail.idpodetail ASC
                                                    ");
                        while ($item = $query_produk->fetch_assoc()) {
                    ?>
                        <div class="col-md-3">
                            <span>
                                <p><b><?= $item['variant'] ?></b> | <?= $item['stok'] ?></p>
                            </span>
                        </div>
                    <?php } ?>

                    <div class="col-12">
                        <hr />
                    </div>
                    <div class="col-lg-5">
                        <p class="fw-semibold"><span class="text-danger">* </span>Hadiah yang di dapat adalah <?= $max; ?></p>
                        <?php
                            $query_produk = $koneksi->query("SELECT * FROM poproduk
                                                            INNER JOIN pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                            INNER JOIN podetail ON podetail.idpo = pokategori.idpo
                                                            WHERE poproduk.idpoproduk = $idpoproduk
                                                            AND podetail.idpodetail != '13558'
                                                            ORDER BY podetail.idpodetail ASC
                                                        ");
                            while ($item = $query_produk->fetch_assoc()) {
                        ?>
                            <div class="form-group mb-2">
                                <label for="<?= $item['idpodetail'] ?>"><?= $item['variant'] ?></label>
                                <input type="number" name="qty[]" id="<?= $item['idpodetail'] ?>" value="0" min="0" max="<?= $max ?>" class="form-control form-control-sm">
                                <input type="hidden" name="idpodetail[]" value="<?= $item['idpodetail'] ?>" class="form-control form-control-sm">
                                <input type="hidden" name="idpo[]" value="<?= $item['idpo'] ?>" class="form-control form-control-sm">
                                <input type="hidden" name="harga[]" value="<?= $item['harga'] ?>" class="form-control form-control-sm">
                                <input type="hidden" name="berat[]" value="<?= $item['berat'] ?>" class="form-control form-control-sm">
                                <input type="hidden" name="custom[]" value="1" class="form-control form-control-sm">
                            </div>
                        <?php } ?>
                    </div>

                    <div class="col-12">
                        <button type="submit" name="kirim" class="btn btn-sm btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
        </div>

        <?php
            if(isset($_POST['kirim'])) {
                include "koneksi.php";
                date_default_timezone_set('Asia/Jakarta');
                $today = date("s");
                $waktu = date("H:i:s");
                $jumlah = $_POST['qty'];
                $idpodetail = $_POST['idpodetail'];
                $idpo = $_POST['idpo'];
                $harga = $_POST['harga'];
                $berat = $_POST['berat'];
                $invoice = $_GET['invoice'];
                $custom = $_POST['custom'];

                $count = count($jumlah);

                for ($x = 0; $x < $count; $x++) {
                    if ($jumlah[$x] > 0) {
                        $total_harga = $jumlah[$x] * $harga[$x];
                        $sql_check = $koneksi->query("SELECT * FROM pokategori WHERE idpo = $idpo[$x]");
                        $query_check = $sql_check->fetch_assoc();
    
                        if ($query_check['stok'] >= $jumlah[$x] ) {
                            $stock = $koneksi->query("UPDATE pokategori SET stok = stok - $jumlah[$x] WHERE idpo = $idpo[$x]");
                            $insert = $koneksi->query("INSERT INTO pomitra (idpomitra, idmitra, idpoproduk,
                                                                            idpo, idpodetail, jumlah,
                                                                            custom, total, invoice,
                                                                            status, tgl, waktu)
                                                                VALUES (NULL, '$idadmin', '$idpoproduk',
                                                                        '$idpo[$x]', '$idpodetail[$x]', '$jumlah[$x]', '$custom[$x]',
                                                                        '$total_harga', '$invoice', 'Belum DP', NOW(), '$waktu')
                                                    ");
                        } else {
                            echo "<script>alert('Stok tidak mencukupi!');</script>";
                            echo "<script>location='formpovoal_custom.php?id=$idpoproduk';</script>";
                        }
                    }
                }

                if ($stock && $insert) {
                    echo "<script>alert('Data berhasil dikirim');</script>";
                    echo "<script>location='datapovoal.php?id=$idpoproduk&invoice=$invoice';</script>";
                } else {
                    echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
                    echo "<script>location='formpovoal_custom.php?id=$idpoproduk';</script>";
                }
                // var_dump($count);
                // die();
            }
        ?>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>