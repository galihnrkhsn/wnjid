<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    ob_start();

    // Load file koneksi.php
    include "koneksi.php";

    $idadmin    = $_GET['idadmin'];
    $email      = $_POST['email'];
    $namamitra  = $_POST['namamitra'];
    $whatsapp   = $_POST['whatsapp'];
    $telegram   = $_POST['telegram'];
    $facebook   = $_POST['facebook'];
    $instagram  = $_POST['instagram'];
    // $alamat = $_POST['alamat'];
    // $titikkordinat = $_POST['titikkordinat'];

    $provinsi_id        = $_POST['provinsi'];
    $result_explode     = explode('|', $provinsi_id);
    $provinsi           = $result_explode[0];

    $kabupaten_id       = $_POST['kabupaten'];
    $result_explode     = explode('|', $kabupaten_id);
    $kabupaten          = $result_explode[0];

    $kecamatan_id       = $_POST['kecamatan'];
    $result_explode     = explode('|', $kecamatan_id);
    $kecamatan          = $result_explode[0];

    // Cek apakah user ingin mengubah foto dan/atau alamat
    $ubah_foto          = isset($_POST['ubah_foto']);
    $ubah_alamat        = isset($_POST['ubahalamat']);

    if ($ubah_foto) {
        $foto           = $_FILES['foto']['name'];
        $tmp            = $_FILES['foto']['tmp_name'];
        $ukuranFile     = $_FILES['foto']['size'];
        $errorFile      = $_FILES['foto']['error'];
        $path           = "foto/".$foto;

                // Debugging information
        echo "Error code: " . $errorFile . "<br>";
        echo "File size: " . $ukuranFile . "<br>";
        echo "Temp file location: " . $tmp . "<br>";

        if ($errorFile !== UPLOAD_ERR_OK) {
            echo "<script>alert('Gagal mengupload file dengan error code: $errorFile');</script>";
            exit;
        }

        if (move_uploaded_file($tmp, $path)) {
            $foto_query = ", foto='$foto'";
        } else {
            echo "Maaf, Gambar gagal untuk diupload.";
            echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
            exit;
        }
    } else {
        $foto_query = "";
    }

    if ($ubah_alamat) {
        $alamat_query = ", alamat='$alamat', provinsi='$provinsi', kota='$kabupaten', kecamatan='$kecamatan'";
    } else {
        $alamat_query = "";
    }

    // Proses ubah data ke Database
    $query = "UPDATE admin_mitra SET 
                email       = '$email',
                namamitra   = '$namamitra', 
                whatsapp    = '$whatsapp', 
                telegram    = '$telegram', 
                facebook    = '$facebook', 
                instagram   = '$instagram',
                provinsi    = '$provinsi',
                kota        = '$kabupaten',
                kecamatan   = '$kecamatan'
              WHERE idadmin='$idadmin'";

    $sql = mysqli_query($koneksi, $query); // Eksekusi/ Jalankan query dari variabel $query

    if ($sql) { // Cek jika proses simpan ke database sukses atau tidak
        if ($ubah_foto) {
            echo "<script>alert('Foto Berhasil Diubah');</script>";
        }
        if ($ubah_alamat) {
            echo "<script>alert('Profile Berhasil Diubah, Silahkan Login kembali untuk refresh data profile');</script>";
            echo "<script>location='logout.php';</script>";
        } else {
            echo "<script>alert('Profile Berhasil Diubah, Silahkan Login kembali untuk refresh data profile');</script>";
            echo "<script>location='logout.php';</script>";
            header("Location:profile.php"); // Redirect ke halaman profile.php
        }
    } else {
        // Jika Gagal, Lakukan :
        echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
        error_log("Error: " . mysqli_error($koneksi)); // Tambahkan ini untuk debugging
        echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
    }

    ob_end_flush();

?>
