<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';

    $idKonsumen = $_SESSION['idkonsumen'];
    $idalamat   = (int) ($_GET['id'] ?? ($_POST['idalamat'] ?? 0));
    $errors     = [];

    $existing = null;
    if ($idalamat > 0) {
        $stmtExisting = $koneksi->prepare("SELECT * FROM alamat WHERE idalamat = ? AND tipe_pemilik = 'konsumen' AND id_pemilik = ?");
        $stmtExisting->bind_param('ii', $idalamat, $idKonsumen);
        $stmtExisting->execute();
        $existing = $stmtExisting->get_result()->fetch_assoc();

        if (!$existing) {
            header('Location: alamat_saya.php');
            exit;
        }
    }

    $selected = [
        'provinsi_id'  => (int) ($existing['provinsi_id'] ?? 0),
        'kota_id'      => (int) ($existing['kota_id'] ?? 0),
        'kecamatan_id' => (int) ($existing['kecamatan_id'] ?? 0),
    ];

    $isiForm = [
        'label'            => $existing['label'] ?? 'Rumah',
        'nama_penerima'    => $existing['nama_penerima'] ?? '',
        'telepon_penerima' => $existing['telepon_penerima'] ?? '',
        'alamat_lengkap'   => $existing['alamat_lengkap'] ?? '',
        'kodepos'          => $existing['kodepos'] ?? '',
        'catatan'          => $existing['catatan'] ?? '',
        'is_utama'         => (int) ($existing['is_utama'] ?? 0),
    ];

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $label       = trim($_POST['label'] ?? '') ?: 'Rumah';
        $nama        = trim($_POST['nama_penerima'] ?? '');
        $telepon     = trim($_POST['telepon_penerima'] ?? '');
        $alamatText  = trim($_POST['alamat_lengkap'] ?? '');
        $provinsiId  = (int) ($_POST['provinsi_id'] ?? 0);
        $kotaId      = (int) ($_POST['kota_id'] ?? 0);
        $kecamatanId = (int) ($_POST['kecamatan_id'] ?? 0);
        $kodepos     = trim($_POST['kodepos'] ?? '');
        $catatan     = trim($_POST['catatan'] ?? '');
        $isUtama     = isset($_POST['is_utama']) ? 1 : 0;

        $isiForm = [
            'label' => $label, 'nama_penerima' => $nama, 'telepon_penerima' => $telepon,
            'alamat_lengkap' => $alamatText, 'kodepos' => $kodepos, 'catatan' => $catatan, 'is_utama' => $isUtama,
        ];
        $selected = ['provinsi_id' => $provinsiId, 'kota_id' => $kotaId, 'kecamatan_id' => $kecamatanId];

        if ($nama === '') {
            $errors[] = 'Nama penerima wajib diisi';
        }
        if ($telepon === '' || !preg_match('/^[0-9+ ]{8,20}$/', $telepon)) {
            $errors[] = 'Nomor telepon penerima tidak valid';
        }
        if ($alamatText === '') {
            $errors[] = 'Alamat lengkap wajib diisi';
        }
        if ($provinsiId <= 0) {
            $errors[] = 'Provinsi wajib dipilih';
        }
        if ($kotaId <= 0) {
            $errors[] = 'Kota/Kabupaten wajib dipilih';
        }
        if ($kecamatanId <= 0) {
            $errors[] = 'Kecamatan wajib dipilih';
        }

        $provinsiNama = $kotaNama = $kecamatanNama = '';

        if (empty($errors)) {
            $stmtP = $koneksi->prepare("SELECT province_name FROM tb_ro_provinces WHERE province_id = ?");
            $stmtP->bind_param('i', $provinsiId);
            $stmtP->execute();
            $provinsiNama = $stmtP->get_result()->fetch_assoc()['province_name'] ?? '';

            $stmtK = $koneksi->prepare("SELECT city_name, postal_code FROM tb_ro_cities WHERE city_id = ? AND province_id = ?");
            $stmtK->bind_param('ii', $kotaId, $provinsiId);
            $stmtK->execute();
            $rowK     = $stmtK->get_result()->fetch_assoc();
            $kotaNama = $rowK['city_name'] ?? '';

            $stmtC = $koneksi->prepare("SELECT subdistrict_name FROM tb_ro_subdistricts WHERE subdistrict_id = ? AND city_id = ?");
            $stmtC->bind_param('ii', $kecamatanId, $kotaId);
            $stmtC->execute();
            $kecamatanNama = $stmtC->get_result()->fetch_assoc()['subdistrict_name'] ?? '';

            if ($provinsiNama === '' || $kotaNama === '' || $kecamatanNama === '') {
                $errors[] = 'Data wilayah tidak valid, silakan pilih ulang';
            }

            if ($kodepos === '') {
                $kodepos = $rowK['postal_code'] ?? '';
            }
        }

        if (empty($errors)) {
            // Cek apakah ini alamat pertama user -> paksa jadi utama walau checkbox tidak dicentang.
            // Angka yang sama juga dipakai buat batas maksimal alamat tersimpan per user.
            $stmtCount = $koneksi->prepare("SELECT COUNT(*) AS jumlah FROM alamat WHERE tipe_pemilik = 'konsumen' AND id_pemilik = ?" . ($idalamat > 0 ? " AND idalamat != ?" : ""));
            if ($idalamat > 0) {
                $stmtCount->bind_param('ii', $idKonsumen, $idalamat);
            } else {
                $stmtCount->bind_param('i', $idKonsumen);
            }
            $stmtCount->execute();
            $jumlahLain = (int) ($stmtCount->get_result()->fetch_assoc()['jumlah'] ?? 0);
            if ($jumlahLain === 0) {
                $isUtama = 1;
            }

            $maxAlamat = 10;
            if ($idalamat <= 0 && $jumlahLain >= $maxAlamat) {
                $errors[] = "Maksimal $maxAlamat alamat tersimpan, hapus salah satu dulu sebelum menambah alamat baru";
            }
        }

        if (empty($errors)) {
            $koneksi->begin_transaction();
            try {
                if ($isUtama === 1) {
                    $stmtUnset = $koneksi->prepare("UPDATE alamat SET is_utama = 0 WHERE tipe_pemilik = 'konsumen' AND id_pemilik = ?");
                    $stmtUnset->bind_param('i', $idKonsumen);
                    $stmtUnset->execute();
                }

                if ($idalamat > 0) {
                    $stmtSave = $koneksi->prepare("UPDATE alamat SET
                                                        label = ?, nama_penerima = ?, telepon_penerima = ?, alamat_lengkap = ?,
                                                        provinsi_id = ?, provinsi = ?, kota_id = ?, kota = ?, kecamatan_id = ?, kecamatan = ?,
                                                        kodepos = ?, catatan = ?, is_utama = ?
                                                    WHERE idalamat = ? AND tipe_pemilik = 'konsumen' AND id_pemilik = ?");
                    $stmtSave->bind_param(
                        'ssssisisisssiii',
                        $label, $nama, $telepon, $alamatText,
                        $provinsiId, $provinsiNama, $kotaId, $kotaNama, $kecamatanId, $kecamatanNama,
                        $kodepos, $catatan, $isUtama, $idalamat, $idKonsumen
                    );
                    $stmtSave->execute();
                } else {
                    $stmtSave = $koneksi->prepare("INSERT INTO alamat
                                                        (tipe_pemilik, id_pemilik, label, nama_penerima, telepon_penerima, alamat_lengkap,
                                                         provinsi_id, provinsi, kota_id, kota, kecamatan_id, kecamatan, kodepos, catatan, is_utama)
                                                    VALUES ('konsumen', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmtSave->bind_param(
                        'issssisisisssi',
                        $idKonsumen, $label, $nama, $telepon, $alamatText,
                        $provinsiId, $provinsiNama, $kotaId, $kotaNama, $kecamatanId, $kecamatanNama,
                        $kodepos, $catatan, $isUtama
                    );
                    $stmtSave->execute();
                }

                $koneksi->commit();
                $_SESSION['message'] = 'Alamat berhasil disimpan';
                header('Location: alamat_saya.php');
                exit;
            } catch (Exception $e) {
                $koneksi->rollback();
                error_log($e->getMessage());
                $errors[] = 'Gagal menyimpan alamat, silakan coba lagi';
            }
        }
    }

    $daftarProvinsi = $koneksi->query("SELECT province_id, province_name FROM tb_ro_provinces ORDER BY province_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $idalamat > 0 ? 'Ubah' : 'Tambah' ?> Alamat | Wanoja</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        body { background: #f5f6fa; }
        .form-panel {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.5rem;
            max-width: 640px;
            margin: 1.5rem auto;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="form-panel">
            <h5 class="font-weight-bold mb-3"><?= $idalamat > 0 ? 'Ubah Alamat' : 'Tambah Alamat Baru' ?></h5>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0 pl-3">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post">
                <input type="hidden" name="idalamat" value="<?= (int) $idalamat ?>">

                <div class="form-group">
                    <label class="mb-1">Label Alamat</label>
                    <input type="text" class="form-control" name="label" list="labelSuggest" value="<?= htmlspecialchars($isiForm['label']) ?>" maxlength="50">
                    <datalist id="labelSuggest">
                        <option value="Rumah">
                        <option value="Kantor">
                        <option value="Lainnya">
                    </datalist>
                </div>
                <div class="form-group">
                    <label class="mb-1">Nama Penerima</label>
                    <input type="text" class="form-control" name="nama_penerima" value="<?= htmlspecialchars($isiForm['nama_penerima']) ?>" required>
                </div>
                <div class="form-group">
                    <label class="mb-1">No. Telepon Penerima</label>
                    <input type="text" class="form-control" name="telepon_penerima" value="<?= htmlspecialchars($isiForm['telepon_penerima']) ?>" required>
                </div>
                <div class="form-group">
                    <label class="mb-1">Alamat Lengkap</label>
                    <textarea class="form-control" name="alamat_lengkap" rows="3" required><?= htmlspecialchars($isiForm['alamat_lengkap']) ?></textarea>
                </div>

                <div class="form-group">
                    <label class="mb-1">Provinsi</label>
                    <select class="form-control" id="provinsi_id" name="provinsi_id" required>
                        <option value="" disabled <?= $selected['provinsi_id'] === 0 ? 'selected' : '' ?>>~Pilih Provinsi~</option>
                        <?php while ($row = $daftarProvinsi->fetch_assoc()): ?>
                            <option value="<?= (int) $row['province_id'] ?>" <?= $selected['provinsi_id'] === (int) $row['province_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['province_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="mb-1">Kota/Kabupaten</label>
                    <select class="form-control" id="kota_id" name="kota_id" required>
                        <option value="" disabled selected>~Pilih Kota/Kabupaten~</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="mb-1">Kecamatan</label>
                    <select class="form-control" id="kecamatan_id" name="kecamatan_id" required>
                        <option value="" disabled selected>~Pilih Kecamatan~</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="mb-1">Kode Pos</label>
                    <input type="text" class="form-control" name="kodepos" id="kodepos" style="max-width:160px" value="<?= htmlspecialchars($isiForm['kodepos']) ?>">
                </div>
                <div class="form-group">
                    <label class="mb-1">Catatan <span class="text-muted">(opsional)</span></label>
                    <textarea class="form-control" name="catatan" rows="2" placeholder="Contoh: patokan rumah, warna pagar, dll"><?= htmlspecialchars($isiForm['catatan']) ?></textarea>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="is_utama" name="is_utama" value="1" <?= $isiForm['is_utama'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_utama">Jadikan alamat utama</label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Simpan Alamat</button>
                <a href="alamat_saya.php" class="btn btn-link btn-block">Batal</a>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function loadKota(provinsiId, selectedKotaId, cb) {
            if (!provinsiId) {
                $('#kota_id').html('<option value="" disabled selected>~Pilih Kota/Kabupaten~</option>');
                return;
            }
            $.get('cek_kabupaten.php', { provinsi_id: provinsiId }, function (data) {
                $('#kota_id').html(data);
                if (selectedKotaId) {
                    $('#kota_id').val(selectedKotaId);
                    var kodepos = $('#kota_id option:selected').data('kodepos');
                    if (kodepos && $('#kodepos').val() === '') { $('#kodepos').val(kodepos); }
                }
                if (cb) { cb(); }
            });
        }

        function loadKecamatan(kotaId, selectedKecamatanId) {
            if (!kotaId) {
                $('#kecamatan_id').html('<option value="" disabled selected>~Pilih Kecamatan~</option>');
                return;
            }
            $.get('cek_kecamatan.php', { kota_id: kotaId }, function (data) {
                $('#kecamatan_id').html(data);
                if (selectedKecamatanId) {
                    $('#kecamatan_id').val(selectedKecamatanId);
                }
            });
        }

        $(document).ready(function () {
            var initProvinsiId  = <?= (int) $selected['provinsi_id'] ?>;
            var initKotaId      = <?= (int) $selected['kota_id'] ?>;
            var initKecamatanId = <?= (int) $selected['kecamatan_id'] ?>;

            if (initProvinsiId) {
                loadKota(initProvinsiId, initKotaId, function () {
                    if (initKotaId) { loadKecamatan(initKotaId, initKecamatanId); }
                });
            }

            $('#provinsi_id').change(function () {
                loadKota($(this).val(), null);
                $('#kecamatan_id').html('<option value="" disabled selected>~Pilih Kecamatan~</option>');
            });

            $('#kota_id').change(function () {
                var kodepos = $('#kota_id option:selected').data('kodepos');
                if (kodepos) { $('#kodepos').val(kodepos); }
                loadKecamatan($(this).val(), null);
            });
        });
    </script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
