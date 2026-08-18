<?php
    session_start();
    include 'includes/db.php';
    include 'includes/otp_helper.php';

    $iduser = (int) ($_SESSION['otp_user_id'] ?? 0);
    if ($iduser === 0) {
        header('Location: resend_verifikasi.php');
        exit;
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Sisa waktu dihitung di sisi MySQL (TIMESTAMPDIFF dari NOW() server DB), bukan
    // dibandingkan dengan time()/strtotime() PHP - supaya tidak meleset kalau timezone
    // PHP & MySQL beda (pernah kejadian: PHP Europe/Berlin vs MySQL WIB, selisih 5 jam).
    $stmtUser = $koneksi->prepare("SELECT id, name, email, email_verified_at, verification_token, verification_attempts,
                                        TIMESTAMPDIFF(SECOND, NOW(), verification_expires_at) AS detik_sampai_expired,
                                        TIMESTAMPDIFF(SECOND, verification_last_sent_at, NOW()) AS detik_sejak_kirim
                                    FROM users WHERE id = ?");
    $stmtUser->bind_param('i', $iduser);
    $stmtUser->execute();
    $user = $stmtUser->get_result()->fetch_assoc();

    if (!$user) {
        unset($_SESSION['otp_user_id']);
        header('Location: resend_verifikasi.php');
        exit;
    }

    $cooldownDetik = 60;
    $maxPercobaan  = 5;

    $pesan          = '';
    $pesanTipe      = 'info'; // info | danger | success
    $sudahVerified  = $user['email_verified_at'] !== null;

    if (!$sudahVerified && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend'])) {
        $detikSejakKirim = $user['detik_sejak_kirim'];
        $bolehKirim       = $detikSejakKirim === null || (int) $detikSejakKirim >= $cooldownDetik;

        if ($bolehKirim) {
            kirimKodeVerifikasi($koneksi, $iduser, $user['email'], $user['name']);
            $pesan     = 'Kode baru sudah dikirim ke email kamu.';
            $pesanTipe = 'success';

            $stmtUser->execute();
            $user = $stmtUser->get_result()->fetch_assoc();
        } else {
            $sisaDetik = $cooldownDetik - (int) $detikSejakKirim;
            $pesan     = 'Tunggu ' . $sisaDetik . ' detik lagi sebelum minta kode baru.';
            $pesanTipe = 'danger';
        }
    } elseif (!$sudahVerified && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify'])) {
        $kodeInput = trim($_POST['kode'] ?? '');

        if ((int) $user['verification_attempts'] >= $maxPercobaan) {
            $pesan     = 'Terlalu banyak percobaan salah. Minta kode baru.';
            $pesanTipe = 'danger';
        } elseif ($user['detik_sampai_expired'] === null || (int) $user['detik_sampai_expired'] < 0) {
            $pesan     = 'Kode sudah kadaluarsa. Minta kode baru.';
            $pesanTipe = 'danger';
        } elseif ($kodeInput !== '' && hash_equals($user['verification_token'] ?? '', hash('sha256', $kodeInput))) {
            $stmtVerify = $koneksi->prepare("UPDATE users
                                                SET email_verified_at = NOW(), verification_token = NULL,
                                                    verification_expires_at = NULL
                                                WHERE id = ?");
            $stmtVerify->bind_param('i', $iduser);
            $stmtVerify->execute();

            unset($_SESSION['otp_user_id']);
            $sudahVerified = true;
            $pesan         = 'Email kamu berhasil diverifikasi. Silakan login.';
            $pesanTipe     = 'success';
        } else {
            $stmtGagal = $koneksi->prepare("UPDATE users SET verification_attempts = verification_attempts + 1 WHERE id = ?");
            $stmtGagal->bind_param('i', $iduser);
            $stmtGagal->execute();

            $pesan     = 'Kode salah, silakan coba lagi.';
            $pesanTipe = 'danger';
        }
    }

    $emailSamar   = preg_replace('/^(.).*(@.*)$/', '$1***$2', $user['email']);
    $cooldownSisa = $user['detik_sejak_kirim'] !== null
        ? max(0, $cooldownDetik - (int) $user['detik_sejak_kirim'])
        : 0;
?>
<!DOCTYPE html>
<html lang="id">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <title>Verifikasi Email | WNJ.id</title>
      <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
      <link rel="icon" href="/home/assets/images/fevicon.png" type="image/gif" />
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
      <style>
         body { background: #f5f6fa; }
         .wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
         }
         .card-box {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
            padding: 2.5rem 2rem;
            text-align: center;
         }
         .card-box i { font-size: 3rem; }
         .kode-input {
            width: 100%;
            font-size: 1.75rem;
            letter-spacing: .6rem;
            text-align: center;
            padding: .6rem .5rem .6rem 1.1rem;
            border-radius: .5rem;
         }
         .loading-overlay {
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse at center, rgba(0,0,0,.35) 0%, rgba(0,0,0,.8) 100%);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
         }
         .loading-overlay.show { display: flex; }
         .loading-spinner {
            width: 56px;
            height: 56px;
            border: 5px solid rgba(255,255,255,.25);
            border-top-color: #C67C4E;
            border-radius: 50%;
            animation: loading-spin .8s linear infinite;
         }
         @keyframes loading-spin { to { transform: rotate(360deg); } }
      </style>
   </head>
   <body>
      <div class="loading-overlay" id="loadingOverlay">
         <div class="loading-spinner"></div>
      </div>
      <div class="wrap">
         <div class="card-box">
            <?php if ($sudahVerified): ?>
               <i class="bi bi-check-circle text-success"></i>
               <h5 class="font-weight-bold mt-3">Email Terverifikasi!</h5>
               <p class="text-muted"><?= htmlspecialchars($pesan ?: 'Akun kamu sudah aktif. Silakan login untuk mulai belanja.') ?></p>
               <a href="index.php" class="btn btn-primary btn-block mt-2">Ke Halaman Login</a>
            <?php else: ?>
               <i class="bi bi-envelope-check text-primary"></i>
               <h5 class="font-weight-bold mt-3">Masukkan Kode Verifikasi</h5>
               <p class="text-muted mb-3">Kode 6 digit sudah dikirim ke <strong><?= htmlspecialchars($emailSamar) ?></strong>, berlaku 10 menit.</p>

               <?php if ($pesan !== ''): ?>
                  <div class="alert alert-<?= htmlspecialchars($pesanTipe) ?>"><?= htmlspecialchars($pesan) ?></div>
               <?php endif; ?>

               <form method="post" id="formVerifikasi">
                  <input type="text" class="form-control kode-input mb-3" name="kode" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="000000" autocomplete="one-time-code" autofocus required>
                  <button type="submit" name="verify" class="btn btn-primary btn-block">Verifikasi</button>
               </form>

               <form method="post" id="formResend" class="mt-3">
                  <button type="submit" name="resend" class="btn btn-outline-secondary btn-block" id="btnResend" <?= $cooldownSisa > 0 ? 'disabled' : '' ?>>
                     <span id="resendLabel"><?= $cooldownSisa > 0 ? 'Kirim Ulang (' . $cooldownSisa . 's)' : 'Kirim Ulang Kode' ?></span>
                  </button>
               </form>

               <p class="text-center mt-3 mb-0">
                  <a href="index.php">Kembali ke Login</a>
               </p>
            <?php endif; ?>
         </div>
      </div>

      <script>
         document.getElementById('formVerifikasi')?.addEventListener('submit', function () {
            document.getElementById('loadingOverlay').classList.add('show');
         });

         (function () {
            var sisa = <?= (int) $cooldownSisa ?>;
            var btn  = document.getElementById('btnResend');
            var label = document.getElementById('resendLabel');
            if (!btn || sisa <= 0) {
               return;
            }
            var timer = setInterval(function () {
               sisa -= 1;
               if (sisa <= 0) {
                  clearInterval(timer);
                  btn.disabled = false;
                  label.textContent = 'Kirim Ulang Kode';
               } else {
                  label.textContent = 'Kirim Ulang (' + sisa + 's)';
               }
            }, 1000);
         })();
      </script>
      <script src="/home/assets/js/jquery.min.js"></script>
      <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
   </body>
</html>
