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
 $jenis=$_GET['jenis'];
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

  <div class="col-2"><a href="pokaos"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
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
                            <span id="dots"></span>
                            <center>
                            <button onclick="myFunction()" id="myBtn" class="btn btn-success btn-sm">
                                <i class='fa fa-eye'></i> Preview</button> 
                                </center>
<br>                                
                            <div id="more" style="display:none;">
                          
         <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Lebaran</a></li>
          <li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Sahabat</a></li>
          <li><a data-toggle="tab" href="#menu2" class="nav-item nav-link">Tokoh Nusantara</a></li>
          <li><a data-toggle="tab" href="#menu3" class="nav-item nav-link">Adha Series</a></li>
        </ul> 
    <div class="tab-content">
        <div id="home" class="tab-pane fade active in show" role="tabpanel"> 
         <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#home1" class="nav-item nav-link active">Kaos Pendek</a></li>
          <li><a data-toggle="tab" href="#menu11" class="nav-item nav-link">Kaos Panjang</a></li>
          <li><a data-toggle="tab" href="#menu12" class="nav-item nav-link">Tunik</a></li>
        </ul>      
            <div class="tab-content">
                <div id="home1" class="tab-pane fade active in show" role="tabpanel">          
                    <iframe src="https://zizazu.id/preview/kaosdepan?jenis=Template" style="width: 100%;height:1100px;overflow:hidden;border: none;"></iframe>
                </div>
                <div id="menu11" class="tab-pane fade">
                    <iframe src="https://zizazu.id/preview/kaospanjang?jenis=Template" style="width: 100%;height:1100px;overflow:hidden;border: none;"></iframe>
                </div>
                <div id="menu12" class="tab-pane fade">
                    <iframe src="https://zizazu.id/preview/tunikdepan" style="width: 100%;height:1100px;overflow:hidden;border: none;"></iframe>
                </div>

                 
            </div>  

        </div>

        <div id="menu1" class="tab-pane fade">
         <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#home2" class="nav-item nav-link active">Kaos Pendek</a></li>
          <li><a data-toggle="tab" href="#menu21" class="nav-item nav-link">Tunik</a></li>
        </ul>      
            <div class="tab-content">
                <div id="home2" class="tab-pane fade active in show" role="tabpanel">          
                    <iframe src="https://zizazu.id/distributor/formposahabat.php?id=30" style="width: 100%;height:1100px;overflow:hidden;border: none;"></iframe>
                </div>
                <div id="menu21" class="tab-pane fade">
                    <iframe src="https://zizazu.id/distributor/formpotunik_sahabat.php?id=30" style="width: 100%;height:1000px;overflow:hidden;border: none;"></iframe> 
                </div>

                 
            </div>              
                   
        </div>

        <div id="menu2" class="tab-pane fade">
          <iframe src="https://zizazu.id/distributor/formpotonus.php?id=30" style="width: 100%;height:1100px;overflow:hidden;border: none;"></iframe> 
        </div> 


        <div id="menu3" class="tab-pane fade">
         <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#home3" class="nav-item nav-link active">Kaos Pendek</a></li>
          <li><a data-toggle="tab" href="#menu13" class="nav-item nav-link">Kaos Panjang</a></li>
          <li><a data-toggle="tab" href="#menu23" class="nav-item nav-link">Tunik</a></li>
        </ul>      
            <div class="tab-content">
                <div id="home3" class="tab-pane fade active in show" role="tabpanel">          
                    <iframe src="https://zizazu.id/preview/kaosdepan2?jenis=Template" style="width: 100%;height:1100px;overflow:hidden;border: none;"></iframe>
                </div>
                <div id="menu13" class="tab-pane fade">
                    <iframe src="https://zizazu.id/preview/kaospanjang2?jenis=Template" style="width: 100%;height:1100px;overflow:hidden;border: none;"></iframe>
                </div>
                <div id="menu23" class="tab-pane fade">    
                    <iframe src="https://zizazu.id/preview/tunik_depan_adha.php" style="width: 100%;height:1100px;overflow:hidden;border: none;"></iframe>          
                </div>                
            </div>  

        </div>                


    </div>        
                            </div>

                         <script>
                            function myFunction() {
                              var dots = document.getElementById("dots");
                              var moreText = document.getElementById("more");
                              var btnText = document.getElementById("myBtn");
                            
                              if (dots.style.display === "none") {
                                dots.style.display = "inline";
                                btnText.innerHTML = "<i class='fa fa-eye'></i> Preview"; 
                                moreText.style.display = "none";
                              } else {
                                dots.style.display = "none";
                                btnText.innerHTML = "<i class='fa fa-eye-slash'></i> Hide Preview"; 
                                moreText.style.display = "inline";
                              }
                            }
                            </script>
