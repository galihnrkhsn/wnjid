<?php 
  include "koneksi.php";


 ?>

<!-- ====================================================1071==================================================== -->
<?php 
$text = 'jenis_filter';
$awal = 1;
$no=1;
for ($i=0; $i < 30 ; $i++) { 

$jenisnya = $text.$awal;

  $jenis_[$awal] = $_GET[$jenisnya];
       $result_[$awal] = explode('|', $jenis_[$awal]);
        $angka_[$awal]=$result_[$awal][0];
        $idnya_[$awal]=$result_[$awal][1];
?>

<script>
    console.log(<?=$jenisnya?>)
</script>
<?php if ($angka_[$awal]>0):  ?>
<?php for($x=0;$x<$angka_[$awal];$x++){ ?> 
<div class='form-group row'>
  <div class='col-3'>  
    <label>Pcs.</label>
    <input type="number" class="form-control" name="jmlh[]" min="0" required>
  </div>
  <div class='col-9'> 
    <label>Variant</label>
   <select class="form-control" name="idpodetail[]">
  
  <?php 
            $sql = "SELECT * FROM podetail  
                    WHERE podetail.idpo='$idnya_[$awal]'
                    AND podetail.variant NOT LIKE '%LD%'
                    ";
            $query = $koneksi->query($sql);
            while($row = $query->fetch_assoc()){
   ?>
    <option value="<?= $row['idpodetail'] ?>"><?= $row['variant'] ?></option>
  <?php } ?>
  </select>
  </div>
</div>  
<?php } ?> 
<?php endif ?>
<?php 
$awal = $awal + 1; 

?>


<?php } ?>
