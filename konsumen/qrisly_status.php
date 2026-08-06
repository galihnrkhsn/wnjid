<?php
    header('Content-Type: application/json');
    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';
    include '../includes/qrisly_helper.php';

    $idKonsumen = $_SESSION['idkonsumen'];
    $invoice    = trim($_POST['invoice'] ?? '');

    $stmt = $koneksi->prepare("SELECT oq.history_id, oq.payment_status, oq.expiry_time, o.status AS order_status
                                FROM orderkonsumen_qris oq
                                INNER JOIN orderkonsumen o ON o.idorder = oq.idorder
                                WHERE o.invoice = ? AND o.idkonsumen = ?
                                ORDER BY oq.idqris DESC LIMIT 1");
    $stmt->bind_param('si', $invoice, $idKonsumen);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row) {
        echo json_encode(['success' => false, 'message' => 'Kode QRIS tidak ditemukan']);
        exit;
    }

    // Webhook biasanya lebih cepat, ini cuma jaring pengaman kalau webhook telat/gagal terkirim.
    if ($row['payment_status'] === 'unpaid' && strtotime($row['expiry_time']) > time()) {
        $cek = qrislyCheckPaymentStatus($row['history_id']);
        if ($cek && ($cek['payment_status'] ?? '') === 'paid') {
            qrislyMarkOrderPaid($koneksi, $row['history_id'], $cek['paid_at'] ?? null);
            $row['payment_status'] = 'paid';
            $row['order_status']   = 'Diproses';
        }
    }

    $expired = $row['payment_status'] === 'unpaid' && strtotime($row['expiry_time']) <= time();

    echo json_encode([
        'success'        => true,
        'payment_status' => $row['payment_status'],
        'order_status'   => $row['order_status'],
        'expired'        => $expired,
    ]);
