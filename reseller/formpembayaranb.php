<h3 class="text-center"><p>Order Mitra</p></h3>
<div class="table-responsive">
    <table class="table" id="tb_belumbayar_agen">
        <thead>
            <tr>
                <th>Invoice</th>
                <th>QTY</th>
                <th>Penerima</th>
                <th>Ekspedisi</th>
                <th>Payment</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <?php
            include "koneksi.php";
            $no         = 1;
            $idmitra    = $_SESSION['idmitrareseller'];
            $sql        = mysqli_query($koneksi, "SELECT DISTINCT *,
                                                        sum(orderreseller.jumlah) AS qty,
                                                        orderreseller.status AS statusnya 
                                                        FROM `orderreseller` 
                                                        INNER JOIN mitrareseller on mitrareseller.idmitrareseller=orderreseller.idmitrareseller 
                                                        INNER JOIN orderpengiriman on orderpengiriman.invoice = orderreseller.invoice 
                                                        WHERE orderreseller.idmitrareseller = '$idmitra' 
                                                        AND (orderreseller.payment<>'Lunas' OR orderreseller.payment <> 'LUNAS') 
                                                        GROUP BY orderreseller.invoice ORDER BY orderreseller.idorder DESC");            
            while($data = mysqli_fetch_array($sql)){
        ?>
                     
        <tr>
            <td>
                <b>
                    <?php if ($data['idproduk'] >= 14039 && $data['idproduk'] <= 14053 ): ?>
                        <a class="text-primary" href="detailordershort.php?id=<?= $data['invoice'] ?>">#<?= $data['invoice'] ?></a>
                    <? elseif (strtotime($data['tgl']) > strtotime('2024-11-02 23:59:59')): ?>
                        <a class="text-danger" href="detailorder2.php?id=<?php echo $data['invoice']; ?>">#<?php echo $data['invoice'];?></a>
                    <? else :?>
                        <a class="text-danger" href="detailorder.php?id=<?php echo $data['invoice']; ?>">#<?php echo $data['invoice'];?></a>
                    <? endif ?>
                </b>
            </td>
            <td><?php echo $data['qty']; ?></td>
            <td><?php echo $data['namapenerima'];?></td>
            <td><?php echo strtoupper($data['ekspedisi']);?> - <?= $data['layanan']; ?></td> 
            <td><?php echo $data['payment']; ?></td>
            <td><?php echo $data['tgl']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>
<?php //include "menubawahstore.php"; ?>
<?php include "settingdatatables.php"; ?>
    </body>
    <?php
        if(isset($_POST["done"])){
        $invoice = $_POST['id'];
        
        $koneksi->query("UPDATE orderreseller SET status='Selesai',payment='LUNAS' where invoice='$invoice' "); 
        echo "<script>location='transaksi.php'</script>";
        }
    ?>
    
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.js"></script>
