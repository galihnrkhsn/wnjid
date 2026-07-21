<?php
    header('Content-Type: application/json');
    error_reporting(E_ALL);
    date_default_timezone_set('Asia/Jakarta');
    require_once '../../includes/db.php';


    $rawInput = file_get_contents("php://input");
    $input = json_decode($rawInput, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid JSON input!'
        ]);
        exit;
    }

    $tanggal            = $input['tanggal'] ?? NULL;
    $rekening           = $input['rekening'] ?? NULL;
    $nominal            = $input['nominal'] ?? NULL;
    $keterangan         = $input['keterangan'] ?? NULL;

    if (!$tanggal || !$rekening || !$nominal) {
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak lengkap!'
        ]);
        exit;
    }

    $tanggal            = trim($tanggal);
    $rekening           = trim($rekening);
    $keterangan         = trim($keterangan);
    $nominal            = (int) $nominal;
    $waktu              = date('H:i:s');


    $stmt               = $koneksi->prepare("INSERT INTO catatan (tanggal, rekening, nominal, ket, waktu, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssiss", $tanggal, $rekening, $nominal, $keterangan, $waktu);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $koneksi->error
        ]);
    }
?>