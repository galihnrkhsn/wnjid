<?php
    session_start();
    error_reporting (0);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';

    $idadmin = $_SESSION['idadmin'];

    if (isset($_GET['no_ds'])) {
        $no_ds = mysqli_real_escape_string($koneksi, $_GET["no_ds"]);

        // Ambil data utama + nama PO + hampers + jumlah
        $query  = "SELECT 
                        pr.namapo, 
                        pd.proses, 
                        pd.idpoproduk,
                        pd.namapengirim, pd.tlppengirim,
                        pd.namapenerima, pd.tlppenerima, pd.alamatpenerima,
                        pd.invoice, pd.iddropship, pd.no_ds,
                        IFNULL(h.no_ds, '') AS hampers_exists,
                        IFNULL(SUM(p.jumlah), 0) AS jumlahnya,
                        prov.province_name AS provinsi,
                        city.city_name AS kota,
                        subd.subdistrict_name AS kecamatan
                    FROM podropship pd
                    JOIN poproduk pr ON pd.idpoproduk = pr.idpoproduk
                    LEFT JOIN pods p ON p.no_ds = pd.no_ds
                    LEFT JOIN hampers h ON h.no_ds = pd.no_ds
                    LEFT JOIN tb_ro_provinces prov ON pd.provinsi = prov.province_id
                    LEFT JOIN tb_ro_cities city ON pd.kota = city.city_id
                    LEFT JOIN tb_ro_subdistricts subd ON pd.kecamatan = subd.subdistrict_id
                    WHERE pd.no_ds = '$no_ds'
                    GROUP BY pd.no_ds
                    LIMIT 1
                ";

        $result = mysqli_query($koneksi, $query);

        if ($result && mysqli_num_rows($result)) {
            $data       = mysqli_fetch_assoc($result);

            $namapo     = $data['namapo'];
            $proses     = $data['proses'];
            $idpoproduk = $data['idpoproduk'];
            $invocenya  = $data['invoice'];
            $idnya      = $data['idpoproduk'];
            $jmlh_semua = $data['jumlahnya'];
            $datanya    = $data['hampers_exists'];
        } else {
            echo "Data tidak ditemukan untuk DS: $no_ds";
        }
    } else {
        echo "No DS tidak ditemukan.";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
      <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <? include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <div class="text-center">
            <h3><?= $namapo; ?></h3>
        </div>
        <div class="mt-4">
            <p><strong>Invoice :</strong> <?= $data['no_ds']; ?></p>
            <p><strong>Pengirim:</strong> <?= $data['namapengirim']; ?> / <?= $data['tlppengirim']; ?></p>
            <p><strong>Penerima:</strong> <?= $data['namapenerima']; ?> / <?= $data['tlppenerima']; ?></p>
            <p><strong>Alamat Penerima:</strong> <?= $data['alamatpenerima']; ?></p>
            <p><?= $data['provinsi']; ?>, <?= $data['kota']; ?>, <?= $data['kecamatan']; ?></p>
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
                        $no     = 1;
                        $sql    = "SELECT 
                                        pods.id, pods.jumlah, pods.invoice, pods.idpodetail, pods.idpomitra,
                                        podetail.variant,
                                        pomitra.jumlah AS jumlah_pomitra, pomitra.custom,
                                        (
                                            SELECT SUM(p2.jumlah) 
                                            FROM pods p2 
                                            WHERE p2.invoice = pods.invoice AND p2.idpodetail = pods.idpodetail AND p2.idpomitra = pods.idpomitra
                                        ) AS progresnya
                                    FROM pods
                                    JOIN podetail ON podetail.idpodetail = pods.idpodetail
                                    JOIN pomitra ON pomitra.invoice = pods.invoice AND pomitra.idpodetail = pods.idpodetail AND pomitra.idpomitra = pods.idpomitra
                                    WHERE pods.no_ds = '$no_ds'
                                    ORDER BY pods.id ASC
                                ";

                        $ambil = $koneksi->query($sql);

                        // Ambil ID Sarung Etnic sekali saja
                        $idsarung = null;
                        if ($idpoproduk == '270') {
                            $result_sarung  = $koneksi->query("SELECT idpodetail FROM podetail WHERE variant LIKE '%Sarung Etnic%' LIMIT 1");
                            $sarung         = $result_sarung->fetch_assoc();
                            $idsarung       = $sarung['idpodetail'] ?? null;
                        }

                        while ($data    = $ambil->fetch_assoc()):
                            $sisa       = $data['jumlah_pomitra'] - $data['progresnya'];
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><input type='checkbox' name='update[]' value='<?= $data['id']; ?>'></td>
                            <td><?= htmlspecialchars($data['variant'] . ' ' . $data['custom']); ?></td>
                            <td><?= $data['jumlah']; ?></td>
                            <?php if ($idnya != 186 && $idnya != 187): ?>
                            <td><?= $sisa; ?></td>
                            <td>
                                <?php if ($idpoproduk == '270' && $data['idpodetail'] == $idsarung): ?>
                                    <input type='number' class="form-control" min="0" name='jumlah<?= $data['id']; ?>' value='' max='<?= $sisa ?>' disabled>
                                <?php else: ?>
                                    <input type='number' class="form-control" min="0" name='jumlah<?= $data['id']; ?>' value='' max='<?= $sisa ?>'>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endwhile; ?>
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
                    $ambil_box          = $koneksi->query("SELECT * FROM hampers
                                                    WHERE hampers.no_ds= '$no_ds'
                                                    ORDER BY nobox ASC"); 
                    while ($data_box = $ambil_box->fetch_assoc()) {
                        $result_explode = explode('|', $data_box['ucapan']);
                        $dari           = $result_explode[0];
                        $kepada         = $result_explode[1];
                        $ucapan         = $result_explode[2];
                        $result_explode1 = explode('|', $data_box['idpodetail']);
                        $detail1        = $result_explode1[0];
                        $detail2        = $result_explode1[1];
                        $detail3        = $result_explode1[2];
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

    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>
    <!-- PHP SYNTAK -->
    <?php 
        if (isset($_POST['ubah'])) {
            date_default_timezone_set('Asia/Jakarta');
            $today = date("Y-m-d H:i:s");

            if (!empty($_POST['update'])) {
                foreach ($_POST['update'] as $updateid) {   
                    $jumlah = $_POST['jumlah' . $updateid] ?? '';
                    if ($jumlah !== '') {
                        $sql = $koneksi->query("UPDATE pods SET jumlah='$jumlah', waktu='$today' WHERE id='$updateid'");
                    }
                }

                if (isset($sql) && $sql) {
                    echo "<script>alert('Data Berhasil Disimpan');</script>";
                    echo "<script>location='detail_ds?no_ds=$no_ds';</script>";
                } else {
                    echo "<script>alert('Data Gagal Disimpan');</script>";
                    echo "<script>location='detail_ds?no_ds=$no_ds';</script>";
                }
            }
        }
    ?>
    <!-- PHP SYNTAK END -->

    <!-- FOOTER -->
    <? include 'menubawah.php'; ?>
    <!-- FOOTER END -->
    
    <!-- SCRIPT -->
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const checkAll = document.getElementById('checkAll');
            const checkboxes = document.querySelectorAll('input[name="update[]"]');

            checkAll.addEventListener('change', function () {
                checkboxes.forEach(cb => cb.checked = this.checked);
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    const total = checkboxes.length;
                    const checked = document.querySelectorAll('input[name="update[]"]:checked').length;
                    checkAll.checked = total === checked;
                });
            });
        });
    </script>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>