-- =====================================================================
-- Schema: public customer self-registration (register.php)
-- =====================================================================

-- `users` already exists in production (referenced throughout adminwnj/*
-- and index.php) but has no CREATE TABLE anywhere in the repo. Included
-- here as reference / for recreating it on a fresh environment.
-- Safe to run: IF NOT EXISTS won't touch the table if it's already there.
CREATE TABLE IF NOT EXISTS `users` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(150) NOT NULL,
    `email`      VARCHAR(150) NOT NULL,
    `password`   VARCHAR(255) NOT NULL,
    `role`       VARCHAR(30)  NOT NULL DEFAULT 'konsumen',
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- New table: one profile row per self-registered customer, mirroring
-- admin_mitra's shape but scoped to the public "konsumen" role.
-- Columns beyond nama/email/whatsapp are left NULL at signup time and are
-- meant to be completed later from a profile page (not built yet):
-- idcssales, kodeakses, telegram, facebook, instagram, alamat, provinsi,
-- kota, kecamatan, kodepos, titikkordinat, foto, mode.
CREATE TABLE IF NOT EXISTS `konsumen` (
    `idkonsumen`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `iduser`        INT UNSIGNED NOT NULL,
    `idcssales`     INT UNSIGNED     DEFAULT NULL,
    `kodeakses`     VARCHAR(50)      DEFAULT NULL,
    `email`         VARCHAR(150) NOT NULL,
    `namamitra`     VARCHAR(150) NOT NULL,
    `whatsapp`      VARCHAR(20)      DEFAULT NULL,
    `telegram`      VARCHAR(100)     DEFAULT NULL,
    `facebook`      VARCHAR(150)     DEFAULT NULL,
    `instagram`     VARCHAR(150)     DEFAULT NULL,
    `alamat`        TEXT             DEFAULT NULL,
    `provinsi`      VARCHAR(100)     DEFAULT NULL,
    `kota`          VARCHAR(100)     DEFAULT NULL,
    `kecamatan`     VARCHAR(100)     DEFAULT NULL,
    `kodepos`       VARCHAR(10)      DEFAULT NULL,
    `titikkordinat` VARCHAR(100)     DEFAULT NULL,
    `foto`          VARCHAR(255)     DEFAULT NULL,
    `mode`          TEXT             DEFAULT NULL,
    `status`        VARCHAR(30)  NOT NULL DEFAULT 'Aktif',
    `privateorder`  TINYINT(1)   NOT NULL DEFAULT 0,
    `tgl_daftar`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`idkonsumen`),
    UNIQUE KEY `uq_konsumen_iduser` (`iduser`),
    UNIQUE KEY `uq_konsumen_email` (`email`),
    KEY `idx_konsumen_idcssales` (`idcssales`),
    CONSTRAINT `fk_konsumen_users`
        FOREIGN KEY (`iduser`) REFERENCES `users` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
