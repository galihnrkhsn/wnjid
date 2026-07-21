<form method="POST">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Pack</th>
                </tr>
            </thead>
    
            <?php
                $no = 1;
                $packs = [];
                $sql = $koneksi->query("SELECT 
                                                pomitra.custom
                                            FROM
                                                pomitra
                                            WHERE
                                                pomitra.invoice = '$invoice'
                                                    AND pomitra.jumlah > 0
                                            GROUP BY pomitra.custom
                                        ");
                while($data = $sql->fetch_assoc()) {
                    $custom = trim($data['custom']);
                    $string = $custom;
                    // Mengubah semua huruf menjadi huruf kecil
                    $string = strtolower($string);
                    // Mengganti spasi dengan tanda hubung
                    $string = str_replace(' ', '-', $string);
                    // Menghilangkan tanda - di awal string
                    $string = ltrim($string, '-');
                    // Menghapus karakter yang tidak diperlukan (opsional, jika diperlukan)
                    $string = preg_replace('/[^a-z0-9\-]/', '', $string);
            ?>
                <tbody>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                        <?php
                            $query = $koneksi->query("SELECT 
                                                            podetail.*, pomitra.*
                                                        FROM
                                                            pomitra
                                                                INNER JOIN
                                                            podetail ON pomitra.idpodetail = podetail.idpodetail
                                                        WHERE
                                                            pomitra.invoice = '$invoice'
                                                                AND pomitra.jumlah > 0
                                                                AND TRIM(pomitra.custom) = '$custom'
                                                    ");
                            $first = true;
                            while ($data_produk = $query->fetch_assoc()) {
                                if (!$first) {
                                    echo " | ";
                                }
                                $first = false;
                        ?>
                                <?= $data_produk['variant'] ?>
                            <?php 
                                    $pack = $data_produk['jumlah'];
                                    $harga = $data_produk['harga'] * $pack;
                                    $idpomitra = $data_produk['idpomitra'];
                                }
                            ?>
                        </td>
                        <td>
                            <input type="number" name="qty[]" value="<?= $pack ?>">
                            <input type="hidden" name="custom[]" value="<?= htmlspecialchars($custom); ?>">
                            <input type="hidden" name="invoice" value=<?= $invoice; ?>>
                        </td>
                    </tr>
                </tbody>
            <?php
                }
            ?>
        </table>
    </div>
    <div class="mt-2">
        <button class="btn btn-primary" type="submit" name="update">Update semua Stok</button>
    </div>
</form>
<hr class="mt-5 mb-5">
<form method="POST">
    <?php
        $queryData      = $koneksi->query("SELECT * FROM pomitra WHERE invoice = '$invoice' ORDER BY idpomitra DESC LIMIT 1");
        $dataPO = $queryData->fetch_assoc();
        
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
                                    <input type="hidden" class="form-control form-control-sm" name="bundling_custom[]" value="Bundling <?= $angkaBaru ?>">
                                    <input type="number" class="form-control form-control-sm" min="0" value="0" name="bundling_qty[]" id="pack" required>
                                    <input type="hidden" class="form-control form-control-sm" min="0" value="0" name="bundling_qty[]" id="form1" required>
                                    <input type="hidden" class="form-control form-control-sm" min="0" value="0" name="bundling_qty[]" id="form2" required>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="mb-0" for="v1">Variant 1</label>
                                    <select class="form-select form-select-sm" name="bundling_variant[]" required>
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
                                    <select class="form-select form-select-sm" name="bundling_variant[]">
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
                                    <select class="form-select form-select-sm" name="bundling_variant[]">
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
                                                <input type="hidden" class="form-control form-control-sm" name="bundling_custom[]" value="Bundling ${formCount + 1}">
                                                <input type="number" class="form-control form-control-sm" min="0" value="0" name="bundling_qty[]" id="pack${formCount + 1}" required>
                                                <input type="hidden" class="form-control form-control-sm" min="0" value="0" name="bundling_qty[]" id="form${formCount + 1}_1" required>
                                                <input type="hidden" class="form-control form-control-sm" min="0" value="0" name="bundling_qty[]" id="form${formCount + 1}_2" required>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="mb-0" for="v1">Variant 1</label>
                                                <select class="form-select form-select-sm" name="bundling_variant[]" required>

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
                                                <select class="form-select form-select-sm" name="bundling_variant[]">

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
                                                <select class="form-select form-select-sm" name="bundling_variant[]">

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
        <button type="submit" name="kirim" class="btn btn-primary btn-sm">Kirim</button>
    </div>
</form>