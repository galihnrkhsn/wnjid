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
                        Export Excel Produk
                    </h1>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    Export Excel Produk
                                </h6>
                            </div>
                            <div class="card-body">
                                <form action="export_excel_update_produk.php" method="POST">
                                    <!-- SEARCH PRODUK -->
                                    <div class="form-group">
                                        <label>
                                            Cari Produk
                                        </label>
                                        <input 
                                            type="text"
                                            id="produkSearch"
                                            class="form-control"
                                            list="listProduk"
                                            placeholder="Ketik nama produk..."
                                        >
                                        <datalist id="listProduk">
                                            <?php
                                            $ambil = $koneksi->query("SELECT 
                                                                        a.namaproduk,
                                                                        a.id
                                                                    FROM products a
                                                                    ORDER BY a.namaproduk ASC
                                            ");
                                            while($pecah = $ambil->fetch_assoc()){
                                            ?>
                                                <option 
                                                    value="<?= $pecah['id']; ?>"
                                                    data-label="<?= $pecah['namaproduk']; ?>">
                                                    <?= $pecah['namaproduk']; ?>
                                                </option>
                                            <?php } ?>
                                        </datalist>
                                        <small class="text-muted">
                                            Cari produk lalu klik tambah
                                        </small>
                                    </div>
                                    <!-- BUTTON TAMBAH -->
                                    <button 
                                        type="button"
                                        class="btn btn-info mb-3"
                                        onclick="tambahProduk()"
                                    >
                                        <i class="fas fa-plus"></i>
                                        Tambah Produk
                                    </button>
                                    <!-- LIST PRODUK TERPILIH -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="tableProduk">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Produk</th>
                                                    <th width="10%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- INPUT HIDDEN -->
                                    <div id="hiddenProduk"></div>
                                    <!-- BUTTON EXPORT -->
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-file-excel"></i>
                                        Export Excel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    Upload File Excel
                                </h6>
                            </div>
                            <div class="card-body">
                                <form action="update_produk_excel.php" method="POST" enctype="multipart/form-data">
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
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    function tambahProduk(){

        let input = document.getElementById('produkSearch');

        let idproducts = input.value;

        if(idproducts == ''){
            alert('Pilih produk terlebih dahulu');
            return;
        }

        let option = document.querySelector(
            '#listProduk option[value="'+idproducts+'"]'
        );

        if(!option){
            alert('Produk tidak ditemukan');
            return;
        }

        let label = option.dataset.label;

        // CEK DUPLIKAT
        if(document.getElementById('produk_'+idproducts)){
            alert('Produk sudah dipilih');
            return;
        }

        // TABLE
        let tr = `
            <tr id="produk_${idproducts}">
                <td>${label}</td>
                <td>

                    <button 
                        type="button"
                        class="btn btn-danger btn-sm"
                        onclick="hapusProduk('${idproducts}')"
                    >
                        Hapus
                    </button>

                </td>
            </tr>
        `;

        document.querySelector('#tableProduk tbody')
            .insertAdjacentHTML('beforeend', tr);

        // HIDDEN INPUT
        let hidden = `
            <input 
                type="hidden"
                name="idproducts[]"
                value="${idproducts}"
                id="hidden_${idproducts}"
            >
        `;

        document.getElementById('hiddenProduk')
            .insertAdjacentHTML('beforeend', hidden);

        input.value = '';
    }

    function hapusProduk(idproducts){

        document.getElementById('produk_'+idproducts).remove();

        document.getElementById('hidden_'+idproducts).remove();
    }
</script>
</body>
</html>