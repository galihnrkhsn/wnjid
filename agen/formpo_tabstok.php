<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

  $idpoproduk = $_GET['id'];
  $idmitraagen=$_SESSION["mitraagen"]["idmitraagen"];
  $query = "SELECT COUNT(*) as jumlah,poproduk.idpoproduk,poproduk.namapo,poproduk.status FROM poproduk inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk WHERE poproduk.idpoproduk='$idpoproduk' AND pomitra.idmitraagen='$idmitraagen'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);
  ?> 
  
<html lang="en">
<head>
<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">


        	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| WNJ.ID </title>

</head>
<body>

<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="listpreorder.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
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
 $idmitraagen=$_SESSION["mitraagen"]["idmitraagen"]; 
 $id=$data['idpoproduk'];
if($data['jumlah']<1){
 
//$stok=$data['stok'];
echo "
    <center> <h4> <b>Formulir Pemesanan $namapo </b> </h4> </center>";
}else{
echo "
    <tr>
      
    </tr>
    <center><b>Anda sudah mengisi Formulir $namapo , Klik Tombol Dibawah ini Jika ingin melihat atau merevisi Invoice, 
                                </b><br><br>
      <a class='btn btn-info' href='datapo.php?idmitra=$idmitraagen&id=$id'> INVOICE</a> </center>";
    }
?>
  </table>
  </div><br><br>
 
  <div class="container panel panel-default">
     
 
            <div class="panel-body">

<b> Sisa Stock: </b>
 <div class="row mb-4">
              <?php
            //initialize total
              $idpoproduk = $_GET['id'];
          
            $sql = "SELECT * from pokategori where idpoproduk='$id' ORDER BY namakategori";
            $query = $koneksi->query($sql);
              while($stok = $query->fetch_assoc()){
                ?>

<div class="col-2">
   <?php echo $stok['namakategori']; ?>
</div>
<div class="col-2">
    (<?php echo $stok['stok']; ?>)
</div>
<br>
<br>
<br>
<?php } ?>
</div> 

            <div class="row">
            <div class="col-md-6">
                                
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
				
			
								
							
								<div class="form-group">
								    <label><?php echo $row_variant['variant']; ?></label>
								    <input type="hidden" name="idpo[]" value="<?php echo $row_variant['idpo']; ?>">
								    <input type="hidden" name="idpodetail[]" value="<?php echo $row_variant['idpodetail']; ?>">
								    <input type="number" min="0" required name="jmlh[]" class="form-control" style="width:300px;" value=0>
								    <input type="hidden" name="harga[]" value="<?php echo $row_variant['harga']; ?>">
								</div>	
								
				                <?php 
				                      } ?>

			</div>
<?php } ?>
		</div>
				


			
			        <?php if($data['jumlah']<1){
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

		
		$idmitraagen=$_SESSION["mitraagen"]["idmitraagen"];
		

		$idpo= $_POST["idpo"];
		$idpodetail=$_POST["idpodetail"];
		$jmlh=$_POST["jmlh"];
		$harga=$_POST["harga"];
			                         
		$jumlah_dipilih = count($jmlh);
		$total=0;
                                    
			for($x=0;$x<$jumlah_dipilih;$x++){
				$total=$jmlh[$x]*$harga[$x];
				$jmlhakhir+=$jmlhakhir+$jmlh[$x];


				$sql_sisa = "SELECT stok from pokategori where idpo='$idpo[$x]'";
				$query_sisa = $koneksi->query($sql_sisa);
				$sisa = $query_sisa->fetch_assoc();
								
					if($sisa['stok']>$jmlh[$x]){
						$koneksi->query("INSERT into pomitra (idpomitra,idmitraagen,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) 
							values (null,'$idmitraagen','$idpoproduk','$idpo[$x]','$idpodetail[$x]','$jmlh[$x]','$total','A$idpoproduk-$idmitraagen','Belum Acc DB',NOW(),'$today')");
						$koneksi->query("UPDATE pokategori set stok=stok-'$jmlh[$x]' where idpo='$idpo[$x]'");
							echo "<script>alert('data berhasil dikirim');</script>";
							echo "<script>location='datapo.php?id=$idpoproduk';</script>";
	
					 }else{ 
							$jmlh[$x]=0;
							$total=0;
								$koneksi->query("INSERT into pomitra (idpomitra,idmitraagen,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) 
								values (null,'$idmitraagen','$idpoproduk','$idpo[$x]','$idpodetail[$x]','$jmlh[$x]','$total','A$idpoproduk-$idmitraagen','Belum Acc DB',NOW(),'$today')");
									echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
									echo "<script>location='ubahpostok.php?id=$idpoproduk';</script>";
						}
				}
}
         ?>					                    
					                    
		</div>
	</div>
</div>	</div>
</div>
