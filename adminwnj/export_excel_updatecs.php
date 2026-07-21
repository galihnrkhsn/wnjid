<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'koneksi.php';

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$tanggal_awal   = $_POST['tanggal_awal'];
$tanggal_akhir  = date('Y-m-d');

$stmt = $koneksi->prepare("SELECT DISTINCT
                            am.idadmin,
                            amc.namacs,
                            am.namamitra
                        FROM admin_mitra am
                        LEFT JOIN admin_mitra_cs amc
                            ON am.idadmin = amc.idadmin
                        LEFT JOIN ordermitra om
                            ON om.idmitra = am.idadmin
                        LEFT JOIN pomitra pm
                            ON pm.idmitra = am.idadmin
                        WHERE
                        (
                            om.tgl BETWEEN ? AND CURDATE()
                            OR
                            pm.tgl BETWEEN ? AND CURDATE()
                        )
                        ORDER BY am.namamitra
                    ");

$stmt->bind_param("ss", $tanggal_awal, $tanggal_awal);
$stmt->execute();

$result = $stmt->get_result();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header
$sheet->setCellValue('A1', 'NO');
$sheet->setCellValue('B1', 'IDADMIN');
$sheet->setCellValue('C1', 'NAMA CS');
$sheet->setCellValue('D1', 'NAMA MITRA');
$sheet->setCellValue('E1', 'PEMBAGIAN DB');

// Style Header (Bold)
$sheet->getStyle('A1:E1')->getFont()->setBold(true);

$row = 2;
$no = 1;

while ($data = $result->fetch_assoc()) {

    $sheet->setCellValue('A'.$row, $no++);
    $sheet->setCellValue('B'.$row, $data['idadmin']);
    $sheet->setCellValue('C'.$row, $data['namacs']);
    $sheet->setCellValue('D'.$row, $data['namamitra']);
    $sheet->setCellValue('E'.$row, ''); // Kosong

    $row++;
}

// Auto Size
foreach (range('A','E') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

// Nama File
$filename = 'Template_Pembagian_DB_'.date('YmdHis').'.xlsx';

// Header Download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

// Output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit;