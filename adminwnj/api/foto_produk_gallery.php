<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header('Content-Type: application/json');
    session_start();
    include __DIR__ . '/../access_guard.php';
    include '../../includes/db.php';
    require_once '../../includes/foto_helper.php';

    if (!isset($_SESSION['administrator'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu']);
        exit();
    }

    $productId = (int) ($_GET['product_id'] ?? 0);
    if ($productId <= 0) {
        echo json_encode(['success' => false, 'message' => 'product_id tidak valid']);
        exit();
    }

    $stmt = $koneksi->prepare("SELECT fp.id, fp.foto, fp.urutan, mf.name AS nama_folder,
            (SELECT COUNT(*) FROM variants v WHERE v.foto = fp.id) AS dipakai
        FROM foto_produk fp
        LEFT JOIN master_folder mf ON fp.folder = mf.id
        WHERE fp.idproduk = ?
        ORDER BY fp.urutan ASC, fp.id ASC");
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $data = [];
    foreach ($rows as $r) {
        $data[] = [
            'id'      => (int) $r['id'],
            'url'     => fotoProdukSrc($r['nama_folder'] ?? null, $r['foto'] ?? null, '../../image/produk/', '../image/produk/'),
            'nama'    => $r['foto'],
            'dipakai' => (int) $r['dipakai'],
        ];
    }

    echo json_encode(['success' => true, 'data' => $data]);
