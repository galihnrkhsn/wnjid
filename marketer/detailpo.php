<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesMarketer.php';
    include "settingdatatables.php";
    $idmitramarketer    = $_SESSION["idmitramarketer"];
    $findUser           = $koneksi->query("SELECT * FROM mitramarketer WHERE idmitramarketer='$idmitramarketer'");
    $queryUser          = $findUser->fetch_assoc();

    $idpoproduk         = $_GET['id'];
    $query              = "SELECT poproduk.idpoproduk, poproduk.namapo, poproduk.jenis
                            FROM poproduk  
                            WHERE poproduk.idpoproduk='$idpoproduk'";
    $sqlpo              = mysqli_query($koneksi, $query);  
    $datapo             = mysqli_fetch_array($sqlpo);

    $query_tgl          = "SELECT bukapo.idpoproduk, bukapo.tgl_bayar
                            FROM bukapo  
                            WHERE bukapo.idpoproduk='$idpoproduk'";
    $sqlpo_tgl          = mysqli_query($koneksi, $query_tgl);  
    $datapo_tgl         = mysqli_fetch_array($sqlpo_tgl); 
    $tgl_bayar          = $datapo_tgl['tgl_bayar']; 
    $waktu_bayar        = '23:59:59';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketer | Wanoja</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">
    <style>
        .aText {
            color: #c21b1b;
        }

        .bText {
            color: #00db50;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar2.php'; ?>
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
                $idmitramarketer = $_SESSION['idmitramarketer'];
                $idpoproduk      = $_GET['id'];

                $sql = mysqli_query($koneksi, "SELECT DISTINCT
                                                            pomitra.tgl,
                                                            pomitra.status,
                                                            pomitra.invoice,
                                                            SUM(pomitra.jumlah) AS jumlahnya,
                                                            poproduk.namapo,
                                                            poproduk.jenis,
                                                            pomitra.ket,
                                                            pomitra.waktu,
                                                            pomitra.idpomitra,
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
                                                            pomitra.idmitramarketer = '$idmitramarketer'
                                                                AND poproduk.idpoproduk = '$idpoproduk'
                                                        GROUP BY pomitra.invoice
                                                        ORDER BY pomitra.invoice DESC;
                                    ");
                while ($data = mysqli_fetch_array($sql)) { // Ambil semua data dari hasil eksekusi $sql
                    ?>
                    <tr>
                        <td class="align-middle"><?php echo $data['tgl']; ?></td>
                        <td class="align-middle"><?php echo $data['status']; ?></td>
                        <td class="align-middle">
                            <?php if ($data['status'] == 'Belum Acc DB' && !in_array($idpoproduk, [182, 183, 186, 187, 194])): ?>
                                <?php
                                date_default_timezone_set('Asia/Jakarta');
                                $jumlahhari     = '+1 days';
                                $tgl1           = $data['tgl'];
                                $tgl2           = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1)));
                                $tgl_bayar      = $tgl2;
                                $waktu_bayar    = $data['waktu'] ?: '23:59:59';
                                ?>
                                <center>
                                    <?php if (substr($data['invoice'], 0, 3) == "MHP"): ?>
                                        Miki Hat Polos
                                    <?php endif; ?>
                                    <div id="link2<?= $data['idpomitra']; ?>">
                                        <?php if (substr($data['invoice'], 0, 2) == "RT"): ?>
                                            <a href="data_rata.php?id=<?php echo $data['idpoproduk']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif ($data['jenis_po'] == 'PO Bundling 2'): ?>
                                            <a class="aText" href="datapobundling2.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 1) == "S"): ?>
                                            <a target="_blank" href="datapom3.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 2) == "DI"): ?>
                                            <a target="_blank" href="datapom.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 3) == "DLC" || $idpoproduk == '260'): ?>
                                            <a target="_blank" href="datapom2.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif (in_array($idpoproduk, [269, 271, 282, 285, 301])): ?>
                                            <a target="_blank" href="datapom3.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif ($idpoproduk == '299'): ?>
                                            <a target="_blank" href="datapovoal.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif ($idpoproduk === '339'): ?>
                                            <a target="_blank" class="bText" href="datapoinner.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif ($idpoproduk === '355' || $idpoproduk == '361' || $idpoproduk == '366' || $idpoproduk == '371' || $idpoproduk == '374'): ?>
                                            <a target="_blank" class="bText" href="datapobundling.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 1) == "M"): ?>
                                            PO Reguler
                                            <br>
                                            <a class="aText" href="datapo.php?idmitra=<?php echo $idmitra;?>&id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($idpoproduk != '260'): ?>
                                        <p id="demomiki<?= $data['idpomitra']; ?>"></p>
                                    <?php endif; ?>
                                </center>
                                <script>
                                    var countDownDatemiki<?= $data['idpomitra']; ?> = new Date("<?php echo $tgl_bayar; ?> <?php echo $waktu_bayar; ?>").getTime();
                                    var x = setInterval(function () {
                                        var now = new Date().getTime();
                                        var distance = countDownDatemiki<?= $data['idpomitra']; ?> - now;
                                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                        document.getElementById("demomiki<?= $data['idpomitra']; ?>").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                                        if (distance < 0) {
                                            clearInterval(x);
                                            document.getElementById("demomiki<?= $data['idpomitra']; ?>").innerHTML = "Melebihi batas waktu konfirmasi Payment PO";
                                            var y = document.getElementById("link2<?= $data['idpomitra']; ?>");
                                            y.style.display = "none";
                                        }
                                    }, 1000);
                                </script>
                            <?php else: ?>
                                <center>
                                    <?php if (substr($data['invoice'], 0, 2) == "RT"): ?>
                                        <a href="data_rata.php?id=<?php echo $data['idpoproduk']; ?>"><?php echo $data['invoice']; ?></a>
                                    <?php elseif ($data['jenis_po'] == 'PO Bundling 2'): ?>
                                        <a class="aText" href="datapobundling2.php?id=<?= $data['idpoproduk']; ?>&invoice=<?= $data['invoice']; ?>"><?= $data['invoice']; ?></a>
                                    <?php elseif (substr($data['invoice'], 0, 1) != "M"): ?>
                                        <a target="_blank" href="datapom.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                    <?php elseif (substr($data['invoice'], 0, 2) == "DI"): ?>
                                        <a target="_blank" href="datapom.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                    <?php elseif ($idpoproduk == '269'): ?>
                                        <a target="_blank" href="datapom3.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                    <?php elseif ($idpoproduk == '331'): ?>
                                        <a class="text-primary" href="datapocustom2.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                    <?php elseif ($idpoproduk === '339'): ?>
                                        <a class="text-primary" href="datapoinner.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                    <?php elseif ($idpoproduk === '355' || $idpoproduk == '361' || $idpoproduk == '366' || $idpoproduk == '371' || $idpoproduk == '374'): ?>
                                        <a class="text-primary" href="datapobundling.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                    <?php elseif (substr($data['invoice'], 0, 1) == "M"): ?>
                                        <a class="bText" href="datapo.php?idmitra=<?php echo $idmitra; ?>&id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                                    <?php endif; ?>
                                </center>
                            <?php endif; ?>
                        </td>
                        <?php if ($idpoproduk == 186 || $idpoproduk == 187): ?>
                            <td><?= $data['jumlahnya']; ?></td>
                        <?php endif; ?>
                        <td class="align-middle">
                            <a href="listds?invoice=<?php echo $data['invoice']; ?>&idmitra=<?php echo $idmitra; ?>&id=<?php echo $data['idpoproduk']; ?>" class="btn btn-primary btn-sm">Isi Alamat Dropship</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <br>
        <hr>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <?php
		$namapo=$_GET["namapo"];
        if(isset($_POST["approve"])){
            $invoice=$_POST['invoice'];
            $koneksi->query("UPDATE pomitra set status='Approve DB' where invoice='$invoice' ");
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
    <!-- PHP END -->

    <!-- FOOTER -->
    <? include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>