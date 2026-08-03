<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header('Content-Type: application/json');
    date_default_timezone_set('Asia/Jakarta');
    require_once '../../includes/db.php';
    require_once '../../includes/image_upload_helper.php';

    $waktu          = date('H:i:s');
    $keterangan     = addslashes(htmlspecialchars($_POST['keterangan'] ?? ''));
    $tipe           = $_POST['tipe'] ?? null;
    $tanggal        = $_POST['tanggal'] ?? null;
    $jumlah         = isset($_POST['jumlah']) ? floatval($_POST['jumlah']) : 0;
    $jenis          = $_POST['jenis_transaksi'] ?? null;
    $kategori       = $_POST['kategori'] ?? null;
    $iduser         = $_POST['iduser'] ?? null;
    $bank           = $_POST['bank'] ?? null;
    $pindahBank     = $_POST['pindah_bank'] ?? null;

    if (!$iduser) {
        echo json_encode(['success' => false, 'message' => 'User tidak terdeteksi!']);
        exit;
    }

    if (!$tipe || !$tanggal || !$keterangan || !$jumlah || !$kategori) {
        echo json_encode(['success' => false, 'message' => 'Data tidak lengkap!']);
        exit;
    }

    if (!in_array($jenis, ['kredit', 'debit'])) {
        echo json_encode(['success' => false, 'message' => 'Jenis Transaksi tidak valid!']);
        exit;
    }

    // Saldo berjalan per tipe (tipe adalah PRIMARY KEY - dipakai juga oleh saldo tagihan vendor
    // di manajemen/manajemen/bill.php, jadi update di bawah HARUS tetap di-scope per $tipe).
    $stmtSaldo = $koneksi->prepare("SELECT sisasaldo FROM saldo_per_tipe WHERE tipe = ?");
    $stmtSaldo->bind_param('s', $tipe);
    $stmtSaldo->execute();
    $saldoRow  = $stmtSaldo->get_result()->fetch_assoc();
    $sisaAwal  = $saldoRow ? (float) $saldoRow['sisasaldo'] : 0.0;
    $sisaBaru  = ($jenis === 'kredit') ? ($sisaAwal + $jumlah) : ($sisaAwal - $jumlah);

    // "Pindah Bank": kategori yang berarti transaksi ini juga harus tercatat sebagai transaksi
    // kedua (mirror) di tipe tujuan - dicek dari nama kategori, bukan id hardcode, supaya tidak
    // rapuh kalau idkategori-nya berubah.
    $stmtKategori   = $koneksi->prepare("SELECT nama_kategori FROM kategori_manajemen WHERE idkategori = ?");
    $stmtKategori->bind_param('i', $kategori);
    $stmtKategori->execute();
    $kategoriRow    = $stmtKategori->get_result()->fetch_assoc();
    $isPindahBank   = $kategoriRow && $kategoriRow['nama_kategori'] === 'Pindah Bank' && $pindahBank;

    $targetDir      = '../../image/bukti_manajemen/';
    $namaFileBaru   = null;
    $hasFile        = isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK;

    if ($hasFile && $tipe !== 'MF') {
        $foto       = $_FILES['foto']['name'];
        $tmp        = $_FILES['foto']['tmp_name'];
        $ukuranFile = $_FILES['foto']['size'];

        $ekstensiValid = ['jpg', 'jpeg', 'png'];
        $ekstensi      = strtolower(pathinfo($foto, PATHINFO_EXTENSION));

        if (!in_array($ekstensi, $ekstensiValid)) {
            echo json_encode(['success' => false, 'message' => 'Yang anda upload bukan gambar (jpg/jpeg/png)']);
            exit;
        }

        $ukuranMaksimal = 2 * 1024 * 1024;
        if ($ukuranFile > $ukuranMaksimal) {
            echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar (maks 2MB)']);
            exit;
        }

        $prefixJenis  = $jenis === 'kredit' ? 'K' : 'D';
        $namaFileBaru = convertUploadedImageToWebp($tmp, $ekstensi, $targetDir, $prefixJenis . $tipe . uniqid());

        if (!$namaFileBaru) {
            echo json_encode(['success' => false, 'message' => 'Gagal memproses file nota']);
            exit;
        }
    }

    $kredit = $jenis === 'kredit' ? $jumlah : 0;
    $debit  = $jenis === 'debit' ? $jumlah : 0;

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $koneksi->begin_transaction();
    try {
        $stmtInsert = $koneksi->prepare("INSERT INTO rekeningkoran
                                            (idrk, iduser, tanggal, waktu, keterangan, kredit, debit, sisasaldo, buktitf, tipe, kategori_id, bank)
                                            VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtInsert->bind_param('isssdddssss', $iduser, $tanggal, $waktu, $keterangan, $kredit, $debit, $sisaBaru, $namaFileBaru, $tipe, $kategori, $bank);
        $stmtInsert->execute();

        $stmtSaldoUpsert = $koneksi->prepare("INSERT INTO saldo_per_tipe (tipe, total_kredit, total_debit, sisasaldo, updated_at)
                                                VALUES (?, ?, ?, ?, NOW())
                                                ON DUPLICATE KEY UPDATE
                                                    total_kredit = total_kredit + VALUES(total_kredit),
                                                    total_debit  = total_debit + VALUES(total_debit),
                                                    sisasaldo    = VALUES(sisasaldo),
                                                    updated_at   = NOW()");
        $stmtSaldoUpsert->bind_param('sddd', $tipe, $kredit, $debit, $sisaBaru);
        $stmtSaldoUpsert->execute();

        if ($isPindahBank) {
            // Mirror: transaksi yang sama ikut tercatat sebagai kredit di tipe tujuan
            // (persis logic lama di manajemen/produksi/input_kredit.php & input_debit.php).
            $stmtSaldoTujuan = $koneksi->prepare("SELECT sisasaldo FROM saldo_per_tipe WHERE tipe = ?");
            $stmtSaldoTujuan->bind_param('s', $pindahBank);
            $stmtSaldoTujuan->execute();
            $saldoTujuanRow  = $stmtSaldoTujuan->get_result()->fetch_assoc();
            $sisaTujuanBaru  = ($saldoTujuanRow ? (float) $saldoTujuanRow['sisasaldo'] : 0.0) + $jumlah;

            $stmtInsertMirror = $koneksi->prepare("INSERT INTO rekeningkoran
                                                    (idrk, iduser, tanggal, waktu, keterangan, kredit, debit, sisasaldo, buktitf, tipe, kategori_id, bank)
                                                    VALUES (NULL, ?, ?, ?, ?, ?, 0, ?, ?, ?, ?, ?)");
            $stmtInsertMirror->bind_param('isssddssss', $iduser, $tanggal, $waktu, $keterangan, $jumlah, $sisaTujuanBaru, $namaFileBaru, $pindahBank, $kategori, $bank);
            $stmtInsertMirror->execute();

            $stmtSaldoTujuanUpsert = $koneksi->prepare("INSERT INTO saldo_per_tipe (tipe, total_kredit, total_debit, sisasaldo, updated_at)
                                                            VALUES (?, ?, 0, ?, NOW())
                                                            ON DUPLICATE KEY UPDATE
                                                                total_kredit = total_kredit + VALUES(total_kredit),
                                                                sisasaldo    = VALUES(sisasaldo),
                                                                updated_at   = NOW()");
            $stmtSaldoTujuanUpsert->bind_param('sdd', $pindahBank, $jumlah, $sisaTujuanBaru);
            $stmtSaldoTujuanUpsert->execute();
        }

        $koneksi->commit();

        echo json_encode(['success' => true, 'message' => 'Berhasil menambahkan ' . $jenis]);
    } catch (Exception $e) {
        $koneksi->rollback();
        echo json_encode(['success' => false, 'message' => 'Gagal menambahkan ' . $jenis . ': ' . $e->getMessage()]);
    }
