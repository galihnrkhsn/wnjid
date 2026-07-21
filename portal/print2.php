<?php
    session_start();
    $id = $_GET['id'];
    include 'koneksi.php';
    $sql = $koneksi->query("SELECT * FROM t_user WHERE id_user = '$id'");
    $data = $sql->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Surat Jalan Wanoja</title>

    <style>
        td {
            border: 1px solid black;
        }

        th {
            border: 1px solid red;
        }

        p {
            margin: 0;
        }
    </style>
</head>
<body onload="window.print()">
    <div align="center" style="margin-bottom: 0.625rem">
        <table style="width: 100%">
            <tr align="center">
                <th>
                    <p style="color: red">Jika Pesanan Sudah Sampai Segera Cek Barang Sesuai Dengan Struk</p>
                    <table style="width: 70%;">
                        <tr>
                            <th>
                                <p style="color:red; font-size: 1.875rem;" >Kami Tidak Menerima Komplain Tanpa Foto/Video Lebih dari 1x24 Jam</p>
                            </th>
                        </tr>
                    </table>
                </th>
            </tr>
        </table>
    </div>

    <div style="border-bottom: 1px dashed #000; margin-bottom: 0.625rem"></div>

    <table style="width: 100%" align="center">
        <tr align="center" colspan="3" height="50">
            <td colspan="3">
                <?php if ($data['jenis_mitra'] === "WNJ") : ?>
                    <img src="img/wanoja.png" width="100">
                <?php else : ?>
                    <img src="img/zizazu.png" width="100">
                <?php endif; ?>
            </td>
        </tr>
        <tr align="center" height="50">
            <td style="width: 30%;"><p style="font-weight: 600;">CSO</p><?= $data['namacs'] ?></td>
            <td style="width: 40%;">
                <p style="font-weight: 500; margin-top: 0.188rem"><?= $data['ekspedisi'] ?></p>
            </td>
            <?php if ($data['status'] === '') : ?>
            <td>
                <p><?= $data['status'] ?></p>
                <p><?= $data['pcs'] ?></p>
            </td>
            <?php endif; ?>
            <td style="width: 30%;">
                <?= $data['pcs']; ?>
            </td>
        </tr>
    </table>
    <p style="margin: 0.25rem auto">Mohon Halalkan Segala Kekurangan dan Ketidaknyamanan dalam Bermuamalah Bersama Kami</p>

    <div style="border-bottom: 1px dashed #000; margin-bottom: 0.625rem"></div>

    <p>Note:</p>
    <p><?= $data['keterangan'] ?></p>

    <div style="margin: 2rem auto">
        <p>Kode Mitra: <?= $data['idadmin'] ?></p>
        <p>Tanggal: <?= date('d-m-Y') ?></p>
    </div>
</body>
</html>