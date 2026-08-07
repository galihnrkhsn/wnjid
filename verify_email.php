<?php
    session_start();
    include 'includes/db.php';

    $token  = trim($_GET['token'] ?? '');
    $status = 'invalid'; // invalid | success | already

    if ($token !== '') {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $stmt = $koneksi->prepare("SELECT id, email_verified_at FROM users WHERE verification_token = ?");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && $user['email_verified_at'] !== null) {
            $status = 'already';
        } elseif ($user) {
            $stmtVerify = $koneksi->prepare("UPDATE users SET email_verified_at = NOW(), verification_token = NULL WHERE id = ?");
            $stmtVerify->bind_param('i', $user['id']);
            $stmtVerify->execute();
            $status = 'success';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
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
      </style>
   </head>
   <body>
      <div class="wrap">
         <div class="card-box">
            <?php if ($status === 'success'): ?>
               <i class="bi bi-check-circle text-success"></i>
               <h5 class="font-weight-bold mt-3">Email Terverifikasi!</h5>
               <p class="text-muted">Akun kamu sudah aktif. Silakan login untuk mulai belanja.</p>
            <?php elseif ($status === 'already'): ?>
               <i class="bi bi-check-circle text-success"></i>
               <h5 class="font-weight-bold mt-3">Email Sudah Terverifikasi</h5>
               <p class="text-muted">Akun kamu sudah aktif sebelumnya. Silakan login.</p>
            <?php else: ?>
               <i class="bi bi-x-circle text-danger"></i>
               <h5 class="font-weight-bold mt-3">Link Tidak Valid</h5>
               <p class="text-muted">Link verifikasi tidak ditemukan atau sudah tidak berlaku. Coba kirim ulang dari halaman login.</p>
            <?php endif; ?>
            <a href="index.php" class="btn btn-primary btn-block mt-2">Ke Halaman Login</a>
         </div>
      </div>
   </body>
</html>
