<?php
    include 'koneksi.php';
    // Ambil data dari API di server lain
    $jenis_mitra = $_GET['jenis_mitra'];
    if ($jenis_mitra === "WNJ") :
?>
    <div class="form-group">
        <label for="jenis_mitra" class="mb-1">Nama Mitra <span class="text-danger">*</span></label>
        <select class="form-control form-control-sm" name="namamitra" required>
            <option disabled selected value="">~ Pilih Mitra ~</option>
            <?php
                $sql = $koneksi->query("SELECT * FROM admin_mitra ORDER BY namamitra ASC");
                while ($data = $sql->fetch_assoc()) {
            ?>
                <option value="<?= $data['idadmin'] ?> | <?= $data['namamitra'] ?>"><?= $data['namamitra'] ?> (<?= $data['idadmin'] ?>)</option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group">
        <label for="jenis-pengiriman" class="mb-1">Jenis Pengiriman <span class="text-danger">*</span></label>
        <select class="form-control form-control-sm" id="jenis-pengiriman" name="jenis_pengiriman" required>
            <option value="ReadyStok">Ready Stok</option>
            <option value="PO">Pre Order</option>
            <option value="GA">Give Away</option>
            <option value="Pribadi">Pribadi</option>
            <option value="Return">Return</option>
        </select>
    </div>
    <!-- Radio pilihan untuk PO -->
    <div class="form-group" id="po-options" style="display: none;">
        <label class="mb-1">Pilih Tipe PO</label><br>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="po_type" id="po_mandiri" value="mandiri">
            <label class="form-check-label" for="po_mandiri">Mandiri</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="po_type" id="po_reguler" value="reguler">
            <label class="form-check-label" for="po_reguler">Reguler</label>
        </div>
    </div>
    <!-- Input Mandiri -->
    <div class="form-group" id="form-mandiri" style="display: none;">
        <label for="input_mandiri" class="mb-1">Nama PO Mandiri</label>
        <input type="text" id="input_mandiri" class="form-control form-control-sm">
    </div>

    <!-- Select Poproduk -->
    <div class="form-group" id="form-poproduk" style="display: none;">
        <label for="poproduk" class="mb-1">Produk PO</label>
        <select id="poproduk" class="form-control form-control-sm">
            <?php 
                $queryPO = $koneksi->query("SELECT idpoproduk, namapo FROM poproduk ORDER BY idpoproduk DESC");
                while($dataPO = $queryPO->fetch_assoc()){ 
            ?>
            <option value="<?= $dataPO['idpoproduk']; ?>"><?= $dataPO['namapo'] ?></option>
            <?php
                }
            ?>
        </select>
    </div>
<?php elseif ($jenis_mitra === "Zizazu") : ?>
    <div class="form-group">
        <?php 
            $apiUrl         = 'https://zizazu.id/admin/API/get-data.php?token=wnjzizazu218!';
            $response       = file_get_contents($apiUrl);
            if ($response === false) {
                echo '<option disabled>Gagal memuat data mitra</option>';
            } else {
                $mitraList      = json_decode($response, true);            
            }
        ?>
        <label for="jenis_mitra" class="mb-1">Nama Mitra <span class="text-danger">*</span></label>
        <select class="form-control form-control-sm" name="namamitra" required>
            <option disabled selected>~ Choose Mitra ~</option>
            <?php foreach ($mitraList as $mitra): ?>
                <option value="<?= $mitra['idadmin']; ?> | <?= $mitra['namamitra'] ?>"><?= $mitra['namamitra']; ?> (<?= $mitra['idadmin'] ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="jenis-pengiriman" class="mb-1">Jenis Pengiriman <span class="text-danger">*</span></label>
        <select class="form-control form-control-sm" id="jenis-pengiriman" name="jenis_pengiriman" required>
            <option value="ReadyStok">Ready Stok</option>
            <option value="PO">Pre Order</option>
            <option value="GA">Give Away</option>
            <option value="Pribadi">Pribadi</option>
            <option value="Return">Return</option>
        </select>
    </div>
    <!-- Radio pilihan untuk PO -->
    <div class="form-group" id="po-options" style="display: none;">
        <label class="mb-1">Pilih Tipe PO</label><br>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="po_type" id="po_mandiri" value="mandiri">
            <label class="form-check-label" for="po_mandiri">Mandiri</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="po_type" id="po_reguler" value="reguler">
            <label class="form-check-label" for="po_reguler">Reguler</label>
        </div>
    </div>
    <!-- Input Mandiri -->
    <div class="form-group" id="form-mandiri" style="display: none;">
        <label for="input_mandiri" class="mb-1">Nama PO Mandiri</label>
        <input type="text" id="input_mandiri" class="form-control form-control-sm">
    </div>

    <!-- Select Poproduk -->
    <div class="form-group" id="form-poproduk" style="display: none;">
        <?php 
            $apiUrl         = 'https://zizazu.id/admin/API/get-poproduk.php?token=wnjzizazu218!';
            $response       = file_get_contents($apiUrl);
            if ($response === false) {
                echo '<option disabled>Gagal memuat data poproduk</option>';
            } else {
                $poprodukList      = json_decode($response, true);            
            }
        ?>
        <label for="poproduk" class="mb-1">Produk PO <span class="text-danger">*</span></label>
        <select id="poproduk" class="form-control form-control-sm">
            <option disabled selected>~ Choose Mitra ~</option>
            <?php foreach ($poprodukList as $poproduk): ?>
                <option value="<?= $poproduk['idpoproduk']; ?>"><?= $poproduk['namapo']; ?> (<?= $poproduk['idpoproduk'] ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
<?php endif; ?>

<script>
    document.getElementById('jenis-pengiriman').addEventListener('change', function() {
        const isPO = this.value === 'PO';
        document.getElementById('po-options').style.display = isPO ? 'block' : 'none';
        document.getElementById('form-mandiri').style.display = 'none';
        document.getElementById('form-poproduk').style.display = 'none';

        // Remove name if not PO
        document.getElementById('input_mandiri').removeAttribute('name');
        document.getElementById('poproduk').removeAttribute('name');
    });

    document.querySelectorAll('input[name="po_type"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const mandiri = document.getElementById('form-mandiri');
            const poproduk = document.getElementById('form-poproduk');
            const inputMandiri = document.getElementById('input_mandiri');
            const selectPoproduk = document.getElementById('poproduk');

            if (this.value === 'mandiri') {
                mandiri.style.display = 'block';
                poproduk.style.display = 'none';
                inputMandiri.setAttribute('name', 'input_mandiri');
                selectPoproduk.removeAttribute('name');
            } else {
                mandiri.style.display = 'none';
                poproduk.style.display = 'block';
                inputMandiri.removeAttribute('name');
                selectPoproduk.setAttribute('name', 'poproduk');
            }
        });
    });
</script>