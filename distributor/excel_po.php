<?php
include "koneksi.php";
include "assets/components/Sessions/sesDistri.php";

$invoice = $_GET["invoice"] ?? '';
$idadmin = $_SESSION["idadmin"];
$sum     = 0;

// invoice hanya boleh alfanumerik supaya query di bawah (yang masih menyisipkan
// $invoice langsung ke string SQL) tidak bisa disalahgunakan untuk SQL injection
if ($invoice === '') {
    header('Location: listnewpo.php');
    exit;
}

// Pastikan invoice ini benar-benar milik mitra yang sedang login
$stmtOwn = $koneksi->prepare("SELECT COUNT(*) AS jumlah FROM pomitra WHERE invoice = ? AND idmitra = ?");
$stmtOwn->bind_param('ss', $invoice, $idadmin);
$stmtOwn->execute();
$ownCheck = $stmtOwn->get_result()->fetch_assoc();
if (($ownCheck['jumlah'] ?? 0) == 0) {
    header('Location: listnewpo.php');
    exit;
}

$stmtMitra = $koneksi->prepare("SELECT poproduk.namapo,admin_mitra.namamitra as db,
                              mitraagen.namaagen as agen,mitrareseller.namaagen as reseller,
                              mitramarketer.namaagen as marketer FROM `pomitra`
                              LEFT JOIN mitraagen on mitraagen.idmitraagen=pomitra.idmitraagen
                              LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=pomitra.idmitrareseller
                              LEFT JOIN mitramarketer on mitramarketer.idmitramarketer=pomitra.idmitramarketer
                              LEFT JOIN admin_mitra on (mitraagen.idadmin=admin_mitra.idadmin or mitrareseller.idadmin=admin_mitra.idadmin or mitramarketer.idadmin=admin_mitra.idadmin or pomitra.idmitra=admin_mitra.idadmin)
                              INNER JOIN poproduk on pomitra.idpoproduk=poproduk.idpoproduk
                              WHERE pomitra.invoice = ?");
$stmtMitra->bind_param('s', $invoice);
$stmtMitra->execute();
$tampilnama = $stmtMitra->get_result()->fetch_assoc();
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
    header("Content-Disposition: attachment; filename=Data PO " . preg_replace('/[^A-Za-z0-9_-]/', '', $invoice) . ".xls");
    ?>
    <title>Pre Order</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="text-center">
            <h3><strong><?= htmlspecialchars($tampilnama['namapo'] ?? '') ?></strong></h3>
            <h4>Invoice <?= htmlspecialchars($invoice) ?></h4>
        </div>
        <div class="mt-4">
            <p><strong>Nama Distributor:</strong> <?= htmlspecialchars($tampilnama['db'] ?? '') ?></p>
            <?php if (!empty($tampilnama['agen']) || !empty($tampilnama['reseller']) || !empty($tampilnama['marketer'])): ?>
                <p><strong>Nama Sub DB:</strong> <?= htmlspecialchars($tampilnama['agen'] ?? '') ?> <?= htmlspecialchars($tampilnama['reseller'] ?? '') ?> <?= htmlspecialchars($tampilnama['marketer'] ?? '') ?></p>
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
                    $stmtItems = $koneksi->prepare("SELECT
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
                        WHERE pomitra.invoice = ? AND pomitra.jumlah > 0");
                    $stmtItems->bind_param('s', $invoice);
                    $stmtItems->execute();
                    $datapo = $stmtItems->get_result();

                    $no     = 1;
                    $sum    = 0;
                    $jumlah = 0;

                    while ($tampilkan = $datapo->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($tampilkan['variant']) ?></td>
                            <td>Rp. <?= number_format($tampilkan['harga']); ?></td>
                            <td><?= (int) $tampilkan['jumlah']; ?></td>
                            <td>Rp. <?= number_format($tampilkan['total']); ?></td>
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
            <p><strong>Total Qty:</strong> <?= $sum; ?></p>
            <p><strong>JUMLAH:</strong> Rp. <?= number_format($jumlah); ?></p>
            <?php
            $diskon = 35 / 100 * $jumlah;
            $subtotal = $jumlah - $diskon;
            ?>
            <p><strong>Diskon DB:</strong> Rp. <?= number_format($diskon); ?></p>
            <p><strong>TOTAL:</strong> Rp. <?= number_format($subtotal); ?></p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
