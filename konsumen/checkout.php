<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';
    include '../includes/invoice_helper.php';

    $idKonsumen   = $_SESSION['idkonsumen'];
    $idsKeranjang = array_filter(array_map('intval', $_POST['idkeranjang'] ?? []));

    if (empty($idsKeranjang)) {
        $_SESSION['message'] = 'Pilih minimal 1 barang untuk checkout';
        header('Location: view_cart.php');
        exit;
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $koneksi->begin_transaction();

    try {
        $placeholders = implode(',', array_fill(0, count($idsKeranjang), '?'));
        $types        = str_repeat('i', count($idsKeranjang));

        // Kunci baris keranjang yang dipilih supaya tidak diutak-atik proses lain saat checkout berjalan
        $stmtItems = $koneksi->prepare("SELECT k.idkeranjang, k.idproduk AS idvariant, k.jmlh, k.harga, k.subtotal,
                                            v.variant, v.size, v.berat, p.namaproduk
                                        FROM keranjang k
                                        JOIN variants v ON k.idproduk = v.id
                                        LEFT JOIN products p ON v.idproducts = p.id
                                        WHERE k.idkonsumen = ? AND k.status = 'Active' AND k.idkeranjang IN ($placeholders)
                                        FOR UPDATE");
        $stmtItems->bind_param('s' . $types, $idKonsumen, ...$idsKeranjang);
        $stmtItems->execute();
        $items = $stmtItems->get_result()->fetch_all(MYSQLI_ASSOC);

        if (empty($items)) {
            throw new Exception('Barang yang dipilih tidak ditemukan di keranjang');
        }

        $subtotal = 0;
        $totalBerat = 0;
        foreach ($items as $item) {
            $subtotal   += (float) $item['subtotal'];
            $totalBerat += (int) ($item['berat'] ?? 0) * (int) $item['jmlh'];
        }
        if ($totalBerat <= 0) {
            $totalBerat = 1000; // fallback minimal 1kg kalau data berat produk belum diisi
        }

        $invoice = generateUniqueInvoice($koneksi, 'orderkonsumen', 'K', $idKonsumen);

        $stmtHeader = $koneksi->prepare("INSERT INTO orderkonsumen
                                            (invoice, idkonsumen, berat, subtotal, ongkir, total, status, payment_status)
                                            VALUES (?, ?, ?, ?, 0, ?, 'Menunggu Alamat', 'Belum Bayar')");
        $stmtHeader->bind_param('siidd', $invoice, $idKonsumen, $totalBerat, $subtotal, $subtotal);
        $stmtHeader->execute();
        $idorder = $stmtHeader->insert_id;

        $stmtDetail = $koneksi->prepare("INSERT INTO orderkonsumen_detail
                                            (idorder, idvariant, namaproduk, variant, size, harga, jumlah, subtotal)
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($items as $item) {
            $idvariant  = (int) $item['idvariant'];
            $namaproduk = $item['namaproduk'] ?? '';
            $variant    = $item['variant'] ?? '';
            $size       = $item['size'] ?? '';
            $harga      = (float) $item['harga'];
            $jumlah     = (int) $item['jmlh'];
            $itemSubtotal = (float) $item['subtotal'];

            $stmtDetail->bind_param('iisssdid', $idorder, $idvariant, $namaproduk, $variant, $size, $harga, $jumlah, $itemSubtotal);
            $stmtDetail->execute();
        }

        // Barang yang sudah masuk order dihapus dari keranjang (stok TIDAK dikembalikan,
        // karena memang sudah dipesan dan menunggu dibayar)
        $stmtDeleteCart = $koneksi->prepare("DELETE FROM keranjang WHERE idkeranjang = ? AND idkonsumen = ?");
        foreach ($idsKeranjang as $idkrj) {
            $stmtDeleteCart->bind_param('is', $idkrj, $idKonsumen);
            $stmtDeleteCart->execute();
        }

        $koneksi->commit();

        header('Location: alamat.php?invoice=' . urlencode($invoice));
        exit;
    } catch (Exception $e) {
        $koneksi->rollback();
        error_log($e->getMessage());
        $_SESSION['message'] = 'Gagal memproses checkout, silakan coba lagi';
        header('Location: view_cart.php');
        exit;
    }
