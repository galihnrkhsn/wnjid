<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

                        //initialize total
                        $idmitra=$_SESSION["admin_mitra"]["idadmin"];
                    
                        $keranjang=0;
            
                        $sql2 = "SELECT * FROM keranjang  WHERE idmitra='$idmitra' and jmlh>0 ";
                        $query2 = $koneksi->query($sql2);
                        while($apaya = $query2->fetch_assoc()){
                            $keranjang+=$apaya['jmlh'];
                        }
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>WNJ</title>

        <!-- Load File bootstrap.min.css yang ada difolder css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <!-- Load File bootstrap.min.css yang ada difolder css -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

        
        <style>
   
        </style>

        
    </head>
    <body>
        <!-- Membuat Menu Header / Navbar -->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><p class="glyphicon glyphicon-chevron-left" value="<" onclick="history.back(-1)"/></p></div>
  <div class="col-8" ><p>READY STOCK</p></div>
  <div class="col-2"><a href="view_cart.php"><span class="glyphicon glyphicon-shopping-cart"></span></a></div>
</div>
<div class="container row fixed-top navbaru2" >

  <div class="col-2"></div>
  <div class="col-8" ></div>
  <div class="col-2"><a href="view_cart.php"><span class="badge" style="border-radius: .25rem;padding: 3px 3px 3px 3px; background-color: #dc3545; color: white;"><?php echo $keranjang; ?></span></a></div>
</div><br><br><br><br>





<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #eee  center center;
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

.navbaru p {
  
  padding: 10px 0;
  font-size: 20px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru span {
  
  padding: 5px 0;
  font-size: 30px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru2 {
   
    
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

</style>

<!--================ NAVBARU END =================-->
      <div class="container">
        
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
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home" >Ready Stok Reguler</a></li>
    <li><a data-toggle="tab" href="#get" >Ready Stok Buy 1 Get 1</a></li>
  </ul> 
</div>

<div class="tab-content">
      <div id="home" class="tab-pane fade in active">  
<br>
            <form method="POST" action="save_cart.php">
            <table class="table table-bordered table-striped">
                <thead>
                    <th><input type="checkbox" id="pilihsemua" onchange="checkAll(this)"/></th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </thead>
                <tbody>
                    <?php
                        include "koneksi.php";
                        //initialize total
                        $idmitra=$_SESSION["admin_mitra"]["idadmin"];
                        $total = 0;
                        $berat=0;
                        $qty=0;
            
                        $sql = "SELECT *, keranjang.status as statusnya 
                                FROM keranjang 
                                inner join produk on keranjang.idproduk=produk.idproduk 
                                WHERE keranjang.idmitra='$idmitra' 
                                and keranjang.jmlh>0 
                                and (produk.idkategori<>11 and produk.idkategori<>12 and produk.idkategori<>13)
                                ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()){
                    ?>
                                <tr>
                                    <input type="hidden" name="idprodukubah[]" value="<?php echo $row['idproduk']; ?>">
                                    <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
                                    <input type="hidden" name="idkeranjangubah[]" value="<?php echo $row['idkeranjang']; ?>">
                                    <input type="hidden" name="idmitra" value="<?php echo $idmitra; ?>">
                                    <input type="hidden" name="stock[]" value="<?php echo $row['stock']; ?>">
                                    <input type="hidden" name="jmlh[]" value="<?php echo $row['jmlh']; ?>">
                                    <input type="hidden" name="subtotal[]" value="<?php echo $row['subtotal']; ?>">
                                    <input type="hidden" name="jenis" value="D">
                                <td style="text-align: right;">
                                    <?php 
                                    $disable = "block";
                                    if ($row['statusnya']=="Expired") {
                                        $disable = "none";
                                }
                                     ?>
                                     <?php if ($row['statusnya']=="Expired"): ?>
                                        <div class="badge bg-warning text-white rounded-pill"><a href="hapus_expired.php?idkeranjang=<?php echo $row['idkeranjang']; ?>" class="link text-white" onclick="return confirm('Yakin Akan Menghapus Pesanan Keranjang?');">Hapus</a></div> | 
                                        <div class="badge bg-danger text-white rounded-pill"><?php echo $row['statusnya']; ?></div>
                                     <?php endif ?>
                                        <?php if ($row['statusnya']=="Active"): ?>
                                        <input type="checkbox" name="idkeranjang[]" value="<?php echo $row['idkeranjang']; ?>"/>
                    <?php endif ?>
                                </td>
                                <td><?php echo $row['namaproduk']; ?></td>
                                <td><?php $coret=number_format($row['hargacoret'],2); if($row['hargacoret']<>0){
                                echo "<span style='text-decoration: line-through'>Rp. $coret </span>"; } ?><br>Rp. <?php echo number_format($row['harga'], 2); ?></td>
                                <?php
                                $max=$row['stock'];
                                $max1=$max+1;
                                ?>
                                <td>
                                    <?php if ($row['statusnya']=="Expired"): ?>
                                        <?php echo $row['jmlh']; ?>
                                        <br>
                                     <?php endif ?>
                                    
                  <input type="number" min="0" class="form-control" style="display: <?= $disable; ?>" value="<?php echo $row['jmlh']; ?>" name="jmlhbaru[]">Ready Stock : <?php echo $row['stock']; ?>

                                    
                                </td>
                                <?php $subtotal=number_format($row['subtotal'], 2); ?>
                                <td><?php echo $subtotal  ?></td>
                                <?php $total +=$row['subtotal']; 
                                       $berat += $row['berat'] ?>
                            </tr>
                    
                            <?php
                            $qty+=$row['jmlh'];
                        }
                      
                    ?>
                        <tr>    
                        <td colspan="4" align="right"><b>Jumlah Qty</b></td>
                        <td><b><?php echo $qty; ?></b></td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right"><b>Total</b></td>
                        <td><b><?php echo number_format($total,2); ?></b></td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" name="berat" value="<?php echo $berat; ?>">
            <div class="container">
            <div class="d-flex justify-content-between  mb-3">
    <div class="p-2 "><a href="store2.php" class="btn btn-warning btn-s"><span class="glyphicon glyphicon-chevron-left"></span></a></div>
    <div class="p-2 "></div>
    <div class="p-2 "><button type="submit" class="btn btn-success btn-s" name="save">Ubah Stock</button></div>
  </div>
            
        
            </div>
            <br>
            <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Klik Ubah Stock sebelum checkout
            </div>
            <br>
            
                <div class="d-flex justify-content-center">
                 
                
                    <?php if($total==0){
                      echo "<a href='store2.php' class='btn btn-primary btn-lg'>Lanjut Belanja Yuk!</a>";
                    }else{
                    echo"<button type='submit' class='btn btn-primary btn-lg' name='checkout'> CHECKOUT <span class='glyphicon glyphicon-chevron-right'> </button>";
                    }
                    ?>       
                </div>
            
            </form>
  </div>            
      <div id="get" class="tab-pane fade">  
        <br>
        <?php include "view_cart_get.php"; ?>
      </div>
      

</div> 


        </div>
    </div>
</div>

<script type="text/javascript">
  function checkAll(box) 
  {
   let checkboxes = document.getElementsByTagName('input');

   if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
    for (let i = 0; i < checkboxes.length; i++) {
     if (checkboxes[i].type == 'checkbox') {
      checkboxes[i].checked = true;
     }
    }
   } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
    for (let i = 0; i < checkboxes.length; i++) {
     if (checkboxes[i].type == 'checkbox') {
      checkboxes[i].checked = false;
     }
    }
   }
  }
 </script>

</body>
</html>