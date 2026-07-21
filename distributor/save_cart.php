<?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include "koneksi.php";
    
    if(isset($_POST['save'])){
        $idprodukubah       = $_POST["idprodukubah"];
        $harga              = $_POST["harga"];
        $idkeranjangubah    = $_POST["idkeranjangubah"];
        $idmitra            = $_POST["idmitra"];
        $jmlh               = $_POST["jmlh"];
        $jmlhbaru           = $_POST["jmlhbaru"];
        $idkeranjang        = $_POST['idkeranjang'];
        $jumlah_dipilih     = count($idprodukubah);

        for( $x = 0; $x < $jumlah_dipilih; $x++ ){
            $datastock      = $koneksi->query("SELECT stock FROM variants WHERE id = '$idprodukubah[$x]' ");
            $tampil         = $datastock->fetch_assoc();
            $stock          = $tampil["stock"];
            $selisih[$x]    = $jmlhbaru[$x] - $jmlh[$x];

            if( $selisih[$x] <= $stock ){
                $koneksi->query("UPDATE keranjang SET jmlh = '$jmlhbaru[$x]', subtotal = $harga[$x] * $jmlhbaru[$x] 
                                WHERE idkeranjang = '$idkeranjangubah[$x]'");
                $total[$x] = $stock - $selisih[$x];
                $koneksi->query("UPDATE variants SET stock = '$total[$x]' WHERE id = '$idprodukubah[$x]'");
                $_SESSION['message'] = 'Keranjang Berhasil di Simpan ';
                echo "<script>location='view_cart.php';</script>";
            } else {}
            if( $selisih[$x] >= $stock ){
                $_SESSION['message'] = 'Stock Kami tidak mencukupi jumlah yang diminta, Silahkan Periksa Lagi Stock yang Tersedia';
                echo "<script>location='view_cart.php';</script>";
            }
        }
    } elseif(isset($_POST['checkout'])){
        date_default_timezone_set('Asia/Jakarta');

        if (!isset($_POST['idkeranjang']) || empty($_POST['idkeranjang'])) {
            $_SESSION['message'] = 'Check salah satu barang yang ingin di checkout';
            echo "<script>location='view_cart.php';</script>";
            return false;
        }

        $today              = date("mdHis");
        $idmitra            = $_POST["idmitra"];
        $id                 = 'D';
        $idkeranjang        = $_POST["idkeranjang"];
        $jenis              = $_POST["jenis"] ?? NULL;
        $jumlah_dipilih     = count($idkeranjang);
        $berat              = 0;
        $total_jumlah       = 0;
        $invoice            = '';
        $data_keranjang     = [];

        for ( $i = 0; $i < $jumlah_dipilih; $i++ ) {
            $data = $koneksi->query("SELECT 
                                             keranjang.idproduk,
                                             keranjang.idkeranjang,
                                             keranjang.jmlh,
                                             keranjang.harga,
                                             keranjang.subtotal,
                                             variants.berat,
                                             variants.jenis,
                                             variants.idproducts
                                         FROM
                                             keranjang
                                                 INNER JOIN
                                             variants ON keranjang.idproduk = variants.id
                                         WHERE
                                             idkeranjang = '$idkeranjang[$i]'
                                     ");
            $item               = $data->fetch_assoc();
            $data_keranjang[]   = $item;
        }

        if ($jenis == 'b1g1') {
            $idproducts             = array_column($data_keranjang, 'idproducts');

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

        $bundling5_items        = array_filter($data_keranjang, fn($item) => $item['jenis'] === 'Bundling 5');
        $bundling3_items        = array_filter($data_keranjang, fn($item) => $item['jenis'] === 'Bundling 3');
        $bundling_items         = array_filter($data_keranjang, fn($item) => $item['jenis'] === 'bundling');
        $non_bundling_items = array_filter($data_keranjang, fn($item) => 
            $item['jenis'] !== 'bundling' &&
            $item['jenis'] !== 'Bundling 5' &&
            $item['jenis'] !== 'Bundling 3'
        );

        if (count($bundling_items) > 0) {
            $idproducts             = array_column($bundling_items, 'idproducts');
            $total_qty_bundling     = array_sum(array_column($bundling_items, 'jmlh'));
            $all_same_idproducts    = count(array_unique($idproducts)) === 1;

            if (!$all_same_idproducts) {
                $_SESSION['message'] = 'Produk dalam bundling harus sama!';
                echo "<script>location='view_cart.php'</script>";
                return false;
            }

            if ($total_qty_bundling % 3 !== 0) {
                $_SESSION['message'] = 'QTY Bundling harus kelipatan 3';
                echo "<script>location='view_cart.php'</script>";
                return false;
            }

            foreach ($bundling_items as $item) {
                $idproduk   = $item['idproduk'];
                $harga      = $item['harga'];
                $jmlhbaru   = $item['jmlh'];
                $subtotal   = $item['subtotal'];
                $berattotal = $item['berat'] * $jmlhbaru;
                $idker      = $item['idkeranjang'];
                if (!$invoice) {
                    $invoice = "D" . $idmitra . $today;
                }
                $dataProduk = $koneksi->query("SELECT * FROM variants WHERE variants.id = '$idproduk'")->fetch_assoc();
                $disc = $dataProduk['disc'] ?? 0;

                $koneksi->query("INSERT INTO ordermitra 
                                        (
                                            idorder, idmitra, idproduk, harga, jumlah, subtotal, tgl, invoice, status, payment, berat, waktu, disc
                                        ) 
                                    VALUES 
                                        (
                                            NULL, '$idmitra', '$idproduk', '$harga', '$jmlhbaru', '$subtotal', NOW(), 
                                            '$invoice', 'Pending', 'Belum Bayar', '$berattotal', '$waktu', '$disc'
                                        )
                                ");
                $koneksi->query("DELETE FROM keranjang WHERE idkeranjang = '$idker'");
                $berat += $berattotal;
            }
        }

        if (count($bundling5_items) > 0) {
            $idproducts             = array_column($bundling5_items, 'idproducts');
            $total_qty_bundling     = array_sum(array_column($bundling5_items, 'jmlh'));
            $all_same_idproducts    = count(array_unique($idproducts)) === 1;

            if (!$all_same_idproducts) {
                echo "<script>alert('Produk dalam bundling harus sama!')</script>";
                $_SESSION['message'] = 'Produk dalam bundling harus sama!';
                echo "<script>location='view_cart.php'</script>";
                return false;
            }

            if ($total_qty_bundling % 5 !== 0) {
                echo "<script>alert('QTY Bundling harus kelipatan 5!')</script>";
                $_SESSION['message'] = 'QTY Bundling harus kelipatan 5';
                echo "<script>location='view_cart.php'</script>";
                return false;
            }

            foreach ($bundling5_items as $item) {
                $idproduk   = $item['idproduk'];
                $harga      = $item['harga'];
                $jmlhbaru   = $item['jmlh'];
                $subtotal = $item['subtotal'];
                $berattotal = $item['berat'] * $jmlhbaru;
                $idker      = $item['idkeranjang'];
                $waktu      = date('H:i:s');

                if (!$invoice) {
                    $invoice = "D" . $idmitra . $today;
                }

                $dataProduk = $koneksi->query("SELECT * FROM variants WHERE variants.id = '$idproduk'")->fetch_assoc();
                $disc = $dataProduk['disc'] ?? 0;

                $koneksi->query("INSERT INTO ordermitra 
                                        (
                                            idorder, idmitra, idproduk, harga, jumlah, subtotal, tgl, invoice, status, payment, berat, waktu, disc
                                        ) 
                                    VALUES 
                                        (
                                            NULL, '$idmitra', '$idproduk', '$harga', '$jmlhbaru', '$subtotal', NOW(), 
                                            '$invoice', 'Pending', 'Belum Bayar', '$berattotal', '$waktu', '$disc'
                                        )
                                ");
                $koneksi->query("DELETE FROM keranjang WHERE idkeranjang = '$idker'");
                $berat += $berattotal;
            }
        }

        if (count($bundling3_items) > 0) {
            $idproducts             = array_column($bundling3_items, 'idproducts');
            $total_qty_bundling     = array_sum(array_column($bundling3_items, 'jmlh'));
            $all_same_idproducts    = count(array_unique($idproducts)) === 1;

            if (!$all_same_idproducts) {
                echo "<script>alert('Produk dalam bundling harus sama!')</script>";
                $_SESSION['message'] = 'Produk dalam bundling harus sama!';
                echo "<script>location='view_cart.php'</script>";
                return false;
            }

            if ($total_qty_bundling % 3 !== 0) {
                echo "<script>alert('QTY Bundling harus kelipatan 3!')</script>";
                $_SESSION['message'] = 'QTY Bundling harus kelipatan 3';
                echo "<script>location='view_cart.php'</script>";
                return false;
            }

            foreach ($bundling3_items as $item) {
                $idproduk   = $item['idproduk'];
                $harga      = $item['harga'];
                $jmlhbaru   = $item['jmlh'];
                $subtotal = $item['subtotal'];
                $berattotal = $item['berat'] * $jmlhbaru;
                $idker      = $item['idkeranjang'];
                $waktu      = date('H:i:s');

                if (!$invoice) {
                    $invoice = "D" . $idmitra . $today;
                }

                $dataProduk = $koneksi->query("SELECT * FROM variants WHERE variants.id = '$idproduk'")->fetch_assoc();
                $disc = $dataProduk['disc'] ?? 0;

                $koneksi->query("INSERT INTO ordermitra 
                                        (
                                            idorder, idmitra, idproduk, harga, jumlah, subtotal, tgl, invoice, status, payment, berat, waktu, disc
                                        ) 
                                    VALUES 
                                        (
                                            NULL, '$idmitra', '$idproduk', '$harga', '$jmlhbaru', '$subtotal', NOW(), 
                                            '$invoice', 'Pending', 'Belum Bayar', '$berattotal', '$waktu', '$disc'
                                        )
                                ");
                $koneksi->query("DELETE FROM keranjang WHERE idkeranjang = '$idker'");
                $berat += $berattotal;
            }
        }

        foreach ($non_bundling_items as $item) {
            $idproduk   = $item['idproduk'];
            $harga      = $item['harga'];
            $jmlhbaru   = $item['jmlh'];
            $subtotal   = $item['subtotal'];
            $berattotal = $item['berat'] * $jmlhbaru;
            $idker      = $item['idkeranjang'];
            $waktu      = date('H:i:s');

            if (!$invoice) {
                $sql_produk = $koneksi->query("SELECT namaproduk 
                                                FROM products
                                                INNER JOIN variants ON variants.idproducts = products.id
                                                WHERE variants.id = '$idproduk'
                                                AND (variants.jenis LIKE '%Promo%' OR variants.jenis LIKE '%Sale%')
                                            ");
                if ($sql_produk->num_rows == 1) {
                    $invoice = 'DP' . $idmitra . $today;
                } else {
                    $invoice = 'D' . $idmitra . $today;
                }
            }
            $dataProduk = $koneksi->query("SELECT * FROM variants WHERE variants.id = '$idproduk'")->fetch_assoc();
            $disc = $dataProduk['disc'] ?? 0;

            $koneksi->query("INSERT INTO ordermitra 
                                    (
                                        idorder, idmitra, idproduk, harga, jumlah, subtotal, tgl, invoice, status, payment, berat, waktu, disc
                                    ) 
                                VALUES 
                                    (
                                        NULL, '$idmitra', '$idproduk', '$harga', '$jmlhbaru', '$subtotal', NOW(), 
                                        '$invoice', 'Pending', 'Belum Bayar', '$berattotal', '$waktu', '$disc'
                                    )
                            ");
            $koneksi->query("DELETE FROM keranjang WHERE idkeranjang = '$idker'");
            $berat += $berattotal;
        }
        echo "<script>location='formpengirimanb.php?id=$invoice&berat=$berat';</script>";
    }
?>
