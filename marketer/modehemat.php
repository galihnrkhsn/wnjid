<?php 
session_start();

include 'header_index.php'; 

?>       
    <table style="width:100%">
      <tr>
        <th>
          <p align="left"><input type="radio" onclick="javascript:window.location.href='modehemat.php'; " checked="checked"> Mode Hemat  &nbsp&nbsp<input type="radio" onclick="javascript:window.location.href='index.php'; " > Mode Cantik
        </p>
        </th>
      </tr>
      <tr>
        <td><a class="btn btn-success" href="excelproduk.php" target="blank()">Export</a></td>
      </tr>
    </table>

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
                        and status<>1 
                        and (produk.idkategori<>51)
                        order by tgl desc, idproduk desc, namaproduk asc ");
          
          $no = 1;
          while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
          ?>
          
            <tr>
            <td>
              <?php if ($data['status']==0): ?>
             <a class="btn btn-info btn-xs" href="add_chart.php?id=<?php echo $data['idproduk']; ?>&harga=<?php echo $data['harga']; ?>"> <span class="glyphicon glyphicon-plus"></span></a>
             <?php else: ?>
              <button class="btn btn-info btn-xs"> <span class="glyphicon glyphicon-exclamation-sign"></span></button>
              
             <?php endif ?>
            </td>
            <td>
              <?php if ($data['status']==0): ?>
              <?php echo $data['stock']; ?> 
              <?php else: ?>
                -
              <?php endif ?>
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
    
  

    </div>
  
  
</div>

<?php include "settingdatatables.php"; ?>
<?php include "footer_index.php"; ?>