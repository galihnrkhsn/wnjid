<?php
    header("Access-Control-Allow-Origin: *"); // Boleh disesuaikan origin-nya
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: POST");
    header('Content-Type: application/json');


    include '../../includes/db.php';

    $tanggal            = $_POST['tanggal'];
    $invoice            = $_POST['invoice'];
    $penerima           = $_POST['penerima'];
    $keterangan         = $_POST['keterangan'];
    $namacs             = $_POST['namacs'];
    $ekspedisi          = $_POST['ekspedisi'];
    $pengirim           = $_POST['pengirim'];
    $telp_pengirim      = $_POST['telp_pengirim'];
    $telp_penerima      = $_POST['telp_penerima'];
    $alm_penerima       = $_POST['alm_penerima'];
    $idmitra            = $_POST['idmitra'];
    $idadmin_mitra      = $_POST['idadmin_mitra'];
    $jenis_mitra        = $_POST['jenis_mitra'];

    // Opsional data
    $idmitraagen        = $_POST['idmitraagen'] ?? null;
    $idmitrareseller    = $_POST['idmitrareseller'] ?? null;
    $idmitramarketer    = $_POST['idmitramarketer'] ?? null;

    try {
        $ins_log = $koneksi->query("INSERT INTO logistik3 
                                                                (
                                                                    `idlogistik`,
                                                                    `tgl`, `penerima`, `ekspedisi`, `noresi`, `biayakirim`, `jumlah_koli`,
                                                                    `keterangan`, `status`, `idadmin`, `idmitraagen`, `idmitrareseller`, `idmitramarketer`,
                                                                    `namacs`, `no_sj`, `jenis_mitra`, `jenis_pengiriman`, `status_pengiriman`,
                                                                    `detail_pengiriman`
                                                                )
                                                            VALUES 
                                                                (
                                                                    NULL, '$tanggal', '$penerima', '$ekspedisi', NULL, 0, 0,
                                                                    '$keterangan', NULL, '$idadmin_mitra', 
                                                                    " . ($idmitra == $idmitraagen ? "'$idmitra'" : "NULL") . ",
                                                                    " . ($idmitra == $idmitrareseller ? "'$idmitra'" : "NULL") . ",
                                                                    " . ($idmitra == $idmitramarketer ? "'$idmitra'" : "NULL") . ",
                                                                    '$namacs', NULL, '$jenis_mitra', 'PO', NULL, NULL
                                                                )
                                        ");

        if (!$ins_log) {
            throw new Exception("Insert logistik3 gagal: " . $koneksi->error);
        }

        $idlogistik_baru    = mysqli_insert_id($koneksi);
        if (!$idlogistik_baru) {
            die("Gagal mendapatkan idlogistik_baru: " . $koneksi->error);
        }
        $pengirim       = mysqli_real_escape_string($koneksi, $pengirim);
        $alm_penerima   = mysqli_real_escape_string($koneksi, $alm_penerima);
        $namacs         = mysqli_real_escape_string($koneksi, $namacs);

        $ins_tuser          = $koneksi->query("INSERT INTO `t_user`
                                                    (
                                                        `id_user`, `idlogistik`, `namacs`, `nama`, `teleponpengirim`, `nama_penerima`, `teleponpenerima`, 
                                                        `alamat`, `keterangan`, `ekspedisi`, `invoice`, `status`,
                                                        `created_date`,`modified_date`,`resi_pengiriman`,`ongkir`,`pcs`,`marketplace`,`namamitra`,`idadmin`,`no_sj`,
                                                        `jenis_mitra`
                                                    ) 
                                                VALUES 
                                                    (
                                                        NULL, '$idlogistik_baru', '$namacs', '$pengirim', '$telp_pengirim', '$penerima', '$telp_penerima', 
                                                        '$alm_penerima', NULL, '$ekspedisi', '$invoice', NULL, NOW(), 
                                                        NOW(), NULL, NULL, NULL, NULL, 
                                                        '$pengirim', '$idadmin_mitra', NULL, '$jenis_mitra'
                                                    )
                                            ");
        if (!$ins_tuser) {
            throw new Exception("Insert t_user gagal: " . $koneksi->error);
        }

        $idTuser = $koneksi->insert_id;

        echo json_encode([
            'status' => 'success',
            'id'    => $idTuser
        ]);
        flush();
    } catch (Exception $e) {
        http_response_code(500);
        echo "Gagal insert data: " . $e->getMessage();
        flush();
    }

    file_put_contents('log-portal.txt', print_r($_POST, true) . "\n\n", FILE_APPEND);
?>