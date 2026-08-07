<?php 
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesManage.php';

    $idpoproduk = $_GET['id'];
    $datapo     = $koneksi->query("SELECT poproduk.namapo FROM poproduk WHERE idpoproduk = '$idpoproduk'"); 
    $data       = $datapo->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Produksi | WNJ.ID</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h2 class="m-0 font-weight-bold text-secondary"><?= $data['namapo']; ?></h2>
            <a href="index" style="float: right;"><i class="fas fa-arrow-left fa-m"></i> Kembali</a>
        </div>
        <hr>
        <div>
            <?php
                $sql = $koneksi->query("SELECT 
                                            poproduk.idpoproduk,
                                            poproduk.namapo,
                                            pomitra.invoice,
                                            SUM(pomitra.jumlah) AS jumlahnya
                                        FROM poproduk
                                        INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                                        WHERE poproduk.idpoproduk = '$idpoproduk'
                                        GROUP BY 
                                            poproduk.idpoproduk,
                                            poproduk.namapo,  -- Ditambahkan agar GROUP BY sesuai dengan SELECT
                                            pomitra.invoice   -- Ditambahkan agar GROUP BY sesuai dengan SELECT
                                        ORDER BY poproduk.updated_at DESC;

                ");
                
                $query_data = $sql->fetch_assoc();
                $query_ambil = $koneksi->query("SELECT idsjk, idpoproduk, jumlah, tanggal, SUM(jumlah) AS total_masuk
                                                FROM sjk 
                                                WHERE idpoproduk = '$idpoproduk'
                                                GROUP BY 
                                                    idsjk, idpoproduk,
                                                    jumlah, tanggal;     -- Tambahkan kolom lain yang dipilih di sini
                ");
                
                while ($data_masuk = $query_ambil->fetch_assoc()) {
                    $barang_masuk       = $data_masuk['total_masuk'];   
                    $query_kekurangan   = $query_data['jumlahnya'] - $barang_masuk;
                }
                
                $persentase = $barang_masuk / $query_data['jumlahnya'] * 100;

                $sql_data_inv = $koneksi->query("SELECT pomitra.invoice, SUM(pomitra.jumlah) AS qty
                                                    FROM pomitra
                                                    WHERE pomitra.idpoproduk = '$idpoproduk'
                                                    AND pomitra.jumlah > 0
                                                    GROUP BY pomitra.invoice
                ");
                
                $total_kurang = 0;
                $totalTesting = 0;

                while ($query_data_inv = $sql_data_inv->fetch_assoc()) {
                    $inv = $query_data_inv['invoice'];
                    $sql_ambil_barang = $koneksi->query("SELECT SUM(surat_jalan_po.progres) AS progresnya, surat_jalan_po.invoice
                                                            FROM surat_jalan_po
                                                            WHERE surat_jalan_po.invoice = '$inv'
                    ");
                    
                    $query_ambil_barang = $sql_ambil_barang->fetch_assoc();
                    $kurang             = $query_data_inv['qty'] - $query_ambil_barang['progresnya'];
                    $total_kurang += $kurang;
                    $totalTesting += $query_data_inv['qty'];
                }
                
                $total_keseluruhan      = $totalTesting - $total_kurang;
                $persentase_keseluruhan = $total_keseluruhan / $query_data['jumlahnya'] * 100;
                $minus_keseluruhan      = $query_data['jumlahnya'] - $total_keseluruhan;
            ?>

            <div class="progress">
                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $persentase_keseluruhan ?>%" aria-valuemin="0" aria-valuemax="<?= $query_data['jumlahnya'] ?>"><?= $total_keseluruhan ?></div>
                <div class="progress-bar" role="progressbar" style="width: <?= $persentase ?>%" aria-valuemin="0" aria-valuemax="<?= $query_data['jumlahnya'] ?>"><?= $barang_masuk ?></div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <p><span class="text-success"><?= $total_keseluruhan ?></span> / <span class="text-primary"><?= $barang_masuk ?></span></p>
                <p>- <span class="text-success"><?= $minus_keseluruhan ?></span> / - <span class="text-primary"><?= $query_kekurangan ?></span></p>
                <p><?= $query_data['jumlahnya'] ?></p>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <!-- PHP END -->

    <!-- FOOTER -->
    <?php include "assets/components/Footer/footer.php"; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <!-- END SCRIPT -->
</body>
</html>