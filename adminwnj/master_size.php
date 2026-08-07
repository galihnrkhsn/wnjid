<?php
    session_start();
    include 'koneksi.php';

    $isReorder = $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'reorder';

    if (!isset($_SESSION["administrator"])) {
        if ($isReorder) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Sesi habis, silakan login ulang']);
            exit;
        }
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    // AJAX: simpan urutan baru hasil drag - hanya berlaku di dalam SATU kategori yang sama
    // (dikirim eksplisit dari JS supaya tidak bisa ke-drag lintas kategori dari sisi server juga).
    if ($isReorder) {
        header('Content-Type: application/json');
        $kategori = trim($_POST['kategori'] ?? '');
        $ids      = array_map('intval', $_POST['ids'] ?? []);

        if ($kategori === '' || empty($ids)) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            exit;
        }

        $koneksi->begin_transaction();
        try {
            $stmt = $koneksi->prepare("UPDATE master_size SET seq = ? WHERE id = ? AND kategori = ?");
            foreach ($ids as $i => $id) {
                $seq = $i + 1;
                $stmt->bind_param('iis', $seq, $id, $kategori);
                $stmt->execute();
            }
            $koneksi->commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $koneksi->rollback();
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan urutan']);
        }
        exit;
    }

    $pesan = '';
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
        $nama_size = trim($_POST['nama_size'] ?? '');
        $kategori  = trim($_POST['kategori'] ?? '');

        if ($nama_size === '' || $kategori === '') {
            $error = 'Nama size dan kategori wajib diisi';
        } else {
            // Otomatis ditaruh di urutan paling akhir kategori itu - urutan pastinya diatur lewat drag.
            $stmtMax = $koneksi->prepare("SELECT COALESCE(MAX(seq), 0) AS maxseq FROM master_size WHERE kategori = ?");
            $stmtMax->bind_param('s', $kategori);
            $stmtMax->execute();
            $seq = (int) $stmtMax->get_result()->fetch_assoc()['maxseq'] + 1;

            $stmt = $koneksi->prepare("INSERT INTO master_size (nama_size, kategori, seq) VALUES (?, ?, ?)");
            $stmt->bind_param('ssi', $nama_size, $kategori, $seq);
            if ($stmt->execute()) {
                $pesan = "Size \"$nama_size\" berhasil ditambahkan";
            } else {
                $error = $koneksi->errno === 1062 ? "Size \"$nama_size\" sudah ada" : 'Gagal menambahkan size';
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
        $id        = (int) $_POST['id'];
        $nama_size = trim($_POST['nama_size'] ?? '');
        $kategori  = trim($_POST['kategori'] ?? '');

        if ($nama_size === '' || $kategori === '') {
            $error = 'Nama size dan kategori wajib diisi';
        } else {
            $stmtLama = $koneksi->prepare("SELECT kategori FROM master_size WHERE id = ?");
            $stmtLama->bind_param('i', $id);
            $stmtLama->execute();
            $kategoriLama = $stmtLama->get_result()->fetch_assoc()['kategori'] ?? '';

            if ($kategoriLama !== $kategori) {
                // Pindah kategori - urutan lama sudah tidak relevan, taruh di akhir kategori baru.
                $stmtMax = $koneksi->prepare("SELECT COALESCE(MAX(seq), 0) AS maxseq FROM master_size WHERE kategori = ?");
                $stmtMax->bind_param('s', $kategori);
                $stmtMax->execute();
                $seq = (int) $stmtMax->get_result()->fetch_assoc()['maxseq'] + 1;

                $stmt = $koneksi->prepare("UPDATE master_size SET nama_size = ?, kategori = ?, seq = ? WHERE id = ?");
                $stmt->bind_param('ssii', $nama_size, $kategori, $seq, $id);
            } else {
                // Kategori tetap - urutan (seq) tidak disentuh, sudah diatur lewat drag.
                $stmt = $koneksi->prepare("UPDATE master_size SET nama_size = ? WHERE id = ?");
                $stmt->bind_param('si', $nama_size, $id);
            }

            if ($stmt->execute()) {
                $pesan = "Size \"$nama_size\" berhasil diubah";
            } else {
                $error = $koneksi->errno === 1062 ? "Nama \"$nama_size\" sudah dipakai size lain" : 'Gagal mengubah size';
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
        $id = (int) $_POST['id'];

        $stmtCek = $koneksi->prepare("SELECT COUNT(*) AS jumlah FROM variants WHERE size_id = ?");
        $stmtCek->bind_param('i', $id);
        $stmtCek->execute();
        $jumlahDipakai = (int) $stmtCek->get_result()->fetch_assoc()['jumlah'];

        if ($jumlahDipakai > 0) {
            $error = "Tidak bisa dihapus, size ini masih dipakai oleh $jumlahDipakai varian produk";
        } else {
            $stmt = $koneksi->prepare("DELETE FROM master_size WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $pesan = 'Size berhasil dihapus';
        }
    }

    $sizes = $koneksi->query("SELECT * FROM master_size ORDER BY kategori ASC, seq ASC")->fetch_all(MYSQLI_ASSOC);

    $sizesPerKategori = [];
    foreach ($sizes as $s) {
        $sizesPerKategori[$s['kategori']][] = $s;
    }
    $kategoriList = array_keys($sizesPerKategori);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Master Size | WNJ.ID</title>

    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/wnj-theme.css" rel="stylesheet">
    <style>
        .kategori-card-header {
            font-weight: 700;
            font-size: .85rem;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #6e707e;
            background: #f8f9fc;
        }
        .drag-handle {
            cursor: grab;
            color: #b7b9cc;
            padding: 0 .5rem;
        }
        .drag-handle:active {
            cursor: grabbing;
        }
        .size-row.sortable-ghost {
            opacity: .4;
            background: #eaecf4;
        }
        .size-row.sortable-chosen td {
            background: #f8f9fc;
        }
        .seq-badge {
            display: inline-block;
            min-width: 22px;
            text-align: center;
            background: #eaecf4;
            border-radius: 4px;
            padding: 1px 5px;
            font-weight: 600;
            font-size: .75rem;
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
                        <h1 class="h3 mb-0 text-gray-800">Master Size</h1>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah">
                            <i class="fa fa-plus"></i> Tambah Size
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
                        Drag baris pakai ikon <i class="fa fa-grip-vertical"></i> untuk mengubah urutan tampil.
                        Urutan cuma berlaku di dalam kategori yang sama.
                    </p>

                    <?php if (empty($sizes)): ?>
                        <div class="card shadow"><div class="card-body text-center text-muted py-4">Belum ada data size</div></div>
                    <?php endif; ?>

                    <?php foreach ($sizesPerKategori as $kategori => $items): ?>
                        <div class="card shadow mb-3">
                            <div class="card-header kategori-card-header"><?= htmlspecialchars($kategori) ?></div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <tbody class="sortable-kategori" data-kategori="<?= htmlspecialchars($kategori) ?>">
                                        <?php foreach ($items as $s): ?>
                                            <tr class="size-row" data-id="<?= (int) $s['id'] ?>">
                                                <td width="30" class="drag-handle"><i class="fa fa-grip-vertical"></i></td>
                                                <td width="40"><span class="seq-badge"><?= (int) $s['seq'] ?></span></td>
                                                <td><?= htmlspecialchars($s['nama_size']) ?></td>
                                                <td width="140" class="text-right">
                                                    <form method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus size &quot;<?= htmlspecialchars($s['nama_size'], ENT_QUOTES) ?>&quot;?');">
                                                        <input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
                                                        <button type="submit" name="hapus" class="btn btn-sm btn-outline-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Modal Tambah -->
                <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Size</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Size</label>
                                        <input type="text" name="nama_size" class="form-control" placeholder="Contoh: XXL" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <input type="text" name="kategori" class="form-control" list="kategoriList" placeholder="Contoh: Dewasa" required>
                                        <small class="text-muted">Boleh pilih kategori yang sudah ada atau ketik kategori baru. Size baru otomatis ditaruh di urutan paling akhir, atur urutan pastinya lewat drag.</small>
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

                <datalist id="kategoriList">
                    <?php foreach ($kategoriList as $k): ?>
                        <option value="<?= htmlspecialchars($k) ?>">
                    <?php endforeach; ?>
                </datalist>
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
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        document.querySelectorAll('.sortable-kategori').forEach(function (tbody) {
            Sortable.create(tbody, {
                handle: '.drag-handle',
                animation: 150,
                // Tidak diberi "group" -> Sortable.js otomatis tidak izinkan drag pindah
                // antar tbody (antar kategori), cuma bisa reorder di dalam tbody yang sama.
                onEnd: function () {
                    var kategori = tbody.dataset.kategori;
                    var ids = Array.from(tbody.querySelectorAll('.size-row')).map(function (row) {
                        return row.dataset.id;
                    });

                    tbody.querySelectorAll('.seq-badge').forEach(function (badge, i) {
                        badge.textContent = i + 1;
                    });

                    $.post('master_size.php', { action: 'reorder', kategori: kategori, ids: ids }, function (res) {
                        if (!res.success) {
                            alert(res.message || 'Gagal menyimpan urutan');
                            window.location.reload();
                        }
                    }, 'json').fail(function () {
                        alert('Gagal menyimpan urutan, silakan coba lagi');
                        window.location.reload();
                    });
                }
            });
        });
    </script>
</body>

</html>
