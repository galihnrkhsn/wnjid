<?php
    // Bitmask untuk products.access: menentukan mitra mana saja yang boleh melihat
    // produk ini. access = 0 (atau NULL) berarti tidak ada pembatasan sama sekali
    // (semua mitra bisa akses) - ini default lama sebelum fitur pembatasan ada,
    // jadi data existing otomatis tetap "terbuka untuk semua".
    if (!defined('ACCESS_DISTRIBUTOR')) {
        define('ACCESS_DISTRIBUTOR', 1);
        define('ACCESS_AGEN', 2);
        define('ACCESS_RESELLER', 4);
        define('ACCESS_MARKETER', 8);
        define('ACCESS_KONSUMEN', 16);
    }

    if (!function_exists('produkBisaDiaksesOleh')) {
        function produkBisaDiaksesOleh(?int $access, int $bitMitra): bool
        {
            return empty($access) || (($access & $bitMitra) !== 0);
        }
    }
