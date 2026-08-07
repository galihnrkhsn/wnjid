<?php
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';
    include '../includes/foto_helper.php';
    include '../includes/promo_badge_helper.php';

    $idproduk = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    $stmtProduk = $koneksi->prepare("SELECT id, namaproduk, idkategori, spek, deskripsi, jenis FROM products WHERE id = ?");
    $stmtProduk->bind_param('i', $idproduk);
    $stmtProduk->execute();
    $produk = $stmtProduk->get_result()->fetch_assoc();

    if (!$produk) {
        header('Location: index.php');
        exit;
    }

    // Label ukuran diambil dari master_size (relasi via size_id), bukan variants.size lagi -
    // urutannya juga ikut master_size.seq, bukan size_id (id cuma urutan input, sudah kebukti
    // salah urut untuk sebagian size, mis. "Vol 3" yang nyempil setelah "Vol 9").
    $stmtVariant = $koneksi->prepare("SELECT v.*, ms.nama_size, ms.kategori AS size_kategori, ms.seq AS size_seq
                                        FROM variants v
                                        LEFT JOIN master_size ms ON ms.id = v.size_id
                                        WHERE v.idproducts = ? AND v.status <> 1
                                        ORDER BY v.variant ASC, ms.seq ASC");
    $stmtVariant->bind_param('i', $idproduk);
    $stmtVariant->execute();
    $variants = $stmtVariant->get_result()->fetch_all(MYSQLI_ASSOC);

    // Ukuran di dalam tiap grup varian urut berdasarkan master_size.seq (S, M, L, XL, ...) -
    // jangan pakai stock buat urutan di sini lagi, ukuran habis cukup ditandai disabled di tombolnya.
    $variantGroups = [];
    foreach ($variants as $v) {
        $variantGroups[$v['variant']][] = $v;
    }

    // Grup varian yang masih ada stok ditaruh duluan, grup yang stoknya habis semua di bawah -
    // urutan di DALAM tiap grup (size_id) tetap tidak berubah, ini cuma menata urutan ANTAR grup.
    uasort($variantGroups, function ($sizesA, $sizesB) {
        $habisA = true;
        foreach ($sizesA as $s) {
            if ($s['stock'] > 0) {
                $habisA = false;
                break;
            }
        }
        $habisB = true;
        foreach ($sizesB as $s) {
            if ($s['stock'] > 0) {
                $habisB = false;
                break;
            }
        }
        return $habisA <=> $habisB;
    });

    // Badge + aturan promo (dikelola admin lewat adminwnj/master_jenis.php) - sekarang atribut
    // produk (products.jenis), bukan diagregasi dari variants lagi.
    $promo = promoInfo($koneksi, $produk['jenis'] ?? null);

    // Varian dengan harga TERMURAH (setelah diskon) di antara yang masih ada stok - dipakai
    // sebagai harga default di harga-card, LENGKAP dengan coret harga asli & badge diskon-nya
    // langsung tanpa perlu pilih varian dulu. Begitu konsumen pilih varian+ukuran, harga-card
    // ikut update ke harga varian yang benar-benar dipilih (lihat updateHargaCard() di JS).
    // Kalau semua stok habis, fallback ke harga terendah dari semua varian.
    $hargaRef = null;
    foreach ([true, false] as $hanyaAdaStok) {
        foreach ($variants as $v) {
            if ($hanyaAdaStok && $v['stock'] <= 0) {
                continue;
            }
            $hargaFinal = $v['disc'] > 0 ? hargaSetelahDisc((int) $v['harga'], (int) $v['disc']) : (int) $v['harga'];
            if ($hargaRef === null || $hargaFinal < $hargaRef['final']) {
                $hargaRef = ['harga' => (int) $v['harga'], 'disc' => (int) $v['disc'], 'hargacoret' => (int) $v['hargacoret'], 'final' => $hargaFinal];
            }
        }
        if ($hargaRef !== null) {
            break;
        }
    }

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
    <title><?= htmlspecialchars($produk['namaproduk']) ?> | WNJ.ID</title>
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
            cursor: zoom-in;
        }
        .gallery-main-wrap {
            position: relative;
        }
        .gallery-zoom-hint {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(0,0,0,.45);
            color: #fff;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            pointer-events: none;
        }
        .promo-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
            background: #dc3545;
            color: #fff;
            font-size: .7rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 4px;
        }
        #previewModal .modal-dialog {
            width: 90vw;
            /* max-width: 92vw; */
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 5vh auto;
        }
        #previewModal .modal-content {
            background: transparent;
            border: none;
            /* width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center; */
        }
        #previewModal img {
            max-width: 100%;
            max-height: 90vh;
            /* width: auto;
            height: auto;
            object-fit: contain; */
            margin: 0 auto;
            border-radius: 8px;
        }
        #previewModal .close {
            position: absolute;
            top: -2.5rem;
            right: 0;
            color: #fff;
            opacity: .9;
            text-shadow: none;
            font-size: 2rem;
        }
        #previewModal .preview-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,.85);
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 4px rgba(0,0,0,.25);
            color: var(--wnj-text);
            font-size: 1.2rem;
        }
        #previewModal .preview-nav:hover {
            background: #fff;
        }
        #previewModal .preview-prev {
            left: -1rem;
        }
        #previewModal .preview-next {
            right: -1rem;
        }
        #previewModal .preview-counter {
            position: absolute;
            bottom: -2rem;
            left: 50%;
            transform: translateX(-50%);
            color: #fff;
            font-size: .85rem;
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
        @media (min-width: 768px) {
            .gallery-thumbs {
                scrollbar-width: none;
            }
            .gallery-thumbs::-webkit-scrollbar {
                display: none;
            }
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
        .harga-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1rem 1.25rem;
            margin-top: .75rem;
        }
        .promo-rule {
            margin-top: .5rem;
            padding-top: .5rem;
            border-top: 1px solid var(--wnj-border);
            font-size: .8rem;
            color: var(--wnj-text-secondary);
        }
        .pilihan-group {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
        }
        .pilihan-btn {
            border: 1px solid var(--wnj-border);
            background: #fff;
            color: var(--wnj-text);
            border-radius: 8px;
            padding: .45rem 1rem;
            font-size: .85rem;
            cursor: pointer;
        }
        .pilihan-btn:hover {
            border-color: var(--wnj-cta);
        }
        .pilihan-btn.active {
            border-color: var(--wnj-cta);
            background: var(--wnj-cta);
            color: #fff;
            font-weight: 600;
        }
        .pilihan-btn.disabled {
            opacity: .4;
            cursor: not-allowed;
            text-decoration: line-through;
        }
        .pilihan-btn.disabled:hover {
            border-color: var(--wnj-border);
        }
        .qty-stepper {
            display: inline-flex;
            align-items: center;
            border: 1px solid var(--wnj-border);
            border-radius: 8px;
            overflow: hidden;
        }
        .qty-btn {
            border: none;
            background: var(--wnj-bg-soft);
            color: var(--wnj-text);
            width: 38px;
            height: 38px;
            font-size: 1.1rem;
            line-height: 1;
            cursor: pointer;
        }
        .qty-btn:disabled {
            opacity: .4;
            cursor: not-allowed;
        }
        .qty-input {
            width: 56px;
            height: 38px;
            border: none;
            border-left: 1px solid var(--wnj-border);
            border-right: 1px solid var(--wnj-border);
            text-align: center;
            font-weight: 600;
            -moz-appearance: textfield;
        }
        .qty-input::-webkit-outer-spin-button,
        .qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
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
        .btn-tambah:disabled {
            background: var(--wnj-bg-soft) !important;
            border-color: var(--wnj-border) !important;
            color: var(--wnj-text-tertiary) !important;
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
                    <img id="galleryMain" class="produk-foto" src="<?= htmlspecialchars($fotoList[0]) ?>" alt="<?= htmlspecialchars($produk['namaproduk']) ?>" data-toggle="modal" data-target="#previewModal">
                    <?php if ($promo !== null): ?>
                        <span class="promo-badge"><?= htmlspecialchars($promo['nama']) ?></span>
                    <?php endif; ?>
                    <span class="gallery-zoom-hint"><i class="bi bi-zoom-in"></i></span>
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

                <?php if (!empty($variants)): ?>
                    <?php $adaDisc = $hargaRef['disc'] > 0; ?>
                    <?php $adaCoret = !$adaDisc && $hargaRef['hargacoret'] > $hargaRef['harga']; ?>
                    <div class="harga-card">
                        <div>
                            <span class="variant-price-old" id="hargaCoretValue" style="<?= ($adaDisc || $adaCoret) ? '' : 'display:none;' ?>">
                                Rp <?= number_format($adaDisc ? $hargaRef['harga'] : $hargaRef['hargacoret']) ?>
                            </span>
                            <span class="disc-badge-inline" id="hargaDiscBadge" style="<?= $adaDisc ? '' : 'display:none;' ?>">-<?= (int) $hargaRef['disc'] ?>%</span>
                            <br>
                            <span class="h4 font-weight-bold mb-0" id="hargaValue">Rp <?= number_format($hargaRef['final']) ?></span>
                        </div>
                        <?php if ($promo !== null && !empty($promo['deskripsi'])): ?>
                            <div class="promo-rule">
                                <i class="bi bi-info-circle"></i> <?= htmlspecialchars($promo['deskripsi']) ?>
                            </div>
                        <?php endif; ?>
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
                        <form method="post" action="add_chart.php" id="variantForm">
                            <?= csrfField() ?>
                            <input type="hidden" name="pid" value="<?= (int) $produk['id'] ?>">
                            <input type="hidden" name="id" id="selectedVariantId" value="">

                            <label class="mb-2 font-weight-bold">Pilih Varian</label>
                            <div class="pilihan-group mb-3" id="variantButtons">
                                <?php foreach ($variantGroups as $namaVarian => $sizes): ?>
                                    <?php
                                        $groupHabis = true;
                                        foreach ($sizes as $s) {
                                            if ($s['stock'] > 0) {
                                                $groupHabis = false;
                                                break;
                                            }
                                        }
                                    ?>
                                    <button type="button"
                                            class="pilihan-btn<?= $groupHabis ? ' disabled' : '' ?>"
                                            data-variant="<?= htmlspecialchars($namaVarian) ?>"
                                            <?= $groupHabis ? 'disabled' : '' ?>>
                                        <?= htmlspecialchars($namaVarian) ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>

                            <div id="sizeSection" style="display:none;">
                                <label class="mb-2 font-weight-bold">Pilih Ukuran</label>
                                <div class="pilihan-group mb-3" id="sizeButtons"></div>
                            </div>

                            <div id="stokInfo" class="text-muted small mb-2" style="display:none;"></div>

                            <div id="qtySection" class="mb-3" style="display:none;">
                                <label class="mb-2 font-weight-bold">Jumlah</label>
                                <div class="qty-stepper">
                                    <button type="button" class="qty-btn" id="qtyMinus" aria-label="Kurangi">&minus;</button>
                                    <input type="number" name="qty" id="qtyInput" class="qty-input" value="1" min="1" max="1" inputmode="numeric">
                                    <button type="button" class="qty-btn" id="qtyPlus" aria-label="Tambah">+</button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-tambah mt-3" id="btnTambah" disabled>
                                <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (!empty($produk['deskripsi'])): ?>
            <div class="row">
                <div class="col-12">
                    <div class="deskripsi-accordion">
                        <button type="button" class="deskripsi-toggle" onclick="toggleDeskripsi(this)">
                            <span>Deskripsi Produk</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="deskripsi-content"><?= htmlspecialchars($produk['deskripsi']) ?></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                <img id="previewImage" src="" alt="<?= htmlspecialchars($produk['namaproduk']) ?>">
                <?php if (count($fotoList) > 1): ?>
                    <button type="button" class="preview-nav preview-prev" onclick="previewMove(-1)" aria-label="Sebelumnya">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button type="button" class="preview-nav preview-next" onclick="previewMove(1)" aria-label="Selanjutnya">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    <div class="preview-counter"><span id="previewCounter">1</span> / <?= count($fotoList) ?></div>
                <?php endif; ?>
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

        var VARIANT_GROUPS  = <?= json_encode($variantGroups) ?>;
        var HARGA_REF       = <?= json_encode($hargaRef) ?>;

        function formatRupiah(n) {
            return 'Rp ' + Number(n).toLocaleString('id-ID');
        }

        // Balik ke harga varian termurah (harga-card default) - dipakai HARGA_REF supaya
        // hasilnya persis sama dengan yang di-render server pertama kali (koret+badge ikutan).
        function resetHargaCard() {
            updateHargaCard(HARGA_REF);
        }

        function updateHargaCard(row) {
            var hargaCoret = document.getElementById('hargaCoretValue');
            var discBadge  = document.getElementById('hargaDiscBadge');

            if (row.disc > 0) {
                var hargaFinal = Math.round(row.harga * (1 - row.disc / 100));
                document.getElementById('hargaValue').textContent = formatRupiah(hargaFinal);
                hargaCoret.textContent = formatRupiah(row.harga);
                hargaCoret.style.display = '';
                discBadge.textContent = '-' + row.disc + '%';
                discBadge.style.display = '';
            } else {
                document.getElementById('hargaValue').textContent = formatRupiah(row.harga);
                discBadge.style.display = 'none';
                if (row.hargacoret > 0) {
                    hargaCoret.textContent = formatRupiah(row.hargacoret);
                    hargaCoret.style.display = '';
                } else {
                    hargaCoret.style.display = 'none';
                }
            }
        }

        function setQty(value) {
            var input = document.getElementById('qtyInput');
            var max   = parseInt(input.max, 10) || 1;
            value     = Math.max(1, Math.min(max, parseInt(value, 10) || 1));
            input.value = value;
            document.getElementById('qtyMinus').disabled = value <= 1;
            document.getElementById('qtyPlus').disabled  = value >= max;
        }

        function pilihUkuran(row, btn) {
            document.querySelectorAll('#sizeButtons .pilihan-btn').forEach(function (el) {
                el.classList.remove('active');
            });
            btn.classList.add('active');

            document.getElementById('selectedVariantId').value = row.id;
            document.getElementById('btnTambah').disabled = false;
            updateHargaCard(row);

            document.getElementById('stokInfo').textContent = 'Stok tersedia: ' + row.stock;
            document.getElementById('stokInfo').style.display = '';

            document.getElementById('qtyInput').max = row.stock;
            document.getElementById('qtySection').style.display = '';
            setQty(1);
        }

        function pilihVarian(namaVarian, btn) {
            document.querySelectorAll('#variantButtons .pilihan-btn').forEach(function (el) {
                el.classList.remove('active');
            });
            btn.classList.add('active');

            document.getElementById('selectedVariantId').value = '';
            document.getElementById('btnTambah').disabled = true;
            resetHargaCard();

            document.getElementById('stokInfo').style.display = 'none';
            document.getElementById('qtySection').style.display = 'none';

            var sizeButtons = document.getElementById('sizeButtons');
            sizeButtons.innerHTML = '';
            VARIANT_GROUPS[namaVarian].forEach(function (row) {
                var habis = row.stock <= 0;
                var b = document.createElement('button');
                b.type = 'button';
                b.className = 'pilihan-btn' + (habis ? ' disabled' : '');
                b.textContent = row.nama_size || row.size;
                if (habis) {
                    b.disabled = true;
                } else {
                    b.addEventListener('click', function () { pilihUkuran(row, b); });
                }
                sizeButtons.appendChild(b);
            });

            document.getElementById('sizeSection').style.display = '';
        }

        document.querySelectorAll('#variantButtons .pilihan-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                pilihVarian(btn.dataset.variant, btn);
            });
        });

        document.getElementById('qtyMinus').addEventListener('click', function () {
            setQty(parseInt(document.getElementById('qtyInput').value, 10) - 1);
        });
        document.getElementById('qtyPlus').addEventListener('click', function () {
            setQty(parseInt(document.getElementById('qtyInput').value, 10) + 1);
        });
        document.getElementById('qtyInput').addEventListener('change', function () {
            setQty(this.value);
        });
    </script>
    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function updatePreviewImage() {
            document.getElementById('previewImage').src = galleryFotos[galleryIndex];
            var counter = document.getElementById('previewCounter');
            if (counter) counter.textContent = galleryIndex + 1;
        }

        function previewMove(delta) {
            galleryMove(delta);
            updatePreviewImage();
        }

        $('#previewModal').on('show.bs.modal', updatePreviewImage);

        $(document).on('keydown', function (e) {
            if (!$('#previewModal').hasClass('show')) return;
            if (e.key === 'ArrowLeft') previewMove(-1);
            if (e.key === 'ArrowRight') previewMove(1);
        });
    </script>
</body>
</html>
