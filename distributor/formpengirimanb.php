<?php
    include 'koneksi.php';
    include '../includes/invoice_helper.php';
    include 'assets/components/Sessions/sesDistri.php';

    $invoice = $_GET["id"]    ?? '';
    $berat   = $_GET["berat"] ?? 0;
    $jenis   = $_GET["jenis"] ?? '';
    $idadmin = $_SESSION["idadmin"];

    $stmtUser = $koneksi->prepare("SELECT * FROM admin_mitra WHERE idadmin = ?");
    $stmtUser->bind_param('s', $idadmin);
    $stmtUser->execute();
    $dataUser = $stmtUser->get_result()->fetch_assoc();

    // Pastikan invoice ini benar-benar milik mitra yang sedang login sebelum diproses/ditampilkan
    $stmtOwn = $koneksi->prepare("SELECT COUNT(*) AS jumlah FROM ordermitra WHERE invoice = ? AND idmitra = ?");
    $stmtOwn->bind_param('ss', $invoice, $idadmin);
    $stmtOwn->execute();
    $ownCheck = $stmtOwn->get_result()->fetch_assoc();

    if ($invoice === '' || ($ownCheck['jumlah'] ?? 0) == 0) {
        $_SESSION['message'] = 'Invoice tidak ditemukan';
        header('Location: view_cart.php');
        exit;
    }
?>

<html lang="en">
<head>
    <title>Mitra <?= htmlspecialchars($dataUser['namamitra'] ?? '') ?>| Wanoja</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
    <script type="text/javascript">
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>
<body>
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <div class="container mt-3">
        <table id="myJudul" class="w3-table-all w3-centered">
            <tr>
                <th style="width:5%;"></th>
                <th style="width:90%;">Data Pengiriman</th>
                <th style="width:5%;"></th>
            </tr>
        </table>
    </div>
    <div class="container">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <p align="right">
                        <input type="radio" onclick="javascript:window.location.href='formpengirimanb.php?id=<?= urlencode($invoice) ?>&berat=<?= urlencode($berat) ?>&jenis=<?= urlencode($jenis) ?>'; " checked="checked"> Dropship
                        <input type="radio" onclick="javascript:window.location.href='formpengiriman2.php?id=<?= urlencode($invoice) ?>&berat=<?= urlencode($berat) ?>&jenis=<?= urlencode($jenis) ?>'; "> Kirim Ke Alamat Pribadi
                    </p>
                    <form method="post">
                        <div class="form-group">
                            <label>Nama Pengirim</label>
                            <input type="text" class="form-control" name="namapengirim" value="<?= htmlspecialchars($dataUser['namamitra'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Telepon Pengirim</label>
                            <input type="text" class="form-control" name="tlppengirim" value="<?= htmlspecialchars($dataUser['whatsapp'] ?? '') ?>" required maxlength="17">
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Nama Penerima</label>
                            <input type="text" class="form-control" name="namapenerima" required>
                        </div>
                        <div class="form-group">
                            <label>Telepon Penerima</label>
                            <input type="text" class="form-control" name="tlppenerima" required maxlength="17">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" name="alamat" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Kode POS</label>
                            <input type="text" class="form-control" name="kodepos" value="<?= htmlspecialchars($dataUser['kodepos'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="prov">Provinsi Tujuan</label><br>
                            <select class="form-control" id="prov" name="prov" required>
                                <option disabled='disabled' selected>~Pilih Provinsi Tujuan~</option>
                                <?php
                                    $ambil = $koneksi->query("SELECT * FROM tb_ro_provinces");
                                    while($row = $ambil->fetch_assoc()){
                                ?>
                                    <option value="<?= htmlspecialchars($row['province_id']) ?>|<?= htmlspecialchars($row['province_name']) ?>"><?= htmlspecialchars($row['province_name']) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kabupaten">Kota/Kabupaten Tujuan</label><br>
                            <select class="form-control" id="kabupaten" name="kabupaten" required value=""></select>
                        </div>
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan Tujuan</label><br>
                            <select class="form-control" id="kecamatan" name="kecamatan" required></select>
                        </div>
                        <div class="form-group">
                            <label for="berat">Berat (gram)</label><br>
                            <input class="form-control" id="berat" type="text" name="berat" value="<?= htmlspecialchars($berat) ?>" readonly />
                        </div>
                        <div class="form-group">
                            <label for="kurir">Kurir</label><br>
                            <select class="form-control" id="kurir" name="kurir" required>
                                <option disabled='disabled' selected>~Pilih Kurir Pengiriman~</option>
                                <option value="OR|jne">JNE</option>
                                <option value="OR|tiki">TIKI</option>
                                <option value="OR|pos">POS INDONESIA</option>
                                <option value="OR|wahana">WAHANA</option>
                                <option value="OR|sicepat">SICEPAT</option>
                                <option value='OR|jnt'>J&T</option>
                                <option value='OR|lion'>LION</option>
                                <option value='OR|anteraja'>Anteraja</option>
                                <option value='OR|ide'>ID Express</option>
                                <optgroup label="Lainnya (Ongkir Manual)">
                                <option value='OM|idetruck'>ID Express Truck</option>
                                <option value='OM|jntcargo'>J&T Cargo</option>
                                <option value='OM|jtr'>JTR</option>
                                <option value='OM|Ahsan'>Ahsan</option>
                                <option value='OM|Baraka'>Baraka</option>
                                <option value='OM|Dakota'>Dakota</option>
                                <option value='OM|IndahCargo'>IndahCargo</option>
                                <option value='OM|Adam Cargo'>Adam Cargo</option>
                                <option value='OM|Pegasus'>Pegasus</option>
                                <option value='OM|Gosend'>GoSend</option>
                                <option value='OM|KALOG'>KALOG</option>
                                <option value='OM|Sentral'>Sentral</option>
                                <option value='OM|CMC KARGO'>CMC CARGO</option>
                                <option value='OM|Triplogic'>Triplogic</option>
                                <option value='OM|Ambil ke Pusat'>Ambil Ke Pusat</option>
                                <option value='OM|Disatukan'>Disatukan Paket Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group" id="ongkir">
                            <label for="layanan">Layanan</label><br>
                            <select class="form-control" name="layanan" id="layanan" >
                                <option value='layanan'>-kosong-</option>
                            </select>
                            <label><font color="grey">*Jika Memilih Kurir dengan Kategori "Lainnya (Ongkir Manual)" lanjut pilih "Kirim" jika opsi layanan masih kosong</font></label>
                        </div>
				    </div>
                </div>
            </div>
        </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#prov').change(function(){
                var provinsi = $('#prov').val();

                $.ajax({
                    type : 'GET',
                    url : 'cek_kabupaten2.php',
                    data :  'prov_id=' + provinsi,
                        success: function (data) {
                        $("#kabupaten").html(data);
                    }
                });
            });


            $('#kabupaten').change(function(){
                var kabupaten = $('#kabupaten').val();

                $.ajax({
                    type : 'GET',
                    url : 'cek_kecamatan2.php',
                    data :  'kabupaten_id=' + kabupaten,
                        success: function (data) {
                        $("#kecamatan").html(data);
                    }
                });
            });



            $("#kurir").change(function(){
                var asal = $('#asal').val();
                var kab = $('#kabupaten').val();
                var kec = $('#kecamatan').val();
                var kurir = $('#kurir').val();
                var berat = $('#berat').val();

                $.ajax({
                    type : 'POST',
                    url : 'cek_ongkir.php',
                    data :  {'kab_id' : kab, 'kec_id' : kec, 'kurir' : kurir, 'asal' : asal, 'berat' : berat},
                        success: function (data) {
                        $("#layanan").html(data);
                    }
                });
            });
        });
    </script>
    </div>


    <center><button type="submit" class="btn btn-primary btn-lg" name="kirim">Kirim</button></center>
    </form>
    <br><br><br><br>
