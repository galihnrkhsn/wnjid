<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';
    include '../includes/foto_helper.php';

    // ---- Input & pagination ----
    $namaproduk = isset($_GET['namaproduk']) ? trim($_GET['namaproduk']) : '';
    $isSearch   = isset($_GET['cari']) && $namaproduk !== '';

    $limit       = 20;
    $page        = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
    $limit_start = ($page - 1) * $limit;

    // ---- Build WHERE clause shared by count & data queries ----
    $whereSql = "v.status <> 1 AND v.stock > 0";
    $params   = [];
    $types    = '';

    if ($isSearch) {
        $whereSql .= " AND p.namaproduk LIKE ? AND p.idkategori > 0";
        $params[]  = '%' . $namaproduk . '%';
        $types    .= 's';
    }

    $countStmt = $koneksi->prepare("SELECT COUNT(*) AS jumlah
                                     FROM variants v
                                     INNER JOIN products p ON v.idproducts = p.id
                                     WHERE $whereSql");
    if ($types !== '') {
        $countStmt->bind_param($types, ...$params);
    }
    $countStmt->execute();
    $totalRows   = (int) ($countStmt->get_result()->fetch_assoc()['jumlah'] ?? 0);
    $countStmt->close();

    $jumlah_page = max(1, (int) ceil($totalRows / $limit));
    if ($page > $jumlah_page) {
        $page        = $jumlah_page;
        $limit_start = ($page - 1) * $limit;
    }

    $dataStmt = $koneksi->prepare("SELECT p.namaproduk, p.idpkategori, p.idkategori, v.*, mf.name AS nama_folder
                                    FROM variants v
                                    INNER JOIN products p ON v.idproducts = p.id
                                    LEFT JOIN master_folder mf ON v.folder = mf.id
                                    WHERE $whereSql
                                    ORDER BY v.updated_at DESC
                                    LIMIT ?, ?");
    $dataParams   = $params;
    $dataParams[] = $limit_start;
    $dataParams[] = $limit;
    $dataStmt->bind_param($types . 'ii', ...$dataParams);
    $dataStmt->execute();
    $result = $dataStmt->get_result();

    // Preserve search state across pagination links
    function buildPageUrl(int $targetPage, bool $isSearch, string $namaproduk): string
    {
        $query = ['page' => $targetPage];
        if ($isSearch) {
            $query['cari']       = 1;
            $query['namaproduk'] = $namaproduk;
        }
        return '?' . http_build_query($query);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Belanja | Wanoja</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: #f5f6fa;
        }
        .search-bar {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
            padding: .5rem;
            margin: 1rem 0 1.25rem;
        }
        .search-bar input {
            border: none;
        }
        .search-bar input:focus {
            box-shadow: none;
        }
        .product-card {
            background: #fff;
            border: none;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            transition: transform .15s ease-in-out, box-shadow .15s ease-in-out;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1.25rem rgba(0,0,0,.1);
        }
        .product-card .fixed-size-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        .product-card .card-title {
            font-size: .9rem;
            font-weight: 600;
            min-height: 2.4em;
        }
        .price {
            color: #2b2f42;
            font-weight: 700;
        }
        .price-old {
            color: #adb5bd;
            text-decoration: line-through;
            font-size: .8rem;
        }
        .btn-beli {
            border-radius: 8px;
            font-weight: 600;
        }
        .empty-state {
            padding: 4rem 1rem;
            text-align: center;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info text-center mt-3 mb-0">
                <?= htmlspecialchars($_SESSION['message']) ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form method="get" class="search-bar d-flex align-items-center">
            <i class="bi bi-search text-muted mx-2"></i>
            <input type="text" class="form-control" name="namaproduk" placeholder="Cari produk..." value="<?= htmlspecialchars($namaproduk) ?>">
            <button class="btn btn-primary ml-2" name="cari" value="1" type="submit">Cari</button>
            <?php if ($isSearch): ?>
                <a href="index.php" class="btn btn-outline-secondary ml-2">Reset</a>
            <?php endif; ?>
        </form>

        <?php if ($totalRows === 0): ?>
            <div class="empty-state">
                <i class="bi bi-box-seam"></i>
                <?php if ($isSearch): ?>
                    Produk dengan nama "<strong><?= htmlspecialchars($namaproduk) ?></strong>" tidak ditemukan.
                <?php else: ?>
                    Belum ada produk yang tersedia saat ini.
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p class="text-muted small">Menampilkan <?= $limit_start + 1 ?>&ndash;<?= min($limit_start + $limit, $totalRows) ?> dari <?= $totalRows ?> produk</p>
            <div class="row">
                <?php while ($data = $result->fetch_assoc()): ?>
                    <div class="col-6 col-md-4 col-lg-3 mb-4">
                        <div class="product-card">
                            <img class="fixed-size-img" loading="lazy" src="<?= fotoProdukSrc($data['nama_folder'] ?? null, $data['foto'] ?? null) ?>" alt="<?= htmlspecialchars($data['namaproduk']) ?>">
                            <div class="card-body">
                                <div class="card-title"><?= htmlspecialchars($data['namaproduk']) ?> <?= htmlspecialchars($data['variant']) ?> <?= htmlspecialchars($data['size']) ?></div>
                                <?php if ($data['status'] == 0): ?>
                                    <div class="mb-2">
                                        <?php if ($data['idkategori'] >= 51): ?>
                                            <span class="text-muted small">Hubungi kami untuk harga</span>
                                        <?php elseif (strpos($data['namaproduk'], "Vanellus Dress") !== false): ?>
                                            <div class="price">Rp <?= number_format($data['harga']) ?></div>
                                            <div class="price-old">Rp <?= number_format(480000) ?></div>
                                        <?php else: ?>
                                            <?php if ($data['hargacoret'] > 0): ?>
                                                <div class="price-old">Rp <?= number_format($data['hargacoret']) ?></div>
                                            <?php endif; ?>
                                            <div class="price">Rp <?= number_format($data['harga']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="add_chart.php?id=<?= (int) $data['id'] ?>" class="btn btn-primary btn-sm btn-beli">
                                            <i class="bi bi-cart-plus"></i> Beli
                                        </a>
                                        <span class="text-muted small">Stok <?= (int) $data['stock'] ?></span>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted small mb-0">Produk sedang diperbarui.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <?php if ($totalRows > 0): ?>
        <nav aria-label="Page navigation" class="mb-5">
            <ul class="pagination justify-content-center">
                <?php if ($page == 1): ?>
                    <li class="page-item disabled"><a class="page-link" href="#">First</a></li>
                    <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
                <?php else:
                    $link_prev = ($page > 1) ? $page - 1 : 1;
                ?>
                    <li class="page-item"><a class="page-link" href="<?= buildPageUrl(1, $isSearch, $namaproduk) ?>">First</a></li>
                    <li class="page-item"><a class="page-link" href="<?= buildPageUrl($link_prev, $isSearch, $namaproduk) ?>">&laquo;</a></li>
                <?php endif; ?>
                <?php
                    $jumlah_number = 3;
                    $start_number  = ($page > $jumlah_number) ? $page - $jumlah_number : 1;
                    $end_number    = ($page < ($jumlah_page - $jumlah_number)) ? $page + $jumlah_number : $jumlah_page;

                    for ($i = $start_number; $i <= $end_number; $i++):
                        $link_active = ($page == $i) ? ' active' : '';
                ?>
                    <li class="page-item<?= $link_active ?>"><a class="page-link" href="<?= buildPageUrl($i, $isSearch, $namaproduk) ?>"><?= $i ?></a></li>
                <?php endfor; ?>
                <?php if ($page == $jumlah_page): ?>
                    <li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>
                    <li class="page-item disabled"><a class="page-link" href="#">Last</a></li>
                <?php else:
                    $link_next = ($page < $jumlah_page) ? $page + 1 : $jumlah_page;
                ?>
                    <li class="page-item"><a class="page-link" href="<?= buildPageUrl($link_next, $isSearch, $namaproduk) ?>">&raquo;</a></li>
                    <li class="page-item"><a class="page-link" href="<?= buildPageUrl($jumlah_page, $isSearch, $namaproduk) ?>">Last</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
