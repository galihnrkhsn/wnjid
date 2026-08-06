<?php
// Saldo dihitung live dari rekeningkoran/bill, bukan dari cache saldo_per_tipe lagi
// (cache-nya dulu tidak pernah konsisten di-update di semua alur, jadi sering basi).

const SALDO_MULAI_TANGGAL = '2026-01-01';

// Saldo kas per tipe (P/AF/M/O/MF, dst). Tipe 'O' sengaja dihitung all-time (tidak
// dibatasi SALDO_MULAI_TANGGAL) karena listing transaksinya di finance.php juga
// memperlakukan tipe 'O' begitu.
function hitungSaldoTipe(mysqli $koneksi, string $tipe): float
{
    $sql = "SELECT COALESCE(SUM(kredit), 0) - COALESCE(SUM(debit), 0) AS sisa
            FROM rekeningkoran
            WHERE tipe = ? AND deleted_at IS NULL";
    if ($tipe !== 'O') {
        $sql .= " AND tanggal >= '" . SALDO_MULAI_TANGGAL . "'";
    }
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('s', $tipe);
    $stmt->execute();
    return (float) ($stmt->get_result()->fetch_assoc()['sisa'] ?? 0);
}

// Sisa tagihan vendor (utang WNJ ke vendor) dari tabel `bill` - all-time, tidak
// dibatasi SALDO_MULAI_TANGGAL karena ini utang, bukan saldo kas berjalan.
function hitungSisaTagihanVendor(mysqli $koneksi, string $idvendor): float
{
    $stmt = $koneksi->prepare("SELECT COALESCE(SUM(tagihan), 0) - COALESCE(SUM(bayar), 0) AS sisa
                                FROM bill
                                WHERE vendor = ? AND deleted_at IS NULL");
    $stmt->bind_param('s', $idvendor);
    $stmt->execute();
    return (float) ($stmt->get_result()->fetch_assoc()['sisa'] ?? 0);
}
