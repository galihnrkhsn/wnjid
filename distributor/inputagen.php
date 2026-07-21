<?php 
session_start();
include 'assets/components/Sessions/sesDistri.php';
include 'koneksi.php';
?>

<html lang="en">
<head>
	<title>Mitra <?php echo $_SESSION['namamitra']; ?>| Wanoja </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body>
        <!-- NAVBAR -->
        <? include 'assets/components/Navbar/navbar.php'; ?>
        <br>
        <!-- NAVBAR END -->

        <!-- MAIN CONTENT -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">Form Tambah Mitra</div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="py-2" for="namaagen">Nama Mitra*</label>
                                    <input type="text" class="form-control" id="namaagen" name="namaagen" required>
                                </div> 
                                <div class="form-group">
                                    <label class="py-2" for="status">Status Kemitraan*</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option disabled selected>Pilih Mitra</option>
                                        <option value="agen">Agen</option>
                                        <option value="reseller">Reseller</option>
                                        <option value="marketer">Marketer</option>
                                    </select>    
                                </div>
                                <div class="form-group">
                                    <label class="py-2" for="email">Email*</label>  
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label class="py-2" for="password">Password*</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label class="py-2" for="whatsapp">No Whatsapp*</label>
                                    <input type="number" class="form-control" id="whatsapp" name="whatsapp" required>
                                </div>
                                <div class="form-group">
                                    <label class="py-2" for="alamat">Alamat*</label>
                                    <textarea id="alamat" name="alamat" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="py-2" for="provinsi">Provinsi*</label>
                                    <select class="form-control" data-live-search="true" id="prov" name="provinsi" required>
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
                                    <label class="py-2" for="kota">Kota/Kabupaten*</label>
                                    <select class="form-control" id="kabupaten" name="kota" required>
                                        <option value="<?= $data['kota'];?>" selected><?= $data['city_name'];?></option> 
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="py-2" for="kecamatan">Kecamatan*</label>
                                    <select class="form-control" id="kecamatan" name="kecamatan" required>
                                        <option value="<?= $data['kecamatan'];?>" selected><?= $data['subdistrict_name'];?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="py-2" for="kodepos">Kode POS</label>
                                    <input type="text" class="form-control" id="kodepos" name="kodepos">
                                </div>
                                <div class="form-group">
                                    <label class="py-2" for="telegram">Telegram</label>
                                    <input type="text" class="form-control" id="telegram" name="telegram">
                                </div>
                                <div class="form-group">
                                    <label class="py-2" for="facebook">Facebook</label>
                                    <input type="text" class="form-control" id="facebook" name="facebook">
                                </div>
                                <div class="form-group">
                                    <label class="py-2" for="instagram">Instagram</label>
                                    <input type="text" class="form-control" id="instagram" name="instagram">
                                </div>
                                <div class="form-group">
                                    <label class="py-2" for="titikkordinat">Titik Kordinat</label>
                                    <input type="number" class="form-control" id="titikkordinat" name="titikkordinat">
                                </div>
                                <hr>  
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary" name="tambah">Simpan</button>
                                    <a class="btn btn-secondary" href="return">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- MAIN CONTENT END -->
    
    <!-- PHP SYNTAK -->
    <?
        if (isset($_POST['tambah'])) {
            $idadmin = $_SESSION["idadmin"];
            $namaagen = $_POST['namaagen'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $status = $_POST['status'];
            $alamat = $_POST['alamat'];
            $provinsi = $_POST['provinsi'];
            $kota = $_POST['kota'];
            $kecamatan = $_POST['kecamatan'];
            $kodepos = $_POST['kodepos'];
            $titikkordinat = $_POST['titikkordinat'];
            $whatsapp = $_POST['whatsapp'];
            $telegram = $_POST['telegram'];
            $instagram = $_POST['instagram'];
            $facebook = $_POST['facebook'];

            $checkEmailQuery = "SELECT email FROM users WHERE email = '$email'";
            $checkEmailResult = $koneksi->query($checkEmailQuery);

            if ($checkEmailResult->num_rows > 0) {
                echo "<script>alert('Email sudah terdaftar. Silakan gunakan email lain.'); window.location.href = 'inputagen.php';</script>";
                exit();
            }

            $queryUsers = "INSERT INTO users (id, name, email, password, role, created_at, updated_at) VALUES (NULL, '$namaagen', '$email', '$password', '$status', NOW(), NOW())";
            $resultUsers = $koneksi->query($queryUsers);

            if ($resultUsers) {
                $iduser = mysqli_insert_id($koneksi);
                // Jika data berhasil disimpan ke tabel users, lanjutkan dengan menyimpan data ke dalam tabel mitra sesuai status kemitraan
                switch ($status) {
                    case 'agen':
                        $queryMitra = "INSERT INTO mitraagen (idmitraagen, idadmin, iduser, kodeakses, namaagen, status, email, whatsapp, telegram, facebook, instagram, alamat, provinsi, kota, kecamatan, kodepos, titikkordinat, tgl_daftar) 
                                    VALUES (NULL, '$idadmin', $iduser, '$status', '$namaagen', '$status', '$email', '$whatsapp', '$telegram', '$facebook', '$instagram', '$alamat', '$provinsi', '$kota', '$kecamatan', '$kodepos', '$titikordinat', NOW())";
                        break;
                    case 'reseller':
                        $queryMitra = "INSERT INTO mitrareseller (idmitrareseller, idadmin, iduser, kodeakses, namaagen, status, email, whatsapp, telegram, facebook, instagram, alamat, provinsi, kota, kecamatan, kodepos, titikkordinat, tgl_daftar) 
                                    VALUES (NULL, '$idadmin', $iduser, '$status', '$namaagen', '$status', '$email', '$whatsapp', '$telegram', '$facebook', '$instagram', '$alamat', '$provinsi', '$kota', '$kecamatan', '$kodepos', '$titikordinat', NOW())";
                        break;
                    case 'marketer':
                        $queryMitra = "INSERT INTO mitramarketer (idmitramarketer, idadmin, iduser, kodeakses, namaagen, status, email, whatsapp, telegram, facebook, instagram, alamat, provinsi, kota, kecamatan, kodepos, titikkordinat, tgl_daftar) 
                                    VALUES (NULL, '$idadmin', $iduser, '$status', '$namaagen', '$status', '$email', '$whatsapp', '$telegram', '$facebook', '$instagram', '$alamat', '$provinsi', '$kota', '$kecamatan', '$kodepos', '$titikordinat', NOW())";
                        break;
                    default:
                        echo "Status kemitraan tidak valid";
                        exit(); // Keluar dari script jika status tidak valid
                }
            
                $resultMitra = $koneksi->query($queryMitra);
                if ($resultMitra) {
                    echo "<script>alert('Data berhasil disimpan.'); window.location.href = 'dataagen.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Gagal menyimpan data.'); window.location.href = 'dataagen.php';</script>";
                } 
            } else {
                echo "<script>alert('Gagal menyimpan data ke tabel users.'); window.location.href = 'dataagen.php';</script>";
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
    <!-- SCRIPT END -->
</body>
</html>  
<?
$idadmin = $_POST["name"];
$namaagen = $_POST['namaagen'];
"INSERT INTO mitrareseller (id, name) VALUES (NULL, '$idadmin',)";
                                    ?>
