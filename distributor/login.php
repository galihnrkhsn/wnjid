<?php
include 'cek_keterangan.php'; 

if (isset($_POST['email'])) { // check apakah ada pengiriman data
    session_start();
    require 'koneksi.php'; // menyisipkan file koneksi

    $email = $_POST['email'];
    $pass = $_POST['password'];


   $ambil=$koneksi->query("SELECT * FROM admin_mitra WHERE email='$email' AND password='$pass' ");
   $ambil2=$koneksi->query("SELECT * FROM mitraagen WHERE email='$email' AND password='$pass' ");
   $ambil3=$koneksi->query("SELECT * FROM mitrareseller WHERE email='$email' AND password='$pass' ");
   $ambil4=$koneksi->query("SELECT * FROM mitramarketer WHERE email='$email' AND password='$pass' ");

   $akundb=$ambil->num_rows;
   $akunagen=$ambil2->num_rows;
   $akunreseller=$ambil3->num_rows;
   $akunmarketer=$ambil4->num_rows;

    if($akundb==1){
        $akun=$ambil->fetch_assoc();
        //setelah di arraykan maka disimpan di sesson
        $_SESSION["admin_mitra"] = $akun;
        $idadmin=$_SESSION["admin_mitra"]["idadmin"];
        $koneksi->query("INSERT INTO statuslogin (idsession,idadmin,keterangan) VALUES (null,'$idadmin','$keterangan')");
        echo "<div class='alert alert-info'>login sukses</div>";
        echo "<script>location='../distributor/index.php';</script>";

    } elseif($akunagen==1){
        $akun=$ambil2->fetch_assoc();
        //setelah di arraykan maka disimpan di sesson
        $_SESSION["mitraagen"] = $akun;
        header('location:../agen/index.php');

    } elseif($akunreseller==1){
        $akun=$ambil3->fetch_assoc();
        //setelah di arraykan maka disimpan di sesson
        $_SESSION["mitraagen"] = $akun;
        header('location:../reseller/index.php');
    }
        elseif($akunmarketer==1){
        $akun=$ambil4->fetch_assoc();
        //setelah di arraykan maka disimpan di sesson
        $_SESSION["mitraagen"] = $akun;
        header('location:../marketer/index.php');
    }
    else { // jika datanya tidak ada
        echo "<script>alert('Username & Password Salah !!!'); window.location.href='index.php'</script>";
    }
 
}

if (!isset($_POST['email'])) {
    header('location:login2.php');
}
?>