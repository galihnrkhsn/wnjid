<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();

    include 'koneksi.php';
    require_once 'helpers/compress_img.php';
    require_once 'helpers/slugify.php';
    require_once '../includes/access_helper.php';

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
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div class="container-fluid">
          <!-- FORM PRODUK -->
            <div class="d-flex justify-content-between mb-0">
                <h4 style="color: #153448;">Input Produk</h4>
                <a href="produk.php" class="text-primary mt-1">Kembali</a>
            </div>
            <hr class="my-2" />
            <form method="POST">
                <div class="row mx-0">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="d-flex align-items-center">Nama Produk<p class="text-danger p-0 m-0 ml-1">*</p></label>
                            <input list="product_list" type="text" class="form-control form-control-sm" placeholder="Masukan nama produk" name="products" required>

                            <datalist id="product_list">
                                <?php 
                                    $products = $koneksi->query("SELECT * FROM products ORDER BY namaproduk ASC");
                                    while($m = $products->fetch_assoc()) {                                        
                                ?>
                                    <option value="<?= $m['namaproduk'] ?>">
                                <?php } ?>
                            </datalist>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="d-flex align-items-center">Kategori Produk<p class="text-danger p-0 m-0 ml-1">*</p></label>
                            <select name="pkategori" class="form-control form-control-sm">
                                <?php
                                    $query_kategori = $koneksi->query("SELECT * FROM pkategori ORDER BY idpkategori ASC");
                                    while ($data_kategori = $query_kategori->fetch_assoc()) {
                                ?>
                                    <option value="<?= $data_kategori['idpkategori'] ?>">
                                        <?= $data_kategori['namakategori'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="d-flex align-items-center">Kategori Diskon<p class="text-danger p-0 m-0 ml-1">*</p></label>
                            <select class="form-control form-control-sm" name="kategori">
                                <?php
                                    $query_kategori = $koneksi->query("SELECT * FROM kategori ORDER BY idkategori ASC");
                                    while ($data_kategori = $query_kategori->fetch_assoc()) {
                                ?>
                                    <option value="<?= $data_kategori['idkategori'] ?>">
                                        <?= $data_kategori['namakategori'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="kode_artikel" class="d-flex align-items-center">Kode Artikel <span class="text-muted small ml-1">(opsional, buat promo B1G1)</span></label>
                            <input list="kode_artikel_list" type="text" class="form-control form-control-sm" placeholder="Contoh: konin_25" name="kode_artikel">
                            <datalist id="kode_artikel_list">
                                <?php
                                    $artikelList = $koneksi->query("SELECT DISTINCT kode_artikel FROM products WHERE kode_artikel IS NOT NULL AND kode_artikel != '' ORDER BY kode_artikel ASC");
                                    while ($a = $artikelList->fetch_assoc()) {
                                ?>
                                    <option value="<?= htmlspecialchars($a['kode_artikel']) ?>">
                                <?php } ?>
                            </datalist>
                            <small class="text-muted">Produk dengan kode artikel yang sama boleh digabung dalam 1x checkout promo B1G1.</small>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label class="d-flex align-items-center">Akses Mitra <span class="text-muted small ml-1">(kosongkan = semua mitra bisa akses)</span></label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="access[]" value="<?= ACCESS_DISTRIBUTOR ?>" id="accessDistributor">
                                <label class="form-check-label" for="accessDistributor">Distributor</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="access[]" value="<?= ACCESS_AGEN ?>" id="accessAgen">
                                <label class="form-check-label" for="accessAgen">Agen</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="access[]" value="<?= ACCESS_RESELLER ?>" id="accessReseller">
                                <label class="form-check-label" for="accessReseller">Reseller</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="access[]" value="<?= ACCESS_MARKETER ?>" id="accessMarketer">
                                <label class="form-check-label" for="accessMarketer">Marketer</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="access[]" value="<?= ACCESS_KONSUMEN ?>" id="accessKonsumen">
                                <label class="form-check-label" for="accessKonsumen">Konsumen</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <button class="btn btn-primary btn-sm" type="submit" name="insert-produk">Tambah Produk</button>
                    </div>

                    <div class="col-sm-12">
                        <hr />
                    </div>
                </div>
            </form>
            <?php
                if (isset($_POST['insert-produk'])) {
                    $produk       = trim($_POST['products']);
                    $pkategori    = $_POST['pkategori'];
                    $kategori     = $_POST['kategori'];
                    $kodeArtikel  = trim($_POST['kode_artikel'] ?? '');
                    $kodeArtikel  = $kodeArtikel !== '' ? $kodeArtikel : null;

                    $access = 0;
                    foreach ($_POST['access'] ?? [] as $bit) {
                        $access |= (int) $bit;
                    }

                    $slug = strtolower($produk); // jadi huruf kecil
                    $slug = preg_replace('/[\s-]+/', '-', $slug); // ganti spasi / double dash jadi 1 dash
                    $slug = trim($slug, '-'); // hapus dash di awal/akhir

                    // Cek duplikasi produk
                    $stmtCheck = $koneksi->prepare("SELECT namaproduk FROM products WHERE TRIM(LOWER(namaproduk)) = TRIM(LOWER(?))");
                    $stmtCheck->bind_param("s", $produk);
                    $stmtCheck->execute();
                    $stmtCheck->store_result();

                    if ($stmtCheck->num_rows > 0) {
                        echo "<script>alert('Produk sudah terdaftar. Silakan masukan Produk lain.'); window.location.href = 'tambah_produk.php';</script>";
                        exit();
                    }
                    $stmtCheck->close();

                    // Insert produk baru
                    $stmtInsert = $koneksi->prepare("INSERT INTO products (namaproduk, idpkategori, idkategori, kode_artikel, created_at, updated_at, slug, access) VALUES (?, ?, ?, ?, now(), now(), ?, ?)");
                    $stmtInsert->bind_param("siissi", $produk, $pkategori, $kategori, $kodeArtikel, $slug, $access);

                    if ($stmtInsert->execute()) {
                        echo "<script>alert('Data berhasil disimpan.'); window.location.href = 'tambah_produk.php';</script>";
                    } else {
                        echo "<script>alert('Gagal menyimpan data.'); window.location.href = 'tambah_produk.php';</script>";
                    }
                    $stmtInsert->close();
                }
            ?>
            <!-- FORM PRODUK END -->
            <!-- FORM VARIANT -->
            <h4>Tambah Produk Variant + Size</h4>
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
                <div class="form-group">
                    <label>Jenis <span class="text-muted small">(opsional, contoh: flash, b1g1, GB, Bundling 3 - kosongkan untuk produk reguler)</span></label>
                    <select class="form-control form-control-sm" name="jenis">
                        <option value="" selected disabled>Pilih Jenis</option>
                        <?php
                            $jenisList = $koneksi->query("SELECT nama_jenis FROM master_jenis_products ORDER BY nama_jenis ASC");
                            while ($jn = $jenisList->fetch_assoc()) {
                        ?>
                        <option value="<?= htmlspecialchars($jn['nama_jenis']) ?>"><?= $jn['nama_jenis'] ?>
                        <?php } ?>
                    </select>
                </div>
                <hr />
                <h5>Daftar Ukuran / Harga</h5>
                <table class="table table-bordered" id="sizeTable">
                    <thead>
                        <tr>
                            <th>Size</th>
                            <th>Berat</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="sizeBody">
                        <tr>
                            <td>
                                <input list="size_list" class="form-control" name="size[]">

                                <datalist id="size_list">
                                    <?php 
                                        $size = $koneksi->query("SELECT * FROM master_size ORDER BY id ASC");
                                        while($m = $size->fetch_assoc()) {                                        
                                    ?>
                                        <option value="<?= $m['nama_size'] ?>">
                                    <?php } ?>
                                </datalist>
                            </td>
                            <td>
                                <input list="berat_list" type="number" name="berat[]" class="form-control" required>

                                <datalist id="berat_list">
                                    <?php
                                        $berat = $koneksi->query("SELECT DISTINCT berat FROM variants");
                                        while($b = $berat->fetch_assoc()) {
                                    ?>
                                    <option value="<?= $b['berat'] ?>"></option>
                                    <?php
                                        }
                                    ?>
                                </datalist>
                            </td>
                            <td>
                                <input list="harga_list" type="number" name="harga[]" class="form-control" required>
                                <datalist id="harga_list">
                                    <?php
                                        $berat = $koneksi->query("SELECT DISTINCT harga FROM variants");
                                        while($b = $berat->fetch_assoc()) {
                                    ?>
                                    <option value="<?= $b['harga'] ?>"></option>
                                    <?php
                                        }
                                    ?>
                                </datalist>
                            </td>
                            <td>
                                <select name="status[]" class="form-control">
                                    <option value="1">UNPUBLISH</option>
                                    <option value="0">PUBLISH</option>
                                </select>
                            </td>
                            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this, 'sizeBody')">Hapus</button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addRow('sizeBody')">+ Tambah Ukuran</button>

                <h5>Daftar Variant</h5>
                    <table class="table table-bordered" id="variantTable">
                        <thead>
                            <tr>
                                <th>Variant</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="variantBody">
                            <tr>
                                <td>
                                    <input list="variant_list" type="text" name="variant[]" class="form-control" required>
                                    <datalist id="variant_list">
                                        <?php
                                            $berat = $koneksi->query("SELECT DISTINCT variant FROM variants");
                                            while($b = $berat->fetch_assoc()) {
                                        ?>
                                        <option value="<?= $b['variant'] ?>"></option>
                                        <?php
                                            }
                                        ?>
                                    </datalist>
                                </td>
                                <td>
                                    <input type="hidden" name="foto_id[]" class="foto-id">

                                    <input type="file" name="foto_file[]" class="foto-file d-none">

                                    <div class="input-group">
                                        <input type="text" class="form-control foto-preview" readonly placeholder="Pilih foto">

                                        <button type="button" class="btn btn-secondary pilih-foto">
                                            Pilih
                                        </button>
                                    </div>
                                </td>
                                <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this, 'variantBody')">Hapus</button></td>
                            </tr>
                        </tbody>
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

                        $stmtProduk = $koneksi->prepare("SELECT namaproduk FROM products WHERE id = ?");
                        $stmtProduk->bind_param("i", $produk);
                        $stmtProduk->execute();
                        $produkRow = $stmtProduk->get_result()->fetch_assoc();
                        $stmtProduk->close();

                        if (!$produkRow) {
                            throw new Exception("Produk tidak ditemukan");
                        }

                        $jenisInput = trim($_POST['jenis'] ?? '');
                        $jenis      = $jenisInput !== '' ? $jenisInput : null;

                        if ($jenis !== null) {
                            $stmtJenis = $koneksi->prepare("SELECT id FROM master_jenis_products WHERE nama_jenis = ?");
                            $stmtJenis->bind_param("s", $jenis);
                            $stmtJenis->execute();
                            if ($stmtJenis->get_result()->num_rows === 0) {
                                $insertJenis = $koneksi->prepare("INSERT INTO master_jenis_products (nama_jenis) VALUES (?)");
                                $insertJenis->bind_param("s", $jenis);
                                $insertJenis->execute();
                                $insertJenis->close();
                            }
                            $stmtJenis->close();
                        }

                        // products.jenis jadi sumber utama (dipakai konsumen/), variants.jenis TETAP diisi
                        // sama karena masih dipakai langsung oleh distributor/agen/mitra lain.
                        $stmtProdukJenis = $koneksi->prepare("UPDATE products SET jenis = ? WHERE id = ?");
                        $stmtProdukJenis->bind_param("si", $jenis, $produk);
                        $stmtProdukJenis->execute();
                        $stmtProdukJenis->close();

                        $folderName     = trim($produkRow['namaproduk']);
                        $f              = slugify($folderName);
                        $folderPath     = "../image/produk/" . $f;

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

                        $stmtUrutan = $koneksi->prepare("SELECT COALESCE(MAX(urutan), -1) + 1 AS next_urutan FROM foto_produk WHERE idproduk = ?");
                        $stmtUrutan->bind_param("i", $produk);
                        $stmtUrutan->execute();
                        $nextUrutan = (int) $stmtUrutan->get_result()->fetch_assoc()['next_urutan'];
                        $stmtUrutan->close();

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
                                (idproducts, variant, size, size_id, berat, harga, hargaCoret, foto, status, tgl, updated_at, jenis)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
                        if (!$stmt) {
                            throw new Exception("Prepare gagal: " . $koneksi->error);
                        }

                        $stmtFotoInsert = $koneksi->prepare("INSERT INTO foto_produk (idproduk, folder, foto, urutan, created_at) VALUES (?, ?, ?, ?, NOW())");

                        foreach ($variants as $i => $variant) {
                            $variant        = trim($variant);
                            $fotoIdPosted   = $_POST['foto_id'][$i] ?? '';
                            $fotoNamaNew    = $fotos['name'][$i] ?? '';
                            $fotoTmp        = $fotos['tmp_name'][$i] ?? '';
                            $ext            = strtolower(pathinfo($fotoNamaNew, PATHINFO_EXTENSION));

                            if (!empty($fotoNamaNew)) {
                                if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
                                    throw new Exception("Format gambar tidak didukung untuk variant: $variant");
                                }

                                $namaFileBaru = slugify($f . '-' . $variant);
                                $namaFileBaru = $namaFileBaru . '.webp';

                                $uploadPath = "../image/produk/" . $f . '/' . $namaFileBaru;

                                $compressed = compressResizeImage($fotoTmp, $uploadPath, 75, 1200);

                                if (!$compressed) {
                                    throw new Exception("Compress gambar gagal untuk variant: $variant");
                                }

                                $stmtFotoInsert->bind_param("iisi", $produk, $folder_id, $namaFileBaru, $nextUrutan);
                                $stmtFotoInsert->execute();
                                $fotoId = $koneksi->insert_id;
                                $nextUrutan++;
                            } elseif (!empty($fotoIdPosted)) {
                                $fotoId = (int) $fotoIdPosted;
                            } else {
                                $fotoId = null;
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
                                        "issidiiiis",
                                        $produk,
                                        $variant,
                                        $size,
                                        $size_id,
                                        $berat,
                                        $harga,
                                        $hargaCoret,
                                        $fotoId,
                                        $status,
                                        $jenis
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
    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>
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
<script>
let currentInput = null;
let fileUpload = null;

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

    let id   = $(this).data("id");
    let nama = $(this).data("nama");

    currentInput.find(".foto-id").val(id);
    currentInput.find(".foto-preview").val(nama);
    currentInput.find(".foto-file").val("");

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