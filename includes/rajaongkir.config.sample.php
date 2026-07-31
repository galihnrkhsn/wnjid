<?php
/**
 * Salin file ini menjadi "rajaongkir.config.php" (di folder yang sama) lalu isi
 * API key asli dari Komerce/RajaOngkir. rajaongkir.config.php TIDAK ikut ke-commit
 * ke git (lihat .gitignore) — harus dibuat manual di setiap environment.
 */
return [
    // Beberapa key dicoba berurutan kalau salah satunya kena limit (HTTP 429)
    'api_keys' => [
        '',
    ],
    // ID district (kecamatan) asal pengiriman di tb_ro_subdistricts, mis. gudang/kantor pusat
    'origin_district_id' => 0,
];
