<?php

 include "koneksi.php";
 
        echo "<option value='' disabled='disabled' selected>~Pilih Nomor invoice~</option>";
        $iddb = $_GET['iddb'];
// echo "<option>$iddb</option>";
         $ambil=$koneksi->query("SELECT *,sum(total)as totaltagihan FROM `pomitra` 
                                 where idmitra='$iddb' AND status='Belum DP'
                                 GROUP BY invoice");
        while($data=$ambil->fetch_assoc()){
        echo "<option value='".$data['invoice']."'>".$data['invoice']."</option>";
}


?>
