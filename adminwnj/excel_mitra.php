<?php
  header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=Data Distributor.xls"); 
  session_start();
  include 'koneksi.php';
  if(!isset($_SESSION["administrator"])){
    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login.php';</script>";
    header('location:login.php');
    exit();
  }
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Pusat | Wanoja</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

  </head>
              
  <body>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama CS</th>
            <th>Idadmin</th>
            <th>Nama Distributor</th>
            <th>Email</th>
          </tr>
        </thead>

        <tbody>
          <?php
            // if (isset($_POST["cari"])) {
            //   $namadb = $_POST['namamitra'];
            //   $halaman = 50;
            //   $page = isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
            //   $mulai = ($page-1) * $halaman;
            //   $haldb = $koneksi->query("SELECT * FROM admin_mitra");
            //   $total = mysqli_num_rows($haldb);
            //   $pages = ceil($total / $halaman);
            //   $no = $mulai + 1;
            //   $dataproduk = $koneksi->query("SELECT admin_mitra_cs.namacs, admin_mitra.idadmin, admin_mitra.namamitra, admin_mitra.password, admin_mitra.email, admin_mitra.whatsapp, admin_mitra.kota, admin_mitra.alamat FROM admin_mitra INNER JOIN admin_mitra_cs ON admin_mitra.idadmin = admin_mitra_cs.idadmin WHERE admin_mitra LIKE '%$namadb%' ORDER BY admin_mitra.namamitra DESC LIMIT $mulai, $halaman");
            // }elseif(isset($_POST["tampil"])) {
            //   $halaman = 50;
            //   $page = isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
            //   $mulai = ($page-1) * $halaman;
            //   $haldb = $koneksi->query("SELECT * FROM admin_mitra");
            //   $total = mysqli_num_rows($haldb);
            //   $pages = ceil($total / $halaman);
            //   $no = $mulai + 1;
            //   $dataproduk = $koneksi->query("SELECT admin_mitra_cs.namacs, admin_mitra.idadmin, admin_mitra.namamitra, admin_mitra.password, admin_mitra.email, admin_mitra.whatsapp, admin_mitra.kota, admin_mitra.alamat FROM admin_mitra INNER JOIN admin_mitra_cs ON admin_mitra.idadmin = admin_mitra_cs.idadmin ORDER BY admin_mitra.namamitra DESC LIMIT $mulai, $halaman");
            // } else {
            //   $halaman = 50;
            //   $page = isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
            //   $mulai = ($page-1) * $halaman ;
            //   $haldb = $koneksi->query("SELECT * FROM admin_mitra");
            //   $pages = ceil($total / $halaman);
            //   $no = $mulai+1;
            // }

            $datadistributor = $koneksi->query("SELECT
                                                    admin_mitra.idadmin,
                                                    admin_mitra.namamitra,
                                                    admin_mitra.email,
                                                    admin_mitra.whatsapp,
                                                    admin_mitra.kota,
                                                    admin_mitra.alamat,
                                                    admin_mitra_cs.namacs
                                                  FROM
                                                    admin_mitra
                                                    LEFT JOIN admin_mitra_cs ON admin_mitra_cs.idadmin = admin_mitra.idadmin
                                                  ORDER BY admin_mitra.idadmin ASC
                                              ");
            $no = 1;
            while ($tampilkan = $datadistributor->fetch_assoc()) {
          ?>
          
          <tr>
            <td><?= $no++; ?></td>
            <td><?= $tampilkan['namacs']; ?></td>
            <td><?= $tampilkan['idadmin'] ?></td>
            <td><?= $tampilkan['namamitra'] ?></td>
            <td><?= $tampilkan['namacs'] ?></td>
            <td><?= $tampilkan['email'] ?></td>
          </tr>

          <?php
            }
          ?>
        </tbody>
      </table>
    </div>
  </body>
</html>
