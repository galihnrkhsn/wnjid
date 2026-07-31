<?php
    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';
    include '../includes/rajaongkir_helper.php';

    $idKonsumen     = $_SESSION['idkonsumen'];
    $invoice        = trim($_POST['invoice'] ?? '');
    $idKecamatan    = (int) ($_POST['kecamatan_id'] ?? 0);
    $kurir          = strtolower(trim($_POST['kurir'] ?? ''));

    // Berat diambil dari order milik konsumen ini sendiri (bukan dipercaya mentah dari client)
    $stmtOrder = $koneksi->prepare("SELECT berat FROM orderkonsumen WHERE invoice = ? AND idkonsumen = ?");
    $stmtOrder->bind_param('si', $invoice, $idKonsumen);
    $stmtOrder->execute();
    $order = $stmtOrder->get_result()->fetch_assoc();

    if (!$order || $idKecamatan <= 0 || $kurir === '') {
        echo '<option value="">Data tidak lengkap</option>';
        exit;
    }

    $layanan = cekOngkirKomerce($idKecamatan, (int) $order['berat'], $kurir);

    if (empty($layanan)) {
        echo '<option value="Manual|0">Layanan Ongkir Manual (dikonfirmasi admin)</option>';
        exit;
    }

    foreach ($layanan as $opsi) {
        $label = $opsi['service'] . ' - Rp' . number_format($opsi['cost']) . ' (estimasi ' . $opsi['etd'] . ')';
        echo '<option value="' . htmlspecialchars($opsi['service']) . '|' . (int) $opsi['cost'] . '">' . htmlspecialchars($label) . '</option>';
    }
