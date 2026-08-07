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

    <title>WNJ.ID</title>

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
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include "sidebar.php"; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-3">
                <h1 class="h4 text-gray-800 fw-bold mb-0">Data Distributor</h1>
            </div>

            <?php 
                $query  = "SELECT 
                                SUM(CASE WHEN status = 0 AND kota <> '' THEN 1 ELSE 0 END) AS tampil,
                                SUM(CASE WHEN (kota = '' OR kota IS NULL) AND (status = 0 OR status = 1) THEN 1 ELSE 0 END) AS tidak_tampil
                            FROM admin_mitra
                        ";
                $result = $koneksi->query($query)->fetch_assoc();
            ?>

            <!-- Informasi Jumlah Status -->
            <div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-info text-white rounded-pill">
                    Status Tampil: <?= $result['tampil'] ?? 0 ?> Akun
                </span>
                <span class="badge bg-danger text-white rounded-pill ml-2">
                    Non-Tampil: <?= $result['tidak_tampil'] ?? 0 ?> Akun
                </span>
            </div>

            <!-- Tombol Aksi -->
            <div class="mb-4 d-flex flex-wrap gap-2">
                <a href="input_mitra.php" class="btn btn-primary btn-sm rounded">
                    <i class="fas fa-plus"></i> Tambah Distributor
                </a>
                <a href="excel_mitra.php" class="btn btn-success btn-sm rounded ml-2">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
            <div class="table-responsive" style="margin-top: 3%">
                <form method="post" class="form-inline mb-3">
                    <div class="form-group mr-2">
                        <label for="namacs" class="mr-2 font-weight-bold">Nama CS:</label>
                        <select class="form-control" name="namacs" id="namacs">
                            <option disabled selected>- Pilih Nama CS -</option>
                            <option value="Semua CS">Semua CS</option>
                            <?php
                            $datadb = $koneksi->query("SELECT DISTINCT namacs FROM admin_mitra_cs WHERE namacs IS NOT NULL AND namacs <> '' ORDER BY namacs ASC");
                            while ($tampilkan = $datadb->fetch_assoc()) {
                                $selected = (isset($_POST['namacs']) && $_POST['namacs'] === $tampilkan['namacs']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($tampilkan['namacs']) . '" ' . $selected . '>' . htmlspecialchars($tampilkan['namacs']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <button class="btn btn-primary" type="submit" name="cari">Cari</button>
                </form>
                <table class="table table-striped table-bordered table-hover" id="tbmitra">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Private Order</th>
                            <th>CS</th>
                            <th style="text-align: center">Nama DB (Kode)</th>
                            <th style="text-align: center">Password</th>
                            <th style="text-align: center">Email</th>
                            <th style="text-align: center">Whatsapp</th>
                            <th style="text-align: center">Kota</th>
                            <th style="text-align: center">Alamat </th>
                            <th style="text-align: right;"><i class="fas fa-cog"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $where = '';
                            if (isset($_POST['cari'])) {
                                $namacs = trim($_POST['namacs'] ?? '');

                                $namacs_safe = $koneksi->real_escape_string($namacs);

                                if ($namacs_safe !== '' && $namacs_safe !== 'Semua CS') {
                                    $where = "WHERE amcs.namacs = '$namacs_safe'";
                                }
                            }

                            $query  = "SELECT
                                            am.idadmin,
                                            am.privateorder,
                                            am.namamitra,
                                            am.email,
                                            am.alamat,
                                            am.whatsapp,
                                            tc.city_name,
                                            amcs.namacs,
                                            am.status
                                        FROM admin_mitra AS am
                                        LEFT JOIN tb_ro_cities tc ON am.kota = tc.city_id
                                        LEFT JOIN admin_mitra_cs AS amcs ON amcs.idadmin = am.idadmin
                                        $where
                                        ORDER BY am.idadmin DESC    
                                    ";
                            $tampil = $koneksi->query($query);

                            while($tampilMas = $tampil->fetch_assoc()){
                        ?>
                            <tr>
                                <td class="py-1">
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?= $tampilMas['idadmin']; ?>">
                                        <input type="hidden" name="nilaistatus" value="<?= $tampilMas['status']; ?>">
                                        <label class="switch">
                                            <input type="checkbox" name="gantistatus" onclick="this.form.submit()" <?= $tampilMas['status'] == 0 ? 'checked' : ''; ?> >
                                            <span class="slider round"></span>
                                        </label>
                                    </form>
                                </td>
                                <td class="py-1">
                                    <form method="post" id="formPrivate<?= $tampilMas['idadmin']; ?>">
                                        <input type="hidden" name="id" value="<?= $tampilMas['idadmin']; ?>">
                                        <input type="hidden" name="nilaiprivate" id="privateValue<?= $tampilMas['idadmin']; ?>" value="<?= $tampilMas['privateorder']; ?>">
                                        <input type="hidden" name="gantiprivate" value="1">
                                        <label class="switch">
                                            <input 
                                                type="checkbox"
                                                <?= $tampilMas['privateorder'] === 'on' ? 'checked' : ''; ?>
                                                onchange="submitPrivateForm(<?= $tampilMas['idadmin']; ?>, this.checked)"
                                            >
                                            <span class="slider round"></span>
                                        </label>
                                    </form>

                                    <script>
                                    function submitPrivateForm(id, isChecked) {
                                        const inputValue = document.getElementById('privateValue' + id);
                                        inputValue.value = isChecked ? 'off' : 'on'; // jika dicek, artinya sebelumnya off → mau jadi on
                                        document.getElementById('formPrivate' + id).submit();
                                    }
                                    </script>

                                </td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#modalView<?= $tampilMas['idadmin']; ?>">
                                        <?= $tampilMas['namacs'] ? $tampilMas['namacs'] : 'Pilih CS'; ?>
                                    </a>

                                    <!-- Modal View CS -->
                                    <div class="modal fade" id="modalView<?= $tampilMas['idadmin']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalViewLabel<?= $tampilMas['idadmin']; ?>" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Pilih atau Tambah CS</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form method="POST" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?= $tampilMas['idadmin']; ?>">
                                                        <input type="hidden" name="namamitra" value="<?= $tampilMas['namamitra']; ?>">
                                                        <div class="form-group">
                                                            <label>Nama CS</label>
                                                            <select name="namacs" class="form-control">
                                                                <option value="<?= $tampilMas['namacs']; ?>"><?= $tampilMas['namacs']; ?></option>
                                                                <?php
                                                                    $ambil_cs = $koneksi->query("SELECT DISTINCT namacs FROM admin_mitra_cs WHERE namacs <> '' ORDER BY namacs ASC");
                                                                    while ($tampil_cs = $ambil_cs->fetch_assoc()) {
                                                                        echo '<option value="' . htmlspecialchars($tampil_cs['namacs']) . '">' . htmlspecialchars($tampil_cs['namacs']) . '</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Tambah Nama CS Baru</label>
                                                            <input type="text" class="form-control" name="csbaru" placeholder="Nama CS Baru">
                                                            <small class="form-text text-danger">* Jika CS tidak tersedia, masukkan nama baru dan klik "Tambah CS".</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary" name="simpan_cs">Simpan</button>
                                                        <button type="submit" class="btn btn-secondary" name="inputcs">Tambah CS</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-user"></i> <?= $tampilMas['namamitra']; ?> (<?= $tampilMas['idadmin']; ?>)
                                </td>
                                <td>
                                    <i class="fas fa-lock text-muted"></i> ********
                                </td>
                                <td>
                                    <i class="fas fa-envelope text-danger"></i> <?= $tampilMas['email']; ?>
                                </td>
                                <td>
                                    <i class="fa fa-whatsapp text-success"></i> <?= $tampilMas['whatsapp']; ?>
                                </td>
                                <td>
                                    <i class="fas fa-map-marker-alt text-danger"></i> <?= $tampilMas['city_name']; ?>
                                </td>
                                <td>
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                    <?= substr($tampilMas['alamat'], 0, 10); ?><span id="dots<?= $tampilMas['idadmin']; ?>">...</span>
                                    <span id="more<?= $tampilMas['idadmin']; ?>" style="display:none;">
                                        <?= substr($tampilMas['alamat'], 10, 200); ?>
                                    </span>
                                    <button onclick="toggleAlamat<?= $tampilMas['idadmin']; ?>()" class="btn btn-outline-secondary btn-sm">
                                        <i class='fa fa-eye'></i>
                                    </button>
                                    <script>
                                        function toggleAlamat<?= $tampilMas['idadmin']; ?>() {
                                            var dots = document.getElementById("dots<?= $tampilMas['idadmin']; ?>");
                                            var moreText = document.getElementById("more<?= $tampilMas['idadmin']; ?>");
                                            if (dots.style.display === "none") {
                                                dots.style.display = "inline";
                                                moreText.style.display = "none";
                                            } else {
                                                dots.style.display = "none";
                                                moreText.style.display = "inline";
                                            }
                                        }
                                    </script>
                                </td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?= $tampilMas['idadmin']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" name="hapus"><i class="fa fa-trash"></i></button>
                                        <button type="submit" class="btn btn-info btn-sm" name="resetPassword"><i class="fa fa-power-off"></i></button>
                                        <a class="btn btn-success btn-sm" href="ubahmitra.php?id=<?= $tampilMas['idadmin']; ?>"><i class="fa fa-pencil"></i></a>
                                    </form>
                                </td>
                            </tr>

                            <script>
                                function myFunction<?php echo $tampilMas['idadmin']; ?>() {
                                    var dots = document.getElementById("dots<?php echo $tampilMas['idadmin']; ?>");
                                    var moreText = document.getElementById("more<?php echo $tampilMas['idadmin']; ?>");
                                    var btnText = document.getElementById("myBtn<?php echo $tampilMas['idadmin']; ?>");
                                
                                    if (dots.style.display === "none") {
                                        dots.style.display = "inline";
                                        btnText.innerHTML = "<i class='fa fa-eye'></i>"; 
                                        moreText.style.display = "none";
                                    } else {
                                        dots.style.display = "none";
                                        btnText.innerHTML = "<i class='fa fa-eye-slash'></i>"; 
                                        moreText.style.display = "inline";
                                    }
                                }
                            </script>
                        <?php } ?>
                    </tbody>
                </table>
                <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $idadmin = (int)$_POST['id'] ?? null;

                        if (isset($_POST['hapus']) && $idadmin) {
                            $koneksi->query("DELETE FROM admin_mitra WHERE idadmin = '$idadmin'");
                            echo "<script>alert('Data sudah terhapus'); location='mitra.php';</script>";
                            exit;
                        }

                        if (isset($_POST['nilaistatus']) && $idadmin) {
                            $status         = $_POST['nilaistatus'];
                            $statusBaru     = $status == "0" ? 1 : 0;

                            $statusMsg      = $statusBaru === 0 ? 'Status telah diaktifkan' : 'Status telah dinonaktifkan';
                            $koneksi->query("UPDATE admin_mitra SET status = $statusBaru WHERE idadmin = '$idadmin'");
                            echo "<script>alert('$statusMsg'); location='mitra.php';</script>";
                            exit;
                        }

                        if (isset($_POST['gantiprivate']) && $idadmin && isset($_POST['nilaiprivate'])) {
                            $current        = $_POST['nilaiprivate'] ?? 'off';
                            $private        = $current === 'on' ? 'off' : 'on';
                            $privateMsg     = $private === 'on' ? 'Private Order telah diaktifkan' : 'Private Order telah dimatikan';
                            
                            $koneksi->query("UPDATE admin_mitra SET privateorder = '$private' WHERE idadmin = '$idadmin'");
                            echo "<script>alert('$privateMsg'); location='mitra.php';</script>";
                            exit;
                        }

                        if (isset($_POST['resetPassword']) && $idadmin) {
                            $queryDb    = $koneksi->query("SELECT email, iduser FROM admin_mitra WHERE idadmin = '$idadmin'");

                            if ($queryDb && $queryDb->num_rows > 0) {
                                $dataDb = $queryDb->fetch_assoc();
                                $email  = $dataDb['email'];
                                $idUser = $dataDb['iduser'];

                                if ($email && $idUser) {
                                    $newPassword    = $email . '_' . $idadmin;
                                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                                    $update         = $koneksi->query("UPDATE users SET password = '$hashedPassword' WHERE id = '$idUser'");

                                    $msg = $update ? 'Password berhasil diubah' : 'Gagal mengubah password';
                                    echo "<script>alert('$msg'); location='mitra.php';</script>";
                                } else {
                                    echo "<script>alert('Email tidak ditemukan');</script>";
                                }
                            } else {
                                echo "<script>alert('Data admin tidak ditemukan');</script>";
                            }
                        }

                        if (isset($_POST['simpan_cs']) && isset($idadmin)) {
                            $namaCS     = $_POST['namacs'];
                            $namamitra  = $_POST['namamitra'];
                            $sql        = $koneksi->query("INSERT INTO admin_mitra_cs 
                                                                (idadmin, namamitra, namacs)
                                                            VALUES
                                                                ('$idadmin', '$namamitra', '$namaCS')
                                                        ");
                            echo "<script>alert('Berhasil ubah CS'); location='mitra.php';</script>";
                            exit;
                        }
                    }
                ?>
                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Your Website 2020</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->
            </div>
            <!-- End of Content Wrapper -->
        </div>
    <!-- End of Page Wrapper -->

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

		                    