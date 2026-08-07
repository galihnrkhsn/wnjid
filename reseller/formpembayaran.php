<?php 
session_start();

include 'koneksi.php'; 
//include 'header.php'; 


include 'assets/components/Sessions/sesReseller.php';



    $idmitrareseller = $_SESSION["idmitrareseller"];

    $queryReseller  = $koneksi->query("SELECT * FROM mitrareseller WHERE idmitrareseller = '$idmitrareseller'");
    $getReseller    = $queryReseller->fetch_assoc();

?>
  
<html lang="en">
    <head>
        <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Agen | WNJ.ID</title>
            <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
            crossorigin="anonymous">
    </head>
    <body>
        <!-- NAVBAR -->
        <?php include 'assets/components/Navbar/navbar2.php'; ?>
        <!-- NAVBAR END -->
        <div class="container mt-4">     
            <h4><center><?= $getReseller["namaagen"]; ?> (Cust ID : <?= $getReseller["idmitrareseller"]; ?> )</center></h4><br>
            <?php 
                if( $_GET["id"] <> "" ){ 
                    include 'formpembayarana.php';
                }
                if( $_GET["id"] == "" ){
                    include 'formpembayaranb.php';
                }
            ?>                
        </div> 
        <?php include "menubawahstore.php"; ?>
    </body>

</html>