<?php 
  include "koneksi.php";


 ?>

<!-- ====================================================1071==================================================== -->
<?php 
$text = 'jenis_filter';
$awal = 1;
$no=1;
for ($i=0; $i < 20 ; $i++) { 

$jenisnya = $text.$awal;

  $jenis_[$awal] = $_GET[$jenisnya];
       $result_[$awal] = explode('|', $jenis_[$awal]);
        $angka_[$awal]=$result_[$awal][0];
        $idnya_[$awal]=$result_[$awal][1];
?>
<?php if ($angka_[$awal]>0):  ?>
<?php for($x=0;$x<$angka_[$awal];$x++){ ?> 
<div class='form-group'>
  <div class='col-12'>  
    <label>Pcs.</label>
    <input type="number" class="form-control" name="jmlh[]" min="0" required>
  </div>
  <div class='col-12'> 
    <label>Variant</label>
   <select class="form-control" name="idpodetail[]">
  
  <?php 
            $sql = "SELECT * FROM podetail  
                    WHERE podetail.idpo='$idnya_[$awal]'
                    ";
            $query = $koneksi->query($sql);
            while($row = $query->fetch_assoc()){
   ?>
    <option value="<?= $row['idpodetail'] ?>"><?= $row['variant'] ?></option>
  <?php } ?>
  </select>
  </div>
  <div class='col-12'>  
    <label>Nama Custom</label>
    <input type="text" class="form-control" name="nama[]" maxlength='15' required>
  </div>  
  <div class='col-12'> 
      <label>Template</label>
                        <select class='form-control' name='template[]' required>
                          <option value='' disabled='disabled' selected>~Pilih Jenis Template~</option>            
                          <option >Tanpa Template</option>
                          <option >Kaos Lebaran</option>
                          <option >Eid Mubarak</option>
                        </select>  
  </div>   
  <div class='col-12'> 
      <label>Font</label>
                        <select class='form-control' name='font[]' required>
                          <option value='' disabled='disabled' selected>~Pilih Jenis Huruf~</option>            
                          <option value='Arial'>Arial</option>
                          <option value='Times New Roman'>Times New Roman</option>
                          <option value='a amazing mother'>a amazing mother</option>
                          <option value='a awal Ramadan'>a awal Ramadan</option>
                          <option value='blackjack'>blackjack</option>
                          <option value='halaney demo'>halaney demo</option>
                        </select>  
  </div>  
</div> 
<hr> 
<?php } ?> 
<?php endif ?>
<?php 
$awal = $awal + 1; 

?>


<?php } ?>
