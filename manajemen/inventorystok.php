<?php 
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesManage.php';

    $title  = $namalengkap;
    $tipe   = $data['tipe'];
?>

<?php include 'assets/components/Navbar/navbar.php'; ?>    

    <div class="container-fluid mt-5">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h2 class="m-0 font-weight-bold text-secondary">Inventory Stok
                <a href="index.php" style="float: right;"><i class="fas fa-arrow-left fa-m "></i></a>
            </h2>
        </div>              
        <h1>Produk Lama</h1>      
        <!-- Content Row -->
        <div class="row" style="margin: auto;">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tb_stock">
                    <thead>
                        <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>HPP</th>
                        <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            $no=1;
                            $ambil=$koneksi->query("SELECT * from produk WHERE stock>0 and harga>0 and idkategori>0 and status=0 ORDER BY idproduk DESC"); 
                            while($tampil=$ambil->fetch_assoc()){
                        ?>      
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $tampil['namaproduk']; ?> </td>
                            <td><?= $tampil['stock']; ?></td>
                            <td>Rp. <?= number_format($hpp = $tampil['harga']/2); ?></td>
                            <td>Rp. <?= number_format($total = $tampil['stock']*($tampil['harga']/2)); ?></td>
                        </tr>
                        <?php 
                        $total_stock += $tampil['stock'];
                        $total_hpp += $hpp;
                        $total_inventory += $total; 

                        ?>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Total</td>
                            <td><?= $total_stock; ?></td>
                            <td>Rp. <?= number_format($total_hpp); ?></td>
                            <td>Rp. <?= number_format($total_inventory);?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>  
        </div><!-- Content Row -->
        <hr>
        <h1>Produk Baru</h1>
        <!-- Content Row -->
        <div class="row" style="margin: auto;">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tb_stock1">
                    <thead>
                        <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>HPP</th>
                        <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            $no=1;
                            $ambil=$koneksi->query("SELECT * from products 
                                                    INNER JOIN variants ON variants.idproducts = products.id 
                                                    WHERE variants.stock > 0 
                                                    AND variants.harga > 0 
                                                    AND products.idkategori > 0 
                                                    AND variants.status = 0 
                                                    ORDER BY products.id DESC"); 

                            while($tampil=$ambil->fetch_assoc()){
                        ?>      
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $tampil['namaproduk']; ?> <?= $tampil['variant']; ?> <?= $tampil['size']; ?></td>
                            <td><?= $tampil['stock']; ?></td>
                            <td>Rp. <?= number_format($hpp = $tampil['harga']/2); ?></td>
                            <td>Rp. <?= number_format($total = $tampil['stock']*($tampil['harga']/2)); ?></td>
                        </tr>
                        <?php 
                        $total_stock += $tampil['stock'];
                        $total_hpp += $hpp;
                        $total_inventory += $total; 

                        ?>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Total</td>
                            <td><?= $total_stock; ?></td>
                            <td>Rp. <?= number_format($total_hpp); ?></td>
                            <td>Rp. <?= number_format($total_inventory);?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>  
        </div><!-- Content Row -->
        <br><br><br><br>
<?php include 'assets/components/Footer/footer.php'; ?>

