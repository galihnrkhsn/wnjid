<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require 'koneksi.php';
    require_once '../includes/foto_helper.php';

    $product_id = (int) $_GET['product_id'];

    $stmt = $koneksi->prepare("SELECT fp.id, fp.foto, mf.name AS nama_folder
                                FROM foto_produk fp
                                LEFT JOIN master_folder mf ON fp.folder = mf.id
                                WHERE fp.idproduk = ?
                                ORDER BY fp.urutan ASC, fp.id ASC");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if (!$rows) {
        echo "Tidak ada foto";
        exit;
    }

    foreach ($rows as $row) {
        $src = fotoProdukSrc($row['nama_folder'] ?? null, $row['foto'] ?? null);

        echo "<img
            src='" . htmlspecialchars($src) . "'
            class='foto-item'
            data-id='" . (int) $row['id'] . "'
            data-nama='" . htmlspecialchars($row['foto']) . "'
        >";
    }