<?php 
include "koneksi.php";

  $jmlh_tab = $_GET['jmlh_tab'];

 // echo "$jmlh_tab";
$result_explode = explode('|', $jmlh_tab);
$idpoproduk=$result_explode[0];
$angka=$result_explode[1];

 ?>
<br>
<?php

for ($x = 0; $x < $angka; $x++) {

?>
<label>Tab <?= $x+1; ?></label>
<hr>
        <div class="form-group">
<label>Nama Tab</label>   
   <input style="width: auto;" type="text" name="nama_tab[]" class="form-control" required>
</div>

  
     <div class="form-group">
          <label>ID Awal</label>
          <br>
          <select class="form-control" id="id_awal[]" name="id_awal[]" required>
          <option disabled='disabled' value="" selected>~Pilih ID Awal~</option>
          <?php
          $ambil=$koneksi->query("SELECT podetail.idpodetail, podetail.variant 
          							FROM podetail
          							JOIN pokategori on pokategori.idpo = podetail.idpo 
          							JOIN poproduk on pokategori.idpoproduk = poproduk.idpoproduk
          							WHERE poproduk.idpoproduk = '$idpoproduk' 
          							ORDER BY podetail.idpodetail DESC");
          while($row=$ambil->fetch_assoc()){
          ?>
          <option value="<?php echo $row['idpodetail']; ?>"><?php echo $row['idpodetail']; ?> | <?php echo $row['variant']; ?>
          </option>
          <?php } ?>
          </select>
          </div>
          <div class="form-group">
<label>ID Akhir</label> 
          <br>
          <select class="form-control" id="id_akhir[]" name="id_akhir[]" required>
          <option disabled='disabled' value="" selected>~Pilih ID Akhir~</option>
          <?php
          $ambil=$koneksi->query("SELECT podetail.idpodetail, podetail.variant 
          							FROM podetail
          							JOIN pokategori on pokategori.idpo = podetail.idpo 
          							JOIN poproduk on pokategori.idpoproduk = poproduk.idpoproduk
          							WHERE poproduk.idpoproduk = '$idpoproduk' 
          							ORDER BY podetail.idpodetail DESC");
          while($row=$ambil->fetch_assoc()){
          ?>
          <option value="<?php echo $row['idpodetail']; ?>"><?php echo $row['idpodetail']; ?> | <?php echo $row['variant']; ?>
          </option>
          <?php } ?>
          </select>
</div>
  <?php
}
 ?>