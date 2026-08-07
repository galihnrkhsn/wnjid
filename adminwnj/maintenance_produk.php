<?php
    session_start();
    include 'koneksi.php';
    include '../includes/access_helper.php';

    if (!isset($_SESSION["administrator"])) {
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $pesan = '';
    $error = '';
    $idproduk = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    // ---- Update data produk ----
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_produk'])) {
        $id          = (int) $_POST['id'];
        $namaproduk  = trim($_POST['namaproduk'] ?? '');
        $idpkategori = (int) ($_POST['idpkategori'] ?? 0);
        $idkategori  = (int) ($_POST['idkategori'] ?? 0);
        $kodeArtikel = trim($_POST['kode_artikel'] ?? '');
        $kodeArtikel = $kodeArtikel !== '' ? $kodeArtikel : null;
        $spek        = trim($_POST['spek'] ?? '');
        $deskripsi   = trim($_POST['deskripsi'] ?? '');
        $jenisInput  = trim($_POST['jenis'] ?? '');
        $jenis       = $jenisInput !== '' ? $jenisInput : null;

        $access = 0;
        foreach ($_POST['access'] ?? [] as $bit) {
            $access |= (int) $bit;
        }

        if ($namaproduk === '' || $idpkategori <= 0 || $idkategori <= 0) {
            $error = 'Nama produk, kategori produk, dan kategori diskon wajib diisi';
        } else {
            $koneksi->begin_transaction();
            try {
                $stmt = $koneksi->prepare("UPDATE products
                                            SET namaproduk = ?, idpkategori = ?, idkategori = ?, kode_artikel = ?, spek = ?, deskripsi = ?, jenis = ?, access = ?, updated_at = NOW()
                                            WHERE id = ?");
                $stmt->bind_param('siissssii', $namaproduk, $idpkategori, $idkategori, $kodeArtikel, $spek, $deskripsi, $jenis, $access, $id);
                $stmt->execute();

                // products.jenis jadi sumber utama (dipakai konsumen/), variants.jenis TETAP disamakan
                // karena masih dipakai langsung oleh distributor/agen/mitra lain.
                $stmtSyncVarian = $koneksi->prepare("UPDATE variants SET jenis = ? WHERE idproducts = ?");
                $stmtSyncVarian->bind_param('si', $jenis, $id);
                $stmtSyncVarian->execute();

                $koneksi->commit();
                $pesan = 'Data produk berhasil diperbarui';
            } catch (Exception $e) {
                $koneksi->rollback();
                $error = 'Gagal memperbarui data produk';
            }
        }
        $idproduk = $id;
    }

    // ---- Tambah variant baru ----
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_variant'])) {
        $idproduk   = (int) $_POST['idproduk'];
        $variant    = trim($_POST['variant'] ?? '');
        $size_id    = (int) ($_POST['size_id'] ?? 0);
        $berat      = (int) ($_POST['berat'] ?? 0);
        $harga      = (int) ($_POST['harga'] ?? 0);
        $hargacoret = (int) ($_POST['hargacoret'] ?? 0);
        $disc       = (int) ($_POST['disc'] ?? 0);
        $stock      = (int) ($_POST['stock'] ?? 0);

        if ($variant === '' || $size_id <= 0 || $harga <= 0) {
            $error = 'Nama varian, ukuran, dan harga wajib diisi';
        } else {
            // jenis ikut produk induknya (dikelola sekali di form Data Produk), bukan per-varian lagi.
            $stmtJenisProduk = $koneksi->prepare("SELECT jenis FROM products WHERE id = ?");
            $stmtJenisProduk->bind_param('i', $idproduk);
            $stmtJenisProduk->execute();
            $jenis = $stmtJenisProduk->get_result()->fetch_assoc()['jenis'] ?? null;

            $stmtCheck = $koneksi->prepare("SELECT id FROM variants WHERE idproducts = ? AND variant = ? AND size_id = ?");
            $stmtCheck->bind_param('isi', $idproduk, $variant, $size_id);
            $stmtCheck->execute();
            if ($stmtCheck->get_result()->fetch_assoc()) {
                $error = 'Kombinasi varian + ukuran ini sudah ada';
            } else {
                $stmt = $koneksi->prepare("INSERT INTO variants
                                            (idproducts, variant, size, size_id, berat, harga, hargacoret, disc, stock, status, jenis, tgl, updated_at)
                                            VALUES (?, ?, (SELECT nama_size FROM master_size WHERE id = ?), ?, ?, ?, ?, ?, ?, 0, ?, NOW(), NOW())");
                $stmt->bind_param('isiiiiiiis', $idproduk, $variant, $size_id, $size_id, $berat, $harga, $hargacoret, $disc, $stock, $jenis);
                if ($stmt->execute()) {
                    $pesan = 'Varian berhasil ditambahkan';
                } else {
                    $error = 'Gagal menambahkan varian';
                }
            }
        }
    }

    // ---- Edit variant ----
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_variant'])) {
        $idvariant  = (int) $_POST['idvariant'];
        $idproduk   = (int) $_POST['idproduk'];
        $variant    = trim($_POST['variant'] ?? '');
        $size_id    = (int) ($_POST['size_id'] ?? 0);
        $berat      = (int) ($_POST['berat'] ?? 0);
        $harga      = (int) ($_POST['harga'] ?? 0);
        $hargacoret = (int) ($_POST['hargacoret'] ?? 0);
        $disc       = (int) ($_POST['disc'] ?? 0);
        $stock      = (int) ($_POST['stock'] ?? 0);

        if ($variant === '' || $size_id <= 0 || $harga <= 0) {
            $error = 'Nama varian, ukuran, dan harga wajib diisi';
        } else {
            // jenis ikut produk induknya (dikelola sekali di form Data Produk), bukan per-varian lagi.
            $stmtJenisProduk = $koneksi->prepare("SELECT jenis FROM products WHERE id = ?");
            $stmtJenisProduk->bind_param('i', $idproduk);
            $stmtJenisProduk->execute();
            $jenis = $stmtJenisProduk->get_result()->fetch_assoc()['jenis'] ?? null;

            $stmt = $koneksi->prepare("UPDATE variants
                                        SET variant = ?, size = (SELECT nama_size FROM master_size WHERE id = ?), size_id = ?,
                                            berat = ?, harga = ?, hargacoret = ?, disc = ?, stock = ?, jenis = ?, updated_at = NOW()
                                        WHERE id = ?");
            $stmt->bind_param('siiiiiiisi', $variant, $size_id, $size_id, $berat, $harga, $hargacoret, $disc, $stock, $jenis, $idvariant);
            if ($stmt->execute()) {
                $pesan = 'Varian berhasil diperbarui';
            } else {
                $error = 'Gagal memperbarui varian';
            }
        }
    }

    // ---- Toggle status (aktif/nonaktif) - soft "hapus", konsisten dgn status<>1 = aktif di semua halaman konsumen ----
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
        $idvariant = (int) $_POST['idvariant'];
        $idproduk  = (int) $_POST['idproduk'];

        $stmt = $koneksi->prepare("UPDATE variants SET status = IF(status <> 1, 1, 0), updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('i', $idvariant);
        $stmt->execute();
        $pesan = 'Status varian berhasil diubah';
    }

    $allProducts = $koneksi->query("SELECT id, namaproduk FROM products ORDER BY namaproduk ASC")->fetch_all(MYSQLI_ASSOC);
    $pkategoriList = $koneksi->query("SELECT * FROM pkategori ORDER BY namakategori ASC")->fetch_all(MYSQLI_ASSOC);
    $kategoriList  = $koneksi->query("SELECT * FROM kategori ORDER BY namakategori ASC")->fetch_all(MYSQLI_ASSOC);
    $jenisList     = $koneksi->query("SELECT nama_jenis FROM master_jenis_products ORDER BY nama_jenis ASC")->fetch_all(MYSQLI_ASSOC);
    $sizeList      = $koneksi->query("SELECT * FROM master_size ORDER BY kategori ASC, seq ASC")->fetch_all(MYSQLI_ASSOC);
    $sizePerKategori = [];
    foreach ($sizeList as $sz) {
        $sizePerKategori[$sz['kategori']][] = $sz;
    }

    $produk   = null;
    $variants = [];
    if ($idproduk > 0) {
        $stmtP = $koneksi->prepare("SELECT * FROM products WHERE id = ?");
        $stmtP->bind_param('i', $idproduk);
        $stmtP->execute();
        $produk = $stmtP->get_result()->fetch_assoc();

        if ($produk) {
            $stmtV = $koneksi->prepare("SELECT v.*, ms.nama_size, ms.kategori AS size_kategori
                                        FROM variants v
                                        LEFT JOIN master_size ms ON ms.id = v.size_id
                                        WHERE v.idproducts = ?
                                        ORDER BY v.variant ASC, ms.seq ASC");
            $stmtV->bind_param('i', $idproduk);
            $stmtV->execute();
            $variants = $stmtV->get_result()->fetch_all(MYSQLI_ASSOC);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Maintenance Produk | WNJ.ID</title>

    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/wnj-theme.css" rel="stylesheet">
    <style>
        #productSearchWrap {
            position: relative;
            max-width: 500px;
        }
        #productSearchResults {
            display: none;
            position: absolute;
            z-index: 30;
            max-height: 300px;
            overflow-y: auto;
            width: 100%;
        }
        .status-badge {
            font-size: .7rem;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 600;
        }
        .status-aktif {
            background: #d1f5e0;
            color: #14804a;
        }
        .status-nonaktif {
            background: #f5d1d1;
            color: #a31414;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Maintenance Produk</h1>

                    <?php if ($pesan): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($pesan) ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <label class="font-weight-bold">Cari Produk</label>
                            <div id="productSearchWrap">
                                <input type="text" id="productSearchInput" class="form-control" placeholder="Ketik nama produk..." autocomplete="off" value="<?= $produk ? htmlspecialchars($produk['namaproduk']) : '' ?>">
                                <div id="productSearchResults" class="list-group shadow-sm"></div>
                            </div>
                        </div>
                    </div>

                    <?php if ($idproduk > 0 && !$produk): ?>
                        <div class="alert alert-warning">Produk tidak ditemukan.</div>
                    <?php endif; ?>

                    <?php if ($produk): ?>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Data Produk</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= (int) $produk['id'] ?>">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Nama Produk</label>
                                            <input type="text" name="namaproduk" class="form-control" value="<?= htmlspecialchars($produk['namaproduk']) ?>" required>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label>Kategori Produk</label>
                                            <select name="idpkategori" class="form-control" required>
                                                <?php foreach ($pkategoriList as $pk): ?>
                                                    <option value="<?= (int) $pk['idpkategori'] ?>" <?= $pk['idpkategori'] == $produk['idpkategori'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($pk['namakategori']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label>Kategori Diskon</label>
                                            <select name="idkategori" class="form-control" required>
                                                <?php foreach ($kategoriList as $k): ?>
                                                    <option value="<?= (int) $k['idkategori'] ?>" <?= $k['idkategori'] == $produk['idkategori'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($k['namakategori']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Kode Artikel <span class="text-muted small">(opsional, buat promo B1G1)</span></label>
                                            <input type="text" name="kode_artikel" class="form-control" value="<?= htmlspecialchars($produk['kode_artikel'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Jenis <span class="text-muted small">(opsional, promo: b1g1/flash/dll)</span></label>
                                            <input type="text" name="jenis" class="form-control" list="jenisList" value="<?= htmlspecialchars($produk['jenis'] ?? '') ?>">
                                            <small class="text-muted">Berlaku untuk semua varian produk ini.</small>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Spek <span class="text-muted small">(opsional)</span></label>
                                            <input type="text" name="spek" class="form-control" value="<?= htmlspecialchars($produk['spek'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label>Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control" rows="6"><?= htmlspecialchars($produk['deskripsi'] ?? '') ?></textarea>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label class="d-block">Akses Mitra <span class="text-muted small">(kosongkan = semua mitra bisa akses)</span></label>
                                            <?php
                                                $accessOptions = [
                                                    'Distributor' => ACCESS_DISTRIBUTOR,
                                                    'Agen'        => ACCESS_AGEN,
                                                    'Reseller'    => ACCESS_RESELLER,
                                                    'Marketer'    => ACCESS_MARKETER,
                                                    'Konsumen'    => ACCESS_KONSUMEN,
                                                ];
                                            ?>
                                            <?php foreach ($accessOptions as $label => $bit): ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="access[]" value="<?= $bit ?>" id="access<?= $bit ?>"
                                                        <?= ((int) $produk['access'] & $bit) !== 0 ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="access<?= $bit ?>"><?= $label ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <button type="submit" name="simpan_produk" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Simpan Perubahan
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Varian &amp; Ukuran</h6>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahVariant">
                                    <i class="fa fa-plus"></i> Tambah Varian
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Varian</th>
                                            <th>Ukuran</th>
                                            <th>Harga</th>
                                            <th>Harga Coret</th>
                                            <th>Disc</th>
                                            <th>Stok</th>
                                            <th>Jenis</th>
                                            <th>Status</th>
                                            <th width="140" class="text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($variants)): ?>
                                            <tr><td colspan="9" class="text-center text-muted py-4">Belum ada varian untuk produk ini</td></tr>
                                        <?php endif; ?>
                                        <?php foreach ($variants as $v): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($v['variant']) ?></td>
                                                <td><?= htmlspecialchars($v['nama_size'] ?? $v['size']) ?></td>
                                                <td>Rp <?= number_format($v['harga']) ?></td>
                                                <td><?= $v['hargacoret'] > 0 ? 'Rp ' . number_format($v['hargacoret']) : '-' ?></td>
                                                <td><?= $v['disc'] > 0 ? $v['disc'] . '%' : '-' ?></td>
                                                <td><?= (int) $v['stock'] ?></td>
                                                <td><?= htmlspecialchars($v['jenis'] ?: '-') ?></td>
                                                <td>
                                                    <?php if ($v['status'] != 1): ?>
                                                        <span class="status-badge status-aktif">Aktif</span>
                                                    <?php else: ?>
                                                        <span class="status-badge status-nonaktif">Nonaktif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-right">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalEditVariant<?= (int) $v['id'] ?>">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <form method="POST" class="d-inline" onsubmit="return confirm('<?= $v['status'] != 1 ? 'Nonaktifkan' : 'Aktifkan' ?> varian ini?');">
                                                        <input type="hidden" name="idvariant" value="<?= (int) $v['id'] ?>">
                                                        <input type="hidden" name="idproduk" value="<?= (int) $idproduk ?>">
                                                        <button type="submit" name="toggle_status" class="btn btn-sm <?= $v['status'] != 1 ? 'btn-outline-danger' : 'btn-outline-success' ?>">
                                                            <i class="fa <?= $v['status'] != 1 ? 'fa-ban' : 'fa-check' ?>"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Variant -->
                                            <div class="modal fade" id="modalEditVariant<?= (int) $v['id'] ?>" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form method="POST">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Ubah Varian</h5>
                                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="idvariant" value="<?= (int) $v['id'] ?>">
                                                                <input type="hidden" name="idproduk" value="<?= (int) $idproduk ?>">
                                                                <div class="form-group">
                                                                    <label>Nama Varian</label>
                                                                    <input type="text" name="variant" class="form-control" value="<?= htmlspecialchars($v['variant']) ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Ukuran</label>
                                                                    <select name="size_id" class="form-control" required>
                                                                        <?php foreach ($sizePerKategori as $kat => $items): ?>
                                                                            <optgroup label="<?= htmlspecialchars($kat) ?>">
                                                                                <?php foreach ($items as $sz): ?>
                                                                                    <option value="<?= (int) $sz['id'] ?>" <?= $sz['id'] == $v['size_id'] ? 'selected' : '' ?>>
                                                                                        <?= htmlspecialchars($sz['nama_size']) ?>
                                                                                    </option>
                                                                                <?php endforeach; ?>
                                                                            </optgroup>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col form-group">
                                                                        <label>Berat (gram)</label>
                                                                        <input type="number" name="berat" class="form-control" value="<?= (int) $v['berat'] ?>" min="0">
                                                                    </div>
                                                                    <div class="col form-group">
                                                                        <label>Stok</label>
                                                                        <input type="number" name="stock" class="form-control" value="<?= (int) $v['stock'] ?>" min="0">
                                                                    </div>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="col form-group">
                                                                        <label>Harga</label>
                                                                        <input type="number" name="harga" class="form-control" value="<?= (int) $v['harga'] ?>" min="0" required>
                                                                    </div>
                                                                    <div class="col form-group">
                                                                        <label>Harga Coret</label>
                                                                        <input type="number" name="hargacoret" class="form-control" value="<?= (int) $v['hargacoret'] ?>" min="0">
                                                                    </div>
                                                                    <div class="col form-group">
                                                                        <label>Disc (%)</label>
                                                                        <input type="number" name="disc" class="form-control" value="<?= (int) $v['disc'] ?>" min="0" max="100">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                <button type="submit" name="edit_variant" class="btn btn-primary">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Modal Tambah Variant -->
                        <div class="modal fade" id="modalTambahVariant" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tambah Varian</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="idproduk" value="<?= (int) $idproduk ?>">
                                            <div class="form-group">
                                                <label>Nama Varian</label>
                                                <input type="text" name="variant" class="form-control" list="variantExistingList" placeholder="Contoh: Black" required>
                                                <datalist id="variantExistingList">
                                                    <?php foreach (array_unique(array_column($variants, 'variant')) as $vn): ?>
                                                        <option value="<?= htmlspecialchars($vn) ?>">
                                                    <?php endforeach; ?>
                                                </datalist>
                                            </div>
                                            <div class="form-group">
                                                <label>Ukuran</label>
                                                <select name="size_id" class="form-control" required>
                                                    <option value="">Pilih Ukuran</option>
                                                    <?php foreach ($sizePerKategori as $kat => $items): ?>
                                                        <optgroup label="<?= htmlspecialchars($kat) ?>">
                                                            <?php foreach ($items as $sz): ?>
                                                                <option value="<?= (int) $sz['id'] ?>"><?= htmlspecialchars($sz['nama_size']) ?></option>
                                                            <?php endforeach; ?>
                                                        </optgroup>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-row">
                                                <div class="col form-group">
                                                    <label>Berat (gram)</label>
                                                    <input type="number" name="berat" class="form-control" min="0" value="0">
                                                </div>
                                                <div class="col form-group">
                                                    <label>Stok</label>
                                                    <input type="number" name="stock" class="form-control" min="0" value="0">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col form-group">
                                                    <label>Harga</label>
                                                    <input type="number" name="harga" class="form-control" min="0" required>
                                                </div>
                                                <div class="col form-group">
                                                    <label>Harga Coret</label>
                                                    <input type="number" name="hargacoret" class="form-control" min="0" value="0">
                                                </div>
                                                <div class="col form-group">
                                                    <label>Disc (%)</label>
                                                    <input type="number" name="disc" class="form-control" min="0" max="100" value="0">
                                                </div>
                                            </div>
                                            <small class="text-muted">Jenis promo ikut produk induk (atur di form Data Produk di atas).</small>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" name="tambah_variant" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <datalist id="jenisList">
                            <?php foreach ($jenisList as $j): ?>
                                <option value="<?= htmlspecialchars($j['nama_jenis']) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    <?php endif; ?>
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; WNJ.ID</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script>
        const ALL_PRODUCTS = <?= json_encode($allProducts) ?>;

        $('#productSearchInput').on('input', function () {
            const q       = $(this).val().trim().toLowerCase();
            const results = $('#productSearchResults');
            results.empty();

            if (q === '') {
                results.hide();
                return;
            }

            const matches = ALL_PRODUCTS.filter(p => p.namaproduk.toLowerCase().includes(q)).slice(0, 20);

            if (matches.length === 0) {
                results.html('<div class="list-group-item text-muted small">Produk tidak ditemukan</div>').show();
                return;
            }

            matches.forEach(function (p) {
                const item = $(`<button type="button" class="list-group-item list-group-item-action">${p.namaproduk}</button>`);
                item.on('click', function () {
                    window.location.href = 'maintenance_produk.php?id=' + p.id;
                });
                results.append(item);
            });

            results.show();
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('#productSearchInput, #productSearchResults').length) {
                $('#productSearchResults').hide();
            }
        });
    </script>
</body>

</html>
