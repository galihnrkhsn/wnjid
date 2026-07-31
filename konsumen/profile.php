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

                $_SESSION['message'] = 'Profil berhasil diperbarui';
                header('Location: profile.php?section=informasi');
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
            header('Location: profile.php?section=password');
            exit;
        }
    }

    // Alamat tersimpan digabung ke sini (dulu halaman terpisah alamat_saya.php) supaya kelola
    // alamat tidak perlu buka halaman baru. Tambah/ubah alamat tetap di alamat_form.php
    // (form-nya lumayan kompleks dgn dropdown provinsi/kota/kecamatan berjenjang).
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_alamat'])) {
        $idalamat = (int) $_POST['hapus_alamat'];

        $stmt = $koneksi->prepare("DELETE FROM alamat WHERE idalamat = ? AND tipe_pemilik = 'konsumen' AND id_pemilik = ?");
        $stmt->bind_param('ii', $idalamat, $idKonsumen);
        $stmt->execute();

        $_SESSION['message'] = 'Alamat berhasil dihapus';
        header('Location: profile.php?section=alamat');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jadikan_utama_alamat'])) {
        $idalamat = (int) $_POST['jadikan_utama_alamat'];

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

        header('Location: profile.php?section=alamat');
        exit;
    }

    $stmtAlamat = $koneksi->prepare("SELECT * FROM alamat WHERE tipe_pemilik = 'konsumen' AND id_pemilik = ? ORDER BY is_utama DESC, updated_at DESC");
    $stmtAlamat->bind_param('i', $idKonsumen);
    $stmtAlamat->execute();
    $daftarAlamat = $stmtAlamat->get_result()->fetch_all(MYSQLI_ASSOC);

    // Section mana yang terbuka duluan: ikuti error validasi kalau ada, atau ?section= dari redirect, default informasi
    $section = $_GET['section'] ?? 'informasi';
    if (!empty($errorsPassword)) {
        $section = 'password';
    } elseif (!empty($errorsProfil)) {
        $section = 'informasi';
    }
    if (!in_array($section, ['informasi', 'alamat', 'password'], true)) {
        $section = 'informasi';
    }
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
        .acc-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .acc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            cursor: pointer;
            color: #2b2f42;
            text-decoration: none;
        }
        .acc-header:hover {
            color: #2b2f42;
            text-decoration: none;
        }
        .acc-header .title {
            display: flex;
            align-items: center;
            gap: .75rem;
            font-weight: 700;
        }
        .acc-header .title i.bi-icon {
            font-size: 1.15rem;
            color: #0d6efd;
        }
        .acc-header .chevron {
            transition: transform .2s ease-in-out;
            color: #adb5bd;
        }
        .acc-header[aria-expanded="true"] .chevron {
            transform: rotate(180deg);
        }
        .acc-body {
            padding: 0 1.25rem 1.25rem;
        }
        .acc-count {
            font-size: .75rem;
            font-weight: 400;
            color: #6c757d;
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

    <div class="container" style="max-width: 640px;">
        <?php if ($pesan !== ''): ?>
            <div class="alert alert-info mt-3 mb-0"><?= htmlspecialchars($pesan) ?></div>
        <?php endif; ?>

        <div class="profile-summary">
            <div class="avatar"><i class="bi bi-person"></i></div>
            <div>
                <div class="font-weight-bold"><?= htmlspecialchars($profil['namamitra']) ?></div>
                <div class="text-muted small"><?= htmlspecialchars($profil['email']) ?></div>
            </div>
        </div>

        <!-- Informasi Akun -->
        <div class="acc-card">
            <a class="acc-header" data-toggle="collapse" href="#sec-informasi" aria-expanded="<?= $section === 'informasi' ? 'true' : 'false' ?>">
                <span class="title"><i class="bi bi-person bi-icon"></i> Informasi Akun</span>
                <i class="bi bi-chevron-down chevron"></i>
            </a>
            <div class="collapse<?= $section === 'informasi' ? ' show' : '' ?>" id="sec-informasi">
                <div class="acc-body">
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
                        <button type="submit" name="update_profile" value="1" class="btn btn-primary btn-block">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Alamat Saya -->
        <div class="acc-card">
            <a class="acc-header" data-toggle="collapse" href="#sec-alamat" aria-expanded="<?= $section === 'alamat' ? 'true' : 'false' ?>">
                <span class="title"><i class="bi bi-geo-alt bi-icon"></i> Alamat Saya <span class="acc-count">(<?= count($daftarAlamat) ?>)</span></span>
                <i class="bi bi-chevron-down chevron"></i>
            </a>
            <div class="collapse<?= $section === 'alamat' ? ' show' : '' ?>" id="sec-alamat">
                <div class="acc-body">
                    <?php if (empty($daftarAlamat)): ?>
                        <p class="text-muted text-center mb-3">Belum ada alamat tersimpan.</p>
                    <?php else: ?>
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
                                            <input type="hidden" name="jadikan_utama_alamat" value="<?= (int) $alamat['idalamat'] ?>">
                                            <button type="submit" class="btn btn-outline-primary btn-sm">Jadikan Utama</button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="post" class="d-inline" onsubmit="return confirm('Hapus alamat ini?');">
                                        <input type="hidden" name="hapus_alamat" value="<?= (int) $alamat['idalamat'] ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <a href="alamat_form.php" class="btn btn-outline-primary btn-block">
                        <i class="bi bi-plus-lg"></i> Tambah Alamat Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Ubah Password -->
        <div class="acc-card">
            <a class="acc-header" data-toggle="collapse" href="#sec-password" aria-expanded="<?= $section === 'password' ? 'true' : 'false' ?>">
                <span class="title"><i class="bi bi-shield-lock bi-icon"></i> Ubah Password</span>
                <i class="bi bi-chevron-down chevron"></i>
            </a>
            <div class="collapse<?= $section === 'password' ? ' show' : '' ?>" id="sec-password">
                <div class="acc-body">
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
                        <button type="submit" name="update_password" value="1" class="btn btn-outline-primary btn-block">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Pesanan Saya (tetap halaman terpisah, isinya list + pagination sendiri) -->
        <a href="riwayat_pesanan.php" class="acc-header acc-card" style="display:flex;">
            <span class="title"><i class="bi bi-bag-check bi-icon"></i> Pesanan Saya</span>
            <i class="bi bi-chevron-right chevron" style="transform:none;"></i>
        </a>

        <a href="logout.php" class="acc-header acc-card mb-4" style="display:flex;">
            <span class="title"><i class="bi bi-box-arrow-right bi-icon"></i> Keluar</span>
            <i class="bi bi-chevron-right chevron" style="transform:none;"></i>
        </a>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
