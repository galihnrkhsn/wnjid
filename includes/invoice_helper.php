<?php
// Format invoice checkout: {tipe}{tanggal DDMMYY}{id mitra/konsumen}{acak 5 digit}.
// Acak di-generate lalu dicek unik ke tabel order terkait sebelum dipakai (retry kalau bentrok),
// supaya tetap "acak" tapi tidak mungkin duplikat.
function generateUniqueInvoice(mysqli $koneksi, string $tabel, string $tipe, $id): string
{
    $tanggal = date('dmy');
    $stmt = $koneksi->prepare("SELECT 1 FROM `$tabel` WHERE invoice = ? LIMIT 1");
    for ($percobaan = 0; $percobaan < 20; $percobaan++) {
        $acak = str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        $invoice = $tipe . $tanggal . $id . $acak;
        $stmt->bind_param('s', $invoice);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) {
            return $invoice;
        }
    }
    throw new RuntimeException('Gagal generate invoice unik, coba lagi');
}

// Pengganti substr($invoice, 0, 2) == "DP"/"AP" - cek langsung ke item invoice itu apa ada yang
// promo/sale, bukan nebak dari huruf di invoice (karena tipe sekarang cuma 1 huruf, tidak ada
// tempat lagi buat nyimpen penanda promo di string invoice-nya).
function invoiceHasPromoItem(mysqli $koneksi, string $orderTabel, string $invoice): bool
{
    $stmt = $koneksi->prepare("SELECT 1 FROM `$orderTabel`
                                INNER JOIN variants ON variants.id = `$orderTabel`.idproduk
                                WHERE `$orderTabel`.invoice = ?
                                AND (variants.jenis LIKE '%Promo%' OR variants.jenis LIKE '%Sale%')
                                LIMIT 1");
    $stmt->bind_param('s', $invoice);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}
