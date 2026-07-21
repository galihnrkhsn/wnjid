<?php 
session_start();

include '../koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}
?>



<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data Agen WNJ.xls");
?>

<?php //include 'templates/header.php';  ?>



    <!-- Page Wrapper|End Page Wrapper di Footer.php -->
    <div id="wrapper">

<?php //include 'templates/sidebar.php';  ?>        
             

                <!-- Begin Page Content -->
                <div class="container-fluid">
     
            
          <!-- Content Row -->
<div class="row" style="margin-top: 2%;">


            <div class="table-responsive" style="margin-top: 2%;">
   <table class="table table-striped table-bordered table-hover" id="tb_produk">
   <thead>
   <tr>
       <th style="text-align: center">No</th>
        <th style="text-align: center">Nama SubDB</th>
        <th style="text-align: center"> Nama DB</th>
        <th style="text-align: center">Kemitraan</th>
        <th style="text-align: center">Email</th>
        <th style="text-align: center">Whatsapp</th>
        <th style="text-align: center">Kota</th>
        <th style="text-align: center"> Alamat</th>
    </tr>
    </thead>
    <tbody>
        <?php 
        $no=1;
        $ambil=$koneksi->query("SELECT admin_mitra.namamitra,mitraagen.namaagen,mitraagen.email,
                            mitraagen.whatsapp,mitraagen.alamat,mitraagen.status,tb_ro_cities.city_name 
                            FROM mitraagen INNER JOIN admin_mitra ON mitraagen.idadmin=admin_mitra.idadmin
                            LEFT JOIN tb_ro_cities ON mitraagen.kota=tb_ro_cities.city_id 
                            ORDER BY idmitraagen DESC");
        while ($tampilMas=$ambil->fetch_assoc()){
        ?>
  <tr>
       <td><?php echo $no++; ?></td>
        <td><?php echo $tampilMas['namaagen']; ?></td>
        <td><?php echo $tampilMas['namamitra']; ?></td>
        <td><?php echo $tampilMas['status']; ?></td>
        <td><?php echo $tampilMas['email']; ?></td>
        <td><?php echo $tampilMas['whatsapp']; ?></td>
        <td><?php echo $tampilMas['city_name']; ?></td>
        <td><?php echo $tampilMas['alamat']; ?></td>

    </tr>


    <?php } ?>
    </tbody>
    </table>
          </div>

</div>
</div>

          </div>

            <!-- End of Main Content -->


<!-- Start Footer -->
<?php //include 'templates/footer.php';  ?>
<?php //include 'settingdatatables.php'; ?>
