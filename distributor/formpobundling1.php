<?php
    session_start();

    require_once 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';

    $idpoproduk = $_GET['id'] ?? 0;
    $idadmin    = $_SESSION["idadmin"] ?? 0;

    if (!$idpoproduk || !$idadmin) {
        die("Data tidak valid.");
    }

    $stmt   = $koneksi->prepare("SELECT namapo FROM poproduk WHERE idpoproduk = ?");
    $stmt->bind_param("s", $idpoproduk);
    $stmt->execute();
    $result = $stmt->get_result();
    $sql    = $result->fetch_assoc();
    $namapo = $sql['namapo'] ?? '-';
    $stmt->close();

    $variantQuery   = $koneksi->prepare("SELECT podetail.idpodetail, podetail.idpo, podetail.variant FROM podetail INNER JOIN pokategori ON pokategori.idpo = podetail.idpo WHERE pokategori.idpoproduk = ? AND podetail.idpo NOT IN (4413, 4382, 4351)");
    $variantQuery->bind_param("s", $idpoproduk);
    $variantQuery->execute();
    $variantResult  = $variantQuery->get_result();
    $rawVariants    = [];
    while ($row     = $variantResult->fetch_assoc()) {
        $rawVariants[] = $row;
    }
    $variantQuery->close();

    function generateVariantOptions($variants, $bundlingLabel) {
        $options = '';
        foreach ($variants as $row) {
            $value      = htmlspecialchars($row['idpodetail']) . ' | ' . htmlspecialchars($row['idpo']) . ' | ' . $bundlingLabel;
            $label      = htmlspecialchars($row['variant']);
            $options .= "<option value=\"$value\">$label</option>";
        }
        return $options;
    }
    $variantOptions = generateVariantOptions($rawVariants, "Bundling 1");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WNJ.ID | Form PO Bundling</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar bg-body-secondary">
        <div class="container">
            <a class="navbar-brand" href="detailpo.php?id=335"><i class="bi bi-chevron-left"></i></a>
            <span class="fw-bold text-uppercase fs-6">
                Pre Order
            </span>
            <span></span>
        </div>
    </nav>
    <div class="container mt-3">
        <div class="text-center">
            <h5>Formulir Pemesanan <?= htmlspecialchars($namapo) ?></h5>
        </div>
        <?php
            $jenis_po = $koneksi->query("SELECT * FROM bukapo WHERE idpoproduk = '$idpoproduk'")->fetch_assoc();
            if ($jenis_po['jenis_po'] === 'PO Custom Stok') :
        ?>
        <b>Sisa Stock:</b>
        <div class="row border border-secondary mx-auto">
            <?php
            $idpoproduk = $_GET['id'];
            $sql = "SELECT * from pokategori where idpoproduk='$idpoproduk' ORDER BY idpo";
            $query = $koneksi->query($sql);
            while ($stok = $query->fetch_assoc()) {
                ?>
                    <div class="col-3">
                        <?php echo $stok['namakategori']; ?>
                    </div>
                    <div class="col-3">
                        (<?php echo $stok['stok']; ?>)
                    </div>
                    <br>
            <?php } ?>
        </div>
        <?php endif; ?>
        <form method="post">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="beanie" role="tabpanel">
                    <section class="my-3">
                        <div class="card shadow">
                            <div class="card-body">
                                <div>
                                    <span class="btn btn-sm btn-success" id="buttonAddPack">Tambah Bundling</span>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-12">
                                        <p class="mb-0 fw-medium text-secondary">Bundling Ke - 1</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="mb-0">Jumlah</label>
                                        <input type="hidden" name="custom[]" value="Bundling 1">
                                        <input type="number" class="form-control form-control-sm" min="0" value="0" name="qty[]" required>
                                        <input type="hidden" name="qty[]" value="0">
                                        <input type="hidden" name="qty[]" value="0">
                                    </div>
                                    <?php for ($i = 1; $i <= 2; $i++): ?>
                                    <div class="col-sm-3">
                                        <label class="mb-0">Variant <?= $i ?></label>
                                        <select class="form-select form-select-sm" name="variant[]">
                                            <?= $variantOptions ?>
                                        </select>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                                <div id="demoPoInner"></div>
                            </div>
                        </div>
                    </section>
                </div>

                <?php
                $query = "SELECT COUNT(*) AS jumlah, poproduk.idpoproduk, poproduk.namapo, poproduk.status, pomitra.invoice FROM poproduk INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk WHERE poproduk.idpoproduk = ? AND pomitra.idmitra = ? AND COALESCE(pomitra.idmitraagen, pomitra.idmitrareseller, pomitra.idmitramarketer) IS NULL";
                $stmt = $koneksi->prepare($query);
                $stmt->bind_param("ss", $idpoproduk, $idadmin);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_assoc();
                ?>
                <button type="submit" name="kirim" class="btn btn-primary btn-sm">Kirim</button>
                <?php if ($result['jumlah'] < 1): ?>
                <?php else: ?>
                    <!-- <a href="datapobundling2.php?id=<?= $result['idpoproduk'] ?>&invoice=<?= $result['invoice'] ?>" class="btn btn-success btn-sm">Invoice</a> -->
                <?php endif; ?>
            </div>
        </form>
    </div>

    <?php
        if (isset($_POST['kirim'])) {
            try {
                $checkQuery     = $koneksi->query("SELECT COUNT(*) as count 
                                                    FROM pomitra 
                                                    WHERE idmitra = '$idadmin' 
                                                    AND idpoproduk = '$idpoproduk'
                                                    AND idmitraagen IS NULL
                                                    AND idmitrareseller IS NULL
                                                    AND idmitramarketer IS NULL
                                                ");
                $exists         = $checkQuery->fetch_assoc()['count'];

                // if ($exists > 0) {
                //     echo "<script>alert('Data sudah ada, tidak bisa mengirim ulang!');</script>";
                //     echo "<script>location='detailpo.php?id=$idpoproduk';</script>";
                //     exit();
                // }

                date_default_timezone_set('Asia/Jakarta');
                $today      = date('s');
                $waktu      = date('H:i:s');
                $qty        = $_POST['qty'];
                $variant    = $_POST['variant'];
                $count      = count($variant);

                if (isset($_GET['invoice'])) {
                    $invoice = $_GET['invoice'];
                } else {  
                    function generateAngkaAcak($length) {
                        $angka_acak = '';
                        for ($i = 0; $i < $length; $i++) {
                            $angka_acak .= mt_rand(0, 9);
                        }
                        return $angka_acak;
                    }
            
                    $angka_acak = generateAngkaAcak(7);
                    $invoice = 'D' . $idpoproduk . '-' . $idadmin . $angka_acak;
                }
                for ($x = 0; $x < $count; $x++) {
                    $qty_item       = $qty[$x];
                    $variant_item   = $variant[$x];
                    $data           = explode("|", $variant_item);
                    $idpodetail     = $data[0];
                    $idpo           = $data[1];
                    $custom         = $data[2];

                    if ($jenis_po['jenis_po'] === 'PO Custom Stok') {
                        $stockCheck     = $koneksi->query("SELECT stok, namakategori FROM pokategori WHERE idpo = $idpo")->fetch_assoc();
                        $stokTersedia   = (int)$stockCheck['stok'];
                        $kategori       = $stockCheck['namakategori'];

                        if ($stokTersedia <= 0) {
                            echo "<script>
                                alert('Stok untuk $kategori ini kosong: $stokTersedia!');
                                location='formpobundling2.php?id=$idpoproduk&jenis=Bundling';
                            </script>";
                            exit();
                        }

                        if ($qty_item > $stokTersedia) {
                            echo "<script>
                                alert('Stok $kategori melebihi stok tersedia: $stokTersedia!');
                                location='formpobundling2.php?id=$idpoproduk&jenis=Bundling';
                            </script>";
                            exit();
                        }

                        $stok_baru = $stokTersedia - $qty_item;
                        $updateStok = $koneksi->query("
                            UPDATE pokategori 
                            SET stok = '$stok_baru'
                            WHERE idpo = '$idpo'
                        ");
                    }
                    
                    $query          = $koneksi->query("SELECT * FROM podetail WHERE idpodetail = '$idpodetail'");
                    $data_produk    = $query->fetch_assoc();
                    $total_harga    = $data_produk['harga'];

                    $sql = $koneksi->query("INSERT INTO pomitra
                                                    (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, custom, total, invoice, status, tgl, waktu, is_custom)
                                                VALUES
                                                    (NULL, '$idadmin', '$idpoproduk', '$idpo', '$idpodetail', '$qty_item', '$custom', '$total_harga', '$invoice', 'Belum DP', NOW(), '$waktu', 'BUNDLING 3')
                                            ");
                    if ($sql) {
                        echo "
                            <script>
                                alert('Data sudah terkirim')
                                location='datapobundling2.php?id=$idpoproduk&invoice=$invoice'
                            </script>
                        ";
                    } else {
                        echo "
                            <script>
                                alert('Data gagal terkirim!')
                                location='formpobundling2.php?id=$idpoproduk'
                            </script>
                        ";
                    }
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    ?>
    <!-- JavaScript Bootstrap -->
    <script>
        const rawVariants = <?= json_encode($rawVariants) ?>;
        const buttonAddPack = document.getElementById("buttonAddPack");
        const demoPoInner = document.getElementById("demoPoInner");
        let formCount = 1;

        buttonAddPack.addEventListener("click", () => {
            formCount++;
            const bundlingLabel = `Bundling ${formCount}`;
            const variantOptions = rawVariants.map(v => {
                const val = `${v.idpodetail} | ${v.idpo} | ${bundlingLabel}`;
                return `<option value="${val}">${v.variant}</option>`;
            }).join('');

            const form = document.createElement("div");
            form.innerHTML = `
                <hr />
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <p class="mb-0 fw-medium text-secondary">${bundlingLabel}</p>
                    </div>
                    <div class="col-sm-3">
                        <label class="mb-0">Jumlah</label>
                        <input type="hidden" name="custom[]" value="${bundlingLabel}">
                        <input type="number" class="form-control form-control-sm" min="0" value="0" name="qty[]" required>
                        <input type="hidden" name="qty[]" value="0">
                        <input type="hidden" name="qty[]" value="0">
                    </div>
                    ${[1,2].map(i => `
                        <div class="col-sm-3">
                            <label class="mb-0">Variant ${i}</label>
                            <select class="form-select form-select-sm" name="variant[]">
                                ${variantOptions}
                            </select>
                        </div>`).join('')}
                </div>`;
            demoPoInner.appendChild(form);
        });

        document.addEventListener('input', function(event) {
            if (event.target && event.target.name.startsWith('qty')) {
                const value = event.target.value;
                const row = event.target.closest('.row');
                const inputs = row.querySelectorAll('input[type="hidden"]');
                inputs.forEach(input => input.value = value);
            }
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
</body>
</html>