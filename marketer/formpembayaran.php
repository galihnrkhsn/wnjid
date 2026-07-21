<?php 
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesMarketer.php';
    $idmitramarketer    = $_SESSION["idmitramarketer"];
    $queryAgen          = $koneksi->query("SELECT * FROM mitramarketer WHERE idmitramarketer = '$idmitramarketer'");
    $dataAgen           = $queryAgen->fetch_assoc();
?>
  
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agen | Wanoja</title>
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
            <?php 
                if($_GET["id"] <> ""){ ?>
                <h4><center><?= $dataAgen["namaagen"]; ?> (Cust ID : <?= $dataAgen["idmitramarketer"]; ?> )</center></h4><br>               
            <?php      
                include 'formpembayarana.php';
                }
                if($_GET["id"]==""){
                    include 'formpembayaranb.php';
                }
            ?>  
        </div> 
        <br><br><br>
        <?php include "menubawahstore.php"; ?>
    </body>

</html>