</body>

    <?php
        if (isset($_POST['kirim'])) {

            $namapengirim = htmlspecialchars($_POST["namapengirim"] ?? '');
            $tlppengirim  = htmlspecialchars($_POST["tlppengirim"] ?? '');
            $namapenerima = htmlspecialchars($_POST["namapenerima"] ?? '');
            $tlppenerima  = htmlspecialchars($_POST["tlppenerima"] ?? '');
            $alamat       = htmlspecialchars($_POST["alamat"] ?? '');

            $provinsi_id    = explode('|', $_POST["prov"] ?? '');
            $provinsi       = $provinsi_id[1] ?? '';

            $kabupaten_id   = explode('|', $_POST["kabupaten"] ?? '');
            $kabupaten      = $kabupaten_id[1] ?? '';

            $kecamatan_id   = explode('|', $_POST["kecamatan"] ?? '');
            $kecamatan      = $kecamatan_id[1] ?? '';

            $layanan        = $_POST["layanan"] ?? '';
            $result_explode = explode('|', $layanan);
            $layananku      = $result_explode[0] ?? '';

            $ekspedisinya   = explode('|', $_POST["kurir"] ?? '');
            $om             = $ekspedisinya[0] ?? '';
            $ekspedisi      = $ekspedisinya[1] ?? '';

            if ($layanan == 'layanan') {
                echo "<script>alert('Gagal Simpan, Jenis layanan masih kosong');</script>";
                echo "<script>location='formpengirimanb.php?id=" . rawurlencode($invoice) . "&berat=" . rawurlencode($berat) . "'</script>";
                exit;
            }
            if ($layanan == '' && $om <> 'OM') {
                echo "<script>alert('Gagal Simpan, Jenis layanan masih kosong');</script>";
                echo "<script>location='formpengirimanb.php?id=" . rawurlencode($invoice) . "&berat=" . rawurlencode($berat) . "'</script>";
                exit;
            }

            $berat = $_POST["berat"];

            $ongkir2 = $result_explode[1] ?? 0;

            $kodepos = $_POST["kodepos"] ?? '';
            $total   = 0;

            // Jika layanan kosong, set layanan dan ongkir
            if (empty($layananku)) {
                $layananku = null;
                $ongkir    = 0;
            } else {
                $ongkir = (int) $ongkir2;
            }

            $stmtTotal = $koneksi->prepare("SELECT SUM(ordermitra.subtotal) AS subtotal, SUM(ordermitra.jumlah) AS jumlah, variants.jenis
                            FROM ordermitra
                            INNER JOIN variants ON variants.id = ordermitra.idproduk
                            WHERE invoice = ? AND ordermitra.idmitra = ?
                            GROUP BY variants.jenis");
            $stmtTotal->bind_param('ss', $invoice, $idadmin);
            $stmtTotal->execute();
            $totalResult = $stmtTotal->get_result();

            while ($row = $totalResult->fetch_assoc()) {
                $total  = $row['subtotal'];
                $jumlah = $row['jumlah'];
                $jenis  = $row['jenis'];
            }

            if ($jenis == 'Bundling Short') {
                if ($jumlah >= 3) {
                    $diskonPerKelipatan = 35000;
                    $jumlahKelipatan    = floor($jumlah / 3);
                    $totalDiskon        = $jumlahKelipatan * $diskonPerKelipatan;
                    $total             -= $totalDiskon;
                }
            }

            $total = $total + $ongkir;

            $stmtCek = $koneksi->prepare("SELECT * FROM orderpengiriman WHERE invoice = ?");
            $stmtCek->bind_param('s', $invoice);
            $stmtCek->execute();
            $pengirimancek = $stmtCek->get_result()->fetch_assoc();

            if ($pengirimancek) {
                $stmtDelete = $koneksi->prepare("DELETE FROM orderpengiriman WHERE invoice = ?");
                $stmtDelete->bind_param('s', $invoice);
                $stmtDelete->execute();
            }

            $stmtInsert = $koneksi->prepare("INSERT INTO orderpengiriman
                                (idorderp, namapengirim, tlppengirim, namapenerima, tlppenerima,
                                 alamat, provinsi, kota, kecamatan, ekspedisi, layanan, berat, ongkir,
                                 dropship, kodepos, invoice, total, diskonramadhan, tgl)
                                VALUES
                                (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ya', ?, ?, ?, 0, NOW())");
            $stmtInsert->bind_param(
                'ssssssssssdissd',
                $namapengirim, $tlppengirim, $namapenerima, $tlppenerima, $alamat,
                $provinsi, $kabupaten, $kecamatan, $ekspedisi, $layananku,
                $berat, $ongkir, $kodepos, $invoice, $total
            );
            $sukses = $stmtInsert->execute();

            if ($sukses) {
                if (substr($invoice, 0, 1) == "F") {
                    echo "<script>alert('data berhasil ditambah');</script>";
                    echo "<script>location='detailorder_get.php?id=" . rawurlencode($invoice) . "'</script>";
                } elseif (invoiceHasPromoItem($koneksi, 'ordermitra', $invoice)) {
                    echo "<script>alert('Data berhasil ditambah');</script>";
                    echo "<script>location='dataorder2.php?id=" . rawurlencode($invoice) . "'</script>";
                } elseif ($jenis == 'Bundling Short') {
                    echo "<script>alert('Data berhasil ditambah');</script>";
                    echo "<script>location='detailordershort.php?id=" . rawurlencode($invoice) . "'</script>";
                } elseif ($jenis == 'Bundling 3') {
                    echo "<script>alert('Data berhasil ditambah');</script>";
                    echo "<script>location='detailorder3.php?id=" . rawurlencode($invoice) . "'</script>";
                } elseif ($jenis == 'Bundling 5') {
                    echo "<script>alert('Data berhasil ditambah');</script>";
                    echo "<script>location='detailorder5.php?id=" . rawurlencode($invoice) . "'</script>";
                } elseif ($jenis == 'Flash') {
                    echo "<script>alert('Data berhasil ditambah');</script>";
                    echo "<script>location='detailorderb2.php?id=" . rawurlencode($invoice) . "&jenis=" . rawurlencode($jenis) . "'</script>";
                } else {
                    echo "<script>alert('data berhasil ditambah');</script>";
                    echo "<script>location='detailorderb2.php?id=" . rawurlencode($invoice) . "'</script>";
                }
            } else {
                echo "<script>alert('Gagal Simpan, Coba Lagi');</script>";
                echo "<script>location='formpengirimanb.php?id=" . rawurlencode($invoice) . "&berat=" . rawurlencode($berat) . "'</script>";
            }
        }
    ?>
</html>
