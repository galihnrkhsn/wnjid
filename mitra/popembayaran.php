<?php
    include 'koneksi.php';
    include 'session_guard.php';
    include '../includes/mitra_role_helper.php';

    $kolomRole = mitraKolomPomitra($mitraRole);

    // rekeningku pakai nama kolom "idadmin" buat distributor (bukan "idmitra" seperti di
    // pomitra) - 3 role lain kebetulan sama namanya (idmitraagen/idmitrareseller/idmitramarketer).
    $kolomRekening = $mitraRole === 'distributor' ? 'idadmin' : $kolomRole;

    $grandtotal = $_GET['total']   ?? 0;
    $invoice    = $_GET['invoice'] ?? '';

    if ($invoice === '') {
        header('Location: preorder.php');
        exit;
    }

    // Pastikan invoice ini benar-benar milik mitra yang sedang login
    $stmtOwn = $koneksi->prepare("SELECT COUNT(*) AS jumlah FROM pomitra WHERE invoice = ? AND $kolomRole = ?");
    $stmtOwn->bind_param('si', $invoice, $idMitra);
    $stmtOwn->execute();
    if (($stmtOwn->get_result()->fetch_assoc()['jumlah'] ?? 0) == 0) {
        header('Location: preorder.php');
        exit;
    }

    // rekeningku juga sudah punya kolom per role (idadmin/idmitraagen/idmitrareseller/idmitramarketer)
    $stmtBank = $koneksi->prepare("SELECT COUNT(*) as bank FROM rekeningku WHERE $kolomRekening = ?");
    $stmtBank->bind_param('i', $idMitra);
    $stmtBank->execute();
    $adaRekening = (int) ($stmtBank->get_result()->fetch_assoc()['bank'] ?? 0);

    $identitas = mitraDetailIdentitas($koneksi, $mitraRole, $idMitra);

    $pesan = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim'])) {
        date_default_timezone_set('Asia/Jakarta');
        $tgl   = date('Y-m-d');
        $waktu = date('H:i:s');

        $bankpengirim     = $_POST['bankpengirim']     ?? '';
        $rekeningpengirim = $_POST['rekeningpengirim'] ?? '';
        $jmlhtransfer     = $_POST['jmlhtransfer']     ?? 0;
        $metodebayar      = $_POST['metodebayar']      ?? '';
        $bayar            = $_GET['bayar']             ?? 0;
        $idpoproduk       = isset($_GET['idpo']) ? (int) $_GET['idpo'] : 0;
        $jenis            = $_GET['jenis']             ?? '';
        $termin           = $_GET['termin']            ?? null;

        $ket = $bankpengirim . '-' . $rekeningpengirim . '-' . $metodebayar . '-' . $jmlhtransfer;

        $balikKeForm = 'popembayaran.php?invoice=' . rawurlencode($invoice) . '&total=' . rawurlencode($grandtotal)
            . '&bayar=' . rawurlencode($bayar) . '&idpo=' . $idpoproduk . '&jenis=' . rawurlencode($jenis);

        $namaFileBaru = null;
        if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $foto = $_FILES['foto']['name'];
            $tmp  = $_FILES['foto']['tmp_name'];

            $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
            $ekstensiGambar      = strtolower(pathinfo($foto, PATHINFO_EXTENSION));

            if (!in_array($ekstensiGambar, $ekstensiGambarValid, true)) {
                $_SESSION['message'] = 'Yang Anda upload bukan gambar.';
                header('Location: ' . $balikKeForm);
                exit;
            }

            $namaFileBaru = mitraPoInvoicePrefix($mitraRole) . $idMitra . $idpoproduk . uniqid() . '.' . $ekstensiGambar;
            move_uploaded_file($tmp, '../image/bukti_transfer/' . $namaFileBaru);
        } else {
            $_SESSION['message'] = 'Silakan upload bukti transfer.';
            header('Location: ' . $balikKeForm);
            exit;
        }

        $stmtBukti = $koneksi->prepare("INSERT INTO buktitf (id, invoice, gambar, jenis, ket, waktu) VALUES (NULL, ?, ?, ?, ?, ?)");
        $stmtBukti->bind_param('sssss', $invoice, $namaFileBaru, $jenis, $ket, $waktu);
        $stmtBukti->execute();

        $sql = false;

        $statusByJenis = [
            'dp'        => 'Sudah Confirm DP',
            'Pelunasan' => 'Sudah Confirm Pelunasan',
            'Payment1'  => 'Sudah Confirm Payment1',
            'Payment2'  => 'Sudah Confirm Payment2',
            'Payment3'  => 'Sudah Confirm Payment3',
            'Payment4'  => 'Sudah Confirm Payment4',
            'Payment5'  => 'Sudah Confirm Payment5',
        ];

        if ($jenis === 'Lunas') {
            $stmtLunasCek = $koneksi->prepare("SELECT jmlh_tambah, jmlh_lunas FROM popembayaran WHERE invoice = ?");
            $stmtLunasCek->bind_param('s', $invoice);
            $stmtLunasCek->execute();
            $tampiltf = $stmtLunasCek->get_result()->fetch_assoc();

            if (empty($tampiltf['jmlh_lunas'])) {
                $stmtLunas = $koneksi->prepare("UPDATE popembayaran SET
                                            jmlh_lunas = ?, bankpengirim = ?, rekeningpengirim = ?, metodebayar = ?, jenis = 'DP'
                                            WHERE invoice = ?");
                $stmtLunas->bind_param('sssss', $jmlhtransfer, $bankpengirim, $rekeningpengirim, $metodebayar, $invoice);
                $sql = $stmtLunas->execute();
            } else {
                $stmtLunas = $koneksi->prepare("UPDATE popembayaran SET
                                            jmlh_lunas = jmlh_lunas + ?, jmlh_tambah = ?, bankpengirim = ?, rekeningpengirim = ?, metodebayar = ?
                                            WHERE invoice = ?");
                $stmtLunas->bind_param('ssssss', $jmlhtransfer, $jmlhtransfer, $bankpengirim, $rekeningpengirim, $metodebayar, $invoice);
                $sql = $stmtLunas->execute();
            }

            $stmtStatus = $koneksi->prepare("UPDATE pomitra SET status = 'Sudah Confirm Pelunasan' WHERE invoice = ? AND $kolomRole = ?");
            $stmtStatus->bind_param('si', $invoice, $idMitra);
            $stmtStatus->execute();
        } elseif (isset($statusByJenis[$jenis])) {
            $stmtInsertPay = $koneksi->prepare("INSERT INTO popembayaran
                                    (idpembayaran, idpoproduk, invoice, bankpengirim, rekeningpengirim, jmlhtransfer, metodebayar, jenis, ket, tgl, waktu, termin_seq)
                                    VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtInsertPay->bind_param(
                'issssssssss',
                $idpoproduk, $invoice, $bankpengirim, $rekeningpengirim, $jmlhtransfer,
                $metodebayar, $jenis, $ket, $tgl, $waktu, $termin
            );
            $sql = $stmtInsertPay->execute();

            $statusValue = $statusByJenis[$jenis];
            $stmtStatus  = $koneksi->prepare("UPDATE pomitra SET status = ? WHERE invoice = ? AND $kolomRole = ?");
            $stmtStatus->bind_param('ssi', $statusValue, $invoice, $idMitra);
            $stmtStatus->execute();
        } else {
            $_SESSION['message'] = 'Jenis pembayaran tidak valid.';
            header('Location: preorder.php');
            exit;
        }

        $_SESSION['message'] = $sql
            ? 'Terima kasih, konfirmasi pembayaran sudah kami terima. Selanjutnya tunggu konfirmasi dari kami.'
            : 'Data gagal ditambahkan, silakan coba lagi.';
        header('Location: preorder.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Konfirmasi Pembayaran | WNJ.ID</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <style>
        body { background: var(--wnj-bg); }
        .form-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.25rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 560px;">
        <div class="d-flex align-items-center mt-3 mb-3" style="gap:.75rem;">
            <a href="preorder.php" class="text-muted"><i class="bi bi-arrow-left"></i></a>
            <h5 class="font-weight-bold mb-0">Konfirmasi Pembayaran</h5>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info"><?= htmlspecialchars($_SESSION['message']) ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="form-card">
            <p class="text-muted small mb-3">
                Nama Mitra: <?= htmlspecialchars($identitas['nama']) ?> (ID: <?= (int) $idMitra ?>)
            </p>

            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Invoice</label>
                    <select name="invoice" class="form-control" required>
                        <option value="<?= htmlspecialchars($invoice) ?>"><?= htmlspecialchars($invoice) ?> | Rp. <?= number_format($grandtotal) ?></option>
                    </select>
                </div>

                <?php if ($adaRekening === 0): ?>
                    <div class="form-group">
                        <label>Nama Bank Pengirim</label>
                        <input type="text" class="form-control" name="bankpengirim" required>
                    </div>
                    <div class="form-group">
                        <label>Nama / Nomor Rekening Pengirim</label>
                        <input type="text" class="form-control" name="rekeningpengirim" required>
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label>Nama Bank Pengirim</label>
                        <select name="bankpengirim" class="form-control">
                            <?php
                                $stmtRek = $koneksi->prepare("SELECT bank FROM rekeningku WHERE $kolomRekening = ?");
                                $stmtRek->bind_param('i', $idMitra);
                                $stmtRek->execute();
                                foreach ($stmtRek->get_result()->fetch_all(MYSQLI_ASSOC) as $rek):
                            ?>
                                <option value="<?= htmlspecialchars($rek['bank']) ?>"><?= htmlspecialchars($rek['bank']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama / Nomor Rekening Pengirim</label>
                        <select name="rekeningpengirim" class="form-control" required>
                            <?php
                                $stmtRek2 = $koneksi->prepare("SELECT namapemilik, rekening FROM rekeningku WHERE $kolomRekening = ?");
                                $stmtRek2->bind_param('i', $idMitra);
                                $stmtRek2->execute();
                                foreach ($stmtRek2->get_result()->fetch_all(MYSQLI_ASSOC) as $rek):
                            ?>
                                <option value="<?= htmlspecialchars($rek['namapemilik'] . ' ' . $rek['rekening']) ?>"><?= htmlspecialchars($rek['namapemilik'] . ' ' . $rek['rekening']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <hr>

                <div class="form-group">
                    <label>Jumlah Transfer</label>
                    <input type="number" min="0" value="<?= (int) $grandtotal ?>" class="form-control" name="jmlhtransfer" required readonly>
                </div>

                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select class="form-control" name="metodebayar" required>
                        <option value="">~ Pilih Rekening Pembayaran ~</option>
                        <?php foreach ($koneksi->query("SELECT namabank, norekening FROM rekeningwnj WHERE status = 'A'")->fetch_all(MYSQLI_ASSOC) as $rekening): ?>
                            <option><?= htmlspecialchars($rekening['namabank']) ?> <?= htmlspecialchars($rekening['norekening']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Bukti Transfer</label>
                    <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png" required>
                    <small class="text-muted">Upload bukti transfer/bukti saldo tampilan di web.</small>
                </div>

                <button type="submit" class="btn btn-primary btn-block" name="kirim">Kirim</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
