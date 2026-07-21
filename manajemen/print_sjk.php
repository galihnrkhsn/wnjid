<?php
    session_start();

    include 'koneksi.php';

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
        echo "
            <script>alert('Anda harus login terlebih dahulu!');</script>
            <script>location='login-multi.php';</script>
        ";
        header("Location: login-multi.php");
        exit();
    }

    $id         = $_SESSION['user_id'];
    $role       = $_SESSION['user_level'];
?>

<?php 
        if(isset($_POST['but_hapus'])){

            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){                  
                    $sqlnya = $koneksi->query("DELETE FROM sjk WHERE sjk='$updateid'" );                                       
                }
                if ($sqlnya) {
                  echo "<script>alert('data berhasil dihapus');</script>";
                  echo "<script>location='index.php';</script>";  
                  }else{
                    echo "<script>alert('data gagal dihapus');</script>";
                    echo "<script>location='index.php';</script>";
                }               
            }            
        }

 ?>

            <!-- CONTENT HEAD -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan</title>
    <style>
        @media print {
            footer { page-break-after: always; }
            table, th, td {
                font-size: 16px; /* Sesuaikan ukuran font agar tidak terlalu besar atau kecil */
            }
            h1, h3, h5 {
                font-size: 20px; /* Ukuran font judul agar tidak terlalu besar atau kecil */
            }
        }
        table, th, td, tr {
            border: 2px solid;
            border-collapse: collapse;
        }
        body {
            color: black;
            font-size: 16px;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <?php 
    if(isset($_POST["but_print"])){ 
        if(isset($_POST['update'])){
            foreach($_POST['update'] as $updateid){ 
                $sjk = $updateid;
                $datamitra = $koneksi->query("SELECT vendor.vendor
                                                FROM sjk
                                                JOIN vendor ON sjk.vendor = vendor.id 
                                                WHERE sjk.sjk='$sjk'");
                $tampilnama = $datamitra->fetch_assoc();       
    ?>
    <center>
        <h1><strong>WNJ.ID</strong></h1>
        <h3><strong><?= $sjk ?></strong></h3>
    </center>
    <div class="row align-items-start">
        <div class="col">
            <h5><strong style="float:left">Vendor : <?= $tampilnama['vendor']; ?></strong></h5>
        </div>
    </div>
    <table style="width:100%;">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th>Pengirim</th>
                <th>Penerima</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $qty = 0;
            $datapodropship = $koneksi->query("SELECT podetail.variant, sjk.jumlah
                                                FROM sjk
                                                JOIN podetail ON podetail.idpodetail = sjk.idpodetail
                                                WHERE sjk.sjk= '$sjk'
                                                AND sjk.jumlah > 0");
            $no = 1;
            while($tampilkan = $datapodropship->fetch_assoc()){
            ?>
            <tr>
                <td><?php echo $no++; ?></td>    
                <td><?= $tampilkan['variant']; ?></td>
                <td><center><?= $tampilkan['jumlah']; ?></center></td>
                <td></td>
                <td></td>
            </tr>
            <?php 
            $qty += $tampilkan['jumlah'];
            ?>                       
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total</td>
                <td><center><?= $qty ?></center></td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>        
    </table>
    <br>
    <br>
    <center>
        <table style="font-size: 25px;border-color: white;">
            <tr>
                <td style="border-color: white;">
                    <center>Pengirim</center>
                    <br>
                    <br>
                    <br>
                    <br>      
                </td>
                <td width="5%" style="border-color: white;"></td>
                <td width="5%" style="border-color: white;"></td>    
                <td style="border-color: white;">
                    <center>Penerima</center>
                    <br>
                    <br>
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td style="border-color: white;">
                    <center>
                        (<span style="color:transparent;">_____________________</span>)
                    </center>      
                </td>
                <td style="border-color: white;"></td> 
                <td style="border-color: white;"></td> 
                <td style="border-color: white;">
                    <center>
                        (<span style="color:transparent;">_____________________</span>)
                    </center>
                </td>
            </tr>
        </table>
    </center>
    <div>
        <p style="font-size: 16px"> 
            Note : Setelah barang diterima, mohon langsung dicek. Surat jalan yang sudah diverifikasi dan sudah ditandatangani mohon untuk difoto dan dikirim melalui No HP CS Pusat maksimal 3 X 24 Jam
            <br>
            <br>
        </p>
    </div>
</body>
</html>
<script>
    window.print();
</script>
<?php
            }
        }
    }
?>


    <!-- CONTENT HEAD END -->
    <!-- FOOTER -->
    <!-- FOOTER END -->
    
    <!-- PHP -->
    <!-- PHP END -->

    <!-- SCRIPT -->
    
    <!-- SCRIPT END -->