<?php
/**
 * export_stock.php
 * -----------------------------------------------------------------
 * Export data Products + Variants (status = 0) ke Excel.
 * Format output:
 *
 *   Bergo Limicola Abaya                150
 *      Blue                              50
 *      Red                               50
 *      Green                             50
 *   Dress Limicola Abaya                 21
 *      Blue                              10
 *      Red                                5
 *      Green                              6
 *
 * -----------------------------------------------------------------
 * INSTALASI:
 *   composer require phpoffice/phpspreadsheet
 *
 * JALANKAN:
 *   php export_stock.php
 *   (atau akses via browser, akan otomatis download file .xlsx)
 * -----------------------------------------------------------------
 */

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../includes/db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// ===================================================================
// 1. KONEKSI DATABASE
// ===================================================================
// Diasumsikan db.php sudah membuat variabel koneksi mysqli bernama $koneksi,
// misalnya: $koneksi = mysqli_connect($host, $user, $pass, $dbname);
// Kalau nama variabel koneksi kamu beda (misal $koneksi / $mysqli),
// ganti semua pemakaian $koneksi di bawah ini.
if (!$koneksi) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

// ===================================================================
// 2. AMBIL DATA — join products & variants, filter status = 0
//    Diurutkan per produk supaya gampang dikelompokkan di PHP
// ===================================================================
$sql = "
    SELECT
        p.id            AS product_id,
        p.namaproduk    AS product_name,
        v.id            AS variant_id,
        v.variant       AS variant_name,
        v.size          AS variant_size,
        v.stock         AS variant_stock
    FROM products p
    INNER JOIN variants v
        ON v.idproducts = p.id
    WHERE v.status = 0
    AND v.stock > 0
    ORDER BY p.namaproduk ASC, v.variant ASC
";

$result = mysqli_query($koneksi, $sql);
if (!$result) {
    die('Query gagal: ' . mysqli_error($koneksi));
}

$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

// ===================================================================
// 3. KELOMPOKKAN DATA PER PRODUK + HITUNG TOTAL STOCK
// ===================================================================
$grouped = []; // [product_id => ['name'=>..., 'total_stock'=>0, 'variants'=>[...]]]

foreach ($rows as $row) {
    $pid = $row['product_id'];

    if (!isset($grouped[$pid])) {
        $grouped[$pid] = [
            'name'        => $row['product_name'],
            'total_stock' => 0,
            'variants'    => [],
        ];
    }

    // Label varian: gabungkan variant + size kalau size ada isinya
    $variantLabel = $row['variant_name'];
    if (!empty($row['variant_size'])) {
        $variantLabel .= ' - ' . $row['variant_size'];
    }

    $grouped[$pid]['variants'][] = [
        'label' => $variantLabel,
        'stock' => (int) $row['variant_stock'],
    ];

    $grouped[$pid]['total_stock'] += (int) $row['variant_stock'];
}

// ===================================================================
// 4. BUAT EXCEL DENGAN PhpSpreadsheet
// ===================================================================
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Stock Report');

// --- Header kolom ---
$sheet->setCellValue('A1', 'Nama Produk / Varian');
$sheet->setCellValue('B1', 'Total Stock');

$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => [
        'fillType'   => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4472C4'],
    ],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
];
$sheet->getStyle('A1:B1')->applyFromArray($headerStyle);

$rowIndex = 2;

foreach ($grouped as $product) {
    // --- Baris Produk (bold + background abu-abu) ---
    $sheet->setCellValue("A{$rowIndex}", $product['name']);
    $sheet->setCellValue("B{$rowIndex}", $product['total_stock']);

    $sheet->getStyle("A{$rowIndex}:B{$rowIndex}")->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType'   => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'D9E1F2'],
        ],
    ]);
    $rowIndex++;

    // --- Baris Varian (indent di kolom A) ---
    foreach ($product['variants'] as $variant) {
        $sheet->setCellValue("A{$rowIndex}", $variant['label']);
        $sheet->setCellValue("B{$rowIndex}", $variant['stock']);

        // indent supaya kelihatan sebagai sub-item dari produk
        $sheet->getStyle("A{$rowIndex}")->getAlignment()->setIndent(1);

        $rowIndex++;
    }
}

// --- Lebar kolom otomatis ---
$sheet->getColumnDimension('A')->setWidth(45);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getStyle("B2:B{$rowIndex}")->getAlignment()
      ->setHorizontal(Alignment::HORIZONTAL_CENTER);

// ===================================================================
// 5. OUTPUT / SIMPAN FILE
// ===================================================================
$fileName = 'stock_report_' . date('Y-m-d_His') . '.xlsx';
$writer = new Xlsx($spreadsheet);

if (php_sapi_name() === 'cli') {
    // Jalan via command line -> simpan ke folder saat ini
    $writer->save(__DIR__ . '/' . $fileName);
    echo "File berhasil dibuat: {$fileName}\n";
} else {
    // Jalan via browser -> langsung download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
}