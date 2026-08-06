<?php
    header('Content-Type: application/json');
    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';
    include '../includes/qrisly_helper.php';

    $idKonsumen = $_SESSION['idkonsumen'];
    $invoice    = trim($_POST['invoice'] ?? '');

    $stmtOrder = $koneksi->prepare("SELECT idorder, total, status FROM orderkonsumen WHERE invoice = ? AND idkonsumen = ?");
    $stmtOrder->bind_param('si', $invoice, $idKonsumen);
    $stmtOrder->execute();
    $order = $stmtOrder->get_result()->fetch_assoc();

    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order tidak ditemukan']);
        exit;
    }

    if ($order['status'] !== 'Menunggu Pembayaran') {
        echo json_encode(['success' => false, 'message' => 'Order ini sudah tidak menunggu pembayaran']);
        exit;
    }

    // Pakai QRIS yang sudah ada kalau masih berlaku & belum lunas (hindari generate ulang
    // tiap reload - tiap generate-qris kena biaya IDR 100 per panggilan).
    $stmtExisting = $koneksi->prepare("SELECT history_id, qris_string, final_amount, expiry_time
                                        FROM orderkonsumen_qris
                                        WHERE idorder = ? AND payment_status = 'unpaid' AND expiry_time > NOW()
                                        ORDER BY idqris DESC LIMIT 1");
    $stmtExisting->bind_param('i', $order['idorder']);
    $stmtExisting->execute();
    $existing = $stmtExisting->get_result()->fetch_assoc();

    if ($existing) {
        echo json_encode([
            'success'     => true,
            'history_id'  => $existing['history_id'],
            'qris_string' => $existing['qris_string'],
            'amount'      => (int) $existing['final_amount'],
            'expiry_time' => $existing['expiry_time'],
        ]);
        exit;
    }

    $hasil = qrislyGenerateQris((int) $order['total']);

    if (!$hasil) {
        echo json_encode(['success' => false, 'message' => 'Gagal membuat kode QRIS, silakan coba lagi']);
        exit;
    }

    $qrisId = qrislyConfig()['qris_id'] ?? '';

    $stmtInsert = $koneksi->prepare("INSERT INTO orderkonsumen_qris
                                        (idorder, history_id, qris_id, qris_string, original_amount, final_amount, payment_status, expiry_time)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $paymentStatus = $hasil['payment_status'] ?? 'unpaid';
    $stmtInsert->bind_param(
        'isssddss',
        $order['idorder'],
        $hasil['history_id'],
        $qrisId,
        $hasil['qris_string'],
        $hasil['original_amount'],
        $hasil['final_amount'],
        $paymentStatus,
        $hasil['expiry_time']
    );
    $stmtInsert->execute();

    echo json_encode([
        'success'     => true,
        'history_id'  => $hasil['history_id'],
        'qris_string' => $hasil['qris_string'],
        'amount'      => (int) $hasil['final_amount'],
        'expiry_time' => $hasil['expiry_time'],
    ]);
