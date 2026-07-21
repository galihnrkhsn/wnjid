<?php 
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesDistri.php';

    $idadmin    = $_SESSION['idadmin'];
    $queryUser  = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin = '$idadmin'");
    $getUser    = $queryUser->fetch_assoc();
    $jenis      = $_GET['jenis']; 
?>
  
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WNJ</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">
    
    <body>
        <!-- NAVBAR -->
        <?php include 'assets/components/Navbar/navbar.php'; ?>
        <!-- NAVBAR END -->
  
        <div class="container"> 
               
  
        <?php 
            if($_GET["id"]<>""){ ?>
            <h4 class="mt-3"><center><?= $getUser['namamitra'] ?> (Cust ID : <?= $getUser["idadmin"]; ?> )</center></h4><br>               
            <?php      
                include 'formpembayarana.php';
            }
                if($_GET["id"]==""){
                include 'formpembayaranb.php';
            }
        ?>
    <?php include "menubawahstore.php"; ?>
</body>

</html>