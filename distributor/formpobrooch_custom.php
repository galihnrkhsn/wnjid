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
 echo "<center> <h4> <b>Formulir Pemesanan $namapo </b> </h4> </center>";
?>
  </table>
  </div><br><br>
 
  <div class="container panel panel-default">

 
            <div class="panel-body">
            <div class="row">

            <div class="col-md-12">

      <form method="POST" name="myForm">

        <?php
            $number=1;
              $idpoproduk = $_GET['id'];
            $sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail asc";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>
        
              
                <div class="form-group">
                    <label><?php echo $row['variant']; ?></label>
                    
                    <br>
                    <label>Jumlah Custom karakter Huruf/Angka</label>
                    <br>
                    <input type="number" min="0" name="provinsi" onChange="tampil<?php echo $row['idpodetail']; ?>(this.value)" value="0" class='form-control' style="width: 100px"><br>
                    <!-- Masukan List Nama <br>
                    (per nama max. 10 karakter & Tidak boleh ada spasi, spasi diganti jadi titik(.) atau underscore(_)) -->
                    <br>
                    <br>
                    <div id="demo<?php echo $row['idpodetail']; ?>"></div>
<script type="text/javascript">
    function tampil<?php echo $row['idpodetail']; ?>(provinsi<?php echo $row['idpodetail']; ?>)
    {
var text = "";
var i;
for (i = 0; i < provinsi<?php echo $row['idpodetail']; ?>; i++) {
 text += "Karakter. "+ (i+1) +"<?php

echo "<div class='form-group row'>";
echo "<div class='col-sm-4'>";
echo "<input type='hidden' name='idpodetail[]' value='$row[idpodetail]'>";
echo "<input type='text' class='form-control' name='nama[]' required maxlength='1' style='text-transform:uppercase' placeholder='Tulis Custom Karakter huruf/angka'><br>";
echo "</div>";
echo "<div class='col-sm-2'>";
echo "<input type='number' class='form-control' name='jumlah[]' min='0' required placeholder='Qty/pcs'><br>";
echo "</div>";  
            
echo "</div>"; 

  

  ?>";
}
document.getElementById("demo<?php echo $row['idpodetail']; ?>").innerHTML = text;
    }

   
  </script>                    

                    
                

                </div>
                <hr>  
                                                    
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
  $waktu = date("H:i:s");
  $idadmin=$_SESSION["admin_mitra"]["idadmin"];

  $idpodetail=$_POST["idpodetail"];
  $nama=$_POST["nama"];
  $jumlah=$_POST["jumlah"];
  $jmlh=$_POST["provinsi"];


  $sql = mysqli_query($koneksi, "SELECT idpomitra FROM pomitra order by idpomitra desc limit 1");
  $data = mysqli_fetch_array($sql);
  $no=$data['idpomitra'];
  $ab=1;
  $nobaru=$no+$ab;


$jumlah_dipilih = count($jmlh);
$jmlhcustom=count($nama);


for($x=0;$x<$jmlhcustom;$x++){
  $namanya = strtoupper(addslashes($nama[$x]));

$query_variant = "SELECT podetail.harga, podetail.idpo
                  FROM podetail
                  WHERE podetail.idpodetail='$idpodetail[$x]'";
  $sql_variant = mysqli_query($koneksi, $query_variant);  
  $data_variant = mysqli_fetch_array($sql_variant);
  $harga = $data_variant['harga'];
  $idpo = $data_variant['idpo'];  
  $total=$jumlah[$x]*$harga;


  if (preg_match("/^\S{1,}$/", $namanya)) {
      $sqlinsert = $koneksi->query("insert into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,custom,total,invoice,status,tgl,waktu) values
      (null,'$idadmin','$idpoproduk','$idpo','$idpodetail[$x]','$jumlah[$x]','$namanya','$total',
      'B$idpoproduk-$idadmin-$nobaru','Belum DP',NOW(),'$waktu')");

  
  }
  if(!preg_match("/^\S{1,}$/", $namanya)){
               echo "<script>alert('Nama Custom ($namanya) tidak boleh mengandung spasi atau karakter tertentu ...! ');</script>";
             }
             



}

if ($sqlinsert) {
  echo "<script>location='https://wnj.web.id/distributor/datapom.php?id=$idpoproduk&invoice=B$idpoproduk-$idadmin-$nobaru';</script>";  
}
    
              

}
?>
                              
    </div>

  </div>
</div>  </div>
</div>
<script src="src/bootstrap-input-spinner.js"></script>
<script>
    $("input[type='number']").inputSpinner()
</script>
