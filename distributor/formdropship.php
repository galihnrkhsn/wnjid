<?php
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesDistri.php';
    $idpoproduk = $_GET['id'];
    $invoice = $_GET['invoice'];
    $idadmin = $_SESSION['idadmin'];

    $query_berat = $koneksi->query("SELECT 
                                            podetail.berat, pomitra.jumlah
                                        FROM
                                            pomitra
                                                INNER JOIN
                                            podetail ON podetail.idpodetail = pomitra.idpodetail
                                        WHERE
                                            pomitra.invoice = '$invoice'
                                                AND pomitra.jumlah > 0
                                ");
    while ($data_berat = $query_berat->fetch_assoc()) {
        $total_berat += $data_berat['berat'] * $data_berat['jumlah'];
    }

    $sql_ds = $koneksi->query("SELECT * FROM podropship WHERE invoice = '$invoice'");
    $data_ds = $sql_ds->fetch_assoc();
    
    // Jika ini adalah permintaan AJAX
    if (isset($_POST['type'])) {
        // Ambil tipe permintaan (apakah kota atau kecamatan)
        $type = $_POST['type'];

        if ($type == 'get_kota') {
            // Ambil province_id dari AJAX
            $province_id = $_POST['province_id'];

            // Query untuk mengambil data kota/kabupaten berdasarkan province_id
            $sql_kokab = $koneksi->query("SELECT * FROM tb_ro_cities WHERE province_id = '$province_id' ORDER BY city_name");

            echo '<option disabled="disabled" selected>~ Pilih Kota / Kabupaten ~</option>';
            while ($kabupaten = $sql_kokab->fetch_assoc()) {
                echo '<option value="' . $kabupaten['city_id'] . '">' . $kabupaten['city_name'] . '</option>';
            }
        } elseif ($type == 'get_kecamatan') {
            // Ambil city_id dari AJAX
            $city_id = $_POST['city_id'];

            // Query untuk mengambil data kecamatan berdasarkan city_id
            $sql_kec = $koneksi->query("SELECT * FROM tb_ro_subdistricts WHERE city_id = '$city_id' ORDER BY subdistrict_name");

            echo '<option disabled="disabled" selected>~ Pilih Kecamatan ~</option>';
            while ($kecamatan = $sql_kec->fetch_assoc()) {
                echo '<option value="' . $kecamatan['subdistrict_id'] . '">' . $kecamatan['subdistrict_name'] . '</option>';
            }
        }
        exit; // Stop further script execution for AJAX
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Wanoja | Form Alamat Konin 2025</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <style>
            .form-group {
                margin-bottom: .7rem;
            }
        </style>
    </head>
    <body>
        <nav class="navbar bg-body-tertiary">
            <div class="container d-flex align-items-center">
                <a href="datapokonin.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>" class="text-muted fw-semibold"><i class="bi bi-chevron-left"></i></a>
                <p class="navbar-brand text-uppercase fw-semibold mb-0" href="#">Pre Order</p>
                <i class="opacity-0 bi bi-chevron-right"></i>
            </div>
        </nav>

        <div class="container mt-2 mb-5 pb-5">
            <div class="col-sm-12 text-center">
                <h5 class="text-uppercase mb-0 fw-normal">Form Dropship</h5>
                <h3>#<?= $invoice ?></h3>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <form method="post" class="row" enctype="multipart/form-data">
                        <div class="col-md-12 d-none">
                            <div class="form-group">
                                <input type="text" class="pe-none form-control form-control-sm bg-secondary bg-opacity-25" value="<?= $data_ds['iddropship'] ?>" name="iddropship" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pengirim" class="form-label mb-1 text-muted">Nama Pengirim</label>
                                <input type="text" class="form-control form-control-sm" id="pengirim" value="<?= $data_ds['namapengirim'] ?>" name="pengirim" placeholder="Masukan nama pengirim..." required>
                            </div>
                            <div class="form-group">
                                <label for="telp-pengirim" class="form-label mb-1 text-muted">Telepon Pengirim</label>
                                <input type="number" class="form-control form-control-sm" id="telp-pengirim" value="<?= $data_ds['tlppengirim'] ?>" name="telp_pengirim" placeholder="0000 0000 0000" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="penerima" class="form-label mb-1 text-muted">Nama Penerima</label>
                                <input type="text" class="form-control form-control-sm" id="penerima" value="<?= $data_ds['namapenerima'] ?>" name="penerima" placeholder="Masukan nama penerima..." required>
                            </div>
                            <div class="form-group">
                                <label for="telp-penerima" class="form-label mb-1 text-muted">Telepon Penerima</label>
                                <input type="number" class="form-control form-control-sm" id="telp-penerima" value="<?= $data_ds['tlppenerima'] ?>" name="telp_penerima" placeholder="0000 0000 0000" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="jenis-pengiriman" class="form-label mb-1 text-muted">Jenis Pengiriman</label>
                                <select class="form-select form-select-sm" id="jenis-pengiriman" name="jenis" required>
                                    <option value="Dropship">Dropship</option>
                                    <option value="Alamat Pribadi">Alamat Pribadi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="alamat" class="form-label mb-1 text-muted">Alamat</label>
                                <textarea class="form-control form-control-sm" id="alamat" name="alamat" rows="4" placeholder="Masukan alamat pengiriman..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="provinsi" class="form-label mb-1 text-muted">Provinsi Tujuan</label>
                                <select class="form-select form-select-sm" id="provinsi" name="provinsi">
                                    <option disabled="disabled" selected>~ Pilih Provinsi ~</option>
                                        <?php
                                            $sql_prov = $koneksi->query("SELECT * FROM tb_ro_provinces ORDER BY province_name");
                                            while ($provinsi = $sql_prov->fetch_assoc()) {
                                        ?>
                                            <option value="<?= $provinsi['province_id']; ?>"><?= $provinsi['province_name']; ?></option>
                                        <?php } ?>
                                </select>
                            </div>
                        </div>
                        <!-- Kota / Kabupaten -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kabupaten" class="form-label mb-1 text-muted">Kota / Kabupaten Tujuan</label>
                                <select class="form-select form-select-sm" id="kabupaten" name="kabupaten" disabled>
                                    <option disabled="disabled" selected>~ Pilih Kota / Kabupaten ~</option>
                                </select>
                            </div>
                        </div>

                        <!-- Kecamatan -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kecamatan" class="form-label mb-1 text-muted">Kecamatan Tujuan</label>
                                <select class="form-select form-select-sm" id="kecamatan" name="kecamatan" disabled>
                                    <option disabled="disabled" selected>~ Pilih Kecamatan ~</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="berat" class="form-label mb-1 text-muted">Berat</label>
                                <input type="number" class="pe-none form-control form-control-sm bg-secondary bg-opacity-25" id="berat" value="<?= $total_berat ?>" name="berat" placeholder="Total Berat Barang..." readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kurir" class="form-label mb-1 text-muted">Kurir</label>
                                <select class="form-select form-select-sm" id="kurir" name="kurir">
                                    <option disabled="disabled" selected>~ Pilih Kurir Pengiriman ~</option>
                                    <option value="OR|jne">JNE</option>
                                    <option value="OR|jnt">J&T</option>
                                    <option value="OR|wahana">Wahana</option>
                                    <option value="OR|sicepat">Sicepat</option>
                                    <option value="OR|pos">Pos</option>
                                    <optgroup label="LAINNYA">
                                        <option value="OM|JTR">JNE Trucking (JTR)</option>
                                        <option value="OM|J&T Cargo">J&T Cargo</option>
                                        <option value="OM|Paxel">Paxel</option>
                                        <option value="OM|SPX Express">SPX Express</option>
                                        <option value="OM|IDE">ID Express</option>
                                        <option value="OM|Ahsan">Ahsan</option>
                                        <option value="OM|Baraka">Baraka</option>
                                        <option value="OM|Pegasus">Pegasus</option>
                                        <option value="OM|Sentral">Sentral</option>
                                        <option value="OM|Lion Parcel">Lion Parcel</option>
                                        <option value="OM|Dakota">Dakota</option>
                                        <option value="OM|Indah Cargo">IndahCargo</option>
                                        <option value="OM|Adam Cargo">Adam Cargo</option>
                                        <option value="OM|Gosend">GoSend</option>
                                        <option value="OM|Kalog">Kalog</option>
                                        <option value="OM|CMC KARGO">CMC CARGO</option>
                                        <option value="OM|Tiki">Tiki</option>
                                        <option value="OM|Triplogic">Triplogic</option>
                                        <option value="OM|Anteraja">Anteraja</option>
                                        <option value="OM|Ambil ke Pusat">Ambil Ke Pusat</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="ongkir">
                                <label for="layanan" class="form-label mb-1 text-muted">Layanan</label><br>
                                <select class="form-select form-select-sm" name="layanan" id="layanan">
                                    <option value='layanan'>~ Layanan Kurir ~</option>           
                                </select>
                                <p class="text-danger mb-0" style="font-size: .875rem">
                                    * Jika Memilih Kurir dengan Kategori "Lainnya (Ongkir Manual)" lanjut pilih "Kirim" jika opsi layanan masih kosong.
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-1 ms-auto">
                            <button type="submit" class="btn btn-sm btn-primary w-100" name="kirim">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

        <script>
            $(document).ready(function(){
                // Ketika Provinsi dipilih
                $('#provinsi').change(function(){
                    var province_id = $(this).val(); // Ambil value dari provinsi yang dipilih
                    
                    // AJAX request untuk mendapatkan data kota/kabupaten berdasarkan province_id
                    $.ajax({
                        url: '', // File PHP ini sendiri
                        type: 'post',
                        data: {type: 'get_kota', province_id: province_id},
                        success: function(response){
                            $('#kabupaten').html(response); // Isi dropdown kota/kabupaten
                            $('#kabupaten').prop('disabled', false); // Aktifkan dropdown kota/kabupaten
                            $('#kecamatan').html('<option disabled="disabled" selected>~ Pilih Kecamatan ~</option>'); // Reset dropdown kecamatan
                            $('#kecamatan').prop('disabled', true); // Nonaktifkan dropdown kecamatan
                        }
                    });
                });

                // Ketika Kota/Kabupaten dipilih
                $('#kabupaten').change(function(){
                    var city_id = $(this).val(); // Ambil value dari kota/kabupaten yang dipilih

                    // AJAX request untuk mendapatkan data kecamatan berdasarkan city_id
                    $.ajax({
                        url: '', // File PHP ini sendiri
                        type: 'post',
                        data: {type: 'get_kecamatan', city_id: city_id},
                        success: function(response){
                            $('#kecamatan').html(response); // Isi dropdown kecamatan
                            $('#kecamatan').prop('disabled', false); // Aktifkan dropdown kecamatan
                        }
                    });
                });

                $("#kurir").change(function(){
                    //Mengambil value dari option select provinsi asal, kabupaten, kurir, berat kemudian parameternya dikirim menggunakan ajax
                    var asal = $('#asal').val();
                    var kab = $('#kabupaten').val();
                    var kec = $('#kecamatan').val();
                    var kurir = $('#kurir').val();
                    var berat = $('#berat').val();

                    console.log(berat);

                    $.ajax({
                        type : 'POST',
                        url : 'cek_ongkir.php',
                        data :  {'kab_id' : kab, 'kec_id' : kec, 'kurir' : kurir, 'asal' : asal, 'berat' : berat},
                        success: function (data) {
                            //jika data berhasil didapatkan, tampilkan ke dalam element div ongkir
                            $("#layanan").html(data);
                        }
                    });
                });
            });
        </script>

        <?php
            if (isset($_POST['kirim'])) {
                try {
                    // Set timezone PHP ke Indonesia (WIB)
                    date_default_timezone_set('Asia/Jakarta');

                    // Dapatkan waktu sekarang
                    $current_time       = date('Y-m-d H:i:s');
                    $iddropship         = $_POST['iddropship'];
                    $pengirim           = addslashes(htmlspecialchars($_POST['pengirim']));
                    $telp_pengirim      = $_POST['telp_pengirim'];
                    $penerima           = addslashes(htmlspecialchars($_POST['penerima']));
                    $telp_penerima      = $_POST['telp_penerima'];
                    $alamat             = addslashes(htmlspecialchars($_POST['alamat']));
                    $jenis              = $_POST['jenis'];
                    $provinsi           = $_POST['provinsi'];
                    $kota               = $_POST['kabupaten'];
                    $kecamatan          = $_POST['kecamatan'];
                    $dkur               = explode("|", $_POST['kurir']);
                    $ekspedisi          = $dkur[1];
                    $dlay               = explode("|", $_POST['layanan']);
                    $layanan            = $dlay[0];
                    $berat              = $_POST['berat'];

                    $layanan = $layanan === "" ? NULL : $layanan;

                    if ($layanan == "Layanan Ongkir Manual") {
                        $ongkir = 0;
                    } else {
                        $ongkir = $dlay[1];
                    }

                    if ($jenis == 'Dropship') {
                        if ($berat < 6000 && $berat >= 0) {
                            $biayadropship = 3000;
                        } elseif ($berat < 110000 && $berat >= 6000 ) {
                            $biayadropship = 5000;
                        } elseif($berat < 21000 && $berat >= 11000) {
                            $biayadropship = 10000;
                        } elseif($berat < 31000 && $berat >= 21000) {
                            $biayadropship = 15000;
                        } elseif($berat < 41000 && $berat >= 31000) {
                            $biayadropship = 20000;
                        } elseif($berat < 51000 && $berat >= 41000) {
                            $biayadropship = 25000;
                        } elseif($berat < 61000 && $berat >= 51000) {
                            $biayadropship = 30000;
                        } elseif($berat < 71000 && $berat >= 61000) {
                            $biayadropship = 35000;
                        } elseif($berat < 81000 && $berat >= 71000) {
                            $biayadropship = 40000;
                        } elseif($berat < 91000 && $berat >= 81000) {
                            $biayadropship = 45000;
                        } elseif($berat < 101000 && $berat >= 91000) {
                            $biayadropship = 50000;
                        } elseif($berat < 111000 && $berat >= 101000) {
                            $biayadropship = 55000;
                        } elseif($berat < 121000 && $berat >= 111000) {
                            $biayadropship = 60000;
                        } elseif($berat < 131000 && $berat >= 121000) {
                            $biayadropship = 65000;
                        } elseif($berat < 141000 && $berat >= 131000) {
                            $biayadropship = 70000;
                        } elseif($berat < 151000 && $berat >= 141000) {
                            $biayadropship = 75000;
                        } elseif($berat < 161000 && $berat >= 151000) {
                            $biayadropship = 80000;
                        } elseif($berat < 171000 && $berat >= 161000) {
                            $biayadropship = 85000;
                        } elseif($berat < 181000 && $berat >= 171000) {
                            $biayadropship = 90000;
                        } elseif($berat < 191000 && $berat >= 181000) {
                            $biayadropship = 95000;
                        } elseif($berat >= 191000) {
                            $biayadropship = 100000;
                        }
                    } elseif ($jenis == 'Alamat Pribadi') {
                        $biayadropship = 0;
                    } else {
                        echo "
                            <script>
                                alert('Terjadi kesalahan pada saat hitung berat barang!')
                                location='formdropship.php?id=$idpoproduk&invoice=$invoice'
                            </script>
                        ";
                    }

                    if (empty($ongkir) || !is_numeric($ongkir)) {
                        $ongkir = 0;
                    } else {
                        $ongkir = (int)$ongkir; // pastikan $ongkir berupa angka
                    }

                    $sql_upd = $koneksi->query("UPDATE podropship 
                                                    SET 
                                                        namapengirim = '$pengirim',
                                                        tlppengirim = '$telp_pengirim',
                                                        namapenerima = '$penerima',
                                                        tlppenerima = '$telp_penerima',
                                                        alamatpenerima = '$alamat',
                                                        provinsi = '$provinsi',
                                                        kota = '$kota',
                                                        kecamatan = '$kecamatan',
                                                        ekspedisi = '$ekspedisi',
                                                        layanan = IF('$layanan' = '', NULL, '$layanan'),
                                                        ongkir = $ongkir,
                                                        dropship = '$biayadropship'
                                                    WHERE
                                                        iddropship = '$iddropship'
                                                ");
                    if ($sql_upd) {
                        $updongkir = $koneksi->query("UPDATE
                                                            ongkir
                                                        SET
                                                            nominal = $ongkir,
                                                            updated_at = '$current_time'
                                                        WHERE 
                                                            invoice = '$invoice'
                                                    ");

                        echo "
                            <script>
                                alert('Data berhasil diubah!')
                                location='datapokonin.php?id=$idpoproduk&invoice=$invoice'
                            </script>
                        ";
                    } else {
                        echo "
                            <script>
                                alert('Terjadi kesalahan pada saat mengubah data!')
                                location='formdropship.php?id=$idpoproduk&invoice=$invoice'
                            </script>
                        ";
                    }
                } catch (Exception $e) {
                    echo "Error: ". $e->getMessage();
                }
            }
        ?>
    </body>
</html>