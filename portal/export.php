<?php
    session_start();
    include 'koneksi.php';

    date_default_timezone_set('Asia/Jakarta');
    $dateNow        = date("Y-m-d");
    $from           = $_GET['f'];
    $to             = $_GET['t'];

    function convertToIndonesianDate($date) {
        // Create a timestamp from the provided date
        $timestamp = strtotime($date);

        // Define arrays for Indonesian days and months
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // Get the day of the week and month
        $day            = $days[date('w', $timestamp)];
        $day_number     = date('d', $timestamp);
        $month          = $months[date('n', $timestamp) - 1];
        $year           = date('Y', $timestamp);

        // Return the formatted date
        return "$day, $day_number $month $year";
    }

    $dayFrom            = convertToIndonesianDate($from);
    $dayTo              = convertToIndonesianDate($to);
    $yesterday          = convertToIndonesianDate($dayKemarin);
    $days               = convertToIndonesianDate($dayLusa);

    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Data Resi.xls");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data Resi</title>
</head>
<body>
    <table border="1">
        <thead style="background-color: green">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama CS</th>
                <th>Penerima</th>
                <th>Ekspedisi</th>
                <th>Noresi</th>
                <th>Biaya Kirim</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Jenis Pengiriman</th>
                <th>Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if (isset($_GET['t'])) {
                    $sql = $koneksi->query("SELECT * FROM logistik3 WHERE tgl BETWEEN '$from' AND '$to' ORDER BY tgl");
                } else {
                    $sql = $koneksi->query("SELECT * FROM logistik3 WHERE tgl LIKE '$from' ORDER BY tgl");
                }
                $no = 1;
                while ($data = $sql->fetch_assoc()) {
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $data['tgl'] ?></td>
                    <td><?= $data['namacs'] ?></td>
                    <td><?= $data['penerima'] ?></td>
                    <td><?= $data['ekspedisi'] ?></td>
                    <td><?= $data['nores'] ?></td>
                    <td><?= $data['biayakirim'] ?></td>
                    <td><?= $data['keterangan'] ?></td>
                    <td>
                        <?php if ($data["status"] <> '') : ?>
                            <?= $data['status'] ?>
                        <?php else : ?>
                            Belum Terkirim
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($data["jenis_pengiriman"] <> '') : ?>
                            <?= $data['jenis_pengiriman'] ?>
                        <?php else : ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($data["status_pengiriman"] <> '') : ?>
                            <?= $data['status_pengiriman'] ?>
                        <?php else : ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>