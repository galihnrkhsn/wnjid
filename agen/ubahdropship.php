<?php
    session_start();
    error_reporting (0);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesAgen.php';
    include "settingdatatables.php";

    $iddropship = $_GET['iddropship'];
    $sql = mysqli_query($koneksi, "SELECT podropship.*, tb_ro_provinces.province_name, tb_ro_provinces.province_id
                                    FROM podropship
                                    JOIN tb_ro_provinces ON podropship.provinsi = tb_ro_provinces.province_id WHERE iddropship = '$iddropship'
                        ");
      
    $data = mysqli_fetch_array($sql); // Ambil semua data dari hasil eksekusi $sql
    $idpoproduk = $data['idpoproduk'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <div class="container panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <div style="padding: 0 15px;">
                            <form method="post">
                                <div class="form-group">
                                    <label>Nama Pengirim</label>
                                    <input type="text" class="form-control" name="namapengirim" value="<?php echo $data['namapengirim']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Telepon Pengirim</label>
                                    <input type="text" class="form-control" name="tlppengirim" value="<?php echo $data['tlppengirim']; ?>" required>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <label>Nama Penerima</label>
                                    <input type="text" class="form-control" name="namapenerima" value="<?php echo $data['namapenerima']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Telepon Penerima</label>
                                    <input type="text" class="form-control" name="tlppenerima" value="<?php echo $data['tlppenerima']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Alamat Penerima</label>
                                    <textarea class="form-control" name="alamatpenerima" required><?php echo $data['alamatpenerima']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="prov">Provinsi Tujuan</label><br>
                                    <select class="form-control" id="prov" name="prov" required>
                                        <option disabled='disabled' selected>~Pilih Provinsi Tujuan~</option>
                                        <?php
                                        $ambil = $koneksi->query("SELECT * FROM tb_ro_provinces");

                                        while ($row = $ambil->fetch_assoc()) {
                                        ?>
                                            <option value="<?php echo $row['province_id']; ?>|<?php echo $row['province_name']; ?>"><?php echo $row['province_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="kabupaten">Kota/Kabupaten Tujuan</label><br>
                                    <select class="form-control" id="kabupaten" name="kabupaten" required></select>
                                </div>

                                <div class="form-group">
                                    <label for="kecamatan">Kecamatan Tujuan</label><br>
                                    <select class="form-control" id="kecamatan" name="kecamatan" required></select>
                                </div>

                                <?php if ($data['idpoproduk'] == 186 or $data['idpoproduk'] == 187) : ?>
                                    <?php
                                    $result_explode = explode('|', $data['keterangan']);
                                    $dari = $result_explode[0];
                                    $kepada = $result_explode[1];
                                    $ucapan = $result_explode[2];
                                    ?>
                                    <!-- <label>Format Kartu Ucapan</label>  
                                    <div class="form-group">
                                        <label>Dari</label>
                                        <input type="text" class="form-control" name="dari" value="<?= $dari; ?>">
                                    </div>    
                                    <div class="form-group">
                                        <label>Kepada</label>
                                        <input type="text" class="form-control" name="kepada" value="<?= $kepada; ?>">
                                    </div>    
                                    <div class="form-group">
                                        <label>Ucapan</label>
                                        <textarea class="form-control" name="keterangan" maxlength="200"><?= $ucapan; ?></textarea>
                                        <p><strong><font color="red" size="5px">*</font></strong>Max 200 Karakter</p> 
                                    </div>  -->
                                <?php else : ?>
                                    <div class="form-group">
                                        <label>Keterangan</label>
                                        <textarea class="form-control" name="keterangan"><?php echo $data['keterangan']; ?></textarea>
                                    </div>
                                <?php endif ?>

                                <div class="form-group">
                                    <label>Ekspedisi</label>
                                    <select class="form-control" name="ekspedisi">
                                        <option><?php echo $data['ekspedisi']; ?></option>
                                        <option value="jne oke">JNE OKE</option>
                                        <option value="jne reg">JNE REG</option>
                                        <option value="jtr">JTR</option>
                                        <option value="jne yes">JNE YES</option>
                                        <option value="wahana">WAHANA</option>
                                        <option value="sicepat">SICEPAT</option>
                                        <option value="lion">LION PARCEL</option>
                                        <option value="j&t">J&T</option>
                                        <option value="tiki">TIKI</option>
                                        <option value='ide'>ID Express</option>
                                        <option value="pos kilat">POS KILAT</option>
                                        <option value="pos ekonomi jumbo">POS EKONOMI JUMBO</option>
                                        <option value="gosend">Gosend</option>
                                    </select>
                                </div>

                                <center><button type="submit" class="btn btn-primary" name="kirim">Ubah</button></center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <?php
        if (isset($_POST['kirim'])) {
            $namapengirim = addslashes($_POST["namapengirim"]);
            $tlppengirim = addslashes($_POST["tlppengirim"]);
            $namapenerima = addslashes($_POST["namapenerima"]);
            $tlppenerima = addslashes($_POST["tlppenerima"]);
            $alamatpenerima = addslashes($_POST["alamatpenerima"]);
            $keterangan = addslashes($_POST["keterangan"]);
            $ekspedisi = $_POST["ekspedisi"];
            $dari = addslashes(htmlspecialchars($_POST["dari"]));
            $kepada = addslashes(htmlspecialchars($_POST["kepada"]));

            $keterangannya = $keterangan;
            if ($idpoproduk == 186 or $idpoproduk == 187) {
                $keterangannya = $dari . '|' . $kepada . '|' . $keterangan;
            }

            $provinsi_id = $_POST["prov"];
            $result_explode = explode('|', $provinsi_id);
            $provinsi = $result_explode[0];

            $kabupaten_id = $_POST["kabupaten"];
            $result_explode = explode('|', $kabupaten_id);
            $kabupaten = $result_explode[0];

            $kecamatan_id = $_POST["kecamatan"];
            $result_explode = explode('|', $kecamatan_id);
            $kecamatan = $result_explode[0];

            $query = "UPDATE podropship SET namapengirim='$namapengirim',
                                            tlppengirim='$tlppengirim',
                                            namapenerima='$namapenerima',
                                            tlppenerima='$tlppenerima',
                                            alamatpenerima='$alamatpenerima',
                                            provinsi='$provinsi',
                                            kota='$kabupaten',
                                            kecamatan='$kecamatan',
                                            keterangan='$keterangannya',
                                            ekspedisi='$ekspedisi' 
                                            WHERE iddropship='$iddropship'";
            $sql = mysqli_query($koneksi, $query);

            if ($sql) {
                echo "<script>alert('data berhasil diubah');</script>";
                echo "<script>location='listnewpo.php'</script>";
            } else {
                echo "<script>alert('data gagal diubah');</script>";
                echo "<script>location='listnewpo.php'</script>";
            }
        }
    ?>
    <!-- PHP END -->

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#prov').change(function() {
                var provinsi = $('#prov').val();

                $.ajax({
                    type: 'GET',
                    url: 'cek_kabupaten_dropship.php',
                    data: 'prov_id=' + provinsi,
                    success: function(data) {
                        $("#kabupaten").html(data);
                    }
                });
            });

            $('#kabupaten').change(function() {
                var kabupaten = $('#kabupaten').val();

                $.ajax({
                    type: 'GET',
                    url: 'cek_kecamatan_dropship.php',
                    data: 'kabupaten_id=' + kabupaten,
                    success: function(data) {
                        $("#kecamatan").html(data);
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- END SCRIPT -->
</body>
</html>