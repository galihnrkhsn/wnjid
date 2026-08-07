<?php
    session_start();

    include 'koneksi.php';
    include 'assets/components/Sessions/sesAgen.php';
    include 'settingdatatables.php';

    $idpoproduk     = $_GET['id'];
    $invoice        = $_GET['invoice'];
    $idmitraagen    = $_SESSION["idmitraagen"];

    $getUser        = $koneksi->query("SELECT * FROM mitraagen WHERE idmitraagen = '$idmitraagen'");
    $dataUser       = $getUser->fetch_assoc();
    $idmitra        = $dataUser['idadmin'];

    $query_po       = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = '$idpoproduk'");
    $sql            = $query_po->fetch_assoc();
    $namapo         = $sql['namapo'];

    $queryData      = $koneksi->query("SELECT * FROM pomitra WHERE invoice = '$invoice' ORDER BY idpomitra DESC LIMIT 1");
    $dataPO         = $queryData->fetch_assoc();

    if ($dataPO) {
        $custom = $dataPO['custom'];

        // Ekstrak angka terakhir dari custom
        preg_match('/\d+/', $custom, $matches);
        $angkaTerakhir = isset($matches[0]) ? (int)$matches[0] : 0;

        // Tingkatkan angka
        $angkaBaru = $angkaTerakhir + 1;

        // Ganti angka lama dengan angka baru
        $customBaru = preg_replace('/\d+/', $angkaBaru, $custom);
    } else {
        echo "Data tidak di temukan";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WNJ.ID | Form Update PO Inner</title>

    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <!-- Icons Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navbar Start -->
    <nav class="navbar bg-body-secondary">
        <div class="container">
            <a class="navbar-brand" href="detailpo.php?id=335"><i class="bi bi-chevron-left"></i></a>
            <span class="fw-bold text-uppercase fs-6">
                Pre Order
            </span>
            <span></span>
        </div>
    </nav>
    <!-- Navbar End -->

    <div class="container mt-3">
        <div class="text-center">
            <h5>Formulir Pemesanan <?= $namapo ?></h5>
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
        <form method="post" class="">
            <div class="tab-content" id="myTabContent">
                <!-- INNER KNIT START -->
                <div class="tab-pane fade show active" id="beanie" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <section class="my-3">
                        <div class="card shadow">
                            <div class="card-body">
                                <div>
                                    <span class="btn btn-sm btn-success" id="buttonAddPack">Tambah Bundling</span>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-12">
                                        <p class="mb-0 fw-medium text-secondary">Bundling Ke - <?= $angkaBaru; ?></p>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="mb-0" for="pack">Jumlah</label>
                                            <input type="hidden" class="form-control form-control-sm" name="custom[]" value="Bundling <?= $angkaBaru ?>">
                                            <input type="number" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="pack" required>
                                            <input type="hidden" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="form1" required>
                                            <input type="hidden" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="form2" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="mb-0" for="v1">Variant 1</label>
                                            <select class="form-select form-select-sm" name="variant[]" required>
                                                <?php
                                                    $sql = $koneksi->query("SELECT 
                                                                                    *
                                                                                FROM
                                                                                    podetail
                                                                                        INNER JOIN
                                                                                    pokategori ON pokategori.idpo = podetail.idpo
                                                                                WHERE
                                                                                    pokategori.idpoproduk = '$idpoproduk'
                                                                        ");
                                                    while ($data = $sql->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Bundling <?= $angkaBaru; ?>"><?= $data['variant'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="mb-0" for="v1">Variant 2</label>
                                            <select class="form-select form-select-sm" name="variant[]">
                                                <?php
                                                    $sql = $koneksi->query("SELECT 
                                                                                    *
                                                                                FROM
                                                                                    podetail
                                                                                        INNER JOIN
                                                                                    pokategori ON pokategori.idpo = podetail.idpo
                                                                                WHERE
                                                                                    pokategori.idpoproduk = '$idpoproduk'
                                                                        ");
                                                    while ($data = $sql->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Bundling <?= $angkaBaru ?>"><?= $data['variant'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="mb-0" for="v1">Variant 3</label>
                                            <select class="form-select form-select-sm" name="variant[]">
                                                <?php
                                                    $sql = $koneksi->query("SELECT 
                                                                                    *
                                                                                FROM
                                                                                    podetail
                                                                                        INNER JOIN
                                                                                    pokategori ON pokategori.idpo = podetail.idpo
                                                                                WHERE
                                                                                    pokategori.idpoproduk = '$idpoproduk'
                                                                        ");
                                                    while ($data = $sql->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Bundling <?= $angkaBaru ?>"><?= $data['variant'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Yang akan di tampilkan ketika klik tambah pack -->
                                <div id="demoPoInner"></div>
                                <script type="text/javascript">
                                    const buttonAddPack = document.getElementById("buttonAddPack");
                                    const demoPoInner = document.getElementById("demoPoInner");
                                    let formCount = <?= $angkaBaru; ?>-1;

                                    buttonAddPack.addEventListener('click', function() {
                                        formCount++;

                                        // Buat Form Baru
                                        const form = document.createElement('div');
                                        form.innerHTML = `
                                            <hr />
                                            <div class="row mb-2">
                                                <div class="col-sm-12">
                                                    <p class="mb-0 fw-medium text-secondary">Bundling Ke - ${formCount + 1}</p>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="mb-0" for="pack">Jumlah</label>
                                                        <input type="hidden" class="form-control form-control-sm" name="custom[]" value="Bundling ${formCount + 1}">
                                                        <input type="number" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="pack${formCount + 1}" required>
                                                        <input type="hidden" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="form${formCount + 1}_1" required>
                                                        <input type="hidden" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="form${formCount + 1}_2" required>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="mb-0" for="v1">Variant 1</label>
                                                        <select class="form-select form-select-sm" name="variant[]" required>
        
                                                            <?php
                                                                $sql = $koneksi->query("SELECT 
                                                                                                *
                                                                                            FROM
                                                                                                podetail
                                                                                                    INNER JOIN
                                                                                                pokategori ON pokategori.idpo = podetail.idpo
                                                                                            WHERE
                                                                                                pokategori.idpoproduk = '$idpoproduk'
                                                                                    ");
                                                                while ($data = $sql->fetch_assoc()) {
                                                            ?>
                                                                <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Bundling ${formCount + 1}"><?= $data['variant'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="mb-0" for="v1">Variant 2</label>
                                                        <select class="form-select form-select-sm" name="variant[]">
        
                                                            <?php
                                                                $sql = $koneksi->query("SELECT 
                                                                                                *
                                                                                            FROM
                                                                                                podetail
                                                                                                    INNER JOIN
                                                                                                pokategori ON pokategori.idpo = podetail.idpo
                                                                                            WHERE
                                                                                                pokategori.idpoproduk = '$idpoproduk'
                                                                                    ");
                                                                while ($data = $sql->fetch_assoc()) {
                                                            ?>
                                                                <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Bundling ${formCount + 1}"><?= $data['variant'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="mb-0" for="v1">Variant 3</label>
                                                        <select class="form-select form-select-sm" name="variant[]">
        
                                                            <?php
                                                                $sql = $koneksi->query("SELECT 
                                                                                                *
                                                                                            FROM
                                                                                                podetail
                                                                                                    INNER JOIN
                                                                                                pokategori ON pokategori.idpo = podetail.idpo
                                                                                            WHERE
                                                                                                pokategori.idpoproduk = '$idpoproduk'
                                                                                    ");
                                                                while ($data = $sql->fetch_assoc()) {
                                                            ?>
                                                                <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Bundling ${formCount + 1}"><?= $data['variant'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        `;

                                        // Menambahkan form ke dalam container demoPoInner
                                        demoPoInner.appendChild(form);
                                    });

                                    document.addEventListener('input', function(event) {
                                        // Cek jika elemen yang di-input adalah Pack
                                        if (event.target && event.target.id.startsWith('pack')) {
                                            var packValue = event.target.value;
                                            var parent = event.target.closest('.row');

                                            // Temukan semua input dengan kelas .form-control-sm di dalam row yang sama
                                            var inputs = parent.querySelectorAll('input.form-control-sm');
                                            
                                            // Update semua input yang ditemukan dengan nilai packValue, kecuali input pertama (Pack)
                                            inputs.forEach(function(input, index) {
                                                if (index !== 0) {
                                                    input.value = packValue;
                                                }
                                            });
                                        }
                                    });

                                    function updateInput(formIndex) {
                                        var select = document.getElementById('customPertama_' + formIndex);
                                        var input = document.getElementById('idpodetail_' + formIndex);
                                        var idpo = document.getElementById('idpo_' + formIndex);

                                        var selectedOption = select.options[select.selectedIndex];
                                        var idpodetailValue = selectedOption.getAttribute('data-idpodetail');
                                        var idpoValue = selectedOption.getAttribute('data-idpo');

                                        idpo.value =idpoValue;
                                        input.value = idpodetailValue;
                                    }
                                </script>
                            </div>
                        </div>
                    </section>
                </div>
                <!-- INNER KNIT END -->
                <button type="submit" name="kirim" class="btn btn-primary btn-sm">Kirim</button>
                <!-- INNER Bandana END -->
            </div>
        </form>
    </div>

    <?php
        if (isset($_POST['kirim'])) {
            try {
                date_default_timezone_set('Asia/Jakarta');
                $today      = date('s');
                $waktu      = date('H:i:s');
                $qty        = $_POST['qty'];
                $variant    = $_POST['variant'];
                $count      = count($variant);

                if (isset($_GET['invoice'])) {
                    $invoice = $_GET['invoice'];
                } else {  
                    // Fungsi untuk menghasilkan angka acak dengan panjang tertentu
                    function generateAngkaAcak($length) {
                        $angka_acak = '';
                        for ($i = 0; $i < $length; $i++) {
                            // Menggunakan mt_rand untuk angka acak dari 0 hingga 9
                            $angka_acak .= mt_rand(0, 9);
                        }
                        return $angka_acak;
                    }
            
                    // Menghasilkan angka acak dengan panjang minimal 7 dan maksimal 7 angka
                    $angka_acak = generateAngkaAcak(7);
                    $invoice = 'A' . $idpoproduk . '-' . $idmitraagen . $angka_acak;
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
                    
                    $query = $koneksi->query("SELECT * FROM podetail WHERE idpodetail = '$idpodetail'");
                    $data_produk = $query->fetch_assoc();
                    $total_harga = $data_produk['harga'];

                    $sstok = $koneksi->query("SELECT 
                                                    SUM(pomitra.jumlah) AS sarung_dewasa
                                                FROM
                                                    pomitra
                                                        INNER JOIN
                                                    podetail ON pomitra.idpodetail = podetail.idpodetail
                                                WHERE
                                                    podetail.variant LIKE '%Sarung Dewasa%'
                                                        AND pomitra.idpoproduk = '$idpoproduk'
                                                        ");
                    $data_stok = $sstok->fetch_assoc();
                    $dump = $data_stok['sarung_dewasa'];
                    $sarung_dewasa = ((650 * 3) - $dump) / 3;

                    $sstok_bandana = $koneksi->query("SELECT 
                                                                SUM(pomitra.jumlah) AS sarung
                                                            FROM
                                                                pomitra
                                                                    INNER JOIN
                                                                podetail ON pomitra.idpodetail = podetail.idpodetail
                                                            WHERE
                                                                podetail.variant NOT LIKE '%Sarung Dewasa%'
                                                                    AND pomitra.idpoproduk = '$idpoproduk'
                                                        ");
                    $data_stok_sarung   = $sstok_bandana->fetch_assoc();
                    $dump_sarung        = $data_stok_sarung['sarung'];
                    $sarung             = ((650 * 3) - $dump_sarung) / 3;

                    $sql = $koneksi->query("INSERT INTO pomitra
                                                    (idpomitra, idmitra, idmitraagen, idpoproduk, idpo, idpodetail, jumlah, custom, total, invoice, status, tgl, waktu)
                                                VALUES
                                                    (NULL, NULL, '$idmitraagen', '$idpoproduk', '$idpo', '$idpodetail', '$qty_item', '$custom', '$total_harga', '$invoice', 'Belum DP', NOW(), '$waktu')
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
</body>
</html>