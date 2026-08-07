<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

  $idpoproduk = 62;
  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
  $query = "SELECT COUNT(*) as jumlah,poproduk.idpoproduk,poproduk.namapo,poproduk.status FROM poproduk inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk WHERE poproduk.idpoproduk='$idpoproduk' AND pomitra.idmitra='$idadmin' AND SUBSTRING(podetail.variant, 1, 6)='Custom' ";
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
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>


  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">  

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

?>
  </table>
  </div><br><br>
 
  <div class="container panel panel-default">
      | <b> Sisa Stock: </b>
              <?php
            //initialize total
              $idpoproduk = 62;
          
            $sql = "Select * from pokategori where idpoproduk='$idpoproduk'";
            $query = $koneksi->query($sql);
              while($stok = $query->fetch_assoc()){
                ?>
                
<?php echo $stok['namakategori'] ?> (<?php echo $stok['stok'] ?>) |
  <?php $stok= $stok['stok']; } ?>
 
            <div class="panel-body">
            <div class="row">

            <div class="col-md-6">
                                  <br><br>
                                  <label>Contoh Input : </label><br>
                      <img src="img/contoh input miki.png" width="100%"><br>
                      
                      <label>Contoh Font Teks : </label><br>
                  <img src="img/mikifont.jpeg" width="100%"><br><br>
                                  <br><br>
      <form method="POST">

        <?php
            //initialize total
              $idpoproduk = 62;
            $total = 0;
            $index = 0;
            $number = 1;
            $sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by pokategori.idpo asc";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>
        
              
                <div class="form-group">
                    <label><?php echo $row['variant']; ?></label>
                    <input type="hidden" name="idpo[]" value="<?php echo $row['idpo']; ?>">
                    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>"><br>
                    <input type="text" name="jmlh[]"  style="width:50px;"> <label>Pcs </label><br>
                    <?php if(substr($row['variant'],0,6)=='Custom'){ ?> 
                    Masukan List Nama (per nama max. 12karakter) <textarea name="custom[]" class="form-control"></textarea>
                	<?php } else{ ?>
                	<input type="hidden" name="custom[]" value="-">
                	<?php } ?>
                    <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
                </div><hr>  
                                                    
                        <?php $namapo2=$row['namapo'];
                        $id=$row['idpoproduk'];
                              $idadmin=$_SESSION["admin_mitra"]["idadmin"]; 
                              } ?>
     
                        <button type='submit' class='btn btn-primary' name='save'>Kirim</button>

                      </form>
                      
                      
                             <?php
                              if(isset($_POST["save"])){
                                   include "koneksi.php";
                                   date_default_timezone_set('Asia/Jakarta');
                                    $today = date("s");
                         $idadmin=$_SESSION["admin_mitra"]["idadmin"];
                         $idpo= $_POST["idpo"];
                         $idpodetail=$_POST["idpodetail"];
                             $jmlh=$_POST["jmlh"];
                             $custom=$_POST["custom"];
                             $harga=$_POST["harga"];

                             $sql = mysqli_query($koneksi, "SELECT idpomitra FROM pomitra order by idpomitra desc limit 1");
								   $data = mysqli_fetch_array($sql);
								   $no=$data['idpomitra'];
								   $ab=1;
								   $nobaru=$no+$ab;

                               
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
                                    $sql = "Select stok from pokategori where idpo='$idpo[$x]'";
                                  $query = $koneksi->query($sql);
                                $sisa = $query->fetch_assoc();
                
                                             if($sisa['stok']>$jmlh[$x]){
                                      $koneksi->query("insert into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,custom,total,invoice,status,tgl) values
                                     (null,'$idadmin','$idpoproduk','$idpo[$x]','$idpodetail[$x]','$jmlh[$x]','$custom[$x]','$total','MH$idpoproduk-$idadmin-$nobaru','Belum DP',NOW())");
                                       $koneksi->query("update pokategori set stok=stok-'$jmlh[$x]' where idpo='$idpo[$x]'");
                                    // $koneksi->query("update pokategori set stok=stok-'$jmlh[$x]' where idpo='$idpo'");
                                      echo "<script>alert('data berhasil dikirim');</script>";
                                            echo "<script>location='datapom.php?invoice=MH$idpoproduk-$idadmin-$nobaru&id=$idpoproduk';</script>";
                                      }else{ 
                                          $jmlh[$x]=0;
                                          $total=0;
                                     $koneksi->query("insert into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,custom,total,invoice,status,tgl) values
                                     (null,'$idadmin','$idpoproduk','$idpo[$x]','$idpodetail[$x]','$jmlh[$x]','$custom[$x]','$total','MH$idpoproduk-$idadmin-$nobaru','Belum DP',NOW())");
                                          echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
                                            echo "<script>location='datapom.php?invoice=MH$idpoproduk-$idadmin-$nobaru&id=$idpoproduk';</script>";
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
