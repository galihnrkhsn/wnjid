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
    <title>Admin Pusat | Wanoja Corp</title>

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
            <div class="d-sm-flex align-items-center">
                <h3 class="mb-0">News</h3>
            </div>
        </div>

        <div class="container-fluid">
            <form method="post" class="my-3" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="image">Choose Image:</label>
                            <input type="file" name="image" id="image" accept="image/*" required>
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
        if (isset($_POST['kirim'])) {
            $uploadDir = '../distributor/assets/img/news/';
            $targetFile = $uploadDir . 'news.jpg';
    
            // Cek apakah ada file yang diunggah
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
                // Ambil informasi file
                $fileTmpPath    = $_FILES['image']['tmp_name'];
                $fileName       = $_FILES['image']['name'];
                $fileType       = $_FILES['image']['type'];
                $fileError      = $_FILES['image']['error'];
                $fileSize       = $_FILES['image']['size'];
    
                // Validasi jenis file (hanya gambar)
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($fileType, $allowedMimeTypes)) {
                    echo "Error: File type not allowed. Only JPEG, PNG, or GIF are accepted.";
                    exit;
                }
    
                // Validasi ukuran file (maksimal 2MB)
                $maxFileSize = 2 * 1024 * 1024; // 2MB
                if ($fileSize > $maxFileSize) {
                    echo "Error: File size exceeds 2MB.";
                    exit;
                }
    
                // Hapus file lama jika ada
                if (file_exists($targetFile)) {
                    unlink($targetFile);
                }
    
                // Pindahkan file baru ke lokasi tujuan dengan nama "news.jpg"
                if (move_uploaded_file($fileTmpPath, $targetFile)) {
                    echo "File uploaded successfully!";
                    header("Location: news.php");
                } else {
                    echo "Error: Unable to upload the file.";
                }
            } else {
                echo "No file uploaded.";
            }
        }
    ?>


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