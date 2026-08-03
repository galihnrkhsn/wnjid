<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesAgen.php';
    
    $idmitraagen = $_SESSION['idmitraagen'];
    $query = "SELECT * FROM mitraagen 
    LEFT JOIN tb_ro_provinces on mitraagen.provinsi = tb_ro_provinces.province_id
    LEFT JOIN tb_ro_cities on mitraagen.kota = tb_ro_cities.city_id
    LEFT JOIN tb_ro_subdistricts on mitraagen.kecamatan = tb_ro_subdistricts.subdistrict_id

    WHERE mitraagen.idmitraagen='$idmitraagen'";
    $sql = mysqli_query($koneksi, $query);  
    $data = mysqli_fetch_array($sql); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <div class="text-center">
            <h2>Data Alamat</h2>
        </div>
        <form method="post">
            <div class="form-group">
                <label>Alamat</label>
                <textarea class="form-control" name="alamat"><?php echo $data['alamat']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Kode POS</label>
                <input type="number" class="form-control" name="kodepos" value="<?php echo $data['kodepos']; ?>" maxlength="6">
            </div>
            <div class="form-group">
                <label>Titik Koordinat</label>  
                <input class="form-control" name="titikkordinat" value="<?php echo $data['titikkordinat']; ?>">
                <label><font size="2" color="silver">1.Buka Aplikasi Google Maps<br>
                2.Cari Lokasi kita, Zoom sampai lokasi terlihat detail<br>
                3.Tekan dan Tahan sampai muncul titik kordinat berada di atas (contoh:-6,121.232.....)<br>
                4.Copy Paste Nomor tersebut, lalu masukan pada form ubah</font>
                </label>
            </div>
            <div class="form-group">
                <label for="prov">Provinsi Tujuan</label><br>
                <select class="form-control" id="prov" name="prov" required>
                <optgroup label="~Pilih Provinsi Tujuan~">
                    <option value="<?= $data['provinsi'];?>|<?= $data['province_name'];?>" selected><?= $data['province_name'];?></option>
                    <?php
                        $ambil=$koneksi->query("SELECT * FROM tb_ro_provinces ");
                        while($row=$ambil->fetch_assoc()){
                    ?>
                    <option value="<?php echo $row['province_id']; ?>|<?php echo $row['province_name']; ?>"><?php echo $row['province_name']; ?></option>
                    <?php } ?>
                </optgroup>
                </select>
            </div>
            <div class="form-group">
                <label for="kabupaten">Kota/Kabupaten Tujuan</label><br>
                <select class="form-control" id="kabupaten" name="kabupaten" required>
                <option value="<?= $data['kota'];?>" selected><?= $data['city_name'];?></option> 
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="kecamatan">Kecamatan Tujuan</label><br>
                <select class="form-control" id="kecamatan" name="kecamatan" required>
                <option value="<?= $data['kecamatan'];?>" selected><?= $data['subdistrict_name'];?></option>
                </select>
            </div>
            <center><button type="submit" class="btn btn-primary btn-lg" name="kirim">Kirim</button></center>
        </form>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP -->
    <?php
        include "koneksi.php";
        if(isset($_POST['kirim'])){
            $idmitraagen=$_SESSION["idmitraagen"];   
            $alamat=$_POST["alamat"];
        
            $provinsi_id=$_POST["prov"];
            $result_explode = explode('|', $provinsi_id);
            $provinsi=$result_explode[0];
        
            $kabupaten_id=$_POST["kabupaten"];
            $result_explode = explode('|', $kabupaten_id);
            $kabupaten=$result_explode[0];
        
            $kecamatan_id=$_POST["kecamatan"];
            $result_explode = explode('|', $kecamatan_id);
            $kecamatan=$result_explode[0];
        
            $kodepos=$_POST["kodepos"];
            $titikkordinat=$_POST["titikkordinat"];
        
            $koneksi->query("UPDATE mitraagen set alamat='$alamat', provinsi='$provinsi', kota='$kabupaten', kecamatan='$kecamatan', kodepos='$kodepos', titikkordinat='$titikkordinat' where idmitraagen='$idmitraagen' ");
            echo "<script>alert('Alamat Berhasil Di Ubah, silahkan re-login Kembali untuk Refresh data Anda');</script>";
            echo "<script>location='dataalamat.php';</script>";
        }
    ?>
    <?php include "settingdatatables.php"; ?>
    <!-- PHP END -->

    <!-- SCRIPT -->
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

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>