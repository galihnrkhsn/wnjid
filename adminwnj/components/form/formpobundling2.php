<?php
    global $koneksi, $idpoproduk;
?>
<p class="font-weight-semibold text-lg text-primary">Jenis Pre-Order Bundling 3</p>
<hr />
<div class="row">
    <div class="col">
        <div class="form-group">
            <label for="jenis_mitra" class="mb-1">Jenis Mitra</label>
            <select id="jenis_mitra" class="form-control form-control-sm">
                <option selected>~ Default Selected ~</option>
                <option value="Distributor">Distributor</option>
                <option value="Agen">Agen</option>
                <option value="Reseller">Reseller</option>
                <option value="Marketer">Marketer</option>
            </select>
        </div>
        <div class="form-group" id="nama_mitra"></div>
        <div class="tab-pane fade show active" id="beanie" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
            <section class="my-3">
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
                            <input type="hidden" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="form1" required>
                            <input type="hidden" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="form2" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="mb-0" for="v1">Variant 1</label>
                            <select class="form-select form-select-sm" name="variant[]" required>
                                <!-- <option value="null" disabled selected>~~ Default Selected ~~</option> -->
                                <?php
                                    $sql = $koneksi->query("SELECT 
                                                                    *
                                                                FROM
                                                                    podetail
                                                                        INNER JOIN
                                                                    pokategori ON pokategori.idpo = podetail.idpo
                                                                WHERE
                                                                    pokategori.idpoproduk = '$idpoproduk'
                                                                AND podetail.idpo NOT IN (4413, 4382, 4351)
                                                        ");
                                    while ($data = $sql->fetch_assoc()) {
                                ?>
                                    <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Bundling 1"><?= $data['variant'] ?></option>
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
                                                                AND podetail.idpo NOT IN (4413, 4382, 4351)
                                                        ");
                                    while ($data = $sql->fetch_assoc()) {
                                ?>
                                    <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Bundling 1"><?= $data['variant'] ?></option>
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
                                                                AND podetail.idpo NOT IN (4413, 4382, 4351)
                                                        ");
                                    while ($data = $sql->fetch_assoc()) {
                                ?>
                                    <option value="<?= $data['idpodetail'] ?> | <?= $data['idpo'] ?> | Bundling 1"><?= $data['variant'] ?></option>
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
                                    <p class="mb-0 fw-medium text-secondary">Bundling Ke - ${formCount + 1}</p>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="mb-0" for="pack">Jumlah</label>
                                        <input type="hidden" class="form-control form-control-sm" name="custom[]" value="Bundling ${formCount + 1}">
                                        <input type="number" class="form-control form-control-sm" min="0" value="0" name="qty[]" id="pack${formCount + 1}" required>
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
                                                                                AND podetail.idpo NOT IN (4413, 4382, 4351)
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
                                                                                AND podetail.idpo NOT IN (4413, 4382, 4351)
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
                                                                                AND podetail.idpo NOT IN (4413, 4382, 4351)
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
                                                                    var select  = document.getElementById('customPertama_' + formIndex);
                                                                    var input   = document.getElementById('idpodetail_' + formIndex);
                                                                    var idpo    = document.getElementById('idpo_' + formIndex);

                                                                    var selectedOption  = select.options[select.selectedIndex];
                                                                    var idpodetailValue = selectedOption.getAttribute('data-idpodetail');
                                                                    var idpoValue       = selectedOption.getAttribute('data-idpo');

                                                                    idpo.value          = idpoValue;
                                                                    input.value         = idpodetailValue;
                                                                }
                                                            </script>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>