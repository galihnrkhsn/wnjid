<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesKonsumen.php';
    include '../includes/foto_helper.php';
    include '../includes/promo_badge_helper.php';

    $idKonsumen = $_SESSION['idkonsumen'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
        $idkeranjang = (int) $_POST['hapus'];

        $koneksi->begin_transaction();
        $stmtRow = $koneksi->prepare("SELECT idproduk, jmlh FROM keranjang WHERE idkeranjang = ? AND idkonsumen = ? FOR UPDATE");
        $stmtRow->bind_param('is', $idkeranjang, $idKonsumen);
        $stmtRow->execute();
        $row = $stmtRow->get_result()->fetch_assoc();

        if ($row) {
            $stmtDelete = $koneksi->prepare("DELETE FROM keranjang WHERE idkeranjang = ? AND idkonsumen = ?");
            $stmtDelete->bind_param('is', $idkeranjang, $idKonsumen);
            $stmtDelete->execute();

            // Kembalikan stok karena batal dibeli
            $stmtRestock = $koneksi->prepare("UPDATE variants SET stock = stock + ? WHERE id = ?");
            $stmtRestock->bind_param('ii', $row['jmlh'], $row['idproduk']);
            $stmtRestock->execute();
        }
        $koneksi->commit();

        header('Location: view_cart.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_jmlh'])) {
        $idkeranjang = (int) ($_POST['idkeranjang'] ?? 0);
        $jmlhDiminta = (int) ($_POST['jmlh'] ?? 0);

        if ($idkeranjang > 0 && $jmlhDiminta >= 1) {
            $koneksi->begin_transaction();
            try {
                $stmtCart = $koneksi->prepare("SELECT idproduk, jmlh, harga FROM keranjang
                                                WHERE idkeranjang = ? AND idkonsumen = ? FOR UPDATE");
                $stmtCart->bind_param('is', $idkeranjang, $idKonsumen);
                $stmtCart->execute();
                $cartRow = $stmtCart->get_result()->fetch_assoc();

                if ($cartRow) {
                    $idvariant = (int) $cartRow['idproduk'];
                    $jmlhLama  = (int) $cartRow['jmlh'];

                    $stmtVar = $koneksi->prepare("SELECT stock FROM variants WHERE id = ? FOR UPDATE");
                    $stmtVar->bind_param('i', $idvariant);
                    $stmtVar->execute();
                    $varRow        = $stmtVar->get_result()->fetch_assoc();
                    $stokTersedia  = (int) ($varRow['stock'] ?? 0);

                    // Aturan: jumlah baru tidak boleh melebihi stok yang tersedia
                    $jmlhMaksimum = $jmlhLama + $stokTersedia;
                    $jmlhBaru     = $jmlhDiminta;
                    if ($jmlhBaru > $jmlhMaksimum) {
                        $jmlhBaru = $jmlhMaksimum;
                        $_SESSION['message'] = 'Jumlah disesuaikan, stok tersisa cuma ' . $stokTersedia;
                    }

                    $delta = $jmlhBaru - $jmlhLama;
                    if ($delta !== 0 && $jmlhBaru >= 1) {
                        $stmtStock = $koneksi->prepare("UPDATE variants SET stock = stock - ? WHERE id = ?");
                        $stmtStock->bind_param('ii', $delta, $idvariant);
                        $stmtStock->execute();

                        $subtotalBaru = $cartRow['harga'] * $jmlhBaru;
                        $stmtUpdate   = $koneksi->prepare("UPDATE keranjang SET jmlh = ?, subtotal = ? WHERE idkeranjang = ?");
                        $stmtUpdate->bind_param('idi', $jmlhBaru, $subtotalBaru, $idkeranjang);
                        $stmtUpdate->execute();
                    }
                }
                $koneksi->commit();
            } catch (Exception $e) {
                $koneksi->rollback();
                error_log($e->getMessage());
                $_SESSION['message'] = 'Gagal memperbarui jumlah, silakan coba lagi';
            }
        }

        header('Location: view_cart.php');
        exit;
    }

    $stmt = $koneksi->prepare("SELECT k.idkeranjang, k.jmlh, k.harga, k.subtotal,
                                    v.variant, ms.nama_size AS size, v.stock AS sisa_stock, p.jenis, v.disc,
                                    p.id AS idproduk, p.namaproduk, fp.foto AS foto_file, mf.name AS nama_folder
                                FROM keranjang k
                                JOIN variants v ON k.idproduk = v.id
                                LEFT JOIN master_size ms ON ms.id = v.size_id
                                LEFT JOIN products p ON v.idproducts = p.id
                                LEFT JOIN foto_produk fp ON fp.id = v.foto
                                LEFT JOIN master_folder mf ON fp.folder = mf.id
                                WHERE k.idkonsumen = ? AND k.status = 'Active'
                                ORDER BY k.tgl DESC, k.idkeranjang DESC");
    $stmt->bind_param('s', $idKonsumen);
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $total = 0;
    foreach ($items as $item) {
        $total += (float) $item['subtotal'];
    }

    // "Promo" = jenis-nya dikenal sebagai badge promo (b1g1/Bundling/dll) ATAU punya disc>0.
    // Dipakai buat mengelompokkan tampilan keranjang jadi 2 seksi.
    $itemsPromo = [];
    $itemsBiasa = [];
    foreach ($items as $item) {
        $isPromo = promoBadgeLabel($item['jenis'] ?? null) !== null || (int) ($item['disc'] ?? 0) > 0;
        if ($isPromo) {
            $itemsPromo[] = $item;
        } else {
            $itemsBiasa[] = $item;
        }
    }

    function renderCartItem(array $item): void
    {
        $badgeJenis = promoBadgeLabel($item['jenis'] ?? null);
        $badgeDisc  = discBadgeLabel($item['disc'] ?? null);
    ?>
        <div class="cart-item">
            <input type="checkbox" class="item-checkbox" data-subtotal="<?= (float) $item['subtotal'] ?>" data-idkeranjang="<?= (int) $item['idkeranjang'] ?>" checked>

            <a href="produk.php?id=<?= (int) $item['idproduk'] ?>">
                <img src="<?= fotoProdukSrc($item['nama_folder'] ?? null, $item['foto_file'] ?? null) ?>" alt="">
            </a>

            <div class="flex-grow-1">
                <a href="produk.php?id=<?= (int) $item['idproduk'] ?>" class="produk-link">
                    <div class="font-weight-bold">
                        <?= htmlspecialchars($item['namaproduk'] ?? '') ?>
                        <?php if ($badgeJenis !== null): ?>
                            <span class="item-promo-badge"><?= htmlspecialchars($badgeJenis) ?></span>
                        <?php endif; ?>
                        <?php if ($badgeDisc !== null): ?>
                            <span class="item-promo-badge"><?= htmlspecialchars($badgeDisc) ?></span>
                        <?php endif; ?>
                    </div>
                </a>
                <div class="text-muted small"><?= htmlspecialchars($item['variant']) ?> <?= htmlspecialchars($item['size'] ?? '') ?></div>
                <div>Rp <?= number_format($item['harga']) ?> &times; <?= (int) $item['jmlh'] ?></div>
                <div class="text-muted small">Sisa stok: <?= (int) $item['sisa_stock'] ?></div>

                <form method="post" class="qty-form">
                    <input type="hidden" name="idkeranjang" value="<?= (int) $item['idkeranjang'] ?>">
                    <input type="number" name="jmlh" value="<?= (int) $item['jmlh'] ?>" min="1" max="<?= (int) $item['jmlh'] + (int) $item['sisa_stock'] ?>">
                    <button type="submit" name="update_jmlh" value="1" class="btn btn-outline-secondary btn-sm">Update</button>
                </form>
            </div>

            <div class="text-right">
                <div class="font-weight-bold mb-2 item-subtotal">Rp <?= number_format($item['subtotal']) ?></div>
                <form method="post" onsubmit="return confirm('Hapus item ini dari keranjang?');">
                    <input type="hidden" name="hapus" value="<?= (int) $item['idkeranjang'] ?>">
                    <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                </form>
            </div>
        </div>
    <?php
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Keranjang | Wanoja</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: var(--wnj-bg); }
        .cart-item {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .cart-item img {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: 8px;
        }
        .cart-item a.produk-link {
            color: inherit;
            text-decoration: none;
        }
        .cart-item a.produk-link:hover {
            color: var(--wnj-cta);
        }
        .item-promo-badge {
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
        .cart-section-heading {
            font-weight: 700;
            font-size: .85rem;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: var(--wnj-text-secondary);
            margin: 1.25rem 0 .75rem;
            padding-bottom: .35rem;
            border-bottom: 2px solid var(--wnj-border);
        }
        .cart-section-heading.promo {
            color: #dc3545;
            border-bottom-color: #dc3545;
        }
        .qty-form {
            display: flex;
            align-items: center;
            gap: .4rem;
            margin-top: .35rem;
        }
        .qty-form input[type="number"] {
            width: 64px;
            padding: .2rem .4rem;
        }
        .cart-summary {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
            padding: 1.25rem;
        }
        .empty-state {
            padding: 4rem 1rem;
            text-align: center;
            color: var(--wnj-text-secondary);
        }
        .select-all-bar {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
            padding: .75rem 1rem;
            margin-bottom: 1rem;
        }
        @media (max-width: 480px) {
            .cart-item {
                flex-wrap: wrap;
            }
            .cart-item > .text-right {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: space-between;
                text-align: left;
                margin-top: .5rem;
            }
            .cart-item > .text-right .item-subtotal {
                margin-bottom: 0 !important;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container my-4">
        <h5 class="font-weight-bold mb-3">Keranjang Saya</h5>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info text-center">
                <?= htmlspecialchars($_SESSION['message']) ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (empty($items)): ?>
            <div class="empty-state">
                <i class="bi bi-cart-x" style="font-size:3rem;"></i>
                <p class="mt-2">Keranjang kamu masih kosong.</p>
                <a href="index.php" class="btn btn-primary btn-sm">Belanja Sekarang</a>
            </div>
        <?php else: ?>
            <div class="select-all-bar">
                <label class="mb-0">
                    <input type="checkbox" id="pilihSemua" checked> Pilih Semua
                </label>
            </div>

            <?php if (!empty($itemsPromo)): ?>
                <div class="cart-section-heading promo">🏷️ Item Promo</div>
                <?php foreach ($itemsPromo as $item): ?>
                    <?php renderCartItem($item); ?>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($itemsBiasa)): ?>
                <?php if (!empty($itemsPromo)): ?>
                    <div class="cart-section-heading">Item Lainnya</div>
                <?php endif; ?>
                <?php foreach ($itemsBiasa as $item): ?>
                    <?php renderCartItem($item); ?>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="cart-summary d-flex align-items-center justify-content-between mt-3">
                <div>
                    <div class="text-muted small">Total (dipilih)</div>
                    <div class="h5 font-weight-bold mb-0">Rp <span id="totalDipilih"><?= number_format($total) ?></span></div>
                </div>
                <button type="button" class="btn btn-primary" onclick="submitCheckout()">
                    <i class="bi bi-bag-check"></i> Checkout
                </button>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        var checkboxes    = document.querySelectorAll('.item-checkbox');
        var pilihSemua     = document.getElementById('pilihSemua');
        var totalDipilihEl = document.getElementById('totalDipilih');

        function hitungTotal() {
            var total = 0;
            checkboxes.forEach(function (cb) {
                if (cb.checked) {
                    total += parseFloat(cb.dataset.subtotal || '0');
                }
            });
            if (totalDipilihEl) {
                totalDipilihEl.textContent = total.toLocaleString('id-ID');
            }
        }

        checkboxes.forEach(function (cb) {
            cb.addEventListener('change', function () {
                if (!cb.checked && pilihSemua) {
                    pilihSemua.checked = false;
                }
                if (cb.checked && pilihSemua && Array.from(checkboxes).every(function (c) { return c.checked; })) {
                    pilihSemua.checked = true;
                }
                hitungTotal();
            });
        });

        if (pilihSemua) {
            pilihSemua.addEventListener('change', function () {
                checkboxes.forEach(function (cb) {
                    cb.checked = pilihSemua.checked;
                });
                hitungTotal();
            });
        }

        function submitCheckout() {
            var checked = Array.prototype.filter.call(checkboxes, function (cb) { return cb.checked; });
            if (checked.length === 0) {
                alert('Pilih minimal 1 barang untuk checkout');
                return;
            }

            var form = document.createElement('form');
            form.method = 'post';
            form.action = 'checkout.php';

            checked.forEach(function (cb) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'idkeranjang[]';
                input.value = cb.dataset.idkeranjang;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
