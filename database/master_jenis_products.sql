-- =====================================================================
-- Master daftar "Jenis" produk (dipakai variants.jenis), dipakai sebagai
-- kamus autocomplete di form tambah produk supaya penulisan konsisten
-- (mis. tidak ada typo "Bundlng 3" vs "Bundling 3") - sama seperti pola
-- master_size untuk variants.size.
-- Diseed dari nilai variants.jenis yang sudah ada di data live.
-- =====================================================================

CREATE TABLE IF NOT EXISTS `master_jenis_products` (
    `id`         INT NOT NULL AUTO_INCREMENT,
    `nama_jenis` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `nama_jenis` (`nama_jenis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `master_jenis_products` (`nama_jenis`) VALUES
    ('gamis'), ('koko'), ('flash'), ('b1g1'), ('GB'), ('Set'),
    ('Bundling 5'), ('Bundling Short'), ('Izmir'), ('Bundling 3');
