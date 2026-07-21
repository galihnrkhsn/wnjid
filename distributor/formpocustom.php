<?php
session_start();
error_reporting(0);

include 'floatingbutton.php';
include 'koneksi.php';
include 'assets/components/Sessions/sesDistri.php';

$idpoproduk = $_GET['id'];
$idadmin    = $_SESSION['idadmin'];

// ── Info PO ───────────────────────────────────────────────────────────────────
$rowPO = $koneksi->query("
    SELECT poproduk.idpoproduk, poproduk.namapo, poproduk.status
    FROM poproduk
    WHERE poproduk.idpoproduk = '$idpoproduk'
    LIMIT 1
")->fetch_assoc();

$namapo = $rowPO['namapo'] ?? '';

// ── Hardcode: foto & ketentuan per idpoproduk ─────────────────────────────────
// Tambah/ubah entri sesuai ID produk baru
$po_config = [
    // '999' => [
    //     'foto'      => 'assets/img/po/nama-produk.jpg',
    //     'ketentuan' => ["Poin ketentuan 1", "Poin ketentuan 2"],
    // ],
];

// Foto produk — fallback ke placeholder jika ID tidak terdaftar
$foto_po = $po_config[$idpoproduk]['foto'] ?? '';

// Ketentuan — array of string, fallback ke ketentuan default
$ketentuan_list = $po_config[$idpoproduk]['ketentuan'] ?? [
    'Isi qty sesuai jumlah item yang dipesan per varian.',
    'Setiap item wajib diberi <strong>1 inisial</strong> (huruf atau angka, maks 1 karakter).',
    'Inisial digunakan untuk identifikasi barang saat pengiriman.',
    'Qty 0 berarti tidak memesan varian tersebut.',
    'Pastikan semua inisial terisi sebelum mengirim formulir.',
    'Pesanan yang sudah dikirim <strong>tidak dapat diulang</strong>.',
];

// ── Cek apakah sudah pernah order ─────────────────────────────────────────────
$rowCek = $koneksi->query("
    SELECT COUNT(*) AS total_inv, idpoproduk, invoice
    FROM pomitra
    WHERE idpoproduk = '$idpoproduk' AND idmitra = '$idadmin'
")->fetch_assoc();

$sudahOrder = ($rowCek['total_inv'] > 0);

// ── Ambil semua variant ───────────────────────────────────────────────────────
$variants = [];
$resVar = $koneksi->query("
    SELECT podetail.idpodetail, podetail.variant, podetail.harga, pokategori.idpo
    FROM poproduk
    INNER JOIN pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
    INNER JOIN podetail   ON pokategori.idpo       = podetail.idpo
    WHERE poproduk.idpoproduk = '$idpoproduk'
    ORDER BY podetail.idpodetail ASC
");
while ($r = $resVar->fetch_assoc()) $variants[] = $r;

$rowBukapo = $koneksi->query("SELECT * FROM bukapo WHERE idpoproduk='$idpoproduk'")->fetch_assoc();
date_default_timezone_set('Asia/Jakarta');

$flash = null;

if (isset($_POST['save'])) {
    try {
        $checkExists = $koneksi->query("
            SELECT COUNT(*) AS count FROM pomitra
            WHERE idmitra='$idadmin' AND idpoproduk='$idpoproduk'
              AND idmitraagen IS NULL AND idmitrareseller IS NULL AND idmitramarketer IS NULL
        ")->fetch_assoc()['count'];

        if ($checkExists > 0) throw new Exception("Data sudah ada, tidak boleh mengirim ulang!");

        $waktu      = date("H:i:s");
        $idpodetail = $_POST['idpodetail'] ?? [];
        $jmlh       = $_POST['jmlh']       ?? [];
        $custom     = $_POST['custom']     ?? [];
        $font       = $_POST['font']       ?? [];
        $totalQty   = array_sum($jmlh);

        if ($idpoproduk == '486' && $totalQty > 2)
            throw new Exception("Total Qty tidak boleh lebih dari 2!");

        if (isset($_GET['invoice'])) {
            $invoice = $_GET['invoice'];
        } else {
            function generateAngkaAcak($length) {
                $s = '';
                for ($i = 0; $i < $length; $i++) $s .= mt_rand(0, 9);
                return $s;
            }
            $invoice = 'DCI' . $idpoproduk . '-' . $idadmin . generateAngkaAcak(5);
        }

        mysqli_begin_transaction($koneksi);
        $insertCount = 0;

        foreach ($idpodetail as $x => $idDetailNow) {
            $qty = (int)($jmlh[$x] ?? 0);
            if ($qty <= 0) continue;

            $detail = $koneksi->query("SELECT * FROM podetail WHERE idpodetail='$idDetailNow'")->fetch_assoc();
            if (!$detail) throw new Exception("Variant tidak ditemukan.");

            $harga = $detail['harga'];
            $idpo  = $detail['idpo'];

            $customArr    = $custom[$idDetailNow] ?? [];
            $fontArr      = $font[$idDetailNow] ?? [];
            $jumlahCustom = count($customArr);

            if ($jumlahCustom != $qty)
                throw new Exception("Jumlah inisial untuk variant {$detail['variant']} harus $qty buah.");

            foreach ($customArr as $idx => $inisial) {
                $inisial    = substr(trim($inisial), 0, 1);
                
                if (!in_array($fontNow, ['Bold', 'Monoline'])) {
                    $fontNow = 'Bold';
                }

                $fontNow    = $fontArr[$idx] ?? 'Bold';

                if (empty($inisial)) throw new Exception("Terdapat inisial kosong pada variant {$detail['variant']}.");
                if (!preg_match('/^[A-Za-z0-9]$/', $inisial)) {
                    throw new Exception("Inisial hanya boleh 1 huruf/angka.");
                }

                $ok = $koneksi->query("
                    INSERT INTO pomitra
                        (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, total, custom, font, invoice, status, tgl, waktu)
                    VALUES
                        (NULL, '$idadmin', '$idpoproduk', '$idpo', '$idDetailNow',
                         '1', '$harga', '$inisial', '$fontNow', '$invoice', 'Belum DP', NOW(), '$waktu')
                ");
                if (!$ok) throw new Exception("Gagal menyimpan. DB: " . $koneksi->error);
                $insertCount++;
            }
        }

        if ($insertCount <= 0) throw new Exception("Tidak ada data yang berhasil diproses.");

        mysqli_commit($koneksi);

        if (headers_sent($file, $line)) {
            die("Headers already sent in $file on line $line");
        }

        header("Location: datapocustom3.php?id=$idpoproduk&invoice=$invoice&flash=success");
        exit();

    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        $flash = ['type' => 'error', 'msg' => $e->getMessage()];
    }
}

if (isset($_GET['flash']) && $_GET['flash'] === 'success') {
    $flash = ['type' => 'success', 'msg' => 'Data berhasil dikirim!'];
}

// Redirect invoice
$invoiceUrl = ($idpoproduk == '405' || $idpoproduk == '406' || $idpoproduk == '407')
    ? "datapokolibri3.php?id={$rowCek['idpoproduk']}&invoice={$rowCek['invoice']}"
    : "datapocustom3.php?id={$rowCek['idpoproduk']}&invoice={$rowCek['invoice']}";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form PO – <?= htmlspecialchars($namapo) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&family=Nunito+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #f5f7ff;
            --surface:   #ffffff;
            --border:    #e5e9f5;
            --text:      #181c2e;
            --muted:     #7280a0;
            --primary:   #4361ee;
            --primary-d: #3451d1;
            --primary-l: #eef1fd;
            --success:   #0ea572;
            --success-l: #ecfdf5;
            --warning:   #c47c0a;
            --warning-l: #fffbeb;
            --danger:    #e03131;
            --danger-l:  #fff5f5;
            --radius:    12px;
            --shadow:    0 2px 10px rgba(67,97,238,.08), 0 0 0 1px rgba(0,0,0,.04);
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding-bottom: 7rem;
        }

        /* ── Page ── */
        .page { max-width: 680px; margin: 0 auto; padding: 1.25rem 1rem 2rem; }

        /* ── Hero gambar produk ── */
        .hero {
            border-radius: var(--radius);
            overflow: hidden;
            background: linear-gradient(135deg, #dde3ff 0%, #f0f4ff 100%);
            margin-bottom: 1.25rem;
            position: relative;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero img {
            width: 100%;
            max-height: 320px;
            object-fit: cover;
            display: block;
        }
        .hero-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .5rem;
            padding: 3rem 1rem;
            color: var(--muted);
        }
        .hero-placeholder .ico { font-size: 3rem; }
        .hero-placeholder span { font-size: .85rem; }
        .hero-badge {
            position: absolute;
            top: .85rem;
            left: .85rem;
            background: var(--primary);
            color: #fff;
            font-size: .72rem;
            font-weight: 700;
            padding: .3rem .7rem;
            border-radius: 99px;
            letter-spacing: .03em;
        }

        /* ── Header teks ── */
        .po-head { margin-bottom: 1.25rem; }
        .po-head h1 {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -.01em;
            line-height: 1.3;
        }
        .po-head .invoice-no {
            font-size: .78rem;
            color: var(--muted);
            margin-top: .25rem;
            font-family: 'Nunito Mono', monospace;
        }

        /* ── Flash ── */
        .flash {
            display: flex;
            align-items: flex-start;
            gap: .6rem;
            padding: .8rem 1rem;
            border-radius: var(--radius);
            font-size: .85rem;
            font-weight: 600;
            margin-bottom: 1rem;
            animation: slideIn .25s ease;
        }
        .flash.success { background: var(--success-l); color: var(--success); border: 1px solid #6ee7b7; }
        .flash.error   { background: var(--danger-l);  color: var(--danger);  border: 1px solid #fca5a5; }
        @keyframes slideIn { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:none; } }

        /* ── Card ── */
        .card {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 1rem;
        }
        .card-header {
            padding: .9rem 1.1rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: .5rem;
            font-weight: 700;
            font-size: .82rem;
            letter-spacing: .03em;
            text-transform: uppercase;
            color: var(--muted);
        }
        .card-body { padding: 1.1rem; }

        /* ── Ketentuan ── */
        .ketentuan-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }
        .ketentuan-list li {
            display: flex;
            align-items: flex-start;
            gap: .55rem;
            font-size: .86rem;
            line-height: 1.5;
        }
        .ketentuan-list li .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--primary);
            flex-shrink: 0;
            margin-top: .45rem;
        }
        .ketentuan-raw {
            font-size: .86rem;
            line-height: 1.65;
            color: var(--text);
            white-space: pre-wrap;
        }

        /* ── Variant card ── */
        .variant-block {
            border-bottom: 1px solid var(--border);
            padding: 1rem 1.1rem;
        }
        .variant-block:last-child { border-bottom: none; }

        .vb-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .5rem;
            margin-bottom: .75rem;
        }
        .vb-name {
            font-size: .95rem;
            font-weight: 700;
        }
        .vb-price {
            font-size: .78rem;
            color: var(--muted);
            font-family: 'Nunito Mono', monospace;
            background: var(--primary-l);
            color: var(--primary);
            padding: .2rem .55rem;
            border-radius: 6px;
            font-weight: 700;
        }

        .qty-row {
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .qty-label {
            font-size: .78rem;
            font-weight: 600;
            color: var(--muted);
            white-space: nowrap;
        }
        .qty-stepper {
            display: flex;
            align-items: center;
            gap: 0;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }
        .qty-stepper button {
            width: 34px;
            height: 34px;
            border: none;
            background: var(--bg);
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            color: var(--primary);
            transition: background .15s;
            flex-shrink: 0;
        }
        .qty-stepper button:hover { background: var(--primary-l); }
        .qty-stepper input {
            width: 52px;
            height: 34px;
            border: none;
            border-left: 1.5px solid var(--border);
            border-right: 1.5px solid var(--border);
            text-align: center;
            font-family: 'Nunito Mono', monospace;
            font-size: .9rem;
            font-weight: 700;
            color: var(--text);
            background: var(--surface);
        }
        .qty-stepper input:focus { outline: none; background: var(--primary-l); }

        /* ── Custom inisial grid ── */
        .inisial-wrapper {
            margin-top: .85rem;
            display: none;
        }
        .inisial-label-row {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .05em;
            color: var(--muted);
            margin-bottom: .5rem;
        }
        .inisial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(68px, 1fr));
            gap: .5rem;
        }
        .inisial-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .25rem;
        }
        .inisial-item label {
            font-size: .68rem;
            color: var(--muted);
            font-weight: 600;
        }
        .inisial-item input {
            width: 100%;
            height: 38px;
            border: 2px solid var(--border);
            border-radius: 8px;
            text-align: center;
            font-family: 'Nunito Mono', monospace;
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary);
            background: var(--primary-l);
            transition: border-color .15s, box-shadow .15s;
        }
        .inisial-item input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67,97,238,.15);
        }

        /* ── Note box ── */
        .note-box {
            background: var(--warning-l);
            border-left: 3px solid #f59e0b;
            border-radius: 0 8px 8px 0;
            padding: .7rem 1rem;
            font-size: .82rem;
            color: var(--warning);
            font-weight: 600;
            margin: 0 1.1rem 1rem;
        }

        /* ── Sudah order banner ── */
        .order-banner {
            background: var(--success-l);
            border: 1.5px solid #6ee7b7;
            border-radius: var(--radius);
            padding: 1.1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .order-banner .ico { font-size: 2rem; }
        .order-banner h3 { font-size: .95rem; font-weight: 800; color: var(--success); margin-bottom: .2rem; }
        .order-banner p  { font-size: .8rem; color: var(--muted); }

        /* ── Bottom bar ── */
        .bottom-bar {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: var(--surface);
            border-top: 1px solid var(--border);
            padding: .85rem 1rem;
            display: flex;
            justify-content: center;
            gap: .75rem;
            z-index: 100;
            box-shadow: 0 -4px 20px rgba(67,97,238,.08);
        }

        /* ── Btn ── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            padding: .65rem 1.4rem;
            border-radius: 10px;
            border: none;
            font-family: 'Nunito', sans-serif;
            font-size: .88rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: opacity .15s, transform .1s;
        }
        .btn:active { transform: scale(.97); }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-d); }
        .btn-success { background: var(--success); color: #fff; }
        .btn-ghost   { background: var(--bg); color: var(--text); border: 1.5px solid var(--border); }
        .btn-ghost:hover { border-color: var(--primary); color: var(--primary); }
        .btn-lg { padding: .75rem 2rem; font-size: .95rem; }

        /* ── Counter badge ── */
        .counter-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .6rem 1.1rem;
            background: var(--primary-l);
            border-top: 1px solid #c7d2fe;
            font-size: .8rem;
            font-weight: 700;
            color: var(--primary);
        }

        @media (max-width: 480px) {
            .inisial-grid { grid-template-columns: repeat(auto-fill, minmax(56px, 1fr)); }
            .btn-lg { padding: .65rem 1.25rem; font-size: .88rem; }
        }
    </style>
