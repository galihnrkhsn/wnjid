<?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Surat Jalan Pengiriman.xls"); 
    session_start();
    
    include 'koneksi.php';
    if (!isset($_SESSION['logistik'])) {
        echo "
            <script>
                alert('Login terlebih dahulu')
                location='login.php'
            </script>
        ";
        exit();
    }

    $iduser     = $_SESSION['logistik']['id'];
    $ekspedisi  = $_GET['eks'];
    $tgl        = $_GET['tgl'];
?>
<h2>Surat Jalan Pengiriman</h2>
<p>Tanggal: <?= $tgl; ?></p>
<table border="1">
    <thead>
        <tr>
            <th>Ekspedisi</th>
            <th>Penerima</th>
            <th>Proses</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $sql = $koneksi->query("SELECT * FROM logistik3 WHERE tgl = '$tgl' AND ekspedisi LIKE '%$ekspedisi%' ORDER BY idlogistik");
            while ($data = $sql->fetch_assoc()) {
        ?>
        <tr>
            <td><?= $data['ekspedisi'] ?></td>
            <td><?= $data['penerima'] ?></td>
            <td></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
