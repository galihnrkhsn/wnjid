<?php
    session_start();
    include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Wanoja | Manajemen</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="template/images/logo.png">
    <link href="template/css/style.css" rel="stylesheet">

    <style>
        .input-group-text {
            background: #593bdb;
            color: #fff;
            border: 1px solid transparent;
            min-width: 50px;
            display: flex;
            justify-content: center;
        }
        .input-group-text i {
            color: #fff;
            font-size: .85rem;
        }
    </style>
</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <img src="template/images/logo-black.png" class="mb-3" width="100">
                                        <p class="font-weight-bold text-dark mb-4 text-uppercase h3">Wanoja</p>
                                    </div>
                                    <form method="post">
                                        <div class="form-group">
                                            <label><strong>Email</strong></label>
                                            <input type="email" class="form-control" placeholder="example@example.com" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Password</strong></label>
                                            <div class="input-group mb-3">
                                                <input type="password" id="password" class="form-control" placeholder="**********" name="password" required>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" onclick="togglePasswordVisibility()">
                                                        <i id="eye-icon" class="bi bi-eye-slash"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block" name="login">Sign me in</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
        if (isset($_POST['login'])) {
            try {
                // Mengambil data input dan membersihkannya
                $email = trim($_POST['email']);
                $pass = $_POST['password'];
        
                // Menggunakan prepared statement
                $stmt = $koneksi->prepare("SELECT * FROM user_manajemen INNER JOIN role ON user_manajemen.id_role = role.id WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();
        
                // Memastikan data ditemukan sebelum melakukan password verification
                if ($data && password_verify($pass, $data['password'])) {
                    $role   = $data['name'];
                    $id     = $data['id'];
        
                    // Set session untuk user
                    $_SESSION["user_id"] = $id;
                    $_SESSION["user_level"] = $role;
                    $_SESSION['id'] = $id;
        
                    // Redirect berdasarkan role
                    echo "
                        <script>alert('Login berhasil!');</script>
                        <script>location='index.php'</script>
                    ";
                } else {
                    echo "
                        <script>alert('Login gagal! Periksa email atau password Anda.');</script>
                        <script>location='login-multi.php'</script>
                    ";
                }
            } catch (Exception $e) {
                // Menangani error yang mungkin terjadi
                echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "');</script>";
            }
        }
    ?>

    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var eyeIcon = document.getElementById("eye-icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("bi-eye-slash");
                eyeIcon.classList.add("bi-eye");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("bi-eye");
                eyeIcon.classList.add("bi-eye-slash");
            }
        }
    </script>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="../vendor/manajemen-template/global/global.min.js"></script>
    <script src="template/js/quixnav-init.js"></script>
    <script src="template/js/custom.min.js"></script>
</body>
</html>