<?php
    session_start();
    include 'includes/db.php';
    include 'includes/mail_helper.php';

    $pesan = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            $stmt = $koneksi->prepare("SELECT id, name FROM users WHERE email = ? AND email_verified_at IS NULL");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();

            if ($user) {
                $tokenBaru = bin2hex(random_bytes(32));
                $stmtToken = $koneksi->prepare("UPDATE users SET verification_token = ? WHERE id = ?");
                $stmtToken->bind_param('si', $tokenBaru, $user['id']);
                $stmtToken->execute();

                $linkVerifikasi = 'https://wnj.id/verify_email.php?token=' . $tokenBaru;
                kirimEmailNotifikasi($email, $user['name'], 'Verifikasi Email Akun Wanoja', emailTemplate('Verifikasi Email Kamu',
                    '<p>Halo ' . htmlspecialchars($user['name']) . ',</p>'
                    . '<p>Klik tombol di bawah untuk verifikasi email dan aktifkan akun kamu.</p>'
                    . '<p style="text-align:center; margin:20px 0;">'
                    . '<a href="' . htmlspecialchars($linkVerifikasi) . '" style="background:#C67C4E; color:#fff; padding:10px 24px; border-radius:8px; text-decoration:none; font-weight:600;">Verifikasi Email</a>'
                    . '</p>'
                    . '<p style="font-size:12px; color:#6E655D;">Atau salin link berikut: ' . htmlspecialchars($linkVerifikasi) . '</p>'));
            }
        }

        // Pesan sama persis baik email ketemu maupun tidak, supaya form ini tidak bisa
        // dipakai buat mengecek email mana saja yang terdaftar (enumeration).
        $pesan = 'Kalau email tersebut terdaftar dan belum diverifikasi, kami sudah kirim ulang link verifikasi.';
    }
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <title>Kirim Ulang Verifikasi | Wanoja App</title>
      <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
      <link rel="icon" href="/home/assets/images/fevicon.png" type="image/gif" />
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
            padding: 2rem;
         }
      </style>
   </head>
   <body>
      <div class="wrap">
         <div class="card-box">
            <h5 class="font-weight-bold mb-3">Kirim Ulang Email Verifikasi</h5>

            <?php if ($pesan !== ''): ?>
               <div class="alert alert-info"><?= htmlspecialchars($pesan) ?></div>
            <?php endif; ?>

            <form method="post">
               <div class="form-group mb-3">
                  <label for="email" class="mb-1">Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com" required>
               </div>
               <button type="submit" class="btn btn-primary btn-block">Kirim Ulang</button>
               <p class="text-center mt-3 mb-0">
                  <a href="index.php">Kembali ke Login</a>
               </p>
            </form>
         </div>
      </div>
   </body>
</html>
