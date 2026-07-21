<?php
    var_dump('as');
    die();
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include "koneksi.php";

    if(isset($_POST['save'])){    
        $idmitra            = $_SESSION["idmitramarketer"];
        $jmlh               = $_POST["jmlh"];
        $harga              = $_POST["harga"];
        $jmlhbaru           = $_POST["jmlhbaru"];
        $idkeranjang        = $_POST["idkeranjang"];
        $idprodukubah       = $_POST["idprodukubah"];
        $idkeranjangubah    = $_POST["idkeranjangubah"];
        $jumlah_dipilih     = count($idprodukubah);
            
         for($x = 0; $x < $jumlah_dipilih; $x++){
            $datastock   = $koneksi->query("SELECT stock FROM variants WHERE id = '$idprodukubah[$x]' ");
            $tampil      = $datastock->fetch_assoc();
            $stock       = $tampil["stock"];
            $selisih[$x] = $jmlhbaru[$x] - $jmlh[$x];

            if($selisih[$x] <= $stock){
                $koneksi->query("UPDATE keranjang SET jmlh = '$jmlhbaru[$x]', subtotal = $harga[$x] * $jmlhbaru[$x] 
                                WHERE idkeranjang = '$idkeranjangubah[$x]' ");
                $total[$x] = $stock - $selisih[$x];
                $koneksi->query("UPDATE variants SET stock = '$total[$x]' WHERE id = '$idprodukubah[$x]' ");
                $_SESSION['message'] = 'Keranjang Berhasil di Simpan ';
                echo "<script>location='view_cart2.php';</script>";
            }
            if($selisih[$x] >= $stock){
                $_SESSION['message'] = 'Stock Kami tidak mencukupi jumlah yang diminta, Silahkan Periksa Lagi Stock yang Tersedia';
                echo "<script>location='view_cart2.php';</script>";
            }
        }
    } else if(isset($_POST['checkout'])){
        date_default_timezone_set('Asia/Jakarta');
        $today              = date("mdHis");
        $idmitra            = $_SESSION["idmitramarketer"];
        $id                 = 'M';
        $idkeranjang        = $_POST["idkeranjang"];
        $queryMitra         = $koneksi->query("SELECT idadmin, idmitraagen FROM mitramarketer WHERE idmitramarketer = '$idmitra'");
        $dataMitra          = $queryMitra->fetch_assoc();
        $jumlah_dipilih     = count($idkeranjang);
        $idDB               = $dataMitra['idadmin'];
        $idAgen             = $dataMitra['idmitraagen'];
        $berat              = 0;

        if ($idkeranjang == '') {
            $_SESSION['message'] = 'Check salah satu barang yang ingin di checkout';
            echo "<script>location='view_cart2.php';</script>";
            return false;
        }
        $total_jumlah = 0
        for ($i = 0; $i < $jumlah_dipilih; $i++) {
            $data = $koneksi->query("SELECT keranjang.idproduk, keranjang.jmlh, keranjang.harga,
                                            keranjang.subtotal, variants.berat
                                            FROM keranjang
                                            INNER JOIN variants ON variants.id = keranjang.idproduk
                                            WHERE idkeranjang = '$idkeranjang[$i]'
                                    ");
            $tampilkan      = $data->fetch_assoc();
            $jmlhbaru       = $tampilkan['jmlh'];
            $total_jumlah   += $jmlhbaru;
        }
        for ($x = 0; $x < $jumlah_dipilih; $x++) {
            $beratsatu  = 0;
            $data       = $koneksi->query("SELECT keranjang.idproduk, keranjang.jmlh, keranjang.harga,
                                                    keranjang.subtotal, variants.berat
                                                    FROM keranjang
                                                    INNER JOIN variants ON variants.id = keranjang.idproduk
                                                    WHERE idkeranjang = '$idkeranjang[$x]'
                                        ");
            $tampilkan  = $data->fetch_assoc();
            $idproduk   = $tampilkan["idproduk"];
            $harga      = $tampilkan["harga"];
            $jmlhbaru   = $tampilkan["jmlh"];
            $subtotal   = $tampilkan["subtotal"];
            $beratsatu  = $tampilkan["berat"];
            $berattotal = $beratsatu * $jmlhbaru;

            if ($total_jumlah % 4 == 0) {
                $sql_produk = $koneksi->query("SELECT namaproduk FROM products
                                                INNER JOIN variants ON variants.idproducts = products.id
                                                WHERE variants.id = '$idproduk'
                                                AND (variants.jenis LIKE '%Promo%')
                                            ");
                if ($sql_produk->num_rows == 1) {
                    $invoice = $id . "P" . $idmitra . $today;
                } else {
                    $invoice = $id . $idmitra . $today;
                }

                $koneksi->query("INSERT INTO ordermarketer (`idorder`, `idmitramarketer`, `idmitraagen`, `iddb`, 
                                                            `idproduk`, `harga`, `jumlah`, `subtotal`, `tgl`, `invoice`, 
                                                            `status`, `payment`, `berat`, `status_progres`)
                                    VALUES (NULL, '$idmitra', '$idAgen', '$idDB', '$idproduk', '$harga', '$jmlhbaru', '$subtotal',
                                            NOW(), '$invoice', 'Pending', 'Belum Bayar', '$berattotal', 0)
                                ");
                $koneksi->query("DELETE FROM keranjang WHERE idkeranjang = '$idkeranjang[$x]'");
                $berat = $berat + $berattotal;
                echo "<script>location='formpengiriman.php?id=$invoice&berat=$berat';</script>";       
            } else {
                $_SESSION['message'] = 'QTY / Jumlah harus kelipatan 4!';
                echo "<script>location='view_cart.php';</script>";
            }
        }
    }
?>
