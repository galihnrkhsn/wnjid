<?php 
session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesReseller.php';              
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Distributor | Wanoja</title>
    </head>
<body>
    <!-- NAVBAR -->
        <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <div class="container mt-2">

        <div>
            <?php
                //info message
                if(isset($_SESSION['message'])){
                ?>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-6">
                        <div class="alert alert-info text-center">
                            <?php echo $_SESSION['message']; ?>
                        </div>
                        </div>
                    </div>
                <?php
                unset($_SESSION['message']);
                }
                ?>      
            <p align="right">
                <input type="radio" onclick="javascript:window.location.href='store3.php'; " checked="checked"> Mode Hemat  &nbsp&nbsp
                <input type="radio" onclick="javascript:window.location.href='store4.php'; "> Mode Cantik
            </p>
            <table class="table table-responsive" id="tb_store" border="0" >
                <thead>
                    <tr>
                        <td><span class="glyphicon glyphicon-shopping-cart"></span></td>
                        <td>Stock</td>
                        <td>Nama Produk</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = mysqli_query($koneksi, "SELECT products.namaproduk, variants.* FROM variants 
                                                            INNER JOIN products ON variants.idproducts = products.id
                                                            WHERE variants.status <> 1 AND variants.stock > 0
                                                            ORDER BY tgl DESC, variants.idproducts DESC, namaproduk ASC");
                        $no = 1;
                        while($data = mysqli_fetch_array($sql)){
                    ?>
          
                    <tr>
                        <td>
                            <?php if ($data['status'] == 0): ?>
                                <a class="btn btn-info btn-xs" href="add_chart2.php?id=<?php echo $data['id']; ?>&harga=<?php echo $data['harga']; ?>" class="btn btn-primary"> <span class="fas fa-cart-shopping"></span></a>
                            <?php else: ?>
                                <button class="btn btn-info btn-xs"> <span class=""></span><i class="fas fa-cart-shopping"></i></button>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if ($data['status'] == 0): ?>
                            <?php echo $data['stock']; ?> 
                            <?php else: ?>
                                -
                            <?php endif ?>
                        </td>
                        <td>
                            <?php echo $data['namaproduk'] . ' ' . $data['variant'] . ' - ' . $data['size']; ?>
                            <?php if ($data['status']==2): ?>
                            (Produk sedang di update, akan aktif setelah proses update selesai)
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <br><br><br><br>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <?php include "menubawahstore.php"; ?>
    <?php include "settingdatatables.php"; ?>    
  </body>
</html>