</div>                            
  <div class="container"> 
   <table id="myJudul" class="w3-table-all w3-centered">
<?php        
$namapo=$data['namapo'];
echo "
    <center> <h4> <b>Formulir Pemesanan $namapo </b> </h4> </center>";
?>
  </table>
    <form method="POST">
      <div class="">
  <div class='col-3'>
    <select name="jenis_filter" id="jenis_filter" class="form-control">
      <option value="0">Pilih Banyak Pcs.</option>
      <option value="1|<?= $jenis; ?>">1</option>
      <option value="2|<?= $jenis; ?>">2</option>
      <option value="3|<?= $jenis; ?>">3</option>
      <option value="4|<?= $jenis; ?>">4</option>
    </select>
    <p><strong><font color="red" size="5px">*</font></strong>Isi dengan jumlah Pcs.</p>  
  </div>
    
    <div class="col"  id="tabel_po" name="tabel_po"></div> 

<script type="text/javascript">

    $(document).ready(function(){

        $('#jenis_filter').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var jenis_filter = $('#jenis_filter').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_pokaos2.php',
                data :  'jenis_filter=' + jenis_filter+'|'+<?= $idpoproduk; ?>,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tabel_po").html(data);
                }
                
            });
        });

    $('#jenis_filter').ready(function(){

      //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
      var jenis_filter = $('#jenis_filter').val();

          $.ajax({
              type : 'GET',
              url : 'cek_pokaos2.php',
              data :  'jenis_filter=' + jenis_filter+'|'+<?= $idpoproduk; ?>,
          success: function (data) {

          //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
          $("#tabel_po").html(data);
        }
            });
    });



        
    });
</script> 
        <div class="col mt-4">                            
                        <?php 
                        // if($data['jumlah']<1){
                          echo "<button type='submit' class='btn btn-primary' name='save'>Kirim</button>";
                        // }else{
                        //         echo "# ";
                        //         }
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
        $jumlah_dipilih = count($idpodetail);

    // for($y=0;$y<$jumlah_dipilih;$y++){
    //   $total += $idpodetail[$y];
    // }
    //     if ($total>4) {
    //       echo "<script>alert('Jumlah barang melebihi 4 Pcs');</script>";
    //       echo "<script>location='formpokaos?id=$idpoproduk';</script>";
    //       return false;
    //     }        

        for($x=0;$x<$jumlah_dipilih;$x++){
  $namanya = addslashes($nama[$x]);          
  $query_variant = "SELECT podetail.harga, podetail.idpo
            FROM podetail
            WHERE podetail.idpodetail='$idpodetail[$x]'";
  $sql_variant = mysqli_query($koneksi, $query_variant);  
  $data_variant = mysqli_fetch_array($sql_variant);
  $harga = $data_variant['harga'];
  $idpo = $data_variant['idpo'];
  
  $total=$harga;
// echo "<script>alert('$idpo, $idpodetail[$x], $font[$x], $namanya');</script>";
        //   if($jmlh[$x]>0){

        $sql = $koneksi->query("INSERT INTO pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,custom,template,font,status,tgl,waktu) values
        (null,'$idadmin','$idpoproduk','$idpo','$idpodetail[$x]','1','$total','$invoice','$namanya','$template[$x]','$font[$x]','Belum DP',NOW(),'$waktu')");  


          
        //                 }
         }
         if ($sql) {
            echo "<script>alert('data berhasil dikirim');</script>";
            echo "<script>location='datapom?invoice=$invoice';</script>";
         }else{
            echo "<script>alert('data gagal dikirim');</script>";
            echo "<script>location='formpokaos2?id=$idpoproduk';</script>";
         }

  }       
?>

  </div>

<!--================ CONTAINER END=================-->



</body>
</html>

