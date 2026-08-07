<?php
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';

    // ---- Input & pagination ----
    $namaproduk = isset($_GET['namaproduk']) ? trim($_GET['namaproduk']) : '';
    $isSearch   = isset($_GET['cari']) && $namaproduk !== '';

    $limit       = 50;
    $page        = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
    $limit_start = ($page - 1) * $limit;

    $whereSql = "variants.status <> 1 AND variants.stock > 0";
    $params   = [];
    $types    = '';

    if ($isSearch) {
        $whereSql .= " AND products.namaproduk LIKE ?";
        $params[]  = '%' . $namaproduk . '%';
        $types    .= 's';
    }

    $countStmt = $koneksi->prepare("SELECT COUNT(*) AS jumlah
                                     FROM variants
                                     INNER JOIN products ON variants.idproducts = products.id
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

    $dataStmt = $koneksi->prepare("SELECT products.namaproduk, variants.*
                                    FROM variants
                                    INNER JOIN products ON variants.idproducts = products.id
                                    WHERE $whereSql
                                    ORDER BY tgl DESC, variants.idproducts DESC, namaproduk ASC
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
        <title>Distributor | WNJ.ID</title>
    </head>
<body>
    <!-- NAVBAR -->
        <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <div class="container mt-2">

        <div>
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
            <p align="right">
                <label class="mb-0"><input type="radio" onclick="javascript:window.location.href='store5.php';" checked="checked"> Mode Hemat</label>
                &nbsp;&nbsp;
                <label class="mb-0"><input type="radio" onclick="javascript:window.location.href='store4.php';"> Mode Cantik</label>
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
            <?php if ($totalRows === 0): ?>
                <div class="text-center text-muted py-4">
                    <?php if ($isSearch): ?>
                        Produk dengan nama "<strong><?= htmlspecialchars($namaproduk) ?></strong>" tidak ditemukan.
                    <?php else: ?>
                        Belum ada produk yang tersedia saat ini.
                    <?php endif; ?>
                </div>
            <?php else: ?>
            <p class="text-muted">Menampilkan <?= $limit_start + 1 ?>&ndash;<?= min($limit_start + $limit, $totalRows) ?> dari <?= $totalRows ?> produk</p>
            <table class="table table-responsive" id="tb_store" border="0" >
                <thead>
                    <tr>
                        <td><span class="glyphicon glyphicon-shopping-cart"></span></td>
                        <td>Stock</td>
                        <td>Nama Produk</td>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($data = $result->fetch_assoc()): ?>

                    <tr>
                        <td>
                            <?php if ($data['status'] == 0): ?>
                                <a class="btn btn-info btn-xs" href="add_chart2.php?id=<?= (int) $data['id'] ?>"><span class="fas fa-cart-shopping"></span></a>
                            <?php else: ?>
                                <button class="btn btn-info btn-xs"><i class="fas fa-cart-shopping"></i></button>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if ($data['status'] == 0): ?>
                            <?= (int) $data['stock'] ?>
                            <?php else: ?>
                                -
                            <?php endif ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($data['namaproduk'] . ' ' . $data['variant'] . ' - ' . $data['size']) ?>
                            <?php if ($data['status'] == 2): ?>
                            (Produk sedang di update, akan aktif setelah proses update selesai)
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- PAGINATION -->
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
            <!-- PAGINATION END -->
            <?php endif; ?>
        </div>
    </div>
    <br><br><br><br>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <?php include "menubawahstore.php"; ?>
    <?php include "settingdatatables.php"; ?>
  </body>
</html>
