<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesMarketer.php';
    include "settingdatatables.php";

    $idpoproduk         = $_GET['idpo'];
    $invoice            = $_GET['invoice'];
    $idmitramarketer    = $_SESSION["idmitramarketer"];
    $findUser           = $koneksi->query("SELECT * FROM mitramarketer WHERE idmitramarketer='$idmitramarketer'");
    $queryUser          = $findUser->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketer | Wanoja</title>
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
        <div class="panel panel-default">
            <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                <div style="padding: 0 15px;">
                    <form method="post">
                        <div class="form-group">
                            <label>Nama Pengirim</label>
                            <input type="text" class="form-control" name="namapengirim" value="<?= $queryUser['namaagen'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Telepon Pengirim</label>
                            <input type="number" class="form-control" name="tlppengirim" value="<?= $queryUser['whatsapp'] ?>" required>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Nama Penerima</label>
                            <input type="text" class="form-control" name="namapenerima" required>
                        </div>
                        <div class="form-group">
                            <label>Telepon Penerima</label>
                            <input type="number" class="form-control" name="tlppenerima" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat Penerima</label>
                            <textarea class="form-control" name="alamatpenerima" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="prov">Provinsi Tujuan</label>
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
                            <label for="kabupaten">Kota/Kabupaten Tujuan</label>
                            <select class="form-control" id="kabupaten" name="kabupaten" required></select>
                        </div>
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan Tujuan</label>
                            <select class="form-control" id="kecamatan" name="kecamatan" required></select>
                        </div>
                        <?php if ($idpoproduk == 186 || $idpoproduk == 187): ?>
                            <!-- <div class="form-group">
                            <label>Dari</label>
                            <input type="text" class="form-control" name="dari">
                            </div>
                            <div class="form-group">
                            <label>Kepada</label>
                            <input type="text" class="form-control" name="kepada">
                            </div>
                            <div class="form-group">
                            <label>Ucapan</label>
                            <textarea class="form-control" name="keterangan" maxlength="200"></textarea>
                            <p><strong><font color="red" size="5px">*</font></strong> Max 200 Karakter</p>
                            </div> -->
                        <?php else: ?>
                            <div class="form-group">
                            <label>Keterangan</label>
                            <textarea class="form-control" name="keterangan"></textarea>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label>Ekspedisi</label>
                            <select class="form-control" name="ekspedisi">
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
                                    $ambil=$koneksi->query("SELECT podetail.variant, pomitra.jumlah , podetail.idpodetail, pomitra.custom, pomitra.idpomitra
                                                                FROM pomitra
                                                                JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                WHERE pomitra.invoice= '$invoice' 
                                                                AND pomitra.jumlah>0
                                                                ORDER BY pomitra.idpomitra ASC");
                                    while($data=$ambil->fetch_assoc()){
                                        $id = $data['idpodetail'];
                                        $idpomitra = $data['idpomitra'];
                                        if ($id == 8920) {
                                            $data_jumlah = $koneksi->query("SELECT SUM(pods.jumlah) as progresnya 
                                                                            FROM pods
                                                                            JOIN pomitra on pomitra.idpomitra = pods.idpomitra
                                                                            WHERE pods.invoice='$invoice'
                                                                            AND pods.idpodetail = '$id'
                                                                            AND pomitra.idpomitra = '$idpomitra'");
                                            $tampilprogres = $data_jumlah->fetch_assoc();                                   
                                            $kurang = $data['jumlah'] - $tampilprogres['progresnya'];
                                        } else {
                                            $data_jumlah = $koneksi->query("SELECT SUM(pods.jumlah) as progresnya 
                                                                            FROM pods
                                                                            WHERE pods.invoice='$invoice'
                                                                            AND pods.idpodetail = '$id'
                                                                            AND pods.idpomitra = '$idpomitra'");
                                            $tampilprogres = $data_jumlah->fetch_assoc();                                   
                                            $kurang = $data['jumlah'] - $tampilprogres['progresnya'];
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            <?php if($kurang > 0): ?>
                                            <input type='checkbox' name='update[]' value='<?= $idpomitra ?>'>
                                            <input type="hidden" name='idpodetail<?= $idpomitra ?>' value='<?= $id ?>'>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $data['variant'];?> <?= $data['custom'];?></td>
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
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <?php
        if(isset($_POST['kirim'])){
            $namapengirim = addslashes(htmlspecialchars($_POST["namapengirim"]));
            $tlppengirim = addslashes(htmlspecialchars($_POST["tlppengirim"]));
            $namapenerima = addslashes(htmlspecialchars($_POST["namapenerima"]));
            $tlppenerima = addslashes(htmlspecialchars($_POST["tlppenerima"]));
            $alamatpenerima = addslashes(htmlspecialchars($_POST["alamatpenerima"]));
            $dari = addslashes(htmlspecialchars($_POST["dari"] ?? ''));
            $kepada = addslashes(htmlspecialchars($_POST["kepada"] ?? ''));
            $keterangan = addslashes(htmlspecialchars($_POST["keterangan"] ?? ''));
            $ekspedisi = $_POST["ekspedisi"];

            $provinsi_id = $_POST["prov"];
            $result_explode = explode('|', $provinsi_id);
            $provinsi = $result_explode[0];
        
            $kabupaten_id = $_POST["kabupaten"];
            $result_explode = explode('|', $kabupaten_id);
            $kabupaten = $result_explode[0];
        
            $kecamatan_id = $_POST["kecamatan"];
            $result_explode = explode('|', $kecamatan_id);
            $kecamatan = $result_explode[0]; 
            $keterangannya = $keterangan;
            if ($idpoproduk == 186 || $idpoproduk == 187) {
                $keterangannya = $dari . '|' . $kepada . '|' . $keterangan;
            }

            $sql_ds = mysqli_query($koneksi, "SELECT iddropship FROM podropship ORDER BY iddropship DESC LIMIT 1");
            if (!$sql_ds) {
                die('Error: ' . mysqli_error($koneksi));
            }

            $data = mysqli_fetch_array($sql_ds);
            $no = $data['iddropship'];
            $ab = 1;
            $nobaru = $no + $ab;
            $no_ds = $invoice . '-' . $nobaru;
            date_default_timezone_set('Asia/Jakarta');
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
                            die('Error: ' . mysqli_error($koneksi));
                        }
                    }
                }
            }

            $podropship_query = "INSERT INTO podropship (iddropship, idadmin, idmitraagen, idmitrareseller, idmitramarketer, idpoproduk, invoice, no_ds, namapengirim, tlppengirim, namapenerima, tlppenerima, alamatpenerima, provinsi, kota, kecamatan, keterangan, ekspedisi)
            VALUES (NULL, '0', '0', '0', '$idmitramarketer', '$idpoproduk', '$invoice', '$no_ds', '$namapengirim', '$tlppengirim', '$namapenerima', '$tlppenerima', '$alamatpenerima', '$provinsi', '$kabupaten', '$kecamatan', '$keterangannya', '$ekspedisi')";
            if (!$koneksi->query($podropship_query)) {
                die('Error: ' . mysqli_error($koneksi));
            } else {
                echo "<script>alert('data berhasil ditambah');</script>";
                echo "<script>location='listds?id=$idpoproduk&invoice=$invoice'</script>";
            }
        }
    ?>
    <!-- PHP END -->

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

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
    <!-- SCRIPT END -->
</body>
</html>