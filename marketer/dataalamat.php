<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesMarketer.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketer | Wanoja</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar2.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <?php 
        $idmitramarketer=$_SESSION["idmitramarketer"];
        $cekalamat=$koneksi->query("SELECT count(provinsi) as jumlah FROM mitramarketer where idmitramarketer='$idmitramarketer'"); 
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
                    $idmitramarketer=$_SESSION["idmitramarketer"];
                    $ambil=$koneksi->query("SELECT
                                                 mitramarketer.alamat,
                                                 mitramarketer.kodepos,
                                                 tb_ro_provinces.province_name,
                                                 tb_ro_cities.city_name,
                                                 tb_ro_subdistricts.subdistrict_name
                                            FROM mitramarketer 
                                            JOIN tb_ro_provinces ON mitramarketer.provinsi = tb_ro_provinces.province_id
                                            JOIN tb_ro_cities ON tb_ro_provinces.province_id = tb_ro_cities.province_id
                                            JOIN tb_ro_subdistricts ON tb_ro_cities.city_id = tb_ro_subdistricts.city_id
                                            where mitramarketer.idmitramarketer='$idmitramarketer' and tb_ro_subdistricts.subdistrict_id = mitramarketer.kecamatan"); 
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
    <br><br><br><br>

    <!-- FOOTER -->
    <? include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP -->
    <? include "settingdatatables.php"; ?>
    <!-- PHP END -->

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>