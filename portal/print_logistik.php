<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include 'koneksi.php';


    $tgl    = $_GET['tgl'];
    $sql    = $koneksi->query("SELECT *, logistik3.status AS statusLogistik FROM logistik3 
                                LEFT JOIN t_user ON t_user.idlogistik = logistik3.idlogistik
                                WHERE tgl = '$tgl' 
                                ORDER BY logistik3.ekspedisi ASC
                            ");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Print Logistik</title>
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 6px;
        }
    </style>
</head>
<body onload="window.print()">

<h2>Data Pengiriman - <?= htmlspecialchars($tgl) ?></h2>

<table>
    <thead>
        <tr>
            <th>Ekspedisi</th>
            <th>Penerima</th>
            <th>Checker</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while($data = $sql->fetch_assoc()) {
                $eks    = $data['ekspedisi'] ?? '';
                $ekspedisi = explode(" ", $eks);
                $ekspedisi[0] = strtolower($ekspedisi[0]);
                if ($data['ekspedisi'] == 'Wahana' || $data['ekspedisi'] == 'Wahana Ekspres') {
                    $data['ekspedisi'] = 'Wahana';
                } else {
                    $data['ekspedisi'];
                }
                $jenis_pengiriman   = $data['jenis_pengiriman'];
                $idLogistik         = $data['idlogistik'];
        ?>
            <tr>
                <td><?= htmlspecialchars($data['ekspedisi'] ?? '-') ?></td>
                <td><?= htmlspecialchars($data['penerima'] ?? '-') ?></td>
                <td></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<p class="no-print">
    <button onclick="window.print()">Print Lagi</button>
</p>

</body>
</html>