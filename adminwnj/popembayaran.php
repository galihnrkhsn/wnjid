<?php 
    session_start();
    include 'koneksi.php'; 
    $idpoproduk     = $_GET['id'];
    $datapo         = $koneksi->query("SELECT poproduk.namapo, popembayaran.jenis 
                                        FROM poproduk
                                        JOIN popembayaran on poproduk.idpoproduk = popembayaran.idpoproduk
                                        WHERE poproduk.idpoproduk = '$idpoproduk'
                                    ");
    $tampilpo       = $datapo->fetch_assoc(); 
    $nama           = $tampilpo['namapo'];
    $jenis          = $tampilpo['jenis'];
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
    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<style type="text/css">
    body{
        padding-right: 0px ! important;
    }  
</style>
<body id="page-top" class="sidebar-toggled">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Content Row -->
            <div class="row">
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Pembayaran DP PO <?= $jenis  ?></h6>
                            <h6><strong><a href="listpopembayaran.php"><span class="fa fa-chevron-left"></span> Kembali</a></strong></h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div>
                                <h2><?php echo $tampilpo['namapo']; ?></h2>
                                    <div class="table-responsive">          
                                        <form method="post">         
                                            <table class="table table-bordered" id="tb_dp" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th><input type='checkbox' id='checkAll'></th>
                                                        <th>Status</th>
                                                        <th>Nama mitra</th>
                                                        <th>Nama CS</th>
                                                        <th>Invoice</th>
                                                        <?php if ($jenis=='dp'): ?>
                                                            <th>Transfer DP</th>              
                                                            <th>Bank</th>
                                                            <th>Tanggal DP</th>
                                                        <?php else: ?>
                                                            <th>Payment 1</th>              
                                                            <th>Bank Payment 1</th>
                                                            <th>Tanggal Payment 1</th>                
                                                        <?php endif ?>
                                                        <?php if ($jenis=='dp'): ?>
                                                            <th>Transfer Pelunasan</th>              
                                                            <th>Bank Pelunasan</th>
                                                            <th>Tanggal Pelunasan</th>
                                                        <?php else: ?>  
                                                            <th>Transfer Payment 2</th>              
                                                            <th>Bank Payment 2</th>
                                                            <th>Tanggal Payment 2</th>
                                                            <th>Transfer Payment 3</th>              
                                                            <th>Bank Payment 3</th>
                                                            <th>Tanggal Payment 3</th>  
                                                            <th>Transfer Payment 4</th>  
                                                            <th>Bank Payment 4</th>
                                                            <th>Tanggal Payment 4</th>   
                                                            <th>Transfer Pelunasan</th>  
                                                            <th>Bank Pelunasan</th>
                                                            <th>Tanggal Pelunasan</th>   
                                                        <?php endif ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        $idpoproduk = $_GET['id'];
                                                        $no         = 1;
                                                        $datapo     = $koneksi->query("SELECT 
                                                                                            admin_mitra_cs.namamitra,
                                                                                            admin_mitra_cs.namacs,
                                                                                            pomitra.invoice,
                                                                                            pomitra.status,
                                                                                            MAX(popembayaran.idpembayaran) as idpembayaran
                                                                                        FROM  popembayaran
                                                                                        LEFT JOIN pomitra on popembayaran.invoice = pomitra.invoice 
                                                                                        LEFT JOIN admin_mitra_cs on pomitra.idmitra = admin_mitra_cs.idadmin                               
                                                                                        WHERE pomitra.idpoproduk = '$idpoproduk'
                                                                                        AND pomitra.idmitra <>''
                                                                                        GROUP BY pomitra.invoice 
                                                                                        ORDER BY popembayaran.idpembayaran DESC
                                                                                    ");
                                                        while($tampilkan = $datapo->fetch_assoc()){
                                                            $id             = $tampilkan['idpembayaran'];
                                                            $invoice        = $tampilkan['invoice'];
                                                            $query          = "SELECT jmlhtransfer, bankpengirim, jenis, tgl
                                                                                FROM popembayaran 
                                                                                WHERE invoice = '$invoice' 
                                                                                AND (jenis = 'Payment DP 1' or jenis ='dp' or jenis IS NULL or jenis = 'Payment 1' or jenis = 'Payment1')";
                                                            $sqlpo          = mysqli_query($koneksi, $query);  
                                                            $tampilpo       = mysqli_fetch_array($sqlpo);   

                                                            $query2         = "SELECT jmlhtransfer, jmlh_lunas, bankpengirim, jenis, tgl
                                                                                FROM popembayaran 
                                                                                WHERE invoice = '$invoice' AND (jenis = 'Payment 2' or jenis = 'dp' or jenis IS NULL or jenis = 'Payment DP 2' or jenis = 'Payment2')";
                                                            $sqlpo2         = mysqli_query($koneksi, $query2);  
                                                            $tampilpo2      = mysqli_fetch_array($sqlpo2);                       
                                                            
                                                            $query3         = "SELECT jmlhtransfer, bankpengirim, jenis, tgl
                                                                                FROM popembayaran 
                                                                                WHERE invoice = '$invoice' AND (jenis = 'Payment 3' or jenis = 'Payment DP 3' or jenis = 'Payment3')";
                                                            $sqlpo3         = mysqli_query($koneksi, $query3);  
                                                            $tampilpo3      = mysqli_fetch_array($sqlpo3);  
                                                            
                                                            $query4         = "SELECT jmlhtransfer, bankpengirim, jenis, tgl
                                                                                FROM popembayaran 
                                                                                WHERE invoice = '$invoice' AND (jenis = 'Payment 4' or jenis = 'Payment DP 4' or jenis = 'Payment4')";
                                                            $sqlpo4         = mysqli_query($koneksi, $query4);  
                                                            $tampilpo4      = mysqli_fetch_array($sqlpo4);

                                                            $pelunasan      = "SELECT jmlhtransfer, bankpengirim, jenis, tgl
                                                                                FROM popembayaran 
                                                                                WHERE invoice = '$invoice' AND (jenis = 'Pelunasan' or jenis = 'Lunas')";
                                                            $pelunasan      = mysqli_query($koneksi, $pelunasan);  
                                                            $tampilpelunasan = mysqli_fetch_array($pelunasan);  
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $no++; ?></td>
                                                            <td><input type='checkbox' name='update[]' value='<?= $id ?>'></td>
                                                            <td><?php echo $tampilkan['status']; ?></td>
                                                            <td><?php echo $tampilkan['namamitra']; ?></td>   
                                                            <td><?= $tampilkan['namacs']; ?></td>                       
                                                            <td><a href="detail_popembayaran.php?id=<?= $tampilkan['invoice']; ?>"><?php echo $tampilkan['invoice']; ?></a></td>
                                                            <td><?php echo $tampilpo['jmlhtransfer']; ?></td>
                                                            <td><?php echo $tampilpo['bankpengirim']; ?></td>                          
                                                            <td><?php echo $tampilpo['tgl']; ?></td>

                                                        
                                                            <?php if ($jenis=='dp'): ?>
                                                                <td><?php echo $tampilpo2['jmlh_lunas']; ?></td>  
                                                                    <?php else: ?>                              
                                                                <td><?php echo $tampilpo2['jmlhtransfer']; ?></td>
                                                            <?php endif ?>                              
                                                            <?php if($jenis=='dp' AND $tampilpo2['jmlh_lunas']<>""): ?>
                                                                <td><?php echo $tampilpo2['bankpengirim']; ?></td>                          
                                                                <td><?php echo $tampilpo2['tgl']; ?></td>
                                                            <?php elseif($jenis=='dp' AND $tampilpo2['jmlh_lunas']==""): ?>
                                                                <td></td>
                                                                <td></td>
                                                            <?php endif ?> 
                                                            <?php if ($jenis<>'dp'): ?>
                                                            <td><?php echo $tampilpo2['bankpengirim']; ?></td>                          
                                                            <td><?php echo $tampilpo2['tgl']; ?></td>  
                                                            <td><?php echo $tampilpo3['jmlhtransfer']; ?></td>
                                                            <td><?php echo $tampilpo3['bankpengirim']; ?></td>                          
                                                            <td><?php echo $tampilpo3['tgl']; ?></td>  
                                                            <td><?php echo $tampilpo4['jmlhtransfer']; ?></td>
                                                            <td><?php echo $tampilpo4['bankpengirim']; ?></td>                          
                                                            <td><?php echo $tampilpo4['tgl']; ?></td>  
                                                            <td><?php echo $tampilpelunasan['jmlhtransfer']; ?></td>
                                                            <td><?php echo $tampilpelunasan['bankpengirim']; ?></td>                          
                                                            <td><?php echo $tampilpelunasan['tgl']; ?></td>  
                                                            <?php endif ?> 
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <input type='submit' class="btn btn-success" value='Done' name='but_update'>
                                            <input type='submit' class="btn btn-danger" value='Batal' name='but_hapus'> 
                                        </form> 
                                    </div>      
                                </div>
                            </div>
                        </div>
                    </div> 

                           
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
    <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

    <?php   
        if(isset($_POST['but_hapus'])){
            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){
                    $data           = $koneksi->query("SELECT 
                                                            invoice, jmlhtransfer, jmlh_lunas, tgl, waktu, metodebayar 
                                                        FROM popembayaran
                                                        WHERE idpembayaran = '$updateid'
                                                    ");
                    $tampilkan      = $data->fetch_assoc();
                    date_default_timezone_set('Asia/Jakarta');
                    $invoice        = $tampilkan["invoice"];  
                    $harinya        = '-1 days'; 
                    $jamnya         = '+1 hour';
                    $tgl11          = date('Y-m-d');// pendefinisian tanggal awal
                    $jam1           = date('H:i:s');
                    $tgl22          = date('Y-m-d', strtotime($harinya, strtotime($tgl11)));
                    $time           = date('H:i:s', strtotime($jamnya, strtotime($jam1)));

                    $sql            = $koneksi->query("UPDATE pomitra 
                                                        SET status = 'Belum DP', tgl = '$tgl22', waktu = '$time' 
                                                        WHERE invoice = '$invoice'
                                                    "); 
                    $delete         = "DELETE FROM popembayaran WHERE invoice = '$invoice'";
                    $sql_bayar      = mysqli_query( $koneksi, $delete); 
                }

                if ($sql) {
                    echo "<script>alert('data sudah diupdate');</script>";
                    echo "<script>location='popembayaran.php?id=$idpoproduk';</script>";
                } else {
                    echo "<script>alert('data gagal diupdate');</script>";
                    echo "<script>location='popembayaran.php?id=$idpoproduk';</script>";
                }                  
            }
        }
    ?>
    <?php 
        if(isset($_POST['but_update'])){
            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){
                    $data               = $koneksi->query("SELECT 
                                                                invoice, jmlhtransfer, jmlh_lunas, jmlh_bayar, 
                                                                tgl, waktu, metodebayar, jenis 
                                                            FROM popembayaran
                                                            WHERE idpembayaran = '$updateid'
                                                        ");
                    $tampilkan          = $data->fetch_assoc();
                    $invoice            = $tampilkan["invoice"];  
                    $jmlh_lunas         = $tampilkan["jmlh_lunas"];  
                    $bayar              = $tampilkan["jmlh_bayar"];  
                    $tgl_bayar          = $tampilkan["tgl"];
                    $waktu_bayar        = $tampilkan["waktu"];
                    $metodebayar        = $tampilkan["metodebayar"];
                    $jenis_payment      = $tampilkan["jenis"];
                    $idpoproduk         = $_GET['id'];

                    $result_explode     = explode(' ', $metodebayar);
                    $bank               = $result_explode[0];

                    if ($bank == "Bank") {
                        $bank = "BSI";
                    }

                    $tgl_explode        = explode('-', $tgl_bayar);
                    $bulan              = date("F", mktime(0, 0, 0, $tgl_explode[1], 10));
                    $hari               = $tgl_explode[2];

                    $datatf             = $koneksi->query("SELECT SUM(pomitra.total) as total,
                                                                pomitra.idmitra,
                                                                pomitra.tgl,
                                                                poproduk.namapo,
                                                                pomitra.status 
                                                            FROM pomitra
                                                            JOIN poproduk ON poproduk.idpoproduk = pomitra.idpoproduk
                                                            WHERE pomitra.invoice = '$invoice'
                                                        ");
                    $tampilkantf        = $datatf->fetch_assoc();
                    $total              = $tampilkantf["total"];
                    $idadminnya         = $tampilkantf["idmitra"];
                    $tgl_order          = $tampilkantf["tgl"];
                    $namapo             = $tampilkantf["namapo"];
                    $statuspo           = $tampilkantf["status"];
                    $diskon             = $total * 35/100;
                    $totalbayar         = $total - $diskon;

                    $kolibri = [405, 406, 407];
                    if (in_array($idpoproduk, $kolibri)) {
                        $dataTF     = $koneksi->query("SELECT SUM(jmlhtransfer) as jmlhtransfer FROM popembayaran WHERE invoice = '$invoice'");
                        $rowTF      = $dataTF->fetch_assoc();
                        $jmlhtransfer = $rowTF['jmlhtransfer'];
                    } else {
                        $jmlhtransfer       = $tampilkan["jmlhtransfer"]; 
                    }

                    if ($jmlhtransfer >= $totalbayar) {
                        $status = "Lunas";
                    } else {
                        $status = "Sudah DP";
                    }

                    $statusList = [
                        "Sudah Confirm Payment1",
                        "Sudah Confirm Payment2",
                        "Sudah Confirm Payment3",
                        "Sudah Confirm Payment4",
                        "Sudah Confirm Payment 1",
                        "Sudah Confirm Payment 2",
                        "Sudah Confirm Payment 3",
                        "Sudah Confirm Payment 4",
                        "Payment DP 1",
                        "Payment DP 2",
                        "Payment DP 3"
                    ];

                    if ($statuspo == "Sudah Confirm Pelunasan") {
                        $sql = $koneksi->query("UPDATE pomitra SET status = '$status' WHERE invoice = '$invoice';"); 
                        if ($sql) {
                            $datatf     = $koneksi->query("SELECT jmlh_tambah, jmlh_lunas, metodebayar 
                                                            FROM popembayaran
                                                            WHERE invoice = '$invoice'");
                            $tampiltf   = $datatf->fetch_assoc();
                            $tambahan   = $tampiltf['jmlh_tambah'];

                            if ($tampiltf['jmlh_tambah'] == "") {
                                $koneksi->query("INSERT INTO saldo (id_saldo,idadmin,tgl,transaksi,debit,credit)
                                                    VALUES (null,'$idadminnya','$tgl_bayar','Bayar Pelunasan $bank $hari $bulan $namapo #$invoice','$jmlh_lunas','0')
                                                ");
                            } else {
                                $koneksi->query("INSERT INTO saldo (id_saldo,idadmin,tgl,transaksi,debit,credit)
                                                    VALUES (null,'$idadminnya','$tgl_bayar','Bayar Pelunasan $bank $hari $bulan $namapo #$invoice','$tambahan','0')");
                            }    
                        }
                    } elseif (in_array($statuspo, $statusList)) {
                        $sql    = $koneksi->query("UPDATE pomitra SET status = 'Sudah $jenis_payment' WHERE invoice = '$invoice';");     
                        $koneksi->query("UPDATE popembayaran SET ket = 'Done' WHERE idpembayaran = '$updateid';"); 
                    } elseif ($statuspo == "Sudah Confirm DP"){
                        $sql = $koneksi->query("UPDATE pomitra SET status='$status' WHERE invoice='$invoice';");     
                        if ($sql) {
                            $koneksi->query("INSERT INTO saldo (id_saldo,idadmin,tgl,transaksi,debit,credit)
                                                VALUES (null,'$idadminnya','$tgl_order','Invoice $namapo #$invoice','0','$totalbayar')");

                            $koneksi->query("INSERT INTO saldo (id_saldo,idadmin,tgl,transaksi,debit,credit)
                                                VALUES (null,'$idadminnya','$tgl_bayar','Bayar DP $bank $hari $bulan $namapo #$invoice','$jmlhtransfer','0')");
                        }
                    } elseif ($statuspo = 'Pelunasan') {
                        $sql = $koneksi->query("UPDATE pomitra SET status = 'Lunas' WHERE invoice = '$invoice';"); 
                        // if ($sql) {
                        //     $datatf     = $koneksi->query("SELECT jmlh_tambah, jmlh_lunas, metodebayar 
                        //                                     FROM popembayaran
                        //                                     WHERE invoice = '$invoice'");
                        //     $tampiltf   = $datatf->fetch_assoc();
                        //     $tambahan   = $tampiltf['jmlh_tambah'];

                        //     if ($tampiltf['jmlh_tambah'] == "") {
                        //         $koneksi->query("INSERT INTO saldo (id_saldo,idadmin,tgl,transaksi,debit,credit)
                        //                             VALUES (null,'$idadminnya','$tgl_bayar','Bayar Pelunasan $bank $hari $bulan $namapo #$invoice','$jmlh_lunas','0')
                        //                         ");
                        //     } else {
                        //         $koneksi->query("INSERT INTO saldo (id_saldo,idadmin,tgl,transaksi,debit,credit)
                        //                             VALUES (null,'$idadminnya','$tgl_bayar','Bayar Pelunasan $bank $hari $bulan $namapo #$invoice','$tambahan','0')");
                        //     }    
                        // }
                    }
                }

                if ($sql) {
                    echo "<script>alert('data sudah diupdate');</script>";
                    echo "<script>location='popembayaran.php?id=$idpoproduk';</script>";
                }else{
                    echo "<script>alert('data gagal diupdate');</script>";
                    echo "<script>location='popembayaran.php?id=$idpoproduk';</script>";
                }     
            }
        }        
    ?> 

  <?php include "settingdatatables.php"; ?>
<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_dp').DataTable({
        "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
          columnDefs: [
    { orderable: false, targets: 1 }
  ]
});
} );
</script>                  

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