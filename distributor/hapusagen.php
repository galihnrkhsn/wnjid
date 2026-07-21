<?php
    include "koneksi.php";
   $id = $_GET['idmitraagen'];
            $koneksi->query("DELETE FROM mitra_agen WHERE idmitraagen='$id'" );
            echo "<script>alert('data berhasil dihapus');</script>";
		    echo "<script>location='dataagen';</script>";
?>