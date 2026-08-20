<?php
    session_start();
    include 'koneksi.php';

    if (!isset($_SESSION["administrator"])) {
        echo "<script>alert('Anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        exit();
    }

    // Copy dari PO lain: form ini di-prefill dengan data PO sumber, tapi belum tersimpan
    // apa-apa sampai admin klik "Simpan Semua Data" - jadi bisa diedit/ditambah/dihapus
    // dulu sebelum jadi PO baru (bukan langsung duplikasi di database).
    $copyFrom   = null;
    $prefillPo  = null;
    $prefillKategori = [];
    $prefillVariant  = [];

    if (isset($_GET['copy_from']) && (int) $_GET['copy_from'] > 0) {
        $copyFromId = (int) $_GET['copy_from'];

        $stmtCopyPo = $koneksi->prepare("SELECT namapo, status, jenis, diskon, tglselesai, tipe, pembayaran FROM poproduk WHERE idpoproduk = ?");
        $stmtCopyPo->bind_param('i', $copyFromId);
        $stmtCopyPo->execute();
        $prefillPo = $stmtCopyPo->get_result()->fetch_assoc();
        $stmtCopyPo->close();

        if ($prefillPo) {
            $copyFrom = $prefillPo['namapo'];

            $stmtCopyKategori = $koneksi->prepare("SELECT namakategori, stok FROM pokategori WHERE idpoproduk = ? ORDER BY idpo ASC");
            $stmtCopyKategori->bind_param('i', $copyFromId);
            $stmtCopyKategori->execute();
            $prefillKategori = $stmtCopyKategori->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmtCopyKategori->close();

            $stmtCopyVariant = $koneksi->prepare("SELECT k.namakategori, d.variant, d.harga, d.berat
                FROM podetail d
                INNER JOIN pokategori k ON k.idpo = d.idpo
                WHERE k.idpoproduk = ?
                ORDER BY d.idpodetail ASC");
            $stmtCopyVariant->bind_param('i', $copyFromId);
            $stmtCopyVariant->execute();
            $prefillVariant = $stmtCopyVariant->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmtCopyVariant->close();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Tambah PO | Admin</title>
        <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet">
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
        <style>
            .row-hapus {
                white-space: nowrap;
            }
            #ringkasanPo {
                position: sticky;
                bottom: 0;
                z-index: 10;
                background: #fff;
                border-top: 1px solid #e3e6f0;
                box-shadow: 0 -2px 6px rgba(0,0,0,.06);
            }
            .kategori-row td, .variant-row td {
                vertical-align: middle;
            }
        </style>
    </head>

<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div class="container-fluid pb-5">
            <h1 class="h3 mb-4 text-gray-800">Tambah PO</h1>

            <?php if ($copyFrom): ?>
            <div class="alert alert-info">
                <i class="fas fa-copy"></i> Form ini sudah diisi otomatis dari PO <strong><?= htmlspecialchars($copyFrom) ?></strong>.
                Boleh diubah bebas (nama, kategori, variant, dll) - belum tersimpan sampai klik "Simpan Semua Data" di bawah.
            </div>
            <?php endif; ?>

            <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_all'])) {
                    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

                    $namapo     = trim($_POST['namapo'] ?? '');
                    $status     = $_POST['status'] ?? '';
                    $jenis      = $_POST['jenis'] ?? '';
                    $tipe       = $_POST['tipe'] ?? '';
                    $pembayaran = $_POST['pembayaran'] ?? '';
                    $diskon     = (int) ($_POST['diskon'] ?? 0);
                    $tglselesai = date("M d, Y", strtotime($_POST['tglselesai'])) . ' 23:59:00';

                    $kategoriNama = array_map('trim', $_POST['kategori'] ?? []);
                    $kategoriStok = $_POST['stok'] ?? [];

                    $variantNama     = array_map('trim', $_POST['variant'] ?? []);
                    $variantHarga    = $_POST['harga'] ?? [];
                    $variantBerat    = $_POST['berat'] ?? [];
                    $variantKategori = $_POST['variant_kategori'] ?? [];

                    try {
                        if ($namapo === '' || empty($kategoriNama) || empty($variantNama)) {
                            throw new Exception('Nama PO, minimal 1 kategori, dan minimal 1 variant wajib diisi');
                        }

                        // Validasi semua data dulu SEBELUM nulis apapun ke DB. Tabel poproduk/
                        // pokategori/podetail pakai engine MyISAM (tidak transactional, begin_transaction()
                        // jadi no-op) - jadi kalau baru ketahuan error di tengah proses insert, baris yang
                        // sudah kepalang masuk tidak bisa di-rollback. Makanya validasi referensi
                        // kategori dilakukan terhadap data POST-nya langsung, bukan setelah insert.
                        $namaKategoriValid = [];
                        foreach ($kategoriNama as $nama) {
                            if ($nama === '') {
                                continue;
                            }
                            if (isset($namaKategoriValid[$nama])) {
                                throw new Exception("Nama kategori \"$nama\" dipakai lebih dari sekali");
                            }
                            $namaKategoriValid[$nama] = true;
                        }

                        foreach ($variantNama as $i => $variant) {
                            if ($variant === '') {
                                continue;
                            }
                            $kategori = $variantKategori[$i] ?? '';
                            if (!isset($namaKategoriValid[$kategori])) {
                                throw new Exception("Variant \"$variant\" pakai kategori \"$kategori\" yang tidak ditemukan");
                            }
                        }

                        $stmtPo = $koneksi->prepare("INSERT INTO poproduk
                                (namapo, status, tglselesai, jenis, diskon, tipe, pembayaran, updated_at, created_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
                        $stmtPo->bind_param('ssssiss', $namapo, $status, $tglselesai, $jenis, $diskon, $tipe, $pembayaran);
                        $stmtPo->execute();
                        $idpoproduk = $koneksi->insert_id;
                        $stmtPo->close();

                        $idpoByKategori = [];
                        $stmtKategori = $koneksi->prepare("INSERT INTO pokategori (idpoproduk, namakategori, stok) VALUES (?, ?, ?)");
                        foreach ($kategoriNama as $i => $nama) {
                            if ($nama === '') {
                                continue;
                            }
                            $stok = (int) ($kategoriStok[$i] ?? 0);
                            $stmtKategori->bind_param('isi', $idpoproduk, $nama, $stok);
                            $stmtKategori->execute();
                            $idpoByKategori[$nama] = $koneksi->insert_id;
                        }
                        $stmtKategori->close();

                        $stmtVariant = $koneksi->prepare("INSERT INTO podetail (idpo, variant, harga, berat) VALUES (?, ?, ?, ?)");
                        foreach ($variantNama as $i => $variant) {
                            if ($variant === '') {
                                continue;
                            }
                            $idpo  = $idpoByKategori[$variantKategori[$i]];
                            $harga = (int) ($variantHarga[$i] ?? 0);
                            $berat = (int) ($variantBerat[$i] ?? 0);
                            $stmtVariant->bind_param('isii', $idpo, $variant, $harga, $berat);
                            $stmtVariant->execute();
                        }
                        $stmtVariant->close();

                        echo "<script>alert('Data berhasil disimpan'); location='produkpo.php';</script>";
                        exit();
                    } catch (Exception $e) {
                        echo "<div class='alert alert-danger'>Gagal menyimpan: " . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                }
            ?>
            <?php
                // Default field: prioritaskan input POST (kalau baru gagal validasi & form
                // di-render ulang), lalu data copy (kalau datang dari "Copy PO"), baru default kosong.
                $defNamapo     = $_POST['namapo'] ?? ($prefillPo ? $prefillPo['namapo'] . ' (Copy)' : '');
                $defStatus     = $_POST['status'] ?? ($prefillPo['status'] ?? 'open');
                $defJenis      = $_POST['jenis'] ?? ($prefillPo['jenis'] ?? 'normal');
                $defTipe       = $_POST['tipe'] ?? ($prefillPo['tipe'] ?? 'normal');
                $defPembayaran = $_POST['pembayaran'] ?? ($prefillPo['pembayaran'] ?? 'DP');
                $defDiskon     = $_POST['diskon'] ?? ($prefillPo['diskon'] ?? 0);
                $defTglselesai = date('Y-m-t');
            ?>
<form method="POST" id="formPo">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi PO</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="namapo">Nama PO</label>
                    <input class="form-control" name="namapo" id="namapo" value="<?= htmlspecialchars($defNamapo) ?>" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" name="status" id="status">
                        <option value="open" <?= $defStatus === 'open' ? 'selected' : '' ?>>Open</option>
                        <option value="close" <?= $defStatus === 'close' ? 'selected' : '' ?>>Close</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="jenis">Jenis PO</label>
                    <select class="form-control" name="jenis" id="jenis">
                        <option value="normal" <?= strtolower($defJenis) === 'normal' ? 'selected' : '' ?>>Normal</option>
                        <option value="custom" <?= strtolower($defJenis) === 'custom' ? 'selected' : '' ?>>Custom</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="tipe">Tipe PO</label>
                    <select class="form-control" name="tipe" id="tipe">
                        <option value="normal" <?= strtolower($defTipe) === 'normal' ? 'selected' : '' ?>>Normal</option>
                        <option value="hide" <?= strtolower($defTipe) === 'hide' ? 'selected' : '' ?>>Hide</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="pembayaran">Pembayaran</label>
                    <select class="form-control" name="pembayaran" id="pembayaran">
                        <option value="DP" <?= $defPembayaran === 'DP' ? 'selected' : '' ?>>DP</option>
                        <option value="Lunas" <?= $defPembayaran === 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="diskon">Diskon Tambahan (%)</label>
                    <input type="number" class="form-control" name="diskon" id="diskon" value="<?= (int) $defDiskon ?>" min="0" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="tglselesai">Tanggal Selesai</label>
                    <input type="date" class="form-control" name="tglselesai" id="tglselesai" value="<?= htmlspecialchars($defTglselesai) ?>" required>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Kategori</h6>
            <button type="button" class="btn btn-sm btn-primary" id="btnTambahKategori"><i class="fas fa-plus"></i> Tambah Kategori</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0" id="tabelKategori">
                    <thead>
                        <tr>
                            <th style="width: 50%">Nama Kategori</th>
                            <th style="width: 30%">Stok</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="kategoriBody"></tbody>
                </table>
            </div>
            <small class="text-muted">Nama kategori otomatis muncul sebagai pilihan di tabel Variant di bawah.</small>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Variant</h6>
            <button type="button" class="btn btn-sm btn-success" id="btnTambahVariant"><i class="fas fa-plus"></i> Tambah Variant</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0" id="tabelVariant">
                    <thead>
                        <tr>
                            <th style="width: 20%">Kategori</th>
                            <th style="width: 30%">Variant <span class="text-muted small">(contoh: Dress Sz S)</span></th>
                            <th style="width: 15%">Harga</th>
                            <th style="width: 15%">Berat</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="variantBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="ringkasanPo" class="py-3 px-1 d-flex justify-content-between align-items-center">
        <div class="small text-muted">
            <span id="ringkasanKategori">0 kategori</span> &middot;
            <span id="ringkasanVariant">0 variant</span> &middot;
            Total stok: <span id="ringkasanStok">0</span>
        </div>
        <button type="submit" name="save_all" class="btn btn-lg btn-primary">Simpan Semua Data</button>
    </div>
</form>

        </div>
    </div>
    <script>
        const PREFILL_KATEGORI = <?= json_encode($prefillKategori) ?>;
        const PREFILL_VARIANT  = <?= json_encode($prefillVariant) ?>;

        function kategoriRow(data) {
            const row = document.createElement('tr');
            row.className = 'kategori-row';
            row.innerHTML = `
                <td><input class="form-control form-control-sm nama-kategori" name="kategori[]" required></td>
                <td><input type="number" class="form-control form-control-sm stok-kategori" name="stok[]" value="0" min="0" required></td>
                <td class="row-hapus"><button type="button" class="btn btn-sm btn-danger btn-hapus-kategori"><i class="fas fa-trash"></i></button></td>
            `;
            if (data) {
                row.querySelector('.nama-kategori').value = data.namakategori;
                row.querySelector('.stok-kategori').value = data.stok;
            }
            return row;
        }

        function variantRow(data, kategoriDefault) {
            const row = document.createElement('tr');
            row.className = 'variant-row';
            row.innerHTML = `
                <td><select class="form-control form-control-sm select-kategori-variant" name="variant_kategori[]" required></select></td>
                <td><input class="form-control form-control-sm" name="variant[]" required></td>
                <td><input type="number" class="form-control form-control-sm harga-variant" name="harga[]" min="0" required></td>
                <td><input type="number" class="form-control form-control-sm" name="berat[]" min="0" required></td>
                <td class="row-hapus"><button type="button" class="btn btn-sm btn-danger btn-hapus-variant"><i class="fas fa-trash"></i></button></td>
            `;
            if (data) {
                row.querySelector('[name="variant[]"]').value = data.variant;
                row.querySelector('.harga-variant').value = data.harga;
                row.querySelector('[name="berat[]"]').value = data.berat;
            }
            syncSelectKategori(row.querySelector('.select-kategori-variant'), data ? data.namakategori : kategoriDefault);
            return row;
        }

        function daftarKategoriSaatIni() {
            return Array.from(document.querySelectorAll('.nama-kategori'))
                .map(el => el.value.trim())
                .filter(v => v !== '');
        }

        function syncSelectKategori(select, defaultValue) {
            const daftar = daftarKategoriSaatIni();
            const sebelumnya = defaultValue ?? select.value;
            select.innerHTML = daftar.map(nama => `<option value="${nama}">${nama}</option>`).join('');
            if (daftar.includes(sebelumnya)) {
                select.value = sebelumnya;
            }
        }

        function syncSemuaSelectKategori() {
            document.querySelectorAll('.select-kategori-variant').forEach(select => syncSelectKategori(select));
            updateRingkasan();
        }

        function kategoriTerakhirDipakai() {
            const selects = document.querySelectorAll('.select-kategori-variant');
            if (selects.length === 0) return null;
            return selects[selects.length - 1].value || null;
        }

        function updateRingkasan() {
            const jumlahKategori = document.querySelectorAll('.kategori-row').length;
            const jumlahVariant  = document.querySelectorAll('.variant-row').length;
            const totalStok      = Array.from(document.querySelectorAll('.stok-kategori'))
                .reduce((sum, el) => sum + (parseInt(el.value) || 0), 0);

            document.getElementById('ringkasanKategori').textContent = `${jumlahKategori} kategori`;
            document.getElementById('ringkasanVariant').textContent  = `${jumlahVariant} variant`;
            document.getElementById('ringkasanStok').textContent     = totalStok;
        }

        document.getElementById('btnTambahKategori').addEventListener('click', function () {
            document.getElementById('kategoriBody').appendChild(kategoriRow());
            updateRingkasan();
        });

        document.getElementById('btnTambahVariant').addEventListener('click', function () {
            if (daftarKategoriSaatIni().length === 0) {
                alert('Isi minimal 1 kategori dulu sebelum menambah variant');
                return;
            }
            document.getElementById('variantBody').appendChild(variantRow(null, kategoriTerakhirDipakai()));
            updateRingkasan();
        });

        document.getElementById('kategoriBody').addEventListener('click', function (e) {
            if (!e.target.closest('.btn-hapus-kategori')) return;
            if (document.querySelectorAll('.kategori-row').length <= 1) {
                alert('Minimal harus ada 1 kategori');
                return;
            }
            e.target.closest('tr').remove();
            syncSemuaSelectKategori();
        });

        document.getElementById('variantBody').addEventListener('click', function (e) {
            if (!e.target.closest('.btn-hapus-variant')) return;
            if (document.querySelectorAll('.variant-row').length <= 1) {
                alert('Minimal harus ada 1 variant');
                return;
            }
            e.target.closest('tr').remove();
            updateRingkasan();
        });

        // Nama kategori berubah -> sinkronkan opsi dropdown di semua baris variant (tanpa
        // menghapus baris variant yang sudah diisi, beda dari perilaku "Generate" yang lama).
        document.getElementById('kategoriBody').addEventListener('input', function (e) {
            if (e.target.classList.contains('nama-kategori')) {
                syncSemuaSelectKategori();
            }
            if (e.target.classList.contains('stok-kategori')) {
                updateRingkasan();
            }
        });

        document.getElementById('formPo').addEventListener('submit', function (e) {
            const namaKategori = daftarKategoriSaatIni();
            const duplikat = namaKategori.filter((v, i) => namaKategori.indexOf(v) !== i);
            if (duplikat.length > 0) {
                e.preventDefault();
                alert('Ada nama kategori yang sama persis: ' + [...new Set(duplikat)].join(', ') + '. Ganti dulu supaya tidak ambigu.');
            }
        });

        // Kalau ada data copy dari PO lain, isi baris dari situ. Kalau tidak, mulai dengan
        // 1 baris kosong masing-masing supaya form langsung siap diisi manual.
        if (PREFILL_KATEGORI.length > 0) {
            PREFILL_KATEGORI.forEach(function (k) {
                document.getElementById('kategoriBody').appendChild(kategoriRow(k));
            });
        } else {
            document.getElementById('kategoriBody').appendChild(kategoriRow());
        }

        if (PREFILL_VARIANT.length > 0) {
            PREFILL_VARIANT.forEach(function (v) {
                document.getElementById('variantBody').appendChild(variantRow(v));
            });
        } else {
            document.getElementById('variantBody').appendChild(variantRow());
        }

        updateRingkasan();
    </script>
</body>
</html>
