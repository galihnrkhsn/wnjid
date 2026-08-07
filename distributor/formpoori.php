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
   
    background: #eee center center;
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
      <a class='btn btn-info' href='datapo.php?idmitra=$idadmin&id=$id'> INVOICE</a> </center>";
    }
?>
  </table>
  </div><br><br>
 
  <div class="container panel panel-default">
  	<!-- <p align="center">Satuan per Pack(isi 10 pasang).</p> -->
    | <b> Sisa Stock: </b>
 <div class="row">
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
<?php } ?>
</div>
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
						$sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail asc ";
						$query = $koneksi->query($sql);
							while($row = $query->fetch_assoc()){
								?>
		            
								<div class="form-group">
								    <label><?php echo $row['variant']; ?></label>
								    <input type="hidden" name="idpo[]" value="<?php echo $row['idpo']; ?>">
								    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
								    <input type="number" min="0" required name="jmlh[]" class="form-control" style="width:80px;" value='0'>
								    <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
								</div>	
								
				                <?php $namapo2=$row['namapo'];
				                $id=$row['idpoproduk'];
				                      $idadmin=$_SESSION["admin_mitra"]["idadmin"]; 
				                      } ?>
<p><strong><font color="red" size="5px">*</font></strong>Jangan Kosongkan Label, Cukup isi dengan Angka 0 jika tidak memesan</p>				                      
				                <?php if($data['jumlah']<1){
                    			echo "<button type='submit' class='btn btn-primary' name='save'>Kirim</button>";
				                }else{
                                echo "# ";
                                }
                    			?>
                    			
                
			        			
                    	</form>
                    	
			                       <?php
			                        if(isset($_POST["save"])){
	                                 include "koneksi.php";
	                               date_default_timezone_set('Asia/Jakarta');
                                    $today = date("H:i:s");
          					               $idadmin=$_SESSION["admin_mitra"]["idadmin"];
          					               $idpo= $_POST["idpo"];
          					               $idpodetail=$_POST["idpodetail"];
			                             $jmlh=$_POST["jmlh"];
			                             $harga=$_POST["harga"];
                                  $total = 0;
                             $jumlah_dipilih = count($jmlh);
                                    for($y=0;$y<$jumlah_dipilih;$y++){
                                      $total += $jmlh[$y];
                                    }
                                    if ($total>4) {
                                      echo "<script>alert('Jumlah barang melebihi 4 Pcs');</script>";
                                      echo "<script>location='formpoori.php?id=$idpoproduk';</script>";
                                      return false;
                                    }else{

                                    for($x=0;$x<$jumlah_dipilih;$x++){
                                      $total_jumlah=$jmlh[$x]*$harga[$x];
                                      
                                    $sql = "SELECT stok from pokategori where idpo='$idpo[$x]'";
                                    $query = $koneksi->query($sql);
                                    $sisa = $query->fetch_assoc();
                
if($sisa['stok']>$jmlh[$x]){
  $koneksi->query("INSERT into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl) 
  values (null,'$idadmin','$idpoproduk','$idpo[$x]','$idpodetail[$x]','$jmlh[$x]','$total_jumlah','D$idpoproduk-$idadmin','Belum DP',NOW())");
  
  $koneksi->query("UPDATE pokategori set stok=stok-'$jmlh[$x]' where idpo='$idpo[$x]'");
    echo "<script>alert('data berhasil dikirim');</script>";
    echo "<script>location='datapo.php?id=$idpoproduk';</script>";
  }
  else{ 
      $jmlh[$x]=0;
      $total_jumlah=0;
      $koneksi->query("INSERT into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl) 
      values (null,'$idadmin','$idpoproduk','$idpo[$x]','$idpodetail[$x]','$jmlh[$x]','$total_jumlah','D$idpoproduk-$idadmin','Belum DP',NOW())");
        echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
        echo "<script>location='ubahpostok.php?id=$idpoproduk';</script>";
    }
                                      }//endfor
                                    }//endelse

			                        }//endisset           
					                    ?>
					                    
		</div>
	</div>
</div>	</div>
</div>

