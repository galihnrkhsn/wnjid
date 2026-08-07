<?php
    session_start();

    include 'koneksi.php';
    include 'assets/components/Sessions/sesReseller.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $invoice = $_GET['invoice'];
    $idmitrareseller = $_SESSION["idmitrareseller"];
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
                    <p class="m-0 fw-semibold" style="font-size: small">Ini Ubah Pre-Order Set, barang yang di pilih harus kelipatan 3!</p>
                </div>

                <form method="post" class="container">
                    <div class="row">
                        <?php
                            $query = $koneksi->query("SELECT 
                                                            podetail.variant,
                                                            podetail.idpodetail,
                                                            pomitra.idpomitra,
                                                            pomitra.invoice,
                                                            pomitra.jumlah
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
                                    <label for="<?= $data['variant'] ?>"><?= $data['variant'] ?></label>
                                    <input type="hidden" name="idpodetail[]" class="form-control form-control-sm" min="0" value="<?= $data['idpodetail'] ?>">
                                    <input type="hidden" name="idpomitra[]" class="form-control form-control-sm" min="0" value="<?= $data['idpomitra'] ?>">
                                    <input type="number" name="qty[]" class="form-control form-control-sm" min="0" value="<?= $data['jumlah'] ?>">
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
                $today = date('s');
                $waktu = date('H:i:s');
                $idpodetail = $_POST['idpodetail'];
                $idpomitra = $_POST['idpomitra'];

                $invoice = $_GET['invoice'];
                for ($x = 0; $x < count($idpodetail); $x++) {
                    $qty = $_POST['qty'][$x];
                    $idpodetail_item = $idpodetail[$x];
                    $idpomitra_item = $idpomitra[$x];
                    $harga_total = $qty * 100000;

                    if ($total_item % 3 == 0) {
                        $sql = $koneksi->query("UPDATE pomitra SET jumlah = '$qty', total = '$harga_total' WHERE idpomitra = '$idpomitra_item' AND idpodetail = '$idpodetail_item'");
                        echo "
                            <script>
                                alert('Data berhasil diubah!');
                                location='datapocustom2.php?id=$idpoproduk&invoice=$invoice';
                            </script>
                        ";
                    } else {
                        echo "
                            <script>
                                alert('Data total yang diubah bukan kelipatan 3!');
                                location='ubahpocustom.php?id=$idpoproduk&invoice=$invoice&jenis=$jenis_po';
                            </script>
                        ";
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