<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header('Content-Type: application/json');
    session_start();
    include __DIR__ . '/../access_guard.php';
    include '../../includes/db.php';

    if (!isset($_SESSION['administrator'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu']);
        exit();
    }

    $keyword = trim($_GET['q'] ?? '');
    if ($keyword === '') {
        echo json_encode(['success' => true, 'data' => []]);
        exit();
    }

    $like = '%' . $keyword . '%';
    $stmt = $koneksi->prepare("SELECT v.id, v.variant, v.size, v.harga, v.berat, v.disc, v.stock, p.namaproduk
        FROM variants v
        INNER JOIN products p ON p.id = v.idproducts
        WHERE v.stock > 0
          AND (p.namaproduk LIKE ? OR v.variant LIKE ? OR v.size LIKE ?)
        ORDER BY p.namaproduk ASC, v.variant ASC, v.size ASC
        LIMIT 30");
    $stmt->bind_param('sss', $like, $like, $like);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $data = [];
    foreach ($rows as $r) {
        $data[] = [
            'id'         => (int) $r['id'],
            'namaproduk' => $r['namaproduk'],
            'variant'    => $r['variant'],
            'size'       => $r['size'],
            'harga'      => (int) $r['harga'],
            'berat'      => (int) $r['berat'],
            'disc'       => (int) $r['disc'],
            'stock'      => (int) $r['stock'],
        ];
    }

    echo json_encode(['success' => true, 'data' => $data]);
