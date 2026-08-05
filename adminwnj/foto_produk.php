<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
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

  <title>Admin Pusat | Wanoja</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <style>
    .foto-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .foto-card {
        position: relative;
        width: 130px;
        border: 2px solid transparent;
        border-radius: 6px;
        cursor: pointer;
        overflow: hidden;
        background: #fff;
        transition: border-color .15s ease;
    }

    .foto-card img {
        width: 100%;
        height: 110px;
        object-fit: cover;
        display: block;
    }

    .foto-card:hover {
        border-color: #b7c3d0;
    }

    .foto-card.selected {
        border-color: #4e73df;
        box-shadow: 0 0 0 2px rgba(78,115,223,.25);
    }

    .foto-card .foto-badge {
        position: absolute;
        top: 4px;
        left: 4px;
        font-size: 10px;
        padding: 1px 6px;
    }

    .foto-card .foto-hapus {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 22px;
        height: 22px;
        line-height: 20px;
        text-align: center;
        padding: 0;
        border-radius: 50%;
    }

    .foto-card .foto-caption {
        font-size: 11px;
        padding: 4px 6px;
        color: #6e707e;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .foto-upload-card {
        width: 130px;
        height: 110px;
        border: 2px dashed #b7c3d0;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: #858796;
        cursor: pointer;
    }

    .foto-upload-card:hover {
        border-color: #4e73df;
        color: #4e73df;
    }

    .variant-thumb {
        width: 42px;
        height: 42px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #e3e6f0;
    }

    .variant-thumb-empty {
        width: 42px;
        height: 42px;
        border-radius: 4px;
        border: 1px dashed #d1d3e2;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d1d3e2;
        font-size: 11px;
    }

    #variantToolbar {
        position: sticky;
        top: 0;
        z-index: 20;
        background: #fff;
        padding-bottom: 10px;
        margin-bottom: 6px;
        border-bottom: 1px solid #e3e6f0;
    }

    #selectedFotoBar {
        display: none;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 6px;
        background: #eef1fb;
        border: 1px solid #d7ddf5;
        margin-bottom: 8px;
    }

    #selectedFotoBar img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #d7ddf5;
        background: #fff;
        cursor: zoom-in;
    }

    #selectedFotoBar .selected-foto-info {
        flex: 1;
        min-width: 0;
    }

    #selectedFotoBar .selected-foto-nama {
        font-size: 12px;
        color: #6e707e;
        word-break: break-all;
    }

    .variant-table-wrap {
        max-height: 460px;
        overflow-y: auto;
        border: 1px solid #e3e6f0;
        border-radius: 4px;
    }

    .variant-table-wrap table thead th {
        position: sticky;
        top: 0;
        background: #f8f9fc;
        z-index: 5;
    }

    tr.variant-row-hidden {
        display: none;
    }

    tr.variant-row-updated {
        animation: variantFlash 1s ease;
    }

    @keyframes variantFlash {
        0%   { background-color: #d4edda; }
        100% { background-color: transparent; }
    }
  </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div class="container-fluid">
            <!-- MAINTENANCE FOTO PRODUK -->
            <div class="d-flex justify-content-between mb-0">
                <h4 style="color: #153448;">Maintenance Foto Produk</h4>
                <a href="produk.php" class="text-primary mt-1">Kembali</a>
            </div>
            <hr class="my-2" />

            <div class="form-group" style="max-width: 480px;">
                <label>Pilih Produk</label>
                <select id="productSelect" class="form-control form-control-sm">
                    <option value="">-- Pilih Produk --</option>
                    <?php
                        $query_produk = $koneksi->query("SELECT id, namaproduk FROM products ORDER BY namaproduk ASC");
                        while ($data_produk = $query_produk->fetch_assoc()) {
                            echo "<option value='{$data_produk['id']}'>{$data_produk['namaproduk']}</option>";
                        }
                    ?>
                </select>
            </div>

            <div id="fotoContent" style="display:none;">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Galeri Foto</h6>
                                <small class="text-muted">Klik foto untuk memilihnya, lalu terapkan ke variant di sebelah kanan. Klik &times; untuk menghapus.</small>
                            </div>
                            <div class="card-body">
                                <div id="galleryLoading" class="text-muted small">Pilih produk dulu...</div>
                                <div id="fotoGrid" class="foto-grid"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Daftar Variant</h6>
                                <small class="text-muted">Centang variant, pilih foto di galeri, lalu klik Terapkan.</small>
                            </div>
                            <div class="card-body">
                                <div id="variantToolbar">
                                    <div id="selectedFotoBar">
                                        <img id="selectedFotoImg" src="" alt="">
                                        <div class="selected-foto-info">
                                            <div class="small font-weight-bold text-primary">Foto terpilih</div>
                                            <div id="selectedFotoNama" class="selected-foto-nama"></div>
                                        </div>
                                        <button type="button" id="btnTerapkan" class="btn btn-sm btn-primary">Terapkan ke Variant Terpilih</button>
                                    </div>

                                    <input type="text" id="variantSearch" class="form-control form-control-sm" placeholder="Cari variant...">
                                </div>

                                <div class="variant-table-wrap">
                                    <table class="table table-sm table-hover mb-0" id="variantTable">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="checkAllVariant"></th>
                                                <th>Variant</th>
                                                <th>Size</th>
                                                <th>Foto Saat Ini</th>
                                            </tr>
                                        </thead>
                                        <tbody id="variantBody">
                                            <tr><td colspan="4" class="text-muted small">Pilih produk dulu...</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- MAINTENANCE FOTO PRODUK END -->
        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Wanoja</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->
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

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <input type="file" id="uploadInput" accept=".jpg,.jpeg,.png,.webp" class="d-none">

    <script>
    let currentProductId = null;
    let selectedFotoId   = null;

    function currentProduct() {
        return $('#productSelect').val();
    }

    function loadGallery() {
        const productId = currentProduct();
        $('#galleryLoading').text('Memuat...').show();
        $('#fotoGrid').empty();
        selectedFotoId = null;
        $('#selectedFotoBar').hide();

        $.getJSON('api/foto_produk_gallery.php', { product_id: productId }, function (res) {
            $('#galleryLoading').hide();
            if (!res.success) {
                $('#galleryLoading').text(res.message || 'Gagal memuat galeri').show();
                return;
            }
            renderGallery(res.data);
        }).fail(function () {
            $('#galleryLoading').text('Gagal memuat galeri').show();
        });
    }

    function renderGallery(items) {
        const grid = $('#fotoGrid');
        grid.empty();

        items.forEach(function (item) {
            const badge = item.dipakai > 0
                ? `<span class="badge badge-info foto-badge">${item.dipakai} variant</span>`
                : '';

            const card = $(`
                <div class="foto-card" data-id="${item.id}" data-url="${item.url}" data-nama="${item.nama}">
                    <img src="${item.url}" loading="lazy">
                    ${badge}
                    <button type="button" class="btn btn-danger btn-sm foto-hapus" title="Hapus foto">&times;</button>
                    <div class="foto-caption">${item.nama}</div>
                </div>
            `);
            grid.append(card);
        });

        const uploadCard = $(`
            <div class="foto-upload-card" id="btnUpload">
                <i class="fas fa-plus fa-lg"></i>
                <small class="mt-1">Upload Foto</small>
            </div>
        `);
        grid.append(uploadCard);
    }

    function loadVariants(flashVariants) {
        const productId = currentProduct();
        $('#variantBody').html('<tr><td colspan="4" class="text-muted small">Memuat...</td></tr>');

        $.getJSON('api/foto_produk_variants.php', { product_id: productId }, function (res) {
            if (!res.success) {
                $('#variantBody').html(`<tr><td colspan="4" class="text-danger small">${res.message || 'Gagal memuat variant'}</td></tr>`);
                return;
            }
            renderVariants(res.data);

            if (flashVariants && flashVariants.length) {
                const flashSet = flashVariants.map(v => v.toLowerCase());
                $('#variantBody tr[data-variant]').each(function () {
                    if (flashSet.includes($(this).data('variant').toString())) {
                        $(this).addClass('variant-row-updated');
                    }
                });
            }
        }).fail(function () {
            $('#variantBody').html('<tr><td colspan="4" class="text-danger small">Gagal memuat variant</td></tr>');
        });
    }

    function renderVariants(items) {
        const body = $('#variantBody');
        body.empty();

        if (items.length === 0) {
            body.html('<tr><td colspan="4" class="text-muted small">Belum ada variant untuk produk ini</td></tr>');
            return;
        }

        items.forEach(function (item) {
            const thumb = item.foto_url
                ? `<img src="${item.foto_url}" class="variant-thumb" loading="lazy">`
                : `<div class="variant-thumb-empty">-</div>`;

            const warn = !item.konsisten
                ? `<i class="fas fa-exclamation-triangle text-warning ml-1" title="Size dalam variant ini pakai foto yang beda-beda"></i>`
                : '';

            const row = $(`
                <tr data-variant="${item.variant.toLowerCase()}">
                    <td><input type="checkbox" class="variant-check" value="${item.variant}"></td>
                    <td>${item.variant} ${warn}</td>
                    <td>${item.jumlah_size}</td>
                    <td>${thumb}</td>
                </tr>
            `);
            body.append(row);
        });

        applyVariantSearch();
    }

    function applyVariantSearch() {
        const keyword = $('#variantSearch').val().trim().toLowerCase();
        $('#variantBody tr[data-variant]').each(function () {
            const match = !keyword || $(this).data('variant').toString().includes(keyword);
            $(this).toggleClass('variant-row-hidden', !match);
        });
    }

    $(document).on('change', '#productSelect', function () {
        currentProductId = $(this).val();
        if (!currentProductId) {
            $('#fotoContent').hide();
            return;
        }
        $('#fotoContent').show();
        loadGallery();
        loadVariants();
    });

    $(document).on('click', '#checkAllVariant', function () {
        const checked = $(this).is(':checked');
        // Hanya centang/uncentang baris yang sedang terlihat (mengikuti filter pencarian).
        $('#variantBody tr[data-variant]').not('.variant-row-hidden').find('.variant-check').prop('checked', checked);
    });

    $(document).on('input', '#variantSearch', function () {
        applyVariantSearch();
        $('#checkAllVariant').prop('checked', false);
    });

    // Klik foto terpilih buat lihat ukuran penuh
    $(document).on('click', '#selectedFotoImg', function () {
        const url = $(this).attr('src');
        if (url) window.open(url, '_blank');
    });

    // Pilih foto di galeri
    $(document).on('click', '.foto-card', function () {
        $('.foto-card').removeClass('selected');
        $(this).addClass('selected');

        selectedFotoId = $(this).data('id');

        $('#selectedFotoImg').attr('src', $(this).data('url'));
        $('#selectedFotoNama').text($(this).data('nama'));
        $('#selectedFotoBar').css('display', 'flex');
    });

    // Hapus foto
    $(document).on('click', '.foto-hapus', function (e) {
        e.stopPropagation();
        const card   = $(this).closest('.foto-card');
        const fotoId = card.data('id');
        const nama   = card.data('nama');

        if (!confirm(`Hapus foto "${nama}"? Variant yang memakai foto ini akan otomatis lepas ikatan.`)) {
            return;
        }

        $.post('api/foto_produk_delete.php', { foto_id: fotoId }, function (res) {
            if (res.success) {
                if (selectedFotoId == fotoId) {
                    selectedFotoId = null;
                    $('#selectedFotoBar').hide();
                }
                loadGallery();
                loadVariants();
            } else {
                alert('Gagal hapus: ' + (res.message || 'Terjadi kesalahan'));
            }
        }, 'json').fail(function () {
            alert('Gagal hapus: tidak bisa terhubung ke server');
        });
    });

    // Buka file picker
    $(document).on('click', '#btnUpload', function () {
        $('#uploadInput').val('').trigger('click');
    });

    // Upload foto baru
    $(document).on('change', '#uploadInput', function () {
        const file = this.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('product_id', currentProduct());
        formData.append('foto', file);

        $.ajax({
            url: 'api/foto_produk_upload.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json'
        }).done(function (res) {
            if (res.success) {
                loadGallery();
            } else {
                alert('Gagal upload: ' + (res.message || 'Terjadi kesalahan'));
            }
        }).fail(function () {
            alert('Gagal upload: tidak bisa terhubung ke server');
        });
    });

    // Terapkan foto terpilih ke variant tercentang
    $(document).on('click', '#btnTerapkan', function () {
        if (!selectedFotoId) {
            alert('Pilih foto di galeri dulu');
            return;
        }

        const variants = $('.variant-check:checked').map(function () {
            return $(this).val();
        }).get();

        if (variants.length === 0) {
            alert('Centang minimal 1 variant');
            return;
        }

        $.post('api/foto_produk_link.php', {
            product_id: currentProduct(),
            foto_id: selectedFotoId,
            variants: variants
        }, function (res) {
            if (res.success) {
                loadGallery();
                loadVariants(variants);
            } else {
                alert('Gagal menerapkan: ' + (res.message || 'Terjadi kesalahan'));
            }
        }, 'json').fail(function () {
            alert('Gagal menerapkan: tidak bisa terhubung ke server');
        });
    });
    </script>
</body>

</html>
