<?php
    
      $idpomitra= $_POST['idpomitra'];
      $idpoproduk= $_POST['idpoproduk'];
      $invoice= $_POST['invoice'];
      $jumlah=$_POST["jumlah"];    
      $sql333 = $koneksi->query("UPDATE pomitra set jumlah='$jumlah'  WHERE idpomitra='$idpomitra'");

        if ($sql333) {
          echo "<script>alert('Data berhasil disimpan ');</script>";
          
        }else{
          echo "<script>alert('Data gagal disimpan ');</script>";
          
        }
echo "<script>document.location.href='datapo?idmitra='$idadmin'&id='$idpoproduk'&invoice=$invoice';</script>";
    
?>