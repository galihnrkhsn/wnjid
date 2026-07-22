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

  <title>Admin Pusat | Wanoja</title>

<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Sisa Saldo WNJ.xls");
?>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>
              

<div class="table-responsive">
<table class="table table-striped table-bordered table-hover" id="tb_sisasaldo">
                      <thead>
                        <tr>
                          <th width="5%">No</th>
                          <th>Nama CS</th>
                            <th style="text-align: center;" width="20%">
                            Nama Distributor
                          </th>
                          <th style="text-align: center;" width="20%">
                          Sisa Saldo
                        </th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
        $totalsaldo=0;
      
            $datamitra=$koneksi->query("SELECT admin_mitra_cs.namamitra,admin_mitra_cs.namacs,saldo.debit,saldo.credit,(sum(saldo.debit) - sum(saldo.credit)) AS sisa FROM saldo inner join admin_mitra ON 
            saldo.idadmin=admin_mitra_cs.idadmin GROUP BY admin_mitra_cs.namamitra order by sisa desc ");
          $no=1;
        while($tampilkan=$datamitra->fetch_assoc()){
        ?>
                        <tr>
                          <td style="text-align: left;"><b><?php echo $no++; ?></b></td>
                          <td style="text-align: left;"><?= $tampilkan['namacs'] ?></td>
                          <td style="text-align: left;">
                            <i class="fas fa-user"></i> <?php echo $tampilkan['namamitra']; ?>
                          </td>
                          <td style="text-align: center;">
                           <i class="fas fa-money"></i> <a href="detailsaldo.php?nama=<?php echo $tampilkan['namamitra']; ?>">Rp. <?php echo number_format($tampilkan['sisa']); ?></a>
                          </td>
                        </tr>
                        <?php $totalsaldo=$totalsaldo+$tampilkan['sisa']; } ?>
                      </tbody>
                     
                    </table>
                 

</html>

		                                    