<?php 
    session_start();
    include 'koneksi.php'; 

    if (!isset($_SESSION["administrator"])) {
        header('Location: login.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>WNJ.ID</title>

    <!-- Custom fonts & styles -->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h3><strong>Image Slider</strong></h3>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="1">No</th>
                            <th>Nama</th>
                            <th>Photo</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $no         = 1;
                            $dataproduk = $koneksi->query("SELECT * FROM slider WHERE tipe = 'banner'");
                            while ($tampilkan = $dataproduk->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $tampilkan['foto']; ?></td>
                            <td>
                                <img src="../distributor/assets/img/news/<?= $tampilkan['foto']; ?>" 
                                    alt="slider" width="150" class="mb-2"><br>
                            </td>
                            <form method="POST" enctype="multipart/form-data">
                                <td>
                                    <input type="hidden" name="id" value="<?= $tampilkan['id']; ?>">
                                    <input type="hidden" name="tipe" value="<?= $tampilkan['tipe']; ?>">
                                    <input type="file" name="photo" accept="image/*" required>
                                </td>
                                <td>
                                    <button type="submit" name="updatebanner" class="btn btn-sm btn-primary">Update</button>
                                </td>
                            </form>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php
                if (isset($_POST['updatebanner'])) {
                    $id     = (int) $_POST['id'];
                    $tipe   = $_POST['tipe'];
                    var_dump('as');
                    die();
                    try {
                        $koneksi->begin_transaction();

                        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                            $result = $koneksi->query("SELECT foto FROM slider WHERE id = $id")->fetch_assoc();

                            $oldFileName = pathinfo($result['foto'], PATHINFO_FILENAME);
                            $ext         = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

                            $newFileName = $oldFileName . "." . $ext;

                            $targetDir  = "../distributor/assets/img/news/";
                            $targetFile = $targetDir . $newFileName;

                            foreach (glob($targetDir . $oldFileName . ".*") as $oldFile) {
                            unlink($oldFile);
                            }

                            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                                throw new Exception("Upload file gagal!");
                            }

                            $update = $koneksi->query("UPDATE slider SET foto = '$newFileName' WHERE id = '$id'");
                            if (!$update) {
                                throw new Exception("Gagal update database: " . $koneksi->error);
                            }
                        }

                        $koneksi->commit();
                        echo "<script>
                            alert('Banner berhasil diupdate!');
                            window.location.href = 'banner.php';
                        </script>";
                        exit();

                    } catch (Exception $e) {
                        $koneksi->rollback();
                        echo "Terjadi kesalahan: " . $e->getMessage();
                    }
                }
            ?>


            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>&copy; WNJ.ID <?= date('Y'); ?></span>
                    </div>
                </div>
            </footer>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts -->
    <script src="js/sb-admin-2.min.js"></script>

    <?php include "settingdatatables.php"; ?>
</body>
</html>
