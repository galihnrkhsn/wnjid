<?php
    include 'koneksi.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idTuser        = $_POST['idlogistik'];

        $getLogistik    = $koneksi->query("SELECT idlogistik FROM t_user WHERE id_user = '$idTuser'");
        $dataLogistik   = $getLogistik->fetch_assoc();
        $idLogistik     = $dataLogistik['idlogistik'];

        if ($idLogistik->num_rows === 0) {
            echo "QR tidak ditemukan: $idTuser";
            exit;
        }
    }

    $updateStatus = "UPDATE logistik3 SET status = 'Terkirim' WHERE idlogistik = '$idLogistik'";

    if ($koneksi->query($updateStatus)) {
        echo "ID $idTuser berhasil diupdate.";
    } else {
        echo "Gagal Update: " . $koneksi->error;
    }
?>