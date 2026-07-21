<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'koneksi.php';

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($_SESSION["administrator"])) {

    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login.php';</script>";
    exit();
}

if (!isset($_POST['idproducts'])) {

    echo "<script>alert('Produk belum dipilih');</script>";
    echo "<script>history.back();</script>";
    exit();
}

$idproducts = $_POST['idproducts'];

if (count($idproducts) == 0) {

    echo "<script>alert('Produk kosong');</script>";
    echo "<script>history.back();</script>";
    exit();
}

$idproducts = array_map('intval', $idproducts);
$in         = implode(',', $idproducts);

$query  = $koneksi->query("SELECT
                            a.namaproduk,
                            a.id as idproducts,
                            b.id,
                            b.variant,
                            b.size,
                            b.berat,
                            b.harga,
                            b.stock
                        FROM products a
                        INNER JOIN variants b ON b.idproducts = a.id 
                        WHERE a.id IN ($in)
                        ORDER BY a.id, b.id ASC
                    ");

$spreadsheet    = new Spreadsheet();
$sheet          = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'id');
$sheet->setCellValue('B1', 'idproducts');
$sheet->setCellValue('C1', 'namaproduk');
$sheet->setCellValue('D1', 'variant');
$sheet->setCellValue('E1', 'size');
$sheet->setCellValue('F1', 'berat');
$sheet->setCellValue('G1', 'harga');
$sheet->setCellValue('H1', 'stock');

$sheet->getStyle('A1:H1')->getFont()->setBold(true);

$sheet->setCellValue(
    'J6',
    'JANGAN UBAH TEMPLATE EXCEL, UBAH ISINYA SAJA'
);

$sheet->setCellValue(
    'J9',
    'HANYA BISA UPDATE VARIANT, BERAT, HARGA, STOCK'
);

$sheet->mergeCells('J6:N7');
$sheet->mergeCells('J9:N10');

$sheet->getStyle('J6:N7')->getFont()
    ->setBold(true)
    ->setSize(10);

$sheet->getStyle('J6:N7')->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
    ->setWrapText(true);

$sheet->getStyle('J6:N7')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFFF0000');

$sheet->getStyle('J6:N7')->getFont()
    ->getColor()
    ->setARGB('FFFFFFFF');

$sheet->getStyle('J6:N7')->getBorders()
    ->getAllBorders()
    ->setBorderStyle(
        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
    );

$sheet->getStyle('J9:N10')->getFont()
    ->setBold(true)
    ->setSize(10);

$sheet->getStyle('J9:N10')->getAlignment()
    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
    ->setWrapText(true);

$sheet->getStyle('J9:N10')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFFFFF00');

$sheet->getStyle('J9:N10')->getFont()
    ->getColor()
    ->setARGB('FF000000');

$sheet->getStyle('J9:N10')->getBorders()
    ->getAllBorders()
    ->setBorderStyle(
        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
    );

$rowExcel = 2;

while ($data = $query->fetch_assoc()) {
    $sheet->setCellValue('A'.$rowExcel, $data['id']);
    $sheet->setCellValue('B'.$rowExcel, $data['idproducts']);
    $sheet->setCellValue('C'.$rowExcel, $data['namaproduk']);
    $sheet->setCellValue('D'.$rowExcel, $data['variant']);
    $sheet->setCellValue('E'.$rowExcel, $data['size']);
    $sheet->setCellValue('F'.$rowExcel, $data['berat']);
    $sheet->setCellValue('G'.$rowExcel, $data['harga']);
    $sheet->setCellValue('H'.$rowExcel, $data['stock']);

    $rowExcel++;
}

foreach(range('A','G') as $column){
    $sheet->getColumnDimension($column)
        ->setAutoSize(true);
}

$filename = 'export_produk_'.date('Ymd_His').'.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit();