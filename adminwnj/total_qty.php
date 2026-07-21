<?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Data Total PO.xls");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p><strong>Totalan PO</strong></p>
    
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama DB</th>
                <th>Sarung I</th>
                <th>Sarung II</th>
                <th>Sarung Tambahan</th>
                <th>Koko Lophura</th>
                <th>Total</th>
            </tr>
        </thead>
        
        <?php
            include "koneksi.php";
            $datapo = $koneksi->query("SELECT poproduk.idpoproduk,
                                                admin_mitra.idadmin,
                                                admin_mitra.namamitra AS db,
                                                mitraagen.namaagen AS agen,
                                                mitrareseller.namaagen AS reseller,
                                                mitramarketer.namaagen AS marketer,
                                                pomitra.invoice,
                                                SUM(CASE WHEN pomitra.idpoproduk = 263 THEN pomitra.jumlah ELSE 0 END) AS qty_263,
                                                SUM(CASE WHEN pomitra.idpoproduk = 262 THEN pomitra.jumlah ELSE 0 END) AS qty_262,
                                                SUM(CASE WHEN pomitra.idpoproduk = 275 THEN pomitra.jumlah ELSE 0 END) AS qty_275,
                                                SUM(CASE WHEN pomitra.idpoproduk = 288 THEN pomitra.jumlah ELSE 0 END) AS qty_288
                                            FROM pomitra
                                            LEFT JOIN mitraagen ON mitraagen.idmitraagen = pomitra.idmitraagen 
                                            LEFT JOIN mitrareseller ON mitrareseller.idmitrareseller = pomitra.idmitrareseller 
                                            LEFT JOIN mitramarketer ON mitramarketer.idmitramarketer = pomitra.idmitramarketer 
                                            LEFT JOIN admin_mitra ON (
                                                mitraagen.idadmin = admin_mitra.idadmin OR
                                                mitrareseller.idadmin = admin_mitra.idadmin OR
                                                mitramarketer.idadmin = admin_mitra.idadmin OR
                                                pomitra.idmitra = admin_mitra.idadmin
                                            ) 
                                            LEFT JOIN admin_mitra_cs ON admin_mitra_cs.idadmin = admin_mitra.idadmin
                                            INNER JOIN poproduk ON pomitra.idpoproduk = poproduk.idpoproduk 
                                            WHERE (pomitra.idpoproduk IN (263, 262, 275, 288))
                                            AND pomitra.jumlah > 0 
                                            AND pomitra.status <> 'Belum Acc DB'
                                            GROUP BY admin_mitra.idadmin
                                            ORDER BY admin_mitra.namamitra ASC
                                    ");
            $no = 1;
            while($tampilkan = $datapo->fetch_assoc()) {
                $total_qty = $tampilkan['qty_262'] + $tampilkan['qty_275'] + $tampilkan['qty_288'] + $tampilkan['qty_263'];
        ?>
            <tbody>
                <tr>
                    <td><?= $no++ ?></td>
                    <td style="text-transform: capitalize"><?= $tampilkan['db'] ?></td>
                    <td><?= $tampilkan['qty_262'] ?></td>
                    <td><?= $tampilkan['qty_275'] ?></td>
                    <td><?= $tampilkan['qty_288'] ?></td>
                    <td><?= $tampilkan['qty_263'] ?></td>
                    <td><?= $total_qty ?></td>
                </tr>
            </tbody>
        <?php 
                $sarung1 += $tampilkan['qty_262'];
                $sarung2 += $tampilkan['qty_275'];
                $sarung3 += $tampilkan['qty_288'];
                $koko += $tampilkan['qty_263'];
            }
        ?>
        <tfoot>
            <tr>
                <td colspan="2">Total</td>
                <td><?= $sarung1 ?></td>
                <td><?= $sarung2 ?></td>
                <td><?= $sarung3 ?></td>
                <td><?= $koko ?></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>