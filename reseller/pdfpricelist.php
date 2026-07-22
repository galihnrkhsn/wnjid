<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>WNJ</title>

<?php
?>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>
              
<center>
  <h2>PRICE LIST</h2>
</center>
<div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                      <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Artikel</th>
                            <!-- <th>Distributor</th>
                            <th>Agen</th> -->
                            <th>Reseller</th>
                            <th>Marketer</th>
                            <th>Konsumen</th>
                     
                        </tr>
                      </thead>
                      <tbody>
                                  <?php 
          
      $no=1;
      $ambil=$koneksi->query("SELECT * FROM pricelist order by namaartikel asc "); 
      while($distributor=$ambil->fetch_assoc()){
          
      ?>
      <tr>
          <?php 
          $marketer=$distributor['harga_ecer_d']*90/100;
          $db=$distributor['harga_ecer_d']*65/100;
          ?>
          <td><?php echo $no++; ?></td>
          <td><?php echo $distributor['namaartikel'];?></td>
          <!-- <td>Rp. <?php echo number_format($db);?></td>
          <td>Rp. <?php echo number_format($distributor['harga_a']);?></td> -->
          <td>Rp. <?php echo number_format($distributor['harga_ecer_a']);?></td>
          <td>Rp. <?php echo number_format($marketer);?></td>
          <td>Rp. <?php echo number_format($distributor['harga_ecer_d']);?></td>
      </tr>
      <?php } ?>
      </tbody>
                    </table>
                 
<script>
window.print();
</script>

</html>

                                            