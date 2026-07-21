<?php

require_once '../adminwnj/helpers/compress_img.php';
$sourceDir = "../distributor/foto/produk/";

$query = $koneksi->query("
SELECT 
v.id,
v.foto,
mf.id AS folder_id,
mf.name AS folder
FROM variants v
JOIN products p ON v.idproducts = p.id
JOIN master_folder mf ON mf.name = p.namaproduk
WHERE v.folder IS NULL
");

while ($row = $query->fetch_assoc()) {

    $variant_id = $row['id'];
    $foto       = $row['foto'];
    $folder     = $row['folder'];
    $folder_id  = $row['folder_id'];

    $sourcePath = $sourceDir . $foto;

    if (!file_exists($sourcePath)) {
        echo "File tidak ditemukan: $foto <br>";
        continue;
    }

    $folderSlug = strtolower(preg_replace('/[^a-zA-Z0-9]/','-', $folder));
    $destinationFolder = $sourceDir . $folderSlug . "/";

    if (!is_dir($destinationFolder)) {
        mkdir($destinationFolder, 0777, true);
    }

    $namaBaru = pathinfo($foto, PATHINFO_FILENAME) . ".webp";
    $destination = $destinationFolder . $namaBaru;

    $compressed = compressResizeImage($sourcePath, $destination, 75, 1200);

    if (!$compressed) {
        echo "Gagal compress: $foto <br>";
        continue;
    }

    // update DB
    $stmt = $koneksi->prepare("
        UPDATE variants 
        SET foto = ?, folder = ?
        WHERE id = ?
    ");

    $stmt->bind_param("sii", $namaBaru, $folder_id, $variant_id);
    $stmt->execute();

    // hapus file lama
    if (file_exists($sourcePath)) {
        unlink($sourcePath);
    }

    echo "Berhasil: $foto → $folderSlug/$namaBaru <br>";
}

echo "Migrasi selesai";