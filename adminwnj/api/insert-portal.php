<?php
    header("Access-Control-Allow-Origin: *"); // Boleh disesuaikan origin-nya
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: POST");

    include '../../includes/db.php';
    $requiredFields = ['idorderp', 'namacs', 'jumlah_koli', 'today', 'penerima', 'ekspedisi', 'biayakirim', 'nama_pengirim', 'telepon_pengirim', 'alamat', 'idadmin', 'namamitra', 'jenis_mitra'];

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field])) {
            echo "missing_field_$field";
            exit;
        }
    }

    $idorderp           = $_POST['idorderp'];
    $namacs             = $_POST['namacs'];
    $jumlah_koli        = $_POST['jumlah_koli'];
    $today              = $_POST['today'];
    $penerima           = $_POST['penerima'];
    $ekspedisi          = $_POST['ekspedisi'];
    $biayakirim         = $_POST['biayakirim'];
    $nama_pengirim      = $_POST['nama_pengirim'];
    $telepon_pengirim   = $_POST['telepon_pengirim'];
    $telepon_penerima   = $_POST['telepon_penerima'];
    $alamat             = $_POST['alamat'];
    $provinsi           = $_POST['provinsi'];
    $kota               = $_POST['kota'];
    $kecamatan          = $_POST['kecamatan'];
    $idadmin            = $_POST['idadmin'];
    $namamitra          = $_POST['namamitra'];
    $jenis_mitra        = $_POST['jenis_mitra'];
    $invoice            = $_POST['invoice'];

    // Opsional data
    $idmitraagen        = $_POST['idmitraagen'] ?? null;
    $idmitrareseller    = $_POST['idmitrareseller'] ?? null;
    $idmitramarketer    = $_POST['idmitramarketer'] ?? null;

    try {
        // INSERT ke logistik3
        $insertLogistik = $koneksi->query("INSERT INTO logistik3 
                                                (
                                                    `idlogistik`, `tgl`,`penerima`,`ekspedisi`,`noresi`,
                                                    `biayakirim`,`jumlah_koli`,`keterangan`,`status`,
                                                    `idadmin`,`idmitraagen`,`idmitrareseller`,`idmitramarketer`,
                                                    `namacs`,`no_sj`,`jenis_mitra`, `jenis_pengiriman`, 
                                                    `status_pengiriman`, `detail_pengiriman`
                                                ) 
                                            VALUES 
                                                (
                                                    NULL, '$today', '$penerima', '$ekspedisi', '', '$biayakirim',
                                                    '$jumlah_koli', '$invoice', NULL, '$idadmin', '$idmitraagen', '$idmitrareseller', '$idmitramarketer', '$namacs', 
                                                    NULL, '$jenis_mitra', 'ReadyStok', NULL, NULL
                                                )
                                        ");

        if (!$insertLogistik) {
            throw new Exception("Insert logistik3 gagal: " . $koneksi->error);
        }

        $idlogistik = $koneksi->insert_id;

        // INSERT ke t_user
        $queryTuser = $koneksi->query("INSERT INTO t_user 
                                                        (`id_user`,`idlogistik`,`namacs`,`nama`,`teleponpengirim`,`nama_penerima`,
                                                        `teleponpenerima`,`alamat`,`keterangan`,`ekspedisi`,`invoice`,`status`,
                                                        `created_date`,`modified_date`,`resi_pengiriman`,`ongkir`,`pcs`,
                                                        `marketplace`,`namamitra`,`idadmin`,`no_sj`,`jenis_mitra`) 
                                                    VALUES
                                                        (NULL, '$idlogistik', '$namacs',
                                                        '$nama_pengirim', '$telepon_pengirim', '$penerima',
                                                        '$telepon_penerima', '$alamat, $provinsi, $kota, $kecamatan', '$invoice',
                                                        '$ekspedisi', '$invoice', NULL,
                                                        '$today', NOW(), NULL, '$biayakirim', NULL,
                                                        NULL, '$namamitra', '$idadmin', NULL, '$jenis_mitra')
                                                 ");

        if (!$queryTuser) {
            throw new Exception("Insert t_user gagal: " . $koneksi->error);
        }

        echo "success";
        flush();
    } catch (Exception $e) {
        http_response_code(500);
        echo "Gagal insert data: " . $e->getMessage();
        flush();
    }

    file_put_contents('log-portal.txt', print_r($_POST, true) . "\n\n", FILE_APPEND);
?>