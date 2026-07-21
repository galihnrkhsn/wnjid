<?php
session_start();
error_reporting(0);

include 'floatingbutton.php';
include 'koneksi.php';
include 'assets/components/Sessions/sesDistri.php';
include 'settingdatatables.php';

$idpoproduk = $_GET['id'];
$invoice     = $_GET['invoice'];
$idadmin     = $_SESSION['idadmin'];

// ── Data PO utama ────────────────────────────────────────────────────────────
$sql_po = "SELECT
               poproduk.namapo,
               poproduk.status,
               poproduk.note,
               poproduk.pembayaran,
               pomitra.ket,
               pomitra.tgl,
               pomitra.waktu,
               pomitra.invoice,
               pomitra.status AS status_mitra
           FROM poproduk
           INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
           WHERE poproduk.idpoproduk = '$idpoproduk'
             AND pomitra.idmitra     = '$idadmin'
             AND pomitra.invoice     = '$invoice'
           LIMIT 1";
$datapo = mysqli_fetch_assoc(mysqli_query($koneksi, $sql_po));

$note          = $datapo['note'];
$pembayaranpo  = $datapo['pembayaran'];
$status_mitra  = $datapo['status_mitra'];

// ── Data user / mitra ─────────────────────────────────────────────────────────
$queryUser = $koneksi
    ->query("SELECT * FROM admin_mitra WHERE idadmin='$idadmin'")
    ->fetch_assoc();

// ── Item-item PO (dengan kolom custom) ───────────────────────────────────────
$sql_items = "SELECT
                  podetail.variant,
                  podetail.harga,
                  pomitra.jumlah,
                  pomitra.total,
                  pomitra.idpomitra,
                  pomitra.custom,
                  poproduk.namapo,
                  poproduk.diskon,
                  pomitra.template,
                  pomitra.font
              FROM pomitra
              JOIN podetail  ON podetail.idpodetail   = pomitra.idpodetail
              JOIN poproduk  ON poproduk.idpoproduk   = pomitra.idpoproduk
              WHERE pomitra.invoice     = '$invoice'
                AND pomitra.idpoproduk  = '$idpoproduk'
                AND pomitra.jumlah      > 0";
$result_items = mysqli_query($koneksi, $sql_items);
$items        = [];
while ($row = mysqli_fetch_assoc($result_items)) {
    $items[] = $row;
}

// ── Hitung totalan ────────────────────────────────────────────────────────────
$total_qty    = 0;
$jumlah_harga = 0;
foreach ($items as $item) {
    $total_qty    += $item['jumlah'];
    $jumlah_harga += $item['jumlah'] * $item['harga'];
}

$persen_diskon  = 35;
$diskon         = $persen_diskon / 100 * $jumlah_harga;
$subtotal       = $jumlah_harga - $diskon;

$persen_dp      = 50;
$dp             = $subtotal * $persen_dp / 100;
$pelunasan      = $subtotal - $dp;

if ($pembayaranpo === 'Lunas') {
    $dp        = $subtotal;
    $pelunasan = 0;
}

// ── Status pembayaran yang sudah dikonfirmasi ─────────────────────────────────
$sqldp   = mysqli_query($koneksi,
    "SELECT jmlhtransfer, jmlh_lunas FROM popembayaran WHERE invoice='$invoice' LIMIT 1");
$datadp  = mysqli_fetch_assoc($sqldp);

// ── Tgl batas konfirmasi DP ───────────────────────────────────────────────────
$tgl_bayar  = '';
$waktu_bayar = '23:59:59';

$sqlpo_tgl = mysqli_query($koneksi,
    "SELECT tgl_bayar FROM bukapo WHERE idpoproduk='$idpoproduk' LIMIT 1");
$datapo_tgl = mysqli_fetch_assoc($sqlpo_tgl);
$tgl_bayar  = $datapo_tgl['tgl_bayar'] ?? '';

