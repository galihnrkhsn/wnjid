<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesAgen.php';

    $idmitraagen = $_SESSION['idmitraagen'];
    $query = "SELECT * FROM mitraagen WHERE idmitraagen='".$idmitraagen."'";
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
        <form method="post" enctype="multipart/form-data">
            <div class="form-group mb-2">
                <label for="namaagen">Nama Mitra</label>
                <input type="text" class="form-control" id="namaagen" name="namaagen" value="<?php echo $data['namaagen']; ?>">
                <input type="hidden" class="form-control" name="password" value="<?php echo $data['password']; ?>">
            </div>

            <div class="form-group mb-2">
                <label for="whatsapp">Whatsapp</label>
                <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="<?php echo $data['whatsapp']; ?>">
                <small class="form-text text-muted">Contoh: 628xxxxxxxx</small>
            </div>    

            <div class="form-group mb-2">
                <label for="telegram">Telegram</label>
                <input type="text" class="form-control" id="telegram" name="telegram" value="<?php echo $data['telegram']; ?>">
                <small class="form-text text-muted">Isi dengan username telegram tanpa @ (untuk melihat username ada di pengaturan)</small>
            </div>

            <div class="form-group mb-2">
                <label for="facebook">Facebook</label>
                <input type="text" class="form-control" id="facebook" name="facebook" value="<?php echo $data['facebook']; ?>">
                <small class="form-text text-muted">Isi dengan link Facebook pribadi atau bisnis, misal: www.facebook.com/wanojahijab cukup masukkan "wanojahijab"</small>
            </div>

            <div class="form-group mb-2">
                <label for="instagram">Instagram</label>
                <input type="text" class="form-control" id="instagram" name="instagram" value="<?php echo $data['instagram']; ?>">
                <small class="form-text text-muted">Isi dengan link Instagram pribadi atau bisnis, misal: www.instagram.com/wanojahijab cukup masukkan "wanojahijab"</small>
            </div>

            <div class="form-group mb-2">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $data['email']; ?>" readonly>
            </div>

            <!-- Contoh bagian untuk pengisian alamat, jika diperlukan
            <div class="form-group mb-2">
                <label for="alamat">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo $data['alamat']; ?></textarea>
                <small class="form-text text-muted">* Isi dengan benar semua detail alamat. Alamat ini akan digunakan untuk proses transaksi</small>
            </div>
            -->

            <!-- Contoh bagian untuk memilih provinsi, kabupaten, dan kecamatan menggunakan API RajaOngkir -->
            <!-- 
            <div class="form-group mb-2">
                <label for="provinsi">Provinsi Tujuan</label>
                <select class="form-control" id="provinsi" name="provinsi">
                    <option>Pilih Provinsi Tujuan</option>
                    <?php
                    // Get Data Provinsi
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://pro.rajaongkir.com/api/province",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => array(
                            "key:d1e0da7453eb42f959f5b3072a94a21c"
                        ),
                    ));
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    $data = json_decode($response, true);
                    for ($i = 0; $i < count($data['rajaongkir']['results']); $i++) {
                        echo "<option value='" . $data['rajaongkir']['results'][$i]['province_id'] . "|" . $data['rajaongkir']['results'][$i]['province'] . "'>" . $data['rajaongkir']['results'][$i]['province'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group mb-2">
                <label for="kabupaten">Kota/Kabupaten Tujuan</label>
                <select class="form-control" id="kabupaten" name="kabupaten"></select>
            </div>

            <div class="form-group mb-2">
                <label for="kecamatan">Kecamatan Tujuan</label>
                <select class="form-control" id="kecamatan" name="kecamatan"></select>
            </div>
            -->

            <button type="submit" class="btn btn-primary">Ubah</button>
            <a class="btn btn-secondary" href='profile.php'>Batal</a>
        </form>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP -->
    <?php
        $idmitraagen = $_SESSION['idmitraagen'];
        // Ambil Data yang Dikirim dari Form
        $email = $_POST['email'];
        $password = $_POST['password'];
        $namaagen = $_POST['namaagen'];
        $whatsapp = $_POST['whatsapp'];
        $telegram = $_POST['telegram'];
        $facebook = $_POST['facebook'];
        $instagram = $_POST['instagram'];
        $alamat = $_POST['alamat'];
        
         $provinsi_id=$_POST["provinsi"];
              $result_explode = explode('|', $provinsi_id);
            $provinsi=$result_explode[0];
             
             $kabupaten_id=$_POST["kabupaten"];
             $result_explode = explode('|', $kabupaten_id);
            $kabupaten=$result_explode[0];
             
             $kecamatan_id=$_POST["kecamatan"];
             $result_explode = explode('|', $kecamatan_id);
            $kecamatan=$result_explode[0];
            
        $titikkordinat = $_POST['titikkordinat'];
        
        // Cek apakah user ingin mengubah fotonya atau tidak
        if(isset($_POST['ubah_foto']) and isset($_POST['ubahalamat']) ){ // Jika user menceklis checkbox yang ada di form ubah, lakukan :
         // Ambil data foto yang dipilih dari form
          $foto = $_FILES['foto']['name'];
          $tmp = $_FILES['foto']['tmp_name'];
          // Set path folder tempat menyimpan fotonya
          $path = "foto/".$foto;
          // Proses upload
          if(move_uploaded_file($tmp, $path)){ // Cek apakah gambar berhasil diupload atau tidak
          
            // Proses ubah data ke Database
            $query = "UPDATE mitraagen SET email='".$email."', password='".$password."', namaagen='".$namaagen."', whatsapp='".$whatsapp."', telegram='".$telegram."', facebook='".$facebook."', instagram='".$instagram."', alamat='".$alamat."', provinsi='".$provinsi."', kota='".$kabupaten."', kecamatan='".$kecamatan."', titikkordinat='".$titikkordinat."', foto='".$foto."' WHERE idmitraagen='".$idmitraagen."'";
            $sql = mysqli_query($koneksi, $query); // Eksekusi/ Jalankan query dari variabel $query
        
            if($sql){ // Cek jika proses simpan ke database sukses atau tidak
              // Jika Sukses, Lakukan :
              header("location: profile.php"); // Redirect ke halaman index.php
            }else{
              // Jika Gagal, Lakukan :
              echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
              echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
            }
          }else{
            // Jika gambar gagal diupload, Lakukan :
            echo "Maaf, Gambar gagal untuk diupload.";
            echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
          }
          
        }else if(isset($_POST['ubah_foto'])){
             // Ambil data foto yang dipilih dari form
          $foto = $_FILES['foto']['name'];
          $tmp = $_FILES['foto']['tmp_name'];
          // Set path folder tempat menyimpan fotonya
          $path = "foto/".$foto;
          // Proses upload
          if(move_uploaded_file($tmp, $path)){ // Cek apakah gambar berhasil diupload atau tidak
          
            // Proses ubah data ke Database
            $query = "UPDATE mitraagen SET email='".$email."', password='".$password."', namaagen='".$namaagen."', whatsapp='".$whatsapp."', telegram='".$telegram."', facebook='".$facebook."', instagram='".$instagram."', titikkordinat='".$titikkordinat."', foto='".$foto."' WHERE idmitraagen='".$idmitraagen."'";
            $sql = mysqli_query($koneksi, $query); // Eksekusi/ Jalankan query dari variabel $query
        
            if($sql){ // Cek jika proses simpan ke database sukses atau tidak
              // Jika Sukses, Lakukan :
              echo "<script>alert('Foto Berhasil Diubah');</script>";
                echo "<script>location='profile.php';</script>";
            }else{
              // Jika Gagal, Lakukan :
              echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
              echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
            }
          }else{
            // Jika gambar gagal diupload, Lakukan :
            echo "Maaf, Gambar gagal untuk diupload.";
            echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
        }
        }else if(isset($_POST['ubahalamat'])){
              // Proses ubah data ke Database
            $query = "UPDATE mitraagen SET email='".$email."', password='".$password."', namaagen='".$namaagen."', whatsapp='".$whatsapp."', telegram='".$telegram."', facebook='".$facebook."', instagram='".$instagram."', alamat='".$alamat."', provinsi='".$provinsi."', kota='".$kabupaten."', kecamatan='".$kecamatan."', titikkordinat='".$titikkordinat."' WHERE idmitraagen='".$idmitraagen."'";
            $sql = mysqli_query($koneksi, $query); // Eksekusi/ Jalankan query dari variabel $query
        
            if($sql){ // Cek jika proses simpan ke database sukses atau tidak
              // Jika Sukses, Lakukan :
              header("location: logout.php"); // Redirect ke halaman index.php
            }else{
              // Jika Gagal, Lakukan :
              echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
              echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
            }
        }else
        { // Jika user tidak menceklis checkbox yang ada di form ubah, lakukan :
          // Proses ubah data ke Database
          $query = "UPDATE mitraagen SET email='".$email."', password='".$password."', namaagen='".$namaagen."', whatsapp='".$whatsapp."', telegram='".$telegram."', facebook='".$facebook."', instagram='".$instagram."', titikkordinat='".$titikkordinat."' WHERE idmitraagen='".$idmitraagen."'";
          $sql = mysqli_query($koneksi, $query); // Eksekusi/ Jalankan query dari variabel $query
        
          if($sql){ // Cek jika proses simpan ke database sukses atau tidak
            // Jika Sukses, Lakukan :
            echo "<script>alert('Profile Berhasil Diubah, Silahkan Login kembali untuk refresh data profile');</script>";
                echo "<script>location='logout.php';</script>";
          }else{
            // Jika Gagal, Lakukan :
            echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
            echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
          }
        }
    ?>
    <?php include "settingdatatables.php"; ?>
    <!-- PHP END -->

    <!-- SCRIPT -->
    <script type="text/javascript">
        $(document).ready(function(){
            $('#provinsi').change(function(){

                //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                var prov = $('#provinsi').val();

                $.ajax({
                    type : 'GET',
                    url : 'cek_kabupaten.php',
                    data :  'prov_id=' + prov,
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
                    url : 'cek_kecamatan.php',
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
                        $("#ongkir").html(data);
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>