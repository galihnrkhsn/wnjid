<?php
    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';

    $idProvinsi = (int) ($_GET['provinsi_id'] ?? 0);

    echo '<option value="" disabled selected>~Pilih Kota/Kabupaten Tujuan~</option>';

    if ($idProvinsi > 0) {
        $stmt = $koneksi->prepare("SELECT city_id, city_name, postal_code FROM tb_ro_cities WHERE province_id = ? ORDER BY city_name ASC");
        $stmt->bind_param('i', $idProvinsi);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . (int) $row['city_id'] . '" data-kodepos="' . htmlspecialchars($row['postal_code'] ?? '') . '">'
                . htmlspecialchars($row['city_name']) . '</option>';
        }
    }
