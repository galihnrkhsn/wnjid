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
  <link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap.css">
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
    <tr>
      
    </tr>
    <center><b>Anda sudah mengisi Formulir $namapo , Klik Tombol Dibawah ini Jika ingin melihat atau merevisi Invoice, 
                                </b><br><br>
      <a class='btn btn-info' href='datapo.php?idmitra=$idadmin&id=$id'> INVOICE</a> </center>";
    }
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
              
          <div style="padding: 0 15px;">

    <div class="form-group">
          <select class="form-control" name="variant1" id="variant1" required>
            <option value="" selected>- Pilih Variant -</option>
<?php
  $sql = "SELECT * FROM poproduk 
      inner join pokategori 
      inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk 
      and pokategori.idpo=podetail.idpo 
      where poproduk.idpoproduk='$idpoproduk' 
      and pokategori.stok > 0
      order by podetail.variant asc";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>            
              
             <option value="<?php echo $row['idpodetail']; ?>|<?php echo $row['idpo']; ?>"><?php echo $row['variant']; ?></option>
<?php } ?>             
          </select>      
    </div> 
  <div class="form-group">
    <input type="number" name="jmlh1" class="form-control" value=1 readonly>
  </div>    
    <div class="form-group">
          <select class="form-control" name="variant2" id="variant2" required>
            <option value="" selected>- Pilih Variant -</option>
<?php
  $sql = "SELECT * FROM poproduk 
      inner join pokategori 
      inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk 
      and pokategori.idpo=podetail.idpo 
      where poproduk.idpoproduk='$idpoproduk' 
      and pokategori.stok > 0
      order by podetail.variant asc";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>            
              
             <option value="<?php echo $row['idpodetail']; ?>|<?php echo $row['idpo']; ?>"><?php echo $row['variant']; ?></option>
<?php } ?>             
          </select>      
    </div>     
  <div class="form-group">
    <input type="number" name="jmlh2" class="form-control" value=1 readonly>
  </div> 

    <div class="form-group">
          <select class="form-control" name="variant3" id="variant3" required>
            <option value="" selected>- Pilih Variant -</option>
<?php
  $sql = "SELECT * FROM poproduk 
      inner join pokategori 
      inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk 
      and pokategori.idpo=podetail.idpo 
      where poproduk.idpoproduk='$idpoproduk' 
      and pokategori.stok > 0
      order by podetail.variant asc";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>            
              
             <option value="<?php echo $row['idpodetail']; ?>|<?php echo $row['idpo']; ?>"><?php echo $row['variant']; ?></option>
<?php } ?>             
          </select>      
    </div>     
  <div class="form-group">
    <input type="number" name="jmlh3" class="form-control" value=1 readonly>
  </div>     

    <div class="form-group">
          <select class="form-control" name="variant4" id="variant4" required>
            <option value="" selected>- Pilih Variant -</option>
<?php
  $sql = "SELECT * FROM poproduk 
      inner join pokategori 
      inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk 
      and pokategori.idpo=podetail.idpo 
      where poproduk.idpoproduk='$idpoproduk' 
      and pokategori.stok > 0
      order by podetail.variant asc";
            $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
                ?>            
              
             <option value="<?php echo $row['idpodetail']; ?>|<?php echo $row['idpo']; ?>"><?php echo $row['variant']; ?></option>
<?php } ?>             
          </select>      
    </div>     
  <div class="form-group">
    <input type="number" name="jmlh4" class="form-control" value=1 readonly>
  </div>                   


                        <?php if($data['jumlah']<1){
                          echo "<button type='submit' class='btn btn-primary' name='save'>Kirim</button>";
                        }else{
                                echo "# ";
                                }
                          ?>
