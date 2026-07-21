<?php
    session_start();

    include 'koneksi.php';

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
        echo "
            <script>alert('Anda harus login terlebih dahulu!');</script>
            <script>location='login-multi.php';</script>
        ";
        header("Location: login-multi.php");
        exit();
    }

    $id         = $_SESSION['user_id'];
    $role       = $_SESSION['user_level'];
    $idpoproduk = $_GET['id'];

    $query      = $koneksi->query("SELECT * FROM user_manajemen INNER JOIN role WHERE user_manajemen.id = '$id'");
    $user       = $query->fetch_assoc();
    $username   = $user['username'];

    $poproduk   = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = '$idpoproduk'");
    $datapo     = $poproduk->fetch_assoc();
    $namapo     = $datapo['namapo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wanoja | <?= $username ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+5hb7ie2TVuGdxH5tk36y3PBo/zKMpQ2dtnb9Xg" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-RdQdUI6YgeTYOPzN/v53qu3Zk5S8dHf73GAdEP+EeFwkw4FEcGITeJEmG70T1NrM" crossorigin="anonymous"></script>
</head>
<body>

    <div id="main-wrapper">
        <!--**********************************
            Nav header start
        ***********************************-->
        <? include "assets/components/Navbar/navbar.php"; ?>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="card shadow-sm">
            <div class="card-body">
                <a href="index.php" class="btn btn-outline-secondary mb-5">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="vendor.php" class="btn btn-outline-secondary mb-5">
                    <i class="fas fa-plus"></i> Tambah Vendor
                </a>
                <br>
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" id="date" class="form-control" name="date" required>
                    </div>

                    <div class="mb-3">
                        <label for="vendor" class="form-label">Vendor</label>
                        <select class="form-select" name="vendor" id="vendor" required>
                            <option value="" disabled selected>Pilih Vendor</option>
                            <?php
                            $sql_vendor = $koneksi->query("SELECT * FROM vendor");
                            while ($vendor = $sql_vendor->fetch_assoc()) {
                            ?>
                                <option value="<?= $vendor['id']; ?>"><?= $vendor['vendor'] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="bukti" class="form-label">Bukti</label>
                        <input type="file" class="form-control" id="bukti" name="bukti">
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Variant Produk</h6>
                        <div class="row g-3">
                            <?php
                            $sql_produk = $koneksi->query("SELECT 
                                                            *
                                                        FROM
                                                            poproduk
                                                                JOIN
                                                            pokategori
                                                                JOIN
                                                            podetail ON poproduk.idpoproduk = pokategori.idpoproduk
                                                                AND pokategori.idpo = podetail.idpo
                                                        WHERE
                                                            poproduk.idpoproduk = '$idpoproduk'
                                                            AND podetail.variant NOT LIKE '%Custom%'
                                                        ORDER BY podetail.idpodetail ASC
                                                    ");
                            while ($produk = $sql_produk->fetch_assoc()) {
                                $d = $produk['variant'];
                                $variant = str_replace("Polos", "", $d);
                            ?>
                                <div class="col-md-4">
                                    <label for="<?= $produk['variant'] ?>" class="form-label">
                                        <?= ($idpoproduk == '326' || $idpoproduk == '315') ? $variant : $produk['variant'] ?>
                                    </label>
                                    <input type="hidden" name="idpodetail[]" value="<?= $produk['idpodetail'] ?>">
                                    <input type="number" class="form-control" min="0" name="qty[]" value="0" id="<?= $produk['variant'] ?>" required>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="text-end">
                        <button class="btn btn-primary" type="submit" name="kirim">Kirim</button>
                    </div>
                </form>
            </div>
        </div>

        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a class="text-success">WNJ.ID</a> 2024</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->
    </div>

    <!--**********************************
        Scripts
    ***********************************-->

    <?php
        if (isset($_POST['kirim'])) {
            try {
                date_default_timezone_set('Asia/Jakarta');
                $today          = date('Y-m-d');
                $waktu          = date('H:i:s');
                $w              = date('Hid');
                $t              = date('Ymd');
                $image          = $_FILES['bukti']['name'];
                $tmp            = $_FILES['bukti']['tmp_name'];
                $size           = $_FILES['bukti']['size'];

                $tanggal        = $_POST['date'];
                $vendor         = $_POST['vendor'];
                $idpodetail     = $_POST['idpodetail'];
                $jumlah         = $_POST['qty'];
                $jumlah_dipilih = count($jumlah);

                if ($image <> '') {
                    $ekstensiGambarValid = ['jpg','jpeg','png','svg'];
                    $ekstensiGambar = explode('.', $image);
                    $ekstensiGambar = strtolower(end($ekstensiGambar));
                    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
                        echo "<script>alert('The file you uploaded is not an image!');</script>";
                        echo "<script>location='form_saldo.php';</script>";
                        return false;
                    }
                    $nama_file = 'SJV' . $t . $w . $id;
                    $nama_file .= '.';
                    $nama_file .= $ekstensiGambar;
                }

                if (move_uploaded_file($tmp, 'buktivendor/' . $nama_file)) {
                    $sql_sjk    = $koneksi->query("SELECT MAX(no_sjk) AS no_sjk FROM sjk WHERE idpoproduk = '$idpoproduk' LIMIT 1");
                    $data       = $sql_sjk->fetch_assoc();
                    $no_sjk     = $data['no_sjk'] + 1;
                    $sjk        = 'SJK-' . $idpoproduk . '-' . $no_sjk;

                    for ($x = 0; $x < $jumlah_dipilih; $x++) {
                        if ($jumlah[$x] > 0) {
                            $sql = $koneksi->query("INSERT INTO sjk VALUES (NULL, '$no_sjk', '$sjk', '$tanggal', '$vendor', '$idpoproduk', '$idpodetail[$x]', '$jumlah[$x]', '$jumlah[$x]', '0', NOW(), NOW(), '$nama_file')");
                        }
                    }

                    if ($sql) {
                        echo "
                            <script>
                                alert('Berhasil menambahkan data!')
                                location='input_sjv.php?id=$idpoproduk'
                            </script>
                        ";
                    } else {
                        echo "
                            <script>
                                alert('Gagal menambahkan data!')
                                location='index.php?id=$idpoproduk'
                            </script>
                        ";
                    }
                } else {
                    echo "
                        <script>
                            alert('Gagal pada saat memindahkan file!')
                            location='index.php'
                        </script>
                    ";
                }
            } catch (Exception $e) {
                echo "
                    <script>
                        alert('SJV gagal ditambahkan!')
                        location='home.php'
                    </script>
                ";
            }
        }
    ?>
</body>
</html>