<?php
include 'koneksi.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$query = "
    SELECT 
        pkategori.namakategori,
        products.namaproduk,
        products.spek,
        products.img,
        variants.variant,
        variants.stock,
        variants.harga,
        master_size.nama_size,
        master_size.id
    FROM variants
    JOIN products ON variants.idproducts = products.id
    JOIN pkategori ON products.idpkategori = pkategori.idpkategori
    JOIN master_size ON variants.size_id = master_size.id
    WHERE variants.status <> 1
    AND variants.stock > 0
    ORDER BY pkategori.namakategori DESC, master_size.id, products.namaproduk
";


$result = mysqli_query($koneksi, $query);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $kategori = $row['namakategori'];
    $produk   = $row['namaproduk'];
    $size     = $row['nama_size'];
    $variant  = $row['variant'];

    // size dinamis
    $data[$kategori]['sizes'][$size] = $size;

    // harga per produk
    $data[$kategori]['produkspek'][$produk]['harga'][] = $row['harga'];

    // simpan spek & img (cukup 1x per produk)
    $data[$kategori]['produkspek'][$produk]['spek'] = $row['spek'];
    $data[$kategori]['produkspek'][$produk]['img']  = $row['img'];

    // simpan data per variant (BUKAN dijumlahkan)
    $data[$kategori]['produk'][$produk][$variant]['stok'][$size] = $row['stock'];
}
// PRIORITAS KATEGORI
$priority = ['Legging', 'Sarung'];

uksort($data, function ($a, $b) use ($priority) {

    $posA = array_search($a, $priority);
    $posB = array_search($b, $priority);

    // Jika dua-duanya ada di priority
    if ($posA !== false && $posB !== false) {
        return $posA - $posB;
    }

    // Jika hanya A yang ada di priority
    if ($posA !== false) return -1;

    // Jika hanya B yang ada di priority
    if ($posB !== false) return 1;

    // Selain itu urut normal ASC
    return strcasecmp($a, $b);
});

?>
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Daftar Produk</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { 
                background:#f8f9fa; 
            }
            .card { 
                border-radius:12px; 
            }
            .table {
                white-space: nowrap; /* supaya tidak pecah baris */
            }
            .table th { 
                background:#f1f3f5; 
                text-align:center; 
                border-collapse: collapse;
            }
            .table th td { 
                text-align:center; 
                vertical-align:middle;
                border:1px solid #000; 
            }
            th, td {
                padding: 8px;
                text-align: center;
                vertical-align: middle;
            }
            .sticky-table {
                max-height: 400px;
                overflow: auto; /* bukan cuma overflow-y */
            }
            .sticky-table thead th {
                position: sticky;
                top: 0;
                background: #f1f3f5; /* wajib ada supaya tidak transparan */
                z-index: 2;
            }
        </style>
    </head>
    <body>
    <div class="container my-5">
        <div class="card shadow-sm p-4">
            <h4 class="fw-bold mb-4">Daftar Produk</h4>
            <ul class="nav nav-tabs" role="tablist">
                <?php $i=0; foreach ($data as $kategori => $item): 
                    $tabId = 'tab-' . md5($kategori);
                ?>
                <li class="nav-item">
                    <button class="nav-link <?= $i==0?'active':'' ?>"
                        data-bs-toggle="tab"
                        data-bs-target="#<?= $tabId ?>"
                        type="button">
                        <?= $kategori ?>
                    </button>
                </li>
                <?php $i++; endforeach; ?>
            </ul>
            <div class="tab-content mt-3">
                <?php $i=0; foreach ($data as $kategori => $item): 
                    $tabId = 'tab-' . md5($kategori);
                ?>
                    <div class="tab-pane fade <?= $i==0?'show active':'' ?>" id="<?= $tabId ?>">
                        <h5 class="fw-bold mb-3"><?= $kategori ?></h5>
                        <!-- ===== INFORMASI PRODUK ===== -->
                        <h6 class="fw-semibold">Informasi Produk</h6>
                        <div class="table-responsive">

                            <table class="table table-bordered w-100 table-sm">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Foto</th>
                                        <th>Spesifikasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($item['produkspek'] as $produk => $p): ?>
                                    <tr>
                                        <td><?= $produk ?></td>
                                        <td>
                                            <?php
                                                $min = number_format(min($p['harga']),0,',','.');
                                                $max = number_format(max($p['harga']),0,',','.');
                                            ?>
                                            <?php if ($min == $max) : ?>
                                                Rp <?= $min ?>
                                            <?php else: ?>
                                                Rp <?= $min ?>
                                                -
                                                Rp <?= $max ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($p['img'])): ?>
                                                <a href="<?= $p['img'] ?>" target="_BLANK">Image</a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($p['spek'])): ?>
                                                <a href="<?= $p['spek'] ?>" target="_BLANK">Spesifikasi</a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- ===== STOK PRODUK ===== -->
                        <h6 class="fw-semibold mt-4">Stok Produk</h6>
                        <div class="table-responsive sticky-table">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <?php foreach ($item['sizes'] as $size): ?>
                                            <th><?= $size ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                    <tbody>
                                    <?php foreach ($item['produk'] as $produk => $variants): ?>

                                        <!-- BARIS PRODUK -->
                                        <tr class="table-light">
                                            <td class="fw-bold"><?= $produk ?></td>

                                            <?php foreach ($item['sizes'] as $size): ?>
                                                <td>-</td>
                                            <?php endforeach; ?>
                                        </tr>

                                        <!-- BARIS VARIANT -->
                                        <?php foreach ($variants as $variant => $v): ?>
                                        <tr>
                                            <td class="ps-4"><?= $variant ?></td>

                                            <?php foreach ($item['sizes'] as $size): ?>
                                                <td>
                                                    <?= $v['stok'][$size] ?? "-" ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                        <?php endforeach; ?>

                                    <?php endforeach; ?>
                                    </tbody>

                            </table>
                        </div>
                    </div>
                <?php $i++; endforeach; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
