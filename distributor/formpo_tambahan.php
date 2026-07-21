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
  $invoice=$_GET["invoice"];
  $query = "SELECT poproduk.idpoproduk,
            poproduk.namapo,
            pomitra.waktu,
            pomitra.tgl,
            pomitra.status,
            pomitra.idpodetail,
            pomitra.idmitra,
            pomitra.idmitraagen,
            pomitra.idmitrareseller,
            pomitra.idmitramarketer

        FROM pomitra 
        inner join poproduk on poproduk.idpoproduk=pomitra.idpoproduk    
        WHERE pomitra.invoice='$invoice'";
  $sqlpo = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sqlpo);

$idpoproduk = $data['idpoproduk'];
$idmitra = $data['idmitra'];
$idmitraagen = $data['idmitraagen'];
$idmitrareseller = $data['idmitrareseller'];
$idmitramarketer = $data['idmitramarketer'];
$tgl = $data['tgl'];
$waktu = $data['waktu'];
$status = $data['status'];
  ?> 
  
<html lang="en">
<head>
<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
<title>WNJ</title>

		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
        	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.js"></script>
	<script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.dataTables.js"></script>
	<link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/dataTables.bootstrap.css">
		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| Wanoja </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>


  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">  

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
 $idadmin=$_SESSION["admin_mitra"]["idadmin"]; 
 $id=$data['idpoproduk'];

echo "
    <center> <h4> <b>Formulir Pemesanan $namapo </b> </h4> </center>";

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
<br>
<br>
<?php } ?>
</div>
            <div class="panel-body">
            <div class="row">
            <div class="col-md-6">
                                
	
		
		<div style="padding: 0 15px;">
        
                                        <form method="POST">
        <?php
$no = 1;
            $sql = "SELECT podetail.idpo, podetail.idpodetail, podetail.variant, podetail.harga FROM podetail
                  join pokategori on pokategori.idpo=podetail.idpo
                  where pokategori.idpoproduk='$idpoproduk'
                  order by podetail.variant asc";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){

  $query_detail = "SELECT 
            pomitra.idpodetail

        FROM pomitra  
        WHERE pomitra.invoice='$invoice'
        and pomitra.idpodetail='$row[idpodetail]'
        ";
  $sqlpo_detail = mysqli_query($koneksi, $query_detail);  
  $datapo_detail = mysqli_fetch_array($sqlpo_detail);
  $idpodetail = $datapo_detail['idpodetail'];                
                ?>                                   
<?php if ($idpodetail==""): ?>
                     
                                   
                <div class="form-group">
                  <label></label>
                    <label><?php echo $row['variant']; ?></label>
                    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                    <input type="number" min="0" name="jmlh[]" class="form-control" style="width:300px;" value=0 required>
                </div>  
<?php endif ?>                   
                        <?php
                          } ?>
                          <button type='submit' class='btn btn-primary' name='save'>Kirim</button>
                                  </form>
                    	
			                       <?php
			                        if(isset($_POST["save"])){
	                                 include "koneksi.php";
	                               date_default_timezone_set('Asia/Jakarta');
                                    $today = date("s");
					               $idadmin=$_SESSION["admin_mitra"]["idadmin"];
					               $idpodetail=$_POST["idpodetail"];
			                       $jmlh=$_POST["jmlh"];
			                         
			                       $jumlah_dipilih = count($jmlh);
                                    $subtotal=0;  
                                    $total=0;
                                    $jmlhakhir=0;
                                    
                                    
                                    for($x=0;$x<$jumlah_dipilih;$x++){
                                    	  $query_variant = "SELECT podetail.harga, podetail.idpo
                                                          FROM podetail
                                                          WHERE podetail.idpodetail='$idpodetail[$x]'";
                                                $sql_variant = mysqli_query($koneksi, $query_variant);  
                                                $data_variant = mysqli_fetch_array($sql_variant);
                                                $harga = $data_variant['harga'];
                                                $idpo = $data_variant['idpo'];  
                                                $total=$jmlh[$x]*$harga;
                                              $sql = "SELECT stok from pokategori where idpo='$idpo'";
						                      $query = $koneksi->query($sql);
							                  $sisa = $query->fetch_assoc();
								
                                             if($sisa['stok']>$jmlh[$x]){
        					                   $koneksi->query("insert into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) values
        					                   (null,'$idadmin','$idpoproduk','$idpo','$idpodetail[$x]','$jmlh[$x]','$total',
                                     '$invoice','$status','$tgl','$waktu')");
        					                   $koneksi->query("UPDATE pokategori set stok=stok-'$jmlh[$x]' where idpo='$idpo'");
        					                   	echo "<script>alert('data berhasil dikirim');</script>";
		                                        echo "<script>location='datapo.php?id=$idpoproduk';</script>";
        					                    }else{ 
        					                        $jmlh[$x]=0;
        					                        $total=0;
        					                   $koneksi->query("insert into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) values
        					                   (null,'$idadmin','$idpoproduk','$idpo','$idpodetail[$x]','$jmlh[$x]','$total','$invoice','$status','$tgl','$waktu')");
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
<script src="src/bootstrap-input-spinner.js"></script>
<script>
    $("input[type='number']").inputSpinner()
</script>
