  <?php 
include "header_index.php";
 ?>     
  <p align="left"><input type="radio" onclick="javascript:window.location.href='#'; " checked="checked"> Mode Hemat &nbsp&nbsp <input type="radio" onclick="javascript:window.location.href='index.php'; "> Mode Cantik</p>

                <table class="w3-table-all" id="tb_store" border="0" >
                  <thead>
                    <tr>
            <td><span class="glyphicon glyphicon-shopping-cart"></span></td>
            <td>stock</td>
            <td>produk</td>
            </tr>
            </thead>
            
          <tbody>
          <?php
          include "koneksi.php";
          
          $sql = mysqli_query($koneksi, "SELECT * from produk 
                        WHERE stock>0 
                        and harga>0 
                        and idkategori>0 
                        -- and status<>1 
                        and (produk.idkategori>50)
                        order by tgl desc, idproduk desc, namaproduk asc ");
          
          $no = 1;
          while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
          ?>
          
            <tr>
            <td>
             <a class="btn btn-info btn-xs" href="add_chart.php?id=<?php echo $data['idproduk']; ?>&harga=<?php echo $data['harga']; ?>"> <span class="glyphicon glyphicon-plus"></span></a>
            </td>
            <td>
              <?php echo $data['stock']; ?> 
            </td>
            <td>
              <?php echo $data['namaproduk']; ?>
                <?php if ($data['status']==2): ?>
                  (Produk sedang di update, akan aktif setelah proses update selesai)
                <?php endif ?>
              </td>
            </tr>
        <?php } ?>
          
          
        </tbody>
        </table>
      </div>
    
  
          <br>
      <br>
      <br>
      <br>
      <br>
    </div>


<?php include "footer_index.php"; ?>
<?php include "settingdatatables.php"; ?>  