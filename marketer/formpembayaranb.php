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
            // Include / load file koneksi.php
            include "koneksi.php";
            
            $idmitra = $_SESSION['idmitramarketer'];
            // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
            $sql = mysqli_query($koneksi, "SELECT DISTINCT *,
                sum(ordermarketer.jumlah) as qty,
                ordermarketer.status as statusnya 
                FROM `ordermarketer` 
                INNER JOIN mitramarketer on mitramarketer.idmitramarketer=ordermarketer.idmitramarketer 
                INNER JOIN orderpengiriman on orderpengiriman.invoice = ordermarketer.invoice   

                where ordermarketer.idmitramarketer='$idmitra' and (ordermarketer.payment<>'Lunas' or ordermarketer.payment<>'LUNAS') GROUP BY ordermarketer.invoice ORDER BY ordermarketer.idorder DESC");
            
            $no =1; // Untuk penomoran tabel
            
            while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
        ?>
                     
        <tr>
            <td>
                <b>
                <?php if ($data['idproduk'] >= 14039 && $data['idproduk'] <= 14053 ): ?>
                    <a class="text-primary" href="detailordershort.php?id=<?= $data['invoice'] ?>">#<?= $data['invoice'] ?></a>
                    <? elseif (strtotime($data['tgl']) > strtotime('2024-11-02 23:59:59')): ?>
                        <a class="text-danger" href="detailorder2.php?id=<?= $data['invoice']; ?>">#<?= $data['invoice'];?></a>
                    <? else :?>
                        <a class="text-danger" href="detailorder.php?id=<?= $data['invoice']; ?>">#<?= $data['invoice'];?></a>
                    <? endif ?>
                </b>
            </td>
            <td><?= $data['qty']; ?></td>
            <td><?= $data['namapenerima'];?></td>
            <td><?= strtoupper($data['ekspedisi']);?> - <?= $data['layanan']; ?></td> 
            <td><?= $data['payment']; ?></td>
            <td><?= $data['tgl']; ?></td>
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
        
        $koneksi->query("UPDATE ordermarketer SET status='Selesai',payment='LUNAS' where invoice='$invoice' "); 
        echo "<script>location='transaksi.php'</script>";
        }
    ?>
    
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.js"></script>
