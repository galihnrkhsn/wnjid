<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';

    $idKonsumen = $_SESSION['idkonsumen'];
    $invoice    = trim($_GET['invoice'] ?? ($_POST['invoice'] ?? ''));
    $errors     = [];

    $stmtOrder = $koneksi->prepare("SELECT * FROM orderkonsumen WHERE invoice = ? AND idkonsumen = ?");
    $stmtOrder->bind_param('si', $invoice, $idKonsumen);
    $stmtOrder->execute();
    $order = $stmtOrder->get_result()->fetch_assoc();

    if (!$order) {
        header('Location: view_cart.php');
        exit;
    }

    // Nilai yang ditampilkan ulang di form kalau validasi POST gagal (supaya inputan tidak hilang)
    $selected = [
        'provinsi_id'  => 0,
        'kota_id'      => 0,
        'kecamatan_id' => 0,
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama        = trim($_POST['nama_penerima'] ?? '');
        $telepon     = trim($_POST['telepon_penerima'] ?? '');
        $alamat      = trim($_POST['alamat_lengkap'] ?? '');
        $provinsiId  = (int) ($_POST['provinsi_id'] ?? 0);
        $kotaId      = (int) ($_POST['kota_id'] ?? 0);
        $kecamatanId = (int) ($_POST['kecamatan_id'] ?? 0);
        $kodepos     = trim($_POST['kodepos'] ?? '');
        $kurir       = trim($_POST['kurir'] ?? '');
        $ekspedisi   = trim($_POST['ekspedisi_label'] ?? '');
        $layananPost = trim($_POST['layanan'] ?? '');
        $catatan     = trim($_POST['catatan'] ?? '');
        $simpanAlamatBaru = isset($_POST['simpan_alamat_baru']);

        $selected['provinsi_id']  = $provinsiId;
        $selected['kota_id']      = $kotaId;
        $selected['kecamatan_id'] = $kecamatanId;

        if ($nama === '') {
            $errors[] = 'Nama penerima wajib diisi';
        }
        if ($telepon === '' || !preg_match('/^[0-9+ ]{8,20}$/', $telepon)) {
            $errors[] = 'Nomor telepon penerima tidak valid';
        }
        if ($alamat === '') {
            $errors[] = 'Alamat lengkap wajib diisi';
        }
        if ($provinsiId <= 0) {
            $errors[] = 'Provinsi tujuan wajib dipilih';
        }
        if ($kotaId <= 0) {
            $errors[] = 'Kota/Kabupaten tujuan wajib dipilih';
        }
        if ($kecamatanId <= 0) {
            $errors[] = 'Kecamatan tujuan wajib dipilih';
        }
        if ($kurir === '') {
            $errors[] = 'Kurir pengiriman wajib dipilih';
        }
        if ($layananPost === '') {
            $errors[] = 'Layanan pengiriman wajib dipilih';
        }

        $provinsiNama = $kotaNama = $kecamatanNama = '';

        if (empty($errors)) {
            // Nama wilayah diambil ulang dari tabel referensi (bukan dipercaya dari input client)
            $stmtP = $koneksi->prepare("SELECT province_name FROM tb_ro_provinces WHERE province_id = ?");
            $stmtP->bind_param('i', $provinsiId);
            $stmtP->execute();
            $rowP = $stmtP->get_result()->fetch_assoc();
            $provinsiNama = $rowP['province_name'] ?? '';

            $stmtK = $koneksi->prepare("SELECT city_name, postal_code FROM tb_ro_cities WHERE city_id = ? AND province_id = ?");
            $stmtK->bind_param('ii', $kotaId, $provinsiId);
            $stmtK->execute();
            $rowK = $stmtK->get_result()->fetch_assoc();
            $kotaNama = $rowK['city_name'] ?? '';

            $stmtC = $koneksi->prepare("SELECT subdistrict_name FROM tb_ro_subdistricts WHERE subdistrict_id = ? AND city_id = ?");
            $stmtC->bind_param('ii', $kecamatanId, $kotaId);
            $stmtC->execute();
            $rowC = $stmtC->get_result()->fetch_assoc();
            $kecamatanNama = $rowC['subdistrict_name'] ?? '';

            if ($provinsiNama === '' || $kotaNama === '' || $kecamatanNama === '') {
                $errors[] = 'Data wilayah tujuan tidak valid, silakan pilih ulang';
            }

            if ($kodepos === '') {
                $kodepos = $rowK['postal_code'] ?? '';
            }
        }

        if (empty($errors)) {
            $layananParts = explode('|', $layananPost);
            $layananNama  = $layananParts[0] ?? '';
            $ongkir       = (int) ($layananParts[1] ?? 0);
            $total        = (float) $order['subtotal'] + $ongkir;

            $stmtUpdate = $koneksi->prepare("UPDATE orderkonsumen SET
                                                nama_penerima = ?, telepon_penerima = ?, alamat_lengkap = ?,
                                                provinsi = ?, kota = ?, kecamatan = ?, kodepos = ?,
                                                ekspedisi = ?, layanan = ?, ongkir = ?, total = ?, catatan = ?,
                                                status = 'Menunggu Pembayaran'
                                                WHERE idorder = ?");
            $stmtUpdate->bind_param(
                'sssssssssddsi',
                $nama, $telepon, $alamat, $provinsiNama, $kotaNama, $kecamatanNama, $kodepos,
                $ekspedisi, $layananNama, $ongkir, $total, $catatan, $order['idorder']
            );
            $stmtUpdate->execute();

            if ($simpanAlamatBaru) {
                // Alamat pertama otomatis jadi utama, walau checkbox "utama" tidak ada di form checkout ini
                $stmtCountAlamat = $koneksi->prepare("SELECT COUNT(*) AS jumlah FROM alamat WHERE tipe_pemilik = 'konsumen' AND id_pemilik = ?");
                $stmtCountAlamat->bind_param('i', $idKonsumen);
                $stmtCountAlamat->execute();
                $jumlahAlamatTersimpan = (int) ($stmtCountAlamat->get_result()->fetch_assoc()['jumlah'] ?? 0);
                $isUtamaBaru = $jumlahAlamatTersimpan === 0 ? 1 : 0;

                // Kalau sudah kena batas maksimal alamat tersimpan, lewati saja penyimpanannya diam-diam
                // (checkout tidak boleh gagal cuma gara-gara alamat book penuh)
                $maxAlamat = 10;
                if ($jumlahAlamatTersimpan < $maxAlamat) {
                    $labelAlamatBaru = 'Rumah';
                    $stmtInsertAlamat = $koneksi->prepare("INSERT INTO alamat
                                                                (tipe_pemilik, id_pemilik, label, nama_penerima, telepon_penerima, alamat_lengkap,
                                                                 provinsi_id, provinsi, kota_id, kota, kecamatan_id, kecamatan, kodepos, catatan, is_utama)
                                                            VALUES ('konsumen', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmtInsertAlamat->bind_param(
                        'issssisisisssi',
                        $idKonsumen, $labelAlamatBaru, $nama, $telepon, $alamat,
                        $provinsiId, $provinsiNama, $kotaId, $kotaNama, $kecamatanId, $kecamatanNama,
                        $kodepos, $catatan, $isUtamaBaru
                    );
                    $stmtInsertAlamat->execute();
                }
            }

            header('Location: detail.php?invoice=' . urlencode($invoice));
            exit;
        }
    }

    // Prefill dari profil konsumen kalau alamat order belum pernah diisi
    $stmtProfil = $koneksi->prepare("SELECT namamitra, whatsapp, alamat, provinsi, kota, kecamatan, kodepos FROM konsumen WHERE idkonsumen = ?");
    $stmtProfil->bind_param('i', $idKonsumen);
    $stmtProfil->execute();
    $profil = $stmtProfil->get_result()->fetch_assoc() ?? [];

    $stmtAlamatTersimpan = $koneksi->prepare("SELECT * FROM alamat WHERE tipe_pemilik = 'konsumen' AND id_pemilik = ? ORDER BY is_utama DESC, updated_at DESC");
    $stmtAlamatTersimpan->bind_param('i', $idKonsumen);
    $stmtAlamatTersimpan->execute();
    $daftarAlamatTersimpan = $stmtAlamatTersimpan->get_result()->fetch_all(MYSQLI_ASSOC);

    $isiForm = [
        'nama_penerima'    => $order['nama_penerima'] ?: ($profil['namamitra'] ?? ''),
        'telepon_penerima' => $order['telepon_penerima'] ?: ($profil['whatsapp'] ?? ''),
        'alamat_lengkap'   => $order['alamat_lengkap'] ?: ($profil['alamat'] ?? ''),
        'kodepos'          => $order['kodepos'] ?: ($profil['kodepos'] ?? ''),
        'catatan'          => $order['catatan'] ?? '',
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validasi gagal: tampilkan ulang apa yang barusan diketik/dipilih user
        $isiForm['nama_penerima']    = $nama ?? $isiForm['nama_penerima'];
        $isiForm['telepon_penerima'] = $telepon ?? $isiForm['telepon_penerima'];
        $isiForm['alamat_lengkap']   = $alamat ?? $isiForm['alamat_lengkap'];
        $isiForm['kodepos']          = $kodepos ?? $isiForm['kodepos'];
        $isiForm['catatan']          = $catatan ?? $isiForm['catatan'];
        $simpanAlamatBaruChecked = $simpanAlamatBaru;
    } else {
        // Kunjungan awal: order sudah pernah diisi -> pakai itu. Kalau belum, coba alamat utama
        // tersimpan (sudah ada ID-nya langsung). Baru fallback ke kolom profil konsumen (perilaku lama).
        $alamatUtama = null;
        foreach ($daftarAlamatTersimpan as $a) {
            if ((int) $a['is_utama'] === 1) {
                $alamatUtama = $a;
                break;
            }
        }
        if (!$alamatUtama && !empty($daftarAlamatTersimpan)) {
            $alamatUtama = $daftarAlamatTersimpan[0];
        }

        $namaProvinsi = $namaKota = $namaKecamatan = '';

        if (!empty($order['provinsi'])) {
            $namaProvinsi  = $order['provinsi'];
            $namaKota      = $order['kota'];
            $namaKecamatan = $order['kecamatan'];
        } elseif ($alamatUtama) {
            $selected['provinsi_id']  = (int) $alamatUtama['provinsi_id'];
            $selected['kota_id']      = (int) $alamatUtama['kota_id'];
            $selected['kecamatan_id'] = (int) $alamatUtama['kecamatan_id'];
            $isiForm['nama_penerima']    = $alamatUtama['nama_penerima'];
            $isiForm['telepon_penerima'] = $alamatUtama['telepon_penerima'];
            $isiForm['alamat_lengkap']   = $alamatUtama['alamat_lengkap'];
            $isiForm['kodepos']          = $alamatUtama['kodepos'] ?: $isiForm['kodepos'];
            if (!empty($alamatUtama['catatan'])) {
                $isiForm['catatan'] = $alamatUtama['catatan'];
            }
        } else {
            $namaProvinsi  = $profil['provinsi'] ?? '';
            $namaKota      = $profil['kota'] ?? '';
            $namaKecamatan = $profil['kecamatan'] ?? '';
        }

        $simpanAlamatBaruChecked = empty($daftarAlamatTersimpan);

        if ($namaProvinsi !== '') {
            $stmtFP = $koneksi->prepare("SELECT province_id FROM tb_ro_provinces WHERE province_name = ?");
            $stmtFP->bind_param('s', $namaProvinsi);
            $stmtFP->execute();
            $selected['provinsi_id'] = (int) ($stmtFP->get_result()->fetch_assoc()['province_id'] ?? 0);
        }

        if ($selected['provinsi_id'] > 0 && $namaKota !== '') {
            $stmtFK = $koneksi->prepare("SELECT city_id FROM tb_ro_cities WHERE city_name = ? AND province_id = ?");
            $stmtFK->bind_param('si', $namaKota, $selected['provinsi_id']);
            $stmtFK->execute();
            $selected['kota_id'] = (int) ($stmtFK->get_result()->fetch_assoc()['city_id'] ?? 0);
        }

        if ($selected['kota_id'] > 0 && $namaKecamatan !== '') {
            $stmtFC = $koneksi->prepare("SELECT subdistrict_id FROM tb_ro_subdistricts WHERE subdistrict_name = ? AND city_id = ?");
            $stmtFC->bind_param('si', $namaKecamatan, $selected['kota_id']);
            $stmtFC->execute();
            $selected['kecamatan_id'] = (int) ($stmtFC->get_result()->fetch_assoc()['subdistrict_id'] ?? 0);
        }
    }

    // Cocokkan pilihan saat ini ke salah satu alamat tersimpan (kalau ada) supaya tahu
    // apakah form input alamat baru perlu ditampilkan atau disembunyikan dulu
    $idAlamatTerpilih = null;
    foreach ($daftarAlamatTersimpan as $a) {
        if ((int) $a['provinsi_id'] === $selected['provinsi_id']
            && (int) $a['kota_id'] === $selected['kota_id']
            && (int) $a['kecamatan_id'] === $selected['kecamatan_id']
            && $a['nama_penerima'] === $isiForm['nama_penerima']) {
            $idAlamatTerpilih = (int) $a['idalamat'];
            break;
        }
    }
    $modeAlamatBaru = $idAlamatTerpilih === null;

    $daftarProvinsi = $koneksi->query("SELECT province_id, province_name FROM tb_ro_provinces ORDER BY province_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Alamat Pengiriman | Wanoja</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        body { background: var(--wnj-bg); }
        .form-panel {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.5rem;
            max-width: 640px;
            margin: 1.5rem auto;
        }
        .step-badge {
            font-size: .75rem;
            color: var(--wnj-text-secondary);
        }
        .alamat-pick-card {
            border: 1px solid var(--wnj-border);
            border-radius: 10px;
            padding: .75rem;
            margin-bottom: .5rem;
            cursor: pointer;
        }
        .alamat-pick-card.dashed {
            border-style: dashed;
        }
        .alamat-pick-card input[type="radio"] {
            margin-right: .4rem;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="form-panel">
            <div class="step-badge mb-2">Langkah 1 dari 3 &middot; Invoice <?= htmlspecialchars($invoice) ?></div>
            <h5 class="font-weight-bold mb-3">Alamat Pengiriman</h5>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0 pl-3">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" id="formAlamat">
                <input type="hidden" name="invoice" value="<?= htmlspecialchars($invoice) ?>">
                <input type="hidden" name="ekspedisi_label" id="ekspedisi_label" value="">

                <?php if (!empty($daftarAlamatTersimpan)): ?>
                    <div class="form-group">
                        <label class="mb-2 d-block">Alamat Tersimpan</label>
                        <?php foreach ($daftarAlamatTersimpan as $a): $cocok = (int) $a['idalamat'] === $idAlamatTerpilih; ?>
                            <label class="alamat-pick-card d-block">
                                <input type="radio" name="pilih_alamat_tersimpan" class="alamat-pick-radio"
                                       data-nama="<?= htmlspecialchars($a['nama_penerima'], ENT_QUOTES) ?>"
                                       data-telepon="<?= htmlspecialchars($a['telepon_penerima'], ENT_QUOTES) ?>"
                                       data-alamat="<?= htmlspecialchars($a['alamat_lengkap'], ENT_QUOTES) ?>"
                                       data-provinsi-id="<?= (int) $a['provinsi_id'] ?>"
                                       data-kota-id="<?= (int) $a['kota_id'] ?>"
                                       data-kecamatan-id="<?= (int) $a['kecamatan_id'] ?>"
                                       data-kodepos="<?= htmlspecialchars($a['kodepos'] ?? '', ENT_QUOTES) ?>"
                                       data-catatan="<?= htmlspecialchars($a['catatan'] ?? '', ENT_QUOTES) ?>"
                                       <?= $cocok ? 'checked' : '' ?>>
                                <strong><?= htmlspecialchars($a['label']) ?></strong>
                                <?php if ((int) $a['is_utama'] === 1): ?><span class="badge badge-primary ml-1">Utama</span><?php endif; ?>
                                <div class="small"><?= htmlspecialchars($a['nama_penerima']) ?> &middot; <?= htmlspecialchars($a['telepon_penerima']) ?></div>
                                <div class="text-muted small"><?= htmlspecialchars($a['alamat_lengkap']) ?></div>
                            </label>
                        <?php endforeach; ?>
                        <label class="alamat-pick-card dashed d-block">
                            <input type="radio" name="pilih_alamat_tersimpan" id="pilihAlamatBaru" class="alamat-pick-radio" data-baru="1" <?= $modeAlamatBaru ? 'checked' : '' ?>>
                            <strong><i class="bi bi-plus-lg"></i> Alamat Baru</strong>
                        </label>
                    </div>
                <?php endif; ?>

                <div id="alamatBaruFields"<?= $modeAlamatBaru ? '' : ' style="display:none"' ?>>
                    <?php if (!empty($daftarAlamatTersimpan)): ?><hr><?php endif; ?>

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
                        <label class="mb-1">Provinsi Tujuan</label>
                        <select class="form-control" id="provinsi_id" name="provinsi_id" required>
                            <option value="" disabled <?= $selected['provinsi_id'] === 0 ? 'selected' : '' ?>>~Pilih Provinsi Tujuan~</option>
                            <?php while ($row = $daftarProvinsi->fetch_assoc()): ?>
                                <option value="<?= (int) $row['province_id'] ?>" <?= $selected['provinsi_id'] === (int) $row['province_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['province_name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="mb-1">Kota/Kabupaten Tujuan</label>
                        <select class="form-control" id="kota_id" name="kota_id" required>
                            <option value="" disabled selected>~Pilih Kota/Kabupaten Tujuan~</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="mb-1">Kecamatan Tujuan</label>
                        <select class="form-control" id="kecamatan_id" name="kecamatan_id" required>
                            <option value="" disabled selected>~Pilih Kecamatan Tujuan~</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="mb-1">Kode Pos</label>
                        <input type="text" class="form-control" name="kodepos" id="kodepos" style="max-width:160px" value="<?= htmlspecialchars($isiForm['kodepos']) ?>">
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="simpanAlamatBaru" name="simpan_alamat_baru" value="1" <?= $simpanAlamatBaruChecked ? 'checked' : '' ?>>
                        <label class="form-check-label" for="simpanAlamatBaru">Simpan alamat ini ke Alamat Tersimpan</label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="mb-1">Kurir Pengiriman</label>
                    <select class="form-control" id="kurir" name="kurir" required>
                        <option value="" disabled selected>~Pilih Kurir Pengiriman~</option>
                        <option value="OR|jne">JNE</option>
                        <option value="OR|tiki">TIKI</option>
                        <option value="OR|pos">POS INDONESIA</option>
                        <option value="OR|wahana">WAHANA</option>
                        <option value="OR|sicepat">SICEPAT</option>
                        <option value="OR|jnt">J&amp;T</option>
                        <option value="OR|lion">LION</option>
                        <option value="OR|anteraja">Anteraja</option>
                        <option value="OR|ide">ID Express</option>
                        <optgroup label="Lainnya (Ongkir Manual)">
                            <option value="OM|idetruck">ID Express Truck</option>
                            <option value="OM|jntcargo">J&amp;T Cargo</option>
                            <option value="OM|jtr">JTR</option>
                            <option value="OM|Ahsan">Ahsan</option>
                            <option value="OM|Baraka">Baraka</option>
                            <option value="OM|Dakota">Dakota</option>
                            <option value="OM|IndahCargo">IndahCargo</option>
                            <option value="OM|Adam Cargo">Adam Cargo</option>
                            <option value="OM|Pegasus">Pegasus</option>
                            <option value="OM|Gosend">GoSend</option>
                            <option value="OM|KALOG">KALOG</option>
                            <option value="OM|Sentral">Sentral</option>
                            <option value="OM|CMC KARGO">CMC CARGO</option>
                            <option value="OM|Triplogic">Triplogic</option>
                            <option value="OM|Ambil ke Pusat">Ambil Ke Pusat</option>
                            <option value="OM|Disatukan">Disatukan Paket Lainnya</option>
                        </optgroup>
                    </select>
                </div>
                <div class="form-group">
                    <label class="mb-1">Layanan</label>
                    <select class="form-control" name="layanan" id="layanan" required>
                        <option value="" disabled selected>~Pilih kurir terlebih dahulu~</option>
                    </select>
                    <small class="text-muted">Kurir kategori "Lainnya (Ongkir Manual)" akan dikonfirmasi ongkirnya oleh admin.</small>
                </div>

                <div class="form-group">
                    <label class="mb-1">Catatan <span class="text-muted">(opsional)</span></label>
                    <textarea class="form-control" name="catatan" rows="2"><?= htmlspecialchars($isiForm['catatan']) ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Lanjut ke Ringkasan Pesanan</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        var INVOICE = <?= json_encode($invoice) ?>;

        function loadKota(provinsiId, selectedKotaId, cb) {
            if (!provinsiId) {
                $('#kota_id').html('<option value="" disabled selected>~Pilih Kota/Kabupaten Tujuan~</option>');
                return;
            }
            $.get('cek_kabupaten.php', { provinsi_id: provinsiId }, function (data) {
                $('#kota_id').html(data);
                if (selectedKotaId) {
                    $('#kota_id').val(selectedKotaId);
                    $('#kodepos').val($('#kota_id option:selected').data('kodepos') || '');
                }
                if (cb) { cb(); }
            });
        }

        function loadKecamatan(kotaId, selectedKecamatanId) {
            if (!kotaId) {
                $('#kecamatan_id').html('<option value="" disabled selected>~Pilih Kecamatan Tujuan~</option>');
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
                $('#kecamatan_id').html('<option value="" disabled selected>~Pilih Kecamatan Tujuan~</option>');
                $('#layanan').html('<option value="" disabled selected>~Pilih kurir terlebih dahulu~</option>');
            });

            $('#kota_id').change(function () {
                $('#kodepos').val($('#kota_id option:selected').data('kodepos') || '');
                loadKecamatan($(this).val(), null);
                $('#layanan').html('<option value="" disabled selected>~Pilih kurir terlebih dahulu~</option>');
            });

            $('#kecamatan_id').change(function () {
                $('#layanan').html('<option value="" disabled selected>~Pilih kurir terlebih dahulu~</option>');
            });

            $('.alamat-pick-radio').change(function () {
                if ($(this).data('baru')) {
                    $('#alamatBaruFields').show();
                    $('#simpanAlamatBaru').prop('checked', true);

                    $('input[name="nama_penerima"]').val('');
                    $('input[name="telepon_penerima"]').val('');
                    $('textarea[name="alamat_lengkap"]').val('');
                    $('#kodepos').val('');
                    $('#provinsi_id').val('');
                    $('#kota_id').html('<option value="" disabled selected>~Pilih Kota/Kabupaten Tujuan~</option>');
                    $('#kecamatan_id').html('<option value="" disabled selected>~Pilih Kecamatan Tujuan~</option>');

                    $('#kurir').val('');
                    $('#layanan').html('<option value="" disabled selected>~Pilih kurir terlebih dahulu~</option>');
                    return;
                }

                $('#alamatBaruFields').hide();
                $('#simpanAlamatBaru').prop('checked', false);

                $('input[name="nama_penerima"]').val($(this).data('nama'));
                $('input[name="telepon_penerima"]').val($(this).data('telepon'));
                $('textarea[name="alamat_lengkap"]').val($(this).data('alamat'));
                $('textarea[name="catatan"]').val($(this).data('catatan') || '');

                var provinsiId  = $(this).data('provinsi-id');
                var kotaId      = $(this).data('kota-id');
                var kecamatanId = $(this).data('kecamatan-id');
                var kodepos     = $(this).data('kodepos');

                $('#provinsi_id').val(provinsiId);
                loadKota(provinsiId, kotaId, function () {
                    loadKecamatan(kotaId, kecamatanId);
                    if (kodepos) { $('#kodepos').val(kodepos); }
                });

                $('#kurir').val('');
                $('#layanan').html('<option value="" disabled selected>~Pilih kurir terlebih dahulu~</option>');
            });

            $('#kurir').change(function () {
                var val   = $(this).val();
                var parts = val.split('|');
                var grup  = parts[0];
                var kode  = parts[1];

                $('#ekspedisi_label').val($('#kurir option:selected').text());

                if (grup === 'OM') {
                    $('#layanan').html('<option value="Manual|0">Ongkir Manual (dikonfirmasi admin)</option>');
                    return;
                }

                var kecamatanId = $('#kecamatan_id').val();
                if (!kecamatanId) {
                    alert('Pilih provinsi, kota, dan kecamatan tujuan terlebih dahulu');
                    $(this).val('');
                    return;
                }

                $('#layanan').html('<option value="" disabled selected>Memuat layanan...</option>');
                $.ajax({
                    type: 'POST',
                    url: 'cek_ongkir.php',
                    data: { invoice: INVOICE, kecamatan_id: kecamatanId, kurir: kode },
                    success: function (data) {
                        $('#layanan').html(data);
                    }
                });
            });
        });
    </script>
</body>
</html>
