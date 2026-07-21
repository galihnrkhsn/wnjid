<?php
  header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=PO KOKO EMBROIDERY BATCH 1 - 2+.xls"); 
  session_start();
  include 'koneksi.php';
  if(!isset($_SESSION["administrator"])){
    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login.php';</script>";
    header('location:login.php');
    exit();
  }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Admin Pusat | Wanoja</title>

        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="css/sb-admin-2.min.css" rel="stylesheet">

    </head>
              
    <body>
        <table border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Warna</th>
                    <th>Batch 1</th>
                    <th>Batch 2</th>
                    <th>Batch 2+</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query = $koneksi->query("SELECT 
                                                podetail.variant AS variant, -- Menyatukan variant
                                                COALESCE(SUM(CASE WHEN poproduk.idpoproduk = 364 THEN pomitra.jumlah ELSE 0 END), 0) AS batch1,
                                                COALESCE(SUM(CASE WHEN poproduk.idpoproduk = 372 THEN pomitra.jumlah ELSE 0 END), 0) AS batch2,
                                                COALESCE(SUM(CASE WHEN poproduk.idpoproduk = 379 THEN pomitra.jumlah ELSE 0 END), 0) AS batch2tambahan
                                            FROM 
                                                poproduk
                                                INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                LEFT JOIN pomitra ON podetail.idpodetail = pomitra.idpodetail 
                                                    AND pomitra.idpoproduk = poproduk.idpoproduk
                                            WHERE 
                                                poproduk.idpoproduk IN (364, 372, 379)
                                            GROUP BY 
                                                podetail.variant -- Menggabungkan berdasarkan variant
                                            ORDER BY 
                                                podetail.variant;");
                    $no = 1;
                    while($data = $query->fetch_assoc()) {
                        $batch1 += $data['batch1'];
                        $batch2 += $data['batch2'];
                        $batch2tambahan += $data['batch2tambahan'];
                        $total  = $data['batch1'] + $data['batch2'] + $data['batch2tambahan'];
                        $totalSemua += $total
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $data['variant'] ?></td>
                    <td><?= $data['batch1'] ?></td>
                    <td><?= $data['batch2'] ?></td>
                    <td><?= $data['batch2tambahan'] ?></td>
                    <td><?= $total; ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="2">Total</td>
                    <td><?= $batch1 ?></td>
                    <td><?= $batch2 ?></td>
                    <td><?= $batch2tambahan ?></td>
                    <td><?= $totalSemua ?></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
