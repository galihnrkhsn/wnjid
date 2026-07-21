<?php 
session_start();
$namalengkap= $_SESSION["management"]["namalengkap"];
$title = $namalengkap;
include 'template/header.php'; 


if(!isset($_SESSION["management"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$iduser= $_SESSION["management"]["id"];

if ($iduser==1) {
    $tipe = $_GET['tipe'];
}else{
  $sql = "SELECT * FROM management WHERE id='$iduser' ";
  $query = $koneksi->query($sql);
  $data = $query->fetch_assoc();

$tipe = $data['tipe'];    
}

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<?php 
include 'template/topbar.php'; 
 ?>    
        <?php
        $ambil2=$koneksi->query("SELECT (bca+bni+bri+bsi+mandiri+muamalat) as total FROM rekeningbank ORDER BY id DESC LIMIT 1"); 
                  $distributor2=$ambil2->fetch_assoc();
        ?>  
         <div class="jumbotron">
        <center><h2>Cash Bank</h2> <h2>Rp. <?php echo number_format($distributor2["total"]); ?></h2></center>
        </div>    
<?php if ($tipe=="AF"): ?>   
<button class="btn btn-success" data-toggle="modal" data-target="#modalForm2"><i class="fa fa-plus"></i> Tambah data</button>  
<?php endif ?>   


<a href="index.php" class="btn btn-info" style="float: right;"><i class="fa fa-arrow-left"></i> Kembali</a>         


<div class="table-responsive" style="border:0px;">
	<br>
  <table class="table table-bordered table-striped" id="tbrk">
    <thead>
        <tr>
        	<th>No</th>
          <th style="align:center;">Waktu</th>
          <!-- <th style="align:center;">Bank</th>
          <th style="align:center;">Jumlah</th> -->
          <!-- <th style="align:center;">BCA</th>
          <th style="align:center;">BNI</th>
          <th style="align:center;">BRI</th>
          <th style="align:center;">BSI</th>
          <th style="align:center;">Mandiri</th>
          <th style="align:center;">Muamalat</th> -->
          <th style="align:center;">Total</th>
          <th >
            <select name="skill_dropdown" id="skill_dropdown">
                <option value="View">
                    View
                </option>
<?php if ($tipe=="AF"): ?>                 
                <option value="Edit">
                    Edit
                </option>
                <option value="Delete">
                    Delete
                </option>
<?php endif ?>	                
            </select>
          </th>
        </tr>
    </thead>
    <tbody>
                  <?php  
                  $no=1;
                  $ambil=$koneksi->query("SELECT * FROM rekeningbank ORDER BY id DESC"); 
                  while($tampil=$ambil->fetch_assoc()){
                  ?>      
        <tr>
        	<td><?= $no++; ?></td>
          <td><?= $tampil['waktu']; ?></td>
          <!-- <td><?= $tampil['bca']; ?></td>
          <td><?= $tampil['bni']; ?></td>
          <td><?= $tampil['bri']; ?></td>
          <td><?= $tampil['bsi']; ?></td>
          <td><?= $tampil['mandiri']; ?></td>
          <td><?= $tampil['muamalat']; ?></td> -->
<!--           <td>
			BCA<br>
          	BNI<br>
          	BRI<br>
          	BSI<br>
          	Mandiri<br>
          	Muamalat<br>
          </td>
          <td>
			<?= $tampil['bca']; ?><br>
          	<?= $tampil['bni']; ?><br>
          	<?= $tampil['bri']; ?><br>
          	<?= $tampil['bsi']; ?><br>
          	<?= $tampil['mandiri']; ?><br>
          	<?= $tampil['muamalat']; ?><br>
          </td> -->
          <td>Rp. <?= number_format($total = $tampil['bca']+$tampil['bni']+$tampil['bri']+$tampil['bsi']+$tampil['mandiri']+$tampil['muamalat']); ?></td>
          <td>
                        <div class="Edit skill" style="display: none">
                            <button class="btn btn-success" data-toggle="modal" data-target="#modalEdit<?php echo $tampil['id'];?>"><i class="fa fa-edit"></i></button>
                        </div>
                        <div class="View skill">
                            <button class="btn btn-info" data-toggle="modal" data-target="#modalView<?php echo $tampil['id'];?>"><i class="fa fa-eye"></i></button>
                        </div>
                        <form method="POST">
                        <div class="Delete skill" style="display: none">
                            <input type="hidden" value="<?= $tampil['id']; ?>" name="id" id="id" readonly>
                            <button class="btn btn-danger" type="submit" name="hapus" onclick="return confirm('Yakin Akan Hapus Data?');"><i class="fa fa-trash"></i></button>
                        </div>
                        </form>  
<script>    
$(document).ready(function () {
                $("#skill_dropdown").change(function () {
                    var inputVal = $(this).val();
                    var eleBox = $("." + inputVal);
                    $(".skill").hide();
                    $(eleBox).show();
                });
            });
</script>                                     
          </td>

        </tr>

<?php 
            if(isset($_POST["hapus"])){
            $id=$_POST["id"];
                
           $sql = $koneksi->query("DELETE FROM rekeningbank WHERE id='$id'");
                
                
	if ($sql) {

                  echo "<script>alert('Cash Bank ($id) telah berhasil dihapus');</script>";
	        		echo "<script>location='cashbank.php'</script>";
				}          
				else{
					echo "<script>alert('Cash Bank ($id) telah berhasil dihapus');</script>";
	        		echo "<script>location='cashbank.php'</script>";
					}                  
                
            }
 ?>



<!-------Modal VIEW--------------->
        <div class="modal fade" id="modalView<?php echo $tampil['id'];?>" role="dialog">
        <div class="modal-dialog">
        <div class="modal-content">
        <!-----ModalHeader-------------->
        <div class="modal-header">
        <h4 class="modal-title" id="labelModalKu"><i class="fa fa-eye"></i> View</h4>
        <button type="button" class="close" data-dismiss="modal">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Tutup</span>
        </button>
        </div>
        <!------ModalBody-------------->
        <form method="POST" enctype="multipart/form-data">
        <div class="modal-body">
        <p class="statusMsg"></p>

		<div class="form-group">
        <label for="bca">BCA</label>
        <input type="text" class="form-control" id="bca" value="Rp. <?= number_format($tampil['bca']); ?>" name="bca" placeholder="BCA" readonly >
        </div>

        <div class="form-group">
        <label for="bni">BNI</label>
        <input type="text" class="form-control" id="bni" value="Rp. <?= number_format($tampil['bni']); ?>" name="bni" placeholder="BNI" readonly >
        </div>

        <div class="form-group">
        <label for="bri">BRI</label>
        <input type="text" class="form-control" id="bri" value="Rp. <?= number_format($tampil['bri']); ?>" name="bri" placeholder="BRI" readonly >
        </div>

        <div class="form-group">
        <label for="bsi">BSI</label>
        <input type="text" class="form-control" id="bsi" value="Rp. <?= number_format($tampil['bsi']); ?>" name="bsi" placeholder="BSI" readonly >
        </div>

        <div class="form-group">
        <label for="mandiri">Mandiri</label>
        <input type="text" class="form-control" id="mandiri" value="Rp. <?= number_format($tampil['mandiri']); ?>" name="mandiri" placeholder="Mandiri" readonly >
        </div>

        <div class="form-group">
        <label for="muamalat">Muamalat</label>
        <input type="text" class="form-control" id="muamalat" value="Rp. <?= number_format($tampil['muamalat']); ?>" name="muamalat" placeholder="Muamalat" readonly >
        </div>                                        



        <div class="form-group">
        <label for="note">Note.</label>
        <textarea class="form-control" id="note" name="note" readonly><?= $tampil['note']; ?></textarea>
        </div>        

        </div>
        <!-------ModalFooter------------>
        <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">&times; Close</button>
        </div>
        </form>
        </div>
        </div>
        </div>    

         
        <!-------Modal EDIT--------------->
        <div class="modal fade" id="modalEdit<?php echo $tampil['id'];?>" role="dialog">
        <div class="modal-dialog">
        <div class="modal-content">
        <!-----ModalHeader-------------->
        <div class="modal-header">
        <h4 class="modal-title" id="labelModalKu"><i class="fa fa-edit"></i> Ubah</h4>
        <button type="button" class="close" data-dismiss="modal">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Tutup</span>
        </button>
        </div>
        <!------ModalBody-------------->
        <form method="POST" enctype="multipart/form-data">
        <div class="modal-body">
        <p class="statusMsg"></p>
        <input type="hidden" name="id" value="<?php echo $tampil['id'];?>"/>    
		<div class="form-group">
        <label for="bca">BCA</label>
        <input type="number" min=0 class="form-control" id="bca" value="<?= $tampil['bca']; ?>" name="bca" placeholder="BCA" required >
        </div>

        <div class="form-group">
        <label for="bni">BNI</label>
        <input type="number" min=0 class="form-control" id="bni" value="<?= $tampil['bni']; ?>" name="bni" placeholder="BNI" required >
        </div>

        <div class="form-group">
        <label for="bri">BRI</label>
        <input type="number" min=0 class="form-control" id="bri" value="<?= $tampil['bri']; ?>" name="bri" placeholder="BRI" required >
        </div>

        <div class="form-group">
        <label for="bsi">BSI</label>
        <input type="number" min=0 class="form-control" id="bsi" value="<?= $tampil['bsi']; ?>" name="bsi" placeholder="BSI" required >
        </div>

        <div class="form-group">
        <label for="mandiri">Mandiri</label>
        <input type="number" min=0 class="form-control" id="mandiri" value="<?= $tampil['mandiri']; ?>" name="mandiri" placeholder="Mandiri" required >
        </div>

        <div class="form-group">
        <label for="muamalat">Muamalat</label>
        <input type="number" min=0 class="form-control" id="muamalat" value="<?= $tampil['muamalat']; ?>" name="muamalat" placeholder="Muamalat" required >
        </div>                                        



        <div class="form-group">
        <label for="note">Note.</label>
        <textarea class="form-control" id="note" name="note"></textarea>
        </div>        
        </div>
        <!-------ModalFooter------------>
        <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">&times; Close</button>
        <button type="submit" class="btn btn-success" name="kirimubah" >Ubah</button>
        </div>
        </form>
        </div>
        </div>
        </div>
<?php 
 if(isset($_POST["kirimubah"])){
            date_default_timezone_set('Asia/Jakarta');
			$note=addslashes(htmlspecialchars($_POST["note"]));
            $bca=$_POST["bca"];
            $bni=$_POST["bni"];
            $bri=$_POST["bri"];
            $bsi=$_POST["bsi"];
            $mandiri=$_POST["mandiri"];
            $muamalat=$_POST["muamalat"];

            $id=$_POST["id"];

            
                $sql= $koneksi->query("UPDATE rekeningbank 
                					SET bca='$bca',
                						bni='$bni',
                						bri='$bri',
                            			bsi='$bsi',
                            			mandiri='$mandiri',
                            			muamalat='$muamalat',
                            			note='$note'
                            		WHERE id='$id'");
	if ($sql) {

                  echo "<script>alert('Cash Bank berhasil diubah');</script>";
	        		echo "<script>location='cashbank.php'</script>";
				}          
				else{
						echo "<script>alert('Cash Bank gagal diubah');</script>";
	        		echo "<script>location='cashbank.php'</script>";
					}  
            }
 ?>        

<?php } ?>
    </tbody>


  </table>
</div>


<!-- ========================================================MODAL KREDIT========================================================    -->
        <div class="modal fade" id="modalForm2" role="dialog">
        <div class="modal-dialog">
        <div class="modal-content">
        <!-----ModalHeader-------------->
        <div class="modal-header">
        <h4 class="modal-title" id="labelModalKu"><i class="fa fa-plus"></i> Kredit</h4>
        <button type="button" class="close" data-dismiss="modal">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Tutup</span>
        </button>
        </div>
        <!------ModalBody-------------->
        <form method="POST" enctype="multipart/form-data">
        <div class="modal-body">
        <p class="statusMsg"></p>
		<div class="form-group">
        <label for="bca">BCA</label>
        <input type="number" min=0 class="form-control" id="bca" value="0" name="bca" placeholder="BCA" required >
        </div>

        <div class="form-group">
        <label for="bni">BNI</label>
        <input type="number" min=0 class="form-control" id="bni" value="0" name="bni" placeholder="BNI" required >
        </div>

        <div class="form-group">
        <label for="bri">BRI</label>
        <input type="number" min=0 class="form-control" id="bri" value="0" name="bri" placeholder="BRI" required >
        </div>

        <div class="form-group">
        <label for="bsi">BSI</label>
        <input type="number" min=0 class="form-control" id="bsi" value="0" name="bsi" placeholder="BSI" required >
        </div>

        <div class="form-group">
        <label for="mandiri">Mandiri</label>
        <input type="number" min=0 class="form-control" id="mandiri" value="0" name="mandiri" placeholder="Mandiri" required >
        </div>

        <div class="form-group">
        <label for="muamalat">Muamalat</label>
        <input type="number" min=0 class="form-control" id="muamalat" value="0" name="muamalat" placeholder="Muamalat" required >
        </div>                                        



        <div class="form-group">
        <label for="note">Note.</label>
        <textarea class="form-control" id="note" name="note"></textarea>
        </div>
        </div>
        <!-------ModalFooter------------>
        <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">&times; Close</button>
        <button type="submit" class="btn btn-primary" name="kirimkredit" >KIRIM</button>
        </div>
        </form>
        </div>
        </div>
        </div>
        

<?php 

// ==============KIRIM KREDIT==============                 
            if(isset($_POST["kirimkredit"])){
            date_default_timezone_set('Asia/Jakarta');
            $waktu=date('Y-m-d H:i:s');
            $note=addslashes(htmlspecialchars($_POST["note"]));
            $bca=$_POST["bca"];
            $bni=$_POST["bni"];
            $bri=$_POST["bri"];
            $bsi=$_POST["bsi"];
            $mandiri=$_POST["mandiri"];
            $muamalat=$_POST["muamalat"];
 

			$sql = $koneksi->query("INSERT INTO rekeningbank (id,iduser,waktu,bca,bni,bri,bsi,mandiri,muamalat,note) VALUES
                                         (null,'$iduser','$waktu','$bca','$bni','$bri','$bsi','$mandiri','$muamalat','$note')"); 
	if ($sql) {

                  echo "<script>alert('Cash Bank berhasil ditambahkan');</script>";
	        		echo "<script>location='cashbank.php'</script>";
				}          
				else{
						echo "<script>alert('Cash Bank gagal ditambahkan');</script>";
	        		echo "<script>location='cashbank.php'</script>";
					}                                    


            }
// ==============KIRIM KREDIT==============    
 ?>        
 <!-- ========================================================MODAL KREDIT========================================================    -->



<?php 
include 'template/footer.php'; 
 ?>


    <script type="text/javascript">
        $(document).ready( function () {
    $('#tbrk').DataTable();
} );
</script>