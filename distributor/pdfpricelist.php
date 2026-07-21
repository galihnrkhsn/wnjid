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
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
                            <th style="width:25%">Nama Artikel</th>
                              <th style="width:15%">Konsumen</th>
                              <th style="width:15%">Distributor</th>
                              <th style="width:15%">Agen</th>
                              <th style="width:15%">Reseller</th>
                              <th style="width:15%">Marketer</th>
                     
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
          <td>Rp. <?php echo number_format($distributor['harga_ecer_d']);?></td>
          <td>Rp. <?php echo number_format($distributor['harga_d']);?></td>
          <td>Rp. <?php echo number_format($distributor['harga_a']);?></td>
          <td>Rp. <?php echo number_format($distributor['harga_ecer_a']);?></td>
          <td>Rp. <?php echo number_format($marketer);?></td>
      </tr>
      <?php } ?>
      </tbody>
                    </table>
                 
<script>
window.print();
</script>

</html>

                                            