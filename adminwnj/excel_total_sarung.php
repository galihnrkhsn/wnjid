<?php
    // header("Content-type: application/vnd-ms-excel");
    // header("Content-Disposition: attachment; filename=Totalan PO Sarung Per DB.xls"); 
    session_start();
    include 'koneksi.php';
    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    // Query untuk mengambil data
    // $sql1 = "SELECT 
    //             g.idproduk,
    //             v.variant,
    //             v.size,
    //             SUM(g.jumlah) AS total_jumlah,
    //             SUM(g.subtotal) AS total_subtotal
    //         FROM (
    //             SELECT idproduk, jumlah, subtotal FROM ordermitra
    //             UNION ALL
    //             SELECT idproduk, jumlah, subtotal FROM orderagen
    //             UNION ALL
    //             SELECT idproduk, jumlah, subtotal FROM orderreseller
    //             UNION ALL
    //             SELECT idproduk, jumlah, subtotal FROM ordermarketer
    //         ) AS g
    //         JOIN variants v ON g.idproduk = v.id
    //         WHERE v.idproducts = 2514
    //         GROUP BY g.idproduk
    //     ";

    // // Eksekusi query
    // $result1 = $koneksi->query($sql1);

    // // Pastikan query berhasil
    // if (!$result1) {
    //     die("Query error: " . $koneksi->error);
    // }

    // QUERY KEDUA
    $sql2 = "SELECT 
                    g.idproduk,
                    v.variant,
                    v.size,
                    SUM(g.jumlah) AS total_jumlah,
                    SUM(g.subtotal) AS total_subtotal
                FROM (
                    SELECT idproduk, jumlah, subtotal FROM ordermitra
                    UNION ALL
                    SELECT idproduk, jumlah, subtotal FROM orderagen
                    UNION ALL
                    SELECT idproduk, jumlah, subtotal FROM orderreseller
                    UNION ALL
                    SELECT idproduk, jumlah, subtotal FROM ordermarketer
                ) AS g
                JOIN variants v ON g.idproduk = v.id
                WHERE v.idproducts = 2515
                GROUP BY g.idproduk
            ";

    $result2 = $koneksi->query($sql2);
    if (!$result2) {
        die("Query error: " . $koneksi->error);
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
                    <th>Id Produk</th>
                    <th>Variant</th>
                    <th>Size</th>
                    <th>Total</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($row = $result->fetch_assoc()) {
                        $idproduk   = $row['idproduk'];
                        $total      = $row['total_jumlah'];
                        $subtotal   = $row['total_subtotal'];
                        $variant    = $row['variant'];
                        $size       = $row['size'];
                ?>
                <tr>
                    <td><?= $idproduk ?></td>
                    <td><?= $variant ?></td>
                    <td><?= $size ?></td>
                    <td><?= $total ?></td>
                    <td><?= $subtotal ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>
