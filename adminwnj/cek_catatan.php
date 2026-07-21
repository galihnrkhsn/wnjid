<?php 
  include "koneksi.php";
  
  $jenis_filter = $_GET['jenis_filter'];



 ?>

 <?php if ($jenis_filter<>"Pilih Nama CS"): ?>
<?php 
  $query = "SELECT idcssales,namacs
        FROM cssales
        WHERE idcssales='$jenis_filter'";
  $sqlcs = mysqli_query($koneksi, $query);  
  $datacs = mysqli_fetch_array($sqlcs);
  $namacs=$datacs['namacs'];
echo "<h4>$namacs</h4>";
 ?> 	
<form method="post">  	
<button type="submit" class="btn btn-success btn-sm" name="done">Done</button>

<button type="submit" class="btn btn-danger btn-sm" name="undone">Undone</button>	

<button type="submit" class="btn btn-primary btn-sm" name="simpan">Simpan</button>	

<input type='submit' class="btn btn-warning btn-sm" value='Hapus' name='hapus' onclick="return confirm('Yakin Akan Hapus Data?');">
<br>	
<br>
<div class="table-responsive">
	<table class="table table-striped" id="tb_price_list">
		<thead>
			<tr>
				<td style="width: 10px">No</td>
				<th style="width: 10px"><input type='checkbox' id='checkAll' ></th>
				<td style="width: 10px">Status</td>
				<td style="width: 10px">Tanggal</td>
				<td>Keterangan</td>			
			</tr>
		</thead>
		<tbody>
			<tr>
			<?php 
				$data_cat=$koneksi->query("SELECT * FROM catatan 
											WHERE idcssales=$jenis_filter
											ORDER BY status DESC ");
				$no=1;                          
 				while($tampilkan=$data_cat->fetch_assoc()){		
 				$id = $tampilkan['idcatatan'];	
			?>
				<td><?= $no++; ?></td>
				<td>
					<input type="hidden" name="idcssales<?= $id ?>" value="<?= $tampilkan['idcssales']; ?>">
					<input type='checkbox' name='update[]' value='<?= $id ?>' >
				</td>
				<td>
					<?php if ($tampilkan['status']=="Done"): ?>
						<span class="badge bg-success text-white"><?= $tampilkan['status']; ?></span>
						<?php else: ?>
							<span class="badge bg-danger text-white"><?= $tampilkan['status']; ?></span>
					<?php endif ?>
						
				</td>
				<td><?= $tampilkan['tanggal']; ?></td>
				<td>
					<textarea class="form-control" name="ket<?= $id ?>" style="width: 95%;"><?= $tampilkan['ket']; ?></textarea>
				</td>	
			</tr>
			<?php } ?>
		</tbody>
	</table>
  			
</div>
	<br>
</form>	
<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update[]"]').prop('checked',true);
                    }else{
                        $('input[name="update[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update[]"]').click(function(){
                    var total_checkboxes = $('input[name="update[]"]').length;
                    var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll').prop('checked',true);
                    }else{
                        $('#checkAll').prop('checked',false);
                    }
                });
            });
        </script>
<?php include 'settingdatatables.php'; ?>        
 <?php endif ?>