if ($datapo['ket'] === 'Perpanjang' || $tgl_bayar === '') {
    date_default_timezone_set('Asia/Jakarta');
    $tgl_bayar   = date('Y-m-d', strtotime('+1 days', strtotime($datapo['tgl'])));
    $waktu_bayar = $datapo['waktu'];
}

// ── Progres pengiriman ────────────────────────────────────────────────────────
$sql_progres = "SELECT
                    pomitra.idpomitra,
                    pomitra.jumlah,
                    pomitra.custom,
                    podetail.variant,
                    SUM(surat_jalan_po.progres) AS progres
                FROM pomitra
                INNER JOIN podetail       ON podetail.idpodetail     = pomitra.idpodetail
                LEFT  JOIN surat_jalan_po ON pomitra.idpomitra       = surat_jalan_po.idpomitra
                WHERE pomitra.idmitra     = '$idadmin'
                  AND pomitra.idpoproduk  = '$idpoproduk'
                  AND pomitra.invoice     = '$invoice'
                  AND pomitra.jumlah      > 0
                GROUP BY podetail.variant, pomitra.idpomitra, pomitra.jumlah, pomitra.custom
                ORDER BY podetail.variant ASC";
$result_progres = mysqli_query($koneksi, $sql_progres);
$progres_rows   = [];
while ($row = mysqli_fetch_assoc($result_progres)) {
    $progres_rows[] = $row;
}

$sum_jumlah  = array_sum(array_column($progres_rows, 'jumlah'));
$sum_progres = array_sum(array_column($progres_rows, 'progres'));
$sum_sisa    = $sum_jumlah - $sum_progres;

