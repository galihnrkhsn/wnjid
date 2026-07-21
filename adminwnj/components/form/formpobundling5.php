<?php
    global $koneksi, $idpoproduk;

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
                            <div class="col-sm-1">
                                <label class="mb-0">Jumlah</label>
                                <input type="hidden" name="custom[]" value="Bundling 1">
                                <input type="number" class="form-control form-control-sm" min="0" value="0" name="qty[]" required>
                                <input type="hidden" name="qty[]" value="0">
                                <input type="hidden" name="qty[]" value="0">
                                <input type="hidden" name="qty[]" value="0">
                                <input type="hidden" name="qty[]" value="0">
                            </div>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <div class="col-sm-2">
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
    </div>
</div>
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
                    <div class="col-sm-1">
                        <label class="mb-0">Jumlah</label>
                        <input type="hidden" name="custom[]" value="${bundlingLabel}">
                        <input type="number" class="form-control form-control-sm" min="0" value="0" name="qty[]" required>
                        <input type="hidden" name="qty[]" value="0">
                        <input type="hidden" name="qty[]" value="0">
                        <input type="hidden" name="qty[]" value="0">
                        <input type="hidden" name="qty[]" value="0">
                    </div>
                    ${[1,2,3,4,5].map(i => `
                        <div class="col-sm-2">
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