<?php
    session_start();

    include 'koneksi.php';
    include 'assets/components/Sessions/sesAgen.php';
    include 'settingdatatables.php';

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
                
                <?php if ($jenis_po === "Set") :  ?>
                    <div class="mb-3">
                        <p class="fw-semibold h6 text-center">Form <?= $jenis_po ?> <?= $namapo; ?></p>
                    </div>

                    <div class="mb-2 bg-danger-subtle px-3 py-2 mx-2 rounded" role="alert">
                        <p class="m-0 fw-semibold" style="font-size: small">Ini Pre-Order Set, barang yang di pilih harus kelipatan 3!</p>
                    </div>
                <?php else : ?>
                    <div class="mb-3">
                        <p class="fw-semibold h6 text-center">Form <?= $jenis_po ?> <?= $namapo; ?></p>
                    </div>
                <?php endif; ?>

                <form method="post" class="container">
                    <div class="row">
                        <?php if ($idmitraagen == 989) : ?>
                            <div class="col-sm-12">
                                <div class="form-group my-1">
                                    <label>Nama Agen</label>
                                    <select class="form-select form-select-sm" name="iddb" required>
                                        <option value="">~ Default Selected ~</option>
                                    <?php
                                        $query = $koneksi->query("SELECT * FROM mitraagen ORDER BY namaagen ASC");
                                        while ($data = $query->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data['idmitraagen'] ?>"><?= $data['namaagen'] ?> (<?= $data['idmitraagen'] ?>)</option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php
                            $query = $koneksi->query("SELECT 
                                                            podetail.variant,
                                                            podetail.idpodetail
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
                                    <label for="<?= $data['variant'] ?>"><?= $data['variant'] ?></label>
                                    <input type="hidden" name="idpodetail[]" class="form-control form-control-sm" min="0" value="<?= $data['idpodetail'] ?>">
                                    <input type="number" name="qty[]" class="form-control form-control-sm" min="0" value="0">
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
                $today = date('s');
                $waktu = date('H:i:s');
                $idpodetail = $_POST['idpodetail'];
                $jumlah = $_POST['qty'];

                if ($idmitraagen == 989) {
                    $iddb = $_POST['iddb'];
                } else {
                    $iddb = $_SESSION['idmitraagen'];
                }

                if (isset($_GET['invoice'])) {
                    $invoice = $_GET['invoice'];
                } else {
                    if ($jenis_po === "Set") {
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
                    } else {
                        $invoice = 'A' . $idpoproduk . '-' . $iddb;
                    }
                }

                $total_item = array_sum($jumlah);
                $alreadyExecuted = false;

                $query_check = $koneksi->query("SELECT COUNT(*) AS jumlah_invoice FROM pomitra WHERE invoice = '$invoice' AND idmitraagen = '$iddb'");
                $check = $query_check->fetch_assoc();

                for ($x = 0; $x < count($jumlah); $x++) {
                    $idpodetail_item = $idpodetail[$x];

                    $query = $koneksi->query("SELECT * FROM podetail WHERE idpodetail = '$idpodetail_item'");
                    $data = $query->fetch_assoc();
                    $idpo_item = $data['idpo'];
                    $harga_item = $data['harga'];
                    $variant = $data['variant'];

                    if ($jenis_po === "Set") {
                        $total = $jumlah[$x] * 100000;
                        if ($total_item % 3 == 0) {
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
                            echo "
                                <script>
                                    alert('Data berhasil di kirim!');
                                    location='datapocustom2.php?id=$idpoproduk&invoice=$invoice';
                                </script>
                            ";
                            $alreadyExecuted = true;
                        } else {
                            echo "
                                <script>
                                    alert('Qty yang di kirimkan bukan kelipatan 3!');
                                    location='formpocustom.php?id=$idpoproduk&jenis=$jenis_po';
                                </script>
                            ";
                            break;
                        }
                    } else {
                        $total = $jumlah[$x] * $harga_item;
                        if ($check['jumlah_invoice'] < 1) {
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
                            echo "
                                <script>
                                    alert('Data berhasil di kirim!');
                                    location='datapocustom2.php?id=$idpoproduk&invoice=$invoice';
                                </script>
                            ";
                        } else {
                            echo "
                                <script>
                                    alert('Invoice sudah ada!');
                                    location='formpocustom.php?id=$idpoproduk&invoice=$invoice&jenis=$jenis_po';
                                </script>
                            ";
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