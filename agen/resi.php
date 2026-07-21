<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesAgen.php';
    include "settingdatatables.php";

    $idmitraagen    = $_SESSION["idmitraagen"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resi WNJ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container">
        <div class="text-center mt-3">
            <h6 class="outset">Menu informasi resi pengiriman semua ekspedisi Wanoja dan Zizazu</h6>
        </div>

        <br>
        <div class="text-right">
            <input type="radio" onclick="javascript:window.location.href='resi.php';" checked="checked"> Semua Resi &nbsp;&nbsp;
            <!-- <input type="radio" onclick="javascript:window.location.href='resi_db.php';"> Resi Per DB -->
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="tbresi">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Penerima</th>
                            <th>Tanggal</th>
                            <th>Ekspedisi</th>
                            <th>No Resi</th>
                            <th>Biaya Kirim</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $ambil = $koneksi->query("SELECT * FROM logistik3 WHERE idmitraagen = '$idmitraagen' ORDER BY idlogistik DESC LIMIT 1000");
                            $no = 1;
                            while ($tampil = $ambil->fetch_assoc()) {
                            // Formatting the date
                            $formattedDate = date('d/m/Y', strtotime($tampil["tgl"]));

                            // Formatting the shipping cost
                            $formattedCost = number_format($tampil["biayakirim"]);
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $tampil["penerima"]; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($tampil["tgl"])); ?></td>
                            <td><?php echo $tampil['ekspedisi']; ?></td>
                            <td><?php echo $tampil["noresi"]; ?></td>
                            <td>Rp.<?php echo number_format($tampil["biayakirim"]); ?></td>
                            <td><?php echo $tampil["keterangan"]; ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div id="cekresicom_id" class="text-center"></div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- FOOTER -->
    <? include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP -->
    <script type="text/javascript">
        $(document).ready( function () {
            $('#tbresi').DataTable();
        } );
    </script>
    <!-- PHP END -->

    <!-- SCRIPT -->
    <!-- SCRIPT END -->
</body>
</html>