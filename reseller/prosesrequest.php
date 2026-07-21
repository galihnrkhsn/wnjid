<?php
// Load file koneksi.php
include "koneksi.php";

// Ambil Data yang Dikirim dari Form
$idadmin = $_POST["idadmin"];
$namaproduk = $_POST["namaproduk"];

  $query = "INSERT INTO requestproduk (idrequest,idadmin,namaproduk,status,tgl) VALUES ('null','$idadmin','$namaproduk','Pending',NOW())";    
  
  $sql = mysqli_query( $koneksi, $query);
  
  if($sql){ // Cek jika proses simpan ke database sukses atau tidak
    // Jika Sukses, Lakukan :
    header("location: requestproduk.php"); // Redirect ke halaman index.php
  }else{
    // Jika Gagal, Lakukan :
    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
    echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
  }
      
?>