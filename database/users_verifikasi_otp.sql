-- Alur verifikasi email registrasi diganti dari link ke kode 6 digit.
-- Kolom verification_token (varchar(64)) dipakai ulang untuk menyimpan SHA-256 hash
-- dari kode 6 digit (bukan token link mentah lagi) - muat pas karena hash sha256 = 64 hex char.
-- verify_email.php (alur link lama) tetap dibiarkan jalan apa adanya untuk user yang
-- sudah kadung menerima email link sebelum perubahan ini.
ALTER TABLE users
    ADD COLUMN verification_expires_at DATETIME NULL AFTER verification_token,
    ADD COLUMN verification_attempts TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER verification_expires_at,
    ADD COLUMN verification_last_sent_at DATETIME NULL AFTER verification_attempts;
