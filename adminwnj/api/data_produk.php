<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
session_start();
include '../../includes/db.php';

if(!isset($_SESSION["administrator"])){
    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login.php';</script>";
    header('location:login.php');
    exit();
}

// Ambil parameter DataTables
$draw   = isset($_GET['draw'])   ? intval($_GET['draw'])   : 1;
$start  = isset($_GET['start'])  ? intval($_GET['start'])  : 0;
$length = isset($_GET['length']) ? intval($_GET['length']) : 10;

$search = $_GET['search']['value'] ?? '';
$orderColumnIndex = $_GET['order'][0]['column'] ?? 0;
$orderDir         = $_GET['order'][0]['dir'] ?? 'asc';

// Mapping kolom sesuai UI
$columns = [
    0  => 'v.id',          // No (dummy, urutan nanti dari $draw/$start)
    1  => 'v.id',          // Checkbox (id variant)
    2  => 'v.idproducts',          // Checkbox (id variant)
    3  => 'v.status',
    4  => 'pk.namakategori',
    5  => 'p.namaproduk',
    6  => 'v.variant',
    7  => 'v.size',
    8  => 'v.harga',
    9  => 'v.berat',
    10  => 'k.namakategori', // Grade
    11 => 'v.stock',
    12 => 'v.stock',       // Opsi Stock (input manual, jadi tidak sorting real)
    13 => 'v.disc',
    14 => 'k.idkategori',  // Opsi Diskon Kategori
    15 => 'v.id'           // Action (Edit)
];

$orderColumn = $columns[$orderColumnIndex] ?? 'v.id';

// Hitung total data
$totalQuery = $koneksi->query("SELECT COUNT(*) as total FROM variants");
$totalData = $totalQuery->fetch_assoc()['total'];

// Filtering
$where = "";
if (!empty($search)) {
    $search = $koneksi->real_escape_string($search);
    $where = "WHERE p.namaproduk LIKE '%$search%' 
              OR v.variant LIKE '%$search%' 
              OR k.namakategori LIKE '%$search%' 
              OR pk.namakategori LIKE '%$search%'";
}

// Hitung total setelah filter
$filteredQuery = $koneksi->query("
    SELECT COUNT(*) as total 
    FROM variants v
    INNER JOIN products p ON v.idproducts = p.id
    INNER JOIN kategori k ON p.idkategori = k.idkategori
    INNER JOIN pkategori pk ON p.idpkategori = pk.idpkategori
    $where
");
$totalFiltered = $filteredQuery->fetch_assoc()['total'];

// length = -1 artinya "All" dipilih di lengthMenu, jadi tanpa LIMIT.
$limitSql = ($length == -1) ? "" : "LIMIT $start, $length";

// Ambil data utama
$query = $koneksi->query("
    SELECT v.id, v.size, v.variant, v.berat, v.harga, v.hargacoret, v.stock, v.foto, v.status,
           p.id as idproducts, p.namaproduk, v.disc,
           k.idkategori, k.namakategori AS kategori, pk.namakategori AS pkategori
    FROM variants v
    INNER JOIN products p ON v.idproducts = p.id
    INNER JOIN kategori k ON p.idkategori = k.idkategori
    INNER JOIN pkategori pk ON p.idpkategori = pk.idpkategori
    $where
    ORDER BY $orderColumn $orderDir
    $limitSql
");

// Opsi dropdown kategori diskon sama untuk semua baris, jadi diambil sekali saja
// di luar loop (dulu di-query ulang tiap baris - sangat lambat untuk data besar/"All").
$kategoriOptions = "";
$ambil = $koneksi->query("SELECT * FROM kategori ORDER BY idkategori ASC");
while ($opt = $ambil->fetch_assoc()) {
    $kategoriOptions .= "<option value='{$opt['idkategori']}'>{$opt['namakategori']}</option>";
}

$data = [];
$no = $start + 1;
while ($row = $query->fetch_assoc()) {
    // Status badge
    $status = ($row['status'] == 0) 
        ? "<span class='badge bg-success text-white'>Publish</span>" 
        : "<span class='badge bg-danger text-white'>Unpublish</span>";

    // Checkbox
    $checkbox       = "<input type='checkbox' class='check-item' name='id[]' value='{$row['id']}'>";
    $inputVariant   = "<input type='text' class='form-control' value='{$row['variant']}' name='variant[{$row['id']}]' size='50'>";
    $inputHarga     = "<input type='number' class='form-control' value='{$row['harga']}' name='harga[{$row['id']}]' size='50'>";
    $inputStock     = "<input type='number' class='form-control' value='{$row['stock']}' name='stock[{$row['id']}]' size='1'>";
    $inputDisc      = "<input type='number' class='form-control' value='{$row['disc']}' name='diskon[{$row['id']}]' size='1'>";
    $inputSize      = "<input type='text' class='form-control' value='{$row['size']}' name='size[{$row['id']}]' size='25'>";
    $inputBerat     = "<input type='number' class='form-control' value='{$row['berat']}' name='berat[{$row['id']}]' size='1'>";
    $idproducts     = $row['idproducts'] . '-' . $row['id'];
    $selectKategori = "<select class='form-control' name='idkategori[{$row['id']}]'>$kategoriOptions</select>";

    // Action
    $action = "<a href='#' class='btn-delete' data-id='{$row['id']}'><i class='fas fa-trash text-danger'></i></a>";

    $data[] = [
        $no++,
        $checkbox,
        $idproducts,
        $status,
        $row['pkategori'],
        $row['namaproduk'],
        $inputVariant,
        $inputSize,
        $inputHarga,
        $inputBerat,
        $selectKategori,
        $inputStock,
        $inputDisc,
        $action
    ];
}

// Response
$response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
];

echo json_encode($response);
