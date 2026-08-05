<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header('Content-Type: application/json');
    session_start();
    include __DIR__ . '/../access_guard.php';
    include '../../includes/db.php';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Hanya admin tertentu yang boleh mengubah isi order, sama seperti batasan hapus item yang sudah ada.
    $namaAdmin = $_SESSION['administrator']['nama'] ?? null;
    if (!isset($_SESSION['administrator']) || !in_array($namaAdmin, ['Delita', 'Master'], true)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Anda tidak punya akses untuk mengubah order ini']);
        exit();
    }

    // Status yang masih boleh diedit - order yang sudah dikirim/selesai tidak boleh diubah lagi.
    $statusBisaEdit = ['Pending', 'Proses', 'Tunggu Confirm Admin', 'Tunggu Confrim Admin'];

    $invoice  = trim($_POST['invoice'] ?? '');
    $idproduk = (int) ($_POST['idproduk'] ?? 0);
    $jumlah   = (int) ($_POST['jumlah'] ?? 0);

    if ($invoice === '' || $idproduk <= 0 || $jumlah <= 0) {
        echo json_encode(['success' => false, 'message' => 'Parameter tidak lengkap']);
        exit();
    }

    $stmtCtx = $koneksi->prepare("SELECT idmitra, status, payment, tgl FROM ordermitra WHERE invoice = ? ORDER BY tgl ASC LIMIT 1");
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

    $koneksi->begin_transaction();
    try {
        $stmtVar = $koneksi->prepare("SELECT v.harga, v.berat, v.disc, v.stock, v.variant, v.size, p.namaproduk
            FROM variants v INNER JOIN products p ON p.id = v.idproducts
            WHERE v.id = ? FOR UPDATE");
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
        $waktu    = date('H:i:s');

        $stmtInsert = $koneksi->prepare("INSERT INTO ordermitra
            (idmitra, idproduk, harga, jumlah, subtotal, berat, tgl, invoice, status, payment, waktu, disc)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtInsert->bind_param(
            'siiiiisssssi',
            $ctx['idmitra'],
            $idproduk,
            $harga,
            $jumlah,
            $subtotal,
            $berat,
            $ctx['tgl'],
            $invoice,
            $ctx['status'],
            $ctx['payment'],
            $waktu,
            $disc
        );
        $stmtInsert->execute();
        $stmtInsert->close();

        $stmtStock = $koneksi->prepare("UPDATE variants SET stock = stock - ? WHERE id = ? AND stock >= ?");
        $stmtStock->bind_param('iii', $jumlah, $idproduk, $jumlah);
        $stmtStock->execute();
        if ($stmtStock->affected_rows === 0) {
            throw new Exception('Stock berubah saat diproses, coba lagi');
        }
        $stmtStock->close();

        $koneksi->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke order',
        ]);
    } catch (Exception $e) {
        $koneksi->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
