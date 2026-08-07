<?php 
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

  <title>WNJ.ID</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

<?php include "sidebar.php"; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search 
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" method="post">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Cari Nama Mitra" aria-label="Search" aria-describedby="basic-addon2" name="namamitra">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit" name="cari">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>-->

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) 
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>-->
              <!-- Dropdown - Messages
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li> -->

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin WNJ.ID</span>
                <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
              <!--  <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings 
                </a> 
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>-->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
           <h3><strong>Distribusi PO</strong></h3><br>
          <div class="row">
              
               
               <form method="post"> <div class="input-group"><select name="namapo" class="form-control">
                                     <?php $datapo=$koneksi->query("SELECT * from poproduk order by idpoproduk desc");
                                            while($tampilkan=$datapo->fetch_assoc()){ ?>
                                   <option><?php echo $tampilkan['namapo'] ?></option>
                                   <?php } ?></select><div class="input-group-append"><button type="submit" name="pilih" class="btn btn-primary"><span class="fa fa-search"></span></button>
               </div></div></form>
               
               <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                      <thead>
                        <tr>
                            <th>
                            Artikel    
                            </th>
                             <th>
                            Variant    
                            </th>
                             <th>
                            Total PO    
                            </th>
                           <th>
                            Distribusi
                          </th>
                          <th>
                            Kurang
                          </th>
                          <th>
                            Stok Gudang
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                          	<?php
                          	error_reporting(0);
					// Include / load file koneksi.php
					include "koneksi.php";
				
					include "koneksi2.php"; 
					if(isset($_POST["pilih"])) {
					    $namapo=$_POST['namapo'];
                          $sql = mysqli_query($koneksi, "SELECT poproduk.namapo,podetail.variant,IFNULL(podetail.variant, 'JUMLAH') AS variant, sum(pomitra.jumlah) as jumlah2 FROM `podetail` left join pomitra USING(idpodetail) INNER join poproduk on pomitra.idpoproduk=poproduk.idpoproduk where poproduk.namapo='$namapo' GROUP by podetail.idpodetail order by podetail.variant asc");
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
					$sql2 = mysqli_query($koneksi2, "SELECT IFNULL(variant, 'DISTRIBUSI') AS db, sum(distribusi) as distribusi,IFNULL(variant, 'JUMLAH') AS nama, sum(jumlah) as jumlah FROM progrespo where namapo='$namapo' GROUP by variant");
					
					$no =  1; // Untuk penomoran tabel
					$total=0;
					$total2=0;
					$totalkurang=0;
					}
					while($data = mysqli_fetch_array($sql) and $data2 = mysqli_fetch_array($sql2) ){ // Ambil semua data dari hasil eksekusi $sql
					?>
					<tr>
					    <td><?php echo $data['namapo']; ?></td>
					    <td><?php echo $data['variant']; ?></td>
					    <td><?php echo $data['jumlah2']; ?></td>
					    <td><?php echo $data2['distribusi']; ?></td>
					    <td><?php echo $data['jumlah2']-$data2['distribusi']; ?></td>
					    <td><?php echo $data2['jumlah']-$data2['distribusi']; ?></td>
					 </tr>
					 </tbody>
					 <?php $po=$data['namapo']; } ?>
					</table>
					<a href="inputdistribusi.php?id=<?php echo $po; ?>" class="btn btn-success">+ Distribusi</a>