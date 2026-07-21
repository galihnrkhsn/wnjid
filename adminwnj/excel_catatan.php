<?php
    session_start();
    include 'koneksi.php';

    if (!isset($_SESSION["administrator"])) {
        echo "<script>
            alert('Anda harus login terlebih dahulu!');
            location='login.php';
        </script>";
        header('location:login.php');
        exit();
    }
    
    $tgl = $_GET['tanggal'];
    function convertToIndonesianDate($date) {
        // Create a timestamp from the provided date
        $timestamp = strtotime($date);

        // Define arrays for Indonesian days and months
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // Get the day of the week and month
        $day = $days[date('w', $timestamp)];
        $day_number = date('d', $timestamp);
        $month = $months[date('n', $timestamp) - 1];
        $year = date('Y', $timestamp);

        // Return the formatted date
        return "$day, $day_number $month $year";
    }

    // Example usage
    $date = $tgl;
    $tanggal = convertToIndonesianDate($date);
    
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Data Catatan.xls");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h6 style="text-transform: uppercase; font-size: 24px"><?= $tanggal ?></h6>
    <table border="2">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Rekening</th>
                <th>Nominal</th>
                <th>Ket</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $no = 1;
                $query = $koneksi->query("SELECT * FROM catatan WHERE tanggal = '$tgl' ORDER BY id DESC");
                while ($data = $query->fetch_assoc()) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $tanggal ?></td>
                    <td><?= $data['rekening'] ?></td>
                    <td><?= $data['nominal'] ?></td>
                    <td><?= $data['ket'] ?></td>
                    <td><?= $data['waktu'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>