<?php
    session_start();
    include "koneksi.php";
    if(isset($_POST['save'])){
        $idprodukubah       = $_POST["idproduk"];
        $harga              = $_POST["harga"];
        $idkeranjangubah    = $_POST["idkeranjang"];
        $idmitra            = $_POST["idmitra"];
        $jmlh               = $_POST["jmlh"];
        $jmlhbaru           = $_POST["jmlhbaru"];
        $jumlah_dipilih     = count($idprodukubah);
            
         for($x=0; $x < $jumlah_dipilih; $x++){
            $datastock   = $koneksi->query("SELECT stock FROM produk WHERE idproduk = '$idprodukubah[$x]'");
            $tampil      = $datastock->fetch_assoc();
            $stock       = $tampil["stock"];
            $selisih[$x] = $jmlhbaru[$x] - $jmlh[$x];

            if($selisih[$x] <= $stock){
                $koneksi->query("UPDATE keranjang SET jmlh = '$jmlhbaru[$x]', subtotal = $harga[$x]*$jmlhbaru[$x] 
                                WHERE idkeranjang = '$idkeranjangubah[$x]'");
                $total[$x] = $stock - $selisih[$x];
                $koneksi->query("UPDATE produk SET stock = '$total[$x]' WHERE idproduk = '$idprodukubah[$x]' ");
                $_SESSION['message'] = 'Keranjang Berhasil di Simpan ';
                echo "<script>location='view_cart2.php';</script>";
            }
            if($selisih[$x] >= $stock){
                $_SESSION['message'] = 'Stock Kami tidak mencukupi jumlah yang diminta, Silahkan Periksa Lagi Stock yang Tersedia';
                echo "<script>location='view_cart2.php';</script>";
            }
        }
    } elseif (isset($_POST['checkout'])) {
        try {
            date_default_timezone_set('Asia/Jakarta');
            $today          = date("mdHis");
            $idmitra        = $_POST["idmitraagen"];
            $id             = 'A';
            $getMitra       = $koneksi->query("SELECT * FROM mitraagen WHERE idmitraagen = '$idmitra'");
            $dataMitra      = $getMitra->fetch_assoc();
            $iddb           = $dataMitra['idadmin'];
            $idkeranjang    = $_POST["idkeranjang"];
            $jumlah_dipilih = count($idkeranjang);
            $berat          = 0;

            if ($idkeranjang == '') {
                $_SESSION['message'] = 'Check salah satu barang yang ingin di checkout';
                echo "<script>location='store4.php';</script>";
                return false;
            }

            $total_jumlah = 0;
            for ($i = 0; $i < count($idkeranjang); $i++) {
                $data = $koneksi->query("SELECT 
                                                keranjang.idproduk,
                                                keranjang.jmlh,
                                                keranjang.harga,
                                                keranjang.subtotal,
                                                produk.berat
                                            FROM
                                                keranjang
                                                    INNER JOIN
                                                produk ON keranjang.idproduk = produk.idproduk
                                            WHERE
                                                idkeranjang = '$idkeranjang[$i]'
                                        ");
                $tampilkan = $data->fetch_assoc();
                $jmlhbaru = $tampilkan["jmlh"];
                $total_jumlah += $jmlhbaru;
            }
            
            for ($x = 0; $x < $jumlah_dipilih; $x++) {
                $beratsatu = 0;
                $data = $koneksi->query("SELECT 
                                                keranjang.idproduk,
                                                keranjang.jmlh,
                                                keranjang.harga,
                                                keranjang.subtotal,
                                                produk.berat
                                            FROM
                                                keranjang
                                                    INNER JOIN
                                                produk ON keranjang.idproduk = produk.idproduk
                                            WHERE
                                                idkeranjang = '$idkeranjang[$x]'
                                        ");
                $tampilkan = $data->fetch_assoc();
                $idproduk = $tampilkan["idproduk"];
                $harga = $tampilkan["harga"];
                $jmlhbaru = $tampilkan["jmlh"];
                $subtotal = $tampilkan["subtotal"];
                $beratsatu = $tampilkan["berat"];
                $berattotal = $beratsatu * $jmlhbaru;

                if ($total_jumlah % 4 == 0) {
                    $sql_produk = $koneksi->query("SELECT namaproduk FROM produk WHERE idproduk = '$idproduk' AND (jenis LIKE '%Promo%' OR jenis LIKE '%Sale%')");
                    if ($sql_produk->num_rows == 1) {
                        $invoice = $id . "P" . $idmitra . $today;
                    } else {
                        $invoice = $id . $idmitra . $today;
                    }

                    $koneksi->query("INSERT INTO orderagen
                                            (idorder, idmitraagen, iddb,
                                            idproduk, harga, jumlah,
                                            subtotal, tgl, invoice,
                                            status, payment, berat) 
                                        VALUES
                                            (NULL, '$idmitra', '$iddb',
                                            '$idproduk', '$harga', '$jmlhbaru',
                                            '$subtotal', NOW(), '$invoice',
                                            'Pending', 'Belum Bayar', '$berattotal')
                                    ");
                    $koneksi->query("DELETE FROM keranjang WHERE idkeranjang = '$idkeranjang[$x]'");
                    $berat = $berat + $berattotal;
                    echo "<script>location='formpengiriman.php?id=$invoice&berat=$berat';</script>";
                    // echo "<script>location='dataorder.php?id=$invoice';</script>";
                } else {
                    $_SESSION['message'] = 'QTY / Jumlah harus kelipatan 4!';
                    echo "<script>location='view_cart2.php';</script>";
                }
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>