<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'koneksi.php';

require 'vendor/autoload.php';

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

    $idVariant  = trim($row[0] ?? '');
    $variant    = trim($row[3] ?? '');
    $size       = trim($row[4] ?? '');
    $berat      = trim($row[5] ?? '');
    $harga      = trim($row[6] ?? '');
    $stock      = trim($row[7] ?? '');

    if (
        $idVariant === '' &&
        $variant === '' &&
        $berat === '' &&
        $harga === '' &&
        $stock === ''
    ) {
        continue;
    }
    
    if ($variant === '' || $berat === '' || $harga === '' || $stock === '') {
        $totalSkip++;

        $skipData[] = [
            'id'        => $idVariant,
            'variant'   => $variant . ' ' . $size,
            'alasan'    => 'ada data yang kosong'
        ];

        continue;
    }

    $stmt = $koneksi->prepare("
        UPDATE variants
        SET variant = ?,
        berat = ?,
        harga = ?,
        stock = ?,
        updated_at = NOW()
        WHERE id = ?
    ");

    $stmt->bind_param("siiii", $variant, $berat, $harga, $stock, $idVariant);

    if (!$stmt->execute()) {
        die($stmt->error);
    }

    if ($stmt->affected_rows > 0) {
        $totalUpdate++;
    } else {
        $totalSkip++;

        $skipData[] = [
            'id'        => $idVariant,
            'variant'   => $variant . ' ' . $size,
            'alasan'    => 'id tidak ditemukan / data tidak berubah'
        ];
    }

}

$detailSkip = "";

if (!empty($skipData)) {

    $detailSkip .= "\\n\\nDETAIL SKIP:\\n";

    foreach ($skipData as $skip) {

        $detailSkip .= 
            "ID: ".$skip['id'].
            " | ".$skip['variant'].
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
window.location='update_produk.php';
</script>
";