<?php
                              if(isset($_POST["save"])){
                                   include "koneksi.php";
                                 date_default_timezone_set('Asia/Jakarta');
                                    $today = date("s");
                         $idadmin=$_SESSION["admin_mitra"]["idadmin"];
                         $variant1= $_POST["variant1"];
                         $variant2= $_POST["variant2"];
                         $variant3= $_POST["variant3"];
                         $variant4= $_POST["variant4"];

                        $result_explode1 = explode('|', $variant1);
                        $idpodetail1=$result_explode1[0];
                        $idpo1=$result_explode1[1];

                        $result_explode2 = explode('|', $variant2);
                        $idpodetail2=$result_explode2[0];
                        $idpo2=$result_explode2[1];
                        
                        $result_explode3 = explode('|', $variant3);
                        $idpodetail3=$result_explode3[0];
                        $idpo3=$result_explode3[1];

                        $result_explode4 = explode('|', $variant4);
                        $idpodetail4=$result_explode4[0];
                        $idpo4=$result_explode4[1];


                        $sql1 = "SELECT stok from pokategori where idpo='$idpo1'";
                        $query1 = $koneksi->query($sql1);
                        $sisa1 = $query1->fetch_assoc(); 
                        $stok1 = $sisa1['stok'];

                        $sqlharga1 = "SELECT harga from podetail where idpodetail='$idpodetail1'";
                        $queryharga1 = $koneksi->query($sqlharga1);
                        $sisaharga1 = $queryharga1->fetch_assoc(); 
                        $stokharga1 = $sisaharga1['harga']; 

                        $sql2 = "SELECT stok from pokategori where idpo='$idpo2'";
                        $query2 = $koneksi->query($sql2);
                        $sisa2 = $query2->fetch_assoc(); 
                        $stok2 = $sisa2['stok'];   

                        $sqlharga2 = "SELECT harga from podetail where idpodetail='$idpodetail2'";
                        $queryharga2 = $koneksi->query($sqlharga2);
                        $sisaharga2 = $queryharga2->fetch_assoc(); 
                        $stokharga2 = $sisaharga2['harga'];  

                        $sql3 = "SELECT stok from pokategori where idpo='$idpo3'";
                        $query3 = $koneksi->query($sql3);
                        $sisa3 = $query3->fetch_assoc(); 
                        $stok3 = $sisa3['stok'];

                        $sqlharga3 = "SELECT harga from podetail where idpodetail='$idpodetail3'";
                        $queryharga3 = $koneksi->query($sqlharga3);
                        $sisaharga3 = $queryharga3->fetch_assoc(); 
                        $stokharga3 = $sisaharga3['harga']; 

                        $sql4 = "SELECT stok from pokategori where idpo='$idpo4'";
                        $query4 = $koneksi->query($sql4);
                        $sisa4 = $query4->fetch_assoc(); 
                        $stok4 = $sisa4['stok'];   

                        $sqlharga4 = "SELECT harga from podetail where idpodetail='$idpodetail4'";
                        $queryharga4 = $koneksi->query($sqlharga4);
                        $sisaharga4 = $queryharga4->fetch_assoc(); 
                        $stokharga4 = $sisaharga4['harga'];                                                         
                        
                        $idpoproduk = $_GET['id'];
                         // echo "<script>alert('$idpodetail1, $idpo1, $stok1 | $idpodetail2, $idpo2, $stok2 ');</script>";

                        if($stok1>0 AND $stok2>0 AND $stok3>0 AND $stok4>0){
                            $sql1= $koneksi->query("INSERT into pomitra 
                              (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl) 
                              VALUES
                              (null,'$idadmin','$idpoproduk','$idpo1','$idpodetail1','1','$stokharga1','D$idpoproduk-$idadmin','Belum DP',NOW())");
                            $koneksi->query("UPDATE pokategori set stok=stok-'1' where idpo='$idpo1'");
                            $sql2= $koneksi->query("INSERT into pomitra 
                              (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl) 
                              VALUES
                              (null,'$idadmin','$idpoproduk','$idpo2','$idpodetail2','1','$stokharga2','D$idpoproduk-$idadmin','Belum DP',NOW())");
                            $koneksi->query("UPDATE pokategori set stok=stok-'1' where idpo='$idpo2'");
                            $sql3= $koneksi->query("INSERT into pomitra 
                              (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl) 
                              VALUES
                              (null,'$idadmin','$idpoproduk','$idpo3','$idpodetail3','1','$stokharga3','D$idpoproduk-$idadmin','Belum DP',NOW())");
                            $koneksi->query("UPDATE pokategori set stok=stok-'1' where idpo='$idpo1'");
                            $sql4= $koneksi->query("INSERT into pomitra 
                              (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl) 
                              VALUES
                              (null,'$idadmin','$idpoproduk','$idpo4','$idpodetail4','1','$stokharga4','D$idpoproduk-$idadmin','Belum DP',NOW())");
                            $koneksi->query("UPDATE pokategori set stok=stok-'1' where idpo='$idpo2'");

                                      }else{
                                        echo "<script>alert('stok produk tidak ada');</script>";
                                            echo "<script>location='form_po.php?id=$idpoproduk';</script>";
                                      }                        
                                      if ($sql1 and $sql2) {
                                        echo "<script>alert('data berhasil dikirim');</script>";
                                            echo "<script>location='datapo.php?id=$idpoproduk';</script>";
                                      }else{
                                        echo "<script>alert('stok produk tidak ada');</script>";
                                            echo "<script>location='form_po.php?id=$idpoproduk';</script>";
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
