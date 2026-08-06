<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';
    include '../includes/foto_helper.php';
    include '../includes/promo_badge_helper.php';

    $idproduk = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    $stmtProduk = $koneksi->prepare("SELECT id, namaproduk, idkategori, spek, deskripsi FROM products WHERE id = ?");
    $stmtProduk->bind_param('i', $idproduk);
    $stmtProduk->execute();
    $produk = $stmtProduk->get_result()->fetch_assoc();

    if (!$produk) {
        header('Location: index.php');
        exit;
    }

    $stmtVariant = $koneksi->prepare("SELECT v.*
                                        FROM variants v
                                        WHERE v.idproducts = ? AND v.status <> 1
                                        ORDER BY (v.stock > 0) DESC, v.variant ASC, v.size ASC");
    $stmtVariant->bind_param('i', $idproduk);
    $stmtVariant->execute();
    $variants = $stmtVariant->get_result()->fetch_all(MYSQLI_ASSOC);

    // Galeri foto produk: satu-satunya jalan untuk ambil foto sekarang lewat foto_produk,
    // tidak lagi turun ke tabel variants.
    $stmtFoto = $koneksi->prepare("SELECT fp.foto, mf.name AS nama_folder
                                    FROM foto_produk fp
                                    LEFT JOIN master_folder mf ON fp.folder = mf.id
                                    WHERE fp.idproduk = ?
                                    ORDER BY fp.urutan ASC, fp.id ASC");
    $stmtFoto->bind_param('i', $idproduk);
    $stmtFoto->execute();
    $fotoRows = $stmtFoto->get_result()->fetch_all(MYSQLI_ASSOC);

    $fotoList = [];
    foreach ($fotoRows as $f) {
        $src = fotoProdukSrc($f['nama_folder'] ?? null, $f['foto'] ?? null);
        if ($src !== '../image/produk/nophoto.png' && !in_array($src, $fotoList, true)) {
            $fotoList[] = $src;
        }
    }
    if (empty($fotoList)) {
        $fotoList[] = '../image/produk/nophoto.png';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= htmlspecialchars($produk['namaproduk']) ?> | Wanoja</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: var(--wnj-bg);
        }
        .back-link {
            color: var(--wnj-text);
            font-weight: 600;
        }
        .produk-foto {
            width: 100%;
            max-height: 420px;
            object-fit: cover;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
        }
        .gallery-main-wrap {
            position: relative;
        }
        .gallery-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,.85);
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 4px rgba(0,0,0,.15);
            color: var(--wnj-text);
            font-size: 1.1rem;
        }
        .gallery-nav:hover {
            background: #fff;
        }
        .gallery-prev {
            left: 10px;
        }
        .gallery-next {
            right: 10px;
        }
        .gallery-thumbs {
            display: flex;
            gap: .5rem;
            margin-top: .6rem;
            overflow-x: auto;
        }
        .gallery-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid transparent;
            cursor: pointer;
            flex-shrink: 0;
        }
        .gallery-thumb.active {
            border-color: var(--wnj-cta);
        }
        .deskripsi-accordion {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            margin-top: .75rem;
            overflow: hidden;
        }
        .deskripsi-toggle {
            width: 100%;
            background: none;
            border: none;
            padding: .75rem 1rem;
            font-size: .9rem;
            font-weight: 700;
            color: var(--wnj-text);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .deskripsi-toggle .bi {
            transition: transform .15s ease-in-out;
        }
        .deskripsi-toggle.open .bi {
            transform: rotate(180deg);
        }
        .deskripsi-content {
            display: none;
            padding: 0 1rem 1rem;
            font-size: .85rem;
            color: var(--wnj-text-secondary);
            white-space: pre-line;
        }
        .deskripsi-content.open {
            display: block;
        }
        .produk-panel {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.25rem;
        }
        .variant-option {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: .5rem .75rem;
            padding: .75rem;
            border: 1px solid var(--wnj-border);
            border-radius: 10px;
            margin-bottom: .5rem;
            cursor: pointer;
        }
        .variant-option:hover {
            border-color: var(--wnj-cta);
            background: var(--wnj-bg-soft);
        }
        .variant-option.disabled {
            opacity: .5;
            cursor: not-allowed;
        }
        .variant-option input[type="radio"] {
            margin-right: .5rem;
        }
        .variant-price {
            font-weight: 700;
            color: var(--wnj-text);
        }
        .variant-price-old {
            color: var(--wnj-text-tertiary);
            text-decoration: line-through;
            font-size: .8rem;
            margin-right: .5rem;
        }
        .disc-badge-inline {
            display: inline-block;
            background: #dc3545;
            color: #fff;
            font-size: .65rem;
            font-weight: 600;
            padding: 1px 6px;
            border-radius: 4px;
            margin-left: .35rem;
            vertical-align: middle;
        }
        .btn-tambah {
            border-radius: 10px;
            padding: .7rem;
            font-weight: 600;
        }
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: var(--wnj-text-secondary);
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container my-3">
        <a href="index.php" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke Belanja</a>

        <div class="row mt-3">
            <div class="col-md-5 mb-3">
                <div class="gallery-main-wrap">
                    <img id="galleryMain" class="produk-foto" src="<?= htmlspecialchars($fotoList[0]) ?>" alt="<?= htmlspecialchars($produk['namaproduk']) ?>">
                    <?php if (count($fotoList) > 1): ?>
                        <button type="button" class="gallery-nav gallery-prev" onclick="galleryMove(-1)" aria-label="Sebelumnya">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="gallery-nav gallery-next" onclick="galleryMove(1)" aria-label="Selanjutnya">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    <?php endif; ?>
                </div>
                <?php if (count($fotoList) > 1): ?>
                    <div class="gallery-thumbs">
                        <?php foreach ($fotoList as $i => $foto): ?>
                            <img class="gallery-thumb<?= $i === 0 ? ' active' : '' ?>" src="<?= htmlspecialchars($foto) ?>" onclick="gallerySet(<?= $i ?>)" alt="Foto <?= $i + 1 ?>">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($produk['deskripsi'])): ?>
                    <div class="deskripsi-accordion">
                        <button type="button" class="deskripsi-toggle" onclick="toggleDeskripsi(this)">
                            <span>Deskripsi Produk</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="deskripsi-content"><?= htmlspecialchars($produk['deskripsi']) ?></div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-7">
                <div class="produk-panel">
                    <h5 class="font-weight-bold mb-1"><?= htmlspecialchars($produk['namaproduk']) ?></h5>
                    <?php if (empty($variants)): ?>
                        <div class="empty-state">
                            <i class="bi bi-box-seam" style="font-size:2rem;"></i>
                            <p class="mt-2 mb-0">Produk ini sedang tidak tersedia.</p>
                        </div>
                    <?php elseif ($produk['idkategori'] >= 51): ?>
                        <p class="text-muted">Hubungi kami untuk informasi harga &amp; ketersediaan produk ini.</p>
                    <?php else: ?>
                        <form method="get" action="add_chart.php">
                            <input type="hidden" name="pid" value="<?= (int) $produk['id'] ?>">
                            <label class="mb-2 font-weight-bold">Pilih Varian</label>

                            <?php foreach ($variants as $v): ?>
                                <?php $habis = $v['stock'] <= 0; ?>
                                <label class="variant-option<?= $habis ? ' disabled' : '' ?>">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="id" value="<?= (int) $v['id'] ?>" <?= $habis ? 'disabled' : '' ?> required>
                                        <div>
                                            <div><?= htmlspecialchars($v['variant']) ?> <?= htmlspecialchars($v['size'] ?? '') ?></div>
                                            <div class="text-muted small"><?= $habis ? 'Stok habis' : 'Stok ' . (int) $v['stock'] ?></div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <?php if ($v['disc'] > 0): ?>
                                            <span class="variant-price-old">Rp <?= number_format($v['harga']) ?></span>
                                            <span class="variant-price">Rp <?= number_format(hargaSetelahDisc((int) $v['harga'], (int) $v['disc'])) ?></span>
                                            <span class="disc-badge-inline"><?= htmlspecialchars(discBadgeLabel((int) $v['disc'])) ?></span>
                                        <?php else: ?>
                                            <?php if ($v['hargacoret'] > 0): ?>
                                                <span class="variant-price-old">Rp <?= number_format($v['hargacoret']) ?></span>
                                            <?php endif; ?>
                                            <span class="variant-price">Rp <?= number_format($v['harga']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </label>
                            <?php endforeach; ?>

                            <button type="submit" class="btn btn-primary btn-block btn-tambah mt-3">
                                <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        var galleryFotos = <?= json_encode($fotoList) ?>;
        var galleryIndex = 0;

        function gallerySet(i) {
            galleryIndex = i;
            document.getElementById('galleryMain').src = galleryFotos[galleryIndex];
            document.querySelectorAll('.gallery-thumb').forEach(function (el, idx) {
                el.classList.toggle('active', idx === galleryIndex);
            });
        }

        function galleryMove(delta) {
            var len = galleryFotos.length;
            gallerySet((galleryIndex + delta + len) % len);
        }

        function toggleDeskripsi(btn) {
            var content = btn.parentElement.querySelector('.deskripsi-content');
            var isOpen  = content.classList.toggle('open');
            btn.classList.toggle('open', isOpen);
        }
    </script>
    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
