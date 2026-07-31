<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';

    $idKonsumen = $_SESSION['idkonsumen'];
    $pesan = $_SESSION['message'] ?? '';
    unset($_SESSION['message']);

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $stmtUser = $koneksi->prepare("SELECT namamitra, email FROM konsumen WHERE idkonsumen = ?");
    $stmtUser->bind_param('i', $idKonsumen);
    $stmtUser->execute();
    $profil = $stmtUser->get_result()->fetch_assoc();

    if (!$profil) {
        header('Location: index.php');
        exit;
    }

    $stmtAlamat = $koneksi->prepare("SELECT COUNT(*) AS jumlah FROM alamat WHERE tipe_pemilik = 'konsumen' AND id_pemilik = ?");
    $stmtAlamat->bind_param('i', $idKonsumen);
    $stmtAlamat->execute();
    $jumlahAlamat = (int) ($stmtAlamat->get_result()->fetch_assoc()['jumlah'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Profil Saya | Wanoja</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f5f6fa; }
        .profile-summary {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.25rem;
            margin: 1rem 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .profile-summary .avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #0d6efd;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .profile-summary .info {
            flex-grow: 1;
            min-width: 0;
        }
        .profile-summary .info .nama {
            font-weight: 700;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .profile-summary .edit-link {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0d6efd;
            background: #f0f6ff;
            flex-shrink: 0;
        }
        .profile-summary .edit-link:hover {
            background: #e2edff;
            text-decoration: none;
        }
        .menu-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .menu-row {
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
            color: #2b2f42;
            text-decoration: none;
            border-bottom: 1px solid #f5f6fa;
        }
        .menu-row:last-child {
            border-bottom: none;
        }
        .menu-row:hover {
            background: #f8f9fb;
            color: #2b2f42;
            text-decoration: none;
        }
        .menu-row i.bi-icon {
            font-size: 1.15rem;
            color: #0d6efd;
            width: 28px;
        }
        .menu-row .label {
            flex-grow: 1;
            font-weight: 600;
        }
        .menu-row .count {
            font-size: .8rem;
            color: #6c757d;
            margin-right: .5rem;
        }
        .menu-row .chevron {
            color: #adb5bd;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 640px;">
        <?php if ($pesan !== ''): ?>
            <div class="alert alert-info mt-3 mb-0"><?= htmlspecialchars($pesan) ?></div>
        <?php endif; ?>

        <div class="profile-summary">
            <div class="avatar"><i class="bi bi-person"></i></div>
            <div class="info">
                <div class="nama"><?= htmlspecialchars($profil['namamitra']) ?></div>
                <div class="text-muted small"><?= htmlspecialchars($profil['email']) ?></div>
            </div>
            <a href="edit_profile.php" class="edit-link" title="Edit Profil">
                <i class="bi bi-pencil"></i>
            </a>
        </div>

        <div class="menu-card">
            <a href="alamat_saya.php" class="menu-row">
                <i class="bi bi-geo-alt bi-icon"></i>
                <span class="label">Alamat Saya</span>
                <?php if ($jumlahAlamat > 0): ?><span class="count"><?= $jumlahAlamat ?></span><?php endif; ?>
                <i class="bi bi-chevron-right chevron"></i>
            </a>
            <a href="riwayat_pesanan.php" class="menu-row">
                <i class="bi bi-bag-check bi-icon"></i>
                <span class="label">Pesanan Saya</span>
                <i class="bi bi-chevron-right chevron"></i>
            </a>
            <a href="ubah_password.php" class="menu-row">
                <i class="bi bi-shield-lock bi-icon"></i>
                <span class="label">Ubah Password</span>
                <i class="bi bi-chevron-right chevron"></i>
            </a>
        </div>

        <div class="menu-card mb-4">
            <a href="logout.php" class="menu-row">
                <i class="bi bi-box-arrow-right bi-icon"></i>
                <span class="label">Keluar</span>
                <i class="bi bi-chevron-right chevron"></i>
            </a>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
