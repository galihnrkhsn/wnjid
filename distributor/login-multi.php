<?php 
    session_start();

    include 'koneksi.php';
    include 'cek_keterangan.php';
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login | WNJ.ID</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <script type="text/javascript">
        // Function to show an alert and redirect to login page
        function showAlertWithRedirect() {
            if (confirm("Silahkan login terlebih dahulu")) {
                // Redirect to login page
                window.location.href = "https://wnj.id/";
            }
        }

        // Call the function immediately after the page loads
        window.onload = function() {
            showAlertWithRedirect();
        }
    </script>
        <style>
            body, html {
                height: 100%;
                margin: 0;
            }

            .card-login {
                position: relative;
                width: 100%;
                height: 100%;
            }
             
            .card-login-v2 {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            .card-login .card-login-v2 .image {
                width: 25%;
            }

            @media (max-width: 768px) {
                .card-login .card-login-v2 .image {
                    width: 75%;
                }
            }
        </style>
    </head>
    <body>
        <div class="card-login">
            <div class="card-login-v2 container">
                <div class="row text-center d-flex justify-content-center">
                    <div class="col-sm-12 mb-4">
                        <img src="../assets/foto/img/mitra-login.png" class="image">
                    </div>
                    <div class="col-lg-4 text-start">
                        <form method="post">
                            <div class="form-group mb-2">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                            </div>
                            <div class="form-group mb-2">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
                                    <span class="input-group-text toggle-password" onclick="togglePasswordVisibility()">
                                        <i id="eye-icon" class="bi bi-eye-slash"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <button class="btn btn-primary" name="login">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.login-logo -->

        <?php
            if(isset($_POST['login'])) {
                // $koneksi->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
                try {
                    $email = $_POST['email'];
                    $pass = $_POST['password'];
    
                    $user = $koneksi->query("SELECT * FROM users WHERE email = '$email'");
                    $data = $user->fetch_assoc();

                    if (password_verify($pass, $data['password'])) {
                        $role = $data['role'];
                        $id = $data['id'];

                        $_SESSION["user_id"] = $id;
                        $_SESSION["user_level"] = $role;

                        if ($role == "distributor") {
                            $queryDistributor = $koneksi->query("SELECT idadmin FROM admin_mitra WHERE iduser = '$id'");
                            $distributorData = $queryDistributor->fetch_assoc();
                            $_SESSION["idadmin"] = $distributorData["idadmin"];
                            echo "<script>alert('Login berhasil!')</script>";
                            echo "<script>location='index2.php'</script>";
                        } elseif ($role == "agen") {
                            $query_agen = $koneksi->query("SELECT idmitraagen FROM mitraagen WHERE iduser = '$id'");
                            $agen_data = $query_agen->fetch_assoc();
                            $_SESSION["idmitraagen"] = $agen_data["idmitraagen"];
                            echo "<script>alert('Login berhasil!')</script>";
                            echo "<script>location='../agen/index3.php'</script>";
                        } elseif ($role == "reseller") {
                            $query_agen = $koneksi->query("SELECT idmitrareseller FROM mitrareseller WHERE iduser = '$id'");
                            $agen_data = $query_agen->fetch_assoc();
                            $_SESSION["idmitrareseller"] = $agen_data["idmitrareseller"];
                            echo "<script>alert('Login berhasil!')</script>";
                            echo "<script>location='../reseller/index3.php'</script>";
                            echo "Berhasil! " . $role;
                        } elseif ($role == "marketer") {
                            $query_agen = $koneksi->query("SELECT idmitramarketer FROM mitramarketer WHERE iduser = '$id'");
                            $agen_data = $query_agen->fetch_assoc();
                            $_SESSION["idmitramarketer"] = $agen_data["idmitramarketer"];
                            echo "<script>alert('Login berhasil!')</script>";
                            echo "<script>location='../marketer/index2.php'</script>";
                            echo "Berhasil! " . $role;
                        } else {
                            echo "Login gagal!";
                        }
                    } else {
                        $management = $koneksi->query("SELECT * FROM management WHERE email = '$email'");
                        $managementData = $management->fetch_assoc();
        
                        if ($managementData && password_verify($pass, $managementData['password'])) {
                            $tipe = $managementData['tipe'];
                            $id = $managementData['id'];
        
                            $_SESSION["user_id"] = $id;
                            $_SESSION["user_tipe"] = $tipe;
        
                            echo "<script>alert('Login berhasil!')</script>";
                            if ($tipe == "M") {
                                $_SESSION["idmanage"] = $managementData["id"];
                                echo "<script>location='../manajemen/index'</script>";
                            } elseif ($tipe == "P") {
                                $_SESSION["idmanage"] = $managementData["id"];
                                echo "<script>location='../produksi/index'</script>";
                            }
                        } else {
                            echo "<script>alert('Login gagal!')</script>";
                            echo "<script>location='login-multi'</script>";
                        }
                    }
                } catch (mysqli_sql_exception $e) {
                    echo "Error: " . $e->getMessage();
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

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    </body>
</html>