<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesMarketer.php';
    include "settingdatatables.php";
    $idmitramarketer    = $_SESSION["idmitramarketer"];
    $findUser           = $koneksi->query("SELECT * FROM mitramarketer WHERE idmitramarketer='$idmitramarketer'");
    $queryUser          = $findUser->fetch_assoc();

    if (isset($_GET['no_ds'])) {
        $no_ds=$_GET["no_ds"];
        $querynamapo = "SELECT poproduk.namapo, podropship.proses, podropship.idpoproduk
                            FROM poproduk
                            JOIN podropship ON podropship.idpoproduk = poproduk.idpoproduk
                            WHERE  podropship.no_ds = '$no_ds'
                        ";
        $sqlnamapo = mysqli_query($koneksi, $querynamapo);
        $datanamapo = mysqli_fetch_array($sqlnamapo);
        $namapo=$datanamapo['namapo'];
        $proses=$datanamapo['proses'];
        $idpoproduk=$datanamapo["idpoproduk"];  
        $querypods = "SELECT SUM(pods.jumlah) as jumlahnya FROM pods WHERE pods.no_ds= '$no_ds'";
        $sqlpods = mysqli_query($koneksi, $querypods);
        $datapods = mysqli_fetch_array($sqlpods);
        $jmlh_semua = $datapods['jumlahnya'];
        $querypods2 = "SELECT * FROM hampers WHERE hampers.no_ds= '$no_ds'";
        $sqlpods2 = mysqli_query($koneksi, $querypods2);
        $datapods2 = mysqli_fetch_array($sqlpods2);
        $datanya = $datapods2['no_ds'];

        $query_ds = "SELECT podropship.namapengirim, 
                            podropship.tlppengirim, 
                            podropship.namapenerima, 
                            podropship.tlppenerima, 
                            podropship.alamatpenerima,
                            podropship.invoice,
                            podropship.idpoproduk,
                            podropship.iddropship,
                            podropship.no_ds,
                            tb_ro_provinces.province_name as provinsi,
                            tb_ro_cities.city_name as kota,
                            tb_ro_subdistricts.subdistrict_name as kecamatan 
                        FROM podropship 
                        LEFT JOIN tb_ro_provinces on podropship.provinsi = tb_ro_provinces.province_id
                        LEFT JOIN tb_ro_cities on podropship.kota = tb_ro_cities.city_id
                        LEFT JOIN tb_ro_subdistricts on podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
                        WHERE podropship.no_ds='$no_ds'
                    ";
        $sql_ds = mysqli_query($koneksi, $query_ds);
        $data_ds = mysqli_fetch_array($sql_ds);
        $invocenya = $data_ds['invoice'];
        $idnya = $data_ds['idpoproduk'];
        $data_nods=$koneksi->query("SELECT no_ds FROM pods WHERE no_ds= '$no_ds'");
        $t_nods=$data_nods->num_rows;
    } else {
        echo "No DS tidak ditemukan.";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketer | WNJ.ID</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar2.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <div class="text-center">
            <h3><?= $namapo; ?></h3>
        </div>
        <div class="mt-4">
            <p><strong>Invoice :</strong> <?= $data_ds['no_ds']; ?></p>
            <p><strong>Pengirim:</strong> <?= $data_ds['namapengirim']; ?> / <?= $data_ds['tlppengirim']; ?></p>
            <p><strong>Penerima:</strong> <?= $data_ds['namapenerima']; ?> / <?= $data_ds['tlppenerima']; ?></p>
            <p><strong>Alamat Penerima:</strong> <?= $data_ds['alamatpenerima']; ?></p>
            <p><?= $data_ds['provinsi']; ?>, <?= $data_ds['kota']; ?>, <?= $data_ds['kecamatan']; ?></p>
        </div>

        <?php if ($idnya != 186 && $proses): ?>
            <a href="print_nods?id=<?= $no_ds; ?>" target="_blank" class="btn btn-info btn-sm">Print Inv</a>
        <?php endif; ?>

        <div class="table-responsive mt-4">
            <form method="post">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th><input type='checkbox' id='checkAll'></th>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <?php if ($idnya != 186 && $idnya != 187): ?>
                                <th>Sisa Stok</th>
                                <th>Opsi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $ambil = $koneksi->query("SELECT podetail.variant, pods.jumlah, pods.invoice, pods.idpodetail, pods.id, pods.idpomitra
                                                    FROM pods
                                                    JOIN podetail ON podetail.idpodetail = pods.idpodetail
                                                    WHERE pods.no_ds= '$no_ds'
                                                    ORDER BY pods.id ASC");
                        while($data = $ambil->fetch_assoc()) {
                            $idpodetail = $data['idpodetail'];
                            $invoice = $data['invoice'];
                            $idpomitra = $data['idpomitra'];
                            $id = $data['id'];
                            $sql_sarung = $koneksi->query("SELECT * FROM podetail WHERE variant LIKE '%Sarung Etnic%'");
                            $query_sarung = $sql_sarung->fetch_assoc();
                            $idsarung = $query_sarung['idpodetail'];

                            $data_jumlah = $koneksi->query("SELECT pomitra.jumlah, pomitra.custom
                                                            FROM pomitra
                                                            WHERE (pomitra.invoice= '$invoice')
                                                            and pomitra.idpodetail = '$idpodetail'
                                                            and pomitra.idpomitra = '$idpomitra'
                                                            GROUP BY pomitra.idpomitra");
                            $tampilprogres = $data_jumlah->fetch_assoc();

                            $data_jumlah2 = $koneksi->query("SELECT SUM(pods.jumlah) as progresnya
                                                            FROM pods
                                                            WHERE pods.invoice='$invoice'
                                                            and pods.idpodetail = '$idpodetail'
                                                            and pods.idpomitra = '$idpomitra'");
                            $tampilprogres2 = $data_jumlah2->fetch_assoc();
                            $sisa = $tampilprogres['jumlah'] - $tampilprogres2['progresnya'];

                            if ($idpodetail == 8920) {
                                $datacustom = $koneksi->query("SELECT pomitra.custom
                                                                FROM pomitra
                                                                WHERE pomitra.idpomitra = '$idpomitra'");
                                $custom = $datacustom->fetch_assoc();
                            }
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><input type='checkbox' name='update[]' value='<?= $id ?>'></td>
                            <td><?= $data['variant']; ?> <?= $tampilprogres['custom']; ?></td>
                            <td><?= $data['jumlah']; ?></td>
                            <?php if ($idnya != 186 && $idnya != 187): ?>
                            <td><?= $sisa; ?></td>
                            <td>
                                <?php if ($idpoproduk == '270' && $data['idpodetail'] == $idsarung): ?>
                                    <input type='number' class="form-control" min="0" name='jumlah<?= $id ?>' value='' max="<?=
 $sisa ?>" disabled>
                                <?php else: ?>
                                    <input type='number' class="form-control" min="0" name='jumlah<?= $id ?>' value='' max="<?=
 $sisa ?>">
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php if (!$proses): ?>
                    <button type="submit" class="btn btn-sm btn-success" name="ubah">Ubah</button>
                <?php endif; ?>
            </form>
        </div>
        <?php if ($idnya == 186 || $idnya == 187): ?>
            <?php if ($jmlh_semua % 3 != 0): ?>
                <div class="alert alert-danger" role="alert">
                    Voal Belum Kelipatan 3
                </div>
            <?php else: ?>
                <h3>Keterangan Isi Box</h3>
                <?php if ($datanya): ?>
                    <?php 
                    $ambil_box = $koneksi->query("SELECT * FROM hampers
                                                    WHERE hampers.no_ds= '$no_ds'
                                                    ORDER BY nobox ASC"); 
                    while ($data_box = $ambil_box->fetch_assoc()) {
                        $result_explode = explode('|', $data_box['ucapan']);
                        $dari = $result_explode[0];
                        $kepada = $result_explode[1];
                        $ucapan = $result_explode[2];
                        $result_explode1 = explode('|', $data_box['idpodetail']);
                        $detail1 = $result_explode1[0];
                        $detail2 = $result_explode1[1];
                        $detail3 = $result_explode1[2];
                    ?>
                    <div>
                        <label>Box <?= $data_box['nobox'] ?></label>
                        <hr style="margin-top: 0px;">
                        <table class="table table-bordered">
                            <tr>
                                <th>Item</th>
                                <th>Kartu Ucapan</th>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">
                                    <?= str_replace("|", "<br>", $data_box['idpodetail']); ?>
                                </td>
                                <td>
                                    <p><strong>Dari:</strong> <?= $dari; ?></p>
                                    <p><strong>Kepada:</strong> <?= $kepada; ?></p>
                                    <p><strong>Ucapan:</strong> <?= $ucapan; ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php } ?>
                <?php else: ?>
                    <?php  
                    $angka = $jmlh_semua / 3;
                    for ($x = 1; $x <= $angka; $x++) {
                    ?>
                    <form method="post">
                        <div class="mb-4">
                            <label>Box : <?= $x ?></label>
                            <input type="hidden" name="box[]" value="<?= $x ?>" readonly>
                            <div class="form-group">
                                <label>Format Kartu Ucapan</label>
                                <div class="form-group">
                                    <label>Dari</label>
                                    <input type="text" class="form-control" name="dari[]">
                                </div>
                                <div class="form-group">
                                    <label>Kepada</label>
                                    <input type="text" class="form-control" name="kepada[]">
                                </div>
                                <div class="form-group">
                                    <label>Ucapan</label>
                                    <textarea class="form-control" name="keterangan[]"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Item 1</label>
                            <select class="form-control" name="idpodetail1[]" required>
                                <option value="">~ Pilih Variant ~</option>
                                <?php 
                                if ($t_nods == 0) {
                                    $ambil = $koneksi->query("SELECT podetail.variant, podetail.idpodetail, pomitra.custom
                                                                FROM pomitra
                                                                JOIN podetail on podetail.idpodetail = pomitra.idpodetail
                                                                WHERE pomitra.invoice='$invocenya'
                                                                AND pomitra.jumlah > 0");
                                } else {
                                    $ambil = $koneksi->query("SELECT podetail.variant, podetail.idpodetail, pomitra.custom
                                                                FROM pomitra
                                                                JOIN podetail on podetail.idpodetail = pomitra.idpodetail
                                                                WHERE pomitra.invoice='$invocenya'
                                                                AND pomitra.jumlah > 0
                                                                AND pomitra.idpodetail IN (SELECT pods.idpodetail 
                                                                                            FROM pods 
                                                                                            WHERE pods.invoice = '$invocenya'
                                                                                            AND no_ds='$no_ds'
                                                                                            AND pods.jumlah > 0)");
                                }
                                while ($data = $ambil->fetch_assoc()) {
                                    $custom = $data['custom'];
                                ?>
                                <option>
                                    <?= $data['variant']; ?>
                                    <?php if ($custom): ?>
                                        <?= $custom; ?>
                                    <?php endif; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Item 2</label>
                            <select class="form-control" name="idpodetail2[]" required>
                                <option value="">~ Pilih Variant ~</option>
                                <?php 
                                if ($t_nods == 0) {
                                    $ambil = $koneksi->query("SELECT podetail.variant, podetail.idpodetail, pomitra.custom
                                                                FROM pomitra
                                                                JOIN podetail on podetail.idpodetail = pomitra.idpodetail
                                                                WHERE pomitra.invoice='$invocenya'
                                                                AND pomitra.jumlah > 0");
                                } else {
                                    $ambil = $koneksi->query("SELECT podetail.variant, podetail.idpodetail, pomitra.custom
                                                                FROM pomitra
                                                                JOIN podetail on podetail.idpodetail = pomitra.idpodetail
                                                                WHERE pomitra.invoice='$invocenya'
                                                                AND pomitra.jumlah > 0
                                                                AND pomitra.idpodetail IN (SELECT pods.idpodetail 
                                                                                            FROM pods 
                                                                                            WHERE pods.invoice = '$invocenya'
                                                                                            AND no_ds='$no_ds'
                                                                                            AND pods.jumlah > 0)");
                                }
                                while ($data = $ambil->fetch_assoc()) {
                                    $custom = $data['custom'];
                                ?>
                                <option>
                                    <?= $data['variant']; ?>
                                    <?php if ($custom): ?>
                                        <?= $custom; ?>
                                    <?php endif; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Item 3</label>
                            <select class="form-control" name="idpodetail3[]" required>
                                <option value="">~ Pilih Variant ~</option>
                                <?php 
                                if ($t_nods == 0) {
                                    $ambil = $koneksi->query("SELECT podetail.variant, podetail.idpodetail, pomitra.custom
                                                                FROM pomitra
                                                                JOIN podetail on podetail.idpodetail = pomitra.idpodetail
                                                                WHERE pomitra.invoice='$invocenya'
                                                                AND pomitra.jumlah > 0");
                                } else {
                                    $ambil = $koneksi->query("SELECT podetail.variant, podetail.idpodetail, pomitra.custom
                                                                FROM pomitra
                                                                JOIN podetail on podetail.idpodetail = pomitra.idpodetail
                                                                WHERE pomitra.invoice='$invocenya'
                                                                AND pomitra.jumlah > 0
                                                                AND pomitra.idpodetail IN (SELECT pods.idpodetail 
                                                                                            FROM pods 
                                                                                            WHERE pods.invoice = '$invocenya'
                                                                                            AND no_ds='$no_ds'
                                                                                            AND pods.jumlah > 0)");
                                }
                                while ($data = $ambil->fetch_assoc()) {
                                    $custom = $data['custom'];
                                ?>
                                <option>
                                    <?= $data['variant']; ?>
                                    <?php if ($custom): ?>
                                        <?= $custom; ?>
                                    <?php endif; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php } ?>
                        <div class='form-group row'>    
                            <div class="col-sm-3">    
                            <button type="submit" name="simpan" class="btn btn-sm btn-info">Kirim</button>
                            </div>
                        </div>
                    </form>
                    <?php
                    if(isset($_POST["simpan"])){
                        $idpodetail1=$_POST["idpodetail1"];
                        $idpodetail2=$_POST["idpodetail2"];
                        $idpodetail3=$_POST["idpodetail3"];
                        $dari=$_POST["dari"];
                        $kepada=$_POST["kepada"];
                        $keterangan=$_POST["keterangan"];
                        $box=$_POST["box"];
                        $jmlh=count($box);
                        date_default_timezone_set('Asia/Jakarta');
                        $today = date("Y-m-d H:i:s");   

                        for($x=0;$x<$jmlh;$x++){
                            $keterangannya = $dari[$x].'|'.$kepada[$x].'|'.$keterangan[$x];
                            $produk = $idpodetail1[$x].'|'.$idpodetail2[$x].'|'.$idpodetail3[$x];
                            $sqlds = $koneksi->query("INSERT INTO hampers (id,no_ds,idpodetail,nobox,ucapan) 
                                                        values (null,'$no_ds','$produk','$box[$x]','$keterangannya')
                                                    ");
                        }
                        if ($sqlds) {
                            echo "<script>alert('Data Berhasil Disimpan');</script>";
                            echo "<script>location='detail_ds.php?no_ds=$no_ds';</script>";
                        } else {
                            echo "<script>location='detail_ds.php?no_ds=$no_ds';</script>";
                        }
                    }
                ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php elseif (in_array($idpoproduk, ['276', '278', '281', '283', '286', '287'])): ?>
            <h3>Keterangan :</h3>
            <?php if ($datanya): ?>
                <?php 
                    $ambil_box = $koneksi->query("SELECT * FROM hampers WHERE hampers.no_ds = '$no_ds' ORDER BY nobox ASC"); 
                    while ($data_box = $ambil_box->fetch_assoc()) {
                        $result_explode = explode('|', $data_box['ucapan']);
                        $dari = $result_explode[0];
                        $kepada = $result_explode[1];
                        $ucapan = $result_explode[2];
                        $result_explode1 = explode('|', $data_box['idpodetail']);
                        $detail1 = $result_explode1[0];
                        $detail2 = $result_explode1[1];
                        $detail3 = $result_explode1[2];
                ?>
                    <div>
                        <hr style="margin-top: 0px;">
                        <table style="width: 100%;">
                            <tr>
                                <th>Item</th>
                                <th style="width: 50%">Kartu Ucapan</th>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">
                                    <?php echo str_replace("|", "<br>", $data_box['idpodetail']); ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Dari</th>
                                <td>:</td>
                                <td><?= $dari; ?></td>
                            </tr>
                            <tr>
                                <th>Kepada</th>
                                <td>:</td>
                                <td><?= $kepada; ?></td>
                            </tr>  
                            <tr>
                                <th style="vertical-align: top;">Ucapan</th>
                                <td>:</td>
                                <td><?= $ucapan; ?></td>
                            </tr> 
                        </table>
                    </div>
                <?php } ?>
            <?php else: ?>
                <?php for ($x = 1; $x <= $jmlh_semua; $x++): ?>
                    <form method="post">
                        <input type="hidden" name="box[]" value="<?= $x ?>" readonly>
                        <div class="col-4"> 
                            <label>Format Kartu Ucapan</label>  
                            <div class="form-group">
                                <label>Dari</label>
                                <input type="text" class="form-control" name="dari[]">
                            </div>    
                            <div class="form-group">
                                <label>Kepada</label>
                                <input type="text" class="form-control" name="kepada[]">
                            </div>
                            <div class="form-group">
                                <label>Ucapan</label>
                                <textarea class="form-control" name="keterangan[]"></textarea>
                            </div>
                        </div>
                        <label>Variant</label>
                        <select class="form-control" name="idpodetail1[]" required>
                            <option value="">~ Pilih Variant ~</option>   
                            <?php 
                                if ($t_nods == 0) {
                                    $ambil = $koneksi->query("SELECT podetail.variant, podetail.idpodetail, pomitra.custom
                                        FROM pomitra
                                        JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                        WHERE pomitra.invoice = '$invocenya'
                                        AND pomitra.jumlah > 0");
                                } else {
                                    $ambil = $koneksi->query("SELECT podetail.variant, podetail.idpodetail, pomitra.custom
                                        FROM pomitra
                                        JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                        WHERE pomitra.invoice = '$invocenya'
                                        AND pomitra.jumlah > 0
                                        AND pomitra.idpodetail IN (SELECT pods.idpodetail 
                                                                    FROM pods 
                                                                    WHERE pods.invoice = '$invocenya'
                                                                    AND no_ds = '$no_ds'
                                                                    AND pods.jumlah > 0)");
                                }
                                while ($data = $ambil->fetch_assoc()) {
                                    $custom = $data['custom'];
                            ?>
                                <option value="<?= $data['idpodetail']; ?>"><?= $data['variant']; ?><?= $custom ? " | $custom" : ""; ?></option>
                            <?php } ?>
                        </select>
                                <?php endfor; ?>
                                    <div class="form-group row">    
                                        <div class="col-sm-3">    
                                            <button type="submit" name="simpan" class="btn btn-sm btn-info">Kirim</button>
                                        </div>
                                    </div>
                                </form>
            <?php endif; ?>
        <?php endif; ?>
        <div class="d-flex justify-content-between mb-0 mt-5">
            <h4 style="color: #153448;">Input Variant</h4>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div id="variantContainer">
                <div class="row mx-0" id="variantForm_0">
                    <div class="col-12">
                        <h6 class="badge text-bg-info text-uppercase">Variant 1</h6>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="variant" class="d-flex align-items-center">Produk<p class="text-danger p-0 m-0 ml-1">*</p></label>
                            <select name="variant[]" class="form-control form-control-sm" id="variantSelect_0" onchange="updateHiddenProductInput(this, 0)">
                                <option value="" disabled selected>Silahkan pilih variant</option>
                                <?php
                                    $ambil = $koneksi->query("SELECT DISTINCT podetail.variant, podetail.idpodetail, pomitra.custom
                                                                FROM poproduk 
                                                                INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                                INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                                INNER JOIN pomitra ON podetail.idpodetail = pomitra.idpodetail
                                                                LEFT JOIN pods ON podetail.idpodetail = pods.idpodetail AND pods.invoice = '$invocenya'
                                                                WHERE poproduk.idpoproduk = '$idpoproduk'
                                                                AND podetail.variant NOT LIKE '%Custom%'
                                                                AND pods.idpodetail IS NULL
                                                                ORDER BY podetail.idpodetail ASC;
                                                                ");
                                    if ($ambil) {
                                        while ($data = $ambil->fetch_assoc()) {
                                            $custom = $data['custom'];
                                            ?>
                                            <option value="<?= $data['idpodetail']; ?>">
                                                <?= $data['variant']; ?><?= !empty($custom) ? " | $custom" : ""; ?> | <!-- Menampilkan stok -->
                                            </option>
                                            <?php
                                        }
                                    } else {
                                        echo "Error executing query: " . $koneksi->error;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="hiddenProductInput_0" name="hiddenProductInput[]" value="">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="jumlah" class="d-flex align-items-center">Jumlah<p class="text-danger p-0 m-0 ml-1">*</p></label>
                            <input type="number" class="form-control form-control-sm" placeholder="Masukan Jumlah" name="jumlah[]" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <button type="button" class="btn btn-secondary btn-sm mb-3" id="buttonAddVariant">Tambah Variant</button>
            </div>
            <div class="col-sm-12">
                <button class="btn btn-primary btn-sm" type="submit" name="insert-variant">Tambah Produk Variant</button>
            </div>
            <div class="col-sm-12">
                <hr />
            </div>
        </form>
        <?php
            if (isset($_POST["insert-variant"])) {
                $processed = false;
                $koneksi->begin_transaction(); // Mulai transaksi
                try {
                    foreach ($_POST['hiddenProductInput'] as $key => $index) {
                        $variant = mysqli_real_escape_string($koneksi, $_POST['variant'][$key]);
                        $jumlah = mysqli_real_escape_string($koneksi, $_POST['jumlah'][$key]);
                        date_default_timezone_set('Asia/Jakarta');
                        $waktu = date("H:i:s");

                        if ($jumlah > 0) {
                            // Cek apakah data sudah ada di tabel pomitra
                            $checkSql = "SELECT * FROM pomitra WHERE idpoproduk = '$idpoproduk' AND idpodetail = '$variant' AND invoice = '$invoice'";
                            $checkQuery = $koneksi->query($checkSql);
                            $pomitra = $checkQuery->fetch_assoc();

                            $sql = "SELECT * FROM podetail WHERE idpodetail = '$variant'";
                            $query = $koneksi->query($sql);
                            $podetail = $query->fetch_assoc();

                            if ($checkQuery) {
                                if ($checkQuery->num_rows > 0) {
                                    // Jika data sudah ada, lakukan update
                                    $idpomitra = $pomitra['idpomitra'];
                                    $newJumlah = $pomitra['jumlah'] + $jumlah;
                                    $newTotal = $newJumlah * $podetail['harga'];
                                    
                                    $updatePomitra = $koneksi->query("UPDATE pomitra SET jumlah = '$newJumlah', total = '$newTotal', waktu = '$waktu' WHERE idpomitra = '$idpomitra'");
                                    
                                    if ($updatePomitra) {
                                        $insertPods = $koneksi->query("INSERT INTO pods (id, no_ds, invoice, idpodetail, idpomitra, jumlah, waktu)
                                        VALUES (NULL, '$no_ds', '$invoice', '$variant', '$idpomitra', '$jumlah', NOW())");

                                        if (!$insertPods) {
                                            throw new Exception("Gagal Menambahkan Produk Dropship: " . $koneksi->error);
                                        }
                                    } else {
                                        throw new Exception("Gagal Mengupdate Produk Dropship: " . $koneksi->error);
                                    }
                                } else {
                                    // Jika data belum ada, lakukan insert
                                    $harga = $podetail['harga'];
                                    $idpo = $podetail['idpo'];
                                    $total = $jumlah * $harga;

                                    $insertPomitra = $koneksi->query("INSERT INTO pomitra (idpomitra, idmitramarketer, idpoproduk, idpo, idpodetail, jumlah, total, invoice, status, tgl, waktu) VALUES
                                    (null, '$idmitramarketer', '$idpoproduk', '$idpo', '$variant', '$jumlah', '$total', '$invoice', 'Belum DP', NOW(), '$waktu')");

                                    if ($insertPomitra) {
                                        $idpomitra = $koneksi->insert_id;

                                        $insertPods = $koneksi->query("INSERT INTO pods (id, no_ds, invoice, idpodetail, idpomitra, jumlah, waktu)
                                        VALUES (NULL, '$no_ds', '$invoice', '$variant', '$idpomitra', '$jumlah', NOW())");

                                        if (!$insertPods) {
                                            throw new Exception("Error executing insert into Pods: " . $koneksi->error);
                                        }
                                    } else {
                                        throw new Exception("Error executing insert into Pomitra: " . $koneksi->error);
                                    }
                                }
                            } else {
                                throw new Exception("Error checking Pomitra: " . $koneksi->error);
                            }
                        }
                    }

                    $koneksi->commit(); // Commit transaksi
                    $processed = true;
                } catch (Exception $e) {
                    $koneksi->rollback(); // Rollback transaksi jika ada error
                    echo $e->getMessage();
                }

                if ($processed) {
                    if ($idpoproduk == '271') {
                        echo "<script>alert('data berhasil dikirim');</script>";
                        echo "<script>location='datapom3?id=$idpoproduk&invoice=$invoice';</script>";
                    } else {
                        echo "<script>alert('data berhasil dikirim');</script>";
                        echo "<script>location='datapo?id=$idpoproduk&invoice=$no_ds';</script>";
                    }
                } else {
                    echo "<script>alert('Tidak ada item yang diproses.')</script>";
                    echo "<script>location='formpoku?id=$idpoproduk';</script>";
                }
            }
        ?>

    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <?php 
        if (isset($_POST['ubah'])) {
            date_default_timezone_set('Asia/Jakarta');
            $today = date("Y-m-d H:i:s");

            if (isset($_POST['update'])) {
                foreach ($_POST['update'] as $updateid) {   
                    $jumlah = $_POST['jumlah' . $updateid];
                    if ($jumlah != '') {
                        $sql = $koneksi->query("UPDATE pods SET jumlah='$jumlah', waktu='$today' WHERE id='$updateid'");
                    }
                }

                if ($sql) {
                    echo "<script>alert('Data Berhasil Disimpan');</script>";
                    echo "<script>location='detail_ds?no_ds=$no_ds';</script>";
                } else {
                    echo "<script>alert('Data Gagal Disimpan');</script>";
                    echo "<script>location='detail_ds?no_ds=$no_ds';</script>";
                }
            }
        }
    ?>
    <!-- PHP END -->

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script type="text/javascript">
        const buttonAddVariant = document.getElementById("buttonAddVariant");
        const variantContainer = document.getElementById("variantContainer");
        let variantFormCount = 0;

        buttonAddVariant.addEventListener('click', function() {
            variantFormCount++;

            const variantForm = document.createElement('div');
            variantForm.className = "row mx-0";
            variantForm.id = `variantForm_${variantFormCount}`;
            variantForm.innerHTML = `
                <div class="col-12">
                    <h6 class="badge text-bg-info text-uppercase">Produk ${variantFormCount + 1}</h6>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="variant" class="d-flex align-items-center">Produk<p class="text-danger p-0 m-0 ml-1">*</p></label>
                        <select name="variant[]" class="form-control form-control-sm" id="variantSelect_${variantFormCount}" onchange="updateHiddenProductInput(this, ${variantFormCount})">
                            <option value="" disabled selected>Silahkan pilih variant</option>
                            <?php
                                $ambil = $koneksi->query("SELECT DISTINCT podetail.variant, podetail.idpodetail, pomitra.custom
                                                            FROM poproduk 
                                                            INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                            INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                                            INNER JOIN pomitra ON podetail.idpodetail = pomitra.idpodetail
                                                            LEFT JOIN pods ON podetail.idpodetail = pods.idpodetail AND pods.invoice = '$invocenya'
                                                            WHERE poproduk.idpoproduk = '$idpoproduk'
                                                            AND podetail.variant NOT LIKE '%Custom%'
                                                            AND pods.idpodetail IS NULL
                                                            ORDER BY podetail.idpodetail ASC");
                                if ($ambil) {
                                    while ($data = $ambil->fetch_assoc()) {
                                        $custom = $data['custom'];
                                        ?>
                                        <option value="<?= $data['idpodetail']; ?>">
                                            <?= $data['variant']; ?><?= !empty($custom) ? " | $custom" : ""; ?>
                                        </option>
                                        <?php
                                    }
                                } else {
                                    echo "Error executing query: " . $koneksi->error;
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <input type="hidden" id="hiddenProductInput_${variantFormCount}" name="hiddenProductInput[]" value="">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="jumlah" class="d-flex align-items-center">Jumlah<p class="text-danger p-0 m-0 ml-1">*</p></label>
                        <input type="number" class="form-control form-control-sm" placeholder="Masukan Jumlah" name="jumlah[]" required>
                    </div>
                </div>
            `;
            variantContainer.appendChild(variantForm);
        });

        function updateHiddenProductInput(selectElement, index) {
            const hiddenProductInput = document.getElementById(`hiddenProductInput_${index}`);
            hiddenProductInput.value = selectElement.value;
        }

    </script>
    <script type="text/javascript">
        $(document).ready(function(){

            // Check/Uncheck ALl
            $('#checkAll').change(function(){
                if($(this).is(':checked')){
                    $('input[name="update[]"]').prop('checked',true);
                }else{
                    $('input[name="update[]"]').each(function(){
                        $(this).prop('checked',false);
                    }); 
                }
            });

            // Checkbox click
            $('input[name="update[]"]').click(function(){
                var total_checkboxes = $('input[name="update[]"]').length;
                var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

                if(total_checkboxes_checked == total_checkboxes){
                    $('#checkAll').prop('checked',true);
                }else{
                    $('#checkAll').prop('checked',false);
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>