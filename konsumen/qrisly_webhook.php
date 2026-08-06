<?php
    // Endpoint publik dipanggil server Qrisly, TIDAK pakai session konsumen.
    // Daftarkan URL ini di dashboard RajaOngkir > Webhook > QRISLY > Outbound Webhook.
    //
    // Body webhook TIDAK dipercaya mentah-mentah untuk update status lunas - selalu
    // di-reverifikasi lewat GET payment-status ke Qrisly pakai API key kita sendiri,
    // supaya penyerang tidak bisa memalsukan status "paid" walau tahu bentuk payload-nya.
    // Signature X-Callback-Api-Key (HMAC-SHA256) dicek juga kalau callback_secret sudah diisi.

    header('Content-Type: application/json');
    include 'koneksi.php';
    include '../includes/qrisly_helper.php';

    $rawBody = file_get_contents('php://input');
    $body    = json_decode($rawBody, true);

    $callbackSecret = qrislyConfig()['callback_secret'] ?? '';
    if ($callbackSecret !== '') {
        $signature = $_SERVER['HTTP_X_CALLBACK_API_KEY'] ?? '';
        $expected  = hash_hmac('sha256', $rawBody, $callbackSecret);
        if (!hash_equals($expected, $signature)) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Invalid signature']);
            exit;
        }
    }

    $historyId = $body['data']['qris_history_id'] ?? $body['data']['history_id'] ?? null;

    if (!$historyId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'history_id tidak ada di payload']);
        exit;
    }

    // Reverifikasi ke Qrisly langsung, jangan percaya field "status"/"payment_status" di body webhook.
    $cek = qrislyCheckPaymentStatus($historyId);

    if ($cek && ($cek['payment_status'] ?? '') === 'paid') {
        qrislyMarkOrderPaid($koneksi, $historyId, $cek['paid_at'] ?? null);
    }

    // Selalu balas 200 + format yang diminta dokumentasi, walau history_id tidak match
    // (misal race dengan penghapusan test data) - biar Qrisly tidak retry sia-sia.
    echo json_encode(['success' => true, 'message' => 'Webhook received and processed']);
