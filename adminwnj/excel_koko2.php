<?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Totalan PO Sarung Per DB.xls"); 
    session_start();
    include 'koneksi.php';
    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }
    // Daftar idpoproduk yang ingin ditampilkan
    $idpoproduk = [356, 363, 367, 375, 380, 364, 372, 379]; 
    
    // Buat string untuk IN clause
    $placeholders = implode(',', $idpoproduk);

    // Query untuk mengambil data
    $sql = "SELECT 
                am.idadmin, 
                am.namamitra, 
                pp.idpoproduk, 
                -- COALESCE(amcs.namacs, 'Tidak Ada CS') AS namacs,
                pp.namapo, 
                COALESCE(SUM(p.jumlah), 0) AS total_pomitra
            FROM admin_mitra am
            LEFT JOIN admin_mitra_cs amcs ON am.idadmin = amcs.idadmin
            CROSS JOIN poproduk pp
            LEFT JOIN pomitra p ON am.idadmin = p.idmitra AND pp.idpoproduk = p.idpoproduk
            WHERE pp.idpoproduk IN ($placeholders)
            GROUP BY am.idadmin, am.namamitra, pp.idpoproduk, pp.namapo
            ORDER BY pp.idpoproduk, am.idadmin ASC
        ";

    // Eksekusi query
    $result = $koneksi->query($sql);

    // Pastikan query berhasil
    if (!$result) {
        die("Query error: " . $koneksi->error);
    }

    // Susun data ke dalam format yang lebih mudah dipakai untuk tabel
    $admins = [];
    $produk_nama = [];

    while ($row = $result->fetch_assoc()) {
        $admins[$row['idadmin']]['nama'] = $row['namamitra'];
        $admins[$row['idadmin']]['idadmin'] = $row['idadmin'];
        // $admins[$row['idadmin']]['namacs'] = $row['namacs'];
        $admins[$row['idadmin']]['produk'][$row['idpoproduk']] = $row['total_pomitra'];
        $admins[$row['idadmin']]['total'] = ($admins[$row['idadmin']]['total'] ?? 0) + $row['total_pomitra'];
        $produk_nama[$row['idpoproduk']] = $row['namapo'];
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
                    <th>Nama DB</th>
                    <?php foreach ($produk_nama as $id => $nama): ?>
                        <th><?= htmlspecialchars($nama) ?></th>
                    <?php endforeach; ?>
                    <th>Total Keseluruhan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?= htmlspecialchars($admin['nama']) ?> (<?= htmlspecialchars($admin['idadmin']) ?>)</td>
                        <?php foreach ($produk_nama as $id => $nama): ?>
                            <td><?= $admin['produk'][$id] ?? 0 ?></td>
                        <?php endforeach; ?>
                        <td><strong><?= $admin['total'] ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
</html>
