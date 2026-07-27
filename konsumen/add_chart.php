<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';

    date_default_timezone_set('Asia/Jakarta');

    $idvariant  = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $idKonsumen = $_SESSION['idkonsumen'];
    $waktu      = date('H:i:s');

    if ($idvariant <= 0) {
        $_SESSION['message'] = 'Produk tidak valid';
        header('Location: index.php');
        exit;
    }

    $koneksi->begin_transaction();

    // Kunci baris variant selama transaksi supaya stock tidak double-terjual saat request bersamaan
    $stmtStock = $koneksi->prepare("SELECT stock, harga FROM variants WHERE id = ? FOR UPDATE");
    $stmtStock->bind_param('i', $idvariant);
    $stmtStock->execute();
    $stock = $stmtStock->get_result()->fetch_assoc();

    if (!$stock) {
        $koneksi->rollback();
        $_SESSION['message'] = 'Variant tidak ditemukan';
        header('Location: index.php');
        exit;
    }

    // Harga SELALU diambil dari database, bukan dari query string, supaya tidak bisa dimanipulasi user
    $harga = $stock['harga'];

    if ($stock['stock'] > 0) {
        $stmtInsert = $koneksi->prepare("INSERT INTO keranjang (idkeranjang, idproduk, idmitra, idagen, idreseller, idmarketer, idkonsumen,
                                                jmlh, harga, subtotal, tgl, waktu, status, variant)
                                        VALUES (NULL, ?, '', '', '', '', ?, 1, ?, ?, NOW(), ?, 'Active', ?)");
        $stmtInsert->bind_param('isddss', $idvariant, $idKonsumen, $harga, $harga, $waktu, $idvariant);
        $stmtInsert->execute();

        $stmtUpdate = $koneksi->prepare("UPDATE variants SET stock = stock - 1 WHERE id = ? AND stock > 0");
        $stmtUpdate->bind_param('i', $idvariant);
        $stmtUpdate->execute();

        if ($stmtUpdate->affected_rows === 0) {
            // Stock habis tepat saat transaksi ini berjalan (kekalahan race condition)
            $koneksi->rollback();
            $_SESSION['message'] = 'Produk Telah Habis';
        } else {
            $koneksi->commit();
            $_SESSION['message'] = 'Produk Telah di Masukan ke Keranjang';
        }
    } else {
        $koneksi->rollback();
        $_SESSION['message'] = 'Produk Telah Habis';
    }

    header('Location: index.php');
    exit;
