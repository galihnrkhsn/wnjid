<?php
    header("Content-Type: application/json");
    include '../../includes/db.php';
    $idadmin = $_POST['idadmin'] ?? NULL;

    if (!$idadmin) {
        echo json_encode(['status' => 'error', 'message' => 'ID Admin tidak ada!']);
        exit;
    }

    $query  = $koneksi->query("SELECT * FROM logistik3 WHERE idadmin = '$idadmin' AND jenis_mitra = 'Zizazu' AND tgl > '2024-12-12' ORDER BY idlogistik DESC LIMIT 500");
    $data   = [];

    while ($row = $query->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $data]);
?>