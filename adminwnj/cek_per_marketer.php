<?php 
  include "koneksi.php";
  
  $idmitramarketer = $_GET['idmitramarketer'];

// echo "$idmitramarketer";

 ?>

            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Ambil Barang</a></li>
                <li><a data-toggle="tab" href="#menu2" class="nav-item nav-link">Checker</a></li>
                <!-- <li><a data-toggle="tab" href="#menu2" class="nav-item nav-link">Reseller</a></li>
                <li><a data-toggle="tab" href="#menu3" class="nav-item nav-link"> Marketer</a></li> -->
            </ul>

<div class="tab-content">
        <div id="home" class="tab-pane fade show active" id="home"  role="tabpanel">    
<div class="table-responsive">
<!--  <center><a class="btn btn-info" href="listpoinvoicemegameli.php">SUMMARY PO MEGA & MELI</a></center><br> -->
<form method="post" action="suratjalan_print_marketer.php" target="_blank">  
<table class="table table-striped" id="tb_multiprint_1">
                     <thead>
          <tr>       
           <th><input type='checkbox' id='checkAll' > Check</th>
            <th>No</th>
            <th>Invoce</th>
            <th>Nama Produk</th>
            <th>Ready</th>
            <th>Status</th>
            <th>Waktu</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            $datapo=$koneksi->query("SELECT surat_jalan_subdb.invoice,
                                            produk.namaproduk,
                                            ordermarketer.jumlah,
                                            surat_jalan_subdb.progres,
                                            surat_jalan_subdb.status,
                                            surat_jalan_subdb.waktu,
                                            surat_jalan_subdb.id_sj,
                                            surat_jalan_subdb.idorder,
                                            ordermarketer.idmitramarketer
                                            FROM surat_jalan_subdb
                                            JOIN produk on produk.idproduk = surat_jalan_subdb.idproduk
                                            JOIN ordermarketer on (ordermarketer.idorder = surat_jalan_subdb.idorder and ordermarketer.invoice = surat_jalan_subdb.invoice)
                                            WHERE ordermarketer.idmitramarketer = '$idmitramarketer' and surat_jalan_subdb.progres>0 and surat_jalan_subdb.status = 'Ambil Barang'
                                            and LEFT(ordermarketer.invoice,1) = 'M'
                                    ");
                            $no=1;
                            while($tampilkan=$datapo->fetch_assoc()){
                                $id = $tampilkan['id_sj'];
            ?>
             <tr>
                         <td><input type='checkbox' name='update[]' value='<?= $id ?>' >
                            <input type='hidden' name='invoice<?= $id ?>' value='<?php echo $tampilkan['invoice']; ?>' >
                            <input type='hidden' name='id_sj<?= $id ?>' value='<?php echo $tampilkan['id_sj']; ?>' >
                            <input type='hidden' name='idorder<?= $id ?>' value='<?php echo $tampilkan['idorder']; ?>' >
                            <input type='hidden' name='idmitramarketer' value='<?php echo $tampilkan['idmitramarketer']; ?>' >
                         </td>
                         <td>
                             <?php echo $no++; ?>
                        </td>     
                          <td>
                            <?php echo $tampilkan['invoice']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['namaproduk']; ?>
                          </td>
                            <td>
                            <?php echo $tampilkan['progres']; ?> 
                          </td>
                           <td>
                            <?php echo $tampilkan['status']; ?> 
                          </td>
                           <td>
                            <?php echo $tampilkan['waktu']; ?> 
                          </td>
                        </tr>
            <?php } ?>
          </tbody>
        </table>
        <input type='submit' class="btn btn-primary" value='Print Data' name='but_export'>
</form>        
      </div>

</div>

<div id="menu2" class="tab-pane fade">
 <div class="table-responsive">
<!--  <center><a class="btn btn-info" href="listpoinvoicemegameli.php">SUMMARY PO MEGA & MELI</a></center><br> -->
<form method="post" action="suratjalan_status_subdb.php" >  
<table class="table table-striped" id="tb_multiprint" target="_blank">
                     <thead>
          <tr>       
           <th><input type='checkbox' id='checkAll_status' > Check</th>
            <th>No</th>
            <th>No Surat Jalan</th>
            <th>Invoce</th>
            <!-- <th>Nama Produk</th> -->
            
            <th>Ready</th>
            <th>Status</th>
            <th>Waktu</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            $datapo=$koneksi->query("SELECT surat_jalan_subdb.invoice,
                                            produk.namaproduk,
                                            ordermarketer.jumlah,
                                            surat_jalan_subdb.no_sj,
                                            surat_jalan_subdb.progres,
                                            SUM(surat_jalan_subdb.progres) as jumlahnya,
                                            surat_jalan_subdb.status,
                                            surat_jalan_subdb.waktu,
                                            surat_jalan_subdb.id_sj,
                                            ordermarketer.idmitramarketer
                                            FROM surat_jalan_subdb
                                            JOIN produk on produk.idproduk = surat_jalan_subdb.idproduk
                                            JOIN ordermarketer on (ordermarketer.idorder = surat_jalan_subdb.idorder and ordermarketer.invoice = surat_jalan_subdb.invoice)
                                            WHERE ordermarketer.idmitramarketer = '$idmitramarketer' and surat_jalan_subdb.progres>0 and surat_jalan_subdb.status = 'Checker'
                                            GROUP BY surat_jalan_subdb.invoice ORDER BY surat_jalan_subdb.no_sj

                                    ");
                            $no=1;
                            while($tampilkan=$datapo->fetch_assoc()){
                                $id = $tampilkan['id_sj'];
            ?>
             <tr>
                         <td><input type='checkbox' name='update_status[]' value='<?= $id ?>' >
                            <input type='hidden' name='invoice<?= $id ?>' value='<?php echo $tampilkan['invoice']; ?>' >
                            <input type='hidden' name='id_sj<?= $id ?>' value='<?php echo $tampilkan['id_sj']; ?>' >
                            <input type='hidden' name='idorder<?= $id ?>' value='<?php echo $tampilkan['idorder']; ?>' >
                            <input type='hidden' name='idadmin' value='<?php echo $tampilkan['idmitramarketer']; ?>' >
                         </td>
                         <td>
                             <?php echo $no++; ?>
                        </td>  
                        <td>
                            <a href="suratjalan_print_subdb.php?no_sj=<?= $tampilkan['no_sj'] ?>&idadmin=<?= $tampilkan['idmitramarketer'] ?>&jenis=Marketer" target="_blank"><i class="fa fa-print"></i> <?php echo $tampilkan['no_sj']; ?></a>
                            
                          </td>   
                          <td>
                            <?php echo $tampilkan['invoice']; ?>
                          </td>
                          <!-- <td>
                            <?php echo $tampilkan['namaproduk']; ?>
                          </td> -->
                         
                          <td>
                            <?php echo $tampilkan['jumlahnya']; ?> 
                          </td>
                           <td>
                            <?php echo $tampilkan['status']; ?> 
                          </td>
                           <td>
                            <?php echo $tampilkan['waktu']; ?> 
                          </td>
                        </tr>
            <?php } ?>
          </tbody>
        </table>
        
 <input type='submit' class="btn btn-success" value='Ubah Status' name='but_update_marketer' onclick="return confirm('Yakin Akan Ubah Status?');">        
    </form>
      </div>
</div>

</div>


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script src="assets/dist/js/jquery.min.js"></script>
    <script src="assets/dist/js/bootstrap.min.js"></script>
    <script src="assets/dist/DataTables/datatables.min.js"></script>

<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_multiprint').DataTable({
        "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
  columnDefs: [
    { orderable: false, targets: 0 }
  ]
});
} );
</script>

<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_multiprint_1').DataTable({
        "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
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
                $('#checkAll_status').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update_status[]"]').prop('checked',true);
                    }else{
                        $('input[name="update_status[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update_status[]"]').click(function(){
                    var total_checkboxes = $('input[name="update_status[]"]').length;
                    var total_checkboxes_checked = $('input[name="update_status[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll_status').prop('checked',true);
                    }else{
                        $('#checkAll_status').prop('checked',false);
                    }
                });
            });
        </script>        
