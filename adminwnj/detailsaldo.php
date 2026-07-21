<?php
    session_start();
    include 'koneksi.php'; 


    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }
    $idmitra = $_GET['nama']; 
?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin Pusat | Wanoja</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top" class="sidebar-toggled">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Content Row -->
                    <div class="row">
                        <h3><strong>Saldo Mitra</strong></h3>
                        <div class="table-responsive">
                            <a href="excel_saldo.php?nama=<?= $idmitra; ?>" class="btn btn-success" target="blank_()">Excel</a>
                            <ul class="nav nav-tabs mt-3" id="saldoTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="saldo-aktif-tab" data-toggle="tab" href="#saldo-aktif" role="tab">Saldo Aktif</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="saldo-deleted-tab" data-toggle="tab" href="#saldo-deleted" role="tab">Saldo Terhapus</a>
                                </li>
                            </ul>
                            <div class="tab-content mt-3" id="saldoTabContent">
                                <!-- Tab Active -->
                                <div class="tab-pane fade show active" id="saldo-aktif" role="tabpanel">
                                    <form method="post">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th><input type='checkbox' id='checkAll' > Check</th>
                                                    <th>No</th>
                                                    <th>Nama Mitra</th>
                                                    <th>Tanggal</th>
                                                    <th>Keterangan</th>
                                                    <th>Masuk</th>
                                                    <th>Keluar</th>
                                                    <th>Saldo</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    if ($idmitra == 233) {
                                                        $datamitra = $koneksi->query("SELECT admin_mitra.namamitra,
                                                                                            saldo.id_saldo, saldo.tgl, saldo.transaksi,
                                                                                            saldo.debit, saldo.credit 
                                                                                        FROM saldo 
                                                                                        INNER JOIN admin_mitra ON saldo.idadmin = admin_mitra.idadmin 
                                                                                        WHERE admin_mitra.idadmin = '$idmitra' 
                                                                                        AND saldo.transaksi NOT LIKE '%Fee Order Agen%'  
                                                                                        AND saldo.transaksi NOT LIKE '%Fee Order Reseller%'
                                                                                        AND saldo.transaksi NOT LIKE '%Fee Order marketer%'
                                                                                        ORDER BY `saldo`.`id_saldo` ASC 
                                                                                    ");
                                                        $datasisa   = $koneksi->query("SELECT admin_mitra.namamitra,
                                                                                            saldo.debit,
                                                                                            saldo.credit,
                                                                                            (sum(saldo.debit) - sum(saldo.credit)) AS sisa 
                                                                                        FROM saldo 
                                                                                        INNER JOIN admin_mitra ON saldo.idadmin=admin_mitra.idadmin 
                                                                                        WHERE admin_mitra.idadmin= '$idmitra' 
                                                                                        AND saldo.transaksi NOT LIKE '%Fee Order Agen%'  
                                                                                        AND saldo.transaksi NOT LIKE '%Fee Order Reseller%'
                                                                                        AND saldo.transaksi NOT LIKE '%Fee Order marketer%'
                                                                                    ");                            
                                                        $no         = 1;
                                                        $tampilin   = $datasisa->fetch_assoc();
                                                    } else{
                                                        $datamitra  = $koneksi->query("SELECT admin_mitra.namamitra,
                                                                                            saldo.id_saldo, saldo.tgl, saldo.transaksi,
                                                                                            saldo.debit, saldo.credit 
                                                                                        FROM saldo 
                                                                                        LEFT JOIN admin_mitra ON saldo.idadmin = admin_mitra.idadmin 
                                                                                        WHERE saldo.idadmin = '$idmitra' 
                                                                                        AND (saldo.credit > 0 OR saldo.debit > 0)
                                                                                        AND saldo.deleted_at IS NULL
                                                                                        ORDER BY `saldo`.`id_saldo` ASC");
                                        
                                                        $datasisa   = $koneksi->query("SELECT (SUM(saldo.debit) - SUM(saldo.credit)) as sisa
                                                                                        FROM saldo 
                                                                                        WHERE saldo.idadmin = '$idmitra' 
                                                                                        AND saldo.deleted_at IS NULL
                                                                                    ");                            
                                                        $no = 1;
                                                        $tampilin = $datasisa->fetch_assoc();
                                                    }
                                                    while($tampilkan = $datamitra->fetch_assoc()){
                                                    $id = $tampilkan['id_saldo'];
                                                ?>
                                                    <tr>
                                                        <td><input type='checkbox' name='update[]' value='<?= $id ?>' ></td>
                                                        <td><?php echo $no++; ?></td>
                                                        <td>
                                                            <?php echo $tampilkan['namamitra']; ?>
                                                            <!-- (<?php echo $tampilkan['id_saldo']; ?>) -->
                                                        </td>
                                                        <td><?php echo $tampilkan['tgl']; ?></td>
                                                        <td><?php echo nl2br($tampilkan['transaksi']); ?></td>
                                                        <td><?php echo number_format($tampilkan['debit']); ?></td>
                                                        <td><?php echo number_format($tampilkan['credit']); ?></td>
                                                        <td>
                                                            <?php 
                                                                $finalsaldo = $finalsaldo + $tampilkan['debit'] - $tampilkan['credit'];
                                                                echo number_format($finalsaldo); 
                                                            ?>
                                                        </td>
                                                        <td><a href="editsaldo.php?id_saldo=<?php echo $tampilkan['id_saldo']; ?>">Edit</a></td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="7"><strong>SISA SALDO</strong></td>
                                                    <td colspan="2"><strong>Rp. <?php echo number_format($tampilin['sisa']); ?></strong></td>
                                                </tr>    
                                            </tbody>
                                        </table>
                                        <input type='submit' class="btn btn-danger" value='Hapus Data' name='but_hapus' onclick="return confirm('Yakin Akan Hapus Data?');">                    
                                    </form>
                                </div>
                                <!-- Tabs Deleted -->
                                <div class="tab-pane fade" id="saldo-deleted" role="tabpanel">
                                    <form method="post">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th><input type='checkbox' id='checkAll' > Check</th>
                                                    <th>No</th>
                                                    <th>Nama Mitra</th>
                                                    <th>Tanggal</th>
                                                    <th>Keterangan</th>
                                                    <th>Masuk</th>
                                                    <th>Keluar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $no_hapus         = 1;
                                                    if ($idmitra == 233) {
                                                        $datamitra = $koneksi->query("SELECT admin_mitra.namamitra,
                                                                                            saldo.id_saldo, saldo.tgl, saldo.transaksi,
                                                                                            saldo.debit, saldo.credit 
                                                                                        FROM saldo 
                                                                                        INNER JOIN admin_mitra ON saldo.idadmin = admin_mitra.idadmin 
                                                                                        WHERE admin_mitra.idadmin = '$idmitra' 
                                                                                        AND saldo.transaksi NOT LIKE '%Fee Order Agen%'  
                                                                                        AND saldo.transaksi NOT LIKE '%Fee Order Reseller%'
                                                                                        AND saldo.transaksi NOT LIKE '%Fee Order marketer%'
                                                                                        ORDER BY `saldo`.`id_saldo` ASC 
                                                                                    ");
                                                        $datasisa   = $koneksi->query("SELECT admin_mitra.namamitra,
                                                                                            saldo.debit,
                                                                                            saldo.credit,
                                                                                            (sum(saldo.debit) - sum(saldo.credit)) AS sisa 
                                                                                        FROM saldo 
                                                                                        INNER JOIN admin_mitra ON saldo.idadmin=admin_mitra.idadmin 
                                                                                        WHERE admin_mitra.idadmin= '$idmitra' 
                                                                                        AND saldo.transaksi NOT LIKE '%Fee Order Agen%'  
                                                                                        AND saldo.transaksi NOT LIKE '%Fee Order Reseller%'
                                                                                        AND saldo.transaksi NOT LIKE '%Fee Order marketer%'
                                                                                    ");
                                                        $tampilin   = $datasisa->fetch_assoc();
                                                    } else{
                                                        $datamitra  = $koneksi->query("SELECT admin_mitra.namamitra,
                                                                                            saldo.id_saldo, saldo.tgl, saldo.transaksi,
                                                                                            saldo.debit, saldo.credit 
                                                                                        FROM saldo 
                                                                                        LEFT JOIN admin_mitra ON saldo.idadmin = admin_mitra.idadmin 
                                                                                        WHERE saldo.idadmin = '$idmitra' 
                                                                                        AND (saldo.credit > 0 OR saldo.debit > 0)
                                                                                        AND saldo.deleted_at IS NOT NULL
                                                                                        ORDER BY `saldo`.`id_saldo` ASC");
                                                    }
                                                    while($tampilkan = $datamitra->fetch_assoc()){
                                                    $id = $tampilkan['id_saldo'];
                                                ?>
                                                    <tr>
                                                        <td><input type='checkbox' name='update[]' value='<?= $id ?>' ></td>
                                                        <td><?php echo $no_hapus++; ?></td>
                                                        <td>
                                                            <?php echo $tampilkan['namamitra']; ?>
                                                            <!-- (<?php echo $tampilkan['id_saldo']; ?>) -->
                                                        </td>
                                                        <td><?php echo $tampilkan['tgl']; ?></td>
                                                        <td><?php echo nl2br($tampilkan['transaksi']); ?></td>
                                                        <td><?php echo number_format($tampilkan['debit']); ?></td>
                                                        <td><?php echo number_format($tampilkan['credit']); ?></td>                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <input type='submit' class="btn btn-warning" value='Kembalikan Data' name='but_recover' onclick="return confirm('Yakin Akan Mengembalikan Data?');">                    
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php 
                            if(isset($_POST['but_hapus'])){
                                if(isset($_POST['update'])){
                                    $ids        = array_map('intval', $_POST['update']);
                                    $idList     = implode(',', $ids);

                                    $deleteSaldo  = $koneksi->query("UPDATE saldo SET deleted_at = NOW() WHERE id_saldo IN ($idList)");

                                    if ($deleteSaldo) {
                                        echo "<script>alert('data berhasil dihapus');</script>";
                                        echo "<script>location='detailsaldo.php?nama=$idmitra';</script>";  
                                    } else {
                                        echo "<script>alert('data gagal dihapus');</script>";
                                        echo "<script>location='detailsaldo.php?nama=$idmitra';</script>";
                                    }  
                                }   
                            } elseif (isset($_POST['but_recover'])) {
                                if (isset($_POST['update'])) {
                                    $ids            = array_map('intval', $_POST['update']);
                                    $idList         = implode(',', $ids);
                                    
                                    $recoverSaldo   = $koneksi->query("UPDATE saldo SET deleted_at = NULL WHERE id_saldo IN ($idList)");

                                    if ($recoverSaldo) {
                                        echo "<script>alert('data berhasil direcovery');</script>";
                                        echo "<script>location='detailsaldo.php?nama=$idmitra';</script>";  
                                    } else {
                                        echo "<script>alert('data gagal direcovery');</script>";
                                        echo "<script>location='detailsaldo.php?nama=$idmitra';</script>";
                                    }
                                }
                            }
                        ?>
                        <!-- Footer -->
                        <footer class="sticky-footer bg-white">
                            <div class="container my-auto">
                                <div class="copyright text-center my-auto">
                                    <span>Copyright &copy; Your Website 2020</span>
                                </div>
                            </div>
                        </footer>
                        <!-- End of Footer -->
                    </div>
                </div>
            </div>
            <!-- End of Content Wrapper -->
        </div>
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>


    <script type="text/javascript">
        $(document).ready(function(){

            // Check/Uncheck ALl
            $('#checkAll').change(function(){
                if($(this).is(':checked')){
                    $('input[name="update[]"]').prop('checked',true);
                }else{
                    $('input[name="update[]"]').each(function(){
                        $(this).prop('checked',false);
                    }); 
                }
            });

            // Checkbox click
            $('input[name="update[]"]').click(function(){
                var total_checkboxes = $('input[name="update[]"]').length;
                var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

                if(total_checkboxes_checked == total_checkboxes){
                    $('#checkAll').prop('checked',true);
                }else{
                    $('#checkAll').prop('checked',false);
                }
            });
        });
    </script>
</body>

</html>

                        