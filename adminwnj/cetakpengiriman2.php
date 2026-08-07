<body onload="window.print()">
<?php
    
    include "koneksi.php";

    $idpengiriman   = $_GET['idpengiriman'];
    $mitra          = $_GET['mitra'];
    $jenis          = $_GET['jenis'] ?? NULL;

    $response       = file_get_contents("https://wnj.id/api/generate_qr_api.php?idpengiriman=$idpengiriman&mitra=$mitra&jenis=$jenis");

    if ($response === false) {
        echo "Gagal mengakses API QR Code.";
        exit;
}
    $data       = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print Surat Jalan WNJ.ID</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2rem;
            color: #000;
        }
        .warning {
            text-align: center;
            color: red;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .section {
            margin-bottom: 1.5rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        td, th {
            padding: 0.5rem;
            border: 1px solid #000;
        }
        h4 {
            margin: 0.25rem 0;
        }
        p {
            margin: 0.125rem 0;
        }
        .divider {
            border-bottom: 1px dashed #000;
            margin: 1.5rem 0;
        }
        .flex-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start; /* atau center jika ingin sejajar vertikal */
            margin-top: 1rem;
        }

        .left-info {
            flex: 1;
        }

        .qr-container {
            text-align: center;
        }

        .qr-container img {
            border: 1px solid #000;
            padding: 5px;
            width: 100px;
        }
        table.columns {
            width: 100%;
            table-layout: fixed;
        }
        table.columns td {
            width: 33.33%; /* 100% / jumlah kolom */
        }
        .text-center {
            text-align: center;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="warning">
        <p>Jika Pesanan Sudah Sampai, Segera Cek Barang Sesuai Struk</p>
        <p style="font-size: 1.25rem;">Kami Tidak Menerima Komplain Tanpa Foto/Video Lebih dari 1x24 Jam</p>
    </div>

    <div class="divider"></div>

    <div class="flex-container">
        <div class="section" align="center">
            <?php if (htmlspecialchars($data['jenis_mitra']) === "WNJ") : ?>
                <img src="../image/wanoja.png" width="100">
            <?php else : ?>
                <img src="../image/zizazu.png" width="100">
            <?php endif; ?>
        </div>
        <div class="qr-container">
            <img src="<?= $data['qr_url'] ?>" alt="QR Code Pengiriman">
            <p><small><?= $data['id'] ?></small></p>
        </div>
    </div>

    <table class="columns text-center">
        <tr>
            <td><strong>CSO:</strong> <?= htmlspecialchars($data['namacs']) ?></td>
            <td><strong>Ekspedisi:</strong> <?= htmlspecialchars($data['ekspedisi']) ?></td>
            <td></td>
        </tr>
    </table>

    <table class="text-center">
        <tr>
            <td>
                <h4>Pengirim:</h4>
                <p><?= htmlspecialchars(html_entity_decode($data['nama'])) ?></p>
                <p><?= htmlspecialchars($data['tlppengirim']) ?></p>
            </td>
        </tr>
    </table>

    <table class="text-center">
        <tr>
            <td>
                <h4>Penerima:</h4>
                <p><?= htmlspecialchars(html_entity_decode($data['nama_penerima'])) ?></p>
                <p><?= htmlspecialchars($data['tlppenerima']) ?></p>
                <p><?= htmlspecialchars($data['alamat']) ?></p>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <p><strong>Note:</strong> <?= htmlspecialchars($data['keterangan']) ?></p>
    
    <div class="left-info">
        <p><strong>Kode Mitra:</strong> <?= htmlspecialchars($data['idadmin'] ?? '') ?></p>
        <p><strong>Tanggal:</strong> <?= date('d-m-Y') ?></p>
    </div>
</body>
</html>