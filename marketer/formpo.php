<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

  $idpoproduk = $_GET['id'];
  $idmitramarketer=$_SESSION["mitraagen"]["idmitramarketer"];
  $query = "SELECT COUNT(*) as jumlah,poproduk.idpoproduk,poproduk.namapo,poproduk.status FROM poproduk inner join poagen on poproduk.idpoproduk=poagen.idpoproduk WHERE poproduk.idpoproduk='$idpoproduk' AND poagen.idmarketer='$idmitramarketer'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);
  ?> 
  
<html lang="en">
<head>
        <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title></title>

		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	    <link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap.css">
	    <link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/jquery.dataTables.css">
	    <link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/dataTables.bootstrap.css">
		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	
        <title>Mitra <?php echo $_SESSION['mitraagen']['namamitra']; ?>| WNJ.ID </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
        crossorigin="anonymous">  
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
   
    background: #eee  url("jumbotron-bg.png") center center;
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
 $idmitramarketer=$_SESSION["mitraagen"]["idmitramarketer"]; 
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
      <a class='btn btn-info' href='datapo.php?poagen=$idmitramarketer&id=$id'> INVOICE</a> </center>";
    }
?>
  </table>
  </div><br><br>
 
  <div class="container panel panel-default">
     
 
            <div class="panel-body">
            <div class="row">
            <div class="col-md-6">
                                
		
		
		<div style="padding: 0 15px;">
            	
					<?php
						//initialize total
						  $idpoproduk = $_GET['id'];
						$total = 0;
						$index = 0;
						$sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk'";
						$query = $koneksi->query($sql);
							while($row = $query->fetch_assoc()){
								?>
								
						
						<form method="POST">			
							
								<div class="form-group">
								    <label><?php echo $row['variant']; ?></label>
								    <input type="hidden" name="idpo[]" value="<?php echo $row['idpo']; ?>">
								    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
								    <input type="text" name="jmlh[]" class="form-control" style="width:300px;">
								    <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
								</div>	
								
				                <?php $namapo2=$row['namapo'];
				                $id=$row['idpoproduk'];
				                      $idmitramarketer=$_SESSION["mitraagen"]["idmitramarketer"]; 
				                      } ?>
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
                                        $today = date("s");
					                    $idmitramarketer=$_SESSION["mitraagen"]["idmitramarketer"];
					                    $idpo= $_POST["idpo"];
					                    $idpodetail=$_POST["idpodetail"];
			                            $jmlh=$_POST["jmlh"];
			                            $harga=$_POST["harga"];
			                            $jumlah_dipilih = count($jmlh);
                                        $subtotal=0;  
                                        $total=0;
                                        $jmlhakhir=0;
                                    
                                        for($x=0;$x<$jumlah_dipilih;$x++){
                                    	    $total=$jmlh[$x]*$harga[$x];
                                    	    //$jmlhtotal=count($total);
                                            // for($x=0;$x<$jmlhtotal;$x++){
                                            //echo number_format($total, 2);
                                            //  $subtotal=$subtotal+$total[$x];
                                            $tot=$total;
                                            $jmlhakhir+=$jmlhakhir+$jmlh[$x];
                                            $tot=0;
                                            // if($stok>$jmlh[$x]){
        					                $koneksi->query("insert into poagen (idpoagen,idmarketer,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl) values
        					                ('null','$idmitramarketer','$idpoproduk','$idpo[$x]','$idpodetail[$x]','$jmlh[$x]','$total','M$idpoproduk-$idmitramarketer','Belum ACC DB',NOW())");
        					                // $koneksi->query("update pokategori set stok=stok-'$jmlh[$x]' where idpo='$idpo'");
        					                echo "<script>alert('data berhasil dikirim');</script>";
		                                    echo "<script>location='datapo.php?id=$idpoproduk';</script>";
        					                //  }else{ echo "<script>alert('Stok kami tidak mencukupi');</script>";
		                                    echo "<script>location='formpo.php?id=$idpoproduk';</script>";
                                         }
			                         }
			                      // }           
					                    ?>
					                    
</div>
</div>
</div>
</div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.js"></script>
<script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.dataTables.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
</html>

