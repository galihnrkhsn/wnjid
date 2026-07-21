<?php 
    session_start();
    include 'koneksi.php';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Penjualan Ready Stok lama.xls");
    
    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }
    $no         = 1;
    $filter     = $_POST['filter'];
    $tanggal    = $_POST['tanggal'];
    $namacs     = $_POST['namacs'];
    
    if ($filter == "Harian") {
        $tanggal2 = $_POST["tanggal2"];
    }
    if ($filter == 'Bulanan') {
        $tahunsekarang  = date('Y');
        $tanggalnyaaa   = $tahunsekarang.'-'.$tanggal;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style type="text/css">
        table, tr , td, th {
            border: 1px solid
        }
    </style>
</head>
<body>
    <h2>Penjualan Ready Stok Per <?= $filter ?></h2>	
    <table class="table table-bordered table-striped mt-3" id="tbmaximus">
        <thead>
            <tr>       
                <th>No</th>
                <th>Tanggal</th>
                <th>CS</th>
                <th>Nama Mitra</th>
                <th>No Surat Jalan</th>
                <th>Invoice</th>
                <th>Produk</th>
                <th>Harga Ecer</th>
                <th>Harga DB</th>
                <th>QTY</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($filter == "Harian") {
                $datapo = $koneksi->query("SELECT surat_jalan.invoice, SUM(surat_jalan.progres) as jumlah,
                                                surat_jalan.progres, surat_jalan.status, surat_jalan.waktu,
                                                admin_mitra.namamitra, admin_mitra.idadmin, admin_mitra_cs.namacs, 
                                                surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.invoice,
                                                produk.namaproduk, produk.harga, ordermitra.tgl
                                            FROM surat_jalan
                                            JOIN ordermitra on ordermitra.invoice = surat_jalan.invoice 
                                            INNER JOIN produk on produk.idproduk = surat_jalan.idproduk 
                                            LEFT JOIN admin_mitra on admin_mitra.idadmin = ordermitra.idmitra 
                                            LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                            WHERE surat_jalan.status <> 'Ambil Barang' 
                                            AND SUBSTRING(surat_jalan.waktu, 1, 10) BETWEEN '$tanggal' AND '$tanggal2'
                                            AND admin_mitra_cs.namacs LIKE '%$namacs%' 
                                            AND ordermitra.tgl < '2024-11-02'
                                            GROUP BY surat_jalan.id_sj
                                            ORDER BY surat_jalan.waktu DESC, surat_jalan.invoice");
            }
            if ($filter == "Bulanan") {
                $datapo = $koneksi->query("SELECT surat_jalan.invoice, SUM(surat_jalan.progres) as jumlah,
                                                surat_jalan.progres, surat_jalan.status, surat_jalan.waktu,
                                                admin_mitra.namamitra, admin_mitra.idadmin, admin_mitra_cs.namacs, 
                                                surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.invoice,
                                                produk.namaproduk, produk.harga, ordermitra.tgl
                                            FROM surat_jalan
                                            JOIN ordermitra on ordermitra.invoice = surat_jalan.invoice 
                                            INNER JOIN produk on produk.idproduk = surat_jalan.idproduk 
                                            LEFT JOIN admin_mitra on admin_mitra.idadmin = ordermitra.idmitra 
                                            LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                            WHERE surat_jalan.status <> 'Ambil Barang' 
                                            AND SUBSTRING(surat_jalan.waktu, 1, 7) LIKE '%$tanggalnyaaa%'
                                            AND admin_mitra_cs.namacs LIKE '%$namacs%' 
                                            AND ordermitra.tgl < '2024-11-02'
                                            GROUP BY surat_jalan.id_sj
                                            ORDER BY surat_jalan.waktu DESC, surat_jalan.invoice");
                }
                $no=1;
                while($tampilkan = $datapo->fetch_assoc()){
                    $result_explode = explode(' ', $tampilkan['waktu']);
                    $tanggal_rs     = $result_explode[0];
                    $no_sj          = $tampilkan['no_sj']; 
                    $invoice        = $tampilkan['invoice']; 
                    $totala         = $tampilkan['harga'];
                    $total_progres  = $tampilkan['progres'];
                    $diskon         = 35/100*$totala;
                    $totalnya       = $totala - $diskon;
                    $jumlahsemua    += $total_progres;
                    $totalsemua     += $totalnya*$total_progres;                       
            ?>
                <tr>
                    <td><strong><?php echo $no++; ?></strong></td>     
                    <td><?= date('d F Y', strtotime($tanggal_rs)); ?></td>
                    <td><?= $tampilkan['namacs']; ?></td>
                    <td><?= $tampilkan['namamitra'].'('.$tampilkan['idadmin'].')'; ?></td>
                    <td><?= $tampilkan['no_sj']; ?></td>
                    <td><?= $tampilkan['invoice']; ?></td>               
                    <td><?= $tampilkan['namaproduk']; ?></td> 
                    <td><?= $tampilkan['harga']; ?></td>
                    <td><?= $totalnya; ?></td>
                    <td><?= $tampilkan['progres']; ?></td>   
                    <td>Rp. <?= number_format($totalnya*$total_progres); ?> </td>                 
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9">Total</td>
                <td><?= $jumlahsemua; ?></td>
                <td>Rp. <?= number_format($totalsemua); ?></td>
            </tr>
        </tfoot>
    </table>
    <h2>Penjualan Ready Stok Sub-DB Per <?= $filter ?></h2>    
    <table class="table table-bordered table-striped mt-3" id="tbmaximus3">
        <thead>
            <tr>       
                <th>No</th>
                <th>Tanggal</th>
                <th>CS</th>
                <th>Nama Mitra</th>
                <th>No Surat Jalan</th>
                <th>Invoice</th>
                <th>Produk</th>
                <th>Harga Ecer</th>
                <th>Harga DB</th>
                <th>QTY</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if ($filter == "Harian") {
                    $datapo = $koneksi->query("SELECT surat_jalan_subdb.invoice,
                                                    SUM(surat_jalan_subdb.progres) as jumlah,
                                                    surat_jalan_subdb.progres,
                                                    surat_jalan_subdb.status,
                                                    surat_jalan_subdb.waktu,
                                                    admin_mitra.namamitra, 
                                                    admin_mitra.idadmin, 
                                                    mitraagen.idmitraagen as idagen,
                                                    mitrareseller.idmitrareseller as idreseller,
                                                    mitramarketer.idmitramarketer as idmarketer,
                                                    mitraagen.namaagen as agen,
                                                    mitrareseller.namaagen as reseller,
                                                    mitramarketer.namaagen as marketer,
                                                    admin_mitra_cs.namacs, 
                                                    surat_jalan_subdb.id_sj,
                                                    surat_jalan_subdb.no_sj,
                                                    produk.namaproduk,
                                                    produk.harga
                                                FROM surat_jalan_subdb
                                                INNER JOIN produk on produk.idproduk = surat_jalan_subdb.idproduk 
                                                LEFT JOIN orderagen on orderagen.invoice = surat_jalan_subdb.invoice 
                                                LEFT JOIN mitraagen on mitraagen.idmitraagen = orderagen.idmitraagen

                                                LEFT JOIN orderreseller on orderreseller.invoice = surat_jalan_subdb.invoice 
                                                LEFT JOIN mitrareseller on mitrareseller.idmitrareseller = orderreseller.idmitrareseller

                                                LEFT JOIN ordermarketer on ordermarketer.invoice = surat_jalan_subdb.invoice 
                                                LEFT JOIN mitramarketer on mitramarketer.idmitramarketer = ordermarketer.idmitramarketer

                                                LEFT JOIN admin_mitra on (admin_mitra.idadmin = mitraagen.idadmin or
                                                                        admin_mitra.idadmin = mitrareseller.idadmin or
                                                                        admin_mitra.idadmin = mitramarketer.idadmin)
                                                LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                WHERE surat_jalan_subdb.status <> 'Ambil Barang' 
                                                AND SUBSTRING(surat_jalan_subdb.waktu, 1, 10) BETWEEN '$tanggal' AND '$tanggal2'
                                                AND admin_mitra_cs.namacs LIKE '%$namacs%' 
                                                AND surat_jalan_subdb.waktu < '2024-11-02 23:59:59'
                                                GROUP BY surat_jalan_subdb.id_sj
                                                ORDER BY surat_jalan_subdb.waktu DESC, surat_jalan_subdb.invoice");
                }
                if ($filter == "Bulanan") {
                    $datapo = $koneksi->query("SELECT surat_jalan_subdb.invoice, SUM(surat_jalan_subdb.progres) as jumlah,
                                                    surat_jalan_subdb.progres, surat_jalan_subdb.status, surat_jalan_subdb.waktu,
                                                    admin_mitra.namamitra, admin_mitra.idadmin, mitraagen.idmitraagen as idagen,
                                                    mitrareseller.idmitrareseller as idreseller, mitramarketer.idmitramarketer as idmarketer,
                                                    mitraagen.namaagen as agen, mitrareseller.namaagen as reseller, mitramarketer.namaagen as marketer,
                                                    admin_mitra_cs.namacs, surat_jalan_subdb.id_sj, surat_jalan_subdb.no_sj,
                                                    produk.namaproduk, produk.harga
                                                FROM surat_jalan_subdb
                                                INNER JOIN produk on produk.idproduk=surat_jalan_subdb.idproduk 
                                                LEFT JOIN orderagen on orderagen.invoice=surat_jalan_subdb.invoice 
                                                LEFT JOIN mitraagen on mitraagen.idmitraagen = orderagen.idmitraagen

                                                LEFT JOIN orderreseller on orderreseller.invoice=surat_jalan_subdb.invoice 
                                                LEFT JOIN mitrareseller on mitrareseller.idmitrareseller = orderreseller.idmitrareseller

                                                LEFT JOIN ordermarketer on ordermarketer.invoice=surat_jalan_subdb.invoice 
                                                LEFT JOIN mitramarketer on mitramarketer.idmitramarketer = ordermarketer.idmitramarketer

                                                LEFT JOIN admin_mitra on (admin_mitra.idadmin = mitraagen.idadmin or
                                                                        admin_mitra.idadmin = mitrareseller.idadmin or
                                                                        admin_mitra.idadmin = mitramarketer.idadmin)
                                                LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                            WHERE surat_jalan_subdb.status <> 'Ambil Barang' 
                                                            AND SUBSTRING(surat_jalan_subdb.waktu, 1, 7) LIKE '%$tanggalnyaaa%'
                                                            AND admin_mitra_cs.namacs LIKE '%$namacs%'
                                                            AND surat_jalan_subdb.waktu < '2024-11-02 23:59:59' 
                                                GROUP BY surat_jalan_subdb.id_sj
                                                ORDER BY surat_jalan_subdb.waktu DESC, surat_jalan_subdb.invoice");
                }              
                $no=1;
                while($tampilkan=$datapo->fetch_assoc()){
                    $result_explode = explode(' ', $tampilkan['waktu']);
                    $tanggal_rs_sub = $result_explode[0];
                    $no_sj          = $tampilkan['no_sj']; 
                    $invoice        = $tampilkan['invoice']; 
                    $totala         = $tampilkan['harga'];
                    $total_progres  = $tampilkan['progres'];
                    $diskon         = 35/100*$totala;
                    $totalnya       = $totala - $diskon;    
                    $jumlahsemua_sub    += $total_progres;
                    $totalsemua_sub     += $totalnya*$total_progres;                       
            ?>
                <tr>
                    <td style="text-align: center;"><strong><?php echo $no++; ?></strong></td>     
                    <td><?= date('d F Y', strtotime($tanggal_rs_sub)); ?></td>
                    <td><?= $tampilkan['namacs']; ?></td>
                    <td>
                        <?php 
                            if ($tampilkan['marketer']!='') {
                                echo $tampilkan['marketer'].'('.$tampilkan['idmarketer'].')'; 
                                $mitra = 'Marketer';              
                            }
                            elseif ($tampilkan['reseller']!='') {
                                echo $tampilkan['reseller'].'('.$tampilkan['idreseller'].')'; 
                                $mitra = 'Reseller'; 
                            }
                            elseif ($tampilkan['agen']!='') {
                                echo $tampilkan['agen'].'('.$tampilkan['idagen'].')';   
                                $mitra = 'Agen'; 
                            } 
                        ?>                   
                    </td>
                    <td><?= $tampilkan['no_sj']; ?></td>
                    <td><?= $tampilkan['invoice']; ?></td>  
                    <td><?= $tampilkan['namaproduk']; ?></td>  
                    <td><?= $tampilkan['harga']; ?></td>  
                    <td><?= $totalnya; ?></td>            
                    <td><?= $total_progres; ?></td>
                    <td>Rp. <?= number_format($totalnya*$total_progres); ?></td>                 
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9">Total</td>
                <td><?= $jumlahsemua_sub; ?></td>
                <td>Rp. <?= number_format($totalsemua_sub); ?></td>
            </tr>
        </tfoot>
    </table>


    <h2>Penjualan Invoice Manual Per <?= $filter ?></h2>  
    <table class="table table-bordered table-striped mt-3" id="tbmaximus2">
        <thead>
            <tr>       
                <th>No</th>
                <th>Tanggal</th>
                <th>CS</th>
                <th>Nama Mitra</th>
                <th>Invoice</th>
                <th>Produk</th>
                <th>Harga Ecer</th>
                <th>Harga DB</th>
                <th>QTY</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if ($filter == "Harian") {
                    $data_inv=$koneksi->query("SELECT surat_jalan_manual.invoice, SUM(surat_jalan_manual.progres) as jumlah,
                                                    surat_jalan_manual.progres, surat_jalan_manual.status,
                                                    surat_jalan_manual.waktu, admin_mitra.namamitra, 
                                                    admin_mitra.idadmin, admin_mitra_cs.namacs, 
                                                    surat_jalan_manual.id_sj, produk.namaproduk, produk.harga
                                                FROM surat_jalan_manual
                                                JOIN admin_mitra on admin_mitra.idadmin = surat_jalan_manual.idadmin 
                                                INNER JOIN produk on produk.idproduk=surat_jalan_manual.idproduk 
                                                LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                WHERE surat_jalan_manual.status = 'INV'
                                                AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) BETWEEN '$tanggal' AND '$tanggal2'
                                                AND admin_mitra_cs.namacs LIKE '%$namacs%' 
                                                AND surat_jalan_manual.waktu < '2024-11-02 23:59:59'
                                                GROUP BY surat_jalan_manual.id_sj
                                                ORDER BY surat_jalan_manual.waktu DESC, surat_jalan_manual.invoice");
                }
                if ($filter == "Bulanan") {
                    $data_inv = $koneksi->query("SELECT surat_jalan_manual.invoice, SUM(surat_jalan_manual.progres) as jumlah,
                                                        surat_jalan_manual.progres, surat_jalan_manual.status, surat_jalan_manual.waktu,
                                                        admin_mitra.namamitra, admin_mitra.idadmin, admin_mitra_cs.namacs, 
                                                        surat_jalan_manual.id_sj, produk.namaproduk, produk.harga
                                                    FROM surat_jalan_manual
                                                    JOIN admin_mitra on admin_mitra.idadmin = surat_jalan_manual.idadmin 
                                                    INNER JOIN produk on produk.idproduk = surat_jalan_manual.idproduk 
                                                    LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                    WHERE surat_jalan_manual.status = 'INV'
                                                    AND SUBSTRING(surat_jalan_manual.waktu, 1, 7) LIKE '%$tanggalnyaaa%'
                                                    AND admin_mitra_cs.namacs LIKE '%$namacs%' 
                                                    AND surat_jalan_manual.waktu < '2024-11-02 23:59:59'
                                                    GROUP BY surat_jalan_manual.id_sj
                                                    ORDER BY surat_jalan_manual.waktu DESC, surat_jalan_manual.invoice");
                }

                $no = 1;
                while($tampilkan_inv = $data_inv->fetch_assoc()){
                    $result_explode     = explode(' ', $tampilkan_inv['waktu']);
                    $tanggal_inv        = $result_explode[0]; 
                    $no_sj_inv          = $tampilkan_inv['no_sj']; 
                    $invoice_inv        = $tampilkan_inv['invoice']; 
                    $total_inv          = $tampilkan_inv['harga'];
                    $total_progres_inv  = $tampilkan_inv['progres'];
                    $diskon_inv         = 35/100*$total_inv;
                    $totalnya_inv       = $total_inv  - $diskon_inv;    
                    $jumlahsemua_inv    += $total_progres_inv;
                    $totalsemua_inv     += $totalnya_inv*$total_progres_inv;                       
            ?>
                <tr>
                    <td style="text-align: center;"><strong><?php echo $no++; ?></strong></td>     
                    <td><?= date('d F Y', strtotime($tanggal_inv)); ?></td>
                    <td><?= $tampilkan_inv['namacs']; ?></td>
                    <td><?= $tampilkan_inv['namamitra'].'('.$tampilkan_inv['idadmin'].')'; ?></td>
                    <td><?= $tampilkan_inv['invoice']; ?></td>   
                    <td><?= $tampilkan_inv['namaproduk']; ?></td>
                    <td><?= $tampilkan_inv['harga']; ?></td>
                    <td><?= $totalnya_inv; ?></td>                      
                    <td><?= $total_progres_inv; ?></td> 
                    <td>Rp. <?= number_format($totalnya_inv*$total_progres_inv); ?></td>
                    
                </tr>

            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8">Total</td>
                <td><?= $jumlahsemua_inv; ?></td>
                <td>Rp. <?= number_format($totalsemua_inv); ?></td>
            </tr>
        </tfoot>
    </table> 
</body>
</html>
