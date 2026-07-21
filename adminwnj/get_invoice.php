<?php
    session_start();
    include 'koneksi.php';
    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $idpoproduk = $_GET['jenisPo'];
    $idadmin = $_GET['jenisDb'];
?>

<label for="invoice" class="form-label mb-0">Invoice</label>
<select class="form-control form-control-sm" name="invoice" id="invoice">
    <?php
        $sqlpo = $koneksi->query("SELECT * FROM pomitra WHERE idmitra = '$idadmin' AND idpoproduk = '$idpoproduk' GROUP BY invoice");
        while ($datapo = $sqlpo->fetch_assoc()) {
    ?>
        <option value="<?= $datapo['invoice'] ?>"><?= $datapo['invoice'] ?></option>
    <?php } ?>
</select>