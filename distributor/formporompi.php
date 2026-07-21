
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
  
<html lang="en">
<head>
<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" >
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


<div class="container panel panel-default">


 <iframe src="https://wnj.web.id/distributor/custom/rompi/" style="width: 100%;height: 600px;overflow:hidden;border: none;"></iframe>


<div class="col-md-12">

<h4><b>Formulir Pemesanan <?= $data['namapo']; ?></b></h4>
<!--   <b> Sisa Stock: </b>
              <?php
          
            $sql = "SELECT * from pokategori where idpoproduk='$idpoproduk'";
            $query = $koneksi->query($sql);
              while($stok = $query->fetch_assoc()){
                ?>             
                
<?php echo $stok['namakategori'] ?> (<?php echo $stok['stok'] ?>) |
  <?php $stok= $stok['stok']; } ?> -->
<br>
<hr> 

      <form method="POST">

        <?php
            //initialize total
              
            $total = 0;
            $index = 0;
            $number = 1;
            $sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk'  order by podetail.idpodetail desc";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>
        
              
                <div class="form-group">
                    <label><?php echo $row['variant']; ?></label>
                    
                    <br>

                    <input type="number" name="provinsi" onChange="tampil<?php echo $row['idpodetail']; ?>(this.value)" value="0" min="0"> <label>Pcs </label><br>
                    Masukan List Nama (per nama max. 25 karakter)
                    <br>
                    <br>
                    <div id="demo<?php echo $row['idpodetail']; ?>"></div>
<script type="text/javascript">
    function tampil<?php echo $row['idpodetail']; ?>(provinsi<?php echo $row['idpodetail']; ?>)
    {
var text = "";
var i;
for (i = 0; i < provinsi<?php echo $row['idpodetail']; ?>; i++) {
 text += "Custom. "+ (i+1) +"<?php

echo "<div class='form-group row'>";
echo "<div class='col-sm-6'>";
echo "<input type='hidden' name='idpo[]' value='$row[idpo]'>";
echo "<input type='hidden' name='idpodetail[]' value='$row[idpodetail]'>";
    echo "<input type='text' class='form-control' name='nama1[]' required maxlength='25' placeholder='Custom Nama Baris Ke 1'><br>";
    echo "<input type='text' class='form-control' name='nama2[]'  maxlength='25' placeholder='Custom Nama Baris Ke 2'><br>";
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
            if ($row['namakategori'] == "Black" or $row['namakategori'] == "Navy") {
echo "<input type='hidden' name='warna[]' value='Gold' readonly>";
            }else{
echo "<input type='hidden' name='warna[]' value='Black' readonly>";           
            }
            
echo "</div>"; 

  

  ?>";
}
document.getElementById("demo<?php echo $row['idpodetail']; ?>").innerHTML = text;
    }
  </script>                    

                    <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
                

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
  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
  $idpo= $_POST["idpo"];
  $idpodetail=$_POST["idpodetail"];
  $nama1=$_POST["nama1"];
  $nama2=$_POST["nama2"];
  $jmlh=$_POST["provinsi"];
  $harga=$_POST["harga"];
  $font=$_POST["font"];


  $sql = mysqli_query($koneksi, "SELECT idpomitra FROM pomitra order by idpomitra desc limit 1");
  $data = mysqli_fetch_array($sql);
  $no=$data['idpomitra'];
  $ab=1;
  $nobaru=$no+$ab;


$jumlah_dipilih = count($jmlh);
$jmlhcustom=count($nama1);
$subtotal=0;  
$total=0;
$jmlhakhir=0;


for($x=0;$x<$jmlhcustom;$x++){
  $total=$jmlh[$x]*$harga[$x];
  $tot=$total;
  $jmlhakhir+=$jmlhakhir+$jmlh[$x];
  $tot=0;
  $namanya1 = addslashes($nama1[$x]);
  $namanya2 = addslashes($nama2[$x]);


// $sql = "SELECT stok from pokategori where idpo='$idpo[$x]'";
// $query = $koneksi->query($sql);
// $sisa = $query->fetch_assoc();


// if($sisa['stok']>=1){
  
      $sqlinsert = $koneksi->query("INSERT into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,custom,font,total,invoice,status,tgl) 
      	values
      (null,'$idadmin','$idpoproduk','$idpo[$x]','$idpodetail[$x]','1','$namanya1|$namanya2','$font[$x]','$harga[$x]',
      'RM$idpoproduk-$idadmin-$nobaru','Belum DP',NOW())");

  // $sqlstok = $koneksi->query("update pokategori set stok=stok-1 where idpo='$idpo[$x]'");    
      
// }

// if($sisa['stok']==0){
// echo "<script>alert('Stok barang untuk Nama Custom ($namanya1) sudah habis. Terimakasih..');</script>";
// if ($sqlstok) {
//   echo "<script>location='datapom.php?id=$idpoproduk&invoice=RM$idpoproduk-$idadmin-$nobaru';</script>";  
// }
// echo "<script>location='listnewpo.php';</script>";
// }

// if($sisa['stok']<1){

//   echo "<script>alert('Stok kami untuk Nama Custom ($namanya1) tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
//   if ($sqlstok) {
//   echo "<script>location='datapom.php?id=$idpoproduk&invoice=RM$idpoproduk-$idadmin-$nobaru';</script>";  
// }
//   echo "<script>location='formporompi.php?id=$idpoproduk';</script>";
// }

}
if ($sqlinsert) {
  echo "<script>location='datapom.php?id=$idpoproduk&invoice=RM$idpoproduk-$idadmin-$nobaru';</script>";  
}



}
?>
                              
    </div>

</div>