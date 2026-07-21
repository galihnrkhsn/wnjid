<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

  $idpoproduk = $_GET["id"];
  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
  $query = "SELECT COUNT(*) as jumlah,poproduk.idpoproduk,poproduk.namapo,poproduk.status FROM poproduk inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk WHERE poproduk.idpoproduk='$idpoproduk' AND pomitra.idmitra='$idadmin'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);
  ?> 
  
<html lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WNJ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  
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

?>
  </table>
  </div>

  <br><br>
 
  <div class="container container panel panel-default">
                      <label>Contoh Font Teks : </label><br>
                  <img src="img/mikifont.jpeg" width="50%"><br><br>
<label>Note : </label><br>
<label>
Warna Font Teks ada 2 Black & Gold. <br>
Warna Font Teks Black : <br>
Untuk Miki Hat<br>
<!-- - Cream<br> -->
- Silver<br>
<!-- - White<br> -->
- Grey<br>
<br>
Warna Font Teks Gold :<br>
Untuk Miki Hat<br>
- Black<br>
- Maroon<br>
<!-- - Army<br>
- Brown<br>
- Emerald<br> -->
- Navy<br>

</label>
<br>

    | <b> Sisa Stock: </b>
 <div class="row">
              <?php
            //initialize total
              $idpoproduk = $_GET['id'];
          
            $sql = "SELECT * from pokategori where idpoproduk='$id' ORDER BY namakategori";
            $query = $koneksi->query($sql);
              while($stok = $query->fetch_assoc()){
                ?>
<div class="row">
<div class="col-4">
   <?php echo $stok['namakategori']; ?>
</div>
<div class="col-4">
    (<?php echo $stok['stok']; ?>)
</div>
</div>
<br>
<?php } ?>
</div>

<hr>

      <form method="POST">

        <?php

            $sql = "SELECT * FROM poproduk 
                    inner join pokategori 
                    inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo 
                    where poproduk.idpoproduk='$idpoproduk'  
                    AND podetail.variant LIKE '%Custom%'
            order by podetail.idpodetail desc";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>
        
              
                <div class="form-group">
                    <label><?php echo $row['variant']; ?></label>
                    
                    <br>
                    <div class="col-3">
                    <input type="number" name="provinsi" class="form-control" onChange="tampil<?php echo $row['idpodetail']; ?>(this.value)" value="0"> <label>Pcs </label><br>
                    Masukan List Nama (per nama max. 15 karakter)
                    </div>
                    <br>
                    <br>
                    <div id="demo<?php echo $row['idpodetail']; ?>"></div>
<script type="text/javascript">
    function tampil<?php echo $row['idpodetail']; ?>(provinsi<?php echo $row['idpodetail']; ?>)
    {
var text = "";
var i;
for (i = 0; i < provinsi<?php echo $row['idpodetail']; ?>; i++) {
 text += "No. "+ (i+1) +"<?php

echo "<div class='form-group row'>";
echo "<div class='col-sm-6'>";
echo "<input type='hidden' name='idpodetail[]' value='$row[idpodetail]'>";
    echo "<input type='text' class='form-control' name='nama[]' required maxlength='15' placeholder='Tulis Nama Custom'><br>";
    echo "</div>"; 
echo "<div class='col-sm-6 mb-3 mb-sm-0'>";
echo "<select class='form-control' name='font[]' required>";
      echo "<option value='' disabled='disabled' selected>~Pilih Jenis Huruf~</option>";            
      echo "<option value='a amazing mother'>a amazing mother</option>";
      echo "<option value='a awal Ramadan'>a awal Ramadan</option>";
      echo "<option value='blackjack'>blackjack</option>";
      echo "<option value='halaney demo'>halaney demo</option>";

    echo "</select>";      
echo "</div>"; 
            if ($row['namakategori'] == "Cream" or $row['namakategori'] == "Silver" or $row['namakategori'] == "White") {
echo "<input type='hidden' name='warna[]' value='Black' readonly>";
            }else{
echo "<input type='hidden' name='warna[]' value='Gold' readonly>";           
            }
            
echo "</div>"; 

  

  ?>";
}
document.getElementById("demo<?php echo $row['idpodetail']; ?>").innerHTML = text;
    }
  </script>                    
                </div>
                <hr>        
<?php } ?>
   
                        <button type='submit' class='btn btn-primary' name='save'>Kirim</button>

                      </form>
</div>                      
                      
<?php
if(isset($_POST["save"])){
  include "koneksi.php";
  date_default_timezone_set('Asia/Jakarta');
  $today = date("s");
  $waktu = date("H:i:s");
  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
  $idpodetail=$_POST["idpodetail"];
  $nama=$_POST["nama"];
  $jmlh=$_POST["provinsi"];
  $font=$_POST["font"];

  $sql = mysqli_query($koneksi, "SELECT idpomitra FROM pomitra order by idpomitra desc limit 1");
  $data = mysqli_fetch_array($sql);
  $no=$data['idpomitra'];
  $ab=1;
  $nobaru=$no+$ab;

$invoice = 'MH'.$idpoproduk.'-'.$idadmin.'-'.$nobaru;

$jumlah_dipilih = count($jmlh);

$jmlhcustom=count($nama);
$subtotal=0;  
$total=0;
$jmlhakhir=0;



for($x=0;$x<$jmlhcustom;$x++){
  $jmlhakhir+=$jmlhakhir+$jmlh[$x];
  $tot=0;
  $namanya = addslashes($nama[$x]);

  $query_variant = "SELECT podetail.harga, podetail.idpo
            FROM podetail
            WHERE podetail.idpodetail='$idpodetail[$x]'";
  $sql_variant = mysqli_query($koneksi, $query_variant);  
  $data_variant = mysqli_fetch_array($sql_variant);
  $harga = $data_variant['harga'];
  $idpo = $data_variant['idpo'];

$sql = "SELECT stok from pokategori where idpo='$idpo'";
$query = $koneksi->query($sql);
$sisa = $query->fetch_assoc();



if($sisa['stok']>=1){
  
      $sqlinsert = $koneksi->query("insert into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,custom,font,total,invoice,status,tgl,waktu) values
      (null,'$idadmin','$idpoproduk','$idpo','$idpodetail[$x]','1','$namanya','$font[$x]','$harga',
      '$invoice','Belum DP',NOW(),'$waktu')");

  $sqlstok = $koneksi->query("UPDATE pokategori set stok=stok-1 where idpo='$idpo[$x]'");    

             
}

if($sisa['stok']<=0){
echo "<script>alert('Stok barang untuk Nama Custom ($namanya) sudah habis. Terimakasih..');</script>";
}

}




if ($sqlstok) {
  echo "<script>location='datapom.php?id=$idpoproduk&invoice=$invoice';</script>";  
}else{
  echo "<script>location='formpomikicustomstock?id=$idpoproduk';</script>";    
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
