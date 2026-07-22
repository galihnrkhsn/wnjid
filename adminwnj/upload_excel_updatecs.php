<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'koneksi.php';

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!isset($_SESSION["administrator"])) {
    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login.php';</script>";
    exit();
}

if (!isset($_FILES['file_excel'])) {

    echo "<script>alert('File belum dipilih');</script>";
    echo "<script>history.back();</script>";
    exit();
}

$fileName = $_FILES['file_excel']['name'];
$tmpName  = $_FILES['file_excel']['tmp_name'];

$ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if ($ext != 'xls' && $ext != 'xlsx') {

    echo "<script>alert('Format file harus Excel');</script>";
    echo "<script>history.back();</script>";
    exit();
}

$spreadsheet = IOFactory::load($tmpName);
$sheet = $spreadsheet->getActiveSheet()->toArray();

$totalUpdate = 0;
$totalSkip   = 0;
$skipData    = [];

foreach ($sheet as $index => $row) {
    if ($index == 0) {
        continue;
    }

    $idAdmin    = trim($row[1] ?? '');
    $namaMitra  = trim($row[3] ?? '');
    $namaCs     = trim($row[4] ?? '');

    if ($namaCs == '') {
        $totalSkip++;

        $skipData[] = [
            'idadmin'       => $idAdmin,
            'nama_mitra'    => $namaMitra,
            'alasan'        => 'nama_cs kosong'
        ];

        continue;
    } elseif ($idAdmin === '') {
        $totalSkip++;

        $skipData[] = [
            'idadmin'       => '',
            'nama_mitra'    => $namaMitra,
            'alasan'        => 'idadmin kosong'
        ];

        continue;
    }

    $mitraCs = $koneksi->query("SELECT idadmin FROM admin_mitra_cs WHERE idadmin = '$idAdmin'")->fetch_assoc();

    if (!$mitraCs) {

        $insert = $koneksi->prepare("
            INSERT INTO admin_mitra_cs
            (
                idadmin,
                namamitra,
                namacs,
                updated_at,
                created_at
            )
            VALUES
            (
                ?,
                ?,
                ?,
                NOW(),
                NOW()
            )
        ");

        $insert->bind_param("iss", $idAdmin, $namaMitra, $namaCs);
        $insert->execute();

        if ($insert->affected_rows > 0) {

            $totalUpdate++;

        } else {

            $totalSkip++;

            $skipData[] = [
                'idadmin'       => 'NEW',
                'nama_mitra'    => $namaMitra,
                'alasan'        => 'gagal insert data baru'
            ];
        }

        continue;
    }

    $stmt = $koneksi->prepare("
        UPDATE admin_mitra_cs
        SET namacs = ?,
        updated_at = NOW()
        WHERE idadmin = ?
    ");

    $stmt->bind_param("ss", $namaCs, $idAdmin); 
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $totalUpdate++;
    } else {
        $totalSkip++;

        $skipData[] = [
            'idadmin'       => $idAdmin,
            'nama_mitra'    => $namaMitra,
            'nama_cs'       => $namaCs,
            'alasan'        => 'idadmin tidak ditemukan / data tidak berubah'
        ];
    }

}

$detailSkip = "";

if (!empty($skipData)) {

    $detailSkip .= "\\n\\nDETAIL SKIP:\\n";

    foreach ($skipData as $skip) {

        $detailSkip .= 
            "ID: ".$skip['idadmin'].
            " | ".$skip['nama_mitra'].
            " | ".$skip['alasan'].
            "\\n";
    }
}
echo "
<script>

alert(
    'Import selesai\\n\\n' +
    'Berhasil Update : $totalUpdate data\\n' +
    'Skip : $totalSkip data' +
    '$detailSkip'
);

window.location='update_cssales.php';

</script>
";