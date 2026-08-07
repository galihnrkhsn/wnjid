<?php
    error_reporting(0);
    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $idadmin    = $_SESSION['idadmin'];

    if ($idpoproduk <= 0) {
        header('Location: listnewpo.php');
        exit;
    }

    $stmtPo = $koneksi->prepare("SELECT poproduk.idpoproduk, poproduk.namapo, poproduk.jenis
                FROM poproduk
                WHERE poproduk.idpoproduk = ?");
    $stmtPo->bind_param('i', $idpoproduk);
    $stmtPo->execute();
    $datapo = $stmtPo->get_result()->fetch_assoc();

    $stmtTgl = $koneksi->prepare("SELECT bukapo.idpoproduk, bukapo.tgl_bayar
                    FROM bukapo
                    WHERE bukapo.idpoproduk = ?");
    $stmtTgl->bind_param('i', $idpoproduk);
    $stmtTgl->execute();
    $datapo_tgl  = $stmtTgl->get_result()->fetch_assoc();
    $tgl_bayar   = $datapo_tgl['tgl_bayar'] ?? '';
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

    <title>Distributor | WNJ.ID</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container" align="center">
        <br>
        <center><h3>Detail <?= htmlspecialchars($datapo['namapo'] ?? '') ?></h3></center>
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
                    $stmtList = $koneksi->prepare("SELECT
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
                                                            pomitra.idmitra = ?
                                                                AND poproduk.idpoproduk = ?
                                                        GROUP BY pomitra.invoice
                                                        ORDER BY pomitra.invoice DESC");
                    $stmtList->bind_param('si', $idadmin, $idpoproduk);
                    $stmtList->execute();
                    $listResult = $stmtList->get_result();
                    while ($data = $listResult->fetch_assoc()) {
                ?>
                    <tr>
                        <td class="align-middle"><?= htmlspecialchars($data['tgl']) ?></td>
                        <td class="align-middle"><?= htmlspecialchars($data['status']) ?></td>
                        <td class="align-middle">
                            <?php if ($data['status'] == 'Belum DP'): ?>
                                <center>
                                    <div id="link2<?= (int) $data['idpomitra'] ?>">
                                        <?php if (substr($data['invoice'], 0, 2) == "RT"): ?>
                                            <a class="aText" href="data_rata.php?id=<?= (int) $data['idpoproduk'] ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif ($data['jenis_po'] == 'PO Custom Inisial' || $data['jenis_po'] == 'PO Custom Template'): ?>
                                            <a class="aText" href="datapocustom3.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif ($data['jenis_po'] == 'PO Custom' || $data['jenis_po'] == 'PO Custom Stok' && $data['is_custom'] == 'BUNDLING 3'): ?>
                                            <a class="aText" href="datapobundling2.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif ($data['jenis_po'] == 'PO Custom' || $data['jenis_po'] == 'PO Custom Stok'): ?>
                                            <a class="aText" href="datapocustom2.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif ($data['jenis_po'] == 'PO Bundling 2' || $data['jenis_po'] == 'PO Bundling 5'): ?>
                                            <a class="aText" href="datapobundling2.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif ($data['jenis_po'] == 'PO Set'): ?>
                                            <a class="aText" href="datapobundling.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 1) == "S"): ?>
                                            <a class="aText" target="_blank" href="datapom3.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 2) == "DI"): ?>
                                            <a class="aText" target="_blank" href="datapom.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 3) == "DLC" || $idpoproduk == 260): ?>
                                            <a class="aText" target="_blank" href="datapom2.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif (in_array($idpoproduk, [269, 271, 282, 285, 301])): ?>
                                            <a class="aText" target="_blank" href="datapom3.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif ($idpoproduk == 299): ?>
                                            <a class="aText" target="_blank" href="datapovoal.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 3) == "MHP"): ?>
                                            <a class="aText" target="_blank" href="datapom2.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 3) == "MHC"): ?>
                                            <a class="aText" target="_blank" href="datapom.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif ($idpoproduk == 325): ?>
                                            <a class="aText" href="datapocustom.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif ($idpoproduk == 405 || $idpoproduk == 406 || $idpoproduk == 407) : ?>
                                            <a class="aText" href="datapokolibri3.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif ($idpoproduk == 328 || $idpoproduk == 331): ?>
                                            <a class="aText" href="datapocustom2.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif ($idpoproduk == 335 || $idpoproduk == 339) : ?>
                                            <a class="aText" href="datapoinner2.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif ($idpoproduk == 355 || $idpoproduk == 361 || $idpoproduk == 366 || $idpoproduk == 371 || $idpoproduk == 374) : ?>
                                            <a class="aText" href="datapobundling.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php elseif (substr($data['invoice'], 0, 1) == "D" && $idpoproduk != 328 && $idpoproduk != 331 && $idpoproduk != 335 && $idpoproduk != 339): ?>
                                            PO Reguler
                                            <br>
                                            <a class="aText" href="datapo.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                        <?php endif; ?>
                                    </div>
                                </center>
                            <?php else: ?>
                                <center>
                                    <?php if (substr($data['invoice'], 0, 2) == "RT"): ?>
                                        <a class="aText" href="data_rata.php?id=<?= (int) $data['idpoproduk'] ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                    <?php elseif ($data['jenis_po'] == 'PO Custom'): ?>
                                        <a class="aText" href="datapocustom2.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                    <?php elseif ($idpoproduk == 405 || $idpoproduk == 406 || $idpoproduk == 407) : ?>
                                        <a class="aText" href="datapokolibri3.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                    <?php elseif ($data['jenis_po'] == 'PO Bundling 2' || $data['jenis_po'] == 'PO Bundling 5'): ?>
                                        <a class="aText" href="datapobundling2.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                    <?php elseif ($data['jenis_po'] == 'PO Set'): ?>
                                        <a class="aText" href="datapobundling.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                    <?php elseif (substr($data['invoice'], 0, 1) != "D"): ?>
                                        <a class="aText" target="_blank" href="datapom.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                    <?php elseif (substr($data['invoice'], 0, 2) == "DI"): ?>
                                        <a class="aText" target="_blank" href="datapom.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                    <?php elseif ($idpoproduk == 269): ?>
                                        <a class="aText" target="_blank" href="datapom3.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                    <?php elseif ($idpoproduk == 328): ?>
                                        <a class="aText" href="datapocustom2.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                    <?php elseif ($idpoproduk == 331): ?>
                                        <a class="aText" href="datapocustom2.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                    <?php elseif ($idpoproduk == 335 || $idpoproduk == 339) : ?>
                                        <a class="aText" href="datapoinner2.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                    <?php elseif ($idpoproduk == 355 || $idpoproduk == 361 || $idpoproduk == 366 || $idpoproduk == 371 || $idpoproduk == 374): ?>
                                        <a class="aText" href="datapobundling.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                    <?php elseif (substr($data['invoice'], 0, 1) == "D" && $idpoproduk != 328 && $idpoproduk != 331 && $idpoproduk != 335 && $idpoproduk != 339): ?>
                                        <a class="aText" href="datapo.php?id=<?= (int) $data['idpoproduk'] ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                                    <?php endif; ?>
                                </center>
                            <?php endif; ?>
                        </td>
                        <?php if ($idpoproduk == 186 || $idpoproduk == 187): ?>
                            <td><?= (int) $data['jumlahnya'] ?></td>
                        <?php endif; ?>
                        <td class="align-middle">
                            <a href="listds?invoice=<?= urlencode($data['invoice']) ?>&id=<?= (int) $data['idpoproduk'] ?>" class="btn btn-primary btn-sm">Isi Alamat Dropship</a>
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
                    $stmtSubdb = $koneksi->prepare("SELECT DISTINCT pomitra.tgl,
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
                                                                    mitraagen.idadmin = ? OR
                                                                    mitrareseller.idadmin = ? OR
                                                                    mitramarketer.idadmin = ?
                                                                )
                                                            AND poproduk.idpoproduk = ?
                                                            ORDER BY pomitra.tgl DESC");
                    $stmtSubdb->bind_param('sssi', $idadmin, $idadmin, $idadmin, $idpoproduk);
                    $stmtSubdb->execute();
                    $subdbResult = $stmtSubdb->get_result();
                    while ($data = $subdbResult->fetch_assoc()) {
                ?>
                    <tr>
                        <td class="align-middle"><?= htmlspecialchars($data['tgl']) ?></td>
                        <td class="align-middle"><?= htmlspecialchars($data['agen'] . $data['reseller'] . $data['marketer']) ?></td>
                        <td class="align-middle"><?= htmlspecialchars($data['status']) ?></td>
                        <td class="align-middle">
                            <a class="text-primary" href="datapo_subdb.php?id=<?= $idpoproduk ?>&subdb=<?= urlencode($data['agen'] ?? '') ?>&invoice=<?= urlencode($data['invoice']) ?>"><?= htmlspecialchars($data['invoice']) ?></a>
                        </td>
                        <td class="align-middle"><?= htmlspecialchars($data['status']) ?></td>
                        <td>
                            <?php
                                $stmtBukapo = $koneksi->prepare("SELECT bukapo.idbpo,
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
                                                                WHERE poproduk.idpoproduk = ?
                                                                AND (
                                                                        bukapo.jenis_mitra = 'Semua Mitra'
                                                                        OR bukapo.jenis_mitra = 'Distributor'
                                                                    )
                                                                LIMIT 1");
                                $stmtBukapo->bind_param('i', $idpoproduk);
                                $stmtBukapo->execute();
                                $bukapoResult = $stmtBukapo->get_result();
                                while ($tampilkan = $bukapoResult->fetch_assoc()) {
                            ?>
                                <form method="post" class="d-flex align-items-center">
                                    <input type="hidden" name="invoice" value="<?= htmlspecialchars($data['invoice']) ?>">
                                    <?php if ($data['status'] !== "Approve DB") : ?>
                                        <button id="link3<?= htmlspecialchars($data['invoice']) ?>" type="submit" class="btn btn-primary btn-sm mx-2" name="approve">Approve</button><p id="demo3<?= htmlspecialchars($data['invoice']) ?>"></p>
                                        <?php if ($idpoproduk != 355 || $idpoproduk == 361 || $idpoproduk == 366) :?>
                                            <button type="submit" class="btn btn-danger btn-sm" name="cancel">Cancel</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <!-- MAIN CONTENT END -->

    <br><br><br><br>

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP SYNTAK -->
    <?php
        if (isset($_POST["approve"])) {
            $invoicePost = $_POST['invoice'] ?? '';

            // Pastikan invoice sub-DB ini benar-benar ada di jaringan mitra yang sedang login
            // sebelum di-approve (mencegah approve/hapus invoice milik mitra lain)
            $stmtOwnSub = $koneksi->prepare("SELECT COUNT(*) AS jumlah
                                              FROM pomitra
                                              LEFT JOIN mitraagen ON pomitra.idmitraagen = mitraagen.idmitraagen
                                              LEFT JOIN mitrareseller ON pomitra.idmitrareseller = mitrareseller.idmitrareseller
                                              LEFT JOIN mitramarketer ON pomitra.idmitramarketer = mitramarketer.idmitramarketer
                                              WHERE pomitra.invoice = ?
                                              AND pomitra.idpoproduk = ?
                                              AND (mitraagen.idadmin = ? OR mitrareseller.idadmin = ? OR mitramarketer.idadmin = ?)");
            $stmtOwnSub->bind_param('sisss', $invoicePost, $idpoproduk, $idadmin, $idadmin, $idadmin);
            $stmtOwnSub->execute();
            $ownSub = $stmtOwnSub->get_result()->fetch_assoc();

            if (($ownSub['jumlah'] ?? 0) > 0) {
                $stmtApprove = $koneksi->prepare("UPDATE pomitra SET status = 'Approve DB' WHERE invoice = ?");
                $stmtApprove->bind_param('s', $invoicePost);
                $stmtApprove->execute();
                echo "<script>alert('PO Sub DB Anda telah di Approve');</script>";
            }
            echo "<script>location='detailpo.php?id=$idpoproduk';</script>";
        }
        if (isset($_POST["cancel"])) {
            $invoicePost = $_POST['invoice'] ?? '';

            $stmtOwnSub = $koneksi->prepare("SELECT COUNT(*) AS jumlah
                                              FROM pomitra
                                              LEFT JOIN mitraagen ON pomitra.idmitraagen = mitraagen.idmitraagen
                                              LEFT JOIN mitrareseller ON pomitra.idmitrareseller = mitrareseller.idmitrareseller
                                              LEFT JOIN mitramarketer ON pomitra.idmitramarketer = mitramarketer.idmitramarketer
                                              WHERE pomitra.invoice = ?
                                              AND pomitra.idpoproduk = ?
                                              AND (mitraagen.idadmin = ? OR mitrareseller.idadmin = ? OR mitramarketer.idadmin = ?)");
            $stmtOwnSub->bind_param('sisss', $invoicePost, $idpoproduk, $idadmin, $idadmin, $idadmin);
            $stmtOwnSub->execute();
            $ownSub = $stmtOwnSub->get_result()->fetch_assoc();

            if (($ownSub['jumlah'] ?? 0) > 0) {
                $stmtCancel = $koneksi->prepare("DELETE FROM pomitra WHERE invoice = ?");
                $stmtCancel->bind_param('s', $invoicePost);
                $stmtCancel->execute();
                echo "<script>alert('PO Sub DB telah di Batalkan');</script>";
            }
            echo "<script>location='detailpo.php?id=$idpoproduk';</script>";
        }
    ?>
    <!-- PHP SYNTAK END -->

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>
