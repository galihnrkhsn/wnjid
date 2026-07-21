<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header('Content-Type: application/json');

    require_once '../../includes/db.php';

    if (isset($_GET['id'])) {

        $id = intval($_GET['id']);
        $koneksi->query("DELETE FROM variants WHERE id = $id");

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'ID not Found!']);
    }
?>