<?php
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';

    $tentangKami = $koneksi->query("SELECT konten FROM konten_web WHERE kunci = 'tentang_kami'")->fetch_assoc();
    $faqList     = $koneksi->query("SELECT pertanyaan, jawaban FROM faq WHERE status = 1 ORDER BY urutan ASC, id ASC")->fetch_all(MYSQLI_ASSOC);
    $promoList   = $koneksi->query("SELECT nama, deskripsi FROM master_jenis_products WHERE nama IS NOT NULL AND nama != '' ORDER BY nama ASC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Bantuan | Wanoja</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: var(--wnj-bg); }
        .panel {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.25rem;
            margin: 1rem 0;
        }
        .panel h6 {
            font-weight: 700;
            margin-bottom: .75rem;
        }
        .tentang-text {
            white-space: pre-line;
            color: var(--wnj-text-secondary);
            font-size: .9rem;
        }
        .promo-item {
            border: 1px solid var(--wnj-border);
            border-radius: 10px;
            padding: .75rem 1rem;
            margin-bottom: .6rem;
        }
        .promo-item:last-child { margin-bottom: 0; }
        .promo-item .nama {
            font-weight: 700;
            color: var(--wnj-cta);
        }
        .promo-item .deskripsi {
            font-size: .85rem;
            color: var(--wnj-text-secondary);
            margin-top: .2rem;
        }
        .faq-item {
            border-bottom: 1px solid var(--wnj-border);
        }
        .faq-item:last-child { border-bottom: none; }
        .faq-toggle {
            width: 100%;
            background: none;
            border: none;
            padding: .85rem 0;
            text-align: left;
            font-weight: 600;
            color: var(--wnj-text);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .faq-toggle .bi {
            transition: transform .15s ease-in-out;
            color: var(--wnj-text-secondary);
            flex-shrink: 0;
            margin-left: .5rem;
        }
        .faq-toggle.open .bi {
            transform: rotate(180deg);
        }
        .faq-answer {
            display: none;
            padding: 0 0 .85rem;
            font-size: .85rem;
            color: var(--wnj-text-secondary);
            white-space: pre-line;
        }
        .faq-answer.open {
            display: block;
        }
        .empty-state {
            padding: 1.5rem 0;
            text-align: center;
            color: var(--wnj-text-secondary);
            font-size: .85rem;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 640px;">
        <div class="d-flex align-items-center mt-3 mb-2" style="gap:.75rem;">
            <a href="profile.php" class="text-muted"><i class="bi bi-arrow-left"></i></a>
            <h5 class="font-weight-bold mb-0">Bantuan</h5>
        </div>

        <?php if (!empty($tentangKami['konten'])): ?>
            <div class="panel">
                <h6><i class="bi bi-shop"></i> Tentang Kami</h6>
                <div class="tentang-text"><?= htmlspecialchars($tentangKami['konten']) ?></div>
            </div>
        <?php endif; ?>

        <div class="panel">
            <h6><i class="bi bi-tags"></i> Promo yang Tersedia</h6>
            <?php if (empty($promoList)): ?>
                <div class="empty-state">Belum ada info promo saat ini.</div>
            <?php else: ?>
                <?php foreach ($promoList as $p): ?>
                    <div class="promo-item">
                        <div class="nama"><?= htmlspecialchars($p['nama']) ?></div>
                        <?php if (!empty($p['deskripsi'])): ?>
                            <div class="deskripsi"><?= htmlspecialchars($p['deskripsi']) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="panel mb-4">
            <h6><i class="bi bi-question-circle"></i> Pertanyaan Umum (FAQ)</h6>
            <?php if (empty($faqList)): ?>
                <div class="empty-state">Belum ada FAQ saat ini.</div>
            <?php else: ?>
                <?php foreach ($faqList as $f): ?>
                    <div class="faq-item">
                        <button type="button" class="faq-toggle" onclick="toggleFaq(this)">
                            <span><?= htmlspecialchars($f['pertanyaan']) ?></span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="faq-answer"><?= htmlspecialchars($f['jawaban']) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleFaq(btn) {
            var answer = btn.nextElementSibling;
            var isOpen = answer.classList.toggle('open');
            btn.classList.toggle('open', isOpen);
        }
    </script>
</body>
</html>
