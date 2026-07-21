<br>
<label>Surat Jalan Distributor</label>

<div class="table-responsive">
 <form method="post">    
    <table class="table table-bordered" id="tb_sj_pr">
        <thead>
        <tr>
            <th><input type='checkbox' id='checkAll_db' > Check</th>
            <th>No</th>
            <th>Nama DB</th>
          <th>No Surat Jalan</th>
                <th>Invoice</th>
                <th>QTY</th>
                <th>Status</th>
                <th>Waktu</th>
                </tr>
        </thead>
        <tbody>
              <?php 
             
                $data_sj=$koneksi->query("SELECT surat_jalan.invoice,
                                            produk.namaproduk,
                                            ordermitra.jumlah,
                                            surat_jalan.no_sj,
                                            surat_jalan.progres,
                                            surat_jalan.status,
                                            surat_jalan.waktu,
                                            surat_jalan.id_sj,
                                            SUM(surat_jalan.progres) as jumlahnya,
                                            ordermitra.idmitra,
                                            admin_mitra.namamitra
                                            FROM surat_jalan
                                            JOIN produk on produk.idproduk = surat_jalan.idproduk
                                            JOIN ordermitra on ordermitra.idorder = surat_jalan.idorder
                                            left join admin_mitra on admin_mitra.idadmin = ordermitra.idmitra
                                            WHERE surat_jalan.progres>0 and surat_jalan.status = 'Checker'
                                            GROUP BY surat_jalan.no_sj ORDER BY surat_jalan.waktu desc
                                            LIMIT 1000
                                            ");
                $no=1;
              
                while($tampilkan_sj=$data_sj->fetch_assoc()){
                   $no_sj_db=$tampilkan_sj['no_sj'];
                ?>
                <tr>
                  <td>
                  <input type='checkbox' name='update_db[]' value='<?= $no_sj_db ?>' ></td>
                    <td>
                     <?php echo $no++; ?>
                </td>   
                <td><?php echo $tampilkan_sj['namamitra']; ?> (<?php echo $tampilkan_sj['idmitra']; ?>)</td>  
                  <td>
                    <a href="suratjalan_print_checker.php?no_sj=<?= $tampilkan_sj['no_sj'] ?>&idadmin=<?= $tampilkan_sj['idmitra'] ?>" target="_blank"><i class="fa fa-print"></i> <?php echo $tampilkan_sj['no_sj']; ?></a>
                  </td>
                  <td>
            <?php 
            $data_invoice_sj=$koneksi->query("SELECT surat_jalan.invoice
                                            
                                            FROM surat_jalan
                                            
                                            WHERE surat_jalan.no_sj = '$no_sj_db'
                                            GROUP BY surat_jalan.invoice

                                    ");
                            while($tampilkan_invoice_sj=$data_invoice_sj->fetch_assoc()){
                              echo $tampilkan_invoice_sj['invoice'];
                              echo "<br>";
                            }
            ?>                            
                           
                  </td>
                  <td>
                   <?php echo $tampilkan_sj['jumlahnya']; ?>
                  </td>
                  <td>
                 <div class="badge bg-warning text-white rounded-pill">
                  <?php echo $tampilkan_sj['status']; ?>
                  </div>
                    </td>                  
                  <td>
                   <?php echo $tampilkan_sj['waktu']; ?>
                  </td>
                        </tr>
            <?php } ?>
          </tbody>
        </table>
        <input type='submit' class="btn btn-success" value='Proses' name='but_update_db'>
</form>        
</div>


   <?php 
        if(isset($_POST['but_update_db'])){
date_default_timezone_set('Asia/Jakarta');          
$tglprint = date("Y-m-d");
$waktuprint = date("H:i:s");

            if(isset($_POST['update_db'])){
                foreach($_POST['update_db'] as $updateid){

                    $sqlnya = $koneksi->query("UPDATE surat_jalan set status='Proses' WHERE no_sj='$updateid'");
                    // waktu = '$tglprint $waktuprint'
                    
                }
                if ($sqlnya) {
                  echo "<script>alert('data berhasil diubah');</script>";
                  echo "<script>location='suratjalan.php';</script>";  
                  }else{
                    echo "<script>alert('data gagal diubah');</script>";
                    echo "<script>location='suratjalan.php';</script>";
                }
               
            }
            
        }
        ?>

