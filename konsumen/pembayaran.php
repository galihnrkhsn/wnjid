<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';
    include '../includes/image_upload_helper.php';

    $idKonsumen = $_SESSION['idkonsumen'];
    $invoice    = trim($_GET['invoice'] ?? ($_POST['invoice'] ?? ''));
    $errors     = [];

    $stmtOrder = $koneksi->prepare("SELECT * FROM orderkonsumen WHERE invoice = ? AND idkonsumen = ?");
    $stmtOrder->bind_param('si', $invoice, $idKonsumen);
    $stmtOrder->execute();
    $order = $stmtOrder->get_result()->fetch_assoc();

    if (!$order) {
        header('Location: view_cart.php');
        exit;
    }

    if ($order['status'] !== 'Menunggu Pembayaran') {
        header('Location: detail.php?invoice=' . urlencode($invoice));
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $bank     = trim($_POST['bank_pengirim'] ?? '');
        $rekening = trim($_POST['rekening_pengirim'] ?? '');

        if ($bank === '') {
            $errors[] = 'Nama bank pengirim wajib diisi';
        }
        if ($rekening === '') {
            $errors[] = 'Nomor rekening/atas nama pengirim wajib diisi';
        }

        $fotoNama      = $_FILES['foto']['name'] ?? '';
        $fotoTmp       = $_FILES['foto']['tmp_name'] ?? '';
        $fotoSize      = $_FILES['foto']['size'] ?? 0;
        $ekstensiValid = ['jpg', 'jpeg', 'png'];
        $ekstensi      = strtolower(pathinfo($fotoNama, PATHINFO_EXTENSION));

        if (empty($fotoNama)) {
            $errors[] = 'Bukti transfer wajib diupload';
        } elseif (!in_array($ekstensi, $ekstensiValid)) {
            $errors[] = 'Ekstensi bukti transfer tidak valid (jpg/jpeg/png)';
        } elseif ($fotoSize > 2000000) {
            $errors[] = 'Ukuran bukti transfer terlalu besar (maks 2MB)';
        }

        if (empty($errors)) {
            $namaFileBaru = convertUploadedImageToWebp($fotoTmp, $ekstensi, '../image/bukti_transfer/', 'K' . uniqid());

            if ($namaFileBaru) {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                $koneksi->begin_transaction();
                try {
                    $stmtBayar = $koneksi->prepare("INSERT INTO orderkonsumen_pembayaran
                                                        (idorder, bank_pengirim, rekening_pengirim, jumlah_transfer, foto)
                                                        VALUES (?, ?, ?, ?, ?)");
                    $stmtBayar->bind_param('issds', $order['idorder'], $bank, $rekening, $order['total'], $namaFileBaru);
                    $stmtBayar->execute();

                    $stmtStatus = $koneksi->prepare("UPDATE orderkonsumen
                                                        SET status = 'Menunggu Konfirmasi Admin', payment_status = 'Sudah Konfirmasi'
                                                        WHERE idorder = ?");
                    $stmtStatus->bind_param('i', $order['idorder']);
                    $stmtStatus->execute();

                    $koneksi->commit();

                    $_SESSION['message'] = 'Bukti pembayaran terkirim, menunggu konfirmasi admin.';
                    header('Location: detail.php?invoice=' . urlencode($invoice));
                    exit;
                } catch (Exception $e) {
                    $koneksi->rollback();
                    error_log($e->getMessage());
                    $errors[] = 'Gagal menyimpan pembayaran, silakan coba lagi';
                }
            } else {
                $errors[] = 'Gagal memproses file bukti transfer';
            }
        }
    }

    $rekeningList = $koneksi->query("SELECT namabank, norekening FROM rekeningwnj WHERE status = 'A' ORDER BY idrekening ASC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pembayaran | Wanoja</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f5f6fa; }
        .panel {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.5rem;
            max-width: 640px;
            margin: 1.5rem auto;
        }
        .rekening-item {
            display: flex;
            justify-content: space-between;
            padding: .5rem 0;
            border-bottom: 1px solid #eef1f5;
        }
        .rekening-item:last-child {
            border-bottom: none;
        }
        .total-box {
            background: #f0f6ff;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1rem;
        }
        .step-badge {
            font-size: .75rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="panel">
            <div class="step-badge mb-2">Langkah 3 dari 3 &middot; Invoice <?= htmlspecialchars($invoice) ?></div>
            <h5 class="font-weight-bold mb-3">Pembayaran</h5>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0 pl-3">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="total-box">
                <div class="text-muted small">Total yang harus dibayar</div>
                <div class="h4 font-weight-bold mb-0">Rp <?= number_format($order['total']) ?></div>
            </div>

            <h6 class="font-weight-bold">Transfer ke salah satu rekening berikut:</h6>
            <?php foreach ($rekeningList as $rek): ?>
                <div class="rekening-item">
                    <span><?= htmlspecialchars($rek['namabank']) ?></span>
                    <span class="font-weight-bold"><?= htmlspecialchars(trim($rek['norekening'])) ?></span>
                </div>
            <?php endforeach; ?>

            <form method="post" enctype="multipart/form-data" class="mt-4" autocomplete="off">
                <div class="form-group">
                    <label class="mb-1">Bank Pengirim</label>
                    <input type="text" class="form-control" name="bank_pengirim" placeholder="Contoh: BCA" required>
                </div>
                <div class="form-group">
                    <label class="mb-1">No. Rekening / Atas Nama Pengirim</label>
                    <input type="text" class="form-control" name="rekening_pengirim" required>
                </div>
                <div class="form-group">
                    <label class="mb-1">Bukti Transfer</label>
                    <input type="file" class="form-control-file" name="foto" accept="image/jpeg,image/png" required>
                    <small class="text-muted">Format jpg/jpeg/png, maksimal 2MB.</small>
                </div>
                <button type="submit" class="btn btn-primary btn-block mt-3">
                    <i class="bi bi-upload"></i> Kirim Bukti Pembayaran
                </button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
