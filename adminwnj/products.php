<?php
    session_start();

    include "koneksi.php";

    if (!isset($_SESSION["administrator"])) {
        echo "<script>alert('Anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ready Stock | Administrator Wanoja</title>
    
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        
        <div class="container-fluid mx-2">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="fw-semibold mb-0">Ready Stock</h4>
                    <hr />
                </div>

                <div class="col-sm-12">
                    <a class="btn btn-primary btn-sm" href="tambah_produk.php">Tambah Produk</a>
                    <a class="btn btn-success btn-sm" href="export_ready_stock.php">Export Excel</a>
                    
                    <div class="my-2 d-flex align-items-center">
                        <div>
                            <input type="radio" id="publish" class="active" onclick="javascript:window.location.href='products.php?id=0'">
                            <label for="publish">Publish</label>
                        </div>
                        
                        <div class="mx-2">
                            <input type="radio" id="unpublish" class="active" onclick="javascript:window.location.href='products.php?id=1'">
                            <label for="unpublish">Unpublish</label>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <form method="post">
                            <table class="table table-bordered" id="tb_produk">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>#</th>
                                        <th>Status</th>
                                        <th>Nama Produk</th>
                                        <th>Variant</th>
                                        <th>Size</th>
                                        <th>Harga</th>
                                        <th>Berat</th>
                                        <th>Grade</th>
                                        <th>Stock</th>
                                        <th>Opsi Stock</th>
                                        <th>Opsi Diskon Kategori</th>
                                        <th><i class="fas fa-cog"></i></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                        $status = $_GET["id"];
                                        $no = 1;
                                        $data_produk = $koneksi->query("SELECT
                                                        pkategori.namakategori,
                                                        kategori.namakategori AS grade,
                                                        products.id AS idproducts,
                                                        products.namaproduk,
                                                        variants.*
                                                        FROM products
                                                        INNER JOIN pkategori ON pkategori.idpkategori = products.idpkategori
                                                        INNER JOIN kategori ON kategori.idkategori = products.idkategori
                                                        INNER JOIN variants ON variants.idproducts = products.id
                                        ");
                                        while ($produk = $data_produk->fetch_assoc()) {
                                            $id = $produk['id'];
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>
                                                <input type="checkbox" name="idproduk[]" value="<?= $id ?>">
                                            </td>
                                            <td>
                                                <?php if ($produk['status'] == 1) : ?>
                                                    <span class="badge badge-danger">Unpublish</span>
                                                <?php elseif ($produk['status'] == 0) : ?>
                                                    <span class="badge badge-success">Publish</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $produk['namaproduk'] ?></td>
                                            <td><?= $produk['variant'] ?></td>
                                            <td><?= $produk['size'] ?></td>
                                            <td><?= $produk['harga'] ?></td>
                                            <td><?= $produk['berat'] ?></td>
                                            <td><?= $produk['grade'] ?></td>
                                            <td><?= $produk['stock'] ?></td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm" name="stock<?= $id ?>" value="0">
                                            </td>
                                            <td>
                                                <select class="form-control form-control-sm">
                                                    <?php
                                                        $kategori = $koneksi->query("SELECT * FROM kategori ORDER BY idkategori ASC");
                                                        while ($row = $kategori->fetch_assoc()) {
                                                    ?>
                                                        <option value="<?= $row['idkategori'] ?>"><?= $row['namakategori'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <a class="text-primary" href="#"><i class="fas fa-edit"></i></a>
                                                <a class="text-danger" href="#"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </form>    
                    </div>
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
    <script type="text/javascript">
        $(document).ready(function(){

            // Check/Uncheck ALl
            $('#checkAll').change(function(){
                if($(this).is(':checked')){
                    $('input[name="id[]"]').prop('checked',true);
                } else {
                    $('input[name="id[]"]').each(function(){
                        $(this).prop('checked',false);
                    }); 
                }
            });

            // Checkbox click
            $('input[name="id[]"]').click(function(){
                var total_checkboxes = $('input[name="id[]"]').length;
                var total_checkboxes_checked = $('input[name="id[]"]:checked').length;

                if(total_checkboxes_checked == total_checkboxes){
                    $('#checkAll').prop('checked',true);
                } else {
                    $('#checkAll').prop('checked',false);
                }
            });
        });
    </script>
    <?php include "settingdatatables.php"; ?>
</body>
</html>