<?php 
    include 'koneksi.php';   
    $tanggal = $_GET['hari'];
?>

<table class="table table-bordered table-striped" id="tbmaximus3">
    <thead>
        <tr>       
            <!-- <th>Check</th> -->
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama PO</th>
            <th>No Surat Jalan</th>
            <th>QTY</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            $datapo = $koneksi->query("SELECT surat_jalan_po.invoice, surat_jalan_po.progres, surat_jalan_po.status, 
                                            surat_jalan_po.waktu, SUM(surat_jalan_po.progres) as jumlah,
                                            surat_jalan_po.id_sj, surat_jalan_po.no_sj, pomitra.idpoproduk
                                        FROM surat_jalan_po
                                        JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                        WHERE surat_jalan_po.status <> 'Ambil Barang' 
                                        AND SUBSTRING(surat_jalan_po.waktu, 1, 10) LIKE '%$tanggal%'
                                        GROUP BY surat_jalan_po.no_sj
                                    ");
            $no     = 1;
            while($tampilkan = $datapo->fetch_assoc()){
                $result_explode     = explode(' ', $tampilkan['waktu']);
                $tanggal            = $result_explode[0];
                $no_sj              = $tampilkan['no_sj']; 
                $totala             = 0;
                $sql                = "SELECT podetail.harga, surat_jalan_po.progres 
                                        FROM surat_jalan_po 
                                        inner join podetail on podetail.idpodetail = surat_jalan_po.idpodetail 
                                        WHERE surat_jalan_po.no_sj = '$no_sj'";
                $query              = $koneksi->query($sql);
                while ($ga = $query->fetch_assoc()){
                    $subtotal       = $ga['harga'] * $ga['progres'];
                    $totala         = $totala + $subtotal;
                }
                $diskon             = 35/100*$totala;
                $totalnya           = $totala - $diskon;    

                $jumlahsemua        += $tampilkan['jumlah'];
                $totalsemua         += $totalnya; 

                $idpoproduk         = $tampilkan['idpoproduk'];
                $datapo1            = $koneksi->query("SELECT namapo 
                                                        FROM poproduk 
                                                        WHERE idpoproduk = '$idpoproduk'");
                $tampilpo           = $datapo1->fetch_assoc(); 
        ?>
        <tr>    
            <td><strong><?php echo $no++; ?></strong></td>
            <td><?= $tanggal; ?></td>
            <td><?= $tampilpo['namapo']; ?></td>
            <td><?= $tampilkan['no_sj']; ?></td>
            <td><?= $tampilkan['jumlah']; ?></td>
            <td>Rp. <?= number_format($totalnya); ?></td>
        </tr>
        <?php } ?>
        <tfoot>
            <tr>
                <td colspan="4">Total</td>
                <td><?= $jumlahsemua; ?></td>
                <td>Rp. <?= number_format($totalsemua); ?></td>
            </tr>
        </tfoot>
    </tbody>
</table>
<script type="text/javascript">
        $(document).ready( function () {
    $('#tbmaximus3').DataTable();
} );
</script>    