</head>
<body>

<?php include "assets/components/Navbar/navbar.php"; ?>

<div class="page">

    <!-- ── Gambar Produk ─────────────────────────────────────────────────── -->
    <div class="hero">
        <span class="hero-badge">📦 PO Open</span>
        <?php if (!empty($foto_po)): ?>
            <img src="<?= htmlspecialchars($foto_po) ?>"
                 alt="<?= htmlspecialchars($namapo) ?>"
                 onerror="this.style.display='none';document.getElementById('hero-placeholder').style.display='flex'">
            <div class="hero-placeholder" id="hero-placeholder" style="display:none">
                <span class="ico">🛍️</span><span><?= htmlspecialchars($namapo) ?></span>
            </div>
        <?php else: ?>
            <div class="hero-placeholder">
                <span class="ico">🛍️</span><span><?= htmlspecialchars($namapo) ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- ── Judul PO ──────────────────────────────────────────────────────── -->
    <div class="po-head">
        <h1><?= htmlspecialchars($namapo) ?></h1>
    </div>

    <!-- ── Flash ─────────────────────────────────────────────────────────── -->
    <?php if ($flash): ?>
    <div class="flash <?= $flash['type'] ?>" id="flash-msg">
        <?= $flash['type'] === 'success' ? '✅' : '⚠️' ?>
        <?= htmlspecialchars($flash['msg']) ?>
    </div>
    <?php endif; ?>

    <!-- ── Sudah order? ───────────────────────────────────────────────────── -->
    <?php if ($sudahOrder): ?>
    <div class="order-banner">
        <div class="ico">✅</div>
        <div>
            <h3>Pesanan Sudah Masuk</h3>
            <p>Kamu sudah melakukan pemesanan untuk PO ini.</p>
        </div>
    </div>
    <div style="text-align:center;margin-bottom:1rem">
        <a href="<?= $invoiceUrl ?>" class="btn btn-success btn-lg">🧾 Lihat Invoice</a>
    </div>

    <?php else: /* ── FORM ORDER ── */ ?>

    <!-- ── Ketentuan PO ───────────────────────────────────────────────────── -->
    <div class="card">
        <div class="card-header">📋 Ketentuan PO</div>
        <div class="card-body">
            <ul class="ketentuan-list">
                <?php foreach ($ketentuan_list as $poin): ?>
                <li><span class="dot"></span><?= $poin ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- ── Form Pesanan ───────────────────────────────────────────────────── -->
    <form method="POST" id="mainForm" novalidate>
        <div class="card">
            <div class="card-header">🛍️ Pilih Varian & Jumlah</div>

            <div class="note-box">
                ⚠️ Isi dengan angka <strong>0</strong> jika tidak memesan varian tersebut.
            </div>

            <?php foreach ($variants as $idx => $row): ?>
            <div class="variant-block">
                <input type="hidden" name="idpodetail[]" value="<?= $row['idpodetail'] ?>">

                <div class="vb-top">
                    <div class="vb-name"><?= htmlspecialchars($row['variant']) ?></div>
                    <div class="vb-price">Rp <?= number_format($row['harga']) ?></div>
                </div>

                <div class="qty-row">
                    <span class="qty-label">Jumlah:</span>
                    <div class="qty-stepper">
                        <button type="button" onclick="stepQty(<?= $row['idpodetail'] ?>, -1)">−</button>
                        <input type="number"
                               id="qty_<?= $row['idpodetail'] ?>"
                               name="jmlh[]"
                               min="0" max="999"
                               value="0"
                               data-id="<?= $row['idpodetail'] ?>"
                               class="qty-input"
                               oninput="generateInisial(<?= $row['idpodetail'] ?>, this.value)">
                        <button type="button" onclick="stepQty(<?= $row['idpodetail'] ?>, 1)">+</button>
                    </div>
                </div>

                <!-- Custom inisial -->
                <div class="inisial-wrapper" id="iw_<?= $row['idpodetail'] ?>">
                    <div class="inisial-label-row">✏️ Inisial per item</div>
                    <div class="inisial-grid" id="ig_<?= $row['idpodetail'] ?>"></div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Summary counter -->
            <div class="counter-wrap">
                <span>Total Qty dipilih</span>
                <span id="totalQtyDisplay">0 item</span>
            </div>
        </div>

        <div style="text-align:center" class="mb-5">
            <button type="submit" name="save" class="btn btn-primary btn-lg">
                🚀 Kirim Pesanan
            </button>
        </div>
    </form>

    <?php endif; ?>

