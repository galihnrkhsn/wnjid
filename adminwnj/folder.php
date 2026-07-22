<?php 
    session_start();

    include 'koneksi.php'; 

    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Admin Pusat | Wanoja</title>

  <!-- Custom fonts for this template-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div class="container-fluid">
            <div class="d-flex justify-content-between mb-0">
                <h4 style="color: #153448;">Input Produk</h4>
                <a href="folder.php" class="text-primary mt-1">Kembali</a>
            </div>

            <hr class="my-2">

            <form method="post">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="d-flex align-items-center mb-0">Nama Folder Foto<p class="text-danger p-0 m-0 ml-1">*</p></label>
                            <input type="text" id="name" class="form-control form-control-sm" placeholder="Masukan nama folder foto" name="name" required>
                        </div>

                        <button class="btn btn-primary btn-sm" name="submit">Kirim</button>
                    </div>
                </div>
            </form>

            <hr class="my-2" />

            <div class="table-responsive">
                <table class="table table-bordered" id="tbFolder">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Folder</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>#</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                            $no = 1;
                            $query = $koneksi->query("SELECT * FROM folder");
                            while ($data = $query->fetch_assoc()) {
                                $datetime = New DateTime($data['created_at']);
                                $tgl = $datetime->format('Y-m-d');
                                $waktu = $datetime->format('H:i');
                        ?>
                            <tr>
                                <th><?= $no++ ?></th>
                                <td><?= $data['name'] ?></td>
                                <td><?= $tgl ?></td>
                                <td><?= $waktu ?></td>
                                <td>
                                    <button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-warning btn-sm"><i class="fas fa-edit" style="font-size: 0.8rem"></i></button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            ...
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                    <a href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash" style="font-size: 0.8rem"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php
        if (isset($_POST['submit'])) {
            date_default_timezone_set("Asia/Jakarta");
            $name = $_POST['name'];
            $folder = ucfirst($name);

            $path = "../assets/foto/produk/$folder";

            if (!file_exists($path)) {
                if (mkdir($path, 0777, true)) {
                    $query = $koneksi->query("INSERT INTO folder VALUES (NULL, '$folder', NOW())");

                    if ($query) {
                        echo "
                            <script>alert('Folder berhasil dibuat!');</script>
                            <script>location='folder.php'</script>
                        ";
                    } else {
                        echo "
                            <script>alert('$query->error');</script>
                            <script>location='folder.php'</script>
                        ";
                    }
                } else {
                    var_dump(error_get_last());
                    // echo "
                    //     <script>alert('Folder gagal dibuat!');</script>
                    //     <script>location='tambah_folder.php'</script>
                    // ";
                }
            } else {
                echo "
                    <script>alert('Folder sudah ada!');</script>
                    <script>location='tambah_folder.php'</script>
                ";
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
    <?php include "settingdatatables.php"; ?>
</body>
</html>