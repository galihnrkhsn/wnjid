<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <?php
    $idadmin    = $_SESSION["idadmin"];
    $query      = "SELECT * FROM admin_mitra
                    LEFT JOIN tb_ro_provinces ON admin_mitra.provinsi = tb_ro_provinces.province_id
                    LEFT JOIN tb_ro_cities ON admin_mitra.kota = tb_ro_cities.city_id
                    LEFT JOIN tb_ro_subdistricts ON admin_mitra.kecamatan = tb_ro_subdistricts.subdistrict_id
                    WHERE idadmin = '$idadmin'";
    $result     = $koneksi->query($query);

    while ($row = $result->fetch_assoc()) {
    ?>
    <div class="container pt-5">
        <div class="card mx-auto text-center" style="width: 18rem;">
            <img src="foto/<?php echo $row['foto']; ?>" class="card-img-top mx-auto pt-2" style="width:120px;height:120px;" alt="Profile">
            <div class="card-body">
                <h5 class="card-title"><?php echo $row["namamitra"]; ?></h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><i class="fa-brands fa-whatsapp"></i> <?php echo $row['whatsapp']; ?></li>
                <li class="list-group-item"><i class="fa-brands fa-telegram"></i> <?php echo $row['telegram']; ?></li>
                <li class="list-group-item"><i class="fa-brands fa-facebook"></i> <?php echo $row['facebook']; ?></li>
                <li class="list-group-item"><i class="fa-brands fa-instagram"></i> <?php echo $row['instagram']; ?></li>
                <li class="list-group-item"><i class="fa-solid fa-location-dot"></i> <?php echo $row['alamat']; ?></li>
                <li class="list-group-item"><i class="fa-solid fa-location-dot"></i> <?php echo $row['province_name']; ?></li>
                <li class="list-group-item"><i class="fa-solid fa-location-dot"></i> <?php echo $row['city_name']; ?></li>
                <li class="list-group-item"><i class="fa-solid fa-location-dot"></i> <?php echo $row['subdistrict_name']; ?></li>
            </ul>
            <div class="card-body">
                <button type="button" class="btn btn-primary">
                    <a class="link" href="form_ubah.php?idadmin=<?php echo $_SESSION ['idadmin']; ?>"><i class="fa fa-edit"></i> Ubah</a>
                </button>
            </div>
        </div>
    </div>
    <?php } ?>
    <!-- MAIN CONTENT END -->
    
    <br><br><br><br>
    
    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->
</body>

    <!-- PHP SYNTAK -->
    <?php 
       $idadmin=$_SESSION ['idadmin'];
            if(isset($_POST['save'])){
            $nama= $_FILES['foto']['name'];
            $lokasi=$_FILES['foto']['tmp_name'];
            move_uploaded_file($lokasi, "../foto/".$nama);
            $koneksi->query("UPDATE admin_mitra SET foto='$nama' WHERE idadmin='$idadmin'");

            echo "<div class='alert alert-info'>upload photo berhasil</div>";
            echo "<script>location='index2.php';</script>";
        }
    ?>
    <!-- PHP SYNTAK END -->
    
    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>