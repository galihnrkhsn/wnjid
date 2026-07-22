<?php 
    error_reporting(E_ALL);
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);

    session_start();
    include 'koneksi.php';
    include 'vendor/autoload.php';

    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        exit();
    }
?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Upload Excel Mitra</title>

    <!-- Font -->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700" rel="stylesheet">

    <!-- SB Admin -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top" class="sidebar-toggled">

<div id="wrapper">

    <?php include "sidebar.php"; ?>

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            <div class="container-fluid">

                <!-- Title -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 text-gray-800">
                        Upload Pembagian DB Mitra
                    </h1>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    Export File Data Distributor
                                </h6>
                            </div>
                            <div class="card-body">
                                <form action="export_excel_updatecs.php" method="POST">
                                    <div class="form-group">
                                        <label>Tanggal Awal</label>
                                        <input type="date"
                                            name="tanggal_awal"
                                            class="form-control"
                                            value="<?= date('Y-m-01') ?>"
                                            required>
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-file-excel"></i>
                                        Export File Excel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">

                        <div class="card shadow mb-4">

                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    Upload File Excel
                                </h6>
                            </div>
                            <div class="card-body">

                                <form action="upload_excel_updatecs.php" method="POST" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label>
                                            Pilih File Excel
                                        </label>

                                        <input 
                                            type="file" 
                                            name="file_excel" 
                                            class="form-control"
                                            accept=".xls,.xlsx"
                                            required
                                        >
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload"></i>
                                        Upload & Proses
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">

                        <div class="card shadow">

                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-success">
                                    Preview Format Excel
                                </h6>
                            </div>
                            <div class="card-body">

                                <div class="table-responsive">

                                    <table class="table table-bordered table-sm">

                                        <thead class="bg-info text-white text-center">

                                            <tr>
                                                <th>No</th>
                                                <th>ID Admin</th>
                                                <th>Nama CS</th>
                                                <th>Nama Mitra</th>
                                                <th>Pembagian DB Per 1 Tahun</th>
                                            </tr>

                                        </thead>

                                        <tbody>

                                            <tr>
                                                <td>1</td>
                                                <td>431</td>
                                                <td>Intan</td>
                                                <td>addyah chandrasari</td>
                                                <td>Mima</td>
                                            </tr>

                                            <tr>
                                                <td>2</td>
                                                <td>49</td>
                                                <td>Daniar</td>
                                                <td>Ade Lisna Pendi</td>
                                                <td>Mima</td>
                                            </tr>

                                            <tr>
                                                <td>3</td>
                                                <td>299</td>
                                                <td>Daniar</td>
                                                <td>ADIMAYA</td>
                                                <td>Mima</td>
                                            </tr>

                                            <tr>
                                                <td>4</td>
                                                <td>688</td>
                                                <td>Intan</td>
                                                <td>Afrina</td>
                                                <td>Mima</td>
                                            </tr>

                                            <tr>
                                                <td>5</td>
                                                <td>640</td>
                                                <td>Nida</td>
                                                <td>Agus Triyani</td>
                                                <td>Mega</td>
                                            </tr>

                                        </tbody>

                                    </table>

                                </div>
                                <div class="alert alert-warning mt-3 mb-0">
                                    <b>Catatan:</b>
                                    <ul class="mb-0">
                                        <li>Pastikan nama kolom sesuai dengan format preview.</li>
                                        <li>File harus berformat .xls atau .xlsx</li>
                                        <li style="color: red;">Kolom yang dipakai untuk update:
                                            <b>ID Admin</b> dan
                                            <b>Pembagian DB Per 1 Tahun</b>
                                            Harus diisi!
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>