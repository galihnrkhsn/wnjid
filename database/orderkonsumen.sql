-- =====================================================================
-- Checkout untuk konsumen. Alurnya meniru distributor (keranjang -> order
-- -> alamat -> ringkasan -> pembayaran manual), tapi dengan perbaikan:
--
-- 1. Header + detail terpisah (orderkonsumen vs orderkonsumen_detail).
--    Punya distributor (`ordermitra`) menyatukan header & baris produk jadi
--    satu tabel: satu invoice = banyak baris dengan status/alamat yang
--    diulang persis di semua baris itu. Kalau update sebagian gagal,
--    baris-baris itu bisa "terpecah" (status beda-beda padahal 1 invoice).
--    Di sini, status/alamat/total cuma ada SEKALI di header.
--
-- 2. Snapshot nama produk & varian di detail (bukan cuma nyimpen id lalu
--    JOIN ke variants/products tiap kali). Kalau produknya diganti nama
--    atau dihapus di kemudian hari, histori pesanan lama tetap utuh.
--
-- 3. Invoice dibangun dari idorder (auto increment), bukan dari
--    timestamp per-detik seperti punya distributor (`D<idmitra><mdHis>`)
--    yang secara teori bisa tabrakan kalau 2 checkout dari mitra yang
--    sama persis di detik yang sama. Formatnya: K + YYMMDD + idorder
--    5 digit, contoh: K2607290001 — dijamin unik karena nempel ke PK.
--
-- 4. Status order & status pembayaran dipisah jadi 2 kolom dengan nilai
--    yang jelas menandai tahap alur (Menunggu Alamat -> Menunggu
--    Pembayaran -> Menunggu Konfirmasi Admin -> Diproses -> Selesai,
--    atau Dibatalkan), dipakai langsung sebagai penentu halaman/aksi
--    mana yang ditampilkan di detail.php.
-- =====================================================================

CREATE TABLE IF NOT EXISTS `orderkonsumen` (
    `idorder`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice`          VARCHAR(30) NOT NULL,
    `idkonsumen`       INT UNSIGNED NOT NULL,
    `nama_penerima`    VARCHAR(150) NOT NULL DEFAULT '',
    `telepon_penerima` VARCHAR(20) NOT NULL DEFAULT '',
    `alamat_lengkap`   TEXT NULL,
    `provinsi`         VARCHAR(100) NULL,
    `kota`             VARCHAR(100) NULL,
    `kecamatan`        VARCHAR(100) NULL,
    `kodepos`          VARCHAR(10) NULL,
    `ekspedisi`        VARCHAR(50) NULL,
    `layanan`          VARCHAR(50) NULL,
    `catatan`          TEXT NULL,
    `berat`            INT UNSIGNED NOT NULL DEFAULT 0,
    `subtotal`         DECIMAL(12,2) NOT NULL DEFAULT 0,
    `ongkir`           DECIMAL(12,2) NOT NULL DEFAULT 0,
    `total`            DECIMAL(12,2) NOT NULL DEFAULT 0,
    `status`           VARCHAR(30) NOT NULL DEFAULT 'Menunggu Alamat',
    `payment_status`   VARCHAR(30) NOT NULL DEFAULT 'Belum Bayar',
    `tgl`              DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`idorder`),
    UNIQUE KEY `uq_orderkonsumen_invoice` (`invoice`),
    KEY `idx_orderkonsumen_idkonsumen` (`idkonsumen`),
    CONSTRAINT `fk_orderkonsumen_konsumen`
        FOREIGN KEY (`idkonsumen`) REFERENCES `konsumen` (`idkonsumen`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `orderkonsumen_detail` (
    `iddetail`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `idorder`    INT UNSIGNED NOT NULL,
    `idvariant`  INT NOT NULL,
    `namaproduk` VARCHAR(255) NOT NULL,
    `variant`    VARCHAR(255) NULL,
    `size`       VARCHAR(255) NULL,
    `harga`      DECIMAL(12,2) NOT NULL,
    `jumlah`     INT UNSIGNED NOT NULL,
    `subtotal`   DECIMAL(12,2) NOT NULL,
    PRIMARY KEY (`iddetail`),
    KEY `idx_orderkonsumen_detail_idorder` (`idorder`),
    CONSTRAINT `fk_orderkonsumen_detail_order`
        FOREIGN KEY (`idorder`) REFERENCES `orderkonsumen` (`idorder`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `orderkonsumen_pembayaran` (
    `idpembayaran`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `idorder`           INT UNSIGNED NOT NULL,
    `bank_pengirim`     VARCHAR(100) NULL,
    `rekening_pengirim` VARCHAR(100) NULL,
    `jumlah_transfer`   DECIMAL(12,2) NOT NULL,
    `foto`              VARCHAR(255) NOT NULL,
    `tgl`               DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`idpembayaran`),
    KEY `idx_orderkonsumen_pembayaran_idorder` (`idorder`),
    CONSTRAINT `fk_orderkonsumen_pembayaran_order`
        FOREIGN KEY (`idorder`) REFERENCES `orderkonsumen` (`idorder`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
