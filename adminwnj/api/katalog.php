<?php
header("Access-Control-Allow-Origin: https://wlink.id");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

header('Content-Type: application/json');

include '../../includes/db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    ? "https://"
    : "http://";

$host = $_SERVER['HTTP_HOST'];

$query = "SELECT 
                pkategori.namakategori,
                products.namaproduk,
                products.spek,
                products.img,
                variants.foto,
                MIN(master_folder.name) AS folder_slug
            FROM products
            JOIN pkategori 
                ON products.idpkategori = pkategori.idpkategori
            LEFT JOIN variants
                ON variants.idproducts = products.id
            LEFT JOIN master_folder
                ON variants.folder = master_folder.id
            WHERE products.idkategori != 2
            GROUP BY products.id
            ORDER BY 
                pkategori.namakategori ASC,
                products.namaproduk ASC
    ";

$result = mysqli_query($koneksi, $query);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $folder     = $row['folder_slug'];
    $kategori   = $row['namakategori'];

    $foto = $protocol . $host .
        "/image/produk/" .
        $folder . "/" .
        $row['foto'];

    $data[$kategori][] = [
        'produk' => $row['namaproduk'],
        'spek'   => $row['spek'],
        'foto'   => $foto,
        'img'    => $row['img']
    ];
}

//
// PRIORITAS KATEGORI
//

$priority = ['Legging', 'Sarung'];

uksort($data, function ($a, $b) use ($priority) {

    $posA = array_search($a, $priority);
    $posB = array_search($b, $priority);

    if ($posA !== false && $posB !== false) {
        return $posA - $posB;
    }

    if ($posA !== false) return -1;

    if ($posB !== false) return 1;

    return strcasecmp($a, $b);
});

echo json_encode(
    $data,
    JSON_PRETTY_PRINT |
    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES
);