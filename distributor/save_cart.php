<?php
    include "koneksi.php";
    include "assets/components/Sessions/sesDistri.php";
    include "../includes/invoice_helper.php";

    date_default_timezone_set('Asia/Jakarta');

    // idmitra HARUS selalu dari session, jangan pernah percaya $_POST['idmitra']
    // (nilai POST bisa dimanipulasi siapa saja untuk checkout atas nama mitra lain)
    $idmitra = $_SESSION["idadmin"];

    if (isset($_POST['save'])) {
        $idprodukubah    = $_POST["idprodukubah"]    ?? [];
        $harga           = $_POST["harga"]           ?? [];
        $idkeranjangubah = $_POST["idkeranjangubah"] ?? [];
        $jmlh            = $_POST["jmlh"]             ?? [];
        $jmlhbaru        = $_POST["jmlhbaru"]         ?? [];
        $jumlah_dipilih  = count($idprodukubah);

        $gagal = false;

        if (count($harga) !== $jumlah_dipilih || count($idkeranjangubah) !== $jumlah_dipilih
            || count($jmlh) !== $jumlah_dipilih || count($jmlhbaru) !== $jumlah_dipilih) {
            $_SESSION['message'] = 'Data keranjang tidak valid, silahkan coba lagi';
            header('Location: view_cart.php');
            exit;
        }

        $stmtStock  = $koneksi->prepare("SELECT stock FROM variants WHERE id = ?");
        $stmtUpdKrj = $koneksi->prepare("UPDATE keranjang SET jmlh = ?, subtotal = ? WHERE idkeranjang = ? AND idmitra = ?");
        $stmtUpdVar = $koneksi->prepare("UPDATE variants SET stock = ? WHERE id = ?");

        for ($x = 0; $x < $jumlah_dipilih; $x++) {
            $idproduk     = (int) $idprodukubah[$x];
            $idkeranjang  = (int) $idkeranjangubah[$x];
            $hargaSatuan  = (float) $harga[$x];
            $jmlhLama     = (int) $jmlh[$x];
            $jmlhBaru     = max(0, (int) $jmlhbaru[$x]);

            $stmtStock->bind_param('i', $idproduk);
            $stmtStock->execute();
            $tampil = $stmtStock->get_result()->fetch_assoc();
            $stock  = $tampil['stock'] ?? 0;

            $selisih = $jmlhBaru - $jmlhLama;

            if ($selisih <= $stock) {
                $subtotalBaru = $hargaSatuan * $jmlhBaru;
                $stmtUpdKrj->bind_param('ddis', $jmlhBaru, $subtotalBaru, $idkeranjang, $idmitra);
                $stmtUpdKrj->execute();

                $stockBaru = $stock - $selisih;
                $stmtUpdVar->bind_param('ii', $stockBaru, $idproduk);
                $stmtUpdVar->execute();
            } else {
                $gagal = true;
            }
        }

        $_SESSION['message'] = $gagal
            ? 'Stock Kami tidak mencukupi jumlah yang diminta, Silahkan Periksa Lagi Stock yang Tersedia'
            : 'Keranjang Berhasil di Simpan';
        header('Location: view_cart.php');
        exit;
    } elseif (isset($_POST['checkout'])) {
        if (!isset($_POST['idkeranjang']) || empty($_POST['idkeranjang'])) {
            $_SESSION['message'] = 'Check salah satu barang yang ingin di checkout';
            header('Location: view_cart.php');
            exit;
        }

        $idkeranjang    = $_POST["idkeranjang"];
        $jenis          = $_POST["jenis"] ?? null;
        $jumlah_dipilih = count($idkeranjang);
        $berat          = 0;
        $invoice        = '';
        $data_keranjang = [];

        // Ambil item keranjang HANYA milik mitra yang sedang login (mencegah checkout keranjang orang lain)
        $stmtItem = $koneksi->prepare("SELECT
                                             keranjang.idproduk,
                                             keranjang.idkeranjang,
                                             keranjang.jmlh,
                                             keranjang.harga,
                                             keranjang.subtotal,
                                             variants.berat,
                                             variants.jenis,
                                             variants.idproducts
                                         FROM keranjang
                                         INNER JOIN variants ON keranjang.idproduk = variants.id
                                         WHERE keranjang.idkeranjang = ? AND keranjang.idmitra = ?");

        for ($i = 0; $i < $jumlah_dipilih; $i++) {
            $idkeranjangItem = (int) $idkeranjang[$i];
            $stmtItem->bind_param('is', $idkeranjangItem, $idmitra);
            $stmtItem->execute();
            $item = $stmtItem->get_result()->fetch_assoc();
            if ($item) {
                $data_keranjang[] = $item;
            }
        }

        if (count($data_keranjang) === 0) {
            $_SESSION['message'] = 'Item keranjang tidak ditemukan';
            header('Location: view_cart.php');
            exit;
        }

        if ($jenis == 'b1g1') {
            // Produk yang boleh digabung dalam 1x checkout B1G1 harus dari artikel/koleksi yang
            // sama. Grouping ini hidup di kolom products.kode_artikel (diisi staff lewat
            // adminwnj/tambah_produk.php), bukan array hardcoded lagi - lihat database/products_kode_artikel.sql
            $idproductsUnik = array_values(array_unique(array_column($data_keranjang, 'idproducts')));
            $placeholders   = implode(',', array_fill(0, count($idproductsUnik), '?'));
            $types          = str_repeat('i', count($idproductsUnik));

            $stmtArtikel = $koneksi->prepare("SELECT DISTINCT kode_artikel FROM products WHERE id IN ($placeholders)");
            $stmtArtikel->bind_param($types, ...$idproductsUnik);
            $stmtArtikel->execute();
            $kodeArtikelList = array_column($stmtArtikel->get_result()->fetch_all(MYSQLI_ASSOC), 'kode_artikel');

            if (in_array(null, $kodeArtikelList, true) || in_array('', $kodeArtikelList, true)) {
                $_SESSION['message'] = 'Ada produk yang belum diberi kode artikel untuk promo B1G1, hubungi admin';
                echo "<script>alert('Ada produk yang belum diberi kode artikel untuk promo B1G1, hubungi admin'); location='view_cart.php';</script>";
                exit;
            }

            $all_same_category = count(array_unique($kodeArtikelList)) === 1;

            if (!$all_same_category) {
                $_SESSION['message'] = 'Tidak bisa memilih lebih dari 1 Artikel!';
                echo "<script>alert('Tidak bisa memilih lebih dari 1 Artikel!'); location='view_cart.php';</script>";
                exit;
            }

            $total_qty = array_sum(array_column($data_keranjang, 'jmlh'));
            if ($total_qty !== 2) {
                $_SESSION['message'] = 'Jumlah item harus 2 untuk promo Buy 1 Get 1';
                echo "<script>alert('Jumlah item harus 2 untuk promo Buy 1 Get 1'); location='view_cart.php';</script>";
                exit;
            }
        }

        // Alias lama 'bundling' (huruf kecil) disatukan ke 'Bundling 3' - aturannya identik
        // (1 bundling = 1 produk saja, kelipatan 3), jadi tidak perlu jadi tier terpisah.
        foreach ($data_keranjang as &$itemNormalisasi) {
            if ($itemNormalisasi['jenis'] === 'bundling') {
                $itemNormalisasi['jenis'] = 'Bundling 3';
            }
        }
        unset($itemNormalisasi);

        // Tier bundling: jenis -> kelipatan qty yang wajib. Nambah tier baru (mis. "Bundling 7")
        // cukup tambah 1 baris di sini, tidak perlu blok kode baru.
        $bundlingRules = [
            'Bundling 3' => 3,
            'Bundling 5' => 5,
        ];

        $bundlingItemsByRule = [];
        foreach ($bundlingRules as $jenisRule => $kelipatan) {
            $bundlingItemsByRule[$jenisRule] = array_filter($data_keranjang, fn($item) => $item['jenis'] === $jenisRule);
        }

        // Bundling Short sengaja TIDAK masuk $bundlingRules (di luar scope - diskonnya dihitung
        // belakangan di formpengirimanb.php, bukan divalidasi kelipatan di sini), jadi otomatis
        // ikut $non_bundling_items seperti item biasa.
        $non_bundling_items = array_filter($data_keranjang, fn($item) => !array_key_exists($item['jenis'], $bundlingRules));

        $koneksi->begin_transaction();

        $stmtVariant   = $koneksi->prepare("SELECT * FROM variants WHERE variants.id = ?");
        $stmtInsertOrd = $koneksi->prepare("INSERT INTO ordermitra
                                (idorder, idmitra, idproduk, harga, jumlah, subtotal, tgl, invoice, status, payment, berat, waktu, disc)
                                VALUES (NULL, ?, ?, ?, ?, ?, NOW(), ?, 'Pending', 'Belum Bayar', ?, ?, ?)");
        $stmtDeleteKrj = $koneksi->prepare("DELETE FROM keranjang WHERE idkeranjang = ? AND idmitra = ?");

        try {
            foreach ($bundlingRules as $jenisRule => $kelipatan) {
                $items = $bundlingItemsByRule[$jenisRule];
                if (count($items) === 0) {
                    continue;
                }

                $idproductsBundling  = array_column($items, 'idproducts');
                $total_qty_bundling  = array_sum(array_column($items, 'jmlh'));
                $all_same_idproducts = count(array_unique($idproductsBundling)) === 1;

                if (!$all_same_idproducts) {
                    throw new RuntimeException('Produk dalam bundling harus sama!');
                }
                if ($total_qty_bundling % $kelipatan !== 0) {
                    throw new RuntimeException("QTY Bundling harus kelipatan $kelipatan");
                }

                foreach ($items as $item) {
                    $idproduk   = (int) $item['idproduk'];
                    $hargaItem  = $item['harga'];
                    $jmlhbaru   = $item['jmlh'];
                    $subtotal   = $item['subtotal'];
                    $berattotal = $item['berat'] * $jmlhbaru;
                    $idker      = (int) $item['idkeranjang'];
                    $waktu      = date('H:i:s');
                    if (!$invoice) {
                        $invoice = generateUniqueInvoice($koneksi, 'ordermitra', 'D', $idmitra);
                    }

                    $stmtVariant->bind_param('i', $idproduk);
                    $stmtVariant->execute();
                    $dataProduk = $stmtVariant->get_result()->fetch_assoc();
                    $disc       = $dataProduk['disc'] ?? 0;

                    $stmtInsertOrd->bind_param('sidddsdsd', $idmitra, $idproduk, $hargaItem, $jmlhbaru, $subtotal, $invoice, $berattotal, $waktu, $disc);
                    $stmtInsertOrd->execute();

                    $stmtDeleteKrj->bind_param('is', $idker, $idmitra);
                    $stmtDeleteKrj->execute();

                    $berat += $berattotal;
                }
            }

            foreach ($non_bundling_items as $item) {
                $idproduk   = (int) $item['idproduk'];
                $hargaItem  = $item['harga'];
                $jmlhbaru   = $item['jmlh'];
                $subtotal   = $item['subtotal'];
                $berattotal = $item['berat'] * $jmlhbaru;
                $idker      = (int) $item['idkeranjang'];
                $waktu      = date('H:i:s');

                if (!$invoice) {
                    $invoice = generateUniqueInvoice($koneksi, 'ordermitra', 'D', $idmitra);
                }

                $stmtVariant->bind_param('i', $idproduk);
                $stmtVariant->execute();
                $dataProduk = $stmtVariant->get_result()->fetch_assoc();
                $disc       = $dataProduk['disc'] ?? 0;

                $stmtInsertOrd->bind_param('sidddsdsd', $idmitra, $idproduk, $hargaItem, $jmlhbaru, $subtotal, $invoice, $berattotal, $waktu, $disc);
                $stmtInsertOrd->execute();

                $stmtDeleteKrj->bind_param('is', $idker, $idmitra);
                $stmtDeleteKrj->execute();

                $berat += $berattotal;
            }

            $koneksi->commit();
        } catch (RuntimeException $e) {
            $koneksi->rollback();
            $_SESSION['message'] = $e->getMessage();
            header('Location: view_cart.php');
            exit;
        }

        header('Location: formpengirimanb.php?id=' . rawurlencode($invoice) . '&berat=' . rawurlencode($berat));
        exit;
    }
