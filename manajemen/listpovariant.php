<?php 
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesManage.php';

    $idmanage = $_SESSION["idmanage"];
    $tipe     = $_SESSION["user_tipe"];
    $id       = $_GET["id"];


    $queryManage = $koneksi->query("SELECT * FROM management WHERE id='$idmanage'");
    $data = $queryManage->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <? include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <a href="javascript:void(0);" onclick="history.back();" style="float: right;">
                <i class="fas fa-arrow-left fa-m"> Kembali</i>
            </a>
            <h2 class="m-0 font-weight-bold text-secondary">List Po Variant</h2>
        </div>
        <div class="row" style="margin: auto;">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width:1px">No</th>
                            <th>Variant</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $subtotal = 0;
                        $datapo = $koneksi->query("SELECT podetail.variant, 
                                                            SUM(pomitra.jumlah) AS jumlah, 
                                                            REPLACE(RIGHT(podetail.variant, 2), ' ', '') AS ukuran 
                                                    FROM poproduk 
                                                    INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk 
                                                    INNER JOIN pokategori ON pokategori.idpo = pomitra.idpo 
                                                    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail 
                                                    WHERE poproduk.idpoproduk = '$id' 
                                                    AND pomitra.status <> 'Belum Acc DB' 
                                                    GROUP BY podetail.variant 
                                                    ORDER BY ukuran");

                        $no = 1;

                        while($tampilkan = $datapo->fetch_assoc()){
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $tampilkan['variant']; ?></td>
                            <td><?php echo $tampilkan['jumlah']; ?></td>
                        </tr>
                        <?php 
                        $subtotal += $tampilkan['jumlah'];
                        } 
                        ?>
                        <tr>
                            <td colspan="2"><b>Total</b></td>
                            <td><b><?php echo $subtotal; ?></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row" style="margin: auto;">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h2 class="m-0 font-weight-bold text-secondary">Summary</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width:1px">No</th>
                            <th>Variant</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        $datapo2 = $koneksi->query("SELECT pokategori.namakategori, 
                                                        SUM(pomitra.jumlah) AS jumlah 
                                                    FROM poproduk 
                                                    INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk 
                                                    INNER JOIN pokategori ON pokategori.idpo = pomitra.idpo 
                                                    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail 
                                                    WHERE poproduk.idpoproduk = '$id' 
                                                    AND pomitra.status <> 'Belum Acc DB' 
                                                    GROUP BY pokategori.namakategori");

                        $no2 = 1;

                        while($tampilkan2 = $datapo2->fetch_assoc()){
                        ?>
                        <tr>
                            <td><?php echo $no2++; ?></td>
                            <td><?php echo $tampilkan2['namakategori']; ?></td>
                            <td><?php echo $tampilkan2['jumlah']; ?></td>
                        </tr>
                        <?php 
                        $total += $tampilkan2['jumlah'];
                        } 
                        ?>
                        <tr>
                            <td colspan="2"><b>Total</b></td>
                            <td><b><?php echo $total; ?></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <!-- PHP END -->

    <!-- FOOTER -->
    <? include "assets/components/Footer/footer.php"; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <!-- END SCRIPT -->
</body>
</html>