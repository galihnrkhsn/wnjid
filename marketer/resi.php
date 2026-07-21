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
    <title>Marketer | Wanoja</title>
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
    <div class="container">
        <div class="text-center mt-3">
            <h6 class="outset">Menu informasi resi pengiriman semua ekspedisi Wanoja dan Zizazu</h6>
        </div>

        <br>
        <div class="text-right">
            <input type="radio" onclick="javascript:window.location.href='resi.php';" checked="checked"> Semua Resi &nbsp;&nbsp;
            <input type="radio" onclick="javascript:window.location.href='resi_db.php';"> Resi Per DB
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="example">
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
                        $idadmin = $_SESSION['idadmin'];
                        // Fetching the last 1000 records from the logistik table
                        $query = "SELECT * FROM logistik where idadmin = $idadmin ORDER BY idlogistik DESC LIMIT 1000 ";
                        $result = $konek->query($query);

                        // Initializing the counter
                        $no = 1;

                        // Looping through the fetched records and displaying them in the table
                        while ($row = $result->fetch_assoc()) {
                            // Formatting the date
                            $formattedDate = date('d/m/Y', strtotime($row["tgl"]));

                            // Formatting the shipping cost
                            $formattedCost = number_format($row["biayakirim"]);
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row["penerima"]); ?></td>
                            <td><?php echo $formattedDate; ?></td>
                            <td><?php echo htmlspecialchars($row['ekspedisi']); ?></td>
                            <td><?php echo htmlspecialchars($row["noresi"]); ?></td>
                            <td>Rp.<?php echo $formattedCost; ?></td>
                            <td><?php echo htmlspecialchars($row["keterangan"]); ?></td>
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
    <? include "settingdatatables.php"; ?>
    <!-- PHP END -->

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>