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

    // Qrisly (pembayaran QRIS) - produk terpisah dari cek ongkir di atas, tapi
    // masih satu akun RajaOngkir Collaborator. Ambil dari dashboard > API Settings > Developer.
    'qrisly' => [
        'api_key'         => '',
        // Diisi otomatis oleh adminwnj/qrisly_setup.php setelah upload QRIS statis toko.
        'qris_id'         => '',
        // Opsional: kalau nanti Qrisly menyediakan secret khusus buat verifikasi webhook.
        // Dikosongkan dulu, webhook tetap aman karena selalu re-verifikasi ke payment-status.
        'callback_secret' => '',
    ],
];
