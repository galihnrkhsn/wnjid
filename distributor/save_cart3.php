<?php
    session_start();
    if(isset($_POST['save'])){
        include "koneksi.php";

        $idproduk = $_POST["idproduk"];
        $idmitra = $_POST["idmitra"];
        $idkeranjang = $_POST["idkeranjang"];
        $idkeranjangubah = $_POST["idkeranjangubah"];
        $idprodukubah = $_POST["idprodukubah"];
        $jmlh = $_POST["jmlh"];
        $jmlhbaru = $_POST["jmlhbaru"];
        $harga = $_POST["harga"];
        $jumlah_dipilih = count($idprodukubah);
        // $stock=$_POST["stock"];

        for( $x = 0; $x < $jumlah_dipilih; $x++ ){
            $datastock = $koneksi->query("SELECT stock FROM produk WHERE idproduk='$idprodukubah[$x]' ");
            $tampil = $datastock->fetch_assoc();
            $stock = $tampil["stock"];

            $selisih[$x] = $jmlhbaru[$x] - $jmlh[$x];

            if ($selisih[$x] <= $stock ){
                $koneksi->query("UPDATE keranjang SET
                                        jmlh = '$jmlhbaru[$x]',
                                        subtotal = $harga[$x] * $jmlhbaru[$x] 
                                    WHERE 
                                        idkeranjang = '$idkeranjangubah[$x]'
                                ");
                $total[$x] = $stock - $selisih[$x];
                $koneksi->query("UPDATE produk SET stock='$total[$x]' WHERE idproduk='$idprodukubah[$x]'");
                $_SESSION['message'] = 'Keranjang Berhasil disimpan!';
                echo "<script>location='view_cart2.php';</script>";
            }

            if( $selisih[$x] >= $stock ){
                $_SESSION['message'] = 'Stock Kami tidak mencukupi jumlah yang diminta, Silahkan Periksa Lagi Stock yang Tersedia';
                echo "<script>location='view_cart2.php';</script>";
            }
        }
    } elseif (isset($_POST['checkout'])) {
        include "koneksi.php";

        // Ambil Data yang Dikirim dari Form
        date_default_timezone_set('Asia/Jakarta');
        $today = date("mdHis");
        $waktu = date('H:i:s');
        $idmitra = $_POST["idmitra"];
        $id = 'D';
        $idkeranjang = $_POST["idkeranjang"]; 
        $jumlah_dipilih = count($idkeranjang);
        $berat = 0;

        if ( $idkeranjang == '' ) {
            $_SESSION['message'] = 'Check salah satu barang yang ingin di checkout';
            echo "<script>location='view_cart2.php';</script>";
            return false;
        }
            for( $x = 0; $x < $jumlah_dipilih; $x++ ){
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
                $total_harga = $tampilkan["harga"];
                $jmlhbaru = $tampilkan["jmlh"];
                $subtotal = $tampilkan["subtotal"];
                $beratsatu = $tampilkan["berat"];
                $berattotal = $beratsatu * $jmlhbaru;

                $harga = $total_harga;
                $sql_produk = $koneksi->query("SELECT namaproduk FROM produk WHERE idproduk = '$idproduk' AND (jenis LIKE '%Promo%' OR jenis LIKE '%Sale%')");
                if ($sql_produk->num_rows == 1) {
                    $invoice = $id . "P" . $idmitra . $today;
                } else {
                    $invoice = $id . $idmitra . $today;
                }

                $koneksi->query("INSERT INTO ordermitra
                                        (idorder, idmitra, idproduk,
                                        harga, jumlah, subtotal,
                                        tgl, invoice, status,
                                        payment, berat, waktu) 
                                    VALUES
                                        (NULL, '$idmitra', '$idproduk',
                                        '$harga', '$jmlhbaru', '$subtotal',
                                        NOW(), '$invoice', 'Pending',
                                        'Belum Bayar', '$berattotal', '$waktu')
                                ");
                $koneksi->query("DELETE FROM keranjang WHERE idkeranjang = '$idkeranjang[$x]'");
                $berat = $berat + $berattotal;
            }
            echo "<script>location='formpengirimanb.php?id=$invoice&berat=$berat';</script>";
        }
?>
