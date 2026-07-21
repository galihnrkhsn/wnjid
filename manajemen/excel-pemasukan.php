<?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Rekap_Pemasukan.xls");
    require_once 'koneksi.php';

    $bulan          = $_GET['bulan'] ?? null;
    $tahun          = $_GET['tahun'] ?? null;

    $judul          = "";
    $filterQuery    = "";
    $totalQuery     = "";

    if ($bulan && $tahun) {
        $bulanStr       = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        $judul          = "Data Catatan Pemasukan Bulan $bulanStr-$tahun";
        $filterQuery    = "SELECT * FROM catatan WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$tahun-$bulanStr' ORDER BY id DESC";
        $totalQuery     = "SELECT TRIM(SUBSTRING_INDEX(rekening, ' ', 1)) AS bank_utama, SUM(nominal) AS total FROM catatan WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$tahun-$bulanStr' GROUP BY bank_utama";
        $jumlahTotal    = "SELECT SUM(nominal) AS jumlah_total FROM catatan WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$tahun-$bulanStr'";
    } else {
        echo "Parameter tidak valid.";
        exit;
    }

    $query          = $koneksi->query($filterQuery);
    $total          = $koneksi->query($totalQuery);
    $jumlah         = $koneksi->query($jumlahTotal);
    $totalJumlah    = $jumlah->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $judul ?></title>
</head>
<body>

<h3><?= $judul ?></h3>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Rekening</th>
            <th>Nominal</th>
            <th>Keterangan</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = $query->fetch_assoc()):
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['rekening'] ?></td>
            <td><?= $row['nominal'] ?></td>
            <td><?= $row['ket'] ?></td>
            <td><?= $row['tanggal'] ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">Total:</td>
            <td colspan="3"><?= $totalJumlah['jumlah_total']; ?></td>
        </tr>
    </tfoot>
</table>

<br>

<h3>Summary</h3>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Rekening</th>
            <th>Total Nominal</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($rekap = $total->fetch_assoc()): ?>
        <tr>
            <td><?= $rekap['bank_utama'] ?></td>
            <td><?= $rekap['total'] ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
    <tfoot>
        <tr>
            <td>Total:</td>
            <td><?= $totalJumlah['jumlah_total']; ?></td>
        </tr>
    </tfoot>
</table>

</body>
</html>
