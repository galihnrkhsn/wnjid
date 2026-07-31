-- =====================================================================
-- Schema: alamat tersimpan (address book), 1 user bisa punya banyak alamat
-- =====================================================================
--
-- Dirancang polymorphic (tipe_pemilik + id_pemilik) supaya nanti bisa dipakai
-- juga oleh mitra/distributor/agen, bukan cuma konsumen. Untuk sekarang cuma
-- konsumen yang mengisi/memakai tabel ini (tipe_pemilik = 'konsumen',
-- id_pemilik = konsumen.idkonsumen).
--
-- ID + nama wilayah (provinsi/kota/kecamatan) sengaja disimpan berdampingan,
-- sama seperti pola di orderkonsumen: ID dipakai untuk prefill dropdown
-- cascading & sebagai district id RajaOngkir, nama dipakai untuk tampilan
-- tanpa perlu join ulang ke tb_ro_*.
--
-- is_utama (alamat default) dijaga di level aplikasi (bukan constraint DB):
-- saat satu alamat dijadikan utama, alamat lain milik pemilik yang sama
-- di-unset dulu dalam satu transaction.
CREATE TABLE IF NOT EXISTS `alamat` (
    `idalamat`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tipe_pemilik`     VARCHAR(20)  NOT NULL DEFAULT 'konsumen',
    `id_pemilik`       INT UNSIGNED NOT NULL,
    `label`            VARCHAR(50)  NOT NULL DEFAULT 'Rumah',
    `nama_penerima`    VARCHAR(150) NOT NULL,
    `telepon_penerima` VARCHAR(20)  NOT NULL,
    `alamat_lengkap`   TEXT         NOT NULL,
    `provinsi_id`      INT UNSIGNED NOT NULL,
    `provinsi`         VARCHAR(100) NOT NULL,
    `kota_id`          INT UNSIGNED NOT NULL,
    `kota`             VARCHAR(100) NOT NULL,
    `kecamatan_id`     INT UNSIGNED NOT NULL,
    `kecamatan`        VARCHAR(100) NOT NULL,
    `kodepos`          VARCHAR(10)      DEFAULT NULL,
    `catatan`          TEXT             DEFAULT NULL,
    `is_utama`         TINYINT(1)   NOT NULL DEFAULT 0,
    `created_at`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`idalamat`),
    KEY `idx_alamat_pemilik` (`tipe_pemilik`, `id_pemilik`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
