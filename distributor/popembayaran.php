<?php
    error_reporting(0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $grandtotal = $_GET["total"]    ?? 0;
    $invoice    = $_GET["invoice"]  ?? '';
    $idadmin    = $_SESSION["idadmin"];

    // invoice hanya boleh alfanumerik supaya query di bawah (yang masih menyisipkan
    // $invoice langsung ke string SQL) tidak bisa disalahgunakan untuk SQL injection
    if ($invoice === '') {
        header('Location: listnewpo.php');
        exit;
    }

    // Pastikan invoice ini benar-benar milik mitra yang sedang login
    $stmtOwn = $koneksi->prepare("SELECT COUNT(*) AS jumlah FROM pomitra WHERE invoice = ? AND idmitra = ?");
    $stmtOwn->bind_param('ss', $invoice, $idadmin);
    $stmtOwn->execute();
    $ownCheck = $stmtOwn->get_result()->fetch_assoc();
    if (($ownCheck['jumlah'] ?? 0) == 0) {
        header('Location: listnewpo.php');
        exit;
    }

    $stmtBank = $koneksi->prepare("SELECT count(*) as bank FROM rekeningku WHERE idadmin = ?");
    $stmtBank->bind_param('s', $idadmin);
    $stmtBank->execute();
    $bank = $stmtBank->get_result()->fetch_assoc();

    $stmtUser = $koneksi->prepare("SELECT * FROM admin_mitra WHERE idadmin = ?");
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

    <title>Distributor | Wanoja</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <h4 align="center">
        Nama Mitra  : <?= htmlspecialchars($queryUser["namamitra"] ?? '') ?> (Cust ID : <?= htmlspecialchars($idadmin) ?> )
        </h4>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">

                    <div class="container">
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Invoice</label>
                                <select name="invoice" class="form-control" required>
                                    <option value="<?= htmlspecialchars($invoice) ?>"><?= htmlspecialchars($invoice) ?> | Rp. <?= number_format($grandtotal) ?></option>
                                </select>
                            </div>

                            <?php if (($bank['bank'] ?? 0) == 0): ?>
                                <div class='form-group'>
                                    <label>Nama Bank Pengirim</label>
                                    <input type='text' class='form-control' name='bankpengirim' required>
                                </div>
                            <?php else: ?>
                                <div class='form-group'>
                                    <label>Nama Bank Pengirim</label>
                                    <select name='bankpengirim' class='form-control'>
                                        <?php
                                            $stmtRek = $koneksi->prepare("SELECT * FROM rekeningku WHERE idadmin = ?");
                                            $stmtRek->bind_param('s', $idadmin);
                                            $stmtRek->execute();
                                            $ambil = $stmtRek->get_result();
                                            while ($distributor = $ambil->fetch_assoc()):
                                        ?>
                                        <option value="<?= htmlspecialchars($distributor['bank']) ?>"><?= htmlspecialchars($distributor['bank']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <?php if (($bank['bank'] ?? 0) == 0): ?>
                                <div class='form-group'>
                                    <label>Nama / Nomor Rekening Pengirim</label>
                                    <input type='text' class='form-control' name='rekeningpengirim' required>
                                </div>
                            <?php else: ?>
                                <div class='form-group'>
                                    <label>Nama / Nomor Rekening Pengirim</label>
                                    <select name='rekeningpengirim' class='form-control' required>
                                        <?php
                                            $stmtRek2 = $koneksi->prepare("SELECT * FROM rekeningku WHERE idadmin = ?");
                                            $stmtRek2->bind_param('s', $idadmin);
                                            $stmtRek2->execute();
                                            $ambil = $stmtRek2->get_result();
                                            while ($distributor = $ambil->fetch_assoc()):
                                        ?>
                                        <option value="<?= htmlspecialchars($distributor['namapemilik'] . ' ' . $distributor['rekening']) ?>"><?= htmlspecialchars($distributor['namapemilik'] . ' ' . $distributor['rekening']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <hr>

                            <div class="form-group">
                                <label>Jumlah Transfer</label>
                                <input type="number" min="0" value='<?= (int) $grandtotal ?>' class="form-control" name="jmlhtransfer" required readonly>
                            </div>

                            <div class="form-group">
                                <label>Metode Pembayaran</label>
                                <select class="form-control" name="metodebayar" required>
                                    <option value="">~Pilih Rekening Pembayaran~</option>
                                    <?php
                                        $ambil_rek = $koneksi->query("SELECT * FROM rekeningwnj WHERE status = 'A'");
                                        while ($rekening = $ambil_rek->fetch_assoc()):
                                    ?>
                                        <option><?= htmlspecialchars($rekening['namabank']) ?> <?= htmlspecialchars($rekening['norekening']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Bukti Transfer</label>
                                <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png" required>
                                <span>Silahkan upload bukti transfer/bukti saldo tampilan di web</span>
                            </div>
                            <center><button type="submit" class="btn btn-primary" name="kirim">Kirim</button></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->

    <br><br><br><br>

    <!-- FOOTER -->
    <?php // include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP SYNTAK -->
    <?php
        if (isset($_POST['kirim'])) {
            date_default_timezone_set('Asia/Jakarta');
            $tgl      = date('Y-m-d');
            $waktu    = date('H:i:s');

            $bankpengirim     = $_POST["bankpengirim"]     ?? '';
            $rekeningpengirim = $_POST["rekeningpengirim"] ?? '';
            $jmlhtransfer     = $_POST["jmlhtransfer"]     ?? 0;
            $metodebayar      = $_POST["metodebayar"]      ?? '';
            $bayar            = $_GET["bayar"]             ?? 0;
            $idpoproduk       = isset($_GET["idpo"]) ? (int) $_GET["idpo"] : 0;
            $jenis            = $_GET["jenis"]             ?? '';
            $termin           = $_GET['termin']            ?? null;

            $ket = $bankpengirim . '-' . $rekeningpengirim . '-' . $metodebayar . '-' . $jmlhtransfer;

            $namaFileBaru = null;
            if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $foto = $_FILES['foto']['name'];
                $tmp  = $_FILES['foto']['tmp_name'];

                $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
                $ekstensiGambar      = strtolower(pathinfo($foto, PATHINFO_EXTENSION));

                if (!in_array($ekstensiGambar, $ekstensiGambarValid, true)) {
                    echo "<script>alert('Yang anda upload bukan gambar');</script>";
                    echo "<script>location='popembayaran.php?invoice=" . rawurlencode($invoice) . "&total=" . rawurlencode($grandtotal) . "&bayar=" . rawurlencode($bayar) . "&idpo=" . $idpoproduk . "&jenis=" . rawurlencode($jenis) . "';</script>";
                    exit;
                }

                $namaFileBaru = 'D' . $idadmin . $idpoproduk . uniqid() . '.' . $ekstensiGambar;
                move_uploaded_file($tmp, '../image/bukti_transfer/' . $namaFileBaru);
            } else {
                echo "<script>alert('Silahkan upload bukti transfer');</script>";
                echo "<script>location='popembayaran.php?invoice=" . rawurlencode($invoice) . "&total=" . rawurlencode($grandtotal) . "&bayar=" . rawurlencode($bayar) . "&idpo=" . $idpoproduk . "&jenis=" . rawurlencode($jenis) . "';</script>";
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
                                                jmlh_lunas = ?,
                                                bankpengirim = ?,
                                                rekeningpengirim = ?,
                                                metodebayar = ?,
                                                jenis = 'DP'
                                                WHERE invoice = ?");
                    $stmtLunas->bind_param('sssss', $jmlhtransfer, $bankpengirim, $rekeningpengirim, $metodebayar, $invoice);
                    $sql = $stmtLunas->execute();
                } else {
                    $stmtLunas = $koneksi->prepare("UPDATE popembayaran SET
                                                jmlh_lunas = jmlh_lunas + ?,
                                                jmlh_tambah = ?,
                                                bankpengirim = ?,
                                                rekeningpengirim = ?,
                                                metodebayar = ?
                                                WHERE invoice = ?");
                    $stmtLunas->bind_param('ssssss', $jmlhtransfer, $jmlhtransfer, $bankpengirim, $rekeningpengirim, $metodebayar, $invoice);
                    $sql = $stmtLunas->execute();
                }

                $stmtStatus = $koneksi->prepare("UPDATE pomitra SET status = 'Sudah Confirm Pelunasan' WHERE invoice = ? AND idmitra = ?");
                $stmtStatus->bind_param('ss', $invoice, $idadmin);
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
                $stmtStatus  = $koneksi->prepare("UPDATE pomitra SET status = ? WHERE invoice = ? AND idmitra = ?");
                $stmtStatus->bind_param('sss', $statusValue, $invoice, $idadmin);
                $stmtStatus->execute();
            } else {
                $_SESSION['message'] = 'Jenis pembayaran tidak valid';
                header('Location: listnewpo.php');
                exit;
            }

            if ($sql) {
                echo "
                    <script>alert('Terima kasih... Konfirmasi pembayaran sudah kami terima, selanjutnya tunggu konfirmasi dari kami!');</script>
                    <script>location='listnewpo';</script>
                ";
            } else {
                echo "
                    <script>alert('Data gagal ditambahkan!');</script>
                    <script>location='listnewpo';</script>
                ";
            }
        }
    ?>
    <!-- PHP SYNTAK END -->

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>
