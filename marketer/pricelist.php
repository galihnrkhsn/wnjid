<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesMarketer.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketer | WNJ.ID</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar2.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container pt-3">
        <div class="text-center mb-3">
            <a class="btn btn-primary" href="pdfpricelist.php" target="_blank"><i class="fas fa-file-export"></i> Export</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover pt-2" id="tb_store">
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
                    $ambil=$koneksi->query("SELECT * FROM pricelist order by namaartikel asc "); 
                    $no = 1;
                    while($distributor=$ambil->fetch_assoc()){
                    ?>
                    <tr>
                        <?php $marketer=$distributor['harga_ecer_d']*90/100;
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