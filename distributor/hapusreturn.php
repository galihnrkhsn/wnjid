<?php
    include "koneksi.php";
   $id = $_GET['idsupport'];
            $koneksi->query("DELETE FROM support_ticket WHERE idsupport='$id'" );
            echo "<script>alert('data berhasil dihapus');</script>";
		    echo "<script>location='return.php';</script>";
?>