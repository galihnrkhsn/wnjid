<?php 
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Penjualan PO.xls");

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();
    include 'koneksi.php'; 
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
    <h2>Penjualan PO Per <?= $filter ?></h2>  
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
                    $datapo = $koneksi->query("SELECT 
                                                    surat_jalan_po.invoice, 
                                                    surat_jalan_po.custom,
                                                    SUM(surat_jalan_po.progres) as jumlah,
                                                    surat_jalan_po.progres, 
                                                    surat_jalan_po.status, 
                                                    surat_jalan_po.waktu,

                                                    admin_mitra.namamitra, 
                                                    admin_mitra.idadmin, 

                                                    mitraagen.idmitraagen as idagen,
                                                    mitrareseller.idmitrareseller as idreseller,
                                                    mitramarketer.idmitramarketer as idmarketer,

                                                    mitraagen.namaagen as agen,
                                                    mitrareseller.namaagen as reseller,
                                                    mitramarketer.namaagen as marketer,

                                                    admin_mitra_cs.namacs, 
                                                    surat_jalan_po.id_sj,
                                                    surat_jalan_po.no_sj, 
                                                    pomitra.idpoproduk,

                                                    GROUP_CONCAT(podetail.variant SEPARATOR ' | ') as variant,
                                                    -- podetail.harga,
                                                    SUM(
                                                        CASE 
                                                            WHEN pomitra.idpoproduk = 458 
                                                            THEN pomitra.total 
                                                            ELSE podetail.harga
                                                        END
                                                    ) AS harga

                                                FROM surat_jalan_po
                                                JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                JOIN podetail ON podetail.idpodetail = surat_jalan_po.idpodetail

                                                LEFT JOIN mitraagen ON mitraagen.idmitraagen = pomitra.idmitraagen
                                                LEFT JOIN mitrareseller ON mitrareseller.idmitrareseller = pomitra.idmitrareseller
                                                LEFT JOIN mitramarketer ON mitramarketer.idmitramarketer = pomitra.idmitramarketer

                                                LEFT JOIN admin_mitra ON (
                                                    admin_mitra.idadmin = pomitra.idmitra OR 
                                                    admin_mitra.idadmin = mitraagen.idadmin OR
                                                    admin_mitra.idadmin = mitrareseller.idadmin OR
                                                    admin_mitra.idadmin = mitramarketer.idadmin
                                                )

                                                LEFT JOIN admin_mitra_cs ON admin_mitra.idadmin = admin_mitra_cs.idadmin 

                                                WHERE surat_jalan_po.status <> 'Ambil Barang' 
                                                AND SUBSTRING(surat_jalan_po.waktu, 1, 10) BETWEEN '$tanggal' AND '$tanggal2'
                                                AND admin_mitra_cs.namacs LIKE '%$namacs%' 

                                                GROUP BY 
                                                    surat_jalan_po.invoice,
                                                    CASE 
                                                        WHEN pomitra.custom IS NULL 
                                                            OR pomitra.custom = '' 
                                                            OR pomitra.idpoproduk IN (374, 371, 366, 361, 355)
                                                        THEN surat_jalan_po.id_sj
                                                        ELSE pomitra.custom
                                                    END

                                                ORDER BY surat_jalan_po.waktu DESC
                    ");
                }
                if ($filter == "Bulanan") {
                    $datapo = $koneksi->query("SELECT 
                                                    surat_jalan_po.invoice, 
                                                    surat_jalan_po.custom,
                                                    SUM(surat_jalan_po.progres) as jumlah,
                                                    surat_jalan_po.progres, 
                                                    surat_jalan_po.status, 
                                                    surat_jalan_po.waktu,

                                                    admin_mitra.namamitra, 
                                                    admin_mitra.idadmin, 

                                                    mitraagen.idmitraagen as idagen,
                                                    mitrareseller.idmitrareseller as idreseller,
                                                    mitramarketer.idmitramarketer as idmarketer,

                                                    mitraagen.namaagen as agen,
                                                    mitrareseller.namaagen as reseller,
                                                    mitramarketer.namaagen as marketer,

                                                    admin_mitra_cs.namacs, 
                                                    surat_jalan_po.id_sj,
                                                    surat_jalan_po.no_sj, 
                                                    pomitra.idpoproduk,

                                                    GROUP_CONCAT(podetail.variant SEPARATOR ' | ') as variant,
                                                    -- podetail.harga,
                                                    SUM(
                                                        CASE 
                                                            WHEN pomitra.idpoproduk = 458 
                                                            THEN pomitra.total 
                                                            ELSE podetail.harga
                                                        END
                                                    ) AS harga

                                                FROM surat_jalan_po
                                                JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                JOIN podetail ON podetail.idpodetail = surat_jalan_po.idpodetail

                                                LEFT JOIN mitraagen ON mitraagen.idmitraagen = pomitra.idmitraagen
                                                LEFT JOIN mitrareseller ON mitrareseller.idmitrareseller = pomitra.idmitrareseller
                                                LEFT JOIN mitramarketer ON mitramarketer.idmitramarketer = pomitra.idmitramarketer

                                                LEFT JOIN admin_mitra ON (
                                                    admin_mitra.idadmin = pomitra.idmitra OR 
                                                    admin_mitra.idadmin = mitraagen.idadmin OR
                                                    admin_mitra.idadmin = mitrareseller.idadmin OR
                                                    admin_mitra.idadmin = mitramarketer.idadmin
                                                )

                                                LEFT JOIN admin_mitra_cs ON admin_mitra.idadmin = admin_mitra_cs.idadmin 

                                                WHERE surat_jalan_po.status <> 'Ambil Barang' 
                                                AND SUBSTRING(surat_jalan_po.waktu, 1, 7) LIKE '%$tanggalnyaaa%'
                                                AND admin_mitra_cs.namacs LIKE '%$namacs%' 

                                                GROUP BY 
                                                    surat_jalan_po.invoice,
                                                    CASE 
                                                        WHEN pomitra.custom IS NULL 
                                                            OR pomitra.custom = '' 
                                                            OR pomitra.idpoproduk IN (374, 371, 366, 361, 355)
                                                        THEN surat_jalan_po.id_sj
                                                        ELSE pomitra.custom
                                                    END

                                                ORDER BY surat_jalan_po.waktu DESC                    
                    ");
                }                         
                $no     = 1;
                $ps = null;
                $semuanya = null;
                while($tampilkan = $datapo->fetch_assoc()){
                    $result_explode = explode(' ', $tampilkan['waktu']);
                    $tanggal_po     = $result_explode[0];
                    $no_sj          = $tampilkan['no_sj']; 
                    $invoice        = $tampilkan['invoice'];
                    $semuanya       += $tampilkan['harga']*$tampilkan['progres'];  
                    $ps             += $tampilkan['progres'];                
                    if ($tampilkan['idpoproduk'] == 447) {
                        $harga = $tampilkan['harga'] / 5;
                    } elseif ($tampilkan['idpoproduk'] == 411) {
                        $harga = $tampilkan['harga'] / 3;
                    } else {
                        $harga = $tampilkan['harga'];
                    }
            ?>
                <tr>
                    <td><strong><?php echo $no++; ?></strong></td>     
                    <td><?= date('d F Y', strtotime($tanggal_po)); ?></td>
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
                            else{
                                echo $tampilkan['namamitra'].'('.$tampilkan['idadmin'].')'; 
                                $mitra = 'Distributor';
                            }  
                        ?>                  
                    </td>
                    <td><?= $tampilkan['no_sj']; ?></td>
                    <td><?= $tampilkan['invoice']; ?></td> 
                    <td><?= $tampilkan['variant']; ?></td>  
                    <td>
                        <?= $harga ?>
                    </td>
                    <td><?= $harga-($harga*35/100); ?></td>             
                    <td><?= $tampilkan['progres']; ?></td>
                    <td>Rp. <?= number_format(($harga-($harga*35/100))*$tampilkan['progres']); ?> </td>                 
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
            <td colspan="9">Total</td>
            <td><?= $ps; ?></td>
            <td>Rp. <?= number_format($semuanya-(35/100*$semuanya)); ?></td>
            </tr>
        </tfoot>
    </table>

    <h2>Penjualan Inv Manual Per <?= $filter ?></h2>  
    <table class="table table-bordered table-striped mt-3" id="tbmaximus2">
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
                    $data_inv = $koneksi->query("SELECT surat_jalan_manual.invoice, SUM(surat_jalan_manual.progres) as jumlah,
                                                        surat_jalan_manual.progres, surat_jalan_manual.status,
                                                        surat_jalan_manual.waktu, admin_mitra.namamitra, admin_mitra.idadmin, 
                                                        admin_mitra_cs.namacs, surat_jalan_manual.id_sj, surat_jalan_manual.no_sj,
                                                        podetail.variant, podetail.harga
                                                    FROM surat_jalan_manual
                                                    JOIN podetail ON podetail.idpodetail = surat_jalan_manual.idproduk
                                                    LEFT JOIN admin_mitra on admin_mitra.idadmin = surat_jalan_manual.idadmin 
                                                    LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                    WHERE surat_jalan_manual.status = 'IPO'
                                                    AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) BETWEEN '$tanggal' AND '$tanggal2'
                                                    AND admin_mitra_cs.namacs LIKE '%$namacs%' 
                                                    GROUP BY surat_jalan_manual.id_sj
                                                    ORDER BY surat_jalan_manual.waktu DESC, surat_jalan_manual.idproduk desc, surat_jalan_manual.invoice");
                }
                if ($filter == "Bulanan") {
                    $data_inv = $koneksi->query("SELECT surat_jalan_manual.invoice, SUM(surat_jalan_manual.progres) as jumlah,
                                                        surat_jalan_manual.progres, surat_jalan_manual.status,
                                                        surat_jalan_manual.waktu, admin_mitra.namamitra, 
                                                        admin_mitra.idadmin, 
                                                        admin_mitra_cs.namacs, 
                                                        surat_jalan_manual.id_sj,
                                                        surat_jalan_manual.no_sj,
                                                        podetail.variant,
                                                        podetail.harga
                                                    FROM surat_jalan_manual
                                                    JOIN podetail ON podetail.idpodetail = surat_jalan_manual.idproduk
                                                    LEFT JOIN admin_mitra on admin_mitra.idadmin = surat_jalan_manual.idadmin 
                                                    LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                    WHERE surat_jalan_manual.status = 'IPO'
                                                    AND SUBSTRING(surat_jalan_manual.waktu, 1, 7) LIKE '%$tanggalnyaaa%'
                                                    AND admin_mitra_cs.namacs LIKE '%$namacs%' 
                                                    GROUP BY surat_jalan_manual.id_sj
                                                    ORDER BY surat_jalan_manual.waktu DESC, surat_jalan_manual.idproduk desc, surat_jalan_manual.invoice");
                }
                $no = 1;
                while($tampilkan_inv = $data_inv->fetch_assoc()){
                    $result_explode     = explode(' ', $tampilkan_inv['waktu']);
                    $tanggal_inv        = $result_explode[0];                        
                    $no_sj_inv          = $tampilkan_inv['no_sj']; 
                    $invoice_inv        = $tampilkan_inv['invoice']; 
                    $totalsemua_inv     +=($tampilkan_inv['harga']-(35/100*$tampilkan_inv['harga']))*$tampilkan_inv['progres'];  
                    $jumlahsemua_inv    += $tampilkan_inv['progres'];       
            ?>
                <tr>
                
                    <td style="text-align: center;">
                        <strong><?php echo $no++; ?></strong>
                    </td>     
                    <td>
                        <?= date('d F Y', strtotime($tanggal_inv)); ?>
                    </td>
                    <td><?= $tampilkan_inv['namacs']; ?></td>
                    <td><?= $tampilkan_inv['namamitra'].'('.$tampilkan_inv['idadmin'].')'; ?></td>
                    <td><?= $tampilkan_inv['no_sj']; ?></td>  
                    <td><?= $tampilkan_inv['invoice']; ?></td>  
                    <td><?= $tampilkan_inv['variant']; ?></td>  
                    <td><?= $tampilkan_inv['harga']; ?></td>
                    <td><?= $tampilkan_inv['harga']-(35/100*$tampilkan_inv['harga']); ?></td>                  
                    <td><?= $tampilkan_inv['progres']; ?></td>
                    <td>Rp. <?= number_format(($tampilkan_inv['harga']-(35/100*$tampilkan_inv['harga']))*$tampilkan_inv['progres']); ?></td>
                    
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9">Total</td>
                <td><?= $jumlahsemua_inv; ?></td>
                <td>Rp. <?= number_format($totalsemua_inv); ?></td>
            </tr>
        </tfoot>
    </table>      
</body>
</html>