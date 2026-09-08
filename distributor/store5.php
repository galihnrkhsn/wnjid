<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include '../includes/promo_badge_helper.php';

    // ---- Input & pagination ----
    $namaproduk = isset($_GET['namaproduk']) ? trim($_GET['namaproduk']) : '';
    $isSearch   = isset($_GET['cari']) && $namaproduk !== '';

    $limit       = 20;
    $page        = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
    $limit_start = ($page - 1) * $limit;

    // Disamakan dengan store4.php agar produk, stok, dan urutannya konsisten di kedua mode.
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
    $totalRows = (int) ($countStmt->get_result()->fetch_assoc()['jumlah'] ?? 0);
    $countStmt->close();

    $jumlah_page = max(1, (int) ceil($totalRows / $limit));
    if ($page > $jumlah_page) {
        $page        = $jumlah_page;
        $limit_start = ($page - 1) * $limit;
    }

    $dataStmt = $koneksi->prepare("SELECT p.namaproduk, p.idpkategori, p.idkategori, v.*
                                    FROM variants v
                                    INNER JOIN products p ON v.idproducts = p.id
                                    WHERE $whereSql
                                    ORDER BY v.updated_at DESC
                                    LIMIT ?, ?");
    $dataParams   = $params;
    $dataParams[] = $limit_start;
    $dataParams[] = $limit;
    $dataStmt->bind_param($types . 'ii', ...$dataParams);
    $dataStmt->execute();
    $result = $dataStmt->get_result();

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
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Mode Hemat | Distributor WNJ.ID</title>
        <style>
            .store-page { padding-bottom: 110px; }
            .store-hero { background: linear-gradient(135deg, #f8fbff, #edf4ff); border: 1px solid #dfeafa; border-radius: 14px; padding: 1.25rem; margin: 1rem 0; }
            .store-title { color: #263a5f; font-size: 1.35rem; font-weight: 700; margin: 0; }
            .store-subtitle { color: #68748a; font-size: .9rem; margin: .25rem 0 0; }
            .view-switch { display: flex; gap: .5rem; flex-wrap: wrap; }
            .view-switch .btn { border-radius: 999px; font-size: .85rem; }
            .search-panel { background: #fff; border: 1px solid #e4e9f2; border-radius: 12px; padding: .85rem; }
            .search-panel .form-control, .search-panel .btn { min-height: 44px; border-radius: 8px; }
            .result-summary { color: #68748a; font-size: .88rem; margin: 1rem 0 .6rem; }
            .product-list { display: flex; flex-direction: column; gap: .75rem; }
            .product-item { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 1rem; align-items: center; background: #fff; border: 1px solid #e4e9f2; border-radius: 12px; padding: .85rem 1rem; box-shadow: 0 2px 8px rgba(39, 61, 99, .04); transition: box-shadow .15s ease, transform .15s ease; }
            .product-item:hover { box-shadow: 0 7px 18px rgba(39, 61, 99, .10); transform: translateY(-1px); }
            .product-name { font-weight: 700; color: #283b5d; line-height: 1.4; margin: 0 0 .55rem; font-size: 1rem; }
            .price-current { color: #1e7e34; font-weight: 700; font-size: 1rem; }
            .price-old { color: #b23a48; font-size: .78rem; text-decoration: line-through; margin-right: .35rem; }
            .stock-info { color: #53627a; font-size: .8rem; margin-top: .4rem; }
            .stock-info i { color: #28a745; }
            .buy-area { min-width: 104px; text-align: right; }
            .buy-area .btn { min-width: 100px; border-radius: 8px; }
            .unavailable { color: #8a5a00; font-size: .82rem; background: #fff7df; border-radius: 6px; padding: .45rem .55rem; }
            .empty-state { padding: 4rem 1rem; text-align: center; color: #6c757d; }
            .empty-state i { display: block; font-size: 3rem; margin-bottom: 1rem; color: #b7c3d6; }
            @media (max-width: 575.98px) { .store-hero { padding: 1rem; } .store-title { font-size: 1.15rem; } .view-switch { margin-top: .9rem; } .product-item { grid-template-columns: 1fr; gap: .7rem; } .buy-area { text-align: left; min-width: 0; } .buy-area .btn { width: 100%; } }
        </style>
    </head>
<body>
    <!-- NAVBAR -->
        <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <main class="container store-page">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info text-center mt-3" role="alert"><?= htmlspecialchars($_SESSION['message']) ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <section class="store-hero" aria-labelledby="store-title">
            <div class="d-sm-flex justify-content-between align-items-center">
                <div>
                    <h1 class="store-title" id="store-title"><i class="fa-solid fa-list mr-1"></i> Katalog Produk</h1>
                    <p class="store-subtitle">Mode Hemat menampilkan daftar ringkas agar belanja lebih cepat.</p>
                </div>
                <nav class="view-switch" aria-label="Pilihan tampilan katalog">
                    <a href="store5.php<?= $isSearch ? '?' . http_build_query(['cari' => 1, 'namaproduk' => $namaproduk]) : '' ?>" class="btn btn-primary" aria-current="page"><i class="fa-solid fa-list"></i> Mode Hemat</a>
                    <a href="store4.php<?= $isSearch ? '?' . http_build_query(['cari' => 1, 'namaproduk' => $namaproduk]) : '' ?>" class="btn btn-outline-primary"><i class="fa-solid fa-table-cells-large"></i> Mode Cantik</a>
                </nav>
            </div>
        </section>

        <form method="get" class="search-panel" role="search">
            <label class="sr-only" for="namaproduk">Cari produk</label>
            <div class="input-group">
                <input type="search" id="namaproduk" class="form-control" name="namaproduk" placeholder="Cari nama produk..." value="<?= htmlspecialchars($namaproduk) ?>">
                <div class="input-group-append"><button class="btn btn-primary" name="cari" value="1" type="submit"><i class="fa-solid fa-magnifying-glass"></i> <span class="d-none d-sm-inline">Cari</span></button></div>
            </div>
            <?php if ($isSearch): ?><a href="store5.php" class="btn btn-link btn-sm px-0 mt-1"><i class="fa-solid fa-xmark"></i> Hapus pencarian</a><?php endif; ?>
        </form>

        <?php if ($totalRows === 0): ?>
            <section class="empty-state">
                <i class="fa-solid fa-box-open" aria-hidden="true"></i>
                <?php if ($isSearch): ?>
                    <h2 class="h5">Produk tidak ditemukan</h2><p>Belum ada produk yang cocok dengan “<?= htmlspecialchars($namaproduk) ?>”.</p><a href="store5.php" class="btn btn-outline-primary">Lihat semua produk</a>
                <?php else: ?>
                    <h2 class="h5">Belum ada produk tersedia</h2><p>Silakan cek kembali beberapa saat lagi.</p>
                <?php endif; ?>
            </section>
        <?php else: ?>
            <p class="result-summary">Menampilkan <strong><?= $limit_start + 1 ?>–<?= min($limit_start + $limit, $totalRows) ?></strong> dari <strong><?= $totalRows ?></strong> produk tersedia.</p>
            <section class="product-list" aria-label="Daftar produk">
                <?php while ($data = $result->fetch_assoc()): ?>
                    <article class="product-item">
                        <div>
                            <h2 class="product-name"><?= htmlspecialchars($data['namaproduk'] . ' ' . $data['variant'] . ' ' . $data['size']) ?></h2>
                            <?php if ((int) $data['status'] === 0): ?>
                                <?php if ((int) $data['idkategori'] >= 51): ?>
                                    <span class="price-current">Harga menyesuaikan</span>
                                <?php elseif ((int) $data['disc'] > 0): ?>
                                    <span class="price-old">Rp <?= number_format((int) $data['harga']) ?></span><span class="price-current">Rp <?= number_format(hargaSetelahDisc((int) $data['harga'], (int) $data['disc'])) ?></span>
                                <?php else: ?>
                                    <?php if ((int) $data['hargacoret'] > 0): ?><span class="price-old">Rp <?= number_format((int) $data['hargacoret']) ?></span><?php endif; ?>
                                    <?php if (strpos($data['namaproduk'], 'Vanellus Dress') !== false): ?>
                                        <span class="price-current">Rp <?= number_format((int) $data['harga']) ?></span><span class="price-old d-block">Rp <?= number_format(480000) ?></span>
                                    <?php else: ?><span class="price-current">Rp <?= number_format((int) $data['harga']) ?></span><?php endif; ?>
                                <?php endif; ?>
                                <div class="stock-info"><i class="fa-solid fa-circle-check"></i> Stok tersedia: <?= (int) $data['stock'] ?></div>
                            <?php else: ?>
                                <p class="unavailable mb-0"><i class="fa-solid fa-clock"></i> Produk sedang diperbarui dan belum dapat dibeli.</p>
                            <?php endif; ?>
                        </div>
                        <div class="buy-area">
                            <?php if ((int) $data['status'] === 0): ?>
                                <a href="add_chart2.php?id=<?= (int) $data['id'] ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-cart-plus"></i> Beli</a>
                            <?php else: ?><button type="button" class="btn btn-secondary btn-sm" disabled>Belum tersedia</button><?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </section>

            <!-- PAGINATION -->
            <?php if ($jumlah_page > 1): ?>
            <nav aria-label="Navigasi halaman katalog">
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
            <!-- PAGINATION END -->
            <?php endif; ?>
    </main>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <?php include "menubawahstore.php"; ?>
    <?php include "settingdatatables.php"; ?>
  </body>
</html>
