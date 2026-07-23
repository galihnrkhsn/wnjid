<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require '../vendor/autoload.php';
    require_once '../includes/db.php';
    header('Content-Type: application/json');

    use Endroid\QrCode\QrCode;
    use Endroid\QrCode\ErrorCorrectionLevel;

    try {
        $idpengiriman   = $_GET['idpengiriman'] ?? null;
        $mitra          = $_GET['mitra'] ?? null;
        $jenis          = $_GET['jenis'] ?? null;

        if ($idpengiriman !== null && $mitra === 'zizazu') {
            $response   = file_get_contents("https://zizazu.id/admin/API/get_pengiriman.php?idpengiriman=" . urlencode($idpengiriman) . "&mitra=" . urlencode($mitra) . "&jenis=" . urlencode((string) $jenis));
            $data       = $response !== false ? json_decode($response, true) : null;
            $invoice    = $data['invoice'] ?? null;

            $id = null;
            if ($invoice !== null) {
                $stmtTuser = $koneksi->prepare("SELECT id_user FROM t_user WHERE invoice = ?");
                $stmtTuser->bind_param('s', $invoice);
                $stmtTuser->execute();
                $dataTuser = $stmtTuser->get_result()->fetch_assoc();
                $id = $dataTuser['id_user'] ?? null;
            }
        } elseif ($idpengiriman !== null && $mitra === 'wnj') {
            $stmtTuser = $koneksi->prepare("SELECT id_user FROM orderpengiriman
                                                INNER JOIN t_user ON t_user.invoice = orderpengiriman.invoice
                                                WHERE orderpengiriman.idorderp = ?");
            $stmtTuser->bind_param('s', $idpengiriman);
            $stmtTuser->execute();
            $dataTuser = $stmtTuser->get_result()->fetch_assoc();
            $id = $dataTuser['id_user'] ?? null;
        } else {
            $id = $_GET['id'] ?? null;
        }

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
            exit;
        }

        $stmtUser = $koneksi->prepare("SELECT * FROM t_user WHERE id_user = ?");
        $stmtUser->bind_param('s', $id);
        $stmtUser->execute();
        $data = $stmtUser->get_result()->fetch_assoc();

        if (!$data) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan!']);
            exit;
        }

        $folder     = '../image/qr/';
        $filename   = $folder . $id . '.png';
        $scheme     = $_SERVER['REQUEST_SCHEME'] ?? 'https';
        $file_url   = $scheme . '://' . $_SERVER['HTTP_HOST'] . '/image/qr/' . $id . '.png';

        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        if (!file_exists($filename)) {
            $qrCode = new QrCode($id);
            $qrCode->setSize(250);
            $qrCode->setMargin(10);
            $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
            $qrCode->writeFile($filename);
        }

        echo json_encode([
            'status'            => 'success',
            'id'                => $id,
            'nama'              => $data['nama'],
            'namacs'            => $data['namacs'],
            'jenis_mitra'       => $data['jenis_mitra'],
            'ekspedisi'         => $data['ekspedisi'],
            'tlppengirim'       => $data['teleponpengirim'],
            'tlppenerima'       => $data['teleponpenerima'],
            'nama_penerima'     => $data['nama_penerima'],
            'alamat'            => $data['alamat'],
            'keterangan'        => $data['keterangan'],
            'qr_url'            => $file_url
        ]);
    } catch (\Throwable $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Gagal membuat QR Code: ' . $e->getMessage()]);
    }
?>