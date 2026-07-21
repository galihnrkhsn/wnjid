<?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Data Pengiriman Ramadhan.xls"); 
    session_start();
    include 'koneksi.php';
    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }
    // Query untuk mengambil data
    $sql = "SELECT 
                namacs,
                nama,
                nama_penerima,
                created_date,
                ekspedisi,
                resi_pengiriman,
                ongkir
            FROM
                wnj_web_v2.t_user
            WHERE
                created_date > '2025-02-28'
            AND created_date < '2025-04-01'
            ";

    // Eksekusi query
    $result = $koneksi->query($sql);

    // Pastikan query berhasil
    if (!$result) {
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
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama CS</th>
                    <th>Pengirim</th>
                    <th>Penerima</th>
                    <th>Tanggal</th>
                    <th>Ekspedisi</th>
                    <th>Resi</th>
                    <th>Ongkir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($row = $result->fetch_assoc()) {                
                        $namacs         = $row['namacs'];
                        $pengirim       = $row['nama'];
                        $penerima       = $row['nama_penerima'];
                        $tgl            = $row['created_date'];
                        $ekspedisi      = $row['ekspedisi'];
                        $noresi         = $row['resi_pengiriman'];
                        $biayakirim     = $row['ongkir'];
                ?>
                <tr>
                    <td><?= $namacs ?></td>
                    <td><?= $pengirim ?></td>
                    <td><?= $penerima ?></td>
                    <td><?= $tgl ?></td>
                    <td><?= $ekspedisi ?></td>
                    <td><?= $noresi ?></td>
                    <td><?= $biayakirim ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>



