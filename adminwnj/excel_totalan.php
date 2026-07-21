<?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Totalan PO Koko Embroidery.xls"); 
    session_start();
    include 'koneksi.php';
    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }
    // 
    $idpoproduk = [364, 372, 379]; // ini bisa kamu atur sesuka hati
    $placeholders = implode(',', $idpoproduk);
    $sql1 = "SELECT 
                p.idpodetail,
                d.variant,
                d.harga,
                p.idpoproduk,
                po.namapo,
                SUM(p.jumlah) AS total_jumlah,
                (SUM(p.jumlah) * d.harga) AS subtotal
            FROM pomitra p
            JOIN podetail d ON p.idpodetail = d.idpodetail
            JOIN poproduk po ON p.idpoproduk = po.idpoproduk
            WHERE p.idpoproduk IN ($placeholders)
            GROUP BY p.idpodetail";
    
    $result1 = $koneksi->query($sql1);

    // Pastikan query berhasil
    if (!$result1) {
        die("Query error: " . $koneksi->error);
    }
    $data = [];
    $idpoproduk_list = []; // untuk header kolom

    while ($row = $result1->fetch_assoc()) {
        $idpodetail = $row['idpodetail'];
        $idpoproduk = $row['idpoproduk'];
        

        // Simpan variant dan harga per idpodetail
        $data[$idpodetail]['variant'] = $row['variant'];
        $data[$idpodetail]['harga'] = $row['harga'];

        // Simpan total jumlah dan subtotal per idpoproduk
        $data[$idpodetail]['produk'][$idpoproduk] = [
            'jumlah' => $row['total_jumlah'],
            'subtotal' => $row['subtotal']
        ];

        // Simpan untuk header
        if (!in_array($idpoproduk, $idpoproduk_list)) {
            $idpoproduk_list[$idpoproduk] = $row['namapo'];
        }
    }
    // Tutup koneksi database
    $koneksi->close();

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Custom fonts for this template-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    </head>
              
    <body>
    <table class="table table-bordered text-center">
    <thead>
        <tr>
            <th>ID PO Detail</th>
            <th>Variant</th>
            <th>Harga</th>
            <?php foreach ($idpoproduk_list as $idpop => $namapo): ?>
                <th>Jumlah (<?= $idpop ?> - <?= $namapo ?>)</th>
                <th>Subtotal (<?= $idpop ?>)</th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $idpodetail => $detail): ?>
            <tr>
                <td><?= $idpodetail ?></td>
                <td><?= htmlspecialchars($detail['variant']) ?></td>
                <td><?= number_format($detail['harga']) ?></td>
                <?php foreach ($idpoproduk_list as $idpop => $namapo): ?>
                    <?php
                        $jumlah = $detail['produk'][$idpop]['jumlah'] ?? 0;
                        $subtotal = $detail['produk'][$idpop]['subtotal'] ?? 0;
                    ?>
                    <td><?= $jumlah ?></td>
                    <td><?= number_format($subtotal) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    </body>
</html>
