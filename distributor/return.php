<?php 
session_start();

include 'koneksi.php'; 
include 'assets/components/Sessions/sesDistri.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

<meta property="og:image:alt" content="A shiny red apple with a bite taken out" />

<link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/bootstrap2.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  
  
<style>
    body {
      background-color: #f8f9fa;
    }

    .container {
      margin-top: 50px;
    }
</style>

</head>
<body>
  <!-- NAVBAR -->
  <?php include "assets/components/Navbar/navbar.php" ?>
  <!-- END NAVBAR -->
  <!-- MAIN SECTION -->
  <div class="container">
    <h1 class="text-center mb-4">Support Ticket</h1>
    <div class="mb-3">
      <a href="formST.php?action=create" class="btn btn-primary">Masukan Support Ticket</a>
    </div>
    <div class="table-container">
      <table class="table table-striped table-bordered table-hover">
        <thead class="bg-dark">
          <tr>
            <th scope="col">Tanggal</th>
            <th scope="col">Masalah</th>
            <th scope="col">Nama CS</th>
            <th scope="col">Note</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
        <?php 
            $idadmin=$_SESSION["admin_mitra"]["idadmin"];				                  
            $ambil=$koneksi->query("SELECT * FROM support_ticket where idadmin='$idadmin' order by tgl desc"); 
            while($data=$ambil->fetch_assoc()){

              $statusClass = '';
              if ($data['status'] == 'Ticket Diajukan') {
                  $statusClass = 'text-bg-warning';
              } else if ($data['status'] == 'Ticket Selesai') {
                  $statusClass = 'text-bg-success';
              }
            ?>
          <tr>
              <td><?php echo $data['tgl']; ?></td>
              <td><?php echo $data['masalah'];?></td>
              <td><?php echo $data['namacs'];?></td>
              <td><?php echo $data['note'];?></td>
              <td><span class="badge <?php echo $statusClass; ?>"><?php echo $data['status']; ?></span></td>
              <td>
                <a href="formST.php?action=edit&idsupport=<?php echo $data['idsupport']?>" class="btn btn-warning">Edit</a>
                <a href="hapusreturn?idsupport=<?php echo $data['idsupport']?>" class="btn btn-danger">Hapus</a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <!-- END MAIN SECTION -->

			    
			
<script>
    const hamMenu = document.querySelector('.ham-menu');
    const offScreenMenu = document.querySelector('.off-screen-menu');

    hamMenu.addEventListener('click', () => {
        hamMenu.classList.toggle('active');
        offScreenMenu.classList.toggle('active');
      });
</script>
</body>
</html>