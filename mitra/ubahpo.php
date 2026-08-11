<?php
    include 'koneksi.php';
    include 'session_guard.php';
    include '../includes/mitra_role_helper.php';

    $kolomRole = mitraKolomPomitra($mitraRole);

    $idpoproduk = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $invoice    = $_GET['invoice'] ?? '';

    if ($idpoproduk <= 0 || $invoice === '') {
        header('Location: preorder.php');
        exit;
    }

    $stmtOwn = $koneksi->prepare("SELECT COUNT(*) as jumlah, poproduk.idpoproduk, poproduk.namapo, poproduk.status
                        FROM poproduk
                        INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                        WHERE poproduk.idpoproduk = ? AND pomitra.$kolomRole = ? AND pomitra.invoice = ?");
    $stmtOwn->bind_param('iis', $idpoproduk, $idMitra, $invoice);
    $stmtOwn->execute();
    $data = $stmtOwn->get_result()->fetch_assoc();

    if (!$data || $data['jumlah'] == 0) {
        header('Location: preorder.php');
        exit;
    }

    date_default_timezone_set('Asia/Jakarta');

    $identitas = mitraDetailIdentitas($koneksi, $mitraRole, $idMitra);

    $pesan = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $idpomitra = isset($_POST['idpomitra']) ? (int) $_POST['idpomitra'] : 0;
        $qty       = isset($_POST['qty']) ? max(0, (int) $_POST['qty']) : 0;

        // Ambil harga & kepemilikan langsung dari database, JANGAN percaya nilai dari form
        $stmtRow = $koneksi->prepare("SELECT pomitra.jumlah, pomitra.invoice, pomitra.$kolomRole AS pemilik, pomitra.idpoproduk,
                                              podetail.harga, podetail.idpo
                                       FROM pomitra
                                       INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                       WHERE pomitra.idpomitra = ?");
        $stmtRow->bind_param('i', $idpomitra);
        $stmtRow->execute();
        $rowData = $stmtRow->get_result()->fetch_assoc();

        if (!$rowData || (int) $rowData['pemilik'] !== $idMitra || $rowData['invoice'] !== $invoice || (int) $rowData['idpoproduk'] !== $idpoproduk) {
            $pesan = 'Data tidak valid atau bukan milik Anda.';
        } else {
            $harga       = $rowData['harga'];
            $idpo        = $rowData['idpo'];
            $jumlah_lama = $rowData['jumlah'];
            $total       = $qty * $harga;

            $stmtJenisPo = $koneksi->prepare("SELECT jenis_po FROM bukapo WHERE idpoproduk = ?");
            $stmtJenisPo->bind_param('i', $idpoproduk);
            $stmtJenisPo->execute();
            $jenis_po = $stmtJenisPo->get_result()->fetch_assoc()['jenis_po'] ?? '';

            $stmtStok = $koneksi->prepare("SELECT stok FROM pokategori WHERE idpo = ?");
            $stmtStok->bind_param('i', $idpo);
            $stmtStok->execute();
            $stok = $stmtStok->get_result()->fetch_assoc()['stok'] ?? 0;

            $qtyBoleh = true;
            if ($idpoproduk == 486) {
                $stmtTotalS = $koneksi->prepare("SELECT sum(jumlah) as total_qty FROM pomitra WHERE invoice = ?");
                $stmtTotalS->bind_param('s', $invoice);
                $stmtTotalS->execute();
                $totalQty = $stmtTotalS->get_result()->fetch_assoc()['total_qty'] ?? 0;

                $qtyAkhir = $totalQty - $jumlah_lama + $qty;
                if ($qtyAkhir > 2) {
                    $pesan    = 'Total qty tidak boleh lebih dari 2.';
                    $qtyBoleh = false;
                }
            }

            if ($qtyBoleh) {
                $difference = $qty - $jumlah_lama;
                $stok_baru  = $stok - $difference;

                if ($jenis_po == 'PO dengan Stok' && $stok_baru < 0) {
                    $pesan = 'Stok tidak mencukupi, perubahan dibatalkan.';
                } else {
                    $koneksi->begin_transaction();
                    try {
                        if ($jenis_po == 'PO dengan Stok') {
                            $stmtUpdStok = $koneksi->prepare("UPDATE pokategori SET stok = ? WHERE idpo = ?");
                            $stmtUpdStok->bind_param('ii', $stok_baru, $idpo);
                            $stmtUpdStok->execute();
                        }

                        $stmtUpdPomitra = $koneksi->prepare("UPDATE pomitra SET jumlah = ?, total = ? WHERE idpomitra = ? AND $kolomRole = ?");
                        $stmtUpdPomitra->bind_param('idii', $qty, $total, $idpomitra, $idMitra);
                        $stmtUpdPomitra->execute();

                        $koneksi->commit();
                        $pesan = 'Data berhasil diubah.';
                    } catch (Exception $e) {
                        $koneksi->rollback();
                        error_log($e->getMessage());
                        $pesan = 'Gagal memperbarui data, silakan coba lagi.';
                    }
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Ubah Pesanan PO | WNJ.ID</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <style>
        body { background: var(--wnj-bg); }
        .invoice-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.25rem;
            margin-bottom: 1rem;
        }
        .qty-form {
            display: flex;
            gap: .4rem;
            align-items: center;
        }
        .qty-form input {
            width: 70px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 720px;">
        <div class="d-flex align-items-center mt-3 mb-3" style="gap:.75rem;">
            <a href="datapo.php?id=<?= $idpoproduk ?>&invoice=<?= urlencode($invoice) ?>" class="text-muted"><i class="bi bi-arrow-left"></i></a>
            <h5 class="font-weight-bold mb-0">Ubah Pesanan</h5>
        </div>

        <?php if ($pesan !== ''): ?>
            <div class="alert alert-info"><?= htmlspecialchars($pesan) ?></div>
        <?php endif; ?>

        <div class="invoice-card">
            <p class="font-weight-bold mb-1"><?= htmlspecialchars($data['namapo'] ?? '') ?></p>
            <p class="text-muted small mb-1">Nama Mitra: <?= htmlspecialchars($identitas['nama']) ?></p>
            <p class="text-muted small mb-0">No Invoice: <?= htmlspecialchars($invoice) ?></p>
        </div>

        <div class="invoice-card">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>No</th><th>Ubah Qty</th><th>QTY</th><th>Nama Barang</th><th>Satuan</th><th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $stmtList = $koneksi->prepare("SELECT podetail.idpodetail, podetail.variant, podetail.idpo, podetail.harga,
                                                                    pomitra.jumlah, pomitra.idpomitra
                                                            FROM podetail
                                                            INNER JOIN pokategori ON pokategori.idpo = podetail.idpo
                                                            LEFT JOIN pomitra ON pomitra.idpodetail = podetail.idpodetail
                                                                AND pomitra.idpoproduk = ? AND pomitra.invoice = ?
                                                            WHERE pokategori.idpoproduk = ?");
                            $stmtList->bind_param('isi', $idpoproduk, $invoice, $idpoproduk);
                            $stmtList->execute();
                            $query = $stmtList->get_result();
                            $no = 1;
                            while ($dataproduk = $query->fetch_assoc()) {
                                $total = $dataproduk['harga'] * $dataproduk['jumlah'];
                        ?>
                            <tr>
                                <td class="align-middle"><?= $no++ ?></td>
                                <td>
                                    <form method="post" class="qty-form">
                                        <input type="hidden" value="<?= (int) $dataproduk['idpomitra'] ?>" name="idpomitra">
                                        <input type="number" class="form-control form-control-sm" name="qty" value="0" min="0">
                                        <button type="submit" name="update" class="btn btn-success btn-sm">Ubah</button>
                                    </form>
                                </td>
                                <td class="align-middle"><?= (int) $dataproduk['jumlah'] ?></td>
                                <td class="align-middle"><?= htmlspecialchars($dataproduk['variant']) ?></td>
                                <td class="align-middle">Rp. <?= number_format($dataproduk['harga']) ?></td>
                                <td class="align-middle">Rp. <?= number_format($total) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-2">
                <?php if (in_array($idpoproduk, [405, 406, 407], true)): ?>
                    <a href="../distributor/datapokolibri3.php?id=<?= $idpoproduk ?>&invoice=<?= urlencode($invoice) ?>" class="btn btn-primary btn-sm">Simpan &amp; Kembali ke Invoice</a>
                <?php else: ?>
                    <a href="datapo.php?id=<?= $idpoproduk ?>&invoice=<?= urlencode($invoice) ?>" class="btn btn-primary btn-sm">Simpan &amp; Kembali ke Invoice</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
