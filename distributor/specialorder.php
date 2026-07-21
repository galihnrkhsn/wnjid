<?php 
session_start();

include 'koneksi.php'; 

if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

// $idsession=$_SESSION["statuslogin"]["idsession"];

// $ambil=$koneksi->query("SELECT count(idsession) as jumlahlogin, idsession FROM statuslogin where idsession='$idsession' "); 
//     $statuslogin=$ambil->fetch_assoc();
// if ($statuslogin['jumlahlogin']==0) {
//     // echo "<script>alert('anda gagal login ');</script>";
//     echo "<script>location='logout.php';</script>";
// }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>WNJ</title>
    <!-- Load File bootstrap.min.css yang ada difolder css -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap.css">
      <link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/jquery.dataTables.css">
      <link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/dataTables.bootstrap.css">
    <!-- Load File bootstrap.min.css yang ada difolder css -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
         crossorigin="anonymous">  
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    
<style>
body{
  padding-right: 0px ! important;
}
.align-middle{
vertical-align: middle !important;
}

#myJudul {
    
text-align: center;
border-collapse: collapse;
width: 100%;
font-size: 18px;
  
  
}
#myJudul th  {
 
  padding: 12px;
  background-color: #f1f1f1;
  font-size: 18px;
}
#myJudul td {
  text-align: center;
  padding: 12px;
 
  
}

/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #eee  url("jumbotron-bg.png") center center;
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

