<?php
    session_start();

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $invoice = $_GET['invoice'];
    $idadmin = $_SESSION["idadmin"];

    $query_po = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = '$idpoproduk'");
    $sql = $query_po->fetch_assoc();
    $namapo = $sql['namapo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wanoja | Form PO Inner</title>

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

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#beanie" type="button" role="tab" aria-controls="beanie" aria-selected="true">Inner Beanie</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bandana" type="button" role="tab" aria-controls="bandana" aria-selected="false">Inner Bandana</button>
            </li>
        </ul>

        <form method="post" class="">
            <div class="tab-content" id="myTabContent">
                <!-- INNER KNIT START -->
                <div class="tab-pane fade show active" id="beanie" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <section class="my-3">
                        <div class="card shadow">
                            <div class="card-body">
                                <div>
                                    <span class="btn btn-sm btn-success" id="buttonAddPack">Tambah Pack</span>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-12">
                                        <p class="mb-0 fw-medium text-secondary">Pack Ke - 1</p>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="mb-0" for="pack">Pack</label>
                                            <input type="hidden" class="form-control form-control-sm" name="custom[]" value="Pack Ke 1">
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
                                                                                    AND
                                                                                    podetail.variant LIKE '%Inner Knit%'
                                                                        ");
                                                    while ($data = $sql->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Pack Ke 1 Beanie"><?= $data['variant'] ?></option>
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
                                                                                    AND
                                                                                    podetail.variant LIKE '%Inner Knit%'
                                                                        ");
                                                    while ($data = $sql->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Pack Ke 1 Beanie"><?= $data['variant'] ?></option>
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
                                                                                    AND
                                                                                    podetail.variant LIKE '%Inner Knit%'
                                                                        ");
                                                    while ($data = $sql->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Pack Ke 1 Beanie"><?= $data['variant'] ?></option>
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
                                    let formCount = 0;

                                    buttonAddPack.addEventListener('click', function() {
                                        formCount++;

                                        // Buat Form Baru
                                        const form = document.createElement('div');
                                        form.innerHTML = `
                                            <hr />
                                            <div class="row mb-2">
                                                <div class="col-sm-12">
                                                    <p class="mb-0 fw-medium text-secondary">Pack Ke - ${formCount + 1}</p>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="mb-0" for="pack">Pack</label>
                                                        <input type="hidden" class="form-control form-control-sm" name="custom[]" value="Pack Ke ${formCount + 1}">
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
                                                                                                AND
                                                                                                podetail.variant LIKE '%Inner Knit%'
                                                                                    ");
                                                                while ($data = $sql->fetch_assoc()) {
                                                            ?>
                                                                <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Pack Ke ${formCount + 1} Beanie"><?= $data['variant'] ?></option>
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
                                                                                                AND
                                                                                                podetail.variant LIKE '%Inner Knit%'
                                                                                    ");
                                                                while ($data = $sql->fetch_assoc()) {
                                                            ?>
                                                                <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Pack Ke ${formCount + 1} Beanie"><?= $data['variant'] ?></option>
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
                                                                                                AND
                                                                                                podetail.variant LIKE '%Inner Knit%'
                                                                                    ");
                                                                while ($data = $sql->fetch_assoc()) {
                                                            ?>
                                                                <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Pack Ke ${formCount + 1} Beanie"><?= $data['variant'] ?></option>
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
                <!-- INNER Bandana START -->
                <div class="tab-pane fade" id="bandana" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                    <section class="my-3">
                        <div class="card shadow">
                            <div class="card-body">
                                <div>
                                    <span class="btn btn-sm btn-success" id="buttonAddPackBandana">Tambah Pack</span>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-12">
                                        <p class="mb-0 fw-medium text-secondary">Pack Ke - 1</p>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="mb-0" for="pack">Pack</label>
                                            <input type="hidden" class="form-control form-control-sm" name="custom[]" value="Pack Ke 1">
                                            <input type="number" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="pack" required>
                                            <input type="hidden" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="form1" required>
                                            <input type="hidden" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="form2" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="mb-0" for="v1">Variant 1</label>
                                            <select class="form-select form-select-sm" name="variant[]" required>
                                                <option selected>~ Default Selected ~</option>
                                                <?php
                                                    $sql = $koneksi->query("SELECT 
                                                                                    *
                                                                                FROM
                                                                                    podetail
                                                                                        INNER JOIN
                                                                                    pokategori ON pokategori.idpo = podetail.idpo
                                                                                WHERE
                                                                                    pokategori.idpoproduk = '$idpoproduk'
                                                                                    AND
                                                                                    podetail.variant NOT LIKE '%Inner Knit%'
                                                                        ");
                                                    while ($data = $sql->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Pack Ke 1 Bandana"><?= $data['variant'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="mb-0" for="v1">Variant 2</label>
                                            <select class="form-select form-select-sm" name="variant[]">
                                                <option selected>~ Default Selected ~</option>
                                                <?php
                                                    $sql = $koneksi->query("SELECT 
                                                                                    *
                                                                                FROM
                                                                                    podetail
                                                                                        INNER JOIN
                                                                                    pokategori ON pokategori.idpo = podetail.idpo
                                                                                WHERE
                                                                                    pokategori.idpoproduk = '$idpoproduk'
                                                                                    AND
                                                                                    podetail.variant NOT LIKE '%Inner Knit%'
                                                                        ");
                                                    while ($data = $sql->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Pack Ke 1 Bandana"><?= $data['variant'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="mb-0" for="v1">Variant 3</label>
                                            <select class="form-select form-select-sm" name="variant[]">
                                                <option selected>~ Default Selected ~</option>
                                                <?php
                                                    $sql = $koneksi->query("SELECT 
                                                                                    *
                                                                                FROM
                                                                                    podetail
                                                                                        INNER JOIN
                                                                                    pokategori ON pokategori.idpo = podetail.idpo
                                                                                WHERE
                                                                                    pokategori.idpoproduk = '$idpoproduk'
                                                                                    AND
                                                                                    podetail.variant NOT LIKE '%Inner Knit%'
                                                                        ");
                                                    while ($data = $sql->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Pack Ke 1 Bandana"><?= $data['variant'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Yang akan di tampilkan ketika klik tambah pack -->
                                <div id="demoPoInnerBandana"></div>

                                <script type="text/javascript">
                                    const buttonAddPackBandana = document.getElementById("buttonAddPackBandana");
                                    const demoPoInnerBandana = document.getElementById("demoPoInnerBandana");
                                    let formCountBandana = 0;

                                    buttonAddPackBandana.addEventListener('click', function() {
                                        formCountBandana++;

                                        // Buat Form Baru
                                        const form = document.createElement('div');
                                        form.innerHTML = `
                                            <hr />
                                            <div class="row mb-2">
                                                <div class="col-sm-12">
                                                    <p class="mb-0 fw-medium text-secondary">Pack Ke - ${formCountBandana + 1}</p>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="mb-0" for="pack">Pack</label>
                                                        <input type="hidden" class="form-control form-control-sm" name="custom[]" value="Pack Ke ${formCountBandana + 1}">
                                                        <input type="number" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="pack${formCountBandana + 1}" required>
                                                        <input type="hidden" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="form${formCountBandana + 1}_1" required>
                                                        <input type="hidden" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="form${formCountBandana + 1}_2" required>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="mb-0" for="v1">Variant 1</label>
                                                        <select class="form-select form-select-sm" name="variant[]" required>
                                                            <option selected>~ Default Selected ~</option>
                                                            <?php
                                                                $sql = $koneksi->query("SELECT 
                                                                                                *
                                                                                            FROM
                                                                                                podetail
                                                                                                    INNER JOIN
                                                                                                pokategori ON pokategori.idpo = podetail.idpo
                                                                                            WHERE
                                                                                                pokategori.idpoproduk = '$idpoproduk'
                                                                                                AND
                                                                                                podetail.variant NOT LIKE '%Inner Knit%'
                                                                                    ");
                                                                while ($data = $sql->fetch_assoc()) {
                                                            ?>
                                                                <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Pack Ke ${formCountBandana + 1} Bandana"><?= $data['variant'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="mb-0" for="v1">Variant 2</label>
                                                        <select class="form-select form-select-sm" name="variant[]">
                                                            <option selected>~ Default Selected ~</option>
                                                            <?php
                                                                $sql = $koneksi->query("SELECT 
                                                                                                *
                                                                                            FROM
                                                                                                podetail
                                                                                                    INNER JOIN
                                                                                                pokategori ON pokategori.idpo = podetail.idpo
                                                                                            WHERE
                                                                                                pokategori.idpoproduk = '$idpoproduk'
                                                                                                AND
                                                                                                podetail.variant NOT LIKE '%Inner Knit%'
                                                                                    ");
                                                                while ($data = $sql->fetch_assoc()) {
                                                            ?>
                                                                <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Pack Ke ${formCountBandana + 1} Bandana"><?= $data['variant'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="mb-0" for="v1">Variant 3</label>
                                                        <select class="form-select form-select-sm" name="variant[]">
                                                            <option selected>~ Default Selected ~</option>
                                                            <?php
                                                                $sql = $koneksi->query("SELECT 
                                                                                                *
                                                                                            FROM
                                                                                                podetail
                                                                                                    INNER JOIN
                                                                                                pokategori ON pokategori.idpo = podetail.idpo
                                                                                            WHERE
                                                                                                pokategori.idpoproduk = '$idpoproduk'
                                                                                                AND
                                                                                                podetail.variant NOT LIKE '%Inner Knit%'
                                                                                    ");
                                                                while ($data = $sql->fetch_assoc()) {
                                                            ?>
                                                                <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Pack Ke ${formCountBandana + 1} Bandana"><?= $data['variant'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        `;

                                        // Menambahkan form ke dalam container demoPoInner
                                        demoPoInnerBandana.appendChild(form);
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
                    $invoice = 'D' . $idpoproduk . '-' . $idadmin . $angka_acak;
                }
                for ($x = 0; $x < $count; $x++) {
                    $qty_item       = $qty[$x];
                    $variant_item   = $variant[$x];
                    $data           = explode("|", $variant_item);
                    $idpodetail     = $data[0];
                    $idpo           = $data[1];
                    $custom         = $data[2];
                    
                    $query = $koneksi->query("SELECT * FROM podetail WHERE idpodetail = '$idpodetail'");
                    $data_produk = $query->fetch_assoc();
                    $total_harga = $data_produk['harga'];

                    $sstok = $koneksi->query("SELECT 
                                                                SUM(pomitra.jumlah) AS inner_beanie
                                                            FROM
                                                                pomitra
                                                                    INNER JOIN
                                                                podetail ON pomitra.idpodetail = podetail.idpodetail
                                                            WHERE
                                                                podetail.variant LIKE '%Inner Knit%'
                                                                    AND pomitra.idpoproduk = '$idpoproduk'
                                                        ");
                    $data_stok = $sstok->fetch_assoc();
                    $dump = $data_stok['inner_beanie'];
                    $inner_beanie = ((650 * 3) - $dump) / 3;

                    $sstok_bandana = $koneksi->query("SELECT 
                                                                SUM(pomitra.jumlah) AS inner_bandana
                                                            FROM
                                                                pomitra
                                                                    INNER JOIN
                                                                podetail ON pomitra.idpodetail = podetail.idpodetail
                                                            WHERE
                                                                podetail.variant NOT LIKE '%Inner Knit%'
                                                                    AND pomitra.idpoproduk = '$idpoproduk'
                                                        ");
                    $data_stok_bandana = $sstok_bandana->fetch_assoc();
                    $dump_bandana = $data_stok_bandana['inner_bandana'];
                    $inner_bandana = ((650 * 3) - $dump_bandana) / 3;

                    $sql = $koneksi->query("INSERT INTO pomitra
                                                    (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, custom, total, invoice, status, tgl, waktu)
                                                VALUES
                                                    (NULL, '$idadmin', '$idpoproduk', '$idpo', '$idpodetail', '$qty_item', '$custom', '$total_harga', '$invoice', 'Belum DP', NOW(), '$waktu')
                                            ");
                    
                    if ($sql) {
                        echo "
                            <script>
                                alert('Data sudah terkirim')
                                location='datapoinner2.php?id=$idpoproduk&invoice=$invoice'
                            </script>
                        ";
                    } else {
                        echo "
                            <script>
                                alert('Data gagal terkirim!')
                                location='formpoinner2.php?id=$idpoproduk'
                            </script>
                        ";
                    }

                    // if ($qty_item <= $inner_beanie || $qty_item <= $inner_bandana) {
                    //     $sql = $koneksi->query("INSERT INTO pomitra
                    //                                     (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, custom, total, invoice, status, tgl, waktu)
                    //                                 VALUES
                    //                                     (NULL, '$idadmin', '$idpoproduk', '$idpo', '$idpodetail', '$qty_item', '$custom', '$total_harga', '$invoice', 'Belum DP', NOW(), '$waktu')
                    //                             ");
                        
                    //     if ($sql) {
                    //         echo "
                    //             <script>
                    //                 alert('Data sudah terkirim')
                    //                 location='datapoinner2.php?id=$idpoproduk&invoice=$invoice'
                    //             </script>
                    //         ";
                    //     } else {
                    //         echo "
                    //             <script>
                    //                 alert('Data gagal terkirim!')
                    //                 location='formpoinner2.php?id=$idpoproduk'
                    //             </script>
                    //         ";
                    //     }
                    // } else {
                    //     echo "
                    //         <script>
                    //             alert('Stock sudah habis!')
                    //             location='datapoinner2.php?id=$idpoproduk'&invoice=$invoice'
                    //         </script>
                    //     ";
                    // }

                    // $sql = $koneksi->query("INSERT INTO pomitra
                    //                                 (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, custom, total, invoice, status, tgl, waktu)
                    //                             VALUES
                    //                                 (NULL, '$idadmin', '$idpoproduk', '$idpo', '$idpodetail', '$qty_item', '$custom', '$total_harga', '$invoice', 'Belum DP', NOW(), '$waktu')
                    //                         ");
                    
                    // if ($sql) {
                    //     echo "
                    //         <script>
                    //             alert('Data sudah terkirim')
                    //             location='datapoinner2.php?id=$idpoproduk&invoice=$invoice'
                    //         </script>
                    //     ";
                    // } else {
                    //     echo "
                    //         <script>
                    //             alert('Data gagal terkirim!')
                    //             location='formpoinner2.php?id=$idpoproduk'
                    //         </script>
                    //     ";
                    // }
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