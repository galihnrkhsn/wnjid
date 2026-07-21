<?php 
    session_start();
    include '../../includes/db.php'; 
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
        echo "
            <script>alert('Anda harus login terlebih dahulu!');</script>
            <script>location='login-multi.php';</script>
        ";
        header("Location: login-multi.php");
        exit();
    }

    $tipe           = $_GET['tipe'];
    $id             = $_SESSION['user_id'];
    $role           = $_SESSION['user_level'];

    $queryManage    = $koneksi->query("SELECT *, role.id as id_role FROM user_manajemen INNER JOIN role ON user_manajemen.id_role = role.id WHERE user_manajemen.id = '$id'");
    $data           = $queryManage->fetch_assoc();
    $iduser         = $data['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= $role ?> | Wanoja</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>

</head> 
<body>
    <!-- NAVBAR -->
    <? include "../assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->
    <div class="container mt-4">
        <a href="../index.php" class="btn btn-info float-right"><i class="fa fa-arrow-left"></i> Kembali</a>
        <h4><i class="fa fa-minus"></i> Kredit</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required/>
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan Keterangan" required/>
            </div>
            <div class="form-group">
                <label for="kredit">Kredit</label>
                <input type="number" min="0" class="form-control" id="kredit" name="kredit" placeholder="Masukkan Total Kredit" required/>
            </div>
            <div class="form-group">
                <label for="kategoridebit">Kategori</label>
                <select name="kategoridebit" id="kategoridebit" class="form-control">
                    <option value="" disabled selected>~~ Pilih Kategori ~~</option>
                    <?php
                        $getKate = $koneksi->query("SELECT * FROM kategori_manajemen WHERE tipe = 'kredit'");
                        while ($data = $getKate->fetch_assoc()) {
                    ?>
                    <option value="<?= $data['idkategori'] ?>"><?= $data['nama_kategori'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group" id="extraSelectContainer" style="display: none;">
                <label for="extraSelect">Pilih Bank</label>
                <select name="extraSelect" id="extraSelect" class="form-control">
                    <option value="" disabled selected>~~ Pilih Sub Kategori ~~</option>
                    <option value="P">Produksi</option>
                    <option value="AF">Admin Finance</option>
                    <option value="M">Manajemen</option>
                </select>
            </div>

            <div class="form-group">
                <label for="foto">Nota</label>
                <br>
                <input type="file" name="foto" id="foto" required>
            </div>

            <button type="submit" class="btn btn-primary" name="kirimdebit">KIRIM</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const kategoriSelect = document.getElementById('kategoridebit');
            const extraSelectContainer = document.getElementById('extraSelectContainer');

            kategoriSelect.addEventListener('change', function () {
                const selectedValue = kategoriSelect.value;
                if (selectedValue == "7") {
                    extraSelectContainer.style.display = 'block';
                } else {
                    extraSelectContainer.style.display = 'none';
                }
            });
        });
    </script>
    <?php 
        if (isset($_POST["kirimdebit"])) {
            date_default_timezone_set('Asia/Jakarta');
            $waktu          = date('H:i:s');
            $tanggal        = $_POST["tanggal"];
            $keterangan     = addslashes(htmlspecialchars($_POST["keterangan"]));
            $kategori       = $_POST['kategoridebit'];
            $kredit         = $_POST["kredit"];
            $pindahBank     = $_POST["extraSelect"];
            $sisa           = $distributor2['sisa'] - $kredit;

            $foto           = $_FILES['foto']['name'];
            $tmp            = $_FILES['foto']['tmp_name'];
            $ukuranFile     = $_FILES['foto']['size'];

            // Cek apakah yang diupload adalah gambar
            $ekstensiGambarValid    = ['jpg', 'jpeg', 'png', 'svg'];
            $ekstensiGambar         = explode('.', $foto);
            $ekstensiGambar         = strtolower(end($ekstensiGambar));
            if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
                echo "<script>alert('Yang anda upload bukan gambar');</script>";
                echo "<script>location='../finance.php?tipe=$tipe'</script>";
                return false;
            }

            $today          = date("His"); 
            $tglsekarang    = date("ymd");
            $namaFileBaru   = 'D' . $tipe . $tglsekarang . $today . '.' . $ekstensiGambar;

            if (move_uploaded_file($tmp, '../buktitransfer/' . $namaFileBaru)) {
                $insert = $koneksi->query("INSERT INTO rekeningkoran 
                                                (idrk, iduser, tanggal, waktu, keterangan, kredit, debit, 
                                                    sisasaldo, buktitf, tipe, kategori_id) 
                                            VALUES 
                                                (null, '$iduser', '$tanggal', '$waktu', '$keterangan', '$kredit', 
                                                '0', '$sisa', '$namaFileBaru', '$tipe', '$kategori')");

                if ($kategori == "7") {
                    $pindah = $koneksi->query("INSERT INTO rekeningkoran 
                                                    (idrk, iduser, tanggal, waktu, keterangan, kredit, debit, 
                                                        sisasaldo, buktitf, tipe, kategori_id)   
                                                VALUES 
                                                    (NULL, '$iduser', '$tanggal', '$waktu', '$keterangan', '$kredit', 
                                                    '0', '$sisa', '$namaFileBaru', '$pindahBank', '$kategori')
                                            ");
                    if (!$pindah) {
                        echo "<script>alert('Pindah Bank ($keterangan) Gagal ditambahkan');</script>";
                        echo "<script>location='../finance.php?tipe=$tipe'</script>";
                    }            
                }
                if ($insert) {
                    echo "<script>alert('Debit ($keterangan) berhasil ditambahkan');</script>";
                    echo "<script>location='../finance.php?tipe=$tipe'</script>";
                } else {
                    echo "<script>alert('Debit ($keterangan) Gagal ditambahkan');</script>";
                    echo "<script>location='../finance.php?tipe=$tipe'</script>";
                }
            }
        }
    ?>      
</body>
</html>