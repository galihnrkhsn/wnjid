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
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>
                    <!-- Content Row -->
                    <h3><strong>Order Pembayaran</strong></h3>
                    <div class="form-group">
                        <div class="form-group">
                            <label>Jenis Mitra</label>
                            <select class="form-control" name="db" id="db">
                                <option enabled selected>- Pilih Jenis Mitra -</option>
                                <option value="Distributor">Distributor</option>
                                <option value="Agen">Agen</option>                      
                                <option value="Reseller">Reseller</option>
                                <option value="Marketer">Marketer</option>
                            </select>      
                        </div> 
                        <form method="post">
                            <div class="form-group"  id="tabel" name="tabel"></div>    
                        </form>
                        <?php
                            if(isset($_POST["done"]) && isset($_POST['selected_ids'])){
                                $selected_id                = $_POST['selected_ids'];

                                foreach ($selected_id as $idorder) {
                                    $updateStatus       = $koneksi->query("UPDATE ordermitra set payment = 'Lunas', status = 'Proses', status_progres = 0 WHERE idorder = '$idorder'");

                                    if (!$updateStatus) {
                                        echo "<script>alert('Gagal mengirim Pembayaran!');</script>";
                                        echo "<script>location='pembayaran.php'</script>";
                                    }

                                    $dataqty            = $koneksi->query("SELECT SUM(jumlah) as jumlah_qty, invoice, idmitra, tgl FROM ordermitra WHERE idorder = '$idorder' ");
                                    $tampil_qty         = $dataqty->fetch_assoc();

                                    $invoicenya         = $tampil_qty['invoice'];
                                    $idmitranya         = $tampil_qty['idmitra'];
                                    $tglorder           = $tampil_qty['tgl'];
                                    $total_qty          = $tampil_qty['jumlah_qty'];
                                    $total              = 0;

                                    if (substr($invoicenya,0,1)=="F") {
                                        $jumlah_produknya   = $total_qty/2;
                                        $sql                = "SELECT variants.harga as subtotal
                                                                FROM ordermitra 
                                                                INNER JOIN variants ON variants.id = ordermitra.idproduk 
                                                                LEFT JOIN products ON products.id = variants.idproducts
                                                                WHERE ordermitra.invoice = '$invoicenya' 
                                                                AND ordermitra.jumlah > 0 
                                                                AND products.idkategori BETWEEN 11 AND 13
                                                                ORDER BY variants.harga desc
                                                                LIMIT ".$jumlah_produknya."
                                                            ";
                                    } else {
                                        $sql    = "SELECT subtotal
                                                    FROM ordermitra 
                                                    INNER JOIN variants ON variants.id = ordermitra.idproduk
                                                    INNER JOIN products ON products.id = variants.idproducts
                                                    WHERE ordermitra.invoice = '$invoicenya' 
                                                    AND ordermitra.jumlah > 0 
                                                    AND products.idkategori > 0 
                                                    AND products.idkategori <> 2
                                                ";
                                    }

                                    $query      = $koneksi->query($sql);
                                    while ($ga = $query->fetch_assoc()){
                                        $total +=  $ga['subtotal']; 
                                    }

                                    $totald17       = 0;
                                    $sql            = "SELECT * FROM ordermitra 
                                                        INNER JOIN variants on variants.id = ordermitra.idproduk 
                                                        INNER JOIN products ON products.id = variants.idproducts
                                                        WHERE ordermitra.invoice = '$invoicenya' 
                                                        AND ordermitra.jumlah > 0 
                                                        AND products.idkategori = 17";

                                    $query          = $koneksi->query($sql);
                                    while ($d17     = $query->fetch_assoc()) {
                                    $totald17       += $d17['subtotal']; }
                                    $diskon17       = $totald17*17/100;

                                    $totald20       = 0;
                                    $sql20          = "SELECT * FROM ordermitra 
                                                        INNER JOIN variants ON variants.id = ordermitra.idproduk 
                                                        INNER JOIN products ON products.id = variants.idproducts
                                                        WHERE ordermitra.invoice = '$invoicenya' 
                                                        and ordermitra.jumlah > 0 
                                                        and products.idkategori = 20";

                                    $query20        = $koneksi->query($sql20);
                                    while ($d20     = $query20->fetch_assoc()){
                                        $totald20       += $d20['subtotal']; 
                                    }
                                    $diskon20       = $totald20*20/100;
                                    $diskon         = 0;
                                    $diskon         = $total * 35/100;

                                    $dataongkir     = $koneksi->query("SELECT berat, ongkir, dropship
                                                                        FROM orderpengiriman 
                                                                        WHERE invoice = '$invoicenya' 
                                                                    ");
                                    $tampilongkir   = $dataongkir->fetch_assoc();

                                    $berat          = $tampilongkir['berat'];
                                    $ongkir         = $tampilongkir['ongkir'];
                                    $dropship       = $tampilongkir['dropship'];
                                    $biayds         = 0;

                                    if($berat <= 5000 and $berat >= 0 and $dropship == 'ya') {
                                        $biayds = 3000;
                                    } elseif ($berat <= 10000 and $berat >= 6000 and $dropship == 'ya') {
                                        $biayds = 5000;
                                    }  

                                    $totalsemua     = 0;
                                    $totalharga     = $total - ($diskon + $diskon17 + $diskon20);
                                    $totalsemua     = ($total + $ongkir + $biayds) - ($diskon + $diskon17 + $diskon20);

                                    $datadb         = $koneksi->query("SELECT tgl, jmlhtransfer,metodebayar FROM orderpembayaran WHERE invoice='$invoicenya' ");
                                    $tampildb       = $datadb->fetch_assoc();
                                    
                                    $jmlhtransfer   = $tampildb['jmlhtransfer']; 
                                    $tglbayar       = $tampildb['tgl'];
                                    $metodeFull     = $tampildb['metodebayar'];
                                    $metodebayar    = preg_replace('/\b\d+\b/', '', $metodeFull);
                                    $metodebayar    = trim(preg_replace('/\s+/', ' ', $metodebayar));

                                    $data = [
                                        "(NULL, '$idmitranya', NOW(), 'Order Invoice $invoicenya', 0, '$totalharga', NULL)",
                                        "(NULL, '$idmitranya', NOW(), 'Biaya DS Invoice $invoicenya', 0, '$biayds', NULL)"
                                    ];

                                    if ($metodebayar != 'Ambil Dari Saldo') {
                                        $data[] = "(NULL, '$idmitranya', NOW(), 'Pembayaran Transfer Order Invoice $invoicenya, Metode $metodebayar, Tanggal $tglbayar', '$jmlhtransfer', 0, NULL)";
                                    }

                                    $values = implode(',', $data);
                                    $query = "INSERT INTO saldo 
                                                                (`id_saldo`, `idadmin`, `tgl`, `transaksi`, `debit`, `credit`, `deleted_at`)
                                                            VALUES
                                                                $values";

                                    $insertSaldo = $koneksi->query($query);

                                    if (!$insertSaldo) {
                                        echo "<script>alert('Gagal mengirim Pembayaran!');</script>";
                                        echo "<script>location='pembayaran.php'</script>";
                                    }
                                }
                                echo "<script>alert('data sudah terupdate');</script>";
                                echo "<script>location='pembayaran.php';</script>";
                            }                    
                        ?>
                        <?php
                            if(isset($_POST["done2"])){
                                include "koneksi.php";
                                $invoice            = $_POST['invoice'];
                                $jumlah_dipilih     = count($invoice);
                                for($x = 0; $x < $jumlah_dipilih; $x++){
                                    $koneksi->query("UPDATE orderagen set payment = 'Lunas', status = 'Proses', status_progres = 0 WHERE invoice = '$invoice[$x]';");
                                    $dataorder      = $koneksi->query("SELECT SUM(subtotal) as totalnya, idmitraagen FROM orderagen WHERE invoice='$invoice[$x]' ");
                                    $tampildeui     = $dataorder->fetch_assoc();
                                    $idagen         = $tampildeui['idmitraagen'];
                                    $total          = $tampildeui['totalnya'];
                                    
                                    $datadb         = $koneksi->query("SELECT * FROM mitraagen WHERE idmitraagen='$idagen' ");
                                    $tampildb       = $datadb->fetch_assoc();
                                    $idadmin        = $tampildb['idadmin'];
                                    $namaagen       = $tampildb['namaagen'];    

                                    $saldo          = $total*10/100;
                                    $koneksi->query("INSERT INTO saldo 
                                                            (id_saldo,idadmin,tgl,transaksi,debit,credit)
                                                        VALUES 
                                                            (null,'$idadmin',NOW(),'Fee Order Agen ($namaagen) invoice #$invoice[$x]','$saldo','0')");
                                }                                    
                                echo "<script>alert('data sudah terupdate');</script>";
                                echo "<script>location='pembayaran.php';</script>";
                            }            
                        ?>   
                        <?php
                            if(isset($_POST["done3"])){
                                include "koneksi.php";
                                $invoice            = $_POST['invoice'];
                                $jumlah_dipilih     = count($invoice);
                                for($x=0; $x < $jumlah_dipilih; $x++){
                                    $koneksi->query("UPDATE orderreseller set payment='Lunas',status='Proses', status_progres=0 WHERE invoice='$invoice[$x]';");
                                    $dataorder          = $koneksi->query("SELECT  SUM(subtotal) as totalnya, idmitrareseller FROM orderreseller WHERE invoice='$invoice[$x]' ");
                                    $tampildeui         = $dataorder->fetch_assoc();
                                    $idmitrareseller    = $tampildeui['idmitrareseller'];
                                    $total              = $tampildeui['totalnya'];
                                    $datadb             = $koneksi->query("SELECT * FROM mitrareseller WHERE idmitrareseller = '$idmitrareseller' ");
                                    $tampildb           = $datadb->fetch_assoc();
                                    $idadmin            = $tampildb['idadmin'];
                                    $namaagen           = $tampildb['namaagen'];    
                                    
                                    $saldo              = $total*20/100;
                                    $koneksi->query("INSERT INTO saldo 
                                                            (id_saldo,idadmin,tgl,transaksi,debit,credit)
                                                        VALUES 
                                                            (null,'$idadmin',NOW(),'Fee Order Reseller ($namaagen) invoice #$invoice[$x]','$saldo','0')");
                                }
                                echo "<script>alert('data sudah terupdate');</script>";
                                echo "<script>location='pembayaran.php';</script>";
                            }
                        ?>
                        <?php
                            if(isset($_POST["done4"])){
                                include "koneksi.php";
                                $invoice            = $_POST['invoice'];
                                $jumlah_dipilih     = count($invoice);
                                for($x = 0; $x < $jumlah_dipilih; $x++){
                                    $koneksi->query("UPDATE ordermarketer set payment = 'Lunas', status = 'Proses', status_progres = 0 WHERE invoice = '$invoice[$x]';");
                                    $dataorder              = $koneksi->query("SELECT SUM(subtotal) as totalnya, idmitramarketer FROM ordermarketer WHERE invoice = '$invoice[$x]' ");
                                    $tampildeui             = $dataorder->fetch_assoc();
                                    $idmitramarketer        = $tampildeui['idmitramarketer'];
                                    $total                  = $tampildeui['totalnya'];
                                    $datadb                 = $koneksi->query("SELECT * FROM mitramarketer WHERE idmitramarketer='$idmitramarketer' ");
                                    $tampildb               = $datadb->fetch_assoc();
                                    $idadmin                = $tampildb['idadmin'];
                                    $namaagen               = $tampildb['namaagen'];
                                    $saldo                  = $total*25/100;
                                    $koneksi->query("INSERT INTO saldo 
                                                            (id_saldo,idadmin,tgl,transaksi,debit,credit)
                                                        VALUES 
                                                            (null,'$idadmin',NOW(),'Fee Order Marketer ($namaagen) invoice #$invoice[$x]','$saldo','0')");
                                }
                                echo "<script>alert('data sudah terupdate');</script>";
                                echo "<script>location='pembayaran.php';</script>";
                            }                 
                        ?>                                                             
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
    <?php include 'settingdatatables.php'; ?>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#db').change(function(){
                //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                var iddb = $('#db').val();
                $.ajax({
                    type : 'GET',
                    url : 'cek_jenis_mitra2.php',
                    data :  'iddb=' + iddb,
                        success: function (data) {

                        //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                        $("#tabel").html(data);
                    }
                });
            });
        });
    </script> 

</body>

</html>

                                                    