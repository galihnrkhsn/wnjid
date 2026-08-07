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

    // ---- Simpan konten "Tentang Kami" ----
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_konten'])) {
        $kunci  = trim($_POST['kunci'] ?? '');
        $konten = trim($_POST['konten'] ?? '');

        $stmt = $koneksi->prepare("UPDATE konten_web SET konten = ? WHERE kunci = ?");
        $stmt->bind_param('ss', $konten, $kunci);
        if ($stmt->execute()) {
            $pesan = 'Konten berhasil disimpan';
        } else {
            $error = 'Gagal menyimpan konten';
        }
    }

    // ---- Tambah FAQ ----
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_faq'])) {
        $pertanyaan = trim($_POST['pertanyaan'] ?? '');
        $jawaban    = trim($_POST['jawaban'] ?? '');

        if ($pertanyaan === '' || $jawaban === '') {
            $error = 'Pertanyaan dan jawaban wajib diisi';
        } else {
            $stmtMax = $koneksi->query("SELECT COALESCE(MAX(urutan), 0) AS maxurutan FROM faq");
            $urutan  = (int) $stmtMax->fetch_assoc()['maxurutan'] + 1;

            $stmt = $koneksi->prepare("INSERT INTO faq (pertanyaan, jawaban, urutan, status) VALUES (?, ?, ?, 1)");
            $stmt->bind_param('ssi', $pertanyaan, $jawaban, $urutan);
            if ($stmt->execute()) {
                $pesan = 'FAQ berhasil ditambahkan';
            } else {
                $error = 'Gagal menambahkan FAQ';
            }
        }
    }

    // ---- Edit FAQ ----
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_faq'])) {
        $id         = (int) $_POST['id'];
        $pertanyaan = trim($_POST['pertanyaan'] ?? '');
        $jawaban    = trim($_POST['jawaban'] ?? '');
        $urutan     = (int) ($_POST['urutan'] ?? 0);

        if ($pertanyaan === '' || $jawaban === '') {
            $error = 'Pertanyaan dan jawaban wajib diisi';
        } else {
            $stmt = $koneksi->prepare("UPDATE faq SET pertanyaan = ?, jawaban = ?, urutan = ? WHERE id = ?");
            $stmt->bind_param('ssii', $pertanyaan, $jawaban, $urutan, $id);
            if ($stmt->execute()) {
                $pesan = 'FAQ berhasil diubah';
            } else {
                $error = 'Gagal mengubah FAQ';
            }
        }
    }

    // ---- Toggle tampil/sembunyikan ----
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
        $id = (int) $_POST['id'];
        $koneksi->query("UPDATE faq SET status = IF(status = 1, 0, 1) WHERE id = " . $id);
        $pesan = 'Status FAQ berhasil diubah';
    }

    // ---- Hapus FAQ ----
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_faq'])) {
        $id = (int) $_POST['id'];
        $stmt = $koneksi->prepare("DELETE FROM faq WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $pesan = 'FAQ berhasil dihapus';
    }

    $tentangKami = $koneksi->query("SELECT * FROM konten_web WHERE kunci = 'tentang_kami'")->fetch_assoc();
    $faqList     = $koneksi->query("SELECT * FROM faq ORDER BY urutan ASC, id ASC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kelola Bantuan | WNJ.ID</title>

    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/wnj-theme.css" rel="stylesheet">
    <style>
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
                    <h1 class="h3 mb-4 text-gray-800">Kelola Bantuan (Halaman Bantuan Konsumen)</h1>

                    <?php if ($pesan): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($pesan) ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tentang Kami</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="kunci" value="tentang_kami">
                                <div class="form-group">
                                    <textarea name="konten" class="form-control" rows="8" placeholder="Tulis deskripsi perusahaan, alamat, kontak, dsb. Teks ini akan tampil apa adanya di halaman Bantuan konsumen."><?= htmlspecialchars($tentangKami['konten'] ?? '') ?></textarea>
                                </div>
                                <button type="submit" name="simpan_konten" class="btn btn-primary btn-sm">
                                    <i class="fa fa-save"></i> Simpan
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">FAQ (Pertanyaan &amp; Jawaban)</h6>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahFaq">
                                <i class="fa fa-plus"></i> Tambah FAQ
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="60">Urutan</th>
                                        <th>Pertanyaan</th>
                                        <th>Status</th>
                                        <th width="140" class="text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($faqList)): ?>
                                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada FAQ</td></tr>
                                    <?php endif; ?>
                                    <?php foreach ($faqList as $f): ?>
                                        <tr>
                                            <td><?= (int) $f['urutan'] ?></td>
                                            <td><?= htmlspecialchars($f['pertanyaan']) ?></td>
                                            <td>
                                                <?php if ($f['status'] == 1): ?>
                                                    <span class="status-badge status-aktif">Tampil</span>
                                                <?php else: ?>
                                                    <span class="status-badge status-nonaktif">Disembunyikan</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-right">
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalEditFaq<?= (int) $f['id'] ?>">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?= (int) $f['id'] ?>">
                                                    <button type="submit" name="toggle_status" class="btn btn-sm <?= $f['status'] == 1 ? 'btn-outline-secondary' : 'btn-outline-success' ?>" title="<?= $f['status'] == 1 ? 'Sembunyikan' : 'Tampilkan' ?>">
                                                        <i class="fa <?= $f['status'] == 1 ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('Hapus FAQ ini?');">
                                                    <input type="hidden" name="id" value="<?= (int) $f['id'] ?>">
                                                    <button type="submit" name="hapus_faq" class="btn btn-sm btn-outline-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Modal Edit FAQ -->
                                        <div class="modal fade" id="modalEditFaq<?= (int) $f['id'] ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form method="POST">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Ubah FAQ</h5>
                                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="<?= (int) $f['id'] ?>">
                                                            <div class="form-group">
                                                                <label>Pertanyaan</label>
                                                                <input type="text" name="pertanyaan" class="form-control" value="<?= htmlspecialchars($f['pertanyaan']) ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Jawaban</label>
                                                                <textarea name="jawaban" class="form-control" rows="4" required><?= htmlspecialchars($f['jawaban']) ?></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Urutan</label>
                                                                <input type="number" name="urutan" class="form-control" value="<?= (int) $f['urutan'] ?>" min="0">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" name="edit_faq" class="btn btn-primary">Simpan</button>
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

                <!-- Modal Tambah FAQ -->
                <div class="modal fade" id="modalTambahFaq" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah FAQ</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Pertanyaan</label>
                                        <input type="text" name="pertanyaan" class="form-control" placeholder="Contoh: Bagaimana cara membayar pesanan?" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Jawaban</label>
                                        <textarea name="jawaban" class="form-control" rows="4" required></textarea>
                                    </div>
                                    <small class="text-muted">FAQ baru otomatis ditaruh di urutan paling akhir, urutan bisa diubah lewat tombol Edit.</small>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" name="tambah_faq" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
</body>

</html>
