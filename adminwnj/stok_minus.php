<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#db_stok" class="nav-item nav-link active">Distributor</a></li>
    <li><a data-toggle="tab" href="#agen_stok" class="nav-item nav-link">Agen</a></li>
    <li><a data-toggle="tab" href="#reseller_stok" class="nav-item nav-link">Reseller</a></li>
    <li><a data-toggle="tab" href="#marketer_stok" class="nav-item nav-link">Marketer</a></li>
  </ul>
<div class="tab-content">


  <div id="db_stok" class="tab-pane fade show active" role="tabpanel"> 
    <p>Distributor</p>
<form method="post" action="ambilbarang_print.php" target="_blank">       
<div class="table-responsive">
    
    <table class="table table-bordered" id="tb_stokminus_db">
        <thead>
        <tr>
         <!--  <th><input type='checkbox' id='checkAll_db' > Check</th> -->
            <th>Nama CS</th>
          <th>Invoice</th>
          <th>Nama Mitra</th>
                <th>Krg</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    
                </tr>
        </thead>
        <tbody>
              <?php 
             
                $datapo=$koneksi->query("SELECT 
                  admin_mitra.namamitra,
                  ordermitra.tgl,
                  ordermitra.invoice,
                  ordermitra.payment,
                  ordermitra.status,
                  admin_mitra.idadmin,
                  admin_mitra_cs.namacs,
                  SUM(ordermitra.jumlah) as qty 
                  FROM `ordermitra` 
                  inner join admin_mitra on ordermitra.idmitra=admin_mitra.idadmin
                  JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin
                    WHERE ordermitra.jumlah>0 
                    And ordermitra.payment = 'Lunas' 
                    and ordermitra.tgl > '2022-05-01' 
                    and admin_mitra_cs.namacs LIKE '%$namacs%'
                    and ordermitra.status_progres = 2
                    GROUP BY ordermitra.invoice ORDER BY ordermitra.idorder DESC limit 2000 ");
                $no=1;
              
                while($tampilkanagen=$datapo->fetch_assoc()){
                   $id = $tampilkanagen['invoice'];
                  $invoiceagen = $tampilkanagen['invoice'];
$datamitra=$koneksi->query("SELECT 
  SUM(surat_jalan.progres) as progresnya, 
  surat_jalan.invoice 
  FROM surat_jalan
  WHERE surat_jalan.invoice='$invoiceagen'");
  $tampilprogres=$datamitra->fetch_assoc();                                   
  $kurangagen = $tampilkanagen['qty']-$tampilprogres['progresnya'];                  
                ?>
<?php if ($kurangagen<>0): ?>
                  
                               
                <tr>
                 <!--  <td>
                    <input type='checkbox' name='update_db[]' value='<?= $id ?>' >
                    <input type='hidden' name='invoice<?= $id ?>' value='<?php echo $tampilkanagen['invoice']; ?>' >
                  </td>  -->                 
                 <td>
                     <strong><?php echo $tampilkanagen['namacs']; ?></strong>
                </td> 
                  <td>
                    <?php echo $tampilkanagen['invoice']; ?>
                       
                      </td>
                   <td>
                   <i class="fas fa-user"></i> <?php echo $tampilkanagen['namamitra']; ?>
                  </td>
                  
                  <td style="text-align:center"><?php echo $kurangagen; ?></td>
                  <td>
                        <i class="fas fa-calendar" style="color: red"></i> <?php echo $tampilkanagen['tgl']; ?>
                      </td>
                      <td>
                        <div class="badge bg-warning text-white rounded-pill">
                  Stok Minus
                  </div>
                      </td>
              
                        </tr>
<?php endif ?>                         
                        <?php } ?>
                      </tbody>
                    </table>
                    
                    </div>  
     
</form>                 
  </div>

  <div id="agen_stok" class="tab-pane" role="tabpanel"> 
    <p>Agen</p>
<form method="post" action="ambilbarang_print.php" target="_blank">       
<div class="table-responsive">
    
    <table class="table table-bordered" id="tb_stokminus_agen">
        <thead>
        <tr>
          <!-- <th><input type='checkbox' id='checkAll_agen_stok' > Check</th> -->
            <th>Nama CS</th>
          <th>Invoice</th>
          <th>Nama Mitra</th>
                <th>Krg</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    
                </tr>
        </thead>
        <tbody>
              <?php 
             
                $datapo=$koneksi->query("SELECT 
                  mitraagen.namaagen,
                  orderagen.tgl,
                  orderagen.invoice,
                  orderagen.payment,
                  orderagen.status,
                  mitraagen.idadmin,
                  admin_mitra_cs.namacs,
                  SUM(orderagen.jumlah) as qty 
                  FROM `orderagen` 
                  inner join mitraagen on orderagen.idmitraagen=mitraagen.idmitraagen
                  JOIN admin_mitra_cs on mitraagen.idadmin = admin_mitra_cs.idadmin
                    WHERE orderagen.jumlah>0 
                    And orderagen.payment = 'Lunas' 
                    and orderagen.tgl > '2022-05-01' 
                    and admin_mitra_cs.namacs LIKE '%$namacs%'
                    and orderagen.status_progres = 2
                    GROUP BY orderagen.invoice ORDER BY orderagen.idorder DESC limit 2000 ");
                $no=1;
              
                while($tampilkanagen=$datapo->fetch_assoc()){
                   $id = $tampilkanagen['invoice'];
                  $invoiceagen = $tampilkanagen['invoice'];
$datamitra=$koneksi->query("SELECT 
  SUM(surat_jalan_subdb.progres) as progresnya, 
  surat_jalan_subdb.invoice 
  FROM surat_jalan_subdb
  WHERE surat_jalan_subdb.invoice='$invoiceagen'");
  $tampilprogres=$datamitra->fetch_assoc();                                   
  $kurangagen = $tampilkanagen['qty']-$tampilprogres['progresnya'];                  
                ?>
<?php if ($kurangagen<>0): ?>
                  
                               
                <tr>
                  <!-- <td>
                    <input type='checkbox' name='update_agen_stok[]' value='<?= $id ?>' >
                    <input type='hidden' name='invoice<?= $id ?>' value='<?php echo $tampilkanagen['invoice']; ?>' >
                  </td>  -->                 
                 <td>
                     <strong><?php echo $tampilkanagen['namacs']; ?></strong>
                </td> 
                  <td>
                    <?php echo $tampilkanagen['invoice']; ?>
                       
                      </td>
                   <td>
                   <i class="fas fa-user"></i> <?php echo $tampilkanagen['namaagen']; ?>
                  </td>
                  
                  <td style="text-align:center"><?php echo $kurangagen; ?></td>
                  <td>
                        <i class="fas fa-calendar" style="color: red"></i> <?php echo $tampilkanagen['tgl']; ?>
                      </td>
              <td>
                        <div class="badge bg-warning text-white rounded-pill">
                  Stok Minus
                  </div>
                      </td>
              
                        </tr>
<?php endif ?>                         
                        <?php } ?>
                      </tbody>
                    </table>
                    
                    </div>  
     
</form>                 
  </div>

  <div id="reseller_stok" class="tab-pane" role="tabpanel">
  <p>Reseller</p>
  <form method="post" action="ambilbarang_print.php" target="_blank"> 
  <div class="table-responsive">
    
    <table class="table table-bordered" id="tb_stokminus_reseller">
        <thead>
        <tr>
          <!-- <th><input type='checkbox' id='checkAll_reseller_stok' > Check</th> -->
            <th>Nama CS</th>
          <th>Invoice</th>
          <th>Nama Mitra</th>
                <th>Krg</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    
                </tr>
        </thead>
        <tbody>
              <?php 
             
                $datapo=$koneksi->query("SELECT 
                  mitrareseller.namaagen,
                  orderreseller.tgl,
                  orderreseller.invoice,
                  orderreseller.payment,
                  orderreseller.status,
                  admin_mitra_cs.namacs,
                  SUM(orderreseller.jumlah) as qty 
                  FROM `orderreseller` 
                  inner join mitrareseller on orderreseller.idmitrareseller=mitrareseller.idmitrareseller
                  JOIN admin_mitra_cs on mitrareseller.idadmin = admin_mitra_cs.idadmin 
                    WHERE orderreseller.jumlah>0 
                    And orderreseller.payment = 'Lunas' 
                    and orderreseller.tgl > '2022-05-01' 
                    and admin_mitra_cs.namacs LIKE '%$namacs%'
                    and orderreseller.status_progres = 2
                    GROUP BY orderreseller.invoice ORDER BY orderreseller.idorder DESC limit 2000 ");
                $no=1;
              
                while($tampilkanreseller=$datapo->fetch_assoc()){
                  $id = $tampilkanreseller['invoice'];
                  $invoicereseller = $tampilkanreseller['invoice'];
$datamitra=$koneksi->query("SELECT 
  SUM(surat_jalan_subdb.progres) as progresnya, 
  surat_jalan_subdb.invoice 
  FROM surat_jalan_subdb
  WHERE surat_jalan_subdb.invoice='$invoicereseller'");
  $tampilprogres=$datamitra->fetch_assoc();                                   
  $kurangreseller = $tampilkanreseller['qty']-$tampilprogres['progresnya'];                  
                ?>
<?php if ($kurangreseller<>0): ?>
                  
                              
                <tr>
                   <!-- <td>
                    <input type='checkbox' name='update_reseller_stok[]' value='<?= $id ?>' >
                    <input type='hidden' name='invoice<?= $id ?>' value='<?php echo $tampilkanreseller['invoice']; ?>' >
                  </td>  -->
                 <td>
                     <strong><?php echo $tampilkanreseller['namacs']; ?></strong>
                </td>  
                  <td>
                    <?php echo $tampilkanreseller['invoice']; ?>
                       
                      </td>
                   <td>
                   <i class="fas fa-user"></i> <?php echo $tampilkanreseller['namaagen']; ?>
                  </td>
                  
                  <td style="text-align:center"><?php echo $kurangreseller; ?></td>
                  <td>
                        <i class="fas fa-calendar" style="color: red"></i> <?php echo $tampilkanreseller['tgl']; ?>
                      </td>
              <td>
                        <div class="badge bg-warning text-white rounded-pill">
                  Stok Minus
                  </div>
                      </td>
              
                        </tr>
<?php endif ?>                          
                        <?php } ?>
                      </tbody>
                    </table>
                    
                    </div>

</form>                    
  </div>


  <div id="marketer_stok" class="tab-pane" role="tabpanel">
  <p>Marketer</p>
  <form method="post" action="ambilbarang_print.php" target="_blank">   
<div class="table-responsive">
    
    <table class="table table-bordered" id="tb_stokminus_marketer">
        <thead>
        <tr>
          <!-- <th><input type='checkbox' id='checkAll_marketer_stok' > Check</th> -->
           <th>Nama CS</th>
          <th>Invoice</th>
          <th>Nama Mitra</th>
                <th>Krg</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    
                </tr>
        </thead>
        <tbody>
              <?php 
             
                $datapo=$koneksi->query("SELECT 
                  mitramarketer.namaagen,
                  ordermarketer.tgl,
                  ordermarketer.invoice,
                  ordermarketer.payment,
                  ordermarketer.status,
                  admin_mitra_cs.namacs,
                  SUM(ordermarketer.jumlah) as qty 
                  FROM `ordermarketer` 
                  inner join mitramarketer on ordermarketer.idmitramarketer=mitramarketer.idmitramarketer 
                  JOIN admin_mitra_cs on mitramarketer.idadmin = admin_mitra_cs.idadmin 
                    WHERE ordermarketer.jumlah>0 
                    And ordermarketer.payment = 'Lunas' 
                    and ordermarketer.tgl > '2022-05-01' 
                    and admin_mitra_cs.namacs LIKE '%$namacs%'
                    and ordermarketer.status_progres = 2
                    GROUP BY ordermarketer.invoice ORDER BY ordermarketer.idorder DESC");
                $no=1;
              
                while($tampilkanmarketer=$datapo->fetch_assoc()){
                  $id = $tampilkanmarketer['invoice'];
                  $invoicemarketer = $tampilkanmarketer['invoice'];
$datamitra=$koneksi->query("SELECT 
  SUM(surat_jalan_subdb.progres) as progresnya, 
  surat_jalan_subdb.invoice 
  FROM surat_jalan_subdb
  WHERE surat_jalan_subdb.invoice='$invoicemarketer'");
  $tampilprogres=$datamitra->fetch_assoc();                                   
  $kurangmarketer = $tampilkanmarketer['qty']-$tampilprogres['progresnya'];                  
                ?>
<?php if ($kurangmarketer): ?>
                  
                                
                <tr>
                  <!-- <td>
                    <input type='checkbox' name='update_marketer_stok[]' value='<?= $id ?>' >
                    <input type='hidden' name='invoice<?= $id ?>' value='<?php echo $tampilkanmarketer['invoice']; ?>' >
                  </td>  -->                  
                <td>
                     <strong><?php echo $tampilkanmarketer['namacs']; ?></strong>
                </td>  
                  <td>
                      <?php echo $tampilkanmarketer['invoice']; ?>
                       
                      </td>
                   <td>
                   <i class="fas fa-user"></i> <?php echo $tampilkanmarketer['namaagen']; ?>
                  </td>
                  
                  <td style="text-align:center"><?php echo $kurangmarketer; ?></td>
                  <td>
                        <i class="fas fa-calendar" style="color: red"></i> <?php echo $tampilkanmarketer['tgl']; ?>
                      </td>
              <td>
                        <div class="badge bg-warning text-white rounded-pill">
                  Stok Minus
                  </div>
                      </td>
              
                        </tr>
<?php endif ?>                        
                        <?php } ?>
                      </tbody>
                    </table>
                    
                    </div>

</form>
  </div>



</div>



<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll_db').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update_db[]"]').prop('checked',true);
                    }else{
                        $('input[name="update_db[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update_db[]"]').click(function(){
                    var total_checkboxes = $('input[name="update_db[]"]').length;
                    var total_checkboxes_checked = $('input[name="update_db[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll_db').prop('checked',true);
                    }else{
                        $('#checkAll_db').prop('checked',false);
                    }
                });
            });
        </script>

<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll_agen_stok').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update_agen_stok[]"]').prop('checked',true);
                    }else{
                        $('input[name="update_agen_stok[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update_agen_stok[]"]').click(function(){
                    var total_checkboxes = $('input[name="update_agen_stok[]"]').length;
                    var total_checkboxes_checked = $('input[name="update_agen_stok[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll_agen_stok').prop('checked',true);
                    }else{
                        $('#checkAll_agen_stok').prop('checked',false);
                    }
                });
            });
</script> 

<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll_reseller_stok').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update_reseller_stok[]"]').prop('checked',true);
                    }else{
                        $('input[name="update_reseller_stok[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update_reseller_stok[]"]').click(function(){
                    var total_checkboxes = $('input[name="update_reseller_stok[]"]').length;
                    var total_checkboxes_checked = $('input[name="update_reseller_stok[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll_reseller_stok').prop('checked',true);
                    }else{
                        $('#checkAll_reseller_stok').prop('checked',false);
                    }
                });
            });
</script>   

<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll_marketer_stok').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update_marketer_stok[]"]').prop('checked',true);
                    }else{
                        $('input[name="update_marketer_stok[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update_marketer_stok[]"]').click(function(){
                    var total_checkboxes = $('input[name="update_marketer_stok[]"]').length;
                    var total_checkboxes_checked = $('input[name="update_marketer_stok[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll_marketer_stok').prop('checked',true);
                    }else{
                        $('#checkAll_marketer_stok').prop('checked',false);
                    }
                });
            });
</script>  





