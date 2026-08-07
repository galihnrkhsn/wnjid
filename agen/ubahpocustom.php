<?php
    session_start();

    include 'koneksi.php';
    include 'assets/components/Sessions/sesAgen.php';
    include 'settingdatatables.php';

    $idpoproduk     = $_GET['id'];
    $invoice        = $_GET['invoice'];
    $idmitraagen    = $_SESSION["idmitraagen"];
    $jenis_po       = $_GET['jenis'];

    $query_po   = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = '$idpoproduk'");
    $sql        = $query_po->fetch_assoc();
    $namapo     = $sql['namapo'];
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
                <a class="navbar-brand" href="datapocustom2.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>"><i class="bi bi-chevron-left"></i></a>
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
                    <p class="fw-semibold h5 text-center">Form Ubah <?= $jenis_po ?> <?= $namapo; ?></p>
                    <p class="fw-bold text-secondary text-center"><span class="text-dark">#</span><?= $invoice ?></p>
                </div>

                <div class="mb-2 bg-danger-subtle px-3 py-2 mx-2 rounded" role="alert">
                    <p class="m-0 fw-semibold" style="font-size: small">Ini Ubah Pre-Order Set, barang yang di pilih harus kelipatan 5!</p>
                </div>

                <form method="post" class="container">
                    <div class="row">
                        <?php
                            $query = $koneksi->query("SELECT 
                                                            podetail.variant,
                                                            podetail.idpodetail,
                                                            pomitra.idpomitra,
                                                            pomitra.invoice,
                                                            pomitra.jumlah,
                                                            pokategori.stok
                                                        FROM
                                                            pomitra
                                                                INNER JOIN
                                                            pokategori ON pokategori.idpo = pomitra.idpo
                                                                INNER JOIN
                                                            podetail ON podetail.idpodetail = pomitra.idpodetail
                                                        WHERE
                                                            pomitra.invoice = '$invoice';
                                                    ");
                            while ($data = $query->fetch_assoc()) {
                                $idpomitra = $data['idpomitra'];
                        ?>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group my-1">
                                    <label for="<?= $data['variant'] ?>"><?= $data['variant'] ?> (<?= $data['stok'] ?>)</label>
                                    <input type="hidden" name="idpodetail[]" class="form-control form-control-sm" min="0" value="<?= $data['idpodetail'] ?>">
                                    <input type="hidden" name="idpomitra[]" class="form-control form-control-sm" min="0" value="<?= $data['idpomitra'] ?>">
                                    <input type="number" name="qty[]" class="form-control form-control-sm" min="0" value="<?= $data['jumlah'] ?>" max="<?= $data['stok']; ?>" <?= $data['stok'] == 0 ? 'readonly' : '' ?>>
                                </div>      
                            </div>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 my-2">
                            <button type="submit" class="btn btn-sm btn-success" name="kirim">
                                Ubah Data
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
                $today      = date('s');
                $waktu      = date('H:i:s');

                $idpodetail = $_POST['idpodetail'];
                $idpomitra  = $_POST['idpomitra'];
                $qty_inputs = $_POST['qty'];

                if (!$koneksi) {
                    throw new Exception("Koneksi database belum diinisialisasi.");
                }

                if (isset($_GET['invoice'])) {
                    $invoice = $_GET['invoice'];
                } else {
                    function generateAngkaAcak($length) {
                        $angka_acak = '';
                        for ($i = 0; $i < $length; $i++) {
                            $angka_acak .= mt_rand(0, 9);
                        }
                        return $angka_acak;
                    }
                    $angka_acak = generateAngkaAcak(5);
                    $invoice = 'A' . $idpoproduk . '-' . $idadmin . $angka_acak;
                }

                // Hitung total_item (jumlah semua qty baru > 0)
                $total_item = 0;
                for ($i = 0; $i < count($qty_inputs); $i++) {
                    $qty_item = (int)$qty_inputs[$i];
                    if ($qty_item > 0) {
                        $total_item += $qty_item;
                    }
                }

                if ($total_item % 5 !== 0) {
                    echo "
                        <script>
                            alert('Total qty yang dikirim harus kelipatan 5!');
                            location='ubahpocustom.php?id=$idpoproduk&invoice=$invoice&jenis=$jenis_po';
                        </script>
                    ";
                    exit;
                }

                $koneksi->begin_transaction();

                for ($x = 0; $x < count($idpodetail); $x++) {
                    $qty_baru           = (int)$qty_inputs[$x];
                    $idpodetail_item    = $koneksi->real_escape_string($idpodetail[$x]);
                    $idpomitra_item     = $koneksi->real_escape_string($idpomitra[$x]);

                    $q = $koneksi->query("
                        SELECT podetail.*, pokategori.stok, pokategori.idpo AS idpo_db
                        FROM podetail
                        INNER JOIN pokategori ON podetail.idpo = pokategori.idpo
                        WHERE podetail.idpodetail = '$idpodetail_item'
                        LIMIT 1
                    ");

                    if (!$q) {
                        $koneksi->rollback();
                        throw new Exception("Query podetail gagal: " . $koneksi->error);
                    }

                    $data = $q->fetch_assoc();

                    if (!$data) {
                        $koneksi->rollback();
                        throw new Exception("Data podetail tidak ditemukan untuk idpodetail = $idpodetail_item");
                    }

                    $stok_db = (int)$data['stok'];
                    $idpo_db = $koneksi->real_escape_string($data['idpo_db']);
                    $harga_total = $qty_baru * 100000;

                    $q2 = $koneksi->query("
                        SELECT jumlah 
                        FROM pomitra 
                        WHERE idpomitra = '$idpomitra_item' 
                        AND idpodetail = '$idpodetail_item'
                        LIMIT 1
                    ");

                    if (!$q2) {
                        $koneksi->rollback();
                        throw new Exception("Query pomitra gagal: " . $koneksi->error);
                    }

                    $row_pomitra = $q2->fetch_assoc();
                    $qty_lama = $row_pomitra ? (int)$row_pomitra['jumlah'] : 0;

                    if ($qty_baru === $qty_lama) {
                        continue;
                    }

                    $selisih = $qty_baru - $qty_lama;

                    if ($selisih > 0) {
                        if ($selisih > $stok_db) {
                            $koneksi->rollback();
                            echo "
                                <script>
                                    alert('Qty bertambah melebihi stok tersedia untuk variant: {$data['variant']}');
                                    location='datapocustom2.php?id=$idpoproduk&invoice=$invoice';
                                </script>
                            ";
                            exit;
                        }
                    }

                    if ($row_pomitra) {
                        $upd = $koneksi->query("
                            UPDATE pomitra
                            SET jumlah = $qty_baru, total = $harga_total
                            WHERE idpomitra = '$idpomitra_item' AND idpodetail = '$idpodetail_item'
                        ");
                        if (!$upd) {
                            $koneksi->rollback();
                            throw new Exception("Gagal update pomitra: " . $koneksi->error);
                        }
                    } else {
                        if ($qty_baru > 0) {
                            $ins = $koneksi->query("
                                INSERT INTO pomitra (idpomitra, idmitraagen, idpoproduk, idpo, idpodetail, jumlah, custom, total, invoice, status, tgl, waktu)
                                VALUES (NULL, '$iddb', '$idpoproduk', '{$data['idpo_db']}', '$idpodetail_item', $qty_baru, '$jenis_po', $harga_total, '$invoice', 'Belum Acc DB', NOW(), '$waktu')
                            ");
                            if (!$ins) {
                                $koneksi->rollback();
                                throw new Exception("Gagal insert pomitra: " . $koneksi->error);
                            }
                        } else {
                            // qty_baru == 0 dan record tidak ada -> nothing to do
                        }
                    }
                    if ($selisih > 0) {
                        $updStok = $koneksi->query("UPDATE pokategori SET stok = stok - $selisih WHERE idpo = '$idpo_db'");
                    } else {
                        $plus = abs($selisih);
                        $updStok = $koneksi->query("UPDATE pokategori SET stok = stok + $plus WHERE idpo = '$idpo_db'");
                    }

                    if (!$updStok) {
                        $koneksi->rollback();
                        throw new Exception("Gagal update stok pokategori: " . $koneksi->error);
                    }
                }

                $koneksi->commit();

                echo "
                    <script>
                        alert('Data berhasil diubah!');
                        location='datapocustom2.php?id=$idpoproduk&invoice=$invoice';
                    </script>
                ";
                exit;

            } catch (Exception $e) {
                if (isset($koneksi) && $koneksi->connect_errno == 0) {
                    $koneksi->rollback();
                }

                echo "Error: " . $e->getMessage();
                exit;
            }

        }
    ?>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>