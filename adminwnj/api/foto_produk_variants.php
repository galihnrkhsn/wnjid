<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header('Content-Type: application/json');
    session_start();
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

    // Digrup per nama variant (satu variant biasanya punya beberapa baris size,
    // dan biasanya semua size dari 1 variant pakai foto yang sama).
    $stmt = $koneksi->prepare("
        SELECT v.variant,
               COUNT(*) AS jumlah_size,
               COUNT(DISTINCT COALESCE(v.foto, 0)) AS distinct_foto,
               MIN(v.foto) AS foto_id
        FROM variants v
        WHERE v.idproducts = ?
        GROUP BY v.variant
        ORDER BY v.variant ASC
    ");
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Ambil url foto representatif sekaligus (hindari query per baris / N+1).
    $fotoIds = array_values(array_unique(array_filter(array_column($rows, 'foto_id'))));
    $fotoMap = [];
    if ($fotoIds) {
        $placeholders = implode(',', array_fill(0, count($fotoIds), '?'));
        $types = str_repeat('i', count($fotoIds));
        $stmtFoto = $koneksi->prepare("SELECT fp.id, fp.foto, mf.name AS nama_folder
                                        FROM foto_produk fp
                                        LEFT JOIN master_folder mf ON fp.folder = mf.id
                                        WHERE fp.id IN ($placeholders)");
        $stmtFoto->bind_param($types, ...$fotoIds);
        $stmtFoto->execute();
        foreach ($stmtFoto->get_result()->fetch_all(MYSQLI_ASSOC) as $f) {
            $fotoMap[$f['id']] = fotoProdukSrc($f['nama_folder'] ?? null, $f['foto'] ?? null, '../../image/produk/', '../image/produk/');
        }
        $stmtFoto->close();
    }

    $data = [];
    foreach ($rows as $r) {
        $fotoId = $r['foto_id'] !== null ? (int) $r['foto_id'] : null;
        $data[] = [
            'variant'     => $r['variant'],
            'jumlah_size' => (int) $r['jumlah_size'],
            'foto_id'     => $fotoId,
            'foto_url'    => ($fotoId && isset($fotoMap[$fotoId])) ? $fotoMap[$fotoId] : null,
            'konsisten'   => (int) $r['distinct_foto'] <= 1,
        ];
    }

    echo json_encode(['success' => true, 'data' => $data]);
