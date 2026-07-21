<?php
    header("Content-Type: application/json");
    include '../../includes/db.php';
    $idProduk = $_GET['id'];

    if (isset($idProduk)) {
        $masterProduk   = "SELECT * FROM products WHERE id = '$idProduk'";
    } else {
        $masterProduk   = "SELECT * FROM products";
    }
    $resultProduk   = $koneksi->query($masterProduk);

    $masterVariant  = "SELECT * FROM variants";
    $resultVariant  = $koneksi->query($masterVariant);
    $result = [];

    // Ubah hasil query menjadi array asosiatif
    $produkArray = [];
    while ($row = $resultProduk->fetch_assoc()) {
        $produkArray[] = $row;
    }

    $variantArray = [];
    while ($row = $resultVariant->fetch_assoc()) {
        $variantArray[] = $row;
    }

    // Grouping varian berdasarkan produk
    foreach ($produkArray as $produk) {
        foreach ($variantArray as $variant) {
            if ($variant['idproducts'] == $produk['id']) { // Pastikan varian sesuai dengan produk
                $result[$produk['namaproduk']]['ID']           = $produk['id'];
                $result[$produk['namaproduk']]['name']         = $produk['namaproduk'];
                $result[$produk['namaproduk']]['variant'][]    = $variant;
            }
        }
    }

    // Mengonversi hasil ke dalam array jika diperlukan
    $data = array_values($result);

    // Mengembalikan data dalam format JSON
    echo json_encode($data);
?>
