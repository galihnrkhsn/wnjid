<?php 
    include "koneksi.php";
    $namacs = $_GET['namacs'];
    echo "$namacs";
 ?>
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Distributor</a></li>
    <li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Sub-DB</a></li>
    <li><a data-toggle="tab" href="#menu2" class="nav-item nav-link">Stok Minus</a></li>
    <!-- <li><a data-toggle="tab" href="#menu3" class="nav-item nav-link"> Marketer</a></li> -->
  </ul>  
  <div class="tab-content">
    <div id="home" class="tab-pane fade show active" id="home"  role="tabpanel">
      <form method="post" action="ambilbarang_print.php" target="_blank">    
        <div class="table-responsive">
          <table class="table table-bordered" id="tb_ambil_barang">
            <thead>
              <tr>
                <th><input type='checkbox' id='checkAll' > Check</th>
                <th>Nama CS</th>
                <th>Invoice</th>
                <th>Nama Mitra</th>
                <th>Krg</th>
                <th>Tanggal</th>        
              </tr>
            </thead>
            <tbody>
            <?php
                $no = 1;

                // Membuat query gabungan untuk mendapatkan semua data yang diperlukan
                $sql = "SELECT 
                        ordermitra.invoice,
                        ordermitra.tgl,
                        ordermitra.payment,
                        ordermitra.status AS status_progres,
                        ordermitra.jumlah AS qty,
                        admin_mitra.namamitra,
                        admin_mitra_cs.namacs,
                        IFNULL(SUM(surat_jalan.progres), 0) AS progresnya
                    FROM 
                        ordermitra
                    INNER JOIN 
                        admin_mitra ON ordermitra.idmitra = admin_mitra.idadmin
                    INNER JOIN 
                        admin_mitra_cs ON admin_mitra.idadmin = admin_mitra_cs.idadmin
                    LEFT JOIN 
                        surat_jalan ON ordermitra.invoice = surat_jalan.invoice
                    WHERE 
                        ordermitra.jumlah > 0 
                        AND ordermitra.payment = 'Lunas' 
                        AND ordermitra.tgl > '2022-05-01' 
                        AND ordermitra.status_progres = 0
                    GROUP BY 
                        ordermitra.invoice,
                        ordermitra.tgl,
                        ordermitra.payment,
                        ordermitra.status_progres,
                        ordermitra.jumlah,
                        admin_mitra.namamitra,
                        admin_mitra_cs.namacs
                    ORDER BY 
                        ordermitra.idorder DESC 
                    LIMIT 2000
                ";

// Eksekusi query
$result = $koneksi->query($sql);

