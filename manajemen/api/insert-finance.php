<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header('Content-Type: application/json');
    date_default_timezone_set('Asia/Jakarta');
    require_once '../../includes/db.php';

    $waktu          = date('H:i:s');
    $keterangan     = addslashes(htmlspecialchars($_POST['keterangan'] ?? ''));
    $tipe           = $_POST['tipe'] ?? null;
    $tanggal        = $_POST['tanggal'] ?? null;
    $jumlah         = isset($_POST['jumlah']) ? floatval($_POST['jumlah']) : 0;
    $jenis          = $_POST['jenis_transaksi'] ?? null;
    $kategori       = $_POST['kategori'] ?? null;
    $iduser         = $_POST['iduser'] ?? null;
    $bank           = $_POST['bank'] ?? null;

    if (!$iduser) {
        echo json_encode([
            'success' => false,
            'message' => 'User tidak terdeteksi!'
        ]);
        exit;
    }

    if (!$tipe || !$tanggal || !$keterangan || !$jumlah || !$kategori) {
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak lengkap!'
        ]);
        exit;
    }

    $ambil          = $koneksi->query("SELECT sisasaldo as sisa FROM saldo_per_tipe WHERE tipe = '$tipe'");

    if (!$ambil) {
        echo json_encode([
            'success' => false,
            'message' => 'Data saldo tidak terdeteksi!'
        ]);
        exit;
    }

    $sisaAwal       = (float) ($ambil->num_rows > 0) ? $ambil->fetch_assoc()['sisa'] : 0;
    $sisaBaru       = ($jenis == 'kredit') ? ($sisaAwal + $jumlah) : ($sisaAwal - $jumlah);

    $targetDir      = '../buktitransfer/';
    $namaFileBaru   = NULL;
    $hasFile = isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK;

    if ($hasFile && $tipe !== 'MF') {
        $foto           = $_FILES['foto']['name'];
        $tmp            = $_FILES['foto']['tmp_name'];
        $ukuranFile     = $_FILES['foto']['size'];
        $errorFile      = $_FILES['foto']['error'];

        if (!isset($_FILES['foto']) || $errorFile !== UPLOAD_ERR_OK) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal mengupload file: ' . $errorFile
            ]);
            exit;
        }

        $ekstensiGambarValid    = ['jpg', 'jpeg', 'png', 'svg'];
        $ekstensiGambar         = explode('.', $foto);
        $ekstensiGambar         = strtolower(end($ekstensiGambar));

        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            echo json_encode([
                'success' => false,
                'message' => 'Yang anda upload bukan Gambar'
            ]);
            exit;
        }

        $ukuranMaksimal = 2 * 1024 * 1024;
        if ($ukuranFile > $ukuranMaksimal) {
            echo json_encode([
                'success' => false,
                'message' => 'Ukuran file terlalu besar!'
            ]);
            exit;
        }

        $today          = date('His');
        $tglsekarang    = date('ymd');
        $namaFileBaru   = 'K' . $tipe . $tglsekarang . $today . '.' . $ekstensiGambar;

        if (!is_dir($targetDir) || !is_writable($targetDir)) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal mengirim foto ke directory'
            ]);
            exit;
        }

        if (!move_uploaded_file($tmp, $targetDir . $namaFileBaru)) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memindahkan File'
            ]);
            exit;
        }
    }

    $kredit = $jenis == 'kredit' ? $jumlah : 0;
    $debit  = $jenis == 'debit' ? $jumlah : 0;

    if (!in_array($jenis, ['kredit', 'debit'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Jenis Transaksi tidak valid!'
        ]);
        exit;
    }

    $query      = "INSERT INTO rekeningkoran (idrk, iduser, tanggal, waktu, keterangan, kredit, debit, sisasaldo, buktitf, tipe, kategori_id, bank)
                    VALUES (NULL, '$iduser', '$tanggal', '$waktu', '$keterangan', '$kredit', $debit, 0, " . 
                    ($namaFileBaru ? "'$namaFileBaru'" : "NULL") . ", '$tipe', '$kategori', '$bank')
                ";
    $insert     = $koneksi->query($query);

    if ($insert) {
        echo json_encode([
            'success' => true,
            'message' => 'Berhasil menambahkan ' . $jenis
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menambahkan Kredit:' . $koneksi->error 
        ]);
    }
?>