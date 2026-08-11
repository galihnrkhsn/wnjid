<?php
    session_start();
    include 'koneksi.php';
    include '../includes/mitra_role_helper.php';

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmtUser = $koneksi->prepare("SELECT * FROM users WHERE email = ?");
        $stmtUser->bind_param('s', $email);
        $stmtUser->execute();
        $user = $stmtUser->get_result()->fetch_assoc();

        $mitraRolesValid = ['distributor', 'agen', 'reseller', 'marketer'];

        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'Email atau password salah.';
        } elseif (!in_array($user['role'], $mitraRolesValid, true)) {
            $error = 'Akun ini bukan akun mitra (distributor/agen/reseller/marketer).';
        } else {
            $identitas = mitraResolveIdentitas($koneksi, $user['role'], (int) $user['id']);

            if ($identitas === null) {
                $error = 'Data mitra untuk akun ini tidak ditemukan, silakan hubungi admin.';
            } else {
                session_regenerate_id(true);

                $_SESSION['user_id']    = (int) $user['id'];
                $_SESSION['user_level'] = $user['role'];
                $_SESSION['idmitra']    = $identitas['idmitra'];
                $_SESSION['idadmin_induk']     = $identitas['idadmin_induk'];
                $_SESSION['idmitraagen_induk'] = $identitas['idmitraagen_induk'];
                $_SESSION['nama_mitra'] = $identitas['nama'];

                // Alias kompatibel dengan nama session lama yang masih dipakai luas di
                // distributor/agen/reseller/marketer (idadmin selalu diisi buat semua role -
                // sebelumnya di beberapa file agen/reseller/marketer session ini malah
                // tidak pernah ke-set walau kodenya baca $_SESSION['idadmin']).
                $_SESSION['idadmin'] = $identitas['idadmin_induk'];
                if ($user['role'] === 'agen') {
                    $_SESSION['idmitraagen'] = $identitas['idmitra'];
                } elseif ($user['role'] === 'reseller') {
                    $_SESSION['idmitrareseller'] = $identitas['idmitra'];
                } elseif ($user['role'] === 'marketer') {
                    $_SESSION['idmitramarketer'] = $identitas['idmitra'];
                }

                header('Location: index.php');
                exit;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="id">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <title>Login Mitra | WNJ.ID</title>
      <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
      <style>
         body { background: #f5f6fa; margin: 0; }
         .wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
         }
         .card-box {
            width: 100%;
            max-width: 400px;
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
            <h5 class="font-weight-bold text-center mb-4">Login Mitra</h5>
            <p class="text-muted text-center small mb-4">Distributor &middot; Agen &middot; Reseller &middot; Marketer</p>

            <?php if ($error !== ''): ?>
               <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post">
               <div class="form-group mb-3">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" required autofocus>
               </div>
               <div class="form-group mb-3">
                  <label>Password</label>
                  <input type="password" name="password" class="form-control" required>
               </div>
               <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
         </div>
      </div>
   </body>
</html>
