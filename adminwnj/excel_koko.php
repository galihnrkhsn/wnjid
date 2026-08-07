<?php
  header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=PO KOKO BATCH 1 - 5.xls"); 
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

        <title>WNJ.ID</title>

        <!-- Custom fonts for this template-->
        <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    </head>
              
    <body>
        <table border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Warna</th>
                    <th>Batch 1</th>
                    <th>Batch 2</th>
                    <th>Batch 3</th>
                    <th>Batch 4</th>
                    <th>Batch 5</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query = $koneksi->query("SELECT 
                                                podetail.variant AS variant, -- Menyatukan variant
                                                COALESCE(SUM(CASE WHEN poproduk.idpoproduk = 356 THEN pomitra.jumlah ELSE 0 END), 0) AS batch1,
                                                COALESCE(SUM(CASE WHEN poproduk.idpoproduk = 363 THEN pomitra.jumlah ELSE 0 END), 0) AS batch2,
                                                COALESCE(SUM(CASE WHEN poproduk.idpoproduk = 367 THEN pomitra.jumlah ELSE 0 END), 0) AS batch3,
                                                COALESCE(SUM(CASE WHEN poproduk.idpoproduk = 375 THEN pomitra.jumlah ELSE 0 END), 0) AS batch4,
                                                COALESCE(SUM(CASE WHEN poproduk.idpoproduk = 380 THEN pomitra.jumlah ELSE 0 END), 0) AS batch5 
                                            FROM 
                                                poproduk
                                                INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                LEFT JOIN pomitra ON podetail.idpodetail = pomitra.idpodetail 
                                                    AND pomitra.idpoproduk = poproduk.idpoproduk
                                            WHERE 
                                                poproduk.idpoproduk IN (356, 363, 367, 375, 380)
                                            GROUP BY 
                                                podetail.variant -- Menggabungkan berdasarkan variant
                                            ORDER BY 
                                                podetail.variant;");
                    $no = 1;
                    while($data = $query->fetch_assoc()) {
                        $batch1 += $data['batch1'];
                        $batch2 += $data['batch2'];
                        $batch3 += $data['batch3'];
                        $batch4 += $data['batch4'];
                        $batch5 += $data['batch5'];
                        $total  = $data['batch1'] + $data['batch2'] + $data['batch3'] + $data['batch4'] + $data['batch5'];
                        $totalSemua += $total
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $data['variant'] ?></td>
                    <td><?= $data['batch1'] ?></td>
                    <td><?= $data['batch2'] ?></td>
                    <td><?= $data['batch3'] ?></td>
                    <td><?= $data['batch4'] ?></td>
                    <td><?= $data['batch5'] ?></td>
                    <td><?= $total; ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="2">Total</td>
                    <td><?= $batch1 ?></td>
                    <td><?= $batch2 ?></td>
                    <td><?= $batch3 ?></td>
                    <td><?= $batch4 ?></td>
                    <td><?= $batch5 ?></td>
                    <td><?= $totalSemua ?></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
