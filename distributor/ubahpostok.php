<?php
    error_reporting(0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $invoice    = $_GET['invoice'] ?? '';
    $idadmin    = $_SESSION["idadmin"];

    if ($idpoproduk <= 0 || $invoice === '' ) {
        header('Location: listnewpo.php');
        exit;
    }

    $stmtOwn = $koneksi->prepare("SELECT COUNT(*) as jumlah,
                        poproduk.idpoproduk,
                        poproduk.namapo,
                        poproduk.status
                    FROM poproduk
                    inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk
                    WHERE poproduk.idpoproduk = ?
                    AND pomitra.idmitra = ?
                    AND pomitra.invoice = ?
                ");
    $stmtOwn->bind_param('iss', $idpoproduk, $idadmin, $invoice);
    $stmtOwn->execute();
    $data = $stmtOwn->get_result()->fetch_assoc();

    if (!$data || $data['jumlah'] == 0) {
        header('Location: listnewpo.php');
        exit;
    }

    $stmtUser  = $koneksi->prepare("SELECT * FROM admin_mitra WHERE idadmin = ?");
    $stmtUser->bind_param('s', $idadmin);
    $stmtUser->execute();
    $queryUser = $stmtUser->get_result()->fetch_assoc();

    $excluded_sarung = [6356, 6357, 6358, 6359, 6360, 6366, 6367, 6368, 6369, 6370, 6371, 6372, 6373, 6540, 6541, 6542, 6543, 6544, 6550, 6551, 6552, 6553, 6554, 6555, 6556, 6557];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <title>Distributor | WNJ.ID</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5 justify-content-center">
        <p align="center"><strong>SALES INVOICE</strong></p>
        <p align="center"><strong><?= htmlspecialchars($data['namapo'] ?? '') ?></strong></p><br>
        <p align="left">Nama Mitra  : <?= htmlspecialchars($queryUser["namamitra"] ?? '') ?></p>
        <p align="left">Alamat  : <?= htmlspecialchars($queryUser["alamat"] ?? '') ?> </p>
        <p align="left">No Invoice  : <?= htmlspecialchars($invoice) ?></p>

        <?php if ($idpoproduk != 181 && $idpoproduk != 182 && $idpoproduk != 183): ?>
            <div class="border border-dark">
                <b>Sisa Stock:</b>
                <div class="row col-12">
                    <?php
                    $stmtStokList = $koneksi->prepare("SELECT * FROM pokategori WHERE idpoproduk = ? ORDER BY namakategori");
                    $stmtStokList->bind_param('i', $idpoproduk);
                    $stmtStokList->execute();
                    $query = $stmtStokList->get_result();
                    while ($stok = $query->fetch_assoc()) {
                    ?>
                        <?php if (!in_array($stok['idpo'], $excluded_sarung)) : ?>
                            <div class="col-2">
                                <?= htmlspecialchars($stok['namakategori']) ?>
                            </div>
                            <div class="col-2">
                                (<?= (int) $stok['stok'] ?>)
                            </div>
                            <br>
                        <?php endif; ?>
                    <?php } ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <tr>
                    <th>No</th>
                    <th>Ubah Qty</th>
                    <th>Qty</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                </tr>
                <?php
                $no = 1;
                $stmtRows = $koneksi->prepare("SELECT
                                                pokategori.namakategori,
                                                podetail.variant,
                                                pomitra.idpomitra,
                                                pomitra.idpo,
                                                pomitra.jumlah,
                                                pomitra.invoice,
                                                pomitra.total,
                                                podetail.harga
                                                FROM pomitra
                                                INNER JOIN pokategori ON pokategori.idpo=pomitra.idpo
                                                INNER JOIN podetail ON podetail.idpodetail=pomitra.idpodetail
                                                WHERE pomitra.idmitra = ?
                                                AND pomitra.idpoproduk = ?
                                                AND pomitra.invoice = ?
                                                ORDER BY podetail.idpodetail");
                $stmtRows->bind_param('sis', $idadmin, $idpoproduk, $invoice);
                $stmtRows->execute();
                $rowsResult = $stmtRows->get_result();

                while ($row = $rowsResult->fetch_assoc()) {
                ?>
                    <tr>
                        <td class="align-middle"><?= $no++; ?></td>
                        <form method="POST">
                            <input type="hidden" name="idpo" value="<?= (int) $row['idpo'] ?>">
                            <input type="hidden" name="idpomitra" value="<?= (int) $row['idpomitra'] ?>">
                            <input type="hidden" name="jumlahsebelum" value="<?= (int) $row['jumlah'] ?>">
                            <td class="align-middle">
                                <input type="number" min="0" name="jmlh" style="width: auto;">
                                <br>
                                <button type="submit" class="btn btn-primary" name="tambah">+</button>
                                <button type="submit" class="btn btn-danger" name="kurang">-</button>
                            </td>
                        </form>
                        <td class="align-middle"><?= (int) $row['jumlah'] ?></td>
                        <td class="align-middle"><?= htmlspecialchars($row['variant']) ?></td>
                        <td class="align-middle">
                            <?= $row['jumlah'] == 0 ? '0' : htmlspecialchars($row['harga']) ?>
                        </td>
                        <td class="align-middle"><?= htmlspecialchars($row['total']) ?></td>
                        <?php
                        $jumlah = ($jumlah ?? 0) + $row['total'];
                    }
                ?>
            </table><br>
            <a class="btn btn-primary" href="datapo.php?id=<?= $idpoproduk ?>&invoice=<?= htmlspecialchars($invoice) ?>">Simpan</a>
            <br><br>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP SYNTAK -->
    <?php
        if (isset($_POST["tambah"]) || isset($_POST["kurang"])) {
            $idpomitra = isset($_POST['idpomitra']) ? (int) $_POST['idpomitra'] : 0;
            $jmlh      = isset($_POST['jmlh']) ? max(0, (int) $_POST['jmlh']) : 0;

            // Ambil harga, idpo & kepemilikan langsung dari database, JANGAN percaya nilai dari form
            $stmtRow = $koneksi->prepare("SELECT pomitra.jumlah, pomitra.invoice, pomitra.idmitra, pomitra.idpoproduk,
                                                  podetail.harga, podetail.idpo
                                           FROM pomitra
                                           INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                           WHERE pomitra.idpomitra = ?");
            $stmtRow->bind_param('i', $idpomitra);
            $stmtRow->execute();
            $rowData = $stmtRow->get_result()->fetch_assoc();

            if (!$rowData || $rowData['idmitra'] !== $idadmin || $rowData['invoice'] !== $invoice || (int) $rowData['idpoproduk'] !== $idpoproduk) {
                echo "<script>alert('Data tidak valid atau bukan milik anda.');</script>";
                echo "<script>location='ubahpostok?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "';</script>";
                exit;
            }

            $harga = $rowData['harga'];
            $idpo  = $rowData['idpo'];
        }

        if (isset($_POST["tambah"])) {
            $stmtQty = $koneksi->prepare("SELECT jumlah FROM pomitra WHERE invoice = ?");
            $stmtQty->bind_param('s', $invoice);
            $stmtQty->execute();
            $current_qty_row = $stmtQty->get_result()->fetch_assoc();
            $current_qty     = $current_qty_row['jumlah'] ?? 0;

            $totalQty_setelah_update = $current_qty + $jmlh;
            if ($idpoproduk == 486 && $totalQty_setelah_update > 2) {
                echo "<script>alert('Total Kuantitas untuk produk ini tidak boleh lebih dari 2 setelah penambahan!');</script>";
                echo "<script>location='ubahpostok?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "';</script>";
                exit;
            }

            $stmtStok = $koneksi->prepare("SELECT stok FROM pokategori WHERE idpo = ?");
            $stmtStok->bind_param('i', $idpo);
            $stmtStok->execute();
            $sisa = $stmtStok->get_result()->fetch_assoc();

            if (($sisa['stok'] ?? 0) >= $jmlh) {
                $stmtUpdPomitra = $koneksi->prepare("UPDATE pomitra SET jumlah = jumlah + ?, total = jumlah * ? WHERE idpomitra = ? AND idmitra = ?");
                $stmtUpdPomitra->bind_param('idis', $jmlh, $harga, $idpomitra, $idadmin);
                $stmtUpdPomitra->execute();

                $stmtUpdStok = $koneksi->prepare("UPDATE pokategori SET stok = stok - ? WHERE idpo = ?");
                $stmtUpdStok->bind_param('ii', $jmlh, $idpo);
                $stmtUpdStok->execute();

                echo "<script>alert('data berhasil diubah');</script>";
                echo "<script>location='ubahpostok?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "';</script>";
            } else {
                echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
                echo "<script>location='ubahpostok?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "';</script>";
            }
        } elseif (isset($_POST["kurang"])) {
            $jumlahsebelum = isset($_POST["jumlahsebelum"]) ? (int) $_POST["jumlahsebelum"] : 0;
            $subjumlah     = $jumlahsebelum - $jmlh;

            if ($subjumlah < 0) {
                echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
                echo "<script>location='ubahpostok?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "';</script>";
            } else {
                $stmtUpdPomitra = $koneksi->prepare("UPDATE pomitra SET jumlah = jumlah - ? WHERE idpomitra = ? AND idmitra = ?");
                $stmtUpdPomitra->bind_param('iis', $jmlh, $idpomitra, $idadmin);
                $stmtUpdPomitra->execute();

                $stmtUpdTotal = $koneksi->prepare("UPDATE pomitra SET total = jumlah * ? WHERE idpomitra = ? AND idmitra = ?");
                $stmtUpdTotal->bind_param('dis', $harga, $idpomitra, $idadmin);
                $stmtUpdTotal->execute();

                $stmtUpdStok = $koneksi->prepare("UPDATE pokategori SET stok = stok + ? WHERE idpo = ?");
                $stmtUpdStok->bind_param('ii', $jmlh, $idpo);
                $stmtUpdStok->execute();

                echo "<script>alert('data berhasil diubah');</script>";
                echo "<script>location='ubahpostok?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "';</script>";
            }
        }
    ?>
        <?php
        if (isset($_POST["insert-variant"])) {
            $processed = false;
            $koneksi->begin_transaction();
            try {
                $hiddenProductInput = $_POST['hiddenProductInput'] ?? [];
                $variants           = $_POST['variant']            ?? [];
                $jumlahs            = $_POST['jumlah']              ?? [];

                foreach ($hiddenProductInput as $key => $index) {
                    $variant = isset($variants[$key]) ? (int) $variants[$key] : 0;
                    $jumlah  = isset($jumlahs[$key]) ? (int) $jumlahs[$key] : 0;
                    date_default_timezone_set('Asia/Jakarta');
                    $waktu = date("H:i:s");

                    if ($jumlah > 0) {
                        $stmtPodetail = $koneksi->prepare("SELECT * FROM podetail WHERE idpodetail = ?");
                        $stmtPodetail->bind_param('i', $variant);
                        $stmtPodetail->execute();
                        $podetail = $stmtPodetail->get_result()->fetch_assoc();

                        $stmtKategori = $koneksi->prepare("SELECT * FROM pokategori WHERE idpoproduk = ?");
                        $stmtKategori->bind_param('i', $idpoproduk);
                        $stmtKategori->execute();
                        $pokategori = $stmtKategori->get_result()->fetch_assoc();

                        if (($pokategori['stok'] ?? 0) >= $jumlah) {
                            $stmtUpdStok2 = $koneksi->prepare("UPDATE pokategori SET stok = stok - ? WHERE idpo = ?");
                            $stmtUpdStok2->bind_param('ii', $jumlah, $podetail['idpo']);
                            if (!$stmtUpdStok2->execute()) {
                                throw new Exception("Error updating stock in pokategori: " . $koneksi->error);
                            }

                            $harga2 = $podetail['harga'];
                            $total2 = $jumlah * $harga2;

                            $stmtInsertPomitra = $koneksi->prepare("INSERT INTO pomitra (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, total, invoice, status, tgl, waktu)
                                                                     VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, 'Belum DP', NOW(), ?)");
                            $stmtInsertPomitra->bind_param('siiiidss', $idadmin, $idpoproduk, $podetail['idpo'], $variant, $jumlah, $total2, $invoice, $waktu);
                            if (!$stmtInsertPomitra->execute()) {
                                throw new Exception("Error executing insert into Pomitra: " . $koneksi->error);
                            }
                        } else {
                            throw new Exception("Stok tidak mencukupi.");
                        }
                    } else {
                        throw new Exception("Jumlah harus lebih dari 0.");
                    }
                }

                $koneksi->commit();
                $processed = true;
            } catch (Exception $e) {
                $koneksi->rollback();
                echo "<script>alert('Error: " . htmlspecialchars(addslashes($e->getMessage())) . "');</script>";
            }

            if ($processed) {
                echo "<script>alert('Data berhasil dikirim');</script>";
                echo "<script>location='datapo.php?id=$idpoproduk&invoice=" . rawurlencode($invoice) . "';</script>";
            } else {
                echo "<script>alert('Tidak ada item yang diproses.')</script>";
                echo "<script>location='formpoku.php?id=$idpoproduk';</script>";
            }
        }
    ?>
    <!-- PHP SYNTAK END -->

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script type="text/javascript">
        const buttonAddVariant = document.getElementById("buttonAddVariant");
        const variantContainer = document.getElementById("variantContainer");
        let variantFormCount = 0;

        buttonAddVariant.addEventListener('click', function() {
            variantFormCount++;

            const variantForm = document.createElement('div');
            variantForm.className = "row mx-0";
            variantForm.id = `variantForm_${variantFormCount}`;
            variantForm.innerHTML = `
            <div class="col-12">
                <h6 class="badge text-bg-info text-uppercase">Variant ${variantFormCount + 1}</h6>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="variant" class="d-flex align-items-center">Produk<p class="text-danger p-0 m-0 ml-1">*</p></label>
                    <select name="variant[]" class="form-control form-control-sm" id="variantSelect_${variantFormCount}" onchange="updateHiddenProductInput(this, ${variantFormCount})">
                        <option value="" disabled selected>Silahkan pilih variant</option>
                        <?php
                            $stmtVariants = $koneksi->prepare(" SELECT DISTINCT podetail.variant, podetail.idpodetail, pomitra.custom
                                                        FROM poproduk
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                        LEFT JOIN pomitra ON pomitra.idpodetail = podetail.idpodetail AND pomitra.invoice = ?
                                                        WHERE poproduk.idpoproduk = ?
                                                        AND podetail.variant NOT LIKE '%Custom%'
                                                        AND pomitra.idpodetail IS NULL
                                                        ORDER BY podetail.idpodetail ASC
                                                        ");
                            $stmtVariants->bind_param('si', $invoice, $idpoproduk);
                            $stmtVariants->execute();
                            $ambil = $stmtVariants->get_result();
                            while ($optionRow = $ambil->fetch_assoc()) {
                                $custom = $optionRow['custom'];
                                ?>
                                <option value="<?= (int) $optionRow['idpodetail']; ?>">
                                    <?= htmlspecialchars($optionRow['variant']); ?><?= !empty($custom) ? " | " . htmlspecialchars($custom) : ""; ?>
                                </option>
                                <?php
                            }
                        ?>
                    </select>
                </div>
            </div>
            <input type="hidden" id="hiddenProductInput_${variantFormCount}" name="hiddenProductInput[]" value="">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="jumlah" class="d-flex align-items-center">Jumlah<p class="text-danger p-0 m-0 ml-1">*</p></label>
                    <input type="number" class="form-control form-control-sm" placeholder="Masukan Jumlah" name="jumlah[]" required>
                </div>
            </div>
            `;
            variantContainer.appendChild(variantForm);
        });

        function updateHiddenProductInput(selectElement, index) {
            const hiddenProductInput = document.getElementById(`hiddenProductInput_${index}`);
            hiddenProductInput.value = selectElement.value;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>
