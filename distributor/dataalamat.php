<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
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
    <? include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <?php 
        $idadmin=$_SESSION["idadmin"];
        $cekalamat=$koneksi->query("SELECT count(provinsi) as jumlah FROM admin_mitra where idadmin='$idadmin'"); 
        $jumlahalamat=$cekalamat->fetch_assoc();
    ?>
    <div class="container pt-5" style="margin-bottom: 2%;">
        <?php if ($jumlahalamat['jumlah']==0) { ?>
            <a href="isialamat.php" class="btn btn-primary"><span class="fa fa-plus"></span>Tambah Alamat</a>
        <?php } ?>
        <?php if ($jumlahalamat['jumlah']==1) { ?>
            <a href="isialamat.php" class="btn btn-success"><span class="fa fa-edit"></span>Ubah Alamat</a>
        <?php } ?>
    </div>
    <div class="container">
        <div class="table-responsive">
            <table class="table table-striped table-bordered border-secondary table-hover">
                <thead>
                    <tr>
                        <th>Alamat</th>
                        <th>Kode Pos</th> 
                        <th>Provinsi</th>
                        <th>Kota</th>
                        <th>Kecamatan</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $idadmin=$_SESSION["idadmin"];
                    $ambil=$koneksi->query("SELECT
                        admin_mitra.alamat,
                        admin_mitra.kodepos,
                        tb_ro_provinces.province_name,
                        tb_ro_cities.city_name,
                        tb_ro_subdistricts.subdistrict_name
                        FROM admin_mitra 
                        JOIN tb_ro_provinces ON admin_mitra.provinsi = tb_ro_provinces.province_id
                        JOIN tb_ro_cities ON tb_ro_provinces.province_id = tb_ro_cities.province_id
                        JOIN tb_ro_subdistricts ON tb_ro_cities.city_id = tb_ro_subdistricts.city_id
                        where admin_mitra.idadmin='$idadmin' and tb_ro_subdistricts.subdistrict_id = admin_mitra.kecamatan"); 
                    while($agen=$ambil->fetch_assoc()){
                    ?>
                    <tr>
                        <td><?php echo $agen['alamat'];?></td>
                        <td><?php echo $agen['kodepos'];?></td>
                        <td><?php echo $agen['province_name'];?></td>
                        <td><?php echo $agen['city_name'];?></td>
                        <td><?php echo $agen['subdistrict_name'] ?></td>
                        <td><a href="isialamat.php" class="btn btn-success"><span class="fa fa-edit"></span></a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    
    
    <!-- FOOTER -->
    <br><br><br><br>
    <? include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP SYNTAK -->
    <!-- PHP SYNTAK END -->
    
    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>