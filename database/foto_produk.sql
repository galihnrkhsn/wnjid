-- =====================================================================
-- Pindahkan foto dari variants ke tabel foto_produk tersendiri.
-- variants.foto berubah dari nama file (string) jadi FK ke foto_produk.id.
-- variants.folder dihapus (foldernya sekarang cuma hidup di foto_produk).
-- =====================================================================

CREATE TABLE IF NOT EXISTS `foto_produk` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `idproduk`   INT NOT NULL,
    `folder`     VARCHAR(255) NULL,
    `foto`       VARCHAR(255) NOT NULL,
    `urutan`     INT NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_foto_produk_idproduk` (`idproduk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Backfill: satu baris foto_produk per kombinasi unik (idproducts, folder, foto)
-- yang sudah ada di variants.
INSERT INTO `foto_produk` (`idproduk`, `folder`, `foto`)
SELECT idproducts, folder, foto
FROM variants
WHERE foto IS NOT NULL AND foto <> ''
GROUP BY idproducts, folder, foto;

-- variants.foto: dari teks (nama file) jadi FK ke foto_produk.id
ALTER TABLE `variants` ADD COLUMN `foto_new` INT UNSIGNED NULL AFTER `foto`;

UPDATE `variants` v
JOIN `foto_produk` fp
  ON fp.idproduk = v.idproducts
 AND fp.folder <=> v.folder
 AND fp.foto = v.foto
SET v.foto_new = fp.id;

ALTER TABLE `variants` DROP COLUMN `foto`;
ALTER TABLE `variants` DROP COLUMN `folder`;
ALTER TABLE `variants` CHANGE COLUMN `foto_new` `foto` INT UNSIGNED NULL;

ALTER TABLE `variants`
    ADD CONSTRAINT `fk_variants_foto`
    FOREIGN KEY (`foto`) REFERENCES `foto_produk` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE;
