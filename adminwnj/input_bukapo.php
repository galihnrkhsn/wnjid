<?php
    session_start();
    include 'koneksi.php';
    if (!isset($_SESSION["administrator"])) {
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    // ---- Termin: seq & pelunasan dihitung otomatis dari urutan baris DP, tidak diinput manual.
    // Baris terakhir = pelunasan, urutan (seq) = posisi baris (1,2,3,...).
    function simpanTermin(mysqli $koneksi, int $idpoproduk, array $dpList): void
    {
        $jumlah = count($dpList);
        $stmt   = $koneksi->prepare("INSERT INTO termin_po (dp, idpoproduk, is_pelunasan, seq) VALUES (?, ?, ?, ?)");
        foreach ($dpList as $i => $dpp) {
            $dpp         = (int) $dpp;
            $seq         = $i + 1;
            $isPelunasan = ($i === $jumlah - 1) ? 1 : 0;
            $stmt->bind_param('iiii', $dpp, $idpoproduk, $isPelunasan, $seq);
            $stmt->execute();
        }
        $stmt->close();
    }

    $errorStandar = null;
    $errorCustom  = null;

    if (isset($_POST["save"])) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $idpoproduk   = (int) $_POST["idpoproduk"];
            $tgl          = $_POST["tgl"];
            $tgl_acc_db   = $_POST["tgl_acc_db"];
            $tgl_ubah     = $_POST["tgl_ubah"];
            $tgl_bayar    = $_POST["tgl_bayar"];
            $tgl_dropship = $_POST["tgl_dropship"];
            $jenis_mitra  = $_POST["jenis_mitra"];
            $jenis_po     = $_POST["jenis_po"];
            $karakter     = (int) ($_POST["karakter"] ?? 0);
            $huruf        = $_POST["huruf"] ?? 'Tidak';
            $dpList       = array_values(array_filter($_POST['dp'] ?? [], fn($v) => $v !== ''));

            if ($idpoproduk <= 0) {
                throw new Exception('PO Produk wajib dipilih');
            }
            if (empty($dpList)) {
                throw new Exception('Minimal 1 termin DP wajib diisi');
            }

            $koneksi->begin_transaction();

            $custom = $karakter . '|' . $huruf;
            $stmt = $koneksi->prepare("INSERT INTO bukapo
                    (idpoproduk, tgl, tgl_acc_db, tgl_ubah, tgl_bayar, tgl_dropship, jenis_mitra, jenis_po, status, custom)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'UNPUBLISH', ?)");
            $stmt->bind_param('issssssss', $idpoproduk, $tgl, $tgl_acc_db, $tgl_ubah, $tgl_bayar, $tgl_dropship, $jenis_mitra, $jenis_po, $custom);
            $stmt->execute();
            $stmt->close();

            simpanTermin($koneksi, $idpoproduk, $dpList);

            $koneksi->commit();
            echo "<script>alert('Data berhasil ditambah'); location='data_bukapo.php';</script>";
            exit();
        } catch (Exception $e) {
            $koneksi->rollback();
            $errorStandar = $e->getMessage();
        }
    }

    if (isset($_POST["save_custom"])) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $idpoproduk   = (int) $_POST["idpoproduk_custom"];
            $tgl          = $_POST["tgl"];
            $tgl_acc_db   = $_POST["tgl_acc_db"];
            $tgl_ubah     = $_POST["tgl_ubah"];
            $tgl_bayar    = $_POST["tgl_bayar"];
            $tgl_dropship = $_POST["tgl_dropship"];
            $jenis_mitra  = $_POST["jenis_mitra"];
            $jenis_po     = $_POST["jenis_po"];
            $jmlh_tab     = $_POST["jmlh_tab"] ?? '';
            $nama_tab     = $_POST["nama_tab"] ?? [];
            $id_awal      = $_POST["id_awal"] ?? [];
            $id_akhir     = $_POST["id_akhir"] ?? [];
            $dpList       = array_values(array_filter($_POST['dp'] ?? [], fn($v) => $v !== ''));

            $resultExplode = explode('|', $jmlh_tab);
            $angka         = (int) ($resultExplode[1] ?? 0);

            if ($idpoproduk <= 0) {
                throw new Exception('PO Produk wajib dipilih');
            }
            if ($angka <= 0 || count($nama_tab) < $angka) {
                throw new Exception('Jumlah Tab & data tab wajib diisi lengkap');
            }
            if (empty($dpList)) {
                throw new Exception('Minimal 1 termin DP wajib diisi');
            }

            $koneksi->begin_transaction();

            $stmt = $koneksi->prepare("INSERT INTO bukapo
                    (idpoproduk, tgl, tgl_acc_db, tgl_ubah, tgl_bayar, tgl_dropship, jenis_mitra, jenis_po, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'UNPUBLISH')");
            $stmt->bind_param('isssssss', $idpoproduk, $tgl, $tgl_acc_db, $tgl_ubah, $tgl_bayar, $tgl_dropship, $jenis_mitra, $jenis_po);
            $stmt->execute();
            $stmt->close();

            $stmtTab = $koneksi->prepare("INSERT INTO bukapo_tab (idpoproduk, nama_tab, id_awal, id_akhir, jmlh_tab) VALUES (?, ?, ?, ?, ?)");
            for ($x = 0; $x < $angka; $x++) {
                $idAwal  = (int) $id_awal[$x];
                $idAkhir = (int) $id_akhir[$x];
                $stmtTab->bind_param('isiii', $idpoproduk, $nama_tab[$x], $idAwal, $idAkhir, $angka);
                $stmtTab->execute();
            }
            $stmtTab->close();

            simpanTermin($koneksi, $idpoproduk, $dpList);

            $koneksi->commit();
            echo "<script>alert('Data berhasil ditambah'); location='data_bukapo.php';</script>";
            exit();
        } catch (Exception $e) {
            $koneksi->rollback();
            $errorCustom = $e->getMessage();
        }
    }

    $today = date('Y-m-d');
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
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .termin-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }
        .termin-item .termin-label {
            width: 90px;
            font-weight: 600;
        }
        .termin-item .dp-input {
            width: 120px;
        }
        .termin-item .termin-badge {
            width: 110px;
        }
        #totalDpBar, #totalDpBarCustom {
            font-weight: 600;
        }
        #totalDpBar.text-danger, #totalDpBarCustom.text-danger {
            color: #e74a3b !important;
        }
        #totalDpBar.text-success, #totalDpBarCustom.text-success {
            color: #1cc88a !important;
        }
    </style>
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h3 class="mb-4"><strong>Input Data Buka PO</strong></h3>

            <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="nav-db-tab" data-toggle="tab" href="#nav-db" role="tab">PO Standar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="nav-agen-tab" data-toggle="tab" href="#nav-agen" role="tab">PO Custom Tab</a>
                </li>
            </ul>

            <div class="tab-content border border-top-0 p-4 mb-4" id="nav-tabContent">
                <!-- ==================== PO STANDAR ==================== -->
                <div class="tab-pane fade show active" id="nav-db" role="tabpanel">
                    <?php if ($errorStandar): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($errorStandar) ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="card shadow mb-3">
                            <div class="card-header py-2"><strong>PO & Jenis</strong></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="idpoproduk">PO Produk</label>
                                        <select class="form-control" id="idpoproduk" name="idpoproduk" required>
                                            <option disabled value="" selected>~Pilih PO Produk~</option>
                                            <?php
                                                $ambil = $koneksi->query("SELECT idpoproduk, namapo FROM poproduk ORDER BY idpoproduk DESC");
                                                while ($row = $ambil->fetch_assoc()) {
                                            ?>
                                                <option value="<?= $row['idpoproduk'] ?>"><?= $row['idpoproduk'] ?> | <?= htmlspecialchars($row['namapo']) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="jenis_mitra">Jenis Mitra</label>
                                        <select class="form-control" id="jenis_mitra" name="jenis_mitra" required>
                                            <option disabled value="" selected>~Pilih Jenis Mitra~</option>
                                            <option value="Semua Mitra">Semua Mitra</option>
                                            <option value="Distributor">Distributor</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="jenis_po">Jenis PO</label>
                                        <select class="form-control" id="jenis_po" name="jenis_po" required>
                                            <option disabled value="" selected>~Pilih Jenis PO~</option>
                                            <option value="PO Mandiri">PO Mandiri</option>
                                            <option value="PO Bundling 2">PO Bundling 2</option>
                                            <option value="PO Bundling 5">PO Bundling 5</option>
                                            <option value="PO Tab tanpa stok">PO Tab tanpa Stok</option>
                                            <option value="PO dengan Stok">PO dengan Stok</option>
                                            <option value="PO tanpa Stok">PO tanpa Stok</option>
                                            <option value="PO Miki Custom">PO Miki Custom</option>
                                            <option value="PO Miki Polos">PO Miki Polos</option>
                                            <option value="PO Brooch Custom">PO Brooch Custom</option>
                                            <option value="PO Bagi Rata">PO Bagi Rata</option>
                                            <option value="PO Kolibri">PO Kolibri</option>
                                            <option value="PO Hampers">PO Hampers</option>
                                            <option value="PO Karakter Stok">PO Karakter Stok</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label>Jumlah Karakter</label>
                                        <input type="number" name="karakter" class="form-control" value="0" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="huruf">Huruf Kapital</label>
                                        <select class="form-control" id="huruf" name="huruf" required>
                                            <option value="Tidak">Tidak</option>
                                            <option value="Ya">Ya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-3">
                            <div class="card-header py-2"><strong>Tanggal</strong></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label>Tutup PO</label>
                                        <input type="date" name="tgl" class="form-control" value="<?= $today ?>" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Acc DB</label>
                                        <input type="date" name="tgl_acc_db" class="form-control" value="<?= $today ?>" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label>Ubah PO</label>
                                        <input type="date" name="tgl_ubah" class="form-control" value="<?= $today ?>" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label>Bayar PO</label>
                                        <input type="date" name="tgl_bayar" class="form-control" value="<?= $today ?>" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label>Dropship</label>
                                        <input type="date" name="tgl_dropship" class="form-control" value="<?= $today ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-3">
                            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                                <strong>Termin Pembayaran</strong>
                                <span id="totalDpBar"></span>
                            </div>
                            <div class="card-body">
                                <small class="text-muted d-block mb-2">Urutan &amp; status pelunasan otomatis - baris terakhir otomatis jadi Pelunasan, tidak perlu diatur manual.</small>
                                <div id="terminContainer"></div>
                                <button type="button" class="btn btn-sm btn-success" onclick="tambahTermin('terminContainer', 'totalDpBar')"><i class="fas fa-plus"></i> Tambah Termin</button>
                            </div>
                        </div>

                        <button class="btn btn-primary" name="save">Tambah Data</button>
                    </form>
                </div>

                <!-- ==================== PO CUSTOM TAB ==================== -->
                <div class="tab-pane fade" id="nav-agen" role="tabpanel">
                    <?php if ($errorCustom): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($errorCustom) ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="card shadow mb-3">
                            <div class="card-header py-2"><strong>PO & Jenis</strong></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="idpoproduk_custom">PO Produk</label>
                                        <select class="form-control" id="idpoproduk_custom" name="idpoproduk_custom" required>
                                            <option disabled value="" selected>~Pilih PO Produk~</option>
                                            <?php
                                                $ambil = $koneksi->query("SELECT idpoproduk, namapo FROM poproduk ORDER BY idpoproduk DESC");
                                                while ($row = $ambil->fetch_assoc()) {
                                            ?>
                                                <option value="<?= $row['idpoproduk'] ?>"><?= $row['idpoproduk'] ?> | <?= htmlspecialchars($row['namapo']) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="jenis_mitra">Jenis Mitra</label>
                                        <select class="form-control" id="jenis_mitra" name="jenis_mitra" required>
                                            <option disabled value="" selected>~Pilih Jenis Mitra~</option>
                                            <option value="Semua Mitra">Semua Mitra</option>
                                            <option value="Distributor">Distributor</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="jenis_po">Jenis PO</label>
                                        <select class="form-control" id="jenis_po" name="jenis_po" required>
                                            <option disabled value="" selected>~Pilih Jenis PO~</option>
                                            <option value="PO Custom Tab">PO Custom Tab</option>
                                            <option value="PO Custom Tab Stok">PO Custom Tab Stok</option>
                                            <option value="PO Konin">PO Konin</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-3">
                            <div class="card-header py-2"><strong>Tanggal</strong></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label>Tutup PO</label>
                                        <input type="date" name="tgl" class="form-control" value="<?= $today ?>" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Acc DB</label>
                                        <input type="date" name="tgl_acc_db" class="form-control" value="<?= $today ?>" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label>Ubah PO</label>
                                        <input type="date" name="tgl_ubah" class="form-control" value="<?= $today ?>" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label>Bayar PO</label>
                                        <input type="date" name="tgl_bayar" class="form-control" value="<?= $today ?>" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label>Dropship</label>
                                        <input type="date" name="tgl_dropship" class="form-control" value="<?= $today ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-3">
                            <div class="card-header py-2"><strong>Tab</strong></div>
                            <div class="card-body" id="tabel_idpoproduk">
                                <small class="text-muted">Pilih PO Produk dulu untuk mengatur jumlah &amp; isi tab.</small>
                            </div>
                        </div>

                        <div class="card shadow mb-3">
                            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                                <strong>Termin Pembayaran</strong>
                                <span id="totalDpBarCustom"></span>
                            </div>
                            <div class="card-body">
                                <small class="text-muted d-block mb-2">Urutan &amp; status pelunasan otomatis - baris terakhir otomatis jadi Pelunasan, tidak perlu diatur manual.</small>
                                <div id="terminContainerCustom"></div>
                                <button type="button" class="btn btn-sm btn-success" onclick="tambahTermin('terminContainerCustom', 'totalDpBarCustom')"><i class="fas fa-plus"></i> Tambah Termin</button>
                            </div>
                        </div>

                        <button class="btn btn-primary" name="save_custom">Tambah Data</button>
                    </form>
                </div>
            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
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

    <script>
        // Satu baris termin = 1 input DP%. Seq (urutan) & status Pelunasan dihitung otomatis
        // dari posisi baris - baris terakhir selalu jadi Pelunasan. Tidak ada input manual
        // untuk seq/pelunasan sama sekali, jadi tidak perlu dikirim ke server: server cukup
        // baca urutan array dp[] apa adanya.
        function terminRowHtml() {
            return `
                <div class="termin-item">
                    <span class="termin-label"></span>
                    <input type="number" min="0" max="100" class="form-control dp-input" name="dp[]" placeholder="Persen" required>
                    <span class="badge badge-secondary termin-badge"></span>
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapusTermin(this)"><i class="fas fa-trash"></i></button>
                </div>
            `;
        }

        function tambahTermin(containerId, totalBarId) {
            document.getElementById(containerId).insertAdjacentHTML('beforeend', terminRowHtml());
            renderUrutanTermin(containerId, totalBarId);
        }

        function hapusTermin(btn) {
            const container = btn.closest('[id^="terminContainer"]');
            if (container.querySelectorAll('.termin-item').length <= 1) {
                alert('Minimal harus ada 1 termin');
                return;
            }
            btn.closest('.termin-item').remove();
            renderUrutanTermin(container.id, container.id === 'terminContainer' ? 'totalDpBar' : 'totalDpBarCustom');
        }

        function renderUrutanTermin(containerId, totalBarId) {
            const items = document.querySelectorAll(`#${containerId} .termin-item`);
            let total = 0;

            items.forEach(function (item, i) {
                const isLast = i === items.length - 1;
                item.querySelector('.termin-label').textContent = `Termin ${i + 1}`;

                const badge = item.querySelector('.termin-badge');
                badge.textContent = isLast ? 'Pelunasan' : 'DP';
                badge.className = 'badge termin-badge ' + (isLast ? 'badge-success' : 'badge-secondary');

                total += parseFloat(item.querySelector('.dp-input').value) || 0;
            });

            const bar = document.getElementById(totalBarId);
            bar.textContent = `Total: ${total}%`;
            bar.className = total === 100 ? 'text-success' : 'text-danger';
        }

        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('dp-input')) {
                const container = e.target.closest('[id^="terminContainer"]');
                renderUrutanTermin(container.id, container.id === 'terminContainer' ? 'totalDpBar' : 'totalDpBarCustom');
            }
        });

        function konfirmasiTotalDp(form, totalBarId) {
            const bar = document.getElementById(totalBarId);
            if (bar.className.includes('text-danger')) {
                return confirm(`Total DP belum 100% (${bar.textContent}). Tetap simpan?`);
            }
            return true;
        }

        document.querySelector('#nav-db form').addEventListener('submit', function (e) {
            if (!konfirmasiTotalDp(this, 'totalDpBar')) e.preventDefault();
        });
        document.querySelector('#nav-agen form').addEventListener('submit', function (e) {
            if (!konfirmasiTotalDp(this, 'totalDpBarCustom')) e.preventDefault();
        });

        // Mulai dengan 1 baris termin kosong di masing-masing tab.
        tambahTermin('terminContainer', 'totalDpBar');
        tambahTermin('terminContainerCustom', 'totalDpBarCustom');
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#idpoproduk_custom').change(function () {
                var idpoproduk_custom = $('#idpoproduk_custom').val();
                $.ajax({
                    type: 'GET',
                    url: 'cek_id_po.php',
                    data: 'idpoproduk_custom=' + idpoproduk_custom,
                    success: function (data) {
                        $("#tabel_idpoproduk").html(data);
                    }
                });
            });
        });
    </script>
</body>
</html>
