<?php
    include "koneksi.php";
    session_start();
    if(isset($_POST['save'])){
        include "koneksi.php";
        $idproduk           = $_POST["idproduk"];
        $idmitramarketer    = $_POST["idmitramarketer"];
        $idkeranjang        = $_POST["idkeranjang"];
        $idkeranjangubah    = $_POST["idkeranjangubah"];
        $idprodukubah       = $_POST["idprodukubah"];
        $jmlh               = $_POST["jmlh"];
        $jmlhbaru           = $_POST["jmlhbaru"];
        $harga              = $_POST["harga"];
        $jumlah_dipilih     = count($idprodukubah);
            
         for($x=0; $x < $jumlah_dipilih; $x++){
            $datastock   = $koneksi->query("SELECT stock FROM produk WHERE idproduk='$idprodukubah[$x]' ");
            $tampil      = $datastock->fetch_assoc();
            $stock       = $tampil["stock"];
            $selisih[$x] = $jmlhbaru[$x] - $jmlh[$x];

            if($selisih[$x] <= $stock){
                $koneksi->query("UPDATE keranjang SET jmlh = '$jmlhbaru[$x]', subtotal = $harga[$x]*$jmlhbaru[$x] 
                                WHERE idkeranjang='$idkeranjangubah[$x]' ");
                $total[$x] = $stock - $selisih[$x];
                $koneksi->query("UPDATE produk SET stock = '$total[$x]' WHERE idproduk = '$idprodukubah[$x]' ");
                $_SESSION['message'] = 'Keranjang Berhasil di Simpan ';
                echo "<script>location='view_cart.php';</script>";
            }

            if($selisih[$x] >= $stock){
                $_SESSION['message'] = 'Stock Kami tidak mencukupi jumlah yang diminta, Silahkan Periksa Lagi Stock yang Tersedia';
                echo "<script>location='view_cart.php';</script>";
            }
        }
    } else if(isset($_POST['checkout'])){
        include "koneksi.php";
        date_default_timezone_set('Asia/Jakarta');
        $today              = date("mdHis");
        $idmitramarketer    = $_POST["idmitramarketer"];
        $marketer           = 'M';
        $idkeranjang        = $_POST["idkeranjang"];
        $jumlah_dipilih     = count($idkeranjang);
        $berat              = 0;

        if ($idkeranjang == '') {
            $_SESSION['message'] = 'Check salah satu barang yang ingin di checkout';
            echo "<script>location='view_cart.php';</script>";
            return false;
        } 
        for($x = 0; $x < $jumlah_dipilih; $x++){
            $beratsatu  = 0;
            $data       = $koneksi->query("SELECT keranjang.idproduk,keranjang.jmlh,keranjang.harga, keranjang.subtotal,produk.berat 
                                            FROM keranjang
                                            INNER JOIN produk ON keranjang.idproduk=produk.idproduk
                                            WHERE idkeranjang = '$idkeranjang[$x]'");
            $tampilkan  = $data->fetch_assoc();

            $queryAgen  = $koneksi->query("SELECT * FROM mitramarketer WHERE idmitramarketer = '$idmitramarketer'");
            $dataAgen   = $queryAgen->fetch_assoc();

            $idDB       = $dataAgen['idadmin'];
            $idAgen     = $dataAgen['idmitraagen'];
            $idproduk   = $tampilkan["idproduk"];
            $harga      = $tampilkan["harga"];
            $jmlhbaru   = $tampilkan["jmlh"];
            $subtotal   = $tampilkan["subtotal"];
            $beratsatu  = $tampilkan["berat"];
            $berattotal = $beratsatu*$jmlhbaru;

            $sql_produk = $koneksi->query("SELECT namaproduk FROM produk WHERE idproduk = '$idproduk' AND jenis LIKE '%Promo%'");
            if ($sql_produk->num_rows == 1) {
                $invoice = $marketer . "P" . $idmitramarketer . $today;
            } else {
                $invoice = $marketer . $idmitramarketer . $today;
            }

            $koneksi->query("INSERT INTO ordermarketer (idorder, idmitramarketer, idmitraagen, iddb, idproduk, harga, jumlah, subtotal, tgl, invoice, status, payment, berat) 
                            VALUES (NULL, '$idmitramarketer', '$idAgen', '$idDB', '$idproduk', '$harga', '$jmlhbaru', '$subtotal', NOW(), '$invoice', 'Pending', 'Belum Bayar', '$berattotal')");
            $koneksi->query("DELETE FROM keranjang WHERE idkeranjang = '$idkeranjang[$x]'");
            $berat      = $berat+$berattotal;
        }
            echo "<script>location='formpengiriman.php?id=$invoice&berat=$berat';</script>";       
    } elseif (isset($_POST['newSave'])) {
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
    } elseif (isset($_POST['newCheckout'])) {
        date_default_timezone_set('Asia/Jakarta');
        $today          = date("mdHis");
        $idmitra        = $_SESSION["idmitramarketer"];
        $id             = 'M';
        $idkeranjang    = $_POST["idkeranjang"];
        $jumlah_dipilih = count($idkeranjang);
        $user           = $koneksi->query("SELECT idadmin, idmitraagen FROM mitramarketer WHERE idmitramarketer = '$idmitra'");
        $dataUser       = $user->fetch_assoc();
        $iddb           = $dataUser['idadmin'];
        $idAgen         = $dataUser['idmitraagen'];
        $berat          = 0;

        if ($idkeranjang == '') {
            $_SESSION['message'] = 'Check salah satu barang yang ingin di checkout';
            echo "<script>location='view_cart2.php'</script>";
            return false;
        }
        $total_jumlah = 0;
        
        for($i = 0; $i < $jumlah_dipilih; $i++) {
            $data = $koneksi->query("SELECT keranjang.idproduk, keranjang.jmlh, keranjang.harga,
                                            keranjang.subtotal, variants.berat 
                                        FROM keranjang
                                        INNER JOIN variants ON keranjang.idproduk = variants.id
                                        WHERE idkeranjang = '$idkeranjang[$i]'");
            $tampilkan  = $data->fetch_assoc();
            $jmlhbaru   = $tampilkan['jmlh'];
            $total_jumlah += $jmlhbaru;
        }
        for($x = 0; $x < $jumlah_dipilih; $x++) {
            $beratsatu = 0;
            $data = $koneksi->query("SELECT keranjang.idproduk, keranjang.jmlh, keranjang.harga,
                                            keranjang.subtotal, variants.berat, variants.idproducts 
                                        FROM keranjang
                                        INNER JOIN variants ON keranjang.idproduk = variants.id
                                        WHERE idkeranjang = '$idkeranjang[$x]'
                                    ");
            $tampilkan  = $data->fetch_assoc();
            $idproduk   = $tampilkan["idproduk"];
            $idproducts = $tampilkan["idproducts"];
            $harga      = $tampilkan["harga"];
            $jmlhbaru   = $tampilkan["jmlh"];
            $subtotal   = $tampilkan["subtotal"];
            $beratsatu  = $tampilkan["berat"];
            $berattotal = $beratsatu * $jmlhbaru;
            $berat      += $berattotal;
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

            if ($idproducts == '') {
                if ($total_jumlah % 4 == 0) {
                    $koneksi->query("INSERT INTO ordermarketer (`idorder`, `idmitramarketer`, `idmitraagen`, `iddb`, 
                                                        `idproduk`, `harga`,`jumlah`,`subtotal`,`tgl`,
                                                        `invoice`,`status`,`payment`, `berat`,`status_progres`)
                                        VALUES (NULL, '$idmitra', '$idAgen', '$iddb', '$idproduk', '$harga', '$jmlhbaru', 
                                                '$subtotal', NOW(), '$invoice', 'Pending', 'Belum Bayar', '$berattotal', 0)
                                    ");
                    $koneksi->query("DELETE FROM keranjang WHERE idkeranjang = '$idkeranjang[$x]'");
                    $berat = $berat + $berattotal;
                    echo "<script>location='formpengiriman.php?id=$invoice&berat=$berat';</script>";
                } else {
                    $_SESSION['message'] = 'QTY / Jumlah harus kelipatan 4!';
                    echo "<script>location='view_cart2.php';</script>";
                }
            } else {
                $koneksi->query("INSERT INTO ordermarketer (`idorder`, `idmitramarketer`, `idmitraagen`, `iddb`, 
                                                            `idproduk`, `harga`,`jumlah`,`subtotal`,`tgl`,
                                                            `invoice`,`status`,`payment`, `berat`,`status_progres`)
                                    VALUES (NULL, '$idmitra', '$idAgen', '$iddb', '$idproduk', '$harga', '$jmlhbaru', 
                                            '$subtotal', NOW(), '$invoice', 'Pending', 'Belum Bayar', '$berattotal', 0)
                                ");
                $koneksi->query("DELETE FROM keranjang WHERE idkeranjang = '$idkeranjang[$x]'");
                $berat = $berat + $berattotal;
                echo "<script>location='formpengiriman.php?id=$invoice&berat=$berat';</script>";
            }
        }
    }
?>
