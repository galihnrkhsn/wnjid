<?php
    session_start();
    error_reporting (0);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesAgen.php';
    include "settingdatatables.php";

    $idpoproduk     = $_GET['idpo'];
    $invoice        = $_GET['invoice'];
    $idmitraagen    = $_SESSION["idmitraagen"];

    $queryAgen        = $koneksi->query("SELECT * FROM mitraagen WHERE idmitraagen = '$idmitraagen'");
    $dataAgen         = $queryAgen->fetch_assoc();
    $idadmin          = $dataAgen['idadmin']; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Agen | Wanoja</title>
</head>

<body>
    <!-- NAVBAR -->
    <? include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-3 mb-5">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <form method="post">
                            <div class="form-group">
                                <label class="form-label mb-0 text-muted">Nama Pengirim</label>
                                <input type="text" class="form-control" name="namapengirim" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label mb-0 text-muted">Telepon Pengirim</label>
                                <input type="number" class="form-control" name="tlppengirim" required>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="form-label mb-0 text-muted">Nama Penerima</label>
                                <input type="text" class="form-control" name="namapenerima" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label mb-0 text-muted">Telepon Penerima</label>
                                <input type="number" class="form-control" name="tlppenerima" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label mb-0 text-muted">Alamat Penerima</label>
                                <textarea class="form-control" name="alamatpenerima" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="prov" class="form-label mb-0 text-muted">Provinsi Tujuan</label>
                                <select class="form-control" id="prov" name="prov" required>
                                    <option disabled='disabled' selected>~Pilih Provinsi Tujuan~</option>
                                    <?php
                                        include "koneksi.php";
                                        $ambil=$koneksi->query("SELECT * FROM tb_ro_provinces");
                                        while($row=$ambil->fetch_assoc()){
                                    ?>
                                        <option value="<?php echo $row['province_id']; ?>|<?php echo $row['province_name']; ?>"><?php echo $row['province_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="kabupaten" class="form-label mb-0 text-muted">Kota/Kabupaten Tujuan</label>
                                <select class="form-control" id="kabupaten" name="kabupaten" required></select>
                            </div>
                            <div class="form-group">
                                <label for="kecamatan" class="form-label mb-0 text-muted">Kecamatan Tujuan</label>
                                <select class="form-control" id="kecamatan" name="kecamatan" required></select>
                            </div>
                            <div class="form-group">
                                <label class="form-label mb-0 text-muted">Keterangan</label>
                                <textarea class="form-control" name="keterangan"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label mb-0 text-muted">Ekspedisi</label>
                                <select id="ekspedisi" class="form-control form-control-sm" name="ekspedisi" required>
                                    <?php if (isset($_GET['id'])) : ?>
                                        <option value="<?= $ekspedisi ?>" selected><?= $ekspedisi ?></option>
                                    <?php else : ?>
                                        <option selected>~ Default Selected ~</option>
                                    <?php endif; ?>
                                    <optgroup label="JNE">
                                        <option value="JNE Reg">JNE Reg</option>
                                        <option value="JNE YES">JNE YES</option>
                                        <option value="JNE Oke">JNE Oke</option>
                                        <option value="JNE CTC">JNE CTC</option>
                                        <option value="JTR">JNE Trucking (JTR)</option>
                                    <optgroup label="J&T">
                                        <option value="J&T">J&T</option>
                                        <option value="J&T Cargo">J&T Cargo</option>
                                    <optgroup label="WAHANA">
                                        <option value="Wahana Ekspres">Wahana Ekspres</option>
                                        <option value="Wahana Kargo">Wahana Kargo</option>
                                    <optgroup label="SICEPAT">
                                        <option value="Sicepat BEST">Sicepat BEST</option>
                                        <option value="Sicepat REG">Sicepat Reg</option>
                                        <option value="Sicepat Kargo">Sicepat Cargo</option>
                                    <optgroup label="POS">
                                        <option value="Pos Ekonomi Jumbo">Pos Ekonomi Jumbo</option>
                                        <option value="Pos Kilat">Pos Kilat</option>
                                    <optgroup label="LAINNYA">
                                        <option value="Paxel">Paxel</option>
                                        <option value="SPX Express">SPX Express</option>
                                        <option value="IDE">ID Express</option>
                                        <option value="Ahsan">Ahsan</option>
                                        <option value="Baraka">Baraka</option>
                                        <option value="Pegasus">Pegasus</option>
                                        <option value="Sentral">Sentral</option>
                                        <option value="Lion Parcel">Lion Parcel</option>
                                        <option value="Dakota">Dakota</option>
                                        <option value="Indah Cargo">IndahCargo</option>
                                        <option value="Adam Cargo">Adam Cargo</option>
                                        <option value="Gosend">GoSend</option>
                                        <option value="Kalog">Kalog</option>
                                        <option value="CMC KARGO">CMC CARGO</option>
                                        <option value="Tiki">Tiki</option>
                                        <option value="Triplogic">Triplogic</option>
                                        <option value="Anteraja">Anteraja</option>
                                        <option value="Ambil ke Pusat">Ambil Ke Pusat</option>
                                </select>
                            </div>

                            <div class="table-responsive">
                                <?php if ($idpoproduk == 186 || $idpoproduk == 187): ?>
                                    <p><strong><font color="red" size="5px">*</font></strong> Pilih Isi Box</p>
                                <?php endif; ?>
                                <p><strong><font color="red" size="5px">*</font></strong> Jangan Kosongkan Label, Cukup isi dengan Angka 0 jika tidak memilih produk.</p>
                                <table class="table">
                                    <thead>
                                        <tr>
                                        <th><input type='checkbox' id='checkAll'> Check</th>
                                        <th>Nama Produk</th>
                                        <th>Stok Invoice</th>
                                        <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $no = 1;
                                        $total_semua = 0; // Menyimpan total semua harga
                                        $sql = $koneksi->query("SELECT 
                                                                    *
                                                                FROM
                                                                    pomitra
                                                                WHERE
                                                                    pomitra.invoice = '$invoice'
                                                                        AND pomitra.jumlah > 0
                                                                GROUP BY pomitra.custom
                                                            ");
                                        while ($data = $sql->fetch_assoc()) {
                                            $custom = $data['custom'];
                                            $string = $custom;
                                            // Mengubah semua huruf menjadi huruf kecil
                                            $string = strtolower($string);
                                            // Mengganti spasi dengan tanda hubung
                                            $string = str_replace(' ', '-', $string);
                                            // Menghilangkan tanda - di awal string
                                            $string = ltrim($string, '-');
                                            // Menghapus karakter yang tidak diperlukan
                                            $string = preg_replace('/[^a-z0-9\-]/', '', $string);
    
                                            // Reset variabel untuk total per custom
                                            $total_custom = 0;
                                            $pack = 0;

                                            $id             = $data['idpodetail'];
                                            $idpomitra      = $data['idpomitra'];
                                            $data_jumlah    = $koneksi->query("SELECT SUM(pods.jumlah) as progresnya 
                                                                                FROM pods
                                                                                WHERE pods.invoice = '$invoice'
                                                                                AND pods.idpodetail = '$id'
                                                                                AND pods.idpomitra = '$idpomitra'");
                                            $tampilprogres = $data_jumlah->fetch_assoc();                                   
                                            $kurang = $data['jumlah'] - $tampilprogres['progresnya'];
                                        ?>
                                        <tr>
                                            <td>
                                                <?php if($kurang > 0): ?>
                                                <input type='checkbox' name='update[]' value='<?= $idpomitra ?>'>
                                                <input type="hidden" name='idpodetail<?= $idpomitra ?>' value='<?= $id ?>'>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php
                                                    $query = $koneksi->query("SELECT 
                                                                                podetail.*, pomitra.*
                                                                            FROM
                                                                                pomitra
                                                                                    INNER JOIN
                                                                                podetail ON pomitra.idpodetail = podetail.idpodetail
                                                                            WHERE
                                                                                pomitra.custom = '$custom'
                                                                                    AND pomitra.invoice = '$invoice'
                                                                                    AND pomitra.jumlah > 0
                                                                            ");
                                                    $first = true;
                                                    while ($data_produk = $query->fetch_assoc()) {
                                                        if (!$first) {
                                                            echo " | ";
                                                        }
                                                        $first = false;

                                                        // Tambahkan data produk
                                                        echo $data_produk['variant'];

                                                        // Hitung jumlah dan harga per item
                                                        $pack = $data_produk['jumlah'];
                                                        $total_custom += $data_produk['jumlah'] * $data_produk['harga'];
                                                    }
                                                ?></td>
                                            <td><?= $kurang;?></td>
                                            <td><input type='number' class="form-control" min="0" max="<?= $kurang;?>" name='jumlah<?= $idpomitra ?>' value='0' required></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <center><button type="submit" class="btn btn-primary" name="kirim">Kirim</button></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->

    <!-- PHP SYNTAK -->
    <?php
        if(isset($_POST['kirim'])){
            $namapengirim       = addslashes(htmlspecialchars($_POST["namapengirim"]));
            $tlppengirim        = addslashes(htmlspecialchars($_POST["tlppengirim"]));
            $namapenerima       = addslashes(htmlspecialchars($_POST["namapenerima"]));
            $tlppenerima        = addslashes(htmlspecialchars($_POST["tlppenerima"]));
            $alamatpenerima     = addslashes(htmlspecialchars($_POST["alamatpenerima"]));
            $keterangan         = addslashes(htmlspecialchars($_POST["keterangan"] ?? ''));
            $dari               = addslashes(htmlspecialchars($_POST["dari"] ?? ''));
            $kepada             = addslashes(htmlspecialchars($_POST["kepada"] ?? ''));
            $ekspedisi          = $_POST["ekspedisi"];

            $provinsi_id        = $_POST["prov"];
            $result_explode     = explode('|', $provinsi_id);
            $provinsi           = $result_explode[0];
        
            $kabupaten_id       = $_POST["kabupaten"];
            $result_explode     = explode('|', $kabupaten_id);
            $kabupaten          = $result_explode[0];
        
            $kecamatan_id       = $_POST["kecamatan"];
            $result_explode     = explode('|', $kecamatan_id);
            $kecamatan          = $result_explode[0]; 
            $keterangannya = $keterangan;

            if ($idpoproduk == 186 || $idpoproduk == 187) {
                $keterangannya = $dari . '|' . $kepada . '|' . $keterangan;
            }
            
            $sql_ds = mysqli_query($koneksi, "SELECT iddropship FROM podropship ORDER BY iddropship DESC LIMIT 1");
            if (!$sql_ds) {
                die('Error: ' . mysqli_error($koneksi));
            }
            date_default_timezone_set('Asia/Jakarta');
            $data = mysqli_fetch_array($sql_ds);
            $no = $data['iddropship'];
            $ab = 1;
            $nobaru = $no + $ab;  
            $no_ds = $invoice . '-' . $nobaru;
            $today = date("Y-m-d H:i:s");
            
            if(isset($_POST['update'])){
                $totaljum = 3;
                if ($idpoproduk == 186 || $idpoproduk == 187) {
                    foreach($_POST['update'] as $updateid){
                        $jumlah = $_POST['jumlah'.$updateid];
                        $totaljum += $jumlah;
                    }              
                }
                foreach($_POST['update'] as $updateid){
                    $jumlah = $_POST['jumlah'.$updateid];
                    $idpodetail = $_POST['idpodetail'.$updateid];
                    if($jumlah != 0){
                        $pods_query = "INSERT INTO pods (id, no_ds, invoice, idpodetail, idpomitra, jumlah, waktu)
                        VALUES (NULL, '$no_ds', '$invoice', '$idpodetail', '$updateid', '$jumlah', '$today')";
                        if (!$koneksi->query($pods_query)) {
                            die('Error Insert pods: ' . mysqli_error($koneksi));
                        }
                    }
                }
            }

            $podropship_query = "INSERT INTO podropship (iddropship, idadmin, idmitraagen, idmitrareseller, idmitramarketer, idpoproduk, invoice, no_ds, namapengirim, tlppengirim, namapenerima, tlppenerima, alamatpenerima, provinsi, kota, kecamatan, keterangan, ekspedisi)
            VALUES (NULL, '$idadmin', '$idmitraagen', '0', '0', '$idpoproduk', '$invoice', '$no_ds', '$namapengirim', '$tlppengirim', '$namapenerima', '$tlppenerima', '$alamatpenerima', '$provinsi', '$kabupaten', '$kecamatan', '$keterangannya', '$ekspedisi')";

            if (!$koneksi->query($podropship_query)) {
                die('Error: ' . mysqli_error(mysql: $koneksi));
            } else {
                echo "<script>alert('data berhasil ditambah');</script>";
                echo "<script>location='listds?id=$idpoproduk&invoice=$invoice'</script>";
            }
        }
    ?>
    <!-- PHP SYNTAK END -->
    
    <!-- SCRIPT -->
    <script type="text/javascript">
        $(document).ready(function(){
            $('#prov').change(function(){
                //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                var provinsi = $('#prov').val();
                $.ajax({
                    type : 'GET',
                    url : 'cek_kabupaten_dropship.php',
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
                    url : 'cek_kecamatan_dropship.php',
                    data :  'kabupaten_id=' + kabupaten,
                    success: function (data) {
                        //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                        $("#kecamatan").html(data);
                    }
                });
            });
        });  
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
    <!-- END SCRIPT -->
</body>
</html>