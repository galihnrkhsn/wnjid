<?php
    include "koneksi.php";
    $invoice=$_GET["invoice"];
    $sum=0;

    $datamitra=$koneksi->query("SELECT poproduk.namapo,admin_mitra.namamitra as db,
                                    mitrareseller.namaagen as agen FROM `pomitra` 
                                LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=pomitra.idmitrareseller 
                                LEFT JOIN admin_mitra on (mitrareseller.idadmin=admin_mitra.idadmin) 
                                INNER JOIN poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
                                WHERE pomitra.invoice='$invoice'");
    $tampilnama=$datamitra->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Data PO $invoice.xls");
    ?>
    <title>Pre Order</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="text-center">
            <h3><strong><?php echo $tampilnama['namapo']; ?></strong></h3>
            <h4>Invoice <?php echo $invoice; ?></h4>
        </div>
        <div class="mt-4">
            <p><strong>Nama Distributor:</strong> <?php echo $tampilnama['db']; ?></p>
            <?php if ($tampilnama['agen'] || $tampilnama['reseller'] || $tampilnama['marketer']): ?>
                <p><strong>Nama Sub DB:</strong> <?php echo $tampilnama['agen']; ?> <?php echo $tampilnama['reseller']; ?> <?php echo $tampilnama['marketer']; ?></p>
            <?php endif; ?>
        </div>
        <div class="table-responsive mt-4">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <!-- <th>Custom</th> -->
                        <th>Satuan</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $datapo = $koneksi->query("SELECT 
                        poproduk.namapo,
                        pokategori.namakategori,
                        podetail.variant,
                        pomitra.idpomitra,
                        pomitra.jumlah,
                        pomitra.invoice,
                        pomitra.total,
                        pomitra.custom,
                        podetail.harga 
                        FROM poproduk 
                        INNER JOIN pomitra ON poproduk.idpoproduk=pomitra.idpoproduk 
                        INNER JOIN pokategori ON pokategori.idpo=pomitra.idpo
                        INNER JOIN podetail ON podetail.idpodetail=pomitra.idpodetail
                        WHERE pomitra.invoice='$invoice' AND pomitra.jumlah>0");

                    $no = 1;
                    $sum = 0;
                    $jumlah = 0;

                    while($tampilkan = $datapo->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $tampilkan['variant']; ?></td>
                            <!-- <td>
                                <?php
                                if($tampilkan['custom'] <> '') {
                                    echo nl2br($tampilkan['custom']);
                                } else {
                                    echo " - ";
                                }
                                ?>
                            </td> -->
                            <td>Rp. <?php echo number_format($tampilkan['harga']); ?></td>
                            <td><?php echo $tampilkan['jumlah']; ?></td>
                            <td>Rp. <?php echo number_format($tampilkan['total']); ?></td>
                        </tr>
                        <?php
                        $sum += $tampilkan['jumlah'];
                        $jumlah += $tampilkan['total'];
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-right">
            <p><strong>Total Qty:</strong> <?php echo $sum; ?></p>
            <p><strong>Jumlah:</strong> Rp. <?php echo number_format($jumlah); ?></p>
            <?php 
            $diskon = 15 / 100 * $jumlah;
            $subtotal = $jumlah - $diskon; 
            ?>
            <p><strong>Diskon Reseller:</strong> Rp. <?php echo number_format($diskon); ?></p>
            <p><strong>Total:</strong> Rp. <?php echo number_format($subtotal); ?></p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

