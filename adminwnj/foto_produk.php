<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();

    include 'koneksi.php'; 
    require_once 'helpers/compress_img.php';
    require_once 'helpers/slugify.php';

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
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div class="container-fluid">
            <!-- FORM VARIANT -->
            <h4>Foto Produk</h4>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Produk</label>
                    <select name="product" class="form-control form-control-sm" required>
                        <option value="">Pilih Produk</option>
                        <?php
                        $query_produk = $koneksi->query("SELECT products.id, products.namaproduk, master_folder.name 
                                                            FROM products 
                                                            LEFT JOIN master_folder ON master_folder.name = products.namaproduk
                                                            ORDER BY products.namaproduk ASC
                                                        ");
                        while ($data_produk = $query_produk->fetch_assoc()) {
                            echo "<option value='{$data_produk['id']}'>{$data_produk['namaproduk']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <hr/>
                <h5>Daftar Variant</h5>

                <div class="input-group mb-3">
                    <input type="text" class="form-control variant-preview" readonly placeholder="Pilih Variant">
                    <input type="hidden" name="variant_selected" id="variant_selected">
                    
                    <button type="button" class="btn btn-secondary pilih-variant">
                        Pilih Variant
                    </button>
                </div>

                <table class="table table-bordered" id="variantTable">
                    <thead>
                        <tr>
                            <th>Variant</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="variantBody"></tbody>

                </table>
                <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addRow('variantBody')">+ Tambah Variant</button>

                <br>
                <button type="submit" name="insert-variant" class="btn btn-primary btn-sm">Simpan Semua Kombinasi</button>
            </form>

            <script>
            function addRow(tbodyId) {
                const tbody = document.getElementById(tbodyId);
                const row = tbody.rows[0].cloneNode(true);
                row.querySelectorAll('input').forEach(input => input.value = '');
                row.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
                tbody.appendChild(row);
            }

            function removeRow(btn, tbodyId) {
                const tbody = document.getElementById(tbodyId);
                const row = btn.closest('tr');
                if (tbody.rows.length > 1) row.remove();
                else alert("Minimal 1 entri harus ada.");
            }
            </script>

            <?php
                if (isset($_POST['insert-variant'])) {
                    $koneksi->begin_transaction();
                    try {
                        $produk       = $_POST['product'];
                        $sizes        = $_POST['size'];
                        $berats       = $_POST['berat'];
                        $hargas       = $_POST['harga'];
                        $hargaCorets  = $_POST['hargaCoret'] ?? [];
                        $statuses     = $_POST['status'];
                        $variants     = $_POST['variant'];
                        $fotos        = $_FILES['foto_file'];
                        $size_ids     = [];

                        $folder = $koneksi->query("SELECT namaproduk FROM products WHERE id = '$produk'")->fetch_assoc();

                        $folderName     = trim($folder['namaproduk']);
                        $f              = slugify($folderName);
                        $folderPath     = "../distributor/foto/produk/" . $f;

                        if (!is_dir($folderPath)) {
                            mkdir($folderPath, 0777, true);
                        }

                        $stmtFold     = $koneksi->prepare("SELECT id FROM master_folder WHERE name = ?");
                        $stmtFold->bind_param("s", $f);
                        $stmtFold->execute();
                        $result = $stmtFold->get_result();

                        if ($result->num_rows > 0) {
                            $d          = $result->fetch_assoc();
                            $folder_id  = $d['id'];
                        } else {
                            $insertFold = $koneksi->prepare("INSERT INTO master_folder (name, created_at) VALUES (?, NOW())");
                            $insertFold->bind_param("s", $f);
                            $insertFold->execute();

                            $folder_id  = $koneksi->insert_id;
                            $insertFold->close();
                        }
                        $stmtFold->close();

                        foreach ($sizes as $s) {
                            $s          = trim(strtoupper($s));
                            $stmtSize   = $koneksi->prepare("SELECT id FROM master_size WHERE nama_size = ?");
                            $stmtSize->bind_param("s", $s);
                            $stmtSize->execute();
                            $result = $stmtSize->get_result();

                            if ($result->num_rows > 0) {
                                $d = $result->fetch_assoc();
                                $size_ids[] = $d['id'];
                            } else {
                                $insertSize = $koneksi->prepare("INSERT INTO master_size (nama_size) VALUES (?)");
                                $insertSize->bind_param("s", $s);
                                $insertSize->execute();

                                $size_ids[] = $koneksi->insert_id;
                                $insertSize->close();
                            }
                            $stmtSize->close();
                        }

                        $stmt = $koneksi->prepare("INSERT INTO variants 
                                (idproducts, variant, size, size_id, berat, harga, hargaCoret, folder, foto, status, tgl, updated_at)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
                        if (!$stmt) {
                            throw new Exception("Prepare gagal: " . $koneksi->error);
                        }

                        foreach ($variants as $i => $variant) {
                            $variant        = trim($variant);
                            $fotoNama       = $_POST['foto_nama'][$i] ?? null;
                            $fotoNamaNew    = $fotos['name'][$i];
                            $fotoTmp        = $fotos['tmp_name'][$i];
                            $ext            = strtolower(pathinfo($fotoNamaNew, PATHINFO_EXTENSION));

                            if (!empty($fotoNamaNew)) {
                                if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
                                    throw new Exception("Format gambar tidak didukung untuk variant: $variant");
                                }
    
                                $namaFileBaru = slugify($f . '-' . $variant);
                                $namaFileBaru = $namaFileBaru . '.webp';
    
                                $uploadPath = "../distributor/foto/produk/" . $f . '/' . $namaFileBaru;
    
                                $compressed = compressResizeImage($fotoTmp, $uploadPath, 75, 1200);
    
                                if (!$compressed) {
                                    throw new Exception("Compress gambar gagal untuk variant: $variant");
                                }
                            } else {
                                $namaFileBaru = $fotoNama;
                            }

                            foreach ($sizes as $j => $size) {
                                $size_id    = $size_ids[$j];
                                $berat      = (float)$berats[$j];
                                $harga      = (int)$hargas[$j];
                                $hargaCoret = isset($hargaCorets[$j]) ? (int)$hargaCorets[$j] : 0;
                                $status     = (int)$statuses[$j];

                                $checkStmt = $koneksi->prepare("SELECT id 
                                        FROM variants 
                                        WHERE idproducts = ? AND variant = ? AND size_id = ?");

                                $checkStmt->bind_param("isi", $produk, $variant, $size_id);
                                $checkStmt->execute();
                                $checkStmt->store_result();

                                if ($checkStmt->num_rows == 0) {
                                    $stmt->bind_param(
                                        "issiidissi",
                                        $produk,
                                        $variant,
                                        $size,
                                        $size_id,
                                        $berat,
                                        $harga,
                                        $hargaCoret,
                                        $folder_id,
                                        $namaFileBaru,
                                        $status
                                    );

                                    if (!$stmt->execute()) {
                                        throw new Exception("Insert variant gagal: " . $stmt->error);
                                    }
                                }
                                $checkStmt->close();
                            }
                        }

                        $stmt->close();
                        $koneksi->commit();

                        echo "<script>
                                alert('Semua kombinasi berhasil disimpan');
                                location='tambah_produk.php';
                            </script>";

                    } catch (Exception $e) {
                        $koneksi->rollback();

                        echo "
                            Error: ".$e->getMessage()."
                        ";
                    }
                }
            ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
<style>
    .foto-item{
    width:120px;
    height:120px;
    object-fit:cover;
    cursor:pointer;
    border:2px solid transparent;
    }

    .foto-item:hover{
    border:2px solid #007bff;
    }
</style>
<div class="modal fade" id="modalFoto">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Pilih Foto Produk</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <div id="galleryFoto" style="display:flex;flex-wrap:wrap;gap:10px;"></div>

                <hr>

                <label>Upload Foto Baru</label>
                <input type="file" id="uploadFotoBaru" class="form-control">

                <div id="previewFotoBaru" style="margin-top:10px;"></div>

                <button type="button" id="pilihFotoBaru" class="btn btn-primary mt-2">
                    Gunakan Foto Ini
                </button>

            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="modalVariant">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Pilih Variant Produk</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <button class="btn btn-primary btn-sm mb-3" id="pilihSemuaVariant">
                    Pilih Semua Variant
                </button>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Pilih</th>
                            <th>Variant</th>
                        </tr>
                    </thead>

                    <tbody id="variantList"></tbody>
                </table>

                <button class="btn btn-success btn-sm" id="gunakanVariant">
                    Gunakan Variant
                </button>

            </div>

        </div>
    </div>
</div>
<script>
let currentInput = null;
let fileUpload = null;

$(document).on("click",".pilih-variant",function(){

    let product_id = $("select[name='product']").val();

    if(!product_id){
        alert("Pilih produk dulu");
        return;
    }

    $.get("ajax_get_variant.php",{product_id:product_id},function(data){

        $("#variantList").html(data);
        $("#modalVariant").modal("show");

    });

});

$("#pilihSemuaVariant").click(function(){

    $(".variant-check").prop("checked",true);

});

$("#gunakanVariant").click(function(){

    let tbody = $("#variantBody");
    tbody.html("");

    $(".variant-check:checked").each(function(){

        let nama = $(this).data("nama");

        let row = `
        <tr>
            <td>
                <input type="text" name="variant[]" class="form-control" value="${nama}">
            </td>

            <td>
                <input type="hidden" name="foto_nama[]" class="foto-nama">
                <input type="file" name="foto_file[]" class="foto-file d-none">

                <div class="input-group">
                    <input type="text" class="form-control foto-preview" readonly placeholder="Pilih foto">

                    <button type="button" class="btn btn-secondary pilih-foto">
                        Pilih
                    </button>
                </div>
            </td>

            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this,'variantBody')">
                    Hapus
                </button>
            </td>
        </tr>
        `;

        tbody.append(row);

    });

    $("#modalVariant").modal("hide");

});

$(document).on("click",".pilih-foto",function(){

    currentInput = $(this).closest("td");

    let product_id = $("select[name='product']").val();

    if(!product_id){
        alert("Pilih produk dulu");
        return;
    }

    $.get("ajax_get_img.php",{product_id:product_id},function(data){

        $("#galleryFoto").html(data);
        $("#modalFoto").modal("show");

    });

});

$(document).on("click",".foto-item",function(){

    let nama = $(this).data("nama");

    currentInput.find(".foto-nama").val(nama);
    currentInput.find(".foto-preview").val(nama);

    $("#modalFoto").modal("hide");

});

$("#uploadFotoBaru").on("change", function(){

    let file = this.files[0];

    if(!file) return;

    fileUpload = file;

    let reader = new FileReader();

    reader.onload = function(e){

        $("#previewFotoBaru").html(
            "<img src='"+e.target.result+"' style='width:120px;border:1px solid #ddd;padding:5px;'>"
        );

    };

    reader.readAsDataURL(file);

});

$("#pilihFotoBaru").on("click", function(){

    if(!fileUpload){
        alert("Pilih foto dulu");
        return;
    }

    let fileInput = currentInput.find(".foto-file")[0];

    let dataTransfer = new DataTransfer();
    dataTransfer.items.add(fileUpload);

    fileInput.files = dataTransfer.files;

    currentInput.find(".foto-preview").val(fileUpload.name);

    $("#modalFoto").modal("hide");

});

</script>
</body>
</html>