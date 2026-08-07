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

    $stmtUser = $koneksi->prepare("SELECT iduser FROM konsumen WHERE idkonsumen = ?");
    $stmtUser->bind_param('i', $idKonsumen);
    $stmtUser->execute();
    $profil = $stmtUser->get_result()->fetch_assoc();

    if (!$profil) {
        header('Location: index.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
        $passwordLama  = $_POST['password_lama'] ?? '';
        $passwordBaru  = $_POST['password_baru'] ?? '';
        $passwordUlang = $_POST['password_ulang'] ?? '';

        $stmtPw = $koneksi->prepare("SELECT password FROM users WHERE id = ?");
        $stmtPw->bind_param('i', $profil['iduser']);
        $stmtPw->execute();
        $hashLama = $stmtPw->get_result()->fetch_assoc()['password'] ?? '';

        if (!password_verify($passwordLama, $hashLama)) {
            $errors[] = 'Password lama tidak sesuai';
        }
        if (strlen($passwordBaru) < 8) {
            $errors[] = 'Password baru minimal 8 karakter';
        } elseif ($passwordBaru !== $passwordUlang) {
            $errors[] = 'Konfirmasi password baru tidak sama';
        }

        if (empty($errors)) {
            $hashBaru = password_hash($passwordBaru, PASSWORD_DEFAULT);
            $stmtSet  = $koneksi->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmtSet->bind_param('si', $hashBaru, $profil['iduser']);
            $stmtSet->execute();

            $_SESSION['message'] = 'Password berhasil diubah';
            header('Location: profile.php');
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Ubah Password | WNJ.ID</title>
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
            <h5 class="font-weight-bold mt-2 mb-3">Ubah Password</h5>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0 pl-3">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" autocomplete="off">
                <?= csrfField() ?>
                <div class="form-group">
                    <label class="mb-1">Password Lama</label>
                    <input type="password" class="form-control" name="password_lama" required>
                </div>
                <div class="form-group">
                    <label class="mb-1">Password Baru</label>
                    <input type="password" class="form-control" name="password_baru" minlength="8" required>
                </div>
                <div class="form-group">
                    <label class="mb-1">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" name="password_ulang" minlength="8" required>
                </div>
                <button type="submit" name="update_password" value="1" class="btn btn-primary btn-block">Ubah Password</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