</div><!-- /page -->

<?php include 'menubawah.php'; ?>

<script>
// ── Stepper ──────────────────────────────────────────────────────────────────
function stepQty(id, delta) {
    const inp = document.getElementById('qty_' + id);
    let val = parseInt(inp.value) || 0;
    val = Math.max(0, val + delta);
    inp.value = val;
    generateInisial(id, val);
    updateTotal();
}

// ── Generate inisial inputs ───────────────────────────────────────────────────
function generateInisial(idpodetail, qty) {
    qty = parseInt(qty) || 0;
    const wrapper = document.getElementById('iw_' + idpodetail);
    const grid    = document.getElementById('ig_' + idpodetail);

    if (qty <= 0) {
        wrapper.style.display = 'none';
        grid.innerHTML = '';
        updateTotal();
        return;
    }

    // Preserve existing values
    const prevInisial = [];
    const prevFont = [];

    grid.querySelectorAll('.inisial-item').forEach(item => {
        prevInisial.push(item.querySelector('input')?.value || '');
        prevFont.push(item.querySelector('select')?.value || 'Bold');
    });

    grid.innerHTML = '';

    for (let i = 0; i < qty; i++) {
        const div = document.createElement('div');
        div.className = 'inisial-item';

        div.innerHTML = `
            <label>Item ${i + 1}</label>

            <input type="text"
                name="custom[${idpodetail}][]"
                maxlength="1"
                placeholder="A"
                value="${prevInisial[i] || ''}"
                required>

            <select class="form-control" name="font[${idpodetail}][]" required>
                <option value="Bold" ${(prevFont[i] || 'Bold') === 'Bold' ? 'selected' : ''}>
                    Bold
                </option>
                <option value="Monoline" ${(prevFont[i] || 'Bold') === 'Monoline' ? 'selected' : ''}>
                    Monoline
                </option>
            </select>
        `;

        grid.appendChild(div);
    }

    wrapper.style.display = 'block';

    // Auto-advance: ketik 1 karakter → pindah ke input berikutnya
    grid.querySelectorAll('input').forEach((inp, idx, arr) => {
        inp.addEventListener('input', () => {
            if (inp.value.length === 1 && idx < arr.length - 1) {
                arr[idx + 1].focus();
            }
        });
    });

    // Focus first empty
    const inputs = grid.querySelectorAll('input');
    for (const i of inputs) { if (!i.value) { i.focus(); break; } }

    updateTotal();
}

// ── Total qty counter ─────────────────────────────────────────────────────────
function updateTotal() {
    let total = 0;
    document.querySelectorAll('.qty-input').forEach(i => {
        total += parseInt(i.value) || 0;
    });
    document.getElementById('totalQtyDisplay').textContent = total + ' item';
}

// ── Auto-dismiss flash ────────────────────────────────────────────────────────
const flash = document.getElementById('flash-msg');
if (flash) setTimeout(() => { flash.style.opacity = '0'; flash.style.transition = 'opacity .5s'; }, 3500);

// ── Form validation sebelum submit ───────────────────────────────────────────
document.getElementById('mainForm')?.addEventListener('submit', function(e) {
    const inisialInputs = document.querySelectorAll('.inisial-item input');
    for (const inp of inisialInputs) {
        if (!inp.value.trim()) {
            e.preventDefault();
            inp.focus();
            inp.style.borderColor = 'var(--danger)';
            alert('Pastikan semua inisial terisi sebelum mengirim!');
            return;
        }
    }
});
</script>
</body>
</html>