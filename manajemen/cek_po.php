<?php
    include 'koneksi.php';
    // include 'template/header.php'; 
    $namapo = $_GET['namapo'];
?>
<table class="table table-bordered table-striped" id="tbmaximus">
    <thead>
        <tr>       
            <!-- <th>Check</th> -->
            <th>No</th>
            <th>Tanggal</th>
            <th>No Surat Jalan</th>
            <th>QTY</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $datapo = $koneksi->query("SELECT surat_jalan_po.invoice, surat_jalan_po.progres, surat_jalan_po.status,
                                            surat_jalan_po.waktu, SUM(surat_jalan_po.progres) as jumlah,
                                            surat_jalan_po.id_sj, surat_jalan_po.no_sj
                                        FROM surat_jalan_po
                                        JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                        WHERE surat_jalan_po.status <> 'Ambil Barang' 
                                        AND pomitra.idpoproduk = '$namapo'
                                        GROUP BY surat_jalan_po.no_sj
                                    ");
            $no = 1;
            while($tampilkan = $datapo->fetch_assoc()){
                $result_explode     = explode(' ', $tampilkan['waktu']);
                $tanggal            = $result_explode[0];                     
                $no_sj              = $tampilkan['no_sj']; 
                $totala             = 0;
                $sql                = "SELECT podetail.harga, surat_jalan_po.progres 
                                        FROM surat_jalan_po 
                                        INNER JOIN podetail on podetail.idpodetail=surat_jalan_po.idpodetail 
                                        WHERE surat_jalan_po.no_sj = '$no_sj'";
                $query              = $koneksi->query($sql);
                while ($ga = $query->fetch_assoc()){
                    $subtotal = $ga['harga'] * $ga['progres'];
                    $totala = $totala + $subtotal;
                }
                $diskon         = 35/100*$totala;
                $totalnya       = $totala - $diskon;
                $jumlahsemua    += $tampilkan['jumlah'];
                $totalsemua     += $totalnya;
        ?>
        <tr>
            <td><strong><?php echo $no++; ?></strong></td>     
            <td><?= $tanggal; ?></td>
            <td><?= $tampilkan['no_sj']; ?></td>
            <td><?= $tampilkan['jumlah']; ?></td>
            <td>Rp. <?= number_format($totalnya); ?> </td>
        </tr>
        <?php } ?>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td><?= $jumlahsemua; ?></td>
                <td>Rp. <?= number_format($totalsemua); ?></td>
            </tr>
        </tfoot>
    </tbody>
</table>
<script type="text/javascript">
        $(document).ready( function () {
    $('#tbmaximus').DataTable();
} );
</script>          