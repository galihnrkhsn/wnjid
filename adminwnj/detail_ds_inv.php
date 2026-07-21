<?php 
    session_start();

    include 'koneksi.php';

    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $invoice    = $_GET['id'];
    $querypo    = "SELECT invoice, idpoproduk FROM podropship WHERE no_ds = '$invoice'";
    $sqlpo      = mysqli_query($koneksi, $querypo);  
    $datapo     = mysqli_fetch_array($sqlpo); 

    $invoicenya = $datapo['invoice'];
    $idpoproduk = $datapo['idpoproduk'];

    $data_nods  = $koneksi->query("SELECT no_ds FROM pods WHERE no_ds= '$invoice'");
    $t_nods     = $data_nods->num_rows;

    $bukapo     = $koneksi->query("SELECT jenis_po FROM bukapo WHERE idpoproduk = '$idpoproduk'")->fetch_assoc();
    $jenisPO    = $bukapo['jenis_po'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Pusat | Wanoja</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-metro.css">
    
    <style>
        .aws {
            border:8px solid #eff4ff;;
            
            padding: 10px;
            
        }
        .aws text
        {
            color: white;
            font-size: x-large;
            text-align: right;
        }
        .aws p
        {
            color: white;
            text-align: left;
            font-size: ;
        
        }
        .aws button 
        {
            text-align: left;
        }
        .aws a 
        {
            text-align: left;
        }
    </style>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">
<!-- ==========================THIS========================== -->
<?php 
    include "sidebar.php";     
?>
<!-- ==========================THIS========================== -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Dashboard Admin Wanoja </h1>
                    <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                </div>

                <!-- MULAI KONTEN AWS -->
                <div class="row w3-container">
                    <div class="col-xl-12 col-lg-7">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Invoice <?= $invoice;?></h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form method="post">    
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th><input type='checkbox' id='checkAll' ></th>
                                                    <th>Nama Produk</th>
                                                    <?php if ($jenisPO === 'PO Custom Inisial') : ?>
                                                        <th>Custom</th>
                                                        <th>Font</th>
                                                    <?php elseif ($jenisPO === 'PO Custom Template') : ?>
                                                        <th>Template</th>
                                                    <?php endif; ?>
                                                    <th>Jumlah</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $datapodropship = $koneksi->query("SELECT podetail.harga,podetail.variant, 
                                                                                            pods.jumlah, pods.invoice, 
                                                                                            pods.idpodetail, pods.id,
                                                                                            pomitra.custom, pomitra.template, pomitra.font
                                                                                        FROM pods
                                                                                        JOIN podetail ON podetail.idpodetail = pods.idpodetail
                                                                                        INNER JOIN pomitra ON pomitra.idpomitra = pods.idpomitra
                                                                                        WHERE pods.no_ds = '$invoice'
                                                                                        AND pods.jumlah > 0
                                                                                    ");
                                                    $no = 1;
                                                    while($tampilkan = $datapodropship->fetch_assoc()){
                                                        $id = $tampilkan['id'];
                                                ?>
                                                    <tr>
                                                        <td><?php echo $no++; ?></td>    
                                                        <td>
                                                            <input type='checkbox'  name='update[]' value='<?= $id ?>' >
                                                        </td>
                                                        <td><?= $tampilkan['variant'];?></td>
                                                        <?php if ($jenisPO === 'PO Custom Inisial') : ?>
                                                            <td><?= $tampilkan['custom'] ?></td>
                                                            <td><?= $tampilkan['font'] ?></td>
                                                        <?php elseif ($jenisPO === 'PO Custom Template') : ?>
                                                            <td><?= $tampilkan['template'] ?></td>
                                                        <?php endif; ?>
                                                        <td><?= $tampilkan['jumlah'];?></td>
                                                        <td><input type='number' class="form-control" min="0"  name='jumlah<?= $id ?>' value='' ></td>
                                                    </tr>
                                                <?php 
                                                        $total_qty += $tampilkan['jumlah'];
                                                    }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">Total</th>
                                                    <td colspan="2"><?= $total_qty ?></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <button type="submit" class="btn btn-sm btn-success" name="ubah">Ubah</button>
                                    </form>
                                </div>
                                <?php 
                                    if(isset($_POST['ubah'])){
                                        date_default_timezone_set('Asia/Jakarta');
                                        $today = date("Y-m-d H:i:s");  
                                        if(isset($_POST['update'])){
                                            foreach($_POST['update'] as $updateid){
                                                $jumlah = $_POST['jumlah'.$updateid];
                                                if ($jumlah<>'') {
                                                    $sql = $koneksi->query("UPDATE pods set jumlah='$jumlah', waktu = '$today' WHERE id='$updateid'"); 
                                                }
                                            }
                                            if ($sql) {
                                                echo "<script>alert('Data Berhasil Disimpan');</script>";
                                                echo "<script>location='detail_ds_inv.php?id=$invoice';</script>";
                                            }else{
                                                echo "<script>alert('Data Gagal Disimpan');</script>";
                                                echo "<script>location='detail_ds_inv.php?id=$invoice';</script>";
                                            }                
                                        }
                                    }
                                ?>                                    
                            </div>
                        </div>
                    </div>   
                    <div class="col-xl-12 col-lg-7">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Tambah Variant Baru</h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <label>Jumlah Variant</label>
                                <div class="">
                                    <form method="post">  
                                        <div class='form-group row'>    
                                            <div class="col-sm-3">    
                                            <input type="number" class="form-control" name="jumlah" min=0 required>
                                            </div>
                                            <div class="col-sm-3">    
                                            <button type="submit" name="kirim" class="btn btn-info">Kirim</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>  
                                <?php 
                                    if(isset($_POST['kirim'])){
                                        $jumlah = $_POST['jumlah'];
                                ?>    
                                    <form method="POST">
                                        <?php
                                            for ($x = 0; $x < $jumlah; $x++) {
                                        ?>
                                            <?= $invocenya; ?>
                                            <div class="col-6"> 
                                                <label>Pilih Variant</label>
                                                <select style="width: auto;" class="form-control" name="idpodetail[]" required>
                                                    <option value="">~ Pilih Variant ~</option>  
                                                    <?php 
                                                        if($t_nods == 0){
                                                            $ambil  = $koneksi->query("SELECT podetail.variant, podetail.idpodetail, pomitra.custom, pomitra.idpomitra
                                                                                        FROM pomitra
                                                                                        JOIN podetail on podetail.idpodetail = pomitra.idpodetail
                                                                                        where pomitra.invoice='$invoicenya'
                                                                                        and pomitra.jumlah > 0
                                                                                        ");     
                                                        } else {
                                                            $ambil  = $koneksi->query("SELECT podetail.variant, podetail.idpodetail, pomitra.custom, pomitra.idpomitra
                                                                                        FROM pomitra
                                                                                        JOIN podetail on podetail.idpodetail = pomitra.idpodetail
                                                                                        where pomitra.invoice='$invoicenya'
                                                                                        and pomitra.jumlah > 0
                                                                                        and pomitra.idpodetail NOT IN (SELECT pods.idpodetail FROM pods WHERE pods.invoice = '$invoicenya' and no_ds='$invoice' and pods.jumlah>0)
                                                                                    ");     
                                                        }

                                                        while($data=$ambil->fetch_assoc()){
                                                            $id = $data['idpodetail'];
                                                            $idpomitra = $data['idpomitra'];                    
                                                    ?>
                                                        <option value='<?php echo $data['idpodetail']; ?>|<?php echo $data['idpomitra']; ?>'><?php echo $data['variant']; ?></option>
                                                    <?php } ?>  
                                                </select> 
                                            </div> 
                                            <div class="col-3">  
                                                <label>Jumlah</label>
                                                <input type="number" name="jumlah[]" class="form-control" min = 0 required>
                                            </div>
                                        <?php } ?>
                                        <div class="col-6">
                                            <button class="btn btn-success mt-4 mb-5" type="submit" name="save">Simpan</button> 
                                        </div>
                                    </form>
                                <?php 
                                    }
                                ?>
                            </div>
                        </div>
                    </div>                  
                </div>   
                <?php
                    if(isset($_POST["save"])){
                        date_default_timezone_set('Asia/Jakarta');
                        $idpodetail = $_POST["idpodetail"];
                        $jumlah     = $_POST["jumlah"];
                        $jmlh       = count($idpodetail);
                        $today      = date("Y-m-d H:i:s");

                        for($x = 0; $x < $jmlh; $x++){
                            $result_explode = explode('|', $idpodetail[$x]);
                            $idpodetail1    = $result_explode[0];
                            $idpomitra      = $result_explode[1];
                            $ambil          = $koneksi->query("SELECT * FROM pods WHERE idpodetail='$idpodetail1' and no_ds= '$invoice'");
                            $produk         = $ambil->num_rows;

                            if($produk == 0){
                                $sql    = $koneksi->query("INSERT INTO 
                                                            pods 
                                                                (id,no_ds,invoice,idpodetail,idpomitra,jumlah,waktu) 
                                                            values 
                                                                (null,'$invoice','$invoicenya',
                                                                    '$idpodetail1','$idpomitra','$jumlah[$x]','$today') 
                                                        ");
                            }else{
                                $sql    = $koneksi->query("UPDATE pods 
                                                            set jumlah = '$jumlah[$x]', 
                                                                waktu = '$today' 
                                                            WHERE idpodetail = '$idpodetail1' 
                                                            and no_ds =  '$invoice'
                                                        "); 
                            }
                        }
                        if ($sql) {
                            echo "<script>alert('Data Berhasil Disimpan');</script>";
                            echo "<script>location='detail_ds_inv.php?id=$invoice';</script>";
                        }else{
                            echo "<script>alert('Data Gagal Disimpan');</script>";
                            echo "<script>location='detail_ds_inv.php?id=$invoice';</script>";
                        }
                    }    
                ?>              
            </div>
            <div class="row">
                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                        <div class="container my-auto">
                            <div class="copyright text-center my-auto">
                                <span>Copyright &copy; Wanoja Development 2020</span>
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
</body>
</html>
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