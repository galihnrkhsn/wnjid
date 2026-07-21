<?php
    require_once '../adminwnj/koneksi.php';
    header('Content-Type: application/json');


    $draw       = $_POST['draw'] ?? 0;
    $start      = $_POST['start'] ?? 0;
    $length     = $_POST['length'] ?? 10;
    $search     = $_POST['search']['value'] ?? '';

    $totalQuery = $koneksi->query("SELECT COUNT(*) as total FROM admin_mitra");
    $totalData  = $totalQuery->fetch_assoc()['total'];

    $sql        = "SELECT   
                        am.idadmin,
                        am.namamitra,
                        am.email,
                        am.alamat,
                        am.whatsapp,
                        tc.city_name,
                        amcs.namacs,
                        am.status
                    FROM admin_mitra AS am
                    LEFT JOIN tb_ro_cities tc ON am.kota = tc.city_id
                    LEFT JOIN admin_mitra_cs amcs ON amcs.idadmin = am.idadmin
                    WHERE 1 ";

    if (!empty($search)) {
        $sql    .= " AND (am.namamitra LIKE '%$search%' OR
                        am.email LIKE '%$search%' OR
                        am.alamat LIKE '%$search%' OR
                        tc.city_name LIKE '%$search%' OR
                        amcs.namacs LIKE '%$search%'
                        )
                    ";
    }

    $filteredQuery  = $koneksi->query($sql);
    $filteredData   = $filteredQuery->num_rows;

    $sql            .= " ORDER BY am.idadmin DESC limit $start, $length";
    $dataQuery      = $koneksi->query($sql);

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