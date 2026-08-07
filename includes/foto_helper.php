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

    // Foto utk 1 variant (dipakai konsumen/detail.php & riwayat_pesanan.php buat nampilin
    // foto barang yang dipesan): utamakan foto variant itu sendiri, kalau variant itu tidak
    // punya foto pakai foto variant lain dari produk yang sama, terakhir nophoto.png.
    if (!function_exists('fotoUntukVariant')) {
        function fotoUntukVariant(mysqli $koneksi, ?int $idvariant, string $checkBasePath = '../image/produk/', ?string $urlBasePath = null): string
        {
            if (empty($idvariant)) {
                return fotoProdukSrc(null, null, $checkBasePath, $urlBasePath);
            }

            $stmtOwn = $koneksi->prepare("SELECT v.idproducts, fp.foto AS foto_file, mf.name AS nama_folder
                                            FROM variants v
                                            LEFT JOIN foto_produk fp ON fp.id = v.foto
                                            LEFT JOIN master_folder mf ON mf.id = fp.folder
                                            WHERE v.id = ?");
            $stmtOwn->bind_param('i', $idvariant);
            $stmtOwn->execute();
            $own = $stmtOwn->get_result()->fetch_assoc();

            $fotoFile   = $own['foto_file'] ?? null;
            $namaFolder = $own['nama_folder'] ?? null;

            if (empty($fotoFile) && !empty($own['idproducts'])) {
                $stmtFallback = $koneksi->prepare("SELECT fp.foto AS foto_file, mf.name AS nama_folder
                                                    FROM variants v2
                                                    JOIN foto_produk fp ON fp.id = v2.foto
                                                    LEFT JOIN master_folder mf ON mf.id = fp.folder
                                                    WHERE v2.idproducts = ? AND v2.foto IS NOT NULL
                                                    ORDER BY v2.id ASC LIMIT 1");
                $stmtFallback->bind_param('i', $own['idproducts']);
                $stmtFallback->execute();
                $fallback = $stmtFallback->get_result()->fetch_assoc();
                if ($fallback) {
                    $fotoFile   = $fallback['foto_file'];
                    $namaFolder = $fallback['nama_folder'];
                }
            }

            return fotoProdukSrc($namaFolder, $fotoFile, $checkBasePath, $urlBasePath);
        }
    }
