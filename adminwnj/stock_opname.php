<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['administrator'])) {
    header('Location: login.php');
    exit;
}

$idproduk = (int) ($_GET['idproduk'] ?? $_POST['idproduk'] ?? 0);
$pesan = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_stock'])) {
    if ($idproduk <= 0) {
        $pesan = ['type' => 'danger', 'text' => 'Produk tidak valid.'];
    } else {
        try {
            $stmtReset = $koneksi->prepare('UPDATE variants SET stock = 0, status = 1, updated_at = NOW() WHERE idproducts = ?');
            $stmtReset->bind_param('i', $idproduk);
            $stmtReset->execute();
            $jumlah = $stmtReset->affected_rows;
            $stmtReset->close();
            $pesan = ['type' => 'success', 'text' => "Stok direset menjadi 0 dan status dinonaktifkan untuk $jumlah varian."];
        } catch (Throwable $e) {
            $pesan = ['type' => 'danger', 'text' => 'Reset stok gagal disimpan.'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_stock_opname'])) {
    $stockPost = $_POST['stock'] ?? [];

    if ($idproduk <= 0 || !is_array($stockPost) || empty($stockPost)) {
        $pesan = ['type' => 'danger', 'text' => 'Pilih produk dan isi data stok terlebih dahulu.'];
    } else {
        try {
            $koneksi->begin_transaction();

            // ID varian selalu divalidasi dengan produk terpilih agar stok produk lain tidak dapat ikut berubah.
            $stmt = $koneksi->prepare('UPDATE variants SET stock = ?, updated_at = NOW(), status = 0 WHERE id = ? AND idproducts = ?');
            $jumlah = 0;
            foreach ($stockPost as $idvariant => $stock) {
                $idvariant = (int) $idvariant;
                if ($idvariant <= 0 || !is_scalar($stock) || !preg_match('/^\d+$/', (string) $stock)) {
                    throw new Exception('Stok harus berupa angka bulat nol atau lebih.');
                }

                $nilaiStock = (int) $stock;
                $stmt->bind_param('iii', $nilaiStock, $idvariant, $idproduk);
                $stmt->execute();
                $jumlah++;
            }
            $stmt->close();
            $koneksi->commit();
            $pesan = ['type' => 'success', 'text' => "Stock opname selesai. $jumlah varian diperbarui."];
        } catch (Throwable $e) {
            $koneksi->rollback();
            $pesan = ['type' => 'danger', 'text' => 'Stock opname gagal disimpan: ' . $e->getMessage()];
        }
    }
}

$produkList = $koneksi->query('SELECT id, namaproduk FROM products ORDER BY namaproduk ASC')->fetch_all(MYSQLI_ASSOC);
$produk = null;
$variants = [];
if ($idproduk > 0) {
    $stmtProduk = $koneksi->prepare('SELECT id, namaproduk FROM products WHERE id = ?');
    $stmtProduk->bind_param('i', $idproduk);
    $stmtProduk->execute();
    $produk = $stmtProduk->get_result()->fetch_assoc();
    $stmtProduk->close();

    if ($produk) {
        $stmtVariants = $koneksi->prepare('SELECT v.id, v.variant, v.size, v.size_id, v.stock, ms.nama_size
            FROM variants v
            LEFT JOIN master_size ms ON ms.id = v.size_id
            WHERE v.idproducts = ?
            ORDER BY v.variant ASC, v.size_id ASC');
        $stmtVariants->bind_param('i', $idproduk);
        $stmtVariants->execute();
        $variants = $stmtVariants->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmtVariants->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock Opname | WNJ.ID</title>
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        #productSearchWrap { position: relative; max-width: 520px; }
        #productSearchResults {
            display: none; position: absolute; z-index: 30; width: 100%;
            max-height: 300px; overflow-y: auto;
        }
        .stock-focused td {
            background-color: #e8f1ff !important;
            transition: background-color .2s ease;
        }
    </style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="container-fluid mt-3">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="mb-1">Stock Opname</h3>
                <p class="text-muted mb-0">Pilih satu produk, lalu masukkan stok fisik setiap varian dan size.</p>
            </div>
        </div>

        <?php if ($pesan): ?>
            <div class="alert alert-<?= htmlspecialchars($pesan['type']) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($pesan['text']) ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endif; ?>

        <div class="card shadow mb-4">
            <div class="card-body">
                <label class="font-weight-bold" for="productSearchInput">Cari Produk</label>
                <div id="productSearchWrap">
                    <input type="search" id="productSearchInput" class="form-control"
                           placeholder="Ketik nama produk..." autocomplete="off"
                           value="<?= $produk ? htmlspecialchars($produk['namaproduk']) : '' ?>">
                    <div id="productSearchResults" class="list-group shadow-sm"></div>
                </div>
                <small class="text-muted">Pilih produk dari hasil pencarian untuk menampilkan varian dan size.</small>
            </div>
        </div>

        <?php if ($idproduk > 0 && !$produk): ?>
            <div class="alert alert-warning">Produk tidak ditemukan.</div>
        <?php elseif ($produk): ?>
            <form method="post" onsubmit="return konfirmasiStockOpname(event);">
                <div class="card mb-4">
                    <button type="submit" name="reset_stock" value="1" class="btn btn-danger">
                        <i class="fas fa-undo"></i> Reset Stock
                    </button>
                </div>
                <input type="hidden" name="idproduk" value="<?= (int) $idproduk ?>">
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><strong><?= htmlspecialchars($produk['namaproduk']) ?></strong></div>
                    <div class="card-body">
                        <?php if (empty($variants)): ?>
                            <div class="text-muted">Produk ini belum memiliki varian.</div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-3">
                                    <thead><tr><th style="width:70px">No</th><th>Variant</th><th>Size</th><th>Stok Sistem</th><th style="width:180px">Update Stok</th></tr></thead>
                                    <tbody>
                                    <?php foreach ($variants as $no => $v): ?>
                                        <tr>
                                            <td><?= $no + 1 ?></td>
                                            <td><?= htmlspecialchars($v['variant']) ?></td>
                                            <td><?= htmlspecialchars($v['nama_size'] ?: $v['size']) ?></td>
                                            <td><?= (int) $v['stock'] ?></td>
                                            <td><input type="number" min="0" step="1" required class="form-control stock-opname-input"
                                                       name="stock[<?= (int) $v['id'] ?>]" value="<?= (int) $v['stock'] ?>"></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex flex-wrap" style="gap: .5rem;">
                                <button type="submit" name="simpan_stock_opname" value="1" class="btn btn-success"><i class="fas fa-save"></i> Update Semua Stok</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
<script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
<script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
<script>
    const ALL_PRODUCTS = <?= json_encode($produkList, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

    function normalisasiPencarianProduk(text) {
        return String(text)
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, ' ')
            .trim();
    }

    $('#productSearchInput').on('input', function () {
        const keyword = normalisasiPencarianProduk($(this).val());
        const results = $('#productSearchResults').empty();

        if (keyword === '') {
            results.hide();
            return;
        }

        // Semua kata perlu ada pada nama produk, tanpa harus mengikuti urutan ketik.
        // Contoh: "bergo konin 21" cocok dengan "konin 21 bergo".
        const kataKunci = keyword.split(/\s+/);
        const matches = ALL_PRODUCTS.filter(function (product) {
            const namaProduk = normalisasiPencarianProduk(product.namaproduk);
            return kataKunci.every(kata => namaProduk.includes(kata));
        }).slice(0, 20);

        if (matches.length === 0) {
            results.append($('<div>', { class: 'list-group-item text-muted small', text: 'Produk tidak ditemukan' })).show();
            return;
        }

        matches.forEach(function (product) {
            const item = $('<button>', {
                type: 'button', class: 'list-group-item list-group-item-action', text: product.namaproduk
            });
            item.on('click', function () {
                window.location.href = 'stock_opname.php?idproduk=' + encodeURIComponent(product.id);
            });
            results.append(item);
        });
        results.show();
    });

    $(document).on('click', function (event) {
        if (!$(event.target).closest('#productSearchInput, #productSearchResults').length) {
            $('#productSearchResults').hide();
        }
    });

    $(document).on('focus', '.stock-opname-input', function () {
        $('.stock-focused').removeClass('stock-focused');
        $(this).closest('tr').addClass('stock-focused');
    });

    $(document).on('blur', '.stock-opname-input', function () {
        $(this).closest('tr').removeClass('stock-focused');
    });

    function konfirmasiStockOpname(event) {
        const tombol = event.submitter;
        if (tombol && tombol.name === 'reset_stock') {
            return confirm('Yakin reset stok semua varian produk ini menjadi 0? Semua variannya juga akan dinonaktifkan.');
        }
        return confirm('Update stok semua baris yang ditampilkan?');
    }
</script>
</body>
</html>
