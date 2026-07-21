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
    <? include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container">
        <div class="text-center mt-3">
            <h6 class="outset">Menu informasi resi pengiriman semua ekspedisi Wanoja dan Zizazu</h6>
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
                            $ambil = $koneksi->query("SELECT * FROM logistik3 WHERE idadmin = '$idadmin' AND jenis_mitra = 'WNJ' ORDER BY idlogistik DESC LIMIT 1000");
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
            <div id="cekresicom_id" class="text-center"></div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>
    
    <!-- FOOTER -->
    <? include 'menubawah.php';?>
    <!-- FOOTER END -->
    <!-- PHP SYNTAK -->

    <!-- PHP SYNTAK END -->
    
    <!-- SCRIPT -->
    <script type="text/javascript" src="https://cekresi.com/widget/widgetcekresicom_v1.js"></script>
    <script type="text/javascript">
        init_widget_cekresicom('w1',380,110)
    </script>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>