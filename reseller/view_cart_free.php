<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

						//initialize total
						$idmitra=$_SESSION["mitraagen"]["idmitrareseller"];
						$idadmin=$_SESSION["mitraagen"]["idadmin"];

						?>
            <form method="POST" action="save_free.php">
            
            <table class="table table-bordered table-striped">
                <thead>
                    <th><input type="checkbox" id="pilihsemua" onchange="checkAll_get(this)"/></th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </thead>
                <tbody>
                    <?php
                        include "koneksi.php";
                        //initialize total
                        $total = 0;
                        $berat=0;
                        $qty=0;
            
                        $sql = "SELECT *, keranjang.status as statusnya 
                                FROM keranjang 
                                inner join produk on keranjang.idproduk=produk.idproduk 
                                WHERE keranjang.idreseller='$idmitra' 
                                and keranjang.jmlh>0 
                                and (produk.idkategori>=52)
                                ";
                        $query = $koneksi->query($sql);
                        while($row = $query->fetch_assoc()){
                    ?>
                                <tr>
                                    <input type="hidden" name="idprodukubah[]" value="<?php echo $row['idproduk']; ?>">
                                    <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
                                    <input type="hidden" name="idkeranjangubah[]" value="<?php echo $row['idkeranjang']; ?>">
                                    <input type="hidden" name="idmitra" value="<?php echo $idmitra; ?>">
									<input type="hidden" name="idadmin" value="<?php echo $idadmin; ?>">
                                    <input type="hidden" name="stock[]" value="<?php echo $row['stock']; ?>">
                                    <input type="hidden" name="jmlh[]" value="<?php echo $row['jmlh']; ?>">
                                    <input type="hidden" name="subtotal[]" value="<?php echo $row['subtotal']; ?>">
                                    <input type="hidden" name="jenis" value="F">

                                    
                                <td style="text-align: right;">
                                    <!-- <input type="text" name="idkategori[]" value="<?php echo $row['idkategori']; ?>"> -->
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
                                <td>
  <?php if ($row['idkategori']>=53): ?>
    -
    <?php else: ?>
                                    <?php $coret=number_format($row['hargacoret'],2); if($row['hargacoret']<>0){
                                echo "<span style='text-decoration: line-through'>Rp. $coret </span>"; } ?><br>Rp. <?php echo number_format($row['harga'], 2); ?></td>
                                <?php
                                $max=$row['stock'];
                                $max1=$max+1;
                                ?>
<?php endif; ?>                                
                                <td>
                                    <?php if ($row['statusnya']=="Expired"): ?>
                                        <?php echo $row['jmlh']; ?>
                                        <br>
                                     <?php endif ?>
                                    
                  <input type="number" min="0" class="form-control" style="display: <?= $disable; ?>" value="<?php echo $row['jmlh']; ?>" name="jmlhbaru[]">Ready Stock : <?php echo $row['stock']; ?>

                                    
                                </td>

                                <?php $subtotal=number_format($row['subtotal'], 2); ?>
                                <td>
  <?php if ($row['idkategori']>=53): ?>
    -
    <?php else: ?>
                                    <?php echo $subtotal  ?>
<?php endif; ?>                                        
                                    </td>
                                <?php $total +=$row['subtotal']; 
                                       $berat += $row['berat'] ?>
                            </tr>
                    
                            <?php
                            $qty+=$row['jmlh'];
                            
                            $idkategori=$row['idkategori'];
                        }
                      
                    ?>
                        <tr>    
                        <td colspan="4" align="right"><b>Jumlah Qty</b></td>
                        <td><b><?php echo $qty; ?></b></td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right"><b>Total</b></td>
                        <td>
  <?php if ($idkategori>=53): ?>
    -
    <?php else: ?>                        
                            <b><?php echo number_format($total,2); ?></b>
<?php endif; ?>                     
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" name="berat" value="<?php echo $berat; ?>">
            <div class="container">
            <div class="d-flex justify-content-between  mb-3">
    <div class="p-2 "><a href="index.php" class="btn btn-warning btn-s"><span class="glyphicon glyphicon-chevron-left"></span></a></div>
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
                      echo "<a href='index.php' class='btn btn-primary btn-lg'>Lanjut Belanja Yuk!</a>";
                    }else{
                    echo"<button type='submit' class='btn btn-primary btn-lg' name='checkout'> CHECKOUT <span class='glyphicon glyphicon-chevron-right'> </button>";
                    }
                    ?>       
                </div>
            
            </form>
 

<script type="text/javascript">
  function checkAll_get(box) 
  {
   let checkboxes_get = document.getElementsByTagName('input');

   if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
    for (let i = 0; i < checkboxes_get.length; i++) {
     if (checkboxes_get[i].type == 'checkbox') {
      checkboxes_get[i].checked = true;
     }
    }
   } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
    for (let i = 0; i < checkboxes_get.length; i++) {
     if (checkboxes_get[i].type == 'checkbox') {
      checkboxes_get[i].checked = false;
     }
    }
   }
  }
 </script>

</body>
</html>