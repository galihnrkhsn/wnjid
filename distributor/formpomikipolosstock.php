<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

  $idpoproduk = $_GET['id'];
  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
  $query = "SELECT COUNT(*) as jumlah,poproduk.idpoproduk,poproduk.namapo,poproduk.status FROM poproduk inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk WHERE poproduk.idpoproduk='$idpoproduk' AND pomitra.idmitra='$idadmin'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);


  $sql_invoice = mysqli_query($koneksi, "SELECT invoice FROM pomitra WHERE idmitra = '$idadmin' and invoice LIKE '%MHP%' ");
  $data_invoice = mysqli_fetch_array($sql_invoice);
$hasil = $data_invoice['invoice'];
  ?> 
  
 <title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>
<html lang="en">
<head>
  <meta charset="utf-8">
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
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> 

</head>
<body>

<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="listnewpo.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
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
 $idadmin=$_SESSION["admin_mitra"]["idadmin"]; 
 $id=$data['idpoproduk'];

echo "
    <center> <h4> <b>Formulir Pemesanan $namapo </b> </h4> </center>";

?>
  </table>
  </div><br><br>
 
  <div class="container panel panel-default">
  	<!-- <p align="center">Satuan per Pack(isi 10 pasang).</p> -->
<?php if ($idpoproduk<>181 and $idpoproduk<>182 and $idpoproduk<>183): ?>
      
    
    | <b> Sisa Stock: </b>
 <div class="row">
              <?php
            //initialize total
              $idpoproduk = $_GET['id'];
          
            $sql = "SELECT * from pokategori where idpoproduk='$id' ORDER BY namakategori";
            $query = $koneksi->query($sql);
              while($stok = $query->fetch_assoc()){
                ?>

<div class="col-3">
   <?php echo $stok['namakategori']; ?>
</div>
<div class="col-3">
    (<?php echo $stok['stok']; ?>)
</div>
<br>
<?php } ?>
</div>
    <?php endif ?>    
            <div class="panel-body">
            <div class="row">
            <div class="col-md-6">
                                
	
		
		<div style="padding: 0 15px;">
        
        						<form method="POST">			
							
					<div style="padding: 0 15px;">

            	
            	
					<?php
						//initialize total
						  $idpoproduk = $_GET['id'];
						$total = 0;
						$index = 0;
						$sql = "SELECT * FROM poproduk 
            inner join pokategori 
            inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo 
            where poproduk.idpoproduk='$idpoproduk'
            AND podetail.variant LIKE '%Polos%'
             order by podetail.idpodetail asc ";
						$query = $koneksi->query($sql);
							while($row = $query->fetch_assoc()){
								?>
		            
								<div class="form-group">
								    <label><?php echo $row['variant']; ?></label>
								    <input type="hidden" name="idpo[]" value="<?php echo $row['idpo']; ?>">
								    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
								    <input type="number" min="0" required name="jmlh[]" class="form-control" style="width:80px;" value='0'>
								    <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>" value='0'>
								</div>	
								
				                <?php } ?>
<p><strong><font color="red" size="5px">*</font></strong>Jangan Kosongkan Label, Cukup isi dengan Angka 0 jika tidak memesan</p>				                      
				                <?php

echo "<button type='submit' class='btn btn-primary' name='save'>Kirim</button>";
                      
                    			
                    			?>
                    			
                
			        			
                    	</form>
                    	
<?php
if(isset($_POST["save"])){
    include "koneksi.php";
    date_default_timezone_set('Asia/Jakarta');
    $today = date("s");
    $waktu = date("H:i:s");
    $idadmin=$_SESSION["admin_mitra"]["idadmin"];
    $idpo= $_POST["idpo"];
    $idpodetail=$_POST["idpodetail"];
    $jmlh=$_POST["jmlh"];
    $harga=$_POST["harga"];
    			                         
    $jumlah_dipilih = count($jmlh);
    $total=0;

  $sql = mysqli_query($koneksi, "SELECT idpomitra FROM pomitra order by idpomitra desc limit 1");
  $data = mysqli_fetch_array($sql);
  $no=$data['idpomitra'];
  $ab=1;
  $nobaru=$no+$ab;

$invoice = 'MHP'.$idpoproduk.'-'.$idadmin.'-'.$nobaru;                                    
                                    
    for($x=0;$x<$jumlah_dipilih;$x++){
      $total=$jmlh[$x]*$harga[$x];
      $sql = "Select stok from pokategori where idpo='$idpo[$x]'";
      $query = $koneksi->query($sql);
      $sisa = $query->fetch_assoc();
 if($sisa['stok']>$jmlh[$x]){
    $koneksi->query("INSERT into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) values
    (null,'$idadmin','$idpoproduk','$idpo[$x]','$idpodetail[$x]','$jmlh[$x]','$total',
                                     '$invoice','Belum DP',NOW(),'$waktu')");
                                     $sqlpo = $koneksi->query("update pokategori set stok=stok-'$jmlh[$x]' where idpo='$idpo[$x]'");
                                      
                                      }else{ 
                                          $jmlh[$x]=0;
                                          $total=0;
                                     $sqlpo = $koneksi->query("INSERT into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) values
                                     (null,'$idadmin','$idpoproduk','$idpo[$x]','$idpodetail[$x]','$jmlh[$x]','$total','$invoice','Belum DP',NOW(),'$waktu')");
                                          
                                                    }             
    }
if ($sqlpo) {
  echo "<script>location='datapom.php?id=$idpoproduk&invoice=$invoice';</script>";  
}else{
  echo "<script>location='formpomikipolosstock?id=$idpoproduk';</script>";    
}
			                        }           
					                    ?>
					                    
		</div>
	</div>
</div>	</div>
</div>
