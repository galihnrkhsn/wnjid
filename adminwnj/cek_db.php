<?php
    include "koneksi.php";
    $jenis_mitra = $_GET['jenis_mitra'];
?>
<?php if ($jenis_mitra == "Distributor") : ?>
    <div class="form-group">
        <label for="<??>" class="mb-0">Nama Mitra</label>
        <select class="form-control form-control-sm" name="jenis_mitra">
            <option selected>~ Default Nama Mitra ~</option>
            <?php
                $query_db = $koneksi->query("SELECT * FROM admin_mitra ORDER BY namamitra");
                while ($sql_db = $query_db->fetch_assoc()) {
            ?>
                <option value="Distributor | <?= $sql_db['idadmin'] ?>"><?= $sql_db['namamitra'] ?> (<?= $sql_db['idadmin'] ?>)</option>
            <?php } ?>
        </select>
    </div>
<?php elseif ($jenis_mitra == "Agen") : ?>
    <div class="form-group">
        <label for="<??>" class="mb-0">Nama Mitra</label>
        <select class="form-control form-control-sm" name="jenis_mitra">
            <option selected>~ Default Nama Mitra ~</option>
            <?php
                $tes = $koneksi->query("SELECT * FROM mitraagen ORDER BY namaagen ASC");
                while ($testing = $tes->fetch_assoc()) {
            ?>
                <option value="Agen | <?= $testing['idmitraagen'] ?>"><?= $testing['namaagen'] ?> (<?= $testing['idmitraagen'] ?>)</option>
            <?php } ?>
        </select>
    </div>
<?php elseif ($jenis_mitra == "Reseller") : ?>
    <div class="form-group">
        <label for="<??>" class="mb-0">Nama Mitra</label>
        <select class="form-control form-control-sm" name="jenis_mitra">
            <option selected>~ Default Nama Mitra ~</option>
            <?php
                $query_db = $koneksi->query("SELECT * FROM mitrareseller ORDER BY idmitrareseller");
                while ($sql_db = $query_db->fetch_assoc()) {
            ?>
                <option value="Reseller | <?= $sql_db['idmitrareseller'] ?>"><?= $sql_db['namaagen'] ?></option>
            <?php } ?>
        </select>
    </div>
<?php elseif ($jenis_mitra == "Marketer") : ?>
    <div class="form-group">
        <label for="<??>" class="mb-0">Nama Mitra</label>
        <select class="form-control form-control-sm" name="jenis_mitra">
            <option selected>~ Default Nama Mitra ~</option>
            <?php
                $query_db = $koneksi->query("SELECT * FROM mitramarketer ORDER BY idmitramarketer");
                while ($sql_db = $query_db->fetch_assoc()) {
            ?>
                <option value="Marketer | <?= $sql_db['idmitramarketer'] ?>"><?= $sql_db['namaagen'] ?></option>
            <?php } ?>
        </select>
    </div>
<?php endif; ?>