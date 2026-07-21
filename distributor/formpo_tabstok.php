<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $invoice = $_GET['invoice'];
    $idadmin = $_SESSION["idadmin"];
    $query = "SELECT COUNT(*) as jumlah,
                    poproduk.idpoproduk,
                    poproduk.namapo,
                    poproduk.status 
                    FROM poproduk 
                    inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                    WHERE poproduk.idpoproduk='$idpoproduk' 
                    AND pomitra.idmitra='$idadmin'
                    AND pomitra.invoice = '$invoice'
            ";
    $sql = mysqli_query($koneksi, $query);  
    $data = mysqli_fetch_array($sql);
    $namapo = $data['namapo'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Load File bootstrap.min.css yang ada difolder css -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!-- Load File bootstrap.min.css yang ada difolder css -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

    <title>Distributor | Wanoja</title>
</head> 
<body>
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="listnewpo"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>PRE ORDER</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #eee  center center;
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

.navbaru p {
  
  padding: 12px 0;
  font-size: 20px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru i {
  
  padding: 15px 0;
  font-size: 23px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru2 {
   
    
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

</style>

<!--================ NAVBARU END =================-->
   <div class="container"> 
   <table id="myJudul" class="w3-table-all w3-centered">
<?php        
$namapo=$data['namapo'];
 $id=$data['idpoproduk'];
if($data['jumlah']<1 || $idpoproduk == '328'){
 
//$stok=$data['stok'];
echo "
    <center> <h4> <b>Formulir Pemesanan $namapo </b> </h4> </center>";
}else{
echo "
    <tr>
      
    </tr>
    <center><b>Anda sudah mengisi Formulir $namapo , Klik Tombol Dibawah ini Jika ingin melihat atau merevisi Invoice, 
                                </b><br><br>
      <a class='btn btn-info' href='datapo.php?idmitra=$idadmin&id=$id'> INVOICE</a> </center>";
    }
?>
  </table>
  </div><br><br>
 
  <div class="container panel panel-default">
     
            <div class="panel-body">
                <?php if ($idpoproduk == '285') : ?>
                
                <?php elseif ($idpoproduk == '295') : ?>
                    <b> Sisa Stock: </b>
                    <div class="row mb-4">
                        <div class="col-6">
                            <p class="my-0">Habib Series:</p>
                            <?php
                                $idpoproduk = $_GET['id'];
                                $sql = "SELECT * FROM pokategori WHERE idpoproduk = '$idpoproduk' AND idpo BETWEEN '3064' AND '3068' ORDER BY idpo";
                                $query = $koneksi->query($sql);
                                while ($stok = $query->fetch_assoc()) {
                                    $nama = str_replace("Habib", "", $stok['namakategori']);
                            ?>
                                <p class="my-0"><?= $nama ?>: <?= $stok['stok'] ?></p>
                            <?php } ?>
                        </div>
                        
                        <div class="col-6">
                            <p class="my-0">Muhibbin Series:</p>
                            <?php
                                $idpoproduk = $_GET['id'];
                                $sql = "SELECT * FROM pokategori WHERE idpoproduk = '$idpoproduk' AND idpo BETWEEN '3069' AND '3073' ORDER BY idpo";
                                $query = $koneksi->query($sql);
                                while ($stok = $query->fetch_assoc()) {
                                    $nama = str_replace("Muhibbin", "", $stok['namakategori']);
                            ?>
                                <p class="my-0"><?= $nama ?>: <?= $stok['stok'] ?></p>
                            <?php } ?>
                        </div>
                    </div>
                <?php else : ?>
                    <b> Sisa Stock: </b>
                    <div class="row mb-4">
                        <?php
                            $idpoproduk = $_GET['id'];
                            $sql = "SELECT * from pokategori where idpoproduk='$idpoproduk' ORDER BY namakategori";
                            $query = $koneksi->query($sql);
                            while($stok = $query->fetch_assoc()){
                        ?>
                            <div class="col-2 my-2">
                               <?php echo $stok['namakategori']; ?>
                            </div>
                            <div class="col-2">
                                (<?php echo $stok['stok']; ?>)
                            </div>
                        <?php } ?>
                    </div>
                <?php endif; ?>

            <div class="row">
            <div class="col-md">
           	
                                
			<form method="POST">
		
		<div style="padding: 0 15px;">
								
					
	<ul class="nav nav-tabs">

<?php
$noo=1;
	$sql = "SELECT * FROM bukapo_tab 
			WHERE idpoproduk='$idpoproduk' order by id asc";
	$query = $koneksi->query($sql);
	while($row = $query->fetch_assoc()){
?>	
<?php if ($noo==1): ?>
		<li class="active"><a data-toggle="tab" href="#home<?= $row['id']; ?>"  class="nav-item nav-link active"><?= $row['nama_tab']; ?></a></li>
		<?php else: ?>				
		<li class=""><a data-toggle="tab" href="#home<?= $row['id']; ?>" class="nav-item nav-link"><?= $row['nama_tab']; ?></a></li>
		<?php endif ?>
<?php $noo++; ?>	
<?php } ?>
	</ul>
		<br>
		<div class="tab-content">
<?php
$no=1;
	$sql_isi = "SELECT * FROM bukapo_tab 
			WHERE idpoproduk='$idpoproduk' order by id asc";
	$query_isi = $koneksi->query($sql_isi);
	while($row_isi = $query_isi->fetch_assoc()){
$id_awal = $row_isi['id_awal'];
						  $id_akhir = $row_isi['id_akhir'];		
?>	
<?php if ($no==1): ?>
			<div id="home<?= $row_isi['id']; ?>" class="tab-pane fade active show in">
		<?php else: ?>
			<div id="home<?= $row_isi['id']; ?>" class="tab-pane fade ">					
		<?php endif ?>		
			

<?php
						$no++;
						  $idpoproduk = $_GET['id'];
						  
						$sql_variant = "SELECT * FROM poproduk 
						inner join pokategori on poproduk.idpoproduk=pokategori.idpoproduk
						inner join podetail on pokategori.idpo=podetail.idpo
						where poproduk.idpoproduk='$idpoproduk' and (podetail.idpodetail BETWEEN '$id_awal' AND '$id_akhir') order by pokategori.idpo asc";
						$query_variant = $koneksi->query($sql_variant);
							while($row_variant = $query_variant->fetch_assoc()){
								?>
				
				                <?//= $id_awal; ?>
				                <?//= $id_akhir; ?>
			
								
							
								<div class="form-group">
								    <label>
								        <?php echo $row_variant['variant']; ?>
								        <?php if ($idpoproduk == '285') : ?>
								            (<?= $row_variant['stok'] ?>)
								        <?php endif; ?>
								    </label>
								    <input type="hidden" name="idpodetail[]" value="<?php echo $row_variant['idpodetail']; ?>">
								    <input type="number" min="0" required name="jmlh[]" class="form-control" style="width:300px;" value=0>
								</div>	
								
				                <?php 
				                      } ?>

			</div>
<?php } ?>	
		</div>
				


			
			        <?php if($data['jumlah']<1 || $idpoproduk == '328'){
                    			echo "<button type='submit' class='btn btn-primary' name='save'>Kirim</button>";
				                }else{
                                echo "# ";
                                }
                    			?>
                    	</form>
			
                    	
<?php
	if(isset($_POST["save"])){
		date_default_timezone_set('Asia/Jakarta');
		
		$today = date("H:i:s");

		$idpodetail=$_POST["idpodetail"];
		$jmlh=$_POST["jmlh"];
			                         
		$jumlah_dipilih = count($jmlh);
		$total=0;
                                    
			for($x=0;$x<$jumlah_dipilih;$x++){

  $query_variant = "SELECT podetail.harga, podetail.idpo
            FROM podetail
            WHERE podetail.idpodetail='$idpodetail[$x]'";
  $sql_variant = mysqli_query($koneksi, $query_variant);  
  $data_variant = mysqli_fetch_array($sql_variant);
  $harga = $data_variant['harga'];
  $idpo = $data_variant['idpo'];
  var_dump(error_get_last());

				$total=$jmlh[$x]*$harga;

				$sql_sisa = "SELECT stok from pokategori where idpo='$idpo'";
				$query_sisa = $koneksi->query($sql_sisa);
				$sisa = $query_sisa->fetch_assoc();
							
					if($sisa['stok']>=$jmlh[$x]){
        				$invoice='D'.$idpoproduk.'-'.$idadmin;
						$koneksi->query("INSERT into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) 
							values (null,'$idadmin','$idpoproduk','$idpo','$idpodetail[$x]','$jmlh[$x]','$total','$invoice','Belum DP',NOW(),'$today')");
						$koneksi->query("UPDATE pokategori set stok=stok-'$jmlh[$x]' where idpo='$idpo'");
							echo "<script>alert('data berhasil dikirim');</script>";
							
							if($idpoproduk == '295') {
    							echo "<script>location='datapom2.php?id=$idpoproduk&invoice=$invoice';</script>";
							} else {
    							echo "<script>location='datapom3.php?id=$idpoproduk&invoice=$invoice';</script>";
							}
					 }else{
					 	// echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
						// echo "<script>location='listnewpo.php'</script>";
					 }

				}
}
         ?>
					                    
		</div>
	</div>
</div>	</div>
</div>

    
    <!-- NAVBAR END -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>