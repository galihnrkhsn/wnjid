<?php
    session_start();
    include 'koneksi.php';
    include '../includes/qrisly_helper.php';

    if (!isset($_SESSION["administrator"])) {
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $pesan = '';
    $error = '';
    $hasil = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_qris'])) {
        $merchantName = trim($_POST['merchant_name'] ?? '');
        $fotoTmp      = $_FILES['qris_image']['tmp_name'] ?? '';
        $fotoNama     = $_FILES['qris_image']['name'] ?? '';
        $ekstensi     = strtolower(pathinfo($fotoNama, PATHINFO_EXTENSION));

        if ($merchantName === '') {
            $error = 'Nama merchant wajib diisi';
        } elseif (empty($fotoNama) || !in_array($ekstensi, ['jpg', 'jpeg', 'png'], true)) {
            $error = 'Upload gambar QRIS statis (jpg/jpeg/png)';
        } else {
            $hasil = qrislyUploadStaticQris($fotoTmp, $fotoNama, $merchantName);

            if (!$hasil) {
                $error = 'Gagal upload ke Qrisly. Cek API key di includes/rajaongkir.config.php sudah benar.';
            } else {
                $configFile = __DIR__ . '/../includes/rajaongkir.config.php';
                $isiFile    = file_get_contents($configFile);
                $isiBaru    = preg_replace(
                    "/('qris_id'\\s*=>\\s*)'[^']*'/",
                    "$1'" . addslashes($hasil['qris_id']) . "'",
                    $isiFile,
                    1
                );
                file_put_contents($configFile, $isiBaru);
                $pesan = 'QRIS statis berhasil di-upload & qris_id sudah otomatis disimpan ke config.';
            }
        }
    }

    $qrisIdSekarang = qrislyConfig()['qris_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Setup QRIS | WNJ.ID</title>
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/wnj-theme.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 mt-5">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-3">Setup QRIS Statis (Qrisly)</h5>
                        <p class="text-muted small">
                            Upload SEKALI SAJA gambar QRIS statis toko (dari e-wallet/bank).
                            <code>qris_id</code> hasilnya otomatis disimpan ke config, dipakai untuk
                            generate QRIS dinamis per order di halaman pembayaran konsumen.
                        </p>

                        <?php if ($qrisIdSekarang !== ''): ?>
                            <div class="alert alert-info">
                                QRIS statis sudah pernah di-setup. <code>qris_id</code> saat ini: <code><?= htmlspecialchars($qrisIdSekarang) ?></code>
                                <br>Upload lagi di bawah ini kalau mau mengganti.
                            </div>
                        <?php endif; ?>

                        <?php if ($pesan): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($pesan) ?></div>
                        <?php endif; ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Nama Merchant</label>
                                <input type="text" name="merchant_name" class="form-control" placeholder="Wanoja" required>
                            </div>
                            <div class="form-group">
                                <label>Gambar QRIS Statis</label>
                                <input type="file" name="qris_image" class="form-control-file" accept="image/jpeg,image/png" required>
                            </div>
                            <button type="submit" name="upload_qris" class="btn btn-primary">
                                <i class="fa fa-upload"></i> Upload ke Qrisly
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