if ($result->num_rows > 0) {
    while ($tampilkan = $result->fetch_assoc()) {
        $id = $tampilkan['invoice'];
        $kurang = $tampilkan['qty'] - $tampilkan['progresnya'];

        if ($kurang != 0) {
?>
              <tr>
                <td><input type='checkbox' name='update[]' value='<?= $id ?>' >
                  <input type='hidden' name='invoice<?= $id ?>' value='<?= $tampilkan['invoice']; ?>' >
                </td>
                <td>
                  <strong><?= $tampilkan['namacs']; ?></strong>
                </td> 
                <td>
                  <?= $tampilkan['invoice']; ?>
                </td>
                <td>
                   <i class="fas fa-user"></i> <?= $tampilkan['namamitra']; ?>
                </td>
                <td style="text-align:center"><?= $kurang; ?></td>
                <td>
                  <i class="fas fa-calendar" style="color: red"></i> <?= $tampilkan['tgl']; ?>
                </td>
              </tr>
<?php
        }
    }
} else {
    echo "Tidak ada data yang ditemukan.";
}
?>
            </tbody>
          </table>      
        </div>
        <input type='submit' class="btn btn-primary" value='Print Data' name='but_export'>  
        <input type='submit' class="btn btn-success" value='Order Selesai' name='but_selesai'>  
        <input type='submit' class="btn btn-warning" value='Stok Minus' name='but_minus'>  
      </form>                  
    </div>
    <div id="menu2" class="tab-pane"  role="tabpanel"> 
      <?php include "stok_minus.php"; ?>
    </div>             
    <div id="menu1" class="tab-pane"  role="tabpanel"> 
      <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#agen" class="nav-item nav-link active">Agen</a></li>
        <li><a data-toggle="tab" href="#reseller" class="nav-item nav-link">Reseller</a></li>
        <li><a data-toggle="tab" href="#marketer" class="nav-item nav-link">Marketer</a></li>
      </ul>
    <div class="tab-content">
      <div id="agen" class="tab-pane fade show active" role="tabpanel"> 
        <p>Agen</p>
    <form method="post" action="ambilbarang_print.php" target="_blank">       
    <div class="table-responsive">
    <table class="table table-bordered" id="tb_ambil_barang_agen">
      <thead>
        <tr>
          <th><input type='checkbox' id='checkAll_agen' > Check</th>
          <th>Nama CS</th>
          <th>Invoice</th>
          <th>Nama Mitra</th>
          <th>Krg</th>
          <th>Tanggal</th>
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
              and orderagen.status_progres = 0
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
            <td>
                    <input type='checkbox' name='update_agen[]' value='<?= $id ?>' >
                    <input type='hidden' name='invoice<?= $id ?>' value='<?php echo $tampilkanagen['invoice']; ?>' >
                  </td>                  
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
              
                        </tr>
<?php endif ?>                         
                        <?php } ?>
                      </tbody>
                    </table>
                    
                    </div>  
<input type='submit' class="btn btn-primary" value='Print Data' name='but_export_agen'>  
<input type='submit' class="btn btn-success" value='Order Selesai' name='but_selesai_agen'>   
<input type='submit' class="btn btn-warning" value='Stok Minus' name='but_minus_agen'>      
</form>                 
  </div>

  <div id="reseller" class="tab-pane" role="tabpanel">
  <p>Reseller</p>
  <form method="post" action="ambilbarang_print.php" target="_blank"> 
  <div class="table-responsive">
    
    <table class="table table-bordered" id="tb_ambil_barang_reseller">
        <thead>
        <tr>
          <th><input type='checkbox' id='checkAll_reseller' > Check</th>
            <th>Nama CS</th>
          <th>Invoice</th>
          <th>Nama Mitra</th>
                <th>Krg</th>
                    <th>Tanggal</th>
                    
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
                    and orderreseller.status_progres = 0
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
                   <td>
                    <input type='checkbox' name='update_reseller[]' value='<?= $id ?>' >
                    <input type='hidden' name='invoice<?= $id ?>' value='<?php echo $tampilkanreseller['invoice']; ?>' >
                  </td> 
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
              
                        </tr>
<?php endif ?>                          
                        <?php } ?>
                      </tbody>
                    </table>
                    
                    </div>
<input type='submit' class="btn btn-primary" value='Print Data' name='but_export_reseller'>  
<input type='submit' class="btn btn-success" value='Order Selesai' name='but_selesai_reseller'> 
<input type='submit' class="btn btn-warning" value='Stok Minus' name='but_minus_reseller'>   
</form>                    
  </div>


  <div id="marketer" class="tab-pane" role="tabpanel">
  <p>Marketer</p>
  <form method="post" action="ambilbarang_print.php" target="_blank">   
<div class="table-responsive">
    
    <table class="table table-bordered" id="tb_ambil_barang_marketer">
        <thead>
        <tr>
          <th><input type='checkbox' id='checkAll_marketer' > Check</th>
           <th>Nama CS</th>
          <th>Invoice</th>
          <th>Nama Mitra</th>
                <th>Krg</th>
                    <th>Tanggal</th>
                    
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
                    and ordermarketer.status_progres = 0
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
                  <td>
                    <input type='checkbox' name='update_marketer[]' value='<?= $id ?>' >
                    <input type='hidden' name='invoice<?= $id ?>' value='<?php echo $tampilkanmarketer['invoice']; ?>' >
                  </td>                   
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
              
                        </tr>
