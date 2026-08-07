<?php
    session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesReseller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseller | WNJ.ID</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar2.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <?php 
        $idmitrareseller=$_SESSION["idmitrareseller"];
        $cekalamat=$koneksi->query("SELECT count(provinsi) as jumlah FROM mitrareseller where idmitrareseller='$idmitrareseller'"); 
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
                    $idmitrareseller=$_SESSION["idmitrareseller"];
                    $ambil=$koneksi->query("SELECT
                                                 mitrareseller.alamat,
                                                 mitrareseller.kodepos,
                                                 tb_ro_provinces.province_name,
                                                 tb_ro_cities.city_name,
                                                 tb_ro_subdistricts.subdistrict_name
                                            FROM mitrareseller 
                                            JOIN tb_ro_provinces ON mitrareseller.provinsi = tb_ro_provinces.province_id
                                            JOIN tb_ro_cities ON tb_ro_provinces.province_id = tb_ro_cities.province_id
                                            JOIN tb_ro_subdistricts ON tb_ro_cities.city_id = tb_ro_subdistricts.city_id
                                            where mitrareseller.idmitrareseller='$idmitrareseller' and tb_ro_subdistricts.subdistrict_id = mitrareseller.kecamatan"); 
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
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP -->
    <?php include "settingdatatables.php"; ?>
    <!-- PHP END -->

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>