<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include '../koneksi.php';

    $data = json_decode(file_get_contents("php://input"), true);

    if (!is_array($data)) {
        die("Data tidak valid");
    }

    foreach ($data as $city) {
        $city_id = intval($city['city_id']);
        if (isset($city['subdistricts']) && is_array($city['subdistricts'])) {
            foreach ($city['subdistricts'] as $subdistrict) {
                $subdistrict_id = intval($subdistrict['id']);
                $subdistrict_name = mysqli_real_escape_string($koneksi, $subdistrict['name']);

                $sql = "INSERT INTO tb_ro_subdistricts (subdistrict_id, city_id, subdistrict_name)
                        VALUES ($subdistrict_id, $city_id, '$subdistrict_name')";

                mysqli_query($koneksi, $sql);
            }
        }
    }

    echo json_encode(["status" => "success"]);
?>