<?php endif ?>                        
                        <?php } ?>
                      </tbody>
                    </table>
                    
                    </div>
<input type='submit' class="btn btn-primary" value='Print Data' name='but_export_marketer'>  
<input type='submit' class="btn btn-success" value='Order Selesai' name='but_selesai_marketer'> 
<input type='submit' class="btn btn-warning" value='Stok Minus' name='but_minus_marketer'>   
</form>
  </div>



</div>      


<?php include "settingdatatables.php"; ?>
<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_ambil_barang').DataTable({
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
         "pageLength": 25,
         order: [[4, 'desc']],
           columnDefs: [
    { orderable: false, targets: 0 }
  ]
});
} );
</script>

<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_ambil_barang_agen').DataTable({
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
         "pageLength": 25,
         order: [[4, 'desc']],
           columnDefs: [
    { orderable: false, targets: 0 }
  ]
});
} );
</script>

<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_ambil_barang_reseller').DataTable({
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
         "pageLength": 25,
         order: [[4, 'desc']],
           columnDefs: [
    { orderable: false, targets: 0 }
  ]
});
} );
</script>

<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_ambil_barang_marketer').DataTable({
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
         "pageLength": 25,
         order: [[4, 'desc']],
           columnDefs: [
    { orderable: false, targets: 0 }
  ]
});
} );
</script>


<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_stokminus_db').DataTable({
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
         "pageLength": 25,
         order: [[4, 'desc']],
           columnDefs: [
    { orderable: false, targets: 0 }
  ]
});
} );
</script>


<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_stokminus_agen').DataTable({
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
         "pageLength": 25,
         order: [[4, 'desc']],
           columnDefs: [
    { orderable: false, targets: 0 }
  ]
});
} );
</script>

<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_stokminus_reseller').DataTable({
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
         "pageLength": 25,
         order: [[4, 'desc']],
           columnDefs: [
    { orderable: false, targets: 0 }
  ]
});
} );
</script>

<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_stokminus_marketer').DataTable({
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
         "pageLength": 25,
         order: [[4, 'desc']],
           columnDefs: [
    { orderable: false, targets: 0 }
  ]
});
} );
</script>

<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update[]"]').prop('checked',true);
                    }else{
                        $('input[name="update[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update[]"]').click(function(){
                    var total_checkboxes = $('input[name="update[]"]').length;
                    var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll').prop('checked',true);
                    }else{
                        $('#checkAll').prop('checked',false);
                    }
                });
            });
        </script>

<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll_agen').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update_agen[]"]').prop('checked',true);
                    }else{
                        $('input[name="update_agen[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update_agen[]"]').click(function(){
                    var total_checkboxes = $('input[name="update_agen[]"]').length;
                    var total_checkboxes_checked = $('input[name="update_agen[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll_agen').prop('checked',true);
                    }else{
                        $('#checkAll_agen').prop('checked',false);
                    }
                });
            });
</script> 

<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll_reseller').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update_reseller[]"]').prop('checked',true);
                    }else{
                        $('input[name="update_reseller[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update_reseller[]"]').click(function(){
                    var total_checkboxes = $('input[name="update_reseller[]"]').length;
                    var total_checkboxes_checked = $('input[name="update_reseller[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll_reseller').prop('checked',true);
                    }else{
                        $('#checkAll_reseller').prop('checked',false);
                    }
                });
            });
</script>   

<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll_marketer').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update_marketer[]"]').prop('checked',true);
                    }else{
                        $('input[name="update_marketer[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update_marketer[]"]').click(function(){
                    var total_checkboxes = $('input[name="update_marketer[]"]').length;
                    var total_checkboxes_checked = $('input[name="update_marketer[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll_marketer').prop('checked',true);
                    }else{
                        $('#checkAll_marketer').prop('checked',false);
                    }
                });
            });
</script>        


