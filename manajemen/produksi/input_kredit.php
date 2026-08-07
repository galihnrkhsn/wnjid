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
    <title><?= $role ?> | WNJ.ID</title>
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
    <?php include "../assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->
    <div class="container mt-4">
        <a href="../index.php" class="btn btn-info float-right"><i class="fa fa-arrow-left"></i> Kembali</a>
        <h4><i class="fa fa-minus"></i> Kredit</h4>
        <p class="statusMsg"></p>
        <form method="POST" enctype="multipart/form-data" id="formKredit">
            <input type="hidden" name="tipe" value="<?= htmlspecialchars($tipe) ?>">
            <input type="hidden" name="iduser" value="<?= htmlspecialchars($iduser) ?>">
            <input type="hidden" name="jenis_transaksi" value="kredit">

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
                <input type="number" min="0" class="form-control" id="kredit" name="jumlah" placeholder="Masukkan Total Kredit" required/>
            </div>
            <div class="form-group">
                <label for="kategoridebit">Kategori</label>
                <select name="kategori" id="kategoridebit" class="form-control">
                    <option value="" disabled selected>~~ Pilih Kategori ~~</option>
                    <?php
                        $getKate = $koneksi->query("SELECT * FROM kategori_manajemen WHERE tipe = 'kredit'");
                        while ($data = $getKate->fetch_assoc()) {
                    ?>
                    <option value="<?= $data['idkategori'] ?>" data-nama="<?= htmlspecialchars($data['nama_kategori']) ?>"><?= $data['nama_kategori'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group" id="extraSelectContainer" style="display: none;">
                <label for="extraSelect">Pilih Bank</label>
                <select name="pindah_bank" id="extraSelect" class="form-control">
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
                const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
                if (selectedOption && selectedOption.dataset.nama === 'Pindah Bank') {
                    extraSelectContainer.style.display = 'block';
                } else {
                    extraSelectContainer.style.display = 'none';
                }
            });

            const form = document.getElementById('formKredit');
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(form);

                fetch('../api/insert-finance.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(function (res) { return res.text(); })
                    .then(function (text) {
                        let json;
                        try {
                            json = JSON.parse(text);
                        } catch (e) {
                            console.error('Respon bukan JSON valid:', text);
                            alert('Terjadi kesalahan, silakan coba lagi');
                            return;
                        }
                        if (json.success) {
                            alert(json.message);
                            location.href = '../finance.php?tipe=<?= urlencode($tipe) ?>';
                        } else {
                            alert('Gagal: ' + json.message);
                        }
                    })
                    .catch(function (err) {
                        console.error('Network error:', err);
                        alert('Gagal terhubung ke server, silakan coba lagi');
                    });
            });
        });
    </script>
</body>
</html>