.navbaru p {
  
  padding: 12px 0;
  font-size: 20px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru i {
  
  padding: 15px 0;
  font-size: 23px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru2 {
   
    
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}
</style>
  
  </head>
  <body>
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="index.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>SPECIAL ORDER</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<!--================ NAVBARU END =================-->


<div class="container" align="center">

<!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->

<!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->

<!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->

  
<!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->


<div>
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home" class="btn btn-primary">MANDIRI</a></li>
    <li><a data-toggle="tab" href="#koalisi" class="btn btn-primary">KOALISI</a></li>
    <li><a data-toggle="tab" href="#series" class="btn btn-primary">BLACK SERIES</a></li>
    <!-- <li><a data-toggle="tab" href="#masker" class="btn btn-primary">List PO Masker Custom</a></li> -->
    <!-- <li><a data-toggle="tab" href="#maximus" class="btn btn-primary">List PO Maximus</a></li> -->
    <!-- <li><a data-toggle="tab" href="#miki" class="btn btn-primary">List PO Miki Hat Custom</a></li> -->
    <!--<li><a data-toggle="tab" href="#menu1" class="btn btn-primary">List PO KOLIBRI 2021</a></li>
    <li><a data-toggle="tab" href="#menu5" class="btn btn-primary">List PO KONIN 2021</a></li>
    <li><a data-toggle="tab" href="#menu2" class="btn btn-warning">List PO Kolibri Agen</a></li>
    <li><a data-toggle="tab" href="#menu3" class="btn btn-warning">List PO Kolibri Reseller</a></li>
    <li><a data-toggle="tab" href="#menu4" class="btn btn-warning">List PO Kolibri Marketer</a></li>-->
  </ul> 
</div>

<div class="tab-content">

<!---mandiri------------------------------------------------------------------------------------------------------------------------------------------------------------>

      <div id="home" class="tab-pane fade in active">
      
      <br><center><h3>Special Order Mandiri </h3></center>
      <center>*minimal order 15 series</center><br>

      <button class="btn-primary btn-sm" style="float: left;" data-toggle="modal" data-target="#modalForm"><i class="fas fa-plus"></i> Tambah Order Mandiri</button>

                 
                    <!-- <!– Modal –> -->
<form method="POST">                      
<div class="modal fade" id="modalForm" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    <!-- <!– Modal Header –> -->
      <div class="modal-header">
      <h4 class="modal-title" id="labelModalKu">Mandiri</h4>
      <button type="button" class="close" data-dismiss="modal">
      <span aria-hidden="true">&times;</span>
      <span class="sr-only">Tutup</span>
      </button>
      </div>
    <!-- <!– Modal Body –> -->
        <div class="modal-body">
        <p class="statusMsg"></p>
        <form role="form">
        <div class="form-group">
        <label for="namadb">Nama</label>
             <input type="text"  class="form-control" id="namadb" name="namadb" placeholder="namadb" required />
        </div>
        <div class="form-group">
        <label for="jumlah">Jumlah</label>
        <input type="number" min="15" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah" required />
        </div>
        </form>
        </div>
    <!-- <!– Modal Footer –> -->
          <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">&times; Close</button>
          <button type="submit" class="btn btn-primary" name="kirim">KIRIM</button>
          </div>
    </div>
  </div>
</div>    
</form>
<?php 

  if (isset($_POST['kirim'])) {
 $idadmin=$_SESSION['admin_mitra']['idadmin'];
  $namadb = $_POST["namadb"];
  $jumlah = $_POST["jumlah"];

    $sql=$koneksi->query("INSERT INTO specialmandiri (idmandiri,namadb,jumlah,idadmin)
      VALUES (null,'$namadb','$jumlah','$idadmin')");

    $sql2=$koneksi->query("INSERT INTO specialblackseries (idblackseries,namadb,jumlah,idadmin)
      VALUES (null,'$namadb',0,'$idadmin')");

    if ($sql) {
      echo "<script>alert('data berhasil ditambah');</script>";
    echo "<script>location='specialorder.php';</script>";
    }else{
      echo "<script>alert('data gagal ditambah');</script>";
    echo "<script>location='specialorder.php';</script>";
    }
      
  }
 ?>  

            <div class="table-responsive">
        <table class="table table-bordered" id="tbspecialorder">
          <thead>
          <tr> 
            <th>No</th> 
            <th style="text-align: center;">Nama</th>
            <th style="text-align: center;">Jumlah</th>          
            <th style="text-align: center;"><i class="fas fa-cog"></i></th>
          </tr>
          </thead>
          <tbody>
          <?php
          // Include / load file koneksi.php
          include "koneksi.php";
                    $idmitra=$_SESSION['admin_mitra']['idadmin'];

          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $sql = mysqli_query($koneksi, "SELECT * from specialmandiri order by idmandiri desc");
          $no=1;
            while($data = mysqli_fetch_array($sql)){

          ?>
            <tr>
              <td><?= $no++; ?></td>
              <form method="POST">
              <td style="text-align: center;"><?= $data['namadb']; ?></td>
              <td style="text-align: center;">
                <input type="hidden"  name="idmandiri" value="<?= $data['idmandiri']; ?>">
                <input style="width: 30%" type="number" min="0" name="jumlah" value="<?= $data['jumlah']; ?>"> Series
              </td>
              <td style="text-align: center;"><button type="submit" class="btn btn-success btn-sm" name="ubah"><i class="fas fa-save"></i></button></td>
              </form>
<?php 

  if (isset($_POST['ubah'])) {
 $idadmin=$_SESSION['admin_mitra']['idadmin'];
  $idmandiri = $_POST["idmandiri"];
  $jumlah = $_POST["jumlah"];

    $sql=$koneksi->query("UPDATE specialmandiri SET jumlah='$jumlah', idadmin='$idadmin' WHERE idmandiri='$idmandiri'");

    if ($sql) {
      echo "<script>alert('data berhasil ditambah');</script>";
    echo "<script>location='specialorder.php';</script>";
    }else{
      echo "<script>alert('data gagal ditambah');</script>";
    echo "<script>location='specialorder.php';</script>";
    }
      
  }
 ?>               
            </tr>
          <?php
          } ?>
          </tbody>
        </table>

      </div>

       <br>

      </div>
<!---mandiri------------------------------------------------------------------------------------------------------------------------------------------------------------>

<!---koalisi------------------------------------------------------------------------------------------------------------------------------------------------------------>

      <div id="koalisi" class="tab-pane fade">
      
      <br><center><h3>Special Order Koalisi </h3></center><br>
   <button class="btn-primary btn-sm" style="float: left;" data-toggle="modal" data-target="#modalFormKoalisi"><i class="fas fa-plus"></i> Tambah Order Koalisi</button>

                 
                    <!-- <!– Modal –> -->
<form method="POST">                      
<div class="modal fade" id="modalFormKoalisi" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    <!-- <!– Modal Header –> -->
      <div class="modal-header">
      <h4 class="modal-title" id="labelModalKu">Koalisi</h4>
      <button type="button" class="close" data-dismiss="modal">
      <span aria-hidden="true">&times;</span>
      <span class="sr-only">Tutup</span>
      </button>
      </div>
    <!-- <!– Modal Body –> -->
        <div class="modal-body">
        <p class="statusMsg"></p>
        <form role="form">
          <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
              <input type="text" class="form-control" id="namadb1" name="namadb1" placeholder="Nama DB" required>
             </div>
            <div class="col-sm-6">
             <input type="number" min="0" value="0" class="form-control" id="jumlah1" name="jumlah1" >
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
              <input type="text" class="form-control" id="namadb2" name="namadb2" placeholder="Nama DB" >
             </div>
            <div class="col-sm-6">
             <input type="number" min="0" value="0" class="form-control" id="jumlah2" name="jumlah2"  >
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
              <input type="text" class="form-control" id="namadb3" name="namadb3" placeholder="Nama DB" >
             </div>
            <div class="col-sm-6">
             <input type="number" min="0" value="0" class="form-control" id="jumlah3" name="jumlah3"  >
            </div>
          </div>
        </form>
        </div>
    <!-- <!– Modal Footer –> -->
          <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">&times; Close</button>
          <button type="submit" class="btn btn-primary" name="kirimkoalisi">KIRIM</button>
          </div>
    </div>
  </div>
</div>    
</form>
<?php 

  if (isset($_POST['kirimkoalisi'])) {
 $idadmin=$_SESSION['admin_mitra']['idadmin'];
  $namadb1 = $_POST["namadb1"];
  $jumlah1 = $_POST["jumlah1"];
  $namadb2 = $_POST["namadb2"];
  $jumlah2 = $_POST["jumlah2"];
  $namadb3 = $_POST["namadb3"];
  $jumlah3 = $_POST["jumlah3"];

    $sqlkoalisi=$koneksi->query("INSERT INTO 
      specialkoalisi (idkoalisi,namadb1,jumlah1,namadb2,jumlah2,namadb3,jumlah3,idadmin)
      VALUES (null,'$namadb1','$jumlah1','$namadb2','$jumlah2','$namadb3','$jumlah3','$idadmin')");

    if ($sqlkoalisi) {
      echo "<script>alert('data berhasil ditambah');</script>";
    echo "<script>location='specialorder.php';</script>";
    }else{
      echo "<script>alert('data gagal ditambah');</script>";
    echo "<script>location='specialorder.php';</script>";
    }
      
  }
 ?> 

<div class="table-responsive">
        <table class="table table-bordered" id="tbspecialorder_koalisi">
          <thead>
          <tr> 
            <th>No</th> 
            <th style="text-align: center;">Nama DB 1</th>
            <th style="text-align: center;">Nama DB 2</th>
            <th style="text-align: center;">Nama DB 3</th>      
            <th style="text-align: center;"><i class="fas fa-cog"></i></th>
          </tr>
          </thead>
          <tbody>
          <?php
          // Include / load file koneksi.php
          include "koneksi.php";
                    $idmitra=$_SESSION['admin_mitra']['idadmin'];

          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $sql = mysqli_query($koneksi, "SELECT * from specialkoalisi order by idkoalisi desc");
          $no=1;
            while($data = mysqli_fetch_array($sql)){

          ?>
            <tr>
              <td><?= $no++; ?></td>
              <form method="POST">
              <td style="text-align: center;">
                <?= $data['namadb1']; ?> 
                <br>
                <input type="hidden"  name="idkoalisi" value="<?= $data['idkoalisi']; ?>">
                <input type="number"  style="float: ;width: 30%" min="0" name="jumlah1" value="<?= $data['jumlah1']; ?>"> Series
              </td>
              <td style="text-align: center;">
                <?php if ($data['namadb2']==""): ?>
                  <input type="text" class="form-control" id="namadb2" name="namadb2" placeholder="Nama DB" >
                <?php else: ?>
                  <input type="hidden" class="form-control" id="namadb2" name="namadb2" value=" <?= $data['namadb2']; ?>" >
                <?= $data['namadb2']; ?> 
                <?php endif ?>
                <br>
                <input type="hidden"  name="idkoalisi" value="<?= $data['idkoalisi']; ?>">
                <input type="number" style="float: ;width: 30%" min="0" name="jumlah2" value="<?= $data['jumlah2']; ?>"> Series
              </td>
              <td style="text-align: center;">
                <?php if ($data['namadb3']==""): ?>
                  <input type="text" class="form-control" id="namadb3" name="namadb3" placeholder="Nama DB" >
                <?php else: ?>
                  <input type="hidden" class="form-control" id="namadb3" name="namadb3" value=" <?= $data['namadb3']; ?>" >
                <?= $data['namadb3']; ?>
                <?php endif ?>
                 
                <br>
                <input type="hidden"  name="idkoalisi" value="<?= $data['idkoalisi']; ?>">
                <input type="number" style="float: ;width: 30%" name="jumlah3" value="<?= $data['jumlah3']; ?>"> Series
                
              </td>
              
              <td style="text-align: center;"><button type="submit" class="btn btn-success btn-sm" name="ubahkoalisi"><i class="fas fa-save"></i></button></td>
              </form>
<?php 

  if (isset($_POST['ubahkoalisi'])) {
 $idadmin=$_SESSION['admin_mitra']['idadmin'];
  $idkoalisi = $_POST["idkoalisi"];
  $jumlah1 = $_POST["jumlah1"];
  $jumlah2 = $_POST["jumlah2"];
  $jumlah3 = $_POST["jumlah3"];
  $namadb2 = $_POST["namadb2"];
  $namadb3 = $_POST["namadb3"];
  
    $sql=$koneksi->query("UPDATE specialkoalisi SET jumlah1='$jumlah1',jumlah2='$jumlah2',jumlah3='$jumlah3',namadb2='$namadb2',namadb3='$namadb3', idadmin='$idadmin' WHERE idkoalisi='$idkoalisi'");

    if ($sql) {
      echo "<script>alert('data berhasil ditambah');</script>";
    echo "<script>location='specialorder.php';</script>";
    }else{
      echo "<script>alert('data gagal ditambah');</script>";
    echo "<script>location='specialorder.php';</script>";
    }
      
  }
 ?>               
            </tr>
          <?php
          } ?>
          </tbody>
        </table>

      </div>
      </div> 
<!---koalisi------------------------------------------------------------------------------------------------------------------------------------------------------------>
<!---Series------------------------------------------------------------------------------------------------------------------------------------------------------------>

      <div id="series" class="tab-pane fade">
      
      <br><center><h3>Special Order Black Series </h3></center>
          <center>*Black Series hanya untuk DB yang sudah Order Mandiri</center>
          <br>
      <!-- <button class="btn-primary btn-sm" style="float: left;" data-toggle="modal" data-target="#modalFormSeries"><i class="fas fa-plus"></i> Tambah Order Black Series</button> -->

                 
                    <!-- <!– Modal –> -->
<form method="POST">                      
<div class="modal fade" id="modalFormSeries" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    <!-- <!– Modal Header –> -->
      <div class="modal-header">
      <h4 class="modal-title" id="labelModalKu">Series</h4>
      <button type="button" class="close" data-dismiss="modal">
      <span aria-hidden="true">&times;</span>
      <span class="sr-only">Tutup</span>
      </button>
      </div>
    <!-- <!– Modal Body –> -->
        <div class="modal-body">
        <p class="statusMsg"></p>
        <form role="form">
        <div class="form-group">
        <label for="namadb">Nama</label>
         <select name="namadb" class="form-control" required>
            <?php 
            include "koneksi.php";
            $ambil=$koneksi->query("SELECT * FROM specialmandiri order by namadb asc");
            while($data=$ambil->fetch_assoc()){
            ?>
            <option value="<?php echo $data['namadb']; ?>"><?php echo $data['namadb']; ?></option>
            <?php } ?>
        </select>
        </div>
        <div class="form-group">
        <label for="jumlah">Jumlah</label>
        <input type="number" min="0" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah" required />
        </div>
        </form>
        </div>
    <!-- <!– Modal Footer –> -->
          <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">&times; Close</button>
          <button type="submit" class="btn btn-primary" name="kirimseries">KIRIM</button>
          </div>
    </div>
  </div>
</div>    
</form>
<?php 

  if (isset($_POST['kirimseries'])) {
 $idadmin=$_SESSION['admin_mitra']['idadmin'];
  $namadb = $_POST["namadb"];
  $jumlah = $_POST["jumlah"];

    $sql=$koneksi->query("INSERT INTO specialblackseries (idblackseries,namadb,jumlah,idadmin)
      VALUES (null,'$namadb','$jumlah','$idadmin')");

    if ($sql) {
      echo "<script>alert('data berhasil ditambah');</script>";
    echo "<script>location='specialorder.php';</script>";
    }else{
      echo "<script>alert('data gagal ditambah');</script>";
    echo "<script>location='specialorder.php';</script>";
    }
      
  }
 ?>  

            <div class="table-responsive">
        <table class="table table-bordered" id="tbspecialorder_series">
          <thead>
          <tr> 
            <th>No</th> 
            <th style="text-align: center;">Nama</th>
            <th style="text-align: center;">Jumlah</th>          
            <th style="text-align: center;"><i class="fas fa-cog"></i></th>
          </tr>
          </thead>
          <tbody>
          <?php
          // Include / load file koneksi.php
          include "koneksi.php";
                    $idmitra=$_SESSION['admin_mitra']['idadmin'];

          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $sql = mysqli_query($koneksi, "SELECT * from specialblackseries order by idblackseries desc");
          $no=1;
            while($data = mysqli_fetch_array($sql)){

          ?>
            <tr>
              <td><?= $no++; ?></td>
              <form method="POST">
              <td style="text-align: center;"><?= $data['namadb']; ?></td>
              <td style="text-align: center;">
                <input type="hidden"  name="idblackseries" value="<?= $data['idblackseries']; ?>">
                <input style="width: 30%" type="number" min="0" name="jumlah" value="<?= $data['jumlah']; ?>"> Pcs
              </td>
              <td style="text-align: center;"><button type="submit" class="btn btn-success btn-sm" name="ubahseries"><i class="fas fa-save"></i></button></td>
              </form>
<?php 

  if (isset($_POST['ubahseries'])) {
 $idadmin=$_SESSION['admin_mitra']['idadmin'];
  $idblackseries = $_POST["idblackseries"];
  $jumlah = $_POST["jumlah"];

    $sql=$koneksi->query("UPDATE specialblackseries SET jumlah='$jumlah', idadmin='$idadmin' WHERE idblackseries='$idblackseries'");

    if ($sql) {
      echo "<script>alert('data berhasil ditambah');</script>";
    echo "<script>location='specialorder.php';</script>";
    }else{
      echo "<script>alert('data gagal ditambah');</script>";
    echo "<script>location='specialorder.php';</script>";
    }
      
  }
 ?>               
            </tr>
          <?php
          } ?>
          </tbody>
        </table>

      </div>

       <br>

      </div>
<!---series------------------------------------------------------------------------------------------------------------------------------------------------------------>


 <!--TUTUP TAB CONTENT------------------------------------------------------------------------------------------------------------------------------------------------------->     
</div>
 <!--TUTUP TAB CONTENT------------------------------------------------------------------------------------------------------------------------------------------------------->       


</div>

      

      

      
  

      
      


<!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->


    <br><br><br><br>
  <?php include "menubawah2.php" ?> 
 
     

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.js"></script>
<script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.dataTables.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
       <?php include "settingdatatables.php" ?> 
  </body>
</html>