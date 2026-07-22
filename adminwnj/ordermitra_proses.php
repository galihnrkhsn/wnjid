<?php
    include 'koneksi.php';

    if (isset($_POST['perpanjang_db'])) {
        if (isset($_POST['idpomitra_dbcheck'])) {
            foreach($_POST['idpomitra_dbcheck'] as $updateid) {
                $query = "UPDATE ordermitra SET tgl = NOW() WHERE invoice = '$updateid'";
                $sql = mysqli_query($koneksi, $query);
            }
            if (!$sql) {
                echo "<script>alert('Order gagal diperbarui');</script>";
                echo "<script>location='ordermitra.php';</script>";
            }
            echo "<script>alert('Order berhasil diperbarui');</script>";
            echo "<script>location='ordermitra.php';</script>";
        }
    }

    if (isset($_POST["done5"])) {
        if (isset($_POST['idpomitra_dbcheck'])) {
            foreach($_POST['idpomitra_dbcheck'] as $updateid) {
                $query = "UPDATE ordermitra SET status='Sedang DiKirim' WHERE invoice='$updateid'";
                $sql = mysqli_query($koneksi, $query);
            }

            if ($sql) {
                echo "<script>alert('Status diubah menjadi Sedang Dikirim');</script>";
                echo "<script>location='ordermitra.php';</script>";
            } else {
                echo "Maaf, Terjadi kesalahan saat anda mencoba untuk menyimpan data ke database";
                echo "<script>loation='ordermitra.php';</script>";
            }
        }
    }

    if (isset($_POST["selesai"])) {
        if (isset($_POST['idpomitra_dbcheck'])) {
            foreach($_POST['idpomitra_dbcheck'] as $updateid) {
                $query = "UPDATE ordermitra SET status='Selesai' WHERE invoice='$updateid'";
                $sql = mysqli_query($koneksi, $query);
            }

            if ($sql) {
                echo "<script>alert('Status diubah menjadi selesai');</script>";
                echo "<script>location='ordermitra.php'</script>";
            } else {
                echo "Maff, yerjadi kesalahan saat mencoba untuk menyimpan data ke databse.";
                echo "<script>location='ordermitra.php'</script>";
            }
        }
    }

    if (isset($_POST["bayar_db"])) {
        if (isset($_POST['idpomitra_dbcheck'])) {
            foreach($_POST['idpomitra_dbcheck'] as $updateid) {
                $invoice    = $updateid;
                $tampil     = $koneksi->query("SELECT SUM(ordermitra.jumlah) AS qty, ordermitra.payment FROM ordermitra WHERE invoice='$updateid' ");
                $tampilMas  = $tampil->fetch_assoc();
                $jumlah_qty = $tampilMas['qty'];
                if ($tampilMas['payment'] == 'Belum Bayar' || $tampilMas['payment'] == 'Tunggu Confirm Admin') {
                    $query = "UPDATE ordermitra SET status='Proses', payment='Lunas', status_progres = 0 WHERE invoice='$invoice'";
                    $sql = mysqli_query($koneksi, $query);

                    if (substr($invoice,0,1) == "F") {
                        $jumlah_produknya = $jumlah_qty/2;
                        $sql = "SELECT ordermitra.idmitra, ordermitra.tgl, produk.harga AS subtotal
                        FROM ordermitra INNER JOIN produk
                        ON produk.idproduk = ordermitra.idproduk
                        WHERE ordermitra.invoice = '$invoice'
                        AND ordermitra.jumlah > 0
                        AND produk.idkategori = 11
                        ORDER BY produk.harga DESC
                        LIMIT " . $jumlah_produknya . " ";

                        $query = $koneksi->query($sql);
                        while ($ga = $query->fetch_assoc()) {
                            $total += $ga['subtotal'];
                            $idadmin = $ga['idmitra'];
                            $tglorder = $ga['tgl'];
                        }
                    } else {
                        $dataorder = $koneksi->query("SELECT idmitra, tgl, SUM(subtotal) AS totalnya FROM ordermitra WHERE invoice='$invoice' ");
                        $tampildeui = $dataorder->fetch_assoc();
                        $idadmin = $tampildeui['idmitra'];
                        $total = $tampildeui['totalnya'];
                        $tglorder = $tampildeui['tgl'];
                    }

                    $diskon = $total * 35/100;

                    $dataongkir = $koneksi->query("SELECT idmitra, tgl, SUM(subtotal) AS totalnya FROM ordermitra WHERE invoice='$invoice'");
                    $tampilongkir = $dataongkir->fetch_assoc();
                    $berat = $tampilongkir['berat'];
                    $ongkir = $tampilongkir['ongkir'];
                    $dropship = $tampilongkir['dropship'];

                    $biayads = 0;

                    if ($berat <= 5000 and $berat >= 0 and $dropship == 'ya') {
                        $biayads = 3000;
                    } elseif ($berat <= 10000 and $berat >= 6000 and $dropship == 'ya') {
                        $biayads = 5000;
                    }

                    $totalsemua = $total - $diskon + $ongkir + $biayads;

                    $koneksi->query("SELECT INTO saldo (id_saldo, idadmin, tgl, transaksi, debit, credit) VALUES (NULL, '$idadmin', '$tglorder', 'Order Invoice #$invoice', '0' '$totalsemua')");
                    $koneksi->query("INSERT INTO saldo (id_saldo,idadmin,tgl,transaksi,debit,credit) VALUES (NULL,'$idadmin',NOW(),'Transfer invoice #$invoice','0','0') ");
                }
            }

            if ($koneksi) {
                echo "<script>alert('Payment diubah menjadi lunas');</script>";
                echo "<script>location='ordermitra.php';</script>";
            } else {
                echo "<script>alert('Maaf, Terjadi kesalahan saat mencoba untuk meyimpan data ke databse.');</script>";
                echo "<script>location='ordermitra.php';</script>";
            }
        }
    }
