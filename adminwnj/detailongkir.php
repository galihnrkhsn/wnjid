<?php
    session_start();
    include 'koneksi.php'; 

    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $idadmin = $_GET['id'];
    $sqlmitra = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin = '$idadmin'");
    $datamitra = $sqlmitra->fetch_assoc();
    $namamitra = $datamitra['namamitra'];

    date_default_timezone_set('Asia/Jakarta');
    $current_time = date('Y-m-d H:i:s');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Admin Pusat | Data Ongkir <?= $namamitra ?></title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-metro.css">
    
    <style>
        .aws {
            border:8px solid #eff4ff;;
            
            padding: 10px;
            
        }
        .aws text {
            color: white;
            font-size: x-large;
            text-align: right;
        }
        .aws p {
            color: white;
            text-align: left;
            font-size: ;
        
        }
        .aws button {
            text-align: left;
        }
        .aws a {
            text-align: left;
        }
    </style>
</head>
<body id="page-top" class="sidebar-toggled">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php 
            if ($_SESSION["administrator"]) {
                include "sidebar.php";
            }
        ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Ongkir</h1>
                        <a href="ongkir.php" class="text-primary">Kembali</a>
                    </div>

                    <div class="table-responsive">
                        <table id="table-ongkir" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Distributor</th>
                                    <th>Total Ongkir</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sqlongkir = $koneksi->query("SELECT 
                                                                        ongkir.*, podropship.dropship, podropship.ekspedisi
                                                                    FROM
                                                                        ongkir
                                                                            INNER JOIN
                                                                        podropship ON podropship.iddropship = ongkir.iddropship
                                                                    WHERE
                                                                        ongkir.idadmin = '$idadmin'
                                                                ");
                                    $no = 1;
                                    while ($dataongkir = $sqlongkir->fetch_assoc()) {
                                        if ($dataongkir['ekspedisi'] == "wahana") {
                                            $assurance = 550;
                                        } else {
                                            $assurance = 0;
                                        }
                                        $total_ongkir = $dataongkir['nominal'] + $dataongkir['dropship'] + $assurance;
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $dataongkir['invoice'] ?> <?= $datads['iddropship'] ?></td>
                                        <td>
                                            <?php if ($dataongkir['status'] == "Konfirmasi Ongkir") : ?>
                                                <form method="post" enctype="multipart/form-data">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-transparent" style="border: 0px">
                                                                Rp. 
                                                                <?php if ($dataongkir['dropship'] > 0) : ?>
                                                                    <?= number_format($dataongkir['dropship'] + $assurance) ?> +
                                                                <?php endif; ?>
                                                            </span>
                                                        </div>
                                                        <input type="hidden" class="form-control form-control-sm" name="invoice" value="<?= $dataongkir['invoice'] ?>">
                                                        <input type="hidden" class="form-control form-control-sm" name="idongkir" value="<?= $dataongkir['idongkir'] ?>">
                                                        <input type="text" class="form-control form-control-sm" name="ongkir" id="ongkir_input" value="<?= number_format($dataongkir['nominal']) ?>" required>
                                                        <div class="input-group-append ml-2">
                                                            <button type="submit" class="btn btn-success" name="update_ongkir"><i class="fas fa-pencil-square"></i></button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <?php
                                                    if (isset($_POST['update_ongkir'])) {
                                                        try {
                                                            $ongkir = htmlspecialchars($_POST['ongkir']);
                                                            $ongkir = str_replace(['Rp. ', '.', ','], '', $ongkir);
                                                            $ongkir = (int)$ongkir;
                                                            $idongkir = $_POST['idongkir'];
                                                            $invoice = $_POST['invoice'];

                                                            $sqlpo = $koneksi->query("SELECT * FROM podropship WHERE invoice = '$invoice'");
                                                            $datads = $sqlpo->fetch_assoc();
                                                            $iddropship = $datads['iddropship'];

                                                            $queryupd = $koneksi->query("UPDATE ongkir SET nominal = '$ongkir', updated_at = '$current_time' WHERE idongkir = '$idongkir'");
                                                            $queryupdpo = $koneksi->query("UPDATE podropship SET ongkir = '$ongkir' WHERE iddropship = '$iddropship'");
                                                            if ($queryupd && $queryupdpo) {
                                                                echo "
                                                                    <script>
                                                                        alert('Ongkir berhasil diubah!')
                                                                        location='detailongkir.php?id=$idadmin'
                                                                    </script>
                                                                ";
                                                            } else {
                                                                echo "
                                                                    <script>
                                                                        alert('Gagal mengubah ongkir!')
                                                                        location='detailongkir.php?id=$idadmin'
                                                                    </script>
                                                                ";
                                                            }
                                                        } catch (Exception $e) {
                                                            echo "Error: " . $e->getMessage();
                                                        }
                                                    }
                                                ?>
                                            <?php else : ?>
                                                Rp. <?= number_format($total_ongkir) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($dataongkir['status'] == "Lunas") : ?>
                                                <div class="badge badge-success"><?= $dataongkir['status'] ?></div>
                                            <?php elseif ($dataongkir['status'] == "Konfirmasi Admin") : ?>
                                                <div class="badge badge-warning"><?= $dataongkir['status'] ?></div>
                                            <?php else : ?>
                                                <div class="badge badge-danger"><?= $dataongkir['status'] ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal-<?= $dataongkir['idongkir'] ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal-<?= $dataongkir['idongkir'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Bukti Transfer Ongkir <?= $dataongkir['invoice'] ?></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <?php if ($dataongkir['buktitf'] == NULL) : ?>
                                                                <p class="mb-0">Bukti Transfer Belum di Kirim!</p>
                                                            <?php else : ?>
                                                                <img width="50%" src="ongkir/<?= $dataongkir['buktitf'] ?>">
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <form method="post" enctype="multipart/form-data" class="d-inline">
                                                <input type="hidden" class="form-control form-control-sm" value="<?= $dataongkir['idongkir'] ?>" name="idongkir">
                                                <?php if ($dataongkir['status'] == "Konfirmasi Ongkir") : ?>
                                                    <button type="submit" class="btn btn-success btn-sm" name="update">Konfirmasi</button>
                                                <?php elseif ($dataongkir['status'] == "Konfirmasi Admin") : ?>
                                                    <button type="submit" class="btn btn-success btn-sm" name="update_status">Konfirmasi</button>
                                                <?php endif; ?>
                                            </form>
                                            <?php
                                                if (isset($_POST['update'])) {
                                                    try {
                                                        $idongkir = $_POST['idongkir'];
                                                        $sqlupt = $koneksi->query("UPDATE ongkir SET status = 'Belum Bayar' WHERE idongkir = '$idongkir'");
                                                        if ($sqlupt) {
                                                            echo "
                                                                <script>
                                                                    alert('Status berhasil diubah!')
                                                                    location='detailongkir.php?id=$idadmin'
                                                                </script>
                                                            ";
                                                        } else {
                                                            echo "
                                                                <script>
                                                                    alert('Gagal mengubah status!')
                                                                    location='detailongkir.php?id=$idadmin'
                                                                </script>
                                                            ";
                                                        }
                                                    } catch (Exception $e) {
                                                        echo "Error: " . $e->getMessage();
                                                    }
                                                } elseif (isset($_POST['update_status'])) {
                                                    try {
                                                        $idongkir = $_POST['idongkir'];
                                                        $sqlupt = $koneksi->query("UPDATE ongkir SET status = 'Lunas', updated_at = '$current_time' WHERE idongkir = '$idongkir'");
                                                        if ($sqlupt) {
                                                            echo "
                                                                <script>
                                                                    alert('Status berhasil diubah!')
                                                                    location='detailongkir.php?id=$idadmin'
                                                                </script>
                                                            ";
                                                        } else {
                                                            echo "
                                                                <script>
                                                                    alert('Gagal mengubah status!')
                                                                    location='detailongkir.php?id=$idadmin'
                                                                </script>
                                                            ";
                                                        }
                                                    } catch (Exception $e) {
                                                        echo "Error: " . $e->getMessage();
                                                    }
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script>
        const ongkirInput = document.getElementById('ongkir_input');

        // Function to format number with thousands separator
        function formatNumber(number) {
            return number.replace(/\D/g, '') // Remove non-digit characters
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ','); // Add thousands separator
        }

        // Function to handle input changes
        ongkirInput.addEventListener('input', function() {
            // Store the raw number without formatting
            let rawValue = this.value.replace(/\./g, ''); // Remove dots
            this.value = formatNumber(rawValue); // Format value with dots
        });
    </script>
    <?php include "settingdatatables.php"; ?>
</body>
</html>
