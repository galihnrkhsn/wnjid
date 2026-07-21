    <h3 class="text-center"><p>Order Mitra</p></h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="tb_belumbayar">
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
            <tbody>
                <?php 
                    $no         = 1;
                    $idmitra    = $_SESSION["idadmin"];
                    $sql        = mysqli_query($koneksi, "SELECT DISTINCT *,
                                                            admin_mitra.namamitra,
                                                            sum(ordermitra.jumlah) AS qty,
                                                            ordermitra.status AS statusnya 
                                                            FROM `ordermitra` 
                                                            INNER JOIN orderpengiriman ON orderpengiriman.invoice = ordermitra.invoice    
                                                            INNER JOIN admin_mitra ON admin_mitra.idadmin = ordermitra.idmitra 
                                                            where ordermitra.idmitra = '$idmitra' AND (ordermitra.payment<>'Lunas' or ordermitra.payment<>'LUNAS') GROUP BY ordermitra.invoice ORDER BY ordermitra.idorder DESC ");
                    while($data=$sql->fetch_assoc()){
                ?>
                <tr>
                    <td>
                        <b>
                            <? if ($data['idproduk'] >= 14039 && $data['idproduk'] <= 14053): ?>
                                <a class="text-primary" href="detailordershort.php?id=<?= $data['invoice'] ?>">#<?= $data['invoice'] ?></a>
                            <? elseif (strtotime($data['tgl']) > strtotime('2024-11-02 23:59:59')): ?>
                                <a class="text-danger" href="detailorderb2.php?id=<?php echo $data['invoice']; ?>">#<?php echo $data['invoice'];?></a>
                            <? else :?>
                                <a class="text-danger" href="detailorderb.php?id=<?php echo $data['invoice']; ?>">#<?php echo $data['invoice'];?></a>
                            <? endif ?>
                        </b>
                    </td>
                    <td><?php echo $data['qty']; ?></td>
                    <td><i class="fas fa-user"></i> <?php echo $data['namapenerima'];?></td>
                    <td><i class="fas fa-truck"></i> <?php echo strtoupper($data['ekspedisi']);?> - <?= $data['layanan']; ?></td> 
                    <td><?php echo $data['payment']; ?></td>
                    <td><?php echo $data['tgl']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include "menubawahstore.php"; ?>
    <?php include "settingdatatables.php"; ?>
    <?php
    if(isset($_POST["done"])){
    $invoice = $_POST['id'];

    $koneksi->query("UPDATE ordermitra SET status='Selesai',payment='LUNAS' where invoice='$invoice' "); 
    echo "<script>location='transaksi.php'</script>";
    }
    ?>

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.js"></script>