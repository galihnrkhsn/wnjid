<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header('Content-Type: application/json');
    session_start();
    include __DIR__ . '/../access_guard.php';
    include '../../includes/db.php';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $namaAdmin = $_SESSION['administrator']['nama'] ?? null;
    if (!isset($_SESSION['administrator']) || !in_array($namaAdmin, ['Delita', 'Master'], true)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Anda tidak punya akses untuk mengubah order ini']);
        exit();
    }

    $statusBisaEdit = ['Pending', 'Tunggu Confirm Admin', 'Tunggu Confrim Admin'];

    $invoice    = trim($_POST['invoice'] ?? '');
    $idproduk   = (int) ($_POST['idproduk'] ?? 0);
    $jumlahBaru = (int) ($_POST['jumlah'] ?? 0);

    if ($invoice === '' || $idproduk <= 0 || $jumlahBaru <= 0) {
        echo json_encode(['success' => false, 'message' => 'Jumlah harus lebih dari 0 (pakai Hapus kalau mau menghilangkan item ini)']);
        exit();
    }

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
        $stmtExisting = $koneksi->prepare("SELECT idorder, harga, disc, berat, jumlah FROM orderagen WHERE invoice = ? AND idproduk = ?");
        $stmtExisting->bind_param('si', $invoice, $idproduk);
        $stmtExisting->execute();
        $existingRows = $stmtExisting->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmtExisting->close();

        if (!$existingRows) {
            throw new Exception('Item tidak ditemukan di order ini');
        }

        $jumlahLama = 0;
        foreach ($existingRows as $row) {
            $jumlahLama += (int) $row['jumlah'];
        }
        $harga     = (int) $existingRows[0]['harga'];
        $disc      = (int) $existingRows[0]['disc'];
        $beratSatu = $existingRows[0]['jumlah'] > 0
            ? (int) $existingRows[0]['berat'] / (int) $existingRows[0]['jumlah']
            : 0;

        $delta = $jumlahBaru - $jumlahLama;

        if ($delta > 0) {
            $stmtStockCheck = $koneksi->prepare("SELECT stock FROM variants WHERE id = ?");
            $stmtStockCheck->bind_param('i', $idproduk);
            $stmtStockCheck->execute();
            $stockRow = $stmtStockCheck->get_result()->fetch_assoc();
            $stmtStockCheck->close();

            if (!$stockRow || $stockRow['stock'] < $delta) {
                $tersisa = $stockRow['stock'] ?? 0;
                throw new Exception("Stock tidak cukup untuk menambah jumlah (tersisa $tersisa)");
            }
        }

        $stmtDelete = $koneksi->prepare("DELETE FROM orderagen WHERE invoice = ? AND idproduk = ?");
        $stmtDelete->bind_param('si', $invoice, $idproduk);
        $stmtDelete->execute();
        $stmtDelete->close();

        $subtotalBaru = $harga * $jumlahBaru;
        $beratBaru    = (int) round($beratSatu * $jumlahBaru);

        $stmtInsert = $koneksi->prepare("INSERT INTO orderagen
            (idmitraagen, iddb, idproduk, harga, jumlah, subtotal, tgl, invoice, status, payment, berat, disc)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtInsert->bind_param(
            'iiiiiissssii',
            $ctx['idmitraagen'],
            $ctx['iddb'],
            $idproduk,
            $harga,
            $jumlahBaru,
            $subtotalBaru,
            $ctx['tgl'],
            $invoice,
            $ctx['status'],
            $ctx['payment'],
            $beratBaru,
            $disc
        );
        $stmtInsert->execute();
        $stmtInsert->close();

        if ($delta !== 0) {
            $stmtStock = $koneksi->prepare("UPDATE variants SET stock = stock - ? WHERE id = ?");
            $stmtStock->bind_param('ii', $delta, $idproduk);
            $stmtStock->execute();
            $stmtStock->close();
        }

        echo json_encode(['success' => true, 'message' => 'Jumlah berhasil diubah']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