// ── Akses "Ubah PO" ───────────────────────────────────────────────────────────
$bukapo_list = [];
if ($status_mitra === 'Belum DP') {
    $res_bpo = $koneksi->query("SELECT bukapo.*, poproduk.namapo
        FROM bukapo
        INNER JOIN poproduk ON bukapo.idpoproduk = poproduk.idpoproduk
        WHERE poproduk.idpoproduk = '$idpoproduk'
        --   AND bukapo.status = 'PUBLISH'
          AND (bukapo.jenis_mitra = 'Semua Mitra' OR bukapo.jenis_mitra = 'Distributor')");
    while ($b = $res_bpo->fetch_assoc()) {
        $bukapo_list[] = $b;
    }
}

$jenisPO = $koneksi->query("SELECT jenis_po FROM bukapo WHERE idpoproduk = '$idpoproduk'")->fetch_assoc();

// ── Route "Ubah PO" ───────────────────────────────────────────────────────────
function ubahpo_url(array $b, $idpoproduk, $invoice): string {
    return match($b['jenis_po']) {
        'PO dengan Stok'         => "ubahpostok?id=$idpoproduk&invoice=$invoice",
        'PO Mandiri'             => "ubahpo_mandiri?id=$idpoproduk&invoice=$invoice",
        'PO Custom Tab Stok'     => "ubahpostok.php?id=$idpoproduk&invoice=$invoice",
        'PO Custom Tab Stok Max' => "ubahpo_tabmax.php?id=$idpoproduk",
        'PO Custom Inisial'      => "ubahpo_custom_inisial.php?id=$idpoproduk&invoice=$invoice",
        'PO Custom Template'     => "ubahpo_custom_template.php?id=$idpoproduk&invoice=$invoice",
        default                  => "ubahpo?id=$idpoproduk&invoice=$invoice",
    };
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice PO | Wanoja</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ── Reset & base ──────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #f4f6fb;
            --surface:   #ffffff;
            --border:    #e4e8f0;
            --text:      #1a1d2e;
            --muted:     #6b7280;
            --primary:   #2563eb;
            --primary-l: #eff4ff;
            --success:   #16a34a;
            --success-l: #f0fdf4;
            --warning:   #d97706;
            --warning-l: #fffbeb;
            --danger:    #dc2626;
            --danger-l:  #fef2f2;
            --radius:    12px;
            --shadow:    0 1px 3px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.06);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding-bottom: 6rem;
        }

        /* ── Layout ────────────────────────────────────────────────────── */
        .page { max-width: 760px; margin: 0 auto; padding: 1.5rem 1rem; }

        /* ── Card ──────────────────────────────────────────────────────── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 1.25rem;
        }
        .card-pdf {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 100px;
        }
        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: .6rem;
            font-weight: 600;
            font-size: .85rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--muted);
        }
        .card-header .icon { font-size: 1rem; }
        .card-body { padding: 1.25rem; }

        /* ── Invoice header ────────────────────────────────────────────── */
        .inv-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 1.5rem 1.25rem 1.25rem;
        }
        .inv-head h1 { font-size: 1.35rem; font-weight: 700; }
        .inv-head .inv-no {
            font-size: .82rem;
            color: var(--muted);
            margin-top: .25rem;
        }
        .badge-status {
            display: inline-block;
            padding: .3rem .75rem;
            border-radius: 99px;
            font-size: .75rem;
            font-weight: 600;
        }
        .badge-status.belum   { background: var(--warning-l); color: var(--warning); }
        .badge-status.sudah   { background: var(--success-l); color: var(--success); }
        .badge-status.lunas   { background: var(--primary-l); color: var(--primary); }

        /* ── Info rows ─────────────────────────────────────────────────── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .6rem 1.5rem;
        }
        @media(max-width:480px){ .info-grid { grid-template-columns: 1fr; } }
        .info-item label {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--muted);
            display: block;
            margin-bottom: .15rem;
        }
        .info-item span { font-size: .9rem; font-weight: 600; }

        /* ── Tabs ──────────────────────────────────────────────────────── */
        .tab-bar {
            display: flex;
            gap: .25rem;
            padding: .5rem;
            background: var(--bg);
            border-radius: 10px;
            margin-bottom: 1.25rem;
        }
        .tab-btn {
            flex: 1;
            padding: .55rem;
            border: none;
            border-radius: 8px;
            background: transparent;
            font-family: inherit;
            font-size: .85rem;
            font-weight: 600;
            color: var(--muted);
            cursor: pointer;
            transition: background .2s, color .2s;
        }
        .tab-btn.active {
            background: var(--surface);
            color: var(--primary);
            box-shadow: 0 1px 4px rgba(0,0,0,.1);
        }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        /* ── Table ─────────────────────────────────────────────────────── */
        .tbl-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; font-size: .85rem; }
        thead th {
            background: var(--bg);
            padding: .65rem .8rem;
            text-align: left;
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        tbody td {
            padding: .7rem .8rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: var(--bg); }
        tfoot td {
            padding: .7rem .8rem;
            border-top: 2px solid var(--border);
            font-weight: 700;
            font-size: .85rem;
        }
        .pill {
            display: inline-block;
            padding: .2rem .55rem;
            border-radius: 99px;
            font-size: .72rem;
            font-weight: 600;
        }
        .pill.done    { background: var(--success-l); color: var(--success); }
        .pill.ongoing { background: var(--warning-l); color: var(--warning); }

        /* ── Summary rows ──────────────────────────────────────────────── */
        .summary {
            display: flex;
            flex-direction: column;
            gap: .5rem;
            margin-top: 1rem;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .6rem .75rem;
            border-radius: 8px;
            font-size: .88rem;
        }
        .summary-row.total    { background: var(--bg); }
        .summary-row.discount { background: var(--danger-l); color: var(--danger); }
        .summary-row.subtotal { background: var(--primary-l); font-weight: 700; color: var(--primary); font-size: .95rem; }
        .summary-row.dp       { background: var(--warning-l); color: var(--warning); font-weight: 600; }
        .summary-row.pelunasan{ background: var(--success-l); color: var(--success); font-weight: 600; }
        .summary-row label    { font-weight: 500; }
        .summary-row span     { font-weight: inherit; }

        /* ── Payment status box ────────────────────────────────────────── */
        .pay-status {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
            margin-top: 1rem;
        }
        .pay-box {
            border-radius: 10px;
            padding: .9rem 1rem;
            text-align: center;
        }
        .pay-box .pay-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .04em; color: var(--muted); }
        .pay-box .pay-value { font-size: 1rem; font-weight: 700; margin-top: .25rem; }
        .pay-box.dp-box   { background: var(--warning-l); }
        .pay-box.dp-box .pay-value { color: var(--warning); }
        .pay-box.lun-box  { background: var(--success-l); }
        .pay-box.lun-box .pay-value { color: var(--success); }

        /* ── Bank info ─────────────────────────────────────────────────── */
        .bank-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .6rem;
        }
        @media(max-width:480px){ .bank-grid { grid-template-columns: 1fr; } }
        .bank-item {
            background: var(--bg);
            border-radius: 10px;
            padding: .8rem 1rem;
        }
        .bank-item .bank-name {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--muted);
            margin-bottom: .3rem;
        }
        .bank-item .bank-owner { font-size: .85rem; font-weight: 600; }
        .bank-item .bank-no    { font-size: .82rem; color: var(--primary); margin-top: .1rem; letter-spacing: .03em; }

        /* ── Note ──────────────────────────────────────────────────────── */
        .note-box {
            background: var(--warning-l);
            border-left: 3px solid var(--warning);
            border-radius: 0 8px 8px 0;
            padding: .75rem 1rem;
            font-size: .85rem;
        }
        .note-box strong { color: var(--warning); }

        /* ── Countdown ─────────────────────────────────────────────────── */
        .countdown {
            text-align: center;
            font-size: .82rem;
            color: var(--muted);
            margin-top: .5rem;
            font-weight: 500;
        }
        .countdown.expired { color: var(--danger); }

        /* ── Progress bar ──────────────────────────────────────────────── */
        .prog-bar-wrap {
            background: var(--border);
            border-radius: 99px;
            height: 6px;
            margin-top: .5rem;
            overflow: hidden;
        }
        .prog-bar-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 99px;
            transition: width .6s ease;
        }

        /* ── Buttons ───────────────────────────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            padding: .6rem 1.25rem;
            border-radius: 8px;
            border: none;
            font-family: inherit;
            font-size: .85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: opacity .15s, transform .1s;
        }
        .btn:active { transform: scale(.97); }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-success { background: var(--success); color: #fff; }
        .btn-outline {
            background: var(--surface);
            color: var(--text);
            border: 1.5px solid var(--border);
        }
        .btn:hover { opacity: .88; }
        .btn-row {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
            justify-content: center;
            margin-top: 1rem;
        }

        /* ── Section title ─────────────────────────────────────────────── */
        .section-title {
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--muted);
            font-weight: 700;
            margin-bottom: .75rem;
        }
        .divider { border: none; border-top: 1px solid var(--border); margin: 1.25rem 0; }
    </style>
