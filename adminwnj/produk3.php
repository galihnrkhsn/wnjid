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

    <!-- jQuery (hanya sekali, jangan dobel) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div class="container-fluid">
            <!-- CONTENT -->
            <div class="row">
                <h3><strong>Stock Produk</strong></h3>
            </div>
            <a class="btn btn-primary" href="tambah_produk"><span class="fas fa-plus"></span> Tambah Produk</a>
            <a class="btn btn-primary" href="update_produk.php"><span class="fas fa-pen"></span> Update Produk</a>
            <a class="btn btn-primary" href="foto_produk"><span class="fas fa-plus"></span> Tambah Foto</a>
            <br><br>
            <form action="excelproduk3.php" method="GET">
                <input type="hidden" name="status" value="<?= $publish ?>">
                <button type="submit" class="btn btn-success btn-sm">Export Produk Publish</button>
            </form>
            <br>
            <form method="post">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="tb_produk">
                        <thead>
                            <tr>
                                <th>No</th>  
                                <th><input type='checkbox' id='checkAll'> Check</th>
                                <th>Idproducts</th>
                                <th>Status</th>
                                <th>Produk Kategori</th>
                                <th>Nama Produk</th>
                                <th>Variant</th>
                                <th>Size</th>
                                <th>Harga</th>
                                <th>Berat</th>
                                <th>Grade</th>
                                <th>Stock</th>
                                <th>Diskon</th>
                                <th><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                    </table>

                    <select name="opsi" class="form-control mt-3">
                        <option>Publish</option>
                        <option>Unpublish</option>
                        <option>Update Produk</option>
                        <option>Update Kategori Diskon</option>
                    </select>
                    <br>
                    <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                </div>
            </form>
            <?php
                if (isset($_POST["simpan"])) {

                    $id         = $_POST["id"];
                    $opsi       = $_POST["opsi"];
                    $stock      = $_POST["stock"];
                    $idkategori = $_POST["idkategori"];
                    $variant    = $_POST['variant'];
                    $harga      = $_POST['harga'];
                    $size       = $_POST['size'];
                    $berat      = $_POST['berat'];
                    $jumlah_dipilih = count($id);
                    
                    if ($opsi == "Publish") {
                        for ($x = 0; $x < $jumlah_dipilih; $x++) {
                            $stmt = $koneksi->prepare("UPDATE variants SET status = 0, updated_at = NOW() WHERE id=?");
                            $stmt->bind_param("i", $id[$x]);
                            
                            if ($stmt->execute()) {
                                echo "<script>alert('Produk berhasil dipublish');</script>";
                            } else {
                                echo "<script>alert('Produk gagal dipublish');</script>";
                            }
                            
                            echo "<script>location='produk3.php';</script>";
                        }
                    } elseif ($opsi == "Unpublish") {
                        for ($x = 0; $x < $jumlah_dipilih; $x++) {
                            $stmt = $koneksi->prepare("UPDATE variants SET status = 1, updated_at = NOW() WHERE id=?");
                            $stmt->bind_param("i", $id[$x]);
                            
                            if ($stmt->execute()) {
                                echo "<script>alert('Produk berhasil diunpublish');</script>";
                            } else {
                                echo "<script>alert('Produk gagal diunpublish');</script>";
                            }

                            echo "<script>location='produk3.php';</script>";
                        }
                    } elseif ($opsi == "Update Produk") {
                        foreach ($id as $updateid) {
                            $stock_value    = $stock[$updateid];
                            $variant_value  = $variant[$updateid];
                            $harga_value    = $harga[$updateid];
                            $size_value     = $size[$updateid];
                            $berat_value    = $berat[$updateid];

                            $stmt = $koneksi->prepare("UPDATE variants SET stock = ?, variant = ?, harga = ?, size = ?, berat = ?, updated_at = NOW() WHERE id=?");
                            $stmt->bind_param("isisii", $stock_value, $variant_value, $harga_value, $size_value, $berat_value, $updateid);
                            
                            if ($stmt->execute()) {
                                echo "<script>alert('Produk berhasil diupdate stock');</script>";
                            } else {
                                echo "<script>alert('Produk gagal diupdate stock');</script>";
                            }
                            
                            echo "<script>location='produk3.php';</script>";
                        }
                    } elseif ($opsi == "Update Kategori Diskon") {
                        foreach ($id as $updateid) {
                            $kategori_value = $idkategori[$updateid];
                            $stmt = $koneksi->prepare("UPDATE products p INNER JOIN variants v ON p.id = v.id SET p.idkategori = ?, updated_at = NOW() WHERE v.id=?");
                            $stmt->bind_param("ii", $kategori_value, $updateid);
                            
                            if ($stmt->execute()) {
                                echo "<script>alert('Produk berhasil diupdate kategori diskon');</script>";
                            } else {
                                echo "<script>alert('Produk gagal diupdate kategori diskon');</script>";
                            }
                            
                            echo "<script>location='produk3.php';</script>";
                        }
                    }
                }
            ?>
            </div>
            <!-- CONTENT END -->
        </div>
    </div>
        <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            // Check/Uncheck ALl
            $('#checkAll').change(function(){
                if($(this).is(':checked')){
                    $('input[name="id[]"]').prop('checked',true);
                }else{
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
                }else{
                    $('#checkAll').prop('checked',false);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $('#tb_produk').DataTable({
                processing: true,
                serverSide: true,
                ajax: "api/data_produk.php",
                order: [[ 0, "desc" ]],
                columnDefs: [
                    { orderable: false, targets: [1,13] } // kolom yang tidak bisa sort
                ],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                pageLength: 10
            });
        });

        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            let id = $(this).data('id');

            if (confirm("Yakin hapus variant ini?")) {
                $.ajax({
                    url: 'api/delete_produk.php',
                    type: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(data) {
                        if(data.success){
                            alert(data.message);
                            $('#tb_produk').DataTable().ajax.reload();
                        } else {
                            alert("Gagal hapus: " + data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("Status error: " + error)
                    }
                });
            }
        })
    </script>
</body>

</html>