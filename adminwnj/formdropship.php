<?php
    session_start();
    include 'koneksi.php';

    if (!isset($_SESSION["administrator"])) {
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $invoice = trim($_GET["invoice"] ?? '');

    $stmtInvoice = $koneksi->prepare("SELECT
                                        pm.idpoproduk,
                                        pm.idmitra, pm.idmitraagen, pm.idmitrareseller, pm.idmitramarketer,
                                        COALESCE(ma.idadmin, mr.idadmin, mm.idadmin, pm.idmitra) AS idadmin_induk,
                                        COALESCE(adm.namamitra, ma.namaagen, mr.namaagen, mm.namaagen) AS nama_pengirim,
                                        COALESCE(adm.whatsapp, ma.whatsapp, mr.whatsapp, mm.whatsapp) AS wa_pengirim,
                                        pp.namapo
                                    FROM pomitra pm
                                        LEFT JOIN admin_mitra adm ON pm.idmitra = adm.idadmin
                                        LEFT JOIN mitraagen ma ON pm.idmitraagen = ma.idmitraagen
                                        LEFT JOIN mitrareseller mr ON pm.idmitrareseller = mr.idmitrareseller
                                        LEFT JOIN mitramarketer mm ON pm.idmitramarketer = mm.idmitramarketer
                                        LEFT JOIN poproduk pp ON pm.idpoproduk = pp.idpoproduk
                                    WHERE pm.invoice = ?
                                    LIMIT 1");
    $stmtInvoice->bind_param('s', $invoice);
    $stmtInvoice->execute();
    $mitra = $stmtInvoice->get_result()->fetch_assoc();

    if ($invoice === '' || !$mitra) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <link href="css/sb-admin-2.min.css" rel="stylesheet">
        </head>
        <body class="text-center" style="padding-top:4rem;">
            <p class="text-danger font-weight-bold">Invoice tidak ditemukan.</p>
            <a href="daftards.php">Kembali</a>
        </body>
        </html>
        <?php
        exit;
    }

    $idpoproduk         = (int) $mitra['idpoproduk'];
    $idadmin            = (int) ($mitra['idadmin_induk'] ?? 0);
    $idmitraagenVal     = (int) ($mitra['idmitraagen'] ?? 0);
    $idmitraresellerVal = (int) ($mitra['idmitrareseller'] ?? 0);
    $idmitramarketerVal = (int) ($mitra['idmitramarketer'] ?? 0);
    $namaProduk         = $mitra['namapo'] ?? '';
    $namaPengirimDefault = $mitra['nama_pengirim'] ?? '';
    $waPengirimDefault   = $mitra['wa_pengirim'] ?? '';

    $errors = [];

    // ------------------------------------------------------------------
    // Ambil daftar variant invoice ini + sisa yang belum masuk pengiriman
    // manapun (jumlah dipesan - total yang sudah pernah dimasukkan ke pods).
    // ------------------------------------------------------------------
    $stmtVariant = $koneksi->prepare("SELECT podetail.idpodetail, podetail.variant, pomitra.idpomitra, pomitra.jumlah, pomitra.custom
                                        FROM pomitra
                                        JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                        WHERE pomitra.invoice = ? AND pomitra.jumlah > 0
                                        ORDER BY pomitra.idpomitra ASC");
    $stmtVariant->bind_param('s', $invoice);
    $stmtVariant->execute();
    $variantRows = $stmtVariant->get_result()->fetch_all(MYSQLI_ASSOC);

    // idpodetail 8920 (aturan lama, dipertahankan): progres dihitung per idpomitra,
    // bukan gabungan semua baris pomitra dengan idpodetail yang sama.
    $stmtSisaKhusus = $koneksi->prepare("SELECT SUM(pods.jumlah) AS progresnya
                                            FROM pods
                                            JOIN pomitra ON pomitra.idpomitra = pods.idpomitra
                                            WHERE pods.invoice = ? AND pods.idpodetail = ? AND pomitra.idpomitra = ?");
    $stmtSisaUmum = $koneksi->prepare("SELECT SUM(jumlah) AS progresnya FROM pods WHERE invoice = ? AND idpodetail = ?");

    $variantList = [];
    foreach ($variantRows as $row) {
        $idpodetail = (int) $row['idpodetail'];
        $idpomitra  = (int) $row['idpomitra'];

        if ($idpodetail === 8920) {
            $stmtSisaKhusus->bind_param('sii', $invoice, $idpodetail, $idpomitra);
            $stmtSisaKhusus->execute();
            $progres = (int) ($stmtSisaKhusus->get_result()->fetch_assoc()['progresnya'] ?? 0);
        } else {
            $stmtSisaUmum->bind_param('si', $invoice, $idpodetail);
            $stmtSisaUmum->execute();
            $progres = (int) ($stmtSisaUmum->get_result()->fetch_assoc()['progresnya'] ?? 0);
        }

        $sisa = (int) $row['jumlah'] - $progres;
        if ($sisa > 0) {
            $variantList[] = [
                'idpodetail' => $idpodetail,
                'idpomitra'  => $idpomitra,
                'variant'    => $row['variant'],
                'custom'     => $row['custom'],
                'sisa'       => $sisa,
            ];
        }
    }

    // ------------------------------------------------------------------
    // Submit form: buat header podropship, lalu (kalau ada variant dipilih)
    // langsung buat no_ds + baris pods-nya juga, dalam satu langkah.
    // ------------------------------------------------------------------
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim'])) {
        $namapengirim   = trim($_POST['namapengirim'] ?? '');
        $tlppengirim    = trim($_POST['tlppengirim'] ?? '');
        $namapenerima   = trim($_POST['namapenerima'] ?? '');
        $tlppenerima    = trim($_POST['tlppenerima'] ?? '');
        $alamatpenerima = trim($_POST['alamatpenerima'] ?? '');
        $keterangan     = trim($_POST['keterangan'] ?? '');
        $ekspedisi      = trim($_POST['ekspedisi'] ?? '');

        [$provinsi]  = explode('|', $_POST['prov'] ?? '') + [null];
        [$kabupaten] = explode('|', $_POST['kabupaten'] ?? '') + [null];
        [$kecamatan] = explode('|', $_POST['kecamatan'] ?? '') + [null];

        if ($namapengirim === '' || $tlppengirim === '' || $namapenerima === '' || $tlppenerima === '' || $alamatpenerima === '') {
            $errors[] = 'Lengkapi semua data pengiriman yang wajib diisi.';
        }

        // Kumpulkan variant yang dicentang + jumlahnya, validasi terhadap sisa yang tersedia.
        $variantTerpilih = [];
        $checkedIds      = $_POST['pilih_variant'] ?? [];
        foreach ($variantList as $v) {
            if (!in_array((string) $v['idpodetail'], $checkedIds, true)) {
                continue;
            }
            $qty = (int) ($_POST['jumlah_' . $v['idpodetail']] ?? 0);
            if ($qty <= 0) {
                continue;
            }
            if ($qty > $v['sisa']) {
                $errors[] = 'Jumlah "' . $v['variant'] . '" melebihi sisa yang tersedia (' . $v['sisa'] . ').';
                continue;
            }
            $variantTerpilih[] = ['idpodetail' => $v['idpodetail'], 'idpomitra' => $v['idpomitra'], 'jumlah' => $qty];
        }

        // Aturan lama untuk PO 186/187 (produk hampers/box): total qty yang dimasukkan
        // dalam satu pengiriman harus kelipatan 3.
        if (empty($errors) && in_array($idpoproduk, [186, 187], true) && !empty($variantTerpilih)) {
            $totalQtyBaru = array_sum(array_column($variantTerpilih, 'jumlah'));
            if ($totalQtyBaru % 3 !== 0) {
                $errors[] = 'Total jumlah variant yang dipilih harus kelipatan 3 untuk produk ini.';
            }
        }

        if (empty($errors)) {
            $koneksi->begin_transaction();
            try {
                $stmtInsert = $koneksi->prepare("INSERT INTO podropship
                                                    (iddropship, idadmin, idmitraagen, idmitrareseller, idmitramarketer,
                                                     idpoproduk, invoice, namapengirim, tlppengirim,
                                                     namapenerima, tlppenerima, alamatpenerima,
                                                     provinsi, kota, kecamatan, keterangan, ekspedisi)
                                                    VALUES (NULL, ?, ?, ?, ?,
                                                            ?, ?, ?, ?,
                                                            ?, ?, ?,
                                                            ?, ?, ?, ?, ?)");
                $stmtInsert->bind_param(
                    'iiiiisssssssssss',
                    $idadmin, $idmitraagenVal, $idmitraresellerVal, $idmitramarketerVal,
                    $idpoproduk, $invoice, $namapengirim, $tlppengirim,
                    $namapenerima, $tlppenerima, $alamatpenerima,
                    $provinsi, $kabupaten, $kecamatan, $keterangan, $ekspedisi
                );
                $stmtInsert->execute();
                $iddropshipBaru = $stmtInsert->insert_id;

                if (!empty($variantTerpilih)) {
                    $noDs = $invoice . '-' . $iddropshipBaru;

                    $stmtPods = $koneksi->prepare("INSERT INTO pods (id, no_ds, invoice, idpodetail, idpomitra, jumlah, waktu)
                                                    VALUES (NULL, ?, ?, ?, ?, ?, NOW())");
                    foreach ($variantTerpilih as $v) {
                        $stmtPods->bind_param('ssiii', $noDs, $invoice, $v['idpodetail'], $v['idpomitra'], $v['jumlah']);
                        $stmtPods->execute();
                    }

                    $stmtNoDs = $koneksi->prepare("UPDATE podropship SET no_ds = ? WHERE iddropship = ?");
                    $stmtNoDs->bind_param('si', $noDs, $iddropshipBaru);
                    $stmtNoDs->execute();
                }

                $koneksi->commit();
                echo "<script>alert('Data berhasil ditambah');</script>";
                echo "<script>location='detaildropship.php?id=" . urlencode($invoice) . "';</script>";
                exit;
            } catch (Throwable $e) {
                $koneksi->rollback();
                error_log($e->getMessage());
                $errors[] = 'Terjadi kesalahan saat menyimpan data, silakan coba lagi.';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Form Dropship | WNJ.ID</title>

    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .variant-row {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .6rem .25rem;
            border-bottom: 1px solid #e3e6f0;
        }
        .variant-row:last-child { border-bottom: none; }
        .variant-row .variant-name {
            flex: 1;
            min-width: 0;
        }
        .variant-row .variant-sisa {
            font-size: .75rem;
            color: #858796;
            background: #f8f9fc;
            border-radius: 999px;
            padding: 1px 8px;
            white-space: nowrap;
        }
        .variant-row .variant-qty {
            width: 90px;
        }
        .variant-summary {
            font-size: .85rem;
            color: #5a5c69;
        }
        .empty-variant {
            padding: 1.5rem;
            text-align: center;
            color: #858796;
        }
        .section-title {
            font-weight: 700;
            color: #4e73df;
            margin-bottom: .5rem;
        }
    </style>
</head>

<body id="page-top" class="sidebar-toggled">

    <div id="wrapper">
        <?php
                include "sidebar.php";
        ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <a class="link" href="detaildropship.php?id=<?= urlencode($invoice) ?>"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </h1>
                    </div>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0 pl-3">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="post" id="formDropship">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            Form PO Dropship
                                            <small class="text-muted d-block font-weight-normal mt-1">
                                                Invoice <?= htmlspecialchars($invoice) ?><?= $namaProduk !== '' ? ' &mdash; ' . htmlspecialchars($namaProduk) : '' ?>
                                            </small>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="section-title">Pengirim</div>
                                        <div class="form-group">
                                            <label>Nama Pengirim</label>
                                            <input type="text" class="form-control" name="namapengirim" value="<?= htmlspecialchars($_POST['namapengirim'] ?? $namaPengirimDefault) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Telepon Pengirim</label>
                                            <input type="text" class="form-control" name="tlppengirim" value="<?= htmlspecialchars($_POST['tlppengirim'] ?? $waPengirimDefault) ?>" required>
                                        </div>

                                        <hr>

                                        <div class="section-title">Penerima</div>
                                        <div class="form-group">
                                            <label>Nama Penerima</label>
                                            <input type="text" class="form-control" name="namapenerima" value="<?= htmlspecialchars($_POST['namapenerima'] ?? '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Telepon Penerima</label>
                                            <input type="text" class="form-control" name="tlppenerima" value="<?= htmlspecialchars($_POST['tlppenerima'] ?? '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Alamat Penerima</label>
                                            <textarea class="form-control" name="alamatpenerima" rows="3" required><?= htmlspecialchars($_POST['alamatpenerima'] ?? '') ?></textarea>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="prov">Provinsi</label>
                                                <select class="form-control" id="prov" name="prov" required>
                                                    <option value="" disabled selected>~ Pilih ~</option>
                                                    <?php
                                                        $ambil = $koneksi->query("SELECT province_id, province_name FROM tb_ro_provinces");
                                                        while ($row = $ambil->fetch_assoc()):
                                                    ?>
                                                    <option value="<?= (int) $row['province_id'] ?>|<?= htmlspecialchars($row['province_name']) ?>"><?= htmlspecialchars($row['province_name']) ?></option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="kabupaten">Kota/Kabupaten</label>
                                                <select class="form-control" id="kabupaten" name="kabupaten" required></select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="kecamatan">Kecamatan</label>
                                                <select class="form-control" id="kecamatan" name="kecamatan" required></select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <textarea class="form-control" name="keterangan" rows="2"><?= htmlspecialchars($_POST['keterangan'] ?? '') ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Ekspedisi</label>
                                            <select id="ekspedisi" class="form-control" name="ekspedisi" required>
                                                <option value="" disabled selected>~ Pilih Ekspedisi ~</option>
                                                <optgroup label="JNE">
                                                    <option value="JNE Reg">JNE Reg</option>
                                                    <option value="JNE YES">JNE YES</option>
                                                    <option value="JNE Oke">JNE Oke</option>
                                                    <option value="JNE CTC">JNE CTC</option>
                                                    <option value="JTR">JNE Trucking (JTR)</option>
                                                </optgroup>
                                                <optgroup label="J&T">
                                                    <option value="J&T">J&T</option>
                                                    <option value="J&T Cargo">J&T Cargo</option>
                                                </optgroup>
                                                <optgroup label="WAHANA">
                                                    <option value="Wahana Ekspres">Wahana Ekspres</option>
                                                    <option value="Wahana Kargo">Wahana Kargo</option>
                                                </optgroup>
                                                <optgroup label="SICEPAT">
                                                    <option value="Sicepat BEST">Sicepat BEST</option>
                                                    <option value="Sicepat REG">Sicepat Reg</option>
                                                    <option value="Sicepat Kargo">Sicepat Cargo</option>
                                                </optgroup>
                                                <optgroup label="POS">
                                                    <option value="Pos Ekonomi Jumbo">Pos Ekonomi Jumbo</option>
                                                    <option value="Pos Kilat">Pos Kilat</option>
                                                </optgroup>
                                                <optgroup label="LAINNYA">
                                                    <option value="Paxel">Paxel</option>
                                                    <option value="SPX Express">SPX Express</option>
                                                    <option value="IDE">ID Express</option>
                                                    <option value="Ahsan">Ahsan</option>
                                                    <option value="Baraka">Baraka</option>
                                                    <option value="Pegasus">Pegasus</option>
                                                    <option value="Sentral">Sentral</option>
                                                    <option value="Lion Parcel">Lion Parcel</option>
                                                    <option value="Dakota">Dakota</option>
                                                    <option value="Indah Cargo">IndahCargo</option>
                                                    <option value="Adam Cargo">Adam Cargo</option>
                                                    <option value="Gosend">GoSend</option>
                                                    <option value="Kalog">Kalog</option>
                                                    <option value="CMC KARGO">CMC CARGO</option>
                                                    <option value="Tiki">Tiki</option>
                                                    <option value="Triplogic">Triplogic</option>
                                                    <option value="Anteraja">Anteraja</option>
                                                    <option value="Ambil ke Pusat">Ambil Ke Pusat</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">Pilih Variant untuk Pengiriman Ini</h6>
                                        <?php if (!empty($variantList)): ?>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="checkAllVariant">
                                                <label class="form-check-label" for="checkAllVariant">Pilih Semua</label>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <?php if (empty($variantList)): ?>
                                            <div class="empty-variant">
                                                <i class="fas fa-box-open fa-2x mb-2"></i><br>
                                                Semua variant pada invoice ini sudah pernah dimasukkan ke pengiriman sebelumnya.
                                            </div>
                                        <?php else: ?>
                                            <?php foreach ($variantList as $v): ?>
                                                <div class="variant-row">
                                                    <input type="checkbox" class="variant-check" name="pilih_variant[]" value="<?= $v['idpodetail'] ?>" data-max="<?= $v['sisa'] ?>" id="variant<?= $v['idpodetail'] ?>">
                                                    <label class="variant-name mb-0" for="variant<?= $v['idpodetail'] ?>">
                                                        <?= htmlspecialchars($v['variant']) ?>
                                                        <?php if (!empty($v['custom'])): ?> <span class="text-muted">(<?= htmlspecialchars($v['custom']) ?>)</span><?php endif; ?>
                                                    </label>
                                                    <span class="variant-sisa">sisa <?= $v['sisa'] ?></span>
                                                    <input type="number" class="form-control form-control-sm variant-qty" name="jumlah_<?= $v['idpodetail'] ?>" min="0" max="<?= $v['sisa'] ?>" value="0" disabled>
                                                </div>
                                            <?php endforeach; ?>
                                            <div class="variant-summary mt-3">
                                                Total dipilih: <strong id="totalQtyDipilih">0</strong> pcs
                                                <?php if (in_array($idpoproduk, [186, 187], true)): ?>
                                                    <span class="text-muted">(harus kelipatan 3)</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mb-4">
                            <button type="submit" class="btn btn-primary" name="kirim">Kirim</button>
                        </div>
                    </form>

                </div>

                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; WNJ.ID Development 2020</span>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="login.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script>
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#prov').change(function () {
                var provinsi = $('#prov').val();
                $.ajax({
                    type: 'GET',
                    url: 'cek_kabupaten2.php',
                    data: 'prov_id=' + provinsi,
                    success: function (data) {
                        $("#kabupaten").html(data);
                    }
                });
            });

            $('#kabupaten').change(function () {
                var kabupaten = $('#kabupaten').val();
                $.ajax({
                    type: 'GET',
                    url: 'cek_kecamatan2.php',
                    data: 'kabupaten_id=' + kabupaten,
                    success: function (data) {
                        $("#kecamatan").html(data);
                    }
                });
            });

            function recalcTotal() {
                var total = 0;
                $('.variant-check:checked').each(function () {
                    var qty = parseInt($(this).closest('.variant-row').find('.variant-qty').val(), 10);
                    total += isNaN(qty) ? 0 : qty;
                });
                $('#totalQtyDipilih').text(total);
            }

            $('.variant-check').change(function () {
                var row = $(this).closest('.variant-row');
                var qtyInput = row.find('.variant-qty');
                if ($(this).is(':checked')) {
                    qtyInput.prop('disabled', false).val($(this).data('max'));
                } else {
                    qtyInput.prop('disabled', true).val(0);
                }
                recalcTotal();
            });

            $('.variant-qty').on('input', recalcTotal);

            $('#checkAllVariant').change(function () {
                var checked = $(this).is(':checked');
                $('.variant-check').prop('checked', checked).trigger('change');
            });
        });
    </script>
</body>

</html>
