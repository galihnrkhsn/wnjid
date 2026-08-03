<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk     = $_GET['id'];
    $idadmin        = $_SESSION["idadmin"];
    $query          = "SELECT COUNT(*) as jumlah,
                        poproduk.idpoproduk,
                        poproduk.namapo,
                        poproduk.status 
                        FROM poproduk 
                        WHERE poproduk.idpoproduk = '$idpoproduk'
                    ";
    $sql            = mysqli_query($koneksi, $query);  
    $data           = mysqli_fetch_array($sql);
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
    <?php include "assets/components/Navbar/navbar2.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-3"> 
        <table id="myJudul" class="w3-table-all w3-centered">
            <center><h4><b>Formulir Pemesanan <?= $data['namapo']; ?></b></h4></center>
        </table>
    </div>

    <div class="container panel panel-default">
        <?php
            $excluded_ids       = [181, 182, 183, 362, 382, 395, 394, 399, 400, 405];
            $excluded_sarung    = [6356, 6357, 6358, 6359, 6360, 6366, 6367, 6368, 6369, 6370, 6371, 6372, 6373, 6540, 6541, 6542, 6543, 6544, 6550, 6551, 6552, 6553, 6554, 6555, 6556, 6557];
            ?>
        <?php if (!in_array($idpoproduk, $excluded_ids)): ?>
            <b>Sisa Stock:</b>
            <div class="row border border-secondary mx-auto">
                <?php
                $idpoproduk = $_GET['id'];
                $sql = "SELECT * from pokategori where idpoproduk='$idpoproduk' ORDER BY idpo";
                $query = $koneksi->query($sql);
                while ($stok = $query->fetch_assoc()) {
                    ?>
                    <?php if (!in_array($stok['idpo'], $excluded_sarung)) :?>
                        <div class="col-3">
                            <?php echo $stok['namakategori']; ?>
                        </div>
                        <div class="col-3">
                            (<?php echo $stok['stok']; ?>)
                        </div>
                        <br>
                    <?php endif; ?>
                <?php } ?>
            </div>
        <?php endif ?>    
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div style="padding: 0 15px;">
                        <form method="POST">			
                            <div style="padding: 0 15px;">
                                <?php
                                $idpoproduk = $_GET['id'];
                                $total = 0;
                                $sql = "SELECT * FROM poproduk 
                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk 
                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo 
                                        WHERE poproduk.idpoproduk='$idpoproduk' AND podetail.variant NOT LIKE '%Custom%'
                                        ORDER BY podetail.idpodetail ASC";
                                $query = $koneksi->query($sql);
                                while ($row = $query->fetch_assoc()) {
                                    ?>
                                    <div class="form-group">
                                        <label>
                                            <?php echo $row['variant']; ?> 
                                            <?php if (!in_array($idpoproduk, $excluded_ids) && !in_array($row['idpo'], $excluded_sarung)) :?>
                                                (<?= $row['stok'] ?>)
                                            <?php else :?>
                                            <?php endif; ?>
                                        </label>
                                        <input type="hidden" name="idpo[]" value="<?php echo $row['idpo']; ?>">
                                        <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                                        <input type="number" min="0" max="<?= $row['stok'] ?>" required name="jmlh[]" class="form-control" style="width:80px;" value='0'>
                                        <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
                                    </div>	
                                    <?php 
                                    $namapo2    = $row['namapo'];
                                    $id         = $row['idpoproduk'];
                                    $idadmin    = $_SESSION["idadmin"]; 
                                } ?>
                                <?php
                                    $sql = $koneksi->query("SELECT COUNT(*) AS total_inv, pomitra.* FROM pomitra WHERE idpoproduk = $idpoproduk AND idmitra = $idadmin");
                                    $row = $sql->fetch_assoc();
                                    if ($row['total_inv'] > 0) : 
                                ?>
                                    <a href="datapo.php?id=<?= $row['idpoproduk'] ?>&invoice=<?= $row['invoice'] ?>" class="btn btn-success btn-sm">Invoice</a>
                                <?php else : ?>
                                    <p><strong><font color="red" size="5px">*</font></strong>Jangan Kosongkan Label, Cukup isi dengan Angka 0 jika tidak memesan</p>
                                    <button type='submit' class='btn btn-primary' name='save'>Kirim</button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <br><br><br><br>
    <!-- PHP SYNTAK -->
    <?php
        if(isset($_POST["save"])){
            include "koneksi.php";
            try {
                $checkQuery     = $koneksi->query("SELECT COUNT(*) as count 
                                                    FROM pomitra 
                                                    WHERE idmitra = '$idadmin' 
                                                    AND idpoproduk = '$idpoproduk' 
                                                    AND idmitraagen IS NULL 
                                                    AND idmitrareseller IS NULL 
                                                    AND idmitramarketer IS NULL");
                $exists         = $checkQuery->fetch_assoc()['count'];

                if ($exists > 0) {
                    echo "<script>alert('Data sudah ada, tidak boleh mengirim ulang!');</script>";
                    echo "<script>location='detailpo.php?id=$idpoproduk';</script>";
                    exit();
                }
                date_default_timezone_set('Asia/Jakarta');
                $today      = date("s");
                $waktu      = date("H:i:s");
                $idpo       = $_POST["idpo"];
                $idpodetail = $_POST["idpodetail"];
                $jmlh       = $_POST["jmlh"];
                $harga      = $_POST["harga"];
    
                $jumlah_dipilih = count($jmlh);
                $totalQty       = array_sum($jmlh);

                if ($idpoproduk == '486' && $totalQty > 2) {
                    echo "<script>alert('Total Qty tidak boleh lebih dari 2!');</script>";
                    echo "<script>location='formpostok.php?id=$idpoproduk';</script>";
                    exit();
                }

                $subtotal       = 0;  
                $total          = 0;
                $jmlhakhir      = 0;
                if (isset($_GET['invoice'])) {
                    $invoice = $_GET['invoice'];
                } else {  
                    // Fungsi untuk menghasilkan angka acak dengan panjang tertentu
                    function generateAngkaAcak($length) {
                        $angka_acak = '';
                        for ($i = 0; $i < $length; $i++) {
                        // Menggunakan mt_rand untuk angka acak dari 0 hingga 9
                        $angka_acak .= mt_rand(0, 9);
                        }
                        return $angka_acak;
                    }
                    // Menghasilkan angka acak dengan panjang minimal 7 dan maksimal 7 angka
                    $angka_acak = generateAngkaAcak(5);
                    $invoice = 'D' . $idpoproduk . '-' . $idadmin . $angka_acak;
                }
                
                for($x = 0; $x < $jumlah_dipilih; $x++){
                    $total      = $jmlh[$x] * $harga[$x];
                    $tot        = $total;
                    $jmlhakhir += $jmlhakhir + $jmlh[$x];
                    $tot        = 0;
    
                    $sql        = "SELECT stok FROM pokategori WHERE idpo='$idpo[$x]'";
                    $query      = $koneksi->query($sql);
                    $sisa       = $query->fetch_assoc();
    
                        if ($sisa['stok'] >= $jmlh[$x]) {
                            $koneksi->query("INSERT into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) values
                            (null,'$idadmin','$idpoproduk','$idpo[$x]','$idpodetail[$x]','$jmlh[$x]','$total',
                            '$invoice','Belum DP',NOW(),'$waktu')");
                            $sqlpo = $koneksi->query("update pokategori set stok=stok-'$jmlh[$x]' where idpo='$idpo[$x]'");
                            
                        } else { 
                            $jmlh[$x] = 0;
                            $total = 0;
                            $sqlpo = $koneksi->query("INSERT into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) values
                            (null,'$idadmin','$idpoproduk','$idpo[$x]','$idpodetail[$x]','$jmlh[$x]','$total','$invoice','Belum DP',NOW(),'$waktu')");
                        }
                }

                if ($sqlpo) {
                    // if ($idpodetail = '405') {
                    //     echo "<script>alert('Data berhasil dikirim');</script>";
                    //     echo "<script>location='datapokolibri3.php?id=$idpoproduk&invoice=$invoice';</script>";
                    // } else {
                    // }
                    echo "<script>alert('Data berhasil dikirim');</script>";
                    echo "<script>location='datapo.php?id=$idpoproduk&invoice=$invoice';</script>";
                } else {
                    echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
                    echo "<script>location='ubahpostok.php?id=$idpoproduk&invoice=$invoice';</script>";
                }
            } catch (Exception $error) {
                echo "Error: " . $error->getMessage();
            }
        }
    ?>
    <!-- PHP SYNTAK END -->

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->
    
    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>