<?php
    require '../portal/vendor/autoload.php';
    require_once '../adminwnj/koneksi.php';
    header('Content-Type: application/json');

    use Endroid\QrCode\QrCode;
    use Endroid\QrCode\ErrorCorrectionLevel;
    $idpengiriman   = $_GET['idpengiriman'];
    $mitra          = $_GET['mitra'];
    $jenis          = $_GET['jenis'];
    
    if (isset($idpengiriman) && $mitra == 'zizazu') {
        $idpengiriman   = $_GET['idpengiriman'] ?? NULL;
        $mitra          = $_GET['mitra'];
        $jenis          = $_GET['jenis'];

        $response       = file_get_contents("https://zizazu.id/admin/API/get_pengiriman.php?idpengiriman=$idpengiriman&mitra=$mitra&jenis=$jenis");
        $data           = json_decode($response, true);

        $invoice        = $data['invoice'];

        $getTuser       = $koneksi->query("SELECT id_user FROM t_user  
                                            WHERE invoice = '$invoice'
                                        ");
        $dataTuser      = $getTuser->fetch_assoc();

        $id             = $dataTuser['id_user'];
    } elseif (isset($_GET['idpengiriman']) && $_GET['mitra'] == 'wnj') {
        $idpengiriman   = $_GET['idpengiriman'] ?? null;
        $getTuser       = $koneksi->query("SELECT id_user FROM orderpengiriman 
                                            INNER JOIN t_user ON t_user.invoice = orderpengiriman.invoice 
                                            WHERE orderpengiriman.idorderp = '$idpengiriman'
                                        ");
        $dataTuser      = $getTuser->fetch_assoc();

        $id             = $dataTuser['id_user'];
    } else {
        $id             = $_GET['id'] ?? null;
    }
    
    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
        exit;
    }

    $sql        = $koneksi->query("SELECT * FROM t_user WHERE id_user = '$id'");
    $data       = $sql->fetch_assoc();

    if (!$data) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan!']);
    }

    $folder     = '../portal/img/qr/';
    $filename   = $folder . $id . '.png';
    $file_url   = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/portal/img/qr/' . $id . '.png';

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }
    
    try {
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
    } catch (Exception $e) {
        echo "Gagal membuat QR Code: " . $e->getMessage();
    }
?>