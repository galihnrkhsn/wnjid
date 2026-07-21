<br>
<label>Per Surat Jalan Checker</label>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home1" class="nav-item nav-link active">Distributor</a></li>
  <li><a data-toggle="tab" href="#menu11" class="nav-item nav-link">Sub-Distributor</a></li>   
</ul>

    <div class="tab-content">
      <div id="home1" class="tab-pane fade show active" role="tabpanel"> 
<?php include"sj_db.php"; ?>
      </div>
      <div id="menu11" class="tab-pane fade">
<div class="table-responsive">
<br>
<label>Surat Jalan Agen</label>
 <form method="post"> 
<table class="table table-striped" id="tb_sj_agen">
                     <thead>
          <tr>       
            <th><input type='checkbox' id='checkAll_agen' > Check</th>
            <th>No</th>
            <th>Nama Sub-DB</th>
            <th>Nama DB</th>
            <th>No Surat Jalan</th>
            <th>Invoce</th>
            <th>Status</th>
            <th>QTY</th>
            <th>Waktu</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            $agen_datapo=$koneksi->query("SELECT surat_jalan_subdb.invoice,
                                            surat_jalan_subdb.no_sj,
                                            surat_jalan_subdb.progres,
                                            SUM(surat_jalan_subdb.progres) as jumlahnya,
                                            surat_jalan_subdb.status,
                                            surat_jalan_subdb.waktu,
                                            surat_jalan_subdb.id_sj,
                                            LEFT(surat_jalan_subdb.invoice,1) as hurufdepan
                                            FROM surat_jalan_subdb
                                            WHERE surat_jalan_subdb.progres>0 and surat_jalan_subdb.status = 'Checker'
                                            GROUP BY surat_jalan_subdb.no_sj ORDER BY surat_jalan_subdb.id_sj desc

                                    ");
                            $no=1;
                            while($agen_tampilkan=$agen_datapo->fetch_assoc()){
                                $no_sj_sub = $agen_tampilkan['no_sj'];
                                $invoice = $agen_tampilkan['invoice'];
                                $hurufdepan = $agen_tampilkan['hurufdepan'];
if ($hurufdepan=="A") {
$tampil_sub =$koneksi->query("SELECT admin_mitra.idadmin,
                                admin_mitra.namamitra,
                                mitraagen.idmitraagen as idsub,
                                mitraagen.namaagen as namasub
                              FROM orderagen 
                              LEFT JOIN mitraagen on mitraagen.idmitraagen = orderagen.idmitraagen
                              LEFT JOIN admin_mitra on admin_mitra.idadmin = mitraagen.idadmin
                              where orderagen.invoice='$invoice' ");
         $tampilkan_sub=$tampil_sub->fetch_assoc();   
$jenis="Agen";
}
if ($hurufdepan=="R") {
$tampil_sub =$koneksi->query("SELECT admin_mitra.idadmin,
                                admin_mitra.namamitra,
                                mitrareseller.idmitrareseller as idsub,
                                mitrareseller.namaagen as namasub
                              FROM orderreseller 
                              LEFT JOIN mitrareseller on mitrareseller.idmitrareseller = orderreseller.idmitrareseller
                              LEFT JOIN admin_mitra on admin_mitra.idadmin = mitrareseller.idadmin
                              where orderreseller.invoice='$invoice' ");
         $tampilkan_sub=$tampil_sub->fetch_assoc();   
$jenis="Reseller";         
}
if ($hurufdepan=="M") {
$tampil_sub =$koneksi->query("SELECT admin_mitra.idadmin,
                                admin_mitra.namamitra,
                                mitramarketer.idmitramarketer as idsub,
                                mitramarketer.namaagen as namasub
                              FROM ordermarketer 
                              LEFT JOIN mitramarketer on mitramarketer.idmitramarketer = ordermarketer.idmitramarketer
                              LEFT JOIN admin_mitra on admin_mitra.idadmin = mitramarketer.idadmin
                              where ordermarketer.invoice='$invoice' ");
         $tampilkan_sub=$tampil_sub->fetch_assoc(); 
$jenis="Marketer";           
}
                               
            ?>
             <tr>
                  <td>
                  <input type='checkbox' name='update_sub[]' value='<?= $no_sj_sub ?>' ></td>
                         <td>
                             <?= $no++; ?>
                        </td>
                          <td>
                            <?= $tampilkan_sub['namasub']; ?> (<?= $tampilkan_sub['idsub']; ?> )
                           
                          </td>
                           <td>
                            <?= $tampilkan_sub['namamitra']; ?> (<?= $tampilkan_sub['idadmin']; ?> )
                          </td>                          
                        <td>
                            <a href="suratjalan_print_subdb.php?no_sj=<?= $no_sj_sub; ?>&idadmin=<?= $tampilkan_sub['idsub'] ?>&jenis=<?= $jenis; ?>" target="_blank"><i class="fa fa-print"></i> <?php echo $no_sj_sub; ?></a>
                            
                          </td>   
                          <td>
            <?php 
            $data_invoice_sj=$koneksi->query("SELECT surat_jalan_subdb.invoice
                                            
                                            FROM surat_jalan_subdb
                                            
                                            WHERE surat_jalan_subdb.no_sj = '$no_sj_sub'
                                            GROUP BY surat_jalan_subdb.invoice

                                    ");
                            while($agen_tampilkan_invoice_sj=$data_invoice_sj->fetch_assoc()){
                              echo $agen_tampilkan_invoice_sj['invoice'];
                              echo "<br>";
                            }
            ?>  
                          </td>
                          <td>
              <div class="badge bg-warning text-white rounded-pill">
                  <?php echo $agen_tampilkan['status']; ?>
                  </div>
                    </td>                            
                          <td>
                            <?php echo $agen_tampilkan['jumlahnya']; ?>
                          </td>
                         

                           <td>
                            <?php echo $agen_tampilkan['waktu']; ?> 
                          </td>
                        </tr>
            <?php } ?>
          </tbody>
        </table>  
        <input type='submit' class="btn btn-success" value='Proses' name='but_update_sub'>
</form>       
   <?php 
        if(isset($_POST['but_update_sub'])){
date_default_timezone_set('Asia/Jakarta');          
$tglprint = date("Y-m-d");
$waktuprint = date("H:i:s");

            if(isset($_POST['update_sub'])){
                foreach($_POST['update_sub'] as $updateid){

                     $sqlnya = $koneksi->query("UPDATE surat_jalan_subdb set status='Proses' WHERE no_sj='$updateid'");
                    // echo "<script>alert('$updateid');</script>";
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

</div>

      </div> 
            

    </div>
