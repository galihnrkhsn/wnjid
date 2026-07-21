<?php 
    include 'koneksi.php';
    $tanggalnya     = $_GET['tanggal'];
    $result_explode = explode('|', $tanggalnya);
    $tanggal        = $result_explode[0];
    $filter         = $result_explode[1];
    // var_dump($tanggalnya);
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
            if ($filter=="Harian") {
                $datapo=$koneksi->query("SELECT surat_jalan.invoice,
                                                SUM(surat_jalan.progres) as jumlah,
                                                surat_jalan.progres,
                                                surat_jalan.status,
                                                surat_jalan.waktu,
                                                surat_jalan.id_sj,
                                                surat_jalan.no_sj
                                                FROM surat_jalan
                                                WHERE surat_jalan.status <> 'Ambil Barang' 
                                                AND SUBSTRING(surat_jalan.waktu, 1, 10) LIKE '%$tanggal%'
                                                GROUP BY surat_jalan.no_sj
                                                ORDER BY surat_jalan.waktu DESC, surat_jalan.invoice");
            }
            if ($filter=="Bulanan") {
                $datapo=$koneksi->query("SELECT surat_jalan.invoice,
                                                SUM(surat_jalan.progres) as jumlah,
                                                surat_jalan.progres,
                                                surat_jalan.status,
                                                surat_jalan.waktu,
                                                surat_jalan.id_sj,
                                                surat_jalan.no_sj
                                                FROM surat_jalan
                                                WHERE surat_jalan.status <> 'Ambil Barang' 
                                                AND SUBSTRING(surat_jalan.waktu, 6, 2) LIKE '%$tanggal%'
                                                GROUP BY surat_jalan.no_sj
                                                ORDER BY surat_jalan.waktu DESC, surat_jalan.invoice");
            }
            $no = 1;
            while($tampilkan = $datapo->fetch_assoc()){
                $result_explode = explode(' ', $tampilkan['waktu']);
                $tanggal        = $result_explode[0];
                $no_sj          = $tampilkan['no_sj']; 
                $totala         = 0;
                $sql = "SELECT produk.harga, surat_jalan.progres 
                            FROM surat_jalan 
                            INNER JOIN produk ON produk.idproduk=surat_jalan.idproduk 
                            WHERE surat_jalan.no_sj='$no_sj'";
                $query = $koneksi->query($sql);
                while ($ga = $query->fetch_assoc()){
                    $subtotal = $ga['harga'] * $ga['progres'];
                    $totala = $totala + $subtotal;
                }
                $diskon     = 35/100*$totala;
                $totalnya   = $totala - $diskon;    

                $jumlahsemua += $tampilkan['jumlah'];
                $totalsemua += $totalnya;                       
        ?>
            <tr>
                <td><strong><?php echo $no++; ?></strong></td>     
                <td><i class="fas fa-calendar" style="color: red"></i> <?= $tanggal; ?></td>
                <td><?= $tampilkan['no_sj']; ?></td>
                <td><?= $tampilkan['jumlah']; ?></td>
                <td>Rp. <?= number_format($totalnya); ?></td>
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
    });
</script>       