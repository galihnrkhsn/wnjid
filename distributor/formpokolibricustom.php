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
 echo "<center> <h4> <b>Formulir Pemesanan $namapo </b> </h4> </center>";
// if($data['jumlah']<1){
 
// //$stok=$data['stok'];
// echo "
//     <center> <h4> <b>Formulir Pemesanan $namapo </b> </h4> </center>";
// }else{
// echo "
//     <tr>
      
//     </tr>
//     <center><b>Anda sudah mengisi Formulir $namapo , Klik Tombol Dibawah ini Jika ingin melihat atau merevisi Invoice, 
//                                 </b><br><br>";?>
       <!-- <a class='btn btn-info' href="detailpo.php?idpoproduk=<?php echo $data['idpoproduk'];?>&namapo=<?php echo $data['namapo']; ?>"> INVOICE</a> </center> -->
<?php //  }
?>
  </table>
  </div><br><br>
 
  <div class="container panel panel-default">
 
            <div class="panel-body">
            <div class="row">

            <div class="col-md-12">

      <form method="POST" name="myForm">

        <?php
            //initialize total
              $idpoproduk = $_GET['id'];
            $total = 0;
            $index = 0;
            $number = 1;
            $sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail asc";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>
        
              
                <div class="form-group">
                    <label><?//= $number++ ; ?> <?php echo $row['variant']; ?></label>
                    
                    <br>

                    <input type="number" name="provinsi" onChange="tampil<?php echo $row['idpodetail']; ?>(this.value)" value="0" min="0"> <label>Pcs </label><br>
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
echo "<div class='col-sm-6 input-group'>";
echo "<input type='hidden' name='idpo[]' value='$row[idpo]'>";
echo "<input type='hidden' name='idpodetail[]' value='$row[idpodetail]'>";
echo "<input type='hidden' name='harga[]' value='$row[harga]''>";
    echo "<input type='number' class='form-control' name='nama[]' min='0' placeholder='Tulis custom PB Dress'><span style='color:red;'>*Isi kolom jika ingin custom ukuran PB Dress (cm)</span><br><br>";

    echo "</div>"; 
echo "<div class='col-sm-6 mb-3 mb-sm-0'>";
echo "<select class='form-control' name='font[]' required>";
      echo "<option value='' disabled='disabled' selected>~Pilih Ukuran Khimar~</option>";            
      echo "<option value='M'>M</option>";
      echo "<option value='L'>L</option>";
      echo "<option value='XL'>XL</option>";

    echo "</select>";      
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
  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
  $idpo= $_POST["idpo"];
  $idpodetail=$_POST["idpodetail"];
  $nama=$_POST["nama"];
  $font=$_POST["font"];
  $jmlh=$_POST["provinsi"];
  $harga=$_POST["harga"];

  $sql = mysqli_query($koneksi, "SELECT idpomitra FROM pomitra order by idpomitra desc limit 1");
  $data = mysqli_fetch_array($sql);
  $no=$data['idpomitra'];
  $ab=1;
  $nobaru=$no+$ab;


$jumlah_dipilih = count($jmlh);
$jmlhcustom=count($nama);
$subtotal=0;  
$total=0;
$jmlhakhir=0;


for($x=0;$x<$jmlhcustom;$x++){
  $total=$jmlh[$x]*$harga[$x];
  $tot=$total;
  $jmlhakhir+=$jmlhakhir+$jmlh[$x];
  $tot=0;

      $sqlinput = $koneksi->query("insert into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,custom,font,total,invoice,status,tgl) values
      (null,'$idadmin','$idpoproduk','$idpo[$x]','$idpodetail[$x]','1','$nama[$x]','$font[$x]','$harga[$x]',
      'LUX$idpoproduk-$idadmin-$nobaru','Belum DP',NOW())");
      


}

if ($sqlinput) {
  echo "<script>alert('data berhasil dikirim ');</script>";
  echo "<script>location='datapom.php?id=$idpoproduk&invoice=LUX$idpoproduk-$idadmin-$nobaru';</script>";  
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
