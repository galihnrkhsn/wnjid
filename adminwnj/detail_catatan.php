<?php
    session_start();
    include 'koneksi.php';

    if (!isset($_SESSION["administrator"])) {
        echo "<script>
            alert('Anda harus login terlebih dahulu!');
            location='login.php';
        </script>";
        header('location:login.php');
        exit();
    }

    $tgl = $_GET['tanggal'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pusat | Wanoja</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include 'sidebar.php'?>

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="h3 font-weight-bold mb-0 text-gray-800">Catatan <?= $tgl ?></h1>
                <a href="catatan.php">Kembali</a>
            </div>

            <hr />

            <div class="d-flex mb-3">
                <a href="input_catatan.php?tanggal=<?= $tgl; ?>" class="btn btn-primary btn-sm">Tambah Catatan</a>
                <a href="excel_catatan.php?tanggal=<?= $tgl; ?>" class="btn btn-success btn-sm mx-2">Excel</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="tbmaximus">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Rekening</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            $query = $koneksi->query("SELECT * FROM catatan WHERE created_at = '$tgl' ORDER BY id DESC");
                            while ($data = $query->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $data['rekening'] ?></td>
                                <td>Rp. <?= number_format($data['nominal']) ?></td>
                                <td><?= $data['tanggal'] ?></td>
                                <td><?= $data['waktu'] ?></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" class="form-control form-control-sm" value="<?= $data['id'] ?>" name="id">
                                        <button type="submit" class="btn btn-danger btn-sm" name="delete"><i class="fas fa-trash"></i></button>
                                    </form>

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModal_<?= $data['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal_<?= $data['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Ubah Data</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form method="post">
                                                    <div class="modal-body">
                                                        <input type="hidden" class="form-control form-control-sm" value="<?= $data['id'] ?>" name="id" readonly>
                                                        <div class="form-group">
                                                            <select class="form-control form-control-sm" name="rekening">
                                                                <option value="<?= $data['rekening'] ?>"><?= $data['rekening'] ?></option>
                                                                <?php
                                                                    $sql = $koneksi->query("SELECT * FROM rekeningwnj ORDER BY namabank");
                                                                    while ($data1 = $sql->fetch_assoc()) {
                                                                ?>
                                                                    <option value="<?= $data1['namabank'] ?>"><?= $data1['namabank'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="number" class="form-control form-control-sm" value="<?= $data['nominal'] ?>" name="nominal">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success btn-sm" name="ubah">Ubah Data</button>
                                                    </div>
                                                </form>

                                                <?php
                                                    if (isset($_POST['ubah'])) {
                                                        try {
                                                            date_default_timezone_set('Asia/Jakarta');
                                                            $waktu = date('H:i:s');
                                                            $nominal = $_POST['nominal'];
                                                            $rekening = $_POST['rekening'];
                                                            $idcatatan = $_POST['id'];

                                                            $sql = $koneksi->query("UPDATE catatan SET nominal = '$nominal', rekening = '$rekening', waktu = '$waktu' WHERE id = '$idcatatan'");
                                                            if ($sql) {
                                                                echo "
                                                                    <script>
                                                                        alert('Data berhasil diubah!');
                                                                        location='detail_catatan.php?tanggal=$tgl';
                                                                    </script>
                                                                ";
                                                            } else {
                                                                echo "
                                                                    <script>
                                                                        alert('Data gagal diubah!');
                                                                        location='detail_catatan.php?tanggal=$tgl';
                                                                    </script>
                                                                ";
                                                            }
                                                        } catch (Exception $e) {
                                                            echo "Error: " . $e->getMessage();
                                                        }
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                        if (isset($_POST['delete'])) {
                                            try {
                                                $idcatatan = $_POST['id'];

                                                $query = $koneksi->query("DELETE FROM catatan WHERE id = '$idcatatan'");
                                                if ($query) {
                                                    $check = $koneksi->query("SELECT * FROM catatan WHERE tanggal = '$tgl'");
                                                    $jumlah = $check->num_rows;

                                                    echo "<script>alert('Data berhasil dihapus!'); </script>";
                                                    if ($jumlah > 0) {
                                                        echo "<script>location='detail_catatan.php?tanggal=$tgl';</script>";
                                                    } else {
                                                        echo "<script>location='catatan.php';</script>";
                                                    }
                                                } else {
                                                    echo "
                                                        <script>
                                                            alert('Data gagal dihapus!');
                                                            location='detail_catatan.php?id=$tgl';
                                                        </script>
                                                    ";
                                                }
                                            } catch(Exception $e) {
                                                echo "Error: " . $e->getMessage();
                                            }
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php 
                                $total += $data['nominal'];
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total</th>
                            <th colspan="3">Rp. <?= number_format($total) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <?php include "settingdatatables.php"; ?>
</body>
</html>