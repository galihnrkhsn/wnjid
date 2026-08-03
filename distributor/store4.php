<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include '../includes/foto_helper.php';
    include '../includes/promo_badge_helper.php';

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

    $dataStmt = $koneksi->prepare("SELECT p.namaproduk, p.idpkategori, p.idkategori, v.*, fp.foto AS foto_file, mf.name AS nama_folder
                                    FROM variants v
                                    INNER JOIN products p ON v.idproducts = p.id
                                    LEFT JOIN foto_produk fp ON fp.id = v.foto
                                    LEFT JOIN master_folder mf ON fp.folder = mf.id
                                    WHERE $whereSql
                                    ORDER BY v.updated_at DESC
                                    LIMIT ?, ?");
    $dataParams  = $params;
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
    <meta name="DESCription" content="">
    <meta name="author" content="">
    <title>Distributor | Wanoja</title>
    <style>
        .fixed-size-img {
            width: 100%;
            height: 350px;
            object-fit: cover;
        }
        .product-card {
            position: relative;
            transition: box-shadow .15s ease-in-out, transform .15s ease-in-out;
        }
        .product-card:hover {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
            transform: translateY(-2px);
        }
        .promo-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            z-index: 2;
            background: #dc3545;
            color: #fff;
            font-size: .7rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
        }
        .disc-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 2;
            background: #dc3545;
            color: #fff;
            font-size: .7rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
        }
        .stock-badge {
            font-size: .8rem;
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
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-2">
    <?php
        //info message
        if (isset($_SESSION['message'])) {
            ?>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-6">
                    <div class="alert alert-info text-center">
                        <?= htmlspecialchars($_SESSION['message']) ?>
                    </div>
                </div>
            </div>
            <?php
            unset($_SESSION['message']);
        }
    ?>
        <p class="text-right">
            <label class="mb-0"><input type="radio" onclick="javascript:window.location.href='store5.php';"> Mode Hemat</label>
            &nbsp;&nbsp;
            <label class="mb-0"><input type="radio" onclick="javascript:window.location.href='store4.php';" checked="checked"> Mode Cantik</label>
        </p>
        <form method="get" class="form-inline justify-content-between mb-3">
            <div class="form-group mr-2 mb-2" style="flex: 1;">
                <input type="text" class="form-control" name="namaproduk" placeholder="Masukkan Nama Produk ..." style="width: 100%;" value="<?= htmlspecialchars($namaproduk) ?>" />
            </div>
            <button class="btn btn-primary mr-2" name="cari" type="submit" style="width:15%;">
                <span><i class="fa-solid fa-magnifying-glass"></i></span>
            </button>
            <button class="btn btn-primary mr-2" name="tampil" type="submit">Tampil Semua</button>
        </form>
    </div>
    <div class="container">
        <?php if ($totalRows === 0): ?>
            <div class="empty-state">
                <i class="fa-solid fa-box-open"></i>
                <?php if ($isSearch): ?>
                    Produk dengan nama "<strong><?= htmlspecialchars($namaproduk) ?></strong>" tidak ditemukan.
                <?php else: ?>
                    Belum ada produk yang tersedia saat ini.
                <?php endif; ?>
            </div>
        <?php else: ?>
        <p class="text-muted">Menampilkan <?= $totalRows === 0 ? 0 : ($limit_start + 1) ?>&ndash;<?= min($limit_start + $limit, $totalRows) ?> dari <?= $totalRows ?> produk</p>
        <div class="row">
            <?php while ($data = $result->fetch_assoc()): ?>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-6" style="margin-bottom: 2%;">
                <div class="card product-card">
                    <?php $badge = promoBadgeLabel($data['jenis'] ?? null); ?>
                    <?php if ($badge !== null): ?>
                        <span class="promo-badge"><?= htmlspecialchars($badge) ?></span>
                    <?php endif; ?>
                    <?php $discBadge = discBadgeLabel($data['disc'] ?? null); ?>
                    <?php if ($discBadge !== null): ?>
                        <span class="disc-badge"><?= htmlspecialchars($discBadge) ?></span>
                    <?php endif; ?>
                    <img class="card-img-top fixed-size-img" loading="lazy" src="<?= fotoProdukSrc($data['nama_folder'] ?? null, $data['foto_file'] ?? null) ?>" alt="<?= htmlspecialchars($data['namaproduk']) ?>">
                    <div class="card-body">
                        <h6 class="card-title"><?= htmlspecialchars($data['namaproduk']) ?> <?= htmlspecialchars($data['variant']) ?> <?= htmlspecialchars($data['size']) ?></h6>
                        <?php if ($data['status'] == 0): ?>
                            <p class="card-text">
                                <?php if ($data['idkategori'] >= 51): ?>
                                    -
                                <?php elseif ($data['disc'] > 0): ?>
                                    <span class="text-danger text-decoration-line-through">Rp. <?= number_format($data['harga']) ?></span>
                                    <br>
                                    Rp. <?= number_format(hargaSetelahDisc((int) $data['harga'], (int) $data['disc'])) ?>
                                <?php else: ?>
                                    <?php if ($data['hargacoret'] > 0) : ?>
                                        <span class="text-danger text-decoration-line-through">Rp. <?= number_format($data['hargacoret']) ?></span>
                                    <?php endif; ?>
                                    <br>
                                    <?php if (strpos($data['namaproduk'], "Vanellus Dress") !== false) : ?>
                                        <span>Rp. <?= number_format($data['harga']) ?></span>
                                        <span class="text-decoration-line-through d-block">Rp. <?= number_format(480000) ?></span>
                                    <?php else: ?>
                                        Rp. <?= number_format($data['harga']) ?>
                                    <?php endif ?>
                                <?php endif ?>
                            </p>
                            <p class="card-text d-flex align-items-center justify-content-between">
                                <a href="add_chart2.php?id=<?= (int) $data['id'] ?>" class="btn btn-primary btn-sm">Beli</a>
                                Stok: <?= (int) $data['stock'] ?>
                            </p>
                        <?php else: ?>
                            <p class="card-text text-muted">
                                Produk sedang diupdate, akan aktif setelah proses update selesai.
                            </p>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>

        <!-- PAGINATION -->
        <?php if ($totalRows > 0): ?>
        <nav aria-label="Page navigation example">
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
                    $jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
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

    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- FOOTER -->
    <?php include 'menubawahstore.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
        <!-- Jquery, Popper, Bootstrap -->
        <script src="../vendor/legacy-js/modernizr-3.5.0.min.js"></script>
        <script src="../vendor/legacy-js/jquery-1.12.4.min.js"></script>
        <script src="./assets2/js/popper.min.js"></script>
        <script src="./assets2/js/bootstrap.min.js"></script>

        <!-- Slick-slider , Owl-Carousel ,slick-nav -->
        <script src="./assets2/js/owl.carousel.min.js"></script>
        <script src="./assets2/js/slick.min.js"></script>
        <script src="./assets2/js/jquery.slicknav.min.js"></script>

        <!-- One Page, Animated-HeadLin, Date Picker -->
        <script src="./assets2/js/wow.min.js"></script>
        <script src="./assets2/js/animated.headline.js"></script>
        <script src="./assets2/js/jquery.magnific-popup.js"></script>
        <script src="./assets2/js/gijgo.min.js"></script>

        <!-- Nice-select, sticky,Progress -->
        <script src="./assets2/js/jquery.nice-select.min.js"></script>
        <script src="./assets2/js/jquery.sticky.js"></script>
        <script src="./assets2/js/jquery.barfiller.js"></script>

        <!-- counter , waypoint,Hover Direction -->
        <script src="./assets2/js/jquery.counterup.min.js"></script>
        <script src="./assets2/js/waypoints.min.js"></script>
        <script src="./assets2/js/jquery.countdown.min.js"></script>
        <script src="./assets2/js/hover-direction-snake.min.js"></script>

        <!-- contact js -->
        <script src="./assets2/js/contact.js"></script>
        <script src="./assets2/js/jquery.form.js"></script>
        <script src="./assets2/js/jquery.validate.min.js"></script>
        <script src="./assets2/js/mail-script.js"></script>
        <script src="./assets2/js/jquery.ajaxchimp.min.js"></script>

        <!-- Jquery Plugins, main Jquery -->
        <script src="./assets2/js/plugins.js"></script>
        <script src="./assets2/js/main.js"></script>
    <!-- SCRIPT END -->

    <?php include "settingdatatables.php"; ?>


</body>
</html>
