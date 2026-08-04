<?php
    // Path foto produk relatif terhadap direktori pemanggil (mis. distributor/, agen/, reseller/,
    // marketer/, konsumen/ — semua satu level di bawah root, sama seperti includes/db.php).
    // Dipakai untuk <img src> maupun pengecekan file_exists sebelum menampilkan foto asli.
    if (!function_exists('fotoProdukSrc')) {
        // $checkBasePath: dipakai untuk file_exists(), harus relatif terhadap lokasi SCRIPT yang
        // memanggil fungsi ini di disk (default satu level di bawah root).
        // $urlBasePath: dipakai untuk string URL yang dikembalikan, harus relatif terhadap HALAMAN
        // yang nanti me-render <img src>-nya. Keduanya SAMA untuk pemanggil biasa (script dan
        // halaman yang me-render ada di file/kedalaman yang sama), tapi endpoint JSON yang lebih
        // dalam dari halaman pemakainya (mis. adminwnj/api/*.php dipakai oleh adminwnj/foto_produk.php)
        // harus isi keduanya secara terpisah, contoh:
        // fotoProdukSrc($folder, $foto, '../../image/produk/', '../image/produk/').
        function fotoProdukSrc(?string $namaFolder, ?string $foto, string $checkBasePath = '../image/produk/', ?string $urlBasePath = null): string
        {
            $urlBasePath = $urlBasePath ?? $checkBasePath;

            if (!empty($foto)) {
                // Sebagian tabel produk lama tidak punya subfolder (langsung di image/produk/<foto>)
                $folderPath = !empty($namaFolder) ? $namaFolder . '/' : '';
                $filePath   = $checkBasePath . $folderPath . $foto;

                if (file_exists($filePath)) {
                    $folderUrl = !empty($namaFolder) ? rawurlencode($namaFolder) . '/' : '';
                    return $urlBasePath . $folderUrl . rawurlencode($foto);
                }
            }

            return $urlBasePath . 'nophoto.png';
        }
    }
