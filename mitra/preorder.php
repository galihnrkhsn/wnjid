<?php
    include 'koneksi.php';
    include 'session_guard.php';

    // Kampanye PO yang lagi dibuka - "Semua Mitra" selalu tampil, ditambah yang
    // memang ditargetkan spesifik ke role mitra yang sedang login.
    $stmtOpenPo = $koneksi->prepare("SELECT bukapo.idbpo, bukapo.jenis_mitra, bukapo.jenis_po, bukapo.idpoproduk,
                                            bukapo.tgl, bukapo.tgl_dropship, bukapo.status, poproduk.namapo
                                        FROM bukapo
                                        INNER JOIN poproduk ON bukapo.idpoproduk = poproduk.idpoproduk
                                        WHERE bukapo.status = 'PUBLISH'
                                          AND (bukapo.jenis_mitra = 'Semua Mitra' OR bukapo.jenis_mitra = ?)
                                        ORDER BY bukapo.tgl DESC");
    $stmtOpenPo->bind_param('s', $mitraCfg['label']);
    $stmtOpenPo->execute();
    $openPoList = $stmtOpenPo->get_result()->fetch_all(MYSQLI_ASSOC);

    // Rute ke form tiap jenis PO - form-nya sendiri belum dipindah dari distributor/,
    // jadi sengaja diarahkan ke sana dulu selama migrasi bertahap ke mitra/ berjalan.
    $jenisPoRoutes = [
        'PO dengan Stok'         => 'formpostok.php',
        'PO tanpa Stok'          => 'formpoku.php',
        'PO Custom Tab'          => 'formpo_tab.php',
        'PO Custom Tab Stok'     => 'formpo_tabstok.php',
        'PO Custom Tab Stok Max' => 'formpo_tabstokmax.php',
        'PO Konin'               => 'pokonin.php.php',
        'PO Kolibri'             => 'pokolibri.php',
        'PO Miki Custom'         => 'formpomikicustom.php',
        'PO Miki Polos'          => 'formpomikipolos.php',
        'PO Brooch Custom'       => 'formpobrooch_custom.php',
        'PO Bagi Rata'           => 'formbagirata.php',
        'PO Hampers'             => 'formpo_thr.php',
        'PO Karakter Stok'       => 'formpo_karakterstok.php',
        'PO Custom Inner'        => 'formpoinner_custom.php',
        'PO custom Rocela'       => 'formporocela.php',
        'PO custom Goura'        => 'formpogoura.php',
        'PO custom Bundling'     => 'formpocustomgabungan.php',
        // Bundling 2/5/Custom sekarang 1 file dinamis (formpobundling.php), konfigurasi
        // per produk dibaca dari tabel bukapo_bundling_config/bukapo_bundling_pool.
        'PO Bundling 2'          => 'formpobundling.php',
        'PO Tab tanpa stok'      => 'formpobundling.php',
        'PO Bundling Custom'     => 'formpobundling.php',
        'PO Ducula Stok'         => 'formpoducula.php',
        'PO Tazmahal'            => 'formpotazmahal.php',
        'PO Bundling 5'          => 'formpobundling.php',
        'PO Set'                 => 'formpobundlingset.php',
        'PO Mandiri'             => 'formpo_mandiri.php',
        'PO Custom'              => 'pocustom.php',
        'PO Custom Inisial'      => 'formpocustom.php',
        'PO Custom Template'     => 'formpocustomtemplate.php',
    ];
    $produkSpecial = [
        '328' => 'pocustom.php', '331' => 'pocustom.php',
        '260' => 'formpocustomlegging', '261' => 'formpocustomlegging2',
        '269' => 'formpomatari', '289' => 'formposongkok.php',
        '299' => 'formpovoal_custom.php', '301' => 'formpovoal_custom.php',
        '315' => 'formpocustom.php', '506' => 'formpocustom.php',
        '307' => 'formpoinner.php', '314' => 'formpoinner.php',
        '317' => 'formpoinner.php', '325' => 'formpoinner.php',
        '339' => 'formpoinner.php', '335' => 'formpoinner2.php',
    ];

    // File yang sudah dimigrasi & direfactor ke mitra/ - linknya tidak lagi diarahkan
    // ke ../distributor/, sisanya (belum dimigrasi) masih ke sana dulu.
    $formSudahDimigrasi = [
        'formpostok.php', 'formpoku.php', 'formpo_tab.php', 'formpo_tabstok.php', 'formpobundling.php',
    ];

    // Riwayat PO reguler - punya mitra ini sendiri, plus downline di bawahnya kalau
    // role-nya memang bisa punya downline (distributor/agen - lihat mitra_role_helper.php).
    switch ($mitraRole) {
        case 'distributor':
            $sqlKondisi  = "p.idmitra = ?
                            OR p.idmitraagen IN (SELECT idmitraagen FROM mitraagen WHERE idadmin = ?)
                            OR p.idmitrareseller IN (SELECT idmitrareseller FROM mitrareseller WHERE idadmin = ?)
                            OR p.idmitramarketer IN (SELECT idmitramarketer FROM mitramarketer WHERE idadmin = ?)";
            $paramTypes  = 'iiii';
            $paramValues = [$idMitra, $idMitra, $idMitra, $idMitra];
            break;
        case 'agen':
            $sqlKondisi  = "p.idmitraagen = ?
                            OR p.idmitrareseller IN (SELECT idmitrareseller FROM mitrareseller WHERE idmitraagen = ?)
                            OR p.idmitramarketer IN (SELECT idmitramarketer FROM mitramarketer WHERE idmitraagen = ?)";
            $paramTypes  = 'iii';
            $paramValues = [$idMitra, $idMitra, $idMitra];
            break;
        case 'reseller':
            $sqlKondisi  = "p.idmitrareseller = ?";
            $paramTypes  = 'i';
            $paramValues = [$idMitra];
            break;
        default: // marketer
            $sqlKondisi  = "p.idmitramarketer = ?";
            $paramTypes  = 'i';
            $paramValues = [$idMitra];
    }

    $stmtRegular = $koneksi->prepare("SELECT x.tgl, pr.namapo, pr.idpoproduk, pr.jenis, bo.jenis_po
                                        FROM (
                                            SELECT p.idpoproduk, MAX(p.tgl) AS tgl
                                            FROM pomitra p
                                            WHERE $sqlKondisi
                                            GROUP BY p.idpoproduk
                                        ) x
                                        JOIN poproduk pr ON pr.idpoproduk = x.idpoproduk
                                        LEFT JOIN bukapo bo ON bo.idpoproduk = pr.idpoproduk
                                        WHERE pr.status = 'open' AND pr.tipe = 'Normal' AND pr.idpoproduk > 233
                                        ORDER BY x.tgl DESC");
    $stmtRegular->bind_param($paramTypes, ...$paramValues);
    $stmtRegular->execute();
    $poRegularList = $stmtRegular->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pre-Order | WNJ.ID</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: var(--wnj-bg); }
        .section-heading {
            font-weight: 700;
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: var(--wnj-text-secondary);
            margin: 1.5rem 0 .75rem;
        }
        .po-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1rem 1.1rem;
            margin-bottom: .75rem;
            display: block;
            text-decoration: none;
            color: var(--wnj-text);
        }
        .po-card:hover, .po-card:focus {
            color: var(--wnj-text);
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(0,0,0,.1);
        }
        .po-card .nama {
            font-weight: 700;
            margin-bottom: .3rem;
        }
        .po-card .countdown {
            font-size: .78rem;
            color: var(--wnj-cta);
            font-weight: 600;
        }
        .po-card .countdown.habis {
            color: var(--wnj-text-tertiary);
        }
        .po-regular-row {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
            padding: .8rem 1rem;
            margin-bottom: .6rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .75rem;
        }
        .po-regular-row a {
            color: var(--wnj-text);
            font-weight: 600;
            text-decoration: none;
        }
        .po-regular-row a:hover {
            color: var(--wnj-cta);
        }
        .po-regular-row .tgl {
            font-size: .72rem;
            color: var(--wnj-text-secondary);
            white-space: nowrap;
        }
        .empty-state {
            padding: 2.5rem 1rem;
            text-align: center;
            color: var(--wnj-text-secondary);
        }
        .empty-state i {
            font-size: 2rem;
            display: block;
            margin-bottom: .5rem;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 720px;">
        <div class="d-flex align-items-center mt-3 mb-1" style="gap:.75rem;">
            <a href="index.php" class="text-muted"><i class="bi bi-arrow-left"></i></a>
            <h5 class="font-weight-bold mb-0">Pre-Order</h5>
        </div>

        <div class="section-heading">PO Sedang Dibuka</div>
        <?php if (empty($openPoList)): ?>
            <div class="empty-state">
                <i class="bi bi-calendar-x"></i>
                Belum ada PO yang sedang dibuka saat ini.
            </div>
        <?php else: ?>
            <?php foreach ($openPoList as $po): ?>
                <?php
                    $idpo = $po['idpoproduk'];
                    $link = '#';
                    if (isset($jenisPoRoutes[$po['jenis_po']])) {
                        $target = $jenisPoRoutes[$po['jenis_po']];
                        $prefix = in_array($target, $formSudahDimigrasi, true) ? '' : '../distributor/';
                        $link   = $prefix . $target . '?id=' . $idpo;
                    } elseif (isset($produkSpecial[$idpo])) {
                        $target = $produkSpecial[$idpo];
                        $prefix = in_array($target, $formSudahDimigrasi, true) ? '' : '../distributor/';
                        $link   = $prefix . $target . '?id=' . $idpo;
                        if (in_array($idpo, ['260', '261'], true)) {
                            $link .= '&idadmin=' . urlencode($idAdminInduk);
                        }
                    }
                ?>
                <a href="<?= htmlspecialchars($link) ?>" class="po-card" id="linkpo<?= (int) $po['idbpo'] ?>">
                    <div class="nama"><?= htmlspecialchars($po['namapo']) ?></div>
                    <div class="countdown" id="countdown<?= (int) $po['idbpo'] ?>" data-countdown-target="<?= htmlspecialchars($po['tgl']) ?> 23:59:00" data-link="linkpo<?= (int) $po['idbpo'] ?>"></div>
                </a>
            <?php endforeach; ?>
            <script>
                (function () {
                    var items = Array.prototype.map.call(document.querySelectorAll('.countdown[data-countdown-target]'), function (el) {
                        return { el: el, target: new Date(el.dataset.countdownTarget).getTime(), link: document.getElementById(el.dataset.link) };
                    });
                    if (!items.length) return;
                    var timer;
                    function tick() {
                        var now = Date.now();
                        items = items.filter(function (item) {
                            var distance = item.target - now;
                            if (distance < 0) {
                                item.el.textContent = 'Link PO tidak tersedia';
                                item.el.classList.add('habis');
                                if (item.link) item.link.style.display = 'none';
                                return false;
                            }
                            var d = Math.floor(distance / 86400000);
                            var h = Math.floor((distance % 86400000) / 3600000);
                            var m = Math.floor((distance % 3600000) / 60000);
                            var s = Math.floor((distance % 60000) / 1000);
                            item.el.textContent = d + 'h ' + h + 'j ' + m + 'm ' + s + 'd lagi';
                            return true;
                        });
                        if (!items.length) clearInterval(timer);
                    }
                    tick();
                    timer = setInterval(tick, 1000);
                })();
            </script>
        <?php endif; ?>

        <div class="section-heading">PO Reguler</div>
        <?php if (empty($poRegularList)): ?>
            <div class="empty-state mb-4">
                <i class="bi bi-inbox"></i>
                Belum ada PO reguler yang tersedia.
            </div>
        <?php else: ?>
            <div class="mb-4">
                <?php foreach ($poRegularList as $data): ?>
                    <?php
                        $idpo = (int) $data['idpoproduk'];
                        if ($data['jenis'] === 'Kolibri') {
                            $detailLink = '../distributor/detailkolibri?id=' . $idpo;
                        } elseif (in_array($data['idpoproduk'], ['259', '267'], true)) {
                            $detailLink = '../distributor/detailkonin?id=' . $idpo;
                        } elseif ($data['jenis_po'] === 'PO Konin') {
                            $detailLink = '../distributor/detailpokonin.php?id=' . $idpo;
                        } elseif ($data['jenis_po'] === 'PO Custom Inisial') {
                            $detailLink = '../distributor/detailpo.php?id=' . $idpo;
                        } else {
                            $detailLink = '../distributor/detailpo?id=' . $idpo;
                        }
                    ?>
                    <div class="po-regular-row">
                        <a href="<?= htmlspecialchars($detailLink) ?>"><?= htmlspecialchars($data['namapo']) ?></a>
                        <span class="tgl"><?= htmlspecialchars($data['tgl']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
