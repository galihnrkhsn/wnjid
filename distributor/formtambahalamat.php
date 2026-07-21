<?php 
    session_start();
    include 'koneksi.php';
    if(!isset($_SESSION["admin_mitra"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $idpoproduk = $_GET["id"];
    $invoice = $_GET["invoice"];

    $query_po = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = $idpoproduk");
    $sql_po = $query_po->fetch_assoc();

    $ambil_pengiriman = $koneksi->query("SELECT podetail.berat, pomitra.jumlah 
                                            FROM pomitra
                                            JOIN podetail on podetail.idpodetail = pomitra.idpodetail
                                            WHERE pomitra.invoice = '$invoice'
                                        ");
    while($row_pengiriman=$ambil_pengiriman->fetch_assoc()){
        $berat = $row_pengiriman['berat'];
        $jumlah = $row_pengiriman['jumlah'];
        $total_berat += $berat*$jumlah;
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>WNJ | <?= $_SESSION['admin_mitra']['namamitra'] ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <style>
            /* Place the navbar at the bottom of the page, and make it stick */
            .navbaru {
                background-color: #eee;
                text-align: center;
                overflow: hidden;
                font-size: 20px;
                font-weight: 600;
            }

            .navbaru p {
                color: #0f0f0a;
                text-align: center;
            }

            .navbaru i {
                color: #0f0f0a;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="row fixed-top navbaru py-2">
            <div class="col-2"><a href="datapovoal.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>"><i class="bi bi-chevron-left"></i></a></div>
            <div class="col-8" ><p class="mb-0">PRE ORDER</p></div>
            <div class="col-2"></div>
        </div>

        <div class="container my-5 pt-3">
            <div class="text-center">
                <h4><?= $sql_po['namapo'] ?></h4>
                <p>#<span class="bg-success-subtle px-1 mx-1 fw-medium"><?= $invoice ?></span></p>
            </div>

            <div class="card py-4 px-3">
                <form method="post" class="row col-lg-5">
                    <div class="form-group mb-1">
                        <label for="pengirim">Nama Pengirim<span class="text-danger"> *</span></label>
                        <input class="form-control form-control-sm" id="pengirim" type="text" name="pengirim" required>
                    </div>
                    <div class="form-group mb-1">
                        <label for="telp-pengirim">Telepon Pengirim<span class="text-danger"> *</span></label>
                        <input class="form-control form-control-sm" id="telp-pengirim" type="text" name="telppengirim" required>
                    </div>
                    <div class="form-group mb-1">
                        <label for="penerima">Nama Penerima<span class="text-danger"> *</span></label>
                        <input class="form-control form-control-sm" id="penerima" type="text" name="penerima" required>
                    </div>
                    <div class="form-group mb-1">
                        <label for="telp-penerima">Telepon Penerima<span class="text-danger"> *</span></label>
                        <input class="form-control form-control-sm" id="telp-penerima" type="text" name="telppenerima" required>
                    </div>
                    <div class="form-group mb-1">
                        <label for="alamat">Alamat Penerima<span class="text-danger"> *</span></label>
                        <textarea class="form-control form-control-sm" id="alamat" type="text" name="alamat" required></textarea>
                    </div>
                    <div class="form-group mb-1">
                        <label for="prov">Provinsi Tujuan<span class="text-danger"> *</span></label>
                        <select class="form-select form-select-sm" id="prov" name="prov" required>
                            <option disabled='disabled' selected>~Pilih Provinsi Tujuan~</option>
                            <?php
                                $ambil = $koneksi->query("SELECT * FROM tb_ro_provinces");
                                while($row = $ambil->fetch_assoc()){
                            ?>
                                <option value="<?= $row['province_id']; ?>|<?= $row['province_name']; ?>"><?= $row['province_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group mb-1">
                        <label for="kabupaten">Kota/Kabupaten Tujuan<span class="text-danger"> *</span></label>
                        <select class="form-select form-select-sm" id="kabupaten" name="kabupaten" required></select>
                    </div>
                    <div class="form-group mb-1">
                        <label for="kecamatan">Kecamatan Tujuan<span class="text-danger"> *</span></label>
                        <select class="form-select form-select-sm" id="kecamatan" name="kecamatan" required></select>
                    </div>
                    <div class="form-group mb-1">
                        <label for="berat">Berat (gram)</label>
                        <input class="form-control-plaintext form-control-sm fw-medium bg-secondary-subtle px-2" id="berat" type="number" name="berat" value="<?= $total_berat; ?>" readonly>
                    </div>
                    <div class="form-group mb-1">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control form-control-sm" id="keterangan" name="keterangan"></textarea>
                    </div>
                    <div class="form-group mb-1">
                        <label for="kurir">Kurir</label><br>
                        <select class="form-select form-select-sm" id="kurir" name="kurir" required>
                            <option disabled='disabled' value="" selected>~Pilih Kurir Pengiriman~</option>
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
                            </optgroup>
                        </select>

                        <div class="form-group mb-1" id="ongkir">
                            <label for="layanan">Layanan</label><br>
                            <select class="form-select form-select-sm" name="layanan" id="layanan" >
                                <option value="layanan">~ Kosong ~</option>           
                            </select>
                            <label>
                                <p class="text-secondary">*Jika Memilih Kurir dengan Kategori "Lainnya (Ongkir Manual)" lanjut pilih "Kirim" jika opsi layanan masih kosong</p>
                            </label>
                        </div>

                        <button class="btn btn-sm btn-primary" name="kirim">Kirim</button>
                    </div>
                </form>
            </div>
        </div>

        <?php
            if (isset($_POST['kirim'])) {
                $idadmin = $_SESSION['admin_mitra']['idadmin'];
                $namapengirim = addslashes(htmlspecialchars($_POST['pengirim']));
                $telppengirim = $_POST['telppengirim'];
                $namapenerima = addslashes(htmlspecialchars($_POST['penerima']));
                $telppenerima = $_POST['telppenerima'];
                $alamat = addslashes(htmlspecialchars($_POST['alamat']));
                $provinsi = $_POST['prov'];
                $kab = $_POST['kabupaten'];
                $kec = $_POST['kecamatan'];
                $berat = $_POST['berat'];
                $ket = addslashes(htmlspecialchars($_POST['keterangan']));
                $ekspedisinya = $_POST['kurir'];
                $layanan = $_POST['layanan'];

                $result_explode = explode('|', $provinsi);
                $prov = $result_explode[0];
            
                $result_explode = explode('|', $kab);
                $kabupaten = $result_explode[0];
            
                $result_explode = explode('|', $kec);
                $kecamatan = $result_explode[0];

                $result_explode = explode('|', $layanan);
                $layananku = $result_explode[0];
                $ongkir2 = $result_explode[1];
                $ongkir = (int)"$ongkir2";

                $result_explode = explode('|', $ekspedisinya);
                $om = $result_explode[0];
                $ekspedisi = $result_explode[1];

                // echo "Nama Pengirim: " . $namapengirim . " <br />";
                // echo "Telp Pengirim: " . $telppengirim . " <br />";
                // echo "Nama Penerima: " . $namapenerima . " <br />";
                // echo "Telp Penerima: " . $telppenerima . " <br />";
                // echo "Alamat: " . $alamat . " <br />";
                // echo "Provinsi: " . $prov . " <br />";
                // echo "Kabupaten: " . $kabupaten . " <br />";
                // echo "Kecamatan: " . $kecamatan . " <br />";
                // echo "Ekspedisi: " . $ekspedisi . " <br />";
                // echo "Kurir: " . $layananku . " <br />";
                // echo "Ongkir: " . $ongkir . " <br />";

                $query = $koneksi->query("INSERT INTO podropship 
                                            (iddropship, idadmin, idpoproduk,
                                            invoice, namapengirim, tlppengirim,
                                            namapenerima, tlppenerima, alamatpenerima,
                                            provinsi, kota, kecamatan,
                                            keterangan, ekspedisi, layanan,
                                            ongkir) VALUES
                                            (NULL, '$idadmin', '$idpoproduk',
                                            '$invoice', '$namapengirim', '$telppengirim',
                                            '$namapenerima', '$telppenerima', '$alamat',
                                            '$prov', '$kabupaten', '$kecamatan',
                                            '$ket', '$ekspedisi', '$layananku', '$ongkir');
                                        ");
                
                if ($query) {
                    echo "<script>alert('Alamat sudah ditambahkan!');</script>";
                    echo "<script>location='datapovoal.php?id=$idpoproduk&invoice=$invoice';</script>";
                } else {
                    echo "<script>alert('Alamat gagal ditambahkan!');</script>";
                    echo "<script>location='formtambahalamat.php?id=$idpoproduk&invoice=$invoice';</script>";
                }
            }
        ?>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#prov').change(function(){

                //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                var provinsi = $('#prov').val();

                    $.ajax({
                        type : 'GET',
                        url : 'cek_kabupaten2.php',
                        data :  'prov_id=' + provinsi,
                        success: function (data) {

                        //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                        $("#kabupaten").html(data);
                        }
                    });
                });
                
            
                $('#kabupaten').change(function(){

                //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                var kabupaten = $('#kabupaten').val();

                    $.ajax({
                        type : 'GET',
                        url : 'cek_kecamatan2.php',
                        data :  'kabupaten_id=' + kabupaten,
                        success: function (data) {

                        //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                        $("#kecamatan").html(data);
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

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>