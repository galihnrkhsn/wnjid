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
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style type="text/css">
        body{
            padding-right: 0px !important;
        }
    </style>
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
                        <h1 class="h3 mb-0 text-gray-800">Penjualan Ready Stok</h1>
                    </div>

                    <div class="table-responsive">
                        <div class="form-group col-4">
                            <label>Filter</label>
                            <select  class="form-control" name="filter" id="filter">
                                <option value="Harian">Harian</option>
                                <option value="Bulanan">Bulanan</option>
                            </select>  
                        </div>
                        <div id="tfilter" name="tfilter"></div> 
                    </div> 

                    <?php if (isset($_POST["cari"])): ?>
                        <?php 
                            $filter     = $_POST["filter"];
                            $tanggal    = $_POST["tanggal"];
                            $cs         = $_POST["namacs"]; 

                            if ($filter=='Bulanan') {
                                $tahunsekarang  = date('Y');
                                $tanggalnyaaa   = $tahunsekarang.'-'.$tanggal;
                            }

                            $namacs = $cs;
                            if ($cs == "Semua CS") {
                                $namacs = "";
                            }  
                        ?> 
                        <div class="table-responsive col mt-2">
                            <form method="post" action="excel_penjualan.php" target="_blank">
                                <input type="hidden" name="filter" value="<?= $filter ?>">
                                <input type="hidden" name="tanggal" value="<?= $tanggal ?>">
                                <input type="hidden" name="namacs" value="<?= $namacs ?>">
                                <?php if ($filter == "Harian"): 
                                    $tanggal2 = $_POST["tanggal2"];
                                ?>
                                    <input type="hidden" name="tanggal2" value="<?= $tanggal2 ?>">      
                                <?php endif ?>
                                <button type="submit" class="btn btn-success md-5" name="export_excel">Export Excel</button>
                            </form>
                            <br>
                            <form method="post" action="excel_penjualan_db.php" target="_blank">
                                <input type="hidden" name="filter" value="<?= $filter ?>">
                                <input type="hidden" name="tanggal" value="<?= $tanggal ?>">
                                <input type="hidden" name="namacs" value="<?= $namacs ?>">
                                <?php if ($filter=="Harian"): 
                                    $tanggal2=$_POST["tanggal2"];
                                ?>
                                    <input type="hidden" name="tanggal2" value="<?= $tanggal2 ?>">      
                                <?php endif ?>    
                                <button type="submit" class="btn btn-success md-5" name="export_excel">Export Excel DB</button>
                            </form>  
                            <br>
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Distributor</a></li>
                                <li><a data-toggle="tab" href="#menu2" class="nav-item nav-link">Sub-DB</a></li>
                                <li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Inv Manual</a></li>
                                <!-- <li><a data-toggle="tab" href="#menu3" class="nav-item nav-link"> Marketer</a></li> -->
                            </ul>  
                            <div class="tab-content">
                                <div id="home" class="tab-pane fade show active" id="home"  role="tabpanel">            
                                    <h3 class="mt-2">Ready Stok</h3>
                                    <table class="table table-bordered table-striped mt-3" id="tbmaximus">
                                        <thead>
                                            <tr>       
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>CS</th>
                                                <th>Nama Mitra</th>
                                                <th>No Surat Jalan</th>
                                                <th>Invoice</th>
                                                <th>Produk</th>
                                                <th>Harga Ecer</th>
                                                <th>Harga DB</th>
                                                <th>QTY</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                if ($filter == "Harian") {
                                                    $datapo = $koneksi->query("SELECT surat_jalan.invoice, SUM(surat_jalan.progres) as jumlah,
                                                                                    surat_jalan.progres, surat_jalan.status, surat_jalan.waktu,
                                                                                    admin_mitra.namamitra, admin_mitra.idadmin, admin_mitra_cs.namacs, 
                                                                                    surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.invoice,
                                                                                    produk.namaproduk, produk.harga, ordermitra.tgl
                                                                                FROM surat_jalan
                                                                                JOIN ordermitra on ordermitra.invoice = surat_jalan.invoice 
                                                                                INNER JOIN produk on produk.idproduk = surat_jalan.idproduk 
                                                                                LEFT JOIN admin_mitra on admin_mitra.idadmin = ordermitra.idmitra 
                                                                                LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                                                WHERE surat_jalan.status <> 'Ambil Barang' 
                                                                                AND SUBSTRING(surat_jalan.waktu, 1, 10) BETWEEN '$tanggal' AND '$tanggal2'
                                                                                AND admin_mitra_cs.namacs LIKE '%$namacs%' 
                                                                                AND ordermitra.tgl < '2024-11-02'
                                                                                GROUP BY surat_jalan.id_sj
                                                                                ORDER BY surat_jalan.waktu DESC, surat_jalan.invoice");
                                                }
                                                if ($filter == "Bulanan") {
                                                    $datapo=$koneksi->query("SELECT surat_jalan.invoice, SUM(surat_jalan.progres) as jumlah,
                                                                                    surat_jalan.progres, surat_jalan.status, surat_jalan.waktu,
                                                                                    admin_mitra.namamitra, admin_mitra.idadmin, admin_mitra_cs.namacs, 
                                                                                    surat_jalan.id_sj, surat_jalan.no_sj, surat_jalan.invoice,
                                                                                    produk.namaproduk, produk.harga, ordermitra.tgl
                                                                                FROM surat_jalan
                                                                                JOIN ordermitra on ordermitra.invoice=surat_jalan.invoice 
                                                                                INNER JOIN produk on produk.idproduk=surat_jalan.idproduk 
                                                                                LEFT JOIN admin_mitra on admin_mitra.idadmin = ordermitra.idmitra 
                                                                                LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                                                WHERE surat_jalan.status <> 'Ambil Barang' 
                                                                                AND SUBSTRING(surat_jalan.waktu, 1, 7) LIKE '%$tanggalnyaaa%'
                                                                                AND admin_mitra_cs.namacs LIKE '%$namacs%' 
                                                                                AND ordermitra.tgl < '2024-11-02'
                                                                                GROUP BY surat_jalan.id_sj
                                                                                ORDER BY surat_jalan.waktu DESC, surat_jalan.invoice");
                                                }                         

                                                $no=1;
                                                while($tampilkan = $datapo->fetch_assoc()){
                                                    $result_explode = explode(' ', $tampilkan['waktu']);
                                                    $tanggal_rs     = $result_explode[0];
                                                        
                                                    $no_sj          = $tampilkan['no_sj']; 
                                                    $invoice        = $tampilkan['invoice']; 



                                                    $totala         = $tampilkan['harga'];
                                                    $total_progres  = $tampilkan['progres'];

                                                    $diskon         = 35/100*$totala;
                                                    $totalnya       = $totala - $diskon;    

                                                    $jumlahsemua    += $total_progres;
                                                    $totalsemua     += $totalnya*$total_progres;                       
                                            ?>
                                                <tr>
                                                    <td><strong><?php echo $no++; ?></strong></td>     
                                                    <td><?= date('d F Y', strtotime($tanggal_rs)); ?></td>
                                                    <td><?= $tampilkan['namacs']; ?></td>
                                                    <td><?= $tampilkan['namamitra'].'('.$tampilkan['idadmin'].')'; ?></td>
                                                    <td><?= $tampilkan['no_sj']; ?></td>
                                                    <td><?= $tampilkan['invoice']; ?></td>               
                                                    <td><?= $tampilkan['namaproduk']; ?></td> 
                                                    <td><?= number_format($tampilkan['harga']); ?></td>
                                                    <td><?= number_format($totalnya); ?></td>
                                                    <td><?= $tampilkan['progres']; ?></td>   
                                                    <td>Rp. <?= number_format($totalnya*$total_progres); ?> </td>                 
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="9">Total</td>
                                                <td><?= $jumlahsemua; ?></td>
                                                <td>Rp. <?= number_format($totalsemua); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div id="menu1" class="tab-pane fade">  
                                    <h3 class="mt-2">Invoice Manual</h3>
                                    <table class="table table-bordered table-striped mt-3" id="tbmaximus2">
                                        <thead>
                                            <tr>       
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>CS</th>
                                                <th>Nama Mitra</th>
                                                <th>Invoice</th>
                                                <th>Produk</th>
                                                <th>Harga Ecer</th>
                                                <th>Harga DB</th>
                                                <th>QTY</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                if ($filter == "Harian") {
                                                    $data_inv = $koneksi->query("SELECT surat_jalan_manual.invoice,
                                                                                        SUM(surat_jalan_manual.progres) as jumlah,
                                                                                        surat_jalan_manual.progres, surat_jalan_manual.status,
                                                                                        surat_jalan_manual.waktu, admin_mitra.namamitra, 
                                                                                        admin_mitra.idadmin, admin_mitra_cs.namacs, 
                                                                                        surat_jalan_manual.id_sj, produk.namaproduk, produk.harga
                                                                                    FROM surat_jalan_manual
                                                                                    JOIN admin_mitra on admin_mitra.idadmin = surat_jalan_manual.idadmin 
                                                                                    INNER JOIN produk on produk.idproduk=surat_jalan_manual.idproduk 
                                                                                    LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                                                    WHERE surat_jalan_manual.status = 'INV'
                                                                                    AND SUBSTRING(surat_jalan_manual.waktu, 1, 10) BETWEEN '$tanggal' AND '$tanggal2'
                                                                                    AND admin_mitra_cs.namacs LIKE '%$namacs%'
                                                                                    AND surat_jalan_manual.waktu < '2024-11-02 23:59:59'
                                                                                    GROUP BY surat_jalan_manual.id_sj
                                                                                    ORDER BY surat_jalan_manual.waktu DESC, surat_jalan_manual.invoice");
                                                }
                                                if ($filter=="Bulanan") {
                                                    $data_inv=$koneksi->query("SELECT surat_jalan_manual.invoice, SUM(surat_jalan_manual.progres) as jumlah,
                                                                                    surat_jalan_manual.progres, surat_jalan_manual.status,
                                                                                    surat_jalan_manual.waktu, admin_mitra.namamitra, 
                                                                                    admin_mitra.idadmin, admin_mitra_cs.namacs, 
                                                                                    surat_jalan_manual.id_sj, produk.namaproduk, produk.harga
                                                                                FROM surat_jalan_manual
                                                                                JOIN admin_mitra on admin_mitra.idadmin = surat_jalan_manual.idadmin 
                                                                                INNER JOIN produk on produk.idproduk = surat_jalan_manual.idproduk 
                                                                                LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                                                WHERE surat_jalan_manual.status = 'INV'
                                                                                AND SUBSTRING(surat_jalan_manual.waktu, 1, 7) LIKE '%$tanggalnyaaa%'
                                                                                AND admin_mitra_cs.namacs LIKE '%$namacs%'
                                                                                AND surat_jalan_manual.waktu < '2024-11-02 23:59:59' 
                                                                                GROUP BY surat_jalan_manual.id_sj
                                                                                ORDER BY surat_jalan_manual.waktu DESC, surat_jalan_manual.invoice");
                                                }
                                                $no=1;
                                                while($tampilkan_inv = $data_inv->fetch_assoc()){
                                                    $result_explode     = explode(' ', $tampilkan_inv['waktu']);
                                                    $tanggal_inv        = $result_explode[0];                                                        
                                                    $no_sj_inv          = $tampilkan_inv['no_sj']; 
                                                    $invoice_inv        = $tampilkan_inv['invoice']; 
                                                    $total_inv          = $tampilkan_inv['harga'];
                                                    $total_progres_inv  = $tampilkan_inv['progres'];
                                                    $diskon_inv         = 35/100*$total_inv;
                                                    $totalnya_inv       = $total_inv  - $diskon_inv;    
                                                    $jumlahsemua_inv    += $total_progres_inv;
                                                    $totalsemua_inv     += $totalnya_inv*$total_progres_inv;                       
                                            ?>
                                                <tr>
                                                    <td style="text-align: center;"><strong><?php echo $no++; ?></strong></td>     
                                                    <td><?= date('d F Y', strtotime($tanggal_inv)); ?></td>
                                                    <td><?= $tampilkan_inv['namacs']; ?></td>
                                                    <td><?= $tampilkan_inv['namamitra'].'('.$tampilkan_inv['idadmin'].')'; ?></td>
                                                    <td><?= $tampilkan_inv['invoice']; ?></td>   
                                                    <td><?= $tampilkan_inv['namaproduk']; ?></td>
                                                    <td><?= number_format($tampilkan_inv['harga']); ?></td>
                                                    <td><?= number_format($totalnya_inv); ?></td>                      
                                                    <td><?= $total_progres_inv; ?></td> 
                                                    <td>Rp. <?= number_format($totalnya_inv*$total_progres_inv); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="8">Total</td>
                                                <td><?= $jumlahsemua_inv; ?></td>
                                                <td>Rp. <?= number_format($totalsemua_inv); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>  
                                </div>
                                <div id="menu2" class="tab-pane fade">
                                    <h3 class="mt-2">Ready Stok Sub-DB</h3>  
                                    <table class="table table-bordered table-striped mt-3" id="tbmaximus3">
                                        <thead>
                                            <tr>       
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>CS</th>
                                                <th>Nama Mitra</th>
                                                <th>No Surat Jalan</th>
                                                <th>Invoice</th>
                                                <th>Produk</th>
                                                <th>Harga Ecer</th>
                                                <th>Harga DB</th>
                                                <th>QTY</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                if ($filter == "Harian") {
                                                    $datapo = $koneksi->query("SELECT surat_jalan_subdb.invoice, SUM(surat_jalan_subdb.progres) as jumlah,
                                                                                    surat_jalan_subdb.progres, surat_jalan_subdb.status,
                                                                                    surat_jalan_subdb.waktu, admin_mitra.namamitra, 
                                                                                    admin_mitra.idadmin, mitraagen.idmitraagen as idagen,
                                                                                    mitrareseller.idmitrareseller as idreseller,
                                                                                    mitramarketer.idmitramarketer as idmarketer,
                                                                                    mitraagen.namaagen as agen,
                                                                                    mitrareseller.namaagen as reseller,
                                                                                    mitramarketer.namaagen as marketer,
                                                                                    admin_mitra_cs.namacs, surat_jalan_subdb.id_sj,
                                                                                    surat_jalan_subdb.no_sj, produk.namaproduk, produk.harga
                                                                                FROM surat_jalan_subdb
                                                                                INNER JOIN produk on produk.idproduk = surat_jalan_subdb.idproduk 
                                                                                LEFT JOIN orderagen on orderagen.invoice = surat_jalan_subdb.invoice 
                                                                                LEFT JOIN mitraagen on mitraagen.idmitraagen = orderagen.idmitraagen

                                                                                LEFT JOIN orderreseller on orderreseller.invoice=surat_jalan_subdb.invoice 
                                                                                LEFT JOIN mitrareseller on mitrareseller.idmitrareseller = orderreseller.idmitrareseller

                                                                                LEFT JOIN ordermarketer on ordermarketer.invoice=surat_jalan_subdb.invoice 
                                                                                LEFT JOIN mitramarketer on mitramarketer.idmitramarketer = ordermarketer.idmitramarketer

                                                                                LEFT JOIN admin_mitra on (admin_mitra.idadmin = mitraagen.idadmin or
                                                                                                        admin_mitra.idadmin = mitrareseller.idadmin or
                                                                                                        admin_mitra.idadmin = mitramarketer.idadmin)
                                                                                LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                                                WHERE surat_jalan_subdb.status <> 'Ambil Barang' 
                                                                                AND SUBSTRING(surat_jalan_subdb.waktu, 1, 10) BETWEEN '$tanggal' AND '$tanggal2'
                                                                                AND admin_mitra_cs.namacs LIKE '%$namacs%' 
                                                                                AND surat_jalan_subdb.waktu < '2024-11-02 23:59:59'
                                                                                GROUP BY surat_jalan_subdb.id_sj
                                                                                ORDER BY surat_jalan_subdb.waktu DESC, surat_jalan_subdb.invoice");
                                                }
                                                if ($filter == "Bulanan") {
                                                    $datapo = $koneksi->query("SELECT surat_jalan_subdb.invoice, SUM(surat_jalan_subdb.progres) as jumlah,
                                                                                    surat_jalan_subdb.progres, surat_jalan_subdb.status,
                                                                                    surat_jalan_subdb.waktu, admin_mitra.namamitra, 
                                                                                    admin_mitra.idadmin, mitraagen.idmitraagen as idagen,
                                                                                    mitrareseller.idmitrareseller as idreseller,
                                                                                    mitramarketer.idmitramarketer as idmarketer,
                                                                                    mitraagen.namaagen as agen,
                                                                                    mitrareseller.namaagen as reseller,
                                                                                    mitramarketer.namaagen as marketer,
                                                                                    admin_mitra_cs.namacs, surat_jalan_subdb.id_sj,
                                                                                    surat_jalan_subdb.no_sj, produk.namaproduk, produk.harga
                                                                                FROM surat_jalan_subdb
                                                                                INNER JOIN produk on produk.idproduk = surat_jalan_subdb.idproduk 
                                                                                LEFT JOIN orderagen on orderagen.invoice = surat_jalan_subdb.invoice 
                                                                                LEFT JOIN mitraagen on mitraagen.idmitraagen = orderagen.idmitraagen

                                                                                LEFT JOIN orderreseller on orderreseller.invoice = surat_jalan_subdb.invoice 
                                                                                LEFT JOIN mitrareseller on mitrareseller.idmitrareseller = orderreseller.idmitrareseller

                                                                                LEFT JOIN ordermarketer on ordermarketer.invoice=surat_jalan_subdb.invoice 
                                                                                LEFT JOIN mitramarketer on mitramarketer.idmitramarketer = ordermarketer.idmitramarketer

                                                                                LEFT JOIN admin_mitra on (admin_mitra.idadmin = mitraagen.idadmin or
                                                                                                        admin_mitra.idadmin = mitrareseller.idadmin or
                                                                                                        admin_mitra.idadmin = mitramarketer.idadmin)
                                                                                LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                                                WHERE surat_jalan_subdb.status <> 'Ambil Barang' 
                                                                                AND SUBSTRING(surat_jalan_subdb.waktu, 1, 7) LIKE '%$tanggalnyaaa%'
                                                                                AND admin_mitra_cs.namacs LIKE '%$namacs%' 
                                                                                AND surat_jalan_subdb.waktu < '2024-11-02 23:59:59'
                                                                                GROUP BY surat_jalan_subdb.id_sj
                                                                                ORDER BY surat_jalan_subdb.waktu DESC, surat_jalan_subdb.invoice");
                                                }
                                                $no=1;
                                                while($tampilkan=$datapo->fetch_assoc()){
                                                    $result_explode     = explode(' ', $tampilkan['waktu']);
                                                    $tanggal_rs_sub     = $result_explode[0];
                                                    $no_sj              = $tampilkan['no_sj']; 
                                                    $invoice            = $tampilkan['invoice']; 
                                                    $totala             = $tampilkan['harga'];
                                                    $total_progres      = $tampilkan['progres'];
                                                    $diskon             = 35/100*$totala;
                                                    $totalnya           = $totala - $diskon;    
                                                    $jumlahsemua_sub        += $total_progres;
                                                    $totalsemua_sub         += $totalnya*$total_progres;
                                            ?>
                                                <tr>
                                                    <td style="text-align: center;"><strong><?php echo $no++; ?></strong></td>     
                                                    <td><?= date('d F Y', strtotime($tanggal_rs_sub)); ?></td>
                                                    <td><?= $tampilkan['namacs']; ?></td>
                                                    <td>
                                                        <?php 
                                                            if ($tampilkan['marketer']!='') {
                                                                echo $tampilkan['marketer'].'('.$tampilkan['idmarketer'].')'; 
                                                                $mitra = 'Marketer';              
                                                            }
                                                            elseif ($tampilkan['reseller']!='') {
                                                                echo $tampilkan['reseller'].'('.$tampilkan['idreseller'].')'; 
                                                                $mitra = 'Reseller'; 
                                                            }
                                                            elseif ($tampilkan['agen']!='') {
                                                                echo $tampilkan['agen'].'('.$tampilkan['idagen'].')';   
                                                                $mitra = 'Agen'; 
                                                            } 
                                                        ?>
                                                    </td>
                                                    <td><?= $tampilkan['no_sj']; ?></td>
                                                    <td><?= $tampilkan['invoice']; ?></td>  
                                                    <td><?= $tampilkan['namaproduk']; ?></td>  
                                                    <td><?= number_format($tampilkan['harga']); ?></td>  
                                                    <td><?= number_format($totalnya); ?></td>            
                                                    <td><?= $total_progres; ?></td>
                                                    <td>Rp. <?= number_format($totalnya*$total_progres); ?></td>                 
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="9">Total</td>
                                                <td><?= $jumlahsemua_sub; ?></td>
                                                <td>Rp. <?= number_format($totalsemua_sub); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>  
                            </div>        
                        </div>       
                    <?php endif ?>
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
    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function(){



            $('#filter').change(function(){

                //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                var filter = $('#filter').val();
                
                $.ajax({
                    type : 'GET',
                    url : 'cek_filter.php',
                    data :  'filter=' + filter,
                        success: function (data) {

                        //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                        $("#tfilter").html(data);
                    }
                    
                });
            });  

            $('#filter').ready(function(){

                //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                var filter = $('#filter').val();
                
                $.ajax({
                    type : 'GET',
                    url : 'cek_filter.php',
                    data :  'filter=' + filter,
                        success: function (data) {

                        //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                        $("#tfilter").html(data);
                    }
                    
                });
            });                




            
        });
    </script> 
               

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script src="assets/dist/js/jquery.min.js"></script>
    <script src="assets/dist/js/bootstrap.min.js"></script>
    <script src="assets/dist/DataTables/datatables.min.js"></script>


    <script type="text/javascript">
        $(document).ready( function () {
        $('#tb_sj_pr').DataTable();
    } );
    </script>
    <script type="text/javascript">
            $(document).ready( function () {
        $('#tbmaximus').DataTable();
    } );
    </script> 

    <script type="text/javascript">
            $(document).ready( function () {
        $('#tbmaximus2').DataTable();
    } );
    </script> 


    <script type="text/javascript">
            $(document).ready( function () {
        $('#tbmaximus3').DataTable();
    } );
    </script> 

</body>

</html>

                                                    