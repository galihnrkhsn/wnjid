<?php
    include 'koneksi.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    $query  = "SELECT 
                    a.idadmin,
                    a.namamitra,

                    b.total_invoice,
                    b.tanggal_terakhir,
                    b.waktu_terakhir,

                    c.total_invoice_rs,
                    c.tanggal_terakhir_rs,
                    c.waktu_terakhir_rs,

                    d.namacs
                FROM admin_mitra a
                LEFT JOIN (
                    SELECT 
                        idmitra,
                        COUNT(DISTINCT invoice) AS total_invoice,
                        SUBSTRING_INDEX(MAX(CONCAT(tgl,' ',waktu)), ' ', 1) AS tanggal_terakhir,
                        SUBSTRING_INDEX(MAX(CONCAT(tgl,' ',waktu)), ' ', -1) AS waktu_terakhir
                    FROM pomitra
                    WHERE tgl > '2024-12-31'
                    GROUP BY idmitra
                ) b ON a.idadmin = b.idmitra
                LEFT JOIN (
                    SELECT 
                        idmitra,
                        COUNT(DISTINCT invoice) AS total_invoice_rs,
                        SUBSTRING_INDEX(MAX(CONCAT(tgl,' ',waktu)), ' ', 1) AS tanggal_terakhir_rs,
                        SUBSTRING_INDEX(MAX(CONCAT(tgl,' ',waktu)), ' ', -1) AS waktu_terakhir_rs
                    FROM ordermitra
                    WHERE tgl > '2024-12-31'
                    GROUP BY idmitra
                ) c ON a.idadmin = c.idmitra
                LEFT JOIN admin_mitra_cs d ON a.idadmin = d.idadmin
                WHERE b.total_invoice IS NOT NULL 
                OR c.total_invoice_rs IS NOT NULL
                ORDER BY b.total_invoice DESC
            ";

    $result = mysqli_query($koneksi, $query);

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=laporan_mitra.xls");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Mitra</title>
</head>
<body>

<h2 style="text-align:center;">LAPORAN MITRA</h2>

<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr style="background-color:#4F81BD; color:white; text-align:center;">
            <th>ID Admin</th>
            <th>Nama CS</th>
            <th>Nama Mitra</th>
            <th>Total Invoice PO</th>
            <th>Tanggal Terakhir Checkout PO</th>
            <th>Total Invoice Ready Stok</th>
            <th>Tanggal Terakhir Checkout Ready Stok</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['idadmin'] ?></td>
            <td><?= $row['namacs'] ?></td>
            <td><?= $row['namamitra'] ?></td>
            <td><?= $row['total_invoice'] ?></td>
            <td><?= $row['tanggal_terakhir'] ?> <?= $row['waktu_terakhir'] ?></td>
            <td><?= $row['total_invoice_rs'] ?></td>
            <td><?= $row['tanggal_terakhir_rs'] ?> <?= $row['waktu_terakhir_rs'] ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>