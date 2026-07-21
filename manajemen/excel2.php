<?php 
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Finance.xls");
    session_start();
    include "koneksi.php";
    $tanggal1   = $_POST["tanggal1"];
    $tanggal2   = $_POST["tanggal2"];
    $tipe       = $_POST["tipe"];
?>
    <h2>  Kas Tanggal <?= $tanggal1 ?> s/d <?= $tanggal2 ?></h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>kategori</th>
                <th>Debit</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $datapo             = $koneksi->query("SELECT rekeningkoran.tanggal, rekeningkoran.keterangan, 
                                                            rekeningkoran.kredit, rekeningkoran.debit,
                                                            rekeningkoran.sisasaldo,
                                                            kategori_manajemen.nama_kategori 
                                                        FROM rekeningkoran
                                                        INNER JOIN kategori_manajemen ON kategori_manajemen.idkategori = rekeningkoran.kategori_id 
                                                        WHERE  rekeningkoran.tipe = 'P' 
                                                        AND `tanggal` BETWEEN '2024-07-01' AND '2025-04-30'
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
                <td><?= $tampilkan['debit']; ?></td>                         
            </tr>
            <?php } ?>
        </tbody>
    </table>
