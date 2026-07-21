<?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include "koneksi.php";

    if(isset($_POST['save'])){    
        $idmitra            = $_POST["idmitra"];
        $idkeranjang        = $_POST["idkeranjang"];
        $idkeranjangubah    = $_POST["idkeranjangubah"];
        $idprodukubah       = $_POST["idprodukubah"];
        $jmlh               = $_POST["jmlh"];
        $jmlhbaru           = $_POST["jmlhbaru"];
        $harga              = $_POST["harga"];
        $jumlah_dipilih     = count($idprodukubah);
            
         for($x = 0; $x < $jumlah_dipilih; $x++){
            $datastock   = $koneksi->query("SELECT stock FROM produk WHERE idproduk = '$idprodukubah[$x]' ");
            $tampil      = $datastock->fetch_assoc();
            $stock       = $tampil["stock"];
            $selisih[$x] = $jmlhbaru[$x] - $jmlh[$x];

            if($selisih[$x] <= $stock){
                $koneksi->query("UPDATE keranjang SET jmlh = '$jmlhbaru[$x]', subtotal = $harga[$x] * $jmlhbaru[$x] 
                                WHERE idkeranjang = '$idkeranjangubah[$x]' ");
                $total[$x] = $stock - $selisih[$x];
                $koneksi->query("UPDATE produk SET stock = '$total[$x]' WHERE idproduk = '$idprodukubah[$x]' ");
                $_SESSION['message'] = 'Keranjang Berhasil di Simpan ';
                echo "<script>location='view_cart.php';</script>";
            }

            if($selisih[$x] >= $stock){
                $_SESSION['message'] = 'Stock Kami tidak mencukupi jumlah yang diminta, Silahkan Periksa Lagi Stock yang Tersedia';
                echo "<script>location='view_cart2.php';</script>";
            }
        }
    } elseif (isset($_POST['checkout'])) {
        date_default_timezone_set('Asia/Jakarta');
        $today = date("mdHis");
        $idmitra = $_POST["idmitramarketer"];
        $queryMitra = $koneksi->query("SELECT * FROM mitramarketer WHERE idmitramarketer = '$idmitra'");
        $dataMitra = $queryMitra->fetch_assoc();
        $id = 'M';
        $idkeranjang = $_POST["idkeranjangubah"];
        $jumlah_dipilih = count($idkeranjang);
        $idDB = $dataMitra['idadmin'];
        $idAgen = $dataMitra['idmitraagen'];
        $berat = 0;

        if ($idkeranjang == '') {
            $_SESSION['message'] = 'Check salah satu barang yang ingin di checkout';
            echo "<script>location='view_cart.php';</script>";
            return false;
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

            if ($jmlhbaru % 4 == 0) {
                $sql_produk = $koneksi->query("SELECT namaproduk FROM produk WHERE idproduk = '$idproduk' AND (jenis LIKE '%Promo%' OR jenis LIKE '%Sale%')");
                if ($sql_produk->num_rows == 1) {
                    $invoice = $id . "P" . $idmitra . $today;
                } else {
                    $invoice = $id . $idmitra . $today;
                }
                $koneksi->query("INSERT INTO ordermarketer
                                        (idorder, idmitramarketer, idmitraagen,
                                        iddb, idproduk, harga,
                                        jumlah, subtotal, tgl,
                                        invoice, status, payment, berat) 
                                    VALUES
                                        (NULL, '$idmitra', '$idAgen',
                                        '$idDB', '$idproduk', '$harga',
                                        '$jmlhbaru', '$subtotal', NOW(),
                                        '$invoice', 'Pending', 'Belum Bayar', 
                                        '$berattotal')
                                ");
                $koneksi->query("DELETE FROM keranjang WHERE idkeranjang = '$idkeranjang[$x]'");
                $berat = $berat + $berattotal;
                echo "<script>location='formpengiriman.php?id=$invoice&berat=$berat';</>";
            } else {
                $_SESSION['message'] = 'QTY / Jumlah harus kelipatan 4!';
                echo "<script>location='view_cart.php';</script>";
            }
        }
    }
?>