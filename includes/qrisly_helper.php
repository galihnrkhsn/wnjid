<?php
// Pembayaran QRIS lewat Qrisly (RajaOngkir Collaborator). Kredensial di
// includes/rajaongkir.config.php (bagian 'qrisly'), sama pola dengan rajaongkir_helper.php.
// Dokumentasi: https://rajaongkir.com/docs/qrisly/getting-started/getting-started

const QRISLY_BASE_URL = 'https://api-sandbox.collaborator.komerce.id/user/api/v1/qrisly/';

if (!function_exists('qrislyConfig')) {
    function qrislyConfig(): array
    {
        $configFile = __DIR__ . '/rajaongkir.config.php';
        if (!file_exists($configFile)) {
            return [];
        }
        $config = require $configFile;
        return $config['qrisly'] ?? [];
    }
}

if (!function_exists('qrislyRequest')) {
    /**
     * @param array|null $jsonBody null kalau GET / tidak ada body JSON
     * @param array $extraHeaders header tambahan (mis. Content-Type multipart tidak perlu di-set manual kalau pakai $postFields array/CURLFile)
     * @return array [$httpCode, $decoded|null]
     */
    function qrislyRequest(string $method, string $path, $body = null, array $extraHeaders = []): array
    {
        $apiKey = qrislyConfig()['api_key'] ?? '';
        if ($apiKey === '') {
            return [0, null];
        }

        $curl = curl_init();
        $headers = array_merge(['x-api-key: ' . $apiKey], $extraHeaders);

        $opts = [
            CURLOPT_URL            => QRISLY_BASE_URL . ltrim($path, '/'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $headers,
        ];

        if ($body !== null) {
            // Array (bisa berisi CURLFile) -> curl otomatis kirim multipart/form-data.
            // String (JSON) -> dikirim apa adanya, Content-Type diset lewat $extraHeaders.
            $opts[CURLOPT_POSTFIELDS] = $body;
        }

        curl_setopt_array($curl, $opts);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err      = curl_error($curl);
        curl_close($curl);

        if ($err || $response === false) {
            return [0, null];
        }

        $decoded = json_decode($response, true);
        return [$httpCode, json_last_error() === JSON_ERROR_NONE ? $decoded : null];
    }
}

if (!function_exists('qrislyUploadStaticQris')) {
    /**
     * Upload QRIS statis toko (dilakukan SEKALI lewat adminwnj/qrisly_setup.php).
     * @return array|null ['qris_id'=>string, 'provider'=>string, 'merchant_name'=>string]
     */
    function qrislyUploadStaticQris(string $tmpFilePath, string $originalName, string $merchantName): ?array
    {
        $ekstensi = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $mime     = $ekstensi === 'png' ? 'image/png' : 'image/jpeg';

        [$httpCode, $decoded] = qrislyRequest('POST', 'upload-qris', [
            'name'       => $merchantName,
            'qris_image' => new CURLFile($tmpFilePath, $mime, $originalName),
        ]);

        if ($httpCode !== 200 || empty($decoded['success']) || empty($decoded['data']['qris_id'])) {
            return null;
        }

        return $decoded['data'];
    }
}

if (!function_exists('qrislyGenerateQris')) {
    /**
     * @return array|null ['history_id','qris_string','original_amount','final_amount','payment_status','expiry_time']
     */
    function qrislyGenerateQris(int $amount): ?array
    {
        $qrisId = qrislyConfig()['qris_id'] ?? '';
        if ($qrisId === '' || $amount <= 0) {
            return null;
        }

        [$httpCode, $decoded] = qrislyRequest('POST', 'generate-qris', json_encode([
            'qris_id'       => $qrisId,
            'amount'        => $amount,
            'output_type'   => 'string',
            'unique_amount' => true,
        ]), ['Content-Type: application/json']);

        if ($httpCode !== 200 || empty($decoded['success']) || empty($decoded['data']['history_id'])) {
            return null;
        }

        return $decoded['data'];
    }
}

if (!function_exists('qrislyCheckPaymentStatus')) {
    /**
     * @return array|null ['history_id','payment_status','amount','paid_at','created_at','updated_at']
     */
    function qrislyCheckPaymentStatus($historyId): ?array
    {
        [$httpCode, $decoded] = qrislyRequest('GET', 'payment-status/' . urlencode((string) $historyId));

        if ($httpCode !== 200 || empty($decoded['data'])) {
            return null;
        }

        return $decoded['data'];
    }
}

if (!function_exists('qrislyMarkOrderPaid')) {
    /**
     * Tandai QRIS + order konsumen sebagai lunas, idempotent (aman dipanggil berkali-kali
     * dari webhook retry maupun polling fallback tanpa dobel proses).
     * Dipakai bareng oleh qrisly_webhook.php & qrisly_status.php supaya satu sumber kebenaran.
     */
    function qrislyMarkOrderPaid(mysqli $koneksi, $historyId, ?string $paidAt): bool
    {
        $paidAt = $paidAt ?: date('Y-m-d H:i:s');

        $stmtQris = $koneksi->prepare("UPDATE orderkonsumen_qris
                                        SET payment_status = 'paid', paid_at = ?
                                        WHERE history_id = ? AND payment_status <> 'paid'");
        $stmtQris->bind_param('ss', $paidAt, $historyId);
        $stmtQris->execute();

        if ($stmtQris->affected_rows === 0) {
            // Sudah pernah ditandai lunas sebelumnya (webhook retry / race dengan polling) - bukan error.
            return false;
        }

        $stmtOrder = $koneksi->prepare("SELECT oq.idorder
                                        FROM orderkonsumen_qris oq
                                        WHERE oq.history_id = ?");
        $stmtOrder->bind_param('s', $historyId);
        $stmtOrder->execute();
        $row = $stmtOrder->get_result()->fetch_assoc();
        if (!$row) {
            return false;
        }

        $stmtUpdateOrder = $koneksi->prepare("UPDATE orderkonsumen
                                                SET status = 'Diproses', payment_status = 'Lunas'
                                                WHERE idorder = ? AND status = 'Menunggu Pembayaran'");
        $stmtUpdateOrder->bind_param('i', $row['idorder']);
        $stmtUpdateOrder->execute();

        return true;
    }
}
