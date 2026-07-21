<?php
    session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesDistri.php';

    $idadmin=$_SESSION["idadmin"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <? include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container">
        <center>
            <h6 style="font-family:verdana;" class="outset">
                Menu informasi resi pengiriman semua ekspedisi wanoja dan zizazu
            </h6>
        </center>

        <?php
        error_reporting(0);
        // $konek = new mysqli("localhost", "wnj218", "Padasuk@218", "db_portalwnj");
        $tgl = $_GET['id'];
        ?>
        
        <br>

        <p align="right">
            <input type="radio" onclick="javascript:window.location.href='resi';"> Semua Resi &nbsp;&nbsp;
            <input type="radio" onclick="javascript:window.location.href='resi_db';" checked="checked"> Resi Per DB
        </p>

        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="example">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Penerima</th>
                            <th>Tanggal</th>
                            <th>Ekpedisi</th>
                            <th>No Resi</th>
                            <th>Biaya Kirim</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ambil = $koneksi->query("SELECT * FROM logistik WHERE idadmin = '$idadmin' ORDER BY idlogistik DESC LIMIT 1000");
                        $no = 1;
                        while ($tampil = $ambil->fetch_assoc()) {
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

            <div id="cekresicom_id" align="center"></div>
            <script type="text/javascript" src="https://cekresi.com/widget/widgetcekresicom_v1.js"></script>
            <script type="text/javascript">
                init_widget_cekresicom('w1', 380, 110);
            </script>
        </div>

        <br><br><br><br>
        <?php include "menubawah.php"; ?>

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
        <script src="../adminwnj/assets/dist/js/jquery.min.js"></script>
        <script src="../adminwnj/assets/dist/js/bootstrap.min.js"></script>
        <script src="../adminwnj/assets/dist/DataTables/datatables.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $('#example').DataTable({
                    "pageLength": 25,
                    "language": {
                        "decimal": "",
                        "emptyTable": "Tidak ada data di dalam tabel",
                        "info": "Ditampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        "infoEmpty": "Ditampilkan 0 sampai 0 dari 0 data",
                        "infoFiltered": "(Disaring dari _MAX_ total data)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Tampilkan _MENU_ Data",
                        "loadingRecords": "Memuat...",
                        "processing": "Pemrosesan...",
                        "search": "Cari Data:",
                        "zeroRecords": "Data yang dicari tidak ditemukan",
                        "paginate": {
                            "first": "Awal",
                            "last": "Akhir",
                            "next": "&#10095;",
                            "previous": "&#10094;"
                        }
                    }
                });
            });
        </script>
    </div>

    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <!-- PHP END -->

    <!-- FOOTER -->
    <? include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <!-- END SCRIPT -->
</body>
</html>