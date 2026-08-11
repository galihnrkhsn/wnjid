<?php
    include 'koneksi.php';
    include 'session_guard.php';
    include '../includes/mitra_role_helper.php';

    $idpoproduk = (int) ($_GET['id'] ?? 0);
    $kolomRole  = mitraKolomPomitra($mitraRole);

    if (!$idpoproduk) {
        die('Data tidak valid.');
    }

    $stmtProduk = $koneksi->prepare("SELECT namapo FROM poproduk WHERE idpoproduk = ?");
    $stmtProduk->bind_param('i', $idpoproduk);
    $stmtProduk->execute();
    $namapo = $stmtProduk->get_result()->fetch_assoc()['namapo'] ?? '-';

    $stmtBukapo = $koneksi->prepare("SELECT jenis_po FROM bukapo WHERE idpoproduk = ?");
    $stmtBukapo->bind_param('i', $idpoproduk);
    $stmtBukapo->execute();
    $jenisPoAktual = $stmtBukapo->get_result()->fetch_assoc()['jenis_po'] ?? '';

    // ---- Config & pool dibaca dari tabel (bukapo_bundling_config/bukapo_bundling_pool),
    // bukan hardcode per file lagi. Produk yang belum pernah dikonfigurasi otomatis
    // dapat default aman: 3 slot bebas, cek stok mengikuti jenis_po, guard duplikat aktif.
    $stmtConfig = $koneksi->prepare("SELECT cek_stok, cek_duplikat, is_custom_label, redirect_page FROM bukapo_bundling_config WHERE idpoproduk = ?");
    $stmtConfig->bind_param('i', $idpoproduk);
    $stmtConfig->execute();
    $config = $stmtConfig->get_result()->fetch_assoc() ?: [
        'cek_stok'        => 'ikut_jenis_po',
        'cek_duplikat'    => 1,
        'is_custom_label' => null,
        'redirect_page'   => 'datapobundling2.php',
    ];

    $stmtPool = $koneksi->prepare("SELECT urutan, label, filter_keyword, filter_mode FROM bukapo_bundling_pool WHERE idpoproduk = ? ORDER BY urutan ASC");
    $stmtPool->bind_param('i', $idpoproduk);
    $stmtPool->execute();
    $pools = $stmtPool->get_result()->fetch_all(MYSQLI_ASSOC);
    if (empty($pools)) {
        $pools = [
            ['urutan' => 1, 'label' => null, 'filter_keyword' => null, 'filter_mode' => 'include'],
            ['urutan' => 2, 'label' => null, 'filter_keyword' => null, 'filter_mode' => 'include'],
            ['urutan' => 3, 'label' => null, 'filter_keyword' => null, 'filter_mode' => 'include'],
        ];
    }
    $jumlahPool = count($pools);

    $pakaiStok = $config['cek_stok'] === 'selalu'
        || ($config['cek_stok'] === 'ikut_jenis_po' && $jenisPoAktual === 'PO Custom Stok');

    // idpo yang selalu dikecualikan dari pool BEBAS (bukan yang pakai filter_keyword) -
    // aturan lama yang dipertahankan (kombinasi variant yang sudah tidak dipakai lagi).
    $idpoDikecualikan = [4413, 4382, 4351];

    function mitraVariantUntukPool(mysqli $koneksi, int $idpoproduk, array $pool, array $idpoDikecualikan): array
    {
        if (!empty($pool['filter_keyword'])) {
            $operator = $pool['filter_mode'] === 'exclude' ? 'NOT LIKE' : 'LIKE';
            $stmt = $koneksi->prepare("SELECT podetail.idpodetail, podetail.idpo, podetail.variant
                                        FROM podetail
                                        INNER JOIN pokategori ON pokategori.idpo = podetail.idpo
                                        WHERE pokategori.idpoproduk = ? AND podetail.variant $operator CONCAT('%', ?, '%')");
            $stmt->bind_param('is', $idpoproduk, $pool['filter_keyword']);
        } else {
            $placeholders = implode(',', array_fill(0, count($idpoDikecualikan), '?'));
            $stmt = $koneksi->prepare("SELECT podetail.idpodetail, podetail.idpo, podetail.variant
                                        FROM podetail
                                        INNER JOIN pokategori ON pokategori.idpo = podetail.idpo
                                        WHERE pokategori.idpoproduk = ? AND podetail.idpo NOT IN ($placeholders)");
            $types  = 'i' . str_repeat('i', count($idpoDikecualikan));
            $params = array_merge([$idpoproduk], $idpoDikecualikan);
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function mitraBundlingOptionValue(array $row, string $label): string
    {
        return htmlspecialchars($row['idpodetail']) . ' | ' . htmlspecialchars($row['idpo']) . ' | ' . htmlspecialchars($label);
    }

    $poolData = [];
    foreach ($pools as $pool) {
        $label = $pool['label'] ?? ('Variant ' . $pool['urutan']);
        $poolData[] = [
            'label'    => $label,
            'variants' => mitraVariantUntukPool($koneksi, $idpoproduk, $pool, $idpoDikecualikan),
        ];
    }

    $invoiceLama = null;
    if ($config['cek_duplikat']) {
        $stmtCekInvoice = $koneksi->prepare("SELECT invoice FROM pomitra WHERE idpoproduk = ? AND $kolomRole = ? LIMIT 1");
        $stmtCekInvoice->bind_param('ii', $idpoproduk, $idMitra);
        $stmtCekInvoice->execute();
        $invoiceLama = $stmtCekInvoice->get_result()->fetch_assoc();
    }

    $pesan = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim']) && !$invoiceLama) {
        $qty     = $_POST['qty'] ?? [];
        $variant = $_POST['variant'] ?? [];
        $jumlahBaris = count($variant);

        $invoice = mitraBuatInvoicePO($mitraRole, $idMitra, $idpoproduk) . mt_rand(1000000, 9999999);
        $gagalStok = null;

        $koneksi->begin_transaction();
        try {
            $stmtDetail      = $koneksi->prepare("SELECT harga FROM podetail WHERE idpodetail = ?");
            $stmtCekStok     = $koneksi->prepare("SELECT stok, namakategori FROM pokategori WHERE idpo = ? FOR UPDATE");
            $stmtKurangiStok = $koneksi->prepare("UPDATE pokategori SET stok = ? WHERE idpo = ?");
            $stmtInsert      = $koneksi->prepare("INSERT INTO pomitra ($kolomRole, idpoproduk, idpo, idpodetail, jumlah, custom, total, invoice, status, tgl, waktu, is_custom)
                                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Belum DP', NOW(), ?, ?)");
            $waktu = date('H:i:s');
            $isCustomLabel = $config['is_custom_label'];

            for ($x = 0; $x < $jumlahBaris; $x++) {
                $qtyItem    = (int) ($qty[$x] ?? 0);
                $data       = explode('|', $variant[$x] ?? '');
                $idpodetail = (int) trim($data[0] ?? '');
                $idpo       = (int) trim($data[1] ?? '');
                $custom     = trim($data[2] ?? '');

                if ($idpodetail <= 0 || $idpo <= 0) {
                    continue; // slot tidak dipilih/kosong
                }

                if ($pakaiStok) {
                    $stmtCekStok->bind_param('i', $idpo);
                    $stmtCekStok->execute();
                    $stok = $stmtCekStok->get_result()->fetch_assoc();
                    $stokTersedia = (int) ($stok['stok'] ?? 0);

                    if ($stokTersedia <= 0 || $qtyItem > $stokTersedia) {
                        $gagalStok = 'Stok untuk ' . ($stok['namakategori'] ?? '-') . ' tidak mencukupi (tersisa ' . $stokTersedia . ').';
                        break;
                    }

                    $stokBaru = $stokTersedia - $qtyItem;
                    $stmtKurangiStok->bind_param('ii', $stokBaru, $idpo);
                    $stmtKurangiStok->execute();
                }

                $stmtDetail->bind_param('i', $idpodetail);
                $stmtDetail->execute();
                $totalHarga = (int) ($stmtDetail->get_result()->fetch_assoc()['harga'] ?? 0);

                $stmtInsert->bind_param('iiiiisdsss', $idMitra, $idpoproduk, $idpo, $idpodetail, $qtyItem, $custom, $totalHarga, $invoice, $waktu, $isCustomLabel);
                $stmtInsert->execute();
            }

            if ($gagalStok !== null) {
                $koneksi->rollback();
                $pesan = $gagalStok;
            } else {
                $koneksi->commit();
                header('Location: ../distributor/' . $config['redirect_page'] . '?id=' . $idpoproduk . '&invoice=' . urlencode($invoice));
                exit;
            }
        } catch (Exception $e) {
            $koneksi->rollback();
            error_log($e->getMessage());
            $pesan = 'Gagal menyimpan pesanan, silakan coba lagi.';
        }
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Form PO Bundling | WNJ.ID</title>
    <link rel="stylesheet" href="/home/assets/css/bootstrap.min.css">
    <style>
        body { background: var(--wnj-bg); }
        .form-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            padding: 1.25rem;
            margin-bottom: 1rem;
        }
        .bundling-label {
            font-weight: 700;
            color: var(--wnj-cta);
            font-size: .82rem;
            text-transform: uppercase;
        }
        .stok-list {
            font-size: .85rem;
            color: var(--wnj-text-secondary);
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 700px;">
        <div class="d-flex align-items-center mt-3 mb-3" style="gap:.75rem;">
            <a href="preorder.php" class="text-muted"><i class="bi bi-arrow-left"></i></a>
            <h5 class="font-weight-bold mb-0">Formulir Pemesanan <?= htmlspecialchars($namapo) ?></h5>
        </div>

        <?php if ($pesan !== ''): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($pesan) ?></div>
        <?php endif; ?>

        <?php if ($invoiceLama): ?>
            <div class="form-card text-center">
                <p class="text-muted mb-3">Kamu sudah memesan PO ini.</p>
                <a href="../distributor/<?= htmlspecialchars($config['redirect_page']) ?>?id=<?= $idpoproduk ?>&invoice=<?= urlencode($invoiceLama['invoice']) ?>" class="btn btn-primary">Lihat Invoice</a>
            </div>
        <?php else: ?>
            <?php if ($pakaiStok): ?>
                <div class="form-card stok-list">
                    <strong class="d-block mb-2">Sisa Stok</strong>
                    <?php
                        $stmtStokAll = $koneksi->prepare("SELECT namakategori, stok FROM pokategori WHERE idpoproduk = ? ORDER BY idpo");
                        $stmtStokAll->bind_param('i', $idpoproduk);
                        $stmtStokAll->execute();
                        foreach ($stmtStokAll->get_result()->fetch_all(MYSQLI_ASSOC) as $s):
                    ?>
                        <div><?= htmlspecialchars($s['namakategori']) ?>: <?= (int) $s['stok'] ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" id="formBundling">
                <div class="form-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="bundling-label">Bundling Ke-1</span>
                        <span class="btn btn-sm btn-success" id="buttonAddPack">+ Tambah Bundling</span>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 col-sm-<?= $jumlahPool > 3 ? 2 : 3 ?>">
                            <label class="mb-0 small">Jumlah</label>
                            <input type="hidden" name="custom[]" value="Bundling 1">
                            <input type="number" class="form-control form-control-sm pack-input" min="0" value="0" name="qty[]" required>
                            <?php for ($h = 1; $h < $jumlahPool; $h++): ?>
                                <input type="hidden" name="qty[]" value="0">
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <?php foreach ($poolData as $pool): ?>
                            <div class="col-8 col-sm-<?= $jumlahPool > 3 ? 2 : 4 ?> mt-2 mt-sm-0">
                                <label class="mb-0 small"><?= htmlspecialchars($pool['label']) ?></label>
                                <select class="form-control form-control-sm" name="variant[]">
                                    <?php foreach ($pool['variants'] as $v): ?>
                                        <option value="<?= mitraBundlingOptionValue($v, 'Bundling 1') ?>"><?= htmlspecialchars($v['variant']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div id="demoPoInner"></div>
                </div>
                <button type="submit" name="kirim" class="btn btn-primary btn-block">Kirim Pesanan</button>
            </form>
            <script>
                const poolData = <?= json_encode($poolData) ?>;
                const jumlahPool = poolData.length;
                const buttonAddPack = document.getElementById('buttonAddPack');
                const demoPoInner = document.getElementById('demoPoInner');
                let formCount = 1;

                buttonAddPack.addEventListener('click', () => {
                    formCount++;
                    const bundlingLabel = `Bundling Ke-${formCount}`;
                    const colClass = jumlahPool > 3 ? 'col-6 col-sm-2' : 'col-8 col-sm-4';

                    let hiddenQty = '';
                    for (let h = 1; h < jumlahPool; h++) {
                        hiddenQty += '<input type="hidden" name="qty[]" value="0">';
                    }

                    let selects = '';
                    poolData.forEach(pool => {
                        const options = pool.variants.map(v => `<option value="${v.idpodetail} | ${v.idpo} | ${bundlingLabel}">${v.variant}</option>`).join('');
                        selects += `
                            <div class="${colClass} mt-2 mt-sm-0">
                                <label class="mb-0 small">${pool.label}</label>
                                <select class="form-control form-control-sm" name="variant[]">${options}</select>
                            </div>`;
                    });

                    const wrap = document.createElement('div');
                    wrap.innerHTML = `
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="bundling-label">${bundlingLabel}</span>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 col-sm-${jumlahPool > 3 ? 2 : 3}">
                                <label class="mb-0 small">Jumlah</label>
                                <input type="hidden" name="custom[]" value="${bundlingLabel}">
                                <input type="number" class="form-control form-control-sm pack-input" min="0" value="0" name="qty[]" required>
                                ${hiddenQty}
                            </div>
                        </div>
                        <div class="row mb-2">
                            ${selects}
                        </div>`;
                    demoPoInner.appendChild(wrap);
                });

                document.getElementById('formBundling').addEventListener('input', function (event) {
                    if (!event.target.classList.contains('pack-input')) {
                        return;
                    }
                    const row = event.target.closest('.row');
                    row.querySelectorAll('input[type="hidden"][name="qty[]"]').forEach(input => { input.value = event.target.value; });
                });
            </script>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script src="/home/assets/js/jquery.min.js"></script>
    <script src="/home/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
