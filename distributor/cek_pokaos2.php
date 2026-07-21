<?php 
  include "koneksi.php";

  $jenis_filter = $_GET['jenis_filter'];

       $result = explode('|', $jenis_filter);
        $angka=$result[0];
        $idnya=$result[2]; 
        $jenis=$result[1];  
  // echo "$jenis";
 ?>
 <?php for ($i=0; $i < $angka ; $i++) {  ?>
<div class='form-group'>
  <div class='col-12'> 
    <label>Variant</label>

   <select class="form-control" name="idpodetail[]">
  <?php 
            $sql = "SELECT podetail.idpodetail, podetail.variant FROM podetail  
                    JOIN pokategori on pokategori.idpo = podetail.idpo
                    WHERE pokategori.idpoproduk='$idnya'
                    GROUP BY podetail.idpodetail
                    ";
            $query = $koneksi->query($sql);
            while($row = $query->fetch_assoc()){
   ?>
    <option value="<?= $row['idpodetail'] ?>"><?= $row['variant'] ?></option>
  <?php } ?>
  </select>
  </div>
   <div class='col-12'> 
      <label>Template</label>
                        <select  class='form-control' name='template[]' required>
                          <option value='' disabled='disabled' selected>~Pilih Template~</option>    
                                                     
                                  
                          <option >Tanpa Template</option> 
                          <option >KL 001</option>
                          <option >KL 002</option>
                          <option >KL 003</option>
                          <option >KL 004</option> 
                          <option >KL 005</option>
                          <option >KL 006</option>
                          <option >KL 007</option>
                                 
                          <option >SH-001</option>
                          <option >SH-002</option>
                          <option >SH-003</option>
                          <option >SH-004</option>
                          <option >SH-005</option>
                          <option >SH-006</option>
                          <option >SH-007</option>
                          <option >SH-008</option>
                          <option >SH-009</option>
                        
                          <option>TN-001</option>
                          <option>TN-002</option>
                          <option>TN-003</option>
                          <option>TN-004</option>
                          <option>TN-005</option>
                          <option>TN-006</option>
                          <option>TN-007</option>
                          <option>TN-008</option>
                          <option>TN-009</option>
                          <option>TN-010</option>
                          <option>TN-011</option>
                          <option>TN-012</option>
                          <option>TN-013</option>
                          <option>TN-014</option>
                          <option>TN-015</option>

                          <option >AS 001</option>
                          <option >AS 002</option>
                          <option >AS 003</option>
                          <option >AS 004</option> 
                          <option >AS 005</option>
                        
                        </select>  

  </div>    
<?php if ($jenis<>"Sahabat"): ?>    
  <div class='col-12'>  
    <label>Nama Custom</label>
    <input type="text" class="form-control" name="nama[]" maxlength='20' required>
  </div>  
  <div class='col-12'> 
      <label>Font</label>
                        <select class='form-control' name='font[]' required>
                          <option value='' disabled='disabled' selected>~Pilih Jenis Huruf~</option>
                          <option value='Arial'>Arial</option>
                          <option value='Arial Rounded MT Bold'>Arial Rounded MT Bold</option>
                          <option value='Times New Roman'>Times New Roman</option>
                          <option value='a amazing mother'>a amazing mother</option>
                          <option value='a awal Ramadan'>a awal Ramadan</option>
                          <option value='blackjack'>blackjack</option>
                          <option value='halaney demo'>halaney demo</option>
                          <option value='Days'>Days</option>
                          <option value='Gemess'>Gemess</option>
                        </select>  
  </div>  
<?php endif ?>   
</div> 
<hr>
 <?php } ?>