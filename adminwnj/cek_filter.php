<?php 
// session_start();
 include 'koneksi.php'; 

//   $tanggal = $_GET['tanggal'];

// echo "$tanggal";

  $filter = $_GET['filter'];

 // echo "$filter";

 ?>

<?php if ($filter=="Harian"): ?>
    <form method="post" class="col-4">
        <input type="hidden" class="form-control" name="filter" id="filter" value="<?= $filter; ?>"> 

<div class="input-group mb-3">

  <input type="date" class="form-control" name="tanggal" id="tanggal" required> 
  <span class="input-group-text" style="border-radius:0;">s/d</span>
  <input type="date" class="form-control" name="tanggal2" id="tanggal2" required> 
</div>                
                      <select class="form-control" name="namacs" id="namacs" required>

                          <option value="" >- Pilih CS -</option>
                          <option value="Semua CS" >Semua CS</option>
                          <?php
                          $datadb=$koneksi->query("SELECT * FROM admin_mitra_cs WHERE namacs <> '' GROUP BY namacs ORDER BY namacs asc");
                          while($tampilkan=$datadb->fetch_assoc()){
                          ?>
                      <option value="<?php echo $tampilkan['namacs']; ?>"><?php echo $tampilkan['namacs']; ?></option>
                      <?php } ?>  
                      </select>   
                      <br>
                <button type="submit" class="btn btn-primary" name="cari">Cari</button> 
    </form>
                      
<?php endif ?>
 
 <?php if ($filter=="Bulanan"): ?>
    <form method="post" class="col-4">

        <input type="hidden" class="form-control" name="filter" id="filter" value="<?= $filter; ?>">    
                    <div class="form-group">
                          <select  class="form-control" name="tanggal" id="tanggal">
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                          </select>               
                    </div> 
                      <select class="form-control" name="namacs" id="namacs" required>
                          <option value="" >- Pilih CS -</option>
                          <option value="Semua CS" >Semua CS</option>
                          <?php
                          $datadb=$koneksi->query("SELECT * FROM admin_mitra_cs WHERE namacs <> '' GROUP BY namacs ORDER BY namacs asc");
                          while($tampilkan=$datadb->fetch_assoc()){
                          ?>
                      <option value="<?php echo $tampilkan['namacs']; ?>"><?php echo $tampilkan['namacs']; ?></option>
                      <?php } ?>  
                      </select>   
                      <br>
                <button type="submit" class="btn btn-primary" name="cari">Cari</button> 
</form>    
 <?php endif ?>

