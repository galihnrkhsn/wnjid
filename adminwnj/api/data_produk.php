<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
session_start();
include __DIR__ . '/../access_guard.php';
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

$search           = $_GET['search']['value'] ?? '';
$orderColumnIndex = $_GET['order'][0]['column'] ?? 0;
$orderDir         = $_GET['order'][0]['dir'] ?? 'asc';
$tab              = ($_GET['tab'] ?? 'publish') === 'unpublish' ? 'unpublish' : 'publish';

// Mapping kolom sesuai UI - produk-level, sebagian kolom agregat dari variants
$columns = [
    0 => 'p.id',              // No (dummy, urutan nanti dari $draw/$start)
    1 => 'p.id',               // Checkbox (id produk)
    2 => 'p.namaproduk',
    3 => 'pk.namakategori',
    4 => 'k.namakategori',
    5 => 'jumlah_varian',
    6 => 'total_stock',
    7 => 'jumlah_publish',     // dasar badge status
    8 => 'p.id'                // Aksi (Edit)
];
$orderColumn = $columns[$orderColumnIndex] ?? 'p.id';
$orderDir    = strtolower($orderDir) === 'desc' ? 'DESC' : 'ASC';

$where = "";
if (!empty($search)) {
    $search = $koneksi->real_escape_string($search);
    $where  = "WHERE (p.namaproduk LIKE '%$search%' OR k.namakategori LIKE '%$search%' OR pk.namakategori LIKE '%$search%')";
}

// tab publish = produk dengan minimal 1 varian aktif (status=0), unpublish = tidak ada sama sekali
$having = $tab === 'publish' ? "HAVING jumlah_publish > 0" : "HAVING jumlah_publish = 0";

$baseFrom = "
    FROM products p
    LEFT JOIN variants v ON v.idproducts = p.id
    LEFT JOIN kategori k ON p.idkategori = k.idkategori
    LEFT JOIN pkategori pk ON p.idpkategori = pk.idpkategori
    $where
    GROUP BY p.id
    $having
";

$totalData = $koneksi->query("SELECT COUNT(*) c FROM products")->fetch_assoc()['c'];

// GROUP BY + HAVING butuh dibungkus subquery buat dihitung total barisnya - alias jumlah_publish
// yang dipakai HAVING harus ikut di-select juga di subquery ini (bukan cuma p.id).
$totalFiltered = $koneksi->query("
    SELECT COUNT(*) c FROM (
        SELECT p.id, SUM(CASE WHEN v.status = 0 THEN 1 ELSE 0 END) AS jumlah_publish
        $baseFrom
    ) t
")->fetch_assoc()['c'];

$limitSql = ($length == -1) ? "" : "LIMIT $start, $length";

$query = $koneksi->query("
    SELECT p.id, p.namaproduk, pk.namakategori AS pkategori, k.namakategori AS kategori,
           COUNT(v.id) AS jumlah_varian,
           COALESCE(SUM(v.stock), 0) AS total_stock,
           SUM(CASE WHEN v.status = 0 THEN 1 ELSE 0 END) AS jumlah_publish
    $baseFrom
    ORDER BY $orderColumn $orderDir
    $limitSql
");

$data = [];
$no = $start + 1;
while ($row = $query->fetch_assoc()) {
    $isPublish = (int) $row['jumlah_publish'] > 0;
    $status    = $isPublish
        ? "<span class='badge bg-success text-white'>Publish</span>"
        : "<span class='badge bg-danger text-white'>Unpublish</span>";

    $checkbox = "<input type='checkbox' name='id[]' value='" . (int) $row['id'] . "'>";
    $aksi     = "<a href='maintenance_produk.php?id=" . (int) $row['id'] . "' class='btn btn-sm btn-outline-primary' title='Kelola varian & detail produk'><i class='fas fa-pen'></i> Edit</a>";

    $data[] = [
        $no++,
        $checkbox,
        htmlspecialchars($row['namaproduk']),
        htmlspecialchars($row['pkategori'] ?? '-'),
        htmlspecialchars($row['kategori'] ?? '-'),
        (int) $row['jumlah_varian'],
        (int) $row['total_stock'],
        $status,
        $aksi
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
