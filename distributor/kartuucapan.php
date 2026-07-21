<?php
session_start();

include 'koneksi.php'; 


// if(!isset($_SESSION["admin_mitra"])){
//   echo "<script>alert('anda harus login terlebih dahulu');</script>";
//    echo "<script>location='login2.php';</script>";
//    header('location:login2.php');
//    exit();
// }

  $id = $_GET['id'];
  $sql = mysqli_query($koneksi, "SELECT * FROM hampers 
                                where id='$id' ");             
        $data = mysqli_fetch_array($sql);

     $result_explode = explode('|', $data['ucapan']);
    $dari=$result_explode[0];  
    $kepada=$result_explode[1];  
    $ucapan=$result_explode[2];    
  ?>  
<!DOCTYPE html>
<html>
<head>
  <title>Preview</title>
</head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 
<style>
body, html {
  height: 100%;
  margin: 0;
}

#gambar {

background-position: center;
background-repeat: no-repeat;
background-size: cover;

}

div.fixed {
position: fixed;
top: 170px;
left: 70px;
font-size: 12px;
float: left;
font-family: Monotype Corsiva
  
}

div.fixed2 {
position: fixed;
top: 189px;
left: 70px;
font-size: 12px;
float: left;
font-family: Monotype Corsiva
  
}

div.fixed3 {
position: fixed;
width: 390px;
top: 218px;
left: 70px;
font-size: 12px;
float: left;
font-family: Monotype Corsiva
  
}
</style>
<body>
<img src="img/kartuhampers.jpg" 
style="position: fixed;
        overflow-x: hidden;
        max-width: 530px;
">   
<div class="fixed">
Dari &nbsp;&nbsp;&nbsp;&nbsp;: <?= $dari; ?>
</div>

<div class="fixed2">
Untuk : <?= $kepada; ?>
</div>

<div class="fixed3">
<?= $ucapan; ?>
</div>  

</body>
</html>
