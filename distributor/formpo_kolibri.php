<?php
session_start();

include 'koneksi.php'; 


// if(!isset($_SESSION["admin_mitra"])){
//   echo "<script>alert('anda harus login terlebih dahulu');</script>";
//   echo "<script>location='login2.php';</script>";
//   header('location:login2.php');
//   exit();
// }

 $invoice = $_GET['invoice'];
  $query = "SELECT poproduk.idpoproduk,poproduk.namapo,podropship.invoice 
            FROM poproduk 
            JOIN podropship on podropship.idpoproduk = poproduk.idpoproduk
            WHERE podropship.invoice='$invoice'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);

$idpoproduk = $data['idpoproduk'];

//   $idadmin=$_GET['idadmin'];
$idadmin=$_SESSION["admin_mitra"]["idadmin"];
 
  ?> 
 <title>WNJ</title>
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
<br><br><br>

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
      <center> <h4> <b>Formulir Pemesanan <br> <?= $data['namapo']; ?>  <?= $data['invoice']; ?></b> </h4> </center>


    <form method="POST">
      <div class="">
        <?php
        $no=1;
            $sql = "SELECT * FROM pokategori  
                    WHERE pokategori.idpoproduk='$idpoproduk'
                    GROUP BY pokategori.idpo asc
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
                url : 'cek_pokategori.php',
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
              url : 'cek_pokategori.php',
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
          <button type='submit' class='btn btn-primary' name='save'>Kirim</button>
        </div>
      </div>
    </form>

<?php
  if(isset($_POST["save"])){
    date_default_timezone_set('Asia/Jakarta');
    $waktu = date("H:i:s");
    $idpodetail=$_POST["idpodetail"];
    $jmlh=$_POST["jmlh"];


        $jumlah_dipilih = count($jmlh);

        for($x=0;$x<$jumlah_dipilih;$x++){
  $query_variant = "SELECT podetail.harga, podetail.idpo
            FROM podetail
            WHERE podetail.idpodetail='$idpodetail[$x]'";
  $sql_variant = mysqli_query($koneksi, $query_variant);  
  $data_variant = mysqli_fetch_array($sql_variant);
  $harga = $data_variant['harga'];
  $idpo = $data_variant['idpo'];
  
  $total=$jmlh[$x]*$harga;

          if($jmlh[$x]>0){

$ambil=$koneksi->query("SELECT idpodetail, invoice FROM pomitra WHERE idpodetail='$idpodetail[$x]' and invoice ='$invoice' ");
$datacocok=$ambil->num_rows;
if($datacocok>=1){
      $sqlnya = $koneksi->query("UPDATE pomitra set jumlah=jumlah + '$jmlh[$x]' WHERE idpodetail='$idpodetail[$x]' and invoice ='$invoice'");
}
else{
        $sql = $koneksi->query("INSERT INTO pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) values
        (null,'$idadmin','$idpoproduk','$idpo','$idpodetail[$x]','$jmlh[$x]','$total','$invoice','Belum DP',NOW(),'$waktu')");  
}

          
                        }
         }
         if ($sql) {
            echo "<script>alert('data berhasil dikirim');</script>";
            echo "<script>location='datapokolibri?invoice=$invoice&idadmin=$idadmin';</script>";
         }else{
            echo "<script>alert('data gagal dikirim');</script>";
            echo "<script>location='formpo_kolibri?id=$idpoproduk&idadmin=$idadmin';</script>";
         }

  }       
?>

  </div>

<!--================ CONTAINER END=================-->



</body>
</html>

