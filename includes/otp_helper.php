<?php
    require_once __DIR__ . '/mail_helper.php';

    if (!function_exists('kirimKodeVerifikasi')) {
        /**
         * Generate kode verifikasi 6 digit baru buat $iduser, simpan SHA-256 hash-nya
         * (bukan kode mentah) ke kolom verification_token, reset percobaan gagal &
         * cooldown, lalu kirim kode itu ke email user. Dipakai bareng oleh register.php
         * (kode pertama) dan resend_verifikasi.php/verifikasi_otp.php (kirim ulang).
         */
        function kirimKodeVerifikasi(mysqli $koneksi, int $iduser, string $email, string $nama): void
        {
            $kode     = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $kodeHash = hash('sha256', $kode);

            $stmt = $koneksi->prepare("UPDATE users
                                        SET verification_token = ?,
                                            verification_expires_at = DATE_ADD(NOW(), INTERVAL 10 MINUTE),
                                            verification_attempts = 0,
                                            verification_last_sent_at = NOW()
                                        WHERE id = ?");
            $stmt->bind_param('si', $kodeHash, $iduser);
            $stmt->execute();

            kirimEmailNotifikasi($email, $nama, 'Kode Verifikasi Akun WNJ.ID', emailTemplate('Verifikasi Email Kamu',
                '<p>Halo ' . htmlspecialchars($nama) . ',</p>'
                . '<p>Gunakan kode berikut untuk verifikasi email dan aktifkan akun kamu. Kode berlaku 10 menit.</p>'
                . '<p style="text-align:center; margin:24px 0;">'
                . '<span style="display:inline-block; background:#F5EEE4; color:#2F2A27; font-size:28px; font-weight:700; letter-spacing:8px; padding:14px 24px; border-radius:8px;">' . $kode . '</span>'
                . '</p>'
                . '<p style="font-size:12px; color:#6E655D;">Kalau kamu tidak merasa mendaftar di WNJ.ID, abaikan email ini.</p>'));
        }
    }
