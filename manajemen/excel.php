<?php 
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Finance.xls");
    session_start();
    include "koneksi.php";
    $tanggal1   = $_POST["tanggal1"];
    $tanggal2   = $_POST["tanggal2"];
    $tipe       = $_POST["tipe"];

    $totalQuery     = "SELECT kategori_manajemen.nama_kategori, SUM(rekeningkoran.debit) AS total 
                        FROM rekeningkoran 
                        INNER JOIN kategori_manajemen ON rekeningkoran.kategori_id = kategori_manajemen.idkategori
                        WHERE rekeningkoran.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
                        AND rekeningkoran.deleted_at IS NULL
                        AND rekeningkoran.tipe = '$tipe'
                        GROUP BY kategori_manajemen.nama_kategori
                    ";

    $jumlahTotal    = "SELECT SUM(debit) AS jumlah_total FROM rekeningkoran WHERE rekeningkoran.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND rekeningkoran.deleted_at IS NULL AND rekeningkoran.tipe = '$tipe'";

    $total          = $koneksi->query($totalQuery);
    $jumlah         = $koneksi->query($jumlahTotal);
    $totalJumlah    = $jumlah->fetch_assoc();
?>
    <h2>  Kas Tanggal <?= $tanggal1 ?> s/d <?= $tanggal2 ?></h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>kategori</th>
                <th>Kredit</th>
                <th>Debit</th>
                <th>Sisa Saldo</th>
                <th>Bank</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                // $saldo              = 0;
                $datapo             = $koneksi->query("SELECT rekeningkoran.tanggal, rekeningkoran.keterangan, 
                                                            rekeningkoran.kredit, rekeningkoran.debit,
                                                            rekeningkoran.sisasaldo,
                                                            kategori_manajemen.nama_kategori, rekeningkoran.bank
                                                        FROM rekeningkoran
                                                        INNER JOIN kategori_manajemen ON kategori_manajemen.idkategori = rekeningkoran.kategori_id 
                                                        WHERE  rekeningkoran.tipe = '$tipe' 
                                                        AND `tanggal` BETWEEN '$tanggal1' AND '$tanggal2'
                                                        AND deleted_at IS NULL 
                                                        ORDER BY tanggal ASC
                                                    ");
                while($tampilkan    = $datapo->fetch_assoc()){
                    $kredit         = (int) $tampilkan['kredit'];
                    $debit          = (int) $tampilkan['debit'];

            ?>
            <tr>                   
                <td><?= $tampilkan['tanggal']; ?></td>
                <td><?= $tampilkan['keterangan']; ?></td>
                <td><?= $tampilkan['nama_kategori']; ?></td>
                <td><?= $tampilkan['kredit']; ?></td>                          
                <td><?= $tampilkan['debit']; ?></td>                         
                <td><?= $tampilkan['sisasaldo']; ?></td>                         
                <td><?= $tampilkan['bank']; ?></td>                         
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <h3>Summary</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Total Nominal</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($rekap = $total->fetch_assoc()): ?>
            <tr>
                <td><?= $rekap['nama_kategori'] ?></td>
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
