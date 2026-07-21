<?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Kekurangan Ambil Barang PO Per $jenis.xls");
    session_start();
    include 'koneksi.php'; 

    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $no         = 1;
    $idpoproduk = $_GET['id'];
    $jenis      = $_GET['jenis'];
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <?php 
        $sql2 = "SELECT * FROM poproduk  WHERE idpoproduk='$idpoproduk'";
        $query2 = $koneksi->query($sql2);
        $apaya = $query2->fetch_assoc();
        $namapo = $apaya['namapo'];
    ?>  
    <h1><?= $namapo ?></h1>
    <table class="table table-bordered" border="1" style="font-size: 12px;">
        <thead>
            <tr>
                <th>No</th>
                <th>Idpomitra</th>
                <th>Invoice</th>
                <th>Nama DB</th>
                <th>Nama Sub-DB</th>
                <th>Nama CS</th>
                <th>Nama Produk</th>
                <?php
                    if ($idpoproduk == '328') {
                        echo "<th>Barang Set</th>";
                    }
                ?>
                <?php if ($idpoproduk == '328' || $idpoproduk == '355' || $idpoproduk == '361' || $idpoproduk == '366' || $idpoproduk == '371' || $idpoproduk == '374' || $idpoproduk == '377') : ?>
                    <th>Bundling</th>
                <?php endif; ?>
                <th>QTY</th>
                <th>Ambil</th>
                <th>Krg</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $datapo=$koneksi->query("SELECT 
                admin_mitra.namamitra, 
                admin_mitra.idadmin, 
                mitraagen.namaagen as agen,
                mitraagen.idmitraagen,
                mitrareseller.namaagen as reseller,
                mitrareseller.idmitrareseller, 
                mitramarketer.namaagen as marketer,
                mitramarketer.idmitramarketer, 
                admin_mitra_cs.namacs, 
                podetail.variant,
                pomitra.idpomitra, 
                pomitra.idpodetail, 
                pomitra.invoice, 
                pomitra.jumlah,
                pomitra.custom,
                COALESCE(SUM(surat_jalan_po.progres), 0) AS progresnya
            FROM 
                pomitra
            INNER JOIN 
                podetail ON podetail.idpodetail = pomitra.idpodetail
            LEFT JOIN 
                surat_jalan_po ON surat_jalan_po.idpomitra = pomitra.idpomitra
            LEFT JOIN 
                poproduk ON poproduk.idpoproduk = pomitra.idpoproduk
            LEFT JOIN 
                mitraagen ON pomitra.idmitraagen = mitraagen.idmitraagen
            LEFT JOIN 
                mitrareseller ON pomitra.idmitrareseller = mitrareseller.idmitrareseller
            LEFT JOIN 
                mitramarketer ON pomitra.idmitramarketer = mitramarketer.idmitramarketer
            LEFT JOIN 
                admin_mitra ON pomitra.idmitra = admin_mitra.idadmin
            LEFT JOIN 
                admin_mitra_cs ON admin_mitra.idadmin = admin_mitra_cs.idadmin
            WHERE 
                pomitra.idpoproduk = $idpoproduk
                AND pomitra.jumlah > 0
            GROUP BY 
                pomitra.idpomitra, 
                admin_mitra.namamitra, 
                admin_mitra.idadmin, 
                mitraagen.namaagen, 
                mitraagen.idmitraagen, 
                mitrareseller.namaagen, 
                mitrareseller.idmitrareseller, 
                mitramarketer.namaagen, 
                mitramarketer.idmitramarketer, 
                admin_mitra_cs.namacs, 
                podetail.variant,
                pomitra.invoice, 
                pomitra.jumlah
            ORDER BY 
                pomitra.idpomitra ASC
                                        ");
                while($tampilkan = $datapo->fetch_assoc()) {
                    $id = $tampilkan['idpomitra'];
                    $kurang = $tampilkan['jumlah'] - $tampilkan['progresnya'];
                    
                    $idmitraagen = $tampilkan['idmitraagen'];
                    $sqlmitra = $koneksi->query("SELECT 
                                                        admin_mitra.namamitra AS mitradb,
                                                        admin_mitra_cs.namacs AS namacs
                                                    FROM
                                                        mitraagen
                                                            INNER JOIN
                                                        admin_mitra ON admin_mitra.idadmin = mitraagen.idadmin
                                                            LEFT JOIN
                                                        admin_mitra_cs ON admin_mitra_cs.idadmin = admin_mitra.idadmin
                                                    WHERE
                                                        mitraagen.idmitraagen = '$idmitraagen'
                                                ");
                    $datamitra = $sqlmitra->fetch_assoc();
            ?>
                <tr> 
                    <td><?= $no++ ?></td>
                    <td><?= $tampilkan['idpomitra'] ?></td>                    
                    <td><?php echo $tampilkan['invoice']; ?></td>
                    <td><?php echo $tampilkan['namamitra']; ?> <?= $datamitra['mitradb'] ?></td>
                    <td><?php echo $tampilkan['agen']; ?><?php echo $tampilkan['reseller']; ?><?php echo $tampilkan['marketer']; ?></td>
                    <td><?php echo $tampilkan['namacs']; ?> <?= $datamitra['namacs'] ?></td>
                    <td>
                        <?= $tampilkan['variant']; ?>
                        <?php if ($idpoproduk == '328' || $idpoproduk == '355' || $idpoproduk == '361' || $idpoproduk == '366' || $idpoproduk == '371' || $idpoproduk == '374' || $idpoproduk == '377') : ?>
                            <td>
                                <?= $tampilkan['custom'] ?>
                            </td>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $tampilkan['jumlah']; ?></td>
                    <td><?php echo $tampilkan['progresnya']; ?></td>
                    <td><?php echo $kurang ?></td>
                </tr>
            <?php 
                    $total_jumlah += $tampilkan['jumlah'];
                    $total_ambil += $tampilkan['progresnya'];
                    $total_kurang += $kurang;
                }
            ?>
        </tbody>
        <tfoot>
            <tr>   
                <?php if ($idpoproduk == '328' || $idpoproduk == '355' || $idpoproduk == '361' || $idpoproduk == '366' || $idpoproduk == '371' || $idpoproduk == '374' || $idpoproduk == '377') : ?>
                    <td colspan="8">Total</td>
                <?php else : ?>
                    <td colspan="7">Total</td>                  
                <?php endif; ?>
                <td><?= $total_jumlah; ?></td>
                <td><?= $total_ambil; ?></td>
                <td><?= $total_kurang; ?></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>