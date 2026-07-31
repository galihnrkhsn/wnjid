<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';

    $idKonsumen = $_SESSION['idkonsumen'];
    $pesan      = $_SESSION['message'] ?? '';
    unset($_SESSION['message']);

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
        $idalamat = (int) $_POST['hapus'];

        $stmt = $koneksi->prepare("DELETE FROM alamat WHERE idalamat = ? AND tipe_pemilik = 'konsumen' AND id_pemilik = ?");
        $stmt->bind_param('ii', $idalamat, $idKonsumen);
        $stmt->execute();

        $_SESSION['message'] = 'Alamat berhasil dihapus';
        header('Location: alamat_saya.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jadikan_utama'])) {
        $idalamat = (int) $_POST['jadikan_utama'];

        $koneksi->begin_transaction();
        try {
            $stmtUnset = $koneksi->prepare("UPDATE alamat SET is_utama = 0 WHERE tipe_pemilik = 'konsumen' AND id_pemilik = ?");
            $stmtUnset->bind_param('i', $idKonsumen);
            $stmtUnset->execute();

            $stmtSet = $koneksi->prepare("UPDATE alamat SET is_utama = 1 WHERE idalamat = ? AND tipe_pemilik = 'konsumen' AND id_pemilik = ?");
            $stmtSet->bind_param('ii', $idalamat, $idKonsumen);
            $stmtSet->execute();

            $koneksi->commit();
            $_SESSION['message'] = 'Alamat utama berhasil diubah';
        } catch (Exception $e) {
            $koneksi->rollback();
            error_log($e->getMessage());
            $_SESSION['message'] = 'Gagal mengubah alamat utama, silakan coba lagi';
        }

        header('Location: alamat_saya.php');
        exit;
    }

    $stmtList = $koneksi->prepare("SELECT * FROM alamat WHERE tipe_pemilik = 'konsumen' AND id_pemilik = ? ORDER BY is_utama DESC, updated_at DESC");
    $stmtList->bind_param('i', $idKonsumen);
    $stmtList->execute();
    $daftarAlamat = $stmtList->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Alamat Saya | Wanoja</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f5f6fa; }
        .panel {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .alamat-card {
            border: 1px solid #eef1f5;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: .75rem;
        }
        .alamat-card:last-child { margin-bottom: 0; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 720px;">
        <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
            <h5 class="font-weight-bold mb-0">Alamat Saya</h5>
            <a href="alamat_form.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah Alamat</a>
        </div>

        <?php if ($pesan !== ''): ?>
            <div class="alert alert-info"><?= htmlspecialchars($pesan) ?></div>
        <?php endif; ?>

        <div class="panel">
            <?php if (empty($daftarAlamat)): ?>
                <p class="text-muted mb-0 text-center">Belum ada alamat tersimpan.</p>
            <?php endif; ?>

            <?php foreach ($daftarAlamat as $alamat): ?>
                <div class="alamat-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="font-weight-bold"><?= htmlspecialchars($alamat['label']) ?></span>
                            <?php if ((int) $alamat['is_utama'] === 1): ?>
                                <span class="badge badge-primary ml-1">Utama</span>
                            <?php endif; ?>
                        </div>
                        <a href="alamat_form.php?id=<?= (int) $alamat['idalamat'] ?>" class="small">Ubah</a>
                    </div>
                    <div class="font-weight-bold mt-1"><?= htmlspecialchars($alamat['nama_penerima']) ?></div>
                    <div><?= htmlspecialchars($alamat['telepon_penerima']) ?></div>
                    <div class="text-muted small"><?= htmlspecialchars($alamat['alamat_lengkap']) ?></div>
                    <div class="text-muted small">
                        <?= htmlspecialchars(trim(implode(', ', array_filter([$alamat['kecamatan'], $alamat['kota'], $alamat['provinsi'], $alamat['kodepos']])))) ?>
                    </div>

                    <div class="mt-2">
                        <?php if ((int) $alamat['is_utama'] !== 1): ?>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="jadikan_utama" value="<?= (int) $alamat['idalamat'] ?>">
                                <button type="submit" class="btn btn-outline-primary btn-sm">Jadikan Utama</button>
                            </form>
                        <?php endif; ?>
                        <form method="post" class="d-inline" onsubmit="return confirm('Hapus alamat ini?');">
                            <input type="hidden" name="hapus" value="<?= (int) $alamat['idalamat'] ?>">
                            <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mb-4">
            <a href="profile.php" class="small">&larr; Kembali ke Profil</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
