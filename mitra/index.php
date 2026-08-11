<?php
    include 'koneksi.php';
    include 'session_guard.php';

    // Sub-Mitra cuma relevan buat distributor & agen (reseller/marketer tidak
    // punya sub-mitra di bawahnya lagi, lihat skema mitrareseller/mitramarketer).
    $bisaKelolaSubMitra = in_array($mitraRole, ['distributor', 'agen'], true);

    // Banner promosi - sumbernya sama dengan yang dikelola adminwnj/banner.php, filenya
    // disimpan di distributor/assets/img/news/ (folder dipakai bareng, bukan punya mitra/ sendiri).
    $bannerList = $koneksi->query("SELECT foto FROM slider WHERE tipe = 'banner' ORDER BY id ASC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard Mitra | WNJ.ID</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <style>
        body { background: var(--wnj-bg); }

        .promo-slider {
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            margin-top: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
        }
        .promo-slider-track {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .promo-slider-track::-webkit-scrollbar {
            display: none;
        }
        .promo-slider-track img {
            flex: 0 0 100%;
            width: 100%;
            aspect-ratio: 16 / 7;
            object-fit: cover;
            scroll-snap-align: start;
        }
        .promo-slider-dots {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 6px;
        }
        .promo-slider-dots span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(255,255,255,.6);
            transition: background .2s, width .2s;
        }
        .promo-slider-dots span.active {
            background: #fff;
            width: 18px;
            border-radius: 3px;
        }

        .greeting-card {
            background: linear-gradient(135deg, var(--wnj-cta) 0%, var(--wnj-cta-hover) 100%);
            border-radius: 16px;
            padding: 1.5rem;
            color: #fff;
            margin: 1rem 0;
            box-shadow: 0 4px 16px rgba(198,124,78,.25);
        }
        .greeting-card .nama {
            font-size: 1.1rem;
            font-weight: 700;
        }
        .greeting-card .diskon-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: rgba(255,255,255,.2);
            border-radius: 999px;
            padding: .3rem .8rem;
            font-size: .8rem;
            font-weight: 600;
            margin-top: .6rem;
        }

        .quick-action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .85rem;
            margin-bottom: 1.25rem;
        }
        .quick-action-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.25rem 1rem;
            text-align: center;
            text-decoration: none;
            color: var(--wnj-text);
            display: block;
            transition: transform .12s ease, box-shadow .12s ease;
        }
        .quick-action-card:hover,
        .quick-action-card:focus {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,.1);
            color: var(--wnj-text);
            text-decoration: none;
        }
        .quick-action-card i {
            font-size: 2rem;
            color: var(--wnj-cta);
            margin-bottom: .5rem;
            display: block;
        }
        .quick-action-card .label {
            font-weight: 700;
            font-size: .95rem;
        }
        .quick-action-card .sub {
            font-size: .72rem;
            color: var(--wnj-text-secondary);
        }

        .menu-section-heading {
            font-weight: 700;
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: var(--wnj-text-secondary);
            margin: 0 0 .75rem;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: .6rem;
            margin-bottom: 1.5rem;
        }
        @media (max-width: 400px) {
            .menu-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        .menu-grid-item {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
            padding: .9rem .5rem;
            text-align: center;
            text-decoration: none;
            color: var(--wnj-text);
            display: block;
        }
        .menu-grid-item:hover,
        .menu-grid-item:focus {
            color: var(--wnj-text);
            text-decoration: none;
            background: var(--wnj-bg-soft);
        }
        .menu-grid-item i {
            font-size: 1.4rem;
            color: var(--wnj-cta);
            display: block;
            margin-bottom: .4rem;
        }
        .menu-grid-item .label {
            font-size: .7rem;
            font-weight: 600;
            line-height: 1.2;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 720px;">

        <?php if (!empty($bannerList)): ?>
            <div class="promo-slider">
                <div class="promo-slider-track" id="promoSliderTrack">
                    <?php foreach ($bannerList as $i => $banner): ?>
                        <img src="../distributor/assets/img/news/<?= htmlspecialchars($banner['foto']) ?>" alt="Promo">
                    <?php endforeach; ?>
                </div>
                <?php if (count($bannerList) > 1): ?>
                    <div class="promo-slider-dots" id="promoSliderDots">
                        <?php foreach ($bannerList as $i => $banner): ?>
                            <span class="<?= $i === 0 ? 'active' : '' ?>"></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="greeting-card">
            <div class="nama">Halo, <?= htmlspecialchars($namaMitra ?: $mitraCfg['label']) ?> 👋</div>
            <div class="small" style="opacity:.9;">Selamat datang kembali di dashboard mitra WNJ.ID</div>
            <div class="diskon-pill"><i class="bi bi-percent"></i> Diskon <?= $diskonPersen ?>% (<?= htmlspecialchars($mitraCfg['label']) ?>)</div>
        </div>

        <div class="quick-action-grid">
            <a href="preorder.php" class="quick-action-card">
                <i class="bi bi-bag-plus"></i>
                <div class="label">Pre-Order</div>
                <div class="sub">Pesan produk yang belum ready stock</div>
            </a>
            <a href="store.php" class="quick-action-card">
                <i class="bi bi-shop"></i>
                <div class="label">Ready Stock</div>
                <div class="sub">Belanja produk siap kirim sekarang</div>
            </a>
        </div>

        <div class="menu-section-heading">Menu Lainnya</div>
        <div class="menu-grid">
            <?php if ($bisaKelolaSubMitra): ?>
                <a href="submitra.php" class="menu-grid-item">
                    <i class="bi bi-diagram-3"></i>
                    <div class="label">Sub-Mitra</div>
                </a>
            <?php endif; ?>
            <a href="pricelist.php" class="menu-grid-item">
                <i class="bi bi-tags"></i>
                <div class="label">Pricelist</div>
            </a>
            <a href="resi.php" class="menu-grid-item">
                <i class="bi bi-truck"></i>
                <div class="label">Resi</div>
            </a>
            <a href="https://wlink.id/katalog" target="_blank" rel="noopener" class="menu-grid-item">
                <i class="bi bi-book"></i>
                <div class="label">Katalog</div>
            </a>
            <a href="https://t.me/joinchat/HI-N0pw7f3rlIxKe" target="_blank" rel="noopener" class="menu-grid-item">
                <i class="bi bi-megaphone"></i>
                <div class="label">Nyabar</div>
            </a>
            <a href="https://t.me/wnjzizazu_supportcenter" target="_blank" rel="noopener" class="menu-grid-item">
                <i class="bi bi-headset"></i>
                <div class="label">Support</div>
            </a>
        </div>

    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            var track = document.getElementById('promoSliderTrack');
            var dots  = document.getElementById('promoSliderDots');
            if (!track || !dots) {
                return;
            }
            var dotEls = dots.querySelectorAll('span');

            function setActiveDot() {
                var index = Math.round(track.scrollLeft / track.clientWidth);
                dotEls.forEach(function (dot, i) {
                    dot.classList.toggle('active', i === index);
                });
            }
            track.addEventListener('scroll', setActiveDot);

            setInterval(function () {
                var next = Math.round(track.scrollLeft / track.clientWidth) + 1;
                if (next >= dotEls.length) {
                    next = 0;
                }
                track.scrollTo({ left: next * track.clientWidth, behavior: 'smooth' });
            }, 4000);
        })();
    </script>
</body>
</html>
