<?php
    require_once '../adminwnj/koneksi.php';
    header('Content-Type: application/json');


    $draw       = (int) ($_POST['draw'] ?? 0);
    $start      = (int) ($_POST['start'] ?? 0);
    $length     = (int) ($_POST['length'] ?? 10);
    $search     = trim($_POST['search']['value'] ?? '');

    $totalQuery = $koneksi->query("SELECT COUNT(*) as total FROM admin_mitra");
    $totalData  = $totalQuery->fetch_assoc()['total'];

    $baseSql    = "SELECT
                        am.idadmin,
                        am.namamitra,
                        am.email,
                        am.alamat,
                        am.whatsapp,
                        am.privateorder,
                        tc.city_name,
                        amcs.namacs,
                        am.status
                    FROM admin_mitra AS am
                    LEFT JOIN tb_ro_cities tc ON am.kota = tc.city_id
                    LEFT JOIN admin_mitra_cs amcs ON amcs.idadmin = am.idadmin";

    $whereSql   = '';
    $params     = [];
    $types      = '';

    if ($search !== '') {
        $whereSql = " WHERE am.namamitra LIKE ? OR
                        am.email LIKE ? OR
                        am.alamat LIKE ? OR
                        tc.city_name LIKE ? OR
                        amcs.namacs LIKE ?";
        $like     = '%' . $search . '%';
        $params   = [$like, $like, $like, $like, $like];
        $types    = 'sssss';
    }

    $stmtFiltered = $koneksi->prepare($baseSql . $whereSql);
    if ($types !== '') {
        $stmtFiltered->bind_param($types, ...$params);
    }
    $stmtFiltered->execute();
    $filteredData = $stmtFiltered->get_result()->num_rows;

    $stmtData   = $koneksi->prepare($baseSql . $whereSql . " ORDER BY am.idadmin DESC LIMIT ?, ?");
    $dataParams = array_merge($params, [$start, $length]);
    $stmtData->bind_param($types . 'ii', ...$dataParams);
    $stmtData->execute();
    $dataQuery  = $stmtData->get_result();

    $data           = [];
    while($row = $dataQuery->fetch_assoc()) {
        $data[]     = [
            'idadmin'       => $row['idadmin'],
            'status'        => $row['status'],
            'namacs'        => $row['namacs'],
            'namamitra'     => $row['namamitra'],
            'email'         => $row['email'],
            'alamat'        => $row['alamat'],
            'whatsapp'      => $row['whatsapp'],
            'city_name'     => $row['city_name'],
            'privateorder'  => $row['privateorder']
        ];
    }

    $response = [
        "draw"              => intval($draw),
        "recordsTotal"      => intval($totalData),
        "recordsFiltered"   => intval($filteredData),
        "data"              => $data
    ];
    echo json_encode($response);
?>