<?php 
error_reporting (0);
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}


$id=$_GET["idpreorder"];
$ambil=$koneksi->query("SELECT * FROM list_po_mitra WHERE idpreorder='$id'");
$tampilkan=$ambil->fetch_assoc();

     $nama=$tampilkan['file'];
     $back_dir    ="../adminpusat/file/";
     $file = $back_dir.$nama;
    
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: private');
            header('Pragma: private');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            
            exit;
    ?>    


