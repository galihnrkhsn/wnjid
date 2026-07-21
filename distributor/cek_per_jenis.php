<?php 
session_start();

include 'koneksi.php'; 

$jenis_mitra = $_GET['jenis_mitra'];

$result_explode = explode('|', $jenis_mitra);
$jenis=$result_explode[0];
$idpoproduk=$result_explode[1];

 ?>

<?php if ($jenis=="Satu"): ?>
    <div class="form-group">
          <select class="form-control" name="jenis_mitra" id="jenis_mitra" required>
          	<option value="" selected>- Pilih Variant -</option>
<?php
	$sql = "SELECT * FROM poproduk 
			inner join pokategori 
			inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk 
			and pokategori.idpo=podetail.idpo 
			where poproduk.idpoproduk='$idpoproduk' 
			order by podetail.variant asc";
						$query = $koneksi->query($sql);
							while($row = $query->fetch_assoc()){
								?>          	
              
             <option value="<?php echo $row['idpodetail']; ?>"><?php echo $row['variant']; ?></option>
<?php } ?>             
          </select>      
    </div> 
	<div class="form-group">
		<input type="number" name="jmlh[]" class="form-control" value=2 readonly>
	</div>
<?php endif ?>


<?php if ($jenis=="Dua"): ?>
    <div class="form-group">
          <select class="form-control" name="jenis_mitra" id="jenis_mitra" required>
          	<option value="" selected>- Pilih Variant -</option>
<?php
	$sql = "SELECT * FROM poproduk 
			inner join pokategori 
			inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk 
			and pokategori.idpo=podetail.idpo 
			where poproduk.idpoproduk='$idpoproduk' 
			order by podetail.variant asc";
						$query = $koneksi->query($sql);
							while($row = $query->fetch_assoc()){
								?>          	
              
             <option value="<?php echo $row['idpodetail']; ?>"><?php echo $row['variant']; ?></option>
<?php } ?>             
          </select>      
    </div> 
	<div class="form-group">
		<input type="number" name="jmlh[]" class="form-control" value=1 readonly>
	</div>    
    <div class="form-group">
          <select class="form-control" name="jenis_mitra" id="jenis_mitra" required>
          	<option value="" selected>- Pilih Variant -</option>
<?php
	$sql = "SELECT * FROM poproduk 
			inner join pokategori 
			inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk 
			and pokategori.idpo=podetail.idpo 
			where poproduk.idpoproduk='$idpoproduk' 
			order by podetail.variant asc";
						$query = $koneksi->query($sql);
							while($row = $query->fetch_assoc()){
								?>          	
              
             <option value="<?php echo $row['idpodetail']; ?>"><?php echo $row['variant']; ?></option>
<?php } ?>             
          </select>      
    </div>     
	<div class="form-group">
		<input type="number" name="jmlh[]" class="form-control" value=1 readonly>
	</div>
<?php endif ?>


