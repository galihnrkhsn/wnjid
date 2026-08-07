<?php
    session_start();
    require 'vendor/autoload.php';
    include 'includes/db.php';
    include 'includes/mail_helper.php';

    use Gregwar\Captcha\PhraseBuilder;

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name           = trim($_POST['name'] ?? '');
        $email          = trim($_POST['email'] ?? '');
        $whatsapp       = trim($_POST['whatsapp'] ?? '');
        $password       = $_POST['password'] ?? '';
        $passwordUlang  = $_POST['password_confirm'] ?? '';
        $captchaJawaban = trim($_POST['captcha'] ?? '');

        // Captcha (gambar) dibuat oleh captcha.php dan jawabannya disimpan di session
        $captchaBenar = !empty($_SESSION['captcha_phrase'])
            && $captchaJawaban !== ''
            && PhraseBuilder::doNiceize($captchaJawaban) === PhraseBuilder::doNiceize($_SESSION['captcha_phrase']);

        if (!$captchaBenar) {
            $errors[] = 'Jawaban captcha salah, silakan coba lagi.';
        }

        if ($name === '') {
            $errors[] = 'Nama lengkap wajib diisi.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email tidak valid.';
        }

        if ($whatsapp !== '' && !preg_match('/^[0-9+ ]{8,20}$/', $whatsapp)) {
            $errors[] = 'Nomor WhatsApp tidak valid.';
        }

        if (strlen($password) < 8) {
            $errors[] = 'Password minimal 8 karakter.';
        } elseif ($password !== $passwordUlang) {
            $errors[] = 'Konfirmasi password tidak sama.';
        }

        if (empty($errors)) {
            $stmtCek = $koneksi->prepare("SELECT id FROM users WHERE email = ?");
            $stmtCek->bind_param('s', $email);
            $stmtCek->execute();
            if ($stmtCek->get_result()->fetch_assoc()) {
                $errors[] = 'Email sudah terdaftar, silakan login.';
            }
        }

        if (empty($errors)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $koneksi->begin_transaction();
            try {
                $tokenVerifikasi = bin2hex(random_bytes(32));

                $stmtUser = $koneksi->prepare("INSERT INTO users (id, name, email, password, role, email_verified_at, verification_token, created_at, updated_at)
                                                VALUES (NULL, ?, ?, ?, 'konsumen', NULL, ?, NOW(), NOW())");
                $stmtUser->bind_param('ssss', $name, $email, $passwordHash, $tokenVerifikasi);
                $stmtUser->execute();
                $iduser = $stmtUser->insert_id;

                $whatsappValue = $whatsapp !== '' ? $whatsapp : null;
                $stmtKonsumen = $koneksi->prepare("INSERT INTO konsumen (idkonsumen, iduser, email, namamitra, whatsapp, status, privateorder, tgl_daftar)
                                                    VALUES (NULL, ?, ?, ?, ?, 'Aktif', 0, NOW())");
                $stmtKonsumen->bind_param('isss', $iduser, $email, $name, $whatsappValue);
                $stmtKonsumen->execute();

                $koneksi->commit();

                unset($_SESSION['captcha_phrase']);

                $linkVerifikasi = 'https://wnj.id/verify_email.php?token=' . $tokenVerifikasi;
                kirimEmailNotifikasi($email, $name, 'Verifikasi Email Akun Wanoja', emailTemplate('Verifikasi Email Kamu',
                    '<p>Halo ' . htmlspecialchars($name) . ',</p>'
                    . '<p>Terima kasih sudah mendaftar di Wanoja. Klik tombol di bawah untuk verifikasi email dan aktifkan akun kamu.</p>'
                    . '<p style="text-align:center; margin:20px 0;">'
                    . '<a href="' . htmlspecialchars($linkVerifikasi) . '" style="background:#C67C4E; color:#fff; padding:10px 24px; border-radius:8px; text-decoration:none; font-weight:600;">Verifikasi Email</a>'
                    . '</p>'
                    . '<p style="font-size:12px; color:#6E655D;">Atau salin link berikut: ' . htmlspecialchars($linkVerifikasi) . '</p>'));

                echo "<script>alert('Registrasi berhasil! Silakan cek email kamu untuk verifikasi akun sebelum login.');</script>";
                echo "<script>location='index.php';</script>";
                exit;
            } catch (Exception $e) {
                $koneksi->rollback();
                error_log($e->getMessage());
                $errors[] = 'Terjadi kesalahan saat menyimpan data, silakan coba lagi.';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <title>Daftar Akun | Wanoja App</title>
      <meta name="description" content="">
      <meta name="author" content="">
      <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="/home/assets/css/style.css">
      <link rel="stylesheet" href="/home/assets/css/responsive.css">
      <link rel="icon" href="/home/assets/images/fevicon.png" type="image/gif" />
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
      <style>
         body {
            background: #f5f6fa;
         }
         .register-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
         }
         .register-card {
            width: 100%;
            max-width: 460px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
            padding: 2rem;
         }
         .register-card .logo {
            text-align: center;
            margin-bottom: 1rem;
         }
         .register-card .logo img {
            height: 48px;
         }
         .form-control {
            border-radius: .5rem;
            padding: .55rem .75rem;
         }
         .captcha-box {
            background: #f0f6ff;
            border-radius: .5rem;
            padding: .6rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            flex-wrap: wrap;
         }
         .captcha-box img {
            border: 1px solid #dee2e6;
            background: #fff;
         }
         .captcha-box input[name="captcha"] {
            flex: 1;
            min-width: 140px;
         }
         .btn-daftar {
            border-radius: .5rem;
            padding: .6rem;
            font-weight: 600;
         }
         .alert-errors {
            border-radius: .5rem;
         }
      </style>
   </head>
   <body>
      <div class="register-wrap">
         <div class="register-card">
            <div class="logo">
               <a href="index.php"><img src="/home/assets/images/Logo.png" alt="Wanoja"></a>
            </div>
            <h4 class="text-center font-weight-bold mb-4">Daftar Akun Baru</h4>

            <?php if (!empty($errors)): ?>
               <div class="alert alert-danger alert-errors">
                  <ul class="mb-0 pl-3">
                     <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                     <?php endforeach; ?>
                  </ul>
               </div>
            <?php endif; ?>

            <form method="post" autocomplete="off">
               <div class="form-group mb-3">
                  <label for="name" class="mb-1">Nama Lengkap</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Nama lengkap" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
               </div>
               <div class="form-group mb-3">
                  <label for="email" class="mb-1">Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
               </div>
               <div class="form-group mb-3">
                  <label for="whatsapp" class="mb-1">No. WhatsApp <span class="text-muted">(opsional)</span></label>
                  <input type="text" class="form-control" id="whatsapp" name="whatsapp" placeholder="08xxxxxxxxxx" value="<?= htmlspecialchars($_POST['whatsapp'] ?? '') ?>">
               </div>
               <div class="form-group mb-3">
                  <label for="password" class="mb-1">Password</label>
                  <div class="input-group">
                     <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 8 karakter" minlength="8" required>
                     <span class="input-group-text" style="cursor:pointer" onclick="togglePassword('password', this)">
                        <i class="bi bi-eye-slash"></i>
                     </span>
                  </div>
               </div>
               <div class="form-group mb-3">
                  <label for="password_confirm" class="mb-1">Konfirmasi Password</label>
                  <div class="input-group">
                     <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Ulangi password" minlength="8" required>
                     <span class="input-group-text" style="cursor:pointer" onclick="togglePassword('password_confirm', this)">
                        <i class="bi bi-eye-slash"></i>
                     </span>
                  </div>
               </div>
               <div class="form-group mb-4">
                  <label class="mb-1">Verifikasi</label>
                  <div class="captcha-box">
                     <img id="captchaImg" src="captcha.php" alt="Captcha" style="height:44px;border-radius:.35rem">
                     <button type="button" class="btn btn-sm btn-outline-secondary" onclick="reloadCaptcha()" title="Ganti gambar">
                        <i class="bi bi-arrow-clockwise"></i>
                     </button>
                     <input type="text" class="form-control" name="captcha" placeholder="Ketik kode di atas" autocomplete="off" required>
                  </div>
               </div>
               <button type="submit" class="btn btn-primary btn-block btn-daftar">Daftar</button>
               <p class="text-center mt-3 mb-0">
                  Sudah punya akun? <a href="index.php" style="color: red;">Login di sini</a>
               </p>
            </form>
         </div>
      </div>

      <script>
         function reloadCaptcha() {
            document.getElementById('captchaImg').src = 'captcha.php?t=' + Date.now();
         }

         function togglePassword(inputId, el) {
            var input = document.getElementById(inputId);
            var icon  = el.querySelector('i');
            if (input.type === 'password') {
               input.type = 'text';
               icon.classList.remove('bi-eye-slash');
               icon.classList.add('bi-eye');
            } else {
               input.type = 'password';
               icon.classList.remove('bi-eye');
               icon.classList.add('bi-eye-slash');
            }
         }
      </script>
      <script src="/home/assets/js/jquery.min.js"></script>
      <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
   </body>
</html>
