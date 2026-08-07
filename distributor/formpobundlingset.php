<?php
    session_start();

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $invoice    = $_GET['invoice'];
    $idadmin    = $_SESSION["idadmin"];

    $query_po   = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = '$idpoproduk'");
    $sql        = $query_po->fetch_assoc();
    $namapo     = $sql['namapo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WNJ.ID | Form PO Bundling</title>

    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <!-- Icons Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                                        <p class="mb-0 fw-medium text-secondary">Bundling Ke - 1</p>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="mb-0" for="pack">Jumlah</label>
                                            <input type="hidden" class="form-control form-control-sm" name="custom[]" value="Bundling 1">
                                            <input type="number" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="pack" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="mb-0">Warna</label>
                                            <select id="" class="form-select form-select-sm warnaSelect" name="kategori[]" required>
                                                <option value="">-- Pilih Warna --</option>
                                                <?php
                                                    $sql = $koneksi->query("SELECT * FROM pokategori WHERE idpoproduk = '$idpoproduk'");
                                                    while ($data = $sql->fetch_assoc()) {
                                                        echo "<option value='{$data['idpo']}'>{$data['namakategori']}</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="mb-0">Tunik</label>
                                            <select class="form-select form-select-sm tunikSelect" name="variant[]">
                                                <option value="">~~ Pilih Tunik ~~</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="mb-0">Kulot</label>
                                            <select class="form-select form-select-sm kulotSelect" name="variant[]">
                                                <option value="">~~ Pilih Kulot ~~</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="mb-0">Bergo</label>
                                            <select class="form-select form-select-sm bergoSelect" name="variant[]">
                                                <option value="">~~ Pilih Bergo ~~</option>
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
                                                    <p class="mb-0 fw-medium text-secondary">Bundling Ke - ${formCount + 1}</p>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="mb-0" for="pack">Jumlah</label>
                                                        <input type="hidden" class="form-control form-control-sm" name="custom[]" value="Bundling ${formCount + 1}">
                                                        <input type="number" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="pack" required>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="mb-0">Warna</label>
                                                        <select id="" class="form-select form-select-sm warnaSelect" name="kategori[]" required>
                                                            <option value="">-- Pilih Warna --</option>
                                                            <?php
                                                                $sql = $koneksi->query("SELECT * FROM pokategori WHERE idpoproduk = '$idpoproduk'");
                                                                while ($data = $sql->fetch_assoc()) {
                                                                    echo "<option value='{$data['idpo']}'>{$data['namakategori']}</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label class="mb-0">Tunik</label>
                                                        <select class="form-select form-select-sm tunikSelect" name="variant[]">
                                                            <option value="">~~ Pilih Tunik ~~</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label class="mb-0">Kulot</label>
                                                        <select class="form-select form-select-sm kulotSelect" name="variant[]">
                                                            <option value="">~~ Pilih Kulot ~~</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label class="mb-0">Bergo</label>
                                                        <select class="form-select form-select-sm bergoSelect" name="variant[]">
                                                            <option value="">~~ Pilih Bergo ~~</option>
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
                                <script>
                                    document.addEventListener('change', function (e) {
                                        if (e.target.classList.contains('warnaSelect')) {
                                            let idpo = e.target.value;
                                            let row = e.target.closest('.row');
                                            let kulotSelect = row.querySelector('.kulotSelect');
                                            let bergoSelect = row.querySelector('.bergoSelect');
                                            let tunikSelect = row.querySelector('.tunikSelect');
    
                                            console.log(idpo)
    
                                            if (idpo) {
                                                fetch('api/get_kulot.php?idpo=' + idpo)
                                                .then(response => response.json())
                                                .then(data => {
                                                    kulotSelect.innerHTML = '<option value="">~~ Pilih Kulot ~~</option>';
                                                    data.forEach(item => {
                                                        kulotSelect.innerHTML += `<option value="${item.idpodetail}">${item.variant}</option>`
                                                    });
                                                });
    
                                                fetch('api/get_bergo.php?idpo=' + idpo)
                                                .then(response => response.json())
                                                .then(data => {
                                                    bergoSelect.innerHTML = '<option value="">~~ Pilih Bergo ~~</option>';
                                                    data.forEach(item => {
                                                        bergoSelect.innerHTML += `<option value="${item.idpodetail}">${item.variant}</option>`
                                                    });
                                                });
    
                                                fetch('api/get_tunik.php?idpo=' + idpo)
                                                .then(response => response.json())
                                                .then(data => {
                                                    tunikSelect.innerHTML = '<option value="">~~ Pilih Tunik ~~</option>';
                                                    data.forEach(item => {
                                                        tunikSelect.innerHTML += `<option value="${item.idpodetail}">${item.variant}</option>`
                                                    });
                                                });
                                            } else {
                                                tunikSelect.innerHTML = '<option value="">~~ Pilih Tunik ~~</option>';
                                                kulotSelect.innerHTML = '<option value="">~~ Pilih Kulot ~~</option>';
                                                bergoSelect.innerHTML = '<option value="">~~ Pilih Bergo ~~</option>';
                                            }
                                        }
                                    })
                                </script>
                            </div>
                        </div>
                    </section>
                </div>
                <!-- INNER KNIT END -->
                <?php
                    // Query untuk mendapatkan data dari database
                    $query  = "SELECT 
                                    COUNT(*) AS jumlah,
                                    poproduk.idpoproduk,
                                    poproduk.namapo,
                                    poproduk.status,
                                    pomitra.invoice
                                FROM
                                    poproduk
                                        INNER JOIN
                                    pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                                WHERE
                                    poproduk.idpoproduk = '$idpoproduk'
                                        AND pomitra.idmitra = '$idadmin'
                                        AND COALESCE(pomitra.idmitraagen, pomitra.idmitrareseller, pomitra.idmitramarketer) IS NULL;";
                    $sql    = mysqli_query($koneksi, $query);  
                    $data   = mysqli_fetch_array($sql); 

                ?>
            <?php if ($data['jumlah'] < 1) : ?>
                <button type="submit" name="kirim" class="btn btn-primary btn-sm">Kirim</button>
            <?php else : ?>
                <a href="datapobundling.php?id=<?= $data['idpoproduk'] ?>&invoice=<?= $data['invoice'] ?>" class="btn btn-success btn-sm">Invoice</a>
            <?php endif ?>
            </div>
        </form>
    </div>

    <?php
        if (isset($_POST['kirim'])) {
            $checkQuery     = $koneksi->query("SELECT COUNT(*) as count 
                                                FROM pomitra 
                                                WHERE idmitra = '$idadmin' 
                                                AND idpoproduk = '$idpoproduk'
                                                AND idmitraagen IS NULL
                                                AND idmitrareseller IS NULL
                                                AND idmitramarketer IS NULL");
            $exists         = $checkQuery->fetch_assoc()['count'];

            if ($exists > 0) {
                echo "<script>alert('Data sudah ada, tidak boleh mengirim ulang!');</script>";
                echo "<script>location='detailpo.php?id=$idpoproduk';</script>";
                exit();
            }

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

            try {
                $koneksi->begin_transaction();
                date_default_timezone_set('Asia/Jakarta');
                $today      = date('s');
                $waktu      = date('H:i:s');
                $qty        = $_POST['qty'];
                $variant    = $_POST['variant'];
                $idpo       = $_POST['kategori'];
                $custom     = $_POST['custom'];
                $count      = count($variant);
            
                for ($x = 0; $x < $count; $x++) {
                    $idpodetail_item = $variant[$x];
                    $query          = $koneksi->query("SELECT * FROM podetail WHERE idpodetail = '$idpodetail_item'");
                    $data_produk    = $query->fetch_assoc();
                    $total_harga    = $data_produk['harga'];
                    
                    $bundling_ke    = floor($x / 3);
                    $custom_item    = 'Bundling ' . ($bundling_ke + 1);
                    $qty_item       = $qty[$bundling_ke];
                    $idpo_item      = $idpo[$bundling_ke];
                    
                    $sql = $koneksi->query("INSERT INTO pomitra
                                                    (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, custom, total, invoice, status, tgl, waktu)
                                                VALUES
                                                    (NULL, '$idadmin', '$idpoproduk', '$idpo_item', '$idpodetail_item', '$qty_item', '$custom_item', '$total_harga', '$invoice', 'Belum DP', NOW(), '$waktu')
                                            ");
                    
                    if (!$sql) {
                        throw new Exception("Gagal insert data ke -$x");
                    }
                }

                $koneksi->commit();
                echo "<script>alert('Data sudah terkirim'); location='datapobundling.php?id=$idpoproduk&invoice=$invoice'</script>";
            } catch (Exception $e) {
                $koneksi->rollback();
                echo "Error: " . $e->getMessage();
            }
        }
    ?>
    <!-- JavaScript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
</body>
</html>