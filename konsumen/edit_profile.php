<?php
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';

    $idKonsumen = $_SESSION['idkonsumen'];
    $errors = [];

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $stmtUser = $koneksi->prepare("SELECT k.idkonsumen, k.iduser, k.namamitra, k.whatsapp, k.email
                                    FROM konsumen k WHERE k.idkonsumen = ?");
    $stmtUser->bind_param('i', $idKonsumen);
    $stmtUser->execute();
    $profil = $stmtUser->get_result()->fetch_assoc();

    if (!$profil) {
        header('Location: index.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
        $nama     = trim($_POST['nama'] ?? '');
        $whatsapp = trim($_POST['whatsapp'] ?? '');
        $email    = trim($_POST['email'] ?? '');

        if ($nama === '') {
            $errors[] = 'Nama lengkap wajib diisi';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email tidak valid';
        }
        if ($whatsapp !== '' && !preg_match('/^[0-9+ ]{8,20}$/', $whatsapp)) {
            $errors[] = 'Nomor WhatsApp tidak valid';
        }

        if (empty($errors)) {
            $stmtCek = $koneksi->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmtCek->bind_param('si', $email, $profil['iduser']);
            $stmtCek->execute();
            if ($stmtCek->get_result()->fetch_assoc()) {
                $errors[] = 'Email sudah dipakai akun lain';
            }
        }

        if (empty($errors)) {
            $koneksi->begin_transaction();
            try {
                $stmtU = $koneksi->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                $stmtU->bind_param('ssi', $nama, $email, $profil['iduser']);
                $stmtU->execute();

                $whatsappValue = $whatsapp !== '' ? $whatsapp : null;
                $stmtK = $koneksi->prepare("UPDATE konsumen SET namamitra = ?, email = ?, whatsapp = ? WHERE idkonsumen = ?");
                $stmtK->bind_param('sssi', $nama, $email, $whatsappValue, $idKonsumen);
                $stmtK->execute();

                $koneksi->commit();

                $_SESSION['message'] = 'Profil berhasil diperbarui';
                header('Location: profile.php');
                exit;
            } catch (Exception $e) {
                $koneksi->rollback();
                error_log($e->getMessage());
                $errors[] = 'Gagal menyimpan profil, silakan coba lagi';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Profil | Wanoja</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: var(--wnj-bg); }
        .panel {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.5rem;
            max-width: 480px;
            margin: 1.5rem auto;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="panel">
            <a href="profile.php" class="text-muted small"><i class="bi bi-arrow-left"></i> Kembali ke Profil</a>
            <h5 class="font-weight-bold mt-2 mb-3">Edit Profil</h5>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0 pl-3">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post">
                <?= csrfField() ?>
                <div class="form-group">
                    <label class="mb-1">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($profil['namamitra']) ?>" required>
                </div>
                <div class="form-group">
                    <label class="mb-1">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($profil['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label class="mb-1">No. WhatsApp</label>
                    <input type="text" class="form-control" name="whatsapp" value="<?= htmlspecialchars($profil['whatsapp'] ?? '') ?>">
                </div>
                <button type="submit" name="update_profile" value="1" class="btn btn-primary btn-block">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
