<?php

 include "koneksi.php";
 
        // echo "<option value='' disabled='disabled' selected>~Pilih Nomor invoice~</option>";
        $invoice = $_GET['invoice'];
// echo "<option>$iddb</option>";
         $ambil=$koneksi->query("SELECT invoice,sum(total) as totaltagihan FROM `pomitra` 
                                 where invoice='$invoice' GROUP BY invoice");
        $data=$ambil->fetch_assoc();
        $diskondb = (65/100)*$data['totaltagihan'];
        echo "<option value='".$diskondb."'>".number_format($diskondb)."</option>";



?>
