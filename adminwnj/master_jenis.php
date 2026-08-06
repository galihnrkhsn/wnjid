<?php
    session_start();
    include 'koneksi.php';

    if (!isset($_SESSION["administrator"])) {
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $pesan = '';
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
        $kode      = trim($_POST['nama_jenis'] ?? '');
        $nama      = trim($_POST['nama'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $deskripsi = $deskripsi !== '' ? $deskripsi : null;

        if ($kode === '' || $nama === '') {
            $error = 'Kode dan nama wajib diisi';
        } else {
            $stmt = $koneksi->prepare("INSERT INTO master_jenis_products (nama_jenis, nama, deskripsi) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $kode, $nama, $deskripsi);
            if ($stmt->execute()) {
                $pesan = "Jenis promo \"$nama\" berhasil ditambahkan";
            } else {
                $error = $koneksi->errno === 1062 ? "Kode \"$kode\" sudah ada" : 'Gagal menambahkan jenis promo';
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
        $id        = (int) $_POST['id'];
        $kode      = trim($_POST['nama_jenis'] ?? '');
        $nama      = trim($_POST['nama'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $deskripsi = $deskripsi !== '' ? $deskripsi : null;

        if ($kode === '' || $nama === '') {
            $error = 'Kode dan nama wajib diisi';
        } else {
            $stmt = $koneksi->prepare("UPDATE master_jenis_products SET nama_jenis = ?, nama = ?, deskripsi = ? WHERE id = ?");
            $stmt->bind_param('sssi', $kode, $nama, $deskripsi, $id);
            if ($stmt->execute()) {
                $pesan = "Jenis promo \"$nama\" berhasil diubah";
            } else {
                $error = $koneksi->errno === 1062 ? "Kode \"$kode\" sudah dipakai jenis lain" : 'Gagal mengubah jenis promo';
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
        $id = (int) $_POST['id'];

        $stmtRow = $koneksi->prepare("SELECT nama_jenis FROM master_jenis_products WHERE id = ?");
        $stmtRow->bind_param('i', $id);
        $stmtRow->execute();
        $kode = $stmtRow->get_result()->fetch_assoc()['nama_jenis'] ?? '';

        $stmtCekP = $koneksi->prepare("SELECT COUNT(*) c FROM products WHERE jenis = ?");
        $stmtCekP->bind_param('s', $kode);
        $stmtCekP->execute();
        $jumlahProduk = (int) $stmtCekP->get_result()->fetch_assoc()['c'];

        if ($jumlahProduk > 0) {
            $error = "Tidak bisa dihapus, jenis ini masih dipakai oleh $jumlahProduk produk";
        } else {
            $stmt = $koneksi->prepare("DELETE FROM master_jenis_products WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $pesan = 'Jenis promo berhasil dihapus';
        }
    }

    $jenisRows = $koneksi->query("SELECT * FROM master_jenis_products ORDER BY nama_jenis ASC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Master Jenis Promo | Wanoja</title>

    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/wnj-theme.css" rel="stylesheet">
    <style>
        .kode-badge {
            display: inline-block;
            background: #eaecf4;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: .8rem;
            font-weight: 600;
            color: #6e707e;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Master Jenis Promo</h1>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah">
                            <i class="fa fa-plus"></i> Tambah Jenis Promo
                        </button>
                    </div>

                    <?php if ($pesan): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($pesan) ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <p class="text-muted small">
                        <i class="fa fa-info-circle"></i>
                        "Kode" adalah nilai yang dipilih di field Jenis pada halaman Tambah/Maintenance Produk.
                        "Nama" jadi label badge yang tampil ke konsumen, "Deskripsi" jadi penjelasan aturan promo untuk konsumen.
                    </p>

                    <div class="card shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="140">Kode</th>
                                            <th width="180">Nama Badge</th>
                                            <th>Deskripsi (aturan promo)</th>
                                            <th width="100" class="text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($jenisRows)): ?>
                                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data</td></tr>
                                        <?php endif; ?>
                                        <?php foreach ($jenisRows as $j): ?>
                                            <tr>
                                                <td><span class="kode-badge"><?= htmlspecialchars($j['nama_jenis']) ?></span></td>
                                                <td><?= htmlspecialchars($j['nama'] ?: '-') ?></td>
                                                <td><?= $j['deskripsi'] ? htmlspecialchars($j['deskripsi']) : '<span class="text-muted">belum diisi</span>' ?></td>
                                                <td class="text-right">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalEdit<?= (int) $j['id'] ?>">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <form method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus jenis promo &quot;<?= htmlspecialchars($j['nama_jenis'], ENT_QUOTES) ?>&quot;?');">
                                                        <input type="hidden" name="id" value="<?= (int) $j['id'] ?>">
                                                        <button type="submit" name="hapus" class="btn btn-sm btn-outline-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit -->
                                            <div class="modal fade" id="modalEdit<?= (int) $j['id'] ?>" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form method="POST">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Ubah Jenis Promo</h5>
                                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" value="<?= (int) $j['id'] ?>">
                                                                <div class="form-group">
                                                                    <label>Kode</label>
                                                                    <input type="text" name="nama_jenis" class="form-control" value="<?= htmlspecialchars($j['nama_jenis']) ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Nama Badge</label>
                                                                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($j['nama'] ?? '') ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Deskripsi <span class="text-muted small">(aturan promo, ditampilkan ke konsumen)</span></label>
                                                                    <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($j['deskripsi'] ?? '') ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
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
                    </div>
                </div>

                <!-- Modal Tambah -->
                <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Jenis Promo</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Kode <span class="text-muted small">(dipilih di field Jenis saat isi produk)</span></label>
                                        <input type="text" name="nama_jenis" class="form-control" placeholder="Contoh: bundling_7" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Badge</label>
                                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Bundling 7" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Deskripsi <span class="text-muted small">(aturan promo, ditampilkan ke konsumen)</span></label>
                                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Contoh: Beli 7 produk dalam artikel yang sama, dapat harga bundling."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Wanoja</span>
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
</body>

</html>
