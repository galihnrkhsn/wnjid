<?php
    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';

    $idKota = (int) ($_GET['kota_id'] ?? 0);

    echo '<option value="" disabled selected>~Pilih Kecamatan Tujuan~</option>';

    if ($idKota > 0) {
        $stmt = $koneksi->prepare("SELECT subdistrict_id, subdistrict_name FROM tb_ro_subdistricts WHERE city_id = ? ORDER BY subdistrict_name ASC");
        $stmt->bind_param('i', $idKota);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . (int) $row['subdistrict_id'] . '">' . htmlspecialchars($row['subdistrict_name']) . '</option>';
        }
    }
