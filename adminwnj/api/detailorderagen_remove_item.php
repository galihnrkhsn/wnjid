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

    $invoice  = trim($_POST['invoice'] ?? '');
    $idproduk = (int) ($_POST['idproduk'] ?? 0);

    if ($invoice === '' || $idproduk <= 0) {
        echo json_encode(['success' => false, 'message' => 'Parameter tidak lengkap']);
        exit();
    }

    $stmtCtx = $koneksi->prepare("SELECT status FROM orderagen WHERE invoice = ? LIMIT 1");
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
        $stmtExisting = $koneksi->prepare("SELECT SUM(jumlah) AS total_jumlah FROM orderagen WHERE invoice = ? AND idproduk = ?");
        $stmtExisting->bind_param('si', $invoice, $idproduk);
        $stmtExisting->execute();
        $existing = $stmtExisting->get_result()->fetch_assoc();
        $stmtExisting->close();

        if (!$existing || $existing['total_jumlah'] === null) {
            throw new Exception('Item tidak ditemukan di order ini');
        }

        $totalJumlah = (int) $existing['total_jumlah'];

        $stmtCount = $koneksi->prepare("SELECT COUNT(DISTINCT idproduk) AS jumlah_item FROM orderagen WHERE invoice = ?");
        $stmtCount->bind_param('s', $invoice);
        $stmtCount->execute();
        $jumlahItem = (int) $stmtCount->get_result()->fetch_assoc()['jumlah_item'];
        $stmtCount->close();

        if ($jumlahItem <= 1) {
            throw new Exception('Tidak bisa menghapus item terakhir di order ini - batalkan ordernya lewat menu order kalau memang perlu dikosongkan');
        }

        $stmtDelete = $koneksi->prepare("DELETE FROM orderagen WHERE invoice = ? AND idproduk = ?");
        $stmtDelete->bind_param('si', $invoice, $idproduk);
        $stmtDelete->execute();
        $stmtDelete->close();

        // Stock dikembalikan ke variants (bukan ke tabel `produk` lama yang sudah tidak dipakai).
        $stmtStock = $koneksi->prepare("UPDATE variants SET stock = stock + ? WHERE id = ?");
        $stmtStock->bind_param('ii', $totalJumlah, $idproduk);
        $stmtStock->execute();
        $stmtStock->close();

        echo json_encode(['success' => true, 'message' => 'Item berhasil dihapus dari order']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
