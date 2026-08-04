<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header('Content-Type: application/json');
    session_start();
    include '../../includes/db.php';

    if (!isset($_SESSION['administrator'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu']);
        exit();
    }

    $fotoId = (int) ($_POST['foto_id'] ?? 0);
    if ($fotoId <= 0) {
        echo json_encode(['success' => false, 'message' => 'foto_id tidak valid']);
        exit();
    }

    $stmt = $koneksi->prepare("SELECT fp.foto, mf.name AS nama_folder,
            (SELECT COUNT(*) FROM variants v WHERE v.foto = fp.id) AS dipakai
        FROM foto_produk fp
        LEFT JOIN master_folder mf ON fp.folder = mf.id
        WHERE fp.id = ?");
    $stmt->bind_param('i', $fotoId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$row) {
        echo json_encode(['success' => false, 'message' => 'Foto tidak ditemukan']);
        exit();
    }

    $stmtDelete = $koneksi->prepare("DELETE FROM foto_produk WHERE id = ?");
    $stmtDelete->bind_param('i', $fotoId);
    $stmtDelete->execute();
    $stmtDelete->close();
    // variants.foto -> foto_produk.id (ON DELETE SET NULL): variant yang pakai foto ini
    // otomatis lepas ikatan begitu baris foto_produk-nya dihapus, tidak perlu manual.

    if (!empty($row['foto'])) {
        $folderPath = !empty($row['nama_folder']) ? $row['nama_folder'] . '/' : '';
        $filePath = '../../image/produk/' . $folderPath . $row['foto'];
        if (is_file($filePath)) {
            @unlink($filePath);
        }
    }

    echo json_encode(['success' => true, 'dipakai' => (int) $row['dipakai']]);
