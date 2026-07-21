<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $invoice=$_GET['invoice'];
    $idadmin=$_SESSION["idadmin"];
    $query = "SELECT COUNT(*) as jumlah,
                    poproduk.idpoproduk,
                    poproduk.namapo,
                    poproduk.status,
                    poproduk.note,
                    poproduk.pembayaran,
                    pomitra.ket,
                    pomitra.tgl,
                    pomitra.waktu,
                    pomitra.invoice
                FROM poproduk 
                INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk 
                WHERE poproduk.idpoproduk = '$idpoproduk' 
                AND pomitra.idmitra = '$idadmin' 
                AND pomitra.invoice = '$invoice'
                GROUP BY poproduk.idpoproduk,
                    poproduk.namapo,
                    poproduk.status,
                    poproduk.note,
                    poproduk.pembayaran,
                    pomitra.ket,
                    pomitra.tgl,
                    pomitra.waktu,
                    pomitra.invoice";
    $sql = mysqli_query($koneksi, $query);  
    $datapo = mysqli_fetch_array($sql);

    $query2 = "SELECT 
                    pomitra.status,
                    pomitra.invoice,
                    pomitra.custom
                FROM poproduk 
                inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                WHERE poproduk.idpoproduk='$idpoproduk' AND pomitra.idmitra='$idadmin' AND pomitra.invoice = '$invoice'";
    $sql2 = mysqli_query($koneksi, $query2);  
    $data2 = mysqli_fetch_array($sql2);  

    $note=$datapo['note'];
    $pembayaranpo = $datapo['pembayaran'];
    $invoice = $datapo['invoice'];
    $query_tgl = "SELECT bukapo.idpoproduk, bukapo.tgl_bayar, jenis_po
                    FROM bukapo  
                    WHERE bukapo.idpoproduk='$idpoproduk'";
    $sqlpo_tgl = mysqli_query($koneksi, $query_tgl);  
    $datapo_tgl = mysqli_fetch_array($sqlpo_tgl); 
    $tgl_bayar = $datapo_tgl['tgl_bayar']; 
    $waktu_bayar = '23:59:59';

    $angka1 = 3;
    $angka2 = 3;
    $angka3 = 3;
    $angka5 = 0;
    $angka6 = 0;

    if ($idpoproduk==167 or $idpoproduk==173 or $idpoproduk==176) {
        $query3 = "SELECT SUM(pomitra.jumlah) as jumlahnya
                    FROM pomitra
                    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail 
                    WHERE (podetail.variant LIKE '%Sz M Paket%' or podetail.variant LIKE '%Sz L Paket%') and pomitra.invoice='$invoice'
                    GROUP BY pomitra.invoice";
        $sql3 = mysqli_query($koneksi, $query3);  
        $data3 = mysqli_fetch_array($sql3); 
        $angka1 = $data3['jumlahnya'];
        $query4 = "SELECT SUM(pomitra.jumlah) as jumlahnyaa
                    FROM pomitra
                    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail 
                    WHERE (podetail.variant LIKE '%Sz XL Paket%' or podetail.variant LIKE '%Sz JMB Paket%') and pomitra.invoice='$invoice'
                    GROUP BY pomitra.invoice";
        $sql4 = mysqli_query($koneksi, $query4);  
        $data4 = mysqli_fetch_array($sql4); 
        $angka2 = $data4['jumlahnyaa'];    
    }

    if ($idpoproduk==174) {
        $query7 = "SELECT SUM(pomitra.jumlah) as jumlahnya
                    FROM pomitra
                    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail 
                    WHERE (podetail.variant LIKE '%Paket%') and pomitra.invoice='$invoice'
                    GROUP BY pomitra.invoice";
        $sql7 = mysqli_query($koneksi, $query7);  
        $data7 = mysqli_fetch_array($sql7);
        $angka3 = $data7['jumlahnya'];
        
    }

    $findUser   = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin='$idadmin'");
    $queryUser  = $findUser->fetch_assoc(); 

    $custom     = $data2['custom'] ?? null;
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <div class="container mt-3" id="invoice-content">
        <h2 class="text-center mb-4">SALES INVOICE</h2>
        <p class="text-center"><strong><?php echo $data['namapo']; ?></strong></p><br>
        <p class="text-left">Nama Mitra  : <?php echo $queryUser["namamitra"]; ?> </p>
        <p class="text-left">Alamat  : <?php echo $queryUser["alamat"]; ?> </p>
        <p class="text-left">No Invoice  : <?php echo $invoice ; ?> </p>
        <div id="agen" class="container tab-pane active">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Qty</th>
                            <?php if ($idpoproduk <> 186 && $idpoproduk <> 187): ?>
                                <th>Satuan</th>
                                <th>Total</th>
                            <?php endif ?>
                            <?php if (!empty($custom)) :?>
                                <th>Custom</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            include "koneksi.php";

                            $idadmin = $_SESSION['idadmin'];
                            $idpoproduk = $_GET["id"];
                            $no = 1;
                            $jumlah = 0;
                            $subtotal = 0;

                            $sql = mysqli_query($koneksi, "SELECT podetail.variant,
                                                                    podetail.harga,
                                                                    pomitra.jumlah,
                                                                    pomitra.total,
                                                                    pomitra.idpomitra,
                                                                    poproduk.namapo,
                                                                    poproduk.diskon,
                                                                    pomitra.custom
                                                                FROM pomitra 
                                                                JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                JOIN poproduk ON poproduk.idpoproduk = pomitra.idpoproduk
                                                                WHERE pomitra.invoice = '$invoice' 
                                                                AND pomitra.jumlah > 0");

                            while($data = mysqli_fetch_array($sql)){ ?>
                            <tr>
                                <td class="align-middle"><?php echo $no++; ?></td>
                                <td class="align-middle">
                                    <?php echo $data['variant']; ?>
                                    <?php if ($idpoproduk == 186 && $idpoproduk == 186): ?>
                                        <?= $data['custom']; ?>
                                    <?php endif ?>
                                </td>
                                <td class="align-middle">
                                    <?php echo $data['jumlah']; ?>
                                </td>
                                <?php if ($idpoproduk <> 186 && $idpoproduk <> 187): ?>
                                    <td class="align-middle">Rp. <?php echo number_format($data['harga']); ?></td>
                                    <td class="align-middle">Rp. <?php echo number_format($data['total']); ?></td>
                                <?php endif ?>
                                <?php if (!empty($custom)) : ?>
                                    <td><?= $data['custom'] ?></td>
                                <?php endif ?>
                                <?php
                                $angkavoal = $data['jumlah'];
                                $sum += $data['jumlah'];
                                $jumlah += $data['jumlah'] * $data['harga'];
                                $invoice = $data['invoice'];
                                $persen_tambahan = $data['diskon'];
                                ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <table style="float: right;width: 100%">
                    <tbody style="float: right;">
                        <?php if ($idpoproduk == 186 || $idpoproduk == 187): ?>
                            <tr>
                                <th style="padding-bottom: 5%;">Harga Box</th>
                                <td style="padding-bottom: 5%;">:</td>
                                <td style="padding-bottom: 5%;">Rp. 110.000</td>
                            </tr>
                            <tr>
                                <th style="padding-bottom: 5%;">Jumlah Seri</th>
                                <td style="padding-bottom: 5%;">:</td>
                                <td style="padding-bottom: 5%;"><?php echo $angkavoal; ?></td>
                            </tr>
                        <?php endif ?>
                            <tr>
                                <th style="padding-bottom: 5%;">Total Qty</th>
                                <td style="padding-bottom: 5%;">:</td>
                                <td style="padding-bottom: 5%;"><?php echo $sum; ?></td>
                            </tr>

                        <?php if ($angka1 % 3 == 0 and $angka2 % 3 == 0 and $angka3 % 3 == 0): ?>
                            <tr>
                                <th>JUMLAH</th>
                                <td>:</td>
                                <td>
                                    <?php 
                                        $sqlharga2 = "SELECT MAX(total) as totalnya, invoice FROM `pomitra` WHERE `idpoproduk`= '$idpoproduk' AND idmitra = '$idadmin' GROUP BY invoice;";
                                        $queryharga2 = $koneksi->query($sqlharga2);
                                        $sisaharga2 = $queryharga2->fetch_assoc(); 
                                        $stokharga2 = $sisaharga2['totalnya']; 
                                        if ($idpoproduk=="120") {
                                        $jumlah=$stokharga2;
                                        }
                                    ?>        
                                    Rp. <?php echo number_format($jumlah); ?>                     
                                </td>
                            </tr>
                            <?php 
                                if ($idpoproduk=='96') {
                                $persen=50;
                                $diskon=50/100*$jumlah;
                                } else {
                                $persen=35;
                                $diskon=35/100*$jumlah;
                                }
                                $diskon_tambahan = $persen_tambahan/100*$jumlah;
                                $subtotal=$jumlah-$diskon-$diskon_tambahan;  
                            ?>
                            <tr>
                                <th>Diskon DB <?= $persen; ?>%</th>
                                <td>:</td>
                                <td>Rp. <?php echo number_format($diskon); ?></td>
                            </tr>
                            <?php if ($diskon_tambahan>0): ?>
                            <tr>
                                <th>Diskon Tambahan</th>
                                <td>:</td>
                                <td>Rp. <?= number_format($diskon_tambahan); ?></td>
                            </tr>                           
                            <?php endif ?>     
                            <tr>
                                <th style="padding-bottom: 5%;">Total Bayar</th>
                                <td style="padding-bottom: 5%;">:</td>
                                <td style="padding-bottom: 5%;">Rp. <?php echo number_format($subtotal); ?></td>
                            </tr>
                        <?php endif ?>
                        <?php
                            if ($idpoproduk == '220') {
                                $angkadp = 30;
                                $dp = $subtotal*$angkadp/100;
                            } 
                            else {
                                $angkadp = 50;
                                $dp = $subtotal*$angkadp/100;
                            }
                            $invoice=$data2['invoice'];
                            if ($idpoproduk == '228') {
                                $namapayemnt='Pembayaran';
                            } else {
                                $namapayemnt='DP';
                            }
                            $jenispayment='dp';
                            if ($pembayaranpo=='Lunas') {
                                $dp = $subtotal;
                                $namapayemnt='Pembayaran';
                            } ?>
                        <?php if ($pembayaranpo<>'Lunas'): ?>
                            <tr>
                                <th>Jumlah DP PO <?= $angkadp;?>%</th>
                                <td>:</td>
                                <td>Rp. <?php echo number_format($dp); ?></td>
                            </tr>
                            <?php
                                include "koneksi.php";      
                                $invoice = $data2['invoice']; 
                                $sqldp = mysqli_query($koneksi, "SELECT popembayaran.invoice, popembayaran.jmlhtransfer,popembayaran.jmlh_lunas
                                                                FROM `popembayaran` 
                                                                WHERE popembayaran.invoice ='$invoice' ");
                                $datadp = mysqli_fetch_array($sqldp);
                            ?>
                            <tr>
                                <th style="padding-top: 5%;">Status PO</th>
                                <td style="padding-top: 5%;">:</td>
                                <td style="padding-top: 5%;">
                            <?php if ($idpoproduk<>'161'): ?>
                                    <?= $data2['status']; ?>
                            <?php else: ?>
                                    Sudah Confirm Payment
                            <?php endif ?>
                                </td>
                            </tr>
                        <?php if ($datadp['invoice']<>""): ?> 
                            <?php if ($idpoproduk<>'161'): ?>   
                            <tr>
                                <th>Konfirmasi DP</th>
                                <td>:</td>
                                <td>Rp. <?php echo number_format($datadp['jmlhtransfer']); ?></td>
                            </tr>
                            <tr>
                                <th>Konfirmasi Pelunasan</th>
                                <td>:</td>
                                <td>Rp. <?php echo number_format($datadp['jmlh_lunas']); ?></td>
                            </tr>        
                            <tr>
                                <th>Sisa Tagihan</th>
                                <td>:</td>
                                <td>
                                Rp. 
                                <?php if ($sisa>0): ?>
                                    +             
                                <?php endif; ?> 
                                <?php echo number_format($sisa); ?> 
                                </td>
                            </tr>
                            <?php endif; ?>  
                        <?php endif; ?>	       
                        <?php endif; ?>    
                    </tbody>
                </table>
                <p align="left"><strong>Note: </strong><?php echo $note ?></p>
            </div>
        </div>
    </div>
    <?php
        $jenis_po = $datapo_tgl['jenis_po'];
        if ($jenis_po == 'PO Custom Inisial') {
            $redirect = "datapocustom3.php";
        } else {
            // PO tanpa Stok dan PO dengan Stok
            $redirect = "datapo.php";
        }
    ?>
    <script>
        // Tunggu halaman benar-benar siap
        window.onload = function () {
            const element = document.getElementById('invoice-content');

            const opt = {
                margin:       0.5,
                filename:     'Invoice-<?= $invoice ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            // Buat PDF dan auto-download
            html2pdf().set(opt).from(element).save().then(() => {
                // Redirect sesuai jenis_po
                window.location.href = "<?= $redirect ?>?idmitra=<?= $idmitra ?>&id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>";
            });
        };
    </script>
</body>
</html>