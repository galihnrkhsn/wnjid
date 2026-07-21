<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data Total PO.xls");
include "koneksi.php";
$idpoproduk = (int)$_GET["id"]; // sanitasi input!
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Laporan PO</title>
</head>
<body>
<p><strong>Totalan PO</strong></p>
<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama PO</th>
            <th>Nama CS</th>
            <th>Distributor</th>
            <th>Kode DB</th>
            <th>Agen</th>
            <th>Reseller</th>
            <th>Marketer</th>
            <?php if ($idpoproduk == 328): ?>
                <th>Variant</th>
                <th>Custom</th>
            <?php endif; ?>
            <th>Inv</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Ongkir</th>
            <th>Dropship</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $sql = "
        SELECT 
            pp.namapo,
            am.idadmin,
            am.namamitra AS db,
            ma.namaagen AS agen,
            mr.namaagen AS reseller,
            mm.namaagen AS marketer,
            pm.invoice,
            pm.status,
            pm.custom,
            amcs.namacs,
            
            -- Qty & Total dari podetail (agregat per invoice)
            SUM(pm.jumlah) AS qty,
            SUM(pm.jumlah * pd.harga) AS sum_total,
            MAX(pm.total) AS stokharga,
            
            -- Variant (ambil salah satu, karena per invoice bisa beda)
            MAX(pd.variant) AS variant,
            
            -- Ongkir & Dropship
            pds.ongkir,
            pds.dropship

        FROM pomitra pm
        INNER JOIN poproduk pp ON pm.idpoproduk = pp.idpoproduk
        LEFT JOIN mitraagen ma ON ma.idmitraagen = pm.idmitraagen
        LEFT JOIN mitrareseller mr ON mr.idmitrareseller = pm.idmitrareseller
        LEFT JOIN mitramarketer mm ON mm.idmitramarketer = pm.idmitramarketer
        LEFT JOIN admin_mitra am ON (
            ma.idadmin = am.idadmin OR
            mr.idadmin = am.idadmin OR
            mm.idadmin = am.idadmin OR
            pm.idmitra = am.idadmin
        )
        LEFT JOIN admin_mitra_cs amcs ON amcs.idadmin = am.idadmin
        LEFT JOIN podetail pd ON pd.idpodetail = pm.idpodetail
        LEFT JOIN (
            SELECT
                invoice,
                MAX(ongkir) AS ongkir,
                MAX(dropship) AS dropship
            FROM podropship
            GROUP BY invoice
        ) pds
        ON pds.invoice = pm.invoice

        WHERE pm.idpoproduk = $idpoproduk
          AND pm.jumlah > 0
          AND pm.status <> 'Belum Acc DB'

        GROUP BY pm.invoice
        ORDER BY pm.tgl ASC
    ";

    $result = $koneksi->query($sql);
    $no = 1;

    while ($row = $result->fetch_assoc()):
        $sum_jumlah = $row['qty'];
        $sum_total  = $row['sum_total'];
        $stokharga2 = $row['stokharga'];
        $variant    = $row['variant'];
        $ongkir     = $row['ongkir'];
        $dropship   = $row['dropship'];

        // Hitung QTY display
        if ($idpoproduk == 186) {
            $qty_display = "(" . ($sum_jumlah / 12) . " Seri)";
        } elseif ($idpoproduk == 339) {
            $qty_display = $sum_jumlah / 3;
        } else {
            $qty_display = $sum_jumlah;
        }

        // Hitung TOTAL display
        if ($idpoproduk == 120) {
            $total_display = $stokharga2;
        } elseif ($idpoproduk == 339) {
            $total_display = $sum_total / 3;
        } elseif ($idpoproduk == 332) {
            $total_display = ($row['custom'] === 'Set')
                ? ($sum_jumlah / 3) * 100000
                : $sum_total;
        } else {
            $total_display = $sum_total;
        }
    ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['namapo']) ?></td>
            <td><?= htmlspecialchars($row['namacs']) ?></td>
            <td><?= htmlspecialchars($row['db']) ?></td>
            <td><?= htmlspecialchars($row['idadmin']) ?></td>
            <td><?= htmlspecialchars($row['agen']) ?></td>
            <td><?= htmlspecialchars($row['reseller']) ?></td>
            <td><?= htmlspecialchars($row['marketer']) ?></td>
            <?php if ($idpoproduk == 328): ?>
                <td><?= htmlspecialchars($variant) ?></td>
                <td><?= htmlspecialchars($row['custom']) ?></td>
            <?php endif; ?>
            <td><?= htmlspecialchars($row['invoice']) ?></td>
            <td><?= $qty_display ?></td>
            <td><?= $total_display ?></td>
            <td><?= $ongkir ?></td>
            <td><?= $dropship ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</body>
</html>