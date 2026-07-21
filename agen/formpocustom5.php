<?php
    session_start();
    error_reporting (0);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesAgen.php';

    $idpoproduk     = $_GET['id'];
    $invoice        = $_GET['invoice'];
    $idmitraagen    = $_SESSION["idmitraagen"];
    $jenis_po       = $_GET['jenis'];

    
    $query_po       = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = '$idpoproduk'");
    $sql            = $query_po->fetch_assoc();
    $namapo         = $sql['namapo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form <?= $namapo ?> | Wanoja</title>

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
                
                <?php if ($jenis_po === "Bundling") :  ?>
                    <div class="mb-3">
                        <p class="fw-semibold h6 text-center">Form <?= $jenis_po ?> <?= $namapo; ?></p>
                    </div>

                    <div class="mb-2 bg-danger-subtle px-3 py-2 mx-2 rounded" role="alert">
                        <p class="m-0 fw-semibold" style="font-size: small">Ini Pre-Order Bundling, barang yang di pilih harus kelipatan 5!</p>
                    </div>
                <?php else : ?>
                    <div class="mb-3">
                        <p class="fw-semibold h6 text-center">Form <?= $jenis_po ?> <?= $namapo; ?></p>
                    </div>
                <?php endif; ?>

                <form method="post" class="container">
                    <div class="row">
                        <?php
                            $query = $koneksi->query("SELECT 
                                                            podetail.variant,
                                                            podetail.idpodetail,
                                                            pokategori.stok
                                                        FROM
                                                            poproduk
                                                                INNER JOIN
                                                            pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                INNER JOIN
                                                            podetail ON podetail.idpo = pokategori.idpo
                                                        WHERE
                                                            poproduk.idpoproduk = '$idpoproduk'
                                                    ");
                            while ($data = $query->fetch_assoc()) {
                        ?>  
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group my-1">
                                    <label for="<?= $data['variant'] ?>"><?= $data['variant'] ?> (<?= $data['stok'] ?>)</label>
                                    <input type="hidden" name="idpodetail[]" class="form-control form-control-sm" min="0" value="<?= $data['idpodetail'] ?>">
                                    <input type="number" name="qty[]" class="form-control form-control-sm" min="0" value="0" max="<?= $data['stok']; ?>" <?= $data['stok'] == 0 ? 'readonly' : '' ?>>
                                </div>      
                            </div>
                        <?php } ?>
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
                $today      = date('s');
                $waktu      = date('H:i:s');
                $idpodetail = $_POST['idpodetail'];
                $jumlah     = $_POST['qty'];
                $iddb       = $_SESSION['idmitraagen'];

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
                    $invoice = 'A' . $idpoproduk . '-' . $iddb . $angka_acak;
                }

                $total_item         = array_sum($jumlah);
                $alreadyExecuted    = false;

                $query_check    = $koneksi->query("SELECT COUNT(*) AS jumlah_invoice FROM pomitra WHERE idmitraagen = '$iddb' AND custom = '$jenis_po' AND idpoproduk = '$idpoproduk'");
                $check          = $query_check->fetch_assoc();
                if ($check['jumlah_invoice'] > 0) {
                    echo "
                        <script>
                            alert('Invoice sudah ada!');
                            location='formpocustom5.php?id=$idpoproduk&jenis=$jenis_po';
                        </script>
                    ";
                    exit;
                }
                for ($x = 0; $x < count($jumlah); $x++) {
                    $idpodetail_item = $idpodetail[$x];

                    $query      = $koneksi->query("SELECT * FROM podetail INNER JOIN pokategori ON podetail.idpo = pokategori.idpo WHERE idpodetail = '$idpodetail_item'");
                    $data       = $query->fetch_assoc();

                    if ($jumlah[$x] > $data['stok']) {
                        echo "
                            <script>
                                alert('Jumlah melebihi stok tersedia!');
                                location='datapocustom2.php?id=$idpoproduk&invoice=$invoice';
                            </script>                                    
                        ";
                    }
                    $idpo_item  = $data['idpo'];
                    $harga_item = $data['harga'];
                    $variant    = $data['variant'];

                    $idpo_db        = $data['idpo'];
                    if ($jenis_po === "Bundling") {
                        $total = $jumlah[$x] * 100000;
                        if ($total_item % 5 == 0) {
                            $sql = $koneksi->query("INSERT INTO pomitra
                                                    (idpomitra, idmitraagen, idpoproduk,
                                                    idpo, idpodetail, jumlah,
                                                    custom, total, invoice,
                                                    status, tgl, waktu)
                                                VALUES
                                                    (NULL, '$iddb', '$idpoproduk',
                                                        '$idpo_item', '$idpodetail_item', '$jumlah[$x]',
                                                        '$jenis_po', '$total', '$invoice',
                                                        'Belum Acc DB', NOW(), '$waktu')
                                        ");
                            $alreadyExecuted = true;
                        } else {
                            echo "
                                <script>
                                    alert('Qty yang di kirimkan bukan kelipatan 5!');
                                    location='formpocustom5.php?id=$idpoproduk&jenis=$jenis_po';
                                </script>
                            ";
                            break;
                        }
                    } else {
                        $total = $jumlah[$x] * $harga_item;
                        $sql = $koneksi->query("INSERT INTO pomitra
                                                        (idpomitra, idmitraagen, idpoproduk,
                                                        idpo, idpodetail, jumlah,
                                                        custom, total, invoice,
                                                        status, tgl, waktu)
                                                    VALUES
                                                        (NULL, '$iddb', '$idpoproduk',
                                                            '$idpo_item', '$idpodetail_item', '$jumlah[$x]',
                                                            '$jenis_po', '$total', '$invoice',
                                                            'Belum Acc DB', NOW(), '$waktu')
                                            ");
                    }

                    $updateStock    = $koneksi->query("UPDATE pokategori SET stok = stok - $jumlah[$x] WHERE idpo = '$idpo_db'");
                    if (!$updateStock) {
                        echo "
                            <script>
                                alert('Gagal mengurangi stok!');
                                location='datapocustom2.php?id=$idpoproduk&invoice=$invoice';
                            </script>                                    
                        ";
                    }

                    echo "
                        <script>
                            alert('Data berhasil di kirim!');
                            location='datapocustom2.php?id=$idpoproduk&invoice=$invoice';
                        </script>
                    ";
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