<?php
    header('Content-Type: application/json');
    $jenis_mitra = $_GET['jenis_mitra'];

    if ($jenis_mitra === 'WNJ') {
        include 'koneksi.php';
        $query = $koneksi->query("SELECT idpoproduk, namapo FROM poproduk ORDER BY idpoproduk DESC");
        $result = [];
        while ($row = $query->fetch_assoc()) {
            $result[] = [
                'idpoproduk' => $row['idpoproduk'],
                'namapo'     => $row['namapo']
            ];
        }
        echo json_encode($result);
    } elseif ($jenis_mitra === 'Zizazu') {
        $apiUrl = 'https://zizazu.id/admin/API/get-poproduk.php?token=wnjzizazu218!';
        $response = file_get_contents($apiUrl);
        echo $response ?: json_encode([]);
    } else {
        echo json_encode([]);
    }
?>
