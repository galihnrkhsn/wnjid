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
    $invoice = $_GET['invoice'];
    $no_ds = $_GET['no_ds'];
    $idadmin = $_SESSION["admin_mitra"]["idadmin"];

    $sql = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = '$idpoproduk'");
    $query = $sql->fetch_assoc();

    $query_ds = $koneksi->query("SELECT podropship.namapengirim, 
                        podropship.tlppengirim, 
                        podropship.namapenerima, 
                        podropship.tlppenerima, 
                        podropship.alamatpenerima,
                        podropship.invoice,
                        podropship.idpoproduk,
                        podropship.iddropship,
                        podropship.no_ds,
                        tb_ro_provinces.province_name as provinsi,
                        tb_ro_cities.city_name as kota,
                        tb_ro_subdistricts.subdistrict_name as kecamatan 
                    FROM podropship 
                    LEFT JOIN tb_ro_provinces on podropship.provinsi = tb_ro_provinces.province_id
                    LEFT JOIN tb_ro_cities on podropship.kota = tb_ro_cities.city_id
                    LEFT JOIN tb_ro_subdistricts on podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
                    WHERE podropship.no_ds='".$no_ds."'
                ");
    $sql_ds = $query_ds->fetch_assoc();
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>WNJ | Edit Drophip</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <style>
            .navbaru {
                background: #eee  url("jumbotron-bg.png") center center;
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
            <div class="col-2"><a href="listds?id=<?= $idpoproduk; ?>&invoice=<?= $invoice; ?>"><i class="bi bi-chevron-left"></i></a></div>
            <div class="col-8"><h3 class="text-uppercase">Dropship</h3></div>
            <div class="col-2"></div>
        </div>

        <div class="container pt-5 my-5">
            <h4><?= $query['namapo'] ?></h4>
            <hr />
            <p class="mb-0 text-secondary"><span class="fw-bold text-dark">Invoice:</span> <?= $no_ds ?></p>

            <div>
                <p class="mb-1 text-secondary"><span class="fw-bold text-dark">Pengirim:</span> <?= $sql_ds['namapengirim'] ?> / <?= $sql_ds['tlppengirim'] ?></p>
                <p class="mb-1 text-secondary"><span class="fw-bold text-dark">Penerima:</span> <?= $sql_ds['namapenerima'] ?> / <?= $sql_ds['tlppenerima'] ?></p>
                <p class="mb-1 text-secondary"><span class="fw-bold text-dark">Alamat Penerima:</span> <?= $sql_ds['alamatpenerima'] ?></p>
            </div>

            <div class="card my-2">
                <div class="card-body">
                    <h5>Variant</h5>
                    <form method="post" class="row col-8">
                        <?php 
                            $ambil = $koneksi->query("SELECT podetail.variant,
                                                                pomitra.jumlah,
                                                                podetail.idpodetail,
                                                                pomitra.custom,
                                                                pomitra.idpomitra
                                                                FROM pomitra
                                                                JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                WHERE pomitra.invoice = '$invoice' 
                                                                AND pomitra.jumlah > 0
                                                                ORDER BY pomitra.idpodetail ASC
                                                            "); 
                            while($data = $ambil->fetch_assoc()) {
                                $id = $data['idpodetail'];
                                $idpomitra = $data['idpomitra'];
                                if ($id == 8920) {
                                    $data_jumlah=$koneksi->query("SELECT SUM(pods.jumlah) AS progresnya 
                                                                            FROM pods
                                                                            JOIN pomitra ON pomitra.idpomitra = pods.idpomitra
                                                                            WHERE pods.invoice = '$invoice'
                                                                            AND pods.idpodetail = '$id'
                                                                            AND pomitra.idpomitra = '$idpomitra'
                                                            ");
                                    $tampilprogres = $data_jumlah->fetch_assoc();                                   
                                    $kurang = $data['jumlah'] - $tampilprogres['progresnya'];
                                } else {
                                    $data_jumlah=$koneksi->query("SELECT SUM(pods.jumlah) AS progresnya 
                                                                            FROM pods
                                                                            WHERE pods.invoice = '$invoice'
                                                                            AND pods.idpodetail = '$id'
                                                                            AND pods.idpomitra = '$idpomitra'
                                                                ");
                                    $tampilprogres = $data_jumlah->fetch_assoc();                                   
                                    $kurang = $data['jumlah'] - $tampilprogres['progresnya'];             
                                }
                        ?> 
                            <div class="col-12 row">
                                <div class="col-10">
                                    <label for="<?= $data['idpodetail'] ?>" class="form-label"><?= $data['variant'] ?></label>
                                    <input type="text" class="form-control" id="<?= $idpomitra ?>" value="<?= $idpomitra ?>" name="idpomitra[]">
                                    <input type="text" class="form-control" id="<?= $id ?>" value="<?= $id ?>" name="idpodetail[]">
                                    <p>PO Mitra ( <?= $data['jumlah'] ?> ) - Pods ( <?= $tampilprogres['progresnya'] ?> )</p>
                                    <?php if($kurang > 0) : ?>
                                        <input type="number" class="form-control" id="<?= $data['idpodetail'] ?>" min="0" value="0" max="<?= $kurang ?>" name="qty[]">
                                    <?php else : ?>
                                        <input type="number" class="form-control" id="<?= $data['idpodetail'] ?>" min="0" value="0" max="<?= $kurang ?>" name="qty[]" disabled>
                                    <?php endif; ?>
                                </div>
                                <div class="col-2">
                                    <label for="<?= $data['idpodetail'] ?>" class="form-label">Stok Invoice</label>
                                    <input class="form-control form-control-disbaled" id="<?= $data['idpodetail'] ?>" value="<?= $kurang ?>" disabled>
                                </div>
                                <div class="col-12">
                                    <hr class="mt-3 mb-2" />
                                </div>
                            </div>
                        <?php } ?>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm" name="kirim">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php
            if (isset($_POST['kirim'])) {
                date_default_timezone_set("Asia/Jakarta");
                $today = date("Y-m-d H:i:s");
                $qty = $_POST['qty'];
                $jumlah = count($_POST['qty']);
                for ($x = 0; $x < $jumlah; $x++) {
                    $idpomitra_item = $_POST['idpomitra'][$x];
                    $idpodetail_item = $_POST['idpodetail'][$x];
                    $qty_item = $qty[$x];
                    if($qty[$x] <> 0) {
                        // echo "Idpodetail: " . $idpodetail_item;
                        $insert_data = $koneksi->query("INSERT INTO pods
                                                            (id, no_ds, invoice,
                                                            idpodetail, idpomitra, jumlah,
                                                            waktu) VALUES
                                                            (NULL, '$no_ds', '$invoice', '$idpodetail_item', '$idpomitra_item', '$qty_item', '$today')
                                                        ");
                        if ($insert_data) {
                            echo "<script>alert('Data berhasil dikirim!')</script>";
                            echo "<script>location='detail_ds.php?no_ds=$no_ds'</script>";
                        } else {
                            echo "<script>alert('Data berhasil dikirim!')</script>";
                            echo "<script>location='formdstambah.php?id=$idpoproduk&invoice =$invoice&no_ds=$no_ds'</script>";
                        }
                    }
                }
            }
        ?>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>