<?php
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';
    include '../includes/promo_badge_helper.php';

    date_default_timezone_set('Asia/Jakarta');

    // POST (bukan GET) supaya perubahan stok ini tidak bisa dipicu dari luar (mis. <img> tag /
    // link pancingan) - lihat csrfRequireValid() di sesKonsumen.php yang juga menolak POST
    // tanpa token yang cocok.
    $idvariant  = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $idproduk   = isset($_POST['pid']) ? (int) $_POST['pid'] : 0;
    $qty        = isset($_POST['qty']) ? (int) $_POST['qty'] : 1;
    $idKonsumen = $_SESSION['idkonsumen'];
    $waktu      = date('H:i:s');
    $kembali    = $idproduk > 0 ? 'produk.php?id=' . $idproduk : 'index.php';

    if ($qty < 1) {
        $qty = 1;
    }

    if ($idvariant <= 0) {
        $_SESSION['message'] = 'Produk tidak valid';
        header('Location: ' . $kembali);
        exit;
    }

    $koneksi->begin_transaction();

    // Kunci baris variant selama transaksi supaya stock tidak double-terjual saat request bersamaan
    $stmtStock = $koneksi->prepare("SELECT stock, harga, disc FROM variants WHERE id = ? FOR UPDATE");
    $stmtStock->bind_param('i', $idvariant);
    $stmtStock->execute();
    $stock = $stmtStock->get_result()->fetch_assoc();

    if (!$stock) {
        $koneksi->rollback();
        $_SESSION['message'] = 'Variant tidak ditemukan';
        header('Location: ' . $kembali);
        exit;
    }

    // Harga SELALU diambil dari database, bukan dari query string, supaya tidak bisa dimanipulasi user.
    // Kalau variant ini punya disc, harga yang tersimpan di keranjang sudah harga setelah diskon.
    $harga = hargaSetelahDisc((int) $stock['harga'], $stock['disc'] ?? null);

    // Kalau variant yang sama sudah ada di keranjang aktif konsumen ini, gabung ke baris itu
    // (tambah jmlh) - jangan bikin baris baru buat produk yang sama persis.
    $stmtExisting = $koneksi->prepare("SELECT idkeranjang, jmlh FROM keranjang WHERE idkonsumen = ? AND idproduk = ? AND status = 'Active' FOR UPDATE");
    $stmtExisting->bind_param('si', $idKonsumen, $idvariant);
    $stmtExisting->execute();
    $existing = $stmtExisting->get_result()->fetch_assoc();

    if ($stock['stock'] >= $qty) {
        if ($existing) {
            $jmlhBaru    = (int) $existing['jmlh'] + $qty;
            $subtotalBaru = $harga * $jmlhBaru;

            $stmtMerge = $koneksi->prepare("UPDATE keranjang SET jmlh = ?, harga = ?, subtotal = ?, tgl = NOW(), waktu = ? WHERE idkeranjang = ?");
            $stmtMerge->bind_param('iddsi', $jmlhBaru, $harga, $subtotalBaru, $waktu, $existing['idkeranjang']);
            $stmtMerge->execute();
        } else {
            $subtotal = $harga * $qty;
            $stmtInsert = $koneksi->prepare("INSERT INTO keranjang (idkeranjang, idproduk, idmitra, idagen, idreseller, idmarketer, idkonsumen,
                                                    jmlh, harga, subtotal, tgl, waktu, status, variant)
                                            VALUES (NULL, ?, '', '', '', '', ?, ?, ?, ?, NOW(), ?, 'Active', ?)");
            $stmtInsert->bind_param('isiddss', $idvariant, $idKonsumen, $qty, $harga, $subtotal, $waktu, $idvariant);
            $stmtInsert->execute();
        }

        // qty ganda dipakai di WHERE juga - jaga-jaga stock berkurang antara SELECT ... FOR UPDATE
        // di atas dan UPDATE ini (walau sudah dikunci, lebih aman eksplisit).
        $stmtUpdate = $koneksi->prepare("UPDATE variants SET stock = stock - ? WHERE id = ? AND stock >= ?");
        $stmtUpdate->bind_param('iii', $qty, $idvariant, $qty);
        $stmtUpdate->execute();

        if ($stmtUpdate->affected_rows === 0) {
            // Stock habis/berkurang tepat saat transaksi ini berjalan (kekalahan race condition)
            $koneksi->rollback();
            $_SESSION['message'] = 'Stok tidak mencukupi';
        } else {
            $koneksi->commit();
            $_SESSION['message'] = 'Produk Telah di Masukan ke Keranjang';
        }
    } else {
        $koneksi->rollback();
        $_SESSION['message'] = 'Stok tidak mencukupi';
    }

    header('Location: ' . $kembali);
    exit;
