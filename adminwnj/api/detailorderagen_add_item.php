<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header('Content-Type: application/json');
    session_start();
    include '../../includes/db.php';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $namaAdmin = $_SESSION['administrator']['nama'] ?? null;
    if (!isset($_SESSION['administrator']) || !in_array($namaAdmin, ['Delita', 'Master'], true)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Anda tidak punya akses untuk mengubah order ini']);
        exit();
    }

    $statusBisaEdit = ['Pending', 'Tunggu Confirm Admin', 'Tunggu Confrim Admin'];

    $invoice  = trim($_POST['invoice'] ?? '');
    $idproduk = (int) ($_POST['idproduk'] ?? 0);
    $jumlah   = (int) ($_POST['jumlah'] ?? 0);

    if ($invoice === '' || $idproduk <= 0 || $jumlah <= 0) {
        echo json_encode(['success' => false, 'message' => 'Parameter tidak lengkap']);
        exit();
    }

    // orderagen (tabel MyISAM, tidak transactional) - konteks invoice (idmitraagen/iddb/status/
    // payment/tgl) diambil dari salah satu baris invoice ini, sebelum menulis baris baru.
    $stmtCtx = $koneksi->prepare("SELECT idmitraagen, iddb, status, payment, tgl FROM orderagen WHERE invoice = ? ORDER BY tgl ASC LIMIT 1");
    $stmtCtx->bind_param('s', $invoice);
    $stmtCtx->execute();
    $ctx = $stmtCtx->get_result()->fetch_assoc();
    $stmtCtx->close();

    if (!$ctx) {
        echo json_encode(['success' => false, 'message' => 'Invoice tidak ditemukan']);
        exit();
    }
    if (!in_array($ctx['status'], $statusBisaEdit, true)) {
        echo json_encode(['success' => false, 'message' => 'Order dengan status "' . $ctx['status'] . '" tidak bisa diubah lagi']);
        exit();
    }

    try {
        $stmtVar = $koneksi->prepare("SELECT harga, berat, disc, stock FROM variants WHERE id = ?");
        $stmtVar->bind_param('i', $idproduk);
        $stmtVar->execute();
        $variant = $stmtVar->get_result()->fetch_assoc();
        $stmtVar->close();

        if (!$variant) {
            throw new Exception('Variant tidak ditemukan');
        }
        if ($variant['stock'] < $jumlah) {
            throw new Exception('Stock tidak cukup (tersisa ' . $variant['stock'] . ')');
        }

        $harga    = (int) $variant['harga'];
        $subtotal = $harga * $jumlah;
        $berat    = (int) $variant['berat'] * $jumlah;
        $disc     = (int) $variant['disc'];

        $stmtStock = $koneksi->prepare("UPDATE variants SET stock = stock - ? WHERE id = ? AND stock >= ?");
        $stmtStock->bind_param('iii', $jumlah, $idproduk, $jumlah);
        $stmtStock->execute();
        if ($stmtStock->affected_rows === 0) {
            throw new Exception('Stock berubah saat diproses, coba lagi');
        }
        $stmtStock->close();

        $stmtInsert = $koneksi->prepare("INSERT INTO orderagen
            (idmitraagen, iddb, idproduk, harga, jumlah, subtotal, tgl, invoice, status, payment, berat, disc)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtInsert->bind_param(
            'iiiiiissssii',
            $ctx['idmitraagen'],
            $ctx['iddb'],
            $idproduk,
            $harga,
            $jumlah,
            $subtotal,
            $ctx['tgl'],
            $invoice,
            $ctx['status'],
            $ctx['payment'],
            $berat,
            $disc
        );
        $stmtInsert->execute();
        $stmtInsert->close();

        echo json_encode(['success' => true, 'message' => 'Produk berhasil ditambahkan ke order']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
