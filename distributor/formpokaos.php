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
 <title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| Wanoja </title>
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

  <div class="col-2"><a href="listnewpo"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
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
  
<!--================ CONTAINER =================-->

  <div class="container"> 
   <table id="myJudul" class="w3-table-all w3-centered">
<?php        
$namapo=$data['namapo'];
 $idadmin=$_SESSION["admin_mitra"]["idadmin"]; 
 $id=$data['idpoproduk'];
if($data['jumlah']<1){
 
//$stok=$data['stok'];
echo "
    <center> <h4> <b>Formulir Reward $namapo </b> </h4> </center>";
}else{
echo "
    <tr>
      
    </tr>
    <center><b>Anda sudah mengisi Formulir $namapo , Klik Tombol Dibawah ini Jika ingin melihat atau merevisi Invoice, 
                                </b><br><br>
      <a class='btn btn-info' href='detailpo?id=$id'> INVOICE</a> </center>";
    }
?>
  </table>

<?php include "preview.php"; ?>

    <form method="POST">
      <div class="">
        <?php
        $no=1;
            $sql = "SELECT * FROM pokategori  
                    WHERE pokategori.idpoproduk='$idpoproduk'
                    GROUP BY pokategori.idpo desc
                    ";
            $query = $koneksi->query($sql);
            while($row = $query->fetch_assoc()){
        ?>
        <hr>
<div class='col-6'>
		<label><?php echo $row['namakategori']; ?></label>
</div>
	<div class='col-3'>
		<input type="number" name="jenis_filter<?= $no; ?>" id="jenis_filter<?= $no; ?>" class="form-control" min='0'>
	</div>
    <p><strong><font color="red" size="5px">*</font></strong>Isi dengan jumlah variant</p>  
    <div class="col"  id="tabel_po<?= $no; ?>" name="tabel_po<?= $no; ?>"></div> 

<script type="text/javascript">

    $(document).ready(function(){

        $('#jenis_filter<?= $no; ?>').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var jenis_filter<?= $no; ?> = $('#jenis_filter<?= $no; ?>').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_pokaos.php',
                data :  'jenis_filter<?= $no; ?>=' + jenis_filter<?= $no; ?>+'|'+<?= $row['idpo']; ?>,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tabel_po<?= $no; ?>").html(data);
                }
                
            });
        });

    $('#jenis_filter<?= $no; ?>').ready(function(){

      //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
      var jenis_filter<?= $no; ?> = $('#jenis_filter<?= $no; ?>').val();

          $.ajax({
              type : 'GET',
              url : 'cek_pokaos.php',
              data :  'jenis_filter<?= $no; ?>=' + jenis_filter<?= $no; ?>+'|'+<?= $row['idpo']; ?>,
          success: function (data) {

          //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
          $("#tabel_po<?= $no; ?>").html(data);
        }
            });
    });



        
    });
</script> 
         <?php $no=$no+1; ?>        
        <?php } ?> 
        <div class="col mt-4">                            
                        <?php if($data['jumlah']<1){
                          echo "<button type='submit' class='btn btn-primary' name='save'>Kirim</button>";
                        }else{
                                echo "# ";
                                }
                          ?>
        </div>
      </div>
    </form>

<?php
  if(isset($_POST["save"])){
    date_default_timezone_set('Asia/Jakarta');
    $waktu = date("H:i:s");
    $idpodetail=$_POST["idpodetail"];
    $nama=$_POST["nama"];
    $jmlh=$_POST["jmlh"];
    $font=$_POST["font"];
    $template=$_POST["template"];
    $idadmin=$_SESSION["admin_mitra"]["idadmin"];

$invoice = 'K'.$idpoproduk.'-'.$idadmin;
        $jumlah_dipilih = count($jmlh);

    for($y=0;$y<$jumlah_dipilih;$y++){
      $total += $jmlh[$y];
    }
        if ($total>4) {
          echo "<script>alert('Jumlah barang melebihi 4 Pcs');</script>";
          echo "<script>location='formpokaos?id=$idpoproduk';</script>";
          return false;
        }        

        for($x=0;$x<$jumlah_dipilih;$x++){
  $namanya = addslashes($nama[$x]);          
  $query_variant = "SELECT podetail.harga, podetail.idpo
            FROM podetail
            WHERE podetail.idpodetail='$idpodetail[$x]'";
  $sql_variant = mysqli_query($koneksi, $query_variant);  
  $data_variant = mysqli_fetch_array($sql_variant);
  $harga = $data_variant['harga'];
  $idpo = $data_variant['idpo'];
  
  $total=$jmlh[$x]*$harga;

          if($jmlh[$x]>0){

        $sql = $koneksi->query("INSERT INTO pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,custom,template,font,status,tgl,waktu) values
        (null,'$idadmin','$idpoproduk','$idpo','$idpodetail[$x]','$jmlh[$x]','$total','$invoice','$namanya','$template[$x]','$font[$x]','Belum DP',NOW(),'$waktu')");  


          
                        }
         }
         if ($sql) {
            echo "<script>alert('data berhasil dikirim');</script>";
            echo "<script>location='datapom?invoice=$invoice';</script>";
         }else{
            echo "<script>alert('data gagal dikirim');</script>";
            echo "<script>location='formpokaos?id=$idpoproduk';</script>";
         }

  }       
?>

  </div>

<!--================ CONTAINER END=================-->



</body>
</html>

