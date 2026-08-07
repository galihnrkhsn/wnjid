<?php
    session_start();
    include "koneksi.php";

    if (!isset($_SESSION["administrator"])) {
        echo "<script>alert('Anda harus login terlebih dahulu!');</script>";
        echo "<script>location='login.php'</script>";
        header('location:login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WNJ.ID Corp</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.js"></script>

    <style type="text/css">
        body{
            padding-right: 0px ! important;
        }    
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input { 
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
</head>
<body id="page-top" class="sidebar-toggled">
    <div id="wrapper">
        <?php include "sidebar.php" ?>
            
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between">
                <h3 class="mb-0">Input Data User Manajemen</h3>
                <a href="user_manajemen.php">Kembali</a>
            </div>

            <hr />

            <form method="post" class="my-3">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="username" class="mb-0">Username <span class="text-danger">*</span></label>
                            <input type="text" id="username" class="form-control form-control-sm" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="mb-0">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" class="form-control form-control-sm" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="role" class="mb-0">User Role <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" name="role" required>
                                <option value="">~ Default Selected ~</option>
                                <?php
                                    $sql = $koneksi->query("SELECT * FROM role");
                                    while ($data = $sql->fetch_assoc()) {
                                ?>
                                    <option value="<?= $data['id'] ?>"><?= $data['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="password" class="mb-0">Password <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="password" placeholder="********" name="password">
                                <div class="input-group-append">
                                    <span class="input-group-text" onclick="togglePasswordVisibility()"><i id="eye-icon" class="fas fa-eye-slash"></i></span>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-sm" name="kirim">Kirim</button>
                    </div>
                </div>
            </form>
        </div>
        
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2020</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->
    </div>

    <?php
        include "koneksi.php";
        if (isset($_POST["kirim"])) {
            try {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $role = $_POST['role'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                
                $check_email = $koneksi->query("SELECT * FROM user_manajemen WHERE email = '$email'");
                if ($check_email->num_rows > 0) {
                    echo "<script>
                        alert('Email sudah terdaftar');
                        window.location.href = 'input_user.php'
                    </script>";
                    exit();
                    }
                    
                    $query = $koneksi->query("INSERT INTO user_manajemen VALUES (NULL, '$role', '$username', '$email', '$password', NOW())");
                    
                if ($query) {
                    echo "<script>
                        alert('User berhasil ditambahkan!');
                        window.location.href = 'user_manajemen.php'
                    </script>";
                } else {
                    echo "<script>
                        alert('User gagal ditambahkan!');
                        window.location.href = 'user_manajemen.php'
                    </script>";
                }
            } catch(Exception $e) {
                echo "Error: " . $e->getMessage();
                die();
            }
        }
    ?>

    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var eyeIcon = document.getElementById("eye-icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            }
        }
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

    <?php include "settingdatatables.php"; ?>
</body>
</html>