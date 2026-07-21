<?php
// Load file koneksi.php
include "koneksi.php";

// Ambil Data yang Dikirim dari Form
$idadmin = $_POST["idadmin"];
$namaproduk = $_POST["namaproduk"];
$stock = $_POST["stock"];


      $ambil=$koneksi->query("SELECT * FROM produk where namaproduk= '$namaproduk'"); 
      while($data=$ambil->fetch_assoc()){
     $idproduk=$data["idproduk"];
    
  $query = "INSERT INTO stockmitra (idstockmitra,idadmin,idproduk,stock,tgl) VALUES ('null','$idadmin','$idproduk','$stock',NOW())";    
  $query2 = "UPDATE produk SET stock= stock+'".$stock."' where idproduk='$idproduk'";
  $sql = mysqli_query( $koneksi, $query);
  $sql2 = mysqli_query( $koneksi, $query2);
  if($sql){ // Cek jika proses simpan ke database sukses atau tidak
    // Jika Sukses, Lakukan :
    header("location: tambahproduk.php"); // Redirect ke halaman index.php
  }else{
    // Jika Gagal, Lakukan :
    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
    echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
  }
      }  
?>