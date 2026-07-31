<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';

    $idKonsumen = $_SESSION['idkonsumen'];
    $errorsProfil = [];
    $errorsPassword = [];
    $pesan = $_SESSION['message'] ?? '';
    unset($_SESSION['message']);

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
            $errorsProfil[] = 'Nama lengkap wajib diisi';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorsProfil[] = 'Email tidak valid';
        }
        if ($whatsapp !== '' && !preg_match('/^[0-9+ ]{8,20}$/', $whatsapp)) {
            $errorsProfil[] = 'Nomor WhatsApp tidak valid';
        }

        if (empty($errorsProfil)) {
            $stmtCek = $koneksi->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmtCek->bind_param('si', $email, $profil['iduser']);
            $stmtCek->execute();
            if ($stmtCek->get_result()->fetch_assoc()) {
                $errorsProfil[] = 'Email sudah dipakai akun lain';
            }
        }

        if (empty($errorsProfil)) {
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

                $profil['namamitra'] = $nama;
                $profil['whatsapp']  = $whatsapp;
                $profil['email']     = $email;

                $_SESSION['message'] = 'Profil berhasil diperbarui';
                header('Location: profile.php');
                exit;
            } catch (Exception $e) {
                $koneksi->rollback();
                error_log($e->getMessage());
                $errorsProfil[] = 'Gagal menyimpan profil, silakan coba lagi';
            }
        }
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
            $errorsPassword[] = 'Password lama tidak sesuai';
        }
        if (strlen($passwordBaru) < 8) {
            $errorsPassword[] = 'Password baru minimal 8 karakter';
        } elseif ($passwordBaru !== $passwordUlang) {
            $errorsPassword[] = 'Konfirmasi password baru tidak sama';
        }

        if (empty($errorsPassword)) {
            $hashBaru = password_hash($passwordBaru, PASSWORD_DEFAULT);
            $stmtSet  = $koneksi->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmtSet->bind_param('si', $hashBaru, $profil['iduser']);
            $stmtSet->execute();

            $_SESSION['message'] = 'Password berhasil diubah';
            header('Location: profile.php');
            exit;
        }
    }

    // Tab yang aktif saat load: ikuti section mana yang barusan error validasi, kalau tidak ada default ke Informasi Akun
    $activeTab = !empty($errorsPassword) ? 'password' : 'informasi';
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
        .panel {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .quick-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            border: 1px solid #eef1f5;
            border-radius: 10px;
            padding: 1rem;
            color: inherit;
            text-decoration: none;
            margin-bottom: .75rem;
        }
        .quick-link:last-child { margin-bottom: 0; }
        .quick-link:hover {
            border-color: #0d6efd;
            text-decoration: none;
            color: inherit;
        }
        .quick-link i {
            font-size: 1.4rem;
            color: #0d6efd;
        }
        .account-sidebar .nav-link {
            color: #2b2f42;
            border-radius: 10px;
            padding: .65rem .9rem;
            margin-bottom: .25rem;
            text-align: left;
        }
        .account-sidebar .nav-link:last-child {
            margin-bottom: 0;
        }
        .account-sidebar .nav-link.active {
            background: #0d6efd;
            color: #fff;
        }
        .account-sidebar .nav-link:not(.active):hover {
            background: #f5f6fa;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 900px;">
        <h5 class="font-weight-bold mt-3 mb-3">Profil Saya</h5>

        <?php if ($pesan !== ''): ?>
            <div class="alert alert-info"><?= htmlspecialchars($pesan) ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-3 mb-3">
                <button class="btn btn-outline-secondary btn-block d-md-none mb-2" type="button" data-toggle="collapse" data-target="#accountSidebar">
                    <i class="bi bi-list"></i> Menu Akun
                </button>

                <div class="collapse d-md-block" id="accountSidebar">
                    <div class="panel p-2 account-sidebar">
                        <div class="nav flex-column nav-pills" id="account-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link <?= $activeTab === 'informasi' ? 'active' : '' ?>" id="informasi-tab" data-toggle="pill" href="#informasi-akun" role="tab" aria-controls="informasi-akun">
                                <i class="bi bi-person mr-1"></i> Informasi Akun
                            </a>
                            <a class="nav-link <?= $activeTab === 'password' ? 'active' : '' ?>" id="password-tab" data-toggle="pill" href="#ubah-password" role="tab" aria-controls="ubah-password">
                                <i class="bi bi-shield-lock mr-1"></i> Ubah Password
                            </a>
                            <a class="nav-link" href="alamat_saya.php">
                                <i class="bi bi-geo-alt mr-1"></i> Alamat Saya
                            </a>
                            <a class="nav-link" href="riwayat_pesanan.php">
                                <i class="bi bi-bag-check mr-1"></i> Pesanan Saya
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="tab-content" id="account-tabContent">
                    <div class="tab-pane fade <?= $activeTab === 'informasi' ? 'show active' : '' ?>" id="informasi-akun" role="tabpanel" aria-labelledby="informasi-tab">
                        <div class="panel">
                            <h6 class="font-weight-bold mb-3">Informasi Akun</h6>

                            <?php if (!empty($errorsProfil)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0 pl-3">
                                        <?php foreach ($errorsProfil as $error): ?>
                                            <li><?= htmlspecialchars($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form method="post">
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
                                <button type="submit" name="update_profile" value="1" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>

                    <div class="tab-pane fade <?= $activeTab === 'password' ? 'show active' : '' ?>" id="ubah-password" role="tabpanel" aria-labelledby="password-tab">
                        <div class="panel">
                            <h6 class="font-weight-bold mb-3">Ubah Password</h6>

                            <?php if (!empty($errorsPassword)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0 pl-3">
                                        <?php foreach ($errorsPassword as $error): ?>
                                            <li><?= htmlspecialchars($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form method="post" autocomplete="off">
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
                                <button type="submit" name="update_password" value="1" class="btn btn-outline-primary">Ubah Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Di mobile, tutup lagi menu akun begitu salah satu tab dipilih
        $('#account-tab a[data-toggle="pill"]').on('click', function () {
            if ($(window).width() < 768) {
                $('#accountSidebar').collapse('hide');
            }
        });
    </script>
</body>
</html>
