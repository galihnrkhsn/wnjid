<?php 
// session_start();
include 'koneksi.php'; 

//   $tanggal = $_GET['tanggal'];

// echo "$tanggal";

  $filter_cs = $_GET['filter_cs'];

$result_explode = explode('|', $filter_cs);
$tanggal=$result_explode[0];
$filter = $result_explode[1];
$cs = $result_explode[2];


     echo "$filter_cs";

$namacs=$cs;
if ($cs=="Semua CS") {
    $namacs = "";
}
// echo $namacs;
 ?>


<table class="table table-bordered table-striped" id="tbmaximus">
                     <thead>
          <tr>       
            <!-- <th>Check</th> -->
            <th>No</th>
            <th>Tanggal</th>
            <th>CS</th>
            <th>Nama DB</th>
            <th>No Surat Jalan</th>
            <th>Invoice</th>
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
                                            admin_mitra.namamitra, 
                                            admin_mitra.idadmin, 
                                            admin_mitra_cs.namacs, 
                                            surat_jalan.id_sj,
                                            surat_jalan.no_sj
                                            FROM surat_jalan
                                            JOIN ordermitra on ordermitra.invoice=surat_jalan.invoice 
                                            LEFT JOIN admin_mitra on admin_mitra.idadmin = ordermitra.idmitra 
                                            LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                            WHERE surat_jalan.status <> 'Ambil Barang' 
                                            AND SUBSTRING(surat_jalan.waktu, 1, 10) LIKE '%$tanggal%'
                                            AND admin_mitra_cs.namacs LIKE '%$namacs%' 
                                            GROUP BY surat_jalan.no_sj
                                            ORDER BY surat_jalan.waktu DESC, surat_jalan.invoice");
            }
if ($filter=="Bulanan") {
            $datapo=$koneksi->query("SELECT surat_jalan.invoice,
                                            SUM(surat_jalan.progres) as jumlah,
                                            surat_jalan.progres,
                                            surat_jalan.status,
                                            surat_jalan.waktu,
                                            admin_mitra.namamitra, 
                                            admin_mitra.idadmin, 
                                            admin_mitra_cs.namacs, 
                                            surat_jalan.id_sj,
                                            surat_jalan.no_sj
                                            FROM surat_jalan
                                            JOIN ordermitra on ordermitra.invoice=surat_jalan.invoice 
                                            LEFT JOIN admin_mitra on admin_mitra.idadmin = ordermitra.idmitra 
                                            LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                            WHERE surat_jalan.status <> 'Ambil Barang' 
                                            AND SUBSTRING(surat_jalan.waktu, 6, 2) LIKE '%$tanggal%'
                                            AND admin_mitra_cs.namacs LIKE '%$namacs%' 
                                            GROUP BY surat_jalan.no_sj
                                            ORDER BY surat_jalan.waktu DESC, surat_jalan.invoice");
            }                         

                            $no=1;
                            while($tampilkan=$datapo->fetch_assoc()){
$result_explode = explode(' ', $tampilkan['waktu']);
$tanggal=$result_explode[0];
  
$no_sj = $tampilkan['no_sj']; 
$invoice = $tampilkan['invoice']; 



$totala = 0;
    $sql = "SELECT produk.harga, surat_jalan.progres 
    FROM surat_jalan 
    inner join produk on produk.idproduk=surat_jalan.idproduk 
    WHERE surat_jalan.no_sj='$no_sj'";
  $query = $koneksi->query($sql);
  while ($ga = $query->fetch_assoc()){
$subtotal = $ga['harga'] * $ga['progres'];
$totala = $totala + $subtotal;

}



$diskon = 35/100*$totala;
$totalnya = $totala  - $diskon;    

$jumlahsemua += $tampilkan['jumlah'];
$totalsemua += $totalnya;                       
            ?>
            <tr>
            
                <td>
                    <strong><?php echo $no++; ?></strong>
                </td>     
                <td>
                    <?= date('d F Y', strtotime($tanggal)); ?>
                </td>
                <td><?= $tampilkan['namacs']; ?></td>
                <td><?= $tampilkan['namamitra'].'('.$tampilkan['idadmin'].')'; ?></td>
                <td>
                    <?= $tampilkan['no_sj']; ?>
                    
                </td>
                <td>
            <?php 
            $data_invoice_sj=$koneksi->query("SELECT surat_jalan.invoice
                                            
                                            FROM surat_jalan
                                            
                                            WHERE surat_jalan.no_sj = '$no_sj'
                                            GROUP BY surat_jalan.invoice

                                    ");
                            while($agen_tampilkan_invoice_sj=$data_invoice_sj->fetch_assoc()){
                              echo $agen_tampilkan_invoice_sj['invoice'];
                              echo "<br>";
                            }
            ?>                      
                </td>                
                <td>
                    <?= $tampilkan['jumlah']; ?>        
                </td>
                <td>
                       
                   Rp. <?= number_format($totalnya); ?> 
                </td>
                 
            </tr>

            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="6">Total</td>
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