?>

<?php 
if(isset($_POST["print_db"])){ ?>
 <style type="text/css">
  @media print {
  footer {page-break-after: always;}
}
</style>

<?php    
if(isset($_POST['idpomitra_dbcheck'])){
  foreach($_POST['idpomitra_dbcheck'] as $updateid){ 
    $invoice=$updateid;
  $datamitra=$koneksi->query("SELECT admin_mitra.idadmin,
                                    admin_mitra.namamitra, 
                                    admin_mitra_cs.namacs
                                    FROM ordermitra
                                    JOIN admin_mitra on ordermitra.idmitra = admin_mitra.idadmin
                                    INNER JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                    WHERE ordermitra.invoice='$updateid'");
                            $tampilnama=$datamitra->fetch_assoc();       
?>

<link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

<!-- Custom styles for this template-->
<link href="css/sb-admin-2.min.css" rel="stylesheet">
<style type="text/css">
    table, th, td, tr {
        border: 2px solid;
        border-collapse: collapse;
    }
    body{
        color: black;
    }
</style>
<center><p style="font-size:60;margin-bottom:0;"><strong>WNJ.ID</strong></p></center>
<center><p style="font-size:40;margin-top:0;"><strong>Inv. <?= $invoice?></strong></p></center>

    <div class="row align-items-start">
        <div class="col">
            <h5><strong style="float:left">Mitra : <?php echo $tampilnama['idadmin']; ?> </strong></h5>
        </div>
        <div class="col"></div>
        <div class="col">
            <h5><strong style="float:rigth">Nama CS : <?php echo $tampilnama['namacs']; ?> </strong></h5>
        </div>
    </div>
    <table style="width:100%;font-size: 28px">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th>Checker</th>
                <th>Penerima</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $datapodropship = $koneksi->query("SELECT produk.namaproduk, ordermitra.jumlah
                                                    FROM ordermitra
                                                    JOIN produk ON produk.idproduk = ordermitra.idproduk
                                                    WHERE ordermitra.invoice = '$updateid'
                                                    AND ordermitra.jumlah > 0
                                                ");
                $no=1;
                while($tampilkan = $datapodropship->fetch_assoc()){
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>    
                    <td><?= $tampilkan['namaproduk'];?></td>
                    <td><center><?= $tampilkan['jumlah'];?></center></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php 
                    $qty += $tampilkan['jumlah'];
                    $totalbayar +=$total;
                ?>                       
            <?php } ?>
        </tbody>
    </table>
    <br><br>
    <center>
        <table style="font-size: 25px;border-color: white;">
            <tr>
                <td style="border-color: white;">
                    <center>Gudang</center>
                    <br><br><br><br>      
                </td>
                <td width="5%" style="border-color: white;"></td>
                <td style="border-color: white;">
                    <center>Checker</center>
                    <br><br><br><br>      
                </td>
                <td width="5%" style="border-color: white;"></td>    
                <td style="border-color: white;">
                    <center>Penerima</center>
                    <br><br><br><br>
                </td>
            </tr>
            <tr>
                <td style="border-color: white;">
                    <center>
                        (<span style="color:transparent;">_____________________</span>)
                    </center>      
                </td >
                <td style="border-color: white;"></td>
                <td style="border-color: white;">
                    <center>
                        (<span style="color:transparent;">_____________________</span>)
                    </center>     
                </td>   
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
        <p style="font-size: 22px"> 
            Note : Setelah barang diterima, mohon langsung dicek. Surat jalan yang sudah diverifikasi dan sudah ditandatangani mohon untuk difoto dan dikirim melalui No HP CS Pusat maksimal 3 X 24 Jam
            <br><br>
            <?php if (substr($invoice,0,1)=="V"): ?>
                <strong>Keterangan Isi Box</strong>
                <br>
                <?php 
                    $ambil_box      = $koneksi->query("SELECT * FROM hampers
                                                        WHERE hampers.no_ds = '$invoice'
                                                        ORDER BY nobox ASC"); 
                    while($data_box = $ambil_box->fetch_assoc()){
                    $result_explode = explode('|', $data_box['ucapan']);
                    $dari           = $result_explode[0];    
                    $kepada         = $result_explode[1];    
                    $ucapan         = $result_explode[2];        
                ?>
                <label>Box <?= $data_box['nobox'] ?> : <?php echo str_replace("Voal Hampers","",str_replace("|",", ",$data_box['idpodetail'])); ?> 
                    <?php if ($dari or $kepada or $ucapan): ?>
                        <span style="color: red">(Req. Kartu Ucapan)</span>
                    <?php endif; ?></label>
                <br>
                <?php } ?>   
            <?php endif; ?>
        </p>
    </div>                  
    <br>


        
        <div class="footer"></div>
        <footer></footer>


    <?php
    }
    }
    ?>
    <script>
    window.print();
    </script>
    <?php
    } 
    ?>
