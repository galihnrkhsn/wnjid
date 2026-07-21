<?php
    include '../../includes/db.php';

    $idpo = $_GET['idpo'];
    $result = [];

    if ($idpo) {
        $sql = $koneksi->query("SELECT podetail.idpodetail, podetail.variant 
                                FROM podetail
                                INNER JOIN pokategori ON pokategori.idpo = podetail.idpo
                                WHERE podetail.idpo = '$idpo'
                                AND podetail.variant LIKE '%Tunik%'
                              ");
        while ($row = $sql->fetch_assoc()) {
            $result[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($result);
?>