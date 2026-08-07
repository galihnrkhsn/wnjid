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
if($data['jumlah']<1){
 
//$stok=$data['stok'];
echo "
    <center> <h4> <b>Formulir Pemesanan $namapo </b> </h4> </center>";
}else{
echo "
    <center> <h4> <b>Formulir Pemesanan $namapo </b> </h4> </center>
    <center><b>Anda sudah mengisi Formulir $namapo , Silahkan isi kembali formulir jika ingin merevisi Invoice, 
                                </b><br><br>
       </center>";
    }
      // <a class='btn btn-info' href='datapo.php?idmitra=$idadmin&id=$id'> INVOICE</a>
?>
  </table>
  </div>
 
  <div class="container panel panel-default">
     
 
            <div class="panel-body">
            <div class="row">
            <div class="col-md-6">
<!-- <label>Note : </label><br>
<label>

  <table>
    <tr>
      <th>Dewasa</th>
      <th>Anak</th>
    </tr>
    <tr>
      <td>
• Seri 1 <hr>
- Blush red<br>
- Misty grey<br>
- Black<br>
- Summer blue &nbsp;&nbsp;&nbsp;<br>
- Taro<br>
<hr>
• Seri 2<hr>
- Brick red<br>
- Dark grey<br>
- Black<br>
- Green<br>
- Taro           
      </td>
      
      <td>
• Seri 1 <hr>
- Red chili <br>
- Grey<br>
- Misty grey<br>
- Teal<br>
- Maroon<br>
<hr>
• Seri 2<hr>
- Apricot<br>
- Grey<br>
- Misty Grey<br>
- Teal<br>
- Maroon    <br>    
      </td>
    </tr>
  </table>
 -->


<?php 
  $idpoproduk = $_GET['id'];
$query = "SELECT *,COUNT(idpo) as jumlahidpo FROM pokategori WHERE idpoproduk='$idpoproduk' order by idpo asc";
  $sqlkategori = mysqli_query($koneksi, $query);  
  $datakategori = mysqli_fetch_array($sqlkategori); 
  $idponya = $datakategori['idpo'];
  $jumlahidponya = $datakategori['jumlahidpo'];  

 ?>
</label>
<br>             
<br> 

                                
      <form method="POST">
    
    <div style="padding: 0 15px;">

         
<p>Gamis Anak</p>          
    <select class="form-control " name="idpodetail[]" required>
     <option>
        Pilih Variant Gamis Anak
      </option> 
              <?php
            //initialize total
              $idpoproduk = $_GET['id'];
            $total = 0;
            $index = 1;
            $sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail LIMIT 60";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>

      <option value="<?php echo $row['idpodetail']; ?>"><?php echo $row['variant']; ?>

      </option>
                              <?php 
                              } ?>
    </select>

<br>

<p>Gamis Dewasa</p>          
    <select class="form-control " name="idpodetail[]" required>
     <option>
        Pilih Variant Gamis Dewasa
      </option> 
              <?php
            //initialize total
              $idpoproduk = $_GET['id'];
            $total = 0;
            $index = 1;
            $sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail LIMIT 60,60";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>

      <option value="<?php echo $row['idpodetail']; ?>"><?php echo $row['variant']; ?>

      </option>
                              <?php 
                              } ?>
    </select>  

<br>

<p>Bergo Anak</p>          
    <select class="form-control " name="idpodetail[]" required>
     <option>
        Pilih Variant Bergo Anak
      </option> 
              <?php
            //initialize total
              $idpoproduk = $_GET['id'];
            $total = 0;
            $index = 1;
            $sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail LIMIT 120,30";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>

      <option value="<?php echo $row['idpodetail']; ?>"><?php echo $row['variant']; ?>

      </option>
                              <?php 
                              } ?>
    </select>  

<br>

<p>Bergo Dewasa</p>          
    <select class="form-control " name="idpodetail[]" required>
     <option>
        Pilih Variant Bergo Dewasa
      </option> 
              <?php
            //initialize total
              $idpoproduk = $_GET['id'];
            $total = 0;
            $index = 1;
            $sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail LIMIT 150,50";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>

      <option value="<?php echo $row['idpodetail']; ?>"><?php echo $row['variant']; ?>

      </option>
                              <?php 
                              } ?>
    </select> 

<br>
<p>Koko Anak</p>          
    <select class="form-control " name="idpodetail[]" required>
     <option>
        Pilih Variant Koko Anak
      </option> 
              <?php
            //initialize total
              $idpoproduk = $_GET['id'];
            $total = 0;
            $index = 1;
            $sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail LIMIT 200,60";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>

      <option value="<?php echo $row['idpodetail']; ?>"><?php echo $row['variant']; ?>

      </option>
                              <?php 
                              } ?>
    </select>                

<br>

<p>Koko Dewasa</p>          
    <select class="form-control " name="idpodetail[]" required>
     <option>
        Pilih Variant Koko Dewasa
      </option> 
              <?php
            //initialize total
              $idpoproduk = $_GET['id'];
            $total = 0;
            $index = 1;
            $sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail LIMIT 260,60";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>

      <option value="<?php echo $row['idpodetail']; ?>"><?php echo $row['variant']; ?>

      </option>
                              <?php 
                              } ?>
    </select>  

               

<br>
<br>
      
    <?php
                          

                          echo "<button type='submit' class='btn btn-primary' name='save'>Kirim</button>";
           // if($data['jumlah']<1){
           //              }else{
           //                      echo "# ";
           //                      }
                          ?>


                      </form>
      
                      
<?php
  if(isset($_POST["save"])){
    include "koneksi.php";
    date_default_timezone_set('Asia/Jakarta');
    $today = date("s");
    $idadmin=$_SESSION["admin_mitra"]["idadmin"];
    $idpodetail=$_POST["idpodetail"];
                               
    $jumlah_dipilih = count($idpodetail);

    $sqlcek = "SELECT invoice FROM pomitra WHERE idpoproduk='$idpoproduk' and  idmitra='$idadmin' ";
    $query = $koneksi->query($sqlcek);
    $pengirimancek = $query->fetch_assoc();
    $invoice = $pengirimancek['invoice']; 

 if ($pengirimancek) {
        $querydelete = "DELETE FROM pomitra WHERE invoice = '$invoice'";
        $sqldelete = mysqli_query( $koneksi, $querydelete); 
      }
                                    
for($x=0;$x<$jumlah_dipilih;$x++){

  $query = "SELECT * FROM podetail WHERE idpodetail='$idpodetail[$x]'";
  $sqldetail = mysqli_query($koneksi, $query);  
  $datadetail = mysqli_fetch_array($sqldetail);                                      

  $harga = $datadetail['harga'];
  $idpo = $datadetail['idpo'];

  $koneksi->query("insert into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl) values
  (null,'$idadmin','$idpoproduk','$idpo','$idpodetail[$x]','1','$harga','PRK$idpoproduk-$idadmin','Belum DP',NOW())");   
                                     
                                                    }
  echo "<script>alert('data berhasil dikirim');</script>";
 echo "<script>location='datapo.php?id=$idpoproduk';</script>";                                                    



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