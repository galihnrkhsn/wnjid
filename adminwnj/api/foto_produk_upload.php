<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header('Content-Type: application/json');
    session_start();
    include '../../includes/db.php';
    require_once '../../includes/foto_helper.php';
    require_once '../helpers/compress_img.php';
    require_once '../helpers/slugify.php';

    if (!isset($_SESSION['administrator'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu']);
        exit();
    }

    $productId = (int) ($_POST['product_id'] ?? 0);
    if ($productId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Produk tidak valid']);
        exit();
    }

    if (empty($_FILES['foto']['name'])) {
        echo json_encode(['success' => false, 'message' => 'Tidak ada file yang diupload']);
        exit();
    }

    $stmtProduk = $koneksi->prepare("SELECT namaproduk FROM products WHERE id = ?");
    $stmtProduk->bind_param('i', $productId);
    $stmtProduk->execute();
    $produkRow = $stmtProduk->get_result()->fetch_assoc();
    $stmtProduk->close();

    if (!$produkRow) {
        echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
        exit();
    }

    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
        echo json_encode(['success' => false, 'message' => 'Format gambar tidak didukung (jpg/jpeg/png/webp)']);
        exit();
    }

    $f = slugify(trim($produkRow['namaproduk']));
    $folderPath = "../../image/produk/" . $f;
    if (!is_dir($folderPath)) {
        mkdir($folderPath, 0777, true);
    }

    $stmtFold = $koneksi->prepare("SELECT id FROM master_folder WHERE name = ?");
    $stmtFold->bind_param('s', $f);
    $stmtFold->execute();
    $result = $stmtFold->get_result();

    if ($result->num_rows > 0) {
        $folder_id = $result->fetch_assoc()['id'];
    } else {
        $insertFold = $koneksi->prepare("INSERT INTO master_folder (name, created_at) VALUES (?, NOW())");
        $insertFold->bind_param('s', $f);
        $insertFold->execute();
        $folder_id = $koneksi->insert_id;
        $insertFold->close();
    }
    $stmtFold->close();

    $namaFileBaru = slugify($f . '-' . uniqid()) . '.webp';
    $uploadPath = "../../image/produk/" . $f . '/' . $namaFileBaru;

    $compressed = compressResizeImage($_FILES['foto']['tmp_name'], $uploadPath, 75, 1200);
    if (!$compressed) {
        echo json_encode(['success' => false, 'message' => 'Gagal memproses gambar']);
        exit();
    }

    $stmtUrutan = $koneksi->prepare("SELECT COALESCE(MAX(urutan), -1) + 1 AS next_urutan FROM foto_produk WHERE idproduk = ?");
    $stmtUrutan->bind_param('i', $productId);
    $stmtUrutan->execute();
    $nextUrutan = (int) $stmtUrutan->get_result()->fetch_assoc()['next_urutan'];
    $stmtUrutan->close();

    $stmtInsert = $koneksi->prepare("INSERT INTO foto_produk (idproduk, folder, foto, urutan, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmtInsert->bind_param('iisi', $productId, $folder_id, $namaFileBaru, $nextUrutan);
    $stmtInsert->execute();
    $fotoId = $koneksi->insert_id;
    $stmtInsert->close();

    echo json_encode([
        'success' => true,
        'data' => [
            'id'      => $fotoId,
            'url'     => fotoProdukSrc($f, $namaFileBaru, '../../image/produk/', '../image/produk/'),
            'nama'    => $namaFileBaru,
            'dipakai' => 0,
        ],
    ]);
