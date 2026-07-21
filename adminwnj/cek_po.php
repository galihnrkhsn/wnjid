<?php 
  include "koneksi.php";
  
  $jenis_filter = $_GET['jenis_filter'];

echo "Filter Nama $jenis_filter";

 ?>

  	<?php if ($jenis_filter=='PO'): ?>

<div class="table-responsive">
  <table class="table table-striped" id="tb_listpo_artikel">
                      <thead>
                        <tr>
                            <th style="width:1%">
                             No
                            </th>
                            <th>
                            ID PO  
                          </th>    
                          <th>
                            Nama PO  
                          </th> 
                           <th>
                            Jumlah PO (Pcs/Pack)  
                          </th>
                          <th>
                            Multiple Print  
                          </th>         
                        </tr>
                      </thead>
                      <tbody>
                          <?php 

        
                            $datapo=$koneksi->query("SELECT poproduk.idpoproduk,
                                                            poproduk.namapo, 
                                                            sum(pomitra.jumlah) as jumlahnya,
                                                            poproduk.jenis
                              FROM poproduk INNER JOIN pomitra on poproduk.idpoproduk = pomitra.idpoproduk 
                              -- WHERE pomitra.jumlah>0
                              GROUP BY pomitra.idpoproduk ORDER BY pomitra.idpoproduk DESC");
                            $no=1;
                          
                            while($tampilkan=$datapo->fetch_assoc()){
                                if ($tampilkan['jenis']=='Kolibri') {
                                  # code...
                                }
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan['idpoproduk']; ?>
                        </td>     
                          <td>
                              <a href="listpokolibri.php?id=<?php echo $tampilkan['idpoproduk']; ?>"> <?php echo $tampilkan['namapo']; ?> </a>
                          </td>
                          <td>
                              <a href="listpokolibri.php?id=<?php echo $tampilkan['idpoproduk']; ?>"> <?php echo $tampilkan['jumlahnya']; ?> </a>   
                              <?php if ($tampilkan['idpoproduk']==186 or $tampilkan['idpoproduk']==187): ?>
                                (<?= $tampilkan['jumlahnya']/12; ?> Seri)
                              <?php endif ?>
                          </td>
                           <td>
                           <a href="multipleinvoice.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="blank()"> <i class="fas fa-print"></i></a>
                          </td>

              
                        </tr>
                        <?php } ?>
                      </tbody>
  </table>
</div>


  	<?php endif ?>

  	<?php if ($jenis_filter=='DB'): ?>

<div class="table-responsive">
<table class="table table-striped" id="tb_listpoartikel2">
                      <thead>
                        <tr>
                            <th style="width:1%">
                             No
                            </th>
                            <th>
                            Nama DB  
                          </th> 
                           <th>
                            Nama SUB-DB  
                          </th>    
                          <th>
                            Nama PO  
                          </th> 
                        <th>Kemitraan</th>
                        <th>Status PO</th>
                           <th>
                            Invoice  
                          </th>         
                        </tr>
                      </thead>
                      <tbody>
                          <?php 

        
$datapo=$koneksi->query("SELECT 
  admin_mitra.idadmin,
  admin_mitra.namamitra,
  poproduk.namapo,
  mitraagen.namaagen as agen,
  mitrareseller.namaagen as reseller,
  mitramarketer.namaagen as marketer,
  pomitra.invoice,
  pomitra.status,
  pomitra.tgl,
  poproduk.namapo,
  poproduk.idpoproduk 
  FROM pomitra 
    LEFT JOIN mitraagen on pomitra.idmitraagen=mitraagen.idmitraagen 
    LEFT JOIN mitrareseller on pomitra.idmitrareseller=mitrareseller.idmitrareseller 
    LEFT JOIN mitramarketer on pomitra.idmitramarketer=mitramarketer.idmitramarketer 
    LEFT JOIN admin_mitra on pomitra.idmitra=admin_mitra.idadmin or mitraagen.idadmin=admin_mitra.idadmin 
    or mitrareseller.idadmin=admin_mitra.idadmin or mitramarketer.idadmin=admin_mitra.idadmin 
    INNER JOIN poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
  WHERE pomitra.jumlah>0
  GROUP BY pomitra.invoice ORDER BY pomitra.idpomitra DESC LIMIT 3000");
                            $no=1;
                          
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>
                        <td>
                          <a href="sumpodb.php?idpoproduk=<?php echo $tampilkan['idpoproduk']; ?>&idadmin=<?php echo $tampilkan['idadmin']; ?>">
                <i class="fas fa-user"></i> <?php echo $tampilkan['namamitra']; ?>
               </a> 
                        </td>     
                          <td class="align-middle">
                  <i class="fas fa-users"></i> <?php echo $tampilkan['agen']; ?> <?php echo $tampilkan['reseller']; ?> <?php echo $tampilkan['marketer']; ?>
                      
                    </td>
                          <td>
                            
                           <a href="listpoinvoice.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a> 
                          </td>

              <td class="align-middle"><?php if($tampilkan['agen']<>''){ echo " 
              <div class='badge bg-info text-white rounded-pill'>Agen</div>";}
                  if($tampilkan['reseller']<>''){ echo "
                  <div class='badge bg-warning text-white rounded-pill'>Reseller</div>";}
                  if($tampilkan['marketer']<>''){ echo "
                  <div class='badge bg-danger text-white rounded-pill'>Marketer</div>";}
                  if($tampilkan['agen']=='' and $tampilkan['reseller']=='' and $tampilkan['marketer']=='' ){ 
                    echo "
                    <div class='badge bg-success text-white rounded-pill'>Distributor</div>";} ?></td>
                <!--<td>
                <?php echo $tampilkan['namapenerima']; ?>
              </td>
              <td>
                <?php echo $tampilkan['alamatpenerima']; ?>
              </td>>-->
              <td>
                <?php if ($tampilkan['status']=='Belum DP'): ?>
                 <div class="badge bg-danger text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>
                
                <?php if ($tampilkan['status']=='Belum Acc DB'): ?>
                 <div class="badge bg-warning text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?> 

                <?php if ($tampilkan['status']=='Sudah Confirm DP'): ?>
                 <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>  
              </td>
                          <td>
                            <a href="detailinvoice.php?invoice=<?php echo $tampilkan['invoice']; ?>&idpoproduk=<?php echo $tampilkan['idpoproduk']; ?>">
                  <?php echo $tampilkan['invoice']; ?>   
                </a>
                          </td>

              
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    
        </div>

  	<?php endif ?>  		
<?php include "settingdatatables.php"; ?>  	