<?php 
session_start();

include 'koneksi.php'; 

if(!isset($_SESSION["mitraagen"])){
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| WNJ.ID </title>
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    
<style>
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
  <div class="col-8" ><p>PRE ORDER</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<!--================ NAVBARU END =================-->


<div class="container" align="center">

<!--     <button type="submit" class="btn btn-info btn-lg" name="cari" id="linkinner">
    <a  style="color:white" href="formpo_bergo.php?id=117">Link PO Khimar Limosa</a>
  </button>
  <p id="demoinner"></p>
  <br>  -->

<script>
// Mengatur waktu akhir perhitungan mundur
// var countDownDateinner = new Date("Jun 28, 2022 23:59:00").getTime();

// // Memperbarui hitungan mundur setiap 1 detik
// var x = setInterval(function() {

//   // Untuk mendapatkan tanggal dan waktu hari ini
//   var now = new Date().getTime();
    
//   // Temukan jarak antara sekarang dan tanggal hitung mundur
//   var distance = countDownDateinner - now;
    
//   // Perhitungan waktu untuk hari, jam, menit dan detik
//   var days = Math.floor(distance / (1000 * 60 * 60 * 24));
//   var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
//   var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
//   var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
//   // Keluarkan hasil dalam elemen dengan id = "demo"
//   document.getElementById("demoinner").innerHTML = days + "d " + hours + "h "
//   + minutes + "m " + seconds + "s ";
    
//   // Jika hitungan mundur selesai, tulis beberapa teks 
//   if (distance < 0) {
//     clearInterval(x);
//     document.getElementById("demoinner").innerHTML = "Link PO tidak tersedia";
//       var x = document.getElementById("linkinner");
 
//     //x.style.display = "block";
//     x.style.display = "none";
//     }
// }, 1000);
</script>

<?php 
  $dataproduk=$koneksi->query("SELECT bukapo.idbpo,
                              bukapo.jenis_mitra,
                              bukapo.jenis_po,
                              bukapo.idpoproduk,
                              bukapo.tgl,
                              bukapo.tgl_dropship,
                              bukapo.status,
                              poproduk.namapo 
                              FROM bukapo inner join poproduk on bukapo.idpoproduk = poproduk.idpoproduk 
                              WHERE bukapo.jenis_mitra = 'Semua Mitra'");
  while($tampilkan=$dataproduk->fetch_assoc()){

?>

      <button type="submit" class="btn btn-primary btn-lg" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">
        <?php if ($tampilkan['jenis_po']=="PO dengan Stok"): ?>
            <a  style="color:white" href="formpostok.php?id=<?php echo $tampilkan['idpoproduk']; ?>">Link <?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>
        <?php if ($tampilkan['jenis_po']=="PO tanpa Stok"): ?>
            <a  style="color:white" href="formpoku.php?id=<?php echo $tampilkan['idpoproduk']; ?>">Link <?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>
        <?php if ($tampilkan['jenis_po']=="PO Custom Tab"): ?>
            <a  style="color:white" href="formpo_tab.php?id=<?php echo $tampilkan['idpoproduk']; ?>">Link <?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>        
        <?php if ($tampilkan['jenis_po']=="PO Konin"): ?>
            <a  style="color:white" href="formpo_konin.php">Link <?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>
        <?php if ($tampilkan['jenis_po']=="PO Kolibri"): ?>
            <a  style="color:white" href="formpo_kolibri.php">Link <?php echo $tampilkan['namapo']; ?></a>
        <?php endif ?>        

      </button>
      <p id="demomiki<?= $tampilkan['idbpo']; ?>">


      </p>
      <br>


<script>

// Mengatur waktu akhir perhitungan mundur
var countDownDatemiki<?= $tampilkan['idbpo']; ?>= new Date("<?php echo $tampilkan['tgl']; ?> 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDatemiki<?= $tampilkan['idbpo']; ?> - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demomiki<?= $tampilkan['idbpo']; ?>").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demomiki<?= $tampilkan['idbpo']; ?>").innerHTML = "Link PO tidak tersedia";
      var x = document.getElementById("linkmiki<?= $tampilkan['idbpo']; ?>");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>

<?php } ?> 

      <br><center><h3>List PO</h3></center><br> 
            
      <div class="table-responsive">
        <table class="table table-bordered">
          <tr>
            <th>Tanggal</th>
            <th>Nama PO</th>
            <th>Status</th>
              <th>Invoice</th>
            <th>List Dropship</th>
          </tr>
          <?php
          // Include / load file koneksi.php
          include "koneksi.php";
          $idmitraagen=$_SESSION['mitraagen']['idmitraagen'];
          
          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $sql = mysqli_query($koneksi, "SELECT DISTINCT pomitra.tgl,
            pomitra.status,
            pomitra.invoice,
            poproduk.namapo,
            poproduk.idpoproduk 
            FROM `pomitra` 
            inner join poproduk on pomitra.idpoproduk=poproduk.idpoproduk  
            where pomitra.idmitraagen='$idmitraagen' 
            
            and poproduk.idpoproduk <> 126

            ORDER BY pomitra.tgl DESC");
        
          while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
          ?>
            <tr>
              
              <!-- <?php if($data['status']=='Belum DP'){
                    echo "<form method='post'><a href='ubahpomax.php?invoice=$data[invoice]&id=$data[idpoproduk]' class='btn btn-success'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a> <input type='hidden' name='id' value='$data[invoice]'><button class='btn btn-danger' name='hapus' type='submit'><i class='fa fa-trash' aria-hidden='true'></i></button> <a href='datapomax.php?invoice=$data[invoice]' class='btn btn-warning'><i class='fa fa-eye' aria-hidden='true'></i></a> <a href='cetakpomax.php?invoice=$data[invoice]' class='btn btn-info'><i class='fa fa-download' aria-hidden='true'></i></a></form></td>";
                    }else {
                        echo "<a href='datapomax.php?invoice=$data[invoice]' class='btn btn-warning'><i class='fa fa-eye' aria-hidden='true'></i></a> <a href='cetakpomax.php?invoice=$data[invoice]' class='btn btn-info'><i class='fa fa-download' aria-hidden='true'></i></a>";
                    }
               ?> -->
              <td class="align-middle"><?php echo $data['tgl']; ?></td>
              <td class="align-middle"><?php echo $data['namapo']; ?></td>
              <td class="align-middle"><?php echo $data['status']; ?></td>
              <td class="align-middle">
                <?php if ($data['idpoproduk']==62) { ?>
                  <a href="datapom.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
               <?php } else if ($data['idpoproduk']==97 or $data['idpoproduk']==101) { ?>
                  
                  <a href="datapokonin.php?id=<?php echo $data['idpoproduk']; ?>&invoice=<?php echo $data['invoice']; ?>"><?php echo $data['invoice']; ?></a>
                
               <?php }else{ ?>

                <a href="datapo.php?id=<?php echo $data['idpoproduk']; ?>"><?php echo $data['invoice']; ?></a>
              <?php } ?>
              </td>
              <td class="align-middle">
              <?php
              $sql2 = mysqli_query($koneksi, "SELECT COUNT(*) as dropship FROM podropship where invoice='$data[invoice]'");
                        $dropship = mysqli_fetch_array($sql2);
              ?>
              <a href="listdropship.php?&invoice=<?php echo $data['invoice']; ?>&idmitra=<?php echo $idadmin; ?>&id=<?php echo $data['idpoproduk']; ?>"><?php echo $dropship['dropship']; ?></a>
            </td>

            </tr>
          <?php } ?>
        </table>
        
      </div><br><br>
      
      <?php
      if(isset($_POST["hapus"])){
       $id=$_POST["id"];
       $koneksi->query("DELETE FROM pomitra WHERE invoice='$id'");
                echo "<script>alert('Data berhasil Dihapus');</script>";
                    echo "<script>location='listpreorder.php';</script>";
          
      }
      ?>
      <br><br>
      
      
    </div>
  <?php include "menubawah2.php" ?> 
  

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
      
  </body>
</html>

