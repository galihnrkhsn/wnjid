<?php
    session_start();

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $invoice    = $_GET['invoice'];
    $pack       = $_GET['pack'];
    $idadmin    = $_SESSION["idadmin"];

    $query_po = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = '$idpoproduk'");
    $sql = $query_po->fetch_assoc();
    $namapo = $sql['namapo'];

    $data_pack = $pack;
    $data_pack = str_replace('-', ' ', $data_pack);
    $data_pack = ucwords($data_pack);

    $inner = explode('-', $pack);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wanoja | Form Update PO Inner</title>

    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <!-- Icons Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navbar Start -->
    <nav class="navbar bg-body-secondary">
        <div class="container">
            <a class="navbar-brand" href="datapoinner2.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>"><i class="bi bi-chevron-left"></i></a>
            <span class="fw-bold text-uppercase fs-6">
                Pre Order
            </span>
            <span></span>
        </div>
    </nav>
    <!-- Navbar End -->

    <div class="container mt-3">
        <div class="text-center mb-3">
            <h5>Formulir Update <?= $invoice ?></h5>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <form method="post" class="">
                    <div class="row">
                        <?php
                            // if ($inner[3] === "beanie") {
                            //     $sql_inner = $koneksi->query("SELECT 
                            //                                             *
                            //                                         FROM
                            //                                             podetail
                            //                                                 INNER JOIN
                            //                                             pokategori ON pokategori.idpo = podetail.idpo
                            //                                                 INNER JOIN
                            //                                             pomitra ON pomitra.idpodetail = podetail.idpodetail
                            //                                         WHERE
                            //                                             pokategori.idpoproduk = '$idpoproduk'
                            //                                                 AND podetail.variant LIKE '%Inner Knit%'
                            //                                                 AND pomitra.invoice = '$invoice'
                            //                                                 AND pomitra.custom LIKE '%$inner[2] $inner[3]%'
                            //                                 ");
                            // } elseif ($inner[3] === "bandana") {
                                $sql_inner = $koneksi->query("SELECT 
                                                                        *
                                                                    FROM
                                                                        podetail
                                                                            INNER JOIN
                                                                        pokategori ON pokategori.idpo = podetail.idpo
                                                                            INNER JOIN
                                                                        pomitra ON pomitra.idpodetail = podetail.idpodetail
                                                                    WHERE
                                                                        pokategori.idpoproduk = '$idpoproduk'
                                                                            AND podetail.variant LIKE '%Inner%'
                                                                            AND pomitra.invoice = '$invoice'
                                                                            AND pomitra.custom LIKE '%$inner[2] $inner[3]%'
                                                            ");
                            // }
                            $no = 1;
                            while ($data_inner = $sql_inner->fetch_assoc()) {
                        ?>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="form-label mb-0">Variant Ke <?= $no++ ?></label>
                                    <input type="hidden" class="form-control form-control-sm bg-body-secondary" name="idpomitra[]" value="<?= $data_inner['idpomitra'] ?>" readonly>
                                    <select class="form-select form-select-sm" name="data[]">
                                        <option value="<?= $data_inner['idpodetail'] ?> | <?= $data_inner['idpo'] ?> | <?= $data_inner['variant'] ?>" selected><?= $data_inner['variant'] ?> (Selected)</option>
                                        <optgroup label="~">
                                            <?php
                                                $i = $koneksi->query("SELECT * FROM podetail INNER JOIN pokategori ON podetail.idpo = pokategori.idpo WHERE pokategori.idpoproduk = '$idpoproduk'");
                                                while ($j = $i->fetch_assoc()) {
                                            ?>
                                                <option value="<?= $j['idpodetail'] ?> | <?= $j['idpo'] ?>"><?= $j['variant'] ?></option>
                                            <?php } ?>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        <?php
                                $pack = $data_inner['jumlah']; 
                                $custom = $data_inner['custom'];
                                $harga = $data_inner['harga'];
                            }
                        ?>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="form-label mb-0">Pack</label>
                                <input type="hidden" class="form-control form-control-sm bg-body-secondary" name="custom" value="<?= $custom ?>" readonly>
                                <input type="hidden" class="form-control form-control-sm bg-body-secondary" name="harga" value="<?= $harga ?>" readonly>
                                <input type="number" class="form-control form-control-sm" name="qty" value="<?= $pack ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-sm mt-3" name="ubah_data">Edit Data</button>
                </form>
            </div>
        </div>
    </div>

    <?php
        if (isset($_POST['ubah_data'])) {
            try {
                date_default_timezone_set('Asia/Jakarta');
                $today = date('s');
                $waktu = date('H:i:s');
                $idpomitra = $_POST['idpomitra'];
                $custom = $_POST['custom'];
                $qty = $_POST['qty'];
                $total_harga = $_POST['harga'];
                $data = $_POST['data'];

                $count = count($idpomitra);
                $inner = explode(' ', $custom);

                if ($inner[3] === "Beanie") {
                    $sstok = $koneksi->query("SELECT SUM(jumlah) AS total_qty FROM pomitra WHERE idpoproduk = '$idpoproduk' AND custom LIKE '%$inner[3]%'");
                    $data_stok = $sstok->fetch_assoc();
                } elseif ($inner[3] === "Bandana") {
                    $sstok = $koneksi->query("SELECT SUM(jumlah) AS total_qty FROM pomitra WHERE idpoproduk = '$idpoproduk' AND custom LIKE '%$inner[3]%'");
                    $data_stok = $sstok->fetch_assoc();
                }

                $tbeli = ($qty * 3) + $data_stok['total_qty'];
                $perpack = $tbeli / 3;
                for ($x = 0; $x < $count; $x++) {
                    $data_barang = explode(" | ", $data[$x]);
                    $idpodetail = $data_barang[0];
                    $idpo = $data_barang[1];
                    $idpomitra_item = $idpomitra[$x];
                    $total_harga = $qty * $harga;

                    $sql = $koneksi->query("UPDATE pomitra SET idpodetail = '$idpodetail', idpo = '$idpo', jumlah = '$qty', total = '$total_harga' WHERE idpomitra = '$idpomitra_item'");

                    if ($sql) {
                        echo "
                            <script>
                                alert('Data berhasil diupdate!');
                                location='datapoinner2.php?id=$idpoproduk&invoice=$invoice';
                            </script>
                        ";
                    } else {
                        echo "
                            <script>
                                alert('Data gagal diupdate!');
                                location='ubahpoinner.php?id=$idpoproduk&invoice=$invoice&pack=$pack';
                            </script>
                        ";
                    }
                }
                // if ($perpack <= 650) {
                //     for ($x = 0; $x < $count; $x++) {
                //         $data_barang = explode(" | ", $data[$x]);
                //         $idpodetail = $data_barang[0];
                //         $idpo = $data_barang[1];
                //         $idpomitra_item = $idpomitra[$x];
                //         $total_harga = $qty * $harga;
    
                //         $sql = $koneksi->query("UPDATE pomitra SET idpodetail = '$idpodetail', idpo = '$idpo', jumlah = '$qty', total = '$total_harga' WHERE idpomitra = '$idpomitra_item'");
    
                //         if ($sql) {
                //             echo "
                //                 <script>
                //                     alert('Data berhasil diupdate!');
                //                     location='datapoinner2.php?id=$idpoproduk&invoice=$invoice';
                //                 </script>
                //             ";
                //         } else {
                //             echo "
                //                 <script>
                //                     alert('Data gagal diupdate!');
                //                     location='ubahpoinner.php?id=$idpoproduk&invoice=$invoice&pack=$pack';
                //                 </script>
                //             ";
                //         }
                //     }
                // } else {
                //     echo "Stock tidak tersedia!";
                // }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    ?>
    <!-- JavaScript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
</body>
</html>