<?php 
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesManage.php';

    $idmanage    = $_SESSION["idmanage"];
    $tipe        = $_SESSION["user_tipe"];

    $queryManage = $koneksi->query("SELECT * FROM management WHERE id='$idmanage'");
    $data = $queryManage->fetch_assoc();

    $invoice=$_GET["invoice"];
    $idpoproduk=$_GET["idpoproduk"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Distributor | WNJ.ID</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
     <div class="container mt-5">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h2 class="m-0 font-weight-bold text-secondary">List PO Per Invoice</h2>
            <a href="preorder.php" class="btn btn-info"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><?= $invoice; ?></h1>
        </div>

        <!-- Content Row -->
        <div class="row" style="margin: auto;">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Qty</th>
                            <th>Nama Barang</th>
                            <?php if (substr($invoice, 0, 2) == "MH") { ?>
                                <th>Custom Nama</th>
                                <th>Font Teks</th>
                                <th>Warna Teks</th>
                            <?php } ?>
                            <?php if (substr($invoice, 0, 2) == "RM") { ?>
                                <th>Custom Nama</th>
                                <th>Font Teks</th>
                            <?php } ?>
                            <?php if (substr($invoice, 0, 1) == "B") { ?>
                                <th>Custom Nama</th>
                                <th>Warna Teks</th>
                            <?php } ?>
                            <?php if (substr($invoice, 0, 1) == "M") { ?>
                                <th>Custom Nama</th>
                            <?php } ?>
                            <?php if (substr($invoice, 0, 3) == "LUX") { ?>
                                <th>Ukuran Custom Panjang Dress</th>
                                <th>Ukuran Khimar</th>
                            <?php } ?>
                            <th>Satuan</th>
                            <th style="text-align:center">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $jumlah = 0;
                        $subtotal = 0;
                        $datapo = $koneksi->query("
                            SELECT 
                                poproduk.namapo,
                                pokategori.namakategori,
                                podetail.variant,
                                pomitra.idpomitra,
                                pomitra.jumlah,
                                pomitra.invoice,
                                pomitra.total,
                                pomitra.custom,
                                pomitra.font,
                                podetail.harga 
                            FROM 
                                poproduk 
                            INNER JOIN 
                                pomitra ON poproduk.idpoproduk = pomitra.idpoproduk 
                            INNER JOIN 
                                pokategori ON pokategori.idpo = pomitra.idpo
                            INNER JOIN 
                                podetail ON podetail.idpodetail = pomitra.idpodetail
                            WHERE 
                                pomitra.invoice = '$invoice' AND pomitra.jumlah > 0");
                        $no = 1;
                        $sum = 0;

                        while ($tampilkan = $datapo->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $tampilkan['jumlah']; ?></td>
                                <td><?= $tampilkan['variant']; ?></td>
                                <?php if (substr($invoice, 0, 2) == "MH") { ?>
                                    <td><?= $tampilkan['custom']; ?></td>
                                    <td><?= $tampilkan['font']; ?></td>
                                    <td>
                                        <?= ($tampilkan['namakategori'] == 'Cream' || $tampilkan['namakategori'] == 'Silver' || $tampilkan['namakategori'] == 'White' || $tampilkan['namakategori'] == 'Grey') ? 'Black' : 'Gold'; ?>
                                    </td>
                                <?php } ?>
                                <?php if (substr($invoice, 0, 2) == "RM") { ?>
                                    <td>
                                        <?php 
                                        $datacustom = $tampilkan['custom'];
                                        $result_explode = explode('|', $datacustom);
                                        echo nl2br($result_explode[0]) . "<br>" . nl2br($result_explode[1]); 
                                        ?>
                                    </td>
                                    <td><?= $tampilkan['font']; ?></td>
                                <?php } ?>
                                <?php if (substr($invoice, 0, 1) == "B") { ?>
                                    <td><?= $tampilkan['custom']; ?></td>
                                    <td>
                                        <?= ($tampilkan['namakategori'] == 'Cream' || $tampilkan['namakategori'] == 'Silver' || $tampilkan['namakategori'] == 'White' || $tampilkan['namakategori'] == 'Grey') ? 'Black' : 'Gold'; ?>
                                    </td>
                                <?php } ?>
                                <?php if (substr($invoice, 0, 1) == "M") { ?>
                                    <td><?= $tampilkan['custom']; ?></td>
                                <?php } ?>
                                <?php if (substr($invoice, 0, 3) == "LUX") { ?>
                                    <td><?= $tampilkan['custom']; ?> cm</td>
                                    <td><?= $tampilkan['font']; ?></td>
                                <?php } ?>
                                <td>Rp. <?= number_format($tampilkan['harga']); ?></td>
                                <td>Rp. <?= number_format($tampilkan['total']); ?></td>
                                <?php
                                $sum += $tampilkan['jumlah'];
                                $jumlah += $tampilkan['total'];
                                $invoice = $tampilkan['invoice'];
                                ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <?php 
            if ($idpoproduk == '96') {
                $persen = 50;
                $diskon = 50 / 100 * $jumlah;
            } else {
                $persen = 35;
                $diskon = 35 / 100 * $jumlah;
            }
            $subtotal = $jumlah - $diskon; 
            ?>

            <table width="100%" style="float: right;">
                <tbody>
                    <tr>
                        <th>Total Qty</th>
                        <td>:</td>
                        <td><?= $sum; ?></td>
                    </tr>
                    <tr>
                        <th>JUMLAH</th>
                        <td>:</td>
                        <td>Rp. <?= number_format($jumlah); ?></td>
                    </tr>
                    <tr>
                        <th>Diskon DB <?= $persen; ?>%</th>
                        <td>:</td>
                        <td>Rp. <?= number_format($diskon); ?></td>
                    </tr>
                    <tr>
                        <th>Total Bayar</th>
                        <td>:</td>
                        <td>Rp. <?= number_format($subtotal); ?></td>
                    </tr>
                </tbody>
            </table>
        </div><!-- Content Row -->

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