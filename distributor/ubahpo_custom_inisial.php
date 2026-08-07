<?php
session_start();
error_reporting(0);

include 'koneksi.php';
include 'assets/components/Sessions/sesDistri.php';

date_default_timezone_set('Asia/Jakarta');

$idpoproduk = $_GET['id'];
$invoice    = $_GET['invoice'];
$idadmin    = $_SESSION['idadmin'];

// ── Info PO & Mitra ───────────────────────────────────────────────────────────
$rowPO = $koneksi->query("
    SELECT poproduk.namapo
    FROM poproduk
    INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
    WHERE poproduk.idpoproduk = '$idpoproduk'
      AND pomitra.idmitra = '$idadmin'
      AND pomitra.invoice = '$invoice'
    LIMIT 1
")->fetch_assoc();

$rowUser = $koneksi->query("
    SELECT namamitra FROM admin_mitra WHERE idadmin = '$idadmin'
")->fetch_assoc();

// ── Data existing pomitra (sudah ada) ────────────────────────────────────────
$existing = [];
$resEx = $koneksi->query("
    SELECT
        pomitra.idpomitra,
        pomitra.custom,
        pomitra.jumlah,
        pomitra.total,
        podetail.variant,
        podetail.harga,
        podetail.idpodetail,
        pomitra.font
    FROM pomitra
    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
    WHERE pomitra.invoice     = '$invoice'
      AND pomitra.idpoproduk  = '$idpoproduk'
      AND pomitra.idmitra     = '$idadmin'
    ORDER BY podetail.variant ASC
");
while ($r = $resEx->fetch_assoc()) {
    $existing[] = $r;
}

// ── Semua variant dari podetail/pokategori (untuk tambah baru) ───────────────
$allVariants = [];
$resAll = $koneksi->query("
    SELECT
        podetail.idpodetail,
        podetail.variant,
        podetail.harga,
        podetail.idpo
    FROM podetail
    INNER JOIN pokategori ON pokategori.idpo = podetail.idpo
    WHERE pokategori.idpoproduk = '$idpoproduk'
    ORDER BY podetail.variant ASC
");
while ($r = $resAll->fetch_assoc()) {
    $allVariants[] = $r;
}

// ════════════════════════════════════════════════════════════════════════════
// POST HANDLER
// ════════════════════════════════════════════════════════════════════════════
$flash = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    // ── 1. Update custom existing row ─────────────────────────────────────
    if ($action === 'update_custom') {
        $idpomitra  = (int)$_POST['idpomitra'];
        $custom_val = trim($_POST['custom_val']);
        $font       = trim($_POST['font']);

        $ok = $koneksi->query("UPDATE pomitra 
                                SET 
                                    custom = '$custom_val', 
                                    font = '$font'
                                WHERE idpomitra = '$idpomitra' 
                                AND idmitra = '$idadmin'
        ");
        $flash = $ok ? ['type'=>'success','msg'=>'Custom berhasil diperbarui.']
                     : ['type'=>'error',  'msg'=>'Gagal memperbarui custom.'];
        header("Location: ubahpo_custom_inisial.php?id=$idpoproduk&invoice=$invoice&flash=".urlencode($flash['msg'])."&ftype=".$flash['type']);
        exit();
    }

    // ── 2. Hapus existing row ─────────────────────────────────────────────
    if ($action === 'delete_row') {
        $idpomitra = (int)$_POST['idpomitra'];
        $ok = $koneksi->query("
            DELETE FROM pomitra
            WHERE idpomitra = '$idpomitra' AND idmitra = '$idadmin'
        ");
        $flash = $ok ? ['type'=>'success','msg'=>'Baris berhasil dihapus.']
                     : ['type'=>'error',  'msg'=>'Gagal menghapus baris.'];
        header("Location: ubahpo_custom_inisial.php?id=$idpoproduk&invoice=$invoice&flash=".urlencode($flash['msg'])."&ftype=".$flash['type']);
        exit();
    }

    if ($action === 'insert_new') {
        $idpodetail = (int)$_POST['idpodetail'];
        $harga      = (float)$_POST['harga'];
        $customs    = $_POST['customs'] ?? [];
        $fonts      = $_POST['fonts'] ?? [];

        $rowPD = $koneksi->query("SELECT idpo FROM podetail WHERE idpodetail='$idpodetail'")->fetch_assoc();
        $idpo  = $rowPD['idpo'];

        $inserted = 0;
        foreach ($customs as $i => $c) {
            $c = trim($c);
            if ($c === '') continue;
            
            $font   = trim($fonts[$i] ?? 'Bold');
            $total  = $harga * 1;

            $ok = $koneksi->query("INSERT INTO pomitra 
                                    (
                                        idpomitra,
                                        idmitra,
                                        idpoproduk,
                                        idpo,
                                        idpodetail,
                                        jumlah,
                                        total,
                                        custom,
                                        font,
                                        invoice,
                                        status,
                                        tgl,
                                        waktu
                                    ) VALUES (
                                        NULL,
                                        '$idadmin',
                                        '$idpoproduk',
                                        '$idpo',
                                        '$idpodetail',
                                        '1',
                                        '$harga',
                                        '$c',
                                        '$font',
                                        '$invoice',
                                        'Belum DP',
                                        NOW(),
                                        '$waktu'
                                    )
                                ");
            if ($ok) $inserted++;
        }
        $flash = $inserted > 0
            ? ['type'=>'success','msg'=>"$inserted baris baru berhasil ditambahkan."]
            : ['type'=>'error',  'msg'=>'Tidak ada baris yang ditambahkan. Pastikan custom inisial diisi.'];
        header("Location: ubahpo_custom_inisial.php?id=$idpoproduk&invoice=$invoice&flash=".urlencode($flash['msg'])."&ftype=".$flash['type']);
        exit();
    }
}

// Flash dari redirect
if (isset($_GET['flash'])) {
    $flash = ['type'=>$_GET['ftype']??'success', 'msg'=>urldecode($_GET['flash'])];
}

// Redirect tujuan setelah simpan
$redirect_after = (in_array($idpoproduk, ['405','406','407']))
    ? "datapokolibri3.php?id=$idpoproduk&invoice=$invoice"
    : "datapocustom3.php?id=$idpoproduk&invoice=$invoice";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ubah PO | WNJ.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:         #f0f2f8;
            --surface:    #ffffff;
            --surface2:   #f7f8fc;
            --border:     #e2e6f0;
            --text:       #111827;
            --muted:      #6b7280;
            --primary:    #3b5bdb;
            --primary-d:  #2f4ac4;
            --primary-l:  #eef2ff;
            --success:    #0d9488;
            --success-l:  #f0fdfa;
            --warning:    #b45309;
            --warning-l:  #fffbeb;
            --danger:     #dc2626;
            --danger-l:   #fef2f2;
            --accent:     #7c3aed;
            --radius:     10px;
            --shadow-sm:  0 1px 2px rgba(0,0,0,.06);
            --shadow:     0 2px 8px rgba(0,0,0,.08), 0 0 0 1px rgba(0,0,0,.04);
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding-bottom: 6rem;
        }

        /* ── Layout ── */
        .page { max-width: 700px; margin: 0 auto; padding: 1.25rem 1rem; }

        /* ── Page header ── */
        .page-head {
            margin-bottom: 1.25rem;
        }
        .page-head h1 {
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: -.01em;
        }
        .page-head .sub {
            font-size: .82rem;
            color: var(--muted);
            margin-top: .2rem;
        }

        /* ── Flash ── */
        .flash {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .75rem 1rem;
            border-radius: var(--radius);
            font-size: .85rem;
            font-weight: 500;
            margin-bottom: 1rem;
            animation: slideIn .25s ease;
        }
        .flash.success { background: var(--success-l); color: var(--success); border: 1px solid #99f6e4; }
        .flash.error   { background: var(--danger-l);  color: var(--danger);  border: 1px solid #fca5a5; }
        @keyframes slideIn { from { opacity:0; transform: translateY(-6px); } to { opacity:1; transform: none; } }

        /* ── Section heading ── */
        .section-head {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--muted);
            margin-bottom: .6rem;
        }
        .section-head .pill-count {
            background: var(--primary);
            color: #fff;
            font-size: .65rem;
            padding: .1rem .45rem;
            border-radius: 99px;
        }

        /* ── Card ── */
        .card {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 1rem;
        }

        /* ── Existing row ── */
        .exist-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 0;
            border-bottom: 1px solid var(--border);
            padding: .85rem 1rem;
            transition: background .15s;
            animation: fadeUp .3s ease both;
        }
        .exist-row:last-child { border-bottom: none; }
        .exist-row:hover { background: var(--surface2); }
        @keyframes fadeUp { from { opacity:0; transform: translateY(4px); } to { opacity:1; transform: none; } }

        .er-left { flex: 1; }
        .er-variant {
            font-size: .88rem;
            font-weight: 600;
            margin-bottom: .35rem;
        }
        .er-meta {
            font-size: .75rem;
            color: var(--muted);
            display: flex;
            gap: .75rem;
            flex-wrap: wrap;
            margin-bottom: .5rem;
        }
        .er-meta span { display: flex; align-items: center; gap: .25rem; }

        .custom-edit-row {
            display: flex;
            align-items: center;
            gap: .4rem;
        }
        .custom-badge {
            font-family: 'DM Mono', monospace;
            font-size: .78rem;
            background: var(--primary-l);
            color: var(--primary);
            border: 1.5px solid #c7d2fe;
            padding: .25rem .55rem;
            border-radius: 6px;
            font-weight: 500;
            min-width: 2.5rem;
            text-align: center;
        }

        .er-actions {
            display: flex;
            flex-direction: column;
            gap: .35rem;
            align-items: flex-end;
            justify-content: center;
            padding-left: .75rem;
        }

        /* ── Inputs ── */
        input[type="text"],
        input[type="number"] {
            font-family: 'DM Sans', sans-serif;
            font-size: .85rem;
            padding: .4rem .65rem;
            border: 1.5px solid var(--border);
            border-radius: 7px;
            background: var(--surface);
            color: var(--text);
            transition: border-color .15s, box-shadow .15s;
            width: 100%;
        }
        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59,91,219,.12);
        }
        input.inline-custom {
            width: 90px;
            font-family: 'DM Mono', monospace;
            font-size: .8rem;
            /* text-transform: uppercase; */
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .3rem;
            padding: .38rem .8rem;
            border-radius: 7px;
            border: none;
            font-family: 'DM Sans', sans-serif;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: opacity .15s, transform .1s, box-shadow .15s;
            white-space: nowrap;
        }
        .btn:active { transform: scale(.96); }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-d); }
        .btn-success { background: var(--success); color: #fff; }
        .btn-danger  {
            background: transparent;
            color: var(--danger);
            border: 1.5px solid #fca5a5;
        }
        .btn-danger:hover { background: var(--danger-l); }
        .btn-ghost {
            background: var(--surface2);
            color: var(--text);
            border: 1.5px solid var(--border);
        }
        .btn-ghost:hover { border-color: var(--primary); color: var(--primary); }
        .btn-sm { padding: .28rem .6rem; font-size: .75rem; }

        /* ── Variant card (untuk tambah baru) ── */
        .variant-card {
            border-bottom: 1px solid var(--border);
            padding: 1rem;
        }
        .variant-card:last-child { border-bottom: none; }

        .vc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: .75rem;
            flex-wrap: wrap;
            gap: .5rem;
        }
        .vc-name {
            font-size: .9rem;
            font-weight: 600;
        }
        .vc-price {
            font-size: .78rem;
            color: var(--muted);
            font-family: 'DM Mono', monospace;
        }

        .qty-control {
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .qty-control label {
            font-size: .78rem;
            color: var(--muted);
            font-weight: 500;
            white-space: nowrap;
        }
        .qty-input {
            width: 70px;
        }
        .btn-gen {
            white-space: nowrap;
        }

        /* ── Custom inisial grid ── */
        .custom-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: .5rem;
            margin-top: .75rem;
        }
        .custom-item {
            display: flex;
            flex-direction: column;
            gap: .2rem;
        }
        .custom-item label {
            font-size: .68rem;
            color: var(--muted);
            text-align: center;
            font-weight: 500;
        }
        .custom-item input {
            text-align: center;
            font-family: 'DM Mono', monospace;
            font-size: .82rem;
            /* text-transform: uppercase; */
        }

        .custom-form-actions {
            margin-top: .75rem;
            display: flex;
            justify-content: flex-end;
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--muted);
            font-size: .85rem;
        }

        /* ── Save bar ── */
        .save-bar {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: var(--surface);
            border-top: 1px solid var(--border);
            padding: .75rem 1rem;
            display: flex;
            justify-content: center;
            gap: .75rem;
            z-index: 100;
            box-shadow: 0 -4px 16px rgba(0,0,0,.08);
        }

        /* ── Tag chip ── */
        .chip {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .2rem .55rem;
            border-radius: 99px;
            font-size: .72rem;
            font-weight: 600;
        }
        .chip-existing { background: var(--primary-l); color: var(--primary); }

        /* Delete confirm modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.4);
            z-index: 200;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: var(--surface);
            border-radius: 14px;
            padding: 1.5rem;
            max-width: 320px;
            width: 90%;
            box-shadow: 0 8px 32px rgba(0,0,0,.18);
            text-align: center;
        }
        .modal-box h3 { font-size: 1rem; font-weight: 700; margin-bottom: .5rem; }
        .modal-box p  { font-size: .85rem; color: var(--muted); margin-bottom: 1.25rem; }
        .modal-actions { display: flex; gap: .6rem; justify-content: center; }

        @media (max-width: 480px) {
            .er-actions { flex-direction: row; }
            .custom-grid { grid-template-columns: repeat(auto-fill, minmax(65px, 1fr)); }
        }
    </style>
</head>
<body>

<?php include "assets/components/Navbar/navbar.php"; ?>

<div class="page">

    <!-- ── Page Header ── -->
    <div class="page-head">
        <h1>✏️ Ubah PO &mdash; <?= htmlspecialchars($rowPO['namapo'] ?? '') ?></h1>
        <div class="sub">
            <?= htmlspecialchars($rowUser['namamitra'] ?? '') ?> &nbsp;·&nbsp; Invoice: <strong><?= htmlspecialchars($invoice) ?></strong>
        </div>
    </div>

    <!-- ── Flash ── -->
    <?php if ($flash): ?>
    <div class="flash <?= $flash['type'] ?>">
        <?= $flash['type'] === 'success' ? '✅' : '❌' ?>
        <?= htmlspecialchars($flash['msg']) ?>
    </div>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════════════════════════════
         SECTION 1 — DATA YANG SUDAH ADA (EXISTING)
    ══════════════════════════════════════════════════════════════════ -->
    <div class="section-head">
        <span>📋 Data Invoice</span>
        <span class="pill-count"><?= count($existing) ?></span>
    </div>

    <div class="card">
        <?php if (empty($existing)): ?>
            <div class="empty-state">Belum ada data di invoice ini.</div>
        <?php else: ?>
            <?php foreach ($existing as $idx => $ex): ?>
            <div class="exist-row" style="animation-delay: <?= $idx * 0.04 ?>s">
                <div class="er-left">
                    <div class="er-variant"><?= htmlspecialchars($ex['variant']) ?></div>
                    <div class="er-meta">
                        <span>💰 Rp <?= number_format($ex['harga']) ?></span>
                        <span>📦 Qty: <strong><?= $ex['jumlah'] ?></strong></span>
                        <span>🧾 Total: Rp <?= number_format($ex['total']) ?></span>
                    </div>
                    <!-- Edit custom inline -->
                    <form method="post" class="custom-edit-row">
                        <input type="hidden" name="action" value="update_custom">
                        <input type="hidden" name="idpomitra" value="<?= $ex['idpomitra'] ?>">
                        <span style="font-size:.75rem;color:var(--muted);white-space:nowrap">Inisial:</span>
                        <input type="text"
                               name="custom_val"
                               maxlength="1"
                               class="inline-custom"
                               value="<?= htmlspecialchars($ex['custom'] ?? '') ?>"
                               placeholder="–">
                        <select name="font" class="form-control inline-font">
                            <option value="Bold"
                                <?= (($ex['font'] ?? 'Bold') === 'Bold') ? 'selected' : '' ?>>
                                Bold
                            </option>

                            <option value="Monoline"
                                <?= (($ex['font'] ?? '') === 'Monoline') ? 'selected' : '' ?>>
                                Monoline
                            </option>
                        </select>
                        <button type="submit" class="btn btn-ghost btn-sm">💾 Simpan</button>
                    </form>
                </div>
                <div class="er-actions">
                    <button class="btn btn-danger btn-sm"
                            onclick="confirmDelete(<?= $ex['idpomitra'] ?>, '<?= htmlspecialchars($ex['variant']) ?>', '<?= htmlspecialchars($ex['custom'] ?? '') ?>')">
                        🗑
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         SECTION 2 — TAMBAH BARIS BARU (SEMUA VARIANT)
    ══════════════════════════════════════════════════════════════════ -->
    <div class="section-head" style="margin-top:.5rem">
        <span>➕ Tambah Baris Baru</span>
        <span class="pill-count"><?= count($allVariants) ?></span>
    </div>

    <div class="card mb-5">
        <?php if (empty($allVariants)): ?>
            <div class="empty-state">Tidak ada variant tersedia.</div>
        <?php else: ?>
            <?php foreach ($allVariants as $idx => $v): ?>
            <div class="variant-card">
                <div class="vc-header">
                    <div>
                        <div class="vc-name"><?= htmlspecialchars($v['variant']) ?></div>
                        <div class="vc-price">Rp <?= number_format($v['harga']) ?> / pcs</div>
                    </div>
                    <div class="qty-control">
                        <label for="qty_<?= $v['idpodetail'] ?>">Qty:</label>
                        <input type="number"
                               id="qty_<?= $v['idpodetail'] ?>"
                               class="qty-input"
                               min="1" max="99"
                               placeholder="0"
                               oninput="generateCustomInputs(<?= $v['idpodetail'] ?>, this.value)">
                        <button type="button"
                                class="btn btn-ghost btn-sm btn-gen"
                                onclick="generateCustomInputs(<?= $v['idpodetail'] ?>, document.getElementById('qty_<?= $v['idpodetail'] ?>').value)">
                            ⚡ Generate
                        </button>
                    </div>
                </div>

                <!-- Container custom inisial (di-generate JS) -->
                <div id="customs_<?= $v['idpodetail'] ?>" style="display:none">
                    <div style="font-size:.75rem;color:var(--muted);margin-bottom:.4rem;font-weight:600;">
                        Isi inisial custom per item:
                    </div>
                    <form method="post">
                        <input type="hidden" name="action" value="insert_new">
                        <input type="hidden" name="idpodetail" value="<?= $v['idpodetail'] ?>">
                        <input type="hidden" name="harga" value="<?= $v['harga'] ?>">
                        <div class="custom-grid" id="grid_<?= $v['idpodetail'] ?>">
                            <!-- generated by JS -->
                        </div>
                        <div class="custom-form-actions">
                            <button type="submit" class="btn btn-secondary">
                                Tambah <?= htmlspecialchars($v['variant']) ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div><!-- /page -->

<!-- ── Save Bar ── -->
<div class="save-bar">
    <a href="<?= $redirect_after ?>" class="btn btn-primary">
        ✔ Selesai & Lihat Invoice
    </a>
</div>

<!-- ── Delete Confirm Modal ── -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <h3>Hapus Baris?</h3>
        <p id="deleteModalDesc">Yakin ingin menghapus baris ini?</p>
        <div class="modal-actions">
            <button class="btn btn-ghost" onclick="closeModal()">Batal</button>
            <form method="post" id="deleteForm">
                <input type="hidden" name="action" value="delete_row">
                <input type="hidden" name="idpomitra" id="deleteIdpomitra">
                <button type="submit" class="btn btn-danger">🗑 Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
// ── Generate custom inisial inputs ──────────────────────────────────────────
function generateCustomInputs(idpodetail, qty) {
    qty = parseInt(qty);
    const container = document.getElementById('customs_' + idpodetail);
    const grid      = document.getElementById('grid_' + idpodetail);

    if (!qty || qty < 1) {
        container.style.display = 'none';
        grid.innerHTML = '';
        return;
    }

    // Preserve existing values
    const existingCustoms = [];
    const existingFonts   = [];

    grid.querySelectorAll('.custom-item').forEach(item => {
        existingCustoms.push(
            item.querySelector('input')?.value || ''
        );

        existingFonts.push(
            item.querySelector('select')?.value || 'Bold'
        );
    });

    grid.innerHTML = '';

    for (let i = 0; i < qty; i++) {
        const item = document.createElement('div');
        item.className = 'custom-item';

        item.innerHTML = `
            <label>Item ${i + 1}</label>

            <input type="text"
                   name="customs[]"
                   maxlength="1"
                   value="${existingCustoms[i] || ''}"
                   autocomplete="off"
                   required>

            <select class="form-control"
                    name="fonts[]"
                    required>
                <option value="Bold"
                    ${(existingFonts[i] || 'Bold') === 'Bold' ? 'selected' : ''}>
                    Bold
                </option>

                <option value="Monoline"
                    ${(existingFonts[i] || 'Bold') === 'Monoline' ? 'selected' : ''}>
                    Monoline
                </option>
            </select>
        `;

        grid.appendChild(item);
    }

    container.style.display = 'block';

    // Auto-advance
    const inputs = grid.querySelectorAll('input');

    inputs.forEach((inp, idx, arr) => {
        inp.addEventListener('input', () => {
            if (inp.value.length === 1 && idx < arr.length - 1) {
                arr[idx + 1].focus();
            }
        });
    });

    // Focus first empty
    for (const inp of inputs) {
        if (!inp.value) {
            inp.focus();
            break;
        }
    }
}

// Auto-generate on Enter in qty input
document.querySelectorAll('.qty-input').forEach(inp => {
    inp.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const id = inp.id.replace('qty_', '');
            generateCustomInputs(id, inp.value);
        }
    });
});

// ── Delete modal ────────────────────────────────────────────────────────────
function confirmDelete(idpomitra, variant, custom) {
    document.getElementById('deleteIdpomitra').value = idpomitra;
    document.getElementById('deleteModalDesc').textContent =
        `Hapus baris "${variant}"${custom ? ' (inisial: ' + custom + ')' : ''}?`;
    document.getElementById('deleteModal').classList.add('open');
}

function closeModal() {
    document.getElementById('deleteModal').classList.remove('open');
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Auto-dismiss flash
const flash = document.querySelector('.flash');
if (flash) setTimeout(() => flash.style.opacity = '0', 3500);
</script>

</body>
</html>