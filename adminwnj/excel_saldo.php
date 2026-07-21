<?php
    ob_start();
    include "koneksi.php";
    $idmitra        = $_GET['nama']; 
    $datamitra      = $koneksi->query("SELECT admin_mitra.namamitra FROM admin_mitra where admin_mitra.idadmin= '$idmitra'");                            
    $tampil_mitra   = $datamitra->fetch_assoc();
    $nama_mitra     = $tampil_mitra['namamitra'];
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Saldo Mitra $nama_mitra.xls");
?>

<h3>Mitra <?= $nama_mitra; ?>(<?= $idmitra;?>)</h3>
<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Masuk</th>
            <th>Keluar</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
    <?php 
        if ($idmitra == 233) {
            $datamitra  = $koneksi->query("SELECT admin_mitra.namamitra,
                                                saldo.id_saldo,
                                                saldo.tgl,
                                                saldo.transaksi,
                                                saldo.debit,
                                                saldo.credit 
                                            FROM saldo 
                                            inner join admin_mitra ON saldo.idadmin=admin_mitra.idadmin 
                                            where admin_mitra.idadmin='$idmitra' 
                                            AND saldo.transaksi NOT LIKE '%Fee Order Agen%'  
                                            AND saldo.transaksi NOT LIKE '%Fee Order Reseller%'
                                            AND saldo.transaksi NOT LIKE '%Fee Order marketer%'
                                            ORDER BY `saldo`.`id_saldo` ASC 
                                        ");
            $datasisa   = $koneksi->query("SELECT admin_mitra.namamitra,
                                                saldo.debit,
                                                saldo.credit,
                                                (sum(saldo.debit) - sum(saldo.credit)) AS sisa 
                                            FROM saldo 
                                            inner join admin_mitra ON saldo.idadmin=admin_mitra.idadmin 
                                            where admin_mitra.idadmin= '$idmitra' 
                                            AND saldo.transaksi NOT LIKE '%Fee Order Agen%'  
                                            AND saldo.transaksi NOT LIKE '%Fee Order Reseller%'
                                            AND saldo.transaksi NOT LIKE '%Fee Order marketer%'
                                        ");                            
            $no    =1;
            $tampilin=$datasisa->fetch_assoc();
        } else {
            $datamitra  = $koneksi->query("SELECT admin_mitra.namamitra,
                                                saldo.id_saldo, saldo.tgl, saldo.transaksi,
                                                saldo.debit, saldo.credit 
                                            FROM saldo 
                                            LEFT JOIN admin_mitra ON saldo.idadmin = admin_mitra.idadmin 
                                            WHERE saldo.idadmin = '$idmitra' 
                                            AND (saldo.credit > 0 OR saldo.debit > 0)
                                            AND saldo.deleted_at IS NULL
                                            ORDER BY `saldo`.`id_saldo` ASC 
                                        ");

            $datasisa   = $koneksi->query("SELECT (SUM(saldo.debit) - SUM(saldo.credit)) as sisa
                                            FROM saldo 
                                            WHERE saldo.idadmin = '$idmitra' 
                                            AND saldo.deleted_at IS NULL
                                        ");                            
            $no         = 1;
            $tampilin   = $datasisa->fetch_assoc();
        }                          
        while($tampilkan = $datamitra->fetch_assoc()){
            $id         = $tampilkan['id_saldo'];
            $finalsaldo = $finalsaldo + $tampilkan['debit'] - $tampilkan['credit'];
    ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $tampilkan['tgl']; ?></td>
            <td><?php echo nl2br($tampilkan['transaksi']); ?></td>
            <td><?php echo number_format($tampilkan['debit']); ?></td>
            <td><?php echo number_format($tampilkan['credit']); ?></td>
            <td><?php echo number_format($finalsaldo); ?></td>
        </tr>
    <?php } ?>
        <tr>
            <td colspan="5"><strong>SISA SALDO</strong></td>
            <td><strong>Rp. <?php echo number_format($tampilin['sisa']); ?></strong></td>
        </tr>    
    </tbody>
</table>
<?php
  ob_end_flush();
?>