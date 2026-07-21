<?php
    session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesDistri.php';

    $idadmin=$_SESSION["idadmin"];
    $query = "SELECT * FROM admin_mitra WHERE idadmin='".$idadmin."'";
    $sql = mysqli_query($koneksi, $query);  
    $data = mysqli_fetch_array($sql);

    $provinsi = $data['provinsi'];
    $kabupaten = $data['kabupaten'];
    $kecamatan = $data['kecamatan'];
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
    <? include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container">
        <form method="post" action="proses_ubah.php?idadmin=<?php echo $idadmin; ?>" enctype="multipart/form-data">
            <!-- <input type="checkbox" name="ubah_foto" value="true"><b> Ceklis jika ingin mengubah foto</b><br><br>
            <input type="file" name="foto"> -->
            <hr>
            <div class="form-group">
            <label>Nama Mitra</label>
            <input class="form-control" name="namamitra" value="<?php echo $data['namamitra']; ?>">
            <input class="form-control" type="hidden" name="password" value="<?php echo $data['password']; ?>">
            </div>

            <!-- <div class="form-group">
            <label>Password</label>
            </div> -->

            <div class="form-group">
            <label>Whatsapp</label>
            <input class="form-control" name="whatsapp" value="<?php echo $data['whatsapp']; ?>">
            <label><font size="2" color="silver">contoh (628xxxxxxxx)</font></label>
            </div>

            <div class="form-group">
            <label>Telegram</label>
            <input class="form-control" name="telegram" value="<?php echo $data['telegram']; ?>">
            <label><font size="2" color="silver">isi dengan username telegram tanpa @ <br>(untuk melihat username ada di pengaturan)</font></label>
            </div>

            <div class="form-group">
            <label>Facebook</label>
            <input class="form-control" name="facebook" value="<?php echo $data['facebook']; ?>">
            <label><font size="2" color="silver">isi dengan link facebook pribadi atau bisnis<br>misal: www.facebook.com/wanojahijab cukup masukan "wanojahijab"</font></label>
            </div>

            <div class="form-group">
            <label>Instagram</label>
            <input class="form-control" name="instagram" value="<?php echo $data['instagram']; ?>">
            <label><font size="2" color="silver">isi dengan link IG pribadi atau bisnis<br>misal: www.instagram.com/wanojahijab cukup masukan "wanojahijab"</font></label>
            </div>

            <div class="form-group">
            <label>Email</label>
            <input class="form-control" name="email" value="<?php echo $data['email']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="provinsi">Provinsi Tujuan</label><br>
                <select class="form-control" id="provinsi" name="provinsi" required>
                <option disabled='disabled' selected>~Pilih Provinsi Tujuan~</option>
                <?php         
                    $ambil = $koneksi->query("SELECT * FROM tb_ro_provinces");
                    while($row = $ambil->fetch_assoc()){
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

            <hr>
            <button type="submit" class="btn btn-primary">Ubah</button>
            <a class="btn btn-default" href='profile.php'>Batal</a>
        </form>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <!-- PHP END -->

    <!-- FOOTER -->
    <? include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#provinsi').change(function(){
                var prov = $('#provinsi').val();
                $.ajax({
                    type: 'GET',
                    url: 'cek_kabupaten2.php',
                    data: 'prov_id=' + prov,
                    success: function(data){
                    $("#kabupaten").html(data);
                    }
                });
            });

            $('#kabupaten').change(function(){
                var kabupaten = $('#kabupaten').val();
                $.ajax({
                    type: 'GET',
                    url: 'cek_kecamatan2.php',
                    data: 'kabupaten_id=' + kabupaten,
                    success: function(data){
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
                    type: 'POST',
                    url: 'cek_ongkir.php',
                    data: {'kab_id': kab, 'kec_id': kec, 'kurir': kurir, 'asal': asal, 'berat': berat},
                    success: function(data){
                    $("#ongkir").html(data);
                    }
                });
            });
        });
    </script>
    <!-- END SCRIPT -->
</body>
</html>