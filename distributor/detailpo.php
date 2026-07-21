<?php
    session_start();
    error_reporting (0);
    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $query = "SELECT poproduk.idpoproduk, poproduk.namapo, poproduk.jenis
                FROM poproduk  
                WHERE poproduk.idpoproduk='$idpoproduk'";
    $sqlpo = mysqli_query($koneksi, $query);  
    $datapo = mysqli_fetch_array($sqlpo);

    $query_tgl = "SELECT bukapo.idpoproduk, bukapo.tgl_bayar
                    FROM bukapo  
                    WHERE bukapo.idpoproduk='$idpoproduk'";
    $sqlpo_tgl = mysqli_query($koneksi, $query_tgl);  
    $datapo_tgl = mysqli_fetch_array($sqlpo_tgl); 
    $tgl_bayar = $datapo_tgl['tgl_bayar']; 
    $waktu_bayar = '23:59:59';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .aText {
            color: red;
        }
    </style>
    
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <? include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container" align="center">
        <br>
        <center><h3>Detail <?= $datapo['namapo']; ?></h3></center>
        <br>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th class="text-center">Invoice</th>
                    <?php if ($idpoproduk == 186 || $idpoproduk == 187): ?>
                        <th>QTY (Pcs)</th>
                        <th>List Alamat & Kartu Ucapan</th>
                    <?php else: ?>
                        <th>List Dropship</th>
                    <?php endif; ?>
                </tr>
                <?php
                    // Include / load file koneksi.php
                    include "koneksi.php";
                    $idadmin    = $_SESSION['idadmin'];
                    $idpoproduk = $_GET['id'];

                    $sql = mysqli_query($koneksi, "SELECT
                                                            pomitra.tgl,
                                                            pomitra.status,
                                                            pomitra.invoice,
                                                            SUM(pomitra.jumlah) AS jumlahnya,
                                                            poproduk.namapo,
                                                            poproduk.jenis,
                                                            pomitra.ket,
                                                            pomitra.waktu,
                                                            pomitra.idpomitra,
                                                            pomitra.is_custom,
                                                            poproduk.idpoproduk,
                                                            podropship.namapenerima,
                                                            bukapo.jenis_po
                                                        FROM
                                                            `pomitra`
														INNER JOIN
                                                            poproduk ON pomitra.idpoproduk = poproduk.idpoproduk
														LEFT JOIN
                                                            podropship ON podropship.invoice = pomitra.invoice
														INNER JOIN bukapo ON bukapo.idpoproduk = poproduk.idpoproduk
                                                        WHERE
                                                            pomitra.idmitra = '$idadmin'
                                                                AND poproduk.idpoproduk = '$idpoproduk'
                                                        GROUP BY pomitra.invoice
                                                        ORDER BY pomitra.invoice DESC;
                                        ");
                    while ($data = mysqli_fetch_array($sql)) {
                ?>
                    <tr>
                        <td class="align-middle"><?php echo $data['tgl']; ?></td>
                        <td class="align-middle"><?php echo $data['status']; ?></td>
                        <td class="align-middle">
                            <?php if ($data['status'] == 'Belum DP'): ?>
                                <center>
                                    <div id="link2<?= $data['idpomitra']; ?>">
                                        <?php if (substr($data['invoice'], 0, 2) == "RT"): ?>
                                            <a class="aText" href="data_rata.php?id=<?php echo $data['idpoproduk']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif ($data['jenis_po'] == 'PO Custom Inisial' || $data['jenis_po'] == 'PO Custom Template'): ?>
                                            <a class="aText" href="datapocustom3.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                                        <?php elseif ($data['jenis_po'] == 'PO Custom' || $data['jenis_po'] == 'PO Custom Stok' && $data['is_custom'] == 'BUNDLING 3'): ?>
                                            <a class="aText" href="datapobundling2.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                                        <?php elseif ($data['jenis_po'] == 'PO Custom' || $data['jenis_po'] == 'PO Custom Stok'): ?>
                                            <a class="aText" href="datapocustom2.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                                        <?php elseif ($data['jenis_po'] == 'PO Bundling 2'|| $data['jenis_po'] == 'PO Bundling 5'): ?>
                                            <a class="aText" href="datapobundling2.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                                        <?php elseif ($data['jenis_po'] == 'PO Set'): ?>
                                            <a class="aText" href="datapobundling.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 1) == "S"): ?>
                                            <a class="aText" target="_blank" href="datapom3.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 2) == "DI"): ?>
                                            <a class="aText" target="_blank" href="datapom.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 3) == "DLC" || $idpoproduk == '260'): ?>
                                            <a class="aText" target="_blank" href="datapom2.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif (in_array($idpoproduk, [269, 271, 282, 285, 301])): ?>
                                            <a class="aText" target="_blank" href="datapom3.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif ($idpoproduk == '299'): ?>
                                            <a class="aText" target="_blank" href="datapovoal.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 3) == "MHP"): ?>
                                            <a class="aText" target="_blank" href="datapom2.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $$data['invoice']; ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 3) == "MHC"): ?>
                                            <a class="aText" target="_blank" href="datapom.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                                        <?php elseif ($idpoproduk == 325): ?>
                                            <a class="aText" href="datapocustom.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif ($idpoproduk == '405' || $idpoproduk == '406' || $idpoproduk == '407') : ?>
                                            <a class="aText" href="datapokolibri3.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif ($idpoproduk == 328 || $idpoproduk == 331): ?>
                                            <a class="aText" href="datapocustom2.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif ($idpoproduk == '335' || $idpoproduk == '339') : ?>
                                            <a class="aText" href="datapoinner2.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                                        <?php elseif ($idpoproduk == '355' || $idpoproduk == '361' || $idpoproduk == '366' || $idpoproduk == '371' || $idpoproduk == '374') : ?>
                                            <a class="aText" href="datapobundling.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 1) == "D" && $idpoproduk != 328 && $idpoproduk != 331 && $idpoproduk != 335 && $idpoproduk != 339): ?>
                                            PO Reguler
                                            <br>
                                            <?php if ($idpoproduk <= 499 || $idpoproduk == 518 || $idpoproduk == 521) : ?>
                                                <a class="aText" href="datapo.php?idmitra=<?php echo $idadmin; ?>&id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                            <?php else : ?>
                                                <a class="aText" href="datapo2.php?idmitra=<?php echo $idadmin; ?>&id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </center>
                            <?php else: ?>
                                <center>
                                    <?php if (substr($data['invoice'], 0, 2) == "RT"): ?>
                                        <a class="aText" href="data_rata.php?id=<?php echo $data['idpoproduk']; ?>"><?php echo $data['invoice']; ?></a>
                                    <?php elseif ($data['jenis_po'] == 'PO Custom'): ?>
                                        <a class="aText" href="datapocustom2.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                                    <?php elseif ($idpoproduk == '405' || $idpoproduk == '406' || $idpoproduk == '407') : ?>
                                        <a class="aText" href="datapokolibri3.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                    <?php elseif ($data['jenis_po'] == 'PO Bundling 2' || $data['jenis_po'] == 'PO Bundling 5'): ?>
                                        <a class="aText" href="datapobundling2.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                                    <?php elseif ($data['jenis_po'] == 'PO Set'): ?>
                                        <a class="aText" href="datapobundling.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                                    <?php elseif (substr($data['invoice'], 0, 1) != "D"): ?>
                                        <a class="aText" target="_blank" href="datapom.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                    <?php elseif (substr($data['invoice'], 0, 2) == "DI"): ?>
                                        <a class="aText" target="_blank" href="datapom.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                    <?php elseif ($idpoproduk == '269'): ?>
                                        <a class="aText" target="_blank" href="datapom3.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                    <?php elseif ($idpoproduk == 328): ?>
                                        <a class="aText" href="datapocustom2.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                    <?php elseif ($idpoproduk == 331): ?>
                                        <a class="aText" href="datapocustom2.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                    <?php elseif ($idpoproduk == '335' || $idpoproduk == '339') : ?>
                                        <a class="aText" href="datapoinner2.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                                    <?php elseif ($idpoproduk == '355' || $idpoproduk == '361' || $idpoproduk == '366' || $idpoproduk == '371' || $idpoproduk == '374'): ?>
                                        <a class="aText" href="datapobundling.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                                    <?php elseif (substr($data['invoice'], 0, 1) == "D" && $idpoproduk != 328 && $idpoproduk != 331 && $idpoproduk != 335 && $idpoproduk != 339): ?>
                                        <?php if ($idpoproduk >= 499): ?>
                                            <a class="aText" href="datapo2.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php else: ?>
                                            <a class="aText" href="datapo.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </center>
                            <?php endif; ?>
                        </td>
                        <?php if ($idpoproduk == 186 || $idpoproduk == 187): ?>
                            <td><?= $data['jumlahnya']; ?></td>
                        <?php endif; ?>
                        <td class="align-middle">
                            <a href="listds?invoice=<?php echo $data['invoice']; ?>&id=<?php echo $data['idpoproduk']; ?>" class="btn btn-primary btn-sm">Isi Alamat Dropship</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <br>
        <hr>
        <center><h3>PO mitra Sub DB</h3></center>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Invoice</th>
                    <th>Keterangan</th>
                    <th>#</th>
                </tr>
                <?php
                    $sql = mysqli_query($koneksi, "SELECT DISTINCT pomitra.tgl,
                                                                pomitra.status,
                                                                pomitra.invoice,
                                                                poproduk.namapo,
                                                                poproduk.idpoproduk,
                                                                mitraagen.namaagen AS agen,
                                                                mitrareseller.namaagen AS reseller, 
                                                                mitramarketer.namaagen AS marketer
                                                            FROM `pomitra` 
                                                            INNER JOIN poproduk ON pomitra.idpoproduk = poproduk.idpoproduk 
                                                            LEFT JOIN mitraagen ON pomitra.idmitraagen = mitraagen.idmitraagen 
                                                            LEFT JOIN mitrareseller ON pomitra.idmitrareseller = mitrareseller.idmitrareseller 
                                                            LEFT JOIN mitramarketer ON pomitra.idmitramarketer = mitramarketer.idmitramarketer 
                                                            WHERE (
                                                                    mitraagen.idadmin = '$idadmin' OR 
                                                                    mitrareseller.idadmin = '$idadmin' OR 
                                                                    mitramarketer.idadmin = '$idadmin'
                                                                ) 
                                                            AND poproduk.idpoproduk = '$idpoproduk' 
                                                            ORDER BY pomitra.tgl DESC"
                                    );
                        while ($data = mysqli_fetch_array($sql)) {
                ?>
                    <tr>
                        <td class="align-middle"><?= $data['tgl']; ?></td>
                        <td class="align-middle"><?= $data['agen']; ?><?= $data['reseller'];?><?= $data['marketer'] ?></td>
                        <td class="align-middle"><?= $data['status']; ?></td>
                        <td class="align-middle">
                            <a class="text-primary" href="datapo_subdb.php?id=<?= $idpoproduk ?>&subdb=<?= $data['agen'] ?>&invoice=<?= $data['invoice'] ?>"><?= $data['invoice']; ?></a>
                        </td>
                        <td class="align-middle"><?= $data['status']; ?></td>
                        <td>
                            <?php 
                                $no = 1;
                                $dataproduk = $koneksi->query("SELECT bukapo.idbpo,
                                                                    bukapo.jenis_mitra,
                                                                    bukapo.jenis_po,
                                                                    bukapo.idpoproduk,
                                                                    bukapo.tgl,
                                                                    bukapo.tgl_acc_db,
                                                                    bukapo.tgl_ubah,
                                                                    bukapo.tgl_dropship,
                                                                    bukapo.status AS statuspublish,
                                                                    poproduk.namapo 
                                                                FROM bukapo
                                                                INNER JOIN poproduk ON bukapo.idpoproduk = poproduk.idpoproduk 
                                                                WHERE poproduk.idpoproduk='$idpoproduk' 
                                                                AND (
                                                                        bukapo.jenis_mitra = 'Semua Mitra'
                                                                        OR bukapo.jenis_mitra = 'Distributor'
                                                                    )
                                                                LIMIT 1
                                                            ");
                                while($tampilkan = $dataproduk->fetch_assoc()){
                            ?>
                                <form method="post" class="d-flex align-items-center">
                                    <input type="hidden" name="invoice" value="<?= $data['invoice'];?>">
                                    <?php if ($data['status'] !== "Approve DB") : ?>
                                        <button id="link3<?= $data['invoice'];?>" type="submit" class="btn btn-primary btn-sm mx-2" name="approve">Approve</button><p id="demo3<?= $data['invoice'];?>"></p>
                                        <?php if ($idpoproduk != 355 || $idpoproduk == '361' || $idpoproduk == '366') :?>
                                            <button type="submit" class="btn btn-danger btn-sm" name="cancel">Cancel</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </form>
                            <?php  } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <!-- MAIN CONTENT END -->
    
    <br><br><br><br>
    
    <!-- FOOTER -->
    <? include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP SYNTAK -->
    <?php
		$namapo=$_GET["namapo"];
        if(isset($_POST["approve"])){
            $invoice=$_POST['invoice'];
            $koneksi->query("UPDATE pomitra set status='Approve DB' where invoice = '$invoice' ");
                  echo "<script>alert('PO Sub DB Anda telah di Approve');</script>";
                echo "<script>location='detailpo.php?id=$idpoproduk';</script>";
        }
        if(isset($_POST["cancel"])){
          $invoice=$_POST['invoice'];
          $koneksi->query("DELETE FROM pomitra where invoice='$invoice' ");
          echo "<script>alert('PO Sub DB telah di Batalkan');</script>";
          echo "<script>location='detailpo.php?id=$idpoproduk';</script>";
        }
    ?>
    <!-- PHP SYNTAK END -->
    
    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>