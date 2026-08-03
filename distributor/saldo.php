<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-4">
        <?php 
            $mitra = $_SESSION['idadmin'];
            if ($mitra == 233) {
                $ambil = $koneksi->query("SELECT (sum(saldo.debit) - sum(saldo.credit)) AS selisih FROM saldo  
                    WHERE idadmin = 233
                    AND saldo.transaksi NOT LIKE '%Fee Order Agen%'  
                    AND saldo.transaksi NOT LIKE '%Fee Order Reseller%'
                    AND saldo.transaksi NOT LIKE '%Fee Order marketer%'
                    GROUP BY idadmin ORDER BY selisih DESC"); 
            } else {
                $ambil = $koneksi->query("SELECT (sum(debit) - sum(credit)) AS selisih 
                                            FROM saldo 
                                            WHERE idadmin = '$mitra' 
                                            AND deleted_at IS NULL
                                        "); 
            }

            while($data = $ambil->fetch_assoc()) {
        ?>

        <div class="card mb-4">
            <div class="card-body text-center">
                <h3>Sisa Saldo <br>Rp. <?php echo number_format($data['selisih']); ?></h3>
            </div>
        </div>

        <?php } ?>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Transaksi</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $mitra = $_SESSION['idadmin'];
                    if ($mitra == 233) {
                        $ambil = $koneksi->query("SELECT * FROM saldo WHERE idadmin = 233
                            AND saldo.transaksi NOT LIKE '%Fee Order Agen%'  
                            AND saldo.transaksi NOT LIKE '%Fee Order Reseller%'
                            AND saldo.transaksi NOT LIKE '%Fee Order marketer%'
                            ORDER BY id_saldo ASC"); 
                    } else {
                        $ambil = $koneksi->query("SELECT * FROM saldo 
                                                    WHERE idadmin = '$mitra' 
                                                    AND deleted_at IS NULL
                                                    AND (credit > 0 OR debit > 0) 
                                                    ORDER BY id_saldo ASC
                                                "); 
                    }

                    while($data = $ambil->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $no++;?></td>
                        <td><?php echo $data['tgl'];?></td>
                        <td><?php echo $data['transaksi'];?></td>
                        <td>Rp. <?php echo number_format($data['debit']);?></td>
                        <td>Rp. -<?php echo number_format($data['credit']);?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include 'settingdatatables.php'; ?>

    <!-- MAIN CONTENT END -->
    
    <br><br><br><br>
    
    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP SYNTAK -->

    <!-- PHP SYNTAK END -->
    
    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>