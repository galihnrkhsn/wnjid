<?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Data Distributor Dan CS.xls"); 
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

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>
    <body>
        <table>
            <thead>
                <tr>
                    <th>Idadmin</th>
                    <th>Nama Mitra</th>
                    <th>CS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query = $koneksi->query("SELECT * FROM admin_mitra_cs ORDER BY idadmin");
                    while ($data = $query->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?= $data['idadmin'] ?></td>
                        <td><?= $data['namamitra'] ?></td>
                        <td><?= $data['namacs'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>