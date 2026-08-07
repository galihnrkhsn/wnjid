<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesMarketer.php';
    include "settingdatatables.php";

    $idpoproduk     = $_GET['id'];
    $invoice        = $_GET['invoice'];
    $bundling       = $_GET['bundling'];
    $idmitramarketer = $_SESSION["idmitramarketer"];

    $query_po       = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = '$idpoproduk'");
    $sql            = $query_po->fetch_assoc();
    $namapo         = $sql['namapo'];

    $data_pack = $bundling;
    $data_pack = str_replace('-', ' ', $data_pack);
    $data_pack = ucwords($data_pack);

    $sarung = explode('-', $data_pack);
    $sarung = trim($sarung[0]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WNJ.ID | Form Update PO Inner</title>

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
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?php
                                $data_rows = []; // Array untuk menampung data
                                $getData = $koneksi->query("SELECT * FROM pomitra WHERE invoice = '$invoice' AND custom LIKE '%$sarung%'");

                                // Menyimpan semua data dalam array
                                while ($data = $getData->fetch_assoc()) {
                                    $data_rows[] = $data;
                                    $custom = $data['custom'];
                                    $harga  = $data['total'];
                                }
                                ?>
                                <label class="form-label mb-0">Bundling</label>
                                <input type="hidden" class="form-control form-control-sm bg-body-secondary" name="custom" value="<?= $custom ?>" readonly>
                                <input type="hidden" class="form-control form-control-sm bg-body-secondary" name="harga" value="<?= $harga ?>" readonly>
                                <input type="number" class="form-control form-control-sm" name="qty" required>
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
                $qty = $_POST['qty'];

                // Iterasi melalui setiap baris data untuk memperbarui harga
                foreach ($data_rows as $row) {
                    $idpomitra  = $row['idpomitra'];
                    $harga      = $row['total'] * $qty; // Contoh: Menghitung harga baru berdasarkan qty

                    // Update setiap baris berdasarkan idpomitra
                    $updatePomitra = $koneksi->query("UPDATE pomitra 
                                                        SET jumlah = '$qty', total = '$harga' 
                                                        WHERE idpomitra = '$idpomitra'");

                    if (!$updatePomitra) {
                        throw new Exception("Gagal memperbarui data dengan ID: $idpomitra");
                    }
                }


                if ($idpoproduk == 377) {
                    echo "<script>alert('Semua data berhasil diperbarui');</script>";
                    echo "<script>location='datapobundling2.php?id=$idpoproduk&invoice=$invoice';</script>";
                } else {
                    echo "<script>alert('Semua data berhasil diperbarui');</script>";
                    echo "<script>location='datapobundling.php?id=$idpoproduk&invoice=$invoice';</script>";
                }
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