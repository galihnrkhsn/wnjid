<?php
    error_reporting(0);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';

    $idpoproduk = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $invoice    = $_GET['invoice'] ?? '';
    $idadmin    = $_SESSION["idadmin"];

    if ($idpoproduk <= 0 || $invoice === '' ) {
        header('Location: listnewpo.php');
        exit;
    }

    $stmtOwn = $koneksi->prepare("SELECT COUNT(*) as jumlah,
                        poproduk.idpoproduk,
                        poproduk.namapo,
                        poproduk.status
                        FROM poproduk
                        inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk
                        WHERE poproduk.idpoproduk = ?
                        AND pomitra.idmitra = ?
                        AND pomitra.invoice = ?
                        ");
    $stmtOwn->bind_param('iss', $idpoproduk, $idadmin, $invoice);
    $stmtOwn->execute();
    $data = $stmtOwn->get_result()->fetch_assoc();

    if (!$data || $data['jumlah'] == 0) {
        header('Location: listnewpo.php');
        exit;
    }

    date_default_timezone_set('Asia/Jakarta');

    $stmtUser  = $koneksi->prepare("SELECT namamitra FROM admin_mitra WHERE idadmin = ?");
    $stmtUser->bind_param('s', $idadmin);
    $stmtUser->execute();
    $queryUser = $stmtUser->get_result()->fetch_assoc();
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
      <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <title>Distributor | WNJ.ID</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-3" align="center">
        <h4 class="text-uppercase fw-semibold">Invoice</h4>
        <h5><?= htmlspecialchars($data['namapo'] ?? '') ?></h5>
    </div>

    <div class="container" style="font-size: .875rem">
        <p class="mb-1">Nama Mitra: <?= htmlspecialchars($queryUser['namamitra'] ?? '') ?></p>
        <p class="mb-3">No Invoice: <?= htmlspecialchars($invoice) ?></p>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Ubah Qty</th>
                        <th>QTY</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $stmtList = $koneksi->prepare("SELECT
                                                        podetail.idpodetail,
                                                        podetail.variant,
                                                        podetail.idpo,
                                                        podetail.harga,
                                                        pomitra.jumlah,
                                                        pomitra.idpomitra
                                                    FROM
                                                        podetail
                                                        INNER JOIN pokategori ON pokategori.idpo = podetail.idpo
                                                        LEFT JOIN pomitra ON pomitra.idpodetail = podetail.idpodetail
                                                            AND pomitra.idpoproduk = ?
                                                            AND pomitra.invoice = ?
                                                    WHERE
                                                        pokategori.idpoproduk = ?
                                                ");
                        $stmtList->bind_param('isi', $idpoproduk, $invoice, $idpoproduk);
                        $stmtList->execute();
                        $query = $stmtList->get_result();
                        $no = 1;
                        while ($dataproduk = $query->fetch_assoc()) {
                            $total = $dataproduk['harga'] * $dataproduk['jumlah'];
                            $minQty = ($idpoproduk == 489) ? 10 : 0;
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <form method="post" enctype="multipart/form-data">
                                    <input type="hidden" class="form-control form-control-sm" value="<?= (int) $dataproduk['idpomitra'] ?>" name="idpomitra" readonly>
                                    <div class="d-sm-flex align-items-center">
                                        <input type="number" class="form-control form-control-sm" name="qty" placeholder="Jumlah QTY" value="<?= $minQty ?>" min="<?= $minQty ?>">
                                        <button type="submit" name="update" class="mx-sm-0 mx-lg-2 btn btn-success btn-sm">Ubah</button>
                                    </div>
                                </form>
                            </td>
                            <td><?= (int) $dataproduk['jumlah'] ?></td>
                            <td><?= htmlspecialchars($dataproduk['variant']) ?></td>
                            <td>Rp. <?= number_format($dataproduk['harga']) ?></td>
                            <td>Rp. <?= number_format($total) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-1">
            <?php if ($idpoproduk == 405 || $idpoproduk == 406 || $idpoproduk == 407) : ?>
                <a href="datapokolibri3.php?id=<?= $idpoproduk ?>&invoice=<?= htmlspecialchars($invoice) ?>" class="btn btn-primary btn-sm">Simpan</a>
            <?php else : ?>
                <a href="datapo.php?id=<?= $idpoproduk ?>&invoice=<?= htmlspecialchars($invoice) ?>" class="btn btn-primary btn-sm">Simpan</a>
            <?php endif; ?>
            <br><br>
        </div>
    </div>

    <?php
        if (isset($_POST['update'])) {
            $idpomitra = isset($_POST['idpomitra']) ? (int) $_POST['idpomitra'] : 0;
            $qty       = isset($_POST['qty']) ? max(0, (int) $_POST['qty']) : 0;

            if ($qty < 10) {
                echo "<script>alert('QTY Minimal 10!')</script>";
                echo "<script>location='ubahpo_mandiri.php?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "';</script>";
                exit;
            }

            // Ambil harga & kepemilikan langsung dari database, JANGAN percaya nilai dari form
            $stmtRow = $koneksi->prepare("SELECT pomitra.jumlah, pomitra.invoice, pomitra.idmitra, pomitra.idpoproduk,
                                                  podetail.harga, podetail.idpo
                                           FROM pomitra
                                           INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                           WHERE pomitra.idpomitra = ?");
            $stmtRow->bind_param('i', $idpomitra);
            $stmtRow->execute();
            $rowData = $stmtRow->get_result()->fetch_assoc();

            if (!$rowData || $rowData['idmitra'] !== $idadmin || $rowData['invoice'] !== $invoice || (int) $rowData['idpoproduk'] !== $idpoproduk) {
                echo "<script>alert('Data tidak valid atau bukan milik anda.');</script>";
                echo "<script>location='ubahpo_mandiri.php?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "';</script>";
                exit;
            }

            $harga       = $rowData['harga'];
            $idpo        = $rowData['idpo'];
            $jumlah_lama = $rowData['jumlah'];
            $total       = $qty * $harga;

            $stmtJenisPo = $koneksi->prepare("SELECT jenis_po FROM bukapo WHERE idpoproduk = ?");
            $stmtJenisPo->bind_param('i', $idpoproduk);
            $stmtJenisPo->execute();
            $rowPO    = $stmtJenisPo->get_result()->fetch_assoc();
            $jenis_po = $rowPO['jenis_po'] ?? '';

            $stmtStok = $koneksi->prepare("SELECT stok FROM pokategori WHERE idpo = ?");
            $stmtStok->bind_param('i', $idpo);
            $stmtStok->execute();
            $dataStok = $stmtStok->get_result()->fetch_assoc();
            $stok     = $dataStok['stok'] ?? 0;

            if ($idpoproduk == 486) {
                $stmtTotalS = $koneksi->prepare("SELECT sum(jumlah) as total_qty FROM pomitra WHERE invoice = ?");
                $stmtTotalS->bind_param('s', $invoice);
                $stmtTotalS->execute();
                $rowStok  = $stmtTotalS->get_result()->fetch_assoc();
                $totalQty = $rowStok['total_qty'] ?? 0;

                $qtyAkhir = $totalQty - $jumlah_lama + $qty;
                if ($qtyAkhir > 2) {
                    echo "<script>alert('Total Qty tidak boleh lebih dari 2!');</script>";
                    echo "<script>location='ubahpo_mandiri.php?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "';</script>";
                    exit;
                }
            }

            $difference = $qty - $jumlah_lama;
            $stok_baru  = $stok - $difference;

            if ($jenis_po == 'PO dengan Stok') {
                if ($stok_baru < 0) {
                    echo "
                        <script>
                            alert('Stok tidak mencukupi! Perubahan dibatalkan.')
                            location='ubahpo_mandiri.php?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "'
                        </script>
                    ";
                } else {
                    $stmtUpdStok = $koneksi->prepare("UPDATE pokategori SET stok = ? WHERE idpo = ?");
                    $stmtUpdStok->bind_param('ii', $stok_baru, $idpo);
                    $updateStok = $stmtUpdStok->execute();

                    if ($updateStok) {
                        $stmtUpdPomitra = $koneksi->prepare("UPDATE pomitra SET jumlah = ?, total = ? WHERE idpomitra = ? AND idmitra = ?");
                        $stmtUpdPomitra->bind_param('idis', $qty, $total, $idpomitra, $idadmin);
                        $updatePomitra = $stmtUpdPomitra->execute();
                        if ($updatePomitra) {
                            echo "
                                <script>
                                    alert('Data berhasil diubah dan stok diperbarui!')
                                    location='ubahpo_mandiri.php?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "'
                                </script>
                            ";
                        } else {
                            echo "
                                <script>
                                    alert('Gagal memperbarui data di pomitra.')
                                    location='ubahpo_mandiri.php?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "'
                                </script>
                            ";
                        }
                    } else {
                        echo "
                            <script>
                                alert('Stok tidak mencukupi.')
                                location='ubahpo_mandiri.php?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "'
                            </script>
                        ";
                    }
                }
            } else {
                $stmtUpdPomitra = $koneksi->prepare("UPDATE pomitra SET jumlah = ?, total = ? WHERE idpomitra = ? AND idmitra = ?");
                $stmtUpdPomitra->bind_param('idis', $qty, $total, $idpomitra, $idadmin);
                $updatePomitra = $stmtUpdPomitra->execute();
                if ($updatePomitra) {
                    echo "
                        <script>
                            alert('Data berhasil diubah dan stok diperbarui!')
                            location='ubahpo_mandiri.php?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "'
                        </script>
                    ";
                } else {
                    echo "
                        <script>
                            alert('Gagal memperbarui data di pomitra.')
                            location='ubahpo_mandiri.php?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "'
                        </script>
                    ";
                }
            }
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>
