<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include "koneksi.php";
    include "assets/components/Sessions/sesDistri.php";

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

        $today          = date("mdHis");
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
            $idproducts = array_column($data_keranjang, 'idproducts');

            $categories = [
                'sarung_etnik'          => [2514],
                'voal_hampers'          => [2489],
                'koko_etnik'            => [2516,2517,2524,2525],
                'rail_sport'            => [2509,2510,2511],
                'outer_parotia'         => [2455],
                'khimar_kolibri_anak'   => [2336],
                'bergo_fiarca'          => [2230],
                'limicola_abaya'        => [2226,2248],
                'konin_25'              => [
                                                2521,2527,
                                                2528,2522,2520,2519,
                                                2565,2526,2523,2529,2531,2530,2566
                                            ],
                'konin_24'              => [2422,2421,2420,2412,2413,2414,2417,2418,2416,2415,2419],
                'sula_scarves'          => [2502],
                'mukena_skena'          => [2515],
                'kolibri_25'            => [2513],
                'kolibri_22'            => [2334],
                'kolibri_24'            => [2400,2391,2394,2398,2399,2395,2396,2397],
                'kolibri_luxury'        => [2558,2559],
                'kolibri_sarung'        => [2536]
            ];

            $productCategories = [];
            foreach ($idproducts as $id) {
                foreach ($categories as $catName => $catIds) {
                    if (in_array($id, $catIds)) {
                        $productCategories[] = $catName;
                        break;
                    }
                }
            }

            $all_same_category = count(array_unique($productCategories)) === 1;

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

        $bundling5_items     = array_filter($data_keranjang, fn($item) => $item['jenis'] === 'Bundling 5');
        $bundling3_items     = array_filter($data_keranjang, fn($item) => $item['jenis'] === 'Bundling 3');
        $bundling_items      = array_filter($data_keranjang, fn($item) => $item['jenis'] === 'bundling');
        $non_bundling_items  = array_filter($data_keranjang, fn($item) =>
            $item['jenis'] !== 'bundling' &&
            $item['jenis'] !== 'Bundling 5' &&
            $item['jenis'] !== 'Bundling 3'
        );

        $koneksi->begin_transaction();

        $stmtVariant   = $koneksi->prepare("SELECT * FROM variants WHERE variants.id = ?");
        $stmtInsertOrd = $koneksi->prepare("INSERT INTO ordermitra
                                (idorder, idmitra, idproduk, harga, jumlah, subtotal, tgl, invoice, status, payment, berat, waktu, disc)
                                VALUES (NULL, ?, ?, ?, ?, ?, NOW(), ?, 'Pending', 'Belum Bayar', ?, ?, ?)");
        $stmtDeleteKrj = $koneksi->prepare("DELETE FROM keranjang WHERE idkeranjang = ? AND idmitra = ?");

        try {
            if (count($bundling_items) > 0) {
                $idproducts          = array_column($bundling_items, 'idproducts');
                $total_qty_bundling  = array_sum(array_column($bundling_items, 'jmlh'));
                $all_same_idproducts = count(array_unique($idproducts)) === 1;

                if (!$all_same_idproducts) {
                    throw new RuntimeException('Produk dalam bundling harus sama!');
                }
                if ($total_qty_bundling % 3 !== 0) {
                    throw new RuntimeException('QTY Bundling harus kelipatan 3');
                }

                foreach ($bundling_items as $item) {
                    $idproduk   = (int) $item['idproduk'];
                    $hargaItem  = $item['harga'];
                    $jmlhbaru   = $item['jmlh'];
                    $subtotal   = $item['subtotal'];
                    $berattotal = $item['berat'] * $jmlhbaru;
                    $idker      = (int) $item['idkeranjang'];
                    $waktu      = date('H:i:s');
                    if (!$invoice) {
                        $invoice = "D" . $idmitra . $today;
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

            if (count($bundling5_items) > 0) {
                $idproducts          = array_column($bundling5_items, 'idproducts');
                $total_qty_bundling  = array_sum(array_column($bundling5_items, 'jmlh'));
                $all_same_idproducts = count(array_unique($idproducts)) === 1;

                if (!$all_same_idproducts) {
                    throw new RuntimeException('Produk dalam bundling harus sama!');
                }
                if ($total_qty_bundling % 5 !== 0) {
                    throw new RuntimeException('QTY Bundling harus kelipatan 5');
                }

                foreach ($bundling5_items as $item) {
                    $idproduk   = (int) $item['idproduk'];
                    $hargaItem  = $item['harga'];
                    $jmlhbaru   = $item['jmlh'];
                    $subtotal   = $item['subtotal'];
                    $berattotal = $item['berat'] * $jmlhbaru;
                    $idker      = (int) $item['idkeranjang'];
                    $waktu      = date('H:i:s');
                    if (!$invoice) {
                        $invoice = "D" . $idmitra . $today;
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

            if (count($bundling3_items) > 0) {
                $idproducts          = array_column($bundling3_items, 'idproducts');
                $total_qty_bundling  = array_sum(array_column($bundling3_items, 'jmlh'));
                $all_same_idproducts = count(array_unique($idproducts)) === 1;

                if (!$all_same_idproducts) {
                    throw new RuntimeException('Produk dalam bundling harus sama!');
                }
                if ($total_qty_bundling % 3 !== 0) {
                    throw new RuntimeException('QTY Bundling harus kelipatan 3');
                }

                foreach ($bundling3_items as $item) {
                    $idproduk   = (int) $item['idproduk'];
                    $hargaItem  = $item['harga'];
                    $jmlhbaru   = $item['jmlh'];
                    $subtotal   = $item['subtotal'];
                    $berattotal = $item['berat'] * $jmlhbaru;
                    $idker      = (int) $item['idkeranjang'];
                    $waktu      = date('H:i:s');
                    if (!$invoice) {
                        $invoice = "D" . $idmitra . $today;
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

            $stmtPromoCheck = $koneksi->prepare("SELECT namaproduk
                                            FROM products
                                            INNER JOIN variants ON variants.idproducts = products.id
                                            WHERE variants.id = ?
                                            AND (variants.jenis LIKE '%Promo%' OR variants.jenis LIKE '%Sale%')");

            foreach ($non_bundling_items as $item) {
                $idproduk   = (int) $item['idproduk'];
                $hargaItem  = $item['harga'];
                $jmlhbaru   = $item['jmlh'];
                $subtotal   = $item['subtotal'];
                $berattotal = $item['berat'] * $jmlhbaru;
                $idker      = (int) $item['idkeranjang'];
                $waktu      = date('H:i:s');

                if (!$invoice) {
                    $stmtPromoCheck->bind_param('i', $idproduk);
                    $stmtPromoCheck->execute();
                    $sql_produk = $stmtPromoCheck->get_result();
                    $invoice    = ($sql_produk->num_rows == 1) ? ('DP' . $idmitra . $today) : ('D' . $idmitra . $today);
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
