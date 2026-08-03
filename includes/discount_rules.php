<?php
// Aturan bisnis diskon per-kategori & tier biaya dropship untuk halaman detail order
// distributor. Dipusatkan di sini (bukan hardcode di tiap file detailorder*) supaya
// bisa dipakai ulang - lihat distributor/detailorderb2.php untuk contoh pemakaian.

// idkategori (tabel kategori: D5/D10/.../D25) => persen diskon tambahan
$categoryDiscountRules = [
    5  => 5,
    10 => 10,
    15 => 15,
    17 => 17,
    20 => 20,
    25 => 25,
];

// Tier biaya dropship: [berat min gram, berat max gram, biaya Rp]. Band diambil persis
// dari kondisi if/elseif yang sudah ada (termasuk celah antar band, mis. 5001-5999 gram
// tidak masuk band manapun) - bukan angka baru, cuma dipindah dari kode ke data.
$dropshipFeeTiers = [
    [0, 5000, 3000],
    [6000, 10000, 5000],
    [11000, 20000, 10000],
    [21000, 30000, 15000],
    [31000, 40000, 20000],
    [41000, 50000, 25000],
    [51000, 60000, 30000],
    [61000, 70000, 35000],
    [71000, 80000, 40000],
    [81000, 90000, 45000],
    [91000, 100000, 50000],
    [101000, 110000, 55000],
    [111000, 120000, 60000],
    [121000, 130000, 65000],
    [131000, 140000, 70000],
    [141000, 150000, 75000],
    [151000, 160000, 80000],
    [161000, 170000, 85000],
    [171000, 180000, 90000],
    [181000, 190000, 95000],
    [191000, 200000, 100000],
];
