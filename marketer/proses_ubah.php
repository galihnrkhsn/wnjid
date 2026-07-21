<?php
// Load file koneksi.php
include "koneksi.php";


$idmitramarketer = $_GET['idmitramarketer'];

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
if(isset($_POST['ubahalamat'])){
      // Proses ubah data ke Database
    $query = "UPDATE mitramarketer SET email='".$email."', password='".$password."', namaagen='".$namaagen."', whatsapp='".$whatsapp."', telegram='".$telegram."', facebook='".$facebook."', instagram='".$instagram."', alamat='".$alamat."', provinsi='".$provinsi."', kota='".$kabupaten."', kecamatan='".$kecamatan."', titikkordinat='".$titikkordinat."' WHERE idmitramarketer='".$idmitramarketer."'";
    $sql = mysqli_query($koneksi, $query); // Eksekusi/ Jalankan query dari variabel $query

    if($sql){ // Cek jika proses simpan ke database sukses atau tidak
      // Jika Sukses, Lakukan :
      echo "<script>alert('Profile Berhasil Di Ubah, silahkan re-login Kembali untuk Refresh data Anda');</script>";
        echo "<script>location='logout.php';</script>";
    }else{
      // Jika Gagal, Lakukan :
      echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
      echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
    }
}else
{ // Jika user tidak menceklis checkbox yang ada di form ubah, lakukan :
  // Proses ubah data ke Database
  $query = "UPDATE mitramarketer SET email='".$email."', password='".$password."', namaagen='".$namaagen."', whatsapp='".$whatsapp."', telegram='".$telegram."', facebook='".$facebook."', instagram='".$instagram."', titikkordinat='".$titikkordinat."' WHERE idmitramarketer='".$idmitramarketer."'";
  $sql = mysqli_query($koneksi, $query); // Eksekusi/ Jalankan query dari variabel $query

  if($sql){ // Cek jika proses simpan ke database sukses atau tidak
    // Jika Sukses, Lakukan :
    echo "<script>alert('Profile Berhasil Di Ubah, silahkan re-login Kembali untuk Refresh data Anda');</script>";
        echo "<script>location='logout.php';</script>";
  }else{
    // Jika Gagal, Lakukan :
    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
    echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
  }
}
?>