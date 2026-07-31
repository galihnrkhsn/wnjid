<?php
    session_start();
    include 'koneksi.php';

    if (!isset($_SESSION['administrator'])) {
        header('location:login.php');
        exit();
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $invoices = $_POST['invoice'] ?? [];
    $daftarOrder = [];

    if (!empty($invoices)) {
        $placeholders = implode(',', array_fill(0, count($invoices), '?'));
        $types        = str_repeat('s', count($invoices));

        $stmtOrder = $koneksi->prepare("SELECT idorder, invoice, nama_penerima
                                         FROM orderkonsumen WHERE invoice IN ($placeholders) ORDER BY tgl ASC");
        $stmtOrder->bind_param($types, ...$invoices);
        $stmtOrder->execute();
        $orders = $stmtOrder->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmtItems = $koneksi->prepare("SELECT namaproduk, variant, size, jumlah FROM orderkonsumen_detail WHERE idorder = ? ORDER BY iddetail ASC");
        foreach ($orders as $order) {
            $stmtItems->bind_param('i', $order['idorder']);
            $stmtItems->execute();
            $order['items'] = $stmtItems->get_result()->fetch_all(MYSQLI_ASSOC);
            $daftarOrder[]   = $order;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Ambil Barang Konsumen</title>
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style type="text/css">
        table, th, td { border: 1px solid black; }
        body { color: black; }
        .blok-invoice { page-break-inside: avoid; margin-bottom: 3rem; }
        .header-invoice {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            font-size: 22px;
            margin-bottom: .5rem;
        }
        .kolom-check { width: 60px; text-align: center; }
        .blok-ttd {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }
        .kotak-ttd {
            width: 260px;
            text-align: center;
        }
        .kotak-ttd .garis-ttd {
            border-bottom: 1px solid black;
            height: 80px;
        }
        .kotak-ttd .label-ttd {
            font-size: 18px;
            margin-top: .5rem;
        }
    </style>
</head>
<body>
    <center><h1><strong>Ambil Barang</strong></h1></center>

    <?php if (empty($daftarOrder)): ?>
        <p>Tidak ada order dipilih.</p>
    <?php endif; ?>

    <?php foreach ($daftarOrder as $order): ?>
        <div class="blok-invoice">
            <div class="header-invoice">
                <span>Invoice: <?= htmlspecialchars($order['invoice']) ?></span>
                <span><?= htmlspecialchars($order['nama_penerima']) ?></span>
            </div>
            <table class="table" style="font-size: 20px;">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Variant</th>
                        <th>Size</th>
                        <th>Qty</th>
                        <th class="kolom-check">Check</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['namaproduk']) ?></td>
                            <td><?= htmlspecialchars($item['variant']) ?></td>
                            <td><?= htmlspecialchars($item['size'] ?? '') ?></td>
                            <td style="text-align:center"><?= (int) $item['jumlah'] ?></td>
                            <td class="kolom-check">&nbsp;</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="blok-ttd">
                <div class="kotak-ttd">
                    <div class="garis-ttd"></div>
                    <div class="label-ttd">Tanda Tangan Checker</div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
<script>
    window.print();
</script>
</html>
