<?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include 'koneksi.php';

    if(isset($_POST['save'])){
        $idprodukubah       = $_POST["idprodukubah"];
        $harga              = $_POST["harga"];
        $idkeranjangubah    = $_POST["idkeranjangubah"];
        $idmitra            = $_SESSION["idmitrareseller"];
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
            } 
            if( $selisih[$x] >= $stock ){
                $_SESSION['message'] = 'Stock Kami tidak mencukupi jumlah yang diminta, Silahkan Periksa Lagi Stock yang Tersedia';
                echo "<script>location='view_cart.php';</script>";
            }
        }
    } elseif (isset($_POST['checkout'])) {
        date_default_timezone_set('Asia/Jakarta');
        $today          = date("mdHis");
        $idmitra        = $_SESSION["idmitrareseller"];
        $id             = 'R';
        $idkeranjang    = $_POST["idkeranjang"];
        $jumlah_dipilih = count($idkeranjang);
        $user           = $koneksi->query("SELECT idadmin, idmitraagen FROM mitrareseller WHERE idmitrareseller = '$idmitra'");
        $dataUser       = $user->fetch_assoc();
        $iddb           = $dataUser['idadmin'];
        $idagen         = $dataUser['idmitraagen'];
        $berat          = 0;

        if ($idkeranjang == '') {
            $_SESSION['message'] = 'Check salah satu barang yang ingin di checkout';
            echo "<script>location='view_cart.php'</script>";
            return false;
        }
        $total_jumlah = 0;
        for ($i = 0; $i < $jumlah_dipilih; $i++) {
            $data = $koneksi->query("SELECT keranjang.idproduk, keranjang.jmlh, keranjang.harga,
                                            keranjang.subtotal, variants.berat
                                        FROM keranjang
                                        INNER JOIN variants ON variants.id = keranjang.idproduk
                                        WHERE idkeranjang = '$idkeranjang[$i]'
                                    ");
            $tampilkan      = $data->fetch_assoc();
            $jmlhbaru       = $tampilkan['jmlh'];
            $total_jumlah += $jmlhbaru;
        }
        for ($x = 0; $x < $jumlah_dipilih; $x++) {
            $beratsatu  = 0;
            $data       = $koneksi->query("SELECT keranjang.idproduk, keranjang.jmlh, keranjang.harga,
                                                keranjang.subtotal, variants.berat, variants.idproducts
                                            FROM keranjang
                                            INNER JOIN variants ON variants.id = keranjang.idproduk
                                            WHERE idkeranjang = '$idkeranjang[$x]'
                                        ");
            $tampilkan  = $data->fetch_assoc();
            $idproduk   = $tampilkan['idproduk'];
            $idproducts = $tampilkan['idproducts'];
            $harga      = $tampilkan["harga"];
            $jmlhbaru   = $tampilkan["jmlh"];
            $subtotal   = $tampilkan["subtotal"];
            $beratsatu  = $tampilkan["berat"];
            $berattotal = $beratsatu * $jmlhbaru;
            $sql_produk = $koneksi->query("SELECT namaproduk FROM products
                                            INNER JOIN variants ON variants.idproducts = products.id
                                            WHERE variants.id = '$idproduk'
                                            AND (variants.jenis LIKE '%Promo%')");
            if ($sql_produk->num_rows == 1) {
                $invoice = $id . 'P' . $idmitra . $today;
            } else {
                $invoice = $id . $idmitra . $today;
            }

            if ($idproducts == '') {
                if ($total_jumlah % 4 == 0) {
                    $koneksi->query("INSERT INTO orderreseller (`idorder`,`idmitrareseller`,`idmitraagen`,`iddb`,`idproduk`,`harga`,`jumlah`,`subtotal`,
                                            `tgl`,`invoice`,`status`,`payment`,`berat`,`no_sj`,`status_progres`)
                                        VALUES (NULL, '$idmitra', '$idagen', '$iddb', '$idproduk', '$harga', '$jmlhbaru', '$subtotal',
                                            NOW(), '$invoice', 'Pending', 'Belum Bayar', '$berattotal', NULL, 0)
                                    ");
                    $koneksi->query("DELETE FROM keranjang WHERE idkeranjang = '$idkeranjang[$x]'");
                    $berat = $berat + $berattotal;
                    echo "<script>location='formpengiriman.php?id=$invoice&berat=$berat';</script>";
                } else {
                    $_SESSION['message'] = 'QTY / Jumlah harus kelipatan 4!';
                    echo "<script>location='view_cart.php';</script>";
                }
            } else {
                $koneksi->query("INSERT INTO orderreseller (`idorder`,`idmitrareseller`,`idmitraagen`,`iddb`,`idproduk`,`harga`,`jumlah`,`subtotal`,
                                                            `tgl`,`invoice`,`status`,`payment`,`berat`,`no_sj`,`status_progres`)
                                    VALUES (NULL, '$idmitra', '$iddb', '$idagen', '$idproduk', '$harga', '$jmlhbaru', '$subtotal',
                                            NOW(), '$invoice', 'Pending', 'Belum Bayar', '$berattotal', NULL, 0)
                                ");
                $koneksi->query("DELETE FROM keranjang WHERE idkeranjang = '$idkeranjang[$x]'");
                $berat = $berat + $berattotal;
                echo "<script>location='formpengiriman.php?id=$invoice&berat=$berat';</script>";
            }

        }
    }
?>
