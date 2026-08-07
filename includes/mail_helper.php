<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception as PHPMailerException;

    if (!function_exists('kirimEmailNotifikasi')) {
        /**
         * Kirim email lewat SMTP (kredensial di mail.config.php, gitignored - lihat
         * mail.config.sample.php). Kegagalan di-log lewat error_log() dan return false,
         * TIDAK melempar exception ke pemanggil - supaya SMTP down/salah kredensial tidak
         * pernah menggagalkan alur utama (mis. update status order tetap harus jalan
         * walau email notifikasinya gagal terkirim).
         */
        function kirimEmailNotifikasi(string $to, string $namaPenerima, string $subject, string $bodyHtml): bool
        {
            $configFile = __DIR__ . '/mail.config.php';
            if (!file_exists($configFile) || $to === '') {
                return false;
            }
            $config = require $configFile;

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = $config['host'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $config['username'];
                $mail->Password   = $config['password'];
                $mail->SMTPSecure = $config['encryption'] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = (int) $config['port'];
                $mail->CharSet    = 'UTF-8';

                $mail->setFrom($config['from_email'], $config['from_name']);
                $mail->addAddress($to, $namaPenerima);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $bodyHtml;
                $mail->AltBody = trim(strip_tags($bodyHtml));

                $mail->send();
                return true;
            } catch (PHPMailerException $e) {
                error_log('Gagal kirim email ke ' . $to . ': ' . $mail->ErrorInfo);
                return false;
            }
        }
    }

    if (!function_exists('emailTemplate')) {
        // Bungkus isi email dengan layout kartu sederhana bertema Wanoja, supaya semua
        // titik pengiriman (konfirmasi order, pembayaran, resi, dll) tampilannya konsisten
        // tanpa perlu tulis ulang HTML boilerplate tiap kali.
        function emailTemplate(string $judul, string $isiHtml): string
        {
            return '
                <div style="font-family: Arial, Helvetica, sans-serif; background:#FCFAF7; padding:24px;">
                    <div style="max-width:520px; margin:0 auto; background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,.06);">
                        <div style="background:#C67C4E; padding:20px 24px;">
                            <span style="color:#ffffff; font-size:20px; font-weight:700;">WNJ.ID</span>
                        </div>
                        <div style="padding:24px; color:#2F2A27;">
                            <h2 style="margin:0 0 12px; font-size:18px; color:#2F2A27;">' . htmlspecialchars($judul) . '</h2>
                            ' . $isiHtml . '
                        </div>
                        <div style="padding:16px 24px; background:#F5EEE4; color:#6E655D; font-size:12px; text-align:center;">
                            Email otomatis dari WNJ.ID - mohon tidak membalas email ini.
                        </div>
                    </div>
                </div>';
        }
    }
