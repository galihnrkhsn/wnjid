<?php
// Label badge promo dari variants.jenis. Kolom jenis dipakai serbaguna (ada juga nilai jenis
// garmen seperti "gamis"/"koko" di dalamnya) - cuma nilai yang benar-benar dikenal sebagai
// penanda promo/bundling yang dipetakan ke badge, sisanya diabaikan (return null).
function promoBadgeLabel(?string $jenis): ?string
{
    if ($jenis === null || $jenis === '') {
        return null;
    }
    if ($jenis === 'b1g1') {
        return 'B1G1';
    }
    if ($jenis === 'GB') {
        return 'Grade B';
    }
    if (stripos($jenis, 'bundling') !== false) {
        return 'Bundling';
    }
    if (stripos($jenis, 'flash') !== false) {
        return 'Flash Sale';
    }
    if (stripos($jenis, 'promo') !== false) {
        return 'Promo';
    }
    if (stripos($jenis, 'sale') !== false) {
        return 'Sale';
    }
    return null;
}

// Versi baru (berbasis master_jenis_products.nama + deskripsi) - dipakai konsumen/ supaya
// nama badge & aturan promo bisa diatur admin (adminwnj/master_jenis.php), bukan hardcode
// stripos() seperti promoBadgeLabel() di atas (yang TETAP dipertahankan apa adanya karena
// masih dipakai langsung oleh distributor/agen/mitra lain).
function promoInfo(mysqli $koneksi, ?string $jenis): ?array
{
    if ($jenis === null || $jenis === '') {
        return null;
    }
    $stmt = $koneksi->prepare("SELECT nama, deskripsi FROM master_jenis_products WHERE nama_jenis = ?");
    $stmt->bind_param('s', $jenis);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row || $row['nama'] === null || $row['nama'] === '') {
        return null;
    }
    return $row;
}

// Untuk baris yang mewakili beberapa variant sekaligus (mis. 1 produk di listing konsumen) -
// $jenisList adalah string hasil GROUP_CONCAT(DISTINCT jenis), ambil badge pertama yang cocok.
function promoBadgeLabelFromList(?string $jenisList): ?string
{
    if ($jenisList === null || $jenisList === '') {
        return null;
    }
    foreach (explode(',', $jenisList) as $jenis) {
        $label = promoBadgeLabel($jenis);
        if ($label !== null) {
            return $label;
        }
    }
    return null;
}

// Harga setelah diskon disc% (dibulatkan ke rupiah terdekat). disc null/0 -> harga apa adanya.
function hargaSetelahDisc(int $harga, ?int $disc): int
{
    if ($disc === null || $disc <= 0) {
        return $harga;
    }
    return (int) round($harga * (1 - $disc / 100));
}

// Label badge "-X%" dari disc, null kalau tidak ada diskon.
function discBadgeLabel(?int $disc): ?string
{
    return ($disc !== null && $disc > 0) ? "-{$disc}%" : null;
}
