<?php
    // Path foto produk relatif terhadap direktori pemanggil (mis. distributor/, agen/, reseller/,
    // marketer/, konsumen/ — semua satu level di bawah root, sama seperti includes/db.php).
    // Dipakai untuk <img src> maupun pengecekan file_exists sebelum menampilkan foto asli.
    if (!function_exists('fotoProdukSrc')) {
        function fotoProdukSrc(?string $namaFolder, ?string $foto): string
        {
            if (!empty($foto)) {
                // Sebagian tabel produk lama tidak punya subfolder (langsung di image/produk/<foto>)
                $folderPath = !empty($namaFolder) ? $namaFolder . '/' : '';
                $filePath   = '../image/produk/' . $folderPath . $foto;

                if (file_exists($filePath)) {
                    $folderUrl = !empty($namaFolder) ? rawurlencode($namaFolder) . '/' : '';
                    return '../image/produk/' . $folderUrl . rawurlencode($foto);
                }
            }

            return '../image/produk/nophoto.png';
        }
    }