</head>
<body>
<?php include "assets/components/Navbar/navbar.php"; ?>

<div class="page">

    <!-- ── Invoice Header ──────────────────────────────────────────────── -->
    <div class="card">
        <div class="inv-head">
            <div>
                <h1>Sales Invoice</h1>
                <div class="inv-no">No. <?= htmlspecialchars($invoice) ?></div>
            </div>
            <?php
                $badge_class = match(true) {
                    $status_mitra === 'Sudah DP'   => 'sudah',
                    $status_mitra === 'Lunas'       => 'lunas',
                    default                         => 'belum',
                };
            ?>
            <span class="badge-status <?= $badge_class ?>">
                <?= htmlspecialchars($status_mitra) ?>
            </span>
        </div>

        <div class="card-body" style="padding-top: 0">
            <div class="info-grid">
                <div class="info-item">
                    <label>Nama Mitra</label>
                    <span><?= htmlspecialchars($queryUser['namamitra']) ?></span>
                </div>
                <div class="info-item">
                    <label>Nama PO</label>
                    <span><?= htmlspecialchars($datapo['namapo']) ?></span>
                </div>
                <div class="info-item">
                    <label>Alamat</label>
                    <span><?= htmlspecialchars($queryUser['alamat']) ?></span>
                </div>
                <div class="info-item">
                    <label>Tgl. Order</label>
                    <span><?= htmlspecialchars($datapo['tgl']) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Tabs ────────────────────────────────────────────────────────── -->
    <div class="tab-bar">
        <button class="tab-btn active" onclick="switchTab('info', this)">📄 Info Invoice</button>
        <button class="tab-btn"        onclick="switchTab('progres', this)">📦 Info Progres</button>
    </div>

    <!-- ══ TAB: INFO INVOICE ═════════════════════════════════════════════ -->
    <div id="tab-info" class="tab-panel active">

        <!-- Tabel item -->
        <div class="card">
            <div class="card-header"><span class="icon">🛍️</span> Detail Pesanan</div>
            <div class="tbl-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Varian</th>
                            <?php if ($jenisPO['jenis_po'] == 'PO Custom Inisial') : ?>
                                <th>Custom</th>
                                <th>Font</th>
                            <?php else : ?>
                                <th>Template</th>
                            <?php endif; ?>
                            <th style="text-align:center">Qty</th>
                            <th style="text-align:right">Satuan</th>
                            <th style="text-align:right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $i => $item): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($item['variant']) ?></td>
                            <?php if ($jenisPO['jenis_po'] == 'PO Custom Inisial') : ?>
                                <td><?= htmlspecialchars($item['custom'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($item['font'] ?? '-') ?></td>
                            <?php else : ?>
                                <td><?= htmlspecialchars($item['template'] ?? '-') ?></td>
                            <?php endif; ?>
                            <td style="text-align:center"><?= (int)$item['jumlah'] ?></td>
                            <td style="text-align:right">Rp <?= number_format($item['harga']) ?></td>
                            <td style="text-align:right">Rp <?= number_format($item['jumlah'] * $item['harga']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total</td>
                            <td style="text-align:center"><?= $total_qty ?></td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right">Rp <?= number_format($jumlah_harga) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="card-body">
                <!-- Summary kalkulasi -->
                <div class="summary">
                    <div class="summary-row total">
                        <label>Subtotal (<?= $total_qty ?> item)</label>
                        <span>Rp <?= number_format($jumlah_harga) ?></span>
                    </div>
                    <div class="summary-row discount">
                        <label>Diskon DB <?= $persen_diskon ?>%</label>
                        <span>− Rp <?= number_format($diskon) ?></span>
                    </div>
                    <div class="summary-row subtotal">
                        <label>Total Bayar</label>
                        <span>Rp <?= number_format($subtotal) ?></span>
                    </div>
                </div>

                <?php if ($pembayaranpo !== 'Lunas'): ?>
                <!-- Rincian DP & Pelunasan -->
                <div class="pay-status">
                    <div class="pay-box dp-box">
                        <div class="pay-label">DP 50%</div>
                        <div class="pay-value">Rp <?= number_format($dp) ?></div>
                    </div>
                    <div class="pay-box lun-box">
                        <div class="pay-label">Pelunasan 50%</div>
                        <div class="pay-value">Rp <?= number_format($pelunasan) ?></div>
                    </div>
                </div>

                <?php if ($datadp): ?>
                <div class="summary" style="margin-top:.75rem">
                    <div class="summary-row dp">
                        <label>Sudah Transfer DP</label>
                        <span>Rp <?= number_format($datadp['jmlhtransfer']) ?></span>
                    </div>
                    <div class="summary-row pelunasan">
                        <label>Sudah Transfer Pelunasan</label>
                        <span>Rp <?= number_format($datadp['jmlh_lunas']) ?></span>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <!-- Note -->
                <?php if (!empty($note)): ?>
                <hr class="divider">
                <div class="note-box"><strong>Note:</strong> <?= htmlspecialchars($note) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── Rekening ────────────────────────────────────────────────── -->
        <div class="card">
            <div class="card-header"><span class="icon">🏦</span> Rekening Transfer WNJ.ID 2025</div>
            <div class="card-body">
                <div class="bank-grid">
                    <?php $banks = [
                        ['Mandiri',  'CV KAFAA BILLAHI SYAHIDA', '1300026727597'],
                        ['BCA',      'Maria Ulfah Fathimah',     '7751616671'],
                        ['BRI',      'Maria Ulfah Fathimah',     '114101000949560'],
                        ['Muamalat', 'Maria Ulfah Fathimah',     '1100003930'],
                        ['BSI',      'Maria Ulfah Fathimah',     '7105696706'],
                    ]; ?>
                    <?php foreach ($banks as [$bname, $bowner, $bno]): ?>
                    <div class="bank-item">
                        <div class="bank-name"><?= $bname ?></div>
                        <div class="bank-owner"><?= $bowner ?></div>
                        <div class="bank-no"><?= $bno ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ── Tombol aksi ──────────────────────────────────────────────── -->
        <div class="card-pdf">
            <div class="card-body">
                <div class="btn-row">
                    <a class="btn btn-danger"
                       href="invoice.php?idmitra=<?= $idadmin ?>&id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>">
                        ⬇️ Download PDF
                    </a>

                    <?php if ($status_mitra !== 'Belum DP'): ?>
                    <a class="btn btn-success"
                       href="excel_po?invoice=<?= $invoice ?>">
                        📊 Excel Invoice
                    </a>
                    <?php endif; ?>
                </div>

                <?php if (!$datadp && $status_mitra === 'Belum DP'): ?>
                <div class="btn-row" style="margin-top:.5rem">
                    <a class="btn btn-primary"
                       href="popembayaran.php?invoice=<?= $invoice ?>&total=<?= $dp ?>&bayar=<?= $subtotal ?>&idpo=<?= $idpoproduk ?>&jenis=dp">
                        💳 Konfirmasi DP
                    </a>
                </div>
                <div class="countdown" id="countdown-dp"></div>
                <?php endif; ?>

                <?php if ($status_mitra === 'Sudah DP'): ?>
                <div class="btn-row" style="margin-top:.5rem">
                    <a class="btn btn-success"
                       href="popembayaran.php?invoice=<?= $invoice ?>&total=<?= $pelunasan ?>&idpo=<?= $idpoproduk ?>&jenis=lunas">
                        ✅ Konfirmasi Pelunasan
                    </a>
                </div>
                <?php endif; ?>

                <!-- Tombol Ubah PO -->
                <?php foreach ($bukapo_list as $bpo): ?>
                <div class="btn-row" style="margin-top:.5rem">
                    <a class="btn btn-warning" id="linkmiki<?= $bpo['idbpo'] ?>"
                       href="<?= ubahpo_url($bpo, $idpoproduk, $invoice) ?>">
                        ✏️ Ubah <?= htmlspecialchars($bpo['namapo']) ?>
                    </a>
                </div>
                <div class="countdown" id="demomiki<?= $bpo['idbpo'] ?>"></div>
                <script>
                (function(){
                    var deadline = new Date("<?= $bpo['tgl_ubah'] ?> 23:59:00").getTime();
                    var el    = document.getElementById("demomiki<?= $bpo['idbpo'] ?>");
                    var elBtn = document.getElementById("linkmiki<?= $bpo['idbpo'] ?>");
                    var t = setInterval(function(){
                        var dist = deadline - Date.now();
                        if (dist < 0) {
                            clearInterval(t);
                            el.textContent = "Waktu ubah sudah berakhir";
                            el.classList.add("expired");
                            elBtn.style.display = "none";
                            return;
                        }
                        var d = Math.floor(dist/86400000),
                            h = Math.floor(dist%86400000/3600000),
                            m = Math.floor(dist%3600000/60000),
                            s = Math.floor(dist%60000/1000);
                        el.textContent = "Sisa waktu: " + d + "h " + h + "j " + m + "m " + s + "d";
                    }, 1000);
                })();
                </script>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ══ TAB: PROGRES ══════════════════════════════════════════════════ -->
    <div id="tab-progres" class="tab-panel">
        <?php
            $pct = $sum_jumlah > 0
                ? round($sum_progres / $sum_jumlah * 100)
                : 0;
        ?>
        <div class="card-pdf">
            <div class="card-header"><span class="icon">📦</span> Progres Pengiriman</div>
            <div class="card-body">
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <span style="font-size:.85rem;font-weight:600">Total Progres</span>
                    <span style="font-size:.85rem;font-weight:700;color:var(--primary)"><?= $pct ?>%</span>
                </div>
                <div class="prog-bar-wrap">
                    <div class="prog-bar-fill" style="width:<?= $pct ?>%"></div>
                </div>
                <div style="display:flex;gap:1.5rem;margin-top:.75rem;font-size:.8rem;color:var(--muted)">
                    <span>✅ Terkirim: <strong style="color:var(--success)"><?= $sum_progres ?></strong></span>
                    <span>🕐 Sisa: <strong style="color:var(--warning)"><?= $sum_sisa ?></strong></span>
                    <span>📦 Total: <strong><?= $sum_jumlah ?></strong></span>
                </div>
            </div>
            <div class="tbl-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Varian</th>
                            <?php if ($jenisPO['jenis_po'] == 'PO Custom Inisial') : ?>
                                <th>Custom</th>
                            <?php else : ?>
                                <th>Template</th>
                            <?php endif; ?>
                            <th style="text-align:center">Qty PO</th>
                            <th style="text-align:center">Progres</th>
                            <th style="text-align:center">Sisa</th>
                            <th style="text-align:center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($progres_rows as $i => $pr):
                            $sisa = $pr['jumlah'] - ($pr['progres'] ?? 0);
                        ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($pr['variant']) ?></td>
                            <td><?= htmlspecialchars($pr['custom'] ?? '-') ?></td>
                            <td style="text-align:center"><?= (int)$pr['jumlah'] ?></td>
                            <td style="text-align:center"><?= (int)($pr['progres'] ?? 0) ?></td>
                            <td style="text-align:center"><?= $sisa ?></td>
                            <td style="text-align:center">
                                <?php if ($sisa == 0): ?>
                                    <span class="pill done">Selesai</span>
                                <?php else: ?>
                                    <span class="pill ongoing">Progres</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total</td>
                            <td style="text-align:center"><?= $sum_jumlah ?></td>
                            <td style="text-align:center"><?= $sum_progres ?></td>
                            <td style="text-align:center"><?= $sum_sisa ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div><!-- /page -->

<!-- Countdown DP -->
<?php if (!$datadp && $status_mitra === 'Belum DP'): ?>
<script>
(function(){
    var deadline = new Date("<?= $tgl_bayar ?> <?= $waktu_bayar ?>").getTime();
    var el = document.getElementById("countdown-dp");
    var t = setInterval(function(){
        var dist = deadline - Date.now();
        if (dist < 0) {
            clearInterval(t);
            el.textContent = "Batas waktu konfirmasi DP sudah berakhir.";
            el.classList.add("expired");
            var btn = document.querySelector('.btn-primary');
            if (btn) btn.style.display = "none";
            return;
        }
        var d = Math.floor(dist/86400000),
            h = Math.floor(dist%86400000/3600000),
            m = Math.floor(dist%3600000/60000),
            s = Math.floor(dist%60000/1000);
        el.textContent = "Sisa waktu DP: " + d + "h " + h + "j " + m + "m " + s + "d";
    }, 1000);
})();
</script>
<?php endif; ?>

<script>
function switchTab(name, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}
</script>

<?php include 'menubawah.php'; ?>
</body>
</html>