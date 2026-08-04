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

    $productId = (int) ($_POST['product_id'] ?? 0);
    $fotoId    = (int) ($_POST['foto_id'] ?? 0);
    $variants  = $_POST['variants'] ?? [];

    if ($productId <= 0 || $fotoId <= 0 || !is_array($variants) || empty($variants)) {
        echo json_encode(['success' => false, 'message' => 'Parameter tidak lengkap']);
        exit();
    }

    // Pastikan foto memang milik produk ini, supaya tidak salah pasang foto produk lain.
    $stmtCheck = $koneksi->prepare("SELECT id FROM foto_produk WHERE id = ? AND idproduk = ?");
    $stmtCheck->bind_param('ii', $fotoId, $productId);
    $stmtCheck->execute();
    if ($stmtCheck->get_result()->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Foto tidak ditemukan untuk produk ini']);
        exit();
    }
    $stmtCheck->close();

    $placeholders = implode(',', array_fill(0, count($variants), '?'));
    $types  = 'ii' . str_repeat('s', count($variants));
    $params = array_merge([$fotoId, $productId], array_values($variants));

    $stmt = $koneksi->prepare("UPDATE variants SET foto = ?, updated_at = NOW() WHERE idproducts = ? AND variant IN ($placeholders)");
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    echo json_encode(['success' => true, 'updated' => $affected]);
