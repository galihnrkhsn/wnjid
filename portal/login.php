<?php
    session_start();
    if (isset($_SESSION['logistik'])) {
        echo "
            <script>
                alert('Anda sudah login')
                location='index.php'
            </script>
        ";
        exit();
    }
    include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Portal WNJ | Log in</title>
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="template/plugins/fontawesome-free/css/all.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="template/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="template/dist/css/adminlte.min.css">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="index.php"><b>Portal</b>WNJ</a>
            </div>
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start your session</p>

                    <form method="post" enctype="multipart/form-data">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-sm" name="username" placeholder="Username" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                        <input type="password" id="password" class="form-control form-control-sm" name="password" placeholder="Password" required>
                            <div class="input-group-append">
                                <div class="input-group-text" onclick="togglePasswordVisibility()">
                                    <span id="lock-icon" class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block" name="login">Sign In</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
        <!-- /.login-box -->

        <?php
            if (isset($_POST['login'])) {
                try {
                    $username = $_POST['username'];
                    $password = $_POST['password'];

                    $get_user   = $portal->query("SELECT * FROM user WHERE username = '$username'");
                    
                    if ($get_user->num_rows > 0 ) {
                        $user = $get_user->fetch_assoc();
                        if(password_verify($password, $user['password'])) {
                            $_SESSION['logistik'] = $user;
                            $_SESSTION['message'] = "Berhasil Login";
                            echo "<script>location='index.php';</script>";
                        } else {
                            echo "<script>alert('Password salah!')</script>";
                            echo "<script>location='login.php';</script>";
                        }
                    } else {
                        echo "<script>alert('Username tidak ditemukan!')</script>";
                        echo "<script>location='login.php';</script>";
                    }
                    // $verify     = password_verify($password, $user['password']);
                    // $ambil      = $koneksi->query("SELECT * FROM user WHERE username = '$username'");
                    // if ($ambil == 1) {
                    //     $akun = $ambil->fetch_assoc();
                    //     $_SESSION['logistik'] = $akun;
                    //     $_SESSION['message'] = "Berhasil Login!";
                    //     echo "<script>location='index.php';</script>";
                    // } else {
                    //     echo "<script>alert('Gagal Login!')</script>";
                    //     echo "<script>location='login.php';</script>";
                    // }
                } catch(Exception $e) {
                    echo $e->getMessage();
                }
            }
        ?>

        <script>
            function togglePasswordVisibility() {
                var passwordInput = document.getElementById("password");
                var lockIcon = document.getElementById("lock-icon");

                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    lockIcon.classList.remove("fa-lock");
                    lockIcon.classList.add("fa-unlock");
                } else {
                    passwordInput.type = "password";
                    lockIcon.classList.remove("fa-unlock");
                    lockIcon.classList.add("fa-lock");
                }
            }
        </script>

        <!-- jQuery -->
        <script src="template/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="template/dist/js/adminlte.min.js"></script>
    </body>
</html>